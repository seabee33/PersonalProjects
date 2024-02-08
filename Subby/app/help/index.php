<?php require("../functions.php"); loginCheck(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php name();?> / View All Subscriptions</title>
	<link rel="stylesheet" href="../assets/appstyles.css">
</head>
<body>
	<div class="appPage">

		<?php echo $appMenu; ?>

		<div class="core">

			<div class='fontStyle1'>Hello & Welcome to <?php name(); ?></div>
			<br>
			<div style='font-family:sans-serif;'>
			More features will be added in the future. In the mean time, please add your subscriptions.<br><br>

			To send me a message to add new features or have any questions, please send an email to <a href="mailto:xxxx">xxxxxxx</a>
			<br>
			</div>

			<!-- <form action="sendMsg.php" method='post'>
				<textarea style='background-color: rgba(255, 255, 255, 0.398); border-radius: 10px; padding:10px;' name="" id="" cols="60" rows="10"></textarea><br>
				<button type="submit" style='padding:10px;'>Submit</button>
			</form> -->

		</div>





	</div>
</body>
</html>
