
<div id="guide-container">
    <div id="guide-main" class="guide-module ">
        <div class="guide-module-toggle">
            <span class="guide-module-toggle-icon">
            <span class="guide-module-toggle-arrow"></span>
            <img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
            <img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="" id="collapsed-notification-icon">
            </span>
            <div class="guide-module-toggle-label">
                <h3>
                    <span>
                    Guide
                    <span class="yt-badge-new">new</span>
                    </span>
                </h3>
            </div>
        </div>
        <div class="guide-module-content">
            <ul class="guide-toplevel">
                <li id="guide-subscriptions-section" class="guide-section without-filter guide-section-no-counts">
                    <div id="guide-subs-footer-container">
                        <div id="guide-subscriptions-container">
                            <div class="guide-channels-content">
                                <ul id="guide-channels" class="guide-channels-list guide-item-container yt-uix-scroller filter-has-matches">
								
												<?php
													$__guide_items = (object) [
														"accset" => (object) [
															"label" => "Account Settings",
															"icon" => "1",
															"url" => "/account/",
															"selected" => false,
														],
														"channelcustom" => (object) [
															"label" => "Channel Settings",
															"icon" => "2",
															"url" => "/account/channel",
															"selected" => false,
														],

													];
													
												?>
												
											<?php foreach($__guide_items as $_guide_item) { 
											if(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) != $_guide_item->url)
												$_guide_item->selected = true;
											?>
                                    <li class="guide-channel">
                                        <a class="guide-item yt-uix-sessionlink <?php echo $_guide_item->selected ? true : "guide-item-selected"; ?>" href="<?php echo $_guide_item->url ?>" title="<?php echo $_guide_item->label ?>" data-channel-id="youtube" data-sessionlink="ei=7pFAUZzAG52shAGGr4DACw&amp;feature=g-channel">
                                        <span class="thumb"><span class="video-thumb ux-thumb yt-thumb-square-18 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Thumbnail" data-thumb-manual="1" data-thumb="/guideico/<?php echo $_guide_item->icon ?>.jpg" data-group-key="guide-channel-thumbs" width="18"><span class="vertical-align"></span></span></span></span></span>
                                        <span class="display-name">
                                        <span><?php echo $_guide_item->label ?></span>
                                        </span>
                                        </a>
                                    </li>
												<?php } ?>
                                </ul>
                            </div>
                        </div>
                        <hr class="guide-section-separator">
                    </div>
                </li>
                <li id="guide-subscription-suggestions-section" class="guide-section guide-section-no-counts">
                    <div class="guide-recommendations-list">
                        <div class="guide-channels-content">
                            <ul class="guide-channels-list guide-item-container yt-uix-scroller filter-has-matches">
                            <?php if(!isset($_SESSION['siteusername'])) { ?>
							              <h3>
											Channels for you
											</h3>
                                    <?php foreach($__server->featured_channels as $channel) { ?>
										<?php $_user22 = $__user_h->fetch_user_username($channel); ?>
                                        <li class="guide-channel">
                                            <a class="guide-item yt-uix-sessionlink  narrow-item" href="/user/<?php echo htmlspecialchars($channel); ?>" title="<?php echo htmlspecialchars($channel); ?>" data-channel-id="UCF1cWZk-kVBrN9AM_ey7qdQ" data-featured="1" data-sessionlink="ei=7pFAUZzAG52shAGGr4DACw&amp;feature=g-featured">
                                            <span class="thumb"><span class="video-thumb ux-thumb yt-thumb-square-18 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Thumbnail" data-thumb-manual="1" data-thumb="/dynamic/pfp/<?php echo $__user_h->fetch_pfp($channel); ?>" data-group-key="guide-channel-thumbs" width="18"><span class="vertical-align"></span></span></span></span></span>
                                            <span class="display-name">
                                            <span>
												<?php		if($_user22['partner'] == "y") { ?>
												<?php if($_user22['title'])	{	?>
												<?php echo htmlspecialchars($_user22['title']); ?>
												<?php } else {	?>
												<?php echo htmlspecialchars($_user22['username']); ?>
												<?php	}	?>
												<?php	} else{	?>
												<?php echo htmlspecialchars($channel); ?>
												<?php	}	?>
											</span>
                                            </span>
                                            </a>
                                        </li>
                                    <?php } } else { ?>
							                <h3>
												Subscriptions
											</h3>
                                    <?php
                                        $stmt = $__db->prepare("SELECT * FROM subscribers WHERE sender = :username ORDER BY id DESC LIMIT 20");
                                        $stmt->bindParam(":username", $_SESSION['siteusername']);
                                        $stmt->execute();
                                        while($channel = $stmt->fetch(PDO::FETCH_ASSOC)) { $channel = $channel['reciever']; ?>
										<?php $_user22 = $__user_h->fetch_user_username($channel); ?>
                                        <li class="guide-channel">
                                            <a class="guide-item yt-uix-sessionlink  narrow-item" href="/user/<?php echo htmlspecialchars($channel); ?>" title="<?php echo htmlspecialchars($channel); ?>" data-channel-id="UCF1cWZk-kVBrN9AM_ey7qdQ" data-featured="1" data-sessionlink="ei=7pFAUZzAG52shAGGr4DACw&amp;feature=g-featured">
                                            <span class="thumb"><span class="video-thumb ux-thumb yt-thumb-square-18 "><span class="yt-thumb-clip"><span class="yt-thumb-clip-inner"><img src="http://s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="Thumbnail" data-thumb-manual="1" data-thumb="/dynamic/pfp/<?php echo $__user_h->fetch_pfp($channel); ?>" data-group-key="guide-channel-thumbs" width="18"><span class="vertical-align"></span></span></span></span></span>
                                            <span class="display-name">
                                            <span>												
												<?php		if($_user22['partner'] == "y") { ?>
												<?php if($_user22['title'])	{	?>
												<?php echo htmlspecialchars($_user22['title']); ?>
												<?php } else {	?>
												<?php echo htmlspecialchars($_user22['username']); ?>
												<?php	}	?>
												<?php	} else{	?>
												<?php echo htmlspecialchars($channel); ?>
												<?php	}	?></span>
                                            </span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <hr class="guide-section-separator">
                    <a href="/channels?feature=guide" class="guide-management-section guide-management-plus-only guide-item ">
                    <span class="thumb guide-management-plus-icon">
                    <img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" alt="">
                    </span>
                    <span class="guide-management-caption">
                    Browse channels
                    </span>
                    </a>
                </li>
            </ul>
			<?php if(!isset($_SESSION['siteusername'])) { ?>
            <div class="guide-section guide-header signup-promo guided-help-box">
                <p>
                    Sign in to add channels to your guide and for great recommendations!
                </p>
                <div id="guide-builder-promo-buttons" class="signed-out clearfix">
                    <a href="/sign_in" class="yt-uix-button   yt-uix-sessionlink yt-uix-button-primary" data-sessionlink="ei=7pFAUZzAG52shAGGr4DACw"><span class="yt-uix-button-content">Sign in ›</span></a>
                </div>
            </div>
			<?php } ?>
        </div>
    </div>
    <div id="watch-context-container" class="guide-module hid "></div>
</div>
