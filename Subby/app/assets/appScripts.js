console.log('App scripts loaded');

$(document).ready(function(){
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

	$('.optionDelete').click(function(){
		var itemID = $(this).val();

		if(confirm("Are you sure you want to delete 'NAME'")){
			deleteItem(itemID);
		}
	});
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
				$("#addNewMessageBox").html("<div style='border-radius:10px; border: 1px solid rgba(33, 33, 33, 0.123); display:flex; flex-direction:column; padding: 10px; margin: 0px 0px 10px 0px;'>" + response.message + '</div>');
				if(response.itemCode == 1){
					location.reload();
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			$("#addNewMessageBox").html("<div style='border-radius:10px; border: 1px solid rgba(33, 33, 33, 0.123); display:flex; flex-direction:column; padding: 10px; margin: 0px 0px 10px 0px;'>Something went wrong. Please try again later.</div>");
            console.error("AJAX Error:", textStatus, errorThrown);
		}
	})
}