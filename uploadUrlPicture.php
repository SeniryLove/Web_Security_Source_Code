<?php session_start();
if($_SESSION['loginUser'] == NULL || !isset($_SESSION['loginUser']) || !isset($_POST['urlPicture']) || $_POST['urlPicture'] == NULL){
	header("Location: member.php");
}
$idd = $_POST['csfr_token'];

if(isset($_SESSION['TOKEN_TIME']) && isset($_SESSION['CSFR_TOKEN'])){
	$timedif = time() - $_SESSION['TOKEN_TIME'];

	if($timedif >= 0 && $timedif <= 1800){
		if(!strcmp($_SESSION['CSFR_TOKEN'],$idd) && strcmp($_SESSION['CSFR_TOKEN'],"")){
			$_SESSION['TOKEN_TIME'] = time();
			preg_match('/^http:\/\/|^https:\/\//i',$_POST['urlPicture'],$checkList);
			if($checkList[0] == NULL){
				$_SESSION['uploadState'] = "Invalid Path";
				header("Location: accountSetting.php");
			}
			else{
				require_once('config.php');
				$sql_getMug = $link->prepare('SELECT MugFile FROM `UsersInformation` WHERE username=?');
				$sql_getMug->bind_param('s',$_SESSION['loginUser']);
				$sql_getMug->execute();
				$result = $sql_getMug->get_result();
				try{
				      	$row = mysqli_fetch_array($result);
				      	$sql_getisUrlPicture= $link->prepare('SELECT `isUrlPicture` FROM `UsersInformation` WHERE username=?');
				      	$sql_getisUrlPicture->bind_param('s',$_SESSION['loginUser']);
				      	$sql_getisUrlPicture->execute();
				      	$result = $sql_getisUrlPicture->get_result();
				      	try{
	
				      		$row2 = mysqli_fetch_array($result);
				      		if(isset($row2) && !$row2[0]){
				      			ob_start();
					    		if(readfile($_POST['urlPicture'])){
					    			$img=ob_get_contents();
					    			$checkimg = strtolower($img);
					    			preg_match('/!doctype html/i',$checkimg,$checkResult);
					    			if($checkResult != NULL){
					    				$_SESSION['uploadState'] = "Failed to get image of url: ".htmlentities(strval($_POST['urlPicture']));
					    			}else{
					    				if(strval($row[0]) != NULL)
					    					unlink('upload/'.strval($row[0]));
					    				$prefix = bin2hex(random_bytes(16));
					    				$user = $_SESSION['loginUser'];
					    				$filename = strval($prefix).'_urlImage.jpg';
					    				$sql_updateMug = $link->prepare('UPDATE `UsersInformation` SET `MugFile`=?,`isUrlPicture`=1,`UrlPicture`=? WHERE `username`=?');
					    				$sql_updateMug->bind_param('sss',$filename,$_POST['urlPicture'],$user);
					    				$sql_updateMug->execute();
					    				$newImage=fopen('upload/'.strval($filename),"w");
					    				fwrite($newImage,$img);
					    				fclose($newImage);
					    				$_SESSION['uploadState'] = 'sucessful';
					    			}
					    		}else{
					    			$_SESSION['uploadState'] = "Failed to get image of url: ".htmlentities(strval($_POST['urlPicture']));
					    		}
					    		ob_end_clean();
				      		}
				      		else if(isset($row2) && $row2[0]){
							$sql_getUrlPicture = $link->prepare('SELECT `UrlPicture` FROM `UsersInformation` WHERE `username`=?');
							$sql_getUrlPicture->bind_param('s',$_SESSION['loginUser']);
							$sql_getUrlPicture->execute();
							$result = $sql_getUrlPicture->get_result();
							try{
								$row3 = mysqli_fetch_array($result);
								if(isset($row3) && strval($row3[0]) != NULL){
									if(strcmp(strval($row3[0]),strval($_POST['urlPicture'])) == 0){
										$_SESSION['uploadState'] = "exist";
									}else{
										ob_start();
										if(readfile($_POST['urlPicture'])){
											$img=ob_get_contents();
											$checkimg = strtolower($img);
											preg_match('/!doctype html/i',$checkimg,$checkResult);
											if($checkResult != NULL){
												$_SESSION['uploadState'] = "Failed to get image of url: ".htmlentities(strval($_POST['urlPicture']));
											}
											else{
												if(strval($row[0]) != NULL)
													unlink('upload/'.strval($row[0]));
												$prefix = bin2hex(random_bytes(16));
												$user = $_SESSION['loginUser'];
												$filename = strval($prefix).'_urlImage.jpg';
												$sql_updateMug = $link->prepare('UPDATE `UsersInformation` SET `MugFile`=?,`UrlPicture`=? WHERE `username`=?');
												$sql_updateMug->bind_param('sss',$filename,$_POST['urlPicture'],$user);
												$sql_updateMug->execute();
												$newImage=fopen('upload/'.strval($filename),"w");
												fwrite($newImage,$img);
												fclose($newImage);
												$_SESSION['uploadState'] = 'sucessful';
											}
										}
										else{
											$_SESSION['uploadState'] = "Failed to get image of url: ".htmlentities(strval($_POST['urlPicture']));
										}
										ob_end_clean();
									}
								}else if(isset($row3) && strval($row3[0]) == NULL){
									ob_start();
									if(readfile($_POST['urlPicture'])){
										$img=ob_get_contents();
										$checkimg = strtolower($img);
										preg_match('/!doctype html/i',$checkimg,$checkResult);
										if($checkResult != NULL){
											$_SESSION['uploadState'] = "Failed to get image of url: ".htmlentities(strval($_POST['urlPicture']));
										}else{
											if(strval($row[0]) != NULL)
												unlink('upload/'.strval($row[0]));
											$prefix = bin2hex(random_bytes(16));
											$user = $_SESSION['loginUser'];
											$filename = strval($prefix).'_urlImage.jpg';
											$sql_updateMug = $link->prepare('UPDATE `UsersInformation` SET `MugFile`=?,`UrlPicture`=? WHERE `username`=?');
											$sql_updateMug->bind_param('sss',$filename,$_POST['urlPicture'],$user);
											$sql_updateMug->execute();
											$newImage=fopen('upload/'.strval($filename),"w");
											fwrite($newImage,$img);
											fclose($newImage);
											$_SESSION['uploadState'] = 'sucessful';
										}
									}
									else{
										$_SESSION['uploadState'] = "Failed to get image of url: ".htmlentities(strval($_POST['urlPicture']));
									}
									ob_end_clean();
								}else{
									$_SESSION['uploadState'] = 'failed';
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
				      		else{
					    		$_SESSION['uploadState'] = 'failed';
				      		}
				     		mysqli_close($link);
				      		header("Location: accountSetting.php");
					}catch (Exception $e){
				      		echo 'Caught exception: ', $e->getMessage(), '<br>';
				      		echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
				       	}
				      	mysqli_close($link);
				      	header("Location: accountSetting.php");
	
				}catch(Exception $e){
					echo 'Caught exception: ', $e->getMessage(), '<br>';
					echo 'Check credentials in config file at: ', $Mysql_config_location, '\n';
					header("Location: accountSetting.php");
				}
				mysqli_close($link);
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
