<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Helpers\CatchErrorHandle;
use App\Repository\UserRepository;
use App\Service\FileUploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api')]
class UserController extends AbstractController
{
    // private FileUploadService $fileUploadService;

    public function __construct()
    {
        // $this->fileUploadService = new FileUploadService();
    }


    #[Route('/users', name: 'add_user', methods: ['post'])]
    public function createUser(
        Request $request,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        UserRepository $userRepository
    ): JsonResponse {

        try {
            $dto = $serializer->deserialize(
                $request->getContent(),
                UserDTO::class,
                'json',
                // [
                //     'disable_type_enforcement' => true
                // ]
            );

            $errors = $validator->validate($dto);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $field = $error->getPropertyPath();
                    if (!isset($errorMessages[$field])) {
                        $errorMessages[$field] = $error->getMessage();
                    }
                }
                return $this->json([
                    'status' => false,
                    'message' => 'Validation Failed',
                    'errors' => $errorMessages
                ], 400);
            }
            // $userData = [
            //     'name' => $dto->name,
            //     'email' => $dto->email,
            //     'password' => $dto->password,
            //     'image' => $dto->image,
            //     'phone_no' => $dto->phone_no
            // ];

            if ($userRepository->createFromDTO($dto)) {
                return $this->json([
                    'status' => true,
                    'message' => 'User created successfully'
                ]);
            }

            return $this->json([
                'status' => false,
                'message' => 'User creation failed'
            ]);
        } catch (NotNormalizableValueException $e) {

            $path = $e->getPath();

            return $this->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => [
                    $path => sprintf(
                        '%s must be of type %s',
                        $path,
                        implode(', ', $e->getExpectedTypes())
                    )
                ]
            ], 400);
        } catch (\Exception $e) {
            return CatchErrorHandle::response($e);
        }
    }




    #[Route('/users', name: 'users_list', methods: ['GET'])]
    public function usersList(UserRepository $userRepository): JsonResponse
    {
        try {
            $users = $userRepository->findAll();

            if (!$users) {
                return $this->json(['users' => []]);
            }
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'name' => $user->getName()
                ];
            }
            return $this->json(['users' => $data]);
        } catch (\Exception $e) {
            return  CatchErrorHandle::response($e);
        }
    }

    #[Route('/images/uploads', name: 'image_upload', methods: ['POST'])]
    public function imageUploads(Request $request, FileUploadService $fileUploadService)
    {
        $image = $request->files->get('image');

        if (!$image) {
            return new JsonResponse(['error' => 'No file uploaded']);
        }

        try {
            $fileName = $fileUploadService->upload($image, 'users/images');
            return new JsonResponse([
                'file' => $fileName,
                'path' => '/uploads/users/images/' . $fileName
            ]);
        } catch (\Exception $e) {
            return  CatchErrorHandle::response($e);
        }
    }
}
