<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/bills/billsFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];

    $billsHandler = new bills;
    $query = $billsHandler->SearchForClient($_GET['dataToSearch']);
    
    if (intval($query->num_rows) == 0) {
        echo json_encode("");
        exit();
    }

    $actualIndex = 0;
    while ($row = mysqli_fetch_array($query)) {
        $jsonData[$actualIndex]["name"] = $row["cliName"];
        $jsonData[$actualIndex]["ID"] = $row["cliID"];
        $actualIndex++;
    }

    $jsonData["maxIndex"] = $actualIndex;
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsonData, JSON_FORCE_OBJECT);
    exit();