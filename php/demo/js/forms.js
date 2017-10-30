$(function () {
    var api = "./../api/";

    $('form#login').submit(function (e) {
        e.preventDefault();
        var email = $(this).find('input#email').val();
        var password = $(this).find('input#password').val();
        var form = $('div#login');
        form.hide();
        form.html('<h3>Your login request has been submitted. </h3>');
        form.fadeIn("slow");

        $.ajax({
            type: 'POST',
            data: 'request=login&email=' + email + '&password=' + password,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                //success
                form.append('<br />' + data);
            },
            error: function () {
                alert("an error has occured!");
            }
        });
    });

    $('form#register').submit(function (e) {
        e.preventDefault();
        var email = $(this).find('input#email').val();
        var password = $(this).find('input#password').val();
        var passwordconf = $(this).find('input#passwordconf').val();
        var form = $('div#register');
        form.hide();
        form.html('<h3>Your register request has been submitted. </h3>');
        form.fadeIn("slow");

        $.ajax({
            type: 'POST',
            data: 'request=register&email=' + email + '&password=' + password + '&passwordconf=' + passwordconf,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                //success
                form.append('<br />' + data);
            },
            error: function () {
                alert("an error has occured!");
            }
        });
    });
});