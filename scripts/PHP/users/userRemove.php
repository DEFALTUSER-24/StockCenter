<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/users/usersFunctions.php";

    $access = $_SESSION['uAccess'];

    if ($access !== "ADMIN") {
        EndScript("Acceso denegado.");
    }

    if (empty($_POST['uNameData'])) {
        EndScript("Usuario incorrecto.");
    }

    if($_POST['uNameData'] == $_SESSION['uName']) {
        EndScript("No se puede eliminar a si mismo.");
    }

    $user = new UsersFunctions();

    if (!$user->UserRemove($_POST['uNameData'])) {
        EndScript("Error en la base de datos al eliminar al usuario.");
    }

    EndScriptWithData(true, "Usuario eliminado correctamente.");