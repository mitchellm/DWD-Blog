<?php

/**
 * Session control
 *
 * @category   Class
 * @package    classes.Session
 * @author     Mitchell M. <mm11096@georgiasouthern.edu>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
require_once __DIR__ . '/../config/global.php';

class Session {

    private static $self_instance;
    public $last_error;
    private $mysqli;

    /**
     * Constructs the class, setting the mysqli variable to the active connection
     * @author Mitchell M. 
     * @version 1.1.0
     */
    public function __construct($dbc) {
        $this->mysqli = $dbc;
        $this->sid = isset($_SESSION['sid']) ? $_SESSION['sid'] : null;
        $this->last_error = "No recorded error...";
    }

    /**
     * Destructs the class
     * @author Mitchell M. 
     * @version 0.7
     */
    public function __destruct() {
        
    }

    public static function getInstance($dbc) {
        if (!self::$self_instance) {
            self::$self_instance = new Session($dbc);
        }
        return self::$self_instance;
    }

    /**
     * Registers the user into the database
     * @author Mitchell M. 
     * @version 1.0
     */
    public function register($email, $password, $passwordconf) {
        $password = md5($password);
        $passwordconf = md5($passwordconf);
        if (!$email)
            $errors[] = "Email is not defined!";
        if (!$password)
            $errors[] = "Password is not defined!";
        if (!$passwordconf)
            $errors[] = "Password confirmation is not defined!";
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
            $errors[] = "Email address is invalid!";
        if ($password != $passwordconf)
            $errors[] = "The two passwords you entered do not match!";
        if ($email) {
            $stmt = $this->mysqli->prepare("SELECT * FROM `users` WHERE `email`= ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = "The e-mail address you supplied is already in use of another user!";
            }
            $stmt->close();
        }
        if (!isset($errors)) {
            //register the account
            $mysqli = $this->mysqli->prepare("INSERT INTO `users` (`email`, `password`) VALUES (?,?)");
            $mysqli->bind_param("ss", $email, $password);
            $mysqli->execute();
            $mysqli->close();
            echo "Registered successfully with email: " . $email . " and password: " . $password;
        } else {
            var_dump($errors);
        }
    }

    /**
     * Sets a users session in the database and sets their client side session
     * @author Mitchell M. 
     * @version 1.0
     */
    function login($email, $pass) {
        $email = htmlspecialchars(mysqli_real_escape_string($this->mysqli, $email));
        $pass = md5($pass);
        $stmt = $this->mysqli->prepare("SELECT * FROM `users` WHERE `email` = ? AND `password` = ?");
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "Successfully logged in to " . $email . "!";
            return true;
        } else {
            echo "Invalid credentials! Try again.";
        }
    }

    /**
     * Validates an active session
     * @author Mitchell M. 
     * @version 1.0
     */
    public function isLoggedIn() {
        return false;
    }

    /**
     * Destroys a session and logs a user out;
     * @author Mitchell M. 
     * @version 1.0
     */
    public function logout() {
        return false;
    }

}

?>