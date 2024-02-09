console.log('App scripts loaded');

$(document).ready(function(){
	//Blur background on "add item" button pressed
    $('#addBtn').click(function(){
        $('#addMenu').slideToggle({
			start: function(){
				if($('#addMenu').is(':visible')){
					$('.toBlur').css('filter', 'blur(10px)');
					$('#addBtn').text('Close');
				} else {
					$('.toBlur').css('filter', 'blur(0px)');
					$('#addBtn').text('Add New +');
				}
			},
			complete: function(){
				if($('#addMenu').is(':visible')){
					$('.toBlur').css('filter', 'blur(10px)');
					$('#addBtn').text('Close');
				} else {
					$('.toBlur').css('filter', 'blur(0px)');
					$('#addBtn').text('Add New +');
				}
			}
        });
    });


	// Delete Item
	$(document).on('click', '.optionDelete', function(){
		var itemID = $(this).val();
		if(confirm("Are you sure you want to delete 'NAME'")){
			deleteItem(itemID);
		}
	});


	// View Edit item
	$(document).on('click', '.optionEdit', function(){
		var itemID = $(this).val();

		$('.editScreen').fadeToggle();
		$('.editScreen').css('display', 'flex');
		$('#subIDFromEditForm').val(itemID);

		$.ajax({
			type: 'post',
			url: 'editFunctions.php',
			dataType: 'json',
			data: {'action': 'viewEdit', 'subID': itemID},
			success: function(response){
				if(response.itemCode == 1){
					$('#saveEditSubName').val(response.subName);
					$('#saveEditPrice').val(response.subPrice);
					$('#saveEditFreqCount').val(response.subFreqCount);
					$('#saveEditFreq').val(response.subFreq);
					$('#saveEditPaymentDate').val(response.subPaymentDate);
					// $('#saveEditMessageBox').html(response.message); 
				}
			}
		});
	})


	// Save edited item
	$(document).on('click', '.saveEditButton', function(){
		var action = 'saveEdit';
		var itemID = $('#subIDFromEditForm').val();
		var itemName = $('#saveEditSubName').val();
		var itemPrice = $('#saveEditPrice').val();
		var itemFreqCount = $('#saveEditFreqCount').val();
		var itemFreq = $('#saveEditFreq').val();
		var itemPaymentDate = $('#saveEditPaymentDate').val();

		$.ajax({
			type: 'post',
			url: 'editFunctions.php',
			dataType: 'json',
			data: {
			'action': action, 
			'getItemID': itemID, 
			'updateSubName': itemName, 
			'updateSubPrice': itemPrice, 
			'updatePaymentFreq': itemFreq, 
			'updatePaymentFreqCount': itemFreqCount, 
			'updateDate': itemPaymentDate},
			success: function(response){
				if(response.itemCode == 3){
					$('.editScreen').fadeOut();
					$('#allSubsTable').load('listAllSubs.php');
				}
				if(response.itemCode == 4){
					$('#saveEditMessageBox').html("<div class='coolBox1'>" + response.publicMessage + '</div>')
				}
			}
		});
	})


	$(document).on('click', '.closeEditScreen', function(){
		$('.editScreen').fadeOut();

	})


});


function deleteItem(itemID){
	$.ajax({
		type: "POST",
		url: "processor.php",
		dataType: 'json',
		data: {itemID: itemID, action: 2},
		success: function(response){
			if(response.itemCode == 5){
				$('#allSubsTable').load('listAllSubs.php');
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.error("Error: ", textStatus, errorThrown);
		}
	});
}




function appAddNewSubscription(){
	var formData = $("#addNewSubscriptionForm").serialize();
	$.ajax({
		type: "post",
		url: "processor.php",
		data: formData,
		dataType: "json",

		success: function(response){
			if(response.status == "success"){
				$("#addNewMessageBox").html("<div class='coolBox1'>" + response.message + '</div>');
				if(response.itemCode == 1){
					location.reload();
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			$("#addNewMessageBox").html("<div class='coolBox1'>Something went wrong. Please try again later.</div>");
            console.error("AJAX Error:", textStatus, errorThrown);
		}
	})
}
