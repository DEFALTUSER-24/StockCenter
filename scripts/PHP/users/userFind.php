<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/users/usersFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];

    if ($access !== "ADMIN") {
        EndScript("Acceso denegado.");
    }

    $user = new UsersFunctions();

    $query = $user->UserFind($_GET['searchingName']);
    if(intval($query->num_rows) == 0) {
        EndScript("El usuario no existe en el sistema.");
    }

    $obtained = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $obtained['N'] = $row['NAME'];
        $obtained['P'] = $row['PASSWORD'];
        $obtained['A'] = $row['ACCESSTYPE'];
    }

    EndScriptWithData(true, "¡Usuario encontrado!",
        array(
            'NAME' => $obtained["N"],
            'PASSWORD' => openssl_decrypt($obtained["P"], "AES-128-ECB", ENCRYPT_KEY),
            'ACCESS' => $obtained["A"]
        )
    );
