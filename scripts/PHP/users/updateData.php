<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/users/usersFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $accessUpdate = "";

    if ($user == $_POST['old'] && $_SESSION['confirmed'] === false) {
        EndScript("Acceso denegado.");
    }

    $accessUpdate = $user == $_POST['old'] ?
        $access :
        $_POST['nAccess'];

    if ($accessUpdate == "") {
        EndScript("Error interno.");
    }

    $user = new UsersFunctions();

    if (!$user->ModifyData($_POST['old'], $_POST['name'], $_POST['pass'], $accessUpdate)) {
        EndScript("Error en la base de datos.");
    }

    if ($user == $_POST['old'])
    {
        $_SESSION['confirmed'] = false;
        EndScriptWithData(true, "Datos modificados correctamente, se cerrará la sesión.");
    }
    else
    {
        EndScriptWithData(true, "¡Datos modificados correctamente!",
            array(
                'NAME' => $_POST['name'],
                'PASS' => $_POST['pass'],
                'ACCESS' => $accessUpdate
            )
        );
    }

