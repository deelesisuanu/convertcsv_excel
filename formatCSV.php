<?php

    require "vendor/autoload.php";
    require "config.php";

    $allowedExts = array("csv");
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');

    $errors= array();

    if( !isset($_FILES['csvFile']) ):
        echo "Please Select File to Upload";
        return;
    endif;
    
    $file_name = $_FILES['csvFile']['name'];
    $file_size = $_FILES['csvFile']['size'];
    $file_tmp = $_FILES['csvFile']['tmp_name'];
    $file_type = $_FILES['csvFile']['type'];

    $temp = explode(".", $file_name);
    $file_ext = end($temp);

    if(in_array( $file_ext, $allowedExts )=== false){
        $errors[0] = "File Type not allowed, please choose a CSV file.";
    }

    if( $file_size > 2097152 ){
        $errors[0] = 'File size must be Less 2 MB';
    }

    $newfilename = round(microtime(true)) . '.' . end($temp);
    
    if( empty($errors) == true ){

        $fileLocation = "temp/" . $newfilename;
        if( move_uploaded_file( $file_tmp, $fileLocation) ):

            $objPHPExcel = $reader->load($fileLocation);
            $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
            $newExcelName = round(microtime(true)) . time();
            $newFileLocation = 'generated/' . $newExcelName . '.xlsx';
            $objWriter->save($newFileLocation);

            $sql = "INSERT INTO infos (fileName) VALUES ('$newFileLocation')";

            if( unlink($fileLocation) && $conn->query($sql) ):
                echo "File Has Been Generated Successfully Please Download From Here <a download href='$newFileLocation'>Download Now</a>";
            endif;

        endif;

     }else{
        echo $errors[0];
     }

?>