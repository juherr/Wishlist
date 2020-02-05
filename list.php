<?php 

	// LA VUE LISTE DE CADEAUX


	//Chargement
	include_once('template-parts/header.php');

	getUsers();

	//GET
	
	//Récupérer l'ID de la liste à afficher

	if(isset($_GET['list'])){
		$listID = $_GET['list'];
	}

	//Query

	// // Récupérer les infos du user

	$activeUser = $bdd->query('SELECT * FROM '.$config['db_tables']['db_users'].' WHERE userID = '.$listID);
		
	while($export_user = $activeUser->fetch()){
		$active_user = [
			"ID" => $export_user['userID'],
			"name" => $export_user['name'],
			"isChildAccount" => $export_user['isChildAccount'],
			"picture" => $export_user['picture'],
			"birthday_date" => $export_user['birthday_date'],
			"size_top" => $export_user['size_top'],
			"size_bottom" => $export_user['size_bottom'],
			"size_feet" => $export_user['size_feet'],
		];
	}

	//Récupérer les derniers cadeaux de l'utilisateur connecté

	$bddGifts = $bdd->query('SELECT * FROM '.$config['db_tables']['db_gifts'].' WHERE userID = '.$listID.' ORDER BY ID DESC');
		
	while($export_gifts = $bddGifts->fetch()){
		$lastgifts[] = [
			"title" => $export_gifts['title'],
			"description" => $export_gifts['description'],
			"link" => $export_gifts['link'],
		];
	}

	//Calcul et affichage date anniversaire

	setlocale(LC_TIME, 'fr_FR'); 
	
	$date_anniversaire = strftime('%e %B %Y', strtotime($active_user['birthday_date']));

	//age en année
	$age = floor((time() - strtotime($active_user['birthday_date']))/60/60/24/365.25);

?>

<body class="connected-user-profil">

	<?php include('template-parts/header-top.php');?>
	
	<?php
	
	/* 
	
	On vérifie si l'user actif peut modifier cette liste
	
	*/

	// Tableau des userID pouvant être modifiés par l'user actif	
	global $canUpdateLists;
	
	$canEdit = false;
	
	if(isset($canUpdateLists)){
			if(in_array($listID, $canUpdateLists)){
		$canEdit = true;
	}
	}


	
	?>

	<?php if($active_user): ?>

	<section class="user-infos background white-background">
		
		<img src="src/img/avatar/avatar<?php echo $active_user['picture'];?>.png" alt="">
		<div class="inner-user-infos">
			<h1>
				<?php echo $active_user['name'];?>
			</h1>

			<?php if($active_user['birthday_date']):?>

			<div class="birthday-infos">
				<div class="svg-wrapper">
					<object data="src/img/svg-prod/baby.svg" type="image/svg+xml"></object>
				</div>
				<span class="birthday"><?php echo $date_anniversaire;?></span>
				<span class="age"><?php echo $age;?> ans</span>
			</div>

			<?php endif;?>

			<?php if($active_user['size_top'] or $active_user['size_bottom'] or isset($active_user['size_feet'])):?>

			<div class="size-infos">

				<?php if($active_user['size_top']):?>
				<div class="wrapper-size" title="Taille haut">
					<div class="svg-wrapper">
						<object data="src/img/svg-prod/tshirt.svg" type="image/svg+xml"></object>
					</div>
					<span class="top"><?php echo $active_user['size_top'];?></span>
				</div>
				
				<?php endif;?>

				<?php if($active_user['size_bottom']):?>
				<div class="wrapper-size" title="Taille pantalon">
					<div class="svg-wrapper">
						<object data="src/img/svg-prod/pant.svg" type="image/svg+xml"></object>
					</div>
					<span class="bottom"><?php echo $active_user['size_bottom'];?></span>
				</div>

				<?php endif;?>

				<?php if(isset($active_user['size_feet'])):?>
				<div class="wrapper-size" title="Taille chaussure">
					<div class="svg-wrapper">
						<object data="src/img/svg-prod/shoe.svg" type="image/svg+xml"></object>
					</div>
					<span class="feet"><?php echo $active_user['size_feet'];?></span>
				</div>

				<?php endif;?>
			</div>

			<?php endif;?>
		</div>
		
	</section>

	<?php endif;?>
	
	<?php if(isset($export_gifts)):?>

	<section class="list-gifts background white-background">

		<ul class="grid">
			
			<?php foreach($lastgifts as $gift): ?>
		
			<li class="list_elt single-gift list_elt_scroll">

				<div class="gift-content">
					<div class="gift-header">
						<h3><?php echo $gift["title"];?></h3>
						
						<?php if($gift['link'] != ''):?>
						
						<a href="<?php echo $gift['link'];?>" target="_blank" class="bt border-pink-bt">Voir le site</a>
						
						<?php endif;?>
					</div>
					
					<?php if($gift['description'] != ''):?>

					<div class="gift-description">
						<p><?php echo $gift['description'];?></p>
					</div>
					
					<?php endif;?>
				</div>
				
				<?php if($canEdit == true):?>
				
				<div class="wrapper-bt-elt">
					<button class="bt white-bt">Réserver</button>
					<button class="bt red-bt">Annuler</button>
				</div>
				
				<?php endif;?>
			</li>
			
			<?php endforeach;?>

		</ul>

		

	</section>
	
	<?php endif;?>

	<?php if($users_list): ?>


	<section class="primary-background background">
		<h2>Voir les listes</h2>
		
		<ul class="grid">
		
			<?php
			
			foreach($users_list as $user){
				if($user['ID'] != $_SESSION['userID'] && $user['ID'] != $listID){
					echo printSingleUser($user, 'Voir la liste', 'list.php?list='.$user['ID']);
				}
			}
			
			?>
			
		</ul>
		
	</section>

	<?php endif;?>

</body>