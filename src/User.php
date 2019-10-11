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

    public function newUser(){
        try {
            $user = $_POST['user'];
            $passMd5 = md5($_POST['pass']);
            if( !$this->getUserName($user)  ){
                $sql = "INSERT INTO user(username, pass) VALUES ('$user', '$passMd5')";
                $res = $this->conn->query($sql);
                if( $res ){
                    return true;
                }else{
                    return false;
                }
            }else{//user exist, please new..
                $ret = "User already exists, please choose another";
                return $ret; 
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
        } 
    }

    /**
     * Function for find user created
     * return true, exist user.
     */
    protected function getUserName($name){
        try {
            $sql = "SELECT * FROM user WHERE username = '$name'";
            $res = $this->conn->query($sql);
            if( $res->num_rows > 0){
                return true;//find user with name.
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return $e->getMessage();
        }
    }

}
