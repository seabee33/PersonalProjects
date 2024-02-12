<?php
require('db.php');
require('toast.php');

if(isset($_GET['action'])){
	$action = $_GET['action'];
	if($action == 'verify'){

		$verifyCode = $_GET['verifyCode'];

		if(strlen($verifyCode) > 30 and strlen($verifyCode) < 34){
			$verifyCheck = $conn->prepare("SELECT id, is_verified FROM users WHERE is_verified=?");
			$verifyCheck->bind_param("s", $verifyCode);
	
			try{
				$verifyCheck->execute();
				$verifyCheckResult = $verifyCheck->get_result();
			
				if($verifyCheckResult->num_rows == 0){
					echo "<script>	$(document).ready(function(){
										toast('Verification code already used or not registered', 'rgb(201,105,16)', 'white', 8);		
									})</script>";
				} if($verifyCheckResult->num_rows == 1){
					$verifyData = $verifyCheckResult->fetch_assoc();
					$verifyCodeFromDB = $verifyData['is_verified'];
					$verifyID = $verifyData['id'];
					
					if($verifyCode == $verifyCodeFromDB){

						$updateUserVerifyStatus = $conn->prepare("UPDATE users SET is_verified='y' WHERE id=$verifyID");
						if($updateUserVerifyStatus->execute()){
							toast('Thank you, your email has been verified, you can now log in', 'green', 'white', 6);
						}
					} else {
						toast('Verification code not found', 'red', 'white', 6);
					}
	
				}
			} finally {
				if(isset($updateUserVerifyStatus)){
					$updateUserVerifyStatus->close();
				}
				if(isset($conn)){
					$conn->close();
				}
			}
		} else {
			toast('Incorrect verification code', 'red', 'white', 6);
		}
	}
}

?>
