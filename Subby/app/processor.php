<?php
require("db.php");

if(isset($_POST['action'])){
	$action = $_POST['action'];

	if($action == 1){
		$newItem = $_POST['addNewItemName'];
		$newPrice = $_POST['addNewPrice'];
		$newFreq = $_POST['addNewFreq'];
		$newFreqCount = $_POST['addNewFreqCount'];

		$newDate = $_POST['addNewPaymentDate'];
		$dateTest = new DateTime($newDate);

		$baselineDate = new DateTime('2020-01-01');

		if($dateTest >= $baselineDate){
			if($newItem != "" and $newPrice != "" and $newFreq != "" and $newFreqCount != "" and $newDate != ""){

				$addItem = $conn->prepare("INSERT INTO user_items (created_by_user, custom_item_name, item_price, payment_frequency, payment_frequency_count, next_payment_date) VALUES (?, ?, ?, ?, ?, ?)");
				$addItem->bind_param("isdiis", $_SESSION['userID'], $newItem, $newPrice, $newFreq, $newFreqCount, $newDate);
		
				$addItem->execute();
				$addItem->close();
				$conn->close();
	
				echo json_encode(array("itemCode" => 1, "status" => "success", "message" => "Item addedd successfully"));
			} else {
				echo json_encode(array("itemCode" => 2, "status" => "success", "message" => "All fields must be filled!"));
			}
		} else {
			echo json_encode(array("itemCode" => 3, "status" => "success", "message" => "Date must be later then Jan 2020"));
		}


	}
}
?>
