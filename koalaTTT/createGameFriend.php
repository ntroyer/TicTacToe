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


		  

while(true){
	$query = "SELECT COUNT(*)
		  	  FROM person
		  	  WHERE playing=1 AND p_id!='$id';";
	$result = mysql_query($query) or die("SELECT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	$number = $row[0];
	
	if($number>0){
		$query = "SELECT f2_id
		  	  	  FROM person AND friends
		  	  	  WHERE playing=1 AND p_id!='$id' AND f1_id = '$id';";
		  	  	  
		$result = mysql_query($query) or die("INSERT query failed: ".mysql_error());
		$row = mysql_fetch_row($result);
		$opponent = $row[0];
		
		
		
		$query = "INSERT INTO currentGames SET
		  XID='$id',
		  YID='$opponent',
		  gameState='_________',
		  curTurn='X';";
		  
		mysql_query($query) or die("INSERT query failed: ".mysql_error());
		
		$setGame = "UPDATE person
					SET playing=0
					WHERE p_id='$opponent' OR
					p_id='$id';";
					
		mysql_query($setGame) or die("UPDATE query failed".mysql_error());
		
	}
	
	sleep(2);
	
	$check = "SELECT playing FROM person WHERE p_id='$id';";
	$result = mysql_query($check) or die("SELECT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	if($row[0]==0){
		$number=0;
		break;
	}
}
					

$query = "SELECT gameID FROM currentGames
		  WHERE XID='$id' OR YID='$id';";
		  
$result = mysql_query($query) or die("INSERT query failed: ".mysql_error());
$row =mysql_fetch_row($result);
$gameID=$row[0];
echo $gameID;


?>
