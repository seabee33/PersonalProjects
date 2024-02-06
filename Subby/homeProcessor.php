<?php
require("app/db.php");

if(isset($_GET['getAction'])){
	$getAction = $_GET['getAction'];

	if($getAction == 'logOut'){
		if(isset($_SESSION['loggedIn'])){
			$_SESSION['loggedIn'] = FALSE;
			session_unset();
			session_destroy();
			header("Location: /");
		}
	}
}

if(isset($_POST['action'])){
	$action = $_POST['action'];

	if($action == "register"){
		$registerEmail = $_POST['registerEmail'];
		$registerUsername = $_POST['username'];
		$registerPassword = $_POST['registerPassword'];

		$hashedPassword = password_hash($registerPassword, PASSWORD_DEFAULT);

		if($registerEmail != "" and $registerPassword != "" and $registerUsername != ""){
			if(strlen($registerPassword) >= 8){
				if(checkEmailDoesNotExist($conn, $registerEmail) == 0){
					echo json_encode(array("itemCode" => 1, "status" => "success", "message" => createNewUser($conn, $registerEmail, $registerUsername, $hashedPassword)));
				} else {
					echo json_encode(array("itemCode" => 2, "status" => "success", "message" => "Email already used"));
				}
			} else {
				echo json_encode(array("itemCode" => 3, "status" => "success", "message" => "Your password must be longer then 8 characters"));
			}
		} else {
			echo json_encode(array("itemCode" => 4, "status" => "success", "message" => "Please fill out all inputs"));
		}
	} elseif ($action == "login"){
		$loginEmail = $_POST['loginEmail'];
		$loginPassword = $_POST['loginPassword'];

		if($loginEmail != "" and $loginPassword != ""){
			$loginProcessor = loginProcessor($conn, $loginEmail, $loginPassword);

			if($loginProcessor == 1){
				echo json_encode(array("itemCode" => 5, "status" => "success", "message" => "No account found with that email"));
			} if($loginProcessor == 2) {
				echo json_encode(array("itemCode" => 6, "status" => "success", "message" => "Correct login"));
			} if($loginProcessor == 3){
				echo json_encode(array("itemCode" => 7, "status" => "success", "message" => "Incorrect password"));
			}
		} else {
			echo json_encode(array("itemCode" => 8, "status" => "success", "message" => "Please fill all fields"));
		}
	}
} else {
	header("Location: /");
}


function checkEmailDoesNotExist($conn, $email){
	$checker = $conn->prepare("SELECT email FROM users WHERE email=?");
	$checker->bind_param("s", $email);

	try {
		$checker->execute();
		$checker->store_result();
	
		if($checker->num_rows == 0){
			return 0;
		} else {
			return 1;
		}
	} finally {
		// $conn->close();
		// $checker->close();
	}
}

function createNewUser($conn, $email, $username, $password){

	$username = str_replace(['<', '>', '/', '"', "'"], '', $username);

	$addNewUser = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
	$addNewUser->bind_param("sss", $email, $username, $password);

	if($addNewUser->execute()){
		$conn->close();
		$addNewUser->close();
		return "success";
	} else {
		$conn->close();
		$addNewUser->close();
		return "error: " . $addNewUser->error;
	}
}



function loginProcessor($conn, $email, $password){
	$login = $conn->prepare("SELECT username, email, password, is_verified, user_type, id FROM users WHERE email=?");
	$login->bind_param("s", $email);

	try{
		$login->execute();
		$loginResult = $login->get_result();
	
		if($loginResult->num_rows == 0){
			return 1;
		} if($loginResult->num_rows == 1){
			$userData = $loginResult->fetch_assoc();
			$username = $userData['username'];
			$DBPassword = $userData['password'];
			$userType = $userData['user_type'];
			$userID = $userData['id'];
	
			if(password_verify($password, $DBPassword)){
				$_SESSION['loggedIn'] = TRUE;
				$_SESSION['username'] = $username;
				$_SESSION['userType'] = $userType;
				$_SESSION['userID'] = $userID;
				return 2;
			} else {
				return 3;
			}
		}
	} finally {
		$conn->close();
		$login->close();
	}
}


?>
