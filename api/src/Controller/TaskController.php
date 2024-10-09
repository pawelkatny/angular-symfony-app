<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\TaskService;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends AbstractController
{
    public function __construct(private TaskService $taskService)
    {
    }

    #[Route('/task', name: 'task_create', methods: ['POST'])]
    public function create(Request $req): Response
    {
        $reqData = $req->getPayload()->all();

        $task = $this->taskService->create($reqData, $this->getUser());

        $res = [
            'data' => $task,
            'message' => Response::$statusTexts[Response::HTTP_CREATED]
        ];

        return new JsonResponse($res, Response::HTTP_CREATED);
    }

    #[Route('/task', name: 'task_get_all', methods: ['GET'])]
    public function readAll(): Response
    {
        $user = $this->getUser();

        $tasks = $this->taskService->getAll($user);

        $res = [
           'data' => $tasks,
           'message' => Response::$statusTexts[Response::HTTP_OK]
       ];

        return new JsonResponse($res, Response::HTTP_OK);
    }

    #[Route('/task/{id}', name: 'task_get_one', methods: ['GET'])]
    public function getTask($id): Response
    {
        $user = $this->getUser();
        $task = $this->taskService->get($id, $user);

        $res = [
           'data' => $task,
           'message' => Response::$statusTexts[Response::HTTP_OK]
       ];

        return new JsonResponse($res, Response::HTTP_OK);
    }

    #[Route('/task/{id}', name: 'task_update_one', methods: ['PATCH'])]
    public function update(Request $req, int $id): Response
    {
        $reqData = $req->getPayload()->all();
        $user = $this->getUser();

        $task = $this->taskService->update($id, $user, $reqData);

        $res = [
           'data' => $task,
           'message' => Response::$statusTexts[Response::HTTP_OK]
        ];

        return new JsonResponse($res, Response::HTTP_OK);
    }

    #[Route('/task/{id}', name: 'task_delete_one', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $user = $this->getUser();

        $this->taskService->delete($id, $user);

        $res = [
            'data' => null,
            'message' => Response::$statusTexts[Response::HTTP_NO_CONTENT]
        ];

        return new JsonResponse($res, Response::HTTP_NO_CONTENT);
    }
}
