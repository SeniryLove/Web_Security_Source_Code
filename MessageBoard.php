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
require_once('config.php');
if(isset($_POST['view'])){

	preg_match('/[^0-9]+/i',$_POST['view'],$ErrorFormat);
	if(count($ErrorFormat) > 0){
		header('Location: MessageBoard.php');
	}else{
		$query = "SELECT `Username`,`Message`,`LeaveTime`,`MessageFile`,`id` FROM `MsgBoard` WHERE id=".$_POST['view'];
		$result = mysqli_query($link,$query);
		try{
			$row = mysqli_fetch_array($result);
			if($row[0] != NULL){
				if(!strcmp($row[0],"")){
					header('Location: MessageBoard.php');				
				}
			echo htmlentities($row[0]).'<br><br>';
			echo strip_tags($row[1],'<b><i><u><img><span>').'<br><br>';
			if($row[3] != NULL)
				echo '<a href="download.php?fileName='.htmlentities($row[3]).'">messageFile</a><br><br>';
			echo htmlentities($row[2]).'<br><br>';
			if(!strcmp($row[0],$_SESSION['loginUser'])){
				echo'						
					<form method="POST" action="deleteMessage.php" onsubmit="return confirm(\'Do you want to delete this message??\')">
					<input name="msgID" value="'.strval($row[4]).'" style="display:none"></input>
					<input type="submit" value="Delete"></input>
					<input name="csfr_token" style="display:none" value="'.$_SESSION['CSFR_TOKEN'].'">	
					</form>';
			}
			}
		}
		catch(Exception $e){

		}
	}

}else{

$query = "SELECT `Username`,`Message`,`LeaveTime`,`MessageFile`,`id` FROM `MsgBoard`";
$result = mysqli_query($link,$query);
try{
	if($result){
		$row = mysqli_fetch_array($result);
	}
	while($row[0] != NULL){

	echo htmlentities($row[0]).':<br>';
	
	echo strip_tags($row[1],'<b><i><u><img><span>').'<br><br>';
	if($row[3] != NULL){
		echo '<a href="download.php?fileName='.htmlentities($row[3]).'">messageFile</a><br><br>';
	}
	echo' <span>'.$row[2].'</span><br>';
		if(!strcmp($row[0],$_SESSION['loginUser'])){
		echo'	
		<form method="POST" action="deleteMessage.php" onsubmit="return confirm(\'Do you want to delete this message??\')">
			<input name="msgID" value="'.strval($row[4]).'" style="display:none"></input>
			<input type="submit" value="Delete"></input>
			<input name="csfr_token" style="display:none" value="'.$_SESSION['CSFR_TOKEN'].'">
		</form>';
		}
	echo '<form method="POST" action="MessageBoard.php">
			<input name="view" value="'.strval($row[4]).'" style="display:none"></input>
			<input type="submit" value="View on other tab"></input>
		</form>';

echo '<br><br>';


	try{
		$row = mysqli_fetch_array($result);
	}catch(Exception $eee){

	}
}
mysqli_close($link);
}catch(Exception $e){

}

echo htmlentities($_SESSION['LeaveState']);
$_SESSION['LeaveState'] = "";
echo '	
<form method="POST" action="leaveMessage.php" enctype="multipart/form-data">
	<span style=\'font-size:24px\'>Leave your message:</span><br><br>
	<span style=\'font-size:16px\'>Message</span>
	<br><br>
	<textarea name="Message" rows="20" cols="30" maxlength="600" minlength="1" required></textarea>
	<br><br>
	<span>Add message file on this page.</span>
	<br><br>
	<span>[img] tag only can convert the source by http:// or https:// and the format must be .jpg, .jpeg, .gif, png, bmp</span>
	<br><br>
	<input type="file" name="leaveFile"></input>
	<input name="csfr_token" style="display:none" value="'.$_SESSION['CSFR_TOKEN'].'">
	<input type="submit" value="Leave Message"></input>
</form>	
';

}
?>
