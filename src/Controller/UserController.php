<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * List of users.
     */
    #[Route('/users/list', name: 'user_list', methods: ["GET"])]
    public function list(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $response = $this->userService->getAllUsers($page, $limit);
        return $this->json($response);
    }

    #[Route('/users', name: 'user_create', methods: ["POST"])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = (new User())->deserialize($data);

        $response = $this->userService->createUser($user);

        return $this->json($response);
    }
    
    #[Route('/users/{id}', name: 'user_get', methods: ["GET"])]
    public function get(int $id): JsonResponse
    {
        $response = $this->userService->getUserById($id);
        return $this->json($response);
    }

    #[Route('/users/{id}', name: 'user_delete', methods: ["DELETE"])]
    public function delete(int $id): JsonResponse
    {
        $response = $this->userService->deleteUser($id);
        return $this->json($response);
    }

    #[Route('/users/{id}', name: 'user_update', methods: ["PUT"])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $response = $this->userService->updateUser($id, $data);

        return $this->json($response);
    }

}
