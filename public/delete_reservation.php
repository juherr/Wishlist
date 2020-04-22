<?php

declare(strict_types=1);

session_start();

include(__DIR__ . '../src/inc/bdd.php');
include(__DIR__ . '../src/inc/config.php');

$bdd->query('SET NAMES "utf8"');


$id_gift = $_POST['gift-id'];


if (isset($id_gift) && ($id_gift !== '')) {
    $etat_resa = 0;
    $user_resa = '';

    $statement = $bdd->prepare('UPDATE ' . $bdd_gifts . ' SET reserve = :etat, IdUser_resa = :userResa WHERE id = :id');

    $statement->bindParam(':etat', $etat_resa, PDO::PARAM_INT);
    $statement->bindParam(':userResa', $user_resa, PDO::PARAM_INT);
    $statement->bindParam(':id', $id_gift, PDO::PARAM_INT);


    $statement->execute();

    $reponse = 'success';

    echo json_encode([
        'reponse' => $reponse,
        'gift_id' => $id_gift,
    ], JSON_THROW_ON_ERROR);
}
