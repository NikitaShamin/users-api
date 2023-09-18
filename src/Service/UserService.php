<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private $userRepository;

    private $validator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function createUser(User $user) : mixed
    {
        $response = [];
        $errors = $this->validator->validate($user);

        if ($errors->count() > 0) 
        {
            $errorMessages = [];
            foreach ($errors as $error) 
            {
                $errorMessages[] = $error->getPropertyPath() . ": " . $error->getMessage();
            }

            $response = ['success' => false, 'errors' => $errorMessages];
        }
        else
        {
            $this->userRepository->save($user);
            $response = ['success' => true, "instance" => $user->jsonSerialize()];
        }

        return $response;
    }

    public function updateUser(int $id, mixed $data) : mixed
    {
        $response = [];

        $user = $this->userRepository->find($id);
        if ($user)
        {
            $user->deserialize($data);
            $user->setUpdatedAt(new \DateTimeImmutable());

            $errors = $this->validator->validate($user);

            if ($errors->count() > 0) 
            {
                $errorMessages = [];
                foreach ($errors as $error) 
                {
                    $errorMessages[] = $error->getPropertyPath() . ": " . $error->getMessage();
                }

                $response = ['success' => false, 'errors' => $errorMessages];
            }
            else
            {
                $this->userRepository->save($user);
                $response = ['success' => true, "instance" => $user->jsonSerialize()];
            }
        }
        else
        {
            $response = ['success' => false, 'errors' => ["User not found by ID"]];
        }

        return $response;
    }

    public function deleteUser(int $id)
    {
        $response = [];

        $user = $this->userRepository->find($id);
        if ($user)
        {
            $this->userRepository->delete($user);
            $response = ['success' => true, 'user_id' => $id];
        }
        else
        {
            $response = ['success' => false, 'errors' => ["User not found by ID"]];
        }

        return $response;
    }

    public function getUserById(int $id): mixed
    {
        $user = $this->userRepository->find($id);
        return ['success' => true, 'instance' => $user];
    }

    public function getAllUsers(int $page, int $limit): array
    {
        $paginator = $this->userRepository->findAllPaginated($page, $limit);
        $users = iterator_to_array($paginator);

        return ['success' => true, 'users' => $users];
    }
}
