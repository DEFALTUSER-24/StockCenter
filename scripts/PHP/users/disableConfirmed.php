<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/main/sysFunctions.php";

    new SysFunct();
    $_SESSION['confirmed'] = false;
    exit();
