<?php

declare(strict_types=1);

namespace App\Controller;

use App\Gifts\GiftRepository;
use App\Users\User;
use App\Users\UserRepository;
use App\Users\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/add-user.php")
     */
    public function add(Request $request): Response
    {
        $username = $request->request->get('username');
        $iconId = $request->request->getInt('choix-illu');
        if (!isset($username) || $username === '') {
            return Response::create('Invalid username', 400);
        }

        $bdd = require __DIR__ . '/../inc/bdd.php';
        $repository = new UserRepository($bdd);
        $user = new User($username, $iconId);
        $repository->create($user);
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/edit-user.php")
     */
    public function update(Request $request): JsonResponse
    {
        $userId = $request->request->getInt('id_personne');
        $username = $request->request->get('username');
        $iconId = $request->request->getInt('choix-illu' . $userId);

        if (empty($username)) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'invalid username',
            ], 400);
        }

        $bdd = require __DIR__ . '/../inc/bdd.php';
        $repository = new UserRepository($bdd);
        $user = $repository->findById((int)$userId);
        if ($user === null) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'user not found',
            ], 404);
        }

        $user->setName($username);
        $user->setIconId((int)$iconId);
        $repository->update($user);

        return $this->json([
            'reponse' => 'success',
            'username' => $username,
        ]);
    }

    /**
     * @Route("/delete-user.php")
     */
    public function delete(Request $request): JsonResponse
    {
        $userId = $request->request->getInt('user-id');

        $bdd = require __DIR__ . '/../inc/bdd.php';
        $service = new UserService(new UserRepository($bdd), new GiftRepository($bdd));
        $service->delete($userId);

        return $this->json([
            'reponse' => 'success',
        ]);
    }
}
