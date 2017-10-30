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
                //success
            var form = $('div#login');
                form.html(data);
            },
            error: function () {
                alert("an error has occured!");
            }
        });
    });
    </script>
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
    ?><form id="login">
                    <table>
                        <tr>
                            <td>
                            Email:
                        </td>
                        <td>
                            <input type="text" name="email" id="email" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Password:
                        </td>
                        <td>
                            <input type="password" name="password" id="password" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="LOGIN>>"  name="submit" />
                        </td>
                    </tr>                
                </table>
            </form>
<?php
}
?>