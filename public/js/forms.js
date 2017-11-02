$(function () {
    var api = "./api/";
    var label = $("label#info");
    var logout = $("input#logout");
    label.hide();
    logout.hide();

    $("form#loginForm").on("submit", function (e) {
        e.preventDefault()
        var email = $("input#loginemail").val();
        var password = $("input#loginpass").val();
        $(this).fadeOut("slow", function () {
            label.html("Submitting your login request...");
            label.fadeIn("slow");
            $.ajax({
                type: 'POST',
                data: 'request=login&email=' + email + '&password=' + password,
                url: api + 'index.php',
                async: true,
                success: function (data) {
                    if (data == 1) {
                        label.html("You are now logged in to your account with email " + email + "!");
                        logout.html("slow");
                    } else {
                        label.html("Invalid login details!");

                    }

                    label.fadeIn(5000, function () {
                        if (data == 1) {
                            logout.fadeIn("fast");
                            $("form#registerForm").fadeOut("slow");
                        } else {
                            label.fadeOut(5000, function () {
                                $("form#loginForm").fadeIn("slow");
                            });
                        }
                    });
                },
                error: function (exception) {
                    console.log('Exception:' + exception);
                }
            });
        });
        clearLogin();
    });

    $("form#registerForm").on("submit", function (e) {
        e.preventDefault();
        var email = $(this).find('input#regemail').val();
        var password = $(this).find('input#regpassword').val();
        var passwordconf = $(this).find('input#regpasswordconf').val();

        label.html("Submitting your login request...");
        label.fadeIn("slow");
        $.ajax({
            type: 'POST',
            data: 'request=register&email=' + email + '&password=' + password + '&passwordconf=' + passwordconf,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                label.html(data);
                label.fadeIn("slow");
            },
            error: function () {
                alert("an error has occured!");
            }
        });
        clearRegistration();
    });


    $('form#logout').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: 'request=logout&r=t',
            url: api + 'index.php',
            async: true,
            success: function (data) {
                label.fadeOut("fast", function () {
                    logout.fadeOut("fast", function () {
                        $("form#loginForm").fadeIn("fast");
                        $("form#registerForm").fadeIn("fast");
                    });
                });
            },
            error: function () {
                alert("error");
            }
        });
        clearLogin();
        clearRegistration();
    });

    function clearRegistration() {
        $("input#regpasswordconf").val("");
        $("input#regemail").val("");
        $("input#regpassword").val("");
    }

    function clearLogin() {
        $("input#loginemail").val("");
        $("input#loginpass").val("");
    }
});