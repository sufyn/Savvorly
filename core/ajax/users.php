<?php 
	include '../init.php';
	$user_id = $_SESSION['user_id'];
  $u_id=1;
  $flag = true;
if(isset($_POST['reweetby']) && !empty($_POST['reweetby'])){
	$weet_id   = $_POST['reweetby'];
	// $user       = User::getData($user_id);
	$users = weet::usersRetweeeted($weet_id);
    $headline = "Reweeted by";

} else if (isset($_POST['likeby']) && !empty($_POST['likeby'])) {
    $weet_id   = $_POST['likeby'];
	// $user       = User::getData($user_id);
	$users = weet::usersLiked($weet_id);
    $headline = "Liked by";

} else if (isset($_POST['follower']) && !empty($_POST['follower'])) {
  $u_id   = $_POST['follower'];
// $user       = User::getData($user_id);
$users = Follow::usersFollowers($u_id);
  $headline = "Followers";

} else if (isset($_POST['following']) && !empty($_POST['following'])) {
  $u_id   = $_POST['following'];
// $user       = User::getData($user_id);
$users = Follow::usersFollowing($u_id);
  $headline = "Following";

} else {
  $flag = false;
}   
if (isset($profileData)) {
  $profile = $profileData->id;
}
if ($flag) {
?>
<div class="reweet-popup">
<div class="wrap5">
<div class="reweet-popup-body-wrap popup-users">
	<div class="reweet-popup-heading users">
		<h3> <?php echo $headline; ?> </h3>
		<span><button class="close-reweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
	</div>

	  <div class="box-share-users">
           <?php foreach($users as $user) { 
               $u = User::getData($user->user_id);
               $user_follow = Follow::isUserFollow($user_id , $user->user_id) ;
               ?>
          <div class="grid-share grid-users">
          <a style="position: relative; z-index:999999; color:black"
           href="<?php echo $u->username;  ?>"
           onclick="window.location = '<?php echo BASE_URL .$u->username;  ?>' ">
                      <img
                        src="assets/images/users/<?php echo $u->img; ?>"
                        alt=""
                        class="img-share"
                      />
                    </a>
                    <div>
                      <p>
                      <a onclick="window.location = '<?php echo BASE_URL .$u->username;  ?>' " 
                      style="color:black" href="<?php echo $u->username;  ?>" >  
                      <strong><?php echo $u->name; ?></strong>
                      </a>
                    </p>
                      <p class="username">@<?php echo $u->username; ?>
                      <?php if (Follow::FollowsYou($u->id , $user_id)) { ?>
                  <span class="ml-1 follows-you">Follows You</span></p>
                  <?php } ?>
                </p>
                    </div>
                    <div> 
                      <?php if ($u->id !== $user_id) { ?>
                      <button class="follow-btn follow-btn-m 
                      <?= $user_follow ? 'following' : 'follow' ?>"
                      data-follow="<?php echo $user->user_id; ?>"
                      data-user="<?php echo $user_id; ?>"
                      data-profile="<?php echo $u_id; ?>">
                      <?php if($user_follow) { ?>
                        Following 
                      <?php } else {  ?>  
                          Follow
                        <?php }  ?> 
                      </button>
                      <?php }  ?> 
                    </div>
                  </div>

                  <?php }?>
            
      </div>

	

</div>
<?php  } ?>
<!-- Reweet PopUp ends-->
