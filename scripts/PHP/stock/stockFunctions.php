<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/main/sysFunctions.php";

    class StockManager {

        private $db;
        public $sysfunc;

        function __construct()
        {
            $this->db = new DBManager();
            $this->sysfunc = new SysFunct();
        }

        function DeployList($m)
        {
            return ($m == 0) ?
                $this->db->DBQuery("SELECT * FROM stock_list ORDER BY PNAME") :
                $this->db->DBQuery("SELECT * FROM stock_list ORDER BY PNAME LIMIT " . $m);
        }
        
        function InsertProduct($pic, $desc, $code, $units, $price)
        {
            $this->sysfunc->SaveLog("Producto con codigo " . $code . " agregado (Descripcion: " . $desc . " - Unidades: " . $units . " - Precio: " . $price . ")");
            return $this->db->DBQuery("INSERT INTO stock_list (PIC, PNAME, PCODE, PUNITS, PRICE) VALUES ('" . $this->db->DBEscape($pic) . "','" . $this->db->DBEscape($desc) . "','" . $this->db->DBEscape($code) . "'," . intval($units) . ",'" . $this->db->DBEscape($price) . "')");
        }
        
        function RemoveProduct($code)
        {
            $this->sysfunc->SaveLog("Producto con codigo " . $code . " ELIMINADO.");
            return $this->db->DBQuery("DELETE FROM stock_list WHERE PCODE = '" . $this->db->DBEscape($code) . "'");
        }
        
        function ModifyProduct($desc, $code, $units, $price)
        {
            $this->sysfunc->SaveLog("Producto con codigo " . $code . " modificado (Descripcion: " . $desc . " - Unidades: " . $units . " - Precio: " . $price . ")");
            return $this->db->DBQuery("UPDATE stock_list SET PNAME = '" . $this->db->DBEscape($desc) . "', PUNITS = " . intval($units) .  ", PRICE = '" . $this->db->DBEscape($price) . "' WHERE PCODE = '" . $this->db->DBEscape($code) . "'");
        }
        
        function SearchProduct($toSearch, $perfectSearch = true)
        {
            return $perfectSearch ?
                $this->db->DBQuery("SELECT * FROM stock_list WHERE PCODE = '" . $this->db->DBEscape($toSearch) . "'") :
                $this->db->DBQuery("SELECT * FROM stock_list WHERE PNAME LIKE '%" . $this->db->DBEscape($toSearch) . "%' OR PCODE LIKE '%" . $this->db->DBEscape($toSearch) . "%'");
        }
        
        function RemoveStock($code, $amount)
        {
            $row = $this->db->DBFetch("SELECT PUNITS FROM stock_list WHERE PCODE = '" . $code . "'");
            $newAmount = intval($row['PUNITS']) - $amount;

            return $newAmount < 0 ? "SURPASSED" :
                $this->db->DBQuery("UPDATE stock_list SET PUNITS = '" . $newAmount . "' WHERE PCODE = '" . $code . "'");
        }
        
        function AddStock($code, $amount)
        {
            $row = $this->db->DBFetch("SELECT PUNITS FROM stock_list WHERE PCODE = '" . $code . "'");
            $newAmount = intval($row['PUNITS']) + intval($amount);
            return $this->db->DBQuery("UPDATE stock_list SET PUNITS = '" . $newAmount . "' WHERE PCODE = '" . $code . "'");
        }
    }