<?php

declare(strict_types=1);

use Wishlist\Config;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';
$bdd->query('SET NAMES "utf8"');

$gift_id = $_POST['gift-id'];

$statement = $bdd->prepare('DELETE FROM ' . Config::getGiftTableName() . ' WHERE id = :id');

$statement->bindParam(':id', $gift_id, PDO::PARAM_STR);
$statement->execute();

// Ajax

$reponse = 'success';
echo json_encode([
    'reponse' => $reponse,
], JSON_THROW_ON_ERROR);
