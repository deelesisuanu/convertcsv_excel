<?php

    $serverName = "localhost";
    $user = "root";
    $password = "";
    $dbName = "convertcsv";

    $conn = new mysqli($serverName, $user, $password, $dbName);

    if( $conn->connect_errno ){
        echo "Connection to Server Failed: ` $conn->connect_errno ` ";
        return;
    }

?>