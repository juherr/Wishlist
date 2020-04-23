<?php

declare(strict_types=1);

use Wishlist\Gifts\GiftRepository;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';

$id_gift = $_POST['gift-id'];
if (isset($id_gift) && ($id_gift !== '')) {

    $repository = new GiftRepository($bdd);
    $gift = $repository->findById((int)$id_gift);
    if ($gift === null) {
        return;
    }
    $gift->book((int)$_SESSION['user']);
    $repository->update($gift);

    $reponse = 'success';

    echo json_encode([
        'reponse' => $reponse,
        'gift_id' => $id_gift,
    ], JSON_THROW_ON_ERROR);
}
