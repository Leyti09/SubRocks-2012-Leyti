<?php
//json shit that makes pickers work
header('Content-Type: application/json');
if(isset($_GET['action_language'])) {
echo file_get_contents("yts/modbin/language-picker.json");
} else if(isset($_GET['action_country'])) {
echo file_get_contents("yts/modbin/region-picker.json");
} else if(isset($_GET['action_safetymode'])) {
echo file_get_contents("yts/modbin/safetymode-picker.json");
}
?>