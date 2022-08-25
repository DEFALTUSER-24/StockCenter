<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/bills/billsFunctions.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/stock/stockFunctions.php";

    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];

    $billHandler = new bills;
    $stockHandler = new StockManager;
    $factType = $_POST['type'];

    if ($factType == "Factura")
    {
        $factClient = $_POST['cliName'];
        $factClientID = $_POST['cliID'];
        $db = new DBManager;
        $productsData = json_decode($_POST['product'], true);
        $amountsData = json_decode($_POST['productAmount'], true);
        $perUnitData = json_decode($_POST['pricePerUnit'], true);
        $products = array();
        
        $arraySize = count($productsData);
        $errorAtStock = false;
        $errorDesc = "";
        
        //CHEQUEA SI FALTA STOCK DE ALGUN PRODUCTO O SI HAY ALGUN ERROR EN LA BASE DE DATOS (COMO QUE EL PRODUCTO NO EXISTE O QUE SE ENCONTRARON 2 O MAS PRODUCTOS CON ESE NOMBRE)
        for($i = 0; $i < $arraySize; ++$i) {
            $query = $stockHandler->SearchProduct($db->DBEscape($productsData[$i]), false);
            if ($query->num_rows == 1)
            {
                $result = mysqli_fetch_array($query);
                if (intval($amountsData[$i]) > intval($result["PUNITS"]) || intval($amountsData[$i]) <= 0) { $errorDesc = "Cantidad inválida en el producto '" . $productsData[$i] . "' (probablemente no hay stock)."; $errorAtStock = true; break; }
            }
            elseif ($query->num_rows > 1) {
                $errorDesc = "Se encontraron 2 o mas productos con el nombre '" . $productsData[$i] . "'.";
                $errorAtStock = true;
                break;
            }
            elseif ($query->num_rows == 0) {
                $errorDesc = "No se encontro al producto '" . $productsData[$i] . "' en la base de datos.";
                $errorAtStock = true;
                break;
            }
            elseif (!$query) {
                $errorDesc = "Error en la base de datos al buscar el producto '" . $productsData[$i] . "'.";
                $errorAtStock = true;
                break;
            }
        }

        if ($errorAtStock) {
            EndScript($errorDesc);
        }

        for($x = 0; $x < $arraySize; ++$x) {
            if ($x >= 0 && $x < $arraySize) { array_push($products, $db->DBEscape($productsData[$x]) . "|" . $db->DBEscape($amountsData[$x]) . "|" . $db->DBEscape($perUnitData[$x]) . ";"); }
            elseif ($x >= $arraySize) { array_push($products, $db->DBEscape($productsData[$x]) . "|" . $db->DBEscape($amountsData[$x]) . "|" . $db->DBEscape($perUnitData[$x])); }
        }

        $cliQuery = $billHandler->SearchForClient($factClientID);
        if ($cliQuery->num_rows == 0)
        {
            $result = $billHandler->AddClient($factClient, $factClientID);
            if (!$result) {
                EndScript("Error al agregar al nuevo cliente en la base de datos.");
            }
            elseif ($result->num_rows == 0) {
                EndScript("Error al agregar al nuevo cliente en la base de datos.");
            }
        }
        elseif (!$cliQuery) {
            EndScript("Error al agregar al nuevo cliente en la base de datos.");
        }

        $query = $billHandler->CreateBill($factType, $products, $user, $factClient, $factClientID);
        if ($query)
        {
            for($z = 0; $z < $arraySize; ++$z) {
                $query = $stockHandler->SearchProduct($db->DBEscape($productsData[$z]), false);
                $result = mysqli_fetch_array($query);

                $stockHandler->RemoveStock($result['PCODE'], intval($amountsData[$z]));
            }

            $jsonData['success'] = true;
            $jsonData['message'] = "¡Factura creada correctamente!";
            $jsonData['data']['actualFact'] = intval($billHandler->ActualBill()) + 1;
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($jsonData, JSON_FORCE_OBJECT);
            exit();
        }
        elseif ($query === "INVALIDBILL") {
            EndScript("Tipo de factura inválido.");
        }
        elseif ($query === "NOPRODUCTS") {
            EndScript("No hay productos en la factura.");
        }
        elseif (!$query) {
            EndScript("Error en la base de datos al agregar una factura.");
        }
    }
    elseif ($factType == "Devolucion")
    {
        $factNumber = $_POST['factNum'];
        $result = $billHandler->FindBill($factNumber);
        if ($result)
        {
            $errorAtStock = false;
            $errorDesc = "";
            while ($row = mysqli_fetch_assoc($result))
            {
                if ($row["billType"] == "Anulada")
                {
                    $errorDesc = "La factura ya estaba anulada anteriormente.";
                    $errorAtStock = true;
                    break;
                }
                else
                {
                    $productos = explode(";", $row["billProducts"], -1);
                    $arraySize = count($productos);
                    for($x = 0; $x < $arraySize; ++$x) {
                        $splitAgain = explode("|", $productos[$x]);

                        $query = $stockHandler->SearchProduct((new DBManager())->DBEscape($splitAgain[0]), false);
                        if ($query->num_rows == 1)
                        {
                            $productSearch = mysqli_fetch_array($query);
                            $stockHandler->AddStock($productSearch['PCODE'], $splitAgain[1]);
                        }
                        elseif ($query->num_rows > 1) { $errorDesc = "Se encontraron 2 o mas productos con el nombre '" . $splitAgain[0] . "'."; $errorAtStock = true; break; }
                        elseif ($query->num_rows == 0) { $errorDesc = "No se encontro al producto '" . $splitAgain[0] . "' en la base de datos."; $errorAtStock = true; break; }
                        elseif (!$query) { $errorDesc = "Error en la base de datos al buscar el producto '" . $splitAgain[0]. "'."; $errorAtStock = true; break; }
                    }
                }
            }
            
            if ($errorAtStock) { EndScript($errorDesc); }
            
            if ($billHandler->RefundBill($factNumber))
            {
                $jsonData['success'] = true;
                $jsonData['message'] = "¡Factura anulada correctamente!";
                header('Content-type: application/json; charset=utf-8');
                echo json_encode($jsonData, JSON_FORCE_OBJECT);
                exit();
            }
            else { EndScript("Error en la base de datos al anular la factura."); }
        }
        else { EndScript("Error en la base de datos al anular la factura."); }  
    }
    else { EndScript("Tipo de factura inválido."); }
?>