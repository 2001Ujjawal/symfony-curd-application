<?php

namespace App\Controller;

use App\Helpers\CatchErrorHandle;
use App\Repository\UserRepository;
use App\Service\FileUploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    // private FileUploadService $fileUploadService;

    public function __construct()
    {
        // $this->fileUploadService = new FileUploadService();
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
