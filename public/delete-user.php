<?php

declare(strict_types=1);

include(__DIR__ . '../src/inc/bdd.php');
include(__DIR__ . '../src/inc/config.php');

$bdd->query('SET NAMES "utf8"');


$user_id = $_POST['user-id'];

$statement = $bdd->prepare('DELETE FROM ' . $bdd_users . ' WHERE id_personne = :user_id');
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();

$statement2 = $bdd->prepare('DELETE FROM ' . $bdd_gifts . ' WHERE la_personne = :user_id');
$statement2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement2->execute();

    //L'ajax

    $reponse = 'success';
    echo json_encode([
        'reponse' => $reponse,
    ], JSON_THROW_ON_ERROR);
