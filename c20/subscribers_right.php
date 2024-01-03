            <div class="inner-box" id="user_subscribers">
    <div style="zoom:1">
  <div class="box-title title-text-color">
    Subscribers

        (<a class="headersSmall" name="channel-box-item-count"><?php echo $_user['subscribers']; ?></a>)
  </div>
  <div class="box-editor">
    <div style="float:right">
    
    </div>
  </div>
  <div class="cb"></div>
  </div>



  <div id="user_subscribers-messages" class="hid"></div>

  <div id="user_subscribers-body">
    

  <div style="zoom:1;margin: 0 -12px">
													<?php
														$stmt = $__db->prepare("SELECT * FROM subscribers WHERE reciever = :username ORDER BY id DESC LIMIT 14");
														$stmt->bindParam(":username", $_user['username']);
														$stmt->execute();
														while($subscribers = $stmt->fetch(PDO::FETCH_ASSOC)) {	
															$subber['name'] = $subscribers['sender'];		
													?><?php $subser = $__user_h->fetch_user_username($subber['name']); ?>
      <div class="user-peep" style="width:14.2%;"><center>
          <div class="user-thumb-large link-as-border-color"><div>
<a href="/user/<?php echo htmlspecialchars($subber['name']); ?>" onmousedown="yt.analytics.trackEvent('ChannelPage', 'subscriptions_image_link', 'kiwi112392 - timm795')" rel="followed-by">
    <img id="" src="/dynamic/pfp/<?php echo $subser['pfp'] ?>" alt="sub">
</a>
  </div></div>

        <a href="/user/<?php echo htmlspecialchars($subber['name']); ?>" onmousedown="yt.analytics.trackEvent('ChannelPage', 'subscriptions_text_link', 'kiwi112392 - timm795')" title="<?php echo htmlspecialchars($subber['name']); ?>" rel="followed-by"><?php if($subser['title'])	{	?> <?php echo htmlspecialchars($__video_h->shorten_description($subser['title'], 13)); ?> <?php } else {	?><?php echo htmlspecialchars($__video_h->shorten_description($subser['username'], 11)); ?> <?php	}	?></a>
							
      </center></div>
														<?php } ?>
   <div style="clear:both;font-height:1px"></div>
  </div>
  <div>
        <div style="font-size: 12px; text-align: right; margin-top: 7px;">
    <b><a name="channel-box-see-all" href="">
see all
    </a></b>
  </div>

  </div>

  </div>
  <div class="clear"></div>
  
</div>