<?php
//comes from first assignment, modified for this project

$user="user";
$password="";
$database="tictactoe"; //assumes db has already been created
$server="localhost";

$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");


$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT COUNT(*)
		  FROM person
		  WHERE name LIKE '$name' AND email LIKE '$email';";
		  
		  
		  
		  
$result = mysql_query($query) or die("Query failed: ".mysql_error());
$row = mysql_fetch_row($result);
$accountExists = $row[0];


if($accountExists==0){
	createAccount($name, $email, $password); 
	mysql_close($conn);

}
else{
	if(checkPassword($email, $password)){
	
	$id=getID($email);
	mysql_close($conn);
	echo $id;
	}
	else{
		mysql_close($conn);
		echo "0"; //Javascript on client reads this and alerts user
	}
		
}


function checkPassword($email, $password){
	$query = "SELECT password FROM person WHERE email LIKE '$email';";
	$result = mysql_query($query) or die("SELECT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	$correctPassword = $row[0];
	if($password==$correctPassword){
		return true;
	}
	else{
		return false;
	}
}

function getID($email){
	$query = "SELECT p_id 
			  FROM person 
			  WHERE email LIKE '$email';";
	$result = mysql_query($query) or die("INSERT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	$id = $row[0];
	return $id;
}
	


// put data into the database
function createAccount($name, $email, $password){
	
	$query = "INSERT INTO person SET 
	 name='$name',
	 email='$email',
	 password='$password',
	 wins = 0,
	 losses = 0,
	 draws = 0,
	 rank = 0,
	 points = 0,
	 playing =0;";
 
	mysql_query($query) or die("INSERT query failed: ".mysql_error());

	$query = "SELECT p_id FROM person WHERE email LIKE '$email';";
	$result = mysql_query($query) or die("INSERT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	$id = $row[0];
	
	$query = "INSERT INTO rank SET
			  r_id = '$id',
	 	      rank = 0;";
 
	mysql_query($query) or die("INSERT query failed: ".mysql_error());
	
	echo $id;
}


?>