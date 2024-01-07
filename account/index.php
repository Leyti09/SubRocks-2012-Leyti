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
	$__server->page_embeds->page_description = "SubRocks is a site dedicated to bring back the 2012 layout of YouTube.";
	$__server->page_embeds->page_url = "https://subrock.rocks/";
?>
<!DOCTYPE html>
<!-- saved from url=(0031)https://www.eracast.cc/account/ -->
<html dir="ltr"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><script id="scriptload-559385626" src="./accountnew_files/www-searchbox-vflsHyn9f.js.загрузка" data-loaded="true"></script>
		<title>FulpTube - Account Settings</title>
		<meta property="og:title" content="FulpTube - Account Settings">
		<meta property="og:url" content="https://www.eracast.cc/">
		<meta property="og:description" content="Broadcast yourself on EraCast">
		<meta property="og:image" content="/yt/imgbin/full-size-logo.png">
		<script>
			var yt = yt || {};yt.timing = yt.timing || {};yt.timing.tick = function(label, opt_time) {var timer = yt.timing['timer'] || {};if(opt_time) {timer[label] = opt_time;}else {timer[label] = new Date().getTime();}yt.timing['timer'] = timer;};yt.timing.info = function(label, value) {var info_args = yt.timing['info_args'] || {};info_args[label] = value;yt.timing['info_args'] = info_args;};yt.timing.info('e', "904821,919006,922401,920704,912806,913419,913546,913556,919349,919351,925109,919003,920201,912706");if (document.webkitVisibilityState == 'prerender') {document.addEventListener('webkitvisibilitychange', function() {yt.timing.tick('start');}, false);}yt.timing.tick('start');yt.timing.info('li','0');try {yt.timing['srt'] = window.gtbExternal && window.gtbExternal.pageT() ||window.external && window.external.pageT;} catch(e) {}if (window.chrome && window.chrome.csi) {yt.timing['srt'] = Math.floor(window.chrome.csi().pageT);}if (window.msPerformance && window.msPerformance.timing) {yt.timing['srt'] = window.msPerformance.timing.responseStart - window.msPerformance.timing.navigationStart;}    
		</script>
		<link id="www-core-css" rel="stylesheet" href="/yt/cssbin/www-core-vfluMRDnk.css">
		<link rel="stylesheet" href="/accountnew_files/www-guide-vflx0V5Tq.css">
        <link rel="stylesheet" href="/accountnew_files/www-extra.css">
		<link rel="stylesheet" href="/accountnew_files/www-videos-nav-vflYGt27y.css">
        <link rel="stylesheet" href="/accountnew_files/www-refresh-static-vfl5mtBQc.css">
        <link rel="stylesheet" href="/accountnew_files/www-the-rest-vflNb6rAI.css">
		<script src="./accountnew_files/www-browse-vflu1nggJ.js.загрузка" data-loaded="true"></script>
        <script>
			if (window.yt.timing) {yt.timing.tick("ct");}   
        </script> 
        <style>
            .master-myaccount-top {
                border-bottom: 1px solid #CACACA;
            }

            .www-home-left a {
                padding-bottom: 3px;
                padding-top: 4px;
                font-weight: 700;
                text-align: left;
                color: black;
                padding-left: 2px;
                width: 193px;
                display: inline-block;
            }

            .www-home-left {
                width: 200px;
                border-right: 1px solid #aaa;
            }

            .www-home-right {
                width: 754px;
                padding: 5px;
            }

            .www-home-left a:hover {
                background-color: rgb(239, 239, 239);
                background: -moz-linear-gradient(0deg,rgb(192,192,192,1)0%,rgb(239,239,239,1)115%);
                background: -webkit-linear-gradient(0deg,rgb(192,192,192,1)0%,rgb(239,239,239,1)115%);
                background: linear-gradient(0deg,rgb(192,192,192)0%, rgb(239,239,239)115%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="c0c0c0",endColorstr="#efefef",GradientType=1);
            }

            a[href="/inbox/send"] {
                margin: 0px !important;
                padding: 0px !important;
                position: relative;
                top: 0px !important;
            }

            #search-button {
                margin: 0px;
                margin-bottom: 7px;
                margin-top: 4px;
            }

            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                text-align: left;
                padding: 3px;
            }

            th {
                border: 1px solid #dddddd;
                background: rgb(215,215,215);
                background: -moz-linear-gradient(0deg, rgba(215,215,215,1) 0%, rgba(255,255,255,1) 100%);
                background: -webkit-linear-gradient(0deg, rgba(215,215,215,1) 0%, rgba(255,255,255,1) 100%);
                background: linear-gradient(0deg, rgba(215,215,215,1) 0%, rgba(255,255,255,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#d7d7d7",endColorstr="#ffffff",GradientType=1); 
                height: 14px;
                font-weight: lighter;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .video-manager-info {
                width: 462px;
            }

            .video-filter-options a {
                margin-left: 5px;
                margin-right: 5px;
            }

            .selected {
                font-weight: bold;
                color: black;
            }

            .yt-analytics-calendar {
                background:no-repeat url(/yt/imgbin/calender-31.png);
                background-size: contain;
                width:20px;
                height:20px
            }
        </style>
	<meta http-equiv="origin-trial" content="AymqwRC7u88Y4JPvfIF2F37QKylC04248hLCdJAsh8xgOfe/dVJPV3XS3wLFca1ZMVOtnBfVjaCMTVudWM//5g4AAAB7eyJvcmlnaW4iOiJodHRwczovL3d3dy5nb29nbGV0YWdtYW5hZ2VyLmNvbTo0NDMiLCJmZWF0dXJlIjoiUHJpdmFjeVNhbmRib3hBZHNBUElzIiwiZXhwaXJ5IjoxNjk1MTY3OTk5LCJpc1RoaXJkUGFydHkiOnRydWV9"><style type="text/css">.gssb_c{border:0;position:absolute;z-index:989}.gssb_e{border:1px solid #ccc;border-top-color:#d9d9d9;box-shadow:0 2px 4px rgba(0,0,0,0.2);-webkit-box-shadow:0 2px 4px rgba(0,0,0,0.2);cursor:default}.gssb_f{visibility:hidden;white-space:nowrap}.gssb_k{border:0;display:block;position:absolute;top:0;z-index:988}.gsdd_a{border:none!important}.gsib_a{width:100%;padding:4px 6px 0}.gsib_a,.gsib_b{vertical-align:top}.gssb_a{padding:0 7px}.gssb_a,.gssb_a td{white-space:nowrap;overflow:hidden;line-height:22px}#gssb_b{font-size:11px;color:#36c;text-decoration:none}#gssb_b:hover{font-size:11px;color:#36c;text-decoration:underline}.gssb_m{color:#000;background:#fff}.gssb_g{text-align:center;padding:8px 0 7px;position:relative}.gssb_h{font-size:15px;height:28px;margin:0.2em;-webkit-appearance:button}.gssb_i{background:#eee}.gss_ifl{visibility:hidden;padding-left:5px}.gssb_i .gss_ifl{visibility:visible}a.gssb_j{font-size:13px;color:#36c;text-decoration:none;line-height:100%}a.gssb_j:hover{text-decoration:underline}.gssb_l{height:1px;background-color:#e5e5e5}.gscp_a,.gscp_c,.gscp_d,.gscp_e,.gscp_f{display:inline-block;vertical-align:bottom}.gscp_f{border:none}.gscp_a{background:#d9e7fe;border:1px solid #9cb0d8;cursor:default;outline:none;text-decoration:none!important;user-select:none;-webkit-user-select:none;}.gscp_a:hover{border-color:#869ec9}.gscp_a.gscp_b{background:#4787ec;border-color:#3967bf}.gscp_c{color:#444;font-size:13px;font-weight:bold}.gscp_d{color:#aeb8cb;cursor:pointer;font:21px arial,sans-serif;line-height:inherit;padding:0 7px}.gscp_d{position:relative;top:1px}.gscp_a:hover .gscp_d{color:#575b66}.gscp_c:hover,.gscp_a .gscp_d:hover{color:#222}.gscp_a.gscp_b .gscp_c,.gscp_a.gscp_b .gscp_d{color:#fff}.gscp_e{height:100%;padding:0 4px}a.gspqs_a{padding:0 3px 0 8px}.gspqs_b{color:#666;line-height:22px}.gspr_a{padding-right:1px}.gsq_a{padding:0}.gsfe_a{border:1px solid #b9b9b9;border-top-color:#a0a0a0;box-shadow:inset 0px 1px 2px rgba(0,0,0,0.1);-moz-box-shadow:inset 0px 1px 2px rgba(0,0,0,0.1);-webkit-box-shadow:inset 0px 1px 2px rgba(0,0,0,0.1);}.gsfe_b{border:1px solid #4d90fe;outline:none;box-shadow:inset 0px 1px 2px rgba(0,0,0,0.3);-moz-box-shadow:inset 0px 1px 2px rgba(0,0,0,0.3);-webkit-box-shadow:inset 0px 1px 2px rgba(0,0,0,0.3);}.gsok_a{background:url(data:image/gif;base64,R0lGODlhEwALAKECAAAAABISEv///////yH5BAEKAAIALAAAAAATAAsAAAIdDI6pZ+suQJyy0ocV3bbm33EcCArmiUYk1qxAUAAAOw==) no-repeat center;display:inline-block;height:11px;line-height:0;width:19px}.gsok_a img{border:none;visibility:hidden}.gsst_a{display:inline-block}.gsst_a{cursor:pointer;padding:0 4px}.gsst_a:hover{text-decoration:none!important}.gsst_b{font-size:16px;padding:0 2px;user-select:none;-webkit-user-select:none;white-space:nowrap}.gsst_e{opacity:0.55;}.gsst_a:hover .gsst_e,.gsst_a:focus .gsst_e{opacity:0.72;}.gsst_a:active .gsst_e{opacity:1;}.gsst_f{background:white;text-align:left}.gsst_g{background-color:white;border:1px solid #ccc;border-top-color:#d9d9d9;box-shadow:0 2px 4px rgba(0,0,0,0.2);-webkit-box-shadow:0 2px 4px rgba(0,0,0,0.2);margin:-1px -3px;padding:0 6px}.gsst_h{background-color:white;height:1px;margin-bottom:-1px;position:relative;top:-1px}.gsfi{font-size:16px}.gsfs{font-size:16px}a.gssb_j{font-size:12px;color:#03c}.gssb_a,.gssb_a td{line-height:20px}.gssb_a{padding:0 6px}.gssb_c{z-index:3000001}.gssb_i td{background:#eee}.gssb_k{z-index:3000000}.gssb_l{margin:2px 0}.gsib_a{padding:0 4px}.gsok_a{padding:0}.gsok_a img{display:block}.gsfe_b{border:1px solid #1c62b9;box-shadow:inset 0 1px 2px rgba(0,0,0,0.3);-webkit-box-shadow:inset 0 1px 2px rgba(0,0,0,0.3);outline:none;}a.gscp_a{position:relative;background:#e2e2e2;border:1px solid #bbb;border-radius:3px}.gsfe_a a.gscp_a{border-width:1px;border-style:solid;border-color:#bbb}a.gscp_a.gscp_b{border-color:#777!important;background:#999;outline:none}.gscp_c{color:#666;font-size:11px;font-weight:bold;padding-right:20px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);-ms-filter:"progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#80ffffff,Positive=true)";zoom:1;filter:progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#80ffffff,Positive=true)}.gsfe_a a.gscp_a .gscp_c{color:#444}a.gscp_a.gscp_b .gscp_c,.gsfe_a a.gscp_a.gscp_b .gscp_c{color:#fff;text-shadow:0 1px 0 rgba(100, 100, 100, 0.5);-ms-filter:"progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#80646464,Positive=true)";zoom:1;filter:progid:DXImageTransform.Microsoft.dropshadow(OffX=0,OffY=1,Color=#80646464,Positive=true)}.gscp_d{position:absolute;padding:0;background:url(//s.ytimg.com/yt/img/icons/close-vflrEJzIW.png);background-repeat:no-repeat;background-position-y:0;right:3px;top:6px;font-size:0;width:13px;height:13px}.gscp_d:hover{background-position-y:-13px}a.gscp_a.gscp_b .gscp_d{background-position-y:-26px}.gsfe_a a.gscp_a.gscp_b .gscp_d:hover{background-position-y:-39px}.gscp_f{background:#000}</style></head>
	<body id="" class="date-20120930 en_US ltr   ytg-old-clearfix guide-feed-v2 " dir="ltr">
		<form name="logoutForm" method="POST" action="https://www.eracast.cc/logout">
			<input type="hidden" name="action_logout" value="1">
		</form>
		<!-- begin page -->
		<div id="page" class="browse-base">
			<!-- begin pagetop -->
			<div id="masthead-container">
                <!-- begin masthead -->
<style>
	.content-region {
		position:absolute;
		top:10px;
		left:97px;
		color:#999;
		font-size:11px;
		text-decoration:none;
		font-weight:400 
	}

	.content-region-footer {
		position:absolute;
		top:25px;
		left:95px;
		color:#999;
		font-size:11px;
		text-decoration:none;
		font-weight:400 
	}
	</style>
<script src="./accountnew_files/alerterror.js.загрузка"></script>
<div id="masthead-container"><?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/header.php") ?></div>
	<div class="alerts-2012" id="alerts-js">
	
	</div>
<div id="masthead-expanded" class="hid" style="height: 165px; display: none;">
	<div id="masthead-expanded-container" style="height: 142px;" class="with-sandbar">
		<div class="yt-uix-slider yt-rounded" id="watch-channel-discoverbox" data-slider-slide-selected="3" data-slider-slides="2" style="
		width: 580px;
		position: absolute;
		top: -9px;
		left: 1px;
		height: 146px;
		">
	<button class="yt-uix-button yt-uix-button-default yt-uix-slider-prev" rel="prev"><img class="yt-uix-slider-prev-arrow" src="./accountnew_files/pixel-vfl3z5WfW(2).gif" alt="previous"></button>
	<button class="yt-uix-button yt-uix-button-default yt-uix-slider-next" rel="next"><img class="yt-uix-slider-next-arrow" src="./accountnew_files/pixel-vfl3z5WfW(2).gif" alt="next"></button>
	<div class="yt-uix-slider-body" style="width: 525px;">
		<div class="yt-uix-slider-slides">
						<ul class="yt-uix-slider-slide ">
				<li class="yt-uix-slider-slide-item ">
					<div class="video-list-item  yt-tile-default ">
						<a href="https://www.eracast.cc/watch?v=Uqy5ff88VER" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=channel&amp;ei=COeB-Y25jrUCFdWNIQodzR51Jg%3D%3D"><span class="ux-thumb-wrap contains-addto "><span class="video-thumb ux-thumb yt-thumb-default-120 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img alt="why angry birds is best game of all time!" src="./accountnew_files/Uqy5ff88VER.jpg" data-thumb="/dynamic/thumbs/Uqy5ff88VER.jpg" onerror=";this.src=&#39;/dynamic/thumbs/default.jpg&#39;;" width="120" data-group-key="thumb-group-0"><span class="vertical-align"></span></span></span></span><span class="video-time">0:28</span>
						<button type="button" onclick=";return false;" title="Watch Later" class="addto-button video-actions spf-nolink addto-watch-later-button yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" data-video-ids="8C-1MRFr4s0" role="button"><span class="yt-uix-button-content">  <img src="./accountnew_files/pixel-vfl3z5WfW(2).gif" alt="Watch Later">
						</span></button>
						</span><span dir="ltr" class="title" title="why angry birds is best game of all time!">why angry birds is best game of all time!</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">StealthTheAB</span></span><span class="stat view-count">98 views</span></a>
					</div>
				</li>
                
				<li>
					<hr>
				</li>
			</ul>
						<ul class="yt-uix-slider-slide ">
				<li class="yt-uix-slider-slide-item ">
					<div class="video-list-item  yt-tile-default ">
						<a href="https://www.eracast.cc/watch?v=S8sCrXJDUWy" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=channel&amp;ei=COeB-Y25jrUCFdWNIQodzR51Jg%3D%3D"><span class="ux-thumb-wrap contains-addto "><span class="video-thumb ux-thumb yt-thumb-default-120 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img alt="Open Your Heart by Crush 40 (Main Theme of Sonic Adventure)" src="./accountnew_files/S8sCrXJDUWy.jpg" data-thumb="/dynamic/thumbs/S8sCrXJDUWy.jpg" onerror=";this.src=&#39;/dynamic/thumbs/default.jpg&#39;;" width="120" data-group-key="thumb-group-0"><span class="vertical-align"></span></span></span></span><span class="video-time">5:15</span>
						<button type="button" onclick=";return false;" title="Watch Later" class="addto-button video-actions spf-nolink addto-watch-later-button yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" data-video-ids="8C-1MRFr4s0" role="button"><span class="yt-uix-button-content">  <img src="./accountnew_files/pixel-vfl3z5WfW(2).gif" alt="Watch Later">
						</span></button>
						</span><span dir="ltr" class="title" title="Open Your Heart by Crush 40 (Main Theme of Sonic Adventure)">Open Your Heart by Crush 40 (Main Theme of Sonic Adventure)</span><span class="stat attribution">by <span class="yt-user-name " dir="ltr">StealthTheAB</span></span><span class="stat view-count">129 views</span></a>
					</div>
				</li>
                
				<li>
					<hr>
				</li>
			</ul>
					</div>
	</div>
</div>


		<div id="masthead-expanded-menus-container">
			<span id="masthead-expanded-menu-shade"></span>
						<div id="masthead-expanded-menu" class="">
				<span class="masthead-expanded-menu-header">EraCast</span>
				<ul id="masthead-expanded-menu-list">
					<li class="masthead-expanded-menu-item">
						<a href="https://www.eracast.cc/user/StealthTheAB" class="yt-uix-sessionlink" data-sessionlink="ei=nn0KUubpEcL8kwLQ5oGQDA&amp;feature=mhee">My channel</a>
					</li>
					<li class="masthead-expanded-menu-item">
						<a href="https://www.eracast.cc/my_videos" class="yt-uix-sessionlink" data-sessionlink="ei=nn0KUubpEcL8kwLQ5oGQDA&amp;feature=mhee">
						Video Manager
						</a>
					</li>
					<li class="masthead-expanded-menu-item">
						<a href="https://www.eracast.cc/subscriptions" class="yt-uix-sessionlink" data-sessionlink="ei=nn0KUubpEcL8kwLQ5oGQDA&amp;feature=mhee">Subscriptions</a>
					</li>
					<li class="masthead-expanded-menu-item">
						<a href="https://www.eracast.cc/account/" class="yt-uix-sessionlink" data-sessionlink="ei=nn0KUubpEcL8kwLQ5oGQDA&amp;feature=mhee">EraCast Settings</a>
					</li>
									</ul>
			</div>
						<div id="masthead-expanded-google-menu">
    			<span class="masthead-expanded-menu-header">
					Aesthetiful+ account
    			</span>
    			<div id="masthead-expanded-menu-google-container">
					<img id="masthead-expanded-menu-gaia-photo" alt="" data-src="/dynamic/pfp/afa29043b7ba8d9eacb4f9d7ca00fed5.jpg" src="./accountnew_files/afa29043b7ba8d9eacb4f9d7ca00fed5.jpg">
  						<div id="masthead-expanded-menu-account-info" class="email-only">
							      						<p>StealthTheAB</p>
    						<p id="masthead-expanded-menu-email">stellabirds735@gmail.com</p>
						</div>
						<div id="masthead-expanded-menu-google-column1">
							<ul>
								<li class="masthead-expanded-menu-item"><a href="https://aesthetiful.com/user/396">Profile</a></li>
								<li class="masthead-expanded-menu-item"><a href="https://aesthetiful.com/">Aesthetiful+</a></li>
								<li class="masthead-expanded-menu-item"><a href="https://www.eracast.cc/account/#">Privacy</a></li>
							</ul>
						</div>
						<div id="masthead-expanded-menu-google-column2">
							<div id="masthead-expanded-menu-account-container">
							</div>
							<ul>
								<li class="masthead-expanded-menu-item">
									<a href="https://aesthetiful.com/app/settings">
										Settings
									</a>
								</li>
								<li class="masthead-expanded-menu-item">
									<a class="end" href="https://www.eracast.cc/account/#" onclick="document.logoutForm.submit(); return false;">
										Sign out
									</a>
								</li>
								<li class="masthead-expanded-menu-item">
									<a href="https://www.eracast.cc/account/#" onclick="yt.www.masthead.accountswitch.toggle(); return false;">
										Switch account
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
					</div>
		<div class="clear"></div>
	</div>
<div id="alerts">

</div>
<!-- Google tag (gtag.js) -->
<script async="" src="./accountnew_files/js"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QNQ43WDK9Q');
</script>
<!-- end masthead -->
            </div>
            <!-- end pagetop -->
            <!-- begin pagemiddle -->
            <div id="content-container">
                <div id="masthead-subnav" class="yt-nav yt-nav-dark ">
                    <ul>
                        <a href="/my_videos">
                            <li class="">
                                <span class="yt-nav-item">
                                    Video Manager
                                </span>
                            </li>
                        </a>
                        <li class="">
                            <span class="yt-nav-item">
                                Video Editor
                            </span>
                        </li>
                        <a href="/subscriptions">
                        <li class="">
                            <span class="yt-nav-item">
                                Subscriptions
                            </span>
                        </li>
                        </a>
                        <a href="/analytics/">
                        <li class="">
                            <span class="yt-nav-item">
                                Analytics
                            </span>
                        </li>
                        </a>
                        <li class=" selected" style="float:right;">
                            <span class="yt-nav-item">
                                Settings
                            </span>
                        </li>
                        <a href="/inbox/">
                            <li class="" style="float:right;">
                                <span class="yt-nav-item">
                                    Inbox
                                </span>
                            </li>
                        </a>
                    </ul>
                </div>
                <div class="ytg-base">
                    <div class="ytg-wide">
                        
<div id="yts-nav" class="ytg-1col">
    <style>
        .yt-uix-button-arrow {
            border: none;
            background: no-repeat url(//s.ytimg.com/yt/imgbin/www-guide-vfl1t2Sk-.png) 0 -300px;
            width: 5px;
            height: 6px
        }

        .item-highlight {background:#fff;text-decoration:none;-moz-border-radius:0;-webkit-border-radius:0;border-radius:0;background-image:-moz-linear-gradient(right,#efefef 0,rgba(255,255,255,.9) 50px);background-image:-ms-linear-gradient(right,#efefef 0,rgba(255,255,255,.9) 50px);background-image:-o-linear-gradient(right,#efefef 0,rgba(255,255,255,.9) 50px);background-image:-webkit-gradient(linear,right top,left top,color-stop(0,#efefef),color-stop(50px,rgba(255,255,255,.9)));background-image:-webkit-linear-gradient(right,#efefef 0,rgba(255,255,255,.9) 50px);background-image:linear-gradient(to left,#efefef 0,rgba(255,255,255,.9) 50px)}
    </style>
    <ol style="margin-top:0px;border-left: 1px solid lightgray;">
        <li class="top-level">
            <a>
                Account settings
            </a>
        </li>
        <ol class="indented">
                            <li class="sub-level">
                    <a class="item-highlight" href="/account/">
                        Overview                    </a>
                </li>
                            <li class="sub-level">
                    <a class="1" href="/account/sharing">
                        Sharing                    </a>
                </li>
                            <li class="sub-level">
                    <a class="1" href="/account/privacy">
                        Privacy                    </a>
                </li>
                            <li class="sub-level">
                    <a class="1" href="/account/email">
                        Email                    </a>
                </li>
                            <li class="sub-level">
                    <a class="1" href="/account/playback">
                        Playback                    </a>
                </li>
                    </ol>
        <li class="top-level">
            <a>
                Channel settings
            </a>
        </li>
        <ol class="indented">
                            <li class="sub-level">
                    <a class="1" href="/account/monetization">
                        Monetization                    </a>
                </li>
                    </ol>
    </ol>
</div>
			<form method="post" action="/account/">
                        <div style="width:73.5%;float:right;border-left: 1px solid lightgray;padding:25px;">
                            <style>
                                .green-dot {
                                    width: 15px;
                                    height: 15px;
                                    border-radius: 50%;
                                    background: linear-gradient(#7ca46c, #496140);
                                    box-shadow: 0 0 0 3px #d1dad3;
                                    margin-right: 10px;
                                }

                                .yellow-dot {
                                    width: 15px;
                                    height: 15px;
                                    border-radius: 50%;
                                    background: linear-gradient(#FFFF66, #999900);
                                    box-shadow: 0 0 0 3px #d1dad3;
                                    margin-right: 10px;
                                }

                                .red-dot {
                                    width: 15px;
                                    height: 15px;
                                    border-radius: 50%;
                                    background: linear-gradient(#FF8080, #AA0000);
                                    box-shadow: 0 0 0 3px #d1dad3;
                                    margin-right: 10px;
                                }
                            </style>
                            <div style="display:flex;flex-direction:row;margin-bottom:10px;">
                                <h1>Overview</h1>
                                <input class="yt-uix-button yt-uix-button-primary " type="submit" value="Save" style="margin-left:auto;padding:0 20px">
                            </div>
                            <div class="yt-horizontal-rule "></div>
                            <div style="margin-top:10px;">
                                <h2><b>Account Information</b></h2>
                                <div style="margin-top:10px;">
                                    <div style="margin-top:20px;display:flex;flex-direction:row;white-space:nowrap;">
                                        <div style="width:30%;">
                                            <p>Name</p>
                                        </div>
                                        <div style="display:flex;flex-direction:row;">
                                            <div style="margin-right:10px;">
                                                <img src="/dynamic/pfp/<?php echo $__user_h->fetch_pfp($_SESSION['siteusername']); ?>" height="100" width="100" alt="Image">
                                            </div>
                                                                                        <div style="display:flex;flex-direction:column">
                                                <a href="/user/<?php echo htmlspecialchars($_SESSION['siteusername']); ?>" style="margin-top:10px;"><b><?php echo htmlspecialchars($_SESSION['siteusername']); ?></b></a>
                                                <div style="display:flex;flex-direction:row;margin-top:5px;">
                                                    <b><?php echo htmlspecialchars($_user['email']); ?></b>
                                                    <a href="/channel_editor" style="margin-left:10px;">Edit profile</a>
                                                </div>
                                                <a style="margin-top:5px;">Advanced</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-top:20px;display:flex;flex-direction:row;white-space:nowrap;">
                                        <div style="width:30%;">
                                            <p>Password</p>
                                        </div>
                                        <div style="display:flex;flex-direction:column">
                                            <a href="/account/#">Change password</a>
                                            <p style="margin-top:5px;color:grey;">You will be redirected to your Foogle account page</p>
                                        </div>
                                    </div>
                                    <div style="margin-top:20px;display:flex;flex-direction:row;white-space:nowrap;">
                                        <div style="width:30%;">
                                            <p>Mobile uploads</p>
                                        </div>
                                        <div style="display:flex;flex-direction:column">
                                            <p>n/a</p>
                                            <p style="margin-top:5px;color:grey;">Upload videos from your phone by emailing this address. Want a different address? <a href="/account/#">Click Here</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top:40px;">
                                <h2><b>Account Status</b></h2>
                                                                <div style="margin-top:20px;margin-bottom:10px;display:flex;flex-direction:row;white-space:nowrap;">
                                    <div style="width:30%;">
                                        <p>Community guidelines</p>
                                    </div>
                                    <div style="display:flex;flex-direction:row">
                                                                                <div class="green-dot"></div>
                                        <p>Good standing</p>
                                                                            </div>
                                </div>
                                <div class="yt-horizontal-rule "></div>
                                <div style="margin-top:10px;margin-bottom:10px;display:flex;flex-direction:row;white-space:nowrap;">
                                    <div style="width:30%;">
                                        <p>Copyright strikes</p>
                                    </div>
                                    <div style="display:flex;flex-direction:row">
                                        <div class="green-dot"></div>
                                        <p>Good standing</p>
                                    </div>
                                </div>
                                                                <div class="yt-horizontal-rule "></div>
                                <div style="margin-top:10px;margin-bottom:10px;display:flex;flex-direction:row;white-space:nowrap;">
                                    <div style="width:30%;">
                                        <p>Content ID claims</p>
                                    </div>
                                    <div style="display:flex;flex-direction:row">
                                                                                <div class="green-dot"></div>
                                        <p>Good standing</p>
                                                                            </div>
                                </div>
                            </div>
                            <div style="margin-top:40px;">
                                <h2><b>Links</b></h2><br>
                                <a href="/account/#">Learn how to promote your videos</a>
                            </div>
			</form>
                    </div>
                </div>
            </div>
            <!-- end pagemiddle -->
            <!-- begin pagebottom -->
            <div id="footer-container"><?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/footer.php") ?></div>
            <div id="playlist-bar" class="hid passive editable" data-video-url="/watch?v=&amp;feature=BFql&amp;playnext=1&amp;list=QL" data-list-id="" data-list-type="QL">
                <div id="playlist-bar-bar-container">
                    <div id="playlist-bar-bar">
                        <div class="yt-alert yt-alert-naked yt-alert-success hid " id="playlist-bar-notifications">
                            <div class="yt-alert-icon">
                                <img src="./accountnew_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
                            </div>
                            <div class="yt-alert-content" role="alert"></div>
                        </div>
                        <span id="playlist-bar-info"><span class="playlist-bar-active playlist-bar-group"><button onclick=";return false;" title="Previous video" type="button" id="playlist-bar-prev-button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-tooltip yt-uix-button-empty" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-prev" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt="Previous video"><span class="yt-valign-trick"></span></span></button><span class="playlist-bar-count"><span class="playing-index">0</span> / <span class="item-count">0</span></span><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" onclick=";return false;" id="playlist-bar-next-button" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-next" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt=""><span class="yt-valign-trick"></span></span></button></span><span class="playlist-bar-active playlist-bar-group"><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" onclick=";return false;" id="playlist-bar-autoplay-button" data-button-toggle="true" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-autoplay" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt=""><span class="yt-valign-trick"></span></span></button><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-button-empty" onclick=";return false;" id="playlist-bar-shuffle-button" data-button-toggle="true" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-shuffle" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt=""><span class="yt-valign-trick"></span></span></button></span><span class="playlist-bar-passive playlist-bar-group"><button onclick=";return false;" title="Play videos" type="button" id="playlist-bar-play-button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-default yt-uix-tooltip yt-uix-button-empty" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-play" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt="Play videos"><span class="yt-valign-trick"></span></span></button><span class="playlist-bar-count"><span class="item-count">0</span></span></span><span id="playlist-bar-title" class="yt-uix-button-group"><span class="playlist-title">Unsaved Playlist</span></span></span>
                        <a id="playlist-bar-lists-back" href="https://www.eracast.cc/account/#">
                        Return to active list
                        </a>
                        <span id="playlist-bar-controls"><span class="playlist-bar-group"><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked  yt-uix-button yt-uix-button-text yt-uix-button-empty" onclick=";return false;" id="playlist-bar-toggle-button" role="button"><span class="yt-uix-button-icon-wrapper"><img class="yt-uix-button-icon yt-uix-button-icon-playlist-bar-toggle" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt=""><span class="yt-valign-trick"></span></span></button></span><span class="playlist-bar-group"><button type="button" class="yt-uix-tooltip yt-uix-tooltip-masked yt-uix-button-reverse flip yt-uix-button yt-uix-button-text" onclick=";return false;" data-button-menu-id="playlist-bar-options-menu" data-button-has-sibling-menu="true" role="button"><span class="yt-uix-button-content">Options </span><img class="yt-uix-button-arrow" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt=""></button></span></span>      
                    </div>
                </div>
                <div id="playlist-bar-tray-container">
                    <div id="playlist-bar-tray" class="yt-uix-slider yt-uix-slider-fluid">
                        <button class="yt-uix-button playlist-bar-tray-button yt-uix-button-default yt-uix-slider-prev" onclick="return false;"><img class="yt-uix-slider-prev-arrow" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt="Previous video"></button><button class="yt-uix-button playlist-bar-tray-button yt-uix-button-default yt-uix-slider-next" onclick="return false;"><img class="yt-uix-slider-next-arrow" src="./accountnew_files/pixel-vfl3z5WfW.gif" alt="Next video"></button>
                        <div class="yt-uix-slider-body">
                            <div id="playlist-bar-tray-content" class="yt-uix-slider-slide">
                                <ol class="video-list"></ol>
                                <ol id="playlist-bar-help">
                                    <li class="empty playlist-bar-help-message">Your queue is empty. Add videos to your queue using this button: <img src="./accountnew_files/pixel-vfl3z5WfW.gif" class="addto-button-help"><br> or <a href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26nomobiletemp%3D1%26hl%3Den_US%26next%3D%252Fvideos%253Ffeature%253Dmh&amp;uilel=3&amp;hl=en_US&amp;service=youtube">sign in</a> to load a different list.</li>
                                </ol>
                            </div>
                            <div class="yt-uix-slider-shade-left"></div>
                            <div class="yt-uix-slider-shade-right"></div>
                        </div>
                    </div>
                    <div id="playlist-bar-save"></div>
                    <div id="playlist-bar-lists" class="dark-lolz"></div>
                    <div id="playlist-bar-loading"><img src="./accountnew_files/pixel-vfl3z5WfW.gif" alt="Loading..."><span id="playlist-bar-loading-message">Loading...</span><span id="playlist-bar-saving-message" class="hid">Saving...</span></div>
                    <div id="playlist-bar-template" style="display: none;" data-video-thumb-url="//i4.ytimg.com/vi/__video_encrypted_id__/default.jpg">
                        <!--<li class="playlist-bar-item yt-uix-slider-slide-unit __classes__" data-video-id="__video_encrypted_id__"><a href="__video_url__" title="__video_title__" class="yt-uix-sessionlink" data-sessionlink="ei=CPjwu5ji3bICFS4RIQod9j-M-A%3D%3D&amp;feature=BFa"><span class="video-thumb ux-thumb yt-thumb-default-106 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="http://s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif" alt="__video_title__" data-thumb-manual="true" data-thumb="__video_thumb_url__" width="106" ><span class="vertical-align"></span></span></span></span><span class="screen"></span><span class="count"><strong>__list_position__</strong></span><span class="play"><img src="//s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif"></span><span class="yt-uix-button yt-uix-button-default delete"><img class="yt-uix-button-icon-playlist-bar-delete" src="//s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif" alt="Delete"></span><span class="now-playing">Now playing</span><span dir="ltr" class="title"><span>__video_title__  <span class="uploader">by __video_display_name__</span>
                            </span></span><span class="dragger"></span></a></li>-->
                    </div>
                    <div id="playlist-bar-next-up-template" style="display: none;">
                        <!--<div class="playlist-bar-next-thumb"><span class="video-thumb ux-thumb yt-thumb-default-74 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="//i4.ytimg.com/vi/__video_encrypted_id__/default.jpg" alt="Thumbnail" onerror="this.onerror=null;this.src='/dynamic/thumbs/default.jpg';" width="74" ><span class="vertical-align"></span></span></span></span></div>-->
                    </div>
                </div>
                <div id="playlist-bar-options-menu" class="hid">
                    <div id="playlist-bar-extras-menu">
                        <ul>
                            <li><span class="yt-uix-button-menu-item" data-action="clear">
                                Clear all videos from this list
                                </span>
                            </li>
                        </ul>
                    </div>
                    <ul>
                        <li><span class="yt-uix-button-menu-item" onclick="window.location.href=&#39;//support.google.com/youtube/bin/answer.py?answer=146749&amp;hl=en-US&#39;">Learn more</span></li>
                    </ul>
                </div>
            </div>
            <div id="shared-addto-watch-later-login" class="hid">
                <a href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26nomobiletemp%3D1%26hl%3Den_US%26next%3D%252Fvideos%253Ffeature%253Dmh&amp;uilel=3&amp;hl=en_US&amp;service=youtube" class="sign-in-link">Sign in</a> to add this to a playlist
            </div>
            <div id="shared-addto-menu" style="display: none;" class="hid sign-in">
                <div class="addto-menu">
                    <div id="addto-list-panel" class="menu-panel active-panel">
                        <span class="yt-uix-button-menu-item yt-uix-tooltip sign-in" data-possible-tooltip="" data-tooltip-show-delay="750"><a href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26nomobiletemp%3D1%26hl%3Den_US%26next%3D%252Fvideos%253Ffeature%253Dmh&amp;uilel=3&amp;hl=en_US&amp;service=youtube" class="sign-in-link">Sign in</a> to add this to a playlist
                        </span>
                    </div>
                    <div id="addto-list-saved-panel" class="menu-panel">
                        <div class="panel-content">
                            <div class="yt-alert yt-alert-naked yt-alert-success  ">
                                <div class="yt-alert-icon">
                                    <img src="./accountnew_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
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
                            <img src="./accountnew_files/pixel-vfl3z5WfW.gif">
                            <span class="error-details"></span>
                            <a class="show-menu-link">Back to list</a>
                        </div>
                    </div>
                    <div id="addto-note-input-panel" class="menu-panel">
                        <div class="panel-content">
                            <div class="yt-alert yt-alert-naked yt-alert-success  ">
                                <div class="yt-alert-icon">
                                    <img src="./accountnew_files/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
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
                            <img src="./accountnew_files/pixel-vfl3z5WfW.gif">
                            <span>Saving note...</span>
                        </div>
                    </div>
                    <div id="addto-note-saved-panel" class="menu-panel">
                        <div class="panel-content">
                            <img src="./accountnew_files/pixel-vfl3z5WfW.gif">
                            <span class="message">Note added to:</span>
                        </div>
                    </div>
                    <div id="addto-note-error-panel" class="menu-panel">
                        <div class="panel-content">
                            <img src="./accountnew_files/pixel-vfl3z5WfW.gif">
                            <span class="message">Error adding note:</span>
                            <ul class="error-details"></ul>
                            <a class="add-note-link">Click to add a new note</a>
                        </div>
                    </div>
                    <div class="close-note hid">
                        <img src="./accountnew_files/pixel-vfl3z5WfW.gif" class="close-button">
                    </div>
                </div>
            </div>
            <!-- end pagebottom -->
        </div>
        <!-- end page -->
<script id="www-core-js" src="./accountnew_files/www-core-vfl1pq97W.js.загрузка" data-loaded="true"></script>
        <script>yt.www.thumbnaildelayload.init(0);</script>
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
                'UNDO_LINK': "Undo"
            });


            yt.setConfig({
                'DRAGDROP_BINARY_URL': "\/\/s.ytimg.com\/yt\/jsbin\/www-dragdrop-vflWKaUyg.js",
                'PLAYLIST_BAR_PLAYING_INDEX': -1
            });

            yt.setAjaxToken('addto_ajax_logged_out', "rmWO31ZGdmAjKQm23MH57ZskA6Z8MTM0OTExMDQ0NkAxMzQ5MDI0MDQ2");

            yt.pubsub.subscribe('init', yt.www.lists.init);









            yt.setConfig({ 'SBOX_JS_URL': "\/\/s.ytimg.com\/yt\/jsbin\/www-searchbox-vflsHyn9f.js", 'SBOX_SETTINGS': { "CLOSE_ICON_URL": "\/\/s.ytimg.com\/yt\/img\/icons\/close-vflrEJzIW.png", "SHOW_CHIP": false, "PSUGGEST_TOKEN": null, "REQUEST_DOMAIN": "us", "EXPERIMENT_ID": -1, "SESSION_INDEX": null, "HAS_ON_SCREEN_KEYBOARD": false, "CHIP_PARAMETERS": {}, "REQUEST_LANGUAGE": "en" }, 'SBOX_LABELS': { "SUGGESTION_DISMISS_LABEL": "Dismiss", "SUGGESTION_DISMISSED_LABEL": "Suggestion dismissed" } });





        </script>
        <script>
            yt.setMsg({
                'ADDTO_WATCH_LATER_ADDED': "Added",
                'ADDTO_WATCH_LATER_ERROR': "Error"
            });
        </script>
    

<iframe class="gstl_0 gssb_k" style="display: none; top: 44px; left: 0px; height: 0px;" src="./accountnew_files/saved_resource.html"></iframe><table cellspacing="0" cellpadding="0" class="gstl_0 gssb_c" style="width: 349px; display: none; top: 44px; position: absolute; left: 366px;"><tbody><tr><td class="gssb_f"></td><td class="gssb_e" style="width: 100%;"></td></tr></tbody></table></body></html>