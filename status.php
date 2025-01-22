<?php
   include 'core/init.php';
  
   $user_id = $_SESSION['user_id'];
  
   $user = User::getData($user_id);
   
   if (User::checkLogIn() === false) 
   header('location: index.php');


   $weet_id =  $_GET['post_id'];
   $weet = weet::getData($weet_id);
   $who_users = Follow::whoToFollow($user_id);
   $notify_count = User::CountNotification($user_id);
 
    
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status | Savvorly</title>
    <base href="<?php echo BASE_URL; ?>">
    <link rel="shortcut icon" type="image/png" href="assets/images/savvorly-logo.png"> 
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/home_style.css?v=<?php echo time(); ?>">
    
   
</head>
<body>
<script src="assets/js/jquery-3.5.1.min.js"></script>
  
    <div id="mine">
 
    <div class="wrapper-left">
        <div class="sidebar-left">
          <div class="grid-sidebar" style="margin-top: 12px">
            <div class="icon-sidebar-align">
            <img src="assets\images\savvorly-logo.png" alt="" height="40px" width="40px" />
            </div>
          </div>

          <a href="home.php">
          <div class="grid-sidebar bg-active" style="margin-top: 12px">
            <div class="icon-sidebar-align">
            <img src="https://img.icons8.com/?size=100&id=1iF9PyJ2Thzo&format=png&color=9157fc" alt="" height="26.25px" width="26.25px" />
            </div>
            <div class="wrapper-left-elements">
              <a class="wrapper-left-active" href="home.php" style="margin-top: 4px;"><strong>Home</strong></a>
            </div>
          </div>
          </a>
  
          <a href="notification.php">
          <div class="grid-sidebar">
            <div class="icon-sidebar-align position-relative">
                <?php if ($notify_count > 0) { ?>
              <i class="notify-count"><?php echo $notify_count; ?></i> 
              <?php } ?>
              <img
              src="https://img.icons8.com/?size=100&id=11668&format=png&color=000000"
                alt=""
                height="26.25px"
                width="26.25px"
              />
            </div>
  
            <div class="wrapper-left-elements">
              <a href="notification.php" style="margin-top: 4px"><strong>Notification</strong></a>
            </div>
          </div>
          </a>
        
            <a href="<?php echo BASE_URL . $user->username; ?>">
          <div class="grid-sidebar">
            <div class="icon-sidebar-align">
            <img src="https://img.icons8.com/?size=100&id=2yC9SZKcXDdX&format=png&color=000000" alt="" height="26.25px" width="26.25px" />
            </div>
  
            <div class="wrapper-left-elements">
              <!-- <a href="/savvorly/<?php echo $user->username; ?>"  style="margin-top: 4px"><strong>Profile</strong></a> -->
              <a  href="<?php echo BASE_URL . $user->username; ?>"  style="margin-top: 4px"><strong>Profile</strong></a>
            
            </div>
          </div>
          </a>
          <a href="<?php echo BASE_URL . "account.php"; ?>">
          <div class="grid-sidebar ">
            <div class="icon-sidebar-align">
            <img src="https://img.icons8.com/?size=100&id=2969&format=png&color=000000" alt="" height="26.25px" width="26.25px" />
            </div>
  
            <div class="wrapper-left-elements">
              <a href="<?php echo BASE_URL . "account.php"; ?>" style="margin-top: 4px"><strong>Settings</strong></a>
            </div>
           
            
          </div>
          </a>
          <a href="includes/logout.php">
          <div class="grid-sidebar">
            <div class="icon-sidebar-align">
            <i style="font-size: 26px; color:red" class="fas fa-sign-out-alt"></i>
            </div>
  
            <div class="wrapper-left-elements">
              <a style="color:red" href="includes/logout.php" style="margin-top: 4px"><strong>Logout</strong></a>
            </div>
          </div>
          </a>
          <button class="button-twittear">
            <strong>Savvorly</strong>
          </button>
  
          <div class="box-user">
            <div class="grid-user">
              <div>
                <img
                  src="assets/images/users/<?php echo $user->img ?>"
                  alt="user"
                  class="img-user"
                />
              </div>
              <div>
                <p class="name"><strong><?php if($user->name !== null) {
                echo $user->name; } ?></strong></p>
                <p class="username">@<?php echo $user->username; ?></p>
              </div>
              <div class="mt-arrow">
                <img
                  src="https://i.ibb.co/mRLLwdW/arrow-down.png"
                  alt=""
                  height="18.75px"
                  width="18.75px"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
          
  

      <div class="grid-posts">
        <div class="border-right">
          <div class="grid-toolbar-center">
            <div class="center-input-search">
              
                
                <div class="container" style="border-bottom: 1px solid #E9ECEF;">
                 
                  <div class="row">
                       <div class="col-xs-1">
                      <!-- history go to the perv page and specific section user come from -->
                 <a href="javascript: history.go(-1);"> <i style="font-size:20px;" class="fas fa-arrow-left arrow-style"></i> </a>
                       </div>
                       <div class="col-xs-10 mt-1">
                           <p class="weet-name" style="
                           font-weight:700"> Post</p>
                          
                      </div>
               

            
                    
                  </div>
                  <div class="part-2">
            
                  </div>
            
                </div>
                
                
             
            </div>
            <!-- <div class="mt-icon-settings">
              <img src="https://i.ibb.co/W5T9ycN/settings.png" alt="" />
            </div> -->
          </div> 
          
          <div class="box-fixed" id="box-fixed"></div>
          
          <?php 

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
               
            //  show real weet comments if reweeted weet 
              if ($reweet_sign)
              $comments = weet::comments($reweeted_weet->id);
              else  $comments = weet::comments($weet_id);

            
            if($reweet_sign)
             $comment_count = weet::countComments($reweeted_weet->id);
             else  $comment_count = weet::countComments($weet->id); 
                     
            
            ?>
             

              
          <div class="box-weet feed" style="position: relative;" >
                 <a href="status/<?php echo $weet->id; ?>">
                    <span style="position:absolute; width:100%; height:100%; top:0;left: 0; z-index: 1;"></span>
                 </a>
            <?php if ($reweet_sign) { ?>
            <span class="retweed-name"> <i class="fa fa-reweet reweet-name-i" aria-hidden="true"></i> 
            <a style="position: relative; z-index:100; color:rgb(102, 117, 130);" href="<?php echo $reweeted_user->name; ?> "> <?php  if($reweeted_user->id == $user_id) echo "You";
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
                <p>
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
                   <!-- ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss --> 
                  
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

                <div class="row home-follow pt-3">
                       
                        <?php if($reweets_count > 0)  { ?>
                            <div class="col-md-2 users-count" >
                            <i class="reweets-u"
                            data-weet="<?php 
                            if($reweet_sign)
                                echo $reweeted_weet->id;
                            else  echo $weet->id; ?>"> 
                     <span class="home-follow-count"> <?php echo $reweets_count ; ?> </span> Reposts</i>
                        </div> 
                        <?php } ?> 
                        <?php if($likes_count > 0)  { ?>
                        <div class="col-md-2 users-count">
                            <i class="likes-u" 
                            data-weet="<?php 
                            if($reweet_sign)
                                echo $reweeted_weet->id;
                            else  echo $weet->id; ?>">
                             <span class="home-follow-count">  <?php echo $likes_count ; ?>  </span> Likes</i>
                        </div>   
                        <?php } ?> 
                  </div>

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
                        <p> <?php if($comment_count > 0) echo $comment_count; ?> </p>
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
                    data-qoq="<?php echo $qoq; ?>"
                    data-status="<?php echo true; ?>">

                    
                      
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
             
              <div class="comments">

            
          <!-- comments place --> 
          <?php foreach($comments as $comment) { 
                     $weet_user = User::getData($comment->user_id) ;
                     $timeAgo = weet::getTimeAgo($comment->time);
                     $replies = weet::replies($comment->id);
                     $reply_count = weet::countReplies($comment->id);
              ?>

          <div class="box-comment feed py-2"  >
                
          
            <div class="grid-weet">
              <div>
                <img
                  src="assets/images/users/<?php echo $weet_user->img; ?>"
                  alt=""
                  class="img-user-weet"
                />
              </div>
  
              <div>
                <p>
                  <strong> <?php echo $weet_user->name ?> </strong>
                  <span class="username-savvorly">@<?php echo $weet_user->username ?> </span>
                  <span class="username-savvorly"><?php echo $timeAgo ?></span>
                </p>
                <p>
                  <?php
                 echo  weet::getweetLinks($comment->comment); ?>
                </p>
                    
                <div class="grid-reactions">
                  <div class="grid-box-reaction-rep">
                    <div class="hover-reaction-rep hover-reaction-comment reply"
                    data-user = "<?php echo $user_id; ?>" 
                    data-weet = "<?php 
                    echo $comment->id; ?>">
                     
                      <i class="far fa-comment"></i>
                      <div class="mt-counter likes-count d-inline-block">
                        <p > <?php if($reply_count > 0) echo $reply_count; ?> </p>
                      </div>
                    </div>
                  </div>
                  
                

                  </div>

              </div> 
            
              
            </div> 
          
        </div> 

        
                        <!-- replies -->
                <?php foreach ($replies as $reply) {
                       $weet_user = User::getData($reply->user_id) ;
                       $timeAgo = weet::getTimeAgo($reply->time);
                    
                    ?>
                        <div class="box-reply feed"  >
                                
                        
                                <div class="grid-weet">
                                <div>
                                    <img
                                    src="assets/images/users/<?php echo $weet_user->img; ?>"
                                    alt=""
                                    class="img-user-weet"
                                    />
                                </div>
                    
                                <div>
                                    <p>
                                    <strong> <?php echo $weet_user->name ?> </strong>
                                    <span class="username-savvorly">@<?php echo $weet_user->username ?> </span>
                                    <span class="username-savvorly"><?php echo $timeAgo ?></span>
                                    </p>
                                    <p>
                                    <?php
                                    echo  weet::getweetLinks($reply->reply); ?>
                                    </p>
                                        
                    
                                </div> 
                                
                                
                                </div> 
                            
                            </div> 

                            <?php } ?>
            <?php } ?>
         
          <div class="popupweet">

          </div>
          <div class="popupComment">

           </div>
           <div class="popupUsers">

           </div>
            
           </div>


         



        </div> 

      
        <div class="wrapper-right">
            <div style="width: 90%;" class="container">

          <div class="input-group py-2 m-auto pr-5 position-relative">

          <i id="icon-search" class="fas fa-search tryy"></i>
          <input type="text" class="form-control search-input"  placeholder="Search cuisine or restaurants">
          <div class="search-result">


          </div>
          </div>
          </div>

          
       


                
          <div class="box-share">
            <p class="txt-share"><strong>Who to follow</strong></p>
            <?php 
            foreach($who_users as $user) { 
              //  $u = User::getData($user->user_id);
               $user_follow = Follow::isUserFollow($user_id , $user->id) ;
               ?>
          <div class="grid-share">
          <a style="position: relative; z-index:5; color:black" href="<?php echo $user->username;  ?>">
                      <img
                        src="assets/images/users/<?php echo $user->img; ?>"
                        alt=""
                        class="img-share"
                      />
                    </a>
                    <div>
                      <p>
                      <a style="position: relative; z-index:5; color:black" href="<?php echo $user->username;  ?>">  
                      <strong><?php echo $user->name; ?></strong>
                      </a>
                    </p>
                      <p class="username">@<?php echo $user->username; ?>
                      <?php if (Follow::FollowsYou($user->id , $user_id)) { ?>
                  <span class="ml-1 follows-you">Follows You</span></p>
                  <?php } ?></p></p>
                    </div>
                    <div>
                      <button class="follow-btn follow-btn-m 
                      <?= $user_follow ? 'following' : 'follow' ?>"
                      data-follow="<?php echo $user->id; ?>"
                      data-user="<?php echo $user_id; ?>"
                      data-profile="<?php echo $u_id; ?>"
                      style="font-weight: 700;">
                      <?php if($user_follow) { ?>
                        Following 
                      <?php } else {  ?>  
                          Follow
                        <?php }  ?> 
                      </button>
                    </div>
                  </div>

                  <?php }?>
         
          
          </div>
  
  
  
        </div>
      </div>
      </div> 

      <script src="assets/js/search.js"></script>
      <script type="text/javascript" src="assets/js/hashtag.js"></script>
      <script type="text/javascript" src="assets/js/like.js"></script>
      <script type="text/javascript" src="assets/js/users.js"></script>
      <script type="text/javascript" src="assets/js/comment.js?v=<?php echo time(); ?>"></script>
      <script type="text/javascript" src="assets/js/reweet.js?v=<?php echo time(); ?>"></script>
      <script type="text/javascript" src="assets/js/follow.js?v=<?php echo time(); ?>"></script>
      <script src="https://kit.fontawesome.com/38e12cc51b.js" crossorigin="anonymous"></script>
      <!-- <script src="assets/js/jquery-3.4.1.slim.min.js"></script> -->
      <script src="assets/js/jquery-3.5.1.min.js"></script>

        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
</body>
</html> 