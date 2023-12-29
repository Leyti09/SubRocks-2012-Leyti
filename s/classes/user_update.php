<?php

/**
* @Auther: bhief
* @Version: 1.0
*
* Sets user information (eg, cooldowns)
*
**/
class user_update {
    public $__db;

	public function __construct($conn){
        $this->__db = $conn;
	}

    // fuck this, this can be compressed into 2 functions

    function update_cooldown_time($username, $rowName) {
        $stmt = $this->__db->prepare("UPDATE users SET ".$rowName." = now() WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
    }

    function update_row($username, $rowName, $new) {
        $stmt = $this->__db->prepare("UPDATE users SET ".$rowName." = :new WHERE username = :username");
        $stmt->bindParam(":new", $new);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        return true;
    }
}

?>