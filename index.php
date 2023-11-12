<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Similar companies</title>
    <style>
/* Reset some default styles */
body, h1, h2, p, ul, li {
  margin: 0;
  padding: 0;
}

/* Style the input forms */
input[type="text"], input[type="email"], input[type="password"] {
  width: 60%;
  padding: 10px; 
  margin-bottom: 10px;
  box-sizing: border-box;
}

/* Style the buttons */
button {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
    width: 100px;
}

input[type="submit"]{
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
      width: 100px;
}

button:hover {
  background-color: #45a049;
}

/* Style the tables */
table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}

th {
  background-color: #4CAF50;
  color: white;
}

/* Style table rows */
tr:nth-child(even) {
  background-color: #f2f2f2;
}

tr:hover {
  background-color: #e0e0e0;
}
.centered-div {
  width: 60%;
  margin: 0 auto;
  background-color: #f2f2f2; /* Optional background color */
  padding: 20px; /* Optional padding */
  max-width:600px;
  min-width: 400px;
}

.tooltip {
      position: relative;
      display: inline-block;
      cursor: help;
      color: #f2f2f2
    }

    .tooltip::before {
      content: "?";
      font-size: 18px;
      color: rgb(255, 146, 0)
    }

    .tooltip .popup {
      visibility: hidden;
      position: absolute;
      top: 100%;
      left: 50%;
      width:150px;
      transform: translateX(-50%);
      padding: 10px;
      background-color: #f2f2f2;
      color: #333;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      z-index: 1;
    }

    .tooltip:hover .popup {
      visibility: visible;
    }
    </style>
</head>
<body>
    
<div class = "centered-div">
<h2>Find out new company matches!</h2>
<form method="post" onsubmit="handleFormSubmission(event)">
    <div id="input-container">
        <label for="input_values[]">Enter your favorite companies:</label><br>
        <input type="text" id="first_input" name="input_values[]" maxlength="50" required>
        <!-- <button type="button" onclick="removeFirstInput(this)">Remove Company</button> -->
    </div>

    <button type="button" onclick="addInput()">Add Company</button>
    <br><br>

    <input type="submit" name="submitButton" value="Submit" width="150px">
</form><br>
  <button width="150px" id="clearLocalStorageBtn">Clear companies</button>
</div>
<script>
    
    document.getElementById('clearLocalStorageBtn').addEventListener('click', function() {
      // Clear all items from local storage
      sessionStorage.setItem("inputValues","")
      var table = document.getElementById('myTable');
      table.parentNode.removeChild(table);
      window.location.href = window.location.origin;
    });
    
    function addInput() {
        var container = document.getElementById("input-container");
        var newInput = document.createElement("input");
        newInput.type = "text";
        newInput.name = "input_values[]";
        newInput.maxLength = 50;
        newInput.required = true;

        var removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.textContent = "Remove Company";
        removeButton.onclick = function() {
            removeInput(this);
        };

        container.appendChild(document.createElement("br"));
        container.appendChild(newInput);
        container.appendChild(removeButton);
    }

    function removeInput(button) {
        var container = document.getElementById("input-container");
        var inputElement = button.previousSibling; // Get the input element

        // Remove the input and its associated line break and button
        container.removeChild(inputElement.nextSibling); // Remove <br>
        container.removeChild(inputElement); // Remove input
        container.removeChild(button); // Remove button
    }

    function handleFormSubmission(event) {
        event.preventDefault(); // Prevent the default form submission behavior

        // Retrieve and store input values in sessionStorage
        var inputElements = document.getElementsByName("input_values[]");
        var inputValues = Array.from(inputElements).map(input => input.value);
        sessionStorage.setItem("inputValues", JSON.stringify(inputValues));

        // Continue with form submission
        event.currentTarget.submit();
    }

    // Populate input values from sessionStorage on page load
    window.onload = function() {
        var storedValues = sessionStorage.getItem("inputValues");
        if (storedValues) {
            var inputValues = JSON.parse(storedValues);
            var inputElements = document.getElementsByName("input_values[]");

            inputValues.forEach(function(value, index) {
                if (index < inputElements.length) {
                    inputElements[index].value = value;
                } else {
                    addInput();
                    inputElements = document.getElementsByName("input_values[]");
                    inputElements[index].value = value;
                }
            });
        }
    };
</script>

</body>
</html>

<?php
if (isset($_POST["input_values"])) {
    // Process the form submission

    // Retrieve the input values
    $input_values = $_POST['input_values'];

    // Display the table
    echo "<br><br><div class=\"centered-div\"><h1>Possible matches</h1></div>";
    echo "<table id=\"myTable\">";

    foreach ($input_values as $value) {
        $output_python_command = "";
 //       var_dump('python3 tinderache.py "'.$value.'"');
        exec('python3 tinderache.py "'.$value.'"', $output_python_command);
        $lists_for_output = "";

        foreach($output_python_command as $output){
            $output = explode(",", $output);

            
$output[0] = substr_replace($output[0], "", 0, 1);
// Remove the last character
$output[0] = substr_replace($output[0], "", -1);
            $lists_for_output .= "<tr>
            <td>$output[0]</td>";
            
            if($output[1] == "-1"){
                $lists_for_output .= "<td>GPT couldn't make a risk score</td>";
            }else{
                $lists_for_output .= "<td>$output[1]/100";
                if($output[3] == 1){
                    $lists_for_output .= "  <div class=\"tooltip\">
                    <div class=\"popup\">
                      Didn't find all data necessarry for generating an accurate risk score.
                    </div>
                  </div>";
                }

                $lists_for_output .= "</td>";
            }
            $lists_for_output .= "
            <td><a href=$output[2]>$output[0]'s company website</td>
            </tr>";
        }

        echo "<tr>
                <td>Similar companies to $value</td>
                <td>
                <table>
                    <th>Company name</th>
                    <th>Risk score</th>
                    <th>Website url</th>
                    " . $lists_for_output . "
                    </table>
                </td>
              </tr>";
    }        
    echo "</table>";
}

?>
