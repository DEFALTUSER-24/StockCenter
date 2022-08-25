<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/stock/stockFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $stockHandler = new StockManager;

    $code = strtoupper(str_replace(".", "_", $_POST['code']));

    if (intval($stockHandler->SearchProduct($code)->num_rows) > 1) {
        EndScript("Varios productos encontrados.");
    }

    if (intval($stockHandler->SearchProduct($code)->num_rows) == 0) {
        EndScript("No se encontró el producto en la base de datos.");
    }

    if(!$stockHandler->ModifyProduct(strtoupper($_POST['desc']), $code, $_POST['units'], $_POST['precio'])) {
        EndScript("Error en la base de datos al modificar el producto.");
    }

    EndScriptWithData(true, "¡Producto modificado correctamente!",
        array(
            0 => strtoupper($_POST['desc']),
            1 => $_POST['units'],
            2 => $_POST['precio']
        )
    );
