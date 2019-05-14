<?php

require_once 'include/DbHandler.php';
require_once 'include/PassHash.php';
require 'libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/**
 * ----------- METHODS WITHOUT AUTHENTICATION ---------------------------------
 */

//users
$app->get('/allUser', function () {
	$response = array();

	$db = new DbHandler();

	// fetching all user
	$result = $db->getAllUser();
	//print_r($result);

	$response["error"] = false;
	$response["users"] = array();

	// looping through result and preparing materi array
	while ($strData = $result->fetch_assoc()) {
	    $tmp = array();
	    $tmp["id"] = utf8_encode($strData["id"]);
	    $tmp["username"] = utf8_encode($strData["username"]);
	    $tmp["password"] = utf8_encode($strData["password"]);

	    array_push($response["users"], $tmp);
	}

	echoRespnse(200, $response);
});

$app->get('/userPerUsername', function() use ($app) {
	// check for required params
	//	verifyRequiredParams(array('username'));
	$response = array();
	// reading post params
	$username = $app->request->get('username');

	$db = new DbHandler();

	// fetching all user per username
	$result = $db->getUserPerUsername($username);
	$response["error"] = false;
	$response["users"] = array();

	// looping through result and preparing materi array
	while ($strData = $result->fetch_assoc()) {
		$tmp = array();
		$tmp["id"] = utf8_encode($strData["id"]);
		$tmp["username"] = utf8_encode($strData["username"]);
		$tmp["password"] = utf8_encode($strData["password"]);

		array_push($response["users"], $tmp);
	}
	echoRespnse(200, $response);
});

$app->post('/insertUser', function() use ($app) {
	// check for required params
	//	verifyRequiredParams(array('username'));
	$response = array();
	// reading post params
	$username = $app->request->post('username');
	$password = $app->request->post('password');

	$db = new DbHandler();

	// We have to check if the username is present before we insert it
	$result = $db->insertUser($username, $password);
	$response["error"] = false;
	$response["is_work"] = $result;
	if ($result == 1) {
		$response["message"] = "Registered successfully";
	} else {
		$response["message"] = "This user is already exists";
	}
	echoRespnse(200, $response);
});

//announcements
$app->get('/allAnnouncements', function () {
	$response = array();

	$db = new DbHandler();

	// fetching all announcements
	$result = $db->getAllAnnouncements();
	//print_r($result);


	$response["error"] = false;
	$response["announcements"] = array();

	// looping through result and preparing materi array
	while ($strData = $result->fetch_assoc()) {
	    $tmp = array();
	    $tmp["id"] = utf8_encode($strData["id"]);
	    $tmp["title"] = utf8_encode($strData["title"]);
	    $tmp["body"] = utf8_encode($strData["body"]);
	    $tmp["category"] = utf8_encode($strData["category"]);
	    $tmp["user"] = utf8_encode($strData["user"]);

	    array_push($response["announcements"], $tmp);
	}

	echoRespnse(200, $response);
});

$app->get('/announcementsPerCategory', function() use ($app) {
	// check for required params
	//	verifyRequiredParams(array('category'));
	$response = array();
	// reading post params
	$category = $app->request->get('category');

	$db = new DbHandler();

	// fetching all announcements per category
	$result = $db->getAnnouncementsPerCategory($category);
	$response["error"] = false;
	$response["announcements"] = array();

	// looping through result and preparing materi array
	while ($strData = $result->fetch_assoc()) {
		$tmp = array();
		$tmp["id"] = utf8_encode($strData["id"]);
		$tmp["title"] = utf8_encode($strData["title"]);
		$tmp["body"] = utf8_encode($strData["body"]);
		$tmp["category"] = utf8_encode($strData["category"]);
		$tmp["user"] = utf8_encode($strData["user"]);

		array_push($response["announcements"], $tmp);
	}
	echoRespnse(200, $response);
});

$app->post('/insertAnnouncement', function() use ($app) {
	// check for required params
	//	verifyRequiredParams(array('title'));
	//	verifyRequiredParams(array('body'));
	//	verifyRequiredParams(array('category'));
	//	verifyRequiredParams(array('user'));
	$response = array();
	// reading post params
	$title = $app->request->post('title');
	$body = $app->request->post('body');
	$category = $app->request->post('category');
	$user = $app->request->post('user');

	$db = new DbHandler();

	$result = $db->insertAnnouncement($title, $body, $category, $user);
	$response["error"] = false;
	echoRespnse(200, $response);
});

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 * Daftar response
 * 200	OK
 * 201	Created
 * 304	Not Modified
 * 400	Bad Request
 * 401	Unauthorized
 * 403	Forbidden
 * 404	Not Found
 * 422	Unprocessable Entity
 * 500	Internal Server Error
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

	//print_r($response);
    echo json_encode($response);
}


$app->run();
?>
