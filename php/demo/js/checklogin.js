$(function() {
    var api = "./../api/";
    
    $.ajax({
        type: 'POST',
        data: 'request=checklogin',
        url: api + 'index.php',
        async: true,
        success: function (data) {
            //success
            var form = $('div#login');
            form.html(data);
        },
        error: function () {
            alert("an error has occured!");
        }
    });
})