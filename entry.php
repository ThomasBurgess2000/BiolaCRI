
<?php
    /* Your password */
    $password = 'yourpassword';

    if (empty($_COOKIE['password']) || $_COOKIE['password'] !== $password) {
        // Password not set or incorrect. Send to login.php.
        header('Location: index.php');
        exit;
	}
?>
<!DOCTYPE html>
<html lang="en" >
<!--
Initially created from 6/4/2019 - 6/13/2019 by Thomas Burgess and Mateo Langston Smith
me@thomasburgess.org
mateolangston@gmail.com
-->
	<head>
        <title>Biola CRI Database</title>
        <meta charset="UTF-8">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
        <link rel="stylesheet" href="css/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
		<script src="js/table2csv.js"></script>
		<!--Our Scripts-->
		<script src="js/output.js"></script>
		<script>
			var equation=[];
			var i = 0;
			$(document).ready(function() {
				$("#variableName").keyup(function () {
    			this.value = this.value.replace(/ /g, "_");
				});
			});
		</script>
    </head>
    
	<body onload="Generate()">
		<div class="container-fluid">
			<h1 class="pageTitle">Biola CRI Database</h1>
            
			
			<div class="row justify-content-center">
				<div class="col-1">
				</div>
				<!--Data Entry Form-->
				<div class="col-md-4 col-xs-8">
					<h2 class="title">Data Entry</h2>
					<form id="formSubmit" method="post">
						<div class="form-group">
							Patient Number: <input type="number" name="patientNumber" id="patientNumber" class="form-control">
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group" id="dropdownVNdiv">
								</div>
							</div>
							<div class="col">
								<div class="form-group" id="textVNdiv">
									New Variable: <input type="text" name="variableName" id="variableName" class="form-control">
								</div>
							</div>
						</div>
						<div class="form-group">
							Value: <input type="text" name="value" id="value" class="form-control">
						</div>
						<div class="form-group">
							<input type="button" id="submitFormData" onclick="SubmitFormData();" value="Submit" class="form-control">
						</div>
						<div class="form-group">
							<input type="reset" class="form-control">
						</div>
					</form>
				</div>
				<div class="col-1">
				</div>
				<!--Equation Form-->
				<div class="col-md-4 col-xs-8">
					<h2 class="title">Search Criteria</h2>
					<form id="submitEQ" method="post">
						<div class="form-group" id="dropdownVNSCdiv">
						</div>
						<div class="form-group">
							Relation: <select id='relation' class="form-control">
								<option value="=">=</option>
								<option value="!=">!=</option>
								<option value=">">></option>
								<option value="<"><</option>
								<option value=">=">>=</option>
								<option value="<="><=</option>
							</select>
						</div>
						<div class="form-group">
							Value: <input type="textarea" name="valueEQ " id="valueEQ" class="form-control">
						</div>
						<div class="form-group">
							<input type="button" id="add" onclick="OutputFormData();" value="Narrow Table By Criteria" class="form-control">
						</div>
						<div class="form-group">
							<input type="button" id="resetEQ" onclick="ResetEQ();" value="Reset to Default" class="form-control">
						</div>
						<div class="form-group">
							<input type="button" id="download" onclick="Download();" value="Download" class="form-control">
						</div>
					</form>
				</div>
				<div class="col-1">
				</div>
			</div>
            
            <!--Shows the information you just entered-->
			<div class="row">
				<div class="col">
					<div id="results"></div>
				</div>
				<div class="col">
					<div id="equation"></div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-3">
				</div>
				<div class="col-6">
					<button class="collapsible"><p class="splitpara">Instructions<span>+</span></p></button>
					<div class="content">
						<p>The "Data Entry" column is for entering new information into the database. 
						You can enter values for existing variables or create new variables. 
						The "Search Criteria" column allows you to narrow the table by various criteria. For example, "cancer_type = brain" and "age > 20" 
						would give you all of the patients over 20 with brain cancer. You can add as many criteria as you want, and reset the parameters with 
						the "Reset" button. Finally, the download button downloads the table as currently narrowed into an Excel-readable CSV file. (Note: <= means less than 
						or equal to, and != means not equal.)</p>
					</div>
				</div>
				<div class="col-3">
				</div>
			</div>
			<script>
			var coll = document.getElementsByClassName("collapsible");
			var i;
			for (i = 0; i < coll.length; i++) {
			coll[i].addEventListener("click", function() {
				this.classList.toggle("active");
				var content = this.nextElementSibling;
				if (content.style.display === "block") {
				content.style.display = "none";
				} else {
				content.style.display = "block";
				}
			});
			}
			</script>
            
			<!--Stop user from adding empty values to equation!-->
			<!--Data Retrieval Form-->
			<h2 class="title">Table</h2>
			<div class="outer-container" id="dataOutput" style="margin-top:20px"></div>
			<!--<p style="text-align:center; margin-top:30px">Enter the patient number, or create a new one. Then enter the variable name to modify (e.g. 'cancer_type'), or create a new one. Finally, type the value of the variable (e.g. 'breast cancer').</p>
			<p style="text-align:center; margin-top:30px">Note: Spaces are not allowed in variable names, but are allowed in variable values. You can enter new variables and patient numbers without specifying anything else, but to enter a value you must specify all fields.</p>
			-->
			<!--Maybe create a popup confirmation if they are overwriting a variable?-->
			
		</div>
    </body>
    
</html>

