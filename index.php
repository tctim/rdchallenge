<?php
include 'includes.php';
?>

<html>
<head>
	<title>Find the Queen - Login</title>
	<meta charset="UTF-8">
	<meta name="description" content="Game of Find the Queen, Real Decoy Programming Challenge by Timothy Campbell">
	<meta name="author" content="Timothy Campbell">

	<link rel="stylesheet" href="css/style.css?v=1.3">

	<script src="http://code.jquery.com/jquery-3.5.1.js"></script>
	
</head>
<body>
	<form name="loginForm" id="loginForm" >
		<h3>Please Login</h3>
		<input type="text" name="username" id="login-username" class="login-input login-username" placeholder="Username" required />
		<input type="password" name="password" id="login-password" class="login-input login-password" placeholder="Password" required />
		<input type="submit" id="btnLogin" name="send-login-request" value="Login" >
		<div id="output"></div>
	</form>

	<script>
		$("#loginForm").submit(function(event){
		    event.preventDefault();
		    var identity = $("#login-username").val();
		    var password = $("#login-password").val();

		    var authURL = "auth.php";
		    //var dataString = "identity="+identity+ "&password="+password+ "&trylogin=";
		    var dataString = {'identity': identity, 'password': password, 'trylogin': ""};

		    if($.trim(identity).length > 0 & $.trim(password).length > 0 ) {	        

		        $.ajax({
		            type: "POST",
		            url: authURL,
		            data: dataString,
		            cache: false,
		            error: function(errordata){console.log("ajax error: "+errordata);},
		            beforeSend: function() { 
				      	$("#btnLogin").val('Please Wait ...');
				      	$("#btnLogin").prop('disabled', true); 
				    },
		            success: function(data){
		                if(data == "success") {
		                    
		                    $("#output").html("<span style='color:green;'>Success</span>");
		                    setTimeout(function() {
		                    	window.location.href = 'game.php';
		                    }, 1000);                

		                } else {
		                	
		                    console.log("Message: "+data);
		                    $("#output").html(data);
		                    $("#btnLogin").val('Login');
				      		$("#btnLogin").prop('disabled', false); 

		                }
		            }
		        });

		    } else { 		    	
		        console.log("Ensure you have typed in your credentials");
		        $("#output").html('Ensure you have typed in your credentials');
		    }	    

		    return false;
		});
	</script>
</body>
</html>