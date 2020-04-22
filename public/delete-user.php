<?php

declare(strict_types=1);

use Wishlist\Config;
use Wishlist\Gifts\GiftRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';

$user_id = $_POST['user-id'];

$statement = $bdd->prepare('DELETE FROM ' . Config::getUserTableName() . ' WHERE id_personne = :user_id');
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();

// TODO remove, should be done by cascading
$repository = new GiftRepository($bdd);
$repository->deleteAll($user_id);

    //L'ajax

    $reponse = 'success';
    echo json_encode([
        'reponse' => $reponse,
    ], JSON_THROW_ON_ERROR);
