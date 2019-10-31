<?php
class User extends Main{

    public function __construct() {
        $db = new Database();
        $this->conn = $db->_connect();
    }

    public function getName(){
        try {
            $id = $_POST['id'];
            if( !$this->getNameById($id) ){
                return false;
            }else{
                return $this->getNameById($id);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function verifyUser(){
        try {
            $username = $_POST['username'];
            $userpass = md5($_POST['pass']);
            $sql = "SELECT * FROM user WHERE username = '$username' AND pass = '$userpass' ";
            $res = $this->conn->query($sql);
            if( $res->num_rows > 0){
                $_SESSION['userLogged'] = true;
                $data_ = $res->fetch_assoc();
                $_r = [
                    $data_['id_user'],
                    true
                ];
                //return true;
                return $_r;
            }else{
                $_SESSION['userLogged'] = false;
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
            return $th->getMessage();
        }
    }

    /**
     * Function return name user by id.
     */
    private function getNameById($id){
        try {
            $sql = "SELECT id_user, username FROM user WHERE id_user = $id";
            $res = $this->conn->query($sql);
            if( $res->num_rows > 0){
                $data = $res->fetch_assoc();
                return $data;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Function for return session user.
     */
    private function getSessionUser(){
        if($_SESSION['userLogged']){
            $ret = "Usuário logado.";
        }else{
            $ret = "Usuário não está logado.";
        }
        return $ret;
    }

}
