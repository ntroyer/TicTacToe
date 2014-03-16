<?php
$user="user";
$password="";
$database="tictactoe";
$server="localhost";

$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");

$email = $_POST['email'];
$XID = $_POST['id'];
$YID= getID($email);


if($YID > 0){
  addFriend($XID,$YID);
  addFriend($YID,$XID);
  echo "You have a new Friend!";

} else{
  echo "Friend not found";
}

function getID($email){
	$query = "SELECT p_id 
			  FROM person 
			  WHERE email LIKE '$email';";
	$result = mysql_query($query) or die("SELECT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	$id = $row[0];
	return $id;
}


function addFriend($F1ID,$F2ID){
	$query = "INSERT INTO friends SET
			  f1_id='$F1ID', f2_id ='$F2ID';";
	$result = mysql_query($query) or die("INSERT query failed: ".mysql_error());
}

?>
