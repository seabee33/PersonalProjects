<div class="addButton"><button class="btn1" id='addBtn'>Add New +</button>

	<form action="" method='post' id='addNewSubscriptionForm'>
		<input type="hidden" name="action" value=1>
		<div class="addMenu" id='addMenu' style='display:none'>
			<div style="text-align:center; margin: 10px 0px 0px 0px;" class="fontStyle2">Add New Subscription</div> <br>
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

			<button class="btn1" type='button' onclick='appAddNewSubscription()'>Add Subscription</button>
		</div> 
	</form>
</div>