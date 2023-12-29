<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/s/classes/config.inc.php");

if(isset($_SESSION['siteusername'])) {
    $_SESSION = [];
    session_destroy();
}
header("Location: /");
die();