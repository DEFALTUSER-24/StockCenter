<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/users/usersFunctions.php";

    $user = new UsersFunctions();

    $user->UserAdd("name", "pass,", "ADMIN");