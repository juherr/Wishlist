'use strict';

(() => {
    $(() => {

        // Neige
        snowStorm.flakesMaxActive = 80000;
        snowStorm.animationInterval = 25;
        snowStorm.followMouse = false;
        snowStorm.targetElement = "snow";

        // Masonry
        window.$grid = $('.content').masonry({
            itemSelector: '.user',
            columnWidth: '.grid-sizer',
            percentPosition: true,
            gutter: 50
        });

        // === GIFT & USER AJAX ===
        $('body').on('submit', '.confirmation-suppression form', function (e) {
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
                        $grid.masonry();
                    })
                }
            });
        });
    });
})();
