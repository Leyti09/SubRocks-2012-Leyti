            <div class="inner-box" id="user_comments">

    <div style="zoom:1">
  <div class="box-title title-text-color">
    Channel Comments
  </div>
  <div class="box-editor">
    <div style="float:right">
        <a href="#" onclick="javascript:get_page('user_comments', 0, 10, 'False');return false;">
Refresh
    </a>

    </div>
  </div>
  <div class="cb"></div>
  </div>



  <div id="user_comments-messages" class="hid"></div>

  <div id="user_comments-body">
      <div class="commentsTableFull text-field outer-box-bg-as-border" style="_width:610px">
  <table border="0" cellspacing="0" cellpadding="0" id="profile_comments_table">
    <tbody>
	
                                                                <?php
                                                                $results_per_page = 12;

                                                                $stmt = $__db->prepare("SELECT * FROM profile_comments WHERE toid = :rid ORDER BY id DESC");
                                                                $stmt->bindParam(":rid", $_user['username']);
                                                                $stmt->execute();

                                                                $number_of_result = $stmt->rowCount();
                                                                $number_of_page = ceil ($number_of_result / $results_per_page);  

                                                                if (!isset ($_GET['page']) ) {  
                                                                    $page = 1;  
                                                                } else {  
                                                                    $page = (int)$_GET['page'];  
                                                                }  

                                                                $page_first_result = ($page - 1) * $results_per_page;  

                                                                $stmt = $__db->prepare("SELECT * FROM profile_comments WHERE toid = :rid ORDER BY id DESC LIMIT :pfirst, :pper");
                                                                $stmt->bindParam(":rid", $_user['username']);
                                                                $stmt->bindParam(":pfirst", $page_first_result);
                                                                $stmt->bindParam(":pper", $results_per_page);
                                                                $stmt->execute();

                                                                while($comment = $stmt->fetch(PDO::FETCH_ASSOC)) { 

                                                            ?><?php $commenter = $__user_h->fetch_user_username($comment['author']); ?>
	<tr class="commentsTableFull ">
          <td valign="top" width="60" style="padding-bottom: 15px;">
      <div id="profile-comment-icon-7CD1569813461D80">
          <div class="user-thumb-medium">
    <div>
<a href="/user/<?php echo htmlspecialchars($comment['author']); ?>">
        <img src="/dynamic/pfp/<?php echo $commenter['pfp'] ?>">
</a>
    </div>
  </div>

      </div>
    </td>
    <td valign="top" style="padding-bottom: 15px;">
      <div class="floatL" style="margin-bottom: 5px;">
        <a name="profile-comment-username" href="/user/<?php echo htmlspecialchars($comment['author']); ?>" style="font-size: 12px;"><b><?php if($commenter['title'])	{	?><?php echo htmlspecialchars($commenter['title']); ?><?php } else {	?><?php echo htmlspecialchars($comment['author']); ?><?php	}	?></b></a>
        <span class="profile-comment-time-created">(<?php echo $__time_h->time_elapsed_string($comment['date']); ?>)</span>
      </div>
        <div class="floatR" style="margin-bottom: 5px">
          
        </div>
      <div class="profile-comment-body" dir="ltr" style="clear:both;" id="profile-comment-7CD1569813461D80">
        <?php echo $__video_h->shorten_description($comment['comment'], 3000, true); ?>
      </div>
    </td>

    </tr>
																<?php } ?>

                                                            <form method="post" action="/d/comment_profile?u=<?php echo htmlspecialchars($_user['username']); ?>">
                                                                <textarea style="resize:none;padding:5px;border-radius:5px;background-color:white;border: 1px solid #d3d3d3; width: 577px; resize: none;"cols="32" id="com" placeholder="Share your thoughts" name="comment"></textarea><br>
                                                                <input style="float: none; margin-right: 0px; margin-top: 0px;" class="yt-uix-button yt-uix-button-default" type="submit" value="Post" name="replysubmit">
                                                            </form>
  </tbody></table>
  </div>
	<?php if ($page < $number_of_page) { ?>
    <div id="user-comments-login-add-comment" style="font-size: 12px; margin-top: 10px" class="alignC">
      <a href="/user/<?php echo htmlspecialchars($_user['username']); ?>&page=<?php echo $page + 1 ?>#user_comments">
Next page
      </a>
    </div>
	<?php } ?>

  </div>
  <div class="clear"></div>
  
</div>