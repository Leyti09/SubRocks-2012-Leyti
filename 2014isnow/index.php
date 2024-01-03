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
<div id="content" class="  content-alignment">  




  <div class="branded-page-v2-container branded-page-base-bold-titles branded-page-v2-secondary-column-hidden branded-page-v2-container-flex-width" >

    <div class="branded-page-v2-col-container">
      <div class="branded-page-v2-col-container-inner">
        <div class="branded-page-v2-primary-col">
          <div class="     yt-card    clearfix">
                <div class="branded-page-v2-primary-col-header-container branded-page-v2-primary-column-content">
      
    </div>
  <div class="branded-page-v2-body branded-page-v2-primary-column-content" id="gh-activityfeed">
        <div id="feed" class="">
        <div id="feed-main-what_to_watch" class="individual-feed" data-feed-name="what_to_watch" data-feed-type="main">
    
      <div class="feed-container" data-filter-type=""

>
        <ul id="" class="feed-list">
                  <li class="feed-item-container yt-section-hover-container browse-list-item-container branded-page-box vve-check " data-sessionlink="ei=E2VUU8utINKz-QODxICoAw">
    <div class="feed-item-dismissable ">
      <div class="feed-item-main feed-item-no-author">
        <div class="feed-item-main-content">
                    <div class="shelf-wrapper clearfix">
          <div class="lohp-newspaper-shelf shelf-item vve-check  yt-section-hover-container" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CAIQ0R4">
    
    
    <div class="lohp-shelf-content">
      <div class="lohp-large-shelf-container">
            <div class="clearfix" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CAMQ0h4oAA">
                                                        <?php
                                                        $stmt = $__db->prepare("SELECT * FROM videos WHERE featured = 'v' ORDER BY id DESC LIMIT 1");
                                                        $stmt->execute();
                                                        while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$_user22 = $__user_h->fetch_user_username($video['author']);
                                                            $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                                                            $video['duration'] = $__time_h->timestamp($video['duration']);
                                                            $video['views'] = $__video_h->fetch_video_views($video['rid']);
                                                            $video['author'] = htmlspecialchars($video['author']);		
                                                            $video['title'] = htmlspecialchars($video['title']);
                                                            $video['description'] = $__video_h->shorten_description($video['description'], 50);
                                                    ?>
      <div class="vve-check"
             data-visibility-tracking="QMa12p6Q1bWk0QE="
           >
        <a href="/watch?v=<?php echo $video['rid'] ?>" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto lohp-thumb-wrap spf-link"  data-sessionlink="feature=g-high&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CAMQ0h4oAA">    <span class="video-thumb  yt-thumb yt-thumb-370 yt-thumb-fluid"
      >
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="/dynamic/thumbs/<?php echo $video['thumbnail'] ?>" width="370"  >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-size-small yt-uix-tooltip" onclick=";return false;" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="0UjWqQPWmsY" role="button"><span class="yt-uix-button-content">  <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
    <span class="video-time"><?php echo $video['duration'] ?></span>
</a>
          <a class="lohp-video-link max-line-2 yt-uix-sessionlink spf-link"
     data-sessionlink="feature=g-high&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CAMQ0h4oAA"
     href="/watch?v=<?php echo $video['rid'] ?>"
     title="<?php echo $video['title'] ?>"><?php echo $video['title'] ?></a>

      </div>

        <div class="lohp-video-metadata">
            <span class="content-uploader lohp-video-metadata-item spf-link">
<span class="username-prepend">by</span> <a href="<?php if($_user22['vanity'])	{	?>/<?php echo htmlspecialchars($_user22['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($video['author']); ?><?php } ?>" class="g-hovercard yt-uix-sessionlink yt-user-name " data-sessionlink="ei=E2VUU8utINKz-QODxICoAw" dir="ltr" data-ytid="UCtI0Hodo5o5dUb67FeUjDeA" data-name=""><?php if($_user22['title'])	{	?><?php echo htmlspecialchars($_user22['title']); ?><?php } else {	?><?php echo htmlspecialchars($video['author']); ?><?php	}	?></a>
  </span>

            <span class="view-count lohp-video-metadata-item">
      <?php echo $video['views'] ?> views
  </span>

              <span class="content-item-time-created lohp-video-metadata-item" title="<?php echo $video['age'] ?>">
      <?php echo $video['age'] ?>
    </span>

														</div><?php } ?>
  </div>

      </div>
      <div class="lohp-medium-shelves-container">
                                                    <?php
                                                        $stmt = $__db->prepare("SELECT * FROM videos WHERE featured = 'v' ORDER BY id DESC LIMIT 1, 3");
                                                        $stmt->execute();
                                                        while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$_user22 = $__user_h->fetch_user_username($video['author']);
                                                            $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                                                            $video['duration'] = $__time_h->timestamp($video['duration']);
                                                            $video['views'] = $__video_h->fetch_video_views($video['rid']);
                                                            $video['author'] = htmlspecialchars($video['author']);		
                                                            $video['title'] = htmlspecialchars($video['title']);
                                                            $video['description'] = $__video_h->shorten_description($video['description'], 50);
                                                    ?>
                <div class="lohp-medium-shelf vve-check spf-link" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CAUQ0h4oAQ">
    
      <div class="vve-check"
             data-visibility-tracking="QOzYsJCtofTNowE="
           >
        <div class="lohp-media-object">
          <a href="/watch?v=<?php echo $video['rid'];  ?>" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto lohp-thumb-wrap"  data-sessionlink="feature=g-high&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CAUQ0h4oAQ">    <span class="video-thumb  yt-thumb yt-thumb-170 yt-thumb-fluid"
      >
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="/dynamic/thumbs/<?php echo $video['thumbnail'];  ?>" width="170"  >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-size-small yt-uix-tooltip" onclick=";return false;" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="o5vRCtIMLGw" role="button"><span class="yt-uix-button-content">  <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
    <span class="video-time"><?php echo $video['duration'] ?></span>
</a>
        </div>
        <div class="lohp-media-object-content lohp-medium-shelf-content">
            <a class="lohp-video-link max-line-2 yt-uix-sessionlink spf-link"
     data-sessionlink="feature=g-high&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CAUQ0h4oAQ"
     href="/watch?v=<?php echo $video['rid'];  ?>"
     title="<?php echo $video['title'] ?>"><?php echo $video['title'] ?></a>

            <div class="lohp-video-metadata attached">
                <span class="content-uploader  spf-link">
<span class="username-prepend">by</span> <a href="<?php if($_user22['vanity'])	{	?>/<?php echo htmlspecialchars($_user22['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($video['author']); ?><?php } ?>" class="g-hovercard yt-uix-sessionlink yt-user-name " data-sessionlink="ei=E2VUU8utINKz-QODxICoAw" dir="ltr" data-ytid="UCzWQYUVCpZqtN93H8RR44Qw" data-name="">
												<?php if($_user22['title'])	{	?>
												<?php echo htmlspecialchars($_user22['title']); ?>
												<?php } else {	?>
												<?php echo htmlspecialchars($_user22['username']); ?>
												<?php	}	?>
</a>
  </span>

            </div>
            <div class="lohp-video-metadata">
                <span class="view-count ">
      <?php echo $video['views'] ?> views
  </span>

                  <span class="content-item-time-created " title="2 days ago">
      <?php echo $video['age'] ?>
    </span>

            </div>
        </div>
      </div>
	</div><?php } ?>
    </div>
    </div>
  </div>

  

  </div>



        </div>
      </div>
    </div>
      <div class="feed-item-dismissal-notices"><div class="feed-item-dismissal feed-item-dismissal-hide hid">This item has been hidden</div></div>
  </li>


            <li class="feed-item-container yt-section-hover-container browse-list-item-container branded-page-box vve-check " data-sessionlink="ei=E2VUU8utINKz-QODxICoAw">
    <div class="feed-item-dismissable ">
      <div class="feed-item-main feed-item-no-author">
        <div class="feed-item-main-content">
                    <div class="shelf-wrapper clearfix">
          <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix  yt-section-hover-container feeds-mode yt-uix-tdl"  data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CAsQ3Bw">
            <h2 class="branded-page-module-title">
          
      <a href="" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink g-hovercard" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw" data-ytid="UCF0pVplsI8R5kcAqgtoRqoA">


    <span class="branded-page-module-title-text">
      Recent videos.
    </span>

      </a>
  </h2>


    

    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
                                                            <?php
                                                                $stmt = $__db->prepare("SELECT * FROM videos ORDER BY id DESC LIMIT 11");
                                                                $stmt->execute();
                                                                while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
																	$_user22 = $__user_h->fetch_user_username($video['author']);
                                                                    $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                                                                    $video['duration'] = $__time_h->timestamp($video['duration']);
                                                                    $video['views'] = $__video_h->fetch_video_views($video['rid']);
                                                                    $video['author'] = htmlspecialchars($video['author']);		
                                                                    $video['title'] = htmlspecialchars($video['title']);
                                                                    $video['description'] = $__video_h->shorten_description($video['description'], 50);
                                                            ?><?php if($__user_h->user_exists($_user22['username'])) { ?>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid vve-check"
      data-visibility-tracking="QMr37N_Czq_M-wE="
  >
    <div class="yt-lockup-thumbnail"
    >
      
  <a href="/watch?v=<?php echo $video['rid']; ?>" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto spf-link"  data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA4QwBs">    <span class="video-thumb  yt-thumb yt-thumb-175 yt-thumb-fluid"
      >
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/thumbs/<?php echo $video['thumbnail']; ?>" width="175"  >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-size-small yt-uix-tooltip" onclick=";return false;" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="-5i-dCv7O8o" role="button"><span class="yt-uix-button-content">  <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
    <span class="video-time"><?php echo $video['duration'] ?></span>
</a>

    </div>
    <div class="yt-lockup-content">
            <h3 class="yt-lockup-title">
    <a class="yt-uix-sessionlink yt-uix-tile-link spf-link yt-ui-ellipsis yt-ui-ellipsis-2"
        dir="ltr"
        title="<?php echo $video['title']; ?>"
        data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA8Qvxs"
        href="/watch?v=<?php echo $video['rid']; ?>"
      >
        <?php echo $video['title']; ?>
    </a>
  </h3>


  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="<?php if($_user22['vanity'])	{	?>/<?php echo htmlspecialchars($_user22['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($video['author']); ?><?php } ?>" class="g-hovercard yt-uix-sessionlink yt-user-name spf-link" data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA0QwRs" dir="ltr" data-ytid="UCeiYXex_fwgYDonaTcSIk6w" data-name="g-high-cpv">												<?php if($_user22['title'])	{	?>
												<?php echo htmlspecialchars($_user22['title']); ?>
												<?php } else {	?>
												<?php echo htmlspecialchars($_user22['username']); ?>
												<?php	}	?></a>  <?php if($__user_h->if_partner($_user22['username'])) { ?><img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"><?php } ?>

        </li>
      <li><?php echo $video['views'] ?> views</li>
        <li class="yt-lockup-deemphasized-text">
            <?php echo $video['age'] ?>
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
																<?php } } ?>
  </ul>
  </div>


      <button class="yt-uix-shelfslider-prev yt-uix-button yt-uix-button-shelf-slider-pager yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-shelfslider-next yt-uix-button yt-uix-button-shelf-slider-pager yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

        <span class="feed-item-action-menu ">
          

      <button class="flip hide-until-delayloaded yt-uix-button yt-uix-button-action-menu yt-uix-button-size-default yt-uix-button-has-icon yt-uix-button-empty" type="button" data-button-has-sibling-menu="True" role="button" aria-pressed="false" aria-expanded="false" aria-haspopup="true" aria-activedescendant="" aria-label="Actions for this feed item"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-action-menu" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""><ul class=" yt-uix-button-menu yt-uix-button-menu-action-menu" role="menu" aria-haspopup="true" style="display: none;"><li role="menuitem" id="aria-id-13739451119"><script src="https://apis.google.com/js/platform.js"></script><h1>Sub</h1><div class="g-ytsubscribe" data-channel="cRo0K3d424" data-layout="full" data-count="default"></div></li></ul></button>
        <div class="yt-uix-overlay hid">
    <div class="  yt-uix-overlay-target yt-uix-overlay-watch-it-again hid">
    </div>
        <div class="yt-dialog hid ">
    <div class="yt-dialog-base">
      <span class="yt-dialog-align"></span>
      <div class="yt-dialog-fg">
        <div class="yt-dialog-fg-content">
            <div class="yt-dialog-header">
                <h2 class="yt-dialog-title">
                        Permanently remove this section?


                </h2>
            </div>
          <div class="yt-dialog-loading">
              <div class="yt-dialog-waiting-content">
    <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Loading...</div>
  </div>

          </div>
          <div class="yt-dialog-content">
              <p class="shelf-dismissal-confirmation">
Are you sure you want to remove the "Watch it again" section? You can't undo this.
  </p>

  <div class="yt-uix-overlay-actions">
    <button class="yt-uix-overlay-close yt-uix-button yt-uix-button-default yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Cancel </span></button>
    <button class="yt-uix-overlay-close action-never-show-in-feed yt-uix-button yt-uix-button-primary yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Remove section </span></button>
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



        </div>
      </div>
    </div>
      <div class="feed-item-dismissal-notices"><div class="feed-item-dismissal feed-item-dismissal-hide hid">This item has been hidden</div></div>
  </li>


            <li class="feed-item-container yt-section-hover-container browse-list-item-container branded-page-box vve-check " data-sessionlink="ei=E2VUU8utINKz-QODxICoAw">
    <div class="feed-item-dismissable ">
      <div class="feed-item-main feed-item-no-author">
        <div class="feed-item-main-content">
                    <div class="shelf-wrapper clearfix">
          <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix  yt-section-hover-container feeds-mode yt-uix-tdl"  data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CDgQ3Bw">
            <h2 class="branded-page-module-title">
          
      <a href="/channel/UC-9-kyTW8ZkZNDHQJ6FgpwQ" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink g-hovercard" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw" data-ytid="UC-9-kyTW8ZkZNDHQJ6FgpwQ">
                <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/pfp/46111c0a078305e99382c38d270b01ce.jpg" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>

    <span class="branded-page-module-title-text">
      Music
    </span>

      </a>
  </h2>


    

    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
                                                            <?php
                                                                $stmt = $__db->prepare("SELECT * FROM videos WHERE category = 'Music' ORDER BY rand() LIMIT 11");
                                                                $stmt->execute();
                                                                while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
																	$_user22 = $__user_h->fetch_user_username($video['author']);
                                                                    $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                                                                    $video['duration'] = $__time_h->timestamp($video['duration']);
                                                                    $video['views'] = $__video_h->fetch_video_views($video['rid']);
                                                                    $video['author'] = htmlspecialchars($video['author']);		
                                                                    $video['title'] = htmlspecialchars($video['title']);
                                                                    $video['description'] = $__video_h->shorten_description($video['description'], 50);
                                                            ?><?php if($__user_h->user_exists($_user22['username'])) { ?>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid vve-check"
      data-visibility-tracking="QMr37N_Czq_M-wE="
  >
    <div class="yt-lockup-thumbnail"
    >
      
  <a href="/watch?v=<?php echo $video['rid']; ?>" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto spf-link"  data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA4QwBs">    <span class="video-thumb  yt-thumb yt-thumb-175 yt-thumb-fluid"
      >
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/thumbs/<?php echo $video['thumbnail']; ?>" width="175"  >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-size-small yt-uix-tooltip" onclick=";return false;" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="-5i-dCv7O8o" role="button"><span class="yt-uix-button-content">  <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
    <span class="video-time"><?php echo $video['duration'] ?></span>
</a>

    </div>
    <div class="yt-lockup-content">
            <h3 class="yt-lockup-title">
    <a class="yt-uix-sessionlink yt-uix-tile-link spf-link yt-ui-ellipsis yt-ui-ellipsis-2"
        dir="ltr"
        title="<?php echo $video['title']; ?>"
        data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA8Qvxs"
        href="/watch?v=<?php echo $video['rid']; ?>"
      >
        <?php echo $video['title']; ?>
    </a>
  </h3>


  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="<?php if($_user22['vanity'])	{	?>/<?php echo htmlspecialchars($_user22['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($video['author']); ?><?php } ?>" class="g-hovercard yt-uix-sessionlink yt-user-name spf-link" data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA0QwRs" dir="ltr" data-ytid="UCeiYXex_fwgYDonaTcSIk6w" data-name="g-high-cpv">												<?php if($_user22['title'])	{	?>
												<?php echo htmlspecialchars($_user22['title']); ?>
												<?php } else {	?>
												<?php echo htmlspecialchars($_user22['username']); ?>
												<?php	}	?></a>  <?php if($__user_h->if_partner($_user22['username'])) { ?><img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"><?php } ?>

        </li>
      <li><?php echo $video['views'] ?> views</li>
        <li class="yt-lockup-deemphasized-text">
            <?php echo $video['age'] ?>
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
																<?php } } ?>
	</ul>
  </div>


      <button class="yt-uix-shelfslider-prev yt-uix-button yt-uix-button-shelf-slider-pager yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-shelfslider-next yt-uix-button yt-uix-button-shelf-slider-pager yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

        <span class="feed-item-action-menu ">
          

      <button class="flip hide-until-delayloaded yt-uix-button yt-uix-button-action-menu yt-uix-button-size-default yt-uix-button-has-icon yt-uix-button-empty" onclick=";return false;" type="button" data-button-has-sibling-menu="True" role="button" aria-pressed="false" aria-expanded="false" aria-haspopup="true" aria-activedescendant="" aria-label="Actions for this feed item"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-action-menu" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""><ul class=" yt-uix-button-menu yt-uix-button-menu-action-menu" role="menu" aria-haspopup="true" style="display: none;"><li role="menuitem" id="aria-id-28689483294"><span class="dismiss-menu-choice yt-uix-button-menu-item" data-dismissal-token="CAES5AFBRkFCOXpmcExDc0pHdHRhU2tpN1dRNkhVa1YtSEQ3MVN0ZWVxV21GUWU3Ml9QWDA1dktNdnJTTEgtWm9FazRhNWFDMzFDNjFaYWQycHk2NjNIMlkwRV9VYlN0QU1qa056YnR1enpKQzVXWDhUMDJMT1lFdGs4MTBZc0YtYU8weHA4OXNwY0hEaTZtdDlUMDMxSlJ1aEN3MFBSZ3dOQXE4bjZSb2Y0TlcxZ016OS1KV2x6cW0tbUF1Tjc4aWRQSzJObFhsalBBXzFXU01rRllDTk5KYmI3TVRCWE9TVXdzcUd6UUE%3D" onclick=";return false;" aria-label="Removes the selected feed item from the feed." data-action="hide" >Hide these videos</span></li></ul></button>
        <div class="yt-uix-overlay hid">
    <div class="  yt-uix-overlay-target yt-uix-overlay-watch-it-again hid">
    </div>
        <div class="yt-dialog hid ">
    <div class="yt-dialog-base">
      <span class="yt-dialog-align"></span>
      <div class="yt-dialog-fg">
        <div class="yt-dialog-fg-content">
            <div class="yt-dialog-header">
                <h2 class="yt-dialog-title">
                        Permanently remove this section?


                </h2>
            </div>
          <div class="yt-dialog-loading">
              <div class="yt-dialog-waiting-content">
    <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Loading...</div>
  </div>

          </div>
          <div class="yt-dialog-content">
              <p class="shelf-dismissal-confirmation">
Are you sure you want to remove the "Watch it again" section? You can't undo this.
  </p>

  <div class="yt-uix-overlay-actions">
    <button class="yt-uix-overlay-close yt-uix-button yt-uix-button-default yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Cancel </span></button>
    <button class="yt-uix-overlay-close action-never-show-in-feed yt-uix-button yt-uix-button-primary yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Remove section </span></button>
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



        </div>
      </div>
    </div>
      <div class="feed-item-dismissal-notices"><div class="feed-item-dismissal feed-item-dismissal-hide hid">This item has been hidden</div></div>
  </li>


            <li class="feed-item-container yt-section-hover-container browse-list-item-container branded-page-box vve-check " data-sessionlink="ei=E2VUU8utINKz-QODxICoAw">
    <div class="feed-item-dismissable ">
      <div class="feed-item-main feed-item-no-author">
        <div class="feed-item-main-content">
                    <div class="shelf-wrapper clearfix">
          <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix  yt-section-hover-container feeds-mode yt-uix-tdl"  data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CHUQ3Bw">
            <h2 class="branded-page-module-title">
          
      <a href="/channel/UCxAgnFbkxldX6YUEvdcNjnA" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink g-hovercard" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw" data-ytid="UCxAgnFbkxldX6YUEvdcNjnA">
                <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/pfp/12cfcb9fb59510cd78b100d2c251d33a.png" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>

    <span class="branded-page-module-title-text">
      Entertainment
    </span>

      </a>
  </h2>


    

    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
                                                             <?php
                                                                $stmt = $__db->prepare("SELECT * FROM videos WHERE category = 'Entertainment' ORDER BY rand() LIMIT 11");
                                                                $stmt->execute();
                                                                while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
																	$_user22 = $__user_h->fetch_user_username($video['author']);
                                                                    $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                                                                    $video['duration'] = $__time_h->timestamp($video['duration']);
                                                                    $video['views'] = $__video_h->fetch_video_views($video['rid']);
                                                                    $video['author'] = htmlspecialchars($video['author']);		
                                                                    $video['title'] = htmlspecialchars($video['title']);
                                                                    $video['description'] = $__video_h->shorten_description($video['description'], 50);
                                                            ?><?php if($__user_h->user_exists($_user22['username'])) { ?>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid vve-check"
      data-visibility-tracking="QMr37N_Czq_M-wE="
  >
    <div class="yt-lockup-thumbnail"
    >
      
  <a href="/watch?v=<?php echo $video['rid']; ?>" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto spf-link"  data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA4QwBs">    <span class="video-thumb  yt-thumb yt-thumb-175 yt-thumb-fluid"
      >
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/thumbs/<?php echo $video['thumbnail']; ?>" width="175"  >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-size-small yt-uix-tooltip" onclick=";return false;" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="-5i-dCv7O8o" role="button"><span class="yt-uix-button-content">  <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
    <span class="video-time"><?php echo $video['duration'] ?></span>
</a>

    </div>
    <div class="yt-lockup-content">
            <h3 class="yt-lockup-title">
    <a class="yt-uix-sessionlink yt-uix-tile-link spf-link yt-ui-ellipsis yt-ui-ellipsis-2"
        dir="ltr"
        title="<?php echo $video['title']; ?>"
        data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA8Qvxs"
        href="/watch?v=<?php echo $video['rid']; ?>"
      >
        <?php echo $video['title']; ?>
    </a>
  </h3>


  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="<?php if($_user22['vanity'])	{	?>/<?php echo htmlspecialchars($_user22['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($video['author']); ?><?php } ?>" class="g-hovercard yt-uix-sessionlink yt-user-name spf-link" data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA0QwRs" dir="ltr" data-ytid="UCeiYXex_fwgYDonaTcSIk6w" data-name="g-high-cpv">												<?php if($_user22['title'])	{	?>
												<?php echo htmlspecialchars($_user22['title']); ?>
												<?php } else {	?>
												<?php echo htmlspecialchars($_user22['username']); ?>
												<?php	}	?></a>  <?php if($__user_h->if_partner($_user22['username'])) { ?><img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"><?php } ?>

        </li>
      <li><?php echo $video['views'] ?> views</li>
        <li class="yt-lockup-deemphasized-text">
            <?php echo $video['age'] ?>
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
																<?php } } ?>
				</ul>
  </div>


      <button class="yt-uix-shelfslider-prev yt-uix-button yt-uix-button-shelf-slider-pager yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-shelfslider-next yt-uix-button yt-uix-button-shelf-slider-pager yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

        <span class="feed-item-action-menu ">
          

      <button class="flip hide-until-delayloaded yt-uix-button yt-uix-button-action-menu yt-uix-button-size-default yt-uix-button-has-icon yt-uix-button-empty" onclick=";return false;" type="button" data-button-has-sibling-menu="True" role="button" aria-pressed="false" aria-expanded="false" aria-haspopup="true" aria-activedescendant="" aria-label="Actions for this feed item"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-action-menu" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""><ul class=" yt-uix-button-menu yt-uix-button-menu-action-menu" role="menu" aria-haspopup="true" style="display: none;"><li role="menuitem" id="aria-id-54792118085"><span class="dismiss-menu-choice yt-uix-button-menu-item" data-dismissal-token="CAESjgFBRkFCOXpmcEpHRmJlbnVld3F6N3U3LVFIcTVGdU1OUVF6bWFBWm5LMXlXY3BZVWcxMnR5NV9SOUtJajZJczBSN0lleTlGSlBRVTVMdzZxTFhZWVNTNGY0U2NpSThGZjhCTlNDaHEtZENvZ1VKUEREdVFiNjNXa1VaRWZRYWNscXEybVJKUnVHOUh0U1N0" onclick=";return false;" aria-label="Removes the selected feed item from the feed." data-action="hide" >Hide these videos</span></li></ul></button>
        <div class="yt-uix-overlay hid">
    <div class="  yt-uix-overlay-target yt-uix-overlay-watch-it-again hid">
    </div>
        <div class="yt-dialog hid ">
    <div class="yt-dialog-base">
      <span class="yt-dialog-align"></span>
      <div class="yt-dialog-fg">
        <div class="yt-dialog-fg-content">
            <div class="yt-dialog-header">
                <h2 class="yt-dialog-title">
                        Permanently remove this section?


                </h2>
            </div>
          <div class="yt-dialog-loading">
              <div class="yt-dialog-waiting-content">
    <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Loading...</div>
  </div>

          </div>
          <div class="yt-dialog-content">
              <p class="shelf-dismissal-confirmation">
Are you sure you want to remove the "Watch it again" section? You can't undo this.
  </p>

  <div class="yt-uix-overlay-actions">
    <button class="yt-uix-overlay-close yt-uix-button yt-uix-button-default yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Cancel </span></button>
    <button class="yt-uix-overlay-close action-never-show-in-feed yt-uix-button yt-uix-button-primary yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Remove section </span></button>
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



        </div>
      </div>
    </div>
      <div class="feed-item-dismissal-notices"><div class="feed-item-dismissal feed-item-dismissal-hide hid">This item has been hidden</div></div>
  </li>


            <li class="feed-item-container yt-section-hover-container browse-list-item-container branded-page-box vve-check " data-sessionlink="ei=E2VUU8utINKz-QODxICoAw">
    <div class="feed-item-dismissable ">
      <div class="feed-item-main feed-item-no-author">
        <div class="feed-item-main-content">
                    <div class="shelf-wrapper clearfix">
          <div class="compact-shelf shelf-item yt-uix-shelfslider yt-uix-shelfslider-at-head yt-uix-shelfslider-at-tail vve-check clearfix  yt-section-hover-container feeds-mode yt-uix-tdl"  data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CIoBENwc">
            <h2 class="branded-page-module-title">
          
      <a href="/channel/UCEgdi0XIXXZ-qJOFPf4JSKw" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink g-hovercard" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw" data-ytid="UCEgdi0XIXXZ-qJOFPf4JSKw">
                <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/pfp/0286c6db6cb631004d6f522ad91131af.png" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>

    <span class="branded-page-module-title-text">
      Sports
    </span>

      </a>
  </h2>


    

    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
                                                             <?php
                                                                $stmt = $__db->prepare("SELECT * FROM videos WHERE category = 'Sports' ORDER BY rand() LIMIT 11");
                                                                $stmt->execute();
                                                                while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
																	$_user22 = $__user_h->fetch_user_username($video['author']);
                                                                    $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                                                                    $video['duration'] = $__time_h->timestamp($video['duration']);
                                                                    $video['views'] = $__video_h->fetch_video_views($video['rid']);
                                                                    $video['author'] = htmlspecialchars($video['author']);		
                                                                    $video['title'] = htmlspecialchars($video['title']);
                                                                    $video['description'] = $__video_h->shorten_description($video['description'], 50);
                                                            ?><?php if($__user_h->user_exists($_user22['username'])) { ?>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid vve-check"
      data-visibility-tracking="QMr37N_Czq_M-wE="
  >
    <div class="yt-lockup-thumbnail"
    >
      
  <a href="/watch?v=<?php echo $video['rid']; ?>" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto spf-link"  data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA4QwBs">    <span class="video-thumb  yt-thumb yt-thumb-175 yt-thumb-fluid"
      >
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/thumbs/<?php echo $video['thumbnail']; ?>" width="175"  >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-size-small yt-uix-tooltip" onclick=";return false;" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="-5i-dCv7O8o" role="button"><span class="yt-uix-button-content">  <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
    <span class="video-time"><?php echo $video['duration'] ?></span>
</a>

    </div>
    <div class="yt-lockup-content">
            <h3 class="yt-lockup-title">
    <a class="yt-uix-sessionlink yt-uix-tile-link spf-link yt-ui-ellipsis yt-ui-ellipsis-2"
        dir="ltr"
        title="<?php echo $video['title']; ?>"
        data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA8Qvxs"
        href="/watch?v=<?php echo $video['rid']; ?>"
      >
        <?php echo $video['title']; ?>
    </a>
  </h3>


  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="<?php if($_user22['vanity'])	{	?>/<?php echo htmlspecialchars($_user22['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($video['author']); ?><?php } ?>" class="g-hovercard yt-uix-sessionlink yt-user-name spf-link" data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA0QwRs" dir="ltr" data-ytid="UCeiYXex_fwgYDonaTcSIk6w" data-name="g-high-cpv">												<?php if($_user22['title'])	{	?>
												<?php echo htmlspecialchars($_user22['title']); ?>
												<?php } else {	?>
												<?php echo htmlspecialchars($_user22['username']); ?>
												<?php	}	?></a>  <?php if($__user_h->if_partner($_user22['username'])) { ?><img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"><?php } ?>

        </li>
      <li><?php echo $video['views'] ?> views</li>
        <li class="yt-lockup-deemphasized-text">
            <?php echo $video['age'] ?>
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
																<?php } } ?>  </ul>
  </div>


      <button class="yt-uix-shelfslider-prev yt-uix-button yt-uix-button-shelf-slider-pager yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-prev-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Previous">
 </span></button>
      <button class="yt-uix-shelfslider-next yt-uix-button yt-uix-button-shelf-slider-pager yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">  <img class="yt-uix-shelfslider-next-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Next">
 </span></button>
    </div>

  </div>

        <span class="feed-item-action-menu ">
          

      <button class="flip hide-until-delayloaded yt-uix-button yt-uix-button-action-menu yt-uix-button-size-default yt-uix-button-has-icon yt-uix-button-empty" onclick=";return false;" type="button" data-button-has-sibling-menu="True" role="button" aria-pressed="false" aria-expanded="false" aria-haspopup="true" aria-activedescendant="" aria-label="Actions for this feed item"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-action-menu" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""><ul class=" yt-uix-button-menu yt-uix-button-menu-action-menu" role="menu" aria-haspopup="true" style="display: none;"><li role="menuitem" id="aria-id-97619760846"><span class="dismiss-menu-choice yt-uix-button-menu-item" data-dismissal-token="CAES5AFBRkFCOXpmcEpwal9vZWp1Ump3YUhRT0k5SkRDbjFoN0VLY0xHQWpwRmpMSEFpTTl4bTB0aFp6TFd1QmVPcnZVLVQyMm01dTkzRTVEU3lsUXB5SmY4LXpZLU5ZYmlRVFZCMHJSRkUzbUlOTk8tSzhsbEtzQVN4OUE0TnJFVmhMand4WFBfQTU4UWFlTXZIekxpTlg1YmZsaHI1YWI3dkFra3V4Y3gtbHhpMGVyaWNfRUl0U3ItRDFacjJkZjMzSVN5U1ozeUY1ay1OY1c5bGVNWlFGb1FCTzRpRUtxXzhqalZUVEE%3D" onclick=";return false;" aria-label="Removes the selected feed item from the feed." data-action="hide" >Hide these videos</span></li></ul></button>
        <div class="yt-uix-overlay hid">
    <div class="  yt-uix-overlay-target yt-uix-overlay-watch-it-again hid">
    </div>
        <div class="yt-dialog hid ">
    <div class="yt-dialog-base">
      <span class="yt-dialog-align"></span>
      <div class="yt-dialog-fg">
        <div class="yt-dialog-fg-content">
            <div class="yt-dialog-header">
                <h2 class="yt-dialog-title">
                        Permanently remove this section?


                </h2>
            </div>
          <div class="yt-dialog-loading">
              <div class="yt-dialog-waiting-content">
    <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Loading...</div>
  </div>

          </div>
          <div class="yt-dialog-content">
              <p class="shelf-dismissal-confirmation">
Are you sure you want to remove the "Watch it again" section? You can't undo this.
  </p>

  <div class="yt-uix-overlay-actions">
    <button class="yt-uix-overlay-close yt-uix-button yt-uix-button-default yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Cancel </span></button>
    <button class="yt-uix-overlay-close action-never-show-in-feed yt-uix-button yt-uix-button-primary yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Remove section </span></button>
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



        </div>
      </div>
    </div>
      <div class="feed-item-dismissal-notices"><div class="feed-item-dismissal feed-item-dismissal-hide hid">This item has been hidden</div></div>
  </li>


            <li class="feed-item-container yt-section-hover-container browse-list-item-container branded-page-box vve-check " data-sessionlink="ei=E2VUU8utINKz-QODxICoAw">
    <div class="feed-item-dismissable ">
      <div class="feed-item-main feed-item-no-author">
        <div class="feed-item-main-content">
                    <div class="shelf-wrapper clearfix">
        <div class="expanded-shelf shelf-item vve-check  yt-section-hover-container"  data-sessionlink="ei=E2VUU8utINKz-QODxICoAw&amp;ved=CMMBENwc">        <h2 class="branded-page-module-title">
          
      <a href="/channel/UCOpNcN46UbXVtpKMrmU4Abg" class="yt-uix-sessionlink branded-page-module-title-link spf-nolink g-hovercard" data-sessionlink="ei=E2VUU8utINKz-QODxICoAw" data-ytid="UCOpNcN46UbXVtpKMrmU4Abg">
                <span class="video-thumb  yt-thumb yt-thumb-20"
      >
      <span class="yt-thumb-square">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/pfp/6ff7375616e6ec95319f579592342dc2.png" width="20"  height="20" >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>

    <span class="branded-page-module-title-text">
      Gaming
    </span>

      </a>
  </h2>


    

    <div class="compact-shelf-content-container">
        <div class="yt-uix-shelfslider-body">
    <ul class="yt-uix-shelfslider-list">
                                                             <?php
                                                                $stmt = $__db->prepare("SELECT * FROM videos WHERE category = 'Gaming' ORDER BY rand() LIMIT 11");
                                                                $stmt->execute();
                                                                while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
																	$_user22 = $__user_h->fetch_user_username($video['author']);
                                                                    $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                                                                    $video['duration'] = $__time_h->timestamp($video['duration']);
                                                                    $video['views'] = $__video_h->fetch_video_views($video['rid']);
                                                                    $video['author'] = htmlspecialchars($video['author']);		
                                                                    $video['title'] = htmlspecialchars($video['title']);
                                                                    $video['description'] = $__video_h->shorten_description($video['description'], 50);
                                                            ?><?php if($__user_h->user_exists($_user22['username'])) { ?>
        <li class="channels-content-item yt-shelf-grid-item yt-uix-shelfslider-item ">
            



    <div class="yt-lockup clearfix  yt-lockup-video yt-lockup-grid vve-check"
      data-visibility-tracking="QMr37N_Czq_M-wE="
  >
    <div class="yt-lockup-thumbnail"
    >
      
  <a href="/watch?v=<?php echo $video['rid']; ?>" class="ux-thumb-wrap yt-uix-sessionlink yt-fluid-thumb-link contains-addto spf-link"  data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA4QwBs">    <span class="video-thumb  yt-thumb yt-thumb-175 yt-thumb-fluid"
      >
      <span class="yt-thumb-default">
        <span class="yt-thumb-clip">
          <img alt="Thumbnail" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/thumbs/<?php echo $video['thumbnail']; ?>" width="175"  >
          <span class="vertical-align"></span>
        </span>
      </span>
    </span>


  <button class="addto-button video-actions spf-nolink hide-until-delayloaded addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-size-small yt-uix-tooltip" onclick=";return false;" type="button" title="Watch Later" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="-5i-dCv7O8o" role="button"><span class="yt-uix-button-content">  <img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
    <span class="video-time"><?php echo $video['duration'] ?></span>
</a>

    </div>
    <div class="yt-lockup-content">
            <h3 class="yt-lockup-title">
    <a class="yt-uix-sessionlink yt-uix-tile-link spf-link yt-ui-ellipsis yt-ui-ellipsis-2"
        dir="ltr"
        title="<?php echo $video['title']; ?>"
        data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA8Qvxs"
        href="/watch?v=<?php echo $video['rid']; ?>"
      >
        <?php echo $video['title']; ?>
    </a>
  </h3>


  <div class="yt-lockup-meta">
    <ul class="yt-lockup-meta-info">
        <li>
          
by <a href="<?php if($_user22['vanity'])	{	?>/<?php echo htmlspecialchars($_user22['vanity']); ?><?php } else{ ?>/user/<?php echo htmlspecialchars($video['author']); ?><?php } ?>" class="g-hovercard yt-uix-sessionlink yt-user-name spf-link" data-sessionlink="feature=g-high-cpv&amp;ei=E2VUU8utINKz-QODxICoAw&amp;ved=CA0QwRs" dir="ltr" data-ytid="UCeiYXex_fwgYDonaTcSIk6w" data-name="g-high-cpv">												<?php if($_user22['title'])	{	?>
												<?php echo htmlspecialchars($_user22['title']); ?>
												<?php } else {	?>
												<?php echo htmlspecialchars($_user22['username']); ?>
												<?php	}	?></a>  <?php if($__user_h->if_partner($_user22['username'])) { ?><img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified"><?php } ?>

        </li>
      <li><?php echo $video['views'] ?> views</li>
        <li class="yt-lockup-deemphasized-text">
            <?php echo $video['age'] ?>
        </li>
    </ul>
  </div>
  
  
  

    </div>
    
  </div>



        </li>
																<?php } } ?></div>
        <span class="feed-item-action-menu ">
          

      <button class="flip hide-until-delayloaded yt-uix-button yt-uix-button-action-menu yt-uix-button-size-default yt-uix-button-has-icon yt-uix-button-empty" onclick=";return false;" type="button" data-button-has-sibling-menu="True" role="button" aria-pressed="false" aria-expanded="false" aria-haspopup="true" aria-activedescendant="" aria-label="Actions for this feed item"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-action-menu" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></span><img class="yt-uix-button-arrow" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""><ul class=" yt-uix-button-menu yt-uix-button-menu-action-menu" role="menu" aria-haspopup="true" style="display: none;"><li role="menuitem" id="aria-id-48876568613"><span class="dismiss-menu-choice yt-uix-button-menu-item" data-dismissal-token="CAESeUFGQUI5emZwSlRkM3BuMmhESUNrZWk5XzZuQ3lpcHRqOEMwRzF3Y0xqdm5DRXIwUTFyNEowSUdSVVhXN01rTWl0NW1aaTkwYXJBYV9CVjdPYlptZlVYZGhpb3hhRTFTT3NJRENkXzZlSTJ6QzM2Y3dvSnBNelVVb0U%3D" onclick=";return false;" aria-label="Removes the selected feed item from the feed." data-action="hide" >Hide these videos</span></li></ul></button>
        <div class="yt-uix-overlay hid">
    <div class="  yt-uix-overlay-target yt-uix-overlay-watch-it-again hid">
    </div>
        <div class="yt-dialog hid ">
    <div class="yt-dialog-base">
      <span class="yt-dialog-align"></span>
      <div class="yt-dialog-fg">
        <div class="yt-dialog-fg-content">
            <div class="yt-dialog-header">
                <h2 class="yt-dialog-title">
                        Permanently remove this section?


                </h2>
            </div>
          <div class="yt-dialog-loading">
              <div class="yt-dialog-waiting-content">
    <div class="yt-spinner-img"></div><div class="yt-dialog-waiting-text">Loading...</div>
  </div>

          </div>
          <div class="yt-dialog-content">
              <p class="shelf-dismissal-confirmation">
Are you sure you want to remove the "Watch it again" section? You can't undo this.
  </p>

  <div class="yt-uix-overlay-actions">
    <button class="yt-uix-overlay-close yt-uix-button yt-uix-button-default yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Cancel </span></button>
    <button class="yt-uix-overlay-close action-never-show-in-feed yt-uix-button yt-uix-button-primary yt-uix-button-size-default" onclick=";return false;" type="button"  role="button"><span class="yt-uix-button-content">Remove section </span></button>
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



        </div>
      </div>
    </div>
      <div class="feed-item-dismissal-notices"><div class="feed-item-dismissal feed-item-dismissal-hide hid">This item has been hidden</div></div>
  </li>



  </ul>


  </div>

  </div>

  <div id="feed-error" class="individual-feed  hid">
    <p class="feed-message">
We were unable to complete the request, please try again later.
    </p>
  </div>

  <div id="feed-loading-template" class=" hid">
    <div class="feed-message">
        <p class="yt-spinner">
      <img class="yt-spinner-img" src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Loading icon" title="">

    <span class="yt-spinner-message">
Loading...
    </span>
  </p>

    </div>
  </div>

    </div>
    <div id="footer-ads">
              


  <div id="ad_creative_3" class="ad-div " style="z-index: 1">
    <div id="ad_creative_div_3"></div>
    <script>(function() {function tagMpuIframe() {var containerEl = document.getElementById('ad_creative_div_3');if (!containerEl) {return;}var iframeEl = document.createElement('iframe');var iframeSrc = 'https://ad.doubleclick.net/N6762/adi/mkt.ythome_1x1/;sz=1x1;tile=3;ssl=1;dc_yt=1;kga=-1;kgg=-1;klg=en;kmyd=ad_creative_3;ytexp=907050,943601,938631,916807,914072,916612,936109;ord=' +Math.floor(Math.random() * 10000000000000000) +'?';iframeEl.id = 'ad_creative_iframe_3';iframeEl.width = '1';iframeEl.height = '1';iframeEl.style.cssText = 'z-index:1;';iframeEl.scrolling = 'no';iframeEl.frameBorder = '0';containerEl.appendChild(iframeEl);iframeEl.src = iframeSrc;}tagMpuIframe();})();</script>
  </div>


    </div>

  </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div></div></div></div>  <div id="footer-container" class="yt-base-gutter"><div id="footer"><div id="footer-main"><div id="footer-logo"><a href="/" title="YouTube home"><img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="YouTube home"></a></div>  <ul class="pickers yt-uix-button-group" data-button-toggle-group="optional">
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

