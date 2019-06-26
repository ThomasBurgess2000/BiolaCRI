<?php

//Connect to database
$db = new mysqli('localhost', 'yourUsername', 'yourPassword','demodb');

//Gets table and gets the columns and sets them to the array 'columns'
$sql = "SELECT * FROM demoTable";
$resultOne = mysqli_query($db, $sql);
$values = $resultOne->fetch_all(MYSQLI_ASSOC);
$columns = array();

if(!empty($values)) {
    $columns = array_keys($values[0]);
}

//Generate dropdown
$menu = "Variable Name: <select class='form-dropdown form-control' id='dropdownVNSC'>";

    foreach($columns as $x)
    {
        $menu .= "<option>" . $x . "</option>";
    }
    // Close menu form
    $menu .= "</select>";
    echo $menu;
?>
