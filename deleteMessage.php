<?php session_start();


if(strcmp($_COOKIE['csfrToken'],$_SESSION['CSFR_TOKEN'])){
	header('Location: member.php');
}


else if(!isset($_SESSION['loginUser']) || !strcmp($_SESSION['loginUser'],"")){
	header('Location: MessageBoard.php');
}
else{ 
	$idd = $_POST['csfr_token'];

	if(isset($_SESSION['TOKEN_TIME']) && isset($_SESSION['CSFR_TOKEN'])){
		$timedif = time() - $_SESSION['TOKEN_TIME'];

		if($timedif >= 0 && $timedif <= 1800){
			if(!strcmp($_SESSION['CSFR_TOKEN'],$idd) && strcmp($_SESSION['CSFR_TOKEN'],"")){
				$_SESSION['TOKEN_TIME'] = time();

				require_once('config.php');
				$id = $_POST['msgID'];
				$Check = $link->prepare('SELECT `Username`,`MessageFile`,`Message` FROM `MsgBoard` WHERE `id`=?');
				$Check->bind_param('i',$id);
				$Check->execute();
				$result = $Check->get_result();
				try{
					$row = mysqli_fetch_array($result);
					if(strcmp($row[0],"") && !strcmp($_SESSION['loginUser'],$row[0])){
						if(strcmp($row[1],"") || strcmp($row[2],"")){
							if(strval($row[1]) != NULL){
								if(unlink('messageFile/'.strval($row[1]))){
									$query = "DELETE FROM `MsgBoard` WHERE `id`='".$id."'";
									if(mysqli_query($link, $query)){
	
									}
									else{
										echo '3';
									}
								}
							}else{
								$query = "DELETE FROM `MsgBoard` WHERE `id`='".$id."'";
								if(mysqli_query($link, $query)){
								}
								else{
									
								}
							}
						}
						else{
							
						}
					}else{
					}
				}catch(Exception $e){
	
				}
				mysqli_close($link);
				header('Location: MessageBoard.php');
			}else{
				header('Location: MessageBoard.php');
			}
		}else{
			session_destroy();
			$_SESSION['overTime'] = 'true';
			header('Location: member.php');
		}
	}else{		
		header('Location: member.php');
	}
}

?>
