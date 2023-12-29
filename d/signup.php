<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/config.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/db_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/time_manip.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/video_helper.php"); ?>
<?php $__video_h = new video_helper($__db); ?>
<?php $__user_h = new user_helper($__db); ?>
<?php $__db_h = new db_helper(); ?>
<?php $__time_h = new time_helper(); ?>
<?php
    function remove_emoji($text) {

        $clean_text = "";

        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        return $clean_text;
    }


    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] && $_POST['username']) {
        $request = (object) [
            "username" => $_POST['username'],
            "password" => $_POST['password'],
            "email" => $_POST['email'],
            "password_hash" => password_hash($_POST['password'], PASSWORD_DEFAULT),

            "error" => (object) [
                "message" => "",
                "status" => "OK"
            ]
        ]; 

        $request->username = remove_emoji($request->username);

        /* ALT DETECT */
        $stmt = $__db->prepare("SELECT * FROM users WHERE ip = :ip");
        $stmt->bindParam(":ip", $_SERVER["HTTP_CF_CONNECTING_IP"]);
        $stmt->execute();
        
        $stmt = $__db->prepare("SELECT username FROM users WHERE username = lower(:username)");
        $stmt->bindParam(":username", $request->username);
        $stmt->execute();
        if($stmt->rowCount()) 
            { $request->error->message = "There's already a user with that same username!"; $request->error->status = ""; }
        
        if($request->error->status == "OK") {
            $stmt = $__db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(":username", $request->username);
            $stmt->bindParam(":email", $request->email);
            $stmt->bindParam(":password", $request->password_hash);
            $stmt->execute();

            $_SESSION['siteusername'] = $request->username;
            header("Location: /");
        } else {
            $_SESSION['error'] = $request->error;
            header("Location: /sign_up");
        }
    }
?>