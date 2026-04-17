<?php

namespace App\Controller;

use App\Helpers\CatchErrorHandle;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UserController extends AbstractController
{
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

    

    
}
