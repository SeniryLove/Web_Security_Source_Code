<?php session_start();

if(strcmp($_COOKIE['csfrToken'],$_SESSION['CSFR_TOKEN'])){
	header('Location: MessageBoard.php');
}
else{

if(isset($_GET['fileName']) && $_GET['fileName'] == NULL){
	header('Location: MessageBoard.php');
}else{

$fileName = $_GET['fileName'];
preg_match('/\/[^\/]+$/i',$fileName,$out);

if(count($out)){
	$fileName = substr($out[0],1,strlen($out[0]) - 1);
	$fileName  = preg_replace('/[^\w\s\.\[\](){}\-_]+/i','',$fileName);
	if(!file_exists("./messageFile/".($fileName))){	
	header("Location: MessageBoard.php");
	}else{
	header("Content-type: ".filetype("./messageFile/".strval($fileName)));//指定類型
	$base = preg_replace('/^[^_]+_/i', '', $fileName);
	header("Content-Disposition: attachment; filename=".strval($base));
	readfile("./messageFile/".$fileName);
	}
}	
else{
$fileName  = preg_replace('/[^\w\s\.\[\](){}\-_]+/i','',$fileName);

if(!file_exists("./messageFile/".($fileName))){	
	header("Location: MessageBoard.php");
}else{
	header("Content-type: ".filetype("./messageFile/".strval($fileName)));//指定類型
	$base = preg_replace('/^[^_]+_/', '', $fileName);
	header("Content-Disposition: attachment; filename=".strval($base));
	readfile("./messageFile/".$fileName);
}
	

}
}
}

?>
