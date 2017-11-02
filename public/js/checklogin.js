$(function () {
    var label = $("label#info");
    var logout = $("input#logout");
    var api = "./api/";
    $.ajax({
        type: 'POST',
        data: 'request=checklogin',
        url: api + 'index.php',
        async: true,
        success: function (data) {
            //success
            if (data == 1) {
                label.html("You are already logged in!");
                $("form#loginForm").fadeOut("slow", function () {
                    label.fadeIn("fast");
                    logout.fadeIn("fast");
                    $("form#registerForm").fadeOut("slow");
                });
            }
        },
        error: function () {
            alert("an error has occured!");
        }
    });
});