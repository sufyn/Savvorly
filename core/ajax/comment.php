<?php 
	include '../init.php';
	$user_id = $_SESSION['user_id'];
	// Comment place
	if(isset($_POST['qoute']) && !empty($_POST['qoute'])){
		$weet_id  = $_POST['qoute'];
		$get_id    = $_POST['user_id'];
		// $flag = $_POST['isQoute'];
		// $qoq = $_POST['qoq'];
		$comment   = User::checkInput($_POST['comment']);
        date_default_timezone_set("Africa/Cairo");
		// $reweet = weet::getReweet($weet_id);
		

        //  if(!$flag_reweeted) {
			

			$data = [
				'user_id' => $_SESSION['user_id'] , 
                'post_id' => $weet_id , 
                'comment' => $comment , 
				'time' => date("Y-m-d H:i:s") ,
			];
		    if ($comment != '') {
				$for_user = weet::getData($weet_id)->user_id;
		
					if($for_user != $user_id) {
						$data_notify = [
						'notify_for' => $for_user ,
						'notify_from' => $user_id ,
						'target' => $weet_id , 
						'type' => 'comment' ,
						'time' => date("Y-m-d H:i:s") ,
						'count' => '0' , 
						'status' => '0'
						];
				
						weet::create('notifications' , $data_notify);
						
					} 

		     User::create('comments' , $data);
		  
			//  $comments = weet::comments($weet_id);
			//  foreach($comments as $comment) {
			// 	$weet_user = User::getData($comment->user_id) ;
            //      echo '<div class="box-comment feed py-2"  >
                
          
			// 	 <div class="grid-weet">
			// 	   <div>
			// 		 <img
			// 		   src="assets/images/users/'. $weet_user->img.' "
			// 		   alt=""
			// 		   class="img-user-weet"
			// 		 />
			// 	   </div>
	   
			// 	   <div>
			// 		 <p>
			// 		   <strong> '. $weet_user->name .' </strong>
			// 		   <span class="username-savvorly">@ '.$weet_user->username.'  </span>
			// 		   <span class="username-savvorly"> $timeAgo </span>
			// 		 </p>
			// 		 <p>
					  
			// 		  '.  weet::getweetLinks($comment->comment) .'
			// 		 </p>
			// 	   </div> 
			   
			// 	 </div>  </div> ';
			//  }



			}
	}

	if(isset($_POST['reply']) && !empty($_POST['reply'])){
		$weet_id  = $_POST['reply'];
		$get_id    = $_POST['user_id'];
	
		$comment   = User::checkInput($_POST['comment']);

			date_default_timezone_set("Africa/Cairo");
          
		
			$data = [
				'user_id' => $_SESSION['user_id'] , 
                'comment_id' => $weet_id , 
                'reply' => $comment , 
				'time' => date("Y-m-d H:i:s") ,
			];
		    if ($comment != '') { 
				// notification
				$for_user = weet::getComment($weet_id)->user_id;
				$target = weet::getComment($weet_id)->post_id;
		
				if($for_user != $user_id) {
					$data_notify = [
					'notify_for' => $for_user ,
					'notify_from' => $user_id ,
					'target' => $target , 
					'type' => 'reply' ,
					'time' => date("Y-m-d H:i:s") ,
					'count' => '0' , 
					'status' => '0'
					];
			
					weet::create('notifications' , $data_notify);
					
				} 
                //  end
				
		     User::create('replies' , $data);
			}
	}
        // Comment on Post popup
	if(isset($_POST['showPopup']) && !empty($_POST['showPopup'])){
		$weet_id   = $_POST['showPopup'];
		$user       = User::getData($user_id);
		$reweet_comment = false;
		$qoq = false;
		if (weet::isReweet($weet_id)) {
		$reweet =weet::getReweet($weet_id);
		if ($reweet->reweet_id == null) {

				// when the reweetd weet is normal weet
				
			if ($reweet->reweet_msg != null) {
				
				// when qoute 

                $user_weet = User::getData($reweet->user_id) ;
				 $timeAgo = weet::getTimeAgo($reweet->post_on) ; 
				 $qoute = $reweet->reweet_msg;
                 $reweet_comment = true;
           

              $weet_inner = weet::getweet($reweet->weet_id);
              $user_inner_weet = User::getData($weet_inner->user_id) ;
              $timeAgo_inner = weet::getTimeAgo($weet_inner->post_on); 


			} else {
				// when normal reweet

				$weet      = weet::getweet($reweet->weet_id);
		    	$user_weet = User::getData($weet->user_id);
		    	$timeAgo = weet::getTimeAgo($weet->post_on) ; 
			}
		} else {
			// if weet_id = null and reweeted_id not null then it's reweet od qoute
			// so we have to get the reweeted weet first

			// here condtion of reweeted a qouted weet
		
			if ($reweet->reweet_msg == null) {
				
				$reweeted_weet = weet::getReweet($reweet->reweet_id);

				if($reweeted_weet->weet_id != null) {
						$user_weet = User::getData($reweeted_weet->user_id) ;
						$timeAgo = weet::getTimeAgo($reweeted_weet->post_on) ; 

						$reweet_inner = weet::getReweet($reweet->reweet_id);

						$qoute = $reweet_inner->reweet_msg;
						$reweet_comment = true;
				

					
					$weet_inner = weet::getweet($reweet_inner->weet_id);
					$user_inner_weet = User::getData($weet_inner->user_id) ;
					$timeAgo_inner = weet::getTimeAgo($weet_inner->post_on); 

				} else {
					// hereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee

					     $user_weet = User::getData($reweeted_weet->user_id) ;
						$timeAgo = weet::getTimeAgo($reweeted_weet->post_on) ; 

						$reweet_inner = weet::getReweet($reweet->reweet_id);

						$qoute = $reweet_inner->reweet_msg;
						$reweet_comment = true;
				        $qoq = true;

					
					$weet_inner = weet::getReweet($reweeted_weet->reweet_id);
					// $weet_inner = weet::getReweet($weet_inner->reweet_id);
					$user_inner_weet = User::getData($weet_inner->user_id) ;
					$timeAgo_inner = weet::getTimeAgo($weet_inner->post_on); 
                    $inner_qoute = $weet_inner->reweet_msg;

				}
			} else {

				// here must handle the qoute of qoute display

				$user_weet = User::getData($reweet->user_id) ;
				$timeAgo = weet::getTimeAgo($reweet->post_on) ; 
				// $likes_count = weet::countLikes($weet->id) ;
				// $user_like_it = weet::userLikeIt($user_id ,$weet->id);
				// $reweets_count = weet::countReweets($weet->id) ;
				// $user_reweeted_it = weet::userRetweeetedIt($user_id ,$weet->id);
				$qoute = $reweet->reweet_msg;
				$qoq = true; // stand for qoute of qoute
				
				$weet_inner = weet::getReweet($reweet->reweet_id);
				$user_inner_weet = User::getData($weet_inner->user_id) ;
				$timeAgo_inner = weet::getTimeAgo($weet_inner->post_on);
				$inner_qoute = $weet_inner->reweet_msg;
			}
			
		}	

	} else {

		 // when normal weet

		$weet      = weet::getweet($weet_id);
		$user_weet = User::getData($weet->user_id);
		$timeAgo = weet::getTimeAgo($weet->post_on) ;
		

	}
	
?>
<div class="reweet-popup">
<div class="wrap5">
	<div class="reweet-popup-body-wrap">
		<div class="reweet-popup-heading">
			<h3>Reply Post</h3>
			<span><button class="close-reweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
		</div>
		<div class="reweet-popup-input">
			<div class="reweet-popup-input-inner">
				<input  class="reweet-msg" type="text" placeholder="Add Comment.."/>
			</div>
		</div>
		
				
		<div class="grid-weet py-2">
              <div>
                <img
                  src="assets/images/users/<?php echo $user_weet->img; ?>"
                  alt=""
                  class="img-user-weet"
                />
              </div>
  
              <div>
                <p>
                  <strong> <?php echo $user_weet->name ?> </strong>
                  <span class="username-savvorly">@<?php echo $user_weet->username ?> </span>
                  <span class="username-savvorly"><?php echo $timeAgo ?></span>
                </p>
                <p>
				<?php
                  // check if it's qoute or normal weet
                  if ($reweet_comment || $qoq)
                  echo  weet::getweetLinks($qoute);
                  else echo  weet::getweetLinks($weet->status); ?>
				</p>
				
				<?php if ($reweet_comment == false && $qoq == false) { ?>
                <?php if ($weet->img != null) { ?>
                <p class="mt-post-weet">
                  <img
                    src="assets/images/weets/<?php echo $weet->img; ?>"
                    alt=""
                    class="img-post-reweet"
                  />
                </p>
			   <?php } ?>
			   <?php }  else { ?>

				<div  class="mt-post-weet comment-post">

				<div class="grid-weet py-3  ">
				<div>
				<img
				src="assets/images/users/<?php echo $user_inner_weet->img; ?>"
				alt=""
				class="img-user-weet"
				/>
				</div>

				<div>
				<p>
				<strong> <?php echo $user_inner_weet->name ?> </strong>
				<span class="username-savvorly">@<?php echo $user_inner_weet->username ?> </span>
				<span class="username-savvorly"><?php echo $timeAgo_inner ?></span>
				</p>
				<p>
				<?php 
				    if ($qoq)
                    echo $inner_qoute;
                    else  echo  weet::getweetLinks($weet_inner->status); ?>
				</p>
				<?php
				if($qoq == false) {
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
			   

	</div>
</div>


		<div class="reweet-popup-footer"> 
			<div class="reweet-popup-footer-right">
				<button class="comment-it" 
				data-weet="<?php echo $weet_id;?>"
				data-user="<?php echo $user_id;?>"
				data-tmp="<?php echo $reweet_comment; ?>" 
				data-qoq="<?php echo $qoq; ?>" 
			 type="submit"><i class="fas fa-pencil-alt" aria-hidden="true"></i>Reply</button>
			</div>
		</div> 
		

</div>

<!-- Post Comment PopUp ends-->

<?php }  

// Repling to comment popup

if(isset($_POST['showReply']) && !empty($_POST['showReply'])){
	$comment_id   = $_POST['showReply'];
	$user       = User::getData($user_id);
	

	$weet      = weet::getComment($comment_id);
	$user_weet = User::getData($weet->user_id);
	$timeAgo = weet::getTimeAgo($weet->time) ; 

?>
<div class="reweet-popup">
<div class="wrap5">
<div class="reweet-popup-body-wrap">
	<div class="reweet-popup-heading">
		<h3>Reply Comment</h3>
		<span><button class="close-reweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
	</div>
	<div class="reweet-popup-input">
		<div class="reweet-popup-input-inner">
			<input  class="reweet-msg" type="text" placeholder="Add Reply.."/>
		</div>
	</div>
	
			
	<div class="grid-weet py-2">
		  <div>
			<img
			  src="assets/images/users/<?php echo $user_weet->img; ?>"
			  alt=""
			  class="img-user-weet"
			/>
		  </div>

		  <div>
			<p>
			  <strong> <?php echo $user_weet->name ?> </strong>
			  <span class="username-savvorly">@<?php echo $user_weet->username ?> </span>
			  <span class="username-savvorly"><?php echo $timeAgo ?></span>
			</p>
			<p>
			<?php
			  // check if it's qoute or normal weet
			   echo  weet::getweetLinks($weet->comment); ?>
			</p>

</div>
</div>
   




	<div class="reweet-popup-footer"> 
		<div class="reweet-popup-footer-right">
			<button class="reply-it" 
			data-weet="<?php echo $comment_id;?>"
			data-user="<?php echo $user_id;?>"
		 type="submit"><i class="fas fa-pencil-alt" aria-hidden="true"></i>Reply</button>
		</div>
	</div> 
	

</div>

<!-- Reweet PopUp ends-->
<?php }?>


