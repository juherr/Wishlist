'use strict';

import {i18n, msnry} from './commons';
import $ from 'jquery';
import 'jquery.scrollto';

$('body')

    // Afficher le modal au click sur 'ajouter un perso'
    .on('click', '.add-user button', () => {
        $('.modal-user').fadeIn(() => {
            msnry.layout();
        })
        const posModal = $('#modal-add-user').offset();
        $(window).scrollTo(posModal.top - 200, 300);
    })

    // La virer si on annule
    .on('click', '.modal-user .bt-cancel', () => {
        $('.modal-user').fadeOut(() => {
            msnry.layout();
        })
    })

    // faire apparaitre l'edit des user
    .on('click', '.ico-edit-user', function () {
        $(this).parent().parent().find('.edit-user').slideToggle(() => {
            msnry.layout();
        });
    })

    // ranger l'edit user si on annule
    .on('click', '.edit-user .bt-cancel', function () {
        $(this).parent().parent().parent().slideToggle(() => {
            msnry.layout();
        });
    })

    // Edit user AJAX
    .on('submit', '.edit-user form', function (e) {
        e.preventDefault();

        const $this = $(this);

        // Je vérifie une première fois pour ne pas lancer la requête HTTP
        // si je sais que mon PHP renverra une erreur
        const username = $this.find('input[name="username"]').val();
        if (username === '') {
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
                $this.parent().parent().find('h2').text(json.username);
                $this.parent().slideUp(() => {
                    msnry.layout();
                })
            }
        });
    })

    // Delete user
    .on('click', '.ico-delete-user', function () {
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
;

// Modal ajouter personne
// Le titre change quand on tape
$('.modal-add-user .input-name').on('keyup', function () {
    const $this = $(this);

    const titleNode = $this.parent().parent();
    const title = titleNode.find('h2');
    const submitButton = titleNode.find('input[type="submit"]');

    const personName = $this.val();
    if (personName === '') {
        title.html(i18n.t('user.add.title'));
        submitButton.attr('value', i18n.t('user.add.action'));
    } else {
        title.html(personName);
        submitButton.attr('value', i18n.t('user.add.action2', { name: personName }));
    }
});

// L'illu change quand on la sélectionne
$('.modal-add-user .wrapper-illus').on('change', function () {
    const $this = $(this);

    const illuName = $this.find('input[type="radio"]:checked').attr('class');
    $this.parent().parent().find('.illu').html('<img src="/build/' + illuName + '.png"/>');
});

// Edit user
// Le titre change quand on tape
$('.edit-user .input-name').on('keyup', function () {
    const $this = $(this);

    const submitButton = $this.parent().parent().find('input[type="submit"]');
    const personName = $this.val();
    if (personName === '') {
        submitButton.attr('value', i18n.t('user.edit.confirm'));
    } else {
        submitButton.attr('value', i18n.t('user.edit.confirm2'));
    }
});

// L'illu change quand on la sélectionne
$('.edit-user .wrapper-illus').on('change', function () {
    const $this = $(this);

    const illuName = $this.find('input[type="radio"]:checked').attr('class');
    $this.parent().parent().parent().find('.illu').html('<img src="/build/' + illuName + '.png"/>');
});
