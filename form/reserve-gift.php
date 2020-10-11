<?php

if(isset($_POST['giftID']) && $_POST['giftID'] != '' && isset($_POST['sessionUser']) && $_POST['sessionUser'] != ''){
	$giftID = filter_var($_POST['giftID'], FILTER_SANITIZE_NUMBER_INT);
	$sessionUser = filter_var($_POST['sessionUser'], FILTER_SANITIZE_NUMBER_INT);
}

if(isset($giftID) && isset($sessionUser)){
	
	global $bdd;
	include_once('../inc/conf/config.php');

	$saveBDD = $bdd->prepare("UPDATE ".$config['db_tables']['db_gifts']." SET isReserved = 1, reservationUserID = ".$sessionUser." WHERE ID = ".$giftID);

	if($saveBDD->execute()){
		$isBDDsuccess = true;
	}else{
		$isBDDsuccess = false;
	}

}else{
	echo 'Erreur.';
}

// Vérifier si tout c'est bien enregistré et messages

if($isBDDsuccess == true){
	echo 'Réussite';
	header("location:../user.php?user=".$_POST['userID']."&statut=giftReserved&gift=".$giftID);
	
}else{
	echo 'Echec';
}