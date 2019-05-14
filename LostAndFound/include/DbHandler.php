<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    /**
    * Fetching semua users
    */
    public function getAllUser(){
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id ASC");
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();

        return $tasks;
    }

    /**
    * Fetching semua users per username
    */
    public function getUserPerUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? ORDER BY id ASC");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }

    /**
     * Insert new users
     */
    public function insertUser($username, $password) {
        $tasks = $this->getUserPerUsername($username);
        $tasks = $tasks->fetch_assoc();
        if ($tasks["username"] == $username) {
            return "0";
        } else {
            $stmt = $this->conn->prepare("INSERT INTO users(username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $stmt->close();
            return "1";
        }
    }


    /**
     * Fetching semua announcements
     */
    public function getAllAnnouncements(){
        $stmt = $this->conn->prepare("SELECT * FROM announcements ORDER BY id ASC");
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();

        return $tasks;
    }

    /**
     * Fetching semua announcements per category
     */
    public function getAnnouncementsPerCategory($category) {
        $stmt = $this->conn->prepare("SELECT * FROM announcements WHERE category = ? ORDER BY id DESC");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }

    /**
     * Insert new announcement
     */
    public function insertAnnouncement($title, $body, $category, $user) {
        $stmt = $this->conn->prepare("INSERT INTO announcements(title, body, category, user) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $body, $category, $user);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Update a announcement
     */
    public function updateAnnouncement($title, $body, $category) {
        $stmt = $this->conn->prepare("UPDATE announcements SET (title, body, category) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $body, $category);
        $stmt->execute();
        $stmt->close();
    }

//  public function verifyRequiredParams($required_fields) {
//        $error = false;
//        $error_fields = "";
//        $request_params = array();
//        $request_params = $_REQUEST;
//        // Handling PUT request params
//        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
//            $app = \Slim\Slim::getInstance();
//            parse_str($app->request()->getBody(), $request_params);
//        }
//        foreach ($required_fields as $field) {
//            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
//                $error = true;
//                $error_fields .= $field . ', ';
//            }
//        }
//        if ($error) {
//            // Required field(s) are missing or empty
//            // echo error json and stop the app
//            $response = array();
//            $app = \Slim\Slim::getInstance();
//            $response["error"] = true;
//            $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
//            echoRespnse(400, $response);
//            $app->stop();
//        }
//  }
}

?>
