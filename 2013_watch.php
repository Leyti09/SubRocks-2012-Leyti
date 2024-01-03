<?php if(isset($_GET['about'])) { ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_about.php"); ?>
<?php } elseif(isset($_GET['channels'])) {
require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_channels.php"); ?>
<?php	} else{	?>
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
	$__server->page_embeds->page_title = "Betatube";
	$__server->page_embeds->page_description = "Share your videos with friends, family, and the world";
	$__server->page_embeds->page_url = "https://www.betatube.net/";
?>
<style>.ytp-scalable-icon-shrink {transform: scale(1) !important; -webkit-transform: scale(1) !important; -moz-transform: scale(1) !important; -o-transform: scale(1) !important; -ms-transform: scale(1) !important; }.ytp-scalable-icon-grow {transform: translate(-50%, -50%) scale(0.5) !important; -webkit-transform: translate(-50%, -50%) scale(0.5) !important; -moz-transform: translate(-50%, -50%) scale(0.5) !important; -o-transform: translate(-50%, -50%) scale(0.5) !important; -ms-transform: translate(-50%, -50%) scale(0.5) !important; }</style>
<html lang="en">
	<head>
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
		<script>
			var yt = yt || {};yt.timing = yt.timing || {};yt.timing.data_ = yt.timing.data_ || {};yt.timing.tick = function(label, opt_time) {var tick = yt.timing.data_['tick'] || {};tick[label] = opt_time || new Date().getTime();yt.timing.data_['tick'] = tick;};yt.timing.info = function(label, value) {var info = yt.timing.data_['info'] || {};info[label] = value;yt.timing.data_['info'] = info;};yt.timing.reset = function() {yt.timing.data_ = {};};if (document.webkitVisibilityState == 'prerender') {yt.timing.info('prerender', 1);document.addEventListener('webkitvisibilitychange', function() {yt.timing.tick('start');}, false);}yt.timing.tick('start');try {var externalPt = (window.gtbExternal && window.gtbExternal.pageT() ||window.external && window.external.pageT);if (externalPt) {yt.timing.info('pt', externalPt);}} catch(e) {}if (window.chrome && window.chrome.csi) {yt.timing.info('pt', Math.floor(window.chrome.csi().pageT));}    
		</script>
		<title>Betatube - <?php if($_user['title'])	{	?>
						<?php echo htmlspecialchars($_user['title']); ?>
						<?php } else {	?>
						<?php echo htmlspecialchars($_user['username']); ?>
						<?php	}	?></title>
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
        <title>Betatube - <?php if($_user['title'])	{	?>
						<?php echo htmlspecialchars($_user['title']); ?>
						<?php } else {	?>
						<?php echo htmlspecialchars($_user['username']); ?>
						<?php	}	?></title>
		<meta property="og:title" content="Betatube - <?php if($_user['title'])	{	?>
						<?php echo htmlspecialchars($_user['title']); ?>
						<?php } else {	?>
						<?php echo htmlspecialchars($_user['username']); ?>
						<?php	}	?>" />
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
		<link id="css-3576209200" class="www-channels4" rel="stylesheet" href="/yts/cssbin/www-channels4-vflQKV14P1.css" data-loaded="true">
		<!-- begin page -->
<div id="content" class="">  <div id="watch7-container" class="  transition-content  " itemscope="" itemid="" itemtype="http://schema.org/VideoObject">
        
    
    
    

      
      

      
      

        

    
    

        
      
      
      

      
      


      <div id="player" class="">
        
  <div id="playlist">
    
  </div>
  <div id="player-unavailable" class="    hid  ">
    
  </div>

  <script>if (window.ytcsi) {ytcsi.tick("bf");}</script>

      <div id="--wm--player-api" class="player-width player-height off-screen-target wm-videoplayer">      
  

</div>


    <script>var ytplayer = ytplayer || {};ytplayer.config = {"url_v8": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/cps-vflJgI5ZP.swf", "url_v9as2": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/cps-vflJgI5ZP.swf", "sts": 1588, "assets": {"js": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/jsbin\/html5player-vfl_ymO4Z.js", "css": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/cssbin\/www-player-vflKROa5W.css", "html": "\/html5_player_template"}, "attrs": {"id": "movie_player"}, "url": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/watch_as3-vflNr3l6D.swf", "html5": false, "min_version": "8.0.0", "args": {"fmt_list": "45\/1280x720\/99\/0\/0,22\/1280x720\/9\/0\/115,44\/854x480\/99\/0\/0,35\/854x480\/9\/0\/115,43\/640x360\/99\/0\/0,34\/640x360\/9\/0\/115,18\/640x360\/9\/0\/115,5\/320x240\/7\/0\/0,36\/320x240\/99\/0\/0,17\/176x144\/99\/0\/0", "yt_pt": "AL4CsgHeGdLhgRQhMIbQmTSgEH7VakshpiMPYeb7iGnFKurFk0PVIRCfeWKFMWwxMqX4sptIIw6rlosV5dOgaMWGdpOP4w9Ei6qFmXt4tcZJBmOA6XbYVKIxy1zRIR2njqOGP6kX-mXeqv2-8eHO", "ptk": "youtube_multi", "ad_slots": "0", "playlist_module": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/playlist_module-vflB-GYEh.swf", "ad_host": "ca-host-pub-1800120140230655", "ad_logging_flag": 1, "storyboard_spec": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/i1.ytimg.com\/sb\/t7UlrHOF3eg\/storyboard3_L$L\/$N.jpg|48#27#100#10#10#0#default#JdriqrCAl1zQTBWgKl4akD3LQBI|80#45#117#10#10#5000#M$M#j8vBGJpzmYeMsqCpjCFr5369NSk|160#90#117#5#5#5000#M$M#oMDphAwG4GziODGmOpDmqPgHfWY", "abd": "1", "ad_flags": 1, "video_id": "t7UlrHOF3eg", "watermark": ",http:\/\/s.ytimg.com\/yts\/img\/watermark\/youtube_watermark-vflHX6b6E.png,http:\/\/s.ytimg.com\/yts\/img\/watermark\/youtube_hd_watermark-vflAzLcD6.png", "cbr": "Firefox", "t": "vjVQa1PpcFMk70XNU0StlTccuKRn02qXCXYUy5_5sGc=", "afv": true, "ucid": "UCj5i58mCkAREDqFWlhaQbOw", "adsense_video_doc_id": "yt_t7UlrHOF3eg", "mpvid": "AATgnHb2lfQ8M10f", "enablejsapi": 1, "account_playback_token": "2s-FYaPrqU6SYz3xT5GHMZ1lFJ98MTM3Mjk0ODkyMkAxMzcyODYyNTIy", "afv_ad_tag": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/googleads.g.doubleclick.net\/pagead\/ads?ht_id=389538\u0026ad_type=text_image_flash\u0026hl=en\u0026channel=invideo_overlay_480x70_cat20%2Bafv_overlay%2BVertical_3%2BVertical_8%2BVertical_36%2BVertical_41%2BVertical_211%2BVertical_613%2Bafv_ugc%2Byt_mpvid_AATgnHb2lfQ8M10f%2Byt_cid_2569964%2Bytdevice_1%2Bytps_default%2Bytel_detailpage\u0026client=ca-pub-6219811747049371\u0026loeid=907724%2C901802%2C913574%2C921055%2C916626\u0026ytdevice=1\u0026host=ca-host-pub-1800120140230655\u0026description_url=http%3A%2F%2Fwww.youtube.com%2Fvideo%2Ft7UlrHOF3eg\u0026yt_pt=AL4CsgHeGdLhgRQhMIbQmTSgEH7VakshpiMPYeb7iGnFKurFk0PVIRCfeWKFMWwxMqX4sptIIw6rlosV5dOgaMWGdpOP4w9Ei6qFmXt4tcZJBmOA6XbYVKIxy1zRIR2njqOGP6kX-mXeqv2-8eHO", "idpj": "-9", "no_get_video_log": "1", "ad_video_pub_id": "ca-pub-6219811747049371", "ttsurl": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/www.youtube.com\/api\/timedtext?asr_langs=pt%2Cru%2Cfr%2Cnl%2Ces%2Cen%2Cja%2Cde%2Cko%2Cit\u0026expire=1372887722\u0026hl=en_US\u0026key=yttt1\u0026signature=C6C01C5BECF5FE922C3631657A36344BDB17BFD4.3CE6822334A40C10969D8249BB71D2B33C28A114\u0026sparams=asr_langs%2Ccaps%2Cv%2Cexpire\u0026caps=asr\u0026v=t7UlrHOF3eg", "tk": "1", "plid": "AATgnHb2VzBRR7Fs", "cafe_experiment_id": "40210013", "oid": "LtuniTgT0PFayH9n3PW4_A.ZP6trPnP707Uw7I8kjT4Eg", "rvs": "author=TheBajanCanadian\u0026id=0xX9GC5DRKA\u0026view_count=314%2C557\u0026length_seconds=1580\u0026title=Minecraft%3A+Hunger+Games+w%2FMitch%21+Game+203+-+%23BurgerCheese,author=stampylonghead\u0026id=OE4KsU7fGZs\u0026view_count=77%2C377\u0026length_seconds=1232\u0026title=Minecraft+Xbox+-+The+Big+Dog+%5B97%5D,author=stampylonghead\u0026id=lumqmbY8Fts\u0026view_count=9%2C159\u0026length_seconds=1231\u0026title=Terraria+Xbox+-+Bound+Goblin+%5B24%5D,author=stampylonghead\u0026id=Hsce1J3Z3fA\u0026view_count=38%2C312\u0026length_seconds=1228\u0026title=Minecraft+Xbox+-+Green+Fingers+%5B98%5D,author=stampylonghead\u0026id=t3NXG7pXurg\u0026view_count=7%2C905\u0026length_seconds=1175\u0026title=Ni+No+Kuni%3A+Wrath+Of+The+White+Witch+-+Cheese+Please+%5B31%5D,author=stampylonghead\u0026id=lfx0JPga73U\u0026view_count=41%2C790\u0026length_seconds=1331\u0026title=Minecraft+Xbox+-+Quest+To+Kill+The+Ender+Dragon+-+Memory+Lane+-+Part+20,author=SkyDoesMinecraft\u0026id=wCJFuXYxhHU\u0026view_count=1%2C636%2C411\u0026length_seconds=1537\u0026title=Minecraft+Mini-Game+%3A+INFINITE+CREEPER+MAZE%21,author=stampylonghead\u0026id=ux8EyVBRkBA\u0026view_count=1%2C181%2C010\u0026length_seconds=1256\u0026title=Minecraft+Xbox+-+Massive+Cruise+Ship,author=SkyDoesMinecraft\u0026id=OdVFumWXzxk\u0026view_count=1%2C738%2C339\u0026length_seconds=812\u0026title=Minecraft+Mini-Game+%3A+SUMO%21,author=SkyDoesMinecraft\u0026id=vOerqifVYKQ\u0026view_count=1%2C821%2C323\u0026length_seconds=791\u0026title=Minecraft+Mini-Game+%3A+TEACHER%21,author=SkyDoesMinecraft\u0026id=nWrf2A6RnZY\u0026view_count=903%2C925\u0026length_seconds=750\u0026title=Minecraft+Hunger+Games+%3A+OPERATION+PROTECT+WARDEN+FREEMAN%21,author=SkyDoesMinecraft\u0026id=0W43dW01IDI\u0026view_count=1%2C199%2C385\u0026length_seconds=680\u0026title=Minecraft+Mini-Game+%3A+JENGA%21", "host_language": "en", "inactive_skippable_threshold": 600000, "ad_eurl": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/www.youtube.com\/video\/t7UlrHOF3eg", "invideo": true, "share_icons": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/sharing-vflF4tO1T.swf", "title": "Minecraft Xbox - Huge Cruise Ship Fly Over - Prestige Sur La Mer - Part 3", "sffb": true, "sk": "kb2dauO3yCxp8QpUpo1HnbCurBTBhe-DC", "hl": "en_US", "endscreen_module": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/endscreen-vflQhqnfl.swf", "shortform": true, "enablecsi": "1", "ad_device": 1, "url_encoded_fmt_stream_map": "sig=819668BFBD38F19C9F7A3DEF8B1A7302EEB6119E.48BD3E28434D257E12A41E814A32914E5E271202\u0026itag=45\u0026fallback_host=tc.v7.cache4.c.youtube.com\u0026quality=hd720\u0026type=video%2Fwebm%3B+codecs%3D%22vp8.0%2C+vorbis%22\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D45%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26ratebypass%3Dyes%26ipbits%3D8%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=CB817B9C40EB892CB69DCFEF8D1723F610D7D71C.1CF766991FB41AC8A00A5EF256B88004E5A03784\u0026itag=22\u0026fallback_host=tc.v14.cache3.c.youtube.com\u0026quality=hd720\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.64001F%2C+mp4a.40.2%22\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D22%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26ratebypass%3Dyes%26ipbits%3D8%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=4ADB3D425EC395945794A21E4646236F9BC52BAB.3E8007D7EA1A876A0C0ADBA902025DBD93F8ED1B\u0026itag=44\u0026fallback_host=tc.v3.cache7.c.youtube.com\u0026quality=large\u0026type=video%2Fwebm%3B+codecs%3D%22vp8.0%2C+vorbis%22\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D44%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26ratebypass%3Dyes%26ipbits%3D8%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=1A0EC80A05E15A9DD440E46158F2669F5BDBCD18.AE6A800BF09AAC24238550A720D6AAADEDC798E6\u0026itag=35\u0026fallback_host=tc.v15.cache4.c.youtube.com\u0026quality=large\u0026type=video%2Fx-flv\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D35%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26algorithm%3Dthrottle-factor%26burst%3D40%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26factor%3D1.25%26ipbits%3D8%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=7AD19795F9D9587340F1CB56E447F9A18E3ABF06.0F8D36397E923639B235D87A90694338F367AEDA\u0026itag=43\u0026fallback_host=tc.v20.cache4.c.youtube.com\u0026quality=medium\u0026type=video%2Fwebm%3B+codecs%3D%22vp8.0%2C+vorbis%22\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D43%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26ratebypass%3Dyes%26ipbits%3D8%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=7AA62B4AA9BE89B8CDCCF40DE023B88CCA6EA280.9899293E72B4E9D68F2FE34F50069BE3A6FF7159\u0026itag=34\u0026fallback_host=tc.v9.cache6.c.youtube.com\u0026quality=medium\u0026type=video%2Fx-flv\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D34%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26algorithm%3Dthrottle-factor%26burst%3D40%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26factor%3D1.25%26ipbits%3D8%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=36188FF1A9EC445D2757919C0F41C9C51C23ABEF.A8327E620C6CD371228DBB9332FA4B3398DFF845\u0026itag=18\u0026fallback_host=tc.v21.cache8.c.youtube.com\u0026quality=medium\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.42001E%2C+mp4a.40.2%22\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D18%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26ratebypass%3Dyes%26ipbits%3D8%26sparams%3Dcp%252Cid%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=7CAC236835C2C94A2777C4D659A29AA06777C0E2.532DB5A90867C077BC4B2953B4C36431991EBF16\u0026itag=5\u0026fallback_host=tc.v6.cache3.c.youtube.com\u0026quality=small\u0026type=video%2Fx-flv\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D5%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26algorithm%3Dthrottle-factor%26burst%3D40%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26factor%3D1.25%26ipbits%3D8%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=184A094F41BB9AA073D0EF3CE40BADF7B7EC0935.6EFB6934AF317023C63C5C59D7EE0281F79AA20D\u0026itag=36\u0026fallback_host=tc.v20.cache1.c.youtube.com\u0026quality=small\u0026type=video%2F3gpp%3B+codecs%3D%22mp4v.20.3%2C+mp4a.40.2%22\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D36%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26algorithm%3Dthrottle-factor%26burst%3D40%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26factor%3D1.25%26ipbits%3D8%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire%26source%3Dyoutube,sig=2A42673FD66B456D7791D6305905ACDA4C3AAAD4.4A675872138D2B0B9C1247EAC591E47339D97ACD\u0026itag=17\u0026fallback_host=tc.v5.cache8.c.youtube.com\u0026quality=small\u0026type=video%2F3gpp%3B+codecs%3D%22mp4v.20.3%2C+mp4a.40.2%22\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fms%3Dau%26id%3Db7b525ac7385dde8%26itag%3D17%26expire%3D1372885971%26mt%3D1372862501%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26newshard%3Dyes%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DE-eW8_EmIqk%26sver%3D3%26algorithm%3Dthrottle-factor%26burst%3D40%26mv%3Dm%26key%3Dyt1%26ip%3D207.241.237.185%26factor%3D1.25%26ipbits%3D8%26sparams%3Dalgorithm%252Cburst%252Ccp%252Cfactor%252Cid%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire%26source%3Dyoutube", "ad3_module": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/ad3-vfldH0DrD.swf", "tmi": "1", "keywords": "Minecraft (Video Game),Xbox 360 (Video Game Platform),Video Game (Industry),World,Map,Tour,Level,Online,Download,Mega,Build,Cruise Ship,Boat,Fly Over,Huge,Best,Most,New,Today,2013,Stampy,Danger Fip,Big,Massive,Amazing,Edition,Version,Console", "pltype": "contentugc", "as_launched_in_country": "1", "thumbnail_num_shards": 1, "cc_module": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/subtitle_module-vflE78TU5.swf", "c": "WEB", "referrer": null, "fexp": "907724,901802,913574,921055,916626,909546,906397,928201,929117,929123,929121,929915,929906,929907,929125,929127,925714,929917,929919,931202,912512,912515,912521,906838,904485,906840,931913,904830,919373,933701,904122,932216,908534,926403,909421,912711,907228", "is_html5_mobile_device": false, "length_seconds": 579, "autohide": "2", "ad_host_tier": 389538, "vq": "auto", "pyv_in_related_cafe_experiment_id": "40210015", "cbrver": "4.0b11,gzip(gfe", "cr": "US", "cid": 2569964, "ldpj": "-26", "dashmpd": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/www.youtube.com\/api\/manifest\/dash\/id\/b7b525ac7385dde8\/expire\/1372885971\/signature\/8270012D81ED8B9A525BFCFC715A8F8C61E8A366.13F135A94F8A6FD92769898071DB8C3525B5EE24\/cp\/U0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY\/as\/fmp4_audio_clear%2Cwebm_audio_clear%2Cfmp4_sd_hd_clear%2Cwebm_sd_hd_clear\/fexp\/907724%2C901802%2C913574%2C921055%2C916626%2C909546%2C906397%2C928201%2C929117%2C929123%2C929121%2C929915%2C929906%2C929907%2C929125%2C929127%2C925714%2C929917%2C929919%2C931202%2C912512%2C912515%2C912521%2C906838%2C904485%2C906840%2C931913%2C904830%2C919373%2C933701%2C904122%2C932216%2C908534%2C926403%2C909421%2C912711%2C907228\/upn\/Ez6PWcl_aA0\/sver\/3\/key\/yt1\/ip\/207.241.237.185\/ipbits\/8\/sparams\/as%2Ccp%2Cid%2Cip%2Cipbits%2Csource%2Cexpire\/source\/youtube", "loudness": -24.7490005493, "adaptive_fmts": "init=0-707\u0026itag=136\u0026bitrate=2254395\u0026size=1280x720\u0026index=708-2131\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fclen%3D156322344%26itag%3D136%26ip%3D207.241.237.185%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DRwmxt8PdPuQ%26algorithm%3Dthrottle-factor%26burst%3D40%26gir%3Dyes%26key%3Dyt1%26ipbits%3D8%26lmt%3D1372553226929604%26sparams%3Dalgorithm%252Cburst%252Cclen%252Ccp%252Cfactor%252Cgir%252Cid%252Cip%252Cipbits%252Citag%252Clmt%252Csource%252Cupn%252Cexpire%26ms%3Dau%26id%3Db7b525ac7385dde8%26mv%3Dm%26mt%3D1372862501%26sver%3D3%26expire%3D1372885971%26newshard%3Dyes%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26factor%3D1.25%26source%3Dyoutube%26signature%3D0A998D2E6705796E86FD41181FA184493D3B0D2B.2ABC75790ABB0821C1E388A52EAF2624AD3D42F8\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.4d401f%22,init=0-707\u0026itag=135\u0026bitrate=1119619\u0026size=854x480\u0026index=708-2131\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fclen%3D76439432%26itag%3D135%26ip%3D207.241.237.185%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DRwmxt8PdPuQ%26algorithm%3Dthrottle-factor%26burst%3D40%26gir%3Dyes%26key%3Dyt1%26ipbits%3D8%26lmt%3D1372553212649535%26sparams%3Dalgorithm%252Cburst%252Cclen%252Ccp%252Cfactor%252Cgir%252Cid%252Cip%252Cipbits%252Citag%252Clmt%252Csource%252Cupn%252Cexpire%26ms%3Dau%26id%3Db7b525ac7385dde8%26mv%3Dm%26mt%3D1372862501%26sver%3D3%26expire%3D1372885971%26newshard%3Dyes%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26factor%3D1.25%26source%3Dyoutube%26signature%3D78B2F1374613C49FAF426E6FD49CE28D22B12A4C.9F80092F69EC0752FF05C87B8A7E2FCE0DF0F952\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.4d401f%22,init=0-707\u0026itag=134\u0026bitrate=478981\u0026size=640x360\u0026index=708-2131\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fclen%3D32871421%26itag%3D134%26ip%3D207.241.237.185%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DRwmxt8PdPuQ%26algorithm%3Dthrottle-factor%26burst%3D40%26gir%3Dyes%26key%3Dyt1%26ipbits%3D8%26lmt%3D1372553174528693%26sparams%3Dalgorithm%252Cburst%252Cclen%252Ccp%252Cfactor%252Cgir%252Cid%252Cip%252Cipbits%252Citag%252Clmt%252Csource%252Cupn%252Cexpire%26ms%3Dau%26id%3Db7b525ac7385dde8%26mv%3Dm%26mt%3D1372862501%26sver%3D3%26expire%3D1372885971%26newshard%3Dyes%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26factor%3D1.25%26source%3Dyoutube%26signature%3D1B8264BEA732DF908F7AADE785C116FB0FF6F809.3E0DB21DD81D4554A6061195C8B67B969F1EF408\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.4d401e%22,init=0-671\u0026itag=133\u0026bitrate=254590\u0026size=426x240\u0026index=672-2095\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fclen%3D17711276%26itag%3D133%26ip%3D207.241.237.185%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DRwmxt8PdPuQ%26algorithm%3Dthrottle-factor%26burst%3D40%26gir%3Dyes%26key%3Dyt1%26ipbits%3D8%26lmt%3D1372553149070181%26sparams%3Dalgorithm%252Cburst%252Cclen%252Ccp%252Cfactor%252Cgir%252Cid%252Cip%252Cipbits%252Citag%252Clmt%252Csource%252Cupn%252Cexpire%26ms%3Dau%26id%3Db7b525ac7385dde8%26mv%3Dm%26mt%3D1372862501%26sver%3D3%26expire%3D1372885971%26newshard%3Dyes%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26factor%3D1.25%26source%3Dyoutube%26signature%3D21432746CB0E28CF220818864D38668DB65CE9F5.CFABB191DCADB0AEA6304AC704B370A591A81EB5\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.4d4015%22,init=0-670\u0026itag=160\u0026bitrate=118073\u0026size=256x144\u0026index=671-2094\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fclen%3D8134868%26itag%3D160%26ip%3D207.241.237.185%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DRwmxt8PdPuQ%26algorithm%3Dthrottle-factor%26burst%3D40%26gir%3Dyes%26key%3Dyt1%26ipbits%3D8%26lmt%3D1372553003757521%26sparams%3Dalgorithm%252Cburst%252Cclen%252Ccp%252Cfactor%252Cgir%252Cid%252Cip%252Cipbits%252Citag%252Clmt%252Csource%252Cupn%252Cexpire%26ms%3Dau%26id%3Db7b525ac7385dde8%26mv%3Dm%26mt%3D1372862501%26sver%3D3%26expire%3D1372885971%26newshard%3Dyes%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26factor%3D1.25%26source%3Dyoutube%26signature%3D9690D578BB3A479B82C51755C37523F26EAFFC77.3E78A1F7CF1E8DD1231CAFB2B5044B0213C5D5BE\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.42c00c%22,init=0-592\u0026itag=139\u0026bitrate=48972\u0026index=593-1320\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fclen%3D3442691%26itag%3D139%26ip%3D207.241.237.185%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DRwmxt8PdPuQ%26algorithm%3Dthrottle-factor%26burst%3D40%26gir%3Dyes%26key%3Dyt1%26ipbits%3D8%26lmt%3D1372553085704330%26sparams%3Dalgorithm%252Cburst%252Cclen%252Ccp%252Cfactor%252Cgir%252Cid%252Cip%252Cipbits%252Citag%252Clmt%252Csource%252Cupn%252Cexpire%26ms%3Dau%26id%3Db7b525ac7385dde8%26mv%3Dm%26mt%3D1372862501%26sver%3D3%26expire%3D1372885971%26newshard%3Dyes%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26factor%3D1.25%26source%3Dyoutube%26signature%3D4B4C7084E0764F59186A83AC58BA8A0CD0DA794C.0747983C66324363A80AD73951DD5DB1256CA45F\u0026type=audio%2Fmp4%3B+codecs%3D%22mp4a.40.5%22,init=0-591\u0026itag=140\u0026bitrate=128453\u0026index=592-1319\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fclen%3D9192462%26itag%3D140%26ip%3D207.241.237.185%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DRwmxt8PdPuQ%26algorithm%3Dthrottle-factor%26burst%3D40%26gir%3Dyes%26key%3Dyt1%26ipbits%3D8%26lmt%3D1372552905340768%26sparams%3Dalgorithm%252Cburst%252Cclen%252Ccp%252Cfactor%252Cgir%252Cid%252Cip%252Cipbits%252Citag%252Clmt%252Csource%252Cupn%252Cexpire%26ms%3Dau%26id%3Db7b525ac7385dde8%26mv%3Dm%26mt%3D1372862501%26sver%3D3%26expire%3D1372885971%26newshard%3Dyes%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26factor%3D1.25%26source%3Dyoutube%26signature%3D2E3BDF8B3911AC8D368C85B5BDEC17ED0421401E.22BC01418722783BD9CDE13D813C8F3E905C13F2\u0026type=audio%2Fmp4%3B+codecs%3D%22mp4a.40.2%22,init=0-591\u0026itag=141\u0026bitrate=256095\u0026index=592-1319\u0026url=http%3A%2F%2Fr14---sn-nwj7kned.c.youtube.com%2Fvideoplayback%3Fclen%3D18452580%26itag%3D141%26ip%3D207.241.237.185%26fexp%3D907724%252C901802%252C913574%252C921055%252C916626%252C909546%252C906397%252C928201%252C929117%252C929123%252C929121%252C929915%252C929906%252C929907%252C929125%252C929127%252C925714%252C929917%252C929919%252C931202%252C912512%252C912515%252C912521%252C906838%252C904485%252C906840%252C931913%252C904830%252C919373%252C933701%252C904122%252C932216%252C908534%252C926403%252C909421%252C912711%252C907228%26upn%3DRwmxt8PdPuQ%26algorithm%3Dthrottle-factor%26burst%3D40%26gir%3Dyes%26key%3Dyt1%26ipbits%3D8%26lmt%3D1372552845034324%26sparams%3Dalgorithm%252Cburst%252Cclen%252Ccp%252Cfactor%252Cgir%252Cid%252Cip%252Cipbits%252Citag%252Clmt%252Csource%252Cupn%252Cexpire%26ms%3Dau%26id%3Db7b525ac7385dde8%26mv%3Dm%26mt%3D1372862501%26sver%3D3%26expire%3D1372885971%26newshard%3Dyes%26cp%3DU0hWR1hTU19MS0NONl9QTVdKOmRKV0NLV09TXzNY%26factor%3D1.25%26source%3Dyoutube%26signature%3D86D6E9E36A8D3A00A9ECE17F6292CB19639C08E1.C78EAC9A9F19842A4FC2A181197A95782BB06A31\u0026type=audio%2Fmp4%3B+codecs%3D%22mp4a.40.2%22", "no_afv_instream": "1", "mpu": true, "cc3_module": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/s.ytimg.com\/yts\/swfbin\/subtitles3_module-vflnYRM8r.swf", "cos": "X11", "ad_tag": "https:\/\/web.archive.org\/web\/20130703144227\/http:\/\/ad.doubleclick.net\/N4061\/pfadx\/com.ytpwatch.gadgetsandgames\/main_2569964;sz=WIDTHxHEIGHT;kvid=t7UlrHOF3eg;kpu=stampylonghead;kpeid=j5i58mCkAREDqFWlhaQbOw;kpid=2569964;u=t7UlrHOF3eg|2569964;mpvid=AATgnHb2lfQ8M10f;plat=pc;afct=site_content;afv=1;k5=3_8_36_41_211_613;kclg=1;kclt=1;kcr=us;kga=-1;kgg=-1;klg=en;ko=c;kpco=582411;kr=N;kvz=203;nlfb=1;shortform=1;tves=2;ytcat=20;ytdevice=1;ytexp=907724,901802,913574,921055,916626;ytps=default;ytvt=w;!c=2569964;k2=3;k2=8;k2=36;k2=41;k2=211;k2=613;kvlg=en;", "prefetch_ad_live_stream": true, "cc_asr": 1, "showpopout": 1, "allow_embed": 1, "timestamp": 1372862522, "dash": "1", "gut_tag": "\/4061\/ytpwatch\/main_2569964", "ad_channel_code_overlay": "invideo_overlay_480x70_cat20,afv_overlay,Vertical_3,Vertical_8,Vertical_36,Vertical_41,Vertical_211,Vertical_613,afv_ugc,yt_mpvid_AATgnHb2lfQ8M10f,yt_cid_2569964,ytdevice_1,ytps_default,ytel_detailpage", "sw": "0.1", "loeid": "907724,901802,913574,921055,916626", "cc_font": "Arial Unicode MS, arial, verdana, _sans", "dclk": true, "csi_page_type": "watch,watch7ad"}, "params": {"allowfullscreen": "true", "allowscriptaccess": "always", "bgcolor": "#000000"}};</script>    <script>
      (function() {
        var encoded = [];
        for (var key in ytplayer.config.args) {
          encoded.push(encodeURIComponent(key) + '=' + encodeURIComponent(ytplayer.config.args[key]));
        }
        var swf = "      \u003cembed type=\"application\/x-shockwave-flash\"     s\u0072c=\"http:\/\/s.ytimg.com\/yts\/swfbin\/watch_as3-vflNr3l6D.swf\"     name=\"movie_player\"     id=\"movie_player\"    flashvars=\"__flashvars__\"     allowfullscreen=\"true\" allowscriptaccess=\"always\" bgcolor=\"#000000\"\u003e\n  \u003cnoembed\u003e\u003cdiv class=\"yt-alert yt-alert-default yt-alert-error  yt-alert-player\"\u003e  \u003cdiv class=\"yt-alert-icon\"\u003e\n    \u003cimg s\u0072c=\"\/\/s.ytimg.com\/yts\/img\/pixel-vfl3z5WfW.gif\" class=\"icon master-sprite\" alt=\"Alert icon\"\u003e\n  \u003c\/div\u003e\n\u003cdiv class=\"yt-alert-buttons\"\u003e\u003c\/div\u003e\u003cdiv class=\"yt-alert-content\" role=\"alert\"\u003e    \u003cspan class=\"yt-alert-vertical-trick\"\u003e\u003c\/span\u003e\n    \u003cdiv class=\"yt-alert-message\"\u003e\n            You need Adobe Flash Player to watch this video. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eDownload it from Adobe.\u003c\/a\u003e\n    \u003c\/div\u003e\n\u003c\/div\u003e\u003c\/div\u003e\u003c\/noembed\u003e\n\n";
        swf = swf.replace('__flashvars__', encoded.join('&'));
        document.getElementById("player-api").innerHTML = swf;
        ytplayer.config.loaded = true
      })();
    </script>


  

  <div id="player-branded-banner">
    
  </div>

      </div>

    <div id="watch7-main-container">
      <div id="watch7-main" class="clearfix">
        <div id="watch7-content" class="watch-content">
                <div class="yt-uix-button-panel">
        <div id="watch7-headline" class="clearfix  yt-uix-expander yt-uix-expander-collapsed">
    <h1 id="watch-headline-title" class="yt">
      


  


  <span id="eow-title" class="watch-title long-title yt-uix-expander-head" dir="ltr" title="Minecraft Xbox - Huge Cruise Ship Fly Over - Prestige Sur La Mer - Part 3">
    Minecraft Xbox - Huge Cruise Ship Fly Over - Prestige Sur La Mer - Part 3
  </span>

    </h1>
  </div>

      <div id="watch7-user-header"><a href="/web/20130703144227/http://www.youtube.com/user/stampylonghead?feature=watch" class="yt-user-photo ">    <span class="video-thumb  yt-thumb yt-thumb-48">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="stampylonghead" src="//web.archive.org/web/20130703144227im_/http://i1.ytimg.com/i/j5i58mCkAREDqFWlhaQbOw/1.jpg?v=51ae3a39" width="48">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
</a><a href="/web/20130703144227/http://www.youtube.com/user/stampylonghead?feature=watch" class="yt-uix-sessionlink yt-user-name " data-sessionlink="feature=watch&amp;ei=OjjUUa_0DM3hggLZtoHADw" dir="ltr">stampylonghead</a><span class="yt-user-separator">·</span><a class="yt-uix-sessionlink yt-user-videos" href="/web/20130703144227/http://www.youtube.com/user/stampylonghead/videos" data-sessionlink="feature=watch&amp;ei=OjjUUa_0DM3hggLZtoHADw">431 videos</a><br><span id="watch7-subscription-container"><span class=" yt-uix-button-subscription-container with-preferences"><button aria-role="button" aria-busy="false" type="button" onclick=";return false;" aria-live="polite" class="yt-uix-subscription-button yt-uix-button yt-uix-button-subscribe-branded" data-channel-external-id="UCj5i58mCkAREDqFWlhaQbOw" data-style-type="branded" data-sessionlink="feature=watch&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CBcQmys" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;uilel=3&amp;hl=en_US&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26continue_action%3DQUFFLUhqbGlyZHN6Y19hTkpUaXRaU2E1ME5kVXVjS0l1Z3xBQ3Jtc0tuMDJrQTFNcm1YM1F3R2xMdWt0YTVNNFJ6emYtVE1NaUplYW5YUGVQcS05enRyZDZkV0pWekVfTVhNNnNRZUhKZUtTTmhTVi04R0RTYXV1eDJXQzQ5WjVFWDJQckQ0OWZGd01EOEdZeGxHdlRmMmpKWFRxNW1YVlNJRWRfMjBoMGtXNnVvcUR6dE5zdjkxN1FyVGM3YWJHbzBraUxUcWk4cmJsNGY5S3dpci01bkFObWdKVW42M0NLZm1FcmxRQ29BenQ0QUg%253D%26feature%3Dsubscribe%26hl%3Den_US%26next%3D%252Fchannel%252FUCj5i58mCkAREDqFWlhaQbOw%26nomobiletemp%3D1&amp;service=youtube" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-subscribe" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
    <span class="yt-uix-button-content">
<span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> 
    </span>
</button><button class="yt-uix-subscription-preferences-button yt-uix-button yt-uix-button-default yt-uix-button-empty" type="button" onclick=";return false;" data-channel-external-id="UCj5i58mCkAREDqFWlhaQbOw" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-subscription-preferences" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
</button><span class="yt-subscription-button-subscriber-count-branded-horizontal">72,739</span>  <span class="yt-subscription-button-disabled-mask" title=""></span>
  
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
      <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

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

</span></span><div id="watch7-views-info">      <span class="watch-view-count ">
    14,098
  </span>

    <div class="video-extras-sparkbars">
    <div class="video-extras-sparkbar-likes" style="width: 97.3360655738%"></div>
    <div class="video-extras-sparkbar-dislikes" style="width: 2.66393442623%"></div>
  </div>
  <span class="video-extras-likes-dislikes">
      <img class="icon-watch-stats-like" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Like">
  <span class="likes-count">475</span>
 &nbsp;&nbsp;&nbsp;   <img class="icon-watch-stats-dislike" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Dislike">
  <span class="dislikes-count">13</span>

  </span>

</div></div>
        <div id="watch7-action-buttons" class="clearfix">
    <div id="watch7-sentiment-actions">
      <span id="watch-like-dislike-buttons" class="yt-uix-button-group " data-vote-state="2" data-button-toggle-group="optional"><span class="yt-uix-clickcard"><button id="watch-like" type="button" onclick=";return false;" class="yt-uix-clickcard-target yt-uix-button yt-uix-button-text yt-uix-tooltip" title="" data-unlike-tooltip="Unlike" data-position="bottomright" data-like-tooltip="I like this" data-button-toggle="true" data-orientation="vertical" data-force-position="true" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-watch-like" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
    <span class="yt-uix-button-content">
Like 
    </span>
</button>  <div class="watch7-hovercard yt-uix-clickcard-content">
    <h3 class="watch7-hovercard-header">Sign in to YouTube</h3>
    <div class="watch7-hovercard-message">
      Sign in with your Google Account (YouTube, Google+, Gmail, Orkut, Picasa, or Chrome) to like <span class="yt-user-name " dir="ltr">stampylonghead</span>'s video.

    </div>
    <ul class="watch7-hovercard-icon-strip clearfix">
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-youtube-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gplus-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gmail-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-picasa-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-chrome-icon"></div>
      </li>
    </ul>
    <div class="watch7-hovercard-account-line">
      <a href="https://web.archive.org/web/20130703144227/https://accounts.google.com/ServiceLogin?passive=true&amp;uilel=3&amp;hl=en_US&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3D__FEATURE__%26hl%3Den_US%26next%3D%252Fwatch%253Fv%253Dt7UlrHOF3eg%2526gl%253DUS%2526hl%253Den%26nomobiletemp%3D1&amp;service=youtube" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-primary" data-sessionlink="ei=OjjUUa_0DM3hggLZtoHADw"><span class="yt-uix-button-content">Sign in</span></a>
    </div>
  </div>
</span><span class="yt-uix-clickcard"><button id="watch-dislike" type="button" onclick=";return false;" class="yt-uix-clickcard-target yt-uix-button yt-uix-button-text yt-uix-tooltip yt-uix-button-empty" title="I dislike this" data-button-toggle="true" data-position="bottomright" data-orientation="vertical" data-force-position="true" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-watch-dislike" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="I dislike this" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
</button>  <div class="watch7-hovercard yt-uix-clickcard-content">
    <h3 class="watch7-hovercard-header">Sign in to YouTube</h3>
    <div class="watch7-hovercard-message">
      Sign in with your Google Account (YouTube, Google+, Gmail, Orkut, Picasa, or Chrome) to dislike <span class="yt-user-name " dir="ltr">stampylonghead</span>'s video.

    </div>
    <ul class="watch7-hovercard-icon-strip clearfix">
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-youtube-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gplus-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gmail-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-picasa-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-chrome-icon"></div>
      </li>
    </ul>
    <div class="watch7-hovercard-account-line">
      <a href="https://web.archive.org/web/20130703144227/https://accounts.google.com/ServiceLogin?passive=true&amp;uilel=3&amp;hl=en_US&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3D__FEATURE__%26hl%3Den_US%26next%3D%252Fwatch%253Fv%253Dt7UlrHOF3eg%2526gl%253DUS%2526hl%253Den%26nomobiletemp%3D1&amp;service=youtube" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-primary" data-sessionlink="ei=OjjUUa_0DM3hggLZtoHADw"><span class="yt-uix-button-content">Sign in</span></a>
    </div>
  </div>
</span></span>
    </div>
    <div id="watch7-secondary-actions" class="yt-uix-button-group" data-button-toggle-group="required">
        <span>
    <button class="action-panel-trigger  yt-uix-button-toggled yt-uix-button yt-uix-button-text yt-uix-tooltip" type="button" onclick=";return false;" title="" data-button-toggle="true" data-trigger-for="action-panel-details" role="button">    <span class="yt-uix-button-content">
About 
    </span>
</button>
  </span>

          <span>
    <button class="action-panel-trigger   yt-uix-button yt-uix-button-text yt-uix-tooltip" type="button" onclick=";return false;" title="" data-button-toggle="true" data-trigger-for="action-panel-share" role="button">    <span class="yt-uix-button-content">
Share 
    </span>
</button>
  </span>

          <span class="yt-uix-clickcard">
    <button class="action-panel-trigger   yt-uix-clickcard-target yt-uix-button yt-uix-button-text yt-uix-tooltip" type="button" onclick=";return false;" title="" data-button-toggle="true" data-position="bottomleft" data-trigger-for="action-panel-none" data-orientation="vertical" data-upsell="playlist" role="button">    <span class="yt-uix-button-content">
Add to 
    </span>
</button>
        <div class="watch7-hovercard yt-uix-clickcard-content">
    <h3 class="watch7-hovercard-header">Sign in to YouTube</h3>
    <div class="watch7-hovercard-message">
      Sign in with your Google Account (YouTube, Google+, Gmail, Orkut, Picasa, or Chrome) to add <span class="yt-user-name " dir="ltr">stampylonghead</span>'s video to your playlist.

    </div>
    <ul class="watch7-hovercard-icon-strip clearfix">
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-youtube-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gplus-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gmail-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-picasa-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-chrome-icon"></div>
      </li>
    </ul>
    <div class="watch7-hovercard-account-line">
      <a href="https://web.archive.org/web/20130703144227/https://accounts.google.com/ServiceLogin?passive=true&amp;uilel=3&amp;hl=en_US&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3D__FEATURE__%26hl%3Den_US%26next%3D%252Fwatch%253Fv%253Dt7UlrHOF3eg%2526gl%253DUS%2526hl%253Den%26nomobiletemp%3D1&amp;service=youtube" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-primary" data-sessionlink="ei=OjjUUa_0DM3hggLZtoHADw"><span class="yt-uix-button-content">Sign in</span></a>
    </div>
  </div>

  </span>

          <span>
    <button class="action-panel-trigger   yt-uix-button yt-uix-button-text yt-uix-tooltip yt-uix-button-empty" type="button" onclick=";return false;" title="Transcript" data-button-toggle="true" data-trigger-for="action-panel-transcript" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-action-panel-transcript" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Transcript" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
</button>
  </span>

            
  <span>
    <button disabled="True" type="button" onclick=";return false;" class="action-panel-trigger   yt-uix-button yt-uix-button-text yt-uix-tooltip yt-uix-button-empty" title="Stats have been disabled for this video" data-button-toggle="true" data-trigger-for="action-panel-stats" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-action-panel-stats" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Stats have been disabled for this video" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
</button>
  </span>


        <span>
    <button class="action-panel-trigger   yt-uix-button yt-uix-button-text yt-uix-tooltip yt-uix-button-empty" type="button" onclick=";return false;" title="Report" data-button-toggle="true" data-trigger-for="action-panel-report" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-action-panel-report" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Report" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
</button>
  </span>

    </div>
  </div>

        <div id="watch7-action-panels" class="yt-uix-button-panel">
      <div id="action-panel-details" class="action-panel-content ">
    <div id="watch-description" class="yt-uix-expander yt-uix-expander-collapsed yt-uix-button-panel">
      <div id="watch-description-content" class="click-to-buy">
        <div id="watch-description-clip">
          <p id="watch-uploader-info">
            <strong>Published on <span id="eow-date" class="watch-video-date">Jul  1, 2013</span>
</strong>
          </p>
          <div id="watch-description-text">
            <p id="eow-description">Welcome to my tour of the Prestige Sur La Mer cruise ship. This build was made entirely by Dang3r Fip.<br><br>All of Minecraft - <a href="https://web.archive.org/web/20130703144227/http://www.youtube.com/playlist?list=PL455493CB1440221D" target="_blank" title="http://www.youtube.com/playlist?list=PL455493CB1440221D" rel="nofollow" dir="ltr" class="yt-uix-redirect-link">http://www.youtube.com/playlist?list=...</a><br><br>My main channel - <a href="https://web.archive.org/web/20130703144227/http://www.youtube.com/stampylongnose" target="_blank" title="http://www.youtube.com/stampylongnose" rel="nofollow" dir="ltr" class="yt-uix-redirect-link">http://www.youtube.com/stampylongnose</a><br><br>Twitter - @stampylongnose<br><br>Facebook - www.facebook.com/stampylongnose<br><br>Podcast - <a href="https://web.archive.org/web/20130703144227/https://itunes.apple.com/gb/podcast/stampys-lovely-podcast/id590290102?mt=2" target="_blank" title="https://itunes.apple.com/gb/podcast/stampys-lovely-podcast/id590290102?mt=2" rel="nofollow" dir="ltr" class="yt-uix-redirect-link">https://itunes.apple.com/gb/podcast/s...</a><br><br>Email - stampylongnose@hotmail.co.uk</p>
          </div>
            <div id="watch-description-extras">
    <ul class="watch-extras-section">
      <li>
        <h4 class="title">
Category
        </h4>
        <div class="content">
              <p id="eow-category"><a href="/web/20130703144227/http://www.youtube.com/gaming">Gaming</a></p>

        </div>
      </li>


        <li>
          <h4 class="title">License</h4>
          <div class="content">
              <p id="eow-reuse">
Standard YouTube License
  </p>

          </div></li>
        
    </ul>
  </div>

        </div>
        <ul id="watch-description-extra-info">
          <li class=""><span class="metadata-info"><span class="metadata-info-title">Buy "Song For Jane" on<br></span></span><div class="offer-image-thumbnail"><a href="https://web.archive.org/web/20130703144227/http://www.youtube.com/cthru?c2b=googlemusic&amp;key=BckR-YPSyE_cETaVzaYgb0XByS88DxM9pr57xrFnZGZ8jcqjLvy42AyNe7XI7uV-IM2FCSAM8NihtsPP6_tRUjqU2ckPyKvn-o6_xLwIZ109N6SJ6sZaJCEI4vm43v4tUD8hhyU8pqtGbsnVLKEjaYwJt4rCegAen85Wz9odvG11S_nVwxUFbIGoeATrACPb9arOnKq-JQtAMVr10H7DjAzAqVnhoxwBf2MbNjTm5xut5JL_zdyTXwi-u1z7jmeMwmesNR-K0CVTv_1Ni2yblDLJijUaRDh7M95TEqOXtMo%3D&amp;v=t7UlrHOF3eg" onmousedown="yt.tracking.track('metadataBuyMusic');" rel="nofollow" target="_blank"><img data-thumb="https://web.archive.org/web/20130703144227/https://lh3.ggpht.com/Sa0w-riwvMRwR6E1ErTR76OncI2UYC5L1SWsfHbDs5S4Sb3mhAjVazNuY2GcRrlH_muamaua" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt=""></a></div><span class="link-list metadata-info offer-links-with-thumbnail"><a href="https://web.archive.org/web/20130703144227/http://www.youtube.com/cthru?c2b=googlemusic&amp;key=AonCt1l6Ud5sG4DfdJAvyTElFUs2PEmrt6yrFRSmTpejRkdaas4wwN24U-LVFWNw3rknAJYd-G1roJaqrsGr2tA3bUU9_t7koyXeL6l34xMKRYDm129jtDlOU0aTkdk69idSBtOG1b-8xyxDQYhilqqjkvz3stdNGPGDquIav4evb0uak5u6Z_sQSoF3qw9LWjhV2GA8uYzGdJ7KLmcAgEqyuzHDmLYbkxUC9pNCPUlBB0CjaJNjpj9jc3CpkZKPbd8vXk2kDnufPx9qV66XG_oyX4QK1BknhfXlFgYAzyY%3D&amp;v=t7UlrHOF3eg" class="nowrap" onmousedown="yt.tracking.track('metadataBuyMusic');" rel="nofollow" target="_blank">Google Play</a><a href="https://web.archive.org/web/20130703144227/http://www.youtube.com/cthru?c2b=itunes&amp;key=liPTnVBXsM7-_WEIBZ0K9U5kC6wMrK-wXUOmW96XKrfvdO9HbKWz8hDV-dpAuNEhRYHRK_tIHSkCTwVw8p6YS3CamUD2LRmN4QdZ_cmnUjzeHNlcQKLemy_RzHFkG_bCQyxv4g_Yf4VOJGZoFiV-18m5StznNg-zd0JfIIXgDz8grG9ZKnKBXx4cH802bJf-lPS499aveynyJ-_xBBhaiIuJdI8c9Oqo2slrCV8BnntiSVmE6SefSnnB0dbxHQexg4_5GoC1_6shqMdVnY2w3SWZ0dF32_j3qhmPcceaWXM566MmvG4-ZmMJmm-ouAWsDxfFWfEZM6wu9wOj_vLG9ZXl3hJDElkctM-u5a2UZ93CtiCfJrn2Wo5a0aaThChExFWmBGowGkE%3D&amp;v=t7UlrHOF3eg" class="nowrap" onmousedown="yt.tracking.track('metadataBuyMusic');" rel="nofollow" target="_blank">iTunes</a><a href="https://web.archive.org/web/20130703144227/http://www.youtube.com/cthru?c2b=amazonmp3&amp;key=ewF4Dq7QcLRJ12bsMZjunJFVMRBzXvzzDKUAuJNYLhVvx3Lik1D0_zxNnHS0OLhwlGmdls0XhgNiIdFOxhbyTvYAxg3FabH3gTum111ztpla9fd3-DQrabFbQSpIfcH0M3A6lKIHXm4%3D&amp;v=t7UlrHOF3eg" class="nowrap" onmousedown="yt.tracking.track('metadataBuyMusic');" rel="nofollow" target="_blank">AmazonMP3</a></span></li><li class="watch-extra-info-long">  <span class="metadata-info">
    <span class="metadata-info-title">
Artist<br>
    </span>
      <span>PM Artist Sessions Project</span>
  </span>
</li>
        </ul>
      </div>

      <div id="watch-description-toggle" class="yt-uix-expander-head yt-uix-button-panel">
        <div id="watch-description-expand" class="expand">
          <button class="metadata-inline yt-uix-button yt-uix-button-text" type="button" onclick=";return false;" role="button">    <span class="yt-uix-button-content">
Show more 
    </span>
</button>
        </div>
        <div id="watch-description-collapse" class="collapse">
          <button class="metadata-inline yt-uix-button yt-uix-button-text" type="button" onclick=";return false;" role="button">    <span class="yt-uix-button-content">
Show less 
    </span>
</button>
        </div>
      </div>
    </div>
  </div>

      <div id="action-panel-share" class="action-panel-content hid">
      <div id="watch-actions-share-loading">
    <div class="action-panel-loading">
        <p class="yt-spinner">
      <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

    </div>
  </div>
  <div id="watch-actions-share-panel"></div>

  </div>

      <div id="action-panel-addto" class="action-panel-content hid" data-auth-required="true">
    <div class="action-panel-loading">
        <p class="yt-spinner">
      <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

    </div>
  </div>

        <div id="action-panel-transcript" class="action-panel-content hid">
    <div id="watch-actions-transcript-loading">
      <div class="action-panel-loading">
          <p class="yt-spinner">
      <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

      </div>
    </div>
      <div id="watch-actions-transcript" class="watch-actions-panel hid">
      <div id="caption-line-template" class="hid">
    <!--
    <div class="caption-line-time">
      <div class="caption-line-start">__start__</div>
    </div>
    <div class="editable-line-text">
      <span class="editable-line-text-original">__original__</span>
      <label class="editable-line-text-current hid">__current__</label>
      <textarea class="editable-line-text-input hid">__input__</textarea>
    </div>
    -->
  </div>



    <div id="watch-transcript-container">
      <div id="watch-transcript-not-found" class="hid">
The interactive transcript could not be loaded.
      </div>

      
    </div>
  </div>

  </div>

      <div id="action-panel-stats" class="action-panel-content hid">
    <div class="action-panel-loading">
        <p class="yt-spinner">
      <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

    </div>
  </div>

      <div id="action-panel-report" class="action-panel-content hid" data-auth-required="true">
    <div class="action-panel-loading">
        <p class="yt-spinner">
      <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

    </div>
  </div>

      <div id="action-panel-login" class="action-panel-content hid">
    <div class="action-panel-login">
      <a href="https://web.archive.org/web/20130703144227/https://accounts.google.com/ServiceLogin?passive=true&amp;uilel=3&amp;hl=en_US&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3D__FEATURE__%26hl%3Den_US%26next%3D%252Fwatch%253Fv%253Dt7UlrHOF3eg%2526gl%253DUS%2526hl%253Den%26nomobiletemp%3D1&amp;service=youtube" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-default" data-sessionlink="ei=OjjUUa_0DM3hggLZtoHADw"><span class="yt-uix-button-content">Sign in</span></a>
    </div>
  </div>

  <div id="action-panel-ratings-disabled" class="action-panel-content hid">
      <div id="watch-actions-ratings-disabled" class="watch-actions-panel">
    <em>Ratings have been disabled for this video.</em>
  </div>

  </div>

  <div id="action-panel-rental-required" class="action-panel-content hid">
      <div id="watch-actions-rental-required" class="watch-actions-panel">
    <strong>Rating is available when the video has been rented.</strong>
  </div>

  </div>

  <div id="action-panel-error" class="action-panel-content hid">
    <div class="action-panel-error">
      This feature is not available right now. Please try again later.
    </div>
  </div>

    <div id="watch7-action-panel-footer">
        <hr class="yt-horizontal-rule ">

    </div>
  </div>

  </div>
      <div id="watch-discussion">

        
        <div id="comments-view" data-type="highlights" class="">

                <div>
      <h4>
      <a href="/web/20130703144227/http://www.youtube.com/all_comments?v=t7UlrHOF3eg">
            <strong>All Comments</strong> (378)

      </a>
  </h4>


          <div class="comments-post-alert comments-post">
        <a href="https://web.archive.org/web/20130703144227/https://accounts.google.com/ServiceLogin?passive=true&amp;uilel=3&amp;hl=en_US&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dcomments%26hl%3Den_US%26next%3D%252Fwatch%253Fv%253Dt7UlrHOF3eg%2526gl%253DUS%2526hl%253Den%26nomobiletemp%3D1&amp;service=youtube">Sign in</a> now to post a comment!

      </div>


      <ul id="all-comments">
      


  

      


  

      


  

      


  

      


  

      


  

      


  

      


  

      


  

      


  <li class="comment" data-author-id="YEx1Ax31s40ZrtB-oHmL9A" data-id="xU7DaONPhh8SPsW7R75Euh5mwC0-FacVOQb1BWH26Qk">
    <button class="flip close yt-uix-button yt-uix-button-link yt-uix-button-empty" type="button" onclick=";return false;" data-button-has-sibling-menu="true" role="button" aria-pressed="false" aria-expanded="false" aria-haspopup="true" aria-activedescendant="">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-comment-close" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""><div class=" yt-uix-button-menu yt-uix-button-menu-link" style="display: none;"><ul><li class="comment-action-remove comment-action" data-action="remove"><span class="yt-uix-button-menu-item">Remove</span></li><li class="comment-action" data-action="flag-profile-pic"><span class="yt-uix-button-menu-item">Report profile image</span></li><li class="comment-action" data-action="flag"><span class="yt-uix-button-menu-item">Flag for spam</span></li><li class="comment-action-block comment-action" data-action="block"><span class="yt-uix-button-menu-item">Block User</span></li><li class="comment-action-unblock comment-action" data-action="unblock"><span class="yt-uix-button-menu-item">Unblock User</span></li></ul></div></button>
      <a href="/web/20130703144227/http://www.youtube.com/channel/UCYEx1Ax31s40ZrtB-oHmL9A" class="yt-user-photo ">    <span class="video-thumb  yt-thumb yt-thumb-48">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="Brent Carney" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="https://web.archive.org/web/20130703144227/https://gp4.googleusercontent.com/-NYhyxFw2uVA/AAAAAAAAAAI/AAAAAAAAAAA/hgRehM4-ETQ/s48-c-k/photo.jpg" width="48">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
</a>

    

  <div class="content">
      <p class="metadata">
        <span class="author ">
          <a href="/web/20130703144227/http://www.youtube.com/channel/UCYEx1Ax31s40ZrtB-oHmL9A" class="yt-uix-sessionlink yt-user-name " data-sessionlink="ei=OjjUUa_0DM3hggLZtoHADw" dir="ltr">Brent Carney</a>
        </span>
          <span class="time" dir="ltr">
            <a dir="ltr" href="https://web.archive.org/web/20130703144227/http://www.youtube.com/comment?lc=xU7DaONPhh8SPsW7R75Euh5mwC0-FacVOQb1BWH26Qk">
              17 hours ago
            </a>
          </span>
      </p>


      <div class="comment-text" dir="ltr">
        <p>my gamertag is﻿ cpt price5055</p>

      </div>
      
  <div class="comment-actions">
    <button class="start comment-action yt-uix-button yt-uix-button-link" type="button" onclick=";return false;" data-action="reply" role="button">    <span class="yt-uix-button-content">
Reply 
    </span>
</button>
    <span class="separator">·</span>


    <span class="yt-uix-clickcard"><button class="start comment-action-vote-up comment-action yt-uix-clickcard-target yt-uix-button yt-uix-button-link yt-uix-tooltip yt-uix-button-empty" type="button" onclick=";return false;" title="" data-action="" data-tooltip-show-delay="300" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-watch-comment-vote-up" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
</button>  <div class="watch7-hovercard yt-uix-clickcard-content">
    <h3 class="watch7-hovercard-header">Sign in to YouTube</h3>
    <div class="watch7-hovercard-message">
      Sign in with your YouTube Account (YouTube, Google+, Gmail, Orkut, Picasa, or Chrome) to rate <span class="yt-user-name " dir="ltr">Brent Carney</span>'s comment.

    </div>
    <ul class="watch7-hovercard-icon-strip clearfix">
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-youtube-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gplus-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gmail-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-picasa-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-chrome-icon"></div>
      </li>
    </ul>
    <div class="watch7-hovercard-account-line">
      <a href="https://web.archive.org/web/20130703144227/https://accounts.google.com/ServiceLogin?passive=true&amp;uilel=3&amp;hl=en_US&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3D__FEATURE__%26hl%3Den_US%26next%3D%252Fwatch%253Fv%253Dt7UlrHOF3eg%2526gl%253DUS%2526hl%253Den%26nomobiletemp%3D1&amp;service=youtube" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-primary" data-sessionlink="ei=OjjUUa_0DM3hggLZtoHADw"><span class="yt-uix-button-content">Sign in</span></a>
    </div>
  </div>
</span><span class="yt-uix-clickcard"><button class="end comment-action-vote-down comment-action yt-uix-clickcard-target yt-uix-button yt-uix-button-link yt-uix-tooltip yt-uix-button-empty" type="button" onclick=";return false;" title="" data-action="" data-tooltip-show-delay="300" role="button">    <span class="yt-uix-button-icon-wrapper">
      <img class="yt-uix-button-icon yt-uix-button-icon-watch-comment-vote-down" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
      <span class="yt-uix-button-valign"></span>
    </span>
</button>  <div class="watch7-hovercard yt-uix-clickcard-content">
    <h3 class="watch7-hovercard-header">Sign in to YouTube</h3>
    <div class="watch7-hovercard-message">
      Sign in with your YouTube Account (YouTube, Google+, Gmail, Orkut, Picasa, or Chrome) to rate <span class="yt-user-name " dir="ltr">Brent Carney</span>'s comment.

    </div>
    <ul class="watch7-hovercard-icon-strip clearfix">
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-youtube-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gplus-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-gmail-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-picasa-icon"></div>
      </li>
      <li class="watch7-hovercard-icon">
        <div class="watch7-hovercard-chrome-icon"></div>
      </li>
    </ul>
    <div class="watch7-hovercard-account-line">
      <a href="https://web.archive.org/web/20130703144227/https://accounts.google.com/ServiceLogin?passive=true&amp;uilel=3&amp;hl=en_US&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3D__FEATURE__%26hl%3Den_US%26next%3D%252Fwatch%253Fv%253Dt7UlrHOF3eg%2526gl%253DUS%2526hl%253Den%26nomobiletemp%3D1&amp;service=youtube" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-primary" data-sessionlink="ei=OjjUUa_0DM3hggLZtoHADw"><span class="yt-uix-button-content">Sign in</span></a>
    </div>
  </div>
</span>
  </div>

  </div>


  </li>

  </ul>

  </div>




    <ul>
      <li class="hid" id="parent-comment-loading">Loading comment...</li>
    </ul>
  </div>
  <div id="comments-loading" class="hid">Loading...</div>
        



  </div>



        </div>
        <div id="watch7-sidebar" class="watch-sidebar ">
            
    <div id="watch7-sidebar-discussion"></div>


          <div id="watch-channel-brand-div" class="">
      <div id="watch-channel-brand-div-text">
Advertisement
      </div>
      <div id="google_companion_ad_div">
      </div>
    </div>


          <div class="watch-sidebar-section">

    <div class="watch-sidebar-body">
      <ul id="watch-related" class="video-list">
        <li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=0xX9GC5DRKA" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=related&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAMQzRooAA"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/0xX9GC5DRKA/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">26:20</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="0xX9GC5DRKA" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft: Hunger Games w/Mitch! Game 203 - #BurgerCheese">Minecraft: Hunger Games w/Mitch! Game 203 - #BurgerCheese</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">TheBajanCanadian</span></span>    <span class="stat view-count">
        314,557 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=OE4KsU7fGZs" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAQQzRooAQ"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/OE4KsU7fGZs/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">20:32</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="OE4KsU7fGZs" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - The Big Dog [97]">Minecraft Xbox - The Big Dog [97]</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        77,377 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=lumqmbY8Fts" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAUQzRooAg"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/lumqmbY8Fts/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">20:31</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="lumqmbY8Fts" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Terraria Xbox - Bound Goblin [24]">Terraria Xbox - Bound Goblin [24]</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        9,159 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=Hsce1J3Z3fA" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAYQzRooAw"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/Hsce1J3Z3fA/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">20:28</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="Hsce1J3Z3fA" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - Green Fingers [98]">Minecraft Xbox - Green Fingers [98]</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        38,312 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=t3NXG7pXurg" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAcQzRooBA"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/t3NXG7pXurg/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">19:35</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="t3NXG7pXurg" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Ni No Kuni: Wrath Of The White Witch - Cheese Please [31]">Ni No Kuni: Wrath Of The White Witch - Cheese Please [31]</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        7,905 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=lfx0JPga73U" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAgQzRooBQ"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/lfx0JPga73U/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">22:11</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="lfx0JPga73U" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - Quest To Kill The Ender Dragon - Memory Lane - Part 20">Minecraft Xbox - Quest To Kill The Ender Dragon - Memory Lane - Part 20</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        41,790 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=wCJFuXYxhHU" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=related&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAkQzRooBg"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/wCJFuXYxhHU/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">25:37</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="wCJFuXYxhHU" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Mini-Game : INFINITE CREEPER MAZE!">Minecraft Mini-Game : INFINITE CREEPER MAZE!</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">SkyDoesMinecraft</span></span>    <span class="stat view-count">
        1,636,411 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=ux8EyVBRkBA" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAoQzRooBw"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/ux8EyVBRkBA/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">20:56</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="ux8EyVBRkBA" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - Massive Cruise Ship">Minecraft Xbox - Massive Cruise Ship</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        1,181,010 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=OdVFumWXzxk" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=related&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAsQzRooCA"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/OdVFumWXzxk/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">13:32</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="OdVFumWXzxk" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Mini-Game : SUMO!">Minecraft Mini-Game : SUMO!</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">SkyDoesMinecraft</span></span>    <span class="stat view-count">
        1,738,339 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=vOerqifVYKQ" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=related&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CAwQzRooCQ"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/vOerqifVYKQ/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">13:11</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="vOerqifVYKQ" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Mini-Game : TEACHER!">Minecraft Mini-Game : TEACHER!</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">SkyDoesMinecraft</span></span>    <span class="stat view-count">
        1,821,323 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=nWrf2A6RnZY" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=related&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CA0QzRooCg"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/nWrf2A6RnZY/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">12:30</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="nWrf2A6RnZY" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Hunger Games : OPERATION PROTECT WARDEN FREEMAN!">Minecraft Hunger Games : OPERATION PROTECT WARDEN FREEMAN!</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">SkyDoesMinecraft</span></span>    <span class="stat view-count">
        903,925 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=0W43dW01IDI" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=related&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CA4QzRooCw"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/0W43dW01IDI/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">11:20</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="0W43dW01IDI" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Mini-Game : JENGA!">Minecraft Mini-Game : JENGA!</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">SkyDoesMinecraft</span></span>    <span class="stat view-count">
        1,199,385 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=S-AhtJei73c" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=related&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CA8QzRooDA"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/S-AhtJei73c/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">7:10</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="S-AhtJei73c" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Mini-Game : TRON!">Minecraft Mini-Game : TRON!</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">SkyDoesMinecraft</span></span>    <span class="stat view-count">
        1,945,232 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=GBpuBy_VSfA" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CBAQzRooDQ"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/GBpuBy_VSfA/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">20:48</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="GBpuBy_VSfA" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - Skyblock Map - Grow Please Sugar - Part 6">Minecraft Xbox - Skyblock Map - Grow Please Sugar - Part 6</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        41,868 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=509uhMCYvDU" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CBEQzRooDg"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/509uhMCYvDU/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">4:16</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="509uhMCYvDU" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - Balloon Ride - SPANKLECHANK's World Tour">Minecraft Xbox - Balloon Ride - SPANKLECHANK's World Tour</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        6,782 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=snenRiOXMR0" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CBIQzRooDw"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/snenRiOXMR0/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">23:22</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="snenRiOXMR0" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - The Infected Temple - Redstone Puzzles - Part 5">Minecraft Xbox - The Infected Temple - Redstone Puzzles - Part 5</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        46,005 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=U0a5ewSUX1U" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CBMQzRooEA"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/U0a5ewSUX1U/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">21:50</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="U0a5ewSUX1U" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - Skyblock Map - Fish Me A Dish - Part 3">Minecraft Xbox - Skyblock Map - Fish Me A Dish - Part 3</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        47,652 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=PvR-bATIMUA" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=related&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CBQQzRooEQ"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/PvR-bATIMUA/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">11:31</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="PvR-bATIMUA" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Mini-Game : KING OF THE LADDER!">Minecraft Mini-Game : KING OF THE LADDER!</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">SkyDoesMinecraft</span></span>    <span class="stat view-count">
        970,263 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=K--ohETbpps" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CBUQzRooEg"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/K--ohETbpps/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">21:03</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="K--ohETbpps" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - Quest To Kill The Ender Dragon - Experience Farm - Part 10">Minecraft Xbox - Quest To Kill The Ender Dragon - Experience Farm - Part 10</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        68,327 views
    </span>
</a></li><li class="video-list-item related-list-item"><a href="/web/20130703144227/http://www.youtube.com/watch?v=j5Mo9uQpvmc" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=relmfu&amp;ei=OjjUUa_0DM3hggLZtoHADw&amp;ved=CBYQzRooEw"><span class="ux-thumb-wrap contains-addto ">    <span class="video-thumb  yt-thumb yt-thumb-120">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <span class="yt-thumb-clip-inner">
            <img alt="" src="https://web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="//web.archive.org/web/20130703144227/http://i1.ytimg.com/vi/j5Mo9uQpvmc/default.jpg" width="120">
            <span class="vertical-align"></span>
          </span>
        </span>
      </span>
    </span>
<span class="video-time">20:46</span>

  <button type="button" onclick=";return false;" class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="j5Mo9uQpvmc" role="button">    <span class="yt-uix-button-content">
  <img src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 
    </span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130703144227im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
</span><span dir="ltr" class="title" title="Minecraft Xbox - Quest To Kill The Ender Dragon - Poop Attack - Part 11">Minecraft Xbox - Quest To Kill The Ender Dragon - Poop Attack - Part 11</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">stampylonghead</span></span>    <span class="stat view-count">
        63,064 views
    </span>
</a></li>
            <div id="watch-more-related" class="hid">
    <li id="watch-more-related-loading">
Loading more suggestions...
    </li>
  </div>
  <button class=" yt-uix-button yt-uix-button-default" id="watch-more-related-button" type="button" onclick=";return false;" data-button-action="yt.www.watch.related.loadMore" role="button">    <span class="yt-uix-button-content">
Load more suggestions 
    </span>
</button>

      </ul>
    </div>   </div> 

        </div>
      </div>
    </div>

      <div style="visibility: hidden; height: 0px; padding: 0px; overflow: hidden;">
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
<?php	}	?>