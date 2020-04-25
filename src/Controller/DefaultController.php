<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/user-connection.php", name="login")
     */
    public function login(Request $request): Response
    {
        $userId = $request->request->getInt('id_personne');
        if ($userId > 0) {
            $user = $this->userRepository->findById($userId);
            if ($user === null) {
                return Response::create('Invalid user', 403);
            }
            $request->getSession()->set('user', $user->getId());
        }
        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/logout.php", name="logout")
     */
    public function logout(Request $request): Response
    {
        $request->getSession()->clear();
        return $this->redirectToRoute('user_list');
    }
}
