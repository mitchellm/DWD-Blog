<?php

/**
 * Session control
 * @category   Class
 * @package    classes.Session
 * @author     Mitchell M. <mm11096@georgiasouthern.edu>
 * @version    Release: 1.0.5
 * @since      Class available since Release 1.0.0
 */
require_once __DIR__ . '/../config/global.php';

class Session {

    private static $self_instance;
    private $mysqli, $qb;
    public $sid;

    /**
     * Constructs the class, setting the mysqli variable to the active connection
     * @author Mitchell M.
     * @version 1.1.0
     */
    public function __construct($dbc) {
        $this->qb = QueryBuilder::getInstance();
        $this->mysqli = $dbc;

        //Determines if the user has a session id set
        $this->sid = isset($_SESSION['sid']) ? $_SESSION['sid'] : null;
        if ($this->sid != null) {
            //Sets the current loggedIn status and validates any session in the browser
            $this->validate($this->sid, time());
        }
    }

    /**
     * Destructs the class
     * @author Mitchell M.
     * @version 0.7
     */
    public function __destruct() {
        
    }

    /*
     * Singleton getInstance
     * Returns an instance of the session object that
     * will utilize the the passed database object for queries
     */

    public static function getInstance($dbc) {
        if (!self::$self_instance) {
            self::$self_instance = new Session($dbc);
        }
        return self::$self_instance;
    }

    /**
     * Validates if a session is valid, and clears it if not
     * @param type $sid
     * @param type $currentTime
     * @return boolean
     */
    function validate($sid, $currentTime) {
        $sid = htmlentities(mysqli_real_escape_string($this->mysqli, $sid));
        $stmt = $this->mysqli->prepare("SELECT timestamp, userid FROM `sessions` WHERE `sid` = ?");
        $stmt->bind_param("s", $sid);
        $stmt->bind_result($timestamp, $uid);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows >= 1) {
            while ($stmt->fetch()) {
                //stored timestamp is the logintime + allowed session length
                //checking to see if we have passed that time
                if ($currentTime > $timestamp) {
                    $this->clear($sid);
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            if (isset($_SESSION['sid'])) {
                $this->clear($sid);
            }
        }
        $stmt->close();
    }

    /**
     * Is a user logged in?
     * @return type
     */
    function isLoggedIn() {
        return isset($_SESSION['sid']);
    }

    /**
     * Returns the UID based on email/sid input
     * @param type $input
     * @return type
     */
    function getUID($input) {
        $qry = $this->qb->start();
        $qry->select("userid");
        if (filter_var($input, FILTER_VALIDATE_EMAIL) == true) {
            $qry->from("users")
                    ->where("email", "=", $input);
            $result = $qry->get();
        } else {
            $qry->from("sessions")
                    ->where("sid", "=", $input);
            $result = $qry->get();
        }

        return isset($result[0]['userid']) ? $result[0]['userid'] : -1;
    }

    function uid() {
        $qry = $this->qb->start();
        $qry->select("userid")
                ->from("sessions")
                ->where("sid", "=", $this->sid);
        $result = $qry->get();

        return isset($result[0]['userid']) ? $result[0]['userid'] : -1;
    }

    /**
     * Clear session based on UserID
     * @param type $userid
     * @return boolean
     */
    function clearByUID($userid) {
        if ($this->mysqli->query("DELETE FROM sessions WHERE userid='{$userid}'")) {
            return true;
        } else {
            return $this->mysqli->error;
        }
        unset($_SESSION['sid']);
    }

    /**
     * Clear session based on SID
     * @param type $sid
     */
    function clear($sid) {
        $sid = mysqli_real_escape_string($this->mysqli, $sid);
        $this->mysqli->query("DELETE FROM sessions WHERE sid='{$sid}'");
        unset($_SESSION['sid']);
    }

    /**
     * Registers the user into the database
     * @author Mitchell M.
     * @version 1.0
     */
    public function register($email, $password, $passwordconf) {
        $display = $password;
        $password = md5($password . $email);
        $passwordconf = md5($passwordconf . $email);

        //An array of checks that add elements to an error array as they occur.
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

        //Did we encounter errors?
        if (!isset($errors)) {
            //No errors, register the account
            $qry = $this->qb->start();
            $qry->insert_into("users", array('email' => $email, 'password' => $password));
            $qry->exec();
            return json_encode("Registered successfully with email: " . $email . " and password: " . $display);
        } else {
            //There were errors, return them
            return json_encode($errors);
        }
    }

    /**
     * Sets a users session in the database and sets their client side session
     * @author Mitchell M.
     * @version 1.0
     */
    function login($email, $pass) {
        //Does the user exist?
        if ($this->userExists($email, $pass)) {
            //User exists, get their userID for session creation
            $userid = $this->getUID($email);
            if ($this->handleSID($userid)) {
                //Create a session with the user's ID
                return 1;
            }
        }
        return 0;
    }

    /*
     * Validates that the login details are valid
     */

    function userExists($email, $password) {
        $email = htmlspecialchars(mysqli_real_escape_string($this->mysqli, $email));
        $pass = md5($password . $email);
        $qry = $this->qb->start();
        $qry->select("*")->from("users")->where("email", "=", $email)->where("password", "=", $pass);
        if ($qry->recordsExist()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Manages sessions and prevents more than one session per user
     */

    public function handleSID($userid) {
        //Does a session already exist for this userID?
        if ($this->exists($userid)) {
            //Session exists, clear it...
            if (!$this->clearByUID($userid)) {
                //Couldnt clear the session, return a json element containing the error
                return json_encode("Couldn't clear SID when creating new session.");
            }
        }
        //Creates the session with the specific userid
        if ($this->buildSID($userid)) {
            return true;
        }
        return false;
    }

    /**
     * Does a session exist for the UserID passed
     * @param type $userid
     * @return boolean
     */
    function exists($userid) {
        $qry = $this->qb->start();
        $qry->select("*")->from("sessions")->where("userid", "=", $userid);
        if ($qry->recordsExist()) {
            return true;
        }
        return false;
    }

    /**
     * Creates a session entry into the database and on the client machine
     * @param type $userid
     * @return int
     */
    function buildSID($userid) {
        $sid = $this->generateRandID(16);
        $time = time();
        $timestamp = $time + 60 * SESSION_LENGTH;

        $qry = $this->qb->start();
        $qry->insert_into("sessions", array('userid' => $userid, 'sid' => $sid, 'timestamp' => $timestamp));
        if ($qry->exec()) {
            $_SESSION['sid'] = $sid;
            return 1;
        }
        return 0;
    }

    /**
     * Posts a new blog/folder to the database under some user account
     */
    public function createEntry($title, $content) {
        if ($this->isLoggedIn()) {
            //Gets the metadata for the entry
            $uid = $this->getUID($this->sid);
            $date = new DateTime();
            $date->setTimestamp(time());
            $postDate = $date->format('Y-m-d');
            $author = $this->uid();
            //Parent blog exists, insert the blog entry
            $qry = $this->qb->start();
            $qry->insert_into("blog", array("title" => $title, "timestamp" => $postDate, "content" => $content, "author" => $author));
            if ($qry->exec()) {
                //Success
                return 1;
            } else {
                //Fail, return json element containing the error
                return json_encode($qry->lastError());
            }
        }
        return 0;
    }

    public function getFriends() {
        $friends = array();
        if ($this->isLoggedIn()) {
            $uid = $this->getUID($this->sid);

            $stmt = $this->mysqli->prepare("SELECT userA, userB FROM friends WHERE userA = ? OR userB = ?");
            $stmt->bind_param("ii", $uid, $uid);
            $stmt->bind_result($userA, $userB);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    if ($userA != $uid) {
                        array_push($friends, $userA);
                    } else if ($userB != $uid) {
                        array_push($friends, $userB);
                    }
                }
            }
            return $friends;
        }
        return 0;
    }

    public function getPendingRequests() {
        if ($this->isLoggedIn()) {
            $pendingRequests = array();
            //Gets uid and selects all blogs under that author
            $uid = $this->getUID($this->sid);
            $stmt = $this->mysqli->prepare("SELECT `sender` FROM `friend_requests` WHERE `recipent` = ?");
            $stmt->bind_param("i", $uid);
            $stmt->bind_result($sender);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    $pendingRequests[] = $sender;
                }
            }
            return $pendingRequests;
        }
        return 0;
    }

    public function acceptRequest($requesterID) {
        if ($this->isLoggedIn()) {
            $uid = $this->getUID($this->sid);
            $qry = $this->mysqli->query("SELECT * FROM `friend_requests` WHERE `sender` = {$requesterID}");
            if ($qry->num_rows > 0) {
                //Success
                $del = $this->mysqli->query("DELETE FROM friend_requests WHERE sender='{$requesterID}' and recipent='{$uid}'");
                if ($del) {
                    $insert = $this->qb->start();
                    $insert->insert_into("friends", array("userA" => $uid, "userB" => $requesterID));
                    if ($insert->exec()) {
                        return 1;
                    }
                } else {
                    return $this->mysqli->error;
                }
            } else {
                //Fail, return json element containing the error
                return json_encode($qry->error());
            }
        }
        return 0;
    }

    public function createRequest($friendID) {
        if ($this->isLoggedIn()) {
            $uid = $this->getUID($this->sid);
            $qry = $this->qb->start();
            $qry->select("*")->from("friend_requests")->where("sender", "=", $uid)->where("recipent", "=", $friendID);
            //If this request has not been made yet
            if ($qry->numRows() == 0) {
                $qry = $this->qb->start();
                $qry->insert_into("friend_requests", array("sender" => $uid, "recipent" => $friendID));
                if ($qry->exec()) {
                    //Success
                    return 1;
                } else {
                    //Fail, return json element containing the error
                    return json_encode($qry->lastError());
                }
            }
        }
        return 0;
    }

    public function deleteFriendRequest($requesterID) {
        if ($this->isLoggedIn()) {
            $uid = $this->getUID($this->sid);
            $qry = $this->mysqli->query("SELECT * FROM `friend_requests` WHERE `sender` = {$requesterID}");
            if ($qry->num_rows > 0) {
                //Success
                $del = $this->mysqli->query("DELETE FROM friend_requests WHERE sender='{$requesterID}' and recipent='{$uid}'");
                if ($del) {
                    return 1;
                } else {
                    return $this->mysqli->error;
                }
            } else {
                //Fail, return json element containing the error
                return json_encode($qry->lastError());
            }
        }
        return 0;
    }

    public function deleteFriend($friend) {
        if ($this->isLoggedIn()) {
            $uid = $this->getUID($this->sid);
            $qry = $this->mysqli->query("SELECT * FROM `friends` WHERE (`userB` = '{$friend}' AND `userA` = '{$uid}') OR (`userA` = '{$friend}' AND `userB` = '{$uid}')");
            if ($qry->num_rows > 0) {
                //Success
                $del = $this->mysqli->query("DELETE FROM friends WHERE (`userB` = '{$friend}' AND `userA` = '{$uid}') OR (`userA` = '{$friend}' AND `userB` = '{$uid}')");
                if ($del) {
                    return 1;
                } else {
                    return $this->mysqli->error;
                }
            } else {
                //Fail, return json element containing the error
                return json_encode($qry->error);
            }
        }
        return 0;
    }

    public function userSearch($search) {
        if ($this->isLoggedIn()) {
            $qry = $this->qb->start();
            $qry->select("email")->from("users")->where("email", "=", "%" + $search + "%");
            return $qry->get();
        }
    }

    public function lookupUsername($userid) {
        $stmt = $this->mysqli->prepare("SELECT `email` FROM `users` WHERE `userid` = ?");
        $stmt->bind_param("i", $userid);
        $stmt->bind_result($email);
        $stmt->execute();
        while ($stmt->fetch()) {
            return $email;
        }
        return null;
    }

    /**
     * Pulls an array of the blogs for the logged in user
     * @return type
     */
    public function getArchive() {
        if ($this->isLoggedIn()) {
            //Gets uid and selects all blogs under that author
            $uid = $this->getUID($this->sid);
            $qry = $this->qb->start();
            $qry->select(array("title", "blogid", "author"))->from("blog")->where("author", "=", $uid);
            $blogs = $qry->get();
            return $blogs;
        }
    }

    public function getLatestEntry() {
        $stmt = $this->mysqli->query("SELECT `title`, `blogid`, `author`, `content` FROM `blog` ORDER BY blogid DESC");
        $blog = $stmt->fetch_assoc();
        $blog['author'] = $this->lookupUsername($blog['author']);
        return $blog;
    }

    public function getUserLatestEntry($userid) {
        if (intval($userid)) {
            $stmt = $this->mysqli->prepare("SELECT `title`, `blogid`, `author`, `content` FROM `blog` WHERE `author` = ? ORDER BY blogid DESC LIMIT 1");
            $stmt->bind_param("i", $userid);
            $stmt->bind_result($title, $blogid, $author, $content);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    $entry = array('title' => $title, 'author' => $this->lookupUsername($author), 'blogid' => $blogid, 'content' => $content);
                }
                return $entry;
            }
        }
        return null;
    }

    /**
     * Pulls an array of the blogs for the logged in user
     * @return type
     */
    public function getArchiveByUID($uid) {
        //Gets uid and selects all blogs under that author
        $qry = $this->qb->start();
        $qry->select(array("title", "blogid"))->from("blog")->where("author", "=", $uid);
        return $qry->get();
    }

    public function loadEntryByID($id) {
        $entry = array();
        $stmt = $this->mysqli->prepare("SELECT `title`, `author`, `content`, `timestamp` FROM `blog` WHERE `blogid` = ?");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($title, $author, $content, $timestamp);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            while ($stmt->fetch()) {
                $entry['title'] = $title;
                $entry['author'] = $author;
                $entry['content'] = $content;
                $entry['timestamp'] = $timestamp;
            }
            return $entry;
        }
        return 0;
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
