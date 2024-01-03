<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/config.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/db_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/time_manip.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/video_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_update.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_insert.php"); ?>
<?php $__video_h = new video_helper($__db); ?>
<?php $__user_h = new user_helper($__db); ?>
<?php $__user_u = new user_update($__db); ?>
<?php $__user_i = new user_insert($__db); ?>
<?php $__db_h = new db_helper(); ?>
<?php $__time_h = new time_helper(); ?>
<?php
	if(isset($_SESSION['siteusername']))
	    $_user_hp = $__user_h->fetch_user_username($_SESSION['siteusername']);
 
    if(!$__user_h->user_exists($_GET['n']))
		if(!$useruse) {
        header("Location: /?error=This user does not exist!");
		}
		if(!$useruse) {
		$_user = $__user_h->fetch_user_username($_GET['n']);
		} else{
		$_user = $__user_h->fetch_user_username($useruse);
		}
 
    $stmt = $__db->prepare("SELECT * FROM bans WHERE username = :username ORDER BY id DESC");
	$stmt->bindParam(":username", $_user['username']);
	$stmt->execute();

	while($ban = $stmt->fetch(PDO::FETCH_ASSOC)) { 
		header("Location: /?error=This user has been terminated for violating BetaTube's Community Guidelines.");
	}

    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

	function addhttp($url) {
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}

    function check_valid_colorhex($colorCode) {
        // If user accidentally passed along the # sign, strip it off
        $colorCode = ltrim($colorCode, '#');
    
        if (
              ctype_xdigit($colorCode) &&
              (strlen($colorCode) == 6 || strlen($colorCode) == 3))
                   return true;
    
        else return false;
    }

    $_user['subscribers'] = $__user_h->fetch_subs_count($_user['username']);
    $_user['videos'] = $__user_h->fetch_user_videos($_user['username']);
    $_user['favorites'] = $__user_h->fetch_user_favorites($_user['username']);
    $_user['subscriptions'] = $__user_h->fetch_subscriptions($_user['username']);
    $_user['views'] = $__video_h->fetch_views_from_user($_user['username']);
    $_user['friends'] = $__user_h->fetch_friends_accepted($_user['username']);

    $_user['s_2009_user_left'] = $_user['2009_user_left'];
    $_user['s_2009_user_right'] = $_user['2009_user_right'];
    $_user['2009_user_left'] = explode(";", $_user['2009_user_left']);
    $_user['2009_user_right'] = explode(";", $_user['2009_user_right']);

    $_user['primary_color'] = substr($_user['primary_color'], 0, 7);
    $_user['secondary_color'] = substr($_user['secondary_color'], 0, 7);
    $_user['third_color'] = substr($_user['third_color'], 0, 7);
    $_user['text_color'] = substr($_user['text_color'], 0, 7);
    $_user['primary_color_text'] = substr($_user['primary_color_text'], 0, 7);
    $_user['2012_bgcolor'] = substr($_user['2012_bgcolor'], 0, 7);

    $_user['genre'] = strtolower($_user['genre']);
	$_user['subscribed'] = $__user_h->if_subscribed(@$_SESSION['siteusername'], $_user['username']);

    if(!check_valid_colorhex($_user['primary_color']) && strlen($_user['primary_color']) != 6) { $_user['primary_color'] = ""; }
    if(!check_valid_colorhex($_user['secondary_color']) && strlen($_user['secondary_color']) != 6) { $_user['secondary_color'] = ""; }
    if(!check_valid_colorhex($_user['third_color']) && strlen($_user['third_color']) != 6) { $_user['third_color'] = ""; }
    if(!check_valid_colorhex($_user['text_color']) && strlen($_user['text_color']) != 6) { $_user['text_color'] = ""; }
    if(!check_valid_colorhex($_user['primary_color_text']) && strlen($_user['primary_color_text']) != 6) { $_user['primary_color_text'] = ""; }
    if(!check_valid_colorhex($_user['2012_bgcolor']) && strlen($_user['2012_bgcolor']) != 6) { $_user['2012_bgcolor'] = ""; }

	if(isset($_SESSION['siteusername']))
    	$__user_i->check_view_channel($_user['username'], @$_SESSION['siteusername']);

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $error = array();

        if(!isset($_SESSION['siteusername'])){ $error['message'] = "you are not logged in"; $error['status'] = true; }
        if(!$_POST['comment']){ $error['message'] = "your comment cannot be blank"; $error['status'] = true; }
        if(strlen($_POST['comment']) > 1000){ $error['message'] = "your comment must be shorter than 1000 characters"; $error['status'] = true; }
        //if(!isset($_POST['g-recaptcha-response'])){ $error['message'] = "captcha validation failed"; $error['status'] = true; }
        //if(!$_user_insert_utils->validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error['message'] = "captcha validation failed"; $error['status'] = true; }
        if($__user_h->if_cooldown($_SESSION['siteusername'])) { $error['message'] = "You are on a cooldown! Wait for a minute before posting another comment."; $error['status'] = true; }
        //if(ifBlocked(@$_SESSION['siteusername'], $user['username'], $__db)) { $error = "This user has blocked you!"; $error['status'] = true; } 

        if(!isset($error['message'])) {
			$text = $_POST['comment'];
            $stmt = $__db->prepare("INSERT INTO profile_comments (toid, author, comment) VALUES (:id, :username, :comment)");
			$stmt->bindParam(":id", $_user['username']);
			$stmt->bindParam(":username", $_SESSION['siteusername']);
			$stmt->bindParam(":comment", $text);
            $stmt->execute();

            $_user_update_utils->update_comment_cooldown_time($_SESSION['siteusername']);

            if(@$_SESSION['siteusername'] != $_user['username']) { 
                $_user_insert_utils->send_message($_user['username'], "New comment", 'I commented "' . $_POST['comment'] . '" on your profile!', $_SESSION['siteusername']);
            }
        }
    }
?>
<?php
	$__server->page_embeds->page_title = "Betatube - " . htmlspecialchars($_user['username']);
	$__server->page_embeds->page_description = htmlspecialchars($_user['bio']);
	$__server->page_embeds->page_image = "/dynamic/pfp/" . htmlspecialchars($_user['pfp']);
	$__server->page_embeds->page_url = "http://betatube.net";
?>
<!DOCTYPE html>
<?php if($_user['hasRedirect'] == "1") { ?>
<?php        if(!empty($_user['redirect'])) { ?>
<script>
window.location.replace("https://betatube.net/<?php echo htmlspecialchars($_user['redirect']); ?>");
</script>
<?php	}	?>
<?php	}	?>
<?php 
		if($useruse) {
			if($userusevanity == 'n') { ?>
				<script>
				window.history.pushState('<?php echo htmlspecialchars($_user['username']); ?>', '<?php echo htmlspecialchars($_user['username']); ?>', '/user/<?php echo htmlspecialchars($_user['username']); ?>');
				</script><?php
			}
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><script src="./index_files/analytics.js.download" type="text/javascript"></script>
<script type="text/javascript">window.addEventListener('DOMContentLoaded',function(){var v=archive_analytics.values;v.service='wb';v.server_name='wwwb-app228.us.archive.org';v.server_ms=999;archive_analytics.send_pageview({});});</script>
<script type="text/javascript" src="./index_files/bundle-playback.js.download" charset="utf-8"></script>
<script type="text/javascript" src="./index_files/wombat.js.download" charset="utf-8"></script>
<script type="text/javascript">
  __wm.init("https://web.archive.org/web");
  __wm.wombat("http://www.youtube.com/user/nintendo","20080614080120","https://web.archive.org/","web","/_static/",
	      "1213430480");
</script>
<link rel="stylesheet" type="text/css" href="./index_files/banner-styles.css">
<link rel="stylesheet" type="text/css" href="./index_files/iconochive.css">
		<title>Betatube - <?php if($_user['title'])	{	?> <?php echo htmlspecialchars($_user['title']); ?> <?php } else {	?> <?php echo htmlspecialchars($_user['username']); ?> <?php	}	?>'s Channel</title>
			<script type="text/javascript" src="./index_files/base_all_with_bidi-vfl42302.js.download"></script>
		<link rel="stylesheet" href="./index_files/base_all-vfl42963.css" type="text/css">
		<link rel="alternate" media="handheld" href="https://web.archive.org/web/20080614080120/http://m.youtube.com/profile?desktop_uri=%2Fuser%2Fnintendo&amp;user=nintendo">
	<style type="text/css">
		.channelMastheadTable {
			padding: 4px 0px 0px 0px;
			background-color: #FFFFFF;
			font-size: 11px;
		}
		#smallMastheadBottom {
			display: block;
		}
		a.masthead:link, a.masthead:visited,
		a.masthead:hover, a.masthead:active { 
			color: #03C;
			font-family: Arial, Helvetica, sans-serif;
		}
		
		.profile-banner-box {
			margin-bottom: 15px;
		}
		
		.profileTitleLinks {
			font-size: 13px;
			margin-bottom: 15px;
			text-align: center;
		}
		.profileTitleLinks .delimiter {
			padding: 0px 6px;
		}
		
		.highlightBox {
			background: #FFC;
			border: 1px solid #FC3;
			padding: 9px;
			margin-bottom: 15px;
		}

		#profile-main-content {
			float: right;
			width: 560px;
		}
		#profile-side-content {
			float: left;
			width: 300px
		}

		.profile-box {
			margin-bottom: 15px;
		}
		.profile-box .box-head, .profile-box .box-foot {
			padding: 2px 5px;
			zoom: 1; /* gain "hasLayout" for IE/Win, enable filter opacity */
		}
		.profile-box .box-body {
			padding: 5px;
			zoom: 1; /* gain "hasLayout" for IE/Win, enable filter opacity */
		}
		.profile-box .box-head[class], .profile-box .box-body[class], .profile-box .box-foot[class] {
			background: none;
			position: relative;
		}
		.profile-box .box-fg[class] {
			position: relative;
			z-index: 1;
		}
		.profile-box .box-bg[class] {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		
		.profile-box .box-head-action {
			float: right;
		}
		.profile-box .headerTitle, .profile-box .headerTitleRight {
			color: #fff; /* overrides base */
		}
		.profile-box .headerTitleEdit {
			font-size: 14px;
			font-weight: bold;
		}
		.profile-box .box-head a:link, .profile-box .box-head a:visited,
		.profile-box .box-head a:hover, .profile-box .box-head a:active {
			color: #fff;
			font-weight: bold;
		}
		
		.profile-box .box-foot .pagingDiv {
			background: none;
		}
		
		.largeTextArea {
			width: 400px; height: 300px; 
			font-size: 12px; 
		}
		
		.headerTitleLeft {
			vertical-align: center;
			line-height: 28px;
		}
		.profileAssets {
			line-height: 16px;
		}
		.sepBox {
			padding-top: 15px; 
			padding-bottom: 8px;
			text-align: left;
		}
		.albumList {
			display: inline; 
			list-style-type: none;
			font-size: 11px;
		}
		.albumNotEnd, .albumEnd {
			padding: 3px;
			margin-right: 5px;
			float: left;
			text-align: center;
		}
		.albumArt {
			width: 40px;
			height: 40px;
			border: none;
		}
		
		.actionsTable {
			margin-top: 3px;
		}
		tr.actionsTable td {
			padding-right: 0px;
		}
		
		.contestsTableTop {
			margin: 10px 0px;
		}
		
		tr.showTable td {
			padding: 8px 5px 3px 8px;
		}
		tr.showTableEnd td {
			padding: 0px 5px 8px 8px;
		}
		
		.bulletinTable {
			text-align: center;
		}
		table.bulletinTable {
			width: 100%;
			border-collapse: collapse;
		}
		tr.bulletinTable th {
			padding: 2px;
		}
		tr.bulletinTable td {
			padding: 2px 5px 3px 5px;
		}
		tr.bulletinTable td img {
			padding-right: 5px;
		}
		
		.vlog-entry {
			zoom: 1; /* gain "hasLayout" for IE/Win, fix peekaboo border bug */
		}
		.vlog-entry-video {
			padding: 5px 0px 0px 5px;
		}
		.vlog-entry-info {
			width: 253px;
			height: 255px;
			padding: 5px; /* also set on box-body */
		}
		.profile-vlogbox-full .vlog-entry-info {
			width: 568px;
		}
		.profile-box .vlog-entry-info {
			padding: 0px;
		}
		.profile-box .vlog-entry-info .box-fg {
			padding: 5px 5px 5px 10px;
		}
		.vlog-entry-stats {
			padding: 5px; /* also set on box-body */
		}
		.vlogBoxTable {
			width: 100%;
			border: none;
			text-align: left;
		}
		tr.vlogBoxTable td {
			border-bottom: none;
			padding-top: 10px;
		}
		#postMainTitles {
			font-size: 16px;
			font-weight: bold;
			padding-bottom: 10px;
		}
		#postSubTitles {
			padding-bottom: 10px;
		}
		
		.emptySetBox {
			height: 123px;
		}
		.profile-box .emptySetBox {
			padding: 0px; /* override standard box padding */
		}
		.emptySetBoxNoImg {
			padding-bottom: 20px;
		}
		.emptySetTitle {
			margin-left: 25px;
			font-size: 20px;
			font-weight:  bold;
			padding-top: 15px;
		}
		.emptySetContent, .emptySetContentLg {
			margin-left: 55px;
			margin-top: 6px;
		}
		.emptySetContentLg {
			margin-right: 55px;
		}
		.emptyImg {
			float: right;
			margin-left: 15px;
		}
		
		.flaggingText {
			padding: 10px 0px;
			text-align: center;
		}
		
		.largeTitles {
			font-size: 14px;
			font-weight: bold;
		}
		input.buttonsCustom {
			color: #FFF;
			font-size: 12px;
			padding: 5px;
		}
		
		table.bulletinTableFull {
			width: 100%;
		}
		tr.bulletinTableFull th {
			padding: 2px 0px 2px 5px;
		}
		tr.bulletinTableFull th.lastCol, tr.bulletinTableFull td.lastCol,
		tr.bulletinTableNBFull td.lastCol {
			width: 65%;
			border-right: none;
		}
		tr.bulletinTableFull td, tr.bulletinTableNBFull td {
			padding: 5px;
		}
		
		table.showTableFull {
			width: 100%;
		}
		tr.showTableFull th {
			padding: 2px 0px 2px 5px;
		}
		tr.showTableFull td {
			padding: 5px;
		}
		tr.showTableFullTop td {
			padding: 5px;
			border-bottom: none;
		}
		
		table.commentsTableFull {
			width: 100%;
		}
		tr.commentsTableFull th {
			padding: 5px 2px;
		}
		tr.commentsTableFull td, tr.commentsTableNBFull td {
			padding: 5px;
		}
		
		
		
		
		body {
			background-color: #ffffff;
		}
		a:link, a:visited, a:hover, a:active {
			color: #03C;
		}
		h1,h2,h3,h4,h5,h6 {
			color: #666666;
		}
		
		.profileTitleLinks {
			font-family: arial,helvetica,sans-serif;
			color: #03C;
		}
		
		.highlightBox {
			font-family: arial,helvetica,sans-serif;
		}
		
		.profile-box {
			border: 1px solid #666666;
			font-family: arial,helvetica,sans-serif;
		}
		.profile-box .box-head, .profile-box .box-foot {
			background: #666666;
			color: #fff;
			filter: Alpha(opacity=95);
		}
		.profile-box .box-body {
			background: #ffffff;
			color: #000000;
			filter: Alpha(opacity=95);
		}
		.profile-box .box-bg[class] {
			opacity: 0.95;
		}
		.profile-box .box-head .box-bg[class], .profile-box .box-foot .box-bg[class] {
			background: #666666;
		}
		.profile-box .box-body .box-bg[class] {
			background: #ffffff;
		}
		
		.profile-highlightbox .box-body {
			background: #e6e6e6;
			color: #666666;
		}
		.profile-highlightbox .box-body .box-bg[class] {
			background: #e6e6e6;
		}
		
		.profile-vlogbox {
			border-color: #ffffff;
		}
		.profile-vlogbox .box-head, .profile-vlogbox .box-foot {
			background: #ffffff;
		}
		.profile-vlogbox .box-body {
			background: #ffffff;
			color: #000000;
		}
		.profile-vlogbox .box-head .box-bg[class], .profile-vlogbox .box-foot .box-bg[class] {
			background: #ffffff;
		}
		.profile-vlogbox .box-body .box-bg[class] {
			background: #ffffff;
		}
		
		.label, .standardLabel, .normalLabel,
		.smallLabel, .xsmallLabel, .largeLabel {
			color: #666666;
		}
		.profile-label {
			color: #666666;
		}
		
		.pagingDiv {
			background: #666666;
			color: #666666;
		}
		.pagerCurrent {
			background-color: #fff;
			color: #666666;
		}
		.pagerNotCurrent {
			background-color: #e6e6e6;
		}
		
		.largeTextArea {
			font-family: arial,helvetica,sans-serif; 
		}
		
		tr.bulletinTable th {
			border-bottom: 1px dashed #666666;
		}
		tr.bulletinTable th.firstCol, tr.bulletinTable td.firstCol {
			border-right: 1px solid #666666;
		}
		tr.bulletinTable td {
			border-top: 1px dashed #666666;
		}
		
		.vlog-entry-video {
			background: #ffffff;
		}
		.postTitles {
			color: #666666;
		}
		.postText {
			color: #000000;
		}
		
		.flaggingText {
			color:  #03C;
			font-family: arial,helvetica,sans-serif;
		}
		
		input.buttonsCustom {
			background-color: #666666;
			font-family: arial,helvetica,sans-serif;
		}
		
		.vimg, .vimgSm, .vimg130, .vimg120, .vimg110, .vimg100,
		.vimg90, .vimg80, .vimg75, .vimg70, .vimg60, .vimg50 {
			border-color: #666666;
		}
		
		.video-thumb-micro, .video-thumb-small, .video-thumb-medium,
		.video-thumb-normal, .video-thumb-big, .video-thumb-large, .video-thumb-jumbo,
		.user-thumb-micro, .user-thumb-small, .user-thumb-medium,
		.user-thumb-partner, .user-thumb-large, .user-thumb-xlarge, .user-thumb-jumbo {
			border-color: #666666;
			background-color: #ffffff;
		}
		
		tr.bulletinTableFull th {
			border-bottom: 1px solid #666666;
			border-right: 1px solid #666666;
		}
		tr.bulletinTableFull td {
			border-bottom: 1px dashed #666666;
			border-right: 1px solid #666666;
		}
		tr.bulletinTableNBFull td {
			border-right: 1px solid #666666;
		}
		
		tr.showTableFull th {
			border-bottom: 1px solid #666666;
			border-right: 1px solid #666666;
		}
		tr.showTableFull td {
			border-bottom: 1px dashed #666666;
		}
		
		tr.commentsTableFull th {
			border-bottom: 1px solid #666666;
		}
		tr.commentsTableFull td {
			border-bottom: 1px dashed #666666;
		}
		
		.headerRCBox {
			font-family: arial,helvetica,sans-serif;
			background: #666666;
			padding: 6px 6px 2px 6px;
			color: #fff;
			font-weight: bold;
		}
		.headerBox {
			background-color: #666666; 
			border: 1px solid #666666;
			color: #fff;
			font-family: arial,helvetica,sans-serif;
			padding: 3px 5px;
			overflow: hidden;
		}
		
		.vListBox, .vEntry, .vDetailEntry {
			border-color: #666666;
		}
		.vListBox {
			background: #e6e6e6;
		}
		.runtime, .title, .vtitle, .desc, .vdesc, .facets, .vfacets {
			font-family: arial,helvetica,sans-serif;
		}
		.runtime, .facets, .vfacets {
			color: #000000;
		}
		.title, .vtitle, .desc, .vdesc { 
			color: #666666;
		}
		
		
	</style>


		
		<script type="text/javascript">
			
	var gXSRF_token = '';
	var gXSRF_field_name = '';
	var gXSRF_ql_pair = '';

		gXSRF_token = 'rvurUyOXHsTVhPf8jw_6_BUrYNp8MTIxMzUxNjg4MA==';
		gXSRF_field_name = 'session_token';
		onLoadFunctionList.push(populate_session_token);

		gXSRF_ql_pair = 'session_token=Gq1H0yuzv2KIDOArW90xXM0mqDF8MA==';



				function _hbLink (a,b) { return false; }
				function urchinTracker (a,b) { }

			var gPixelGif = 'https://web.archive.org/web/20080614080120/http://s.ytimg.com/yt/img/pixel-vfl73.gif';
		</script>

		<!--<base target="_top">--><base href="." target="_top">
	</head>

	<body onload="performOnLoadFunctions();">
	<div id="baseDiv">
		<table class="channelMastheadTable">
			<tbody><tr>
				<td width="104" valign="absmiddle"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/"><img id="smallMastheadLogo" src="./index_files/pixel-vfl73.gif"></a></td>			
				<td valign="absmiddle" nowrap="">
					<div class="alignL padTlg padLsm">
						<a href="/browse?s=mp" class="masthead">Videos</a> | 
						<a href="/channels" class="masthead">Channels</a> |
						<a href="/layouts/" class="masthead">Layouts</a> | 
						<a href="/new_upload" class="masthead">Upload</a>
					</div>
				</td>
				<td valign="top" width="100%">
					<div width="100%" class="alignR floatR">
						
	<div id="loginBoxZ" style="display: none;">
		<div class="contentBox">
			<div>
				<div id="loginBoxZ-signup">
					<a href="/sign_up">Sign Up</a>
				| <a href="https://discord.gg/uPy2QyuMg8">Help</a>
				</div>
				<div id="loginBoxZ-login">
					 Login 
				</div>
				<div class="clear"></div>
			</div>

			<form method="post" name="loginForm" id="loginFormZ" action="/signup">
				<input type="hidden" name="next" value="/user/nintendo" id="loginNextZ">
				<input type="hidden" name="current_form" value="loginForm">
				<input type="hidden" name="action_login" value="1">
				<div id="loginBoxZ-container">
					<div id="loginBoxZ-labels" class="floatL">
						<label for="loginUserZ" class="nowrap">Username:</label>
						<label for="loginPassZ" class="nowrap">Password:</label>
					</div>
					<div class="floatL">
						<input id="loginUserZ" class="loginBoxZ-input" type="text" size="16" name="username" value=""><br>
						<input id="loginPassZ" class="loginBoxZ-input" type="password" size="16" name="password"><br>
						<input type="submit" class="smallText" value="Login">
					</div>
					<div class="clearL"></div>
				</div>
			<input name="session_token" type="hidden" value="rvurUyOXHsTVhPf8jw_6_BUrYNp8MTIxMzUxNjg4MA=="></form>
			<div id="loginBoxZ-forgot">
				<a href="">Forgot Username</a>
			| <wbr><nobr><a href="">Forgot Password</a></nobr>
			</div>
			<div id="loginBoxZ-gaia">
				<a href="">Login with your Google account</a>&nbsp;
				<a href="#" onclick="window.open(&#39;/t/help_gaia&#39;,&#39;login_help&#39;,&#39;width=580,height=480,resizable=yes,scrollbars=yes,status=0&#39;).focus();" rel="nofollow"><img src="./index_files/pixel-vfl73.gif" border="0" class="alignMid gaiaHelpBtn" alt=""></a>
			</div>
		</div>
	</div>

							<div id="localePickerBox" style="display: none;">
	<div id="flagDiv">
			<script type="text/javascript">
				var gLocales = [
				['ru_RU','&#x420;&#x43E;&#x441;&#x441;&#x438;&#x44F;']									,				['zh_TW','&#x53F0;&#x7063;']									,				['ja_JP','&#x65E5;&#x672C;']									,				['zh_HK','&#x9999;&#x6E2F;']									,				['ko_KR','&#xD55C;&#xAD6D;']									,				['en_AU','Australia']									,				['pt_BR','Brasil']									,				['en_CA','Canada']									,				['de_DE','Deutschland']									,				['es_ES','Espa&#xF1;a']									,				['fr_FR','France']									,				['en_US','Global']									,				['en_IN','India']									,				['en_IE','Ireland']									,				['it_IT','Italia']									,				['es_MX','M&#xE9;xico']									,				['nl_NL','Nederland']									,				['en_NZ','New Zealand']									,				['pl_PL','Polska']									,				['en_GB','United Kingdom']				
				];
			</script>
			<div id="flagDivInner">
			</div>

		<div class="alignR smallText"><a href="" onclick="closeLocalePicker(); return false;">Close</a></div>
	</div>
	</div>

							




	<div id="util-links" class="small-utility-links">

		<span class="util-item first"><b><a href="/sign_up" onclick="_hbLink(&#39;SignUp&#39;,&#39;UtilityLinks&#39;);">Sign Up</a></b></span>
		<span class="util-item"><a href="https://discord.gg/uPy2QyuMg8">Help</a></span>
		<span class="util-item"><a href="/sign_in">Log In</a></span>
		<span class="util-item"><a href="#" class="localePickerLink eLink" onclick="loadFlagImgs();toggleDisplay(&#39;localePickerBox&#39;);return false;">Site:</a></span>
		<span class="util-item first">&nbsp;<a href="#" class="localePickerLink" onclick="loadFlagImgs();toggleDisplay(&#39;localePickerBox&#39;);return false;"><img src="./index_files/pixel-vfl73.gif" class="currentFlag  globalFlag" alt="Site:"></a></span>
	</div>

	<form name="logoutForm" method="post" target="_top" action="https://web.archive.org/web/20080614080120/http://youtube.com/index">
		<input type="hidden" name="action_logout" value="1">
	<input name="session_token" type="hidden" value="rvurUyOXHsTVhPf8jw_6_BUrYNp8MTIxMzUxNjg4MA=="></form>

		<div class="clear searchDiv" style="margin-top:3px;">
				<form autocomplete="off" name="searchForm" id="searchForm" method="get" action="https://web.archive.org/web/20080614080120/http://youtube.com/results">
		<input tabindex="10000" type="text" onkeyup="goog.i18n.bidi.setDirAttribute(event,this)" name="search_query" maxlength="128" class="searchField" value="">
		&nbsp;
			<input type="submit" name="search" value="Search">
	</form>

		</div>

					</div>
				</td>
			</tr>
		</tbody></table>
		<div>
			<img id="smallMastheadBottom" src="./index_files/pixel-vfl73.gif">
		</div>
	
		<br>
		<!--Begin Page Container Table-->



		<div class="profileTitleLinks">
			<div id="profileSubNav">


				<a href="/user/<?php echo htmlspecialchars($_user['username']); ?>/videos">Videos</a>


				<span class="delimiter">|</span>
		<a href="/channel_favorites?n=<?php echo htmlspecialchars($_user['username']); ?>">Favorites</a>


				<span class="delimiter">|</span>
		<a href="/playlists?n=<?php echo htmlspecialchars($_user['username']); ?>">Playlists</a>

				<span class="delimiter">|</span>
		<a href="/friends?n=<?php echo htmlspecialchars($_user['username']); ?>">Friends</a>


				<span class="delimiter">|</span>
		<a href="/subs?n=<?php echo htmlspecialchars($_user['username']); ?>">Subscribers</a>


				<span class="delimiter">|</span>
		<a href="/subs1?n=<?php echo htmlspecialchars($_user['username']); ?>">Subscriptions</a>

</div>

		</div>
		
		

<script type="text/javascript">
	
	function getFormVars(form)
	{	var formVars = new Array();
		for (var i = 0; i < form.elements.length; i++)
		{
			var formElement = form.elements[i];
			formVars[formElement.name] = formElement.value;
		}
		return urlEncodeDict(formVars);
	}

	
	function redirectToUrl(req)
	{
		window.location.href=self.new_redirect_url;
		return true;
	}
	function unblockUserLink(friend_id, url)
	{
		if (!confirm("Are you sure you want to unblock this user?"))
			return false;
		self.new_redirect_url = url;
		data ="unblock_user=1&&friend_id=" + friend_id;
		postUrlXMLResponse("/link_servlet",data ,execOnSuccess(redirectToUrl));
		return true;
	}
	function blockUserLink(friend_id, url)
	{
		if (!confirm("Are you sure you want to block this user?"))
			return true;
		self.new_redirect_url = url;
		data ="block_user=1&&friend_id=" + friend_id;
		postUrlXMLResponse("/link_servlet", data, redirectToUrl);
		return true;
	}
	function unblockUserLinkByUsername(friend_username)
	{
		if (!confirm("Are you sure you want to unblock this user?"))
			return false;
		data ="unblock_user=0&&friend_username=" + friend_username;
		postUrlXMLResponse("/link_servlet", data);
		return false;
	}
	function blockUserLinkByUsername(friend_username)
	{
		if (!confirm("Are you sure you want to block this user?"))
			return false;
		data ="block_user=1&&friend_username=" + friend_username;
		postUrlXMLResponse("/link_servlet", data);
		return false;
	}



	
				onLoadFunctionList.push(function() { imagesInit_subscribers();} );

		function imagesInit_subscribers() {
			imageBrowsers['subscribers'] = new ImageBrowser(4, 1, "subscribers");
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg",
				"/user/sabbate",
				"sabbate",
				"/user/sabbate",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg",
				"/user/IEAMLink",
				"IEAMLink",
				"/user/IEAMLink",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg",
				"/user/darthreggie22",
				"darthreggie22",
				"/user/darthreggie22",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("https://web.archive.org/web/20080614080120/http://i.ytimg.com/vi/6owAxk3KsQ0/default.jpg",
				"/user/superdragondude",
				"superdragondude",
				"/user/superdragondude",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg",
				"/user/MIGUETITO",
				"MIGUETITO",
				"/user/MIGUETITO",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("https://web.archive.org/web/20080614080120/http://i.ytimg.com/vi/mhvuIXOQ0Yg/default.jpg",
				"/user/humboldpower",
				"humboldpower",
				"/user/humboldpower",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("https://web.archive.org/web/20080614080120/http://i.ytimg.com/vi/ghC2An_HdZw/default.jpg",
				"/user/maya97",
				"maya97",
				"/user/maya97",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("https://web.archive.org/web/20080614080120/http://i.ytimg.com/vi/S7hTFzN3czI/default.jpg",
				"/user/twilightkey",
				"twilightkey",
				"/user/twilightkey",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg",
				"/user/Gorgarth",
				"Gorgarth",
				"/user/Gorgarth",
				"",
				"",
				"",
				false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg",
				"/user/NG8YCHE",
				"NG8YCHE",
				"/user/NG8YCHE",
				"",
				"",
				"",
				false) );
			imageBrowsers['subscribers'].initDisplay();
			imageBrowsers['subscribers'].showImages();
			images_loaded = true;
		}

				onLoadFunctionList.push(function() { imagesInit_friends();} );

		function imagesInit_friends() {
			imageBrowsers['friends'] = new ImageBrowser(2, 1, "friends");
				imageBrowsers['friends'].addImage(new ytImage("https://web.archive.org/web/20080614080120/http://i.ytimg.com/vi/DniP0dPixRE/default.jpg",
				"/user/K8t05",
				"K8t05",
				"/user/K8t05",
				"",
				"",
				"",
				false) );
				imageBrowsers['friends'].addImage(new ytImage("https://web.archive.org/web/20080614080120/http://i.ytimg.com/i/7NxCxRmzvpgBXWOfY7oA-A/1.jpg",
				"/user/hey1031",
				"hey1031",
				"/user/hey1031",
				"",
				"",
				"",
				false) );
			imageBrowsers['friends'].initDisplay();
			imageBrowsers['friends'].showImages();
			images_loaded = true;
		}

	function share_profile()
	{
		var fs = window.open( "/share?u=nintendo",
			"Share", "toolbar=no,width=546,height=485,status=no,resizable=yes,fullscreen=no,scrollbars=no");
		fs.focus();
	}
	function updateLinks(linkid)
	{
		document.getElementById(linkid + 'Link').style.display = "none";
		document.getElementById(linkid + 'Span').style.display = "inline";
		if (linkid == "recent") {
			document.getElementById('viewedLink').style.display = "inline";
			document.getElementById('viewedSpan').style.display = "none";
			if (_gel('discussedLink')){
				document.getElementById('discussedLink').style.display = "inline";
				document.getElementById('discussedSpan').style.display = "none";
			}
			document.getElementById('searchResultsSpan').style.display = "none";
		}
		if (linkid == "viewed") {
			document.getElementById('recentLink').style.display = "inline";
			document.getElementById('recentSpan').style.display = "none";
			if (_gel('discussedLink')){
				document.getElementById('discussedLink').style.display = "inline";
				document.getElementById('discussedSpan').style.display = "none";
			}
			document.getElementById('searchResultsSpan').style.display = "none";
		}
		if (linkid == "discussed") {
			document.getElementById('recentLink').style.display = "inline";
			document.getElementById('recentSpan').style.display = "none";
			document.getElementById('viewedLink').style.display = "inline";
			document.getElementById('viewedSpan').style.display = "none";
			document.getElementById('searchResultsSpan').style.display = "none";
		}
	}

	function updateVideoSortLater(linkId, videoDiv, loadingDiv) {
		return function(req) {updateVideoSort(req, linkId, videoDiv, loadingDiv)};
	}

	function updateVideoSort(req, linkId, videoDiv, loadingDiv) {
		updateLinks(linkId);
		_gel(loadingDiv).style.display = 'none';
		_gel(videoDiv).style.display = '';

		_gel(videoDiv).innerHTML = getNodeValue(req.responseXML, "html_content");
	}

	function submitProfileVideoSort(sortType, linkId, videoDiv, loadingDiv, action) {
		_gel(loadingDiv).style.display = '';
		_gel(videoDiv).style.display = 'none';

		var data = new Array();

		var v = new Object;
		v.name = 'action_'+ action; 
		v.value = '1';
		data.push(v);

		var v2 = new Object;
		v2.name = 'user';
		v2.value = 'nintendo';
		data.push(v2)

		var v3 = new Object;
		v3.name = 'p';
		v3.value = sortType;
		data.push(v3)

		postUrl('/profile_ajax', urlEncodeDict(data), true, execOnSuccess(updateVideoSortLater(linkId, videoDiv, loadingDiv)));
	}

	function updateSearchResultsLater(form, resultsDivName, loadingDiv) {
		return function(req) {updateSearchResults(req, form, resultsDivName, loadingDiv)};
	}

	function updateSearchResults(req, form, resultsDivName, loadingDiv) {
		for (var i = 0; i < form.elements.length; i++) {
			form.elements[i].disabled = false;
		}
		if (resultsDivName == "profileVideos") {
			document.profileSearchForm.search_query.blur();
			document.profileSearchForm.search_query.focus();
		}else {
			document.uploadedprofileSearchForm.search_query.blur();
			document.uploadedprofileSearchForm.search_query.focus();
		}
		_gel(resultsDivName).innerHTML = getNodeValue(req.responseXML, "html_content");
		_gel('searchResultsSpan').style.display = "inline";

		_gel('viewedLink').style.display = "inline";
		_gel('viewedSpan').style.display = "none";
		if (resultsDivName == "profileVideos") {
			_gel('discussedLink').style.display = "inline";
			_gel('discussedSpan').style.display = "none";
		}
		_gel('recentLink').style.display = "inline";
		_gel('recentSpan').style.display = "none";
		_gel(loadingDiv).style.display = 'none';
		_gel(resultsDivName).style.display = '';
	}

	function submitProfileSearchRequest(formName, resultsDivName, loadingDiv) {
		_gel(loadingDiv).style.display = '';
		_gel(resultsDivName).style.display = 'none';

		form = document.forms[formName];
		for (var i = 0; i < form.elements.length; i++) {
			form.elements[i].disabled = true;
		}

		postFormXMLResponse(formName, updateSearchResultsLater(form, resultsDivName, loadingDiv));
	}
</script>

	<script>
	function share(playlist_encrypted_id)
	{
		var fs = window.open( "/share?p=" + playlist_encrypted_id,
						"Share", "toolbar=no,width=546,height=485,status=no,resizable=yes,fullscreen=no,scrollbars=no");
		fs.focus();
	}
	</script>



			


<!-- Begin Channel Content -->





<!-- Begin Side Column -->
<div id="profile-side-content">
	
	
	<div class="profile-box profile-highlightbox">
		<div class="box-head">
			<div class="box-fg">
				<div class="headerTitleEdit">
					<div class="headerTitleRight">
							<script type="text/javascript">
								var watchUsername = 'hwilliams8548';
								var subscribeaxc = '';
								var isLoggedIn =  true ;
							</script>
									<div id="subscribeDiv">
										<a class="action-button" onclick="subscribe(watchUsername, subscribeaxc, true); urchinTracker('/Events/SUB_click/Profile/smosh'); return false;" title="subscribe to smosh's videos">
											<span class="action-button-leftcap"></span>
											<span class="action-button-text">Subscribe</span>
											<span class="action-button-rightcap"></span>
										</a>
									</div>
									<div id="unsubscribeDiv" class="hid">
										<a class="action-button inactive" onclick="unsubscribe(watchUsername, subscribeaxc); return false;">
											<span class="action-button-leftcap"></span>
											<span class="action-button-text">Unsubscribe</span>
											<span class="action-button-rightcap"></span>
										</a>
									</div>
					</div>
					<div class="headerTitleLeft">
							<span><?php if($_user['title'])	{	?> <?php echo htmlspecialchars($_user['title']); ?> <?php } else {	?> <?php echo htmlspecialchars($_user['username']); ?> <?php	}	?></span>
					</div>
				</div>
			</div>
			<div class="box-bg"></div>
		</div>
		<div class="box-body">
			<div class="box-fg">
				<div id="subscribeMessage">
					Please login to perform this operation.
				</div>
				<div class="hid">
					See related Channels
				</div>
				<div class="floatL">
					<div class="user-thumb-xlarge">
						<img src="/dynamic/pfp/<?php echo htmlspecialchars($_user['pfp']); ?>" alt="nintendo">
					</div>
				</div>
				<div style="float:left;margin-left:5px;width:180px;">
					<div class="largeTitles"><strong><?php if($_user['title'])	{	?> <?php echo htmlspecialchars($_user['title']); ?> <?php } else {	?> <?php echo htmlspecialchars($_user['username']); ?> <?php	}	?></strong></div>
					<div class="padT3">
						<div class="smallText">Joined: <strong><?php echo date("M d, Y", strtotime($_user['created'])); ?></strong></div>
						<div class="smallText">Last Login: <strong><?php echo date("M d, Y", strtotime($_user['lastlogin'])); ?></strong></div>
						<div class="smallText">Subscribers: <strong><?php echo $_user['subscribers']; ?></strong></div>
						<div class="smallText">Channel Views: <strong>0</strong></div>
						<div class="smallText">Video Views: <strong><?php echo $_user['views']; ?></strong></div>

					</div>
				</div>
				<div style="clear:both"></div>
		
					<br>

						<span class="smallText">Name:</span> <strong><?php echo htmlspecialchars($_user['username']); ?></strong><br>
						<span class="smallText">Country:</span> <strong><?php echo htmlspecialchars($_user['country']); ?></strong>
						<br>
				

		
		
				
				
			</div>
			<div class="box-bg"></div>
		</div>
	</div>
	
	
	
	
	<div class="profile-box">
		<div class="box-head">
			<div class="box-fg">
				<div class="headerTitle">
					Connect with <?php if($_user['title'])	{	?> <?php echo htmlspecialchars($_user['title']); ?> <?php } else {	?> <?php echo htmlspecialchars($_user['username']); ?> <?php	}	?>
				</div>
			</div>
			<div class="box-bg"></div>
		</div>
		<div class="box-body">
			<div class="box-fg">
				<table cellpadding="3" cellspacing="0" border="0" width="100%">
					<tbody><tr>
						<td align="right" width="110" valign="middle">
						</td>
						<td align="left" valign="top">
							<table class="actionsTable">
								<tbody><tr class="actionsTable">
									<td class="actionsTable">
										<div class="smallText">
										<a id="aProfileSendMsg" href="/inbox/compose?to=<?php echo htmlspecialchars($_user['username']); ?>"><img src="./index_files/pixel-vfl73.gif" id="profileSendMsg" class="icnProperties" alt="Send Message">Send Message</a>
										</div>
										<div class="smallText">
											<a id="aProfileAddComment" href=""><img src="./index_files/pixel-vfl73.gif" id="profileAddComment" class="icnProperties" alt="Add Comment">Add Comment</a>
										</div>

										<div class="smallText">
											<a id="aProfileFwdMember" href="javascript:share_profile()"><img src="./index_files/pixel-vfl73.gif" id="profileFwdMember" class="icnProperties" alt="Share Channel">Share Channel</a>
										</div>
										<div class="smallText">
											
										</div>
									</td>
								</tr>
							</tbody></table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="marB3 alignC"><a href="https://web.archive.org/web/20080614080120/http://www.youtube.com/nintendo">https://www.betatube.net/<?php if($_user['vanity'])	{	?><?php echo htmlspecialchars($_user['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($_user['username']); ?><?php } ?></a></div>
						</td>												
					</tr>
				</tbody></table>
			</div>
			<div class="box-bg"></div>
		</div>
	</div>
	
	
	
	
	
	
	
	
			<div class="profile-box">
				<div class="box-head">
					<div class="box-fg">
						<div class="headerTitle">
							<span>Subscriptions (<a href="/subs1?n=<?php echo htmlspecialchars($_user['username']); ?>" class="headersSmall"><?php echo '0' ?></a>)</span>
						</div>
					</div>
					<div class="box-bg"></div>
				</div>
				<div class="box-body smallText">
					<div class="box-fg">
						<table border="0" align="center" width="100%">
									<tbody><tr>

																					
													<?php
														$stmt = $__db->prepare("SELECT * FROM subscribers WHERE sender = :username ORDER BY id LIMIT 3");
														$stmt->bindParam(":username", $_user['username']);
														
														$stmt->execute();
														while($subscribers = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$subber['name'] = $subscribers['reciever'];		
													?><?php $subser = $__user_h->fetch_user_username($subber['name']); ?>
										<div class="marB5">	
										<td width="33%" align="center">	
										<div class="user-thumb-large">
											<a href="/user/<?php echo htmlspecialchars($subber['name']); ?>" title="<?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 13)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 11)); ?> <?php	}	?>"><img src="/dynamic/pfp/<?php echo $subser['pfp'] ?>"></a>
										</div>
										<div class="bar_test">
											<a href="/user/<?php echo htmlspecialchars($subber['name']); ?>" title="<?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 13)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 12)); ?> <?php	}	?>"><?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 13)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 11)); ?> <?php	}	?></a>
										</div>
										</td>
										</div>
														<?php } ?>	</tr><tr>
													<?php
														$stmt = $__db->prepare("SELECT * FROM subscribers WHERE sender = :username ORDER BY id LIMIT 3, 3");
														$stmt->bindParam(":username", $_user['username']);
														
														$stmt->execute();
														while($subscribers = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$subber['name'] = $subscribers['reciever'];		
													?><?php $subser = $__user_h->fetch_user_username($subber['name']); ?>
										<div class="marB5">	
										<td width="33%" align="center">	
										<div class="user-thumb-large">
											<a href="/user/<?php echo htmlspecialchars($subber['name']); ?>" title="<?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 13)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 11)); ?> <?php	}	?>"><img src="/dynamic/pfp/<?php echo $subser['pfp'] ?>"></a>
										</div>
										<div class="bar_test">
											<a href="/user/<?php echo htmlspecialchars($subber['name']); ?>" title="<?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 13)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 12)); ?> <?php	}	?>"><?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 13)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 11)); ?> <?php	}	?></a>
										</div>
										</td>
										</div>
														<?php } ?>	
							</tr><tr>
								<td colspan="3">
									<div style="text-align:center;margin:3px;">
										<a href="/subs1?n=<?php echo htmlspecialchars($_user['username']); ?>">See all subscriptions</a>
									</div>
								</td>
							</tr>
						</tbody></table>
					</div>
					<div class="box-bg"></div>
				</div>
			</div>
	
	
	
	
	
	
	
	
	
		<?php if(!empty($_user['custom'])) { ?>
											<?php $custom = htmlspecialchars($_user['custom']) ?>
											<?php $custom = str_replace("%h1%", "<h1>", $custom); ?>
											<?php $custom = str_replace("%/h1%", "</h1>", $custom); ?>
											<?php $custom = str_replace("%h2%", "<h2>", $custom); ?>
											<?php $custom = str_replace("%/h2%", "</h2>", $custom); ?>
											<?php $custom = str_replace("%h3%", "<h3>", $custom); ?>
											<?php $custom = str_replace("%/h3%", "</h3>", $custom); ?>
											<?php $custom = str_replace("%h4%", "<h4>", $custom); ?>
											<?php $custom = str_replace("%/h4%", "</h4>", $custom); ?>
											<?php $custom = str_replace("%h5%", "<h5>", $custom); ?>
											<?php $custom = str_replace("%/h5%", "</h5>", $custom); ?>
											<?php $custom = str_replace("%h6%", "<h6>", $custom); ?>
											<?php $custom = str_replace("%/h6%", "</h6>", $custom); ?>
											<?php $custom = str_replace("%p%", "<p>", $custom); ?>
											<?php $custom = str_replace("%/p%", "</p>", $custom); ?>
											<?php $custom = str_replace("%center%", "<center>", $custom); ?>
											<?php $custom = str_replace("%/center%", "</center>", $custom); ?>
		<div class="profile-box">
			<div class="box-head">
				<div class="box-fg">
					<div class="headerTitle">
						<span>Custom Box</span>
					</div>
				</div>
				<div class="box-bg"></div>
			</div>
			<div class="box-body">
				<div class="box-fg">
					<?php echo $custom ?>
				</div>
				<div class="box-bg"></div>
			</div>
		</div><?php } ?>
	
	
	
	
</div>
<!-- End Side Column -->




<!-- Begin Main Column -->
<div id="profile-main-content">
<div class="profile-box">
				<div class="box-head">
					<div class="box-fg">
						<div class="headerTitle">
							<div class="headerTitleRight">
									<form name="subscribeToUsernameBox" method="post" action="/web/20080612034836/http://youtube.com/subscription_center">
									
									<input type="hidden" name="add_user" value="smosh">
									<a href="/get/subscribe?n=<?php echo htmlspecialchars($_user['username']) ?>" class="headers" title="Subscribe to <?php echo htmlspecialchars($_user['username']) ?>'s videos">Subscribe to <?php echo htmlspecialchars($_user['username']) ?>'s videos</a>
									<input name="session_token" type="hidden" value="MvVn5WrBjuxKQDwBzykMs2-qb-98MTIxMzMyODkxNg=="></form>
							</div>
							<span>Videos (<a href="/user/<?php echo htmlspecialchars($_user['username']) ?>/videos" class="headersSmall"><?php echo $__user_h->fetch_user_videos($_user['username']); ?></a>)</span>
						</div>
					</div>
					<div class="box-bg"></div>
				</div>
				<div class="box-body">
					<div class="box-fg">
							<div id="profileVideos">
								<table border="0" width="510" align="center">
	<tbody><tr style="height:1px;">
		<td width="170"></td><td width="170"></td><td width="170"></td>
	</tr>
	<tr>
	
		</tr><tr>
													<?php
														$stmt = $__db->prepare("SELECT * FROM videos WHERE author = :username ORDER BY id DESC LIMIT 3");
														$stmt->bindParam(":username", $_user['username']);
														$stmt->execute();

														while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$video['age'] = $__time_h->time_elapsed_string($video['publish']);		
															$video['duration'] = $__time_h->timestamp($video['duration']);
															$video['views'] = $__video_h->fetch_video_views($video['rid']);
															$video['author'] = htmlspecialchars($video['author']);		
															$video['title'] = htmlspecialchars($video['title']);
															$video['description'] = $__video_h->shorten_description($video['description'], 50);
													?>
		<td width="170" valign="top" align="center">
		<div style="width: 140px; margin-bottom: 6px;">
			<div class="video-thumb-big">
				<a href="/web/20080612034836/http://youtube.com/watch?v=T6SZyALXu0Y"><img src="https://web.archive.org/web/20080612034836im_/http://i.ytimg.com/vi/T6SZyALXu0Y/default.jpg" alt="Smosh - That Damn Yard Sale"></a>
			</div>
			<div style="text-align:left;">
				<div class="vtitle">
					<a href="/web/20080612034836/http://youtube.com/watch?v=T6SZyALXu0Y">Smosh - That Damn Yard Sale</a><br>
					<span class="runtime">04:02</span>
				</div>
				<div class="vfacets">
					Added: 2 weeks ago<br>
						Views: 1,912,054<br>
				</div>
			</div>
		</div>
	</td><?php } ?></tr><tr>
													<?php
														$stmt = $__db->prepare("SELECT * FROM videos WHERE author = :username ORDER BY id DESC LIMIT 3, 3");
														$stmt->bindParam(":username", $_user['username']);
														$stmt->execute();

														while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$video['age'] = $__time_h->time_elapsed_string($video['publish']);		
															$video['duration'] = $__time_h->timestamp($video['duration']);
															$video['views'] = $__video_h->fetch_video_views($video['rid']);
															$video['author'] = htmlspecialchars($video['author']);		
															$video['title'] = htmlspecialchars($video['title']);
															$video['description'] = $__video_h->shorten_description($video['description'], 50);
													?>
		<td width="170" valign="top" align="center">
		<div style="width: 140px; margin-bottom: 6px;">
			<div class="video-thumb-big">
				<a href="/web/20080612034836/http://youtube.com/watch?v=T6SZyALXu0Y"><img src="https://web.archive.org/web/20080612034836im_/http://i.ytimg.com/vi/T6SZyALXu0Y/default.jpg" alt="Smosh - That Damn Yard Sale"></a>
			</div>
			<div style="text-align:left;">
				<div class="vtitle">
					<a href="/web/20080612034836/http://youtube.com/watch?v=T6SZyALXu0Y">Smosh - That Damn Yard Sale</a><br>
					<span class="runtime">04:02</span>
				</div>
				<div class="vfacets">
					Added: 2 weeks ago<br>
						Views: 1,912,054<br>
				</div>
			</div>
		</div>
	</td><?php } ?> </tr><tr>
													<?php
														$stmt = $__db->prepare("SELECT * FROM videos WHERE author = :username ORDER BY id DESC LIMIT 6, 3");
														$stmt->bindParam(":username", $_user['username']);
														$stmt->execute();

														while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$video['age'] = $__time_h->time_elapsed_string($video['publish']);		
															$video['duration'] = $__time_h->timestamp($video['duration']);
															$video['views'] = $__video_h->fetch_video_views($video['rid']);
															$video['author'] = htmlspecialchars($video['author']);		
															$video['title'] = htmlspecialchars($video['title']);
															$video['description'] = $__video_h->shorten_description($video['description'], 50);
													?>
		<td width="170" valign="top" align="center">
		<div style="width: 140px; margin-bottom: 6px;">
			<div class="video-thumb-big">
				<a href="/web/20080612034836/http://youtube.com/watch?v=T6SZyALXu0Y"><img src="https://web.archive.org/web/20080612034836im_/http://i.ytimg.com/vi/T6SZyALXu0Y/default.jpg" alt="Smosh - That Damn Yard Sale"></a>
			</div>
			<div style="text-align:left;">
				<div class="vtitle">
					<a href="/web/20080612034836/http://youtube.com/watch?v=T6SZyALXu0Y">Smosh - That Damn Yard Sale</a><br>
					<span class="runtime">04:02</span>
				</div>
				<div class="vfacets">
					Added: 2 weeks ago<br>
						Views: 1,912,054<br>
				</div>
			</div>
		</div>
	</td><?php } ?> </tr><tr>

		</tr>
	
	<tr>
		<td colspan="3" align="right">
			<b><a href="/web/20080612034836/http://youtube.com/profile_videos?user=smosh&amp;p=r">See All 49 Videos</a></b>
		</td>
	</tr>
</tbody></table>

							</div>
						<div id="loadingDiv" class="marT18 alignC" style="display:none;height:500px;">
							<br><br>
							<img src="https://web.archive.org/web/20080612034836im_/http://s.ytimg.com/yt/img/icn_loading_animated-vfl24663.gif">
							<br><br>
						</div>
					</div>
					<div class="box-bg"></div>
				</div>
			</div>
			<div class="profile-box">
				<div class="box-head">
					<div class="box-fg">
						<div class="headerTitle">
							<span>Subscribers (<a href="/subs?n=<?php echo htmlspecialchars($_user['username']) ?>" class="headersSmall">24</a>)</span>
						</div>
					</div>
					<div class="box-bg"></div>
				</div>
				<div class="box-body">
					<div class="box-fg">
						<center>
								<table height="121" cellpadding="0" cellspacing="0">
			<tbody><tr>
				<td class="alignTop" style="padding-top:4px">
					<table width="500" height="121" style="background-color: XXXXXX; " cellpadding="0" cellspacing="0">
						<tbody><tr>
							<td style="border-bottom:none;" class="alignTop">
							<?php $sub1 = 0 ?>
													<?php
														$stmt = $__db->prepare("SELECT * FROM subscribers WHERE reciever = :username ORDER BY id LIMIT 4");
														$stmt->bindParam(":username", $_user['username']);
														$stmt->execute();
														while($subscribers = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$subber['name'] = $subscribers['sender'];		
													?><?php $subser = $__user_h->fetch_user_username($subber['name']); ?><?php $sub1 = $sub1 + 1 ?>
							<div class="videobarthumbnail_block" id="div_subscribers_">
								<center>
									<div class="user-thumb-xlarge"><a id="href_subscribers_" href="/user/<?php echo htmlspecialchars($subber['name']); ?>"><img id="img_subscribers_" src="/dynamic/pfp/<?php echo $subser['pfp'] ?>" onload="opacity(&#39;img_subscribers_0&#39;, 80, 100, 800);" style="opacity: 1;"></a></div>
									<div id="title1_subscribers_0" class="xsmallText grayText padB3"><a href="/user/<?php echo htmlspecialchars($subber['name']); ?>" title="<?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 13)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 11)); ?> <?php	}	?>"><?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 14)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 14)); ?> <?php	}	?></a></div>
									<div id="title2_subscribers_0" class="xsmallText grayText padB3"><span style="color: #333"></span></div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_subscribers__alternate" style="display: none">
								<center>
									<div><img src="./index_files/pixel-vfl73.gif" width="80" height="60"></div>
									<div class="xsmallText grayText padB3">&nbsp;</div>
									<div class="xsmallText grayText padB3">&nbsp;</div>
								</center>
							</div><?php } ?>

							</td>
						</tr>
					</tbody></table>
				</td>
			</tr>
		</tbody></table>

						</center>
					</div>
					<div class="box-bg"></div>
				</div>
			</div>
		
	
	
		
		
			<div class="profile-box">
				<div class="box-head">
					<div class="box-fg">
						<div class="headerTitle">
							<span>Friends (<a href="https://web.archive.org/web/20080614080120/http://youtube.com/profile_friends?user=nintendo" class="headersSmall">2</a>)</span>
						</div>
					</div>
					<div class="box-bg"></div>
				</div>
				<div class="box-body">
					<div class="box-fg">
						<center>
								<table height="121" cellpadding="0" cellspacing="0">
			<tbody><tr>
				<td class="alignTop padTsm"><img src="./index_files/pixel-vfl73.gif" class="btn_vscroll_lt_18x106 hand" onclick="fadeOldImage(&#39;friends&#39;,&#39;2&#39;);shiftLeft(&#39;friends&#39;)" id="vbrol"></td>

				<td class="alignTop" style="padding-top:4px">
					<table width="500" height="121" style="background-color: XXXXXX; " cellpadding="0" cellspacing="0">
						<tbody><tr>
							<td style="border-bottom:none;" class="alignTop">
							<div class="videobarthumbnail_block" id="div_friends_0">
								<center>
									<div class="user-thumb-xlarge"><a id="href_friends_0" href="https://web.archive.org/web/20080614080120/youtube.com/user/null/user/K8t05"><img id="img_friends_0" src="./index_files/default(4).jpg" onload="opacity(&#39;img_friends_0&#39;, 80, 100, 800);" style="opacity: 1;"></a></div>
									<div id="title1_friends_0" class="xsmallText grayText padB3"><a href="https://web.archive.org/web/20080614080120mp_/http://www.youtube.com/user/K8t05" title="K8t05">K8t05</a></div>
									<div id="title2_friends_0" class="xsmallText grayText padB3"><span style="color: #333"></span></div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_friends_0_alternate" style="display: none">
								<center>
									<div><img src="./index_files/pixel-vfl73.gif" width="80" height="60"></div>
									<div class="xsmallText grayText padB3">&nbsp;</div>
									<div class="xsmallText grayText padB3">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_friends_1">
								<center>
									<div class="user-thumb-xlarge"><a id="href_friends_1" href="https://web.archive.org/web/20080614080120/youtube.com/user/null/user/hey1031"><img id="img_friends_1" src="./index_files/1.jpg" onload="opacity(&#39;img_friends_1&#39;, 80, 100, 800);" style="opacity: 1;"></a></div>
									<div id="title1_friends_1" class="xsmallText grayText padB3"><a href="https://web.archive.org/web/20080614080120mp_/http://www.youtube.com/user/hey1031" title="hey1031">hey1031</a></div>
									<div id="title2_friends_1" class="xsmallText grayText padB3"><span style="color: #333"></span></div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_friends_1_alternate" style="display: none">
								<center>
									<div><img src="./index_files/pixel-vfl73.gif" width="80" height="60"></div>
									<div class="xsmallText grayText padB3">&nbsp;</div>
									<div class="xsmallText grayText padB3">&nbsp;</div>
								</center>
							</div>
							</td>
						</tr>
					</tbody></table>
				</td>
				
				<td class="alignTop padTsm"><img src="./index_files/pixel-vfl73.gif" class="btn_vscroll_rt_18x106 hand" onclick="fadeOldImage(&#39;friends&#39;,&#39;2&#39;);shiftRight(&#39;friends&#39;);" id="vbror"></td>
			</tr>
		</tbody></table>

						</center>
					</div>
					<div class="box-bg"></div>
				</div>
			</div>
		
	
	
		<div class="profile-box">
			<div class="box-head">
				<div class="box-fg">
					<div class="headerTitle">
						<div class="headerTitleRight">
							<a href="https://web.archive.org/web/20080614080120/http://youtube.com/profile_comment_post?user=nintendo" class="headers" rel="nofollow">Add Comment</a>
						</div>
						<span>Channel Comments (<a href="https://web.archive.org/web/20080614080120/http://youtube.com/profile_comment_all?user=nintendo" class="headersSmall">260</a>)</span>
					</div>
				</div>
				<div class="box-bg"></div>
			</div>
			<div class="box-body">
				<div class="box-fg">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody><tr>
							<td width="100%" colspan="2">&nbsp;</td>
						</tr>
									
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/youngkirbymaster" title="youngkirbymaster"><img src="./index_files/no_videos_140.jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/youngkirbymaster" title="youngkirbymaster">youngkirbymaster</a><span class="labels"> | June 14, 2008</span>
				<span>
					</span>
			</div>

		<div>Make a new earthbound nintendo</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/YoshiPower65" title="YoshiPower65"><img src="./index_files/1(1).jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/YoshiPower65" title="YoshiPower65">YoshiPower65</a><span class="labels"> | June 11, 2008</span>
				<span>
					</span>
			</div>

		<div>thanks for creating Yoshi!!</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/TheWordOfWWE" title="TheWordOfWWE"><img src="./index_files/default(5).jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/TheWordOfWWE" title="TheWordOfWWE">TheWordOfWWE</a><span class="labels"> | June 10, 2008</span>
				<span>
					</span>
			</div>

		<div>me = was here</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/nintendo250" title="nintendo250"><img src="./index_files/default(6).jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/nintendo250" title="nintendo250">nintendo250</a><span class="labels"> | June 09, 2008</span>
				<span>
					</span>
			</div>

		<div>-_-" What a waste of a good username....YOU STOLE IT</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/lolman180" title="lolman180"><img src="./index_files/default(7).jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/lolman180" title="lolman180">lolman180</a><span class="labels"> | June 08, 2008</span>
				<span>
					</span>
			</div>

		<div>Waiste of a user</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/lastlogin14yearsago" title="lastlogin14yearsago"><img src="./index_files/no_videos_140.jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/lastlogin14yearsago" title="lastlogin14yearsago">lastlogin14yearsago</a><span class="labels"> | June 04, 2008</span>
				<span>
					</span>
			</div>

		<div>waste of a username</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/WARDEATHFUN" title="WARDEATHFUN"><img src="./index_files/default(8).jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/WARDEATHFUN" title="WARDEATHFUN">WARDEATHFUN</a><span class="labels"> | June 04, 2008</span>
				<span>
					</span>
			</div>

		<div>so many good names :(</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/Xtream3amer" title="Xtream3amer"><img src="./index_files/1(2).jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/Xtream3amer" title="Xtream3amer">Xtream3amer</a><span class="labels"> | June 03, 2008</span>
				<span>
					</span>
			</div>

		<div>I was here.</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/SOUPERxXxMUSHROOM89" title="SOUPERxXxMUSHROOM89"><img src="./index_files/1(3).jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/SOUPERxXxMUSHROOM89" title="SOUPERxXxMUSHROOM89">SOUPERxXxMUSHROOM89</a><span class="labels"> | June 02, 2008</span>
				<span>
					</span>
			</div>

		<div>visit my channel</div>
		</td>
		</tr>
		
		<tr class="commentsTableFull">
		<td valign="top" width="60" align="left">
			<div class="user-thumb-large">
			<a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/particleion" title="particleion"><img src="./index_files/1(4).jpg"></a>
			</div>
		</td>
		<td valign="top" width="85%" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="https://web.archive.org/web/20080614080120/http://youtube.com/user/particleion" title="particleion">particleion</a><span class="labels"> | June 02, 2008</span>
				<span>
					</span>
			</div>

		<div>Seek Truth</div>
		</td>
		</tr>

						<tr>
							<td colspan="3" align="center">
								<div style="padding: 10px 0px; text-align: center;">
									<span class="smallText">
										<a href="https://web.archive.org/web/20080614080120/http://youtube.com/profile_comment_post?user=nintendo" rel="nofollow">Add Comment</a>
											| <a href="https://web.archive.org/web/20080614080120/http://youtube.com/profile_comment_all?user=nintendo">See all comments</a>
									</span>
								</div>
							</td>
						</tr>
					</tbody></table>
				</div>
				<div class="box-bg"></div>
			</div>
		</div>
	
	
</div>
<!-- End Main Column -->




<div style="clear:both"></div>

<!-- End Channel Content -->


<script type="text/javascript" src="./index_files/ga.js.download"></script>
<script type="text/javascript">
var pageTracker;
function urchinTracker (a) {pageTracker._trackPageview(a);}
</script>


<script type="text/javascript">
pageTracker = _gat._getTracker("UA-3482161-1");
pageTracker._initData();
</script>

<script type="text/javascript">
var page = "" + "/Profiles" + "/" + "nintendo";
pageTracker._trackPageview(page);
</script>



			<script src="./index_files/AJAX.js.download"></script>
	<script>
		function getSimpleCallback(xhr, callback, domEl) {
			if(xhr.responseXML == null) {
				callback("Error while processing your request.", domEl);
				return;
			}
			var root_node = getRootNode(xhr);
			var return_code = getNodeValue(root_node, 'return_code');

			if(return_code == 0) {
				var success_message = getNodeValue(root_node, 'success_message');
				if (success_message != null) {
					callback(success_message, domEl);
				}
			} else {
				var error_msg = getNodeValue(root_node, 'error_message');
				if (error_msg == null || error_msg.length == 0) {
					error_msg = "An error occured while performing this operation.";
				}
				callback(error_msg, domEl)
			}

			redirect_val = getNodeValue(root_node, 'redirect_on_success');
			if(redirect_val != null) {
				window.location=redirect_val;
			}
		}
		function getSimpleXR(url, callback, domEl) {
			getUrl(url, true, execOnSuccess(getSimpleCallback, callback, domEl));
		}
		function showConfMsg(msg, domEl) {
			if(domEl && domEl.parentNode) {
				domEl.parentNode.style.backgroundColor = '#fff';
				domEl.parentNode.innerHTML = msg;
			}
		}
	</script>
		
		
		
	</div>
	




<!--
playback timings (ms):
  captures_list: 679.043
  exclusion.robots: 218.062
  exclusion.robots.policy: 218.05
  xauthn.identify: 166.561
  xauthn.chkprivs: 51.179
  RedisCDXSource: 17.632
  esindex: 0.011
  LoadShardBlock: 177.772 (3)
  PetaboxLoader3.datanode: 187.002 (4)
  CDXLines.iter: 41.678 (3)
  load_resource: 288.374
  PetaboxLoader3.resolve: 77.913
--></body></html>