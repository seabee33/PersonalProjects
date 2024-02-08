<?php require("../functions.php"); loginCheck(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<script src='../../assets/jq.js'></script>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php name();?> / View All Subscriptions</title>
	<link rel="stylesheet" href="../assets/appstyles.css">
</head>
<body>

<div class="editScreen">
	<div class="editBox">

		<div class='split borderBottom1'>
			<div>Edit</div>
			<div class='closeButton1'>X</div>
		</div>

		<form id='editSubscriptionForm'>
			<input type="hidden" name="action" value=1>
			<div>
				<div class='niceBorder1'>
					<label for="">Subscription Name</label>
					<input type="text" style="padding:5px; border-radius:5px; border:1px solid grey;" name='addNewItemName'>
				</div>

				<div class='niceBorder1'>
					<label for="">Cost</label>
					<input type="number" class='specialNumerInput' style="padding:5px; border-radius:5px; border:1px solid grey; width:100%" placeholder='$' name='addNewPrice'>
				</div>

				<div class='niceBorder1'>
					<div style="display:flex; align-items:center;">
						<div style='width:100%;'>Repeating every</div>
						<input type="number" class='specialNumerInput' style="padding:5px; width:30px;" name='addNewFreqCount'>
						<div style="padding: 0px 5px;"></div>
						<select name='addNewFreq' style="width:90px; padding:5px;">
							<option value="1">Days</option>
							<option value="2">Weeks</option>
							<option value="3">Fortnight</option>
							<option value="4">Month</option>
							<option value="5">Quarter</option>
							<option value="6">Year</option>
						</select>
					</div>
				</div>

				<div class='niceBorder1'>
					<label for="">Next payment on</label>
					<input type="date" class='specialNumerInput' style="padding:5px; border-radius:5px; border:1px solid grey; width:100%" name='addNewPaymentDate'>
				</div>

				<div id='addNewMessageBox'></div>

				<button class="btn1" type='button' onclick='appAddNewSubscription()'>Save Changes</button>
				<button class="btn2" type='button' onclick='appAddNewSubscription()'>Cancel</button>
			</div> 
		</form>

	</div>
</div>


	<div class="appPage">

		<?php echo $appMenu; ?>

		<div class="core">

			<div class="split" style='max-width:1200px; margin: 0px auto;'>
				<div class="fontStyle1">All Upcoming Subscriptions</div>
				<?php require('../addButton.php'); ?>
			</div>

			<?php viewAllSubscriptions($conn); ?>

		</div>





	</div>
</body>
<script src='../assets/appScripts.js'></script>
</html>
