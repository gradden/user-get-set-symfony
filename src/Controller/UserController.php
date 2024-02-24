<?php

namespace App\Controller;

use App\Exception\UserCreateException;
use App\Exception\UserNotFoundException;
use App\Request\UserCreateRequest;
use App\Response\UserResponse;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request, UserResponse $response): Response
    {
        return $response->createResponse(
            user: $this->userService->index(),
            responseType: $request->headers->get('Accept')
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function show(int $id, Request $request, UserResponse $response): Response
    {
        return $response->createResponse(
            user: $this->userService->show($id),
            responseType: $request->headers->get('Accept')
        );
    }

    /**
     * @throws UserCreateException
     */
    public function store(UserCreateRequest $request, UserResponse $response): Response
    {
        $request = $request->getRequest();
        $requestData = $request->toArray();

        $user = $this->userService->create(
            $requestData['firstName'],
            $requestData['lastName'],
            $requestData['email'],
            $requestData['password']
        );

        return $response->createResponse(
            user: $user,
            responseType: $request->headers->get('Accept')
        );
    }
}
