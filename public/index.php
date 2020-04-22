<?php declare(strict_types=1);

use Wishlist\Gifts\GiftRepository;
use Wishlist\Users\UserRepository;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();
    if (! isset($_SESSION['user']) && empty($_SESSION['user'])) {
        header('Location: login.php');
    }
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Wishlist de Noël</title>
	<link rel="stylesheet" href="style.css">
	<link href='https://fonts.googleapis.com/css?family=Lato:400,900,700' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

	<div class="svg-wrapper" aria-hidden="true">
		<?php echo file_get_contents('img/svg-prod/sprite/svgs.svg'); ?>
	</div>

	<div id="snow"></div>

	<div class="wrapper">

		<header class="logo">
			<?php echo file_get_contents('img/logo.svg'); ?>
		</header>

		<div class="content">

			<div class="grid-sizer"></div>

			<?php
                require_once __DIR__ . '/../src/inc/bdd.php';

                $userRepository = new UserRepository($bdd);
                foreach ($userRepository->findAll() as $export_user) {
                    $nom_personne = $export_user->getUsername();
                    $id_personne = $export_user->getId();
                    $id_illu = $export_user->getIconId();
            ?>

			<div class="user" id="user<?php echo $id_personne; ?>">
				<div class="illu">
					<img src="img/perso<?php echo $id_illu; ?>.png">
				</div>

				<div class="wrapper-username">
					<h2><?php echo $nom_personne ?></h2>
				</div>


				<ul class="gift-list">

					<?php
                        $giftRepository = new GiftRepository($bdd);

                        foreach ($giftRepository->findByUserId((int)$id_personne) as $gift) {
                            $nom_gift = $gift->getTitle();
                            $link_gift = $gift->getLink();
                            $description_gift = $gift->getDescription();
                            $id_gift = $gift->getId();
                            $resa_gift = $gift->isBooked();

                    ?>
					<li <?php //Si le cadeau est réservé et qu'on est pas sur notre propre liste, on le marque différemment
                        if ($resa_gift === true && $id_personne !== $_SESSION['user']) {
                            echo 'class="reserve"';
                        } ?>
                    >
						<div class="wrapper-title">

							<p class="gift-title"><?php echo $nom_gift; ?></p>

							<?php if ($link_gift) : ?>
								<a title="Lien vers le cadeau" href="<?php echo $link_gift; ?>" class="gift-link">
									<svg viewBox="0 0 100 100" class="icon">
										<use xlink:href="#icon-link"></use>
									</svg>
								</a>
							<?php endif; ?>

							<?php
                                //Chaque personne identifié peut modifier sa propre liste
                                if ($id_personne === $_SESSION['user']) :
                            ?>

							<span class="submit-delete ico-trash">
								<svg viewBox="0 0 100 100" class="icon">
									<use xlink:href="#icon-ico-trash"></use>
								</svg>
							</span>

							<div class="confirmation-suppression">
									<p>Êtes-vous sûr ?</p>
									<form action="delete-gift.php" method="post">
										<input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">
										<input type="submit" class="confirm-suppression bt" value="Oui" />
									</form>
									<p class="annuler-suppression">Non, annuler</p>
							</div>

							<span class="ico-edit" title="Éditer le cadeau">
								<svg viewBox="0 0 100 100" class="icon">
									<use xlink:href="#icon-ico-edit"></use>
								</svg>
							</span>

							<?php
                                //Si on est pas le propriétaire la liste, on gère la réservation
                                else :
                                // On récupère l'état de réservation, la personne qui l'a éventuellement réservé
                                $resa = $giftRepository->findById($id_gift);
                                if ($resa !== null) {

                                    $etat_reservation = $resa->isBooked();
                                    $user_reservation = $resa->getBookedByUserId();

                                    //Si le cadeau n'est pas réservé, j'affiche le bouton "réserver"
                                    if (!$etat_reservation) :
                                ?>
							    <form action="gift-reservation.php" method="post" id="form-resa">
							        <input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">
							        <input type="submit" value="Réserver" class="bt_resa bt">
							    </form>
                                <?php
                                    // Si le cadeau est réservé
                                    else :
                                        //Si il est réservé par moi, je peux annuler
                                        if ($user_reservation === $_SESSION['user']) :
                                ?>
                                        <form action="delete_reservation.php" method="post" id="cancel_resa">
                                            <input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">
                                            <input type="submit" value="Annuler" class="bt bt_annuler" title="Tu as indiqué vouloir réserver ce cadeau. Changé d'avis ?">
                                        </form>
                                <?php
                                        //Si il est réservé par qqun d'autre, j'affiche ce qqun d'autre
                                        else :
                                            $userRepository = new UserRepository($bdd);
                                            $mec_resa = $userRepository->findById($user_reservation);
                                            if ($mec_resa === null) {
                                                $nom_dumec = null;
                                                $illu = null;
                                            } else {
                                                $nom_dumec = $mec_resa->getUsername();
                                                $illu = $mec_resa->getIconId();
                                            }
                                ?>
                                       <div class="resaPar" title="<?php echo $nom_dumec; ?> a réservé ce cadeau">
                                            <img src="img/perso<?php echo $illu; ?>.png">
                                            <span><?php echo $nom_dumec; ?></span>
                                       </div>
                            <?php
                                        endif;
                                    endif;
                                }
                            endif;
                            ?>
						</div>

						<?php if ($description_gift) : ?>
						<p class="gift-description"><?php echo $description_gift; ?></p>
						<?php endif; ?>

                        <?php
                            //Chaque personne identifié peut modifier sa propre liste
                            if ($id_personne === $_SESSION['user']) :
                        ?>

						<?php //Le formulaire, pour edition?>
						<form class="form-gift form-edit" action="update-gift.php" method="post">
							<div class="wrapper-gift-input">
								<span>
									<svg viewBox="0 0 100 100" class="icon">
										<use xlink:href="#icon-ico-item"></use>
									</svg>
								</span>
								<input type="text" name="gift-name" required placeholder="Désignation" value="<?php echo $nom_gift; ?>">
							</div>
							<div class="wrapper-gift-input">
								<span>
									<svg viewBox="0 0 100 100" class="icon">
										<use xlink:href="#icon-link"></use>
									</svg>
								</span>
								<input type="text" name="gift-url" placeholder="Lien optionnel" value="<?php echo $link_gift; ?>">
							</div>

							<textarea name="gift-description" id="" rows="3" placeholder="Détail optionnel"><?php echo $description_gift; ?></textarea>

							<input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">

							<input type="submit" class="bt bt-edit-gift" value="Modifier le cadeau">

							<div class="wrapper-bt-edit-gift">
								<span class="cancel-edit-gift bt-cancel">Annuler</span>
							</div>
						</form>

						<?php endif; ?>
					</li>
					<?php } // end gifts ?>
				</ul>

				<form class="form-gift form-add" action="add-gift.php" method="post" id="add-user">
					<div class="wrapper-gift-input">
						<span>
							<svg viewBox="0 0 100 100" class="icon">
								<use xlink:href="#icon-ico-item"></use>
							</svg>
						</span>
						<input type="text" name="gift-name" required placeholder="Désignation">
					</div>
					<div class="wrapper-gift-input">
						<span>
							<svg viewBox="0 0 100 100" class="icon">
								<use xlink:href="#icon-link"></use>
							</svg>
						</span>
						<input type="text" name="gift-url" placeholder="Lien optionnel">
					</div>

					<textarea name="gift-description" id="" rows="3" placeholder="Détail optionnel"></textarea>

					<input type="hidden" value="<?php echo $id_personne; ?>" name="gift-user">

					<input type="submit" class="bt" value="Ajouter le cadeau">
				</form>

				<?php if ($id_personne === $_SESSION['user']) : ?>
				<div class="wrapper-bt wrapper-add">
					<button class="bt bt-add-gift">Ajouter un cadeau</button>
				</div>
				<?php endif; ?>
			</div>
			<?php } // end user?>
		</div>
        </div>
	</div>

	<footer><div class="logout"><a href="logout.php">Se déconnecter</a></div></footer>


<script src="js/snowstorm-min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/masonry.pkgd.min.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/scripts.js"></script>

</body>
</html>
