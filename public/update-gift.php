<?php

declare(strict_types=1);

use Wishlist\Gifts\GiftRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';

$gift_title = $_POST['gift-name'];
if (isset($gift_title) && ($gift_title !== '')) {
    $gift_id = $_POST['gift-id'];
    $repository = new GiftRepository($bdd);
    $gift = $repository->findById($gift_id);
    if ($gift === null) {
        return;
    }

    $gift->setTitle($gift_title);

    $gift_url = $_POST['gift-url'];
    $gift->setLink($gift_url);

    $gift_description = $_POST['gift-description'];
    $gift->setDescription($gift_description);

    $repository->update($gift);

    //L'ajax
    $reponse = 'success';
    echo json_encode([
        'reponse' => $reponse,
        'gift_title' => $gift_title,
        'gift_url' => $gift_url,
        'gift_description' => $gift_description,
    ], JSON_THROW_ON_ERROR);
}
