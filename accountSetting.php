<?php session_start();

if(!isset($_COOKIE['csfrToken']) && isset($_SESSION['CSFR_TOKEN'])){
	header('Set-Cookie: csfrToken='.$_SESSION['CSFR_TOKEN'].'; SameSite=Strict; Secure; HttpOnly;');
	header('Location: member.php');
}
else if(!isset($_SESSION['CSFR_TOKEN']) || !isset($_SESSION['TOKEN_TIME'])){
	header('Location: member.php');
}else{
	$timedif = time() - $_SESSION['TOKEN_TIME'];
	if($timedif < 0 || $timedif > 1800){
		session_destroy();
		header('Location: member.php');
	}
	$_SESSION['TOKEN_TIME'] = time();
}
if(!isset($_SESSION['loginUser']) || $_SESSION['loginUser']==""){
		header('Location: member.php');
}
require_once('config.php');
$sql_getMug = $link->prepare('SELECT MugFile FROM `UsersInformation` WHERE username=?');
$sql_getMug->bind_param('s',$_SESSION['loginUser']);
$sql_getMug->execute();
$result = $sql_getMug->get_result();
mysqli_close($link);

try{
  $row = mysqli_fetch_array($result);
  if(isset($_SESSION['uploadState'])){
	  echo strval($_SESSION['uploadState']);
  }
  $_SESSION['uploadState'] = "";
  if(isset($row) &&
     $row[0]!="" &&
     file_exists('upload/'.strval($row[0]))){
     echo '
  <br>
  <img style="heght:200px;width:150px;" src="/upload/'.strval($row[0]).'">
  <br><br>
  ';


  }
echo'  <br>
  <span>Username:'.htmlentities(strval($_SESSION['loginUser'])).'</span>
  <br>
  <br>
  <form method="POST" enctype="multipart/form-data" action="uploadPicture.php">
    <input type="file" name="mugShot"></input>
    <input type="submit" value="Upload"></input>
    <br><br>
    <span>Only access the format .jpg, .jpeg, .png and .gif</span>
    <br>
    <span>The filename only can contain letters, numbers, underline, dash, dot and square brackets.</span>
    <input name="csfr_token" style="display:none" value="'.$_SESSION['CSFR_TOKEN'].'">
  </form>
  <br>
  <br>
  <span>Upload picture from url:</span><br>
  <form method="POST" action="uploadUrlPicture.php">
    <input type="text" name="urlPicture"></input>
    <input type="submit" value="Upload"></input>
    <br><br>
    <span>It will try to catch picture. If failed, you can try upload with url by the above method.</span>
    <br>
    <span>All format will convert to .jpg</span>
    <br>
    <span>The url only accept "http://" or "https://"</span>
    <input name="csfr_token" style="display:none" value="'.$_SESSION['CSFR_TOKEN'].'">
  </form>

';



}catch (Exception $e){
      	mysqli_close($link);
	echo 'Caught exception: ', $e->getMessage(), '<br>';
	echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
}

?>
