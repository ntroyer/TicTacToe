<?php
$user="user";
$password="";
$database="tictactoe";
$server="localhost";

$id = $_POST['id'];

$conn = mysql_connect($server,$user,$password)
or die("Unable to connect to MySQL server");

mysql_select_db($database) or die( "Unable to select database");

$query = "SELECT f2_id FROM friends WHERE f1_id = '$id';";
 
$result = mysql_query($query);

echo "<table border='1' align='center'>
<tr>
<th>Name</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
  $t = $row['f2_id'];
  $q = "SELECT name FROM person WHERE p_id = '$t'";
  $res = mysql_query($q);
  $r = mysql_fetch_array($res);
  echo "<tr>";
  echo "<td>" . $r['name'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysql_close($conn);
?>
