<?php
/**
 * Class abstract for loop in $_POST, find action and return method.
 */
abstract class Main {
    public function __construct() {
        $db = new Database();
        $this->conn = $db->_connect();
    }
    
    public function init() {
        foreach ($_POST as $key => $val) {
            $this->$key = $val;
        }
        if (isset($this->action) && method_exists($this, $this->action)) {
            $_method = $this->action;
            return $this->$_method();
        }
    }
}