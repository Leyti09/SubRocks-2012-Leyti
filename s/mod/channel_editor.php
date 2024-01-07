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

    if(!$__user_h->user_exists($_SESSION['siteusername']))
        header("Location: /?user_no_exist");

    $_user = $__user_h->fetch_user_username($_SESSION['siteusername']);

    $stmt = $__db->prepare("SELECT * FROM bans WHERE username = :username ORDER BY id DESC");
	$stmt->bindParam(":username", $_user['username']);
	$stmt->execute();

	while($ban = $stmt->fetch(PDO::FETCH_ASSOC)) { 
		header("Location: /logout");
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
		<link id="www-core-css" rel="stylesheet" href="/yt/cssbin/www-core-vfluMRDnk.css">
		<script src="/s/js/alert.js"></script>
		<link rel="stylesheet" href="/yt/cssbin/www-guide-vflx0V5Tq.css">
		<link rel="stylesheet" href="//s.ytimg.com/yt/cssbin/www-player-vflE5bu0u.css">
        <link rel="stylesheet" href="/yt/cssbin/www-channels3-vfl-wJB5W.css">
        <link rel="stylesheet" href="/yt/cssbin/www-the-rest-vflNb6rAI.css">
		<link rel="stylesheet" href="/yt/cssbin/www-extra.css">

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
		<div id="page" class="  branded-page channel ">
			<div id="masthead-container"><?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/header.php"); ?></div>
			<div id="content-container">
				<!-- begin content -->
				<?php if(isset($_SESSION['siteusername']) && $_user['username'] == $_SESSION['siteusername']) { ?>
					<?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/channel_customization2.php"); ?>
				<?php } else{ ?>
				<h1>never gonna give you up</h1>
				<?php	}	?>
				<!-- end content -->
			</div>
			<div id="footer-container">

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
		</script>
	</body>
</html>
