<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/settings.php";

    class DBManager {

        private $db;

        function __construct()
        {
            $this->db = $this->Connect();
        }

        private function Connect()
        {
            $db = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            if (mysqli_connect_error()) {
                die("Conexion con la base de datos fallida. Será re-direccionado al login del sistema. <script>setTimeout(function() { window.location.href='../../index.php'; }, 4000); </script>");
            }
            return $db;
        }
        
        public function DBQuery($queryInfo)
        {
            return mysqli_query($this->db, $queryInfo);
        }
        
        public function DBEscape($data)
        {
            return mysqli_real_escape_string($this->db, $data);
        }
        
        public function DBFetch($queryInfo)
        {
            return mysqli_fetch_array($this->DBQuery($queryInfo));
        }
    }