<?php session_start();
if($_SESSION['username'] != null){
	echo('Login sucessfully!!<br/>');
}

require_once('config.php');

$sql_search = $link->prepare('SELECT `title` FROM `MainTitle`');
$sql_search->execute();
$result = $sql_search->get_result();
try{
	$row = mysqli_fetch_array($result);
	$Title = $row[0];
	echo '
<!DOCTYPE html>
<head>
	<title>'.$Title.'</title>
	<meta charset="utf-8">
</head>
<body style="postition:absolute;width:800px;height:600px;">
	<span style="color:rgb(0,255,255);font-size:36px;">B10802115</span>
	<br>
	<input type="button" onclick="window.location=\'https://lab.feifei.tw\'" value="FeiFei Lab"></input>
	<br>
	<br>
	<iframe src="member.php" style="position:relative;left:0%;top:10px;width:100%;height:100%;" allow-forms allow-scripts>
		Your browser does not support iframe.
	</iframe>
</body>
';
}
catch (Exception $e){
	echo 'Caught exception: ', $e->getMessage(), '<br>';
	echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
}

?>
