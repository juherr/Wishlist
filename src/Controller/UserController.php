<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends BaseController
{
    private UserRepository $repository;
    private UserService $service;
    private ValidatorInterface $validator;

    public function __construct(UserRepository $repository, UserService $service, ValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->validator = $validator;
    }

    /**
     * @Route("/add-user.php")
     */
    public function add(Request $request): Response
    {
        $username = $request->request->get('username');
        $iconId = $request->request->getInt('choix-illu');
        $user = new User($username, $iconId);

        if (empty($username)) {
            return Response::create('Invalid username', 400);
        }
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return $this->failed((string) $errors, 400);
        }

        $this->repository->create($user);
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/edit-user.php")
     */
    public function update(Request $request): JsonResponse
    {
        $userId = $request->request->getInt('id_personne');
        $user = $this->repository->findById($userId);
        if ($user === null) {
            return $this->failed('User not found', 404);
        }

        $username = $request->request->get('username');
        $user->setName($username);
        $iconId = $request->request->getInt('choix-illu' . $userId);
        $user->setIconId((int)$iconId);

        if (empty($username)) {
            return $this->failed( 'Invalid username', 400);
        }
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return $this->failed((string) $errors, 400);
        }

        $this->repository->update($user);

        return $this->success([
            'username' => $username,
        ]);
    }

    /**
     * @Route("/delete-user.php")
     */
    public function delete(Request $request): JsonResponse
    {
        $userId = $request->request->getInt('user-id');

        $this->service->delete($userId);

        return $this->success();
    }
}
