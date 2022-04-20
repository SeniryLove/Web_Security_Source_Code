<?php session_start();

if( 	!isset($_POST['username']) ||
	!isset($_POST['password']) ||
	$_POST['username'] == "" ||
	$_POST['password'] == "") {
	header("Location: TitleSetting.php");
}

$userID = $_POST['username'];
$passWD = $_POST['password'];
$newTitle = $_POST['newTitle'];
$idd = $_POST['csfr_token'];

if(isset($_SESSION['TOKEN_TIME']) && isset($_SESSION['CSFR_TOKEN']) && strcmp($_SESSION['CSFR_TOKEN'],"")){
	
	$timedif = time() - $_SESSION['TOKEN_TIME'];
	if($timedif >= 0 && $timedif <= 1800 && !strcmp($_SESSION['CSFR_TOKEN'],$idd)){
		$_SESSION['TOKEN_TIME'] = time();
		require_once('config.php');
		$sql_search = $link->prepare('SELECT * FROM `UsersInformation` WHERE `username`=? and `password`=? and `priority` = 5');
		$sql_search->bind_param('ss', $userID, $passWD);
		$sql_search->execute();
		$result = $sql_search->get_result();
		$sql_changeTitle = $link->prepare('UPDATE MainTitle SET title=?');
		$sql_changeTitle->bind_param('s',$newTitle);
		try{
		      	$row = mysqli_fetch_array($result);

		      	if($row){
		      		if($newTitle != ""){
		      			$sql_changeTitle->execute();
		      			mysqli_close($link);
		      			echo "Change Title Successfully!!";
		      		}else{
		      			mysqli_close($link);
		      			header("Location: TitleSetting.php");
		      		}
		      	}else{
		      		mysqli_close($link);
		      		header("Location: TitleSetting.php");
		      	}
		}catch (Exception $e){
			echo 'Caught exception: ', $e->getMessage(), '<br>';
			echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
		}
	}else{
		header("Location: TitleSetting.php");		
	}
}else{
	header("Location: TitleSetting.php");
}
?>
