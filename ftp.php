<?php

/*
FTP Creation Page
(c) 2009 Micheal Jones
Version 1.0.4

Version History
======

1.0.4 - Add version display
1.0.3 - Deal with issue of clicking button and it not going forward.
		Add feedback in case the click is not on the button itself.
		Version Display fixes
1.0.2 - Deal with ampersands correctly, place AFP URL where it's accessible, font size larger
1.0.1 - Fix empty username returning a "username exists" error.
1.0 - Initial Release
*/
$version = "1.0.4";

//If this has been submitted:
if(isset($_POST["submission"])) {
	//Do things for submission
	$emailAddress = $_POST["emailAddress"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$output = "";
	$return = 0;
	
	// **** CHANGE
	$cmd = "/PATH/TO/python2.5 /PATH/TO/rumpusAddUser.py \"$emailAddress\" \"$username\" \"$password\"";
	exec($cmd, &$output, &$return);
	
	//Split $output into pieces:
	$createdUsername = $output[0];
	$createdPassword = $output[1];
	$createdAFPURL = $output[3];
}

if(isset($_GET["username"]))
	$username = $_GET["username"];
else
	$username = "";

// **** CHANGE
$users = array("fill", "in", "user", "names", "here");

function rumpusVersion() {
	$cmd = "/opt/local/bin/python2.5 /Volumes/MailRAID/websites/jmg/internalapps/rumpusAddUser.py --version";
	exec($cmd, $rumpusVersion);
	return $rumpusVersion[0];
}

?>

<html>
	<head>
		<title>FTP Creation</title>
		<script type="text/javascript">
			<!--
			var validUser = false;
			var validEmail = <?php echo ($username != "") ? 'true' : 'false' ?>;

			var k;
			if(window.XMLHttpRequest) {
				k = new XMLHttpRequest();
			}
			
			function checkInput(skipTest) {
				//Disable button
				var e = document.getElementById("submitButton");
				e.enabled = false;
				e.value = "Processing...";
				
				if(skipTest == null)
					skipTest = false;

				//In case username still has focus, check the username and give it two seconds to return the result before continuing on (calls itself saying that we can skip the user test)
				if(!skipTest) {
					if(!validUser) {
						validateUsername();
						//Call self again in a second giving a second for the AJAX call to finish
						var t = setTimeout("checkInput(true)", 2000);
						return false;
					}
				}
				
				if(validUser && validEmail) {
					var e = document.getElementById("ftp_creation");
					e.submit();
				} else {
					var e = document.getElementById("submitButton");
					e.enabled = true;
					e.value = "Create User";
				}
				
				if(!validUser) {
					var e = document.getElementById("username");
					e.style.background = "#ff0000";
				}
				if(!validEmail) {
					var e = document.getElementById("email");
					e.style.background = "#ff0000";
				}
				
			}
			
			function toggleInstructions() {
				var e = document.getElementById("instructions");
				if(e.style.display == "block")
					e.style.display = "none";
				else
					e.style.display = "block";
			}
			
			function validateUsername() {
				var e = document.getElementById("progressIndicator");
				e.style.display = "block";
				var username = document.getElementById("username").value;
				
				//If username is blank, abort
				if(username == "")
					exit();
				e = document.getElementById("infoBox");
				if(e)
					e.style.display = "none";
				
				if(window.ActiveXObject)
					k = new ActiveXObject("Microsoft.XMLHTTP");
				
				// **** CHANGE
				k.open("POST", "http://www.domain.com/path/to/checkUser.php?username=" + username);
				k.send(null);
				
				k.onreadystatechange = function() {
					if(k.readyState == 4) {
						var exists = k.responseText;
						e = document.getElementById("username");
						var error = document.getElementById("error");
						if(exists == "false") {
							validUser = true;
							if(e.style.background != "")
								e.style.background = "";
							error.innerHTML = "";
						} else {
							validUser = false;
							e.style.background = "#ff0000";
							error.innerHTML = "Username already exists";
						}
						e = document.getElementById("progressIndicator");
						e.style.display = "none";
					}
				}
			}
			
			
			function validateEmail() {
				var email = document.getElementById("email");
				if(email.value != "") {
					validEmail = true;
					email.style.background = "";
				} else {
					validemail = false;
					email.style.background = "#ff0000";
				}
				
			}
			-->
		</script>
		<style type="text/css">
			body {
				height: 480px;
				width: 640px;
				padding:0;
				margin:0;
				font-family: "Segoe UI", "Candara", serif;
				overflow-x: hidden;
			}
			#box { 	border: 3px double #000;
					background: #ddd;
					margin: 2px;
					padding: 5px;
					width: 640px;
					min-height: 480px;
				}
			#header {
					font-weight: bold;
					font-size: 48px;
					text-align: center;
					margin: 0 0 15px 0;
					padding: 0;
				}
			#subheader {
					font-size: 14px;
					width: 300px;
					margin: 0 auto;
			}
			#instructions {
					display: none;
			}
			form {  width: 420px;
					margin: 0 auto;
					padding: 0;
			}
			label { 
					width: 4em;
					text-align: right;
					margin-right: 0.5em;
					font-size: 18px;
			}
			input {
					border: 1px solid #000;
					font-size: 18px;
					width: 150px;
			}
			#submitButton {
					border-right: 2px solid #000;
					border-bottom: 2px solid #000;
			}
			#submitButton:active {
					background: #fff;
					border-right: 1px solid #ccc;
					border-bottom: 1px solid #ccc;
					border-top: 2px solid #000;
					border-left: 2px solid #000;
			}
			select {
					width: 100%;
					text-align: right;
					font-size: 18px;
			}
			.small {
					font-size: 12px;
			}
			#progressIndicator {
					display: none;
			}
			#infoBox {
					text-align: center;
					margin: 5px auto;
					padding: 5px;
					border-width: 1px 0px 1px 0;
					border: solid #000;
					width: 430px;
			}
			#infoBox pre {
					text-align: left;
					padding-left: 150px;
			}
			#copyright {
					font-size: 14px;
					text-align: center;
					margin-top: 15px;
					padding: 0;
			}
			#AFPURL {
					display: none;
			}
		</style>
	</head>
	<body>
		<div id="box">
			<div id="header">
				COMPANY NAME<br />FTP Creation
			</div>
			<div id="subheader">
				<strong>Instructions:</strong> <a href="#" onclick="toggleInstructions();"><span id="instruction" class="small">(Click to toggle instructions)</span></a><br />
				<ol>
					<div id="instructions">
					<li>Choose your email address to have the details sent to.</li>
					<li>Enter the desired Username. If this turns red, the username is not available</li>
					<li>Enter the desired password. If you leave this blank one will be automatically generated for you.</li>
					<li>Press Submit</li>
					</div>
				</ol>
			</div>
			<?php 
			
			if(isset($_POST["submission"])) {
				if(isset($output)) {
					$details = "<pre>Username: " . $createdUsername . "\nPassword: " . $createdPassword . "\n" . "</pre><br />" . "<div id=\"AFPURL\">" . $createdAFPURL . "</div>";
				}
				
				print "<div id=\"infoBox\"><p>Created! Please check your email for the username and password details.</p>" . $details . "</div>";
			}
			
			?>
			<form id="ftp_creation" name="ftp" method="POST" action="">
				<table>
					<tr>
						<td><label>Your Email Address:</label></td>
						<td><select name="emailAddress" id="email" onmouseup="validateEmail();">
					
						<?php

							//Select Usernames
							if($username == "")
								echo "<option selected=\"selected\">Choose Email Address</option>";

							foreach($users as $user) {
								if($user == $username)
									echo "<option selected=\"selected\">$user</option>";
								else
									echo "<option>$user</option>";
							}

						?>
					
						</select></td>
						<td>@joemedia.tv</td>
					</tr>
					<tr><td><label>Username:</label></td>
						<td><input type="text" name="username" id="username" onblur="validateUsername();" /></td><td><img src="indicator.gif" id="progressIndicator" /><span id="error" class="small"></span></td>
					</tr>
					<tr>
						<td><label>Password:<br /><span class="small">(Leave blank to have one auto generated)</span></label></td>
						<td><input type="password" name="password" /></td><td></td>
					</tr>
					<tr><td></td><td>
							<input type="hidden" name="submission" value="true" />
							<input type="button" id="submitButton" onMouseUp="checkInput();" value="Create User" />
						</td><td></td></tr>
				</table>
			</form>
			<div id="copyright">
				<!-- bug: Needs to open in Safari, target="_new" prevents app from screwing up. -->
				&copy; 2009 - Version 1.0.0<span class="small">(<?php echo rumpusVersion() . " " . $version ?>)</span>
			</div>
		</div>
	</body>
</html>
