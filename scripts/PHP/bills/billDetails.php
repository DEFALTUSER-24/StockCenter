<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/bills/billsFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $billNumber = $_POST['factNum'];
    $billHandler = new bills;

    if ($billNumber == "") {
        EndScript("Numero de factura invalido.");
    }

    $query = $billHandler->FindBill($billNumber);
    if (!$query) {
        EndScript("No se encontró la factura en la base de datos.");
    }

    $result = mysqli_fetch_array($query);
    if ($result['billType'] != "Factura") {
        EndScript("Solo se peude buscar una factura.");
    }

    EndScriptWithData(true, "¡Factura encontrada!",
        array(
            'details' => $result['billProducts']
        )
    );