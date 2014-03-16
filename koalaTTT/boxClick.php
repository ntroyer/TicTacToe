<?php
$user="user";
$password="";
$database="tictactoe"; //assumes db has already been created
$server="localhost";

$gameID = $_POST['gameId'];
$userID = $_POST['userID'];
$boxNumber = $_POST['box'];

$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");


$query = "SELECT XID, YID FROM currentGames WHERE gameID='$gameID';";
$result = mysql_query ($query) or die('SELECT query failed: '.mysql_error());
$row = mysql_fetch_row($result);
$player1_ID = $row[0];
$player2_ID = $row[1];


	


if($userID==$player1_ID){
	move($gameID, "X", $boxNumber);
}
else{
	move($gameID, "O", $boxNumber);
}

if($player2_ID==9999){
	$gameState = poll($gameID);
	$cpuCell = cpuMove($gameState);
	move($gameID, "O", $cpuCell);
}


function cpuMove($gameState){
	$cpuCell = rand(0, 8);
	while (validMove($gameState, $cpuCell) == false){
		//echo "Invalid move";
		$cpuCell = rand(0, 8);
	}
	//echo $cpuCell;
	//echo "CPU Move is working";
	//$gameState[$cpuCell] = "O";
	//isWin($gameState);
	//echo $gameState[$cpuCell];
	return $cpuCell;
}



function poll($gameID) {
	// get $gameState from DB
	$query = "SELECT gameState FROM currentGames WHERE gameID='$gameID';";
	$result = mysql_query($query) or die('SELECT query failed: ' . mysql_error());
	$row = mysql_fetch_row($result);
	$gameState = $row[0];
	return (str_split($gameState));
}

function pollCell($gameID, $cell) {
	$gameState = poll($gameID);
	return $gameState[$cell];
}

function pollTurn($gameID) {
	$query = "SELECT curTurn FROM currentGames WHERE gameID='$gameID';";
	$result = mysql_query($query) or die('SELECT query failed: '.mysql_error());
	$row = mysql_fetch_row($result);
	$curTurn = $row[0];
	return $curTurn;
}

function validMove($gameState, $cell){
	if ($gameState[$cell] != 'X' && $gameState[$cell] != 'O'){
		//echo $gameState[$cell];
		return true;
	}
	return false;
}

function move($gameID, $player, $cell) {
	$gameState = poll($gameID);
	$curTurn = pollTurn($gameID);
	// check if player's turn to move
	if ($curTurn != $player){
		return false;
	}
	if (validMove($gameState, $cell)) {
		$gameState[$cell] = $player;
		// update whose turn it is
		if ($curTurn == 'X'){
			$curTurn = 'O';
		}
		else{
			$curTurn = 'X';
		}
		// update gameState in DB
		$gameStateStr = implode("",$gameState);
		$query = "UPDATE currentGames SET gameState='$gameStateStr', curTurn='$curTurn' WHERE gameID='$gameID';";
		mysql_query($query) or die("UPDATE query failed: ".mysql_error());
	}
}





?>