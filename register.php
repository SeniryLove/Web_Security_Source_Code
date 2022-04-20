<?php session_start();

if( 	!isset($_POST['username']) ||
	!isset($_POST['password']) ||
	$_POST['username'] == "" ||
	$_POST['password'] == "") {
	$_SESSION['register_result']="empty";
	header("Location: register_page.php");
}

$userID = $_POST['username'];
$passWD = $_POST['password'];

preg_match('/[ \t]+/i',strval($userID).strval($passWD),$invalidChar);

if(!strcmp($invalidChar[0],' ') || !strcmp($invalidChar[0],'\t')){
	 $_SESSION['register_result'] = 'false';
	  header("Location: register_page.php");
}else{

require_once('config.php');
$sql_search = $link->prepare('SELECT * FROM `UsersInformation` WHERE `username`= ?');
$sql_search->bind_param('s', $userID);
$sql_search->execute();
$result = $sql_search->get_result();
$sql_insert = $link->prepare("INSERT INTO `UsersInformation` (`username`,`password`,`priority`) VALUES (?,?,1)");
$sql_insert->bind_param('ss',$userID,$passWD);
try{
  $row = mysqli_fetch_array($result);

  if($row){
	  mysqli_close($link);
	  $_SESSION['register_result'] = 'false';
	  header("Location: register_page.php");
 }else{
	  $sql_insert->execute();
	  mysqli_close($link);
	  $_SESSION['register_result'] = 'true';
	  header("Location: member.php");
  }
}catch (Exception $e){
	echo 'Caught exception: ', $e->getMessage(), '<br>';
	echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
}
}
?>
