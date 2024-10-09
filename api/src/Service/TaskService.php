<?php

namespace App\Service;

use App\Entity\Task;
use App\RequestValidator\Task\TaskRequestValidator;
use App\Service\ObjectSerializerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskService
{
    public function __construct(protected EntityManagerInterface $em, protected TaskRequestValidator $taskRequestValidator)
    {
    }

    public function create(array $reqData, UserInterface $user): array
    {
        $this->taskRequestValidator->fill($reqData);
        $this->taskRequestValidator->validate();
        $dueTime = new DateTime($this->taskRequestValidator->dueDate);

        $task = new Task();
        $task->setTitle($this->taskRequestValidator->title);
        $task->setDescription($this->taskRequestValidator->description);
        $task->setDueDate($dueTime);
        $task->setUserId($user);

        if (isset($this->taskRequestValidator->userId) && in_array('ROLE_ADMIN', $user->getRoles())) {
            $userById = $this->em->getRepository(Task::class)->find($this->taskRequestValidator->userId);

            if (!$userById) {
                throw new Exception(json_encode(['message' => 'User not found'], Response::HTTP_NOT_FOUND));
            }

            $task->setUserId($userById);

        }

        $this->em->persist($task);
        $this->em->flush();

        return ObjectSerializerService::normalize($task);
    }

    public function getAll(UserInterface $user): array
    {
        $taskRepository = $this->em->getRepository(Task::class);

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return ObjectSerializerService::normalize($taskRepository->findAll());
        }

        return ObjectSerializerService::normalize($taskRepository->findBy(['userId' => $user]));
    }

    public function get(int $id, UserInterface $user): array
    {
        return ObjectSerializerService::normalize($this->loadEntityById($id, $user));
    }

    public function loadEntityById(int $id, UserInterface $user): Task
    {
        $task = null;

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $task = $this->em->getRepository(Task::class)->find($id);
        }

        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            $task = $this->em->getRepository(Task::class)->findOneBy(['id' => $id, 'userId' => $user]);
        }

        if (!$task) {
            throw new Exception(Response::$statusTexts[Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        return $task;
    }

    public function update(int $id, UserInterface $user, array $reqData): array
    {
        $task = $this->loadEntityById($id, $user);

        $this->taskRequestValidator->fill($reqData);
        $this->taskRequestValidator->validate();

        $task->setTitle($this->taskRequestValidator->title);
        $task->setDescription($this->taskRequestValidator->description);
        $task->setDueDate(new DateTime($this->taskRequestValidator->dueDate));

        $this->em->persist($task);
        $this->em->flush();

        return ObjectSerializerService::normalize($task);
    }

    public function delete($id, $user)
    {
        $task = $this->loadEntityById($id, $user);

        $this->em->remove($task);
        $this->em->flush();

        return true;
    }
}
