<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/users/usersFunctions.php";

    $userName = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $user = new UsersFunctions();

    $result = $user->CheckCredentials($userName, $_POST['pass'], false);

    if(!$result) {
        EndScript("Error al validar datos.");
    }

    $_SESSION['confirmed'] = true;

    EndScriptWithData(true, "Identidad confirmada",
        array(
            'NAME' => $result['NAME'],
            'PASS' => openssl_decrypt($result['PASSWORD'], "AES-128-ECB", ENCRYPT_KEY),
            'ACCESS' => $result['ACCESSTYPE']
        )
    );
