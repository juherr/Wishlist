<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');


$user_id = $_POST['user-id'];

$statement = $bdd->prepare("DELETE FROM personne WHERE id_personne = :user_id");
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();

$statement2 = $bdd->prepare("DELETE FROM liste WHERE la_personne = :user_id");
$statement2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement2->execute();

	//L'ajax

	$reponse = 'success';
	echo json_encode(array(
		'reponse'=>$reponse
	));



?>
