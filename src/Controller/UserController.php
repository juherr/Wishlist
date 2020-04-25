<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends BaseController
{
    private UserRepository $repository;
    private ValidatorInterface $validator;

    public function __construct(UserRepository $repository, ValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @Route("/add-user.php", name="user_add")
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
     * @Route("/edit-user.php", name="user_update")
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
     * @Route("/delete-user.php", name="user_delete")
     */
    public function delete(Request $request): JsonResponse
    {
        $userId = $request->request->getInt('user-id');
        $user = $this->repository->findById($userId);
        if ($user === null) {
            return $this->failed('User not found', 404);
        }

        $this->repository->delete($user);

        return $this->success();
    }

    /**
     * @Route("/users/", name="user_list")
     */
    public function list(Request $request): Response
    {
        $users = $this->repository->findAll();

        $currentUserId = $request->getSession()->get('user');
        if ($currentUserId === null) {
            return $this->render('login.html.twig', [
                'users' => $users,
            ]);
        }
        $currentUser = $this->repository->findById($currentUserId);

        return $this->render('index.html.twig', [
            'users' => $users,
            'loggedUser' => $currentUser,
        ]);
    }
}
