<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/settings.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/main/dbFunctions.php";

    if (session_status() != PHP_SESSION_ACTIVE) session_start();

    class SysFunct {

        private $db;

        public function __construct()
        {
            $this->db = new DBManager();
        }

        public function ValidateUser($user)
        {
            if (empty($user))
            {
                $userIP = $this->GetUserIp();
                
                $this->SaveLog("Intento de acceso sin login de la IP " . $userIP . ".");
                die("Acceso denegado. <script>setTimeout(function() { window.location.href = '../../../index.php'; } , 3000);</script>");
            }
        }
        
        public function redirect($url)
        {
            if (headers_sent())
            {
                die("Acceso no autorizado.");
            }
            else
            {
                exit(header('location: ' . $url));
            }
        }

        private function GetUserIp()
        {
            return isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '' ?
                $_SERVER['HTTP_X_FORWARDED_FOR'] :
                $_SERVER['REMOTE_ADDR'];
        }

        public function SaveLog($action)
        {
            $user = !empty($_SESSION['uName']) ?
                $_SESSION['uName'] :
                $this->GetUserIp();

            $this->db->DBQuery("INSERT INTO logs (UNAME, ACTIONDATE, ACTIONINFO) VALUES ('" . $this->db->DBEscape($user) . "','" . date("d/m/Y") . " - " . date("H:m:s") . "','" . $this->db->DBEscape($action) . "')");
        }

        public function DeployList($m)
        {
            return $m == 0 ?
                $this->db->DBQuery("SELECT * FROM logs ORDER BY ACTIONDATE DESC") :
                $this->db->DBQuery("SELECT * FROM logs ORDER BY ACTIONDATE DESC LIMIT " . $m);
        }
    }

    function EndScript($message)
    {
        $jsonData['success'] = false;
        $jsonData['message'] = $message;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsonData, JSON_FORCE_OBJECT);
        exit();
    }

    function EndScriptWithData($success, $message, $data = array())
    {
        $jsonData['success'] = $success;
        $jsonData['message'] = $message;
        $jsonData['data'] = $data;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsonData, JSON_FORCE_OBJECT);
        exit();
    }
