<?php

declare(strict_types=1);

use App\Gifts\GiftRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';
$bdd->query('SET NAMES "utf8"');

$gift_id = $_POST['gift-id'];

$repository = new GiftRepository($bdd);
$repository->delete($gift_id);

// Ajax

$reponse = 'success';
echo json_encode([
    'reponse' => $reponse,
], JSON_THROW_ON_ERROR);
