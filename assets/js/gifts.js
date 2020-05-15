'use strict';

import {i18n, msnry} from './commons';
import $ from 'jquery';

$('body')

    // Ouverture et fermeture du form d'ajout cadeau
    .on('click', '.bt-add-gift', function () {
        const $this = $(this);
        const formAdd = $this.parent().parent().find('.form-add');
        formAdd.toggleClass('open');
        msnry.layout();
        $this.toggleClass('bt open bt-cancel');
        formAdd.slideToggle(() => {
            msnry.layout();
        });

        if ($this.hasClass('open')) {
            $this.html(i18n.t('cancel'));
        } else {
            $this.html(i18n.t('gift.add.title'));
        }
    })

    // Edition des cadeaux
    .on('click', '.ico-edit', function () {
        $('.form-edit').slideUp(() => {
            msnry.layout();
        });
        $(this).parent().parent().find('.form-edit').slideToggle(() => {
            msnry.layout();
        });
    })

    .on('click', '.cancel-edit-gift', function () {
        $(this).parent().parent().slideToggle(() => {
            msnry.layout();
        });
    })

    // Suppression cadeau
    .on('click', '.submit-delete', function () {
        $(this).parent().find('.confirmation-suppression').fadeIn();
    })

    .on('click', '.annuler-suppression', function () {
        $(this).parent().fadeOut();
    })

    .on('submit', '.confirmation-suppression form', function (e) {
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
                $this.parent().parent().parent().fadeOut(function () {
                    msnry.layout();
                })
            }
        });
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
            alert(i18n.t('empty_form'));
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
                        '<a title="' + i18n.t('gift.link') + '" href="' + json.gift_url + '" class="gift-link">' +
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
                    '      <p>' + i18n.t('delete.confirmation') + '</p>' +
                    '      <form action="' + route_gift_delete + '" method="post">' +
                    '        <input type="hidden" value="' + json.gift_id + '" name="gift-id">' +
                    '        <input type="submit" class="confirm-suppression bt" value="' + i18n.t('yes') + '" />' +
                    '      </form>' +
                    '      <p class="annuler-suppression">' + i18n.t('delete.cancel') + '</p>' +
                    '    </div>' +
                    '    <span class="ico-edit" title="' + i18n.t('gift.edit.title') + '">' +
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
                    '      <input type="text" name="gift-name" required placeholder="' + i18n.t('gift.placeholder.title') + '" value="' + giftTitle + '">' +
                    '    </div>' +
                    '    <div class="wrapper-gift-input">' +
                    '      <span>' +
                    '        <svg viewBox="0 0 100 100" class="icon">' +
                    '          <use xlink:href="#icon-link"></use>' +
                    '        </svg>' +
                    '      </span>' +
                    '      <input type="text" name="gift-url" placeholder="' + i18n.t('gift.placeholder.link') + '" value="' + json.gift_url + '">' +
                    '    </div>' +
                    '    <textarea name="gift-description" id="" rows="3" placeholder="' + i18n.t('gift.placeholder.description') + '">' + json.gift_description + '</textarea>' +
                    '    <input type="hidden" value="' + json.gift_id + '" name="gift-id">' +
                    '    <input type="submit" class="bt bt-edit-gift" value="' + i18n.t('gift.edit.confirm') + '">' +
                    '    <div class="wrapper-bt-edit-gift">' +
                    '      <span class="cancel-edit-gift bt-cancel">' + i18n.t('cancel') + '</span>' +
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
            alert(i18n.t('empty_form'));
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
                        '<a title="' + i18n.t('gift.link') + '" href="' + json.gift_url + '" class="gift-link">' +
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
                    msnry.layout();
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
                    '  <input type="submit" value="' + i18n.t('cancel') + '" class="bt bt_annuler" ' +
                    '    title="' + i18n.t('gift.book.cancel') + '" />' +
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
                    '  <input type="submit" value="' + i18n.t('gift.book.title') +'" class="bt_resa bt">' +
                    '</form>');
                $this.remove();
            }
        });
    })
;
