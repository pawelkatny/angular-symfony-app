<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\RequestValidator\User\UserRequestValidator;
use App\Service\ObjectSerializerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(protected EntityManagerInterface $em, protected UserRequestValidator $userRequestValidator, protected UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function create(array $reqData): array
    {
        $this->userRequestValidator->fill($reqData);
        $this->userRequestValidator->validate();

        if ($this->loadEntityByEmail($this->userRequestValidator->email)) {
            $errorData = [
                'message' => Response::$statusTexts[Response::HTTP_CONFLICT],
                'errors' => [
                    ['message' => 'User with that email already exists']
                ]
            ];
            throw new \Exception(json_encode($errorData), Response::HTTP_CONFLICT);
        }

        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $this->userRequestValidator->password
        );

        $user->setEmail($this->userRequestValidator->email);
        $user->setPassword($hashedPassword);
        $user->setRoles($this->userRequestValidator->roles);

        $this->em->persist($user);
        $this->em->flush();

        return ObjectSerializerService::normalize($user);
    }

    protected function loadEntityById(int $id): null|User
    {
        $user = $this->em->getRepository(User::class)->find($id);

        if (!$user) {
            throw new \Exception(Response::$statusTexts[Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    public function loadEntityByEmail(string $email): null|User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function remove(int $id): bool
    {
        $user = $this->loadEntityById($id);

        $this->em->remove($user);
        $this->em->flush();

        return true;
    }

    public function loadNormalized(int $id): array
    {
        $user = $this->loadEntityById($id);

        return ObjectSerializerService::normalize($user);
    }

}
