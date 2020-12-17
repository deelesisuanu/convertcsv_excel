<?php

    require "config.php";
    require "functions.php";

    $sql = "SELECT * FROM infos";
    $result = $conn->query($sql);

    if( $result->num_rows > 0 ):
        while( $rowData = $result->fetch_assoc() ):
            $id = $rowData["id"];
            $fileToDelete = $rowData["fileName"];
            $dateCreated = $rowData["created"];
            $sql = "DELETE FROM infos WHERE id = '$id'";
            $hoursAgo = time_elapsed_hours($dateCreated);
            if($hoursAgo >= 5):
                if( unlink($fileToDelete) && $conn->query($sql) ):
                endif;
            endif;
        endwhile;
    endif;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple DB PHP</title>
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="custom.css">

</head>

<body>

    <div class="container">
        <h1>Convert CSV to Excel:</h1>
        <p> PLEASE NOTE: Files Generated Deletes After 5 Hours </p>
        <form>
            <div>
                <div>
                    <label class="custom-file-label" for="expectedCSV">Select CSV File:</label>
                    <br>
                    <input type="file" accept=".csv" id="expectedCSV" class="custom-file-input">
                </div>
                <br>
                <hr>
                <div>
                    <input type="button" class="btn btn-danger" id="submitButton" value="Convert CSV">
                </div>
            </div>
        </form>

        <br>
        <hr>

        <div id="responseText" class="customStyle"  style="display: none;"></div>

        <hr>
        <br>
        <br>

    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>

    <script>

        let submitBtn = document.getElementById("submitButton");

        if(submitBtn != null){
            submitBtn.addEventListener("click", function() {
                uploadCsv();
            });
        }

        function generateFormData(object) {
            var formData = new FormData();
            Object.keys(object).forEach((key) => formData.append(key, object[key]));
            return formData;
        }

        function uploadCsv(){
            
            $("#responseText").show();
            const csvFile = $("#expectedCSV")[0].files[0];
            const object = {
                "csvFile": csvFile
            };

            let formData = generateFormData(object);

            let requestObject = new XMLHttpRequest();
            requestObject.open( "POST", "formatCSV.php");
            requestObject.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    console.log( this.responseText );
                    $("#responseText").html(this.responseText);
                }
            };
            requestObject.send(formData);

        }

    </script>

</body>

</html>