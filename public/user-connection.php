<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/inc/bdd.php';

$id_user = $_POST['id_personne'];

if (isset($id_user) && ($id_user !== '')) {
    session_start();
    $_SESSION['user'] = $id_user;
    header('Location: index.php');
} else {
    header('Location: login.php');
}
