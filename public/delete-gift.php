<?php

declare(strict_types=1);

include('../src/inc/bdd.php');
include('../src/inc/config.php');

$bdd->query('SET NAMES "utf8"');


$gift_id = $_POST['gift-id'];

$statement = $bdd->prepare('DELETE FROM ' . $bdd_gifts . ' WHERE id = :id');

$statement->bindParam(':id', $gift_id, PDO::PARAM_STR);
$statement->execute();

// Ajax

$reponse = 'success';
echo json_encode([
    'reponse' => $reponse,
]);
