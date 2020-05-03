'use strict';

(() => {
    $(() => {
        $('body')

        // Ouverture et fermeture du form d'ajout cadeau
        .on('click', '.bt-add-gift', function () {
            const $this = $(this);
            const formAdd = $this.parent().parent().find('.form-add');
            formAdd.toggleClass('open');
            $grid.masonry();
            $this.toggleClass('bt open bt-cancel');
            formAdd.slideToggle(() => {
                $grid.masonry();
            });

            if ($this.hasClass('open')) {
                $this.html('Annuler');
            } else {
                $this.html('Ajouter un cadeau');
            }
        })

        // Edition des cadeaux
        .on('click', '.ico-edit', function () {
            $('.form-edit').slideUp(() => {
                $grid.masonry();
            });
            $(this).parent().parent().find('.form-edit').slideToggle(() => {
                $grid.masonry();
            });
        })

        .on('click', '.cancel-edit-gift', function () {
            $(this).parent().parent().slideToggle(() => {
                $grid.masonry();
            });
        })

        // Suppression cadeau
        .on('click', '.submit-delete', function () {
            $(this).parent().find('.confirmation-suppression').fadeIn();
        })

        .on('click', '.annuler-suppression', function () {
            $(this).parent().fadeOut();
        })

        // Ajout cadeau AJAX
        .on('submit', '.form-add', function (e) {
            e.preventDefault();

            const $this = $(this);

            // Je récupère les valeurs
            const giftTitle = $this.find('input[name="gift-name"]').val();
            // Je vérifie une première fois pour ne pas lancer la requête HTTP
            // si je sais que mon PHP renverra une erreur
            if (giftTitle === '') {
                alert('Les champs doivent êtres remplis');
                return;
            }
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                dataType: 'json', // JSON
                success: json => {
                    if (json.reponse !== 'success') {
                        return;
                    }

                    let giftUrlCode = '';
                    if (json.gift_url !== '') {
                        giftUrlCode =
                            '<a title="Lien vers le cadeau" href="' + json.gift_url + '" class="gift-link">' +
                            '  <svg viewBox="0 0 100 100" class="icon">' +
                            '    <use xlink:href="#icon-link"></use>' +
                            '  </svg>' +
                            '</a>';
                    }

                    let giftDescriptionCode = '';
                    if (json.gift_description !== '') {
                        giftDescriptionCode = '<p class="gift-description">' + json.gift_description + '</p>'
                    }

                    $this.parent().find('ul').append(
                        '<li>' +
                        '  <div class="wrapper-title">' +
                        '    <p class="gift-title">' + json.gift_title + '</p>' +
                        giftUrlCode +
                        '    <span class="submit-delete ico-trash">' +
                        '      <svg viewBox="0 0 100 100" class="icon">' +
                        '        <use xlink:href="#icon-ico-trash"></use>' +
                        '      </svg>' +
                        '    </span>' +
                        '    <div class="confirmation-suppression">' +
                        '      <p>Êtes-vous sûr ?</p>' +
                        '      <form action="' + route_gift_delete + '" method="post">' +
                        '        <input type="hidden" value="' + json.gift_id + '" name="gift-id">' +
                        '        <input type="submit" class="confirm-suppression bt" value="Oui" />' +
                        '      </form>' +
                        '      <p class="annuler-suppression">Non, annuler</p>' +
                        '    </div>' +
                        '    <span class="ico-edit" title="Éditer le cadeau">' +
                        '      <svg viewBox="0 0 100 100" class="icon">' +
                        '        <use xlink:href="#icon-ico-edit"></use>' +
                        '      </svg>' +
                        '    </span>' +
                        '  </div>' +
                        giftDescriptionCode +
                        '  <form class="form-gift form-edit" action="' + route_gift_update + '" method="post">' +
                        '    <div class="wrapper-gift-input">' +
                        '      <span>' +
                        '        <svg viewBox="0 0 100 100" class="icon">' +
                        '          <use xlink:href="#icon-ico-item"></use>' +
                        '        </svg>' +
                        '      </span>' +
                        '      <input type="text" name="gift-name" required placeholder="Désignation" value="' + giftTitle + '">' +
                        '    </div>' +
                        '    <div class="wrapper-gift-input">' +
                        '      <span>' +
                        '        <svg viewBox="0 0 100 100" class="icon">' +
                        '          <use xlink:href="#icon-link"></use>' +
                        '        </svg>' +
                        '      </span>' +
                        '      <input type="text" name="gift-url" placeholder="Lien optionnel" value="' + json.gift_url + '">' +
                        '    </div>' +
                        '    <textarea name="gift-description" id="" rows="3" placeholder="Détail optionnel">' + json.gift_description + '</textarea>' +
                        '    <input type="hidden" value="' + json.gift_id + '" name="gift-id">' +
                        '    <input type="submit" class="bt bt-edit-gift" value="Modifier le cadeau">' +
                        '    <div class="wrapper-bt-edit-gift">' +
                        '      <span class="cancel-edit-gift bt-cancel">Annuler</span>' +
                        '    </div>' +
                        '  </form>' +
                        '</li>').children(':last').hide().fadeIn(1000);

                    $this.find("input[type=text], textarea").val("");
                }
            });

        })

        // Edit cadeau ajax
        .on('submit', '.form-edit', function (e) {
            e.preventDefault();

            const $this = $(this);

            // Je vérifie une première fois pour ne pas lancer la requête HTTP
            // si je sais que mon PHP renverra une erreur
            const giftTitle = $this.find('input[name="gift-name"]').val();
            if (giftTitle === '') {
                alert('Les champs doivent êtres remplis');
                return;
            }

            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                dataType: 'json', // JSON
                success: json => {
                    if (json.reponse !== 'success') {
                        alert('Erreur : ' + json.reponse);
                        return;
                    }

                    //ce qui se passe si succès
                    $this.parent().find('.gift-title').html(json.gift_title);

                    // Gérer le lien
                    $this.parent().find('.gift-link').remove();
                    if (json.gift_url !== '') {
                        $this.parent().find('.wrapper-title').append(
                            '<a title="Lien vers le cadeau" href="' + json.gift_url + '" class="gift-link">' +
                            '  <svg viewBox="0 0 100 100" class="icon">' +
                            '    <use xlink:href="#icon-link"></use>' +
                            '  </svg>' +
                            '</a>');
                    }

                    //Gérer la description
                    $this.parent().find('.gift-description').remove();
                    if (json.gift_description !== '') {
                        $this.parent().find('.wrapper-title').after(
                            '<p class="gift-description">' + json.gift_description + '</p>');
                    }

                    $this.slideUp(() => {
                        $grid.masonry();
                    });
                }
            });
        })

        //Reserver un cadeau AJAX
        .on('submit', '#form-resa', function (e) {
            e.preventDefault();

            const $this = $(this);
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                dataType: 'json', // JSON
                success: json => {
                    if (json.reponse !== 'success') {
                        alert('Erreur : ' + json.reponse);
                        return;
                    }
                    //ce qui se passe si succès
                    $this.parent().parent().addClass('reserve');
                    $this.parent().append(
                        '<form action="' + route_booking_delete + '" id="cancel_resa" method="post">' +
                        '  <input type="hidden" value="' + json.gift_id + '" name="gift-id">' +
                        '  <input type="submit" value="Annuler" class="bt bt_annuler" ' +
                        '    title="Tu as indiqué vouloir réserver ce cadeau. Changé d\'avis ?" />' +
                        '</form>');
                    $this.remove();
                }
            });
        })

        //Annuler une résa AJAX
        .on('submit', '#cancel_resa', function (e) {
            e.preventDefault();

            const $this = $(this);
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                dataType: 'json', // JSON
                success: json => {
                    if (json.reponse !== 'success') {
                        alert('Erreur : ' + json.reponse);
                        return;
                    }
                    //ce qui se passe si succès
                    $this.parent().parent().removeClass('reserve');
                    $this.parent().append(
                        '<form action="' + route_booking_create + '" method="post" id="form-resa">' +
                        '  <input type="hidden" value="' + json.gift_id + '" name="gift-id">' +
                        '  <input type="submit" value="Réserver" class="bt_resa bt">' +
                        '</form>');
                    $this.remove();
                }
            });
        });
    });
})();
