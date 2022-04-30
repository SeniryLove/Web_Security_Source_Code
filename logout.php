<?php session_start();

$idd = $_POST['csfr_token'];



if(strcmp($_COOKIE['csfrToken'],$_SESSION['CSFR_TOKEN'])){
	header("Location: member.php");
}
else
{
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
	header("Location: member.php");
}
header("Location: member.php");
}
?>
