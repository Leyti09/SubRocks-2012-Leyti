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
	$__server->page_embeds->page_description = "Betatube is a website that is using the YouTube 2012 layout.";
	$__server->page_embeds->page_image = "/yt/imgbin/full-size-logo.png";
	$__server->page_embeds->page_url = "http://betatube.net/";
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head><script src="//archive.org/includes/analytics.js?v=cf34f82" type="text/javascript"></script>
<script type="text/javascript">window.addEventListener('DOMContentLoaded',function(){var v=archive_analytics.values;v.service='wb';v.server_name='wwwb-app216.us.archive.org';v.server_ms=1304;archive_analytics.send_pageview({});});</script>
<script type="text/javascript" src="/_static/js/bundle-playback.js?v=poeZ53Bz" charset="utf-8"></script>
<script type="text/javascript" src="/_static/js/wombat.js?v=UHAOicsW" charset="utf-8"></script>
<script type="text/javascript">
  __wm.init("https://web.archive.org/web");
  __wm.wombat("http://m.youtube.com/","20120401043357","https://web.archive.org/","web","/_static/",
	      "1333254837");
</script>
<link rel="stylesheet" type="text/css" href="/_static/css/banner-styles.css?v=fantwOh2" />
<link rel="stylesheet" type="text/css" href="/_static/css/iconochive.css?v=qtvMKcIJ" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>
<title>Betatube - mobile version yes</title>
<link rel="icon" href="https://web.archive.org/web/20120401043357im_/http://s.ytimg.com/yt/favicon-vflZlzSbU.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="https://web.archive.org/web/20120401043357im_/http://s.ytimg.com/yt/favicon-vflZlzSbU.ico" type="image/x-icon"/>
<style type="text/css">
/* <![CDATA[ */
a:link,a:visited,a:hover {color:#0033CC;text-decoration:none}
/* ]]> */
</style>
<script type="text/javascript">
/* <![CDATA[ */
if(typeof ytm == "undefined") var ytm = {};
ytm.startTime = new Date().getTime();
ytm.iref = {};
ytm.nextiref_ = 1;
ytm.isOn3g_ = function() {
if (navigator.connection && navigator.connection.type) {
return navigator.connection.type != navigator.connection.WIFI;
}
return 0
};
ytm.sendPlaybackPing = function(vid, fmt, feature) {
setTimeout(function() {
var img = new Image();
var id = ytm.nextiref_++;
ytm.iref[id] = img;
img.onload = img.onerror = function() {
delete ytm.iref[id];
};
img.src = "/gen_204?"+[
"app=youtube_mobile",
"el=",
"feature="+feature,
(fmt ? "fmt="+fmt : ""),
"gl=FR",
"hl=en",
"on3g="+(ytm.isOn3g_() ? 1 : 0),
"plid=",
"ps=mobile",
"rdm="+(new Date().getTime()%10000),
"video_id="+vid
].join("&");
img = null;
}, 0);
return true;
};
/* ]]> */
</script>
</head>
<body style="color:#333;font-size:13px;font-family:sans-serif;margin:0;background-color:#fff"><!-- BEGIN WAYBACK TOOLBAR INSERT -->
<style type="text/css">
body {
  margin-top:0 !important;
  padding-top:0 !important;
  /*min-width:800px !important;*/
}
</style>
<table width="100%" cellspacing="0">
<tr>
<td valign="top" style="padding: 6px 0 2px 5px; font-size: 0">
<img src="https://web.archive.org/web/20120401043357im_/http://s.ytimg.com/yt/mobile/img/pic_ytlogo_58x20-vflqOLiic.gif" alt="YouTube logo" width="58" height="20" style="border:0;margin:0px;"/>
<a id="top"></a>
</td>
<td align="right" valign="middle" style="padding-right: 3px">
<span style="color:#000000"><a href="https://web.archive.org/web/20120401043357/https://accounts.google.com/ServiceLogin?uilel=3&amp;service=youtube&amp;passive=true&amp;continue=http%3A%2F%2Fm.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26nomobiletemp%3D1%26warned%3D1%26feature%3Dmobile%26next%3D%252F%26hl%3Den_US&amp;hl=en_US&amp;ltmpl=mobile">Sign In</a></span>
</td>
</tr>
</table>
<div style="height:1px;width:1px;overflow:hidden"><img src="https://web.archive.org/web/20120401043357im_/http://s0.2mdn.net/1275935/trans_gif1x1.gif" width="1" height="1"/></div>
<div style="margin-left:3px;margin-right:3px">
<div style="border-top:1px solid #999;border-bottom: 1px solid #999;padding:10px;font-size:140%;background:#EEE;text-align:center">
<b>Mobile</b>
| <a href="/">Desktop</a>
</div>



<form id="searchForm" action="/web/20120401043357/http://m.youtube.com/results?gl=FR&amp;hl=en&amp;client=mv-google" method="get" style="padding:5px 0">
<div>

<input name="gl" type="hidden" value="FR"/>
<input name="client" type="hidden" value="mv-google"/>
<input name="hl" type="hidden" value="en"/>
<input accesskey="*" name="q" type="text" size="15" maxlength="128" style="color:#333;padding:0;font-family:sans-serif;width:65%" value=""/>
<input type="submit" name="submit" value="Search" style="padding:0;color:black;margin-top:2px;font-size:100%"/>
</div>
</form>

</div>
											<?php
												$stmt = $__db->prepare("SELECT * FROM videos WHERE visibility = 'n' ORDER BY id DESC LIMIT 20");
												$stmt->execute();
												while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
													$video['age'] = $__time_h->time_elapsed_string($video['publish']);		
													$video['duration'] = $__time_h->timestamp($video['duration']);
													$video['views'] = $__video_h->fetch_video_views($video['rid']);
													$video['author'] = htmlspecialchars($video['author']);		
													$video['title'] = htmlspecialchars($video['title']);
													$video['description'] = $__video_h->shorten_description($video['description'], 50);
													$video['pfp'] = $__user_h->fetch_pfp($video['author']);
													$_user22 = $__user_h->fetch_user_username($video['author']);
													if($_user22['title'])	{
														$video['dname'] = $_user22['title'];
													} else{
														$video['dname'] = $video['author'];
													}
													if($_user22['vanity'])	{	$video['url'] = $_user22['vanity']; } else{ $video['url'] = 'user/' . $video['author']; }
											?>
<hr size="1" noshade="noshade" color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;"/>
<table width="100%">
<tr valign="top">
<td style="font-size:0px" width="85">

<a href="https://web.archive.org/web/20120401043357/rtsp://v6.cache6.c.youtube.com/CjYLENy73wIaLQkKDTQMD-XGaRMYESARFEIJbXYtZ29vZ2xlSARSBWluZGV4YLrVn4Siwa67Tww=/0/0/0/video.3gp" onclick="return ytm.sendPlaybackPing('acblDww0DQo','17','m-feedf');">  <img src="/dynamic/thumbs/<?php echo htmlspecialchars($video['thumbnail']) ?>" alt="video" width="80" height="60" style="border:0;"/></a>
</td>
<td style="width:100%;font-size:13px">
<div style="font-size:90%;text-align:left;padding-bottom:1px" dir="ltr">
<a accesskey="4" href="/watch?v=<?php echo htmlspecialchars($video['rid']) ?>"><?php echo htmlspecialchars($video['title']) ?>
</a>
</div>
<div style="color:#333;font-size:80%">4:43&nbsp;&nbsp;<span style="color: #006500">5992</span> likes, <span style="color: #CB0000">127</span> dislikes
</div>
<div style="color:#333;font-size:80%">
by <a href="profile?n=<?php echo htmlspecialchars($video['author']) ?>"><?php echo htmlspecialchars($video['dname']) ?></a>
<span style="color:#F00;font-size:80%">
NEW
</span>
</div>
<div style="color:#333;font-size:80%">0 views</div>
<div style="color:#666;margin-top:5px;font-size:90%">
In <a>Recent Videos</a>
</div>
</td>
</tr>
</table>
<?php } ?>
</div>
<hr size="1" noshade="noshade" color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;"/>
<div id="botPagination">      <div style="font-size:90%;margin-top:8px;text-align:center">
<span style="padding:0px 3px">
<a accesskey="#" href="/web/20120401043357/http://m.youtube.com/?gl=FR&amp;hl=en&amp;client=mv-google&amp;utcoffset=0&amp;p=2">Next page     &raquo;
</a>
</span>
</div>
</div>
<br/>
<hr size="1" noshade="noshade" color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;"/>
<br/>
<div style="margin-left:3px;margin-right:3px">
<div style="padding-bottom:0"><a href="#top" accesskey="0">YouTube Home</a></div>
<div style="padding-bottom:0"><a href="/web/20120401043357/http://m.youtube.com/videos?gl=FR&amp;hl=en&amp;client=mv-google&amp;s=mp&amp;t=t">Browse Videos</a></div>
<div style="padding-bottom:0"><a href="/web/20120401043357/http://m.youtube.com/my_account?gl=FR&amp;hl=en&amp;client=mv-google">My Account</a></div>
<div style="padding-bottom:0"><a href="/web/20120401043357/http://m.youtube.com/my_videos_upload?gl=FR&amp;hl=en&amp;client=mv-google">Upload</a></div>
</div>
<br/>

<div style="border-top:1px solid #999;font-size:80%;background:#EEE;text-align:center">
<br/>
<a href="https://web.archive.org/web/20120401043357/http://www.google.com/m/survey/youtube?gl=FR&amp;hl=en">Feedback</a>
<br/>
</div>
<div style="border-top:1px solid #999;font-size:80%;background:#EEE;text-align:center">
<br/>
<div>
Location:
<a href="">Earth</a>
-
Language:
<a href="">English</a>
</div>
<div>
<a href="https://web.archive.org/web/20120401043357/https://accounts.google.com/ServiceLogin?uilel=3&amp;service=youtube&amp;passive=true&amp;continue=http%3A%2F%2Fm.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26nomobiletemp%3D1%26warned%3D1%26feature%3Dmobile%26next%3D%252F%26hl%3Den_US&amp;hl=en_US&amp;ltmpl=mobile">Sign In</a>
</div>
<div>
<a href="https://web.archive.org/web/20120401043357/http://www.google.com/support/youtube?p=youtube&amp;hl=en">Help</a>
-
<a href="/web/20120401043357/http://m.youtube.com/terms?gl=FR&amp;hl=en&amp;client=mv-google">Terms &amp; Privacy</a>
</div>
<br/>
<b>Mobile</b>
| <a href="/">Desktop</a>
<div dir="ltr">
2022 Betatube
</div>
<br/>
</div>
</body>
</html>