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
        header("Location: /?no");

    $_user = $__user_h->fetch_user_username($_GET['n']);

	$stmt = $__db->prepare("SELECT * FROM bans WHERE username = :username ORDER BY id DESC");
	$stmt->bindParam(":username", $_user['username']);
	$stmt->execute();

	while($ban = $stmt->fetch(PDO::FETCH_ASSOC)) { 
		header("Location: /?error=This user has been terminated for violating Betatube's Community Guidelines.");
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

    $_user['subscribed'] = $__user_h->if_subscribed(@$_SESSION['siteusername'], $_user['username']);
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
    $_user['2009_bgcolor'] = substr($_user['2009_bgcolor'], 0, 7);

    $_user['genre'] = strtolower($_user['genre']);
    $_user['subscribed'] = $__user_h->if_subscribed(@$_SESSION['siteusername'], $_user['username']);

    if(!check_valid_colorhex($_user['primary_color']) && strlen($_user['primary_color']) != 6) { $_user['primary_color'] = ""; }
    if(!check_valid_colorhex($_user['secondary_color']) && strlen($_user['secondary_color']) != 6) { $_user['secondary_color'] = ""; }
    if(!check_valid_colorhex($_user['third_color']) && strlen($_user['third_color']) != 6) { $_user['third_color'] = ""; }
    if(!check_valid_colorhex($_user['text_color']) && strlen($_user['text_color']) != 6) { $_user['text_color'] = ""; }
    if(!check_valid_colorhex($_user['primary_color_text']) && strlen($_user['primary_color_text']) != 6) { $_user['primary_color_text'] = ""; }
    if(!check_valid_colorhex($_user['2009_bgcolor']) && strlen($_user['2009_bgcolor']) != 6) { $_user['2009_bgcolor'] = ""; }

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
?>
<html lang="en">
	<head>
		<script>
			var yt = yt || {};yt.timing = yt.timing || {};yt.timing.data_ = yt.timing.data_ || {};yt.timing.tick = function(label, opt_time) {var tick = yt.timing.data_['tick'] || {};tick[label] = opt_time || new Date().getTime();yt.timing.data_['tick'] = tick;};yt.timing.info = function(label, value) {var info = yt.timing.data_['info'] || {};info[label] = value;yt.timing.data_['info'] = info;};yt.timing.reset = function() {yt.timing.data_ = {};};if (document.webkitVisibilityState == 'prerender') {yt.timing.info('prerender', 1);document.addEventListener('webkitvisibilitychange', function() {yt.timing.tick('start');}, false);}yt.timing.tick('start');try {var externalPt = (window.gtbExternal && window.gtbExternal.pageT() ||window.external && window.external.pageT);if (externalPt) {yt.timing.info('pt', externalPt);}} catch(e) {}if (window.chrome && window.chrome.csi) {yt.timing.info('pt', Math.floor(window.chrome.csi().pageT));}    
		</script>
		<title><?php echo $__server->page_embeds->page_title; ?></title>
		<link id="css-2955892050" rel="stylesheet" href="/yts/cssbin/www-core-vflEJosKh.css">
		<link id="css-151587203" rel="stylesheet" href="/yts/cssbin/www-home-vfl_Eri60.css">
		<script>
			if (window.yt.timing) {yt.timing.tick("ct");}    
		</script>
	</head>
	<body dir="ltr" class="  ltr      site-left-aligned  exp-new-site-width  exp-watch7-comment-ui  hitchhiker-enabled      guide-enabled    guide-expanded  ">
		<div id="body-container">
			<form name="logoutForm" method="POST" action="/logout"><input type="hidden" name="action_logout" value="1"></form>
			<?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_header.php"); ?>
			<div id="alerts"></div>
			<div id="header">
				<div id="masthead_child_div"></div>
				<div id="ad_creative_expand_btn_1" class="masthead-ad-control masthead-ad-control-lohp open hid">
					<a onclick="masthead.expand_ad(); return false;">
					<span>Show ad</span>
					<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
					</a>
				</div>
			</div>
			<div id="page-container">
				<div id="page" class="  home     branded-page-v2-detached-top  clearfix">
					<div id="guide"><?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_guide.php"); ?></div>
					<div id="content" class="">
<html dir="ltr" xmlns:og="http://opengraphprotocol.org/schema/" lang="en">
	<!-- machid: sNW5tN3Z2SWdXaDRqNGxuNEF5MFBxM1BxWXd0VGo0Rkg3UXNTTTNCUGRDWjR0WGpHR3R1YzFR -->
	<head>
	<script>
         var yt = yt || {};yt.timing = yt.timing || {};yt.timing.tick = function(label, opt_time) {var timer = yt.timing['timer'] || {};if(opt_time) {timer[label] = opt_time;}else {timer[label] = new Date().getTime();}yt.timing['timer'] = timer;};yt.timing.info = function(label, value) {var info_args = yt.timing['info_args'] || {};info_args[label] = value;yt.timing['info_args'] = info_args;};yt.timing.info('e', "907722,906062,910102,927104,922401,920704,912806,927201,913546,913556,925109,919003,920201,912706,900816");yt.timing.wff = true;yt.timing.info('an', "");if (document.webkitVisibilityState == 'prerender') {document.addEventListener('webkitvisibilitychange', function() {yt.timing.tick('start');}, false);}yt.timing.tick('start');yt.timing.info('li','0');try {yt.timing['srt'] = window.gtbExternal && window.gtbExternal.pageT() ||window.external && window.external.pageT;} catch(e) {}if (window.chrome && window.chrome.csi) {yt.timing['srt'] = Math.floor(window.chrome.csi().pageT);}if (window.msPerformance && window.msPerformance.timing) {yt.timing['srt'] = window.msPerformance.timing.responseStart - window.msPerformance.timing.navigationStart;}    
      </script>
      <script>var yt = yt || {};yt.preload = {};yt.preload.counter_ = 0;yt.preload.start = function(src) {var img = new Image();var counter = ++yt.preload.counter_;yt.preload[counter] = img;img.onload = img.onerror = function () {delete yt.preload[counter];};img.src = src;img = null;};yt.preload.start("\/\/o-o---preferred---sn-o097zne7---v18---lscache1.c.youtube.com\/crossdomain.xml");yt.preload.start("\/\/o-o---preferred---sn-o097zne7---v18---lscache1.c.youtube.com\/generate_204?ip=207.241.237.166\u0026upn=sWh0pzcodo0\u0026sparams=algorithm%2Cburst%2Ccp%2Cfactor%2Cgcr%2Cid%2Cip%2Cipbits%2Citag%2Csource%2Cupn%2Cexpire\u0026fexp=907722%2C906062%2C910102%2C927104%2C922401%2C920704%2C912806%2C927201%2C913546%2C913556%2C925109%2C919003%2C920201%2C912706%2C900816\u0026mt=1349916311\u0026key=yt1\u0026algorithm=throttle-factor\u0026burst=40\u0026ipbits=8\u0026itag=34\u0026sver=3\u0026signature=C397DCB00566E0FBB1551675B6108A4158C34557.CB3777882F05D65158C043C258FF8D4EBA90FA50\u0026mv=m\u0026source=youtube\u0026ms=au\u0026gcr=us\u0026expire=1349937946\u0026factor=1.25\u0026cp=U0hTTllOVV9JUENOM19RSFlKOmVLUWdkTXRmS0dX\u0026id=a078394896111c0d");</script>
        <title><?php echo $__server->page_embeds->page_title; ?></title>
		<meta property="og:title" content="<?php echo $__server->page_embeds->page_title; ?>" />
		<meta property="og:url" content="<?php echo $__server->page_embeds->page_url; ?>" />
		<meta property="og:description" content="<?php echo $__server->page_embeds->page_description; ?>" />
		<meta property="og:image" content="<?php echo $__server->page_embeds->page_image; ?>" />
		<script>
			var yt = yt || {};yt.timing = yt.timing || {};yt.timing.tick = function(label, opt_time) {var timer = yt.timing['timer'] || {};if(opt_time) {timer[label] = opt_time;}else {timer[label] = new Date().getTime();}yt.timing['timer'] = timer;};yt.timing.info = function(label, value) {var info_args = yt.timing['info_args'] || {};info_args[label] = value;yt.timing['info_args'] = info_args;};yt.timing.info('e', "904821,919006,922401,920704,912806,913419,913546,913556,919349,919351,925109,919003,920201,912706");if (document.webkitVisibilityState == 'prerender') {document.addEventListener('webkitvisibilitychange', function() {yt.timing.tick('start');}, false);}yt.timing.tick('start');yt.timing.info('li','0');try {yt.timing['srt'] = window.gtbExternal && window.gtbExternal.pageT() ||window.external && window.external.pageT;} catch(e) {}if (window.chrome && window.chrome.csi) {yt.timing['srt'] = Math.floor(window.chrome.csi().pageT);}if (window.msPerformance && window.msPerformance.timing) {yt.timing['srt'] = window.msPerformance.timing.responseStart - window.msPerformance.timing.navigationStart;}    
		</script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="/yt/jsbin/plupload.full.min.js"></script>
		<script id="scriptload-1728513939" src="//s.ytimg.com/yt/jsbin/html5player-vfl1S0-AB.js" data-loaded="true"></script>
		   <script>
         var gYouTubePlayerReady = false;
         if (!window['onYouTubePlayerReady']) {
           window['onYouTubePlayerReady'] = function() {
             gYouTubePlayerReady = true;
           };
         }
      </script>
      <script>
         if (window.yt.timing) {yt.timing.tick("ct");}    
      </script>
	</head>
	<body id="" class="date-20120614 en_US ltr   ytg-old-clearfix " dir="ltr">
		<form name="logoutForm" method="POST" action="/logout">
			<input type="hidden" name="action_logout" value="1">
		</form>
		<!-- begin page -->
        <div class="branded-page-v2-secondary-col">
          

    <?php $_user['featured_channels'] = explode(",", $_user['featured_channels']); ?>
    <?php if(count($_user['featured_channels']) != 0) { ?>
        <div class="branded-page-related-channels branded-page-gutter-padding">
          <h2 class="branded-page-related-channels-title" dir="ltr">
        <a>Featured channels</a>
    </h2>

        <ul class="branded-page-related-channels-list">
			<?php 
                foreach($_user['featured_channels'] as $user) {
                    if($__user_h->user_exists($user)) {
            ?>
	  <?php $_user2 = $__user_h->fetch_user_username($user); ?>
        <li class="branded-page-related-channels-item clearfix" data-external-id="UCSAUGyc_xA8uYzaIVG6MESQ">
    <a href="/user/<?php echo htmlspecialchars($_user2['username']); ?>" class="spf-link yt-uix-sessionlink" data-sessionlink="ved=CDgQwBs&amp;ei=fWDQUaHNBtCYhgGJiYHIBg&amp;feature=rc-f">
          <span class="video-thumb branded-page-related-channels-thumb yt-thumb yt-thumb-32">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img data-thumb="/dynamic/pfp/<?php echo htmlspecialchars($_user['pfp']); ?>" alt="Thumbnail" src="/dynamic/pfp/<?php echo htmlspecialchars($_user2['pfp']); ?>" width="32" data-group-key="thumb-group-1">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>

    </a>
    <div class="branded-page-related-channels-content">
        <h3>
    <a class="spf-link yt-uix-tooltip yt-uix-sessionlink" href="/user/<?php echo htmlspecialchars($_user2['username']); ?>" dir="ltr" data-sessionlink="ved=CDkQvxs&amp;ei=fWDQUaHNBtCYhgGJiYHIBg&amp;feature=rc-f">
      <span class="qualified-channel-title ellipsized" title="<?php if($_user2['title'])	{	?><?php echo htmlspecialchars($_user2['title']); ?><?php } else {	?><?php echo htmlspecialchars($_user2['username']); ?><?php	}	?>"><span class="qualified-channel-title-wrapper ">  <span class="qualified-channel-title-text">
      <?php if($_user2['title'])	{	?><?php echo htmlspecialchars($_user2['title']); ?><?php } else {	?><?php echo htmlspecialchars($_user2['username']); ?><?php	}	?>
  </span>
</span></span>
    </a>
  </h3>

      <span class=" yt-uix-button-subscription-container"><?php echo $__user_h->fetch_subs_count($user); ?> subscribers <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
    </div>
  </li>
					<?php } ?>
					<?php } ?>
					<?php } ?>
  </ul>
    <p class="branded-page-related-channels-see-more">
      <a href="/web/20130630164445/http://youtube.com/user/smosh/about">
      </a>
    </p>

    </div>
        </div>
<div class="branded-page-v2-primary-col">
            <div class="branded-page-v2-primary-col-header-container">
      

  <div id="context-source-container" data-context-source="user" data-context-image="//i2.ytimg.com/i/Y30JRSgfhYXA6i6xX1erWg/1.jpg?v=519198b1" style="display:none;"></div>
  <style>
			#c4-header-bg-container {
				background-color: <?php echo $_user['primary_color'];  ?>;
				background-image: url(/dynamic/banners/<?php echo $_user['2012_bg']; ?>);
				background-repeat: repeat;
				background-position: center;
				<?php
					switch($_user['2012_bgoption']) {
						case "stretch":
						echo "background-size: cover;";
						break;
						case "solid":
						echo "";
						break;
						case "norepeat":
						echo "background-repeat: no-repeat !important;";
						break;
						case "repeatxy":
						echo "background-repeat: repeat;";
						break;
						case "repeaty":
						echo "background-repeat: repeat-y;";
						break;
						case "repeatx":
						echo "background-repeat: repeat-x;";
						break;
					}
				?>
			}
	</style>
    <div class="branded-page-v2-header channel-header">
    <div id="gh-banner">
        <div id="c4-header-bg-container" class=" has-custom-banner">


          <a class="channel-header-profile-image-container spf-link" href="/web/20130615211109/http://youtube.com/user/user">
      <img class="channel-header-profile-image" src="/dynamic/pfp/<?php echo htmlspecialchars($_user['pfp']); ?>" title="user" alt="user">
    </a>


  </div>

    </div>
      <div class="">
    <div class="primary-header-contents" id="c4-primary-header-contents">
      <div class="primary-header-actions clearfix">
<style>
.subscribe-label,.subscribed-label,.unsubscribe-label,.unavailable-label,.yt-uix-button-subscribed-branded.hover-enabled:hover .subscribed-label,.yt-uix-button-subscribed-unbranded.hover-enabled:hover .subscribed-label {
    display: block;
    line-height: 0;
    visibility: hidden;
    overflow: hidden;
    white-space: nowrap;
    word-wrap: normal;
    *zoom:1;-o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    -moz-box-sizing: border-box;
    -ms-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}
.yt-uix-button-subscribe-branded .subscribe-label,.yt-uix-button-subscribe-branded .unavailable-label,.yt-uix-button-subscribed-branded .subscribed-label,.yt-uix-button-subscribed-branded.hover-enabled:hover .unsubscribe-label {
    line-height: 22px;
    visibility: visible
}
.live-badge {
    border: 1px solid #b91f1f;
    padding: 0 4px;
    color: #b91f1f;
    font-size: 10px;
    background-color: #fff;
    line-height: 1.5em;
    display: inline-block;
    *display: inline;
    *zoom:1}

    .yt-subscription-button .subscribe-label,
    .yt-subscription-button .subscribed-label,
    .yt-subscription-button .unsubscribe-label {
    display:block
    }
    .yt-subscription-button .subscribed-label,
    .yt-subscription-button .unsubscribe-label,
    .yt-subscription-button.subscribed .subscribe-label,
    .yt-subscription-button.subscribed .unsubscribe-label,
    .yt-subscription-button.subscribed.hover-enabled:hover .subscribed-label,
    .yt-subscription-button.subscribed.hover-enabled[disabled]:hover .unsubscribe-label {
    line-height:0;
    visibility:hidden
    }
    .yt-subscription-button.subscribed .subscribed-label,
    .yt-subscription-button.subscribed.hover-enabled:hover .unsubscribe-label,
    .yt-subscription-button.subscribed.hover-enabled[disabled]:hover .subscribed-label {
    line-height:normal;
    visibility:visible
    }
    .yt-subscription-button-disabled-mask-container {
    position:relative;
    display:inline-block
    }
    .yt-subscription-button-disabled-mask {
    display:none;
    position:absolute;
    top:0;
    right:0;
    bottom:0;
    left:0
    }
    .yt-subscription-button-disabled-mask-container .yt-subscription-button-disabled-mask {
    display:block
    }
	body .yt-uix-button-icon-subscribe {
		margin-right: 0
	}
	@media screen and (-webkit-min-device-pixel-ratio: 0) {
		.yt-uix-button-subscribed-branded .yt-uix-button-icon-subscribe,.yt-uix-button-subscribed-unbranded .yt-uix-button-icon-subscribe,.yt-uix-button-subscribe-unbranded.ypc-enabled .yt-uix-button-icon-subscribe {
			margin-top:-2px
		}
	}
	.yt-uix-button-subscribe-branded.ypc-enabled .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -209px -212px;
		background-size: auto;
		width: 16px;
		height: 12px
	}
	.yt-uix-button-subscribe-branded.ypc-unavailable .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -126px -21px;
		background-size: auto;
		width: 16px;
		height: 12px
	}
	.yt-uix-button-subscribe-unbranded.ypc-enabled .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -53px -62px;
		background-size: auto;
		width: 13px;
		height: 13px
	}
	.yt-uix-button-subscribe-unbranded.ypc-enabled:hover .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -124px -83px;
		background-size: auto;
		width: 13px;
		height: 13px
	}
	.yt-uix-button-subscribe-unbranded.ypc-enabled:hover .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -124px -83px;
		background-size: auto;
		width: 13px;
		height: 13px
	}
	.yt-uix-button-subscribed-unbranded .yt-uix-button-icon-subscribe,.yt-uix-button-subscribed-branded .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -208px -132px;
		background-size: auto;
		width: 11px;
		height: 8px
	}
	.yt-uix-button-subscribed-branded.external .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -104px -62px;
		background-size: auto;
		width: 16px;
		height: 12px
	}
	.yt-uix-button-subscribed-branded.hover-enabled:hover .yt-uix-button-icon-subscribe,.yt-uix-button-subscribed-unbranded.hover-enabled:hover .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -173px -104px;
		background-size: auto;
		width: 11px;
		height: 10px
	}
	body .yt-uix-button-icon-subscribe {
		margin-right: 0
	}
	@media screen and (-webkit-min-device-pixel-ratio: 0) {
		.yt-uix-button-subscribed-branded .yt-uix-button-icon-subscribe,.yt-uix-button-subscribed-unbranded .yt-uix-button-icon-subscribe,.yt-uix-button-subscribe-unbranded.ypc-enabled .yt-uix-button-icon-subscribe {
			margin-top:-2px
		}
	}
	.yt-uix-button-subscribe-branded .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -295px -94px;
		background-size: auto;
		width: 16px;
		height: 12px
	}
	.yt-uix-button-subscribe-branded.ypc-enabled .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -209px -212px;
		background-size: auto;
		width: 16px;
		height: 12px
	}
	.yt-uix-button-subscribe-branded.ypc-unavailable .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -126px -21px;
		background-size: auto;
		width: 16px;
		height: 12px
	}
	.yt-uix-button-subscribe-unbranded.ypc-enabled .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -53px -62px;
		background-size: auto;
		width: 13px;
		height: 13px
	}
	.yt-uix-button-subscribe-unbranded.ypc-enabled:hover .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -124px -83px;
		background-size: auto;
		width: 13px;
		height: 13px
	}
	.yt-uix-button-subscribed-unbranded .yt-uix-button-icon-subscribe,.yt-uix-button-subscribed-branded .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -208px -132px;
		background-size: auto;
		width: 11px;
		height: 8px
	}
	.yt-uix-button-subscribed-branded.external .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -104px -62px;
		background-size: auto;
		width: 16px;
		height: 12px
	}
	.yt-uix-button-subscribed-branded.hover-enabled:hover .yt-uix-button-icon-subscribe,.yt-uix-button-subscribed-unbranded.hover-enabled:hover .yt-uix-button-icon-subscribe {
		background: no-repeat url(/yts/imgbin/www-hitchhiker-vflFMVkjB1.png) -173px -104px;
		background-size: auto;
		width: 11px;
		height: 10px
	}
</style>

<?php if (@$_SESSION['siteusername'] == $_user['username']) { ?>
                            <button onclick=";window.location.href=this.getAttribute('href');return false;" disabled="True" aria-role="button" aria-busy="false" type="button" aria-live="polite" data-tooltip-text="No need to subscribe to yourself!" class="start yt-uix-tooltip yt-uix-button yt-uix-button-subscribe-branded" title="No need to subscribe to yourself!" role="button"> <span class="yt-uix-button-icon-wrapper">
                                <img class="yt-uix-button-icon yt-uix-button-icon-subscribe" src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
                                <span class="yt-uix-button-valign"></span>
                              </span>
                              <span class="yt-uix-button-content">
                                <span class="subscribe-label" aria-label="Subscribe">Subscribe</span>
                              </span>
                            </button>
							<span class="yt-subscription-button-subscriber-count-branded-horizontal"><?php echo $_user['subscribers']; ?></span>
                          <?php
                          } else { ?>
                            <button onclick=";subscribe();return false;" title="" id="subscribe-button" type="button" class="<?php if ($_user['subscribed']) {
                                                                                                                                echo "subscribed ";
                                                                                                                              } ?> yt-subscription-button hover-enabled yt-uix-button yt-uix-button-subscribe-branded" role="button"> <span class="yt-uix-button-icon-wrapper">
                                <img class="yt-uix-button-icon yt-uix-button-icon-subscribe" src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
                                <span class="yt-uix-button-valign"></span>
                              </span>
                              <span class="yt-uix-button-content">
                                <span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span>
                              </span>
                            </button>
							<span class="yt-subscription-button-subscriber-count-branded-horizontal"><?php echo $_user['subscribers']; ?></span>
                          <?php } ?>
  <div class="yt-uix-overlay " data-overlay-style="primary" data-overlay-shape="tiny">
    
        <div class="yt-dialog hid">
    <div class="yt-dialog-base">
      <span class="yt-dialog-align"></span>
      <div class="yt-dialog-fg">
        <div class="yt-dialog-fg-content">
            <div class="yt-dialog-header">
              <h2 class="yt-dialog-title">
                      Subscription preferences


              </h2>
            </div>
          <div class="yt-dialog-loading">
              <div class="yt-dialog-waiting-content">
    <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Loading...</div>
  </div>

          </div>
          <div class="yt-dialog-content">
              <div class="subscription-preferences-overlay-content-container">
    <div class="subscription-preferences-overlay-loading ">
        <p class="yt-spinner">
      <img src="//web.archive.org/web/20130615211109im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

    </div>
    <div class="subscription-preferences-overlay-content">
    </div>
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


  </div>

</span>

      </div>
        <h1 class="branded-page-header-title">
    <a class="spf-link" href="/user/<?php echo htmlspecialchars($_user['username']); ?>">
      <span class="qualified-channel-title ellipsized" title="user"><span class="qualified-channel-title-wrapper ">  <span class="qualified-channel-title-text">
	<?php		if($_user['partner'] == "y") { ?>
	<?php if($_user['title'])	{	?>
	<?php echo htmlspecialchars($_user['title']); ?>
	<?php } else {	?>
	<?php echo htmlspecialchars($_user['username']); ?>
	<?php	}	?>
	<?php	} else{	?>
	<?php echo htmlspecialchars($_user['username']); ?>
	<?php	}	?>

	<?php if($__user_h->if_partner($_user['username'])) { ?>
	<img style="width: 29px;vertical-align: middle;margin-left: 10px;" title="This user is a partner!" src="/yt/imgbin/RenderedImage.png">
	<?php } ?>
												<?php if($_user['username'] == "neontflame") { ?>
													<img style="width: 29px;vertical-align: middle;margin-left: 10px;" title="This user is very cool" src="/awesom_face.png">
												<?php } ?>
  </span>
</span></span>
    </a>
  </h1>

    </div>
      <div id="channel-subheader" class="clearfix branded-page-gutter-padding">
    
    <ul id="channel-navigation-menu" class="clearfix">
      <li>
              <button class="epic-nav-item-empty yt-uix-button yt-uix-button-epic-nav-item yt-uix-button-empty" type="button" onclick=";return false;" data-button-menu-id="channel-navigation-menu-dropdown" role="button" aria-label="Select view:"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-c4-home" src="//web.archive.org/web/20130615211109im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""><span class="yt-uix-button-valign"></span></span><img class="yt-uix-button-arrow" src="//web.archive.org/web/20130615211109im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
    <div id="channel-navigation-menu-dropdown" class="hid epic-nav-item-dropdown">
    <ul>
          <li class="epic-nav-item-selected">
    <a class="spf-link yt-uix-button-menu-item" href="<?php if($_user['vanity'])	{	?>/<?php echo htmlspecialchars($_user['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($_user['username']); ?><?php } ?>">Browse</a>
  </li>

        <li>
    <a class="spf-link yt-uix-button-menu-item" href="<?php if($_user['vanity'])	{	?>/<?php echo htmlspecialchars($_user['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($_user['username']); ?><?php } ?>">Feed</a>
  </li>

    </ul>
  </div>


      </li>
      <li>
        <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>/videos" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item" data-sessionlink="ei=bdi8UZPkOOKVhgHu94HAAQ"><span class="yt-uix-button-content">Videos</span></a>
      </li>
	   <li>
        <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>/discussion" class="yt-uix-button  spf-link yt-uix-sessionlink yt-uix-button-epic-nav-item" data-sessionlink="ei=bdi8UZPkOOKVhgHu94HAAQ"><span class="yt-uix-button-content">Discussion</span></a>
      </li>
        <li>
          <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>&about" class="yt-uix-button  spf-link selected  yt-uix-sessionlink yt-uix-button-epic-nav-item" data-sessionlink="ei=bdi8UZPkOOKVhgHu94HAAQ"><span class="yt-uix-button-content">About</span></a>
        </li>
    </ul>
  </div>

  </div>

  </div>


    </div>
  <div class="branded-page-v2-body" id="gh-overviewtab">
          

      <div class="c4-spotlight-module">
      <div class="c4-spotlight-module-component upsell">
          
  <div class="upsell-video-container">
          <div class="video-player-view-component c4-box">
    <div class="video-content clearfix ">
          <div class="c4-player-container  c4-flexible-player-container">
      <div class="c4-flexible-height-setter"></div>
      <div id="upsell-video" class="c4-flexible-player-box" data-video-id="7xg48eBUkDw" data-swf-config="{&amp;quot;url_v9as2&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/cps-vflWSUfeV.swf&amp;quot;, &amp;quot;url_v8&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/cps-vflWSUfeV.swf&amp;quot;, &amp;quot;attrs&amp;quot;: {&amp;quot;id&amp;quot;: &amp;quot;movie_player&amp;quot;}, &amp;quot;params&amp;quot;: {&amp;quot;allowscriptaccess&amp;quot;: &amp;quot;always&amp;quot;, &amp;quot;bgcolor&amp;quot;: &amp;quot;#000000&amp;quot;, &amp;quot;allowfullscreen&amp;quot;: &amp;quot;true&amp;quot;}, &amp;quot;sts&amp;quot;: 1586, &amp;quot;assets&amp;quot;: {&amp;quot;js&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/jsbin\/html5player-vfl39KBj1.js&amp;quot;, &amp;quot;css&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/cssbin\/www-player-vfl5QSahP.css&amp;quot;, &amp;quot;html&amp;quot;: &amp;quot;\/html5_player_template&amp;quot;}, &amp;quot;html5&amp;quot;: false, &amp;quot;min_version&amp;quot;: &amp;quot;8.0.0&amp;quot;, &amp;quot;args&amp;quot;: {&amp;quot;autoplay&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;hl&amp;quot;: &amp;quot;en_US&amp;quot;, &amp;quot;ps&amp;quot;: &amp;quot;default&amp;quot;, &amp;quot;oid&amp;quot;: &amp;quot;hCep59706eqLQBo0TOAFXQ&amp;quot;, &amp;quot;allow_ratings&amp;quot;: 1, &amp;quot;track_embed&amp;quot;: 1, &amp;quot;rmktPingThreshold&amp;quot;: 0, &amp;quot;rel&amp;quot;: 0, &amp;quot;has_cc&amp;quot;: false, &amp;quot;eventLabel&amp;quot;: &amp;quot;profilepage&amp;quot;, &amp;quot;pltype&amp;quot;: &amp;quot;content&amp;quot;, &amp;quot;playerStyle&amp;quot;: &amp;quot;default&amp;quot;, &amp;quot;avg_rating&amp;quot;: 4.86076233032, &amp;quot;url_encoded_fmt_stream_map&amp;quot;: &amp;quot;url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fnewshard%3Dyes%26ratebypass%3Dyes%26id%3Def1838f1e054903c%26key%3Dyt1%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D46%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26mt%3D1371330451%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire\u0026quality=hd1080\u0026itag=46\u0026type=video%2Fwebm%3B+codecs%3D%22vp8.0%2C+vorbis%22\u0026sig=CF861A245ACAC5F04DBBF929F78CA51BB5FB7BE6.488EC401BEA18EDB10E6669CE734D8F1BE93DEBE\u0026fallback_host=tc.v20.cache2.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fnewshard%3Dyes%26ratebypass%3Dyes%26id%3Def1838f1e054903c%26key%3Dyt1%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D37%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26mt%3D1371330451%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire\u0026quality=hd1080\u0026itag=37\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.64001F%2C+mp4a.40.2%22\u0026sig=656E81DBCB2BC9A216A70A403C1185294B189CE6.066D24B754F4BCB8865B70AD140986CF5D433FF9\u0026fallback_host=tc.v11.cache8.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fnewshard%3Dyes%26ratebypass%3Dyes%26id%3Def1838f1e054903c%26key%3Dyt1%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D45%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26mt%3D1371330451%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire\u0026quality=hd720\u0026itag=45\u0026type=video%2Fwebm%3B+codecs%3D%22vp8.0%2C+vorbis%22\u0026sig=C3E0846EE757AF1C24CB7CEC387BE0152AC1AE29.256013A63AC9E1B26D3BDD09081EB40DAA7EB0C0\u0026fallback_host=tc.v3.cache2.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fnewshard%3Dyes%26ratebypass%3Dyes%26id%3Def1838f1e054903c%26key%3Dyt1%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D22%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26mt%3D1371330451%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire\u0026quality=hd720\u0026itag=22\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.64001F%2C+mp4a.40.2%22\u0026sig=051E5E2BF47816113241D2E676D942FEA1FBDF65.11B583405AC55CD1536EA33D27420A997C0E226D\u0026fallback_host=tc.v10.cache4.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fnewshard%3Dyes%26ratebypass%3Dyes%26id%3Def1838f1e054903c%26key%3Dyt1%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D44%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26mt%3D1371330451%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire\u0026quality=large\u0026itag=44\u0026type=video%2Fwebm%3B+codecs%3D%22vp8.0%2C+vorbis%22\u0026sig=45D2D3AA907F3DD6C40FE4689A8EB69D77D7825D.9A6FAA25FCA203F143A843E9C1B1807E656C2AF6\u0026fallback_host=tc.v13.cache3.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fkey%3Dyt1%26id%3Def1838f1e054903c%26burst%3D40%26algorithm%3Dthrottle-factor%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D35%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26newshard%3Dyes%26mt%3D1371330451%26factor%3D1.25%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire\u0026quality=large\u0026itag=35\u0026type=video%2Fx-flv\u0026sig=59C4E3B5C367BCCFF3D99084BD736E086CDE599D.9CD4B1F2DC10836D7C058B87FBF9E38EE81DD673\u0026fallback_host=tc.v7.cache3.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fnewshard%3Dyes%26ratebypass%3Dyes%26id%3Def1838f1e054903c%26key%3Dyt1%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D43%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26mt%3D1371330451%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire\u0026quality=medium\u0026itag=43\u0026type=video%2Fwebm%3B+codecs%3D%22vp8.0%2C+vorbis%22\u0026sig=B86C98BF437634ACF0D78599045183DEB13EFCB5.9652AACBA6A9620EC079BE3C8D48BE0875ED6DDC\u0026fallback_host=tc.v16.cache5.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fkey%3Dyt1%26id%3Def1838f1e054903c%26burst%3D40%26algorithm%3Dthrottle-factor%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D34%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26newshard%3Dyes%26mt%3D1371330451%26factor%3D1.25%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire\u0026quality=medium\u0026itag=34\u0026type=video%2Fx-flv\u0026sig=90460D72708A3784F3C08A286C02C72FDF0DAC23.AD78479EB913022026DC708488EF101AE33343F8\u0026fallback_host=tc.v8.cache2.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fnewshard%3Dyes%26ratebypass%3Dyes%26id%3Def1838f1e054903c%26key%3Dyt1%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D18%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26mt%3D1371330451%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire\u0026quality=medium\u0026itag=18\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.42001E%2C+mp4a.40.2%22\u0026sig=2A4CA7FFC2BBE5E4AA05F7C9E1DBDAB8584A8E27.143919CA42C93C652740FF76E8F032D2B15C1726\u0026fallback_host=tc.v2.cache6.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fkey%3Dyt1%26id%3Def1838f1e054903c%26burst%3D40%26algorithm%3Dthrottle-factor%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D5%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26newshard%3Dyes%26mt%3D1371330451%26factor%3D1.25%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire\u0026quality=small\u0026itag=5\u0026type=video%2Fx-flv\u0026sig=266F80E52F42AB05C684B2C5FBABF7EB168802BD.BBCFA7A990F8D5489C35A5A3716AABD0A4119309\u0026fallback_host=tc.v1.cache6.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fkey%3Dyt1%26id%3Def1838f1e054903c%26burst%3D40%26algorithm%3Dthrottle-factor%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D36%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26newshard%3Dyes%26mt%3D1371330451%26factor%3D1.25%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire\u0026quality=small\u0026itag=36\u0026type=video%2F3gpp%3B+codecs%3D%22mp4v.20.3%2C+mp4a.40.2%22\u0026sig=9AD02478DD5DFB0A18EB4B333129D5CF000FAE4D.3B8F843EA8626A3996D097516BB2A96ECE2BF95B\u0026fallback_host=tc.v13.cache6.c.youtube.com,url=http%3A%2F%2Fr2---sn-huojp-5cwe.c.youtube.com%2Fvideoplayback%3Fkey%3Dyt1%26id%3Def1838f1e054903c%26burst%3D40%26algorithm%3Dthrottle-factor%26ipbits%3D8%26fexp%3D901441%252C900352%252C921047%252C924605%252C928201%252C901208%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C925714%252C929919%252C931202%252C928017%252C912512%252C912515%252C912521%252C906906%252C904488%252C931910%252C931913%252C932227%252C904830%252C919373%252C906836%252C933701%252C904122%252C932216%252C926403%252C912711%252C930618%252C930621%252C929606%252C910075%26itag%3D17%26ms%3Dau%26source%3Dyoutube%26expire%3D1371355638%26mv%3Du%26newshard%3Dyes%26mt%3D1371330451%26factor%3D1.25%26sver%3D3%26ip%3D207.241.226.82%26upn%3Dk2Zjr6MYHn0%26cp%3DU0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire\u0026quality=small\u0026itag=17\u0026type=video%2F3gpp%3B+codecs%3D%22mp4v.20.3%2C+mp4a.40.2%22\u0026sig=7524849D80D6504F5EA745404A08AB799194167D.C0F4AB60C3E4C1465AAB5E6ECC979129FC615C6F\u0026fallback_host=tc.v2.cache5.c.youtube.com&amp;quot;, &amp;quot;el&amp;quot;: &amp;quot;profilepage&amp;quot;, &amp;quot;account_playback_token&amp;quot;: &amp;quot;agGi3u8fIVX0SS0-K5yMrzEYcHB8MTM3MTQxNzA3MEAxMzcxMzMwNjcw&amp;quot;, &amp;quot;width&amp;quot;: &amp;quot;360&amp;quot;, &amp;quot;thumbnail_url&amp;quot;: &amp;quot;http:\/\/i4.ytimg.com\/vi\/7xg48eBUkDw\/default.jpg&amp;quot;, &amp;quot;ptk&amp;quot;: &amp;quot;Alloy&amp;quot;, &amp;quot;aid&amp;quot;: &amp;quot;P-DGOr13Jm8&amp;quot;, &amp;quot;delay&amp;quot;: &amp;quot;9&amp;quot;, &amp;quot;iurlsd&amp;quot;: &amp;quot;http:\/\/i4.ytimg.com\/vi\/7xg48eBUkDw\/sddefault.jpg&amp;quot;, &amp;quot;is_video_preview&amp;quot;: false, &amp;quot;token&amp;quot;: &amp;quot;vjVQa1PpcFOF5GJJNuku2medSriRQfqZ8nvWNYjGnjo=&amp;quot;, &amp;quot;idpj&amp;quot;: &amp;quot;-7&amp;quot;, &amp;quot;status&amp;quot;: &amp;quot;ok&amp;quot;, &amp;quot;quality_cap&amp;quot;: &amp;quot;highres&amp;quot;, &amp;quot;height&amp;quot;: &amp;quot;203&amp;quot;, &amp;quot;iv_module&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/iv_module-vflz0k0Qw.swf&amp;quot;, &amp;quot;vq&amp;quot;: &amp;quot;auto&amp;quot;, &amp;quot;ftoken&amp;quot;: &amp;quot;jjXyxS2BIzAfFBGr7XDYcJWTaC98MTM3MTQxNzA3MEAxMzcxMzMwNjcw&amp;quot;, &amp;quot;endscreen_module&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/endscreen-vflQhqnfl.swf&amp;quot;, &amp;quot;allow_embed&amp;quot;: 1, &amp;quot;author&amp;quot;: &amp;quot;user&amp;quot;, &amp;quot;showinfo&amp;quot;: &amp;quot;0&amp;quot;, &amp;quot;iv3_module&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/iv3_module-vfliaXqHM.swf&amp;quot;, &amp;quot;ytfocEnabled&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;keywords&amp;quot;: &amp;quot;dixon cider,user,funny,music video,ian hecox,anthony padilla,dixon,cider,pun,parody,comedy,humor,twerk,donut,Spoof,Dancing,Dance,Official,Youtube&amp;quot;, &amp;quot;autohide&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;watermark&amp;quot;: &amp;quot;,http:\/\/s.ytimg.com\/yts\/img\/watermark\/youtube_watermark-vflHX6b6E.png,http:\/\/s.ytimg.com\/yts\/img\/watermark\/youtube_hd_watermark-vflAzLcD6.png&amp;quot;, &amp;quot;vid&amp;quot;: &amp;quot;7xg48eBUkDw&amp;quot;, &amp;quot;view_count&amp;quot;: 5000038, &amp;quot;enablejsapi&amp;quot;: 1, &amp;quot;ldpj&amp;quot;: &amp;quot;0&amp;quot;, &amp;quot;timestamp&amp;quot;: 1371330670, &amp;quot;sourceid&amp;quot;: &amp;quot;y&amp;quot;, &amp;quot;iv_invideo_url&amp;quot;: &amp;quot;http:\/\/www.youtube.com\/annotations_invideo?features=1\u0026legacy=1\u0026video_id=7xg48eBUkDw&amp;quot;, &amp;quot;eurl&amp;quot;: &amp;quot;http:\/\/www.youtube.com\/user\/user&amp;quot;, &amp;quot;sdetail&amp;quot;: &amp;quot;p:\/user\/viceversa2013\/&amp;quot;, &amp;quot;tmi&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;dashmpd&amp;quot;: &amp;quot;http:\/\/www.youtube.com\/api\/manifest\/dash\/id\/ef1838f1e054903c\/signature\/0A3A82449300D1EC73F9840FBB5CEB1C077850EA.AD6E873EBABBC8B88C2FA2A6CFF98731CE590CF1\/key\/yt1\/ipbits\/8\/fexp\/901441%2C900352%2C921047%2C924605%2C928201%2C901208%2C929123%2C929121%2C929915%2C929906%2C929907%2C929125%2C925714%2C929919%2C931202%2C928017%2C912512%2C912515%2C912521%2C906906%2C904488%2C931910%2C931913%2C932227%2C904830%2C919373%2C906836%2C933701%2C904122%2C932216%2C926403%2C912711%2C930618%2C930621%2C929606%2C910075\/source\/youtube\/expire\/1371355638\/as\/fmp4_audio_clear%2Cwebm_audio_clear%2Cfmp4_sd_hd_clear%2Cwebm_sd_hd_clear\/sver\/3\/ip\/207.241.226.82\/upn\/atACq8_PwUY\/cp\/U0hWRlNQU19IUkNONl9LSldHOm1OTzJaMzA3TVph\/sparams\/as%2Ccp%2Cid%2Cip%2Cipbits%2Csource%2Cexpire&amp;quot;, &amp;quot;fmt_list&amp;quot;: &amp;quot;46\/1920x1080\/99\/0\/0,37\/1920x1080\/9\/0\/115,45\/1280x720\/99\/0\/0,22\/1280x720\/9\/0\/115,44\/854x480\/99\/0\/0,35\/854x480\/9\/0\/115,43\/640x360\/99\/0\/0,34\/640x360\/9\/0\/115,18\/640x360\/9\/0\/115,5\/320x240\/7\/0\/0,36\/320x240\/99\/0\/0,17\/176x144\/99\/0\/0&amp;quot;, &amp;quot;video_id&amp;quot;: &amp;quot;7xg48eBUkDw&amp;quot;, &amp;quot;iv_load_policy&amp;quot;: 1, &amp;quot;sw&amp;quot;: &amp;quot;1.0&amp;quot;, &amp;quot;cr&amp;quot;: &amp;quot;US&amp;quot;, &amp;quot;uid&amp;quot;: &amp;quot;Y30JRSgfhYXA6i6xX1erWg&amp;quot;, &amp;quot;share_icons&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/sharing-vflF4tO1T.swf&amp;quot;, &amp;quot;no_get_video_log&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;is_purchased&amp;quot;: false, &amp;quot;ptchn&amp;quot;: &amp;quot;user&amp;quot;, &amp;quot;fexp&amp;quot;: &amp;quot;901441,900352,921047,924605,928201,901208,929123,929121,929915,929906,929907,929125,925714,929919,931202,928017,912512,912515,912521,906906,904488,931910,931913,932227,904830,919373,906836,933701,904122,932216,926403,912711,930618,930621,929606,910075&amp;quot;, &amp;quot;focEnabled&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;advideo&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;rmktEnabled&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;referrer&amp;quot;: &amp;quot;http:\/\/www.youtube.com\/user\/viceversa2013\/&amp;quot;, &amp;quot;plid&amp;quot;: &amp;quot;AATfN817Pq4U5ZDN&amp;quot;, &amp;quot;iurlmaxres&amp;quot;: &amp;quot;http:\/\/i4.ytimg.com\/vi\/7xg48eBUkDw\/maxresdefault.jpg&amp;quot;, &amp;quot;dash&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;baseUrl&amp;quot;: &amp;quot;http:\/\/googleads.g.doubleclick.net\/pagead\/viewthroughconversion\/962985656\/&amp;quot;, &amp;quot;length_seconds&amp;quot;: 253, &amp;quot;st_module&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/st_module-vflY6RCKF.swf&amp;quot;, &amp;quot;muted&amp;quot;: &amp;quot;0&amp;quot;, &amp;quot;iurl&amp;quot;: &amp;quot;http:\/\/i4.ytimg.com\/vi\/7xg48eBUkDw\/hqdefault.jpg&amp;quot;, &amp;quot;sk&amp;quot;: &amp;quot;C4ZFc_ZmhYebOkXdJJWmvDzgKu-0zqMxC&amp;quot;}, &amp;quot;url&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/watch_as3-vflrBfphp.swf&amp;quot;}" style="overflow: hidden;">
  </div>

  </div>
<div class="video-content-info">
      </div>
    </div>
  </div>

  </div>

      </div>
  </div>

      <div id="c4-shelves-container" class="context-data-container">
        
          <div class="expanded-shelf shelf-item c4-box">

    <h2 class="branded-page-module-title">
          About <?php		if($_user['partner'] == "y") { ?>
	<?php if($_user['title'])	{	?>
	<?php echo htmlspecialchars($_user['title']); ?>
	<?php } else {	?>
	<?php echo htmlspecialchars($_user['username']); ?>
	<?php	}	?>
	<?php	} else{	?>
	<?php echo htmlspecialchars($_user['username']); ?>
	<?php	}	?>

	</h2>
    </div>
<div class="about-metadata branded-page-box-padding clearfix ">
        <ul class="about-stats">
<p><?php echo $__video_h->shorten_description($_user['bio'], 5000, true); ?></p>
<br>
<br>
          <ul class="about-custom-links">
          <li class="custom-links-item">
		   <?php if(!empty($_user['website'])) { ?>
            <div class="user-profile-item">
    <a href="<?php echo addhttp(htmlspecialchars($_user['website'])); ?>" class="about-custom-link yt-uix-redirect-link about-custom-link-with-icon">
        <img src="https://www.google.com/s2/favicons?domain=<?php echo htmlspecialchars($_user['website']); ?>" class="about-custom-link-favicon" alt="">
      <span class="about-custom-link-text">
        <?php echo htmlspecialchars($_user['website']); ?>
      </span>
    </a>
		  <?php } ?>
  </li>

    </ul>
<hr class="yt-horizontal-rule ">
	<div align="right">
      <li class="about-stat">
        <span class="about-stat-value"><b><?php echo $_user['subscribers']; ?></b></span> subscribers
      </li>

      <li class="about-stat">
        <span class="about-stat-value"><b><?php echo $_user['views']; ?></b></span> views
      </li>
      <small><li class="about-stat joined-date">
Joined
        <span class="value">
          <?php echo date("M d, Y", strtotime($_user['created'])); ?>
        </span></small>
      </li>
	  <div>
  </ul>      
    </div>




            
      

      </div>
  </div>

      </div>
		<!-- end page -->
<script id="www-core-js" src="/yt/jsbin/www-core-vfl1pq97W.js" data-loaded="true"></script>
		<script id="www-core-js" src="//s.ytimg.com/yt/jsbin/www-core-vfl-1JTp7.js" data-loaded="true"></script>
		<script>
			yt.setConfig({
			'XSRF_TOKEN': 'sWZ0733z73lb8fEYAYSd84MaNV98MTM0OTEzMDExNUAxMzQ5MDQzNzE1',
			'XSRF_FIELD_NAME': 'session_token'
			});
			yt.pubsub.subscribe('init', yt.www.xsrf.populateSessionToken);
			
			yt.setConfig('XSRF_REDIRECT_TOKEN', '08fYRr2a9pjbx2VYZhoZtyl-4lh8MTM0OTEzMDExNUAxMzQ5MDQzNzE1');
			
			yt.setConfig({
			'EVENT_ID': "CJuY27ur3rICFaL4OgodEHRznw==",
			'CURRENT_URL': "\/\/www.youtube.com\/watch?v=<?php echo htmlspecialchars($_video['rid']); ?>\u0026feature=g-logo-xit",
			'LOGGED_IN': false,
			'SESSION_INDEX': null,
			
			'WATCH_CONTEXT_CLIENTSIDE': false,
			
			'FEEDBACK_LOCALE_LANGUAGE': "en",
			'FEEDBACK_LOCALE_EXTRAS': {"logged_in": false, "experiments": "906717,901803,907354,904448,901424,922401,920704,912806,913419,913546,913556,919349,919351,925109,919003,912706,900816", "guide_subs": "NA", "accept_language": null}    });
		</script>
		<script>
			if (window.yt.timing) {yt.timing.tick("js_head");}    
		</script>
		<script>
			yt.setAjaxToken('subscription_ajax', "");
			yt.pubsub.subscribe('init', yt.www.subscriptions.SubscriptionButton.init);
			
		</script>
		<script>
			yt.setConfig({
			  'VIDEO_ID': "<?php echo htmlspecialchars($_video['rid']); ?>"    });
			yt.setAjaxToken('watch_actions_ajax', "");
			
			if (window['gYouTubePlayerReady']) {
			  yt.registerGlobal('gYouTubePlayerReady');
			}
		</script>
		<script>
        yt = yt || {};
      yt.playerConfig = {"assets": {"css_actions": "\/\/s.ytimg.com\/yt\/cssbin\/www-player-actions-vflWsl9n_.css", "html": "\/html5_player_template", "css": "\/\/s.ytimg.com\/yt\/cssbin\/www-player-vflE5bu0u.css", "js": "\/\/s.ytimg.com\/yt\/jsbin\/html5player-vfl1S0-AB.js"}, "url": "\/\/s.ytimg.com\/yt\/swfbin\/watch_as3-vfloWhEvq.swf", "min_version": "8.0.0", "args": {"fexp": "907722,906062,910102,927104,922401,920704,912806,927201,913546,913556,925109,919003,920201,912706,900816", "ptk": "youtube_multi", "enablecsi": "1", "allow_embed": 1, "rvs": "", "vq": "auto", "account_playback_token": "", "autohide": "2", "csi_page_type": "watch5", "keywords": "<?php echo htmlspecialchars($_video['tags']); ?>", "cr": "US", "iv3_module": "\/\/s.ytimg.com\/yt\/swfbin\/iv3_module-vflGCS_pr.swf", "fmt_list": "43\/320x240\/99\/0\/0,34\/320x240\/9\/0\/115,18\/320x240\/9\/0\/115,5\/320x240\/7\/0\/0,36\/320x240\/99\/0\/0,17\/176x144\/99\/0\/0", "title": "<?php echo htmlspecialchars($_video['title']); ?>", "length_seconds": <?php echo $_video['duration']; ?>, "enablejsapi": 1, "advideo": "1", "tk": "o3_r7m6s_HAaFxeywi14S3qFcY4uSrEiWfZ8KVUoyEB_gj1rlrELuQ==", "iv_load_policy": 1, "iv_module": "\/\/s.ytimg.com\/yt\/swfbin\/iv_module-vflBJ5PLc.swf", "sdetail": "p:bit.ly\/dwMq4b", "url_encoded_fmt_stream_map": "", "watermark": ",\/\/s.ytimg.com\/yt\/img\/watermark\/youtube_watermark-vflHX6b6E.png,\/\/s.ytimg.com\/yt\/img\/watermark\/youtube_hd_watermark-vflAzLcD6.png", "sourceid": "r", "timestamp": 1349916364, "storyboard_spec": "", "plid": "AATLveVba5g8mPZ8", "showpopout": 1, "hl": "en_US", "tmi": "1", "iv_logging_level": 4, "st_module": "\/\/s.ytimg.com\/yt\/swfbin\/st_module-vflCXoloO.swf", "no_get_video_log": "1", "iv_close_button": 0, "endscreen_module": "\/\/s.ytimg.com\/yt\/swfbin\/endscreen-vflK6XzTZ.swf", "iv_read_url": "\/\/www.youtube.com\/annotations_iv\/read2?sparams=expire%2Cvideo_id\u0026expire=1349959800\u0026key=a1\u0026signature=815C68436F1E8F95A9283A421D758B7A6452EFD9.5029A9CC9CFCF79F0B17A60238447CA0FE7CA991\u0026video_id=oHg5SJYRHA0\u0026feat=CS", "iv_queue_log_level": 0, "referrer": "\/\/bit.ly\/dwMq4b", "video_id": "<?php echo htmlspecialchars($_video['rid']); ?>", "sw": "1.0", "sk": "4md16KjsgYmUvVHOsiBQxSFIkPbju0d8C", "pltype": "contentugc", "t": "vjVQa1PpcFN8E8yJ1Q1BJFTy1GYmGAMgRZUyNC4FMBY=", "loudness": -23.6900005341}, "url_v9as2": "\/\/s.ytimg.com\/yt\/swfbin\/cps-vfl2Ur0rq.swf", "params": {"allowscriptaccess": "always", "allowfullscreen": "true", "bgcolor": "#000000"}, "attrs": {"id": "movie_player"}, "url_v8": "\/\/s.ytimg.com\/yt\/swfbin\/cps-vfl2Ur0rq.swf", "html5": false};
      yt.setConfig({
    'EMBED_HTML_TEMPLATE': "\u003ciframe width=\"__width__\" height=\"__height__\" src=\"__url__\" frameborder=\"0\" allowfullscreen\u003e\u003c\/iframe\u003e",
    'EMBED_HTML_URL': "\/\/www.youtube.com\/embed\/__videoid__"
  });
    yt.setMsg('FLASH_UPGRADE', "\u003cdiv class=\"yt-alert yt-alert-default yt-alert-error  yt-alert-player\"\u003e  \u003cdiv class=\"yt-alert-icon\"\u003e\n    \u003cimg s\u0072c=\"\/\/s.ytimg.com\/yt\/img\/pixel-vfl3z5WfW.gif\" class=\"icon master-sprite\" alt=\"Alert icon\"\u003e\n  \u003c\/div\u003e\n\u003cdiv class=\"yt-alert-buttons\"\u003e\u003c\/div\u003e\u003cdiv class=\"yt-alert-content\" role=\"alert\"\u003e    \u003cspan class=\"yt-alert-vertical-trick\"\u003e\u003c\/span\u003e\n    \u003cdiv class=\"yt-alert-message\"\u003e\n            You need to upgrade your Adobe Flash Player to watch this video. \u003cbr\u003e \u003ca href=\"\/\/get.adobe.com\/flashplayer\/\"\u003eDownload it from Adobe.\u003c\/a\u003e\n    \u003c\/div\u003e\n\u003c\/div\u003e\u003c\/div\u003e");
  yt.setMsg('PLAYER_FALLBACK', "\u003cdiv class=\"yt-alert yt-alert-default yt-alert-error  yt-alert-player\"\u003e  \u003cdiv class=\"yt-alert-icon\"\u003e\n    \u003cimg s\u0072c=\"\/\/s.ytimg.com\/yt\/img\/pixel-vfl3z5WfW.gif\" class=\"icon master-sprite\" alt=\"Alert icon\"\u003e\n  \u003c\/div\u003e\n\u003cdiv class=\"yt-alert-buttons\"\u003e\u003c\/div\u003e\u003cdiv class=\"yt-alert-content\" role=\"alert\"\u003e    \u003cspan class=\"yt-alert-vertical-trick\"\u003e\u003c\/span\u003e\n    \u003cdiv class=\"yt-alert-message\"\u003e\n            The Adobe Flash Player or an HTML5 supported browser is required for video playback. \u003cbr\u003e \u003ca href=\"\/\/get.adobe.com\/flashplayer\/\"\u003eGet the latest Flash Player\u003c\/a\u003e \u003cbr\u003e \u003ca href=\"\/html5\"\u003eLearn more about upgrading to an HTML5 browser\u003c\/a\u003e\n    \u003c\/div\u003e\n\u003c\/div\u003e\u003c\/div\u003e");
  yt.setMsg('QUICKTIME_FALLBACK', "\u003cdiv class=\"yt-alert yt-alert-default yt-alert-error  yt-alert-player\"\u003e  \u003cdiv class=\"yt-alert-icon\"\u003e\n    \u003cimg s\u0072c=\"\/\/s.ytimg.com\/yt\/img\/pixel-vfl3z5WfW.gif\" class=\"icon master-sprite\" alt=\"Alert icon\"\u003e\n  \u003c\/div\u003e\n\u003cdiv class=\"yt-alert-buttons\"\u003e\u003c\/div\u003e\u003cdiv class=\"yt-alert-content\" role=\"alert\"\u003e    \u003cspan class=\"yt-alert-vertical-trick\"\u003e\u003c\/span\u003e\n    \u003cdiv class=\"yt-alert-message\"\u003e\n            The Adobe Flash Player or QuickTime is required for video playback. \u003cbr\u003e \u003ca href=\"\/\/get.adobe.com\/flashplayer\/\"\u003eGet the latest Flash Player\u003c\/a\u003e \u003cbr\u003e \u003ca href=\"\/\/www.apple.com\/quicktime\/download\/\"\u003eGet the latest version of QuickTime\u003c\/a\u003e\n    \u003c\/div\u003e\n\u003c\/div\u003e\u003c\/div\u003e");


    (function() {
      var forceUpdate = yt.www.watch.player.updateConfig(yt.playerConfig);
      var youTubePlayer = yt.player.update('watch-player', yt.playerConfig,
          forceUpdate, gYouTubePlayerReady);
      yt.setConfig({'PLAYER_REFERENCE': youTubePlayer});
    })();
  </script>
		<script>
			yt.setConfig({
			  'SUBSCRIBE_AXC': "",
			
			  'IS_OWNER_VIEWING': null,
			  'IS_WIDESCREEN': false,
			  'PREFER_LOW_QUALITY': false,
			  'WIDE_PLAYER_STYLES': ["watch-wide-mode"],
			  'COMMENT_SHARE_URL': "\/\/www.youtube.com\/comment?lc=_COMMENT_ID_",
			  'ALLOW_EMBED': true,
			  'ALLOW_RATINGS': true,
			
			  'LIST_AUTO_PLAY_ON': false,
			  'LIST_AUTO_PLAY_VALUE': 1,
			  'SHUFFLE_VALUE': 0,
			  'SHUFFLE_ENABLED': false,
			  'YPC_CAN_RATE_VIDEO': true,
			  'YPC_SHOW_VPPA_CONFIRM_RATING': false,
			
			
			
			
			
			
			
			
			  'PLAYBACK_ID': "AATK8rd3IxlBnwIO",
			  'PLAY_ALL_MAX': 480    });
			
			yt.setMsg({
			  'LOADING': "Loading...",
			  'WATCH_ERROR_MESSAGE': "This feature is not available right now. Please try again later."    });
			
			
			
			  yt.setMsg({
			'UNBLOCK_USER': "Are you sure you want to unblock this user?",
			'BLOCK_USER': "Are you sure you want to block this user?"
			});
			yt.setConfig('BLOCK_USER_AJAX_XSRF', '');
			
			
			  yt.setConfig({
			'COMMENT_SHARE_URL': "\/\/www.youtube.com\/comment?lc=_COMMENT_ID_",
			'COMMENTS_SIGNIN_URL': "",
			'COMMENTS_THRESHHOLD': -5,
			'COMMENTS_PAGE_SIZE': 10,
			'COMMENTS_COUNT': 41353,
			'COMMENTS_YPC_CAN_POST_OR_REACT_TO_COMMENT': true,
			'COMMENT_VOTE_XSRF' : '',
			'COMMENT_ACTIONS_XSRF' : '',
			'COMMENT_SOURCE': "w",
			'ENABLE_LIVE_COMMENTS': true  });
			
			yt.setAjaxToken('link_ajax', "");
			yt.setAjaxToken('comment_servlet', "");
			yt.setAjaxToken('comment_voting', "");
			
			yt.setMsg({
			'COMMENT_OK': "OK",
			'COMMENT_BLOCKED': "You have been blocked by the owner of this video.",
			'COMMENT_CAPTCHAFAIL': "The response to the letters on the image was not correct, please try again.",
			'COMMENT_PENDING': "Comment Pending Approval!",
			'COMMENT_ERROR_EMAIL': "Error, account unverified (see email)",
			'COMMENT_ERROR': "Error, try again",
			'COMMENT_OWNER_LINKING': "Comments can't contain links, please put the link in your video description and refer to it in the comment."
			});
			
			yt.pubsub.subscribe('init', yt.www.comments.init);
			
			  yt.setConfig({
			'ENABLE_LIVE_COMMENTS': true,
			'COMMENTS_VIDEO_ID': "<?php echo htmlspecialchars($_video['rid']); ?>",
			'COMMENTS_LATEST_TIMESTAMP': 1349043702,
			'COMMENTS_POLLING_INTERVAL': 15000,
			'COMMENTS_FORCE_SCROLLING': false,
			'COMMENTS_PAGE_SIZE': 10  });
			
			yt.setMsg({
			'LC_COUNT_NEW_COMMENTS': "\u003ca href=\"#\" onclick=\"yt.www.watch.livecomments.showNewComments(); return false;\"\u003eShow $count new comments.\u003c\/a\u003e"
			});
			
			yt.pubsub.subscribe('init', function() {
			  yt.net.scriptloader.load("\/\/s.ytimg.com\/yt\/jsbin\/www-livecomments-vflCp_BeU.js", function() {
			    yt.www.watch.livecomments.init();
			  });
			});
			
			
			
			  yt.setConfig('ENABLE_AUTO_LARGE', true);
			  yt.www.watch.watch5.updatePlayerSize();
			  yt.pubsub.subscribe('init', function() {
			    yt.events.listen(window, 'resize',
			        yt.www.watch.watch5.handleResize);
			  });
			
			yt.pubsub.subscribe('init', yt.www.watch.activity.init);
			yt.pubsub.subscribe('init', yt.www.watch.player.init);
			yt.pubsub.subscribe('init', yt.www.watch.actions.init);
			yt.pubsub.subscribe('init', yt.www.watch.shortcuts.init);
			
			
			yt.pubsub.subscribe('init', function() {
			  var description = _gel('watch-description');
			  if (!_hasclass(description, 'yt-uix-expander-collapsed')) {
			    yt.www.watch.watch5.handleToggleDescription(description);
			  }
			});
			
			
			
			
			
			
			
			
			
			
		</script>
		<script>
			var subscribed = <?php echo($_user['subscribed'] ? 'true' : 'false') ?>;
			var loggedIn = <?php echo(isset($_SESSION['siteusername']) ? 'true' : 'false') ?>;
			var alerts = 0;
 
			function subscribe() {
				if(loggedIn == true) { 
					if(subscribed == false) { 
						$.ajax({
							url: "/get/subscribe?n=<?php echo htmlspecialchars($_user['username']); ?>",
							type: 'GET',
							success: function(res) {
								alerts++;
								$("#subscribe-button").addClass("subscribed");
								addAlert("editsuccess_" + alerts, "Successfully added <?php echo htmlspecialchars($_user['username']); ?> to your subscriptions!");
								showAlert("#editsuccess_" + alerts);
								console.log("DEBUG: " + res);
								subscribed = true;
							}
						});
					} else {
						$.ajax({
							url: "/get/unsubscribe?n=<?php echo htmlspecialchars($_user['username']); ?>",
							type: 'GET',
							success: function(res) {
								alerts++;
								$("#subscribe-button").removeClass("subscribed");
								addAlert("editsuccess_" + alerts, "Successfully removed <?php echo htmlspecialchars($_user['username']); ?> from your subscriptions!");
								showAlert("#editsuccess_" + alerts);
								console.log("DEBUG: " + res);
								subscribed = false;
							}
						});
					}
				} else {
					alerts++;
					addAlert("editsuccess_" + alerts, "You need to log in to add subscriptions!");
					showAlert("#editsuccess_" + alerts);
				}
			}
		</script>
		<script>
			yt.setConfig('PYV_REQUEST', true);
			yt.setConfig('PYV_AFS', false);
		</script>
		<script>
			yt.www.ads.pyv.loadPyvIframe("\n  \u003cscript\u003e\n    var google_max_num_ads = '1';\n    var google_ad_output = 'js';\n    var google_ad_type = 'text';\n    var google_only_pyv_ads = true;\n    var google_video_doc_id = \"yt_<?php echo htmlspecialchars($_video['rid']); ?>\";\n      var google_ad_request_done = parent.yt.www.ads.pyv.pyvWatchAfcWithPpvCallback;\n    var google_ad_client = 'ca-pub-6219811747049371';\n    var google_ad_block = '3';\n      var google_ad_host = \"ca-host-pub-6813290291914109\";\n      var google_ad_host_tier_id = \"464885\";\n      var google_page_url = \"\\\/\\\/www.youtube.com\\\/video\\\/<?php echo htmlspecialchars($_video['rid']); ?>\";\n      var google_ad_channel = \"PyvWatchInRelated+PyvYTWatch+PyvWatchNoAdX+pw+non_lpw+afv_user_funker530+afv_user_id_<?php echo htmlspecialchars($_video['author']); ?>+yt_mpvid_AATK8rd3hYr5XSL9+yt_cid_676+ytexp_906717.901803.907354.904448.901424.922401.920704.912806.913419.913546.913556.919349.919351.925109.919003.912706.900816\";\n      var google_language = \"en\";\n      var google_eids = ['56702372'];\n      var google_yt_pt = \"AD1B29l_Eb6GvswrtaJp3Xbg-8Cen9ZYRkIWEEZsAd6dGBgqPd1L2hDoHNZ3vsezXxxrRKglcrLrvmR_xDdeypbUNSFkZJs63DRNWYRvVQ\";\n  \u003c\/script\u003e\n\n  \u003cscript s\u0072c=\"\/\/pagead2.googlesyndication.com\/pagead\/show_ads.js\"\u003e\u003c\/script\u003e\n");
		</script>
		<script>
			window['google_language'] = "en";
			
			
			window['google_ad_type'] = 'image';
			window['google_ad_width'] = '300';
			window['google_ad_block'] = '2';
			window['google_ad_client'] = "ca-pub-6219811747049371";
			window['google_ad_host'] = "ca-host-pub-6813290291914109";
			window['google_ad_host_tier_id'] = "464885";
			window['google_ad_channel'] = "6031455484+6031455482+0854550288+afv_user_funker530+afv_user_id_<?php echo htmlspecialchars($_video['author']); ?>+yt_mpvid_AATK8rd3hYr5XSL9+yt_cid_676+ytexp_906717.901803.907354.904448.901424.922401.920704.912806.913419.913546.913556.919349.919351.925109.919003.912706.900816+Vertical_397+Vertical_881+ytps_default+ytel_detailpage";
			window['google_video_doc_id'] = "yt_<?php echo htmlspecialchars($_video['rid']); ?>";
			window['google_color_border'] = 'FFFFFF';
			window['google_color_bg'] = 'FFFFFF';
			window['google_color_link'] = '0033CC';
			window['google_color_text'] = '444444';
			window['google_color_url'] = '0033CC';
			window['google_language'] = "en";
			window['google_alternate_ad_url'] = "\/\/www.youtube.com\/ad_frame?id=watch-channel-brand-div";
			window['google_yt_pt'] = "AD1B29l_Eb6GvswrtaJp3Xbg-8Cen9ZYRkIWEEZsAd6dGBgqPd1L2hDoHNZ3vsezXxxrRKglcrLrvmR_xDdeypbUNSFkZJs63DRNWYRvVQ";
			window['google_eids'] = ['56702371'];
			window['google_page_url'] = "\/\/www.youtube.com\/video\/<?php echo htmlspecialchars($_video['rid']); ?>";
		</script>
		<script>
			yt.pubsub.subscribe('init', function() {
			  var scriptEl = document.createElement('script');
			  scriptEl.src = "\/\/pagead2.googlesyndication.com\/pagead\/show_companion_ad.js";
			  var headEl = document.getElementsByTagName('head')[0];
			  headEl.appendChild(scriptEl);
			});
		</script>
		<script>
			function afcAdCall() {
			  var channels = "6031455484+6031455482+0854550288+afv_user_funker530+afv_user_id_<?php echo htmlspecialchars($_video['author']); ?>+yt_mpvid_AATK8rd3hYr5XSL9+yt_cid_676+ytexp_906717.901803.907354.904448.901424.922401.920704.912806.913419.913546.913556.919349.919351.925109.919003.912706.900816+Vertical_397+Vertical_881+ytps_default+ytel_detailpage";
			  channels = channels.replace('0854550288', '0854550287');
			  channels = channels.replace('afv_brand_mpu', '0854550287');
			  channels = channels + '+afc_on_page';
			  window['google_ad_format'] = '300x250_as';
			  window['google_ad_height'] = '250';
			  window['google_page_url'] = "\/\/www.youtube.com\/video\/<?php echo htmlspecialchars($_video['rid']); ?>";
			    window['google_yt_pt'] = "AD1B29l_Eb6GvswrtaJp3Xbg-8Cen9ZYRkIWEEZsAd6dGBgqPd1L2hDoHNZ3vsezXxxrRKglcrLrvmR_xDdeypbUNSFkZJs63DRNWYRvVQ";
			
			
			  var afcOptions = {
			    'ad_type': 'image',
			    'format': '300x250_as',
			    'ad_block': '2',
			    'ad_client': "ca-pub-6219811747049371",
			    'ad_host': "ca-host-pub-6813290291914109",
			    'ad_host_tier_id': "464885",
			    'ad_channel': channels,
			    'video_doc_id': "yt_<?php echo htmlspecialchars($_video['rid']); ?>",
			    'color_border': 'FFFFFF',
			    'color_bg': 'FFFFFF',
			    'color_link': '0033CC',
			    'color_text': '444444',
			    'color_url': '0033CC',
			    'language': "en",
			    'alternate_ad_url': "\/\/www.youtube.com\/ad_frame?id=watch-channel-brand-div"
			  };
			  var afcCallback = function() {
			    if (window.google && google.ads && google.ads.Ad) {
			      yt.www.watch.ads.handleShowAfvCompanionAdDiv(false);
			      var ad = new google.ads.Ad("ca-pub-6219811747049371", 'google_companion_ad_div', afcOptions);
			    } else {
			      yt.setTimeout(afcCallback, 200);
			    }
			  };
			  afcCallback();
			}
		</script>
		<script>
			yt.pubsub.subscribe('init', function() {
			  var scriptEl = document.createElement('script');
			  scriptEl.src = "\/\/www.google.com\/jsapi?autoload=%7B%22modules%22%3A%5B%7B%22name%22%3A%22ads%22%2C%22version%22%3A%221%22%2C%22callback%22%3A%22(function()%7B%7D)%22%2C%22packages%22%3A%5B%22content%22%5D%7D%5D%7D";
			  var headEl = document.getElementsByTagName('head')[0];
			  headEl.appendChild(scriptEl);
			});
		</script>
		<script src="//www.googletagservices.com/tag/js/gpt.js"></script>
		<script>
			yt.www.watch.ads.createGutSlot("\/4061\/ytpwatch\/main_676");
		</script>
		<script>
			if (window.yt.timing) {yt.timing.tick("js_page");}    
		</script>
		<script>
			yt.setConfig('TIMING_ACTION', "watch5ad");    
		</script>
		<script>yt.pubsub.subscribe('init', function() {yt.www.thumbnaildelayload.init(0);});</script>
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
			  'DRAGDROP_BINARY_URL': "\/\/s.ytimg.com\/yt\/jsbin\/www-dragdrop-vflWKaUyg.js",
			  'PLAYLIST_BAR_PLAYING_INDEX': -1  });
			
			  yt.setAjaxToken('addto_ajax_logged_out', "KTlts1bRmBPkwoVCGIRuG79_hSF8MTM0OTEzMDExNUAxMzQ5MDQzNzE1");
			
			  yt.www.lists.init();
			
			
			
			
			
			
			
			
			
			  yt.setConfig({'SBOX_JS_URL': "\/\/s.ytimg.com\/yt\/jsbin\/www-searchbox-vflsHyn9f.js",'SBOX_SETTINGS': {"CLOSE_ICON_URL": "\/\/s.ytimg.com\/yt\/img\/icons\/close-vflrEJzIW.png", "SHOW_CHIP": false, "PSUGGEST_TOKEN": null, "REQUEST_DOMAIN": "us", "EXPERIMENT_ID": -1, "SESSION_INDEX": null, "HAS_ON_SCREEN_KEYBOARD": false, "CHIP_PARAMETERS": {}, "REQUEST_LANGUAGE": "en"},'SBOX_LABELS': {"SUGGESTION_DISMISS_LABEL": "Dismiss", "SUGGESTION_DISMISSED_LABEL": "Suggestion dismissed"}});
			
			
			
			
			
		</script>
		<script>
			yt.setMsg({
			  'ADDTO_WATCH_LATER_ADDED': "Added",
			  'ADDTO_WATCH_LATER_ERROR': "Error"
			});
		</script>
		<script>
			if (window.yt.timing) {yt.timing.tick("js_foot");}    
		</script></div>
				</div>
			</div>
		</div>
		<div id="footer-container"><?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_footer.php"); ?></div>
		<div class="yt-dialog hid" id="feed-privacy-lb">
			<div class="yt-dialog-base">
				<span class="yt-dialog-align"></span>
				<div class="yt-dialog-fg">
					<div class="yt-dialog-fg-content">
						<div class="yt-dialog-loading">
							<div class="yt-dialog-waiting-content">
								<div class="yt-spinner-img"></div>
								<div class="yt-dialog-waiting-text">Loading...</div>
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
									<div class="yt-spinner-img"></div>
									<div class="yt-dialog-waiting-text">Working...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="shared-addto-watch-later-login" class="hid">
			Watch later on BetaTube? Are you crazy? I am too lazy to add that.
		</div>
		<div id="shared-addto-menu" style="display: none;" class="hid sign-in">
			<div class="addto-menu">
				<div id="addto-list-panel" class="menu-panel active-panel">
					<span class="yt-uix-button-menu-item yt-uix-tooltip sign-in" data-possible-tooltip="" data-tooltip-show-delay="750"><a href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26hl%3Den_US%26next%3D%252F%26nomobiletemp%3D1&amp;uilel=3&amp;hl=en_US&amp;service=youtube" class="sign-in-link">Sign in</a> to add this to a playlist
					</span>
				</div>
				<div id="addto-list-saved-panel" class="menu-panel">
					<div class="panel-content">
						<div class="yt-alert yt-alert-naked yt-alert-success  ">
							<div class="yt-alert-icon">
								<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
							</div>
							<div class="yt-alert-content" role="alert">
								<span class="yt-alert-vertical-trick"></span>
								<div class="yt-alert-message">
									<span class="message">Added to <span class="addto-title yt-uix-tooltip yt-uix-tooltip-reverse" title="More information about this playlist" data-tooltip-show-delay="750"></span></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="addto-list-error-panel" class="menu-panel">
					<div class="panel-content">
						<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
						<span class="error-details"></span>
						<a class="show-menu-link">Back to list</a>
					</div>
				</div>
				<div id="addto-note-input-panel" class="menu-panel">
					<div class="panel-content">
						<div class="yt-alert yt-alert-naked yt-alert-success  ">
							<div class="yt-alert-icon">
								<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
							</div>
							<div class="yt-alert-content" role="alert">
								<span class="yt-alert-vertical-trick"></span>
								<div class="yt-alert-message">
									<span class="message">Added to playlist:</span>
									<span class="addto-title yt-uix-tooltip" title="More information about this playlist" data-tooltip-show-delay="750"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="yt-uix-char-counter" data-char-limit="150">
						<div class="addto-note-box addto-text-box"><textarea id="addto-note" class="addto-note yt-uix-char-counter-input" maxlength="150"></textarea><label for="addto-note" class="addto-note-label">Add an optional note</label></div>
						<span class="yt-uix-char-counter-remaining">150</span>
					</div>
					<button disabled="disabled" type="button" class="playlist-save-note yt-uix-button yt-uix-button-default" onclick=";return false;" role="button"><span class="yt-uix-button-content">Add note </span></button>
				</div>
				<div id="addto-note-saving-panel" class="menu-panel">
					<div class="panel-content loading-content">
						<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
						<span>Saving note...</span>
					</div>
				</div>
				<div id="addto-note-saved-panel" class="menu-panel">
					<div class="panel-content">
						<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
						<span class="message">Note added to:</span>
					</div>
				</div>
				<div id="addto-note-error-panel" class="menu-panel">
					<div class="panel-content">
						<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
						<span class="message">Error adding note:</span>
						<ul class="error-details"></ul>
						<a class="add-note-link">Click to add a new note</a>
					</div>
				</div>
				<div class="close-note hid">
					<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="close-button">
				</div>
			</div>
		</div>
		<script>
			if (window.yt.timing) {yt.timing.tick("js_head");}    
		</script>
		<script id="js-3960859142" src="//s.ytimg.com/yts/jsbin/www-core-vflKz5-wF.js" data-loaded="true"></script>
		<script>
			var searchBox = document.getElementById('masthead-search-term');
			if (searchBox) {
			  searchBox.focus();
			}
			  yt.setConfig('FEED_DEBUG', true);
			
		</script>
		<script>
			// yt.setMsg('FLASH_UPGRADE', "\u003cdiv class=\"yt-alert yt-alert-default yt-alert-error  yt-alert-player\"\u003e  \u003cdiv class=\"yt-alert-icon\"\u003e\n    \u003cimg s\u0072c=\"\/\/s.ytimg.com\/yts\/img\/pixel-vfl3z5WfW.gif\" class=\"icon master-sprite\" alt=\"Alert icon\"\u003e\n  \u003c\/div\u003e\n\u003cdiv class=\"yt-alert-buttons\"\u003e\u003c\/div\u003e\u003cdiv class=\"yt-alert-content\" role=\"alert\"\u003e    \u003cspan class=\"yt-alert-vertical-trick\"\u003e\u003c\/span\u003e\n    \u003cdiv class=\"yt-alert-message\"\u003e\n            You need to upgrade your Adobe Flash Player to watch this video. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eDownload it from Adobe.\u003c\/a\u003e\n    \u003c\/div\u003e\n\u003c\/div\u003e\u003c\/div\u003e");
			yt.setConfig({
			'PLAYER_CONFIG': {"url": "\/\/s.ytimg.com\/yts\/swf\/masthead_child-vflRMMO6_.swf", "url_v9as2": "", "url_v8": "", "params": {"bgcolor": "#FFFFFF", "allowfullscreen": "false", "allowscriptaccess": "always"}, "attrs": {"width": "1", "id": "masthead_child", "height": "1"}, "min_version": "8.0.0", "args": {"enablejsapi": 1}, "html5": false}
			});
			
			// yt.flash.embed("masthead_child_div", yt.getConfig('PLAYER_CONFIG'));
		</script>
		<script id="js-90506381" src="//s.ytimg.com/yts/jsbin/www-home-vflk-sIPg.js" data-loaded="true"></script>
		<script>
			yt.setConfig({
			  'GUIDE_SELECTED_ITEM': "youtube"
			});
		</script>
		<script>yt.setConfig({'EVENT_ID': "7pFAUZzAG52shAGGr4DACw",'LOGGED_IN': false,'SESSION_INDEX': null,'CURRENT_URL': "http:\/\/www.youtube.com\/",'SAFETY_MODE_PENDING': false,'WATCH_CONTEXT_CLIENTSIDE': true,'FEEDBACK_BUCKET_ID': "Home",'FEEDBACK_LOCALE_LANGUAGE': "en",'FEEDBACK_LOCALE_EXTRAS': {"logged_in": false, "guide_subs": 8, "accept_language": null, "experiments": "906378,925005,919359,910207,914061,916611,920704,912806,902000,919512,929901,913605,925006,906938,931202,931401,908529,930803,920201,930101,930603,906834,926403", "is_branded": "", "is_partner": ""}});yt.setMsg({'ADDTO_WATCH_LATER_ADDED': "Added",'ADDTO_WATCH_LATER_ERROR': "Error"});yt.setAjaxToken('addto_ajax_logged_out', "H6seGTii3HNcaaSYiOcuR3-DGLF8MTM2MzI3MjU1OEAxMzYzMTg2MTU4");yt.setAjaxToken('channel_details_ajax', "TwF1IzDuM74TMIFat4yLZSiVCVB8MTM2MzI3MjU1OEAxMzYzMTg2MTU4");  yt.setConfig('FEED_PRIVACY_CSS_URL', "\/\/s.ytimg.com\/yts\/cssbin\/www-feedprivacydialog-vflQ4FT2R.css");
			yt.setAjaxToken('feed_privacy_ajax', "");
			  yt.pubsub.subscribe('init', yt.www.account.FeedPrivacyDialog.init);
			yt.setConfig({'SBOX_JS_URL': "\/\/s.ytimg.com\/yts\/jsbin\/www-searchbox-vflzZmr_k.js",'SBOX_SETTINGS': {"SESSION_INDEX": null, "SHOW_CHIP": false, "USE_HTTPS": false, "PSUGGEST_TOKEN": null, "HAS_ON_SCREEN_KEYBOARD": false, "REQUEST_LANGUAGE": "en", "IS_HH": true, "EXPERIMENT_ID": -1, "REQUEST_DOMAIN": "us", "CHIP_PARAMETERS": {}, "CLOSE_ICON_URL": "\/\/s.ytimg.com\/yts\/img\/icons\/close-vflrEJzIW.png"},'SBOX_LABELS': {"SUGGESTION_DISMISS_LABEL": "Dismiss", "SUGGESTION_DISMISSED_LABEL": "Suggestion dismissed"}});
		</script>    <script>
			yt.setConfig({'TIMING_ACTION': "glo",'TIMING_INFO': {"mod_li": 0, "mod_spf": 0, "e": "906378,925005,919359,910207,914061,916611,920704,912806,902000,919512,929901,913605,925006,906938,931202,931401,908529,930803,920201,930101,930603,906834,926403", "mod_lt": "cold"}});    
		</script>
		<script>yt.setConfig({'XSRF_TOKEN': "S0uwk0EgvxoAOX_v0c0U9_twFVh8MTM2MzI3MjU1OEAxMzYzMTg2MTU4",'XSRF_REDIRECT_TOKEN': "5MaT5zwJCAslCgglIiPwGx8NqXZ8MTM2MzIwMDU1OEAxMzYzMTg2MTU4",'XSRF_FIELD_NAME': "session_token"});</script><script>yt.setConfig('THUMB_DELAY_LOAD_BUFFER', 300);</script>    <script>
			if (window.yt.timing) {yt.timing.tick("js_foot");}    
		</script>
		<div id="debug"></div>
	</body>
</html>
