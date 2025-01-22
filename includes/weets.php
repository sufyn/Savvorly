<?php
$user_id = $_SESSION['user_id'];
// global $weets;
foreach($weets as $weet) { 

$reweet_sign = false;
$reweet_comment =false;
$qoq = false;

if (weet::isweet($weet->id)) {

$weet_user = User::getData($weet->user_id) ;
$weet_real = weet::getweet($weet->id);
$timeAgo = weet::getTimeAgo($weet->post_on) ; 
$likes_count = weet::countLikes($weet->id) ;
$user_like_it = weet::userLikeIt($user_id ,$weet->id);
$reweets_count = weet::countReweets($weet->id) ;
$user_reweeted_it = weet::userRetweeetedIt($user_id ,$weet->id);

} else if (weet::isReweet($weet->id)) {

$reweet = weet::getReweet($weet->id);

if ($reweet->reweet_msg == null) {

    if ($reweet->reweet_id == null) {
      
      // if reweeted normal weet
      $reweeted_weet = weet::getweet($reweet->weet_id);
    $weet_user = User::getData($reweeted_weet->user_id) ;
    $weet_real = weet::getweet($reweet->weet_id);
    $timeAgo = weet::getTimeAgo($weet_real->post_on) ; 
    $likes_count = weet::countLikes($reweet->weet_id) ;
    $user_like_it = weet::userLikeIt($user_id ,$reweet->weet_id);
    $reweets_count = weet::countReweets($reweet->weet_id) ;
    $user_reweeted_it = weet::userRetweeetedIt($user_id ,$reweet->weet_id); 
    $reweeted_user = User::getData($weet->user_id);
    $reweet_sign = true;
    } else {

      // this condtion if user reweeted qouted weet or qoute of qoute weet


    $reweeted_weet = weet::getReweet($reweet->reweet_id);

        if($reweeted_weet->weet_id != null) {
        // here it's reweeted qouted
        // if($reweeted_weet->) 
        $weet_user = User::getData($reweeted_weet->user_id) ;
        $timeAgo = weet::getTimeAgo($reweeted_weet->post_on) ; 
        $likes_count = weet::countLikes($reweeted_weet->post_id) ;
        $user_like_it = weet::userLikeIt($user_id ,$reweeted_weet->post_id);
        $reweets_count = weet::countReweets($reweeted_weet->post_id) ;
        $user_reweeted_it = weet::userRetweeetedIt($user_id ,$reweeted_weet->post_id);
      
        
        $weet_inner = weet::getweet($reweeted_weet->weet_id);
        $user_inner_weet = User::getData($weet_inner->user_id) ;
        $timeAgo_inner = weet::getTimeAgo($weet_inner->post_on); 
        $reweeted_user = User::getData($weet->user_id);
        $reweet_sign = true;

        $qoute = $reweeted_weet->reweet_msg;
        $reweet_comment = true;
        } else {
            // here is reweeted qouted of qouted

        $reweet_sign = true;
        $weet_user = User::getData($reweeted_weet->user_id) ;

        $timeAgo = weet::getTimeAgo($reweeted_weet->post_on) ; 
        $likes_count = weet::countLikes($reweeted_weet->post_id) ;
        $user_like_it = weet::userLikeIt($user_id ,$reweeted_weet->post_id);
        $reweets_count = weet::countReweets($reweeted_weet->post_id) ;
        $user_reweeted_it = weet::userRetweeetedIt($user_id ,$reweeted_weet->post_id);

        $qoq = true; // stand for qoute of qoute
        $qoute = $reweeted_weet->reweet_msg;
        $weet_inner = weet::getReweet($reweeted_weet->reweet_id);
        $user_inner_weet = User::getData($weet_inner->user_id) ;
        $timeAgo_inner = weet::getTimeAgo($weet_inner->post_on);
        $inner_qoute  = $weet_inner->reweet_msg;
      
        

        $reweeted_user = User::getData($weet->user_id);

        }
    }

} else {
// qoute weet condtion
if ($reweet->reweet_id == null) {
$weet_user = User::getData($weet->user_id) ;
$timeAgo = weet::getTimeAgo($weet->post_on) ; 
$likes_count = weet::countLikes($weet->id) ;
$user_like_it = weet::userLikeIt($user_id ,$weet->id);
$reweets_count = weet::countReweets($weet->id) ;
$user_reweeted_it = weet::userRetweeetedIt($user_id ,$weet->id);
$qoute = $reweet->reweet_msg;
$reweet_comment = true;


$weet_inner = weet::getweet($reweet->weet_id);
$user_inner_weet = User::getData($weet_inner->user_id) ;
$timeAgo_inner = weet::getTimeAgo($weet_inner->post_on); 
} else {

// this condtion for qoute of qoute which reweet_id not null and reweet msg not null
$weet_user = User::getData($weet->user_id) ;
$timeAgo = weet::getTimeAgo($weet->post_on) ; 
$likes_count = weet::countLikes($weet->id) ;
$user_like_it = weet::userLikeIt($user_id ,$weet->id);
$reweets_count = weet::countReweets($weet->id) ;
$user_reweeted_it = weet::userRetweeetedIt($user_id ,$weet->id);
$qoute = $reweet->reweet_msg;
$qoq = true; // stand for qoute of qoute

$weet_inner = weet::getReweet($reweet->reweet_id);
$user_inner_weet = User::getData($weet_inner->user_id) ;
$timeAgo_inner = weet::getTimeAgo($weet_inner->post_on);
$inner_qoute = $weet_inner->reweet_msg;
if($inner_qoute == null) {
            
$weet_innerr = weet::getReweet($weet_inner->reweet_id);
$inner_qoute = $weet_innerr->reweet_msg;

// $inner_qoute = "qork";

}

}

}

} 
$weet_link = $weet->id;

if($reweet_sign)
$comment_count = weet::countComments($reweeted_weet->id);
else  $comment_count = weet::countComments($weet->id); 

?>
         
        <div class="box-weet feed" style="position: relative;" >
        <a href="status/<?php echo $weet_link; ?>">
        <span style="position:absolute; width:100%; height:100%; top:0;left: 0; z-index: 1;"></span>
        </a>
        <?php if ($reweet_sign) { ?>
        <span class="retweed-name"> <i class="fa fa-reweet reweet-name-i" aria-hidden="true"></i> 
        <a style="position: relative; z-index:100; color:rgb(102, 117, 130);" href="<?php echo $reweeted_user->username; ?> "> <?php  if($reweeted_user->id == $user_id) echo "You";
        else echo $reweeted_user->name; ?> </a>  reposted</span>
        <?php } ?>
        <div class="grid-weet">
        <a style="position: relative; z-index:1000" href="<?php echo $weet_user->username;  ?>">
        <img
        src="assets/images/users/<?php echo $weet_user->img; ?>"
        alt=""
        class="img-user-weet"
        />
        </a >

        <div>
        <p> 
        <a style="position: relative; z-index:1000; color:black" href="<?php echo $weet_user->username;  ?>">
        <strong> <?php echo $weet_user->name ?> </strong> 
        </a>
        <span class="username-savvorly">@<?php echo $weet_user->username ?> </span>
        <span class="username-savvorly"><?php echo $timeAgo ?></span>
        </p>
        <p class="weet-links">
        <?php
        // check if it's qoute or normal weet
        if ($reweet_comment || $qoq)
        echo  weet::getweetLinks($qoute);
        else echo  weet::getweetLinks($weet_real->status); ?>
        </p>
        <?php if ($reweet_comment == false && $qoq == false) { ?>
        <?php if ($weet_real->img != null) { ?>
        <p class="mt-post-weet">
        <img
        src="assets/images/weets/<?php echo $weet_real->img; ?>"
        alt=""
        class="img-post-weet"
        />
        </p>
        <?php } } else { ?>
        <!-- qoued weet place here --> 

        <div  class="mt-post-weet comment-post" style="position: relative;">

        <a href="status/<?php echo $weet_inner->id; ?>">
        <span class="" style="position:absolute; width:100%; height:100%; top:0;left: 0; z-index: 2;"></span>
        </a>
        <div class="grid-weet py-3 "  > 

        <a style="position: relative; z-index:1000" href="<?php echo $user_inner_weet->username;  ?>">
        <img
        src="assets/images/users/<?php echo $user_inner_weet->img; ?>"
        alt=""
        class="img-user-weet"
        />
        </a >

        <div>
        <p> 
        <a style="position: relative; z-index:1000; color:black" href="<?php echo $user_inner_weet->username;  ?>">
        <strong> <?php echo $user_inner_weet->name ?> </strong> 
        </a>
        <span class="username-savvorly">@<?php echo $user_inner_weet->username ?> </span>
        <span class="username-savvorly"><?php echo $timeAgo_inner ?></span>
        </p>
        <p>
        <?php
        if ($qoq)
        echo weet::getweetLinks($inner_qoute);
        else  echo  weet::getweetLinks($weet_inner->status); ?>
        </p>
        <?php   // don't show img if qoute of qoute
        if ($qoq == false) { 
        if ($weet_inner->img != null) { ?>
        <p class="mt-post-weet">
        <img
        src="assets/images/weets/<?php echo $weet_inner->img; ?>"
        alt=""
        class="img-post-reweet"
        />
        </p>
        <?php } } ?>

        </div> 

        </div>


        </div>

        <?php } ?>

        <div class="grid-reactions">
        <div class="grid-box-reaction">
        <div class="hover-reaction hover-reaction-comment comment"
        data-user = "<?php echo $user_id; ?>" 
        data-weet = "<?php 
        if($reweet_sign)
        echo $reweeted_weet->id;
        else  echo $weet->id; ?>">

        <i class="far fa-comment"></i>
        <div class="mt-counter likes-count d-inline-block">
        <p> <?php if($comment_count > 0) echo $comment_count; ?>  </p>
        </div>
        </div>
        </div>
        <div class="grid-box-reaction">

        <div  class="hover-reaction hover-reaction-reweet
        <?= $user_reweeted_it ? 'reweeted' : 'reweet' ?> option"
        data-weet="<?php
        // send the weet you wanna undo reweet to undo function
        // if the user reweeted it and it's the real weet
        // to send the id of reweeted weet
        // if($user_reweeted_it && !$reweet_sign)
        // echo weet::reweetRealId($weet->id);
        // else
        echo $weet->id ;
        ?>" 
        data-user="<?php echo $user_id; ?>
        "
        data-reweeted = "<?php echo $user_reweeted_it; ?>"
        data-sign = "<?php echo $reweet_sign; ?>"
        data-tmp="<?php echo $reweet_comment; ?>"
        data-qoq="<?php echo $qoq; ?>">



        <i class="fas fa-reweet"></i>
        <div class="mt-counter likes-count d-inline-block">
        <p><?php if($reweets_count > 0)  echo $reweets_count ; ?></p>
        </div>



        </div>

        <div class="options">

            
        </div> 

        </div>
        <div  class="grid-box-reaction"  >
        <a class="hover-reaction hover-reaction-like 
        <?= $user_like_it ? 'unlike-btn' : 'like-btn' ?> " 
        data-weet="<?php 
        if($reweet_sign) {
            if($reweet->weet_id != null) {
            echo $reweet->weet_id;
            } echo $reweet->reweet_id;
        }  else echo $weet->id ;
        //  echo weet::likedweetRealId($weet->id);

        ?>" 
        data-user="<?php echo $user_id; ?>">


        <i class="fa-heart <?= $user_like_it ? 'fas' : 'far mt-icon-reaction' ?>"></i>
        <!-- <i class="fas fa-heart liked"></i> -->

        <div class="mt-counter likes-count d-inline-block">
        <p> <?php if($likes_count > 0)  echo $likes_count ; ?> </p>
        </div>
        </a>


        </div>

        <div class="grid-box-reaction">
        <div class="hover-reaction hover-reaction-comment">

        <i class="fas fa-ellipsis-h mt-icon-reaction"></i>
        </div>
        <div class="mt-counter">
        <p></p>
        </div>
        </div>
        </div>
        </div>
        </div>




        </div>


        <div class="popupweet">

        </div>
        <div class="popupComment">

        </div>




<?php } ?>