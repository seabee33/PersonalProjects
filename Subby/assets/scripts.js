//  ================= Register =================
function showRegisterWindow(){
	$("#registerBox").css("display", "flex");
}

function closeRegisterWindow(){
	$("#registerBox").css("display", "none");
}


$(document).ready(function(){
	// Login function
	$('#loginForm').submit(function (event){
		event.preventDefault();
		var LoginFormData = $(this).serialize();

		$.ajax({
			type: "post",
			url: "app/phpScripts/homeProcessor.php",
			data: LoginFormData,
			dataType: "json",
	
			success: function(response){
				if(response.status == "success"){
					$("#loginMessages").html("<div>" + response.message + "</div>");
					if(response.itemCode && response.itemCode == 6){
						$("#actionButtons").html("<button onclick='goToApp()'>App</button> <button onclick='logOut()'>Log Out</button>");
						closeLoginWindow();
						toast('You have been logged in, please click "app" to continue', 'green', 'white', 5);
					}
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#loginMessages").html("<div>Something went wrong. Please try again later.</div>");
				console.error("AJAX Error:", textStatus, errorThrown);
			}
		});
	});

	// Register function
	$('#registerForm').submit(function(event){
		event.preventDefault();
		var RegisterFormData = $("#registerForm").serialize();

		$.ajax({
			type: "post",
			url: "app/phpScripts/homeProcessor.php",
			data: RegisterFormData,
			dataType: "json",
	
			success: function(response){
				if(response.status == "success"){
					if(response.itemCode == 1){
						closeRegisterWindow();
						toast('Successfully registered , please check your email to confirm your account', 'green', 'white', 5);
					} else {
						$("#registerMessages").html("<div>" + response.message + "</div>");
					}
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#registerMessages").html("<div>Error occurred during registration. Please try again later.</div>");
				console.error("AJAX Error:", textStatus, errorThrown);
			}
		});
	});


});





function register(){
	var RegisterFormData = $("#registerForm").serialize();

	$.ajax({
		type: "post",
		url: "homeProcessor.php",
		data: RegisterFormData,
		dataType: "json",

		success: function(response){
			if(response.status == "success"){
				$("#registerMessages").html("<div>" + response.message + "</div>");
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			$("#registerMessages").html("<div>Error occurred during registration. Please try again later.</div>");
            console.error("AJAX Error:", textStatus, errorThrown);
		}
	});
}


// ================= Login =================
function showLoginWindow(){
	$("#loginBox").css("display", "flex");
}

function closeLoginWindow(){
	$("#loginBox").css("display", "none");
}


function goToApp(){
	window.location.href = "/app";
}


// ================= Log Out =================
function logOut(){
	window.location.replace("/app/phpScripts/homeProcessor.php?getAction=logOut")
}



function toast(message, bgColor, textColor, secondToDisappear){
	$('.toastBox').css('color', textColor);
	$('.toastBox').css('background-color', bgColor);
	$('.toastBox').text(message);
	$('.toastBox').fadeToggle();
	$('.toastBox').css('display', 'flex');

	$('.toastBox').delay(secondToDisappear * 1000).fadeToggle();
}
