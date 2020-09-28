<?php
include 'includes.php';

if (isset($_SESSION['username'])) {
	$username = $_SESSION['username']; 
} else {
	$username = "Not Set";
	echo "<script> window.location.href = 'index.php?logout=1'; </script>";
}
?>

<html>
<head>
	<title>Find the Queen - Play</title>
	<meta charset="UTF-8">
	<meta name="description" content="Game of Find the Queen, Real Decoy Programming Challenge by Timothy Campbell">
	<meta name="author" content="Timothy Campbell">

	<link rel="stylesheet" href="css/style.css">
	<script src="http://code.jquery.com/jquery-3.5.1.js"></script>
	<script> 
	</script>
</head>
	
<body>
	<h3>Hey <?=$username;?></h3> 
	<label>Score - <span class="score-keep"></span></label>

	<form name="frmChat" id="frmChat">
		<div id="chat-box"></div>
		<input type="hidden" name="chat-user" id="chat-user" placeholder="Name" class="chat-input" value="<?=$username;?>" required />
		<input type="text" name="chat-message" id="chat-message" placeholder="Message" class="chat-input chat-message" required />
		<input type="submit" id="btnSend" name="send-chat-message" value="Send" >

		<input type="hidden" id="userID" value="x">
		<input type="hidden" id="round" value="0">

		<input type="hidden" id="selected" value="">
		<input type="hidden" id="notselected" value="">

		<input type="hidden" id="lastanswer" value="">

		<input type="hidden" id="myscore" value="0">

	</form>	
</body>

<script>
	function showMessage(messageHTML) {
		$('#chat-box').append(messageHTML);
	}

	$(document).ready(function(){
		var conn = new WebSocket('ws://localhost:7621');
		var username = $("#chat-user").val();
		var msg = $("#chat-message").val();

		var selected = $("#selected").val();
		var notselected = $("#notselected").val();

		var myscore = parseInt($("#myscore").val());
		$(".score-keep").html(myscore);
		
		conn.onopen = function(e) {
		    console.log("Connection established!");
		    conn.send(username+" has connected.");
		    showMessage("<div class='chat-connection-ack'>"+username+" has connected!</div>");
		};

		conn.onclose = function(e) {
			console.log("Connection Closed!");
		}

		conn.onmessage = function(e) {
		    console.log('log: '+e.data);
		    var data = JSON.parse(e.data);

		    // get user ids
		    var userID = $("#userID").val();
		    if (userID == "x") {
		    	$("#userID").val(data.userID);
		    }

		    if(data.username != null && data.msg != null) {
			    var row = "<div class='chat-box-html'>"+data.username+": "+data.msg+" </div>";
			    showMessage(row);
			}

			//score keeping
			if(data.username == 'GAMEMASTER' && data.msg == 'Correct') {
				//update score
				if (data.selected == username) {
					myscore = myscore + 1;
					$("#myscore").val(myscore);
					$(".score-keep").html(myscore);
				}
				// they have been swapped so update them here 
			    //$("#selected").val(data.selected);
			    //$("#notselected").val(data.notselected);
			}

			if(data.username == 'GAMEMASTER' && data.msg == 'Incorrect') {
				//update score
				if (data.notselected == username) {
					myscore = myscore + 1;
					$("#myscore").val(myscore);
					$(".score-keep").html(myscore);
				}
				// they have been swapped so update them here 
			    //$("#selected").val(data.selected);
			    //$("#notselected").val(data.notselected);
			}

			// set number 
			if(data.username == 'GAMEMASTER' && data.round == 0) {
			    $("#round").val(1);
			    $("#selected").val(data.selected);
			    $("#notselected").val(data.notselected);
			}

			// guess number 
			if(data.username == 'GAMEMASTER' && data.round == 1.5) {
				$("#round").val(1.5);			    
			    // previous answer
			    $("#lastanswer").val(data.lastanswer); 
			}

			if(data.username == 'GAMEMASTER' && data.round == 2) {
			    $("#round").val(2);			    
			}

			if(data.username == 'GAMEMASTER' && data.round == 2.5) {
				$("#round").val(2.5);
			    // previous answer
			    $("#lastanswer").val(data.lastanswer); 
			}

			if(data.username == 'GAMEMASTER' && data.round == 3) {
			    $("#round").val(3);
			}

			if(data.username == 'GAMEMASTER' && data.round == 3.5) {
				$("#round").val(3.5);
			    // previous answer
			    $("#lastanswer").val(data.lastanswer); 
			}

			if(data.username == 'GAMEMASTER' && data.round == 4) {
			    $("#round").val(4);
			}

			if(data.username == 'GAMEMASTER' && data.round == 4.5) {
				$("#round").val(4.5);
			    // previous answer
			    $("#lastanswer").val(data.lastanswer); 
			}

			if(data.username == 'GAMEMASTER' && data.round == 5) {
			    $("#round").val(5);
			}

			if(data.username == 'GAMEMASTER' && data.round == 5.5) {
				$("#round").val(5.5);
			    // previous answer
			    $("#lastanswer").val(data.lastanswer); 
			}

			if(data.username == 'GAMEMASTER' && data.msg == 'Game Over') {
				if (myscore >= 3) {
					var row = "<div class='chat-connection-ack'>Victory!!</div>";
				} else {
					var row = "<div class='error'>Defeat!!</div>";
				}
				showMessage(row);

				setTimeout(function() {
					var rowty = "<div class='chat-connection-ack'>Thanks for Playing</div>";
					showMessage(rowty);
					
					var rowcl = "<div class='error'>Connection Closed.</div>";
					showMessage(rowcl);
					conn.close();
				}, 1000); 
			}
		};

		$('#frmChat').on("submit",function(event){
			event.preventDefault();
			
			var userID = $("#userID").val();
			var username = $("#chat-user").val();
			var msg = $("#chat-message").val();
			var round = $("#round").val();
			var selected = $("#selected").val();
			var notselected = $("#notselected").val();
			var lastanswer = $("#lastanswer").val();

			var data = {
					'userID': userID, 
					'username': username, 
					'msg': msg, 
					'round': round,
					'selected': selected,
					'notselected': notselected,
					'lastanswer': lastanswer,
				};
			conn.send(JSON.stringify(data));

			$("#chat-message").val("");
		});
	});
	
</script>

</html>