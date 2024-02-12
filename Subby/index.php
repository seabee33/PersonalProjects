<?php 
require (__DIR__ . '/app/phpScripts/homeProcessor.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Subby</title>
	<meta name="description" content="Subby - Keep track of your subscriptions">
	<meta name="keywords" content="subby, subscription tracking">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<link rel="stylesheet" href="assets/styles.css?v=4">
	<script src="assets/jq.js"></script>

</head>
<body>


<div class="toastBox"></div>


<div class="registerScreen" id="registerBox">
	<div class="register">
		<div>
			<button onclick="closeRegisterWindow()" class="registerCloseButton">X</button>
		</div>
		<div>
			<div>Track where your money is running away to</div><br>
			<div>T&C coming soon...</div><br>

			<div id="registerMessages"></div><br>
	
			<form method="post" id="registerForm">
				<input type="hidden" name="action" value="register">
	
				<label for="">Email</label><br>
				<input name="registerEmail" type="email"><br><br>

				<label for="">First name</label><br>
				<input name="username" type="text"><br><br>
	
				<label for="">Password</label>
				<input name="registerPassword" type="password"><br><br><br>
	
				<button type="submit" style='cursor: pointer;'>Sign up</button>
			</form>
		</div>
	</div>
</div>



<div class="loginBox" id="loginBox">
	<div class="login">
		<div>
			<button onclick="closeLoginWindow()" class="loginCloseButton">X</button>
		</div>
		<div>
			<div>Track where your money is running away to</div><br>

			<div id="loginMessages"></div><br>
	
			<form method="post" id="loginForm">
				<input type="hidden" name="action" value="login">
	
				<label for="">Email</label><br>
				<input name="loginEmail" type="email"><br><br>
	
				<label for="">Password</label>
				<input name="loginPassword" type="password"><br><br><br>
	
				<button type="submit" style='cursor: pointer;'>Log In</button>
			</form>
		</div>
	</div>
</div>



<div class="navbarContainer">
	<div class="navbar" id="navbar">
		<div class="logo"><img src="assets/logo.png" alt="Logo"></div>
		<div class="links">
			<!-- <a href="#home">Home</a> -->
			<!-- <a href="#about">Features</a>
			<a href="#pricing">Pricing</a> -->
		</div>
		<div class="actionButton" id="actionButtons">
			<?php 
			if(isset($_SESSION['loggedIn'])){
				echo "<button onclick='goToApp()'>App</button>
				<button style='margin: 0px 20px 0px 20px;' onclick='logOut()'>Log Out</button>";
			} else {
				echo"<button onclick='showRegisterWindow()'>Register</button>
				<button style='margin: 0px 20px 0px 20px;' onclick='showLoginWindow()'>Log In</button>";
			}
			
			?>
		</div>
	</div>
</div>


<div class="homesplash" id="home">
	<div class="main">
		<div><span>Subby</span></div>
		<div><span id="services">Track your subscriptions</span></div>
	</div>
</div>


<div class="container centerChild">
	<div class="row">
		<div class="card2">
			<img class="card2Icon" src="https://kbcollective.com.au/public/box.svg" alt="">
			<span class="header3">Version: 24-0212-02 BETA</span>

		</div>

		<div class="card2">
			<img class="card2Icon" src="https://kbcollective.com.au/public/box.svg" alt="">
			<span class="header3">Still in development (obviously)</span>

		</div>

		<div class="card2">
			<img class="card2Icon" src="https://kbcollective.com.au/public/box.svg" alt="">
			<span class="header3">Sign up before beta ends and you will automatically be upgraded to a paid account for free </span>

		</div>
	</div>
</div>



</body>
<script src="assets/scripts.js"></script>
<?php
require(__DIR__ . '/app/phpScripts/verify.php');
?>
</html>
