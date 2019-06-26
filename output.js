function SubmitFormData() {
    //Sets variables based on whether they choose an existing variable or enter a new one
    var patientNumber = $("#patientNumber").val();
    var variableName = $('#variableName').val();
    if(variableName.length>0) {
        variableName = $('#variableName').val();
    } else {
        variableName = $('#dropdownVN :selected').text();
    }
    var value = $("#value").val();
    //Sends the entry to the database
    $.post("onSubmit.php",{patientNumber:patientNumber, variableName:variableName, value:value},
    function(data) {
    //And shows what they entered in the results div
	 $('#results').html(data);
     $('#formSubmit')[0].reset();
     Generate();
    });
}

//Runs on Add click
function OutputFormData() {
    //Takes in variable values for search criteria
    var relation = $("#relation :selected").text();
    var variableNameEQ = $("#dropdownVNSC :selected").text();
    var valueEQ = $("#valueEQ").val();
    //Check if fields are empty
    if(relation!="" && variableNameEQ!="" && valueEQ!="") {
        //Creates the equation array
        equation[i]=[variableNameEQ,relation,valueEQ];
        //Sends the information to onOutput.php
        $.post("onOutput.php",{equation:equation},
        function(data) {
            $('#equation').html(data);
            $('#submitEQ')[0].reset();
        });
        //Increments equation array
        i++;
        Generate();
    }
}
//Runs on Generate click
function Generate() {
    
    //If equation is empty, do this:
    if (equation.length==0)
    {
        var funcString = "SELECT * FROM demoTable ORDER BY patientID";
        $.post("onGenerate.php",{funcString:funcString},
        function(data)
        {
            $('#dataOutput').html(data);
            $('#submitEQ')[0].reset(); 
        });
    }
    //If equation isn't empty, do this:
    else 
    {
        //Creates base string for SQL function that will output rows where equation conditions are met
        var baseFuncString = "SELECT * FROM demoTable WHERE (" + equation[0][0] + equation[0][1] +"'" + equation[0][2] + "')";
        //Make empty string
        var loopFuncString = "";
        //Add additional variable and values to SQL function from the equation array
        for(i=1;i<equation.length;i++) {
            loopFuncString += " AND (" + equation[i][0] + equation[i][1]+"'" + equation[i][2] + "')";
        }
        //Concatenates SQL function and adds order
        var funcString = baseFuncString + loopFuncString + " ORDER BY patientID";
        //Sends the information to onGenerate.php
        $.post("onGenerate.php",{funcString:funcString}, 
        function(data) {
            $('#dataOutput').html(data);
            $('#submitEQ')[0].reset(); 
        });
    }
    Dropdown();
}

function Download() {
    var d = new Date();
    var newFilename=String(d.getFullYear())+String(d.getMonth())+String(d.getDay());
    var relationalOp="=";
    
    for (i=0;i<equation.length;i++) {
        if (equation[i][1]=='>')
        {
            relationalOp="GREATERTHAN";
        }
        else if (equation[i][1]=='<')
        {
            relationalOp="LESSTHAN";
        }
        else if (String(equation[i][1])=='<=')
        {
            relationalOp="LESSTHANOREQUAL";
        }
        else if (equation[i][1]=='>=')
        {
            relationalOp="GREATERTHANOREQUAL";
        }
        
        newFilename= newFilename+ equation[i][0] + relationalOp + equation[i][2];
        if (i!=(equation.length-1))
        {
            newFilename= newFilename+",";
        }
    }
    newFilename=newFilename+".csv";
    let options = {
        "filename": newFilename
    }
    $("#dataTable").table2csv('download',options);
}

//Reset the equation
function ResetEQ() {
    equation.length = 0;
    i=0;
    $('#submitEQ')[0].reset();
    $('#equation').empty();
    Generate();
}

//Every time the table generates, update the dropdowns
function Dropdown() {
    $.post("dropdownvn.php",{}, 
        function(data) {
            $('#dropdownVNdiv').html(data);
    });
    $.post("dropdownvnsc.php",{}, 
        function(data) {
            $('#dropdownVNSCdiv').html(data);
    });
}
