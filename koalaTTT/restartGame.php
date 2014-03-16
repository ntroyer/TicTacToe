<?php

$user="user";
$password="";
$database="tictactoe"; //assumes db has already been created
$server="localhost";

$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");


$gameID = $_POST['gameId'];

$query = "UPDATE currentGames SET gameState='_________', curTurn='O' WHERE gameID='$gameID';";

mysql_query($query) or die("UPDATE query failed: ".mysql_error());


?>