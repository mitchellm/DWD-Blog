<?php

/**
 * Session control
 *
 * @category   Class
 * @package    classes.Session
 * @author     Mitchell M. <mm11096@georgiasouthern.edu>
 * @version    Release: 1.0.5
 * @since      Class available since Release 1.0.0
 */
require_once __DIR__ . '/../config/global.php';

class Session {

    private static $self_instance;
    public $last_error;
    private $mysqli, $qb;

    /**
     * Constructs the class, setting the mysqli variable to the active connection
     * @author Mitchell M. 
     * @version 1.1.0
     */
    public function __construct($dbc) {
        $this->qb = QueryBuilder::getInstance();
        $this->mysqli = $dbc;
        $this->sid = isset($_SESSION['sid']) ? $_SESSION['sid'] : null;
        is_null($this->sid) ? null : $this->validate($this->sid, time());
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
        $password = md5($password . $email);
        $passwordconf = md5($passwordconf . $email);
        if (!$email) {
            $errors[] = "Email is not defined!";
        }
        if (!$password) {
            $errors[] = "Password is not defined!";
        }
        if (!$passwordconf) {
            $errors[] = "Password confirmation is not defined!";
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $errors[] = "Email address is invalid!";
        }
        if ($password != $passwordconf) {
            $errors[] = "The two passwords you entered do not match!";
        }
        if ($email) {
            $qry = $this->qb->start();
            $qry->select('*')->from('users')->where('email', '=', $email);
            $matching = $qry->numRows();
            if ($matching > 0) {
                $errors[] = "The e-mail address you supplied is already in use of another user!";
            }
        }
        if (!isset($errors)) {
            //register the account
            $qry = $this->qb->start();
            $qry->insert_into("users", array('email' => $email, 'password' => $password));
            $qry->exec();
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
        if ($this->userExists($email, $pass)) {
            $userid = $this->getUID($email);
            if ($this->exists($userid)) {
                $this->clear($userid);
            }
            $this->build($userid,$email);
            return 1;
        }
        return 0;
    }

    function userExists($email, $password) {
        $email = htmlspecialchars(mysqli_real_escape_string($this->mysqli, $email));
        $pass = md5($password . $email);
        $qry = $this->qb->start();
        $qry->select("*")->from("users")->where("email","=",$email)->where("password","=",$pass);
        if ($qry->recordsExist()) {
            return true;
        } else {
            return false;
        }
    }

    function exists($userid) {
        $qry = $this->qb->start();
        $qry->select("*")->from("sessions")->where("userid", "=",$userid);
        if ($qry->recordsExist()) {
            return true;
        }
        return false;
    }
    
    function isLoggedIn() {
        if(isset($this->sid)) 
            return true;
        return false;
    }

    function getUID($email) {
        $qry = $this->qb->start();
        $qry->select("userid")
                ->from("users")
                ->where("email", "=", $email);
        $result = $qry->get();
        return $result['userid'];
    }

    function build($userid, $email) {
        $sid = $this->generateRandID(16);
        $timestamp = time() + 60 * SESSION_LENGTH;
        
        $qry = $this->qb->start();
        $qry->insert_into("sessions", array('userid' => $userid, 'sid' => $sid, 'timestamp' => $timestamp));
        $qry->exec();
        
        $_SESSION['username'] = $email;
        $_SESSION['sid'] = $sid;
    }
    
    function validate($sid, $currentTime) {
        $timestamp = time();
        $sid = htmlentities(mysqli_real_escape_string($this->mysqli, $sid));
        $stmt = $this->mysqli->prepare("SELECT timestamp, userid FROM `sessions` WHERE `sid` = ?");
        $stmt->bind_param("s", $sid);
        $stmt->bind_result($timestamp, $uid);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows >= 1) {
            while ($stmt->fetch()) {
                if ($currentTime > $timestamp) {
                    $this->clear($sid);
                    return true;
                }
            }
        }
        $stmt->close();
        $updateClick = $this->mysqli->prepare("UPDATE `sessions` SET `lastclick` = ? WHERE sid = ?");
        $updateClick->bind_param("is", $timestamp, $sid);
        $updateClick->execute();
        $updateClick->close();
        return false;
    }

    function clearByUID($userid) {
        $this->mysqli->query("DELETE FROM sessions WHERE userid='{$userid}'");
    }
    
    function clear($sid) {
        $sid = mysqli_real_escape_string($sid);
        $this->mysqli->query("DELETE FROM sessions WHERE sid='{$sid}'");
        session_destroy();
        $this->redirect('index.php');
    }
    
    /**
     * Redirects the the specified location
     * @param string $location to redirect to
     */
    function redirect($location) {
        if (!headers_sent())
            header('Location: ' . $location);
        else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $location . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
            echo '</noscript>';
        }
        die();
    }

    /**
     * Generates a random ID with the length specified
     * @param int $length to use
     */
    function generateRandID($length) {
        return md5($this->generateRandStr($length));
    }

    /**
     * Generates a random string based on the length provided
     * @param int $length to use
     */
    function generateRandStr($length) {
        $randstr = "";
        for ($i = 0; $i < $length; $i++) {
            $randnum = mt_rand(0, 61);
            if ($randnum < 10) {
                $randstr .= chr($randnum + 48);
            } elseif ($randnum < 36) {
                $randstr .= chr($randnum + 55);
            } else {
                $randstr .= chr($randnum + 61);
            }
        }
        return $randstr;
    }
}

?>