<?php

if(isset($_SESSION['username'])){
	$usernameForHome = htmlspecialchars(ucfirst($_SESSION['username']));
} else {
	$usernameForHome = "";
}



$appMenu = "
<div class='leftMenu'>
	<div class='upperLeftSection'>
		<div class='logo'><img src='/assets/logo.png' alt='Logo' width='50'><pre> Subby</pre></div>
		<div class='userWelcome'>Welcome, " . $usernameForHome . " </div>
		<div class='menuItem'><a href='/app'>Dashboard</a></div>
		<div class='menuItem'><a href='/app/all'>View all subscriptions</a></div>
		<!-- <div class='menuItem'><a href=''>Share my subscriptions</a></div> -->
	</div>
	<div class='bottomLeftSection'>
		<div class='menuItem'><a href='/'>Home Site</a></div>
		<div class='menuItem'><a href='/app/help'>Help</a></div>
		<!-- <div class='menuItem'><a href='/app/settings'>Settings</a></div> -->
		<div class='logOutButton'><a href='/logout'>Log Out</a></div>
	</div>
</div>";
?>
