<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/stock/stockFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $stockHandler = new StockManager;
    
    $code = strtoupper(str_replace(".", "_", $_GET['code']));
    $query = $stockHandler->SearchProduct($code);

    if (intval($query->num_rows) == 0) {
        EndScript("No se encontró el producto.");
    }

    if (intval($query->num_rows) > 1) {
        EndScript("Varios productos encontrados.");
    }

    $result = mysqli_fetch_array($query);
    if (strpos($result["PIC"], 'no_image.png') !== false)
    {
        if(!$stockHandler->RemoveProduct($code)) {
            EndScript("Error en la base de datos al eliminar el producto.");
        }

        EndScriptWithData(true, "¡Producto eliminado correctamente!");
    }
    else
    {
        if(!file_exists($result['PIC'])) {
            EndScript("El producto contiene una foto pero no se encuentra en el servidor.");
        }

        if(!unlink($result['PIC'])) {
            EndScript("Error al eliminar el producto, no se pudo eliminar la foto.");
        }

        if (!$stockHandler->RemoveProduct($code)) {
            EndScript("Error en la base de datos al eliminar el producto.");
        }

        EndScriptWithData(true, "¡Producto eliminado correctamente!");
    }
