<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

use App\Response\ApiResponse;
use Exception;


#[Route('/users')]
class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/list', name: 'user_list', methods: ["GET"])]
    public function list(Request $request): ApiResponse
    {
        try
        {
            $page = $request->query->getInt('page', 1);
            $limit = $request->query->getInt('limit', 10);

            $response = $this->userService->getAllUsers($page, $limit);
            return ApiResponse::success($response);
        }
        catch (Exception $exception)
        {
            return ApiResponse::failure(["error" => $exception->getMessage()]);
        }
    }

    #[Route('/', name: 'user_create', methods: ["POST"])]
    public function create(Request $request): ApiResponse
    {
        try
        {
            $data = json_decode($request->getContent(), true);
            $user = (new User())->deserialize($data);

            $response = $this->userService->createUser($user);
            return ApiResponse::success($response);
        }
        catch (Exception $exception)
        {
            return ApiResponse::failure(["error" => $exception->getMessage()]);
        }
    }

    #[Route('/{id}', name: 'user_get', methods: ["GET"], requirements: ["id" => "\d+"])]
    public function get(int $id): ApiResponse
    {
        try
        {
            $response = $this->userService->getUserById($id);
            return ApiResponse::success($response);
        }
        catch (Exception $exception)
        {
            return ApiResponse::failure(["error" => $exception->getMessage()]);
        }
    }

    #[Route('/{id}', name: 'user_delete', methods: ["DELETE"], requirements: ["id" => "\d+"])]
    public function delete(int $id): ApiResponse
    {
        try
        {
            $response = $this->userService->deleteUser($id);
            return ApiResponse::success($response);
        }
        catch (Exception $exception)
        {
            return ApiResponse::failure(["error" => $exception->getMessage()]);
        }
    }

    #[Route('/{id}', name: 'user_update', methods: ["PUT"], requirements: ["id" => "\d+"])]
    public function update(int $id, Request $request): ApiResponse
    {
        try
        {
            $data = json_decode($request->getContent(), true);
            $response = $this->userService->updateUser($id, $data);

            return ApiResponse::success($response);
        }
        catch (Exception $exception)
        {
            return ApiResponse::failure(["error" => $exception->getMessage()]);
        }
    }
}
