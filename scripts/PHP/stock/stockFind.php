<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/stock/stockFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $stockHandler = new StockManager;

    $code = strtoupper(str_replace(".", "_", $_GET['code']));
    $query = $stockHandler->SearchProduct($code);

    if (intval($query->num_rows) == 0) {
        EndScript("No se encontró el producto en la base de datos.");
    }

    $obtained = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $obtained["PNAME"] = $row["PNAME"];
        $obtained["PUNITS"] = $row["PUNITS"];
        $obtained["PRICE"] = $row["PRICE"];
    }

    EndScriptWithData(true, "¡Producto encontrado!",
        array(
            'DESC' => $obtained["PNAME"],
            'UNITS' => intval($obtained["PUNITS"]),
            'PRICE' => intval($obtained["PRICE"]),
        )
    );