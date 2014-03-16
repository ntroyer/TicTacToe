<?php
//comes from first assignment, modified for this project
print("Worked.");
$user="user";
$password="";
$database="tictactoe";
$server="localhost";

$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");

$query = "CREATE TABLE IF NOT EXISTS person
(p_id int NOT NULL auto_increment,
 name text NOT NULL,
 email text NOT NULL,
 password text NOT NULL,
 wins int,
 losses int,
 draws int,
 rank int,
 points int,
 playing int,
PRIMARY KEY (p_id)
) ENGINE=MyISAM;";

mysql_query($query) or die('Query failed: '.mysql_error());

print ("PHP successfully connected to mydatabase, and created a table named 'person'!");



$query = "CREATE TABLE IF NOT EXISTS friends
(f1_id int NOT NULL,
 f2_id int NOT NULL,
 FOREIGN KEY (f1_id) REFERENCES person(p_id),
FOREIGN KEY (f2_id) REFERENCES person(p_id)
)ENGINE=MyISAM;";

mysql_query($query) or die('Query failed: '.mysql_error());

print ("PHP successfully connected to tictactoe, and created a table named 'friends'!");


$query = "CREATE TABLE IF NOT EXISTS currentGames
(XID int NOT NULL,
 YID int NOT NULL,
 gameID int NOT NULL auto_increment,
 gameState text NOT NULL,
 curTurn text,
 PRIMARY KEY (gameID),
 FOREIGN KEY (XID) REFERENCES person(p_id),
FOREIGN KEY (YID) REFERENCES person(p_id)
)ENGINE=MyISAM;";

mysql_query($query) or die ('Query failed: '.mysql_error());
print ("PHP successfully connected to tictactoe, and created a table named 'currentGames'!");


$query = "CREATE TABLE IF NOT EXISTS computerGames
(XID int NOT NULL,
 gameID int NOT NULL auto_increment,
 gameState text NOT NULL,
 PRIMARY KEY (gameID),
 FOREIGN KEY (XID) REFERENCES person(p_id)
)ENGINE=MyISAM;";

mysql_query($query) or die ('Query failed: '.mysql_error());
print ("PHP successfully connected to tictactoe, and created a table named 'computerGames'!");

$query ="CREATE TABLE IF NOT EXISTS rank
(r_id int NOT NULL,
 rank int NOT NULL,
 PRIMARY KEY (r_id),
 FOREIGN KEY (r_id) REFERENCES person(p_id)
)ENGINE=MyISAM;";
 
mysql_query($query) or die ('Query failed: '.mysql_error());
print ("PHP successfully connected to tictactoe, and created a table named 'rank'!");

mysql_close($conn);

?>