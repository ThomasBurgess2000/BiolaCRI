<?php
$equation = $_POST['equation'];

//Outputs lines of equation
foreach ($equation as $x)
{
    echo $x[0] . " ". $x[1]." " . "'" . $x[2]."'<br />";
}
?>