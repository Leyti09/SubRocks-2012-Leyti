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
	$__server->page_embeds->page_title = "SubRocks";
	$__server->page_embeds->page_description = "FulpTube is a website that is cool";
	$__server->page_embeds->page_url = "https://subrock.rocks/";
?>
<!DOCTYPE html>
<!-- saved from url=(0063)https://web.archive.org/web/20121031174402/youtube.com/testtube -->
<html lang="en" dir="ltr"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><script id="js-1582103043" src="./testrocks_files/www-searchbox-vflOEotgN.js.загрузка" data-loaded="true"></script><script src="./testrocks_files/analytics.js.загрузка" type="text/javascript"></script>
<script type="text/javascript">window.addEventListener('DOMContentLoaded',function(){var v=archive_analytics.values;v.service='wb';v.server_name='wwwb-app201.us.archive.org';v.server_ms=1175;archive_analytics.send_pageview({});});</script>
<script type="text/javascript" src="./testrocks_files/bundle-playback.js.загрузка" charset="utf-8"></script>
<script type="text/javascript" src="./testrocks_files/wombat.js.загрузка" charset="utf-8"></script>
<script type="text/javascript">
  __wm.init("https://web.archive.org/web");
  __wm.wombat("https://www.youtube.com/testtube","20121031174402","https://web.archive.org/","web","https://web-static.archive.org/_static/",
	      "1351705442");
</script>
<link rel="stylesheet" type="text/css" href="./testrocks_files/banner-styles.css">
<link rel="stylesheet" type="text/css" href="./testrocks_files/iconochive.css">
<!-- Start Wayback Rewrite JS Include -->
<script type="text/javascript" src="./testrocks_files/jwplayer.js.загрузка" charset="utf-8"></script>
<script type="text/javascript" src="./testrocks_files/bundle-video.js.загрузка" charset="utf-8"></script>
<script type="text/javascript">
_wmVideos_.init({ prefix:"/web" });
</script>
<!-- End Wayback Rewrite JS Include -->

      <script>
var yt = yt || {};yt.timing = yt.timing || {};yt.timing.tick = function() {};yt.timing.info = function() {};    </script>

<title>SubRocks</title><link rel="search" type="application/opensearchdescription+xml" href="https://web.archive.org/web/20121031174402/http://www.youtube.com/opensearch?locale=en_US" title="YouTube Video Search"><link rel="icon" href="https://web.archive.org/web/20121031174402im_/https://s.ytimg.com/yts/img/favicon-vfldLzJxy.ico" type="image/x-icon"><link rel="shortcut icon" href="https://web.archive.org/web/20121031174402im_/https://s.ytimg.com/yts/img/favicon-vfldLzJxy.ico" type="image/x-icon">   <link rel="icon" href="https://web.archive.org/web/20121031174402im_/http://s.ytimg.com/yts/img/favicon.ico" sizes="32x32">  <meta name="description" content="Share your videos with friends, family, and the world">
  <meta name="keywords" content="video, sharing, camera phone, video phone, free, upload">
  <link id="css-2281657340" rel="stylesheet" href="/yt/cssbin/www-core-vfluMRDnk.css">
  <link id="css-3031658408" rel="stylesheet" href="/yt/cssbin/www-the-rest-vflzYVqky.css">
    <link id="css-3251794819" rel="stylesheet" href="/yt/cssbin/www-static-vflJ4wqNO.css">

  
<style type="text/css">.gssb_c{border:0;position:absolute;z-index:989}.gssb_e{border:1px solid #ccc;border-top-color:#d9d9d9;box-shadow:0 2px 4px rgba(0,0,0,0.2);-webkit-box-shadow:0 2px 4px rgba(0,0,0,0.2);cursor:default}.gssb_f{visibility:hidden;white-space:nowrap}.gssb_k{border:0;display:block;position:absolute;top:0;z-index:988}.gsdd_a{border:none!important}.gsib_a{width:100%;padding:4px 6px 0}.gsib_a,.gsib_b{vertical-align:top}.gssb_a{padding:0 7px}.gssb_a,.gssb_a td{white-space:nowrap;overflow:hidden;line-height:22px}#gssb_b{font-size:11px;color:#36c;text-decoration:none}#gssb_b:hover{font-size:11px;color:#36c;text-decoration:underline}.gssb_m{color:#000;background:#fff}.gssb_g{text-align:center;padding:8px 0 7px;position:relative}.gssb_h{font-size:15px;height:28px;margin:0.2em;-webkit-appearance:button}.gssb_i{background:#eee}.gss_ifl{visibility:hidden;padding-left:5px}.gssb_i .gss_ifl{visibility:visible}a.gssb_j{font-size:13px;color:#36c;text-decoration:none;line-height:100%}a.gssb_j:hover{text-decoration:underline}.gssb_l{height:1px;background-color:#e5e5e5}.gscp_a,.gscp_c,.gscp_d,.gscp_e,.gscp_f{display:inline-block;vertical-align:bottom}.gscp_f{border:none}.gscp_a{background:#d9e7fe;border:1px solid #9cb0d8;cursor:default;outline:none;text-decoration:none!important;user-select:none;-webkit-user-select:none;}.gscp_a:hover{border-color:#869ec9}.gscp_a.gscp_b{background:#4787ec;border-color:#3967bf}.gscp_c{color:#444;font-size:13px;font-weight:bold}.gscp_d{color:#aeb8cb;cursor:pointer;font:21px arial,sans-serif;line-height:inherit;padding:0 7px}.gscp_d{position:relative;top:1px}.gscp_a:hover .gscp_d{color:#575b66}.gscp_c:hover,.gscp_a .gscp_d:hover{color:#222}.gscp_a.gscp_b .gscp_c,.gscp_a.gscp_b .gscp_d{color:#fff}.gscp_e{height:100%;padding:0 4px}a.gspqs_a{padding:0 3px 0 8px}.gspqs_b{color:#666;line-height:22px}.gspr_a{padding-right:1px}.gsq_a{padding:0}.gsfe_a{border:1px solid #b9b9b9;border-top-color:#a0a0a0;box-shadow:inset 0px 1px 2px rgba(0,0,0,0.1);-moz-box-shadow:inset 0px 1px 2px rgba(0,0,0,0.1);-webkit-box-shadow:inset 0px 1px 2px rgba(0,0,0,0.1);}.gsfe_b{border:1px solid #4d90fe;outline:none;box-shadow:inset 0px 1px 2px rgba(0,0,0,0.3);-moz-box-shadow:inset 0px 1px 2px rgba(0,0,0,0.3);-webkit-box-shadow:inset 0px 1px 2px rgba(0,0,0,0.3);}.gsok_a{background:url(data:image/gif;base64,R0lGODlhEwALAKECAAAAABISEv///////yH5BAEKAAIALAAAAAATAAsAAAIdDI6pZ+suQJyy0ocV3bbm33EcCArmiUYk1qxAUAAAOw==) no-repeat center;display:inline-block;height:11px;line-height:0;width:19px}.gsok_a img{border:none;visibility:hidden}.gsst_a{display:inline-block}.gsst_a{cursor:pointer;padding:0 4px}.gsst_a:hover{text-decoration:none!important}.gsst_b{font-size:16px;padding:0 2px;user-select:none;-webkit-user-select:none;white-space:nowrap}.gsst_e{opacity:0.55;}.gsst_a:hover .gsst_e,.gsst_a:focus .gsst_e{opacity:0.72;}.gsst_a:active .gsst_e{opacity:1;}.gsst_f{background:white;text-align:left}.gsst_g{background-color:white;border:1px solid #ccc;border-top-color:#d9d9d9;box-shadow:0 2px 4px rgba(0,0,0,0.2);-webkit-box-shadow:0 2px 4px rgba(0,0,0,0.2);margin:-1px -3px;padding:0 6px}.gsst_h{background-color:white;height:1px;margin-bottom:-1px;position:relative;top:-1px}.gsfi{font-size:16px}.gsfs{font-size:16px}a.gssb_j{font-size:12px;color:#03c}.gssb_a,.gssb_a td{line-height:20px}.gssb_a{padding:0 6px}.gssb_c{z-index:3000001}.gssb_i td{background:#eee}.gssb_k{z-index:3000000}.gssb_l{margin:2px 0}.gsib_a{padding:0 4px}.gsok_a{padding:0}.gsok_a img{display:block}.gsfe_b{border:1px solid #1c62b9;box-shadow:inset 0 1px 2px rgba(0,0,0,0.3);-webkit-box-shadow:inset 0 1px 2px rgba(0,0,0,0.3);outline:none;}a.gscp_a{position:relative;background:#e2e2e2;border:1px solid #bbb;border-radius:3px}.gsfe_a a.gscp_a{border-width:1px;border-style:solid;border-color:#bbb}a.gscp_a.gscp_b{border-color:#777!important;background:#999;outline:none}.gscp_c{color:#666;font-size:11px;font-weight:bold;padding-right:20px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);-ms-filter:"progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#80ffffff,Positive=true)";zoom:1;filter:progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#80ffffff,Positive=true)}.gsfe_a a.gscp_a .gscp_c{color:#444}a.gscp_a.gscp_b .gscp_c,.gsfe_a a.gscp_a.gscp_b .gscp_c{color:#fff;text-shadow:0 1px 0 rgba(100, 100, 100, 0.5);-ms-filter:"progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#80646464,Positive=true)";zoom:1;filter:progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#80646464,Positive=true)}.gscp_d{position:absolute;padding:0;background:url(//web.archive.org/web/20121031174402/http://s.ytimg.com/yts/img/icons/close-vflrEJzIW.png);background-repeat:no-repeat;background-position-y:0;right:3px;top:6px;font-size:0;width:13px;height:13px}.gscp_d:hover{background-position-y:-13px}a.gscp_a.gscp_b .gscp_d{background-position-y:-26px}.gsfe_a a.gscp_a.gscp_b .gscp_d:hover{background-position-y:-39px}.gscp_f{background:#000}</style></head>
<!-- machid: sWkFSZzctYUFHdmhOZmtDWXllLWZpZW16WlJwZ1FjZEY3RE5zdTJMWXVKb2xKdUo0VzVxc0ln -->



  <body id="" class="date-20121031 en_US ltr   ytg-old-clearfix guide-feed-v2 " dir="ltr"><!-- BEGIN WAYBACK TOOLBAR INSERT -->
<script>__wm.rw(0);</script>
<div id="wm-ipp-base" lang="en" style="display: block; direction: ltr; height: 1px;"><template shadowrootmode="closed"><div id="wm-ipp" style="position:fixed;left:0;top:0;right:0;" class="">
<div id="donato" style="position:relative;width:100%;">
  <div id="donato-base">
    <iframe id="donato-if" src="https://archive.org/includes/donate.php?as_page=1&amp;platform=wb&amp;referer=https%3A//web.archive.org/web/20121031174402/youtube.com/testtube" scrolling="no" frameborder="0" style="width:100%; height:100%">
    </iframe>
  </div>
</div><div id="wm-ipp-inside" style="display: none;">
  <div id="wm-toolbar" style="position:relative;display:flex;flex-flow:row nowrap;justify-content:space-between;">
    <div id="wm-logo" style="/*width:110px;*/padding-top:12px;">
      <a href="https://web.archive.org/web/" title="Wayback Machine home page"><img src="https://web-static.archive.org/_static/images/toolbar/wayback-toolbar-logo-200.png" srcset="https://web-static.archive.org/_static/images/toolbar/wayback-toolbar-logo-100.png, https://web-static.archive.org/_static/images/toolbar/wayback-toolbar-logo-150.png 1.5x, https://web-static.archive.org/_static/images/toolbar/wayback-toolbar-logo-200.png 2x" alt="Wayback Machine" style="width:100px" border="0"></a>
    </div>
    <div class="c" style="display:flex;flex-flow:column nowrap;justify-content:space-between;flex:1;">
      <form class="u" style="display:flex;flex-direction:row;flex-wrap:nowrap;" target="_top" method="get" action="https://web.archive.org/web/submit" name="wmtb" id="wmtb"><input type="text" name="url" id="wmtbURL" value="http://youtube.com/testtube" onfocus="this.focus();this.select();" style="flex:1;" autocomplete="off"><input type="hidden" name="type" value="replay"><input type="hidden" name="date" value="20121031174402"><input type="submit" value="Go">
      </form>
      <div style="display:flex;flex-flow:row nowrap;align-items:flex-end;">
                <div class="s" id="wm-nav-captures" style="flex:1;"><a class="t" href="https://web.archive.org/web/*/http://youtube.com/testtube" title="See a list of every capture for this URL">56,849 captures</a><div class="r" title="Timespan for captures of this URL">10 Dec 2006 - 30 Dec 2023</div></div>
        <div class="k">
          <a href="https://web.archive.org/web/20150501000000/http://youtube.com/testtube" id="wm-graph-anchor">
            <div id="wm-ipp-sparkline" title="Explore captures for this URL" style="position: relative">
              <canvas id="wm-sparkline-canvas" width="725" height="27" border="0"></canvas>
            <div class="yt" style="display: none; width: 25px; height: 27px; left: 475px;"></div><div class="mt" style="display: none; width: 2px; height: 27px; left: 484px;"></div></div>
          </a>
        </div>
      </div>
    </div>
    <div class="n">
      <table>
        <tbody>
          <!-- NEXT/PREV MONTH NAV AND MONTH INDICATOR -->
          <tr class="m">
            <td class="b" nowrap="nowrap"><a href="https://web.archive.org/web/20120930165447/http://www.youtube.com/testtube" title="30 Sep 2012"><strong>Sep</strong></a></td>
            <td class="c" id="displayMonthEl" title="You are here: 17:44:02 Oct 31, 2012">Oct</td>
            <td class="f" nowrap="nowrap"><a href="https://web.archive.org/web/20121201024322/http://www.youtube.com/testtube" title="01 Dec 2012"><strong>Dec</strong></a></td>
          </tr>
          <!-- NEXT/PREV CAPTURE NAV AND DAY OF MONTH INDICATOR -->
          <tr class="d">
            <td class="b" nowrap="nowrap"><a href="https://web.archive.org/web/20121029093844/http://www.youtube.com/testtube" title="09:38:44 Oct 29, 2012"><img src="https://web-static.archive.org/_static/images/toolbar/wm_tb_prv_on.png" alt="Previous capture" width="14" height="16" border="0"></a></td>
            <td class="c" id="displayDayEl" style="width:34px;font-size:22px;white-space:nowrap;" title="You are here: 17:44:02 Oct 31, 2012">31</td>
            <td class="f" nowrap="nowrap"><a href="https://web.archive.org/web/20121101200258/https://www.youtube.com/testtube" title="20:02:58 Nov 01, 2012"><img src="https://web-static.archive.org/_static/images/toolbar/wm_tb_nxt_on.png" alt="Next capture" width="14" height="16" border="0"></a></td>
          </tr>
          <!-- NEXT/PREV YEAR NAV AND YEAR INDICATOR -->
          <tr class="y">
            <td class="b" nowrap="nowrap"><a href="https://web.archive.org/web/20111030180332/https://www.youtube.com/testtube" title="30 Oct 2011"><strong>2011</strong></a></td>
            <td class="c" id="displayYearEl" title="You are here: 17:44:02 Oct 31, 2012">2012</td>
            <td class="f" nowrap="nowrap"><a href="https://web.archive.org/web/20131031234217/http://www.youtube.com/testtube" title="31 Oct 2013"><strong>2013</strong></a></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="r" style="display:flex;flex-flow:column nowrap;align-items:flex-end;justify-content:space-between;">
      <div id="wm-btns" style="text-align:right;height:23px;">
                <span class="xxs">
          <div id="wm-save-snapshot-success">success</div>
          <div id="wm-save-snapshot-fail">fail</div>
          <a id="wm-save-snapshot-open" href="https://web.archive.org/web/20121031174402/youtube.com/testtube#" title="Share via My Web Archive" style="display: none;">
            <span class="iconochive-web"></span>
          </a>
          <a href="https://archive.org/account/login.php" title="Sign In" id="wm-sign-in" style="display: inline-block;">
            <span class="iconochive-person"></span>
          </a>
          <span id="wm-save-snapshot-in-progress" class="iconochive-web" style="display: none;"></span>
        </span>
                <a class="xxs" href="http://faq.web.archive.org/" title="Get some help using the Wayback Machine" style="top:-6px;"><span class="iconochive-question" style="color:rgb(87,186,244);font-size:160%;"></span></a>
        <a id="wm-tb-close" href="https://web.archive.org/web/20121031174402/youtube.com/testtube#close" style="top:-2px;" title="Close the toolbar"><span class="iconochive-remove-circle" style="color:#888888;font-size:240%;"></span></a>
      </div>
      <div id="wm-share" class="xxs">
        <a href="https://web.archive.org/web/20121031174402/http://web.archive.org/screenshot/http://youtube.com/testtube" id="wm-screenshot" title="screenshot" style="visibility: hidden;">
          <span class="wm-icon-screen-shot"></span>
        </a>
        <a href="https://web.archive.org/web/20121031174402/youtube.com/testtube#" id="wm-video" title="video">
          <span class="iconochive-movies"></span>
        </a>
        <a id="wm-share-facebook" href="https://web.archive.org/web/20121031174402/youtube.com/testtube#" data-url="https://web.archive.org/web/20121031174402/https://www.youtube.com/testtube" title="Share on Facebook" style="margin-right:5px;" target="_blank"><span class="iconochive-facebook" style="color:#3b5998;font-size:160%;"></span></a>
        <a id="wm-share-twitter" href="https://web.archive.org/web/20121031174402/youtube.com/testtube#" data-url="https://web.archive.org/web/20121031174402/https://www.youtube.com/testtube" title="Share on Twitter" style="margin-right:5px;" target="_blank"><span class="iconochive-twitter" style="color:#1dcaff;font-size:160%;"></span></a>
      </div>
      <div style="padding-right:2px;text-align:right;white-space:nowrap;">
        <a id="wm-expand" class="wm-btn wm-closed" href="https://web.archive.org/web/20121031174402/youtube.com/testtube#expand" onclick="__wm.ex(event);return false;"><span id="wm-expand-icon" class="iconochive-down-solid"></span> <span class="xxs" style="font-size:80%;">About this capture</span></a>
      </div>
    </div>
  </div>
    <div id="wm-capinfo" style="border-top:1px solid #777;display:none; overflow: hidden">
        <div id="wm-capinfo-notice" source="api"></div>
            <div id="wm-capinfo-timestamps">
    <div style="background-color:#666;color:#fff;font-weight:bold;text-align:center" title="Timestamps for the elements of this page">TIMESTAMPS</div>
    <div>
      <div id="wm-capresources" style="margin:0 5px 5px 5px;max-height:250px;overflow-y:scroll !important"></div>
      <div id="wm-capresources-loading" style="text-align:left;margin:0 20px 5px 5px;display:none"><img src="https://web-static.archive.org/_static/images/loading.gif" alt="loading"></div>
    </div>
    </div>
  </div></div></div><link rel="stylesheet" type="text/css" href="./testrocks_files/banner-styles.css"><link rel="stylesheet" type="text/css" href="./testrocks_files/iconochive.css"><div class="wb-autocomplete-suggestions "></div></template>
</div><div id="wm-ipp-print">The Wayback Machine - https://web.archive.org/web/20121031174402/https://www.youtube.com/testtube</div>
<script type="text/javascript">//<![CDATA[
__wm.bt(725,27,25,2,"web","http://youtube.com/testtube","20121031174402",1996,"https://web-static.archive.org/_static/",["https://web-static.archive.org/_static/css/banner-styles.css?v=S1zqJCYt","https://web-static.archive.org/_static/css/iconochive.css?v=qtvMKcIJ"], false);
  __wm.rw(1);
//]]></script>
<!-- END WAYBACK TOOLBAR INSERT -->
 

  <form name="logoutForm" method="POST" action="https://web.archive.org/web/20121031174402/http://youtube.com/logout">
    <input type="hidden" name="action_logout" value="1">
  <input name="session_token" type="hidden" value="OipTjNPAWEgm-nbjGauzpOKVihF8MTM1MTc5MTg0M0AxMzUxNzA1NDQz"></form>



  <!-- begin page -->
    <div id="page" class="about-pages">
        
  
  <div id="masthead-container">
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/header.php"); ?>
    </div>
  </div>
  
      <div id="masthead-subnav" class="yt-nav yt-nav-dark ">
    <ul>
      
  <li>
    <a class="yt-nav-item" href="/t/about_youtube">
        About
    </a>
  </li>

      
  <li>
    <a class="yt-nav-item" href="/t/press">
        Press &amp; Blogs
    </a>
  </li>

      
  <li>
    <a class="yt-nav-item" href="/t/copyright_center">
        Copyright
    </a>
  </li>

      
  <li>
    <a class="yt-nav-item" href="/creators">
        Creators &amp; Partners
    </a>
  </li>

      
  <li>
    <a class="yt-nav-item" href="/t/advertising_overview">
        Advertising
    </a>
  </li>

      
  <li>
    <a class="yt-nav-item" href="/dev">
        Developers
    </a>
  </li>

      
  <li>
    <a class="yt-nav-item" href="https://web.archive.org/web/20121031174402/http://support.google.com/youtube/?hl=en-US">
        Help
    </a>
  </li>

    </ul>
  </div>




      <div id="alerts"></div>

    <!-- end masthead -->
  </div>
  <div id="content-container">
    <!-- begin content -->
    <div id="content">
      <div class="ytg-base">
  <div class="ytg-wide">
    <div id="yts-nav" class="ytg-1col">
      <ol>
  <li class="top-level">
    <a href="/dev">
Developer Tools
    </a>
  </li>
  <li class="top-level">
    <a href="/testrocks" class="item-highlight">
TestRocks
    </a>
  </li>
</ol>

    </div>

    <div id="yts-article" class="testtube">
      <div id="header">
TestRocks
      </div>
      <div id="article-container" class="ytg-box">
        <p>
Welcome to TestRocks, our ideas incubator. This is where SubRocks engineers and developers test out recipes and concoctions that aren't quite fully baked and invite you to tell us how they're coming along.
        </p>
        <p>
Your comments will help us improve and perfect the mixtures we're working on. So jump in, play around, and send your feedback directly to the brains behind the scenes.
        </p>

        <div>
          <a class="icon" href="/2009/">
											<img src="/yt/imgbin/testrocks-2009-vfl26Dox4.gif" width="103" height="74" border="0" alt="SubRocks 2009">
										</a>
          <h3>SubRocks 2009</h3>
          <p>
Want to use our 2009 UI? It's in very early stages of development.<br>
            <strong>
              <a href="/2009" onclick="window.open(this.href,&#39;Leanback&#39;, &#39;scrollbar=no, menubar=no, status=no, toolbar=no&#39;); return false">Try out 2009</a>
            </strong>

              &nbsp;|&nbsp; <a href="https://web.archive.org/web/20121031174402/http://www.google.com/support/forum/p/youtube/label?lid=31a878cd44b81c60&amp;hl=en">Feedback?</a>

          </p>
        </div>

        <div>
          <a class="icon" href="/2013">
            <img src="/yt/imgbin/testrocks-html5-vfl26Dox4.gif" width="103" height="74" border="0" alt="SubRocks 2013">
          </a>
          <h3>SubRocks 2013</h3>
          <p>Want to use our 2013 UI? It's in very early stages of development.</p>
          <p>
            <strong><a href="/2013">Try it out</a></strong>
              &nbsp;|&nbsp; <a href="https://web.archive.org/web/20121031174402/http://www.google.com/support/forum/p/youtube/label?lid=31a878cd44b81c60&amp;hl=en">Feedback?</a>
          </p>
        </div>



      </div>
    </div>
  </div>
</div>

    </div>
    <!-- end content -->
  </div>
  <div id="footer-container"><?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/footer.php"); ?></div>
    



  <div id="playlist-bar" class="hid passive editable" data-video-url="/watch?v=&amp;feature=BFql&amp;playnext=1&amp;list=QL" data-list-id="" data-list-type="QL">
    <div id="playlist-bar-bar-container">
      <div id="playlist-bar-bar">
        <div class="yt-alert yt-alert-naked yt-alert-success hid " id="playlist-bar-notifications">  <div class="yt-alert-icon">
    <img src="./testrocks_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-content" role="alert"></div></div>
<span id="playlist-bar-info"><span class="playlist-bar-active playlist-bar-group"><button onclick=";return false;" title="Previous video" type="button" id="playlist-bar-prev-button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-tooltip yt-uix-button-empty" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-prev" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt="Previous video"><span class="yt-valign-trick"></span></span></button><span class="playlist-bar-count"><span class="playing-index">0</span> / <span class="item-count">0</span></span><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" onclick=";return false;" id="playlist-bar-next-button" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-next" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt=""><span class="yt-valign-trick"></span></span></button></span><span class="playlist-bar-active playlist-bar-group"><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" onclick=";return false;" id="playlist-bar-autoplay-button" data-button-toggle="true" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-autoplay" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt=""><span class="yt-valign-trick"></span></span></button><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" onclick=";return false;" id="playlist-bar-shuffle-button" data-button-toggle="true" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-shuffle" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt=""><span class="yt-valign-trick"></span></span></button></span><span class="playlist-bar-passive playlist-bar-group"><button onclick=";return false;" title="Play videos" type="button" id="playlist-bar-play-button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-tooltip yt-uix-button-empty" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-play" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt="Play videos"><span class="yt-valign-trick"></span></span></button><span class="playlist-bar-count"><span class="item-count">0</span></span></span><span id="playlist-bar-title" class="yt-uix-button-group"><span class="playlist-title">Unsaved Playlist</span></span></span>
        <a id="playlist-bar-lists-back" href="https://web.archive.org/web/20121031174402/youtube.com/testtube#">
Return to active list
        </a>

<span id="playlist-bar-controls"><span class="playlist-bar-group"><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-text yt-uix-button-empty" onclick=";return false;" id="playlist-bar-toggle-button" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-toggle" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt=""><span class="yt-valign-trick"></span></span></button></span><span class="playlist-bar-group"><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked yt-uix-button-reverse flip yt-uix-button yt-uix-button-text" onclick=";return false;" data-button-menu-id="playlist-bar-options-menu" data-button-has-sibling-menu="true" role="button"><span class="yt-uix-button-content">Options </span><img class="yt-uix-button-arrow" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt=""></button></span></span>      </div>
    </div>

<div id="playlist-bar-tray-container"><div id="playlist-bar-tray" class="yt-uix-slider yt-uix-slider-fluid"><button class="yt-uix-button playlist-bar-tray-button yt-uix-button-default yt-uix-slider-prev" onclick="return false;"><img class="yt-uix-slider-prev-arrow" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt="Previous video"></button><button class="yt-uix-button playlist-bar-tray-button yt-uix-button-default yt-uix-slider-next" onclick="return false;"><img class="yt-uix-slider-next-arrow" src="./testrocks_files/pixel-vfl3z5WfW.gif" alt="Next video"></button><div class="yt-uix-slider-body"><div id="playlist-bar-tray-content" class="yt-uix-slider-slide"><ol class="video-list"></ol><ol id="playlist-bar-help"><li class="empty playlist-bar-help-message">Your queue is empty. Add videos to your queue using this button: <img src="./testrocks_files/pixel-vfl3z5WfW.gif" class="addto-button-help"><br> or <a href="https://web.archive.org/web/20121031174402/https://accounts.google.com/ServiceLogin?passive=true&amp;continue=https%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26nomobiletemp%3D1%26hl%3Den_US%26next%3D%252Ftesttube&amp;uilel=3&amp;hl=en_US&amp;service=youtube">sign in</a> to load a different list.</li></ol></div><div class="yt-uix-slider-shade-left"></div><div class="yt-uix-slider-shade-right"></div></div></div><div id="playlist-bar-save"></div><div id="playlist-bar-lists" class="dark-lolz"></div><div id="playlist-bar-loading"><img src="./testrocks_files/pixel-vfl3z5WfW.gif" alt="Loading..."><span id="playlist-bar-loading-message">Loading...</span><span id="playlist-bar-saving-message" class="hid">Saving...</span></div><div id="playlist-bar-template" style="display: none;" data-video-thumb-url="//i4.ytimg.com/vi/__video_encrypted_id__/default.jpg"><!--<li class="playlist-bar-item yt-uix-slider-slide-unit __classes__" data-video-id="__video_encrypted_id__"><a href="__video_url__" title="__video_title__" class="yt-uix-sessionlink" data-sessionlink="ei=CO7kmZbnq7MCFVu4IQod_w3tVw%3D%3D&amp;feature=BFa"><span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="https://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="__video_title__" data-thumb-manual="true" data-thumb="__video_thumb_url__" width="106" ><span class="vertical-align"></span></span></span></span><span class="screen"></span><span class="count"><strong>__list_position__</strong></span><span class="play"><img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif"></span><span class="yt-uix-button yt-uix-button-default delete"><img class="yt-uix-button-icon-playlist-bar-delete" src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Delete"></span><span class="now-playing">Now playing</span><span dir="ltr" class="title"><span>__video_title__  <span class="uploader">by __video_display_name__</span>
</span></span><span class="dragger"></span></a></li>--></div><div id="playlist-bar-next-up-template" style="display: none;"><!--<div class="playlist-bar-next-thumb"><span class="video-thumb ux-thumb yt-thumb-default-74 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="//i4.ytimg.com/vi/__video_encrypted_id__/default.jpg" alt="Thumbnail" width="74" ><span class="vertical-align"></span></span></span></span></div>--></div></div>      <div id="playlist-bar-options-menu" class="hid">

    <div id="playlist-bar-extras-menu">
        <ul>
      <li><span class="yt-uix-button-menu-item" data-action="clear">
Clear all videos from this list
      </span></li>
  </ul>

    </div>

    <ul>
      <li><span class="yt-uix-button-menu-item" onclick="window.location.href=&#39;//web.archive.org/web/20121031174402/http://support.google.com/youtube/bin/answer.py?answer=146749&amp;hl=en-US&#39;">Learn more</span></li>
    </ul>
  </div>

  </div>


  
    <div id="shared-addto-watch-later-login" class="hid">
      <a href="https://web.archive.org/web/20121031174402/https://accounts.google.com/ServiceLogin?passive=true&amp;continue=https%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26nomobiletemp%3D1%26hl%3Den_US%26next%3D%252Ftesttube&amp;uilel=3&amp;hl=en_US&amp;service=youtube" class="sign-in-link">Sign in</a> to add this to a playlist

    </div>

  <div id="shared-addto-menu" style="display: none;" class="hid sign-in">
      <div class="addto-menu">
        <div id="addto-list-panel" class="menu-panel active-panel">
        <span class="yt-uix-button-menu-item yt-uix-tooltip sign-in" data-possible-tooltip="" data-tooltip-show-delay="750"><a href="https://web.archive.org/web/20121031174402/https://accounts.google.com/ServiceLogin?passive=true&amp;continue=https%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26nomobiletemp%3D1%26hl%3Den_US%26next%3D%252Ftesttube&amp;uilel=3&amp;hl=en_US&amp;service=youtube" class="sign-in-link">Sign in</a> to add this to a playlist
</span>

  </div>
  <div id="addto-list-saved-panel" class="menu-panel">
    <div class="panel-content">
      <div class="yt-alert yt-alert-naked yt-alert-success  ">  <div class="yt-alert-icon">
    <img src="./testrocks_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
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
      <img src="./testrocks_files/pixel-vfl3z5WfW.gif">
      <span class="error-details"></span>
      <a class="show-menu-link">Back to list</a>
    </div>
  </div>

        <div id="addto-note-input-panel" class="menu-panel">
    <div class="panel-content">
      <div class="yt-alert yt-alert-naked yt-alert-success  ">  <div class="yt-alert-icon">
    <img src="./testrocks_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
  </div>
<div class="yt-alert-content" role="alert">    <span class="yt-alert-vertical-trick"></span>
    <div class="yt-alert-message">
              <span class="message">Added to playlist:</span>
  <span class="addto-title yt-uix-tooltip" title="More information about this playlist" data-tooltip-show-delay="750"></span>

    </div>
</div></div>
    </div>
<div class="yt-uix-char-counter" data-char-limit="150"><div class="addto-note-box addto-text-box"><textarea id="addto-note" class="addto-note yt-uix-char-counter-input" maxlength="150"></textarea><label for="addto-note" class="addto-note-label">Add an optional note</label></div><span class="yt-uix-char-counter-remaining">150</span></div>    <button disabled="disabled" type="button" class="playlist-save-note yt-uix-button yt-uix-button-default" onclick=";return false;" role="button"><span class="yt-uix-button-content">Add note </span></button>
  </div>
  <div id="addto-note-saving-panel" class="menu-panel">
    <div class="panel-content loading-content">
      <img src="./testrocks_files/pixel-vfl3z5WfW.gif">
      <span>Saving note...</span>
    </div>
  </div>
  <div id="addto-note-saved-panel" class="menu-panel">
    <div class="panel-content">
      <img src="./testrocks_files/pixel-vfl3z5WfW.gif">
      <span class="message">Note added to:</span>
    </div>
  </div>
  <div id="addto-note-error-panel" class="menu-panel">
    <div class="panel-content">
      <img src="./testrocks_files/pixel-vfl3z5WfW.gif">
      <span class="message">Error adding note:</span>
      <ul class="error-details"></ul>
      <a class="add-note-link">Click to add a new note</a>
    </div>
  </div>
  <div class="close-note hid">
    <img src="./testrocks_files/pixel-vfl3z5WfW.gif" class="close-button">
  </div>

  </div>

  </div>


  

    </div>
  <!-- end page -->
    
  
    <script id="js-1682662951" src="./testrocks_files/www-core-vfloDTB4f.js.загрузка" data-loaded="true"></script>


  <script>
        yt.setConfig({
      'XSRF_TOKEN': 'OipTjNPAWEgm-nbjGauzpOKVihF8MTM1MTc5MTg0M0AxMzUxNzA1NDQz',
      'XSRF_FIELD_NAME': 'session_token'
    });

    yt.setConfig('XSRF_REDIRECT_TOKEN', 'WBuoW8qXLNPUvIKDdxEN-SXfWRJ8MTM1MTc5MTg0M0AxMzUxNzA1NDQz');

    yt.setConfig({
      'EVENT_ID': "CO7kmZbnq7MCFVu4IQod_w3tVw==",
      'CURRENT_URL': "https:\/\/web.archive.org\/web\/20121031174402\/http:\/\/www.youtube.com\/testtube",
      'LOGGED_IN': false,
      'SESSION_INDEX': null,

      'WATCH_CONTEXT_CLIENTSIDE': false,

      'FEEDBACK_LOCALE_LANGUAGE': "en",
      'FEEDBACK_LOCALE_EXTRAS': {"logged_in": false, "experiments": "906370,922401,920704,912806,927201,925003,913546,913556,920201,900816,911112,901451", "guide_subs": "NA", "accept_language": null}    });
  </script>


  
  
  
    




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
    'DRAGDROP_BINARY_URL': "\/\/web.archive.org\/web\/20121031174402\/http:\/\/s.ytimg.com\/yts\/jsbin\/www-dragdrop-vfllufND3.js",
    'PLAYLIST_BAR_PLAYING_INDEX': -1  });

    yt.setAjaxToken('addto_ajax_logged_out', "qunZ-pBjnJGPpW18qemz7u0d69R8MTM1MTc5MTg0M0AxMzUxNzA1NDQz");

    yt.pubsub.subscribe('init', yt.www.lists.init);









    yt.setConfig({'SBOX_JS_URL': "\/\/web.archive.org\/web\/20121031174402\/http:\/\/s.ytimg.com\/yts\/jsbin\/www-searchbox-vflOEotgN.js",'SBOX_SETTINGS': {"CLOSE_ICON_URL": "\/\/web.archive.org\/web\/20121031174402\/http:\/\/s.ytimg.com\/yts\/img\/icons\/close-vflrEJzIW.png", "SHOW_CHIP": false, "PSUGGEST_TOKEN": null, "REQUEST_DOMAIN": "us", "EXPERIMENT_ID": -1, "SESSION_INDEX": null, "HAS_ON_SCREEN_KEYBOARD": false, "CHIP_PARAMETERS": {}, "REQUEST_LANGUAGE": "en"},'SBOX_LABELS': {"SUGGESTION_DISMISS_LABEL": "Dismiss", "SUGGESTION_DISMISSED_LABEL": "Suggestion dismissed"}});





  </script>

  <script>
    yt.setMsg({
      'ADDTO_WATCH_LATER_ADDED': "Added",
      'ADDTO_WATCH_LATER_ERROR': "Error"
    });
  </script>

  

  


    <div id="debug">
    
  </div>





<iframe class="gstl_0 gssb_k" style="display: none;" allow="autoplay &#39;self&#39;; fullscreen &#39;self&#39;" src="./testrocks_files/saved_resource.html"></iframe><table cellspacing="0" cellpadding="0" class="gstl_0 gssb_c" style="width: 463px; display: none; top: 109px; left: 366px; position: absolute;"><tbody><tr><td class="gssb_f"></td><td class="gssb_e" style="width: 100%;"></td></tr></tbody></table></body></html>