<?php session_start();
if($_SESSION['register_result']=="false"){
	echo 'Register Failed!!<br/><br/>';
}else if($_SESSION['register_result'] == "empty"){
	echo 'Input is empty!!<br><br>';
}
$_SESSION['register_result']="";
echo '
<span style=\'font-size:36px\'>Register Page</span>
<br><br>
<form method="POST" action="./register.php">
	<span style="font-size:20px">Username:</span>
	<input type="text" name="username" autocomplete="username" placeholder="Username" required></input>
	<br>
	<span style="font-size:20px">Password:</span>
	<input type="password" name="password" autocomplete="off" placeholder="Password" required></input>
	<br><br>
	<span> Can\'t contain the whitespace and \t</span>
	<br><br>
	<input type="submit" value="Submit"></input>
</form>
	<button onclick="window.location=\'https://b10802115.centralindia.cloudapp.azure.com/member.php\'">Cancel</button>';
?>

