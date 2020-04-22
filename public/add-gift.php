<?php

declare(strict_types=1);

use Wishlist\Gifts\Gift;
use Wishlist\Gifts\GiftRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';
$bdd->query('SET NAMES "utf8"');

$gift_title = $_POST['gift-name'];
$gift_url = $_POST['gift-url'];
$gift_description = $_POST['gift-description'];
$gift_user = $_POST['gift-user'];

if (isset($gift_title) && ($gift_title !== '')) {
    $repository = new GiftRepository($bdd);

    $gift_id = $repository->add(new Gift(
        (int)$gift_user,
        $gift_title,
        $gift_url,
        $gift_description,
        false
    ));

    echo json_encode([
        'reponse' => 'success',
        'gift_title' => $gift_title,
        'gift_url' => $gift_url,
        'gift_description' => $gift_description,
        'gift_id' => $gift_id,
    ], JSON_THROW_ON_ERROR);
}
