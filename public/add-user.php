<?php

declare(strict_types=1);

use Wishlist\Users\User;
use Wishlist\Users\UserRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';

$username = $_POST['username'];
if (isset($username) && ($username !== '')) {
    $repository = new UserRepository($bdd);

    $choix_illu = $_POST['choix-illu'];
    $user = new User($username, (int)$choix_illu);

    $repository->create($user);

    header('location:login.php');
}
