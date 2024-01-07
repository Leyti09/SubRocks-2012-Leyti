<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/config.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/db_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/time_manip.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/video_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_update.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_insert.php"); ?>
<?php $__video_h = new video_helper($__db); ?>
<?php $__user_h = new user_helper($__db); ?>
<?php $__user_i = new user_insert($__db); ?>
<?php $__user_u = new user_update($__db); ?>
<?php $__db_h = new db_helper(); ?>
<?php $__time_h = new time_helper(); ?>
<?php if(!$__video_h->video_exists($_GET['v'])) { header("Location: /?error=This video does not exist!"); } ?>
<?php $_video = $__video_h->fetch_video_rid($_GET['v']); ?>
<?php $_video['comments'] = $__video_h->get_comments_from_video($_video['rid']); ?>
<?php
	if($_video['visibility'] == "v" && @$_SESSION['siteusername'] != $_video['author'])
		header("Location: /");

	$__video_h->check_view($_GET['v'], @$_SERVER["HTTP_CF_CONNECTING_IP"]);

	$_SESSION['current_video'] = $_video['rid'];
?>
<!-- idk how the april watch page looks so I am just guessing -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $sitename	?></title>
<link href="april/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="720" bgcolor="#FFFFFF" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td bgcolor="#999999"><img src="april/imfg/box_tl.gif" width="10" height="10"></td>
		<td colspan="3"><img src="april/imfg/box_top.gif" width="700" height="10"></td>
		<td bgcolor="#999999"><img src="april/imfg/box_tr.gif" width="10" height="10"></td>
	</tr>
	<tr>
		<td><img src="april/imfg/box_lside.gif" width="10" height="72"></td>
		<td width="700" colspan="3">
			<table width="700" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td rowspan="2"><a href="index.php"><img src="/BetaTube.png" width="154" height="72" border="0"></a></td>
					<td colspan="4"><img src="april/imfg/tab_tspace.gif" width="394" height="34" border="0"></td>
										
						<td><a href="signup.php"><img src="april/imfg/link_signup.gif" width="94" height="34" border="0"></a></td>
						<td><a href="help.php"><img src="april/imfg/link_help.gif" width="58" height="34" border="0"></a></td>
				</tr>
				<tr>
					<td><a href="index.php"><img src="april/imfg/tab_home_on.gif" width="69" height="38" border="0"></a></td>
					<td><a href="my_favorites.php"><img src="april/imfg/tab_fav_off.gif" width="111" height="38" border="0" alt="Favorites"></a></td>
					<td><a href="my_messages.php"><img src="april/imfg/tab_mes_off.gif" width="118" height="38" border="0" alt="Messages"></a></td>
					<td><a href="my_videos.php"><img src="april/imfg/tab_vid_off.gif" width="96" height="38" border="0" alt="Videos"></a></td>
					<?php if(!isset($_SESSION['siteusername'])) { ?>
					<td><a href="/sign_in"><img src="april/imfg/tab_pro_off.gif" width="94" height="38" border="0" alt="Profile"></a></td>
					<?php	}	else{	?>
					<td><a href="/profile?n=<?php echo htmlspecialchars($_SESSION['siteusername']); ?>"><img src="april/imfg/tab_pro_off.gif" width="94" height="38" border="0" alt="Profile"></a></td>	
					<?php	}	?>
					<td><img src="april/imfg/tab_rspacc.gif" width="58" height="38"></td>
				</tr>
			</table></td>
		<td><img src="april/imfg/box_rside.gif" width="10" height="72"></td>
	</tr>
	<tr>
		<td valign="top"><img src="april/imfg/box_point_tlo.gif" width="10" height="40"></td>
		<td valign="top"><img src="april/imfg/box_point_tli.gif" width="28" height="40"></td>
		<td width="644" align="center">
		</td>
		<td valign="top"><img src="april/imfg/box_point_tri.gif" width="28" height="40"></td>
		<td valign="top"><img src="april/imfg/box_point_tro.gif" width="10" height="40"></td>
	</tr>
</table>

<table width="720" bgcolor="#FFFFFF" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="april/imfg/pixel.gif" width="15" height="1"></td>
		<td width="690">
<table width="690" cellpadding="0" cellspacing="0" border="0">
<div>
	<tr>
		<br>
		<?php	$vidid = $_GET['v']	?>
			<td align="left">
			<h1><?php echo htmlspecialchars($_video['title']); ?></h1>
			<iframe id="vid-player" style="border: 0px; overflow: hidden;" src="/april/player/splayer?v=<?php	echo $vidid	?>" width="366" height="236"></iframe>
			</td><br>
		<td valign="bottom">

		</td>
	</tr>
</div>
<div>
	<td align="bottom">
		<h3>
			<?php echo $__video_h->shorten_description($_video['description'], 400000, true); ?>
		</h3>		
		<div class="watchAdded">
			Added: <?php echo date("M d, Y", strtotime($_video['publish'])); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($_video['author']); ?>"><?php echo htmlspecialchars($_video['author']); ?></a> //
		<a href="profile_videos.php?user=<?php echo htmlspecialchars($_video['author']); ?>">Videos</a> | <a href="profile_favorites.php?user=<?php echo htmlspecialchars($_video['author']); ?>">Favorites</a> | <a href="profile_friends.php?user=<?php echo htmlspecialchars($_video['author']); ?>">Friends</a>
		</div>
		<div class="watchDetails">
			Views: <?php echo $__video_h->fetch_video_views($_video['rid']); ?>
		</div>
	</td>
</div>
</table>
		</td>
		<td><img src="april/imfg/pixel.gif" width="15" height="1"></td>
	</tr>
</table>

<table width="720" bgcolor="#FFFFFF" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="april/imfg/box_bottom.gif" width="720" height="62"></td>
	</tr>
</table>

<br><table width="720" bgcolor="#CCCCCC" align="center" cellpadding="7" cellspacing="1" border="0">
	<tr>
		<td align="center" bgcolor="#AAAAAA"><span class="footer"><a href="about.php">About Us</a> | <a href="terms.php">Terms of Use</a> | <a href="privacy.php">Privacy Policy</a></span></td>
	</tr>
</table>

</body>
</html>

