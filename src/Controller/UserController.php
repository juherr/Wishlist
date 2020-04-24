<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("user-connection.php")
     */
    public function connect(Request $request): Response
    {
        $userId = $request->request->get('id_personne');
        if (empty($userId)) {
            return $this->redirectToRoute('login');
        }
        $request->getSession()->set('user', $userId);
        return $this->redirectToRoute('index');
    }
}
