<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private UserRepository $userRepository;
    private ValidatorInterface $validator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function createUser(User $user): array
    {
        $errors = $this->validateUser($user);

        if (count($errors) > 0) {
            return ['errors' => $errors];
        }

        $this->userRepository->save($user);

        return ['instance' => $user->jsonSerialize()];
    }

    public function updateUser(int $id, mixed $data): array
    {
        $user = $this->userRepository->find($id);

        $user->deserialize($data);
        $user->setUpdatedAt(new \DateTimeImmutable());

        $errors = $this->validateUser($user);

        if (count($errors) > 0) {
            return ['errors' => $errors];
        }

        $this->userRepository->save($user);

        return ['instance' => $user->jsonSerialize()];
    }

    public function deleteUser(int $id): array
    {
        $user = $this->userRepository->find($id);
        $this->userRepository->delete($user);

        return ['user_id' => $id];
    }

    public function getUserById(int $id): array
    {
        $user = $this->userRepository->find($id);

        if (!$user) 
        {
            throw new NotFoundHttpException('User not found by ID');
        }

        return ["instance" => $user];
    }

    public function getAllUsers(int $page, int $limit): array
    {
        $paginator = $this->userRepository->findAllPaginated($page, $limit);
        $users = iterator_to_array($paginator);

        return ['users' => $users];
    }

    private function validateUser(User $user): array
    {
        $errors = $this->validator->validate($user);
        $errorMessages = [];

        foreach ($errors as $error) 
        {
            $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
        }

        return $errorMessages;
    }
}
