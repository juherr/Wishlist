<?php

declare(strict_types=1);

use Wishlist\Gifts\GiftRepository;
use Wishlist\Users\UserRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';

$user_id = (int)$_POST['user-id'];

$repository = new UserRepository($bdd);
$repository->delete($user_id);

// TODO remove, should be done by cascading
$repository = new GiftRepository($bdd);
$repository->deleteAll($user_id);

//L'ajax
$reponse = 'success';
echo json_encode([
    'reponse' => $reponse,
], JSON_THROW_ON_ERROR);
