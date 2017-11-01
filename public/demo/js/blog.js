$(function () {
    var api = "./../api/";
    
    $('form#create').submit(function (e) {
        e.preventDefault();
        var title = $(this).find('input#title').val();
        var form = $(this);
        form.hide();
        $.ajax({
            type: 'POST',
            data: 'request=createBlog&title=' + title,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                form.html(data);
                form.fadeIn("slow");
            },
            error: function () {
                alert("an error has occured!");
            }
        });
    });
});