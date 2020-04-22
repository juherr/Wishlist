<?php

declare(strict_types=1);

include('../src/inc/bdd.php');
include('../src/inc/config.php');
$bdd->query('SET NAMES "utf8"');


$username = $_POST['username'];
$choix_illu = $_POST['choix-illu'];


if (isset($username) && ($username !== '')) {
    $statement = $bdd->prepare(
        'INSERT INTO ' . $bdd_users . ' (id_personne, nom_personne, choix_illu) VALUES (NULL, ?, ?)'
    );

    $statement->bindParam(1, $username, PDO::PARAM_STR);
    $statement->bindParam(2, $choix_illu, PDO::PARAM_INT);

    $statement->execute();

    header('location:login.php');
}
