<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/config.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/db_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/time_manip.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/video_helper.php"); ?>
<?php $__video_h = new video_helper($__db); ?>
<?php $__user_h = new user_helper($__db); ?>
<?php $__db_h = new db_helper(); ?>
<?php $__time_h = new time_helper(); ?>
<?php header('Content-Type: application/json'); ?>
<?php
    if(!isset($_GET['action_load_user_feed'])) {
        switch(@$_GET['chart_name']) {
        case "trending":
            $category = 'Trending';
            $diviconn = 'trending';
            break;
        case "popular":
            $category = 'Popular';
            $diviconn = 'popular';
            break;
        case "music":
            $category = 'Music';
            $diviconn = 'music';
            break;
        case "entertainment":
            $category = 'Entertainment';
            $diviconn = 'entertainment';
            break;
        case "sports":
            $category = 'Sports';
            $diviconn = 'sports';
            break;
        case "comedy":
            $category = 'Comedy';
            $diviconn = 'comedy';
            break;
        case "film":
            $category = 'Film & Animation';
            $diviconn = 'film';
            break;
        case "gadgets":
            $category = 'Gaming';
            $diviconn = 'gadgets';
            break;
        }
        
        switch(@$_GET['feed_name']) {
        case "trending":
            $category = 'Trending';
            $diviconn = 'trending';
            break;
        case "popular":
            $category = 'Popular';
            $diviconn = 'popular';
            break;
        case "music":
            $category = 'Music';
            $diviconn = 'music';
            break;
        case "entertainment":
            $category = 'Entertainment';
            $diviconn = 'entertainment';
            break;
        case "sports":
            $category = 'Sports';
            $diviconn = 'sports';
            break;
        case "comedy":
            $category = 'Comedy';
            $diviconn = 'comedy';
            break;
        case "film":
            $category = 'Film & Animation';
            $diviconn = 'film';
            break;
        case "gadgets":
            $category = 'Gaming';
            $diviconn = 'gadgets';
            break;
        }

        $stmt = $__db->prepare("SELECT * FROM videos WHERE category = :category ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(":category", $category);
        $stmt->execute();
    } else {
        $username = $_GET['user_id'];
        $category = htmlspecialchars($_GET['user_id']);
        $diviconn = "trending";
        $stmt = $__db->prepare("SELECT * FROM videos WHERE author = :username ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
    }
	?>
{'paging': null, 'feed_html': `
<div class=\'feed-header no-metadata\'>
	<div class=\'feed-header-thumb\'>
		<img class=\'feed-header-icon <?php echo $diviconn; ?>\' src=\'//s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif\' alt=\'\'>
	</div>
	<div class=\'feed-header-details\'>
		<h2>    <?php echo $category; ?></h2>
	</div>
</div>
<div class=\'feed-container\' data-filter-type=\'\' data-view-type=\'\'>
<div class=\'feed-page\'>
	<ul>
        <?php
            while($video = $stmt->fetch(PDO::FETCH_ASSOC)) {	
                $video['age'] = $__time_h->time_elapsed_string($video['publish']);		
                $video['duration'] = $__time_h->timestamp($video['duration']);
                $video['views'] = $__video_h->fetch_video_views($video['rid']);
                $video['author'] = htmlspecialchars($video['author']);		
                $video['title'] = htmlspecialchars($video['title']);
                $video['description'] = $__video_h->shorten_description($video['description'], 50);
        ?>
        <li>
            <div class="feed-item-container first " data-channel-key="UCR2A9ZNliJfgC66IvIpe-Zw">
                <div class="feed-author-bubble-container">
                    <a href="/user/<?php echo $video['author']; ?>" class="feed-author-bubble   ">  <span class="feed-item-author">
                    <span class="video-thumb ux-thumb yt-thumb-square-28 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="http://s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif" alt="<?php echo $video['author']; ?>" data-thumb="/dynamic/pfp/<?php echo $__user_h->fetch_pfp($video['author']); ?>" width="28"><span class="vertical-align"></span></span></span></span>
                    </span>
                    </a>  
                </div>
                <div class="feed-item-main">
                    <div class="feed-item-header">
                        <span class="feed-item-actions-line ">
                        <span class="feed-item-owner">    <a href="/user/<?php echo $video['author']; ?>" class="yt-uix-sessionlink yt-user-name " data-sessionlink="feature=g-logo-xit" dir="ltr"><?php echo $video['author']; ?></a>
                        </span>
                        uploaded a video
                        <span class="feed-item-time">
                        <?php echo $video['age']; ?>
                        </span>
                        </span>
                    </div>
                    <div class="feed-item-content-wrapper clearfix">
                        <div class="feed-item-thumb">
                            <a class="ux-thumb-wrap contains-addto  yt-uix-contextlink yt-uix-sessionlink" data-sessionlink="ei=CNLr3rbS3rICFSwSIQodSW397Q%3D%3D&amp;context=G266dc06FOAAAAAAAAAA" href="/watch?v=<?php echo $video['rid']; ?>">
                            <span class="video-thumb ux-thumb yt-thumb-default-185 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="http://s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif" alt="Thumbnail" onerror="this.onerror=null;this.src='/dynamic/thumbs/default.jpg';" data-thumb="/dynamic/thumbs/<?php echo $video['thumbnail']; ?>" width="185"><span class="vertical-align"></span></span></span></span>
                            <span class="video-time"><?php echo $video['duration']; ?></span>
                            <button onclick=";return false;" title="Watch Later" type="button" class="addto-button video-actions addto-watch-later-button-sign-in yt-uix-button yt-uix-button-default yt-uix-button-short yt-uix-tooltip" data-button-menu-id="shared-addto-watch-later-login" data-video-ids="rLHU-_OhT8g" role="button"><span class="yt-uix-button-content">  <img src="//s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif" alt="Watch Later">
                            </span><img class="yt-uix-button-arrow" src="//s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif" alt=""></button>
                            </a>
                        </div>
                        <div class="feed-item-content">
                            <h4>
                                <a class="feed-video-title title yt-uix-contextlink  yt-uix-sessionlink  secondary" href="/watch?v=<?php echo $video['rid']; ?>" data-sessionlink="ei=CNLr3rbS3rICFSwSIQodSW397Q%3D%3D&amp;feature=g-logo-xit&amp;context=G266dc06FOAAAAAAAAAA">
                                    <?php echo $video['title']; ?>
                                </a>
                            </h4>
                            <div class="metadata">
                                <a href="/user/<?php echo $video['author']; ?>" class="yt-user-photo ">
                                <span class="video-thumb ux-thumb yt-thumb-square-18 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="http://s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif" alt="<?php echo $video['author']; ?>" data-thumb="/dynamic/pfp/<?php echo $__user_h->fetch_pfp($video['author']); ?>" width="18"><span class="vertical-align"></span></span></span></span>
                                </a>
                                <a href="/user/<?php echo $video['author']; ?>" class="yt-uix-sessionlink yt-user-name " data-sessionlink="ei=CNLr3rbS3rICFSwSIQodSW397Q%3D%3D&amp;feature=g-logo-xit" dir="ltr"><?php echo $video['author']; ?></a>
                                <span class="bull">•</span>
                                <span class="view-count">
                                <?php echo $video['views']; ?> views
                                </span>
                                <div class="description">
                                    <p><?php echo $video['description']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feed-item-dismissal-notices">
                <div class="feed-item-dismissal feed-item-dismissal-hide hid">This item has been hidden</div>
                <div class="feed-item-dismissal feed-item-dismissal-uploads hid">In the future you will only see uploads from   <span class="feed-item-owner">    <a href="/user/<?php echo $video['author']; ?>" class="yt-uix-sessionlink yt-user-name " data-sessionlink="feature=g-logo-xit" dir="ltr"><?php echo $video['author']; ?></a>
                    </span>
                </div>
                <div class="feed-item-dismissal feed-item-dismissal-all-activity hid">In the future you will see all activity from   <span class="feed-item-owner">    <a href="/user/<?php echo $video['author']; ?>" class="yt-uix-sessionlink yt-user-name " data-sessionlink="feature=g-logo-xit" dir="ltr"><?php echo $video['author']; ?></a>
                    </span>
                </div>
                <div class="feed-item-dismissal feed-item-dismissal-unsubscribe hid">You have been unsubscribed from   <span class="feed-item-owner">    <a href="/user/<?php echo $video['author']; ?>" class="yt-uix-sessionlink yt-user-name " data-sessionlink="feature=g-logo-xit" dir="ltr"><?php echo $video['author']; ?></a>
                    </span>
                </div>
            </div>
        </li>
        <?php } ?>
	</ul>
</div>
`}