<?php declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Gifts\GiftRepository;
use App\Users\UserRepository;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
}

require_once __DIR__ . '/../src/inc/bdd.php';
$userRepository = new UserRepository($bdd);
$giftRepository = new GiftRepository($bdd);

$users = $userRepository->findAll();
$gifts = [];
foreach ($users as $user) {
    $gifts[$user->getId()] = $giftRepository->findByUserId($user->getId());
    $users[$user->getId()] = $user;
}

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    //'cache' => '/path/to/compilation_cache',
    'debug' => true,
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$template = $twig->load('index.html.twig');

echo $template->render([
    'users' => $users,
    'gifts' => $gifts,
    'loggedUserId' => $_SESSION['user'],
]);
