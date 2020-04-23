<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Wishlist\Users\UserRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';
$repository = new UserRepository($bdd);

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    //'cache' => '/path/to/compilation_cache',
    'debug' => true,
]);
$template = $twig->load('login.html.twig');

echo $template->render([
    'users' => $repository->findAll(),
]);
