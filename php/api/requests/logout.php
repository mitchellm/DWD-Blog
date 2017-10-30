<?php
require_once('base.php');
$session->clear($_SESSION['sid']);
?>
You are logged out!
<form id="login">
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
