<?php

declare(strict_types=1);

namespace App\Controller;

use App\Gifts\GiftRepository;
use App\Users\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $userId = $request->getSession()->get('user');
        if (empty($userId)) {
            return $this->redirectToRoute('login');
        }

        $bdd = require __DIR__ . '/../inc/bdd.php';
        $userRepository = new UserRepository($bdd);
        $giftRepository = new GiftRepository($bdd);

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
    public function login(): Response
    {
        $bdd = require __DIR__ . '/../inc/bdd.php';
        $repository = new UserRepository($bdd);

        return $this->render('login.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }
}
