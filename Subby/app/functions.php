<?php
require 'db.php';
require 'appMenu.php';

function printFrequencyOptions($conn){
	$options = $conn->query("SELECT id, frequency FROM payment_frequency_table");
	if($options){
		while($row = $options->fetch_assoc()){
			echo "<option value=" . $row['id'] . ">" . $row['frequency'] . "</option>";
		}
	}
}


function loginCheck(){
	if(isset($_SESSION['loggedIn'])){
		if($_SESSION['loggedIn'] != TRUE){
			header("Location: /");
		}
	} else {
		header("Location: /");
	}
}


function name(){
	echo "Subby";
}


function viewAllSubscriptions($conn){

	$listItems = ['itemID', 'itemName' => [], 'itemPrice' => [], 'frequency' => [], 'futureDate' => [], 'frequencyCount' => [], 'countdownDays'];

	$allSubscriptions = $conn->prepare("SELECT id, next_payment_date, custom_item_name, item_price, payment_frequency, payment_frequency_count FROM user_items WHERE user_items.created_by_user=?");
	$allSubscriptions->bind_param("s", $_SESSION['userID']);
	$allSubscriptions->execute();
	$result = $allSubscriptions->get_result();

	if($result->num_rows != 0){

		while ($rowData = $result->fetch_assoc()){
			$subID = $rowData['id'];
			$rawPaymentDate = $rowData['next_payment_date'];
			$subName = $rowData['custom_item_name'];
			$subPrice = '$' . number_format($rowData['item_price'], 2);
			$paymentFrequency = $rowData['payment_frequency'];
			$paymentFrequencyCount = $rowData['payment_frequency_count'];

			$listItems['itemID'][] = $subID;
			$listItems['itemName'][] = $subName;
			$listItems['itemPrice'][] = $subPrice;
			$listItems['frequency'][] = formatFrequency($paymentFrequency);
			$listItems['frequencyCount'][] = $paymentFrequencyCount;
			$listItems['futureDate'][] = futureDate($rawPaymentDate, $paymentFrequency, $paymentFrequencyCount);
			$listItems['countdownDays'][] = countdownDays($rawPaymentDate, $paymentFrequency, $paymentFrequencyCount);
		} 

		$countdownFromArray = $listItems['countdownDays'];
		array_multisort($countdownFromArray, $listItems['futureDate'], $listItems['frequency'], $listItems['frequencyCount'], $listItems['itemPrice'], $listItems['itemName'], $listItems['itemID']);

		echo "<table id='allSubsTable' class='toBlur' style='border-spacing: 0px 10px; text-align: left; width:100%; max-width:1200px; margin: 0px auto; font-family:sans-serif;'>
					<tr>
						<th>Subscription</th>
						<th>Frequency</th>
						<th>Next Payment Date</th>
						<th>Countdown</th>
						<th style='padding:0px 0px 0px 20px;'>Options</th>
					</tr>";

		foreach($countdownFromArray as $index => $countdown){

			if($countdown == 0){
				$countdown = "Today";
			}elseif($countdown == 1){
				$countdown = "Tomorrow";
			} else {
				$countdown = $countdown . " days";
			}

			$sBit = "";
			if($listItems['frequencyCount'][$index] != 1){
				$sBit = "s";
			}

			echo "
				<tr class='allItemsRow'>
					<td>" . $listItems['itemName'][$index] . "</td>
					<td>" . $listItems['itemPrice'][$index] . " / " . $listItems['frequencyCount'][$index] . " " . $listItems['frequency'][$index] . $sBit . " </td>
					<td>" . $listItems['futureDate'][$index] . "</td>
					<td>" . $countdown . "</td>
					<td class='optionsCell' style='padding: 0px 0px 0px 20px;'> 
						<img src='../assets/cog.png' alt='options' height='40px' class='optionsCog'> 
						<div class='optionsDropDown'>
							<button class='optionEdit'>Edit</button> <br>
							<button class='optionDelete' value='" . $listItems['itemID'][$index] . "'>Delete</button>
						</div>
					</td>
				</tr>
				";
		}
		echo '</table>';
	} else {
		echo "<tr class='allItemsRow'><td>No subscriptions added yet</td> <td></td> <td></td> <td></td></tr>";
	}
}



function countAllUserSubscriptions($conn){
	$totalSubCountSTMT = $conn->prepare("SELECT COUNT(*) AS count FROM user_items WHERE created_by_user=?");
	$totalSubCountSTMT->bind_param("s", $_SESSION['userID']);
	$totalSubCountSTMT->execute();
	$totalSubCountSTMT->bind_result($totalSubCount);
	$totalSubCountSTMT->fetch();
	$totalSubCountSTMT->close();

	echo $totalSubCount;
}



function futureDate($DBDate, $frequency, $frequencyCount){

	if(!is_numeric($frequencyCount) or !is_numeric($frequency)){
		return "error";
	}

	$rawPaymentDateForCountdown = new DateTime($DBDate);
	$currentDate = new DateTime();
	$currentDate->modify('-1 day');

	$newDate = clone $rawPaymentDateForCountdown;

	switch($frequency){
		case 1:
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($newDate->format('Y-m-d'))){
				$newDate->modify("+$frequencyCount day");
			}
			break;
		case 2:
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($newDate->format('Y-m-d'))){
				$newDate->modify("+$frequencyCount week");
			}
			break;
		case 3:
			$frequencyCount = 2 * $frequencyCount;
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($newDate->format('Y-m-d'))){
				$newDate->modify("+$frequencyCount weeks");
			}
			break;
		case 4:
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($newDate->format('Y-m-d'))){
				$newDate->modify("+$frequencyCount month");
			}
			break;
		case 5:
			$frequencyCount = 3 * $frequencyCount;
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($newDate->format('Y-m-d'))){
				$newDate->modify("+$frequencyCount months");
			}
			break;
		case 6:
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($newDate->format('Y-m-d'))){
				$newDate->modify("+$frequencyCount year");
			}
			break;
	}

	$newDate = $newDate->format('j F Y');
	return $newDate;
}



function countdownDays($DBDate, $frequency, $frequencyCount){
	$rawPaymentDateForCountdown = new DateTime($DBDate);
	$currentDate = new DateTime();
	$currentDate->modify('-1 day');

	$countdownDays = clone $rawPaymentDateForCountdown;

	switch($frequency){
		case 1:
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($countdownDays->format('Y-m-d'))){
				$countdownDays->modify("+$frequencyCount day");
			}
			break;
		case 2:
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($countdownDays->format('Y-m-d'))){
				$countdownDays->modify("+$frequencyCount week");
			}
			break;
		case 3:
			$frequencyCount = $frequencyCount * 2;
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($countdownDays->format('Y-m-d'))){
				$countdownDays->modify("+$frequencyCount weeks");
			}
			break;
		case 4:
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($countdownDays->format('Y-m-d'))){
				$countdownDays->modify("+$frequencyCount month");
			}
			break;
		case 5:
			$frequencyCount = $frequencyCount * 3;
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($countdownDays->format('Y-m-d'))){
				$countdownDays->modify("+$frequencyCount months");
			}
			break;
		case 6:
			while(strtotime($currentDate->format('Y-m-d')) >= strtotime($countdownDays->format('Y-m-d'))){
				$countdownDays->modify("+$frequencyCount year");
			}
			break;
		default:
			return "error";
	}
	$countdownDays = $currentDate->diff($countdownDays)->format('%a');

	return $countdownDays;
}




function formatFrequency($paymentFrequency){
	switch($paymentFrequency){
		case 1:
			return "Day";
			break;
		case 2:
			return "Week";
			break;
		case 3:
			return "Fortnight";
			break;
		case 4:
			return "Month";
			break;
		case 5:
			return "Quarter";
			break;
		case 6:
			return "Year";
			break;
		default:
			return "Error";
	}
}



class coreFunctions{
	
	private $totalLeftToPayThisMonth = 0;
	private $totalForThisMonth = 0;
	private $totalLeftForThisYear = 0;
	private $totalForThisYear = 0;

	public function totalLeftToPayThisMonth($conn){

		$this->totalLeftToPayThisMonth = 0;
		$this->totalForThisMonth = 0;
		$this->totalLeftForThisYear = 0;
		$this->totalForThisYear = 0;

		$query = $conn->prepare("SELECT item_price, payment_frequency, payment_frequency_count, next_payment_date FROM user_items WHERE created_by_user=?");
		try{
			if($query){

				$query->bind_param("s", $_SESSION['userID']);
				$query->execute();
				$result = $query->get_result();
		
				$currentDate = new DateTime();
				$nextMonthDate = new DateTime('first day of next month');
				$thisMonthFirstDate = new DateTime('first day of this month');
				$firstDayOfNextYear = new DateTime('first day of next year');
				$firstDayOfThisYear = new DateTime('first day of this year');
				$thisMonthFirstDate->format('Y-m-d');
				$nextMonthDate->format('Y-m-d');

		
				while($rowData = $result->fetch_assoc()){

					$itemPrice = $rowData['item_price'];
					$paymentFrequency = $rowData['payment_frequency'];
					$paymentFrequencyCount = $rowData['payment_frequency_count'];
					$nextPaymentDate = new DateTime($rowData['next_payment_date']);
		
					while($nextPaymentDate <= $firstDayOfNextYear){
						switch($paymentFrequency){
							case 1:	
								// Add days
								if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalLeftToPayThisMonth += $itemPrice;
								}if($nextPaymentDate >= $thisMonthFirstDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalForThisMonth += $itemPrice;
								}if($nextPaymentDate >= $firstDayOfThisYear and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalForThisYear += $itemPrice;
								}if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalLeftForThisYear += $itemPrice;
								}
								$nextPaymentDate->modify("+$paymentFrequencyCount days");
								break;
							case 2:
								// Add weeks
								if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalLeftToPayThisMonth += $itemPrice;
								}if($nextPaymentDate >= $thisMonthFirstDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalForThisMonth += $itemPrice;
								}if($nextPaymentDate >= $firstDayOfThisYear and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalForThisYear += $itemPrice;
								}if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalLeftForThisYear += $itemPrice;
								}								
								$nextPaymentDate->modify("+$paymentFrequencyCount weeks");
								break;
							case 3:
								// Add fortnights
								$paymentFrequencyCount = $paymentFrequencyCount * 2;
								if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalLeftToPayThisMonth += $itemPrice;
								}if($nextPaymentDate >= $thisMonthFirstDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalForThisMonth += $itemPrice;
								}if($nextPaymentDate >= $firstDayOfThisYear and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalForThisYear += $itemPrice;
								}if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalLeftForThisYear += $itemPrice;
								}	
								$nextPaymentDate->modify("+$paymentFrequencyCount weeks");
									break;
							case 4:
								// Add months
								if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalLeftToPayThisMonth += $itemPrice;
								}if($nextPaymentDate >= $thisMonthFirstDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalForThisMonth += $itemPrice;
								}if($nextPaymentDate >= $firstDayOfThisYear and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalForThisYear += $itemPrice;
								}if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalLeftForThisYear += $itemPrice;
								}	
								$nextPaymentDate->modify("+$paymentFrequencyCount months");
								break;
							case 5:
								// Add quarters
								$paymentFrequencyCount = $paymentFrequencyCount * 3;
								if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalLeftToPayThisMonth += $itemPrice;
								}if($nextPaymentDate >= $thisMonthFirstDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalForThisMonth += $itemPrice;
								}if($nextPaymentDate >= $firstDayOfThisYear and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalForThisYear += $itemPrice;
								}if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalLeftForThisYear += $itemPrice;
								}	
								$nextPaymentDate->modify("+$paymentFrequencyCount months");
								break;
							case 6:
								// Add years
								if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalLeftToPayThisMonth += $itemPrice;
								}if($nextPaymentDate >= $thisMonthFirstDate and $nextPaymentDate <= $nextMonthDate){
									$this->totalForThisMonth += $itemPrice;
								}if($nextPaymentDate >= $firstDayOfThisYear and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalForThisYear += $itemPrice;
								}if($nextPaymentDate >= $currentDate and $nextPaymentDate <= $firstDayOfNextYear){
									$this->totalLeftForThisYear += $itemPrice;
								}	
								$nextPaymentDate->modify("+$paymentFrequencyCount years");
								break;
						}
					}
				}
			}
		} finally {
			if($query){$query->close();}
		}
	}

	public function getTotalLeftToPayThisMonth(){
		echo ' $' . number_format($this->totalLeftToPayThisMonth);
	}

	public function getTotalToPayThisMonth(){
		echo ' $' . number_format($this->totalForThisMonth);
	}

	public function getTotalLeftToPayThisYear(){
		echo ' $' . number_format($this->totalLeftForThisYear);
	}

	public function getTotalToPayThisYear(){
		echo ' $' . number_format($this->totalForThisYear);
	}


}


$coreFunctions = new coreFunctions();
$coreFunctions->totalLeftToPayThisMonth($conn);



?>
