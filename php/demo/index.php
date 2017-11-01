<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="./js/forms.js"></script>
        <script src="./js/checklogin.js"></script>
        <meta charset="UTF-8">
        <title>register/login functional demo</title>
    </head>
    <body>
        <div id="loggedIn">         
            You are logged in already!
            <form id="logout">
                <input type="submit" value="Logout" />
            </form>
        </div>
        <div id="login">
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
        </div>
        <hr />
        <div id="register">
            <form id="register">
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
                        <td>
                            Password Conf:
                        </td>
                        <td>
                            <input type="password" name="passwordconf" id="passwordconf" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="REGISTER>>"  name="submit" />
                        </td>
                    </tr>                
                </table>
            </form>
        </div>
        <br/><br/><br/>
        This demo works by using jquery ajax calls to submit the form data to the backend, and then returns and displays the response from the backend.<br/>
        <b>NOTE: This is actually inserting records into the database if the input values pass the validation, so you need to register an account for the login form to work!</b>
    </body>
</html>
