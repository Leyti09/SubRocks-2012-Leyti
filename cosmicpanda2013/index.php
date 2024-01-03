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
<!DOCTYPE html>
<!-- saved from url=(0072)https://web.archive.org/web/20130304225950/youtube.com/user/osfirsttimer -->
<html lang="en" dir="ltr" xmlns:og="http://opengraphprotocol.org/schema/"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript">window.addEventListener('DOMContentLoaded',function(){var v=archive_analytics.values;v.service='wb';v.server_name='wwwb-app216.us.archive.org';v.server_ms=484;archive_analytics.send_pageview({});});</script>
<script type="text/javascript">
  __wm.init("https://web.archive.org/web");
  __wm.wombat("http://www.youtube.com/user/OsFirstTimer","20130304225950","https://web.archive.org/","web","/_static/",
	      "1362437990");
</script>
<link rel="stylesheet" type="text/css" href="/yt/cssbin/banner-styles.css">
<link rel="stylesheet" type="text/css" href="/yt/cssbin/iconochives.css">
      <script>
var yt = yt || {};yt.timing = yt.timing || {};yt.timing.data_ = yt.timing.data_ || {};yt.timing.tick = function(label, opt_time) {var tick = yt.timing.data_['tick'] || {};tick[label] = opt_time || new Date().getTime();yt.timing.data_['tick'] = tick;};yt.timing.info = function(label, value) {var info = yt.timing.data_['info'] || {};info[label] = value;yt.timing.data_['info'] = info;};yt.timing.reset = function() {yt.timing.data_ = {};};if (document.webkitVisibilityState == 'prerender') {yt.timing.info('prerender', 1);document.addEventListener('webkitvisibilitychange', function() {yt.timing.tick('start');}, false);}yt.timing.tick('start');try {var externalPt = (window.gtbExternal && window.gtbExternal.pageT() ||window.external && window.external.pageT);if (externalPt) {yt.timing.info('pt', externalPt);}} catch(e) {}if (window.chrome && window.chrome.csi) {yt.timing.info('pt', Math.floor(window.chrome.csi().pageT));}    </script>

<title><?php if($_user['title'])	{	?> <?php echo htmlspecialchars($_user['title']); ?> <?php } else {	?> <?php echo htmlspecialchars($_user['username']); ?> <?php	}	?> - Betatube</title><link rel="search" type="application/opensearchdescription+xml" href="https://web.archive.org/web/20130304225950/http://www.youtube.com/opensearch?locale=en_US" title="YouTube Video Search"><link rel="icon" href="https://web.archive.org/web/20130304225950im_/http://s.ytimg.com/yts/img/favicon-vfldLzJxy.ico" type="image/x-icon"><link rel="shortcut icon" href="https://web.archive.org/web/20130304225950im_/http://s.ytimg.com/yts/img/favicon-vfldLzJxy.ico" type="image/x-icon">   <link rel="icon" href="https://web.archive.org/web/20130304225950im_/http://s.ytimg.com/yts/img/favicon_32-vflWoMFGx.png" sizes="32x32"><link rel="canonical" href="https://web.archive.org/web/20130304225950/http://www.youtube.com/user/OsFirstTimer"><link rel="alternate" media="handheld" href="https://web.archive.org/web/20130304225950/http://m.youtube.com/user/OsFirstTimer?&amp;desktop_uri=%2Fuser%2FOsFirstTimer"><link rel="alternate" media="only screen and (max-width: 640px)" href="https://web.archive.org/web/20130304225950/http://m.youtube.com/user/OsFirstTimer?&amp;desktop_uri=%2Fuser%2FOsFirstTimer">    <meta name="title" content="">

    <meta name="description" content="What Computer operating system are you using on your computer right now? Windows 8? Mac OSX Lion? Ubuntu? Did you know there&#39;s thousands of operating systems...">

  <meta name="keywords" content="video, sharing, camera phone, video phone, free, upload">
    <link rel="image_src" href="./index_files/1.jpg">
    <link rel="alternate" type="application/rss+xml" title="RSS" href="https://web.archive.org/web/20130304225950/http://gdata.youtube.com/feeds/base/users/OsFirstTimer/uploads?alt=rss&amp;v=2&amp;orderby=published&amp;client=ytapi-youtube-profile">
        <meta property="og:url" content="https://web.archive.org/web/20130304225950/http://www.youtube.com/user/OsFirstTimer?view=&amp;feature=">
    <meta property="og:title" content="<?php if($_user['title'])	{	?> <?php echo htmlspecialchars($_user['title']); ?> <?php } else {	?> <?php echo htmlspecialchars($_user['username']); ?> <?php	}	?> - Betatube">
    <meta property="og:description" content="<?php echo $__server->page_embeds->page_description; ?>">
    <meta property="og:type" content="profile">
    <meta property="og:image" content="/dynamic/pfp/<?php echo htmlspecialchars($_user['pfp']); ?>">
    <meta property="og:site_name" content="Betatube">
        <meta name="twitter:card" value="summary">
    <meta name="twitter:url" value="https://www.betatube.net/user/<?php echo htmlspecialchars($_user['username']); ?>?view=&amp;feature=">
    <meta name="twitter:title" value="">
    <meta name="twitter:description" value="<?php echo $__server->page_embeds->page_description; ?>">
    <meta name="twitter:image" value="/dynamic/pfp/<?php echo htmlspecialchars($_user['pfp']); ?>">
    <meta name="twitter:site" value="@youtube">

        <link itemprop="url" href="https://www.betatube.net/user/<?php echo htmlspecialchars($_user['username']); ?>?view=&amp;feature=">
    <meta itemprop="name" content="">
    <meta itemprop="description" content="<?php echo $__server->page_embeds->page_description; ?>">
    <meta itemprop="paid" content="False">
	<span itemprop="author" itemscope="" itemtype="http://schema.org/Person">
        <link itemprop="url" href="http://www.youtube.com/user/OsFirstTimer">
      </span>
    <link itemprop="thumbnailUrl" href="/dynamic/pfp/<?php echo htmlspecialchars($_user['pfp']); ?>">
    <span itemprop="thumbnail" itemscope="" itemtype="http://schema.org/ImageObject">
      <link itemprop="url" href="/dynamic/pfp/<?php echo htmlspecialchars($_user['pfp']); ?>">
      <meta itemprop="width" content="500">
      <meta itemprop="height" content="500">
    </span>
      <meta itemprop="isFamilyFriendly" content="True">
      <meta itemprop="regionsAllowed" content="AD,AE,AF,AG,AI,AL,AM,AO,AQ,AR,AS,AT,AU,AW,AX,AZ,BA,BB,BD,BE,BF,BG,BH,BI,BJ,BL,BM,BN,BO,BQ,BR,BS,BT,BV,BW,BY,BZ,CA,CC,CD,CF,CG,CH,CI,CK,CL,CM,CN,CO,CR,CU,CV,CW,CX,CY,CZ,DE,DJ,DK,DM,DO,DZ,EC,EE,EG,EH,ER,ES,ET,FI,FJ,FK,FM,FO,FR,GA,GB,GD,GE,GF,GG,GH,GI,GL,GM,GN,GP,GQ,GR,GS,GT,GU,GW,GY,HK,HM,HN,HR,HT,HU,ID,IE,IL,IM,IN,IO,IQ,IR,IS,IT,JE,JM,JO,JP,KE,KG,KH,KI,KM,KN,KP,KR,KW,KY,KZ,LA,LB,LC,LI,LK,LR,LS,LT,LU,LV,LY,MA,MC,MD,ME,MF,MG,MH,MK,ML,MM,MN,MO,MP,MQ,MR,MS,MT,MU,MV,MW,MX,MY,MZ,NA,NC,NE,NF,NG,NI,NL,NO,NP,NR,NU,NZ,OM,PA,PE,PF,PG,PH,PK,PL,PM,PN,PR,PS,PT,PW,PY,QA,RE,RO,RS,RU,RW,SA,SB,SC,SD,SE,SG,SH,SI,SJ,SK,SL,SM,SN,SO,SR,SS,ST,SV,SX,SY,SZ,TC,TD,TF,TG,TH,TJ,TK,TL,TM,TN,TO,TR,TT,TV,TW,TZ,UA,UG,UM,US,UY,UZ,VA,VC,VE,VG,VI,VN,VU,WF,WS,YE,YT,ZA,ZM,ZW">


  <link id="css-304759147" rel="stylesheet" href="/yt/cssbin/www-core-vflcZE6_v.css">
  <link id="css-583125701" rel="stylesheet" href="/yt/cssbin/www-the-rest-vflzYVqky.css">
<style>body,#masthead-container,#yt-admin #vm-pageheader-container h1,#vm-video-actions-inner {background: #fbfbfb;}</style>    <link id="css-2184992028" rel="stylesheet" href="./index_files/www-channels3-vflmJQncG.css">

    <style>
      #branded-page-body-container {
      background-color: transparent;
      background-image: none;
  }

    </style>
      <script>
if (window.yt.timing) {yt.timing.tick("ct");}    </script>


<!-- machid: sNW5tN3Z2SWdXaDZybDJrTU5TeTlIV2poRWRnX2s4WFhBOUFFcEZETDB1Qk5temc4N3llUjVR -->

  

  <div id="body-container">
    <form name="logoutForm" method="POST" action="/logout">
      <input type="hidden" name="action_logout" value="1">
    <input name="session_token" type="hidden" value="6zhv_R6yQjzBoYrc_vI7cmBUDU18MTM2MjUyNDM5MEAxMzYyNDM3OTkw"></form>

    
    

    

    <!-- begin page -->
          
    <div id="yt-masthead-container" class="yt-grid-box"><div id="yt-masthead" class="">    <a id="logo-container" href="/" title="Betatube home"><img id="logo" src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Betatube home"></a>
<div id="yt-masthead-signin"><button class=" yt-uix-button yt-uix-button-primary" type="button" href="https://accounts.google.com/ServiceLogin?uilel=3&amp;service=youtube&amp;passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dsign_in_button%26hl%3Den_US%26next%3D%252Fuser%252FOsFirstTimer%26nomobiletemp%3D1&amp;hl=en_US" onclick=";window.location.href=this.getAttribute(&#39;href&#39;);return false;" role="button"><span class="yt-uix-button-content">Sign in </span></button></div><div id="yt-masthead-content"><span id="masthead-upload-button-group"><a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/upload" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-default" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ"><span class="yt-uix-button-content">Upload</span></a></span><form id="masthead-search" class="search-form consolidated-form" action="https://web.archive.org/web/20130304225950/http://youtube.com/results" onsubmit="if (_gel(&#39;masthead-search-term&#39;).value == &#39;&#39;) return false;"><button dir="ltr" class="search-btn-component search-button yt-uix-button yt-uix-button-default" type="submit" id="search-btn" onclick="if (_gel(&#39;masthead-search-term&#39;).value == &#39;&#39;) return false; _gel(&#39;masthead-search&#39;).submit(); return false;;return true;" tabindex="2" role="button"><span class="yt-uix-button-content">Search </span></button><div id="masthead-search-terms" class="masthead-search-terms-border" dir="ltr"><label><input id="masthead-search-term" class="search-term yt-uix-form-input-bidi" name="search_query" value="" type="text" tabindex="1" title="Search" dir="ltr" autocomplete="off" spellcheck="false" style="outline: none;"></label></div><input type="hidden" name="oq"><input type="hidden" name="gs_l"></form></div></div></div>
    
  


  <div id="alerts" class="  branded-page channel "></div>

  <div id="page-container">
    <div id="page" class="page-default   branded-page channel ">
      <div id="content" class="">
          
    <div class="subscription-menu-expandable subscription-menu-expandable-channels3 yt-rounded ytg-wide hid">
    <div class="content" id="recommended-channels-list"></div>
    <button class="close" type="button">close</button>
  </div>

      <div class="hid">
    <div class="yt-alert yt-alert-default yt-alert-success  " id="success-template">  <div class="yt-alert-icon">
    <img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-buttons"><button class="close yt-uix-close yt-uix-button yt-uix-button-close" type="button" onclick=";return false;" data-close-parent-class="yt-alert" role="button"><span class="yt-uix-button-content">Close </span></button></div><div class="yt-alert-content" role="alert"></div></div>
    <div class="yt-alert yt-alert-default yt-alert-error  " id="error-template">  <div class="yt-alert-icon">
    <img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-buttons"><button class="close yt-uix-close yt-uix-button yt-uix-button-close" type="button" onclick=";return false;" data-close-parent-class="yt-alert" role="button"><span class="yt-uix-button-content">Close </span></button></div><div class="yt-alert-content" role="alert"></div></div>
    <div class="yt-alert yt-alert-default yt-alert-warn  " id="warn-template">  <div class="yt-alert-icon">
    <img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-buttons"><button class="close yt-uix-close yt-uix-button yt-uix-button-close" type="button" onclick=";return false;" data-close-parent-class="yt-alert" role="button"><span class="yt-uix-button-content">Close </span></button></div><div class="yt-alert-content" role="alert"></div></div>
    <div class="yt-alert yt-alert-default yt-alert-info  " id="info-template">  <div class="yt-alert-icon">
    <img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-buttons"><button class="close yt-uix-close yt-uix-button yt-uix-button-close" type="button" onclick=";return false;" data-close-parent-class="yt-alert" role="button"><span class="yt-uix-button-content">Close </span></button></div><div class="yt-alert-content" role="alert"></div></div>
    <div class="yt-alert yt-alert-default yt-alert-status  " id="status-template">  <div class="yt-alert-icon">
    <img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-buttons"><button class="close yt-uix-close yt-uix-button yt-uix-button-close" type="button" onclick=";return false;" data-close-parent-class="yt-alert" role="button"><span class="yt-uix-button-content">Close </span></button></div><div class="yt-alert-content" role="alert"></div></div>
  </div>

  <div class="hid">
    <div id="message-container-template" class="message-container"></div>
  </div>




  <div id="branded-page-default-bg" class="ytg-base">
    <div id="branded-page-body-container" class="ytg-base clearfix enable-fancy-subscribe-button">

      <div id="branded-page-header-container" class="ytg-wide ">
          <div id="branded-page-header" class="ytg-wide">
    <div id="channel-header-main">
      <div class="upper-section clearfix">
        <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer">
          <span class="channel-thumb">
            <span class="video-thumb ux-thumb yt-thumb-square-60 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="./index_files/1.jpg" alt="OsFirstTimer" width="60"><span class="vertical-align"></span></span></span></span>
          </span>
        </a>
          <div class="upper-left-section ">

    <h1>
        <span class="qualified-channel-title" title="OsFirstTimer"><span class="qualified-channel-title-inner">OsFirstTimer</span></span><span class="qualified-channel-title-badge"></span>

    </h1>

    <div id="context-source-container" data-context-source="OsFirstTimer" data-context-image="//i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" style="display:none;"></div>

  </div>

        <div class="upper-left-section">
              <span class="yt-uix-button-subscription-container yt-uix-button-context-light"><button onclick=";window.location.href=this.getAttribute(&#39;href&#39;);return false;" class="yt-subscription-button subscription-button-with-recommended-channels yt-uix-button yt-uix-button-subscribe-branded" type="button" href="https://accounts.google.com/ServiceLogin?uilel=3&amp;service=youtube&amp;passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26continue_action%3DskWtl8y6c7Xhxi9_TM4IudYWz-uXEtgo8-0IRchlo0NeJA-1Cv0qrII-qW6OhNEkUYvQlG_KPH3vXV1PhvFUcwXsRTCr9jhsJSQryanR27I=%26feature%3Dsubscribe%26hl%3Den_US%26next%3D%252Fuser%252FOsFirstTimer%26nomobiletemp%3D1&amp;hl=en_US" data-subscription-value="UCRjXNz9JNSuE8n-Oej4Rflw" data-subscription-button-type="branded" data-subscription-feature="channels3" data-subscription-type="" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=channels3" role="button"><span class="yt-uix-button-icon-wrapper">  <img class="yt-uix-button-icon yt-uix-button-icon-subscribe-branded" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
<span class="yt-uix-button-valign"></span></span><span class="yt-uix-button-content">  <span class="subscribe-hh-label">Subscribe</span>
  <span class="subscribed-hh-label">Subscribed</span>
  <span class="unsubscribe-hh-label">Unsubscribe</span>
 </span></button><span class="yt-subscription-button-disabled-mask"></span></span>
        </div>
        <div class="upper-right-section">
            <div class="header-stats">

    
    <div class="stat-entry">
        <span class="stat-value">4,313</span>
  <span class="stat-name">subscribers</span>

    </div>


      
    <div class="stat-entry">
        <span class="stat-value">434,725</span>
  <span class="stat-name">video views</span>

    </div>

  </div>

          <span class="valign-shim"></span>
        </div>
      </div>
        <div class="channel-horizontal-menu clearfix">
            <ul role="tablist">
          <li role="presentation" class="selected">
    <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/videos?view=0" class="gh-tab-101" role="tab" aria-selected="true">
      Browse videos

    </a>
  </li>

  </ul>

              <form id="channel-search" class="
    " action="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/videos">
    <input name="query" type="text" autocomplete="off" class="search-field label-input-label" maxlength="100" placeholder="Search Channel" value="">
    <button class="search-btn" type="submit">
      <span class="search-btn-content">
Search
      </span>
    </button>
    <a class="search-dismiss-btn" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/videos?view=0">
      <span class="search-btn-content">
Clear
      </span>
    </a>
  </form>

        </div>
    </div>
  </div>

      </div>

      <div id="branded-page-body">
          <div class="channel-tab-content community-template channel-layout-two-column selected channel-tab-feed-content">
    <div class="tab-content-body">
      <div class="primary-pane">
          <div class="channel-activity-feeds">
    <div class="activity-feeds-container">
      <div class="browse-heading channels-browse-gutter-padding">
          <div class="activity-feeds-header clearfix">
      <div id="browse-view-options">
      <button class="flip channels-browse-options yt-uix-button yt-uix-button-text" type="button" onclick=";return false;" data-button-menu-id="browse-view-options-menu" role="button"><span class="yt-uix-button-content">View </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>
        <div id="browse-view-options-menu" class="channel-nav-item-dropdown hid">
    <ul>
        <li>
    <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/feed?activity_view=1&amp;filter=2" class="yt-uix-button-menu-item">
      <span class="browse-view-option-check selected"><img src="./index_files/pixel-vfl3z5WfW.gif"></span>
      All activities
    </a>
  </li>


        <li>
    <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/feed?activity_view=2&amp;filter=2" class="yt-uix-button-menu-item">
      <span class="browse-view-option-check "><img src="./index_files/pixel-vfl3z5WfW.gif"></span>
      Recent posts
    </a>
  </li>

    </ul>
  </div>

  </div>

      <ul>

    
      <li class="channels-browse-filter ">
    <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/videos?flow=grid&amp;view=0">
      Uploads
    </a>
  </li>

      <li class="channels-browse-filter ">
    <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/videos?flow=grid&amp;view=15">
      Likes
    </a>
  </li>



        <li class="channels-browse-filter selected">
    <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/feed?filter=2">
      Feed
    </a>
  </li>


      <li class="channels-browse-filter ">
    <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer/feed?filter=1">
      Comments
    </a>
  </li>


  </ul>

  </div>

        
          <hr class="yt-horizontal-rule channel-section-hr">

      </div>
      <div class="activity-feed">
        <div class="feed-list-container context-data-container">
                <div class="feed-item-list">
            <ul id="channel-feed" class="context-data-container">
              <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-0"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      1 day ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>The video of myself trying to perform a bunch of tasks I usually do on windows 7 on ubuntu 12.10 has been fully recorded. I was very pleased with ubuntu however when it came to using ubuntu to edit the video I was unfortunately unimpressed. I managed to get write a document with pictures and print it after installing the printer driver, run a windows game using playonlinux, play a nes game and much more! The video will take a very long time to edit but it should be uploaded within 7 days.</p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-0"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      3 days ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="iB7B_Lz4jzY" data-context-item-title="Mum tries out Windows Longhorn Build 4074 (2004)" data-context-item-views="2,300 views" data-context-item-time="34:58" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=iB7B_Lz4jzY" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/iB7B_Lz4jzY/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault.jpg" alt="" width="185" data-group-key="thumb-group-0"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">34:58</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="iB7B_Lz4jzY" role="button" data-tooltip-text="Watch Later"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Windows Longhorn Build 4074 (2004)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=iB7B_Lz4jzY" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Windows Longhorn Build 4074 (2004)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    2,300 views
  </span>



      <div class="description lines-3">
        <p>Once again Diana uses 3 different computers at once. On one laptop computer she has windows xp (2001) and on the other Windows Vista (2006/2007). She is testing Windows Longhorn Build 4074 on the main desktop computer whil...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-0"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      3 days ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>Once I can eventually get movie maker to render the video of mum trying longhorn I will make a video of "Can Ubuntu replace windows 7". Basically I have never used linux for any other purpose than letting my mum try basic tasks on various distros. The next vid will be me at the computer attempting to do tasks such as record the screen, edit videos, get it to work with various hardware ect... I'll do the vid with no prior research to show the process of searching for apps I need. Longest vid ever</p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-0"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      4 days ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>A new video of mum trying windows longhorn build 4074 would have been uploaded today however due to stupid windows live movie maker having problems rendering the 34 minutes of edited footage I am unable to upload it today. I tried rendering it 3 times and the furthest I managed to get it was 96% rendered. I'll try again tomorrow and hopefully it will get to 100%</p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-1"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      1 week ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="_hz3mwu8FjA" data-context-item-title="Mum tries out Cylon Linux 12.04 (2012)" data-context-item-views="3,629 views" data-context-item-time="27:30" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=_hz3mwu8FjA" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i4.ytimg.com/vi/_hz3mwu8FjA/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(1).jpg" alt="" width="185" data-group-key="thumb-group-1"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">27:30</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="_hz3mwu8FjA" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Cylon Linux 12.04 (2012)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=_hz3mwu8FjA" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Cylon Linux 12.04 (2012)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,629 views
  </span>



      <div class="description lines-3">
        <p>Wow... This operating system has so much eye candy installed by default that well... I don't know but it looks AWESOME! Plus, using this operating system allows you to save the world (watch video to get what I mean ;)). An...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-1"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      1 week ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>We have recorded our next operating system video however due to lack of time I am unable to upload it and edit it at this point. The video will be upload and viewable within the next 48 hours though ;)</p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-1"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 weeks ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="FP_IofsQyOA" data-context-item-title="Mum tries out Bodhi Linux 2.2.0 (2013)" data-context-item-views="3,352 views" data-context-item-time="23:12" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=FP_IofsQyOA" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/vi/FP_IofsQyOA/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(2).jpg" alt="" width="185" data-group-key="thumb-group-1"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">23:12</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="FP_IofsQyOA" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Bodhi Linux 2.2.0 (2013)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=FP_IofsQyOA" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Bodhi Linux 2.2.0 (2013)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,352 views
  </span>



      <div class="description lines-3">
        <p>Another Ubuntu Branch? Yep! But this one seems quite interesting. It uses the enlightenment 17 desktop window manager and is very minimalist... A little too much in my opinion. This is the first time Diana has actually nee...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-2"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 weeks ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="uWO3vif7hTw" data-context-item-title="How to make Linux Bootable off a USB Stick (Live USB)" data-context-item-views="3,446 views" data-context-item-time="10:00" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=uWO3vif7hTw" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/uWO3vif7hTw/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(3).jpg" alt="" width="185" data-group-key="thumb-group-2"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">10:00</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="uWO3vif7hTw" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="How to make Linux Bootable off a USB Stick (Live USB)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=uWO3vif7hTw" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          How to make Linux Bootable off a USB Stick (Live USB)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,446 views
  </span>



      <div class="description lines-3">
        <p>This is a complete noob friendly tutorial which will teach you how to make any linux operating system bootable from a usb. By creating a live usb you will be able to test what ever distro of linux you want on any computer ...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-2"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      3 weeks ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="Sldc18LxCFk" data-context-item-title="Mum Tries Out Windows Chicago Build 73g (1993)" data-context-item-views="3,157 views" data-context-item-time="27:37" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=Sldc18LxCFk" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i4.ytimg.com/vi/Sldc18LxCFk/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(4).jpg" alt="" width="185" data-group-key="thumb-group-2"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">27:37</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="Sldc18LxCFk" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum Tries Out Windows Chicago Build 73g (1993)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=Sldc18LxCFk" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum Tries Out Windows Chicago Build 73g (1993)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,157 views
  </span>



      <div class="description lines-3">
        <p>This time around Diana uses 3 different computers at once. On one laptop computer she has windows 3.11 (August 1993) and on the other Windows 95 (1995). She is testing Windows Chicago build 73 (an early version of windows ...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-3"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      3 weeks ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="GCWrfmSPOYA" data-context-item-title="Mum tries out Beos 5 Personal Edition (2000)" data-context-item-views="2,620 views" data-context-item-time="22:54" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=GCWrfmSPOYA" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i4.ytimg.com/vi/GCWrfmSPOYA/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(5).jpg" alt="" width="185" data-group-key="thumb-group-3"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">22:54</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="GCWrfmSPOYA" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Beos 5 Personal Edition (2000)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=GCWrfmSPOYA" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Beos 5 Personal Edition (2000)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    2,620 views
  </span>



      <div class="description lines-3">
        <p>Beos was certainly an interesting one... It booted with words of wisdom, let my mum play doom and performs a magic trick by making the home folder appear on the desktop when you attempt to throw away everything into trash....</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-3"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      3 weeks ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>Do not fear a new episode is almost here ;) Beos 5 PE (2000) will be up within the next 24 hours along with 1 more operating system video which has yet to be decided... I'm thinking either Slackware, longhorn, reactos or a more modern version of amigaOS...</p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-3"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      4 weeks ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="d6mDXKU29w0" data-context-item-title="Mum tries out AmigaOS Workbench 1.1 (1985)" data-context-item-views="3,252 views" data-context-item-time="26:04" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=d6mDXKU29w0" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i1.ytimg.com/vi/d6mDXKU29w0/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(6).jpg" alt="" width="185" data-group-key="thumb-group-3"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">26:04</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="d6mDXKU29w0" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out AmigaOS Workbench 1.1 (1985)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=d6mDXKU29w0" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out AmigaOS Workbench 1.1 (1985)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,252 views
  </span>



      <div class="description lines-3">
        <p>Moving away from Windows, Mac and Linux once again Diana visits another windows/mac competitor in 1985: Amiga. This noisy system runs of a floppy disc and comes with barely any applications out of the box. She prefers this...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-4"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      4 weeks ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="hGcwIuEoEsU" data-context-item-title="Mum tries out OS/2 Warp 4 (1996)" data-context-item-views="3,452 views" data-context-item-time="21:46" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=hGcwIuEoEsU" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i1.ytimg.com/vi/hGcwIuEoEsU/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(7).jpg" alt="" width="185" data-group-key="thumb-group-4"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">21:46</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="hGcwIuEoEsU" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out OS/2 Warp 4 (1996)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=hGcwIuEoEsU" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out OS/2 Warp 4 (1996)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,452 views
  </span>



      <div class="description lines-3">
        <p>Moving away from Windows, Mac and Linux Diana decides to try out OS/2 Warp 4 made in 1996 by IBM. This operating system has some cross compatibility with windows 3.1 and even dos yet at the same time doesn't have much cont...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-4"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      1 month ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>2 new videos will be up within 24 hours :) OS/2 warp 4 (1996) followed by AmigaOS workbench 1.1 (1985)</p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-4"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      1 month ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="-seAnnrihD4" data-context-item-title="Mum tries out NeXTSTEP 3.3 (1995)" data-context-item-views="3,494 views" data-context-item-time="20:03" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=-seAnnrihD4" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/-seAnnrihD4/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(8).jpg" alt="" width="185" data-group-key="thumb-group-4"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">20:03</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="-seAnnrihD4" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out NeXTSTEP 3.3 (1995)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=-seAnnrihD4" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out NeXTSTEP 3.3 (1995)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,494 views
  </span>



      <div class="description lines-3">
        <p>Finally she's trying out an operating system that isn't a Linux distro or something made by Microsoft or Apple. NeXTSTEP was developed by NEXT Computers until it was bought out by Apple in 1997. NeXTSTEP was used as a base...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-5"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      1 month ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="z56Uk7ND3wM" data-context-item-title="Mum tries out Debian 6.0.6 with Gnome 2 (2012) [Compiz tried at the end]" data-context-item-views="7,689 views" data-context-item-time="34:55" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=z56Uk7ND3wM" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/vi/z56Uk7ND3wM/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(9).jpg" alt="" width="185" data-group-key="thumb-group-5"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">34:55</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="z56Uk7ND3wM" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Debian 6.0.6 with Gnome 2 (2012) [Compiz tried at the end]" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=z56Uk7ND3wM" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Debian 6.0.6 with Gnome 2 (2012) [Compiz tried at the end]
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    7,689 views
  </span>



      <div class="description lines-3">
        <p>She's tried Ubuntu and many operating systems branching off it but what about Debian? Debian originally started in 1993 is what Ubuntu was originally based off. After trying Debian I get her to attempt installing compiz an...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-5"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      1 month ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="lAXBuFNWQ0A" data-context-item-title="Mum tries out Puppy Linux 4.3.1 (2009)" data-context-item-views="3,478 views" data-context-item-time="26:15" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=lAXBuFNWQ0A" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i1.ytimg.com/vi/lAXBuFNWQ0A/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(10).jpg" alt="" width="185" data-group-key="thumb-group-5"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">26:15</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="lAXBuFNWQ0A" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Puppy Linux 4.3.1 (2009)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=lAXBuFNWQ0A" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Puppy Linux 4.3.1 (2009)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,478 views
  </span>



      <div class="description lines-3">
        <p>For a change here's a slightly older version of a linux based operating system called "puppy linux". Diana has a little bit of difficulty finding programs in this OS due to the extremely long names given to each program. S...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-6"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      1 month ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="L7krm0_OqgE" data-context-item-title="Mum tries out Mac OS System 1.1 (1984)" data-context-item-views="7,876 views" data-context-item-time="20:33" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=L7krm0_OqgE" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i1.ytimg.com/vi/L7krm0_OqgE/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(11).jpg" alt="" width="185" data-group-key="thumb-group-6"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">20:33</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="L7krm0_OqgE" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Mac OS System 1.1 (1984)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=L7krm0_OqgE" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Mac OS System 1.1 (1984)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    7,876 views
  </span>



      <div class="description lines-3">
        <p>She's tried Mac from 2002 and 2012 but what about its origins? Yep, lets go back to 1984 and test out Mac OS System 1. Its got icons, a menu bar and programs in movable windows... Almost everything that makes up a modern d...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-6"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      1 month ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="0vjzmCi_Cko" data-context-item-title="Mum tries out Xubuntu 12.10 (2012)" data-context-item-views="7,095 views" data-context-item-time="24:29" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=0vjzmCi_Cko" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i1.ytimg.com/vi/0vjzmCi_Cko/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(12).jpg" alt="" width="185" data-group-key="thumb-group-6"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">24:29</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="0vjzmCi_Cko" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Xubuntu 12.10 (2012)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=0vjzmCi_Cko" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Xubuntu 12.10 (2012)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    7,095 views
  </span>



      <div class="description lines-3">
        <p>She's tried Ubuntu and even Kubuntu but what about Xubuntu? Let's just say she didn't mind this one but it wasn't exactly an exciting operating system for her. At least it was clean, uncluttered and easy to use. She wasn't...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-6"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      1 month ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="sxNo7h45luc" data-context-item-title="Mum tries out Windows 7 (2009)" data-context-item-views="7,876 views" data-context-item-time="28:46" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=sxNo7h45luc" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i4.ytimg.com/vi/sxNo7h45luc/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(13).jpg" alt="" width="185" data-group-key="thumb-group-6"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">28:46</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="sxNo7h45luc" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Windows 7 (2009)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=sxNo7h45luc" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Windows 7 (2009)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    7,876 views
  </span>



      <div class="description lines-3">
        <p>Diana only just lost her attachment to xp for windows vista yet she's already lost her attachment to windows vista with windows 7. This video differs greatly from our other videos however (don't worry we will go back to ou...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-7"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="wa51hGr6neo" data-context-item-title="Mum Tries Out Windows Vista (2007)" data-context-item-views="6,045 views" data-context-item-time="19:45" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=wa51hGr6neo" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i4.ytimg.com/vi/wa51hGr6neo/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(14).jpg" alt="" width="185" data-group-key="thumb-group-7"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">19:45</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="wa51hGr6neo" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum Tries Out Windows Vista (2007)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=wa51hGr6neo" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum Tries Out Windows Vista (2007)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    6,045 views
  </span>



      <div class="description lines-3">
        <p>Diana finally changes her mind about only liking windows xp. Her attachment to the windows 95 classic theme is completely shattered when she see's vista. In fact, she wants to update to vista now! WE FINALLY GOT HER OUT OF...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-7"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="uyYGWo_XweM" data-context-item-title="Mum tries out Arch Linux 2012.12.1 (2012)" data-context-item-views="16,901 views" data-context-item-time="23:42" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=uyYGWo_XweM" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/uyYGWo_XweM/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(15).jpg" alt="" width="185" data-group-key="thumb-group-7"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">23:42</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="uyYGWo_XweM" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Arch Linux 2012.12.1 (2012)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=uyYGWo_XweM" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Arch Linux 2012.12.1 (2012)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    16,901 views
  </span>



      <div class="description lines-3">
        <p>It's time to revisit Diana's worst nightmare: A text based operating system! In this episode Diana tries out her Arch Enemy - Arch Linux. Let's just say this operating system although heavily customizable is not for Diana....</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-8"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="jp0sSrWen90" data-context-item-title="Mum Tries Out Computer Games - New Channel" data-context-item-views="3,419 views" data-context-item-time="3:25" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=jp0sSrWen90" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/vi/jp0sSrWen90/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(16).jpg" alt="" width="185" data-group-key="thumb-group-8"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">3:25</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="jp0sSrWen90" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum Tries Out Computer Games - New Channel" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=jp0sSrWen90" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum Tries Out Computer Games - New Channel
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    3,419 views
  </span>



      <div class="description lines-3">
        <p>She's been trying many operating systems but what about games? Yep, the time has come to terrify her with a bunch of horrifying games. Oh and yes... shes never played computer games before so I guess you can call this "Gam...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-8"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="lXuFPb7kxYU" data-context-item-title="Mum tries Mac OSX 10.8 Mountain Lion (2012)" data-context-item-views="13,617 views" data-context-item-time="27:03" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=lXuFPb7kxYU" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i1.ytimg.com/vi/lXuFPb7kxYU/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(17).jpg" alt="" width="185" data-group-key="thumb-group-8"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">27:03</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="lXuFPb7kxYU" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries Mac OSX 10.8 Mountain Lion (2012)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=lXuFPb7kxYU" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries Mac OSX 10.8 Mountain Lion (2012)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    13,617 views
  </span>



      <div class="description lines-3">
        <p>Finally an operating system she really likes and is possibility her favorite! The latest version of Mac (10.8 Mountain Lion) has everything she wants. Mac doesn't change dramatically over time like Windows does (e.g. windo...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-9"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>If you are having difficulty running the blue scream prank please download this first: <a href="https://web.archive.org/web/20130304225950/http://www.microsoft.com/en-us/download/details.aspx?id=30653" target="_blank" title="http://www.microsoft.com/en-us/download/details.aspx?id=30653" rel="nofollow" dir="ltr" class="yt-uix-redirect-link">http://www.microsoft.com/en-us/download/details.aspx?id=3...</a></p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-9"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>Be sure to make videos of pranking your family and friends with the blue scream prank I made and put them up as a video response to my windows me video. Download the Blue Scream Prank Here: <a href="https://web.archive.org/web/20130304225950/http://www.speedyshare.com/8jXJ2/OSFirstTimerBlue-Scream.exe" target="_blank" title="http://www.speedyshare.com/8jXJ2/OSFirstTimerBlue-Scream.exe" rel="nofollow" dir="ltr" class="yt-uix-redirect-link">http://www.speedyshare.com/8jXJ2/OSFirstTimerBlue-Scream.exe</a></p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-9"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>A basic non-customizable version of the prank displayed in the windows me video has been added. It will do absolutely nothing for the first minute after you open it (stealth mode). After one minute a BSOD will show. Alt-Tabbing will only show other programs for a 10th of a second making this program inescapable once executed until it has run it's course.</p>
<p></p>
<p>Download the prank in the description of the windows me video or here:</p>
<p><a href="https://web.archive.org/web/20130304225950/http://www.speedyshare.com/8jXJ2/OSFirstTimerBlue-Scream.exe" target="_blank" title="http://www.speedyshare.com/8jXJ2/OSFirstTimerBlue-Scream.exe" rel="nofollow" dir="ltr" class="yt-uix-redirect-link">http://www.speedyshare.com/8jXJ2/OSFirstTimerBlue-Scream.exe</a></p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="UCRjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-9"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> posted


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                    <div class="feed-item-post ">
    <p>Since this channel consisting of my mum testing operating systems is getting so popular another idea poped into my head. My mum is hilarious when she watches scary movies and screams, jumps back and closes her eyes in fear. What if I got her to play some terrifying video games? </p>
<p></p>
<p>On a new channel I would record the games in 720p full speed with fraps and have my mum displayed playing the game in a little box at the bottom of the screen. The footage of my mum should be hilarious!</p>

  </div>


  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-10"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="YbxxWuNo9Qw" data-context-item-title="Mum tries out Windows ME (2000) [Prank Included]" data-context-item-views="8,534 views" data-context-item-time="17:35" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=YbxxWuNo9Qw" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/YbxxWuNo9Qw/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(18).jpg" alt="" width="185" data-group-key="thumb-group-10"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">17:35</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="YbxxWuNo9Qw" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Windows ME (2000) [Prank Included]" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=YbxxWuNo9Qw" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Windows ME (2000) [Prank Included]
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    8,534 views
  </span>



      <div class="description lines-3">
        <p>I may have given her an easy operating system to try out this time but a well planned prank literally makes Diana jump out of her chair screaming in fear. Windows ME may have been a little unstable and buggy here and there...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-10"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="vD0HxrCfIEo" data-context-item-title="Mum tries out Fedora 17 (2012)" data-context-item-views="13,575 views" data-context-item-time="20:01" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=vD0HxrCfIEo" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/vi/vD0HxrCfIEo/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(19).jpg" alt="" width="185" data-group-key="thumb-group-10"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">20:01</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="vD0HxrCfIEo" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Fedora 17 (2012)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=vD0HxrCfIEo" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Fedora 17 (2012)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    13,575 views
  </span>



      <div class="description lines-3">
        <p>Diana finally tries a Linux operating system that isn't based of Ubuntu. Fedora was based off the discontinued red hat Linux in 2004 however has heavily changed since then. It looks partially similar to Elementary OS howev...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-11"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
          <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
 uploaded a video


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                  

    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="sYTOavWs6Aw" data-context-item-title="Top 3 Worst And Best Operating System User Interfaces (1985-2012)" data-context-item-views="35,242 views" data-context-item-time="49:28" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=sYTOavWs6Aw" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i4.ytimg.com/vi/sYTOavWs6Aw/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(20).jpg" alt="" width="185" data-group-key="thumb-group-11"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">49:28</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="sYTOavWs6Aw" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Top 3 Worst And Best Operating System User Interfaces (1985-2012)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=sYTOavWs6Aw" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Top 3 Worst And Best Operating System User Interfaces (1985-2012)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    35,242 views
  </span>



      <div class="description lines-3">
        <p>This video involves an inexperienced computer user attempting to use a variety of operating systems for the first time and ranking them based on how much she likes/dislikes them. The user interface, default programs and us...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-11"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> replied to a <a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/watch?v=I-PTQVeCF9k&amp;lc=_CJ_20Q9f20i6YPIhoQvhgxO3Oa4VVFmctdMYDJ2YS4" class="yt-uix-sessionlink" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">comment</a> from <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/pixelr0?feature=plcp">Popescu Sorin</a>


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                        <div class="feed-item-post ">
    <p>Thankyou everyone, after 100 comments across my videos telling me how to say ubuntu I have learned how to pronounce it. Great job!</p>

  </div>



    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="I-PTQVeCF9k" data-context-item-title="Mum tries out Linux Mint Cinnamon Edition 14 (2012)" data-context-item-views="36,012 views" data-context-item-time="26:12" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=I-PTQVeCF9k" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/I-PTQVeCF9k/default.jpg" tabindex="-1" src="./index_files/default.jpg" alt="" width="106" data-group-key="thumb-group-11"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">26:12</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="I-PTQVeCF9k" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Linux Mint Cinnamon Edition 14 (2012)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=I-PTQVeCF9k" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Linux Mint Cinnamon Edition 14 (2012)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    36,012 views
  </span>



      <div class="description lines-2">
        <p>She's back again with another distro of her favorite kernel so far: Linux. Last time she tried Ubuntu (most popular version of Linux) but this time...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-12"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> replied to a <a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/watch?v=ULblsnv48WM&amp;lc=et-WMo4Wr3YRC8WapubZXe2luIpAeY1xtCZzRKzW1DM" class="yt-uix-sessionlink" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">comment</a> from <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/TheWindowsChannel?feature=plcp">Darren Persad</a>


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                        <div class="feed-item-post ">
    <p>Thanks for letting me know. I thought it looked very similar and when I asked my mum "what's the difference" in the video all she could come up with was "the name". Seriously... She's downloading Firefox just because she likes the name ;)</p>

  </div>



    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="ULblsnv48WM" data-context-item-title="Mum tries out Elementary OS Luna Beta 1 (2012)" data-context-item-views="11,974 views" data-context-item-time="22:07" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=ULblsnv48WM" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/ULblsnv48WM/default.jpg" tabindex="-1" src="./index_files/default(1).jpg" alt="" width="106" data-group-key="thumb-group-12"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">22:07</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="ULblsnv48WM" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Elementary OS Luna Beta 1 (2012)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=ULblsnv48WM" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Elementary OS Luna Beta 1 (2012)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    11,974 views
  </span>



      <div class="description lines-2">
        <p>Diana is at it again and this time trying an operating system while its still in beta testing. Overall this is the fastest operating system we have...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-12"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> replied to a <a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/watch?v=1ujmDrcKWo8&amp;lc=0rZ26Nvy1oAEde4JLZjbVNsXg5ns7Z3g4KezkEKcPTg" class="yt-uix-sessionlink" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">comment</a> from <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/FlippinWindows?feature=plcp">FlippinWindows</a>


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                        <div class="feed-item-post ">
    <p>Well actually she has windows xp but uses the windows 95-like classic theme on xp. I agree strongly about how she may like windows 7 though because zorin 6 (based on 7/vista) is her favorite operating system so far.</p>

  </div>



    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="1ujmDrcKWo8" data-context-item-title="A mother struggles to use windows 8 for the first time" data-context-item-views="49,075 views" data-context-item-time="15:38" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=1ujmDrcKWo8" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/1ujmDrcKWo8/default.jpg" tabindex="-1" src="./index_files/default(2).jpg" alt="" width="106" data-group-key="thumb-group-12"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">15:38</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="1ujmDrcKWo8" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="A mother struggles to use windows 8 for the first time" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=1ujmDrcKWo8" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          A mother struggles to use windows 8 for the first time
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    49,075 views
  </span>



      <div class="description lines-2">
        <p>Windows 8 has just been released but how will users react when they encounter it for the first time? Check out how a mother who is attached to wind...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-12"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
        <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span>
uploaded and 
replied to a <a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/watch?v=Jb-ydiNATDE&amp;lc=XzHoEspRWtWRVCIGOJqUpE7GuGNs-N5obfvSwIEID08" class="yt-uix-sessionlink" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">comment</a> from <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/thiebaude?feature=plcp">Edmond Thiebaud</a>
  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                        <div class="feed-item-post ">
    <p>You're in luck we have already made a video of kubuntu 12.10. Check it out on the uploaded videos page.</p>

  </div>



    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="Jb-ydiNATDE" data-context-item-title="Mum tries out Joli OS 1.2 (2011)" data-context-item-views="7,727 views" data-context-item-time="41:26" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=Jb-ydiNATDE" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/vi/Jb-ydiNATDE/mqdefault.jpg" tabindex="-1" src="./index_files/mqdefault(21).jpg" alt="" width="185" data-group-key="thumb-group-12"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">41:26</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="Jb-ydiNATDE" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Joli OS 1.2 (2011)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=Jb-ydiNATDE" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Joli OS 1.2 (2011)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    7,727 views
  </span>



      <div class="description lines-3">
        <p>Diana tries a cloud operating system for the first time in her life. She doesn't hate it but at the same time she doesn't love it. Being able to access the operating system from a web browser, usb, local pc installation, i...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-13"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> replied to a <a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/watch?v=u3wQztA0vq0&amp;lc=y0oWBznbetwWWPXj1gLxi0XmuEsjpIkPUpq2_m1jPns" class="yt-uix-sessionlink" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">comment</a> from <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/MrJohnGamesAndTuts?feature=plcp">MrJohnGamesAndTuts</a>


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                        <div class="feed-item-post ">
    <p>We will in the future (within next month) but don't expect it to run very fast. It may be quite laggy unfortunately...</p>

  </div>



    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="u3wQztA0vq0" data-context-item-title="Mum tries out MAC OSX 10.2 Jaguar (2002)" data-context-item-views="18,448 views" data-context-item-time="31:09" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=u3wQztA0vq0" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i2.ytimg.com/vi/u3wQztA0vq0/default.jpg" tabindex="-1" src="./index_files/default(3).jpg" alt="" width="106" data-group-key="thumb-group-13"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">31:09</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="u3wQztA0vq0" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out MAC OSX 10.2 Jaguar (2002)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=u3wQztA0vq0" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out MAC OSX 10.2 Jaguar (2002)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    18,448 views
  </span>



      <div class="description lines-2">
        <p>Please note that this is not the first mac operating system! I meant to say at the beginning of the video that it was the first mac operating syste...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-13"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> replied to a <a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/watch?v=PgGbZfR6Vec&amp;lc=TI5Ji2IHBBmWNdzF15i-98snKjJ6sQ0uKijpE9mq-zo" class="yt-uix-sessionlink" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">comment</a> from <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/GSVDeathAndGravity?feature=plcp">GSVDeathAndGravity</a>


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                        <div class="feed-item-post ">
    <p>I have a very good computer. Windows 7 runs lightning fast on it so no problems there.</p>

  </div>



    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="PgGbZfR6Vec" data-context-item-title="Mother&#39;s First Ubuntu 12.10 Experience" data-context-item-views="90,360 views" data-context-item-time="17:22" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=PgGbZfR6Vec" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i1.ytimg.com/vi/PgGbZfR6Vec/default.jpg" tabindex="-1" src="./index_files/default(4).jpg" alt="" width="106" data-group-key="thumb-group-13"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">17:22</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="PgGbZfR6Vec" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mother&#39;s First Ubuntu 12.10 Experience" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=PgGbZfR6Vec" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mother's First Ubuntu 12.10 Experience
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    90,360 views
  </span>



      <div class="description lines-2">
        <p>Are you a windows user? Thinking of updating to Windows 8? Well... think again! A mother who loves windows xp tried windows 8 and hated it (link be...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>


        <li>
    <div class="feed-item-container  last " data-channel-key="RjXNz9JNSuE8n-Oej4Rflw">
            <div class="feed-author-bubble-container">
<a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp" class="feed-author-bubble   ">  <span class="feed-item-author ">
      <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i3.ytimg.com/i/RjXNz9JNSuE8n-Oej4Rflw/1.jpg?v=50d8fafa" src="./index_files/1(1).jpg" alt="OsFirstTimer" width="28" data-group-key="thumb-group-14"><span class="vertical-align"></span></span></span></span>
  </span>
</a>  </div>


      <div class="feed-item-main">
        <div class="feed-item-header">
          
  <span class="feed-item-actions-line">
      
    <span class="feed-item-owner"><a title="OsFirstTimer" dir="ltr" href="https://web.archive.org/web/20130304225950/http://youtube.com/user/OsFirstTimer?feature=plcp">OsFirstTimer</a></span> replied to a <a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/watch?v=p3chib6dGO4&amp;lc=RaHowEwwznGxtpXW7OrJEjS_2hmUn16yeTe-sfGaNI8" class="yt-uix-sessionlink" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">comment</a> from <a href="https://web.archive.org/web/20130304225950/http://youtube.com/user/hctim2001?feature=plcp">hctim2001</a>


  </span>

              <span class="feed-item-time">
      2 months ago
    </span>

        </div>
        <div class="feed-item-main-content">
                        <div class="feed-item-post ">
    <p>She already tried ubuntu 12.10. Is 11.04 really that different from the latest ubuntu?</p>

  </div>



    <div class="feed-item-content-wrapper clearfix context-data-item" data-context-item-id="p3chib6dGO4" data-context-item-title="Mum tries out Microsoft BOB (1995)" data-context-item-views="14,107 views" data-context-item-time="22:27" data-context-item-type="video" data-context-item-user="OsFirstTimer" data-context-item-actionuser="OsFirstTimer">
    <div class="feed-item-thumb">
          <a class="ux-thumb-wrap  yt-uix-contextlink yt-uix-sessionlink  contains-addto" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=p3chib6dGO4" tabindex="-1">
      <span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="//web.archive.org/web/20130304225950/http://i1.ytimg.com/vi/p3chib6dGO4/default.jpg" tabindex="-1" src="./index_files/default(5).jpg" alt="" width="106" data-group-key="thumb-group-14"><span class="vertical-align"></span></span></span></span>
    <span class="video-time">22:27</span>
      


  <button onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="p3chib6dGO4" role="button"><span class="yt-uix-button-content">  <img src="./index_files/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>

  </a>

    </div>
    <div class="feed-item-content">
      
      <h4>
        <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2" title="Mum tries out Microsoft BOB (1995)" href="https://web.archive.org/web/20130304225950/http://youtube.com/watch?v=p3chib6dGO4" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=plcp">
          Mum tries out Microsoft BOB (1995)
        </a>
      </h4>
          <div class="metadata">
    




        <span class="view-count">
    14,107 views
  </span>



      <div class="description lines-2">
        <p>Windows 3.1 was a little hard to use for my mum just like it was for those who used it in 1992-1995. So it was time for microsoft to make a revolut...</p>

      </div>

  </div>

    </div>
  </div>



  

        </div>
      </div>
    </div>
  </li>



  </ul>

        <button class="yt-uix-load-more load-more-button yt-uix-button yt-uix-button-default" type="button" onclick=";return false;" data-uix-load-more-href="/channel_ajax?action_load_more_feed_items=1&amp;activity_view=1&amp;channel_id=UCRjXNz9JNSuE8n-Oej4Rflw&amp;paging=1355432775" data-uix-load-more-target-id="channel-feed" role="button"><span class="yt-uix-button-content">Load more </span></button>



      </div>

        </div>
      </div>
    </div>
  </div>

      </div>
      <div class="secondary-pane">
        
        
                <div class="user-profile channel-module yt-uix-c3-module-container ">
    <div class="module-view profile-view-module" data-owner-external-id="RjXNz9JNSuE8n-Oej4Rflw">
        <h2>
About OsFirstTimer
        </h2>
      <div class="section first">
        <div class="user-profile-item profile-description">
                <div class="yt-uix-expander yt-c3-expander yt-uix-expander-collapsed">
    <div class="yt-uix-expander-body">
      <p>What Computer operating system are you using on your computer right now? Windows 8? Mac OSX Lion? Ubuntu? Did you know there's thousands of operating systems that you could potentially be using! But are they better than what you already have? Lets find out and see what operating systems we can find out in the wild and throughout history.</p>
<p></p>
<p>About My Computer:</p>
<p>Ram: 12 Gigabytes</p>
<p>CPU: 2 Intel Xeon W3680's Running at 3.33ghertz (12 cores total)</p>
<p>Graphics Card: nVidia FX3800(1GB)</p>

        <button class="yt-uix-expander-head yt-uix-button yt-uix-button-link" type="button" onclick=";return false;" role="button"><span class="yt-uix-button-content">  less <img alt="" src="./index_files/pixel-vfl3z5WfW.gif">
 </span></button>

    </div>
    <div class="yt-uix-expander-collapsed-body">
      <p>What Computer operating system are you using on your computer right now? Windows 8? Mac OSX Lion? Ubuntu? Did you know there's thousands of operating systems that you could potentially be using! But are they better than what you already have? Lets...</p>

        <button class="yt-uix-expander-head yt-uix-button yt-uix-button-link" type="button" onclick=";return false;" role="button"><span class="yt-uix-button-content">  more <img alt="" src="./index_files/pixel-vfl3z5WfW.gif">
 </span></button>

    </div>
  </div>


        </div>
          <hr class="yt-horizontal-rule ">

      </div>
      <div class="section created-by-section">
        <div class="user-profile-item">
by <span class="yt-user-name " dir="ltr">OsFirstTimer</span>
        </div>
        <ul>
              <li class="user-profile-item ">
        <span class="item-name">Date Joined</span>
      <span class="value">Nov  3, 2012</span>
    </li>

        </ul>
      </div>
        
  <ul class="section">
        <li class="user-profile-item ">
        <span class="item-name">Country</span>
      <span class="value">Australia</span>
    </li>

  </ul>
    <hr class="yt-horizontal-rule ">


    </div>
  </div>


        
        
      </div>
    </div>
  </div>

      </div>


      
    </div>
  </div>



      </div>
    </div>
  </div>

    <div class="legacy-playlist-bar">
    



  <div id="playlist-bar" class="hid passive editable" data-video-url="/watch?v=&amp;playnext=1&amp;list=QL" data-list-id="" data-list-type="QL">
    <div id="playlist-bar-bar-container">
      <div id="playlist-bar-bar">
        <div class="yt-alert yt-alert-naked yt-alert-success hid " id="playlist-bar-notifications">  <div class="yt-alert-icon">
    <img src="./index_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-content" role="alert"></div></div>
<span id="playlist-bar-info"><span class="playlist-bar-active playlist-bar-group"><button id="playlist-bar-prev-button" onclick=";return false;" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-tooltip yt-uix-button-empty" type="button" title="Previous video" role="button"><span class="yt-uix-button-icon-wrapper">  <img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-prev" src="./index_files/pixel-vfl3z5WfW.gif" alt="Previous video" title="">
<span class="yt-uix-button-valign"></span></span></button><span class="playlist-bar-count"><span class="playing-index">0</span> / <span class="item-count">0</span></span><button id="playlist-bar-next-button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" type="button" onclick=";return false;" role="button"><span class="yt-uix-button-icon-wrapper">  <img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-next" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
<span class="yt-uix-button-valign"></span></span></button></span><span class="playlist-bar-active playlist-bar-group"><button id="playlist-bar-autoplay-button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" type="button" onclick=";return false;" data-button-toggle="true" role="button"><span class="yt-uix-button-icon-wrapper">  <img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-autoplay" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
<span class="yt-uix-button-valign"></span></span></button><button id="playlist-bar-shuffle-button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" type="button" onclick=";return false;" data-button-toggle="true" role="button"><span class="yt-uix-button-icon-wrapper">  <img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-shuffle" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
<span class="yt-uix-button-valign"></span></span></button></span><span class="playlist-bar-passive playlist-bar-group"><button id="playlist-bar-play-button" onclick=";return false;" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-tooltip yt-uix-button-empty" type="button" title="Play videos" role="button"><span class="yt-uix-button-icon-wrapper">  <img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-play" src="./index_files/pixel-vfl3z5WfW.gif" alt="Play videos" title="">
<span class="yt-uix-button-valign"></span></span></button><span class="playlist-bar-count"><span class="item-count">0</span></span></span><span id="playlist-bar-title" class="yt-uix-button-group"><span class="playlist-title">Unsaved Playlist</span></span></span>
        <a id="playlist-bar-lists-back" href="https://web.archive.org/web/20130304225950/youtube.com/user/osfirsttimer#">
Return to active list
        </a>

<span id="playlist-bar-controls"><span class="playlist-bar-group"><button id="playlist-bar-toggle-button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-text yt-uix-button-empty" type="button" onclick=";return false;" role="button"><span class="yt-uix-button-icon-wrapper">  <img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-toggle" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
<span class="yt-uix-button-valign"></span></span></button></span><span class="playlist-bar-group"><button class="yt-uix-tooltip yt-uix-tooltip-masked yt-uix-button-reverse flip yt-uix-button yt-uix-button-text" type="button" onclick=";return false;" data-button-has-sibling-menu="true" data-button-menu-id="playlist-bar-options-menu" role="button"><span class="yt-uix-button-content">Options </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button></span></span>      </div>
    </div>

<div id="playlist-bar-tray-container"><div id="playlist-bar-tray" class="yt-uix-slider yt-uix-slider-fluid"><button class="yt-uix-button playlist-bar-tray-button yt-uix-button-default yt-uix-slider-prev" onclick="return false;"><img class="yt-uix-slider-prev-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="Previous video"></button><button class="yt-uix-button playlist-bar-tray-button yt-uix-button-default yt-uix-slider-next" onclick="return false;"><img class="yt-uix-slider-next-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="Next video"></button><div class="yt-uix-slider-body"><div id="playlist-bar-tray-content" class="yt-uix-slider-slide"><ol class="video-list"></ol><ol id="playlist-bar-help"><li class="empty playlist-bar-help-message">Your queue is empty. Add videos to your queue using this button: <img src="./index_files/pixel-vfl3z5WfW.gif" class="addto-button-help"><br> or <a href="https://web.archive.org/web/20130304225950/https://accounts.google.com/ServiceLogin?uilel=3&amp;service=youtube&amp;passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26hl%3Den_US%26next%3D%252Fuser%252FOsFirstTimer%26nomobiletemp%3D1&amp;hl=en_US">sign in</a> to load a different list.</li></ol></div><div class="yt-uix-slider-shade-left"></div><div class="yt-uix-slider-shade-right"></div></div></div><div id="playlist-bar-save"></div><div id="playlist-bar-lists" class="dark-lolz"></div><div id="playlist-bar-loading"><img src="./index_files/pixel-vfl3z5WfW.gif" alt="Loading..."><span id="playlist-bar-loading-message">Loading...</span><span id="playlist-bar-saving-message" class="hid">Saving...</span></div><div id="playlist-bar-template" style="display: none;" data-video-thumb-url="//i4.ytimg.com/vi/__video_encrypted_id__/default.jpg"><!--<li class="playlist-bar-item yt-uix-slider-slide-unit __classes__" data-video-id="__video_encrypted_id__"><a href="__video_url__" title="__video_title__" class="yt-uix-sessionlink" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ&amp;feature=BFa"><span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img data-thumb="__video_thumb_url__" data-thumb-manual="true" src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="__video_title__" width="106" ><span class="vertical-align"></span></span></span></span><span class="screen"></span><span class="count"><strong>__list_position__</strong></span><span class="play"><img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"></span><span class="yt-uix-button yt-uix-button-default delete"><img class="yt-uix-button-icon-playlist-bar-delete" src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Delete"></span><span class="now-playing">Now playing</span><span dir="ltr" class="title"><span>__video_title__  <span class="uploader">by __video_display_name__</span>
</span></span><span class="dragger"></span></a></li>--></div><div id="playlist-bar-next-up-template" style="display: none;"><!--<div class="playlist-bar-next-thumb"><span class="video-thumb ux-thumb yt-thumb-default-74 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="//i4.ytimg.com/vi/__video_encrypted_id__/default.jpg" alt="Thumbnail" width="74" ><span class="vertical-align"></span></span></span></span></div>--></div></div>      <div id="playlist-bar-options-menu" class="hid">

    <div id="playlist-bar-extras-menu">
        <ul>
      <li><span class="yt-uix-button-menu-item" data-action="clear">
Clear all videos from this list
      </span></li>
  </ul>

    </div>

    <ul>
      <li><span class="yt-uix-button-menu-item" onclick="window.location.href=&#39;//web.archive.org/web/20130304225950/http://support.google.com/youtube/bin/answer.py?answer=146749&amp;hl=en-US&#39;">Learn more</span></li>
    </ul>
  </div>

  </div>

  </div>

  
    <div id="shared-addto-watch-later-login" class="hid">
      <a href="https://web.archive.org/web/20130304225950/https://accounts.google.com/ServiceLogin?uilel=3&amp;service=youtube&amp;passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26hl%3Den_US%26next%3D%252Fuser%252FOsFirstTimer%26nomobiletemp%3D1&amp;hl=en_US" class="sign-in-link">Sign in</a> to add this to a playlist

    </div>

  <div id="shared-addto-menu" style="display: none;" class="hid sign-in">
      <div class="addto-menu">
        <div id="addto-list-panel" class="menu-panel active-panel">
        <span class="yt-uix-button-menu-item yt-uix-tooltip sign-in" data-possible-tooltip="" data-tooltip-show-delay="750"><a href="https://web.archive.org/web/20130304225950/https://accounts.google.com/ServiceLogin?uilel=3&amp;service=youtube&amp;passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26hl%3Den_US%26next%3D%252Fuser%252FOsFirstTimer%26nomobiletemp%3D1&amp;hl=en_US" class="sign-in-link">Sign in</a> to add this to a playlist
</span>

  </div>
  <div id="addto-list-saved-panel" class="menu-panel">
    <div class="panel-content">
      <div class="yt-alert yt-alert-naked yt-alert-success  ">  <div class="yt-alert-icon">
    <img src="./index_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-content" role="alert">    <span class="yt-alert-vertical-trick"></span>
    <div class="yt-alert-message">
            
  <span class="message">Added to <span class="addto-title yt-uix-tooltip yt-uix-tooltip-reverse" title="More information about this playlist" data-tooltip-show-delay="750"></span></span>

    </div>
</div></div>
    </div>
  </div>
  <div id="addto-list-error-panel" class="menu-panel">
    <div class="panel-content">
      <img src="./index_files/pixel-vfl3z5WfW.gif">
      <span class="error-details"></span>
      <a class="show-menu-link">Back to list</a>
    </div>
  </div>

        <div id="addto-note-input-panel" class="menu-panel">
    <div class="panel-content">
      <div class="yt-alert yt-alert-naked yt-alert-success  ">  <div class="yt-alert-icon">
    <img src="./index_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-content" role="alert">    <span class="yt-alert-vertical-trick"></span>
    <div class="yt-alert-message">
              <span class="message">Added to playlist:</span>
  <span class="addto-title yt-uix-tooltip" title="More information about this playlist" data-tooltip-show-delay="750"></span>

    </div>
</div></div>
    </div>
<div class="yt-uix-char-counter" data-char-limit="150"><div class="addto-note-box addto-text-box"><textarea id="addto-note" class="addto-note yt-uix-char-counter-input" maxlength="150"></textarea><label for="addto-note" class="addto-note-label">Add an optional note</label></div><span class="yt-uix-char-counter-remaining">150</span></div>    <button disabled="disabled" class="playlist-save-note yt-uix-button yt-uix-button-default" type="button" onclick=";return false;" role="button"><span class="yt-uix-button-content">Add note </span></button>
  </div>
  <div id="addto-note-saving-panel" class="menu-panel">
    <div class="panel-content loading-content">
      <img src="./index_files/pixel-vfl3z5WfW.gif">
      <span>Saving note...</span>
    </div>
  </div>
  <div id="addto-note-saved-panel" class="menu-panel">
    <div class="panel-content">
      <img src="./index_files/pixel-vfl3z5WfW.gif">
      <span class="message">Note added to:</span>
    </div>
  </div>
  <div id="addto-note-error-panel" class="menu-panel">
    <div class="panel-content">
      <img src="./index_files/pixel-vfl3z5WfW.gif">
      <span class="message">Error adding note:</span>
      <ul class="error-details"></ul>
      <a class="add-note-link">Click to add a new note</a>
    </div>
  </div>
  <div class="close-note hid">
    <img src="./index_files/pixel-vfl3z5WfW.gif" class="close-button">
  </div>

  </div>

  </div>


        <div class="yt-dialog hid" id="feed-privacy-lb">
    <div class="yt-dialog-base">
      <span class="yt-dialog-align"></span>
      <div class="yt-dialog-fg">
        <div class="yt-dialog-fg-content">
          <div class="yt-dialog-loading">
              <div class="yt-dialog-waiting-content">
    <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Loading...</div>
  </div>

          </div>
          <div class="yt-dialog-content">
              <div id="feed-privacy-dialog">
  </div>

          </div>
          <div class="yt-dialog-working">
              <div id="yt-dialog-working-overlay">
  </div>
  <div id="yt-dialog-working-bubble">
    <div class="yt-dialog-waiting-content">
      <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Working...</div>
    </div>
  </div>

          </div>
        </div>
      </div>
    </div>
  </div>




    <!-- end page -->
  </div>

  <div id="footer-container">
    <div id="footer"><div id="footer-main"><div id="footer-logo"><a href="https://web.archive.org/web/20130304225950/http://youtube.com/" title="YouTube home"><img src="./index_files/pixel-vfl3z5WfW.gif" alt="YouTube home"></a></div>  <ul class="pickers yt-uix-button-group" data-button-toggle-group="optional">
      <li>
          
  <button id="yt-picker-language-button" class=" yt-uix-button yt-uix-button-default" type="button" onclick=";return false;" data-button-menu-id="arrow-display" data-picker-key="language" data-picker-position="footer" data-button-toggle="true" data-button-action="yt.www.picker.load" role="button"><span class="yt-uix-button-icon-wrapper">  <img class="yt-uix-button-icon yt-uix-button-icon-footer-language" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
<span class="yt-uix-button-valign"></span></span><span class="yt-uix-button-content">  <span class="yt-picker-button-label">
Language:
  </span>
  English
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>


      </li>
      <li>
          
  <button id="yt-picker-country-button" class=" yt-uix-button yt-uix-button-default" type="button" onclick=";return false;" data-button-menu-id="arrow-display" data-picker-key="country" data-picker-position="footer" data-button-toggle="true" data-button-action="yt.www.picker.load" role="button"><span class="yt-uix-button-content">  <span class="yt-picker-button-label">
Country:
  </span>
  Worldwide
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>


      </li>
      <li>
          
  <button id="yt-picker-safetymode-button" class=" yt-uix-button yt-uix-button-default" type="button" onclick=";return false;" data-button-menu-id="arrow-display" data-picker-key="safetymode" data-picker-position="footer" data-button-toggle="true" data-button-action="yt.www.picker.load" role="button"><span class="yt-uix-button-content">  <span class="yt-picker-button-label">
Safety:
  </span>
Off
 </span>  <img class="yt-uix-button-arrow" src="./index_files/pixel-vfl3z5WfW.gif" alt="" title="">
</button>


      </li>
  </ul>
<a href="https://web.archive.org/web/20130304225950/http://support.google.com/youtube/?hl=en-US&amp;p=" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-default" data-sessionlink="ei=Zic1UbL8OYqrhAH-4ID4AQ"><span class="yt-uix-button-content">  <img class="questionmark" src="./index_files/pixel-vfl3z5WfW.gif">
  <span>Help</span>
</span></a>      <div id="yt-picker-language-footer" class="yt-picker" style="display: none">
      <p class="yt-spinner">
      <img src="./index_files/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

  </div>

      <div id="yt-picker-country-footer" class="yt-picker" style="display: none">
      <p class="yt-spinner">
      <img src="./index_files/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

  </div>

      <div id="yt-picker-safetymode-footer" class="yt-picker" style="display: none">
      <p class="yt-spinner">
      <img src="./index_files/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

  </div>

</div><div id="footer-links"><ul id="footer-links-primary">  <li><a href="https://web.archive.org/web/20130304225950/http://youtube.com/t/about_youtube">About</a></li>
  <li><a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/yt/press/">Press &amp; Blogs</a></li>
  <li><a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/yt/copyright/">Copyright</a></li>
  <li><a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/yt/creators/">Creators &amp; Partners</a></li>
  <li><a href="https://web.archive.org/web/20130304225950/http://www.youtube.com/yt/advertise/">Advertising</a></li>
  <li><a href="https://web.archive.org/web/20130304225950/http://youtube.com/dev">Developers</a></li>
</ul><ul id="footer-links-secondary">  <li><a href="https://web.archive.org/web/20130304225950/http://youtube.com/t/terms">Terms</a></li>
  <li><a href="https://web.archive.org/web/20130304225950/http://www.google.com/intl/en/policies/privacy/">Privacy</a></li>
  <li><a href="https://web.archive.org/web/20130304225950/http://support.google.com/youtube/bin/request.py?contact_type=abuse&amp;hl=en-US">Safety</a></li>

  <li><a href="https://web.archive.org/web/20130304225950/http://www.google.com/tools/feedback/intl/en/error.html" onclick="return yt.www.feedback.start(yt.getConfig(&#39;FEEDBACK_LOCALE_LANGUAGE&#39;), yt.getConfig(&#39;FEEDBACK_LOCALE_EXTRAS&#39;), null, yt.getConfig(&#39;FEEDBACK_BUCKET_ID&#39;));" id="reportbug">Send feedback</a></li>
  <li><a href="https://web.archive.org/web/20130304225950/http://youtube.com/testtube">Try something new!</a></li>
  <li></li>
</ul></div>    
</div>
  </div>

    
  
    <script id="js-1292425619" src="./index_files/www-core-vflZy9WJV.js.download" data-loaded="true"></script>


  <script>
      yt.setConfig({
      'XSRF_TOKEN': '6zhv_R6yQjzBoYrc_vI7cmBUDU18MTM2MjUyNDM5MEAxMzYyNDM3OTkw',
      'XSRF_FIELD_NAME': 'session_token',

      'XSRF_REDIRECT_TOKEN': '57Sr61_C03kl-PzAiOBamzRmdcd8MTM2MjQ1MjM5MUAxMzYyNDM3OTkx'
  });

    yt.setConfig({
      'EVENT_ID': "Zic1UbL8OYqrhAH-4ID4AQ",
      'CURRENT_URL': "https:\/\/web.archive.org\/web\/20130304225950\/http:\/\/www.youtube.com\/user\/OsFirstTimer",
      'LOGGED_IN': false,
      'SESSION_INDEX': null,
      'SAFETY_MODE_PENDING': false,

      'WATCH_CONTEXT_CLIENTSIDE': true,

      'FEEDBACK_BUCKET_ID': "Channels 3",
      'FEEDBACK_LOCALE_LANGUAGE': "en",
      'FEEDBACK_LOCALE_EXTRAS': {"guide_subs": "NA", "is_partner": "", "is_branded": "", "logged_in": false, "experiments": "923414,906080,919359,910207,914079,916624,901441,920704,912806,902000,922403,922405,929901,913605,925006,906938,931202,908529,904830,920201,930101,906834,926403,913570,901451", "accept_language": null}    });
  </script>


      <script>
if (window.yt.timing) {yt.timing.tick("js_head");}    </script>

      
    <script id="js-1277760591" src="./index_files/www-channels3-vflwXZnzH.js.download" data-loaded="true"></script>



  <script>
    yt.setConfig('CHANNEL_ID', "UCRjXNz9JNSuE8n-Oej4Rflw");
    yt.setAjaxToken('channel_ajax', "");
      yt.setMsg({
    'UNBLOCK_USER': "Are you sure you want to unblock this user?",
    'BLOCK_USER': "Are you sure you want to block this user?"
  });
  yt.setConfig('BLOCK_USER_AJAX_XSRF', '');


    yt.setMsg({
      'GENERIC_EDITOR_ERROR': "An error occurred. Please try again later."
    });
    yt.pubsub.subscribe('init', yt.www.channels.c3.channel.init);

  </script>
    <script>
      yt.setAjaxToken('subscription_ajax', "");

  </script>




    <script>
    yt.pubsub.subscribe('init', yt.www.channels.c3.channel.initFeedsTab);
  </script>



  

        <script>
yt.setConfig({'TIMING_ACTION': "channels3",'TIMING_INFO': {"mod_lt": "warm", "e": "923414,906080,919359,910207,914079,916624,901441,920704,912806,902000,922403,922405,929901,913605,925006,906938,931202,908529,904830,920201,930101,906834,926403,913570,901451", "mod_li": 0, "mod_spf": 0}});    </script>





  <script>yt.setConfig('THUMB_DELAY_LOAD_BUFFER', 0);</script>

  <script>
    


  yt.setMsg({
    'LIST_CLEARED': "List cleared",
    'PLAYLIST_VIDEO_DELETED': "Video deleted.",
    'ERROR_OCCURRED': "Sorry, an error occurred.",
    'NEXT_VIDEO_TOOLTIP': "Next video:\u003cbr\u003e \u0026#8220;${next_video_title}\u0026#8221;",
    'NEXT_VIDEO_NOTHUMB_TOOLTIP': "Next video",
    'SHOW_PLAYLIST_TOOLTIP': "Show playlist",
    'HIDE_PLAYLIST_TOOLTIP': "Hide playlist",
    'AUTOPLAY_ON_TOOLTIP': "Turn autoplay off",
    'AUTOPLAY_OFF_TOOLTIP': "Turn autoplay on",
    'SHUFFLE_ON_TOOLTIP': "Turn shuffle off",
    'SHUFFLE_OFF_TOOLTIP': "Turn shuffle on",
    'PLAYLIST_BAR_PLAYLIST_SAVED': "Playlist saved!",
    'PLAYLIST_BAR_ADDED_TO_FAVORITES': "Added to favorites",
    'PLAYLIST_BAR_ADDED_TO_PLAYLIST': "Added to playlist",
    'PLAYLIST_BAR_ADDED_TO_QUEUE': "Added to queue",
    'AUTOPLAY_WARNING1': "Next video starts in 1 second...",
    'AUTOPLAY_WARNING2': "Next video starts in 2 seconds...",
    'AUTOPLAY_WARNING3': "Next video starts in 3 seconds...",
    'AUTOPLAY_WARNING4': "Next video starts in 4 seconds...",
    'AUTOPLAY_WARNING5': "Next video starts in 5 seconds...",
    'UNDO_LINK': "Undo"  });


  yt.setConfig({
    'DRAGDROP_BINARY_URL': "\/\/web.archive.org\/web\/20130304225950\/http:\/\/s.ytimg.com\/yts\/jsbin\/www-dragdrop-vflXzT8zZ.js",
    'PLAYLIST_BAR_PLAYING_INDEX': -1  });

    yt.setAjaxToken('addto_ajax_logged_out', "K4EkdWZdEWSq4CO6mbbzSPOcmkp8MTM2MjUyNDM5MUAxMzYyNDM3OTkx");

    yt.pubsub.subscribe('init', yt.www.lists.init);









    yt.setConfig({'SBOX_JS_URL': "\/\/web.archive.org\/web\/20130304225950\/http:\/\/s.ytimg.com\/yts\/jsbin\/www-searchbox-vflzZmr_k.js",'SBOX_SETTINGS': {"SHOW_CHIP": false, "SESSION_INDEX": null, "PSUGGEST_TOKEN": null, "EXPERIMENT_ID": -1, "USE_HTTPS": false, "CLOSE_ICON_URL": "\/\/web.archive.org\/web\/20130304225950\/http:\/\/s.ytimg.com\/yts\/img\/icons\/close-vflrEJzIW.png", "REQUEST_LANGUAGE": "en", "HAS_ON_SCREEN_KEYBOARD": false, "CHIP_PARAMETERS": {}, "REQUEST_DOMAIN": "us"},'SBOX_LABELS': {"SUGGESTION_DISMISSED_LABEL": "Suggestion dismissed", "SUGGESTION_DISMISS_LABEL": "Dismiss"}});


      yt.setAjaxToken('channel_details_ajax', "aOBMIGsGqfFbNbJujEYDnS_g5qp8MTM2MjUyNDM5MUAxMzYyNDM3OTkx");



      yt.setConfig('FEED_PRIVACY_CSS_URL', "\/\/web.archive.org\/web\/20130304225950\/http:\/\/s.ytimg.com\/yts\/cssbin\/www-feedprivacydialog-vfl_TNLwP.css");
  yt.setAjaxToken('feed_privacy_ajax', "");
    yt.pubsub.subscribe('init', yt.www.account.FeedPrivacyDialog.init);


    yt.setMsg({
      'ADDTO_WATCH_LATER_ADDED': "Added",
      'ADDTO_WATCH_LATER_ERROR': "Error"
    });

  </script>

  

      <script>
if (window.yt.timing) {yt.timing.tick("js_foot");}    </script>



  <div id="debug">
    
  </div>



<!--
     FILE ARCHIVED ON 22:59:50 Mar 04, 2013 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 02:18:13 Mar 27, 2022.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
-->
<!--
playback timings (ms):
  captures_list: 390.688
  exclusion.robots: 197.954
  exclusion.robots.policy: 197.944
  xauthn.identify: 146.901
  xauthn.chkprivs: 50.774
  RedisCDXSource: 4.127
  esindex: 0.022
  LoadShardBlock: 162.265 (3)
  PetaboxLoader3.datanode: 173.876 (4)
  CDXLines.iter: 21.002 (3)
  load_resource: 73.405
  PetaboxLoader3.resolve: 41.128
--><iframe class="gstl_0 gssb_k" style="display: none; top: 43px; left: 0px; height: 0px;" allow="autoplay &#39;self&#39;; fullscreen &#39;self&#39;" src="./index_files/saved_resource.html"></iframe><table cellspacing="0" cellpadding="0" class="gstl_0 gssb_c" style="width: 489px; display: none; top: 43px; position: absolute; left: 225px;"><tbody><tr><td class="gssb_f"></td><td class="gssb_e" style="width: 100%;"></td></tr></tbody></table></body></html>