$(function () {
    var api = "./../api/";
    $("div#loggedIn").hide();
    
    $.ajax({
        type: 'POST',
        data: 'request=checklogin',
        url: api + 'index.php',
        async: true,
        success: function (data) {
            //success
            if(data == 1){
               $("div#loggedIn").show();
               $("form#login").hide();
            } 
        },
        error: function () {
            alert("an error has occured!");
        }
    });
    
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
                if(data == 1) {
                    form.fadeOut("slow");
                    $("div#loggedIn").hide();
                    $("div#loggedIn").show();
                } else {
                    form.append("<br/> Failed to login!");
                }
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

        $.ajax({
            type: 'POST',
            data: 'request=register&email=' + email + '&password=' + password + '&passwordconf=' + passwordconf,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                //success
                form.html(data);
                form.fadeIn("slow");
            },
            error: function () {
                alert("an error has occured!");
            }
        });
    });
    $('form#logout').submit(function (e) {
        e.preventDefault();
       $.ajax({
            type: 'POST',
            data: 'request=logout&r=t',
            url: api + 'index.php',
            async: true,
            success: function (data) {
                //success
                $("div#loggedIn").html("<a href=\"logreg.php\">Refresh</a>");
            },
            error: function () {
                alert("error");
            }
        });
    });
});