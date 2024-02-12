<?php
session_start();
require("db.php");

if(isset($_SESSION['userID'])){
	if(isset($_POST['action'])){
		$action = $_POST['action'];
	
		if($action == 1){
			$newItem = $_POST['addNewItemName'];
			$newPrice = $_POST['addNewPrice'];
			$newFreq = $_POST['addNewFreq'];
			$newFreqCount = $_POST['addNewFreqCount'];
	
			$newDate = $_POST['addNewPaymentDate'];
			$dateTest = new DateTime($newDate);
	
			$oldDate = (new DateTime())->modify('-5 years');
			$futureDate = (new DateTime())->modify('+5 years');
	
			if($dateTest <= $futureDate){
				if($dateTest >= $oldDate){
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
					echo json_encode(array("itemCode" => 3, "status" => "success", "message" => "Date can't be older then 5 years"));
				}
			} else {
				echo json_encode(array("itemCode" => 4, "status" => "success", "message" => "Date can't be more then 5 years away"));
			}
		}

		// Delete item (scary!)
		if($action == 2){
			$userID = $_SESSION['userID'];
			$itemID = $_POST['itemID'];

			try{
				$deleteQuery = $conn->prepare("DELETE FROM user_items WHERE created_by_user=? AND id=?");
				$deleteQuery->bind_param('ii', $userID, $itemID);

				$deleteQuery->execute();
				if($deleteQuery->affected_rows > 0){
					echo json_encode(array("itemCode" => 5, "status" => "success", "message" => "Item Deleted"));
				} else {
					echo json_encode(array("itemCode" => 6, "status" => "success", "message" => "Something went wrong deleting item"));
				}
				$deleteQuery->close();
			} catch(Exception $e) {
				echo json_encode(array("itemCode" => 7, "status" => "error", "message" => $e->getMessage()));
			}
		}
	}
} else {
	echo json_encode(array("itemCode" => 8, "status" => "success", "message" => "OOPSIE DOOPSIE! It looks like you aren't properly logged in"));
}
?>
