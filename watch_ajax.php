<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/config.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/db_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/time_manip.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/video_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_update.php"); ?><?php $__video_h = new video_helper($__db); ?>
<?php $__user_h = new user_helper($__db); ?>
<?php $__user_u = new user_update($__db); ?>
<?php $__db_h = new db_helper(); ?>
<?php $__time_h = new time_helper(); ?>
<?php header("Content-type: text/xml"); ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<?php $_video = $__video_h->fetch_video_rid($_GET['video_id']); ?>
<root><return_code><![CDATA[0]]></return_code><html_content><![CDATA[  
    <div class="yt-uix-slider yt-rounded" id="watch-channel-discoverbox" data-slider-slide-selected="3" data-slider-slides="4">
	<button class="yt-uix-button yt-uix-button-default yt-uix-slider-prev" rel="prev"><img class="yt-uix-slider-prev-arrow" src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="previous"></button>
	<button class="yt-uix-button yt-uix-button-default yt-uix-slider-next" rel="next"><img class="yt-uix-slider-next-arrow" src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="next"></button>
	<div class="yt-uix-slider-head">
		<span class="yt-uix-slider-nums yt-uix-pager">
        <!--
		<button onclick=";return false;" class="yt-uix-slider-num yt-uix-slider-num-current yt-uix-button yt-uix-button-default" type="button" data-slider-num="0" role="button"><span class="yt-uix-button-content">1 </span></button>
		<button onclick=";return false;" class="yt-uix-slider-num  yt-uix-button yt-uix-button-default" type="button" data-slider-num="1" role="button"><span class="yt-uix-button-content">2 </span></button>
		<button onclick=";return false;" class="yt-uix-slider-num  yt-uix-button yt-uix-button-default" type="button" data-slider-num="2" role="button"><span class="yt-uix-button-content">3 </span></button>
		<button onclick=";return false;" class="yt-uix-slider-num  yt-uix-button yt-uix-button-default" type="button" data-slider-num="3" role="button"><span class="yt-uix-button-content">4 </span></button>
        -->
        </span>
		<div class="yt-uix-slider-title">
			<h2><a href="/channel_videos?n=<?php echo htmlspecialchars($_video['author']); ?>">See all <?php echo $__video_h->fetch_user_videos($_video['author']); ?> videos
				&raquo;</a>
			</h2>
		</div>
	</div>
	<div class="yt-uix-slider-body">
		<div class="yt-uix-slider-slides">
			<ul class="yt-uix-slider-slide ">
                <?php 
                    $stmt = $__db->prepare("SELECT * FROM videos WHERE author = :username ORDER BY id DESC LIMIT 20");
                    $stmt->bindParam(":username", $_video['author']);
                    $stmt->execute();
                    while($video = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                        $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                        $video['duration'] = $__time_h->timestamp($video['duration']);
                        $video['views'] = $__video_h->fetch_video_views($video['rid']);
                        $video['author'] = htmlspecialchars($video['author']);		
                        $video['title'] = htmlspecialchars($video['title']);
                        $video['description'] = $__video_h->shorten_description($video['description'], 50);
                ?>
                    <li class="yt-uix-slider-slide-item ">
                        <div class="video-list-item  yt-tile-default ">
                            <a href="/watch?v=<?php echo $video['rid']; ?>" class="related-video yt-uix-contextlink  yt-uix-sessionlink" data-sessionlink="feature=channel&amp;ei=COeB-Y25jrUCFdWNIQodzR51Jg%3D%3D"><span class="ux-thumb-wrap contains-addto "><span class="video-thumb ux-thumb yt-thumb-default-120 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img alt="<?php echo $video['title']; ?>" src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" data-thumb="/dynamic/thumbs/<?php echo $video['thumbnail']; ?>" width="120" ><span class="vertical-align"></span></span></span></span><span class="video-time"><?php echo $video['duration']; ?></span>
                            <button type="button" onclick=";return false;" title="Watch Later" class="addto-button video-actions spf-nolink addto-watch-later-button yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" data-video-ids="8C-1MRFr4s0" role="button"><span class="yt-uix-button-content">  <img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
                            </span></button>
                            </span><span dir="ltr" class="title" title="<?php echo $video['title']; ?>"><?php echo $video['title']; ?></span><span class="stat attribution">by <span class="yt-user-name " dir="ltr"><?php echo $video['author']; ?></span></span><span class="stat view-count"><?php echo $video['views']; ?> views</span></a>
                        </div>
                    </li>
                <?php } ?>
				<li>
					<hr >
				</li>
			</ul>
		</div>
	</div>
</div>
]]></html_content></root>