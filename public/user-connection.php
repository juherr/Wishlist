<?php

declare(strict_types=1);

include(__DIR__ . '../src/inc/bdd.php');
include(__DIR__ . '../src/inc/config.php');

$bdd->query('SET NAMES "utf8"');


$id_user = $_POST['id_personne'];

if (isset($id_user) && ($id_user !== '')) {
    session_start();
    $_SESSION['user'] = $id_user;
    header('Location: index.php');
} else {
    header('Location: login.php');
}
