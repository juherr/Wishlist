<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\GiftRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, UserRepository $userRepository, GiftRepository $giftRepository): Response
    {
        $userId = $request->getSession()->get('user');
        if (empty($userId)) {
            return $this->redirectToRoute('login');
        }

        $users = $userRepository->findAll();
        $gifts = [];
        foreach ($users as $user) {
            $gifts[$user->getId()] = $giftRepository->findByUserId($user->getId());
            $users[$user->getId()] = $user;
        }
        return $this->render('index.html.twig', [
            'users' => $users,
            'gifts' => $gifts,
            'loggedUserId' => $userId,
        ]);
    }

    /**
     * @Route("/login.php", name="login")
     */
    public function login(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('login.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user-connection.php")
     */
    public function connect(Request $request): Response
    {
        $userId = $request->request->getInt('id_personne');
        if ($userId <= 0) {
            return $this->redirectToRoute('login');
        }
        $request->getSession()->set('user', $userId);
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/logout.php")
     */
    public function logout(Request $request): Response
    {
        $request->getSession()->clear();
        return $this->redirectToRoute('login');
    }
}
