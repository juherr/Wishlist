<?php

declare(strict_types=1);

use Wishlist\Config;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';
$bdd->query('SET NAMES "utf8"');

$gift_title = $_POST['gift-name'];
$gift_url = $_POST['gift-url'];
$gift_description = $_POST['gift-description'];
$gift_id = $_POST['gift-id'];


if (isset($gift_title) && ($gift_title !== '')) {
    $statement = $bdd->prepare(
        'UPDATE ' . Config::getGiftTableName() . ' SET titre = :title, lien = :lien, description = :description WHERE id = :id'
    );

    $statement->bindParam(':title', $gift_title, PDO::PARAM_STR);
    $statement->bindParam(':lien', $gift_url, PDO::PARAM_STR);
    $statement->bindParam(':description', $gift_description, PDO::PARAM_STR);
    $statement->bindParam(':id', $gift_id, PDO::PARAM_INT);


    $statement->execute();

    //L'ajax

    $reponse = 'success';
    echo json_encode([
        'reponse' => $reponse,
        'gift_title' => $gift_title,
        'gift_url' => $gift_url,
        'gift_description' => $gift_description,
    ], JSON_THROW_ON_ERROR);
}
