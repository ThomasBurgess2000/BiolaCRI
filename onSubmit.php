<?php
//Connect to database
$db = new mysqli('localhost', 'yourUsername', 'yourPassword','demodb');
//Set variables
$tableName = 'demoTable';
$patientID = $_POST['patientNumber'];
$variableName = $_POST['variableName'];
$value = $_POST['value'];

//Check if patientID is empty
if("" != trim($patientID)) {
    //Check if patientID exists...somehow
    $sql  = "SELECT count(1) FROM demoTable WHERE patientID = $patientID";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $patientID);
    $stmt->execute();
    $stmt->bind_result($found);
    $stmt->fetch();
    $stmt -> close();
    $db->next_result();

    if ($found)
    {
        //Select patientID to modify
        echo "Patient ID #". $patientID ." found." . "<br />";
    } else {
        //If not found, add patientID to table
        $insertPN = "INSERT INTO demoTable (patientID) VALUES ($patientID)";
        //Confirms successful entry or warns of error
        if(mysqli_query($db, $insertPN) === TRUE) {
            echo "Patient ID #" . $patientID . " added."."<br />";
        } else {
            echo "Error: " . $insertPN . "<br />" . $db->error;
        }
    }
}

//Checks if variable name is empty
if("" != trim($variableName)) {
    
    //Check if variable name exists
    if (mysqli_query($db, "SELECT $variableName FROM $tableName") == TRUE){
        //if exists, confirm.
        echo "Variable found: " . $variableName . "<br />";
    } else {
        //if doesn't exist, add variable name as column
        mysqli_query($db, "ALTER TABLE $tableName ADD $variableName TEXT");
        echo "New variable created: " . $variableName . "<br />";
    }
}

//Makes sure all fields are populated
if(("" != trim($_POST['patientNumber'])) && ("" != trim($_POST['variableName'])) && ("" != trim($_POST['value']))) {
    //If yes, set variable value to the corresponding patientID row and variableName column
    mysqli_query($db, "UPDATE $tableName SET $variableName = '$value' WHERE patientID = $patientID");
    echo "'".$variableName . "' set to '" . $value . "' for Patient #" . $patientID . ".<br/>";
} else {
    //If no, CONFRONT USER.
    echo "Specify Patient ID and Variable Name, please.";
}

?>
