<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/config.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/db_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/time_manip.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/video_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/page_builder.php"); ?>
<?php $__video_h = new video_helper($__db); ?>
<?php $__page_b = new page_builder("templates/m"); ?>
<?php $__user_h = new user_helper($__db); ?>
<?php $__db_h = new db_helper(); ?>
<?php $__time_h = new time_helper(); ?>
<?php ob_start(); ?>
<?php
	$__server->page_embeds->page_title = "Betatube";
	$__server->page_embeds->page_description = "Betatube is a website that is cool";
	$__server->page_embeds->page_url = "https://betatube.net/";
?>
<?php
if ($_SESSION["layout"] == "cosmic") {
	require_once($_SERVER['DOCUMENT_ROOT'] . "/2012home.php");
}else {
	if ($_SESSION["layout"] == "aug") {
		require_once($_SERVER['DOCUMENT_ROOT'] . "/2011indexpage.php");
	}else {
		require_once($_SERVER['DOCUMENT_ROOT'] . "/2013home.php");
	}
}
?>