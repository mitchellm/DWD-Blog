$(function() {
    var api = "./../api/";
    
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
})