$(function () {
    var api = "./../";
    var info = $("p#info");
    info.hide();
    $('form#create').submit(function (e) {
        e.preventDefault();
        var title = $(this).find('input#title').val();
                    alert(api + "index.php");
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
    
    $('form#newentry').submit(function (e) {
        e.preventDefault();
        var title = $(this).find('input#title').val();
        var content = $(this).find('textarea#content').val();        
        var blogid = $(this).find('select#blogid option:selected').val();
        
        $.ajax({
            type: 'POST',
            data: 'request=createEntry&title=' + title +'&content='+content+'&blogid='+blogid,
            url: api + 'index.php',
            async: true,
            success: function (data) {
                if (data == 1) {
                    info.html("Successfully created your blog entry.");
                } else {
                    info.html("Failed to create your blog entry.");
                }
                info.fadeIn("slow");
            },
            error: function () {
                alert("an error has occured!");
            }
        });
    });
});