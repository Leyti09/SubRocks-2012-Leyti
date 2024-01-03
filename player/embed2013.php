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
<?php $_video = $__video_h->fetch_video_rid($_GET['v']); ?>
<?php $_video['comments'] = $__video_h->get_comments_from_video($_video['rid']); ?>
<?php
  if($_video['visibility'] == "v" && @$_SESSION['siteusername'] != $_video['author'])
    header("Location: /");

  $_SESSION['current_video'] = $_video['rid'];

      if($_video['likes'] == 0 && $_video['dislikes'] == 0) {
        $_video['likeswidth'] = 0;
        $_video['dislikeswidth'] = 0;
    } else {
        $_video['likeswidth'] = $_video['likes'] / ($_video['likes'] + $_video['dislikes']) * 100;
        $_video['dislikeswidth'] = 100 - $_video['likeswidth'];
    }

  $__server->page_embeds->page_title = htmlspecialchars($_video['title']);
  $__server->page_embeds->page_description = htmlspecialchars($_video['description']);
  $__server->page_embeds->page_image = "/dynamic/thumbs/" . $_video['thumbnail'];
  $__server->page_embeds->page_url = "https://betatube.net/watch?v=" . htmlspecialchars($_video['rid']);
?>
<script id="js-2900126986" class="www-embed-player" src="//s.ytimg.com/yts/jsbin/www-embed-player-vflUmkt61.js" data-loaded="true"></script>
<link rel="stylesheet" href="/2014_asset/www-player.css" name="www-player">
<body style="margin: 0;">
<div id="player" style="width:640px; height:392px;">

<div id="player-mole-container">
<div id="player-api" class="player-width player-height off-screen-target player-api"></div>

		<script>
		  yt.setConfig({
			'EVENT_ID': "RUXDUpmwIoiRiQLA54GABQ",
			'VIDEO_ID': "<?php echo htmlspecialchars($video['rid']); ?>",
			'POST_MESSAGE_ORIGIN': "*",
			'EURL': "http:\/\/www.itv.com\/news\/topic\/gangnam-style\/"
		  });
		  yt.setConfig({
			'PLAYER_CONFIG': {
			  "sts": 16057,
			  "params": {
				"allowfullscreen": "true",
				"allowscriptaccess": "always",
				"bgcolor": "#000000"
			  },
			  "url_v8": "http:\/\/s.ytimg.com\/yts\/swfbin\/player-vflqv4MLv\/cps.swf",
			  "min_version": "8.0.0",
			  "html5": false,
			  "url": "http:\/\/s.ytimg.com\/yts\/swfbin\/player-vflqv4MLv\/watch_as3.swf",
			  "args": {
				"enablejsapi": "0",
				"iurlmaxres": "\/dynamic\/thumbs\/<?php echo htmlspecialchars($video['thumbnail']); ?>",
				"threed_module": "1",
				"ldpj": "-3",
				"fexp": "931319,927606,901479,930102,916624,909717,924616,932295,936912,936910,923305,936913,907231,907240,921090",
				"hl": "en_US",
				"autohide": true,
				"eurl": "http:\/\/www.itv.com\/news\/topic\/gangnam-style\/",
				"el": "embedded",
				"allow_embed": 1,
				"avg_rating": 4.57515427974,
				"idpj": "-7",
				"video_id": "<?php echo htmlspecialchars($video['rid']); ?>",
				"view_count": 5462747,
				"allow_ratings": 1,
				"cc3_module": "1",
				"sw": "1.0",
				"iurlsd": "\/dynamic\/thumbs\/<?php echo htmlspecialchars($video['thumbnail']); ?>",
				"cc_module": "http:\/\/s.ytimg.com\/yts\/swfbin\/player-vflqv4MLv\/subtitle_module.swf",
				"iurl": "\/dynamic\/thumbs\/<?php echo htmlspecialchars($video['thumbnail']); ?>",
				"cc_font": "Arial Unicode MS, arial, verdana, _sans",
				"length_seconds": <?php echo $video['duration']; ?>,
				"title": "<?php echo htmlspecialchars($video['title']); ?>",
				"cr": "US",
				"advideo": "1",
				"streaminglib_module": "1",
				"rel": "1",
				"is_html5_mobile_device": false
			  },
			  "attrs": {
				"height": "100%",
				"id": "video-player",
				"width": "100%",
			  },
			  "url_v9as2": "http:\/\/s.ytimg.com\/yts\/swfbin\/player-vflqv4MLv\/cps.swf",
			  "assets": {
				"js": "\/\/s.ytimg.com\/yts\/jsbin\/html5player-vflG49soT.js",
				"html": "\/2014html5_player_template",
				"css": "\/yts\/cssbin\/www-player-vflBjKccE.css"
			  }
			},
			'EMBED_HTML_TEMPLATE': "\u003ciframe width=\"__width__\" height=\"__height__\" src=\"__url__\" frameborder=\"0\" allowfullscreen\u003e\u003c\/iframe\u003e",
			'EMBED_HTML_URL': "\/\/betatube.net\/embed\/__videoid__"
		  });
		  yt.setMsg('FLASH_UPGRADE', "\u003cdiv class=\"yt-alert yt-alert-default yt-alert-error  yt-alert-player\"\u003e  \u003cdiv class=\"yt-alert-icon\"\u003e\n    \u003cimg s\u0072c=\"http:\/\/s.ytimg.com\/yts\/img\/pixel-vfl3z5WfW.gif\" class=\"icon master-sprite\" alt=\"Alert icon\"\u003e\n  \u003c\/div\u003e\n\u003cdiv class=\"yt-alert-buttons\"\u003e\u003c\/div\u003e\u003cdiv class=\"yt-alert-content\" role=\"alert\"\u003e    \u003cspan class=\"yt-alert-vertical-trick\"\u003e\u003c\/span\u003e\n    \u003cdiv class=\"yt-alert-message\"\u003e\n            You need to upgrade your Adobe Flash Player to watch this video. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eDownload it from Adobe.\u003c\/a\u003e\n    \u003c\/div\u003e\n\u003c\/div\u003e\u003c\/div\u003e");
		  yt.setMsg('PLAYER_FALLBACK', "The Adobe Flash Player or an HTML5 supported browser is required for video playback. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eGet the latest Flash Player\u003c\/a\u003e \u003cbr\u003e \u003ca href=\"\/html5\"\u003eLearn more about upgrading to an HTML5 browser\u003c\/a\u003e");
		  yt.setMsg('QUICKTIME_FALLBACK', "The Adobe Flash Player or QuickTime is required for video playback. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eGet the latest Flash Player\u003c\/a\u003e \u003cbr\u003e \u003ca href=\"http:\/\/www.apple.com\/quicktime\/download\/\"\u003eGet the latest version of QuickTime\u003c\/a\u003e");
		  yt.setMsg('AUTOPLAY_MESSAGE', {
			"other": "Next video in #",
			"case1": "Next video in 1"
		  });

		  writeEmbed();
		</script>
		<script>
		  ytcsi.span('st', 8);
		  yt.setConfig({
			'TIMING_ACTION': "",
			'TIMING_INFO': {
			  "e": "931319,927606,901479,930102,916624",
			  "yt_lt": "cold",
			  "ei": "RUXDUpmwIoiRiQLA54GABQ",
			  "yt_li": 0,
			  "yt_spf": 0
			}
		  });
		</script></div>
</div>
</body>