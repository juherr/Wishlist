<?php

declare(strict_types=1);

include('../src/inc/bdd.php');
include('../src/inc/config.php');

$bdd->query('SET NAMES "utf8"');

$id_personne = $_POST['id_personne'];
$username = $_POST['username'];
$choix_illu = $_POST['choix-illu' . $id_personne];

if (isset($username) && ($username !== '')) {
    $statement = $bdd->prepare(
        'UPDATE ' . $bdd_users . ' SET nom_personne = :nom, choix_illu = :choix_illu WHERE id_personne = :id_personne'
    );

    $statement->bindParam(':nom', $username, PDO::PARAM_STR);
    $statement->bindParam(':choix_illu', $choix_illu, PDO::PARAM_INT);
    $statement->bindParam(':id_personne', $id_personne, PDO::PARAM_INT);
    $statement->execute();

    //L'ajax

    $reponse = 'success';
    echo json_encode([
        'reponse' => $reponse,
        'username' => $username,
    ]);
}