$(document).ready(function($) {
    $('#configurations').addClass('active');

    $('#resetConfig').on('click', function (e) {
        if (confirm("Voulez-vous réinitiliser les paramètres ?")) {
            $.ajax({
                url: 'api/resetConfig',
                type: 'POST',
            })
            .done(function() {
                window.location.reload();
            });
        }
        
    });
});