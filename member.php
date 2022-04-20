<?php session_start();
if($_SESSION['logout'] == "sucessful"){
	$_SESSION['logout'] = "";
	echo 'Logout sucessfully!!<br/>';
}
if($_SESSION['register_result'] == 'true'){
	echo 'Register Sucessfully!!<br><br>';
	$_SESSION['register_result'] = "";
}


if($_SESSION['login_result'] == '200'){
	if(!isset($_SESSION['CSFR_TOKEN']) || !isset($_SESSION['TOKEN_TIME'])){
		session_destroy();
		header('Location: member.php');
	}else{
		$timedif = time() - $_SESSION['TOKEN_TIME'];
		if($timedif < 0 || $timedif > 1800){
			session_destroy();
			header('Location: member.php');
		}
		$_SESSION['TOKEN_TIME'] = time();
	}
	require_once('config.php');
	$check = $link->prepare('SELECT `priority`,`MugFile` FROM `UsersInformation` WHERE `username`=?');
	$check->bind_param('s',$_SESSION['loginUser']);
	$check->execute();
	$result = $check->get_result();
	try{
		if(isset($_SESSION['CSFR_TOKEN']) && isset($_SESSION['TOKEN_TIME'])){
			$row = mysqli_fetch_array($result);
				if($row[1] != NULL){		
					echo '<img style="heght:200px;width:150px;" src="/upload/'.htmlentities($row[1]).'"><br><br>';
				}
				if(!strcmp(strval($row[0]),'5')){
					echo '<a href="TitleSetting.php">Change Title</a><br><br>';
				}
				echo 'Hello, '.htmlentities(strval($_SESSION['loginUser'])).'<br/><br/>';
				echo ' 
					<a href="accountSetting.php">Accout Setting</a><br><br>
					<a href="MessageBoard.php">Message Board</a><br><br>
					<form method=POST action="logout.php">
					<input type="submit" value="Log out"></input>
					<input name="csfr_token" style="display:none" value="'.$_SESSION['CSFR_TOKEN'].'">
					</form>
					<span>If you over 30 minutes without any actions, it will be logout automatically.</span>
					<br><br>';
		}
		else{
			session_destroy();
			header("Location: member.php");
		}
	}catch(Exception $e){

	}
}else if($_SESSION['login_result'] == "403"){
	if(!strcmp($_SESSION['register_result'],"true")){
		echo 'Register sucessfully!!';
		$_SESSION['register_result'] = "";
	}

	$_SESSION['login_result'] = "";
	echo 'Login failed!!<br/>';
	echo '
	<span style=\'font-size: 36px\'>Login Page</span>
	<br><br>
	<form method="POST" action="./login.php">
		<span style="font-size:20px">Username:</span>
		<input type="text" name="username" autocomplete="username" placeholder="Username" required></input>
		<br><br>
		<span style="font-size:20px">Password:</span>
		<input type="password" name="password" autocomplete="off" placeholder="Password" required></input>
		<br><br>
		<input type="submit" value="Submit"></input>
		<br><br>
		<span>Do you have no account? Press the register button to regist one.</span>
		<br><br>
		<input type="button" onclick="'."window.location='register_page.php'".'" value="Register"></input>
		<br>
	</form>';
}
else{
	$isOver = $_SESSION['overTime'];
	if(isset($_SESSION['overTime']) && !strcmp($isOver,"true")){
		echo 'No action is performed for more than 30 minutes<br>
		      Please login again!!<br><br>';
	}
	else if(!strcmp($_SESSION['register_result'], "true")){
		echo 'Register sucessfully!!';
		$_SESSION['register_result'] = "";
	}

	echo '
	<span style=\'font-size: 36px\'>Login Page</span>
	<br><br>

	<form method="POST" action="./login.php">
		<span style="font-size:20px">Username:</span>
		<input type="text" name="username" autocomplete="username" placeholder="Username" required></input>
		<br><br>
		<span style="font-size:20px">Password:</span>
		<input type="password" name="password" autocomplete="off" placeholder="Password" required></input>
		<br><br>
		<input type="submit" value="Submit"></input>
		<br><br>
		<span>Do you have no account? Press the register button to regist one.</span>
		<br><br>
	<input type="button" onclick="'."window.location='register_page.php'".'" value="Register"></input>
		<br>
	</form>';
}
?>

