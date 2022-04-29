<?php session_start();

if(isset($_GET['fileName']) && $_GET['fileName'] == NULL){
	header('Location: MessageBoard.php');
}

$fileName = $_GET['fileName'];

preg_match('/\/[^\/]+$/i',$out);

if(count($out)){
	$fileName = substr($out[0],1,strlen($out[0]) - 1);
}

if(!file_exists("./messageFile/".$fileName)){	
	header("Location: MessageBoard.php");
}else{
	header("Content-type: ".filetype("./messageFile/".strval($fileName)));//指定類型
	$base = preg_replace('/^[^_]+_/', '', $fileName);
	header("Content-Disposition: attachment; filename=".strval($base));
	readfile("./messageFile/".$fileName);
}

?>
