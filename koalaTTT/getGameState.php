<?php

$user="user";
$password="";
$database="tictactoe"; //assumes db has already been created
$server="localhost";



$gameID = $_POST['gameID'];


$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");


// obtain state of the game
$query = "SELECT gameState FROM currentGames WHERE gameID='$gameID';";
$result = mysql_query($query) or die('SELECT query failed: '.mysql_error());
$row = mysql_fetch_row($result);
$gameState = str_split($row[0]);

$gameOutcome = isWin($gameState);

$query = "SELECT XID, YID FROM currentGames WHERE gameID='$gameID';";
$result = mysql_query($query) or die('SELECT query failed: '.mysql_error());
$row = mysql_fetch_row($result);
$player1_ID = $row[0];
$player2_ID = $row[1];

if($gameOutcome=="X"){ //Player 1 wins
	addWin($player1_ID);
	addLoss($player2_ID);
	updateRankings();
	echo json_encode(array("X"));
}
else if($gameOutcome=="O"){ //Player 2 wins
	addWin($player2_ID);
	addLoss($player1_ID);
	updateRankings();
	echo json_encode(array("O"));
}
else if($gameOutcome=="T"){ //Game ends in draw
	addDraw($player1_ID);
	addDraw($player2_ID);
	updateRankings();
	echo json_encode(array("T"));
}

else{
	echo json_encode($gameState);

}

function addWin($id){ //Access.1.1
	$query = "SELECT wins FROM person WHERE p_id ='$id';";
	$result = mysql_query ( $query ) or die("INSERT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	$win = $row[0]+1;
	$query = "UPDATE person SET wins = '$win' WHERE p_id = '$id';";
	mysql_query($query) or die("INSERT query failed: ".mysql_error());
	updatePoints($id, 3);

}

function addLoss($id){//Access.1.2
	$query = "SELECT losses FROM person WHERE p_id ='$id';";
	$result = mysql_query ( $query ) or die("INSERT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);
	$loss=$row[0]+1;
	$query = "UPDATE person SET losses = '$loss' WHERE p_id = '$id';";
	mysql_query($query) or die("INSERT query failed: ".mysql_error());
	updatePoints($id, 0);

}

function addDraw($id){//Access.1.3
	$query = "SELECT draws FROM person WHERE p_id ='$id';";
	$result = mysql_query($query) or die("INSERT query failed: ".mysql_error());
	$row = mysql_fetch_row($result);	
	$draws=$row[0]+1;
	$query = "UPDATE person SET draws = '$draws' WHERE p_id = '$id';";
	mysql_query($query) or die("INSERT query failed: ".mysql_error());
	updatePoints($id, 1);
}

function updatePoints($id, $result) {
	$ea = 1 / (1 + pow(10, ($id)/400));
	$npoints = $id + 32*($result - $ea);
	$query = "UPDATE person SET points = '$npoints' WHERE p_id = '$id';";
	mysql_query($query) or die("INSERT query failed: ".mysql_error());
}

function updateRankings($id, $npoints){
	$query = "SELECT points, p_id FROM person ORDER BY points DESC;";
	$allpoints = mysql_query($query) or die("SELECT query failed: ".mysql_error());
	$var = 0;
	while ($row = mysql_fetch_array($allpoints)){
		$var = $var + 1;
		$newID = $row['p_id'];
		$query = "UPDATE person SET rank = '$var' WHERE p_id = '$newID';";
		mysql_query ( $query ) or die("INSERT query failed: ".mysql_error());
	}
}

function isWin($gameState){
	if ($gameState[0] == "X" && $gameState[1] == "X" && $gameState[2] == "X"){
		return "X";
	} else if ($gameState[0] == "X" && $gameState[3] == "X" && $gameState[6] == "X"){
		return "X";
	} else if ($gameState[0] == "X" && $gameState[4] == "X" && $gameState[8] == "X"){
		return "X";
	} else if ($gameState[1] == "X" && $gameState[4] == "X" && $gameState[7] == "X"){
		return "X";
	} else if ($gameState[2] == "X" && $gameState[4] == "X" && $gameState[6] == "X"){
		return "X";
	} else if ($gameState[2] == "X" && $gameState[5] == "X" && $gameState[8] == "X"){
		return "X";
	} else if ($gameState[3] == "X" && $gameState[4] == "X" && $gameState[5] == "X"){
		return "X";
	} else if ($gameState[6] == "X" && $gameState[7] == "X" && $gameState[8] == "X"){
		return "X";
	} else if ($gameState[0] == "O" && $gameState[1] == "O" && $gameState[2] == "O"){
		return "O";
	} else if ($gameState[0] == "O" && $gameState[3] == "O" && $gameState[6] == "O"){
		return "O";
	} else if ($gameState[0] == "O" && $gameState[4] == "O" && $gameState[8] == "O"){
		return "O";
	} else if ($gameState[1] == "O" && $gameState[4] == "O" && $gameState[7] == "O"){
		return "O";
	} else if ($gameState[2] == "O" && $gameState[4] == "O" && $gameState[6] == "O"){
		return "O";
	} else if ($gameState[2] == "O" && $gameState[5] == "O" && $gameState[8] == "O"){
		return "O";
	} else if ($gameState[3] == "O" && $gameState[4] == "O" && $gameState[5] == "O"){
		return "O";
	} else if ($gameState[6] == "O" && $gameState[7] == "O" && $gameState[8] == "O"){
		return "O";
	} else if ($gameState[0] != "_" && $gameState[1] != "_" && $gameState[2] != "_" && $gameState[3] != "_" && $gameState[4] != "_" && $gameState[5] != "_" && $gameState[6] != "_" && $gameState[7] != "_" && $gameState[8] != "_"){
		return "T";
	} else {
		return "_";
	}
}

?>

