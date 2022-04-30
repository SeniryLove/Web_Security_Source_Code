<?php session_start();

if( 	(isset($_SESSION['CSFR_TOKEN']) && !strcmp($_SESSION['CSFR_TOKEN'],"")) ||
	(isset($_SESSION['TOKEN_TIME']) && !strcmp($_SESSION['TOKEN_TIME'],"")) ||
	!isset($_POST['username']) ||
	!isset($_POST['password']) ||
	$_POST['username'] == "" ||
	$_POST['password'] == "") {
	header("Location: member.php");
}

$userID = $_POST['username'];
$passWD = $_POST['password'];

require_once('config.php');
$sql_search = $link->prepare('SELECT * FROM `UsersInformation` WHERE `username`=? and `password`=?');
$sql_search->bind_param('ss', $userID, $passWD);
$sql_search->execute();
$sql_query = "SELECT * FROM `UsersInformation` WHERE `username` = '$userID' and `password` = '$passWD';";
$sql_insert = "INSERT INTO `UsersInformation` (`username`,`password`) VALUES ('$userID','$passWD')";
//$result = mysqli_query($link,$sql_query);
$result = $sql_search->get_result();
mysqli_close($link);
try{
  $row = mysqli_fetch_array($result);

  if($row){
	  $_SESSION['login_result'] = '200';
	  $_SESSION['loginUser'] = $userID;
	  $_SESSION['CSFR_TOKEN'] = bin2hex(random_bytes(16)).'_'.bin2hex(random_bytes(16));
	  header('Set-Cookie: csfrToken='.$_SESSION['CSFR_TOKEN'].'; SameSite=Strict; Secure; HttpOnly;');
	  $_SESSION['TOKEN_TIME'] = time();
	  $_SESSION['mugFile'] = $row[5];
  }else{
	  $_SESSION['login_result'] = '403';

  }
	  header("Location: member.php");
}catch (Exception $e){
	echo 'Caught exception: ', $e->getMessage(), '<br>';
	echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
}

?>
