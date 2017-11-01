$(function () {
    var api = "./../api/";
    var info = $("p#info");
    info.hide();
    $('form#create').submit(function (e) {
        e.preventDefault();
        var title = $(this).find('input#title').val();
        $.ajax({
            type: 'POST',
            data: 'request=createBlog&title=' + title,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                if (data == 1) {
                    info.html("Successfully created your blog heading.");
                    $.ajax({
                        type: 'POST',
                        data: 'request=refreshBlogs',
                        url: api + 'index.php',
                        async: true,
                        success: function (data) {
                            $("form#newentry select").html(data);
                        },
                        error: function () {
                            alert("an error has occured!");
                        }
                    });
                } else {
                    info.html("Failed to create your blog heading.");
                }
                info.fadeIn("slow");
            },
            error: function () {
                alert("an error has occured!");
            }
        });
    });
});