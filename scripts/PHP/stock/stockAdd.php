<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/stock/stockFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];
    $stockHandler = new StockManager;
    $target_path = "../../../img/stock/";
    $code = strtoupper(str_replace(".", "_", $_POST['code']));

    if (intval($stockHandler->SearchProduct($code)->num_rows) > 0) {
        EndScript("Ya existe un producto con ese código");
    }

    $FileName = $_FILES['picture']['name'];
    if ($FileName != "")
    {
        $FileLocation = $target_path.basename($FileName);
        if (file_exists($FileLocation)) {
            EndScript("Ya existe un archivo con ese nombre.");
        }

        $FileType = $_FILES['picture']['type'];
        $FileExtension = strtolower(substr($FileName,strrpos($FileName,'.')+1));
        if($FileExtension != "gif" && $FileExtension != "jpg" && $FileExtension != "png") {
            EndScript("El archivo seleccionado no es una imagen.");
        }

        if ($FileName == "no_image.png") {
            EndScript("Nombre de imagen inválido.");
        }

        $FileSize = $_FILES['picture']['size'];
        if($FileSize > 10485760) {
            EndScript("La foto seleccionada es muy pesada - (MAX 10 MB)");
        }

        $FileTemp = $_FILES['picture']['tmp_name'];
        $FileLocation = $target_path.basename($code . "." . $FileExtension);
        if (!move_uploaded_file($FileTemp, $FileLocation)) {
            EndScript("Error interno en el servidor.");
        }

        if (!$stockHandler->InsertProduct($FileLocation, strtoupper($_POST['desc']), $code, intval($_POST['units']), $_POST['precio'])) {
            EndScript("Error en la base de datos al agregar un producto.");
        }

        $width = 800;
        $height = 600;
        list($width_orig, $height_orig) = getimagesize($FileLocation);
        $ratio_orig = $width_orig/$height_orig;
        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($FileLocation);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        imagejpeg($image_p, $FileLocation, 100);

        EndScriptWithData(true,"¡Producto agregado correctamente!",
            array(
                'DESC' => strtoupper($_POST['desc']),
                'CODE' => $code,
                'UNITS' => $_POST['units'],
                'PIC' => '<a data-fancybox="gallery" href="' . $FileLocation .'">' . '<img src="' . $FileLocation . '" class="img-thumbnail" width="60" height="60"></a>',
                'PRICE' => $_POST['precio'],
            )
        );
    }
    else
    {
        if (!$stockHandler->InsertProduct("img/no_image.png", strtoupper($_POST['desc']), $code, intval($_POST['units']), $_POST['precio'])) {
            EndScript("Error en la base de datos al agregar un producto.");
        }

        EndScriptWithData(true, "¡Producto agregado correctamente!",
            array(
                'DESC' => strtoupper($_POST['desc']),
                'CODE' => $code,
                'UNITS' => $_POST['units'],
                'PIC' => '<img src="img/no_image.png" class="img-thumbnail" width="60" height="60"></a>',
                'PRICE' => $_POST['precio']
            )
        );
    }