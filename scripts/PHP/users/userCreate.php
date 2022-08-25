<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/users/usersFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $user = new UsersFunctions();

    if ($access !== "ADMIN") {
        EndScript("Acceso denegado.");
    }

    if (empty($_POST['uName']) || empty($_POST['uPassword']) || empty($_POST['uAccess'])) {
        EndScript("Hay campos vacíos.");
    }

    if($user->UserFind($_POST['uName'])->num_rows > 0) {
        EndScript("El usuario ya existe en el sistema.");
    }

    $name = $_POST['uName'];
    $pass = $_POST['uPassword'];
    $access = $_POST['uAccess'];

    if (!$user->UserAdd($name, $pass, $access)) {
        EndScript("Error en la base de datos al crear un usuario.");
    }

    EndScriptWithData(true, "Usuario creado correctamente.",
        array(
            'NAME' => $name,
            'PASS' => $pass,
            'ACCESS' => $access
        )
    );