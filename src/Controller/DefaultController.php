<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gift;
use App\Entity\User;
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
    public function index(Request $request): Response
    {
        $userId = $request->getSession()->get('user');
        if (empty($userId)) {
            return $this->redirectToRoute('login');
        }

        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        /** @var GiftRepository $giftRepository */
        $giftRepository = $this->getDoctrine()->getRepository(Gift::class);

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
        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository(User::class);
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
    public function logout(Request $request)
    {
        $request->getSession()->clear();
        return $this->redirectToRoute('login');
    }
}
