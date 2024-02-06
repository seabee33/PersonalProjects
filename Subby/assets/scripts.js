//  ================= Register =================
function showRegisterWindow(){
	$("#registerBox").css("display", "flex");
}

function closeRegisterWindow(){
	$("#registerBox").css("display", "none");
}

function register(){
	var formData = $("#registerForm").serialize();

	$.ajax({
		type: "post",
		url: "homeProcessor.php",
		data: formData,
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
	})
}


// ================= Login =================
function showLoginWindow(){
	$("#loginBox").css("display", "flex");
}

function closeLoginWindow(){
	$("#loginBox").css("display", "none");
}

function login(){
	var formData = $("#loginForm").serialize();

	$.ajax({
		type: "post",
		url: "homeProcessor.php",
		data: formData,
		dataType: "json",

		success: function(response){
			if(response.status == "success"){
				$("#loginMessages").html("<div>" + response.message + "</div>");
				if(response.itemCode && response.itemCode == 6){
					// $("#actionButtons").html("<button onclick='logOut()'>Log Out</button>");
					closeLoginWindow();
					window.location.href = "/app";
					//location.reload();
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			$("#loginMessages").html("<div>Something went wrong. Please try again later.</div>");
            console.error("AJAX Error:", textStatus, errorThrown);
		}
	})
}

function goToApp(){
	window.location.href = "/app";
}


// ================= Log Out =================
function logOut(){
	window.location.replace("/homeProcessor.php?getAction=logOut")
}