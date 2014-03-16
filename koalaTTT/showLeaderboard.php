<?php
$user="user";
$password="";
$database="tictactoe";
$server="localhost";

$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");

$query = "SELECT name, rank, wins, losses, draws, points FROM person WHERE rank <= 10 AND rank > 0 ORDER BY rank;";

$result = mysql_query($query) or die("Query failed: ".mysql_error());

echo "<table border='1' align='center'>
<tr>
<th>Name</th>
<th>Rank</th>
<th>Wins</th>
<th>Losses</th>
<th>Draws</th>
<th>Points</th>
</tr>";

while($row = mysql_fetch_array($result))
 {
 echo "<tr>";
 echo "<td>" . $row['name'] . "</td>";
 echo "<td>" . $row['rank'] . "</td>";
 echo "<td>" . $row['wins'] . "</td>";
 echo "<td>" . $row['losses'] . "</td>";
 echo "<td>" . $row['draws'] . "</td>";
 echo "<td>" . $row['points'] . "</td>";
 echo "</tr>";
 }
echo "</table>";

mysql_close($conn);
?>