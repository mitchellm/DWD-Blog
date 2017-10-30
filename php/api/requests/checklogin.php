<?php
require_once('base.php');
if ($session->isLoggedIn()) {
?>
    You are logged in already!
    <form id="logout">
        <input type="submit" value="Logout" />
    </form>
    <?php
} else {
    die("0");
}
?>
<script type="text/javascript">
    var api = "./../api/";
    $('form#logout').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: 'request=logout',
            url: api + 'index.php',
            async: true,
            success: function (data) {
                var form = $('div#login');
                form.html(data);
            },
            error: function () {
                alert("an error has occured!");
            }
        });
    });
</script>