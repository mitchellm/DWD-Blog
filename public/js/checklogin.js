/**
 * This document ready function fires when the page is loaded, it checks the api to see if the user is logged in. 
 * If they are logged in, it hides the login/register forms and displays a message and a logout button.
 * @returns {undefined}
 */
$(function () {
    // Selecting the elements that are frequently accessed and the api path
    var api = "./api/";
    var logoutButton = $("a#logoutButton");
    //jQuery ajax call to the api
    $.ajax({
        type: 'POST',
        data: 'request=checklogin',
        url: api + 'index.php',
        async: true,
        success: function (response) {
            //If api returns 1
            if (response == 1) {
                $("div#loginPanel").fadeOut("slow", function () {
                    logoutButton.fadeIn("fast", function () {
                        $("div#notice").fadeIn("slow");
                    });
                });
            }
            //Otherwise do nothing
        },
        error: function () {
            alert("An error has occured while verifying logged-in status!");
        }
    });
});