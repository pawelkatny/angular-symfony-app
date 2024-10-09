<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\RequestValidator\User\UserRequestValidator;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    public function __construct(private UserRequestValidator $userValidator, private UserService $userService)
    {
    }

    #[Route('/user', name: 'user_create', methods: ['POST'])]
    public function create(Request $req): Response
    {
        $reqData = $req->getPayload()->all();
        $user = $this->userService->create($reqData);


        $res = [
            'data' => $user,
            'message' => Response::$statusTexts[Response::HTTP_CREATED]
        ];

        return new JsonResponse($res, Response::HTTP_CREATED);
    }

    #[Route('/user/{id}', name: 'user_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $user = $this->userService->loadNormalized($id);

        $res = [
            'data' => $user,
            'message' => Response::$statusTexts[Response::HTTP_OK]
        ];

        return new JsonResponse($res, Response::HTTP_OK);
    }

    #[Route('/user/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $this->userService->remove($id);

        $res = [
            'data' => null,
            'message' => Response::$statusTexts[Response::HTTP_NO_CONTENT]
        ];
        return new JsonResponse($res, Response::HTTP_NO_CONTENT);
    }
}
