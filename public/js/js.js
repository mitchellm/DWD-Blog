$(function () {
//    // Selecting the elements that are frequently accessed and the api path
    var api = "./api/";
    var loginArea = $("div#loginPanel");
    /**
     * Binds an event listener on the loginForm, captures the entered inputs on submit and fires this function
     */


    $("form#loginForm").on("submit", function (e) {
        e.preventDefault()
        var email = $("input#loginemail").val();
        var password = $("input#loginpass").val();
        //Fades out the login form, and calls a function that adds a "in progress" response to the page 
        //once the form is faded it makes an ajax call to determine if login is correct
        loginArea.fadeOut("slow", function () {
//            label.html("Submitting your login request...");
//            label.fadeIn("slow");
            //Ajax call to verify login details
            $.ajax({
                type: 'POST',
                data: 'request=login&email=' + email + '&password=' + password,
                url: api + 'index.php',
                async: true,
                success: function (response) {
                    if (response == 1) {
                        //API returned 1, ALL GOOD USER DETAILS CORRECT...
//                        label.html("You are now logged in to your account with email " + email + "!");
//                       logout.html("slow");
                        $("a#logoutButton").fadeIn("slow");
                    } else {
                        //API DIDNT RETURN 1, BAD LOGIN
                        loginArea.fadeIn("slow", function () {
                            $("a#logoutButton").fadeIn("slow");
                        });
                    }
                    //Fades in the response label, then when that completes it 
                    //changes the html based on success or fail of login.
//                    label.fadeIn(5000, function () {
//                        if (response == 1) {
//                            logout.fadeIn("fast");
//                            $("form#registerForm").fadeOut("slow");
//                        } else {
//                            label.fadeOut(5000, function () {
//                                $("form#loginForm").fadeIn("slow");
//                            });
//                        }
//                    });
                },
                error: function (exception) {
                    console.log('Exception:' + exception);
                }
            });
        });
        //clearLogin();
    });

    /**
     * Binds an event listener on the registerForm, captures the entered inputs on submit and fires this function
     */
    $("form#registerForm").on("submit", function (e) {
        e.preventDefault();
        var email = $(this).find('input#regemail').val();
        var password = $(this).find('input#regpassword').val();
        var passwordconf = $(this).find('input#regpasswordconf').val();

        //Fades out the register form, and calls a function that adds a "in progress" response to the page 
        //once the form is faded it makes an ajax call to determine if register info is valid
//        label.html("Submitting your login request...");
//        label.fadeIn("slow");
        //Ajax register call
        $.ajax({
            type: 'POST',
            data: 'request=register&email=' + email + '&password=' + password + '&passwordconf=' + passwordconf,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                alert(data);
                $("div#register").modal('hide');
                //Sets the response label to the API response and fades it in
//                label.html(data);
//                label.fadeIn("slow");
            },
            error: function () {
                alert("An error with the register form has occured!");
            }
        });
//        clearRegistration();
    });

    /**
     * Binds an event listener on the logout button, captures the entered inputs on submit and fires this function
     */

    $('a#logoutButton').on('click', function (e) {
        e.preventDefault();
        //Sends api request to logout and changes the page, impossible for logout to fail so no conditional
        $.ajax({
            type: 'POST',
            data: 'request=logout&r=t',
            url: api + 'index.php',
            async: true,
            success: function (response) {
                loginArea.fadeIn("slow");
                $("a#logoutButton").fadeOut("slow");
            },
            error: function () {
                alert("Error with logout!");
            }
        });
//        clearLogin();
//        clearRegistration();
    });
//
//    function clearRegistration() {
//        $("input#regpasswordconf").val("");
//        $("input#regemail").val("");
//        $("input#regpassword").val("");
//    }
//
//    function clearLogin() {
//        $("input#loginemail").val("");
//        $("input#loginpass").val("");
//    }
});