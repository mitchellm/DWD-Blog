$(function () {
// Selecting the elements that are frequently accessed and the api path
    var api = "./api/";
    var loginArea = $("div#loginPanel");
    /**
     * Binds an event listener on the loginForm, captures the entered inputs on submit and fires this function
     */

    function reloadArchive() {
        $.ajax({
            type: 'POST',
            data: 'request=getArchive',
            url: api + 'index.php',
            async: true,
            success: function (data) {
                $("div#prev").html(data);
            },
            error: function (exception) {
                console.log('Exception:' + exception);
            }
        });
    }

    $("form#newBlog").on("submit", function (e) {
        e.preventDefault();
        var title = $("input#title").val();
        var content = $("textarea#content").val();
        $.ajax({
            type: 'POST',
            data: 'request=createEntry&title=' + title + '&content=' + content,
            url: api + 'index.php',
            async: true,
            success: function (response) {
                if (response == 1) {
                    //API returned 1, ALL GOOD USER DETAILS CORRECT...
//                        label.html("You are now logged in to your account with email " + email + "!");
//                       logout.html("slow");
                    $("div#newpost").modal('hide');
                    reloadArchive();
                    $("div#prev").removeClass();
                    $("div#prev").addClass('collapse');
                    $("div#prev").addClass('show');
                } else {
                    alert("Failed, try again!");
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
                        $("a#logoutButton").fadeIn("slow", function () {
                            $("div#notice").fadeIn("slow");
                        });
                    } else {
                        //API DIDNT RETURN 1, BAD LOGIN
                        loginArea.fadeIn("slow", function () {
                            $("a#logoutButton").fadeOut("slow");
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
            success: function () {
                $("div#notice").fadeOut("slow", function () {
                    loginArea.fadeIn("slow", function () {
                        $("a#logoutButton").fadeOut("slow");
                    });
                });
            },
            error: function () {
                alert("Error with logout!");
            }
        });
//        clearLogin();
//        clearRegistration();
    });

    $(document).on('click', 'a#remove', function (e) {
        e.preventDefault();
        var data = $(this).attr('friendid');
        //Sends api request to logout and changes the page, impossible for logout to fail so no conditional
        $.ajax({
            type: 'POST',
            data: 'request=removeFriend&friendID=' + data,
            url: api + 'index.php',
            async: true,
            success: function () {
                document.href = "profile.php";
            },
            error: function () {
                alert("Error with logout!");
            }
        });
    });

    $(document).on('click', 'button#accbutton', function (e) {
        e.preventDefault();
        var data = $(this).attr('friendid');
        //Sends api request to logout and changes the page, impossible for logout to fail so no conditional
        $.ajax({
            type: 'POST',
            data: 'request=acceptRequest&friendID=' + data,
            url: api + 'index.php',
            async: true,
            success: function () {
                document.href = "profile.php";
            },
            error: function () {
                alert("Error with logout!");
            }
        });
    });

    $(document).on('click', 'button#decbutton', function (e) {
        e.preventDefault();
        var data = $(this).attr('friendid');
        //Sends api request to logout and changes the page, impossible for logout to fail so no conditional
        $.ajax({
            type: 'POST',
            data: 'request=declineRequest&friendID=' + data,
            url: api + 'index.php',
            async: true,
            success: function () {
                document.href = "profile.php";
            },
            error: function () {
                alert("Error with logout!");
            }
        });
    });

    $(document).on('click', 'h3#openarchive', function (e) {
        e.preventDefault();
        var data = $(this).attr('friendid');
        //Sends api request to logout and changes the page, impossible for logout to fail so no conditional
        $.ajax({
            type: 'POST',
            data: 'request=getArchiveByUID&user=' + data,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                $("div#prev").html(data);
            },
            error: function () {
                alert("Error with logout!");
            }
        });
    });

    $(document).on('click', 'a#showblog', function (e) {
        e.preventDefault();
        var data = $(this).attr('blogid');
        //Sends api request to logout and changes the page, impossible for logout to fail so no conditional
        $.ajax({
            type: 'POST',
            data: 'request=loadEntryByID&blog=' + data,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                $("div#content").html(data);
            },
            error: function () {
                alert("Error with logout!");
            }
        });
    });

    $(document).on('click', 'a#reloadown', function (e) {
        e.preventDefault();
        //Sends api request to logout and changes the page, impossible for logout to fail so no conditional
        $.ajax({
            type: 'POST',
            data: 'request=getArchive',
            url: api + 'index.php',
            async: true,
            success: function (data) {
                $("div#prev").html(data);
            },
            error: function () {
                alert("Error with logout!");
            }
        });
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