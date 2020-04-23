<?php

declare(strict_types=1);

use Wishlist\Users\UserRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';
$bdd->query('SET NAMES "utf8"');

$id_personne = $_POST['id_personne'];
$username = $_POST['username'];
$choix_illu = $_POST['choix-illu' . $id_personne];

if (isset($username) && ($username !== '')) {
    $repository = new UserRepository($bdd);
    $user = $repository->findById((int)$id_personne);
    if ($user === null) {
        return;
    }
    $user->setName($username);
    $user->setIconId((int)$choix_illu);
    $repository->update($user);

    //L'ajax
    $reponse = 'success';
    echo json_encode([
        'reponse' => $reponse,
        'username' => $username,
    ], JSON_THROW_ON_ERROR);
}
