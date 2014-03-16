<?php

$user="user";
$password="";
$database="tictactoe"; //assumes db has already been created
$server="localhost";

$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");

$id = $_POST['id'];

$query = "UPDATE person
		  SET playing=1
		  WHERE p_id='$id';";
		  
mysql_query($query) or die("UPDATE query failed: ".mysql_error());

$query = "SELECT p_id
		  FROM person
		  WHERE playing=1;";
		  
$connected = false;

while($connected==false){
	$result = mysql_query($query) or die("SELECT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	$opponent = $row[0];
	
	if($opponent!=""){
		$setGame = "UPDATE person
					SET playing=0
					WHERE p_id='$opponent' OR
					p_id='$id';";
					
		mysql_query($setGame) or die("UPDATE query failed".mysql_error());
		
		$query = "INSERT into currentGames SET
		  XID='$id',
		  YID='$opponent,
		  gameState='_________';
		  curTurn='X';";
		  
		mysql_query($query) or die("INSERT query failed: ".mysql_error());
	}
	
	sleep(2);
	
	$check = "SELECT playing FROM person WHERE p_id='$id';";
	$row = mysql_fetch_row($check);
	if($row[0]==0){
		$connected==true;
	}
}
					

$query = "SELECT gameID FROM currentGames
		  WHERE XID='$id' OR YID='$id';";
		  
$result = mysql_query($query) or die("INSERT query failed: ".mysql_error());
$row =mysql_fetch_row($result);
$gameID=$row[0];
echo $gameID;

?>

