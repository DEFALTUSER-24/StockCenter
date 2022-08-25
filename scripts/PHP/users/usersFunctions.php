<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/main/sysFunctions.php";

    class UsersFunctions {

        private DBManager $db;
        private SysFunct $sysfunc;

        function __construct()
        {
            $this->db = new DBManager();
            $this->sysfunc = new SysFunct();
        }

        public function CheckCredentials($username, $password, $comingFromLogin)
        {
            $username = $this->db->DBEscape($username);

            $result = $this->db->DBFetch("SELECT * FROM users WHERE NAME = '" . $username . "'");

            if (empty($result)) {
                return $comingFromLogin ? "Credenciales incorrectas." : false;
            }

            if (openssl_decrypt($result['PASSWORD'], "AES-128-ECB", ENCRYPT_KEY) != $password) {

                if (!$comingFromLogin) {
                    return false;
                }

                $this->sysfunc->SaveLog("Intento de login con " . $username . " fallido.");
                return "Credenciales incorrectas.";
            }

            if (!$comingFromLogin) {
                return $result;
            }

            $this->sysfunc->SaveLog($username . " ingreso al sistema.");
            session_start();
            $_SESSION['uName'] = $username;
            $_SESSION['uAccess'] = $result['ACCESSTYPE'];
            $this->sysfunc->redirect('system.php?stock&limit=10');
        }

        function ModifyData($oldUser, $newUser, $newPassword, $newAccess)
        {
            $this->sysfunc->SaveLog("Datos del usuario " . $oldUser . " modificados");
            return $this->db->DBQuery("UPDATE users SET NAME = '" . $this->db->DBEscape($newUser) . "', PASSWORD = '" . openssl_encrypt($newPassword, "AES-128-ECB", ENCRYPT_KEY) .  "', ACCESSTYPE = '" . $newAccess . "' WHERE NAME = '" . $this->db->DBEscape($oldUser) . "'");
        }

        function UserRemove($uName)
        {
            $this->sysfunc->SaveLog("Usuario " . $uName . " eliminado");
            return $this->db->DBQuery("DELETE FROM users WHERE NAME = '" . $this->db->DBEscape($uName) . "'");
        }

        function UserAdd($uName, $uPass, $uAccess)
        {
            $this->sysfunc->SaveLog("Usuario " . $uName . " agregado");
            return $this->db->DBQuery("INSERT INTO users (NAME, PASSWORD, ACCESSTYPE) VALUES ('" . $this->db->DBEscape($uName) . "','" . openssl_encrypt($uPass, "AES-128-ECB", ENCRYPT_KEY) . "','" . $this->db->DBEscape($uAccess) . "')");
        }

        function UserFind($uName)
        {
            return $this->db->DBQuery("SELECT * FROM users WHERE NAME = '" . $this->db->DBEscape($uName) . "'");
        }

        function DeployList()
        {
            return $this->db->DBQuery("SELECT * FROM users");
        }
    }
