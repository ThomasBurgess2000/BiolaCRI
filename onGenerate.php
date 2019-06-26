<?php

//Connect to database
$db = new mysqli('localhost', 'yourusername', 'yourpassword','demodb');
$funcString = $_POST['funcString'];

//Gets table and gets the columns and sets them to the array 'columns'
$sql = "SELECT * FROM demoTable";
$resultOne = mysqli_query($db, $sql);
$values = $resultOne->fetch_all(MYSQLI_ASSOC);
$columns = array();

if(!empty($values)) {
	$columns = array_keys($values[0]);
}

//Gets table with equation constraints
$sql2 = $funcString;
$resultTwo = mysqli_query($db, $sql2);

//Outputs results as HTML table

echo "<table id='dataTable'>";
	echo "<thead>";
		echo "<tr>";
		foreach($columns as $i)
		{
			echo "<th style='width:100px'>". $i . "</th>";
		}
		echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
//Loops through each row
		while($row = mysqli_fetch_array($resultTwo)) {
			echo "<tr>";
			//Loops through each column in row and outputs as cell in row of table
			foreach($columns as $i) {
				echo "<td style='width:100px'>" . $row[$i] . "</td>";
			}
			echo "</tr>";
		}
	echo "</tbody>";
echo "</table>";
//dropdown

?>
