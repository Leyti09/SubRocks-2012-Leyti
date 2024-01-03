<?php if(isset($_GET['about'])) { ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_about.php"); ?>
<?php } elseif(isset($_GET['channels'])) {
require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_channels.php"); ?>
<?php    } else{    ?>
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
		<title>Betatube - <?php if($_user['title'])	{	?>
						<?php echo htmlspecialchars($_user['title']); ?>
						<?php } else {	?>
						<?php echo htmlspecialchars($_user['username']); ?>
						<?php	}	?></title>
		<link id="css-2955892050" rel="stylesheet" href="/yts/cssbin/www-core-vflEJosKh.css">
		<link id="css-151587203" rel="stylesheet" href="/yts/cssbin/www-home-vfl_Eri60.css">
		<link id="css-151587203" rel="stylesheet" href="/yts/cssbin/www-channels4-vflLzPxqN.css">
		<script>
			if (window.yt.timing) {yt.timing.tick("ct");}    
		</script>
	</head>
	<body dir="ltr" class="ltr site-left-aligned exp-new-site-width exp-watch7-comment-ui hitchhiker-enabled guide-enabled guide-expanded flex-width-enabled page-loaded">


<div id="body-container">
	<form name="logoutForm" method="POST" action="/logout"><input type="hidden" name="action_logout" value="1"></form>

	<?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_header.php"); ?>

<div id="page-container">
	<div id="page" class="  channel  clearfix">
	<div id="guide"><?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_guide.php"); ?></div>
	


	<div id="content" class="">



<div class="branded-page-v2-container  branded-page-v2-flex-width" id="c4-overview-tab">
<div class="branded-page-v2-col-container clearfix">
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

</div>


	</div>
<div class="branded-page-v2-primary-col">
	  <div class="branded-page-v2-primary-col-header-container">


<div id="context-source-container" data-context-source="<?php echo htmlspecialchars($_user['title']); ?>" data-context-image="/dynamic/banners/<?php echo htmlspecialchars($_user['2012_bg']); ?>" style="display:none;"></div>


<style>
#c4-header-bg-container {
background-image: url(/dynamic/banners/<?php echo htmlspecialchars($_user['2012_bg']); ?>);
background-position: center center;
}


@media screen and (-webkit-min-device-pixel-ratio: 1.5),
	   screen and (min-resolution: 1.5dppx) {
#c4-header-bg-container {
	background-image: url(/dynamic/banners/<?php echo htmlspecialchars($_user['2012_bg']); ?>);
  }
}

</style>


<div class="branded-page-v2-header channel-header">
<div id="gh-banner">
  <div id="c4-header-bg-container" class=" has-custom-banner">
  <div id="header-links">
  <!-- <div class="about-network-links">
	<a href="http://web.archive.org/web/20130630211852/http://google.com/+Smosh" title="http://google.com/+Smosh" class="about-network-link" target="_blank">
	  <img class="network-icon-google" src="//web.archive.org/web/20130630211852im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="http://google.com/+Smosh" title="">
	</a>
	<a href="http://web.archive.org/web/20130630211852/http://facebook.com/smosh" title="http://facebook.com/smosh" class="about-network-link" target="_blank">
	  <img class="network-icon-facebook" src="//web.archive.org/web/20130630211852im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="http://facebook.com/smosh" title="">
	</a>
	<a href="http://web.archive.org/web/20130630211852/http://twitter.com/smosh" title="http://twitter.com/smosh" class="about-network-link" target="_blank">
	  <img class="network-icon-twitter" src="//web.archive.org/web/20130630211852im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="http://twitter.com/smosh" title="">
	</a>
	<a href="http://web.archive.org/web/20130630211852/https://itunes.apple.com/us/artist/smosh/id268806012" title="https://itunes.apple.com/us/artist/smosh/id268806012" class="about-network-link" target="_blank">
	  <img class="network-icon-itunes" src="//web.archive.org/web/20130630211852im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="https://itunes.apple.com/us/artist/smosh/id268806012" title="">
	</a>
</div> -->

<?php if(!empty($_user['website'])) { ?>
<ul class="about-custom-links">
	<li class="custom-links-item">
<a href="
<?php echo htmlspecialchars($_user['website']) ?>" rel="me nofollow" target="_blank" title="<?php echo htmlspecialchars($_user['website']) ?>" class="yt-uix-redirect-link about-custom-link about-custom-link-with-icon">
  <img src="https://www.google.com/s2/favicons?domain=<?php echo htmlspecialchars($_user['website']) ?>" class="about-custom-link-favicon" alt="">
<span class="about-custom-link-text">
Website
</span>
</a>
</li>

</ul>
<?php } ?>
</div>



	<a class="channel-header-profile-image-container spf-link" href="/user/<?php echo htmlspecialchars($_user['username']); ?>">
<img class="channel-header-profile-image" src="/dynamic/pfp/<?php echo htmlspecialchars($_user['pfp']); ?>" title="<?php echo htmlspecialchars($_user['title']); ?>" alt="<?php echo htmlspecialchars($_user['title']); ?>">
</a>


</div>

</div>
<div class="">
<div class="primary-header-contents" id="c4-primary-header-contents">
<div class="primary-header-actions clearfix">
		
<span class=" channel-header-subscription-button-container yt-uix-button-subscription-container with-preferences">
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
					  <span class="yt-subscription-button-subscriber-count-branded-horizontal"><?php echo htmlspecialchars($__user_h->fetch_subs_count($_user['username'])); ?></span> <span class="yt-subscription-button-disabled-mask" title=""></span>

					  <div class="yt-uix-overlay " data-overlay-style="primary" data-overlay-loaded="true" data-overlay-shape="tiny">

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
									<div class="yt-spinner-img"></div>
									<div class="yt-dialog-waiting-text">Loading...</div>
								  </div>

								</div>
								<div class="yt-dialog-content">
								  <div class="subscription-preferences-overlay-content-container">
									<div class="subscription-preferences-overlay-loading ">
									  <p class="yt-spinner">
										<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-spinner-img" alt="Loading icon">

										<span class="yt-spinner-message">
										  Loading...
										</span>
									  </p>

									</div>
									<div class="subscription-preferences-overlay-content">
									  i love sex
									</div>
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

</span>

</div>
  <h1 class="branded-page-header-title">
<a class="spf-link" href="/user/<?php echo htmlspecialchars($_user['username']); ?>">
<span class="qualified-channel-title ellipsized has-badge" title="<?php echo htmlspecialchars($_user['title']); ?>"><span class="qualified-channel-title-wrapper ">  <span class="qualified-channel-title-text">
<?php echo htmlspecialchars($_user['title']); ?>
</span>
</span>
<?php if($__user_h->if_partner($_user['username'])) { ?>
<span class="qualified-channel-title-badge">
<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="yt-uix-tooltip yt-channel-title-icon-verified" alt="" title="Verified" data-tooltip-text="Verified">  
</span></span>
<?php } ?>
</a>
</h1>
</div>
<div id="channel-subheader" class="clearfix branded-page-gutter-padding">

<ul id="channel-navigation-menu" class="clearfix">
<li>
		<button onclick=";return false;" class="epic-nav-item-empty  yt-uix-button yt-uix-button-epic-nav-item yt-uix-button-empty" type="button" data-button-menu-id="channel-navigation-menu-dropdown" role="button" aria-label="Select view:">    <span class="yt-uix-button-icon-wrapper">
<img class="yt-uix-button-icon yt-uix-button-icon-c4-home" src="//web.archive.org/web/20130630211852im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
<span class="yt-uix-button-valign"></span>
</span>
<img class="yt-uix-button-arrow" src="//web.archive.org/web/20130630211852im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title=""></button>
<div id="channel-navigation-menu-dropdown" class="hid epic-nav-item-dropdown">
<ul>
	<li class="epic-nav-item-selected">
<a class="spf-link yt-uix-button-menu-item" href="/user/<?php echo htmlspecialchars($_user['username']); ?>/featured">Browse</a>
</li>

  <li>
<a class="spf-link yt-uix-button-menu-item" href="/user/<?php echo htmlspecialchars($_user['username']); ?>/feed">Feed</a>
</li>

</ul>
</div>


</li>
<li>
  <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>/videos" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item selected" data-sessionlink="ei=vaDQUfP-O-ONyAH0oYCwDQ"><span class="yt-uix-button-content">Videos</span></a>
</li>
  <li>
	<a href="/user/<?php echo htmlspecialchars($_user['username']); ?>/discussion" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item" data-sessionlink="ei=vaDQUfP-O-ONyAH0oYCwDQ"><span class="yt-uix-button-content">Discussion</span></a>
  </li>
  <li>
	<a href="/user/<?php echo htmlspecialchars($_user['username']); ?>&about" class="yt-uix-button  spf-link  yt-uix-sessionlink yt-uix-button-epic-nav-item" data-sessionlink="ei=vaDQUfP-O-ONyAH0oYCwDQ"><span class="yt-uix-button-content">About</span></a>
  </li>
  <!-- <li>
  <div id="channel-search"><label class="show-search epic-nav-item secondary-nav" for="channels-search-field"><img class="epic-nav-item-heading-icon" src="//web.archive.org/web/20130701011003im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Search Channel"></label><form class="search-form epic-nav-item secondary-nav" action="/web/20130701011003/http://www.youtube.com/user/realannoyingorange/search" method="get"><span class=" yt-uix-form-input-container yt-uix-form-input-text-container ">    <input class="yt-uix-form-input-text search-field" name="query" id="channels-search-field" type="text" placeholder="Search Channel" maxlength="100" autocomplete="off">
</span><button class="search-button yt-uix-button yt-uix-button-c4-search yt-uix-button-empty" onclick=";return true;" type="submit" role="button">    <span class="yt-uix-button-icon-wrapper">
  <img class="yt-uix-button-icon yt-uix-button-icon-search" src="//web.archive.org/web/20130701011003im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
  <span class="yt-uix-button-valign"></span>
</span>
</button></form></div> 
  </li> -->
</ul>
</div>

</div>

</div>


</div>
<div class="branded-page-v2-body" id="gh-overviewtab">

    
  <div id="video-page-content" class="branded-page-gutter-padding">
        <ul id="channels-browse-content-grid" class="channels-browse-content-grid context-data-container ">
															<?php
														$stmt = $__db->prepare("SELECT * FROM videos WHERE author = :username ORDER BY id DESC");
														$stmt->bindParam(":username", $_user['username']);
														$stmt->execute();
														if($stmt->rowCount() == 0) { echo '<span style="font-size:11px;color:grey;">This user has no videos uploaded.</span>'; }

														while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$video['age'] = $__time_h->time_elapsed_string($video['publish']);		
															$video['duration'] = $__time_h->timestamp($video['duration']);
															$video['views'] = $__video_h->fetch_video_views($video['rid']);
															$video['author'] = htmlspecialchars($video['author']);		
															$video['title'] = htmlspecialchars($video['title']);
															$video['description'] = $__video_h->shorten_description($video['description'], 50);
													?>
        <li class="channels-content-item">
            <span class="context-data-item data-context-item-user=" betatube"="" data-context-item-title="<?php echo $video['title'] ?>" data-context-item-time="<?php echo $video['duration'] ?>" data-context-item-type="video" data-context-item-id="cWQ3NXh5tUE" data-context-item-views="156,599 views">
    <div class="channel-video-thumb-container">
      <a href="/watch?v=<?php echo $video['rid'] ?>" class="ux-thumb-wrap yt-uix-sessionlink yt-uix-contextlink contains-addto spf-link" data-sessionlink=""><span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img alt="Thumbnail" src="/dynamic/thumbs/<?php echo $video['thumbnail'] ?>" width="185"><span class="vertical-align"></span></span></span></span><span class="video-time"><?php $video['duration'] ?></span>


  <button class="addto-button video-actions spf-nolink addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" title="Watch Later" type="button" onclick=";return false;" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="cWQ3NXh5tUE" role="button"><span class="yt-uix-button-content">  <img src="//web.archive.org/web/20130225023311im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
 </span>  <img class="yt-uix-button-arrow" src="//web.archive.org/web/20130225023311im_/http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" title="">
</button>
</a>
    </div>
    <span class="content-item-detail">
      <a href="/watch?v=<?php echo $video['rid'] ?>" title="<?php echo $video['title'] ?>" class="content-item-title spf-link yt-uix-sessionlink yt-uix-contextlink yt-ui-ellipsis yt-ui-ellipsis-2" dir="ltr" data-sessionlink="ei=Z80qUfeiIJamhAGPg4DoBg&amp;feature=c4-videos-u"><span class="yt-ui-ellipsis-wrapper" data-original-html="<?php echo $video['title'] ?>
      "><?php echo $video['title'] ?></span></a>
      <span class="content-item-metadata">
        
        
          <span class="content-item-view-count">
            <?php echo $video['views'] ?> views
          </span>
          <span class="metadata-separator">|</span>
          <span class="content-item-time-created"><?php echo $video['age'] ?></span>
      </span>
    </span>
  </span>


    </li>
														<?php } ?>
  </ul>
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
</div>
</div>
</div>
<div id="footer-container">
	<?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_footer.php"); ?>
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
			<span class="yt-uix-button-menu-item yt-uix-tooltip sign-in" data-possible-tooltip="" data-tooltip-show-delay="750">Watch later on BetaTube? Are you crazy? I am too lazy to add that.
			</span>

		</div>
		<div id="addto-list-saved-panel" class="menu-panel">
			<div class="panel-content">
				<div class="yt-alert yt-alert-naked yt-alert-success  ">
					<div class="yt-alert-icon">
						<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
					</div>
					<div class="yt-alert-content" role="alert"> <span class="yt-alert-vertical-trick"></span>
						<div class="yt-alert-message">

							<span class="message">Added to <span class="addto-title yt-uix-tooltip yt-uix-tooltip-reverse" title="More information about this playlist" data-tooltip-show-delay="750"></span></span>

						</div>
					</div>
				</div>
				<div class="yt-alert yt-alert-naked yt-alert-warn  private-video-warning hid">
					<div class="yt-alert-icon">
						<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
					</div>
					<div class="yt-alert-content" role="alert"> <span class="yt-alert-vertical-trick"></span>
						<div class="yt-alert-message">
							Private videos will be skipped if viewers don&#39;t have access, but playlist notes are publicly visible.
						</div>
					</div>
				</div>

			</div>
		</div>
		<div id="addto-list-error-panel" class="menu-panel">
			<div class="panel-content">
				<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
				<span class="error-details"></span>
				<a class="show-menu-link">Back to list</a>
			</div>
		</div>

		<div id="addto-note-input-panel" class="menu-panel">
			<div class="panel-content">
				<div class="yt-alert yt-alert-naked yt-alert-success  ">
					<div class="yt-alert-icon">
						<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
					</div>
					<div class="yt-alert-content" role="alert"> <span class="yt-alert-vertical-trick"></span>
						<div class="yt-alert-message">
							<span class="message">Added to playlist:</span>
							<span class="addto-title yt-uix-tooltip" title="More information about this playlist" data-tooltip-show-delay="750"></span>

						</div>
					</div>
				</div>
				<div class="yt-alert yt-alert-naked yt-alert-warn  private-video-warning hid">
					<div class="yt-alert-icon">
						<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
					</div>
					<div class="yt-alert-content" role="alert"> <span class="yt-alert-vertical-trick"></span>
						<div class="yt-alert-message">
							Private videos will be skipped if viewers don&#39;t have access, but playlist notes are publicly visible.
						</div>
					</div>
				</div>

			</div>
			<div class="yt-uix-char-counter" data-char-limit="150">
				<div class="addto-note-box addto-text-box"><textarea id="addto-note" class="addto-note yt-uix-char-counter-input" maxlength="150"></textarea><label for="addto-note" class="addto-note-label">Add an optional note</label></div><span class="yt-uix-char-counter-remaining">150</span>
			</div> <button disabled="disabled" class="playlist-save-note yt-uix-button yt-uix-button-default" type="button" onclick=";return false;" role="button"> <span class="yt-uix-button-content">
					Add note
				</span>
			</button>
		</div>
		<div id="addto-note-saving-panel" class="menu-panel">
			<div class="panel-content loading-content">
				<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
				<span>Saving note...</span>
			</div>
		</div>
		<div id="addto-note-saved-panel" class="menu-panel">
			<div class="panel-content">
				<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
				<span class="message">Note added to:</span>
			</div>
		</div>
		<div id="addto-note-error-panel" class="menu-panel">
			<div class="panel-content">
				<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
				<span class="message">Error adding note:</span>
				<ul class="error-details"></ul>
				<a class="add-note-link">Click to add a new note</a>
			</div>
		</div>
		<div class="close-note hid">
			<img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="close-button">
		</div>

	</div>

</div>
<script>
	var ytspf = ytspf || {};
	ytspf.enabled = false;
</script>
<script>
	if (window.ytcsi) {
		ytcsi.tick("js_head");
	}
</script>
<script id="js-471614378" class="www_base_mod" src="http://s.ytimg.com/yts/jsbin/www_base_mod-vflaJzeRi.js" data-loaded="true"></script>
<script>
	var searchBox = document.getElementById('masthead-search-term');
	if (searchBox) {
		searchBox.focus();
	}
</script>




<script>
yt.setConfig({
  'MASTHEAD_ENCRYPTED_ID': "tdwoKUR8RaA",
  'MASTHEAD_IS_BRANDED': true
});






yt.setConfig('JS_PAGE_MODULES', {
  "\/yts\/jsbin\/www_feed_mod-vflNimF0P.js": []
});
</script>


<script>
yt.setAjaxToken('feed_change_ajax', "ViR0e1JZqSnaPZ5100v5Zo6oaeV8MTM3MjcyMzI0M0AxMzcyNjM2ODQz");
yt.setConfig({
  'GUIDE_SELECTED_ITEM': "HCtnHdj3df7iM"
});
</script>
<script>
yt.setConfig({
  'EVENT_ID': "qsbQUa_GNoafyQGSlIHQCw",
  'PAGE_NAME': "index",
  'LOGGED_IN': false,
  'SESSION_INDEX': null,
  'DELEGATED_SESSION_ID': null,
  'GAPI_HOST': "https:\/\/web.archive.org\/web\/20130701000042\/https:\/\/apis.google.com",
  'GAPI_HINT_PARAMS': "m;\/_\/scs\/abc-static\/_\/js\/k=gapi.gapi.en.aBqw11eoBzM.O\/m=__features__\/am=EA\/rt=j\/d=1\/rs=AItRSTMkiisOVRW5P7l3Ig59NtxV0JdMMA",
  'GAPI_LOCALE': "en_US",
  'MASTHEAD_JS': "\/yts\/jsbin\/www-masthead-vfl8Ap0u_.js",
  'JS_COMMON_MODULE': "\/yts\/jsbin\/www_common_mod-vflFz4kv3.js",
  'SAFETY_MODE_PENDING': false,
  'LOCAL_DATE_TIME_CONFIG': {
	"formatWeekdayShortTime": "EE h:mm a",
	"months": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
	"formatLongDate": "MMMM d, yyyy h:mm a",
	"weekdays": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
	"shortMonths": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	"formatShortDate": "MMM d, yyyy",
	"shortWeekdays": ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
	"amPms": ["AM", "PM"],
	"formatLongDateOnly": "MMMM d, yyyy"
  },
  'FEEDBACK_BUCKET_ID': "Home",
  'FEEDBACK_LOCALE_LANGUAGE': "en",
  'FEEDBACK_LOCALE_EXTRAS': {
	"logged_in": false,
	"is_partner": "",
	"guide_subs": "NA",
	"is_branded": "",
	"experiments": "916904,912516,932223,914052,916626,919515,906397,928201,929123,929121,929915,929906,929907,929125,929127,925714,929917,929919,931202,912512,912515,912518,912521,906838,906840,931913,904830,919373,933701,904122,900816,926403,909421,912711,907228",
	"accept_language": null
  }
});
yt.setMsg({
  'ADDTO_WATCH_LATER': "Watch Later",
  'ADDTO_WATCH_LATER_ADDED': "Added",
  'ADDTO_WATCH_LATER_ERROR': "Error"
});
yt.setAjaxToken('addto_ajax_logged_out', "Oo1hj2iWs9TPzzACwMHQuMe9DTx8MTM3MjcyMzI0M0AxMzcyNjM2ODQz");
yt.setConfig('FEED_PRIVACY_CSS_URL', "https:\/\/web.archive.org\/web\/20130701000042\/http:\/\/s.ytimg.com\/yts\/cssbin\/www-feedprivacydialog-vflnT4HKf.css");
yt.setAjaxToken('feed_privacy_ajax', "JCr3sBLdC3okeqXF2zBFLwE-X-Z8MTM3MjcyMzI0M0AxMzcyNjM2ODQz");
yt.setConfig('FEED_PRIVACY_LIGHTBOX_ENABLED', true);
yt.setConfig({
  'SBOX_JS_URL': "\/\/web.archive.org\/web\/20130701000042\/http:\/\/s.ytimg.com\/yts\/jsbin\/www-searchbox-vflkqp0xg.js",
  'SBOX_SETTINGS': {
	"REQUEST_DOMAIN": "us",
	"REQUEST_LANGUAGE": "en",
	"PSUGGEST_TOKEN": null,
	"EXPERIMENT_ID": -1,
	"USE_HTTPS": false,
	"HAS_ON_SCREEN_KEYBOARD": false,
	"CHIP_PARAMETERS": {},
	"SHOW_CHIP": false,
	"SESSION_INDEX": null,
	"CLOSE_ICON_URL": "\/s\/img\/icons\/close-vflrEJzIW.png"
  },
  'SBOX_LABELS': {
	"VIEW_CHANNEL_LABEL": "View channel",
	"SUGGESTION_DISMISSED_LABEL": "Suggestion dismissed",
	"WATCH_NOW_LABEL": "Watch now",
	"SUGGESTION_DISMISS_LABEL": "Dismiss"
  }
});
yt.setConfig({
  'YPC_LOADER_ENABLED': true,
  'YPC_LOADER_CONFIGS': "\/ypc_config_ajax",
  'YPC_LOADER_JS': "\/yts\/jsbin\/www-ypc-vflKxDpzV.js",
  'YPC_LOADER_CSS': "\/yts\/cssbin\/www-ypc-vfl7OlCUa.css",
  'YPC_LOADER_CALLBACKS': ['yt.www.ypc.checkout.init', 'yt.www.ypc.subscription.init']
});
</script>
<script>
	ytcsi.span('st', 153);
	yt.setConfig({
		'TIMING_ACTION': "glo",
		'TIMING_INFO': {
			"yt_spf": 0,
			"ei": "qsbQUa_GNoafyQGSlIHQCw",
			"yt_li": 0,
			"yt_lt": "cold",
			"e": "916904,912516,932223,914052,916626,919515"
		}
	});
</script>
<script>
	yt.setConfig({
		'XSRF_TOKEN': "Ug16hErgO6XbVqSRo9sAw3sa6ep8MTM3MjcyMzI0MkAxMzcyNjM2ODQy",
		'XSRF_REDIRECT_TOKEN': "GQpRVsYnWgcpEZFXuF-pJm7hAZF8MTM3MjcyMzI0M0AxMzcyNjM2ODQz",
		'XSRF_FIELD_NAME': "session_token"
	});
</script>
<script>
	yt.setConfig('THUMB_DELAY_LOAD_BUFFER', 300);
</script>
<script>
	if (window.ytcsi) {
		ytcsi.tick("js_foot");
	}
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

<div id="debug"></div>
</body>
</html>
<?php	}	?>