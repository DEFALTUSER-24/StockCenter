<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/main/sysFunctions.php";

    class bills {

        private DBManager $db;
        private SysFunct $sysfunc;

        function __construct()
        {
            $this->db = new DBManager();
            $this->sysfunc = new SysFunct();
        }

        function DeployList($n)
        {
            return $n == 0 ?
                $this->db->DBQuery("SELECT * FROM bills_list ORDER BY billNumber DESC") :
                $this->db->DBQuery("SELECT * FROM bills_list ORDER BY billNumber DESC LIMIT " . $n);
        }
        
        function ActualBill()
        {
            $billNumber = $this->db->DBFetch("SELECT MAX(billNumber) FROM bills_list");

            return empty($billNumber["MAX(billNumber)"]) ?
                intval(0) :
                intval($billNumber["MAX(billNumber)"]);
        }
        
        function CreateBill($billType, array $products, $username, $client, $clientID) //INVALIDBILL - NOPRODUCTS
        {
            if ($billType != "Factura" && $billType != "Devolucion") {
                return "INVALIDBILL";
            }

            if (empty($products)) {
                return "NOPRODUCTS";
            }

            $this->sysfunc->ValidateUser($username);
            $eachProduct = "";
            foreach($products as $data)
            {
                $eachProduct .= $data;
            }
            $this->sysfunc->SaveLog("Factura numero " . $this->ActualBill() . " generada.");
            return $this->db->DBQuery("INSERT INTO bills_list (billDate, billType, billProducts, billMadeBy, billClient) VALUES ('" .
                date("d/m/Y") . "','" .
                $billType . "','" .
                $eachProduct . "','" .
                $this->db->DBEscape($username) . "','" .
                $this->db->DBEscape($client). " - " . $this->db->DBEscape($clientID) . "')"
            );
        }
        
        function CheckProducts()
        {
            include_once $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/stock/stockFunctions.php";
            return (new StockManager())->DeployList(0);
        }
        
        function SearchForClient($toSearch)
        {
            return $this->db->DBQuery("SELECT * FROM client WHERE cliName LIKE '%" . $this->db->DBEscape($toSearch) . "%' OR cliID LIKE '%" . $this->db->DBEscape($toSearch) . "%'");
        }
        
        function AddClient($clientName, $clientID)
        {
            $this->sysfunc->SaveLog("Cliente " . $this->db->DBEscape($clientName) . " (" . $this->db->DBEscape($clientID) . ") agregado.");
            return $this->db->DBQuery("INSERT INTO client (cliName, cliID) VALUES ('" . $this->db->DBEscape($clientName) . "','" . $this->db->DBEscape($clientID) . "')");
        }
        
        function RefundBill($billNumber)
        {
            $this->sysfunc->SaveLog("Factura numero " . $billNumber . " anulada.");
            return $this->db->DBQuery("UPDATE bills_list SET billType = 'Anulada' WHERE billNumber = '" . intval($billNumber) . "'");
        }
        
        function FindBill($billNumber)
        {
            return $this->db->DBQuery("SELECT * FROM bills_list WHERE billNumber = " . intval($billNumber));
        }
    }
?>