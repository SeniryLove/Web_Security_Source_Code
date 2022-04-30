<?php session_start();


function ConvertBBcode2Htmlentities($text) {
	
	$text  = htmlspecialchars($text, ENT_QUOTES, $charset);

$find = array(
		'~\[b\](.*?)\[/b\]~s',
		'~\[i\](.*?)\[/i\]~s',
		'~\[u\](.*?)\[/u\]~s',
		'~\[color=(.*?)\](.*?)\[/color\]~s',
		'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
	);

	$replace = array(
		'<b>$1</b>',
		'<i>$1</i>',
		'<u>$1</u>',
		'<span style="color:$1;">$2</span>',
		'<img src="$1"/>'
	);


return preg_replace($find,$replace,$text);
}

function SaveMessageFile(){
	$saveName = $_FILES['leaveFile']['name'];
	preg_match('/[^\w\s\[\]\-_\.{}\[\]()]+/i',$saveName,$invalidChar);
	if(count($invalidChar) || !strcmp($saveName,"")){
		return ;
	}
	$fileName = bin2hex(random_bytes(16)).'_'.$saveName;
	move_uploaded_file($_FILES['leaveFile']['tmp_name'],'messageFile/'.$fileName);
	return $fileName;
}
if(strcmp($_COOKIE['csfrToken'],$_SESSION['CSFR_TOKEN'])){
	header('Location: member.php');
}
else{

if(!isset($_SESSION['loginUser']) || $_SESSION['loginUser'] == NULL || !isset($_POST['Message']) || $_POST['Message'] == NULL){
	header("Location: MessageBoard.php");
}
else{
	$idd = $_POST['csfr_token'];

	if(isset($_SESSION['TOKEN_TIME']) && isset($_SESSION['CSFR_TOKEN'])){
		$timedif = time() - $_SESSION['TOKEN_TIME'];
		if($timedif >= 0 && $timedif <= 1800){
			if(!strcmp($_SESSION['CSFR_TOKEN'],$idd)){
				$_SESSION['TOKEN_TIME'] = time();
				if($_FILES['leaveFile']['error'] != 4 && $_FILES['leaveFile']['error'] != 0){
					$err = $_FILES['leaveFile']['error'];
					switch($err){
					case 1:
						$errlog = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
						break;
					case 2:
						$errlog = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
						break;
					case 3:
						$errlog = 'The uploaded file was only partially uploaded.';
						break;
					case 4:
						$errlog = 'No file was uploaded.';
						break;
				case 6:
					$errlog = 'Missing a temporary folder.';
					break;
				case 7:
					$errlog = 'Failed to write file to disk.';
					break;

					}
					$_SESSION['LeaveState'] = 'Failed, '.$errlog;
					header('Location: MessageBoard.php');
					echo strval($errlog);
				}else{


					require_once('config.php');

					#preg_match('/[^\w\s\[\]\-\=\#\&\.]+/i',$_POST['Message'],$InvalidChar);
					#if(isset($InvalidChar) && $InvalidChar[0] != NULL){
					#	$_SESSION['LeaveState'] = "Invalid Message".strval($InvalidChar[0]);
					#}
					#else
					{
						$stack = array();
						$stackIndex = array();
						$allBBcodeIndex = array();
						$allBBcode = array();
						$index = 0;
						$times = 0;
						$tmp = ConvertBBcode2Htmlentities(($_POST['Message']));
						$filename = SaveMessageFile();
						if($_FILES['leaveFile']['size'] && $filename == NULL){
							$_SESSION['LeaveState'] = "Error: upload file name is wrong format or empty";
						}else{
							$sql_leaveMsg = $link->prepare("INSERT INTO `MsgBoard` (`Username`, `Message`,`MessageFile`) VALUES (?,?,?)");
							$sql_leaveMsg->bind_param('sss',$_SESSION['loginUser'],$tmp,$filename);
							$sql_leaveMsg->execute();
							mysqli_close($link);
							$_SESSION['LeaveState'] = "Sucessfully";
						}
					}
				}
			}
			header("Location: MessageBoard.php");
		}else{
			session_destroy();
			$_SESSION['overTime'] = 'true';
			header("Location: member.php");
		}
	}else{
		header("Location: member.php");
	}
}
}
?>

