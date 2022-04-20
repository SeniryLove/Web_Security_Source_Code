<?php session_start();
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
if($_SESSION['login_result'] != "200" || !strcmp($_SESSION['loginUser'],"") != 0){
	header("Location: member.php");
}

require_once('config.php');

$check = $link->prepare('SELECT `priority` FROM `UsersInformation` WHERE `username`=?');
$userID = $_SESSION['loginUser'];
$check->bind_param('s',$userID);
$check->execute();
$result = $check->get_result();
try{
	$row = mysqli_fetch_array($result);
	if(strval($row[0]) != '5'){
		header('Location: member.php');
	}else{
echo '
<!DOCTYPE html>
<html>
  <head>
    <title>Title Setting Page</title>
  </head>
  <body>
    <form method="POST" action="changeMainTitle.php">
	<span>New Title:</span>
	<input name="newTitle" type="text" ></input>
	<br><br>
	<span>Administrator:</span>
	<input name="username"></input>
	<br><br>
	<span>Password:</span>
	<input type="password" name="password"></input>
	<br><br>
	<input name="csfr_token" style="display:none" value="'.$_SESSION['CSFR_TOKEN'].'">
	<input type="submit" value="Change"></input>
    </form>
	<br>
	<button onclick="window.location=\'https://b10802115.centralindia.cloudapp.azure.com/member.php\'">Cancel</button>
  </body>
</html>

';
	}
}catch(Exception $e){
	header('Location: member.php');
}
mysqli_close($link);
?>
