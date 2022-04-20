<?php session_start();

$idd = $_POST['csfr_token'];

if(isset($_SESSION['TOKEN_TIME']) && isset($_SESSION['CSFR_TOKEN'])){
	$timedif = time() - $_SESSION['TOKEN_TIME'];

	if($timedif >= 0 && $timedif <= 1800){
		if(!strcmp($_SESSION['CSFR_TOKEN'],$idd) && strcmp($_SESSION['CSFR_TOKEN'],"")){
			session_destroy();
			$_SESSION['logout'] = "sucessful";
		}
	}else{
		session_destroy();
		$_SESSION['overTime'] = true;
		header("Location: member.php");
	}
}else{
	session_destroy();
	header("Location: member.php");
}
header("Location: member.php");
?>
