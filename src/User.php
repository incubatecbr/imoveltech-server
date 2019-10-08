<?php
class User extends Main{

    public function __construct() {
        $db = new Database();
        $this->conn = $db->_connect();
    }

    public function verifyUser(){
        try {
            $username = $_POST['username'];
            $userpass = $_POST['pass'];
            $sql = "SELECT * FROM user WHERE username = '$username' AND pass = '$userpass' ";
            $res = $this->conn->query($sql);
            if( $res->num_rows > 0){
                return true;
            }else{
                return false;
            }
            
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

}
