<?php

/**
* @Auther: bhief
* @Version: 1.0
*
* Inserts user information
*
**/
class user_insert {
    public $__db;

	public function __construct($conn){
        $this->__db = $conn;
	}

    function check_view_channel($vidid, $user) {
        $stmt = $this->__db->prepare("SELECT * FROM channel_views WHERE viewer = :user AND channel = :vidid");
        $stmt->bindParam(":user", $user);
        $stmt->bindParam(":vidid", $vidid);
        $stmt->execute();
        if($stmt->rowCount() === 0) {
            $this->add_view_channel($vidid, $user);
        }
    }
    
    function add_view_channel($vidid, $user) {
        $stmt = $this->__db->prepare("INSERT INTO channel_views (viewer, channel) VALUES (:user, :vidid)");
        $stmt->bindParam(":user", $user);
        $stmt->bindParam(":vidid", $vidid);
        $stmt->execute();
    }   

    function send_message($author, $subject, $to, $message, $video = "", $type = "nm") {
        $stmt = $this->__db->prepare("INSERT INTO pms (owner, subject, touser, message, video_attribute, type) VALUES (:owner, :subject, :touser, :message, :video, :type)");
        $stmt->bindParam(":owner", $author);
        $stmt->bindParam(":subject", $subject);
        $stmt->bindParam(":touser", $to);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":video", $video);
        $stmt->bindParam(":type", $type);
        $stmt->execute();
    }   
}

?>