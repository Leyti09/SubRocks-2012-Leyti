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
  <!DOCTYPE html><html lang="en" data-cast-api-enabled="true"><head><script>var ytcsi = {gt: function(n) {n = (n || '') + 'data_';return ytcsi[n] || (ytcsi[n] = {tick: {},span: {},info: {}});},tick: function(l, t, n) {ytcsi.gt(n).tick[l] = t || +new Date();},span: function(l, s, n) {ytcsi.gt(n).span[l] = (typeof s == 'number') ? s :+new Date() - ytcsi.data_.tick[l];},info: function(k, v, n) {ytcsi.gt(n).info[k] = v;}};(function() {var perf = window['performance'] || window['mozPerformance'] ||window['msPerformance'] || window['webkitPerformance'];ytcsi.tick('_start', perf ? perf['timing']['responseStart'] : null);})();if (document.webkitVisibilityState == 'prerender') {ytcsi.info('prerender', 1);document.addEventListener('webkitvisibilitychange', function() {ytcsi.tick('_start');}, false);}try {ytcsi.pt_ = (window.chrome && chrome.csi().pageT ||window.gtbExternal && gtbExternal.pageT() ||window.external && external.pageT);if (ytcsi.pt_) {ytcsi.info('pt', Math.floor(ytcsi.pt_));}} catch(e) {}</script>    <script>
try {window.ytbuffer = {};ytbuffer.handleClick = function(e) {var element = e.target || e.srcElement;while (element.parentElement) {if (element.className.match(/(^| )yt-can-buffer( |$)/)) {window.ytbuffer = {bufferedClick: e};element.className += ' yt-is-buffered';break;}element = element.parentElement;}};if (document.addEventListener) {document.addEventListener('click', ytbuffer.handleClick);} else {document.attachEvent('onclick', ytbuffer.handleClick);}} catch(e) {}  </script>


    <script>
    var ytpreload = ytpreload || {};
    ytpreload.SnapClassList = ['content-snap-width-1', 'content-snap-width-2',
                               'content-snap-width-3'];
    ytpreload.getSnapClass = function(guidePinned) {
      var SCROLLBAR_WIDTH = 21;
      var CONTENT_PADDING = 50;
      var GUIDE_WIDTH = 230;
        var SNAP_WIDTH_2 = 1056;
        var SNAP_WIDTH_3 = 1262;

      var screenWidth = window.innerWidth ||
          document.documentElement.clientWidth;

      var spaceForContent = screenWidth - SCROLLBAR_WIDTH - CONTENT_PADDING;
      if (ytpreload.usePinnedGuideStyle() && guidePinned) {
        spaceForContent -= GUIDE_WIDTH;
      }

      if (spaceForContent >= SNAP_WIDTH_3) {
        return ytpreload.SnapClassList[2];
      } else if (spaceForContent >= SNAP_WIDTH_2){
        return ytpreload.SnapClassList[1];
      } else {
        return ytpreload.SnapClassList[0];
      }

    };
    ytpreload.GUIDE_PINNED_CLASS = 'guide-pinned';
    ytpreload.GUIDE_VISIBILITY_CLASS = 'show-guide';
    ytpreload.usePinnedGuideStyle = function() {
      var screenWidth = window.innerWidth ||
          document.documentElement.clientWidth;
      return screenWidth >= 1251;
    };
    (function() {
      var htmlEl = document.getElementsByTagName('html')[0];
      var classList = [htmlEl.className];
        if (ytpreload.usePinnedGuideStyle()) {
          classList.push(ytpreload.GUIDE_PINNED_CLASS);
            classList.push(ytpreload.GUIDE_VISIBILITY_CLASS);
        }
        classList.push(' ', ytpreload.getSnapClass(
            true));
      htmlEl.className = classList.join(' ');
    })();
  </script>




  
    <link id="css-2656287469" class="www-core" rel="stylesheet" href="/2014isnow/www-core-vflqJi9JP.css" data-loaded="true">
      <link id="css-3731966628" class="www-home-c4" rel="stylesheet" href="/2014isnow/www-home-c4-vfljtKkXJ.css" data-loaded="true">

<style class="html5-viewport-sheet" disabled="true">@-o-viewport { width: device-width; }@-moz-viewport { width: device-width; }@-ms-viewport { width: device-width; }@-webkit-viewport { width: device-width; }@viewport { width: device-width; }</style><script>if (window.ytcsi) {window.ytcsi.tick("ce", null, '');}</script>  

    
<title>Betatube</title><link rel="search" type="application/opensearchdescription+xml" href="http://www.youtube.com/opensearch?locale=en_US" title="YouTube Video Search"><link rel="shortcut icon" href="https://s.ytimg.com/yts/img/favicon-vfldLzJxy.ico" type="image/x-icon">     <link rel="icon" href="//s.ytimg.com/yts/img/favicon_32-vflWoMFGx.png" sizes="32x32"><link rel="canonical" href="http://www.youtube.com/"><link rel="alternate" media="handheld" href="https://m.youtube.com/?"><link rel="alternate" media="only screen and (max-width: 640px)" href="https://m.youtube.com/?"><meta name="description" content="Share your videos with friends, family, and the world"><meta name="keywords" content="video, sharing, camera phone, video phone, free, upload">  <meta property="og:image" content="//s.ytimg.com/yts/img/youtube_logo_stacked-vfl225ZTx.png">
  <meta property="fb:app_id" content="87741124305">
  <link rel="publisher" href="https://www.youtube.com/channel/UC7MqHVJp7KH4J96gOkO6GFQ">
    <link id="css-3971706119" class="www-pageframe" rel="stylesheet" href="/2014isnow/www-pageframe-vfl4Zg_oQ1.css" data-loaded="true">
    <link id="css-2885288003" class="www-guide" rel="stylesheet" href="//s.ytimg.com/yts/cssbin/www-guide-vflNY2N0G.css" data-loaded="true">
<script>if (window.ytcsi) {window.ytcsi.tick("cl", null, '');}</script></head>
    <body dir="ltr" class="  ltr       site-center-aligned site-as-giant-card guide-pinning-enabled appbar-hidden    visibility-logging-enabled   not-nirvana-dogfood  hitchhiker-enabled    guide-enabled  guide-expanded    flex-width-enabled      flex-width-enabled-snap    delayed-frame-styles-not-in  " id="body">

  <div id="body-container"><form name="logoutForm" method="POST" action="/logout"><input type="hidden" name="action_logout" value="1"></form><div id="masthead-positioner">  
  <div id="yt-masthead-container" class="yt-grid-box yt-base-gutter"><div id="yt-masthead" class=""><div class="yt-masthead-logo-container ">    <a id="logo-container" href="/" title="YouTube home" class="spf-link"><img id="logo" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="YouTube home"></a><?php $logged = $__user_h->fetch_user_username($_SESSION['siteusername']); ?>
<div id="appbar-guide-button-container"><button class="appbar-guide-toggle appbar-guide-clickable-ancestor yt-uix-button yt-uix-button-text yt-uix-button-size-default yt-uix-button-has-icon yt-uix-button-empty" id="appbar-guide-button" onclick=";return false;" type="button"  role="button" aria-label="Guide"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-appbar-guide" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button><div id="appbar-guide-button-notification-check" class="yt-valign"><img class="appbar-guide-notification-icon yt-valign-content" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></div></div><div id="appbar-main-guide-notification-container"></div></div><div id="yt-masthead-signin"><span id="appbar-onebar-upload-group" class="yt-uix-button-group"><a href="/new_upload" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-default yt-uix-button-size-default" data-sessionlink="feature=mhsb&amp;ei=E2VUU8utINKz-QODxICoAw" id="upload-btn"><span class="yt-uix-button-content">Upload </span></a></span><?php if(!isset($_SESSION['siteusername'])) { ?><button class=" yt-uix-button yt-uix-button-primary yt-uix-button-size-default" onclick=";window.location.href=this.getAttribute(&#39;href&#39;);return false;" href="/sign_in" type="button"  role="button"><span class="yt-uix-button-content">Sign in </span></button><?php } else{ ?><img src="/dynamic/pfp/<?php echo $logged['pfp'] ?>" style="width:35px;margin-left:3px;height: 23px;object-fit: contain;"><?php } ?></div><div id="yt-masthead-content"><form id="masthead-search" class="search-form consolidated-form" action="/results" onsubmit="if (_gel(&#39;masthead-search-term&#39;).value == &#39;&#39;) return false;"><button type="submit" dir="ltr" class="search-btn-component search-button yt-uix-button yt-uix-button-default yt-uix-button-size-default" onclick="if (_gel(&#39;masthead-search-term&#39;).value == &#39;&#39;) return false; _gel(&#39;masthead-search&#39;).submit(); return false;;return true;" tabindex="2" id="search-btn"  role="button"><span class="yt-uix-button-content">Search </span></button><div id="masthead-search-terms" class="masthead-search-terms-border" dir="ltr"><label><input id="masthead-search-term" autocomplete="off" autofocus class="search-term yt-uix-form-input-bidi" name="search_query" value="" type="text" tabindex="1" title="Search"></label></div></form></div></div></div>
    
    <div id="masthead-appbar-container" class="clearfix"><div id="masthead-appbar"><div id="appbar-content" class=""></div></div></div>

<iframe id="masthead-ie-mask"></iframe></div><div id="masthead-positioner-height-offset"></div><div id="page-container"><div id="page" class="  home     branded-page-v2-masthead-ad-header  clearfix"><div id="guide" class="yt-scrollbar">        <div id="appbar-guide-menu" class="appbar-menu appbar-guide-menu-layout appbar-guide-clickable-ancestor yt-uix-scroller">
    <div id="guide-container" class="vve-check" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CMgBEP4e">
        <div class="guide-module-content yt-scrollbar">
    <ul class="guide-toplevel">
            <li class="guide-section vve-check"
    data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CMkBEOYrKAA"
    data-visibility-tracking="CAAQ5isiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
    <div class="guide-item-container personal-item">
      
      <ul class="guide-user-links yt-uix-tdl yt-box">
            <li class="vve-check overflowable-list-item guide-channel" id="what_to_watch-guide-item"
      data-visibility-tracking="CAEQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link  guide-item-selected  "
    href="/"
    title="What to Watch"
    data-sessionlink="feature=g-system&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CMoBELUsKAA"
    data-visibility-tracking="CAEQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="what_to_watch"
    data-serialized-endpoint="0qDduQEREg9GRXdoYXRfdG9fd2F0Y2g%3D"
  >
    <span class="yt-valign-container">
        <img class="thumb guide-what-to-watch-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
        <span class="display-name  no-count">
          <span>
            What to Watch
          </span>
        </span>
    </span>
  </a>

  </li>

      </ul>
    </div>
      <hr class="guide-section-separator">
  </li>

            <li class="guide-section vve-check"
    data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CMsBEOYrKAE"
    data-visibility-tracking="CAIQ5isiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
    <div class="guide-item-container personal-item">
          <h3>
      Best of Betatube
    </h3>

      <ul class="guide-user-links yt-uix-tdl yt-box">
            <li class="vve-check overflowable-list-item guide-channel" id="UCF0pVplsI8R5kcAqgtoRqoA-guide-item"
      data-visibility-tracking="CAMQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link   "
    href="/user/PopularonBetatube"
    title="Popular on Betatube"
    data-sessionlink="feature=g-channel&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CMwBELUsKAA"
    data-visibility-tracking="CAMQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="UCF0pVplsI8R5kcAqgtoRqoA"
    data-serialized-endpoint="0qDduQEaEhhVQ0YwcFZwbHNJOFI1a2NBcWd0b1Jxb0E%3D"
  >
    <span class="yt-valign-container">
        <span class="thumb">    <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/2014isnow/pob.jpg" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</span>
        <span class="display-name  no-count">
          <span>
            Popular on Betatube
          </span>
        </span>
    </span>
  </a>

  </li>

            <li class="vve-check overflowable-list-item guide-channel" id="UC-9-kyTW8ZkZNDHQJ6FgpwQ-guide-item"
      data-visibility-tracking="CAQQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link   "
    href="/user/music"
    title="Music"
    data-sessionlink="feature=g-channel&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CM0BELUsKAE"
    data-visibility-tracking="CAQQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="UC-9-kyTW8ZkZNDHQJ6FgpwQ"
    data-serialized-endpoint="0qDduQEaEhhVQy05LWt5VFc4WmtaTkRIUUo2Rmdwd1E%3D"
  >
    <span class="yt-valign-container">
        <span class="thumb">    <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/2014isnow/music.jpg" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</span>
        <span class="display-name  no-count">
          <span>
            Music
          </span>
        </span>
    </span>
  </a>

  </li>

            <li class="vve-check overflowable-list-item guide-channel" id="UCEgdi0XIXXZ-qJOFPf4JSKw-guide-item"
      data-visibility-tracking="CAUQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link   "
    href="/user/sports"
    title="Sports"
    data-sessionlink="feature=g-channel&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CM4BELUsKAI"
    data-visibility-tracking="CAUQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="UCEgdi0XIXXZ-qJOFPf4JSKw"
    data-serialized-endpoint="0qDduQEaEhhVQ0VnZGkwWElYWFotcUpPRlBmNEpTS3c%3D"
  >
    <span class="yt-valign-container">
        <span class="thumb">    <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/2014isnow/sports.png" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</span>
        <span class="display-name  no-count">
          <span>
            Sports
          </span>
        </span>
    </span>
  </a>

  </li>

            <li class="vve-check overflowable-list-item guide-channel" id="UCOpNcN46UbXVtpKMrmU4Abg-guide-item"
      data-visibility-tracking="CAYQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link   "
    href="/user/gaming"
    title="Gaming"
    data-sessionlink="feature=g-channel&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CM8BELUsKAM"
    data-visibility-tracking="CAYQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="UCOpNcN46UbXVtpKMrmU4Abg"
    data-serialized-endpoint="0qDduQEaEhhVQ09wTmNONDZVYlhWdHBLTXJtVTRBYmc%3D"
  >
    <span class="yt-valign-container">
        <span class="thumb">    <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/2014isnow/gaming.png" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</span>
        <span class="display-name  no-count">
          <span>
            Gaming
          </span>
        </span>
    </span>
  </a>

  </li>

            <li class="vve-check overflowable-list-item guide-channel" id="UC3yA8nDwraeOfnYfBWun83g-guide-item"
      data-visibility-tracking="CAcQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link   "
    href="/user/education"
    title="Education"
    data-sessionlink="feature=g-channel&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CNABELUsKAQ"
    data-visibility-tracking="CAcQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="UC3yA8nDwraeOfnYfBWun83g"
    data-serialized-endpoint="0qDduQEaEhhVQzN5QThuRHdyYWVPZm5ZZkJXdW44M2c%3D"
  >
    <span class="yt-valign-container">
        <span class="thumb">    <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/2014isnow/edu.jpg" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</span>
        <span class="display-name  no-count">
          <span>
            Education
          </span>
        </span>
    </span>
  </a>

  </li>

            <li class="vve-check overflowable-list-item guide-channel" id="UCczhp4wznQWonO7Pb8HQ2MQ-guide-item"
      data-visibility-tracking="CAgQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link   "
    href="/user/movies"
    title="Movies"
    data-sessionlink="feature=g-channel&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CNEBELUsKAU"
    data-visibility-tracking="CAgQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="UCczhp4wznQWonO7Pb8HQ2MQ"
    data-serialized-endpoint="0qDduQEaEhhVQ2N6aHA0d3puUVdvbk83UGI4SFEyTVE%3D"
  >
    <span class="yt-valign-container">
        <span class="thumb">    <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/2014isnow/movie.jpg" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</span>
        <span class="display-name  no-count">
          <span>
            Movies
          </span>
        </span>
    </span>
  </a>

  </li>
            <li class="vve-check overflowable-list-item guide-channel" id="HCPvDBPPFfuaM-guide-item"
      data-visibility-tracking="CAoQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link   "
    href="/user/news"
    title="News"
    data-sessionlink="feature=g-channel&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CNMBELUsKAc"
    data-visibility-tracking="CAoQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="HCPvDBPPFfuaM"
    data-serialized-endpoint="0qDduQEPEg1IQ1B2REJQUEZmdWFN"
  >
    <span class="yt-valign-container">
        <span class="thumb">    <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/2014isnow/news.jpg" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</span>
        <span class="display-name  no-count">
          <span>
            News
          </span>
        </span>
    </span>
  </a>

  </li>

            <li class="vve-check overflowable-list-item guide-channel" id="UCBR8-60-B28hp2BmDPdntcQ-guide-item"
      data-visibility-tracking="CAsQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-link   "
    href="/user/Betatube"
    title="Spotlight"
    data-sessionlink="feature=g-channel&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CNQBELUsKAg"
    data-visibility-tracking="CAsQtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="UCBR8-60-B28hp2BmDPdntcQ"
    data-serialized-endpoint="0qDduQEaEhhVQ0JSOC02MC1CMjhocDJCbURQZG50Y1E%3D"
  >
    <span class="yt-valign-container">
        <span class="thumb">    <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/2014isnow/spotlightdeeznuts.png" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</span>
        <span class="display-name  no-count">
          <span>
            Spotlight
          </span>
        </span>
    </span>
  </a>

  </li>

      </ul>
    </div>
      <hr class="guide-section-separator">
  </li>

            <li class="guide-section vve-check"
    data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CNUBEOYrKAI"
    data-visibility-tracking="CAwQ5isiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
    <div class="guide-item-container personal-item">
      
      <ul class="guide-user-links yt-uix-tdl yt-box">
            <li class="vve-check overflowable-list-item guide-channel" id="guide_builder-guide-item"
      data-visibility-tracking="CA0QtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU=">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-nolink   "
    href="/channels"
    title="Browse channels"
    data-sessionlink="feature=g-manage&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CNYBELUsKAA"
    data-visibility-tracking="CA0QtSwiEwiLg7WaqPC9AhXSWX4KHQMiADU="
    data-external-id="guide_builder"
    data-serialized-endpoint="0qPduQECCAE%3D"
  >
    <span class="yt-valign-container">
        <img class="thumb guide-builder-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
        <span class="display-name  no-count">
          <span>
            Browse channels
          </span>
        </span>
    </span>
  </a>

  </li>

      </ul>
    </div>
      <hr class="guide-section-separator">
  </li>
			<?php if(!isset($_SESSION['siteusername'])) { ?>
            <li class="guide-section guide-header signup-promo ">
    <p>
      Sign in now to see your channels and recommendations!
    </p>
    <div id="guide-builder-promo-buttons" class="signed-out clearfix">
      <a href="/sign_in" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-primary yt-uix-button-size-default" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw"><span class="yt-uix-button-content">Sign In </span></a>
    </div>
			</li><?php } ?>

    </ul>
  </div>

    </div>
  </div>
  <div id="appbar-guide-notifications" class="hid">
        <div id="appbar-guide-notification-watch-later-video-added">
    <!--
<div class="appbar-guide-notification"><span class="appbar-guide-notification-content-wrapper yt-valign"><img class="appbar-guide-notification-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"><span class="appbar-guide-notification-text-content">Added to Watch Later</span></span></div>    -->
  </div>


    <div id="appbar-guide-notification-watch-later-video-removed">
    <!--
<div class="appbar-guide-notification"><span class="appbar-guide-notification-content-wrapper yt-valign"><img class="appbar-guide-notification-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"><span class="appbar-guide-notification-text-content">Removed from Watch Later</span></span></div>    -->
  </div>


    <div id="appbar-guide-notification-subscription">
    <!--
<div class="appbar-guide-notification"><span class="appbar-guide-notification-content-wrapper yt-valign"><img class="appbar-guide-notification-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"><span class="appbar-guide-notification-text-content">Subscription added</span></span></div>    -->
  </div>


    <div id="appbar-guide-notification-unsubscription">
    <!--
<div class="appbar-guide-notification"><span class="appbar-guide-notification-content-wrapper yt-valign"><img class="appbar-guide-notification-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"><span class="appbar-guide-notification-text-content">Subscription removed</span></span></div>    -->
  </div>


    <div id="appbar-guide-notification-playlist-like">
    <!--
<div class="appbar-guide-notification"><span class="appbar-guide-notification-content-wrapper yt-valign"><img class="appbar-guide-notification-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"><span class="appbar-guide-notification-text-content">Playlist added</span></span></div>    -->
  </div>


    <div id="appbar-guide-notification-playlist-unlike">
    <!--
<div class="appbar-guide-notification"><span class="appbar-guide-notification-content-wrapper yt-valign"><img class="appbar-guide-notification-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"><span class="appbar-guide-notification-text-content">Playlist removed</span></span></div>    -->
  </div>


    <div id="appbar-guide-notification-video-like">
    <!--
<div class="appbar-guide-notification"><span class="appbar-guide-notification-content-wrapper yt-valign"><img class="appbar-guide-notification-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"><span class="appbar-guide-notification-text-content">Added to Liked videos</span></span></div>    -->
  </div>


    <div id="appbar-guide-notification-video-unlike">
    <!--
<div class="appbar-guide-notification"><span class="appbar-guide-notification-content-wrapper yt-valign"><img class="appbar-guide-notification-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"><span class="appbar-guide-notification-text-content">Removed from Liked videos</span></span></div>    -->
  </div>


  </div>
  <div id="appbar-guide-item-templates" class="hid">
      <div id="appbar-guide-item-template-playlist">
    <!--
        <li class="vve-check overflowable-list-item guide-channel" id="__ID__-guide-item"
      data-visibility-tracking="">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-nolink   "
    href="__URL__"
    title="__TITLE__"
    data-sessionlink="feature=g-playlists&amp;ei=E2VUU8utINKz-QODxICoAw"
    data-visibility-tracking=""
    data-external-id="__ID__"
    data-serialized-endpoint=""
  >
    <span class="yt-valign-container">
        <img class="thumb guide-playlists-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
        <span class="display-name  no-count">
          <span>
            __TITLE__
          </span>
        </span>
    </span>
  </a>

  </li>

    -->
  </div>
  <div id="appbar-guide-item-template-mix">
    <!--
        <li class="vve-check overflowable-list-item guide-channel" id="__ID__-guide-item"
      data-visibility-tracking="">
      
  <a class="guide-item yt-uix-sessionlink yt-valign spf-nolink   "
    href="__URL__"
    title="__TITLE__"
    data-sessionlink="feature=g-playlists&amp;ei=E2VUU8utINKz-QODxICoAw"
    data-visibility-tracking=""
    data-external-id="__ID__"
    data-serialized-endpoint=""
  >
    <span class="yt-valign-container">
        <img class="thumb guide-mix-icon" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
        <span class="display-name  no-count">
          <span>
            __TITLE__
          </span>
        </span>
    </span>
  </a>

  </li>

    -->
  </div>

  </div>
  <iframe id="appbar-guide-iframe-mask" class="appbar-guide-menu-layout"></iframe>

</div><div id="alerts" class="content-alignment">  
</div><div id="header">    <div id="masthead_child_div"><?php if(!empty($_GET['error'])) { ?>
<div id="flash-upgrade"><div class="yt-alert yt-alert-default yt-alert-error  yt-alert-player">  <div class="yt-alert-icon">
    <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-buttons"></div><div class="yt-alert-content" role="alert">    <span class="yt-alert-vertical-trick"></span>
    <div class="yt-alert-message"><?php echo htmlspecialchars($_GET['error']) ?></div>
</div></div></div>
<?php } ?></div>







    <div id="ad_creative_1" class="ad-div mastad" style="z-index: 1;">
<script>(function() {var loaded = function() {return window.yt && yt.www && yt.www.home && yt.www.home.ads;};window.masthead_ad_creative_iframe_1_workaround = function() {if (loaded()) {yt.www.home.ads.workaroundIE(this);}};window.masthead_ad_creative_iframe_1_onload = function() {if (!loaded()) {setTimeout(masthead_ad_creative_iframe_1_onload, 50);return;}yt.www.home.ads.workaroundLoad();};})();</script>

      <script>(function() {function tagMpuIframe() {var containerEl = document.getElementById('ad_creative_1');if (!containerEl) {return;}var iframeEl = document.createElement('iframe');var iframeSrc = 'https://ad.doubleclick.net/N4061/adi/com.ythome/_default;sz=970x250;tile=1;ssl=1;dc_yt=1;kbsg=HPUS140420;kga=-1;kgg=-1;klg=en;kmyd=ad_creative_1;ytexp=907050,943601,938631,916807,914072,916612,936109;ord=' +Math.floor(Math.random() * 10000000000000000) +'?';iframeEl.id = 'ad_creative_iframe_1';iframeEl.width = '970';iframeEl.height = '250';iframeEl.style.cssText = 'z-index:1;';iframeEl.onload = window.masthead_ad_creative_iframe_1_onload;iframeEl.onmouseover = window.masthead_ad_creative_iframe_1_workaround;iframeEl.onfocus = window.masthead_ad_creative_iframe_1_workaround;iframeEl.scrolling = 'no';iframeEl.frameBorder = '0';containerEl.appendChild(iframeEl);iframeEl.src = iframeSrc;}tagMpuIframe();})();</script>
    </div>



        <div id="ad_creative_expand_btn_1" class="masthead-ad-control open content-alignment masthead-ad-control-header hid">
    <a onclick="masthead.expand_ad(); return false;" class="yt-valign">
      <span class="yt-valign-container">Show ad</span>
      <img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" class="yt-valign-container">
    </a>
  </div>

</div><div id="player" class="    off-screen  ">
  <div id="playlist" class="playlist">
    
  </div>

  <div id="player-unavailable" class="  hid  ">
    
  </div>

    <div id="player-api" class="player-width player-height off-screen-target  player-api"></div>

        <script>if (window.ytcsi) {window.ytcsi.tick("bf", null, '');}</script>
        <div id="html5-template" class="html5-video-player" style="display: none;" tabindex="-1">
      <div id="html5-player-messages" style="display:none">
    <!--
    <div id="HTML5_NO_AVAILABLE_FORMATS_FALLBACK">Your browser does not currently recognize any of the video formats available.<br><a href="/html5">Click here to visit our frequently asked questions about HTML5 video.</a></div>
    <div id="FLASH_FALLBACK">The Adobe Flash Player is required for video playback. <br> <a href="http://get.adobe.com/flashplayer/">Get the latest Flash Player</a></div>
    <div id="LEARN_MORE"><div class="error-help-link"><a href="http://support.google.com/youtube/?p=player_error1" target="_blank">Learn more</a></div></div>
    <div id="YTP_VIDEOS">{"case1": "1 video", "case0": "No videos", "other": "# videos"}</div>

    <div id="YTP_QUALITY_HIGHRES">2160p <sup>4K</sup></div>
    <div id="YTP_QUALITY_HD1440">1440p <sup>HD</sup></div>
    <div id="YTP_QUALITY_HD1080">1080p <sup>HD</sup></div>
    <div id="YTP_QUALITY_HD720">720p <sup>HD</sup></div>
    <div id="YTP_QUALITY_LARGE">480p</div>
    <div id="YTP_QUALITY_MEDIUM">360p</div>
    <div id="YTP_QUALITY_SMALL">240p</div>
    <div id="YTP_QUALITY_TINY">144p</div>
    <div id="YTP_QUALITY_AUTO">Auto</div>
    <div id="YTP_QUALITY_AUTO_WITH_QUALITY">Auto ($video_quality)</div>

    <div id="YTP_AD_RESUME_MESSAGE">Your video will resume after the following ad.</div>

    <div id="HTML5_SUBS_ASR">automatic captions</div>
    <div id="VISIT_ADVERTISERS_SITE">Visit advertiser's site</div>
    <div id="YTP_TRANSLATE_CAPTIONS">Translate Captions</div>

    <div id="YTP_SUBTITLES_FONT_FAMILY">Font family</div>
    <div id="YTP_SUBTITLES_FONT_COLOR">Font color</div>
    <div id="YTP_SUBTITLES_FONT_SIZE">Font size</div>
    <div id="YTP_SUBTITLES_BACKGROUND_COLOR">Background color</div>
    <div id="YTP_SUBTITLES_BACKGROUND_OPACITY">Background opacity</div>
    <div id="YTP_SUBTITLES_WINDOW_COLOR">Window color</div>
    <div id="YTP_SUBTITLES_WINDOW_OPACITY">Window opacity</div>
    <div id="YTP_SUBTITLES_CHAR_EDGE_STYLE">Character edge style</div>
    <div id="YTP_SUBTITLES_TEXT_OPACITY">Text opacity</div>

    <div id="YTP_SUBTITLES_FONT_FAMILY_MONO_SERIF">Monospaced Serif</div>
    <div id="YTP_SUBTITLES_FONT_FAMILY_PROP_SERIF">Proportional Serif</div>
    <div id="YTP_SUBTITLES_FONT_FAMILY_MONO_SANS">Monospaced Sans-Serif</div>
    <div id="YTP_SUBTITLES_FONT_FAMILY_PROP_SANS">Proportional Sans-Serif</div>
    <div id="YTP_SUBTITLES_FONT_FAMILY_CASUAL">Casual</div>
    <div id="YTP_SUBTITLES_FONT_FAMILY_CURSIVE">Cursive</div>
    <div id="YTP_SUBTITLES_FONT_FAMILY_SMALL_CAPS">Small Capitals</div>
    <div id="YTP_SUBTITLES_CHAR_EDGE_STYLE_NONE">None</div>
    <div id="YTP_SUBTITLES_CHAR_EDGE_STYLE_DROP_SHADOW">Drop Shadow</div>
    <div id="YTP_SUBTITLES_CHAR_EDGE_STYLE_RAISED">Raised</div>
    <div id="YTP_SUBTITLES_CHAR_EDGE_STYLE_DEPRESSED">Depressed</div>
    <div id="YTP_SUBTITLES_CHAR_EDGE_STYLE_UNIFORM">Uniform</div>
    <div id="YTP_SUBTITLES_COLOR_WHITE">White</div>
    <div id="YTP_SUBTITLES_COLOR_YELLOW">Yellow</div>
    <div id="YTP_SUBTITLES_COLOR_GREEN">Green</div>
    <div id="YTP_SUBTITLES_COLOR_CYAN">Cyan</div>
    <div id="YTP_SUBTITLES_COLOR_BLUE">Blue</div>
    <div id="YTP_SUBTITLES_COLOR_MAGENTA">Magenta</div>
    <div id="YTP_SUBTITLES_COLOR_RED">Red</div>
    <div id="YTP_SUBTITLES_COLOR_BLACK">Black</div>

    <div id="YTP_SUBTITLES_TRANSLATE_DIALOG_TITLE">Translate...</div>

    <div id="FRESCA_STARTING_SOON_MESSAGE">Starting soon...</div>
    <div id="FRESCA_EVENT_OVER_MESSAGE">This live event is over.</div>
    <div id="FRESCA_COMPLETE_MESSAGE">Thanks for watching!</div>
    <div id="FRESCA_STAND_BY_MESSAGE">Please stand by.</div>
    <div id="FRESCA_TECHNICAL_DIFFICULTIES_MESSAGE">We're experiencing technical difficulties.</div>

    <div id="YPC_CLICKWRAP_HEADER">Would you like to start this rental?</div>
    <div id="YPC_CLICKWRAP_BUTTON">Start rental period</div>

    <div id="YTP_THREED_WARNING_DIALOG_TITLE">No HTML5 3D hardware detected</div>
    <div id="YTP_THREED_WARNING_DIALOG_MESSAGE">Get <a href="//support.google.com/youtube/bin/answer.py?answer=1229982&amp;hl=en">help setting up HTML5 3D</a>, or change 3D viewing modes.</div>
    <div id="YTP_THREED_WARNING_DIALOG_CHANGE_MODE">Change 3D viewing mode</div>
    -->
  </div>

  </div>

<script>var ytplayer = ytplayer || {};ytplayer.config = {"attrs": {"id": "movie_player"}, "assets": {"js": "\/\/s.ytimg.com\/yts\/jsbin\/html5player-en_US-vflMYwWq8.js", "css": "\/\/s.ytimg.com\/yts\/cssbin\/www-player-vfl5HOAyS.css", "html": "\/html5_player_template"}, "url_v8": "https:\/\/s.ytimg.com\/yts\/swfbin\/player-vflu1MO2p\/cps.swf", "min_version": "8.0.0", "url_v9as2": "https:\/\/s.ytimg.com\/yts\/swfbin\/player-vflu1MO2p\/cps.swf", "args": {"cr": "US", "enablejsapi": 1, "hl": "en_US", "autoplay": "0", "fexp": "907050,943601,938631,916807,914072,916612,936109,937417,913434,923328,936916,934022,936923", "ssl": "1"}, "params": {"allowfullscreen": "true", "allowscriptaccess": "always", "bgcolor": "#000000"}, "url": "https:\/\/s.ytimg.com\/yts\/swfbin\/player-vflu1MO2p\/watch_as3.swf", "html5": false, "sts": 16177};</script>



  <div id="playlist-tray" class="playlist-tray">
    
  </div>

  <div class="clear"></div>
</div>
<div id="content" class="  content-alignment  
">  




  <div class="branded-page-v2-container branded-page-base-bold-titles branded-page-v2-container-flex-width branded-page-v2-has-top-row" id="c4-overview-tab">
      <div class="branded-page-v2-top-row">
        




    <div class="branded-page-v2-header channel-header yt-card">
    <div id="gh-banner">
          <style>
      #c4-header-bg-container {
      background-image: url(//web.archive.org/web/20140705154919im_/http://i1.ytimg.com/u/j5i58mCkAREDqFWlhaQbOw/channels4_banner.jpg?v=51ae3b20);
  }


  @media screen and (-webkit-min-device-pixel-ratio: 1.5),
         screen and (min-resolution: 1.5dppx) {
#c4-header-bg-container {
        background-image: url(//web.archive.org/web/20140705154919im_/http://i1.ytimg.com/u/j5i58mCkAREDqFWlhaQbOw/channels4_banner_hd.jpg?v=51ae3b20);
    }
  }

#c4-header-bg-container .hd-banner-image {
      background-image: url(//web.archive.org/web/20140705154919im_/http://i1.ytimg.com/u/j5i58mCkAREDqFWlhaQbOw/channels4_banner_hd.jpg?v=51ae3b20);
  }

    </style>
  <div id="c4-header-bg-container" class=" has-custom-banner">
    <div class="hd-banner">
      <div class="hd-banner-image"></div>
    </div>
          <div id="header-links">
      <ul class="about-network-links">
            <li class="channel-links-item">
    <a href="https://web.archive.org/web/20140705154919/https://plus.google.com/102183245191097332237" rel="me nofollow" target="_blank" title="" class="about-channel-link yt-uix-redirect-link about-channel-link-with-icon">
        <img src="//web.archive.org/web/20140705154919im_/http://s2.googleusercontent.com/s2/favicons?domain=plus.google.com&amp;feature=youtube_channel" class="about-channel-link-favicon" alt="" width="16" height="16">
    </a>
  </li>

            <li class="channel-links-item">
    <a href="https://web.archive.org/web/20140705154919/http://www.facebook.com/Stampylongnose" rel="me nofollow" target="_blank" title="" class="about-channel-link yt-uix-redirect-link about-channel-link-with-icon">
        <img src="//web.archive.org/web/20140705154919im_/http://s2.googleusercontent.com/s2/favicons?domain=www.facebook.com&amp;feature=youtube_channel" class="about-channel-link-favicon" alt="" width="16" height="16">
    </a>
  </li>

            <li class="channel-links-item">
    <a href="https://web.archive.org/web/20140705154919/https://twitter.com/stampylongnose" rel="me nofollow" target="_blank" title="" class="about-channel-link yt-uix-redirect-link about-channel-link-with-icon">
        <img src="//web.archive.org/web/20140705154919im_/http://s2.googleusercontent.com/s2/favicons?domain=twitter.com&amp;feature=youtube_channel" class="about-channel-link-favicon" alt="" width="16" height="16">
    </a>
  </li>

      </ul>

  </div>



          <a class="channel-header-profile-image-container spf-link" href="/web/20140705154919/http://www.youtube.com/user/stampylonghead">
      <img class="channel-header-profile-image" src="https://web.archive.org/web/20140705154919im_/https://yt3.ggpht.com/-bY_OkstVA0g/AAAAAAAAAAI/AAAAAAAAAAA/x2CqwQ35Dco/s100-c-k-no/photo.jpg" title="stampylonghead" alt="stampylonghead">
    </a>

  </div>

    </div>
      
  <div class="">
    <div class="primary-header-contents" id="c4-primary-header-contents">
      <div class="primary-header-actions clearfix">
                <span class="channel-header-subscription-button-container yt-uix-button-subscription-container with-preferences"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-branded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer" type="button" onclick=";return false;" aria-role="button" aria-live="polite" aria-busy="false" data-channel-external-id="UCj5i58mCkAREDqFWlhaQbOw" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbjhxVVM2SHRGZVh2ZHR5S0FxV1ZzU1E2UG1jUXxBQ3Jtc0trUld2bzJDTVZDX2diZndiSmJwLW9BbnBDNXZXN0VFRzJDbUROeUJHUmlkZTBwVkdMdEt3bTlacEJvXzNzX3JTZmx6MTZ6NlpSWDRWd1E1eWFLcnpsaE5ybjh0RjFOaDlVaC1FNklrRHdZQlQyOFpHYV9fQlhFNDd4ejJMTXhwU2ZuYVBHTUpXdGtuQ3pfdkx6S1RBM3VTVDh2aUkwbVFpOHIyQWhNaHh1Wkd4eHhIUUlUWDVqeThROE52WFlWYzRldC1hREs%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCj5i58mCkAREDqFWlhaQbOw&amp;hl=en&amp;uilel=3&amp;service=youtube" data-style-type="branded" data-sessionlink="ved=CBgQmys&amp;feature=channels4&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-default yt-uix-button-empty yt-uix-button-has-icon yt-uix-subscription-preferences-button" type="button" onclick=";return false;" data-channel-external-id="UCj5i58mCkAREDqFWlhaQbOw"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-icon yt-uix-button-icon-subscription-preferences"></span></button><span class="yt-subscription-button-subscriber-count-branded-horizontal subscribed">3,054,642</span>  <span class="yt-subscription-button-disabled-mask" title=""></span>
  
  <div class="yt-uix-overlay " data-overlay-style="primary" data-overlay-shape="tiny">
    
        <div class="yt-dialog hid ">
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
      <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Loading icon" class="yt-spinner-img">

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
              <div class="yt-dialog-working-overlay"></div>
  <div class="yt-dialog-working-bubble">
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
        <span class="qualified-channel-title ellipsized has-badge"><span class="qualified-channel-title-wrapper"><span dir="ltr" class="qualified-channel-title-text"><a dir="ltr" href="<?php if($_user['vanity'])	{	?>/<?php echo htmlspecialchars($_user['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($_user['username']); ?><?php } ?>" class="spf-link branded-page-header-title-link yt-uix-sessionlink" title="<?php if($_user['title'])	{	?> <?php echo htmlspecialchars($_user['title']); ?> <?php } else {	?> <?php echo htmlspecialchars($_user['username']); ?> <?php	}	?>" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><?php if($_user['title'])	{	?> <?php echo htmlspecialchars($_user['title']); ?> <?php } else {	?> <?php echo htmlspecialchars($_user['username']); ?> <?php	}	?></a></span></span><a class="qualified-channel-title-badge" href="//web.archive.org/web/20140705154919/http://support.google.com/youtube/bin/answer.py?answer=3046484&amp;hl=en" target="_blank"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"></a></span>
        
      </h1>
    </div>
      <div id="channel-subheader" class="clearfix branded-page-gutter-padding appbar-content-trigger">
    <ul id="channel-navigation-menu" class="clearfix">
        <li>
          <h2 class="epic-nav-item-heading ">Home</h2>
        </li>
        <li>
          <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead/videos" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item yt-uix-button-size-default" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-content">Videos </span></a>
        </li>
        <li>
          <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead/playlists" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item yt-uix-button-size-default" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-content">Playlists </span></a>
        </li>
        <li>
          <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead/channels" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item yt-uix-button-size-default" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-content">Channels </span></a>
        </li>
        <li>
          <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead/discussion" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item yt-uix-button-size-default" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-content">Discussion </span></a>
        </li>
        <li>
          <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead/about" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item yt-uix-button-size-default" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-content">About </span></a>
        </li>
        <li>
            <div id="channel-search"><label class="show-search epic-nav-item secondary-nav" for="channels-search-field"><img class="epic-nav-item-heading-icon" src="//web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Search Channel"></label><form class="search-form epic-nav-item secondary-nav" action="/web/20140705154919/http://www.youtube.com/user/stampylonghead/search" method="get"><span class=" yt-uix-form-input-container yt-uix-form-input-text-container ">    <input class="yt-uix-form-input-text search-field" name="query" id="channels-search-field" type="text" placeholder="Search Channel" maxlength="100" autocomplete="off">
</span></form></div>
        </li>
    </ul>
  </div>

  </div>

  </div>


      </div>

    <div class="branded-page-v2-col-container">
      <div class="branded-page-v2-col-container-inner">
        <div class="branded-page-v2-primary-col">
          <div class="   yt-card  clearfix">
              <div class="branded-page-v2-body branded-page-v2-primary-column-content" id="gh-overviewtab">
      
<?php if($_user['featured'] != "None") { $video = $__video_h->fetch_video_rid($_user['featured']); } else { $_user['featured'] = false; } ?>
<?php if($_user['featured'] != false && $__video_h->video_exists($_user['featured'])) { ?>
      <div class="c4-spotlight-module  yt-section-hover-container">
      <div class="c4-spotlight-module-component upsell">
          
  <div class="upsell-video-container yt-section-hover-container">
          <div class="video-player-view-component branded-page-box">
    <div class="video-content clearfix ">
        <div class="c4-player-container  c4-flexible-player-container">
      <div class="c4-flexible-height-setter"></div>
      <div id="upsell-video" class="c4-flexible-player-box" data-video-id="KOFm-JBOqFc" data-swf-config="{&amp;quot;min_version&amp;quot;: &amp;quot;8.0.0&amp;quot;, &amp;quot;url_v9as2&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/player-vfl2ziCRD\/cps.swf&amp;quot;, &amp;quot;url_v8&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/player-vfl2ziCRD\/cps.swf&amp;quot;, &amp;quot;assets&amp;quot;: {&amp;quot;html&amp;quot;: &amp;quot;\/html5_player_template&amp;quot;, &amp;quot;js&amp;quot;: &amp;quot;\/\/s.ytimg.com\/yts\/jsbin\/html5player-en_US-vflhZC-Jn.js&amp;quot;, &amp;quot;css&amp;quot;: &amp;quot;\/\/s.ytimg.com\/yts\/cssbin\/www-player-vflwl1JvU.css&amp;quot;}, &amp;quot;args&amp;quot;: {&amp;quot;timestamp&amp;quot;: 1404575360, &amp;quot;width&amp;quot;: &amp;quot;360&amp;quot;, &amp;quot;eventid&amp;quot;: &amp;quot;gB64U72tCo2h-AOms4GAAQ&amp;quot;, &amp;quot;cc3_module&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;eurl&amp;quot;: &amp;quot;http:\/\/www.youtube.com\/user\/stampylonghead&amp;quot;, &amp;quot;rel&amp;quot;: 0, &amp;quot;showinfo&amp;quot;: &amp;quot;0&amp;quot;, &amp;quot;height&amp;quot;: &amp;quot;203&amp;quot;, &amp;quot;track_embed&amp;quot;: 1, &amp;quot;is_purchased&amp;quot;: false, &amp;quot;quality_cap&amp;quot;: &amp;quot;highres&amp;quot;, &amp;quot;autoplay&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;keywords&amp;quot;: &amp;quot;Stampy,Stampylongnose,Stampylonghead,Youtube,Channel,Trailer,Minecraft,Video,Game,Gameplay,Lets Play,Video Game (Industry),Commentary,Funny,New,Today,2013,Best,Most&amp;quot;, &amp;quot;avg_rating&amp;quot;: 4.66736401674, &amp;quot;allow_ratings&amp;quot;: 1, &amp;quot;tmi&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;no_get_video_log&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;cc_asr&amp;quot;: 1, &amp;quot;iurlsd&amp;quot;: &amp;quot;http:\/\/i1.ytimg.com\/vi\/KOFm-JBOqFc\/sddefault.jpg&amp;quot;, &amp;quot;vq&amp;quot;: &amp;quot;auto&amp;quot;, &amp;quot;cr&amp;quot;: &amp;quot;US&amp;quot;, &amp;quot;delay&amp;quot;: &amp;quot;9&amp;quot;, &amp;quot;fexp&amp;quot;: &amp;quot;902408,903945,914020,916625,924213,924217,924222,930008,931331,934024,934030,945011,950841&amp;quot;, &amp;quot;has_cc&amp;quot;: true, &amp;quot;author&amp;quot;: &amp;quot;stampylonghead&amp;quot;, &amp;quot;muted&amp;quot;: &amp;quot;0&amp;quot;, &amp;quot;ps&amp;quot;: &amp;quot;default&amp;quot;, &amp;quot;iurlhq&amp;quot;: &amp;quot;http:\/\/i1.ytimg.com\/vi\/KOFm-JBOqFc\/hqdefault.jpg&amp;quot;, &amp;quot;plid&amp;quot;: &amp;quot;AAT9dC9sfdHPI8RN&amp;quot;, &amp;quot;allow_embed&amp;quot;: 1, &amp;quot;referrer&amp;quot;: &amp;quot;http:\/\/www.youtube.com\/stampylonghead&amp;quot;, &amp;quot;ptk&amp;quot;: &amp;quot;youtube_none&amp;quot;, &amp;quot;ttsurl&amp;quot;: &amp;quot;http:\/\/www.youtube.com\/api\/timedtext?caps=asr\u0026signature=8A5176809227C56530AAFE427D9794F25DF68E8B.0C6627BE7A22F1096922D0D51C05E49250FA0650\u0026asr_langs=ru%2Cfr%2Cit%2Cnl%2Ces%2Cde%2Cko%2Cen%2Cja%2Cpt\u0026expire=1404600560\u0026sparams=asr_langs%2Ccaps%2Cv%2Cexpire\u0026v=KOFm-JBOqFc\u0026key=yttt1\u0026hl=en_US&amp;quot;, &amp;quot;dashmpd&amp;quot;: &amp;quot;http:\/\/manifest.googlevideo.com\/api\/manifest\/dash\/ipbits\/0\/ip\/207.241.237.221\/sver\/3\/signature\/3B72E65E8CFBF6EDADB521B62712EBFE017DF762.B12DAE057E3D0DC95A8B32A4ED487BF3725084FC\/mws\/yes\/as\/fmp4_audio_clear%2Cwebm_audio_clear%2Cfmp4_sd_hd_clear%2Cwebm_sd_hd_clear\/fexp\/902408%2C903945%2C914020%2C916625%2C924213%2C924217%2C924222%2C930008%2C931331%2C934024%2C934030%2C945011%2C950841\/upn\/ASiFFksVxwQ\/playback_host\/r16---sn-nwj7kne6.googlevideo.com\/expire\/1404597600\/sparams\/as%2Cid%2Cip%2Cipbits%2Citag%2Cplayback_host%2Csource%2Cexpire\/id\/o-AJ85nmeWHH8vTSVAifSODz0iPw6hTv5HB6N6X035e4kl\/key\/yt5\/source\/youtube\/mt\/1404575280\/mv\/m\/itag\/0\/ms\/au&amp;quot;, &amp;quot;autohide&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;watermark&amp;quot;: &amp;quot;,http:\/\/s.ytimg.com\/yts\/img\/watermark\/youtube_watermark-vflHX6b6E.png,http:\/\/s.ytimg.com\/yts\/img\/watermark\/youtube_hd_watermark-vflAzLcD6.png&amp;quot;, &amp;quot;use_cipher_signature&amp;quot;: false, &amp;quot;idpj&amp;quot;: &amp;quot;-8&amp;quot;, &amp;quot;iurl&amp;quot;: &amp;quot;http:\/\/i1.ytimg.com\/vi\/KOFm-JBOqFc\/hqdefault.jpg&amp;quot;, &amp;quot;video_id&amp;quot;: &amp;quot;KOFm-JBOqFc&amp;quot;, &amp;quot;fmt_list&amp;quot;: &amp;quot;22\/1280x720\/9\/0\/115,43\/640x360\/99\/0\/0,18\/640x360\/9\/0\/115,5\/320x240\/7\/0\/0,36\/320x240\/99\/1\/0,17\/176x144\/99\/1\/0&amp;quot;, &amp;quot;enablejsapi&amp;quot;: 1, &amp;quot;el&amp;quot;: &amp;quot;profilepage&amp;quot;, &amp;quot;status&amp;quot;: &amp;quot;ok&amp;quot;, &amp;quot;iurlmaxres&amp;quot;: &amp;quot;http:\/\/i1.ytimg.com\/vi\/KOFm-JBOqFc\/maxresdefault.jpg&amp;quot;, &amp;quot;cc_module&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/player-vfl2ziCRD\/subtitle_module.swf&amp;quot;, &amp;quot;url_encoded_fmt_stream_map&amp;quot;: &amp;quot;url=http%3A%2F%2Fr16---sn-nwj7kne6.googlevideo.com%2Fvideoplayback%3Fipbits%3D0%26ip%3D207.241.237.221%26mt%3D1404575280%26signature%3D5576A8182645C633EE93754CF1A381D6FE9C5FBF.21293987898C312714505F8C21AABF79ED96C166%26mws%3Dyes%26sver%3D3%26fexp%3D902408%252C903945%252C914020%252C916625%252C924213%252C924217%252C924222%252C930008%252C931331%252C934024%252C934030%252C945011%252C950841%26upn%3DsR4DVBCtlzg%26expire%3D1404597600%26sparams%3Did%252Cinitcwndbps%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire%26id%3Do-AJ85nmeWHH8vTSVAifSODz0iPw6hTv5HB6N6X035e4kl%26key%3Dyt5%26source%3Dyoutube%26ratebypass%3Dyes%26mv%3Dm%26initcwndbps%3D1101000%26itag%3D22%26ms%3Dau\u0026fallback_host=tc.v23.cache5.googlevideo.com\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.64001F%2C+mp4a.40.2%22\u0026itag=22\u0026quality=hd720,url=http%3A%2F%2Fr16---sn-nwj7kne6.googlevideo.com%2Fvideoplayback%3Fipbits%3D0%26ip%3D207.241.237.221%26mt%3D1404575280%26signature%3D6397B66B43405CB089430F38475817B1AB8876AA.8FFDA4060D85595E2D8B8C8F4766F057F142A2E5%26mws%3Dyes%26sver%3D3%26fexp%3D902408%252C903945%252C914020%252C916625%252C924213%252C924217%252C924222%252C930008%252C931331%252C934024%252C934030%252C945011%252C950841%26upn%3DsR4DVBCtlzg%26expire%3D1404597600%26sparams%3Did%252Cinitcwndbps%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire%26id%3Do-AJ85nmeWHH8vTSVAifSODz0iPw6hTv5HB6N6X035e4kl%26key%3Dyt5%26source%3Dyoutube%26ratebypass%3Dyes%26mv%3Dm%26initcwndbps%3D1101000%26itag%3D43%26ms%3Dau\u0026fallback_host=tc.v23.cache2.googlevideo.com\u0026type=video%2Fwebm%3B+codecs%3D%22vp8.0%2C+vorbis%22\u0026itag=43\u0026quality=medium,url=http%3A%2F%2Fr16---sn-nwj7kne6.googlevideo.com%2Fvideoplayback%3Fipbits%3D0%26ip%3D207.241.237.221%26mt%3D1404575280%26signature%3D15F654D1356CF20FED28DC983C8DA1B6871DB7A3.AF2559FDA7444086BC2A63DF2866F255B74FC9DE%26mws%3Dyes%26sver%3D3%26fexp%3D902408%252C903945%252C914020%252C916625%252C924213%252C924217%252C924222%252C930008%252C931331%252C934024%252C934030%252C945011%252C950841%26upn%3DsR4DVBCtlzg%26expire%3D1404597600%26sparams%3Did%252Cinitcwndbps%252Cip%252Cipbits%252Citag%252Cratebypass%252Csource%252Cupn%252Cexpire%26id%3Do-AJ85nmeWHH8vTSVAifSODz0iPw6hTv5HB6N6X035e4kl%26key%3Dyt5%26source%3Dyoutube%26ratebypass%3Dyes%26mv%3Dm%26initcwndbps%3D1101000%26itag%3D18%26ms%3Dau\u0026fallback_host=tc.v2.cache6.googlevideo.com\u0026type=video%2Fmp4%3B+codecs%3D%22avc1.42001E%2C+mp4a.40.2%22\u0026itag=18\u0026quality=medium,url=http%3A%2F%2Fr16---sn-nwj7kne6.googlevideo.com%2Fvideoplayback%3Fipbits%3D0%26ip%3D207.241.237.221%26mt%3D1404575280%26signature%3D81F0C2D13A12F1D4536D734FC4C77803B42DF394.60CC14BDB8F6F8EB419D7039C3D34CC3029DE251%26mws%3Dyes%26sver%3D3%26fexp%3D902408%252C903945%252C914020%252C916625%252C924213%252C924217%252C924222%252C930008%252C931331%252C934024%252C934030%252C945011%252C950841%26upn%3DsR4DVBCtlzg%26expire%3D1404597600%26sparams%3Did%252Cinitcwndbps%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire%26id%3Do-AJ85nmeWHH8vTSVAifSODz0iPw6hTv5HB6N6X035e4kl%26key%3Dyt5%26source%3Dyoutube%26mv%3Dm%26initcwndbps%3D1101000%26itag%3D5%26ms%3Dau\u0026fallback_host=tc.v17.cache6.googlevideo.com\u0026type=video%2Fx-flv\u0026itag=5\u0026quality=small,url=http%3A%2F%2Fr16---sn-nwj7kne6.googlevideo.com%2Fvideoplayback%3Fipbits%3D0%26ip%3D207.241.237.221%26mt%3D1404575280%26signature%3D083552FAA6C0C6933E66123BC009DBE298ECC9F6.3EA35205BD73A44E50CC596D4FC8E8211D998697%26mws%3Dyes%26sver%3D3%26fexp%3D902408%252C903945%252C914020%252C916625%252C924213%252C924217%252C924222%252C930008%252C931331%252C934024%252C934030%252C945011%252C950841%26upn%3DsR4DVBCtlzg%26expire%3D1404597600%26sparams%3Did%252Cinitcwndbps%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire%26id%3Do-AJ85nmeWHH8vTSVAifSODz0iPw6hTv5HB6N6X035e4kl%26key%3Dyt5%26source%3Dyoutube%26mv%3Dm%26initcwndbps%3D1101000%26itag%3D36%26ms%3Dau\u0026fallback_host=tc.v6.cache7.googlevideo.com\u0026type=video%2F3gpp%3B+codecs%3D%22mp4v.20.3%2C+mp4a.40.2%22\u0026itag=36\u0026quality=small,url=http%3A%2F%2Fr16---sn-nwj7kne6.googlevideo.com%2Fvideoplayback%3Fipbits%3D0%26ip%3D207.241.237.221%26mt%3D1404575280%26signature%3D8D447336759CEBDEDB0E5299DA08CBD88EFE4406.A697CF22FDD229AC5C1DA6EA9D64915F359F1DBC%26mws%3Dyes%26sver%3D3%26fexp%3D902408%252C903945%252C914020%252C916625%252C924213%252C924217%252C924222%252C930008%252C931331%252C934024%252C934030%252C945011%252C950841%26upn%3DsR4DVBCtlzg%26expire%3D1404597600%26sparams%3Did%252Cinitcwndbps%252Cip%252Cipbits%252Citag%252Csource%252Cupn%252Cexpire%26id%3Do-AJ85nmeWHH8vTSVAifSODz0iPw6hTv5HB6N6X035e4kl%26key%3Dyt5%26source%3Dyoutube%26mv%3Dm%26initcwndbps%3D1101000%26itag%3D17%26ms%3Dau\u0026fallback_host=tc.v2.cache6.googlevideo.com\u0026type=video%2F3gpp%3B+codecs%3D%22mp4v.20.3%2C+mp4a.40.2%22\u0026itag=17\u0026quality=small&amp;quot;, &amp;quot;iurlmq&amp;quot;: &amp;quot;http:\/\/i1.ytimg.com\/vi\/KOFm-JBOqFc\/mqdefault.jpg&amp;quot;, &amp;quot;is_video_preview&amp;quot;: false, &amp;quot;dash&amp;quot;: &amp;quot;1&amp;quot;, &amp;quot;account_playback_token&amp;quot;: &amp;quot;QUFFLUhqbXVoTHk4TkZtcXpVdFB5d2FWSnJuOGhBdWhaZ3xBQ3Jtc0trM2hrSWlsY0Z0VU1HNlhNa255cUlFOGhvZklxeFlpVktOaGFFQ25YRklfbWo0QnlnbzFRYlJtOWlFWnZ6R0NjMjJrcnhaUlNZbDlPWG9Qcjg4aHBHRERTSnlrQ3NGMzVNYkdJYU9BanMxU01MWnMwMA==&amp;quot;, &amp;quot;token&amp;quot;: &amp;quot;vjVQa1PpcFPHiQAtIr7pXxYmGbDFXyBaOSTPE58P984=&amp;quot;, &amp;quot;pltype&amp;quot;: &amp;quot;contentugc&amp;quot;, &amp;quot;cc_font&amp;quot;: &amp;quot;Arial Unicode MS, arial, verdana, _sans&amp;quot;, &amp;quot;length_seconds&amp;quot;: 159, &amp;quot;ldpj&amp;quot;: &amp;quot;-21&amp;quot;, &amp;quot;view_count&amp;quot;: 3038143, &amp;quot;thumbnail_url&amp;quot;: &amp;quot;http:\/\/i1.ytimg.com\/vi\/KOFm-JBOqFc\/default.jpg&amp;quot;, &amp;quot;hl&amp;quot;: &amp;quot;en_US&amp;quot;}, &amp;quot;attrs&amp;quot;: {&amp;quot;id&amp;quot;: &amp;quot;c4-player&amp;quot;}, &amp;quot;params&amp;quot;: {&amp;quot;allowscriptaccess&amp;quot;: &amp;quot;always&amp;quot;, &amp;quot;bgcolor&amp;quot;: &amp;quot;#000000&amp;quot;, &amp;quot;allowfullscreen&amp;quot;: &amp;quot;true&amp;quot;}, &amp;quot;sts&amp;quot;: 16252, &amp;quot;url&amp;quot;: &amp;quot;http:\/\/s.ytimg.com\/yts\/swfbin\/player-vfl2ziCRD\/watch_as3.swf&amp;quot;, &amp;quot;html5&amp;quot;: false}">
  </div>

  </div>

        <div class="video-detail">
      <h3 class="title">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=KOFm-JBOqFc" title="Stampy - Youtube Channel Trailer" class="yt-uix-sessionlink yt-ui-ellipsis yt-ui-ellipsis-2  spf-link " data-sessionlink="feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
          <?php echo htmlspecialchars($video['title']); ?>
        </a>
      </h3>
      <div class="view-count">
        <span class="count">
          <?php echo $__video_h->fetch_video_views($video['rid']); ?> views
        </span>
          <span class="content-item-time-created">
            <?php echo $__time_h->time_elapsed_string($video['publish']); ?>
          </span>
      </div>
      <div class="description yt-uix-expander yt-uix-expander-ellipsis yt-ui-ellipsis-10 yt-uix-expander-collapsed">
        <div class="yt-ui-ellipsis yt-ui-ellipsis-10">
          <?php echo $__video_h->shorten_description($video['description'], 60, true); ?>
      </div>
  </div>

      <div class="video-content-info">
      </div>
    </div>
  </div>

  </div>

      </div>
</div><?php } ?>

      <div id="c4-shelves-container">
                  <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix branded-page-box yt-section-hover-container fluid-shelf yt-uix-tdl" id="" data-sessionlink="ved=CB0Q3BwoAA&amp;ei=gB64U72tCo2h-AOms4GAAQ">
              <h2 class="branded-page-module-title">
      <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead/videos?sort=dd&amp;shelf_id=1&amp;view=0" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ">
            <span class="branded-page-module-title-text">
      <span class="">Uploads</span>
    </span>

      </a>
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=3yGwxcARGJ0&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="yt-uix-button  shelves-play yt-uix-sessionlink yt-uix-button-default yt-uix-button-size-small" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-icon yt-uix-button-icon-play-all"></span><span class="yt-uix-button-content">Play </span></a>

  </h2>


    

    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="3yGwxcARGJ0">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=3yGwxcARGJ0&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CCAQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/3yGwxcARGJ0/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="3yGwxcARGJ0" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">18:35</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft Xbox - The Forgotten Vale - The Library - {3}" data-sessionlink="ved=CB8Qvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=3yGwxcARGJ0&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Minecraft Xbox - The Forgotten Vale - The Library - {3}</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>216,735 views</li>
        <li class="yt-lockup-deemphasized-text">
            20 hours ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="iyTWqUZNT_c">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=iyTWqUZNT_c&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CCMQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/iyTWqUZNT_c/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="iyTWqUZNT_c" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">20:56</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Disney Infinity - Toy Story In Space - Part 1" data-sessionlink="ved=CCIQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=iyTWqUZNT_c&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Disney Infinity - Toy Story In Space - Part 1</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>226,299 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 day ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="pfbEo06Yjpc">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=pfbEo06Yjpc&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CCYQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/pfbEo06Yjpc/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="pfbEo06Yjpc" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:21</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft Xbox - Potion Challenge - Part 1" data-sessionlink="ved=CCUQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=pfbEo06Yjpc&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Minecraft Xbox - Potion Challenge - Part 1</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>577,126 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 day ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="cXCtifKGgvg">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=cXCtifKGgvg&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CCkQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/cXCtifKGgvg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="cXCtifKGgvg" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">30:49</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Lego The Hobbit - Great Goblin - Part 10" data-sessionlink="ved=CCgQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=cXCtifKGgvg&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Lego The Hobbit - Great Goblin - Part 10</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>176,141 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 days ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="7tj3iNxbTsU">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=7tj3iNxbTsU&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CCwQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/7tj3iNxbTsU/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="7tj3iNxbTsU" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:29</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft Xbox - Pushy Pirates [208]" data-sessionlink="ved=CCsQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=7tj3iNxbTsU&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Minecraft Xbox - Pushy Pirates [208]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>1,133,807 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 days ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="LFJG8X9eJRo">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=LFJG8X9eJRo&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CC8QwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/LFJG8X9eJRo/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="LFJG8X9eJRo" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">23:34</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft Xbox - The Forgotten Vale - The Citadel - {2}" data-sessionlink="ved=CC4Qvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=LFJG8X9eJRo&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Minecraft Xbox - The Forgotten Vale - The Citadel - {2}</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>525,498 views</li>
        <li class="yt-lockup-deemphasized-text">
            3 days ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="M6e_9Z0PaDM">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=M6e_9Z0PaDM&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CDIQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/M6e_9Z0PaDM/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="M6e_9Z0PaDM" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:15</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Nazcaa  [85]" data-sessionlink="ved=CDEQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=M6e_9Z0PaDM&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Ni No Kuni: Wrath Of The White Witch - Nazcaa  [85]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>192,079 views</li>
        <li class="yt-lockup-deemphasized-text">
            4 days ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="vX5bjfYw3lI">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=vX5bjfYw3lI&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CDUQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/vX5bjfYw3lI/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="vX5bjfYw3lI" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:09</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft Xbox - Quest For a Ice Cream Slide (39)" data-sessionlink="ved=CDQQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=vX5bjfYw3lI&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Minecraft Xbox - Quest For a Ice Cream Slide (39)</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>959,018 views</li>
        <li class="yt-lockup-deemphasized-text">
            4 days ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="0xihXAzral0">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=0xihXAzral0&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CDgQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/0xihXAzral0/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="0xihXAzral0" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:51</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft Xbox - Disney Pixar - Hunger Games" data-sessionlink="ved=CDcQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=0xihXAzral0&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Minecraft Xbox - Disney Pixar - Hunger Games</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>1,336,378 views</li>
        <li class="yt-lockup-deemphasized-text">
            5 days ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="pJlMdFg8Fsc">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=pJlMdFg8Fsc&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CDsQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/pJlMdFg8Fsc/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="pJlMdFg8Fsc" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:05</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft Xbox - Treasure Chest [207]" data-sessionlink="ved=CDoQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=pJlMdFg8Fsc&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Minecraft Xbox - Treasure Chest [207]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>2,055,084 views</li>
        <li class="yt-lockup-deemphasized-text">
            6 days ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="CC2XB3sI50w">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=CC2XB3sI50w&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CD4QwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/CC2XB3sI50w/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="CC2XB3sI50w" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:53</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft Xbox - The Forgotten Vale - Big Door - {1}" data-sessionlink="ved=CD0Qvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=CC2XB3sI50w&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Minecraft Xbox - The Forgotten Vale - Big Door - {1}</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>838,552 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 week ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="TdibBjzJa3g">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=TdibBjzJa3g&amp;list=UUj5i58mCkAREDqFWlhaQbOw" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CEEQwBs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/TdibBjzJa3g/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="TdibBjzJa3g" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">16:52</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Mario Kart 8 - Grand Prix - Banana Cup" data-sessionlink="ved=CEAQvxs&amp;feature=c4-overview-u&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=TdibBjzJa3g&amp;list=UUj5i58mCkAREDqFWlhaQbOw">Mario Kart 8 - Grand Prix - Banana Cup</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
      <li>556,470 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 week ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
          <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item compact-shelf-view-all-card">
    <a class="compact-shelf-view-all-card-link yt-valign" href="    /user/stampylonghead/videos?sort=dd&amp;shelf_id=1&amp;view=0
" yt-uix-sessionlink="" spf-link="" &quot;="" data-sessionlink="&quot;&quot;">
      <h4 class="compact-shelf-view-all-card-link-text yt-valign-container">
        <span class="">500+ more</span>
      </h4>
    </a>
  </li>

    </ul>
  </div>


      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-prev" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-next" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

  


                  <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix branded-page-box yt-section-hover-container fluid-shelf yt-uix-tdl" id="" data-sessionlink="ved=CEIQ3BwoAQ&amp;ei=gB64U72tCo2h-AOms4GAAQ">
              <h2 class="branded-page-module-title">
      <a href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ">
            <span class="branded-page-module-title-text">
      <span class="">Minecraft - Main Series</span>
    </span>

      </a>
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=EWRscl-GPCg&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="yt-uix-button  shelves-play yt-uix-sessionlink yt-uix-button-default yt-uix-button-size-small" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-icon yt-uix-button-icon-play-all"></span><span class="yt-uix-button-content">Play </span></a>

  </h2>


        <div class="shelf-description yt-ui-ellipsis yt-ui-ellipsis-2">
        Welcome to my main series of Minecraft lets plays on the Xbox 360 Edition. In this playlist you can watch through all of my Minecraft videos in my lovely world.
    </div>


    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="EWRscl-GPCg">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=EWRscl-GPCg&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CEYQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/EWRscl-GPCg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="EWRscl-GPCg" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">23:47</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Welcome To Stampy's Lovely World [1]" data-sessionlink="ved=CEUQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=EWRscl-GPCg&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Welcome To Stampy's Lovely World [1]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CEQQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>3,031,923 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="xkOsAelrvyI">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=xkOsAelrvyI&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CEoQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/xkOsAelrvyI/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="xkOsAelrvyI" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">18:55</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Stampy's First Home [2]" data-sessionlink="ved=CEkQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=xkOsAelrvyI&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Stampy's First Home [2]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CEgQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>2,460,812 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="JVLZBrt86-o">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=JVLZBrt86-o&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CE4QwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/JVLZBrt86-o/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="JVLZBrt86-o" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">24:16</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Visiting Crimcity [3]" data-sessionlink="ved=CE0Qvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=JVLZBrt86-o&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Visiting Crimcity [3]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CEwQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>2,484,786 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="_0MSZj0RhrY">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=_0MSZj0RhrY&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CFIQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/_0MSZj0RhrY/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="_0MSZj0RhrY" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:44</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Stampy's Treehouse [4]" data-sessionlink="ved=CFEQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=_0MSZj0RhrY&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Stampy's Treehouse [4]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CFAQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,024,181 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="PUl7xqfC6BE">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=PUl7xqfC6BE&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CFYQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/PUl7xqfC6BE/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="PUl7xqfC6BE" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:29</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Underwater Mine Track [5]" data-sessionlink="ved=CFUQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=PUl7xqfC6BE&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Underwater Mine Track [5]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CFQQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,336,816 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="sKsbpkoazHs">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=sKsbpkoazHs&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CFoQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/sKsbpkoazHs/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="sKsbpkoazHs" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:22</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Gregory The Dog [6]" data-sessionlink="ved=CFkQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=sKsbpkoazHs&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Gregory The Dog [6]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CFgQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,917,255 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="Wk5go1kGlM0">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=Wk5go1kGlM0&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CF4QwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/Wk5go1kGlM0/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="Wk5go1kGlM0" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:18</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - White Chocolate Paradise [7]" data-sessionlink="ved=CF0Qvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=Wk5go1kGlM0&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - White Chocolate Paradise [7]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CFwQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,285,245 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="GH4M2Pzpjqo">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=GH4M2Pzpjqo&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CGIQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/GH4M2Pzpjqo/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="GH4M2Pzpjqo" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">25:25</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Inconvenient Death [8]" data-sessionlink="ved=CGEQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=GH4M2Pzpjqo&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Inconvenient Death [8]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CGAQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>845,583 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="LmRNVAvTCEg">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=LmRNVAvTCEg&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CGYQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/LmRNVAvTCEg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="LmRNVAvTCEg" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">23:04</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Journey Into The Nether [9]" data-sessionlink="ved=CGUQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=LmRNVAvTCEg&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Journey Into The Nether [9]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CGQQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,460,430 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="Piuf_F8pF7Q">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=Piuf_F8pF7Q&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CGoQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/Piuf_F8pF7Q/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="Piuf_F8pF7Q" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">28:00</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Fun At The Farm [10]" data-sessionlink="ved=CGkQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=Piuf_F8pF7Q&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Fun At The Farm [10]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CGgQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,230,730 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="ps7KNwd5lF8">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=ps7KNwd5lF8&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CG4QwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/ps7KNwd5lF8/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="ps7KNwd5lF8" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">26:13</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - My Lovely Doghouse [11]" data-sessionlink="ved=CG0Qvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=ps7KNwd5lF8&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - My Lovely Doghouse [11]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CGwQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,861,270 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="NMRfDIOAs_I">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=NMRfDIOAs_I&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CHIQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/NMRfDIOAs_I/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="NMRfDIOAs_I" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:50</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Back In Crimcity [13]" data-sessionlink="ved=CHEQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=NMRfDIOAs_I&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Back In Crimcity [13]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CHAQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,168,067 views</li>
        <li class="yt-lockup-deemphasized-text">
            2 years ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
          <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item compact-shelf-view-all-card">
    <a class="compact-shelf-view-all-card-link yt-valign" href="    /playlist?list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V
" yt-uix-sessionlink="" spf-link="" &quot;="" data-sessionlink="&quot;&quot;">
      <h4 class="compact-shelf-view-all-card-link-text yt-valign-container">
        <span class="">100+ more</span>
      </h4>
    </a>
  </li>

    </ul>
  </div>


      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-prev" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-next" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

  


                  <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix branded-page-box yt-section-hover-container fluid-shelf yt-uix-tdl" id="" data-sessionlink="ved=CHMQ3BwoAg&amp;ei=gB64U72tCo2h-AOms4GAAQ">
              <h2 class="branded-page-module-title">
      <a href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ">
            <span class="branded-page-module-title-text">
      <span class="">Lets Play - Terraria Xbox 360 Edition</span>
    </span>

      </a>
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=V-HES8B9Rfo&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="yt-uix-button  shelves-play yt-uix-sessionlink yt-uix-button-default yt-uix-button-size-small" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-icon yt-uix-button-icon-play-all"></span><span class="yt-uix-button-content">Play </span></a>

  </h2>


        <div class="shelf-description yt-ui-ellipsis yt-ui-ellipsis-2">
        Welcome to my Let's Play of the Xbox 360 Edition of Terraria. In this series I will learn how to play the game while I have fun in my wonderful world.
    </div>


    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="V-HES8B9Rfo">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=V-HES8B9Rfo&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CHcQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/V-HES8B9Rfo/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="V-HES8B9Rfo" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:00</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - My First Night [1]" data-sessionlink="ved=CHYQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=V-HES8B9Rfo&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - My First Night [1]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CHUQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>1,434,929 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="2GhrhK548Ng">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=2GhrhK548Ng&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CHsQwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/2GhrhK548Ng/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="2GhrhK548Ng" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:23</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - Stampy's Wonderful Hut [2]" data-sessionlink="ved=CHoQvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=2GhrhK548Ng&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - Stampy's Wonderful Hut [2]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CHkQwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>808,631 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="OJ-F5PuAVkY">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=OJ-F5PuAVkY&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CH8QwBs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/OJ-F5PuAVkY/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="OJ-F5PuAVkY" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:14</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - The Dangerous Depths [3]" data-sessionlink="ved=CH4Qvxs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=OJ-F5PuAVkY&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - The Dangerous Depths [3]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CH0QwRs&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>610,601 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="Oag-iFMrLvo">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=Oag-iFMrLvo&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CIMBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/Oag-iFMrLvo/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="Oag-iFMrLvo" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:00</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - House Extension [4]" data-sessionlink="ved=CIIBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=Oag-iFMrLvo&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - House Extension [4]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CIEBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>457,139 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="jXrLEAzPHnI">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=jXrLEAzPHnI&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CIcBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/jXrLEAzPHnI/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="jXrLEAzPHnI" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">17:58</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - Upgrades [5]" data-sessionlink="ved=CIYBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=jXrLEAzPHnI&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - Upgrades [5]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CIUBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>418,306 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="C_CxYk1cC_4">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=C_CxYk1cC_4&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CIsBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/C_CxYk1cC_4/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="C_CxYk1cC_4" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:08</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - The Corruption [6]" data-sessionlink="ved=CIoBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=C_CxYk1cC_4&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - The Corruption [6]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CIkBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>350,155 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="tyCsjS8Wqys">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=tyCsjS8Wqys&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CI8BEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/tyCsjS8Wqys/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="tyCsjS8Wqys" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:50</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - Cave Bunny [7]" data-sessionlink="ved=CI4BEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=tyCsjS8Wqys&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - Cave Bunny [7]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CI0BEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>277,664 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="X2Lt0-NhVL0">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=X2Lt0-NhVL0&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CJMBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/X2Lt0-NhVL0/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="X2Lt0-NhVL0" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:10</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - I'm Rich [8]" data-sessionlink="ved=CJIBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=X2Lt0-NhVL0&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - I'm Rich [8]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CJEBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>361,540 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="OBJsqPvWAEY">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=OBJsqPvWAEY&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CJcBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/OBJsqPvWAEY/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="OBJsqPvWAEY" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:22</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - Jedi Friends [9]" data-sessionlink="ved=CJYBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=OBJsqPvWAEY&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - Jedi Friends [9]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CJUBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>467,721 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="6jKGzVH1a-E">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=6jKGzVH1a-E&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CJsBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/6jKGzVH1a-E/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="6jKGzVH1a-E" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:49</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - Super Gerbil [10]" data-sessionlink="ved=CJoBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=6jKGzVH1a-E&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - Super Gerbil [10]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CJkBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>408,062 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="0AMdr_BlYaM">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=0AMdr_BlYaM&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CJ8BEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/0AMdr_BlYaM/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="0AMdr_BlYaM" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:29</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - Eye Of Cthulhu [11]" data-sessionlink="ved=CJ4BEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=0AMdr_BlYaM&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - Eye Of Cthulhu [11]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CJ0BEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>462,022 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="-kzeCIOSsg8">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=-kzeCIOSsg8&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CKMBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/-kzeCIOSsg8/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="-kzeCIOSsg8" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">20:58</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Terraria Xbox - Underground Jungle [12]" data-sessionlink="ved=CKIBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=-kzeCIOSsg8&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Terraria Xbox - Underground Jungle [12]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CKEBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>355,248 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
          <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item compact-shelf-view-all-card">
    <a class="compact-shelf-view-all-card-link yt-valign" href="    /playlist?list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK
" yt-uix-sessionlink="" spf-link="" &quot;="" data-sessionlink="&quot;&quot;">
      <h4 class="compact-shelf-view-all-card-link-text yt-valign-container">
        <span class="">30+ more</span>
      </h4>
    </a>
  </li>

    </ul>
  </div>


      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-prev" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-next" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

  


                  <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix branded-page-box yt-section-hover-container fluid-shelf yt-uix-tdl" id="" data-sessionlink="ved=CKQBENwcKAM&amp;ei=gB64U72tCo2h-AOms4GAAQ">
              <h2 class="branded-page-module-title">
      <a href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ">
            <span class="branded-page-module-title-text">
      <span class="">Doki-Doki Universe</span>
    </span>

      </a>
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=bi1kHm4c4AE&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="yt-uix-button  shelves-play yt-uix-sessionlink yt-uix-button-default yt-uix-button-size-small" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-icon yt-uix-button-icon-play-all"></span><span class="yt-uix-button-content">Play </span></a>

  </h2>


        <div class="shelf-description yt-ui-ellipsis yt-ui-ellipsis-2">
        Welcome to my lets play of the Playstation exclusive game called "Doki-Doki Universe". In this series I will play through the entire game with commentary.
    </div>


    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="bi1kHm4c4AE">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=bi1kHm4c4AE&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CKgBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/bi1kHm4c4AE/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="bi1kHm4c4AE" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:06</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Robot Cat - Part 2" data-sessionlink="ved=CKcBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=bi1kHm4c4AE&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Robot Cat - Part 2</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CKYBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>477,089 views</li>
        <li class="yt-lockup-deemphasized-text">
            6 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="bS93jTWGKWs">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=bS93jTWGKWs&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CKwBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/bS93jTWGKWs/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="bS93jTWGKWs" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:28</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - The Beginning - Part 1" data-sessionlink="ved=CKsBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=bS93jTWGKWs&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - The Beginning - Part 1</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CKoBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>833,162 views</li>
        <li class="yt-lockup-deemphasized-text">
            6 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="MZI65PlL2Vo">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=MZI65PlL2Vo&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CLABEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/MZI65PlL2Vo/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="MZI65PlL2Vo" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">20:04</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Dr. Therapist - Part 3" data-sessionlink="ved=CK8BEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=MZI65PlL2Vo&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Dr. Therapist - Part 3</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CK4BEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>347,421 views</li>
        <li class="yt-lockup-deemphasized-text">
            6 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="sutAmVEM87k">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=sutAmVEM87k&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CLQBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/sutAmVEM87k/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="sutAmVEM87k" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">18:13</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Quiz Time - Part 4" data-sessionlink="ved=CLMBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=sutAmVEM87k&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Quiz Time - Part 4</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CLIBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>339,031 views</li>
        <li class="yt-lockup-deemphasized-text">
            6 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="-ZrJwPhcO8o">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=-ZrJwPhcO8o&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CLgBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/-ZrJwPhcO8o/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="-ZrJwPhcO8o" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:43</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Friendly Sushi - Part 5" data-sessionlink="ved=CLcBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=-ZrJwPhcO8o&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Friendly Sushi - Part 5</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CLYBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>362,557 views</li>
        <li class="yt-lockup-deemphasized-text">
            6 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="dQI-0p9e2ag">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=dQI-0p9e2ag&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CLwBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/dQI-0p9e2ag/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="dQI-0p9e2ag" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:19</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Translator Parrot - Part 6" data-sessionlink="ved=CLsBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=dQI-0p9e2ag&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Translator Parrot - Part 6</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CLoBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>276,654 views</li>
        <li class="yt-lockup-deemphasized-text">
            5 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="yqpt8Myp3E8">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=yqpt8Myp3E8&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CMABEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/yqpt8Myp3E8/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="yqpt8Myp3E8" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">20:08</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - New Steed - Part 7" data-sessionlink="ved=CL8BEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=yqpt8Myp3E8&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - New Steed - Part 7</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CL4BEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>261,556 views</li>
        <li class="yt-lockup-deemphasized-text">
            5 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="sCsc3QrkT2c">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=sCsc3QrkT2c&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CMQBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/sCsc3QrkT2c/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="sCsc3QrkT2c" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:56</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Priest Ank - Part 8" data-sessionlink="ved=CMMBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=sCsc3QrkT2c&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Priest Ank - Part 8</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CMIBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>207,768 views</li>
        <li class="yt-lockup-deemphasized-text">
            5 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="qRQog2FNwvc">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=qRQog2FNwvc&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CMgBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/qRQog2FNwvc/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="qRQog2FNwvc" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:28</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Yuckers - Part 9" data-sessionlink="ved=CMcBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=qRQog2FNwvc&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Yuckers - Part 9</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CMYBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>256,168 views</li>
        <li class="yt-lockup-deemphasized-text">
            5 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="3OriwLIddKk">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=3OriwLIddKk&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CMwBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/3OriwLIddKk/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="3OriwLIddKk" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:34</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Bunnipi - Part 10" data-sessionlink="ved=CMsBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=3OriwLIddKk&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Bunnipi - Part 10</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CMoBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>276,240 views</li>
        <li class="yt-lockup-deemphasized-text">
            4 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="S2lafKDuPZg">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=S2lafKDuPZg&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CNABEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/S2lafKDuPZg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="S2lafKDuPZg" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">24:42</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Brrr - Part 11" data-sessionlink="ved=CM8BEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=S2lafKDuPZg&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Brrr - Part 11</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CM4BEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>263,059 views</li>
        <li class="yt-lockup-deemphasized-text">
            4 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="d_xvsFtc8N0">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=d_xvsFtc8N0&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CNQBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/d_xvsFtc8N0/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="d_xvsFtc8N0" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:36</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe - Nook Nook's Boyfriend - Part 12" data-sessionlink="ved=CNMBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=d_xvsFtc8N0&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe - Nook Nook's Boyfriend - Part 12</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CNIBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>227,047 views</li>
        <li class="yt-lockup-deemphasized-text">
            4 months ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
          <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item compact-shelf-view-all-card">
    <a class="compact-shelf-view-all-card-link yt-valign" href="    /playlist?list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv
" yt-uix-sessionlink="" spf-link="" &quot;="" data-sessionlink="&quot;&quot;">
      <h4 class="compact-shelf-view-all-card-link-text yt-valign-container">
        <span class="">12 more</span>
      </h4>
    </a>
  </li>

    </ul>
  </div>


      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-prev" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-next" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

  


                  <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix branded-page-box yt-section-hover-container fluid-shelf yt-uix-tdl" id="" data-sessionlink="ved=CNUBENwcKAQ&amp;ei=gB64U72tCo2h-AOms4GAAQ">
              <h2 class="branded-page-module-title">
      <a href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ">
            <span class="branded-page-module-title-text">
      <span class="">Ni No Kuni: Wrath Of The White Witch</span>
    </span>

      </a>
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=A8HD9aRLex4&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="yt-uix-button  shelves-play yt-uix-sessionlink yt-uix-button-default yt-uix-button-size-small" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-icon yt-uix-button-icon-play-all"></span><span class="yt-uix-button-content">Play </span></a>

  </h2>


        <div class="shelf-description yt-ui-ellipsis yt-ui-ellipsis-2">
        Welcome to my lets play of Ni No Kuni: Wrath Of The White Witch. In this series I will playthrough the entire game with commentary.
    </div>


    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="A8HD9aRLex4">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=A8HD9aRLex4&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CNkBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/A8HD9aRLex4/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="A8HD9aRLex4" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">27:14</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Starting My Adventure [1]" data-sessionlink="ved=CNgBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=A8HD9aRLex4&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - Starting My Adventure [1]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CNcBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>609,335 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="CBgWZFQa1kg">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=CBgWZFQa1kg&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CN0BEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/CBgWZFQa1kg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="CBgWZFQa1kg" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">16:37</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Brown And Sticky [2]" data-sessionlink="ved=CNwBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=CBgWZFQa1kg&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - Brown And Sticky [2]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CNsBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>293,802 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="vCz3TMeL3Mo">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=vCz3TMeL3Mo&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=COEBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/vCz3TMeL3Mo/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="vCz3TMeL3Mo" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:15</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Another World [3]" data-sessionlink="ved=COABEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=vCz3TMeL3Mo&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - Another World [3]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CN8BEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>265,505 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="-un5UID7Lgo">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=-un5UID7Lgo&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=COUBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/-un5UID7Lgo/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="-un5UID7Lgo" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">20:25</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - My First Familiar [4]" data-sessionlink="ved=COQBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=-un5UID7Lgo&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - My First Familiar [4]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=COMBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>244,817 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="A-EvoYuN0rg">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=A-EvoYuN0rg&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=COkBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/A-EvoYuN0rg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="A-EvoYuN0rg" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">18:33</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - The Guardian Of The Woods [5]" data-sessionlink="ved=COgBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=A-EvoYuN0rg&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - The Guardian Of The Woods [5]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=COcBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>193,029 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="N8HfkEtaOj8">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=N8HfkEtaOj8&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CO0BEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/N8HfkEtaOj8/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="N8HfkEtaOj8" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">21:49</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Ding Dong Dell [6]" data-sessionlink="ved=COwBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=N8HfkEtaOj8&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - Ding Dong Dell [6]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=COsBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>160,780 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="KhO8aCLhmjk">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=KhO8aCLhmjk&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CPEBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/KhO8aCLhmjk/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="KhO8aCLhmjk" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:34</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Dreaming Of Mum [7]" data-sessionlink="ved=CPABEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=KhO8aCLhmjk&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - Dreaming Of Mum [7]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CO8BEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>165,511 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="Q4WDJvNuqEI">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=Q4WDJvNuqEI&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CPUBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/Q4WDJvNuqEI/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="Q4WDJvNuqEI" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">22:30</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - The Cat King [8]" data-sessionlink="ved=CPQBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=Q4WDJvNuqEI&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - The Cat King [8]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CPMBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>146,940 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="HQwRQzM4ZHU">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=HQwRQzM4ZHU&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CPkBEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/HQwRQzM4ZHU/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="HQwRQzM4ZHU" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">18:41</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Tommy Stout [9]" data-sessionlink="ved=CPgBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=HQwRQzM4ZHU&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - Tommy Stout [9]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CPcBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>128,398 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="X7XFoqmeSxg">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=X7XFoqmeSxg&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CP0BEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/X7XFoqmeSxg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="X7XFoqmeSxg" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:32</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - The Cat Mystery [10]" data-sessionlink="ved=CPwBEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=X7XFoqmeSxg&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - The Cat Mystery [10]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CPsBEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>118,244 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="nMbLUdU6M1c">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=nMbLUdU6M1c&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CIECEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/nMbLUdU6M1c/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="nMbLUdU6M1c" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">20:00</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Ding Dong Well [11]" data-sessionlink="ved=CIACEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=nMbLUdU6M1c&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - Ding Dong Well [11]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CP8BEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>116,382 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid" data-context-item-id="sp2cuLhFd1E">
    <div class="yt-lockup-thumbnail">
        <a href="/web/20140705154919/http://www.youtube.com/watch?v=sp2cuLhFd1E&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto  spf-link " data-sessionlink="ved=CIUCEMAb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/sp2cuLhFd1E/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="yt-uix-button yt-uix-button-size-small yt-uix-button-default addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-tooltip" type="button" onclick=";return false;" title="Watch Later" data-video-ids="sp2cuLhFd1E" data-button-menu-id="shared-addto-watch-later-login"><span class="yt-uix-button-content">  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-button-arrow"></button>
    <span class="video-time">19:51</span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch - Hickory Dock [12]" data-sessionlink="ved=CIQCEL8b&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/watch?v=sp2cuLhFd1E&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch - Hickory Dock [12]</a></h3>

  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead" class="g-hovercard yt-uix-sessionlink yt-user-name  spf-link " data-sessionlink="ved=CIMCEMEb&amp;feature=c4-overview-vl&amp;ei=gB64U72tCo2h-AOms4GAAQ" dir="ltr" data-ytid="UCj5i58mCkAREDqFWlhaQbOw" data-name="c4-overview-vl">stampylonghead</a>  <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified">

        </li>
      <li>136,585 views</li>
        <li class="yt-lockup-deemphasized-text">
            1 year ago
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
          <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item compact-shelf-view-all-card">
    <a class="compact-shelf-view-all-card-link yt-valign" href="    /playlist?list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe
" yt-uix-sessionlink="" spf-link="" &quot;="" data-sessionlink="&quot;&quot;">
      <h4 class="compact-shelf-view-all-card-link-text yt-valign-container">
        <span class="">30+ more</span>
      </h4>
    </a>
  </li>

    </ul>
  </div>


      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-prev" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-next" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

  


                  <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix branded-page-box yt-section-hover-container fluid-shelf yt-uix-tdl" id="" data-sessionlink="ved=CIYCENwcKAU&amp;ei=gB64U72tCo2h-AOms4GAAQ">
              <h2 class="branded-page-module-title">
      <a href="/web/20140705154919/http://www.youtube.com/user/stampylonghead/playlists?sort=dd&amp;shelf_id=5&amp;view=1" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink" data-sessionlink="ei=gB64U72tCo2h-AOms4GAAQ">
            <span class="branded-page-module-title-text">
      <span class="">Playlists by stampylonghead</span>
    </span>

      </a>
  </h2>


    

    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=g8B6idscChA&amp;list=LLj5i58mCkAREDqFWlhaQbOw" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CIkCEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/g8B6idscChA/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>381</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Liked videos" data-sessionlink="ved=CIoCEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=LLj5i58mCkAREDqFWlhaQbOw">Liked videos</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=5JpAnTm8Lyg&amp;list=PLEZiAg2bYC7ljb-IocGpZ6UpSGHP5aKqe" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CI0CEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/5JpAnTm8Lyg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>8</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Lego The Hobbit" data-sessionlink="ved=CI4CEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7ljb-IocGpZ6UpSGHP5aKqe">Lego The Hobbit</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          3 weeks ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=f41P6fDl9js&amp;list=PLEZiAg2bYC7kkShgWuP8DpbK5FAJrRfOK" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CJECEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/YcsWXAFcH3M/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>12</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Broken Age: Act 1" data-sessionlink="ved=CJICEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7kkShgWuP8DpbK5FAJrRfOK">Broken Age: Act 1</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          5 months ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=bi1kHm4c4AE&amp;list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CJUCEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/bi1kHm4c4AE/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>24</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Doki-Doki Universe" data-sessionlink="ved=CJYCEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7lSqfBQlBKEIAqkfeUN7bhv">Doki-Doki Universe</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          6 months ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=SwL1haF5iPQ&amp;list=PLEZiAg2bYC7lLbgmOeEI5mvBWdsHE1csC" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CJkCEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/SwL1haF5iPQ/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>30</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Disney Infinity" data-sessionlink="ved=CJoCEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7lLbgmOeEI5mvBWdsHE1csC">Disney Infinity</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          8 months ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=88CzQRAOYoQ&amp;list=PLEZiAg2bYC7n0iAKEstuTKrEd_6ZjkgA4" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CJ0CEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/88CzQRAOYoQ/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>8</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Rain" data-sessionlink="ved=CJ4CEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7n0iAKEstuTKrEd_6ZjkgA4">Rain</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          8 months ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=RcKWCQSrgbg&amp;list=PLEZiAg2bYC7kQ1gF_dxsR4nwPMaEmPnq9" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CKECEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/RcKWCQSrgbg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>8</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Brothers: A Tale Of Two Sons" data-sessionlink="ved=CKICEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7kQ1gF_dxsR4nwPMaEmPnq9">Brothers: A Tale Of Two Sons</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          10 months ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=NXO8M2W-GUA&amp;list=PLEZiAg2bYC7nhc2TwDPw7DqK8YXTpljIG" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CKUCEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/NXO8M2W-GUA/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>11</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Lets Play - Papo &amp; Yo" data-sessionlink="ved=CKYCEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7nhc2TwDPw7DqK8YXTpljIG">Lets Play - Papo &amp; Yo</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          1 year ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=qADXXnPy4hA&amp;list=PLEZiAg2bYC7m9O9MvnMEpkjGf_fugJ7UC" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CKkCEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/qADXXnPy4hA/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>9</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="The Unfinished Swan" data-sessionlink="ved=CKoCEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7m9O9MvnMEpkjGf_fugJ7UC">The Unfinished Swan</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          1 year ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=V-HES8B9Rfo&amp;list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CK0CEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/V-HES8B9Rfo/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>70</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Lets Play - Terraria Xbox 360 Edition" data-sessionlink="ved=CK4CEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7mxFaRna94UtM8rWG_ZpGRK">Lets Play - Terraria Xbox 360 Edition</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          1 year ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=A8HD9aRLex4&amp;list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CLECEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/A8HD9aRLex4/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>82</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Ni No Kuni: Wrath Of The White Witch" data-sessionlink="ved=CLICEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7ls6rDxyWuNoQPyyJLG2Wxe">Ni No Kuni: Wrath Of The White Witch</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          1 year ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-playlist yt-lockup-grid">
    <div class="yt-lockup-thumbnail">
            <a href="/web/20140705154919/http://www.youtube.com/watch?v=EWRscl-GPCg&amp;list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V" class="yt-pl-thumb-link yt-uix-sessionlink  spf-link " data-sessionlink="ved=CLUCEMAb&amp;feature=c4-overview&amp;ei=gB64U72tCo2h-AOms4GAAQ">
    
  <span class="yt-pl-thumb  yt-pl-thumb-fluid">
      
      <span class="video-thumb  yt-thumb yt-thumb-185 yt-thumb-fluid">
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="//web.archive.org/web/20140705154919/http://i1.ytimg.com/vi/EWRscl-GPCg/mqdefault.jpg" aria-hidden="true" width="185">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>



      <span class="sidebar">
        <span class="yt-pl-sidebar-content yt-valign">
          <span class="yt-valign-container">
                <span class="formatted-video-count-label">
      <b>206</b> videos
    </span>

            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-pl-icon yt-pl-icon-reg">
          </span>
        </span>
      </span>
        <span class="yt-pl-thumb-overlay">
          <span class="yt-pl-thumb-overlay-content">
            <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <span class="yt-pl-thumb-overlay-text">
Play all
            </span>
          </span>
        </span>
  </span>

  </a>


    </div>
    <div class="yt-lockup-content">
        <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link  yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" title="Minecraft - Main Series" data-sessionlink="ved=CLYCEL8b&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/playlist?list=PLEZiAg2bYC7ngh-_Z_ruvzSfn1KPQRL5V">Minecraft - Main Series</a></h3>
  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">

    </ul>
      <ul class="yt-lockup-meta-info">
        <li class="yt-lockup-deemphasized-text">
          1 year ago
        </li>
      </ul>
  </div>

    </div>
    
  </div>



        </li>
          <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item compact-shelf-view-all-card">
    <a class="compact-shelf-view-all-card-link yt-valign" href="    /user/stampylonghead/playlists?sort=dd&amp;shelf_id=5&amp;view=1
" yt-uix-sessionlink="" spf-link="" &quot;="" data-sessionlink="&quot;&quot;">
      <h4 class="compact-shelf-view-all-card-link-text yt-valign-container">
        <span class="">9 more</span>
      </h4>
    </a>
  </li>

    </ul>
  </div>


      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-prev" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-button yt-uix-button-size-default yt-uix-button-shelf-slider-pager yt-uix-shelfslider-next" type="button" onclick=";return false;"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

  


    </div>




  

        


  <div id="ad_creative_1" class="ad-div hid" style="z-index: 1">
    <div id="ad_creative_div_1"><iframe id="ad_creative_iframe_1" width="1" height="1" scrolling="no" frameborder="0" style="z-index: 1;" allow="autoplay 'self'; fullscreen 'self'" src="https://web.archive.org/web/20140705154919/http://ad.doubleclick.net/N4061/adi/com.ytbc/stampylonghead;sz=1x1;kpu=stampylonghead;kpeid=j5i58mCkAREDqFWlhaQbOw;tile=1;dc_yt=1;kga=-1;kgg=-1;klg=en;kmyd=ad_creative_1;ytexp=931331,903945,945011,950841,916625,914020;ord=4892403978052126?"></iframe></div>
    <script>(function() {function tagMpuIframe() {var containerEl = document.getElementById('ad_creative_div_1');if (!containerEl) {return;}var iframeEl = document.createElement('iframe');var iframeSrc = 'https://web.archive.org/web/20140705154919/http://ad.doubleclick.net/N4061/adi/com.ytbc/stampylonghead;sz=1x1;kpu=stampylonghead;kpeid=j5i58mCkAREDqFWlhaQbOw;tile=1;dc_yt=1;kga=-1;kgg=-1;klg=en;kmyd=ad_creative_1;ytexp=931331,903945,945011,950841,916625,914020;ord=' +Math.floor(Math.random() * 10000000000000000) +'?';iframeEl.id = 'ad_creative_iframe_1';iframeEl.width = '1';iframeEl.height = '1';iframeEl.style.cssText = 'z-index:1;';iframeEl.scrolling = 'no';iframeEl.frameBorder = '0';containerEl.appendChild(iframeEl);iframeEl.src = iframeSrc;}tagMpuIframe();})();</script>
  </div>



  </div>

          </div>
        </div>
          <div class="branded-page-v2-secondary-col">
            


        <div class="branded-page-related-channels branded-page-box  yt-card">
            <h2 class="branded-page-module-title" dir="ltr">
        Featured Channels
    </h2>

          <ul class="branded-page-related-channels-list">
        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCLmgD9bQ6q9okrWrfx9i56g">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/stampylongnose" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CLsCEMAb&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCLmgD9bQ6q9okrWrfx9i56g">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-pVicX0z7CZ0/AAAAAAAAAAI/AAAAAAAAAAA/Ps321a5o81E/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="stampylongnose" data-sessionlink="ved=CLwCEL8b&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/stampylongnose">stampylongnose</a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="169,660 subscribers" aria-busy="false" data-subscriber-count-title="169,660 subscribers" data-style-type="unbranded" data-channel-external-id="UCLmgD9bQ6q9okrWrfx9i56g" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbkhUM0VPb1pTRHlYZ3VjREZSaENPTXBTMUN6UXxBQ3Jtc0trbkZYSlZHdGJMUUtVbFYweDkteE13VDdaRGp4WUxYaW03d2V1ejRFTXVkWl9sLXlQZzBBeEpDRFAzTlJLM3gxWEdxcnlGRVJWM0tUVktYQS1UVDZDaFRDQmZwV1pLUW05eExFZ2xtbGZJWVNQaHdOQWRHUXg3QTZvRXAwMUpjMzh5R0V6N3ZFRW0tTDZxZ0NXZWNBbGJ4d1RwV0pudFhtV21IaXRmY1pGbGFMRVdLUTA0VXBadzRGVERBSkpxNHJfbXFXcjk%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCLmgD9bQ6q9okrWrfx9i56g&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CLoCEJsr&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="169,660 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UChIql1MwfMNRdbHz9I7MrLA">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/MagicAnimalClub" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CL8CEMAb&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UChIql1MwfMNRdbHz9I7MrLA">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-0DlED3mI9p8/AAAAAAAAAAI/AAAAAAAAAAA/EUC-2ZTvJ-0/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="MagicAnimalClub" data-sessionlink="ved=CMACEL8b&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/MagicAnimalClub">MagicAnimalClub</a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="335,731 subscribers" aria-busy="false" data-subscriber-count-title="335,731 subscribers" data-style-type="unbranded" data-channel-external-id="UChIql1MwfMNRdbHz9I7MrLA" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbk5ud1dQY09nV2p2akNSbkdTemx4SUoxM2p6d3xBQ3Jtc0trQWlUSWZjU1VNSTB3Q3dNby1fSDdqOFpGbHNCRDZncXNWX2RYaHhGMEpvcjVmSFluRU9YS0Q2YTNwdWV1YnR6bW5qWmF2N3RQdDdvcTVKVWQ5TkF4MW13NlhxcHlzeWFGcnYweDFRdkU5VE5VY1FPRnhpdW5qS3IxVlBfMmh4ZnY2emUzeXlPRkp3TVNMcEtaazJDTUtiWmRXS3dlaW9CUUZnQTdfVGxCeVI4aG12emJTM3FrMDJrRnRmbHhQUUFKQ3Y4SFA%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUChIql1MwfMNRdbHz9I7MrLA&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CL4CEJsr&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="335,731 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCa6Hg8HmooiDNaCT0_1NbQQ">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/iBallisticSquid" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CMMCEMAb&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCa6Hg8HmooiDNaCT0_1NbQQ">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-KHaabp0g7kA/AAAAAAAAAAI/AAAAAAAAAAA/ZLx5S7z1jjU/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="iBallisticSquid" data-sessionlink="ved=CMQCEL8b&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/iBallisticSquid">iBallisticSquid</a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="1,366,040 subscribers" aria-busy="false" data-subscriber-count-title="1,366,040 subscribers" data-style-type="unbranded" data-channel-external-id="UCa6Hg8HmooiDNaCT0_1NbQQ" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbkpmRkluMVYzWjBtUUNodkpVem4ybl9pVXdaQXxBQ3Jtc0ttWlprLU9BLW5uZlltT2NDSkNkbDZpeWRyYklzc00zNDdWYlpYRklIRV9lWmY4c1d3SUhoaGxXSFpoeWtZYmFEa29yVUNwd2oweGdVZll3bklyUjlsTzZ3QTNFdkRaWkdrb1hwNlpITEliS2ZMUktzSl91bi1uR25aQ0dEbDFrMUQxMlY3cFVUbm9SeHRCcjVGWEJXMzFqNnVtVXJJUEZaX25XMU93a3p0YmFVN1ZtWmlOOElwWkIzTjZfclR0cDUzTGtPb3A%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCa6Hg8HmooiDNaCT0_1NbQQ&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CMICEJsr&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="1,366,040 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UChvX4j5cuIBc0iDCWUS1Qxw">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/KomLit" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CMcCEMAb&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UChvX4j5cuIBc0iDCWUS1Qxw">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-zxwZcimuMWM/AAAAAAAAAAI/AAAAAAAAAAA/LOfbVz_MnLo/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="KomLit-Minecraft Builder &amp; Mustache Gamer" data-sessionlink="ved=CMgCEL8b&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/KomLit">KomLit-Minecraft Builder &amp; Mustache Gamer</a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="28,063 subscribers" aria-busy="false" data-subscriber-count-title="28,063 subscribers" data-style-type="unbranded" data-channel-external-id="UChvX4j5cuIBc0iDCWUS1Qxw" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqa0pYVVRBbUdadGpkdFNyN2hOUFctZjk0UlpZZ3xBQ3Jtc0tuUWhMel9IV2NjR3k1bWRzWmVJRWlXSGxrNjZGam5CSXVkWFhZRTlaSFZLWUd4V1Fxb1UxZ0NId1YwWnNQRjhMYlJFWUlhWFltMm5va2s4QUo1cS11NHZoLWVwUUtMRTQtOGh6TEtPcXN2NWF3RHkyOTZRNUZWYWwtLXE1RWdsdUtqNTh2T3BYNzcxZ3FvX3ZqU0lxN0IxbWh5cHRwWEcycjdrRTJmOEljNXF3eGllUXpPZTJIempPNHNTdnBnUFMzWTVvVV8%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUChvX4j5cuIBc0iDCWUS1Qxw&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CMYCEJsr&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="28,063 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCEhPQ2fEKbxGdlld4XTTAEg">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/iMineZone" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CMsCEMAb&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCEhPQ2fEKbxGdlld4XTTAEg">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-Khp5kNuhoVM/AAAAAAAAAAI/AAAAAAAAAAA/uso8XNVPpME/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="iMineZone" data-sessionlink="ved=CMwCEL8b&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/iMineZone">iMineZone</a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="14,063 subscribers" aria-busy="false" data-subscriber-count-title="14,063 subscribers" data-style-type="unbranded" data-channel-external-id="UCEhPQ2fEKbxGdlld4XTTAEg" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqa3hlemdHTkNuZTdJOXVRay1ZcGpiWDh4SHdoZ3xBQ3Jtc0trREhVZENMaUFLdzZFOWtrNk9VSUdFV1g2RkRiR0lmYk1UdzhaOURIQmpSZVZjMG5TbTF1Zkw1RDZyZzhLdjJMYVVBTUNDTEQwVGRsRXJnbE5DbWZrdVZYTHh3ekt4YTdnazdLUXZpQ01VY1M4U3RZd3lDc2ZYazFSM21UZlJqVV9UWENRXy1NSGxvYUFoMTVzQ1hEMFY1NWtZMWJJWDkzUUtDbjFSVTFfSDczMUxpcVQtNlZuWUNZQ1pqY1g1X0VDczQ1NVA%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCEhPQ2fEKbxGdlld4XTTAEg&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CMoCEJsr&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="14,063 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCBdDCfMX_NaRYLejRJHZZ1A">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/LewisBlogsGaming" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CM8CEMAb&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCBdDCfMX_NaRYLejRJHZZ1A">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-6v6L2yvgkg0/AAAAAAAAAAI/AAAAAAAAAAA/1JRfWVuYJos/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="LewisBlogsGaming" data-sessionlink="ved=CNACEL8b&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/LewisBlogsGaming">LewisBlogsGaming</a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="63,880 subscribers" aria-busy="false" data-subscriber-count-title="63,880 subscribers" data-style-type="unbranded" data-channel-external-id="UCBdDCfMX_NaRYLejRJHZZ1A" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbEw1aV9jRFdNaFdvSTlMRXhBQ3dNcHp0RWhqQXxBQ3Jtc0tuM0RYRmI2TmJLSUpGTF9hS19vU1hCRkxLRGV0bjQ3YXJzTmJseE4zSXZacWVGQmpDQWJhdWVJREZDRUUyTmRFVGsyV0l4YWVGVldGRkZuV2NBTUpHVkxsQzBEc3VSckh6SXV4cDhDVllXXzQ2eUt4OXdYY1MzYk1UM3JhWVlUeGdPUFdWTktiTVdFZENVaWFGX1NBa0xmdWoxYjI5NWdTZmpwUjM5eVZjUjExUU9ra2lCTy1MTFNiX2NSNlk0c04wTzZtS2U%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCBdDCfMX_NaRYLejRJHZZ1A&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CM4CEJsr&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="63,880 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UClEQqLc5V_j1wg1Ro8Hcrow">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/minecritters" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CNMCEMAb&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UClEQqLc5V_j1wg1Ro8Hcrow">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-eyKf7eMtOhY/AAAAAAAAAAI/AAAAAAAAAAA/WCeC9C3kiDs/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="minecritters" data-sessionlink="ved=CNQCEL8b&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/minecritters">minecritters</a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="12,254 subscribers" aria-busy="false" data-subscriber-count-title="12,254 subscribers" data-style-type="unbranded" data-channel-external-id="UClEQqLc5V_j1wg1Ro8Hcrow" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqa0VjQjJSTWpXS0tadmNuSEVDTEJzcVR1bHpMQXxBQ3Jtc0ttLWhnRHRhakthaUowY0xjM3RzTDVmVEdOckVDRExtTTNyQmRSWXNocVAwa1ItZVFWYTVQeDMwZXZBcnVsR2xXXzNpbWQ3bHd2dmtnbWYycWhLNFpiSWFoS2k4eThhZjNZYjQwcmd3MnloWVNjU3RuZ0pOYmRlWEgycV9ZbjV6eHoxRXFycTVYNXotemJ6a00xcW81aUNqTm0xejY4YXN1SmxUUnU0S1BMT0EzRHFra0MxRU0zUEtFQ3JfeVVRaUpRdHVoWkI%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUClEQqLc5V_j1wg1Ro8Hcrow&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CNICEJsr&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="12,254 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCuUrDQTxrdsLUEHPP0ZMkEg">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/ChooChoosGaming" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CNcCEMAb&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCuUrDQTxrdsLUEHPP0ZMkEg">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-nCZe1D2MfY4/AAAAAAAAAAI/AAAAAAAAAAA/LaTxHr6-aJY/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="ChooChoosGaming" data-sessionlink="ved=CNgCEL8b&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/ChooChoosGaming">ChooChoosGaming<span class="qualified-channel-title-badge"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"></span></a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="168,724 subscribers" aria-busy="false" data-subscriber-count-title="168,724 subscribers" data-style-type="unbranded" data-channel-external-id="UCuUrDQTxrdsLUEHPP0ZMkEg" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqblNtYnA5SjB0UThfS0lRbFpnWXFZREJvNV9sUXxBQ3Jtc0tscWRma01pMDNzNENDOVVybl9PV2hlRGdDbkJ4OEh5TlNScmxvWkJ2Vko3UnhkQ2NhWi1IRnd5NmxMUmdESUpEelJndW42RW5BR3VfTmJjdjZHaklRcmxFNVZIOHNnNnJSR2FZQ1ZKcUhJVHFOd1pSZjFrWVkzVlQ3QWdUN2NhXzB3RGc4WHl2dC15ZXJ3WjBWZmJHRllEM0puRnlhMjBTczZCcmRBNENELVRzSmpxOEIzNzM2WS1RXzBmYkljY2RkbDFiRnc%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCuUrDQTxrdsLUEHPP0ZMkEg&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CNYCEJsr&amp;feature=rc-feat&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="168,724 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

  </ul>

    </div>


        <div class="branded-page-related-channels branded-page-box  yt-card">
            <h2 class="branded-page-module-title yt-uix-tooltip" data-tooltip-text="These recommendations have been automatically generated by YouTube." dir="ltr">
        Popular channels on YouTube
    </h2>

          <ul class="branded-page-related-channels-list">
        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCuj1Ms9_LCsQPSJ4p8nvOVA">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/TheBajanCanadian" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=CNwCEMAb&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCuj1Ms9_LCsQPSJ4p8nvOVA">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-hXtnY2yyt5s/AAAAAAAAAAI/AAAAAAAAAAA/qvU6kL4oPCI/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="TheBajanCanadian" data-sessionlink="ved=CN0CEL8b&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/TheBajanCanadian">TheBajanCanadian<span class="qualified-channel-title-badge"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"></span></a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="3,848,774 subscribers" aria-busy="false" data-subscriber-count-title="3,848,774 subscribers" data-style-type="unbranded" data-channel-external-id="UCuj1Ms9_LCsQPSJ4p8nvOVA" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbFVQVzhiVUVfSkIzOEpkSkF0NVlsSmJBZUVaUXxBQ3Jtc0tudHNiTmF1QjZmWHlyYnp6azh2YnN1ZUZaaHF3MUdCS1o4U3BYX0tVam5pUHFIVUxvbDhjdWdvbGNobGFZLVVzQ3FzWEtvdG5fUUpITDNEN0F2X084c05ib2hHWkI4Z3hKRExWaE95TlBqV3J5VDBVa1hkYThIbGI3Nm85emFieTIyczdic3FHR3AxZWVZTklicjJHZktLUGdVbkJYMXJCWHVpRnJwTlI0SE1YMmdWTEcybHlkMHVZX3JzMnA0T3hWN056Z0g%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCuj1Ms9_LCsQPSJ4p8nvOVA&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CNsCEJsr&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="3,848,774 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCdm1fwk5iqteE0MVOBUuE8Q">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/VenomExtreme" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=COACEMAb&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCdm1fwk5iqteE0MVOBUuE8Q">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-iUlPE-XzXYk/AAAAAAAAAAI/AAAAAAAAAAA/KwP_BVlS-aE/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="VenomExtreme" data-sessionlink="ved=COECEL8b&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/VenomExtreme">VenomExtreme<span class="qualified-channel-title-badge"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"></span></a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="3,389,702 subscribers" aria-busy="false" data-subscriber-count-title="3,389,702 subscribers" data-style-type="unbranded" data-channel-external-id="UCdm1fwk5iqteE0MVOBUuE8Q" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbWFZdEhscl9XcFZmc29YVV9ZQ3hucmhFY1ZwQXxBQ3Jtc0tsR3JPSWRfanNaVjUxWUszT3JmeGlIbWhwdzB4dWNyRFAwM0FLbWNQVWFRMDdzOGpFbm1VQkdsNGJrckZkbHk2ZHVSMVUtVWF2LWlMLWJ2U1dBd3pHa24tTzlqOG9zWWtUN29hajF6SFhEZU4wSEM1TXJhWGoyMXIzNWRSZ1NSWUNBVmxXbVEwSF9KdFZVbDFieDlBQVluY2FYTV82NzUzaDB6U3YyMmIxalM0SGxBV0tHLUZlaGVLR05YUDlSWWNXdEdNVFE%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCdm1fwk5iqteE0MVOBUuE8Q&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=CN8CEJsr&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="3,389,702 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCLCmJiSbIoa_ZFiBOBDf6ZA">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/TobyGames" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=COQCEMAb&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCLCmJiSbIoa_ZFiBOBDf6ZA">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-04X7ZKmOgJk/AAAAAAAAAAI/AAAAAAAAAAA/PxfQvwyrf_U/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="TobyGames" data-sessionlink="ved=COUCEL8b&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/TobyGames">TobyGames<span class="qualified-channel-title-badge"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"></span></a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="6,676,337 subscribers" aria-busy="false" data-subscriber-count-title="6,676,337 subscribers" data-style-type="unbranded" data-channel-external-id="UCLCmJiSbIoa_ZFiBOBDf6ZA" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbjA1d0xBMEFfaTZCUGd1UWZxMnNZR014OEQyZ3xBQ3Jtc0tsU3JZdlJ4ZnAya19ZQ0J5SFBRRzQ3RnRtRlR0N2FyZmMtVUxtYkw5YUFMZEplMS1jQWs3dDhYci1qSTItRklKODh5M0hZMU15Z240S0NHZlVyN09lRzdoQ3QySjBmeWQ3czBaYnNkWlY4blNxaGg0Y1VsNlk2NnRpNmRzWU5Ud1MxRWdDSm1idUI3NDRzckMxbkJOZE9yQ0JIYVFFLU53WlhjWjNZcHZXRW1kS0cxQ095V19Jb0lfR0ZFMTY3T0ZheUFMcG4%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCLCmJiSbIoa_ZFiBOBDf6ZA&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=COMCEJsr&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="6,676,337 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCYiGq8XF7YQD00x7wAd62Zg">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/JuegaGerman" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=COgCEMAb&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCYiGq8XF7YQD00x7wAd62Zg">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-PPtgop-zx1c/AAAAAAAAAAI/AAAAAAAAAAA/4e4AOO7ArcQ/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="JuegaGerman" data-sessionlink="ved=COkCEL8b&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/JuegaGerman">JuegaGerman</a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="3,872,614 subscribers" aria-busy="false" data-subscriber-count-title="3,872,614 subscribers" data-style-type="unbranded" data-channel-external-id="UCYiGq8XF7YQD00x7wAd62Zg" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbThpbUhGUElKc3NzTTdLcUl2LThJTHFpb09Id3xBQ3Jtc0ttOEF2X3oxQ0VFSjl1d0lUTWt0WWFQWXEzU2RTVHZjNm9LWVUyRzV4TDExdUQyb3VPUTdyZ0tNWU4xanB3QlRxUWNoMEh1eHdObmtmckZjXzdxQmVtV1FmTUV1TjRNSldWOGVVVktOTVZLcng2aU5rZ041ZVdQanJieUt6ZUVkQVZwcnpqUlpxV29vblN3SkszcGZTNS1EYmdid3lGdHZpV0RlOUZRN2Fpbzg3bG9oekI3eW44VS1HdzNBN2hvMlVPd2RKcTI%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCYiGq8XF7YQD00x7wAd62Zg&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=COcCEJsr&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="3,872,614 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

        <li class="branded-page-related-channels-item  spf-link  clearfix" data-external-id="UCH-_hzb2ILSCo9ftVSnrCIQ">
    




    <span class="yt-lockup clearfix  yt-lockup-channel yt-lockup-mini">
    <div class="yt-lockup-thumbnail" style="width: 34px;">
        <a href="/web/20140705154919/http://www.youtube.com/user/BlueXephos" class="ux-thumb-wrap yt-uix-sessionlink  spf-link " data-sessionlink="ved=COwCEMAb&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ">    <span class="video-thumb  yt-thumb yt-thumb-34 g-hovercard" data-ytid="UCH-_hzb2ILSCo9ftVSnrCIQ">
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" data-thumb="https://web.archive.org/web/20140705154919/https://yt3.ggpht.com/-FMO2nSO2pP8/AAAAAAAAAAI/AAAAAAAAAAA/QZLWwqsqMIU/s176-c-k-no/photo.jpg" aria-hidden="true" width="34" height="34">
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>
</a>

    </div>
    <div class="yt-lockup-content">
          <h3 class="yt-lockup-title"><a class="yt-uix-sessionlink yt-uix-tile-link  spf-link " dir="ltr" title="YOGSCAST Lewis &amp; Simon" data-sessionlink="ved=CO0CEL8b&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ" href="/web/20140705154919/http://www.youtube.com/user/BlueXephos">YOGSCAST Lewis &amp; Simon<span class="qualified-channel-title-badge"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"></span></a></h3>


  <div class="yt-lockup-meta spf-nolink">
      <span class=" yt-uix-button-subscription-container"><button class="yt-uix-button yt-uix-button-size-default yt-uix-button-subscribe-unbranded yt-uix-button-has-icon yt-uix-subscription-button yt-can-buffer yt-uix-hovercard yt-uix-tooltip" type="button" onclick=";return false;" aria-role="button" aria-live="polite" title="6,999,372 subscribers" aria-busy="false" data-subscriber-count-title="6,999,372 subscribers" data-style-type="unbranded" data-channel-external-id="UCH-_hzb2ILSCo9ftVSnrCIQ" data-subscriber-count-tooltip="True" data-href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26continue_action%3DQUFFLUhqbHFvblYxa01QN1VzZmJHQVJCV2ZONE1RbVVBd3xBQ3Jtc0tsR1VJU0RmV0ZDNzM4VkRPdWRRUl9tOVNoVVBhMmlWcF8zaU16Ym9adnUyZjFpQmpUMkt4N21MUzJmaGx3M2k5elg1YXN4d0tlLW5LY2ZmUGN5ZHZQdnFHMkEwMVJfZ2NxWkxmQi1wd3B4eDE2QWVodmpBYjlvczlVTW9xZW9oTzE3Y0xuYTZ6Y3QtZ0lWVUNmaVBMb2Z3eEFRdXBndGgyWWQ3RTZYdFZFOHdubVVPSVJLb1FUa0l6bGlCd3hSTXdnZHJlTE8%253D%26feature%3Dsubscribe%26hl%3Den%26next%3D%252Fchannel%252FUCH-_hzb2ILSCo9ftVSnrCIQ&amp;hl=en&amp;uilel=3&amp;service=youtube" data-sessionlink="ved=COsCEJsr&amp;feature=rc-rel&amp;ei=gB64U72tCo2h-AOms4GAAQ"><span class="yt-uix-button-icon-wrapper"><img src="https://web.archive.org/web/20140705154919im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="6,999,372 subscribers" class="yt-uix-button-icon yt-uix-button-icon-subscribe"></span><span class="yt-uix-button-content"><span class="subscribe-label" aria-label="Subscribe">Subscribe</span><span class="subscribed-label" aria-label="Unsubscribe">Subscribed</span><span class="unsubscribe-label" aria-label="Unsubscribe">Unsubscribe</span> </span></button>  <span class="yt-subscription-button-disabled-mask" title=""></span>
</span>
  </div>

    </div>
    
  </span>


  </li>

  </ul>

    </div>


          </div>
      </div>
    </div>
  </div>
  </div></div>
</div></div>  <div id="footer-container" class="yt-base-gutter"><div id="footer"><div id="footer-main"><div id="footer-logo"><a href="/" title="YouTube home"><img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="YouTube home"></a></div>  <ul class="pickers yt-uix-button-group" data-button-toggle-group="optional">
      <li>
            <button class=" yt-uix-button yt-uix-button-default yt-uix-button-size-default yt-uix-button-has-icon" id="yt-picker-language-button" onclick=";return false;" type="button" data-picker-key="language" data-picker-position="footer" data-button-menu-id="arrow-display" data-button-toggle="true" data-button-action="yt.www.picker.load" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-footer-language" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></span><span class="yt-uix-button-content">  <span class="yt-picker-button-label">
Language:
  </span>
  English
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>


      </li>
      <li>
            <button class=" yt-uix-button yt-uix-button-default yt-uix-button-size-default" id="yt-picker-country-button" onclick=";return false;" type="button" data-picker-key="country" data-picker-position="footer" data-button-menu-id="arrow-display" data-button-toggle="true" data-button-action="yt.www.picker.load" role="button"><span class="yt-uix-button-content">  <span class="yt-picker-button-label">
Country:
  </span>
  Worldwide
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>


      </li>
      <li>
            <button class=" yt-uix-button yt-uix-button-default yt-uix-button-size-default" id="yt-picker-safetymode-button" onclick=";return false;" type="button" data-picker-key="safetymode" data-picker-position="footer" data-button-menu-id="arrow-display" data-button-toggle="true" data-button-action="yt.www.picker.load" role="button"><span class="yt-uix-button-content">    <span class="yt-picker-button-label">
Safety:
    </span>
Off
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>


      </li>
  </ul>
    <button class="yt-uix-button-reverse yt-google-help-link inq-no-click  yt-uix-button yt-uix-button-default yt-uix-button-size-default" onclick=";return false;" type="button" data-ghelp-tracking-param="" id="google-help" data-ghelp-anchor="google-help"  role="button"><span class="yt-uix-button-content">    <img class="questionmark" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
Help
 </span></button>
      <div id="yt-picker-language-footer"
      class="yt-picker"
      style="display: none"
>
      <p class="yt-spinner">
      <img class="yt-spinner-img" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Loading icon" title="">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

  </div>

      <div id="yt-picker-country-footer"
      class="yt-picker"
      style="display: none"
>
      <p class="yt-spinner">
      <img class="yt-spinner-img" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Loading icon" title="">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

  </div>

      <div id="yt-picker-safetymode-footer"
      class="yt-picker"
      style="display: none"
>
      <p class="yt-spinner">
      <img class="yt-spinner-img" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Loading icon" title="">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

  </div>

</div><div id="footer-links"><ul id="footer-links-primary">  <li><a href="//www.youtube.com/yt/about/">About</a></li>
  <li><a href="//www.youtube.com/yt/press/">Press &amp; Blogs</a></li>
  <li><a href="//www.youtube.com/yt/copyright/">Copyright</a></li>
  <li><a href="//www.youtube.com/yt/creators/">Creators &amp; Partners</a></li>
  <li><a href="//www.youtube.com/yt/advertise/">Advertising</a></li>
  <li><a href="//www.youtube.com/yt/dev/">Developers</a></li>
  <li><a href="https://plus.google.com/+youtube" dir="ltr">+YouTube</a></li>
</ul><ul id="footer-links-secondary">  <li><a href="/t/terms">Terms</a></li>
  <li><a href="https://www.google.com/intl/en/policies/privacy/">Privacy</a></li>
  <li><a href="//www.youtube.com/yt/policyandsafety/">
Policy &amp; Safety
  </a></li>
  <li><a href="//support.google.com/youtube/?hl=en" onclick="return yt.www.feedback.start(59);" class="reportbug">Send feedback</a></li>
  <li><a href="/testtube">Try something new!</a></li>
  <li>  <span class="copyright" dir="ltr">&copy; 2014 YouTube, LLC</span>
</li>
</ul></div></div></div>


      <div class="yt-dialog hid " id="feed-privacy-lb">
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
              <div class="yt-dialog-working-overlay"></div>
  <div class="yt-dialog-working-bubble">
    <div class="yt-dialog-waiting-content">
      <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Working...</div>
    </div>
  </div>

          </div>
        </div>
      </div>
    </div>
  </div>



    <div id="shared-addto-watch-later-login" class="hid">
      <a href="https://accounts.google.com/ServiceLogin?passive=true&uilel=3&hl=en&continue=https%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26feature%3Dplaylist%26hl%3Den%26next%3D%252F&service=youtube" class="sign-in-link">Sign in</a> to add this to Watch Later

    </div>

  <div id="shared-addto-menu" style="display: none;" class="hid sign-in">
      <div class="addto-menu">
        <div id="addto-list-panel" class="menu-panel active-panel addto-playlist-panel yt-scrollbar">
          <span class="addto-playlist-item yt-uix-button-menu-item yt-uix-tooltip sign-in"
        data-possible-tooltip=""
        data-tooltip-show-delay="750"


>
<img class="playlist-status" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""><a href="https://accounts.google.com/ServiceLogin?passive=true&uilel=3&hl=en&continue=https%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26feature%3Dplaylist%26hl%3Den%26next%3D%252F&service=youtube" class="sign-in-link">Sign in</a> to add this to Watch Later
  </span>


  </div>
  <div id="addto-list-saving-panel" class="menu-panel">
    <div class="addto-loading loading-content">
        <p class="yt-spinner">
      <img class="yt-spinner-img" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Loading icon" title="">

    <span class="yt-spinner-message">
        Loading playlists...
    </span>
  </p>

    </div>
  </div>
  <div id="addto-list-error-panel" class="menu-panel">
    <div class="panel-content">
      <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
      <span class="error-details"></span>
      <a class="show-menu-link">Back</a>
    </div>
  </div>

  </div>

  </div>
  <div id="yt-uix-videoactionmenu-menu">
    <div class="hide-on-create-pl-panel">
      <h3>
Add to
      </h3>
    </div>
    <div class="add-to-widget">
    </div>
  </div>
<script>if (window.ytcsi) {window.ytcsi.tick("hr", null, '');}</script><script>var ytspf = ytspf || {};ytspf.enabled = true;ytspf.config = {};ytspf.config['navigate-limit'] = 10;ytspf.config['navigate-lifetime'] = 64800000;</script>    <script id="js-1138418169" class="spf" src="//s.ytimg.com/yts/jsbin/spf-vfl_SHw60.js" data-loaded="true"></script>
    <script id="js-2887504560" class="www_base_mod" src="//s.ytimg.com/yts/jsbin/www-en_US-vflr23GH7/base.js" data-loaded="true"></script>
<script>if (window.ytcsi) {window.ytcsi.tick("je", null, '');}</script>  
<script>yt.setConfig({'EVENT_ID': "E2VUU8utINKz-QODxICoAw",'PAGE_NAME': "index",'LOGGED_IN': false,'SESSION_INDEX': null,'DELEGATED_SESSION_ID': null,'GAPI_HOST': "https:\/\/apis.google.com",'GAPI_HINT_PARAMS': "m;\/_\/scs\/abc-static\/_\/js\/k=gapi.gapi.en.wNKQZRCdm0I.O\/m=__features__\/rt=j\/d=1\/rs=AItRSTN5_i0KBm0FLby7W1B4Q5dHsZBNkw",'GAPI_LOCALE': "en_US",'UNIVERSAL_HOVERCARDS': true,'VISITOR_DATA': "CgtudVY0VzVTWTZtNA%3D%3D",'APIARY_HOST': "",'APIARY_HOST_FIRSTPARTY': "",'INNERTUBE_CONTEXT_HL': "en",'INNERTUBE_CONTEXT_GL': "US",'INNERTUBE_CONTEXT_CLIENT_VERSION': "20140416",'INNERTUBE_API_KEY': "AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8",'GOOGLEPLUS_HOST': "https:\/\/plus.google.com",'PAGEFRAME_JS': "\/\/s.ytimg.com\/yts\/jsbin\/www-pageframe-vfluYvMs2.js",'JS_COMMON_MODULE': "\/\/s.ytimg.com\/yts\/jsbin\/www-en_US-vflr23GH7\/common.js",'PAGE_FRAME_DELAYLOADED_CSS': "\/\/s.ytimg.com\/yts\/cssbin\/www-pageframedelayloaded-vflL-mQUy.css",'PREFETCH_CSS_RESOURCES' : ["\/\/s.ytimg.com\/yts\/cssbin\/www-player-vfl5HOAyS.css",''         ],'PREFETCH_JS_RESOURCES': ["\/\/s.ytimg.com\/yts\/jsbin\/html5player-en_US-vflMYwWq8.js",''         ],'SAFETY_MODE_PENDING': false,'LOCAL_DATE_TIME_CONFIG': {"months": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], "formatWeekdayShortTime": "EE h:mm a", "shortMonths": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], "formatLongDateOnly": "MMMM d, yyyy", "weekdays": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"], "shortWeekdays": ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], "formatLongDate": "MMMM d, yyyy h:mm a", "formatShortDate": "MMM d, yyyy", "amPms": ["AM", "PM"]},'PAGE_CL': 65093206,'PAGE_BUILD_TIMESTAMP': "Wed Apr 16 19:34:30 2014 (1397702070)",'PLAYER_PERSISTENCE_REFACTOR': true,'FEEDBACK_BUCKET_ID': "Home",'FEEDBACK_LOCALE_LANGUAGE': "en",'FEEDBACK_LOCALE_EXTRAS': {"guide_subs": "NA", "logged_in": false, "experiments": "907050,943601,938631,916807,914072,916612,936109,909555,935628,927617,937417,921905,937801,937809,943405,920605,937003,931943,921410,920609,922804,932804,911507,913434,944702,906957,946801,931970,947101,901812,927616,932256,919389,934003,934004,938626,938636,938639,938643,938651,943303,923328,936916,927006,918119,918121,934022,930819,933218,936923,931967,909553,935006,939201,901604,912714,929237,944309,931952,935707,945815,937803,945109,906001,902022", "is_branded": "", "accept_language": null, "is_partner": ""}});yt.setConfig('SPF_SEARCH_BOX', true);yt.setMsg({'ADDTO_WATCH_LATER': "Watch Later",'ADDTO_WATCH_LATER_ADDED': "Added",'ADDTO_WATCH_LATER_ERROR': "Error",'ADDTO_WATCH_QUEUE': "Watch Queue",'ADDTO_WATCH_QUEUE_ADDED': "Added",'ADDTO_WATCH_QUEUE_ERROR': "Error",'ADDTO_TV_QUEUE': "TV Queue"});yt.setAjaxToken('addto_ajax_logged_out', "QUFFLUhqa0tFVHZzRkRFQlJEMG9heXAtdGJTM0NtRUlaZ3xBQ3Jtc0tuMU5Kd05ma0YzUTFYeXhaZnhNckN6dG9hZDc3RnYtWHJyTVRMQ1Z1bVl6RWJtYXFzNjF3VEpvZ1YtWTFLeUhkUHY1ejlxTkd0RTdBb3RoRVJuZlVzOWQ5ZkRDMDUwSzBuRkhDTWQ1cHRUZ1hLcG1KWQ==");yt.setAjaxToken('watch_queue_ajax', "QUFFLUhqa0drWGpPbG9jUGNkOGs5eW1CbElmVzhnNjl2Z3xBQ3Jtc0tsV1NNU0Jfc2N2MVlVLWVPZ2VjWmg2OEhYamVfbzRRTW14bVhhVl9hWjgyMUl4NTBIZUFNV0Y3Q3pDUWZuUzlqT1hpZmt6NkU1Rlg1bjhyUzdaaHRXM092Zm5uREV1Rjhzb3Ntb29mTHVqQjFaX2VZOA==");yt.setAjaxToken('watch_fragments_ajax', "QUFFLUhqa1dwTFR3TC1uR0h0SFJsbDJhYTRFbzBWcXY0QXxBQ3Jtc0tuRlQtR2xteVpFZF9mZUc5SHdTRTRSVFJZNHFuVHRkVjFaaEZCZG9qZE9LQ2dzelh1M2I0MjhJY2JPMDF3bVhOWkIwYjdhZnhpYXFrcnZZaHJMTE9SN2ZBSWM5R0J3el9feGJKZXl5eDR2cjFQb3Z4NA==");  yt.setConfig('FEED_PRIVACY_CSS_URL', "\/\/s.ytimg.com\/yts\/cssbin\/www-feedprivacydialog-vflg7YKmq.css");
  yt.setAjaxToken('feed_privacy_ajax', "QUFFLUhqa24zd1NsZWZpdVg4OVJ6Y2diRWhOT1ZFOG9pZ3xBQ3Jtc0tualIySHg5LTcwYTl4MjhqMVpSdDRkVTV3OEJKSEZVVXdBZFpmaDkxdUFINXQtU1lMMW1GUUZLeU9fQmZMQUhacmltbFJVWENlWG5tT2JCUVpGVHBNV1VIRk9BRkhnRXFFRkFCRENOeXJ6VGxFTF9hbw==");
  yt.setConfig('FEED_PRIVACY_LIGHTBOX_ENABLED', true);
yt.setConfig({'SBOX_JS_URL': "\/\/s.ytimg.com\/yts\/jsbin\/www-searchbox-vflRtyDTW.js",'SBOX_SETTINGS': {"PSUGGEST_TOKEN": null, "SESSION_INDEX": null, "REQUEST_DOMAIN": "us", "USE_HTTPS": false, "EXPERIMENT_ID": -1, "HAS_ON_SCREEN_KEYBOARD": false, "DROPDOWN_POSITION": "fixed", "REQUEST_LANGUAGE": "en"},'SBOX_LABELS': {"SUGGESTION_DISMISSED_LABEL": "Suggestion dismissed", "WATCH_NOW_LABEL": "Watch now", "SUGGESTION_DISMISS_LABEL": "Dismiss", "VIEW_CHANNEL_LABEL": "View channel"}});  yt.setConfig({
    'YPC_LOADER_ENABLED': true,
    'YPC_LOADER_CONFIGS': "\/ypc_config_ajax",
    'YPC_LOADER_JS': "\/\/s.ytimg.com\/yts\/jsbin\/www-ypc-vfla2skXI.js",
    'YPC_LOADER_CSS': "\/\/s.ytimg.com\/yts\/cssbin\/www-ypc-vflxpZgzu.css",
    'YPC_LOADER_CALLBACKS': ['yt.www.ypc.checkout.init', 'yt.www.ypc.subscription.init']
  });
  yt.setConfig('GOOGLE_HELP_CONTEXT', "homepage");
ytcsi.span('st', 246);yt.setConfig({'TIMING_ACTION': "glo",'TIMING_INFO': {"yt_li": 0, "yt_spf": 0, "yt_ref": "watch", "e": "907050,943601,938631,916807,914072,916612,936109,937417,913434,923328,936916,934022,936923", "ei": "E2VUU8utINKz-QODxICoAw", "yt_lt": "warm"}});  yt.setConfig({
    'XSRF_TOKEN': "QUFFLUhqbUFfYmxUS25sbnRzNjYxZjlONmhNR3Q4QmhnUXxBQ3Jtc0trc0VBQjlOaXUwWWMwZ1ZKRVVSYTlja2Ezak5Pb1dFM2lkOXFBNm0yNUwwS0xORHlfUnFULUVMVW5LekljLTA3UXhrQTBoazBRcU9Bb2FqbGtMYmhlTHBId2IycnRxYWpYTUUzZndGQXpyTHR1ZzFPY3k2UXg3dlplcXpUWGFnOXZ0bEJZTzhyQ25Ha293SS1fR2hNcUw0dk5ZSFE=",
    'XSRF_REDIRECT_TOKEN': "GLtX39EdpaZB3lSNxhGvErikyAR8MTM5ODEyNjIyN0AxMzk4MDM5ODI3",
    'XSRF_FIELD_NAME': "session_token"
  });
  yt.setConfig('THUMB_DELAY_LOAD_BUFFER', 300);
if (window.ytcsi) {window.ytcsi.tick("jl", null, '');}</script></body></html>

