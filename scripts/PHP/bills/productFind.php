<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/stock/stockFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $stockHandler = new StockManager;
    $obtained = ["Consumidor final"];

    $data = strtoupper(str_replace(".", "_", $_GET['dataToSearch']));
    $query = $stockHandler->SearchProduct($data, false);
    
    if (intval($query->num_rows) == 0) {
        echo json_encode(array_values($obtained));
        exit();
    }

    $actualIndex = 0;
    while ($row = mysqli_fetch_array($query)) {
        $jsonData[$actualIndex]["name"] = $row["PNAME"];
        $jsonData[$actualIndex]["code"] = $row["PCODE"];
        $jsonData[$actualIndex]["units"] = intval($row["PUNITS"]);
        $jsonData[$actualIndex]["price"] = intval($row["PRICE"]);
        $actualIndex++;
    }
    $jsonData["maxIndex"] = $actualIndex;
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsonData, JSON_FORCE_OBJECT);
    exit();