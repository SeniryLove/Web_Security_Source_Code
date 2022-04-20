<?php session_start();
if($_SESSION['loginUser'] == "" || !isset($_SESSION['loginUser'])){
	header("Location: member.php");
}

$idd = $_POST['csfr_token'];

if(isset($_SESSION['TOKEN_TIME']) && isset($_SESSION['CSFR_TOKEN'])){
	$timedif = time() - $_SESSION['TOKEN_TIME'];

	if($timedif >= 0 && $timedif <= 1800){
		if(!strcmp($_SESSION['CSFR_TOKEN'],$idd) && strcmp($_SESSION['CSFR_TOKEN'],"")){
			$_SESSION['TOKEN_TIME'] = time();
			if($_FILES['mugShot']['error']){
				$err = $_FILES['mugShot']['error'];
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
				$_SESSION['uploadState'] = 'Failed, '.$errlog;
				header("Location: accountSetting.php");
			}else{
				require_once('config.php');
				$sql_getMug = $link->prepare('SELECT MugFile FROM `UsersInformation` WHERE username=?');
				$sql_getMug->bind_param('s',$_SESSION['loginUser']);
				$sql_getMug->execute();
				$result = $sql_getMug->get_result();
			$sss = NULL;
				try{
				      	$row = mysqli_fetch_array($result);
				      	$sql_getisUrlPicture = $link->prepare('SELECT isUrlPicture FROM `UsersInformation` WHERE username=?');
				      	$sql_getisUrlPicture->bind_param('s',$_SESSION['loginUser']);
				      	$sql_getisUrlPicture->execute();
				      	$result = $sql_getisUrlPicture->get_result();
	
				      	try{
				      		$row2 = mysqli_fetch_array($result);
				      		if(isset($row2) && strval($row2[0])){
				      			preg_match('/[_][\w\s\[\].-]+/i',$row[0],$outputArray);
				      			if(isset($row) &&
				      				file_exists('upload/'.strval($row[0]))){
				      				if(strcmp(preg_replace('/^[_]/i','',$outputArray[0]),$_FILES['mugShot']['name'])==0 && $_FILES['mugShot']['name'] != NULL){
				      					$_SESSION['uploadState'] = 'exist';
				      				}else{
				      					preg_match("/[^\w\s\[\].-]+|[\^\`]+/i",$_FILES['mugShot']['name'],$errorList);
				      					preg_match("/(\.(p|P)(n|N)(g|G))$|(\.(j|J)(p|P)(e|E)?(g|G))$|(\.(g|G)(i|I)(f|F))$/i",$_FILES['mugShot']['name'],$forName);
	
					      				if(strval($errorList[0]) != NULL || explode('.',$_FILES['mugShot']['name'])[0] == NULL || $forName[0] == NULL){
					      					$_SESSION['uploadState'] = 'format failed, '.strval($_FILES['mugShot']['name']).', '.strval($errorList[0]);
					      				}else{		  
					      					if($row[0] == NULL){
					      						$prefix = bin2hex(random_bytes(8));
					      						$user = $_SESSION['loginUser'];
					      						$filename = strval($prefix).'_'.strval($_FILES['mugShot']['name']);
					      						$sql_updateMug = $link->prepare('UPDATE `UsersInformation` SET `MugFile`=?,`UrlPicture`=?,`isUrlPicture`=0 WHERE `username`=?');
					      						$sql_updateMug->bind_param('sss',$filename,$sss,$user);
					      						$sql_updateMug->execute();
					      						move_uploaded_file($_FILES['mugShot']['tmp_name'], 'upload/'.$filename);
					      						$_SESSION['uploadState'] = 'sucessful';
					      					}
					      					else if($row[0] != NULL && unlink('upload/'.strval($row[0]))){
					      						require_once('config.php');
					      						$prefix = bin2hex(random_bytes(8));
					      						$user = $_SESSION['loginUser'];
									   		
						      					$filename = strval($prefix).'_'.strval($_FILES['mugShot']['name']);
	
						      					$sql_updateMug = $link->prepare('UPDATE `UsersInformation` SET `MugFile`=?,`UrlPicture`=?,`isUrlPicture`=0 WHERE `username`=?');
						      					$sql_updateMug->bind_param('sss',$filename,$sss,$user);
						      					$sql_updateMug->execute();
						      					move_uploaded_file($_FILES['mugShot']['tmp_name'], 'upload/'.$filename);
						      					$_SESSION['uploadState'] = 'sucessful';		     		  
					      					}	     
					      					else{
					      						$_SESSION['uploadState'] = 'DELETE FILE ERROR!!';
					      					}
					      				}
				      				}
				      			}
	
				      		}else if(isset($row2) && !strval($row2[0])){
				      			preg_match('/[_][\w\s\[\].-]+/i',$row[0],$outputArray);
				      			if(isset($row) &&
				      				file_exists('upload/'.strval($row[0]))){
				      				if(strcmp(preg_replace('/^[_]/i','',$outputArray[0]),$_FILES['mugShot']['name'])==0 && $_FILES['mugShot']['name'] != NULL){
				      					$_SESSION['uploadState'] = 'exist';
				      				}else{
				      					preg_match("/[^\w\s\[\].-]+|[\^\`]+/i",$_FILES['mugShot']['name'],$errorList);
				      					preg_match("/(\.(p|P)(n|N)(g|G))$|(\.(j|J)(p|P)(e|E)?(g|G))$|(\.(g|G)(i|I)(f|F))$/i",$_FILES['mugShot']['name'],$forName);
	
					      				if(strval($errorList[0]) != NULL || explode('.',$_FILES['mugShot']['name'])[0] == NULL || $forName[0] == NULL){
					      					$_SESSION['uploadState'] = 'format failed, '.strval($_FILES['mugShot']['name']).', '.strval($errorList[0]);
					      				}else{		  
					      					if($row[0] == NULL){
					      						$prefix = bin2hex(random_bytes(8));
					      						$user = $_SESSION['loginUser'];
					      						$filename = strval($prefix).'_'.strval($_FILES['mugShot']['name']);
					      						$sql_updateMug = $link->prepare('UPDATE `UsersInformation` SET `MugFile`=?,`UrlPicture`=? WHERE `username`=?');
					      						$sql_updateMug->bind_param('sss',$filename,$sss,$user);
					      						$sql_updateMug->execute();
					      						move_uploaded_file($_FILES['mugShot']['tmp_name'], 'upload/'.$filename);
					      						$_SESSION['uploadState'] = 'sucessful';
					      					}
					      					else if($row[0] != NULL && unlink('upload/'.strval($row[0]))){
					      						require_once('config.php');
					      						$prefix = bin2hex(random_bytes(8));
					      						$user = $_SESSION['loginUser'];
									   		
						      					$filename = strval($prefix).'_'.strval($_FILES['mugShot']['name']);
	
						      					$sql_updateMug = $link->prepare('UPDATE `UsersInformation` SET `MugFile`=?,`UrlPicture`=? WHERE `username`=?');
						      					$sql_updateMug->bind_param('sss',$filename,$sss,$user);
						      					$sql_updateMug->execute();
						      					move_uploaded_file($_FILES['mugShot']['tmp_name'], 'upload/'.$filename);
						      					$_SESSION['uploadState'] = 'sucessful';		     		  
					      					}	     
					      					else{
					      						$_SESSION['uploadState'] = 'DELETE FILE ERROR!!';
					      					}
					      				}
				      				}
				      			}
					      	}
				      		mysqli_close($link);
				      		header("Location: accountSetting.php");
				      	}catch(Exception $e){
				      		echo 'Caught exception: ', $e->getMessage(), '<br>';
				      		echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
				      	}
				      	mysqli_close($link);
				      	header("Location: accountSetting.php");
				}
				catch (Exception $e){
					echo 'Caught exception: ', $e->getMessage(), '<br>';
					echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
				}
				mysqli_close($link);
				header("Location: accountSetting.php");
			}
		}else{
			header("Location: accountSetting.php");
		}		
	}else{
		session_destroy();
		$_SESSION['overTime'] = 'true';
		header("Location: member.php");
	}
}else{
	session_destroy();
	header("Location: member.php");
}



?>
