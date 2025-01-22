<?php 
	include '../init.php';
	$user_id = $_SESSION['user_id'];
	date_default_timezone_set("Africa/Cairo");
	if(isset($_POST['qoute']) && !empty($_POST['qoute'])){
		$weet_id  = $_POST['qoute'];
		$get_id    = $_POST['user_id'];
		$flag = $_POST['isQoute'];
		$qoq = $_POST['qoq'];
		$comment   = User::checkInput($_POST['comment']);

		$reweet = weet::getReweet($weet_id);
		

		// for notification
		if(isset($reweet->user_id))
		$for_user = $reweet->user_id;
		else $for_user = weet::getweet($weet_id)->user_id;	

		if($for_user != $user_id) {
			$data_notify = [
              'notify_for' => $for_user ,
			  'notify_from' => $user_id ,
			  'type' => 'qoute' ,
			   'time' => date("Y-m-d H:i:s") ,
			   'count' => '0' , 
			   'status' => '0'
			];
				
		} 


		// check if user reweeted it to avoid double reweet from qoute btn
		if ($flag == false && $qoq == false && weet::isReweet($weet_id)) {
			$flag_reweeted = weet::userRetweeetedIt($user_id,$reweet->weet_id);
		} else $flag_reweeted = weet::userRetweeetedIt($user_id,$weet_id);
		

        //  if(!$flag_reweeted) {
			date_default_timezone_set("Africa/Cairo");

			$data = [
				'user_id' => $_SESSION['user_id'] , 
				'post_on' => date("Y-m-d H:i:s") ,
			];
			// create function can handle with all tables and return last inserted id
			$post_id =   User::create('posts' , $data);
			// qoq is check if this weet qoute of qoute or not
			if ($comment != '') {
	
			// if flag true then the reweeted post is qoute weet and the fk is reweet_id
					if ($flag && !$qoq) {
						if(weet::isReweet($weet_id)) {

							$data_weet = [
								'post_id' => $post_id ,
								'reweet_msg' => $comment , 
								'reweet_id' => $reweet->post_id ,
								'weet_id' => null
							];
							// for notification
								if($for_user != $user_id) 
								$data_notify['target']= $post_id;
							
						} else {
                            $data_weet = [
								'post_id' => $post_id ,
								'reweet_msg' => $comment , 
								'reweet_id' => $weet_id ,
								'weet_id' => null
							];
							// for notification
							if($for_user != $user_id) 
							$$data_notify['target']= $post_id;
						}
						
					} else if ($qoq) {

							if ($reweet->reweet_msg == null ) {
							$data_weet = [
								'post_id' => $post_id ,
								'reweet_msg' => $comment , 
								'reweet_id' => $reweet->post_id ,
								'weet_id' => null
							];
							// for notification
							if($for_user != $user_id) 
							$data_notify['target']= $post_id;

						}	else {
							$data_weet = [
								'post_id' => $post_id ,
								'reweet_msg' => $comment , 
								'reweet_id' => $weet_id ,
								'weet_id' => null
							];
							// for notification
							if($for_user != $user_id) 
							$data_notify['target']= $post_id;
						}
	
	
					} else {
							if(weet::isReweet($weet_id)) {
								$data_weet = [
									'post_id' => $post_id ,
									'reweet_msg' => $comment , 
									'weet_id' => $reweet->weet_id ,
									'reweet_id' => null
								];
								// for notification
							if($for_user != $user_id) 
							$data_notify['target']= $post_id;
							} else {
								$data_weet = [
									'post_id' => $post_id ,
									'reweet_msg' => $comment , 
									'weet_id' => $weet_id ,
									'reweet_id' => null
								]; 
								// for notification
							    if($for_user != $user_id) 
								$data_notify['target']= $post_id;
							}
					}
		} else if ($comment == '') {
	          
			 $data_notify['type'] = 'reweet';

			if ($flag) {
				if(weet::isReweet($weet_id)) {
					$data_weet = [
						'post_id' => $post_id ,
						'reweet_msg' => null , 
						'reweet_id' => $reweet->post_id,
						'weet_id' => null
					];
					// for notification
				if($for_user != $user_id) 
				$data_notify['target']=$reweet->post_id;
				} else {
					$data_weet = [
						'post_id' => $post_id ,
						'reweet_msg' => null , 
						'reweet_id' => $weet_id,
						'weet_id' => null
					];
					// for notification
					if($for_user != $user_id) 
					$data_notify['target']= $weet_id;
				}
			} else if ($qoq) {

	            if ($reweet->reweet_msg == null ) {
				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'reweet_id' => $reweet->post_id ,
					'weet_id' => null
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']=$reweet->post_id;

			   } else {
				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'reweet_id' => $weet_id ,
					'weet_id' => null
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $weet_id;

			   } 
	
	
			} else {
	
				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'weet_id' => $weet_id,
					'reweet_id' => null
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $weet_id;
			}
	
	
	
	
		}
			User::create('reweets' , $data_weet);

			  // for notification
		if($for_user != $user_id) 
		weet::create('notifications' , $data_notify);

		//  }
		
		
		// echo `<div class="tmp d-none">
        //      `+ weet::countReweets($weet_id) +`            
		// </div>` ;

	}
	if(isset($_POST['reweet']) && !empty($_POST['reweet'])){
		$weet_id  = $_POST['reweet'];
		$get_id    = $_POST['user_id'];
		$flag = $_POST['isQoute'];
		$qoq = $_POST['qoq'];
		$reweet = weet::getReweet($weet_id);

		// for notification
		if(isset($reweet->user_id))
		$for_user = $reweet->user_id;
		else $for_user = weet::getweet($weet_id)->user_id;	

		if($for_user != $user_id) {
			$data_notify = [
              'notify_for' => $for_user ,
			  'notify_from' => $user_id ,
			  'type' => 'reweet' ,
			   'time' => date("Y-m-d H:i:s") ,
			   'count' => '0' , 
			   'status' => '0'
			];
				
		} 

		date_default_timezone_set("Africa/Cairo");

        $data = [
            'user_id' => $_SESSION['user_id'] , 
            'post_on' => date("Y-m-d H:i:s") ,
        ];
        // create function can handle with all tables and return last inserted id
		$post_id =   User::create('posts' , $data);

		 // if flag true then the reweeted post is qoute weet and the fk is reweet_id
		 if ($flag) {
				if(weet::isReweet($weet_id)) {
				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'reweet_id' => $reweet->post_id,
					'weet_id' => null
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $reweet->post_id;

			} else {
				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'reweet_id' => $weet_id,
					'weet_id' => null
				];
					// for notification
					if($for_user != $user_id) 
					$data_notify['target']=  $weet_id;

			}
		} else if ($qoq) {

			if(weet::isReweet($weet_id) && $reweet->reweet_msg == null) {
				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'reweet_id' => $reweet->post_id,
					'weet_id' => null
				];
					// for notification
					if($for_user != $user_id) 
					$data_notify['target']= $reweet->post_id;
			} else {
				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'reweet_id' => $weet_id,
					'weet_id' => null
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']=  $weet_id;
			}


		} else {
			
			if (weet::isReweet($weet_id)) {
				   
				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'weet_id' => $reweet->weet_id,
					'reweet_id' => null
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $reweet->weet_id;

			} else {

				$data_weet = [
					'post_id' => $post_id ,
					'reweet_msg' => null , 
					'weet_id' => $weet_id,
					'reweet_id' => null
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $weet_id;
			}
		}

		User::create('reweets' , $data_weet);
		
	    // for notification
		if($for_user != $user_id) 
		    weet::create('notifications' , $data_notify);
       
		
		echo `<div class="tmp d-none">
             `+ weet::countReweets($weet_id) +`            
		</div>` ;


	}
	if(isset($_POST['unreweet']) && !empty($_POST['unreweet'])){

        $weet_id  = $_POST['unreweet'];
		$user_id    = $_POST['user_id'];
        
		$reweet = weet::getReweet($weet_id);

		// for notification
		if(isset($reweet->weet_id)) {
		$for_user = weet::getweet($reweet->weet_id)->user_id; 
		$target = $reweet->weet_id;
	   } else {
		   $for_user = weet::getReweet($reweet->reweet_id)->user_id;	
	       $target = $reweet->reweet_id;
	   }
	
	
		if($for_user != $user_id) {
			$data = [
              'notify_for' => $for_user ,
			  'notify_from' => $user_id ,
			  'target' => $target , 
			  'type' => "'reweet'" ,
			];
	        
			// var_dump($data);
			// die();

			weet::delete('notifications' , $data);
			
		} 
		weet::undoReweet($user_id , $weet_id );
		
		echo `<div class="tmp d-none">
             `+ weet::countReweets($weet_id) +`            
		</div>` ;

	}
	if(isset($_POST['option']) && !empty($_POST['option'])){ 
		$weet_id  = $_POST['option'];
		$get_id    = $_POST['user_id'];
		$user_reweeted_it = $_POST['reweeted'];
		$reweet_sign = $_POST['sign'];
		$reweet_comment = $_POST['tmp'];
		$qoq = $_POST['qoq'];
		if(isset($_POST['status']))
		 $status = $_POST['status'];
		 else $status = false;

		$flaga = false;
		if($reweet_sign && $user_reweeted_it) {
		$reweeted_user = weet::getReweet($weet_id); 
		    	if ($reweeted_user->user_id != $user_id) {
                       $flaga = true;
		     	} 

			    
			        
	    }
        // $weet_id_reweeted = weet::likedweetRealId($weet_id);
		
		// if ($user_reweeted_it && !$reweet_sign) {
		// 	$reweet = weet::getReweet($weet_id);
		// 	$user_reweeted_itt =weet::userRetweeetedIt($user_id , $reweet->id);
		// } else {
			
		// 	$user_reweeted_itt =$user_reweeted_it;
		// }
	    //   $user_reweeted_it = weet::checkReweet($user_id , $weet_id);
		
		// $reweet = weet::getReweet($weet_id);
		// $user_reweeted_itt = weet::userRetweeetedIt($user_id ,$weet_id);
	?>

                    <div class="reweet-div">
							<a href="#" 
							class="<?=$user_reweeted_it ? 'reweeted-i' : 'reweet-i' ?>"
							data-user="<?php echo $user_id; ?>"
							data-weet="<?php 
							if(($user_reweeted_it && !$reweet_sign) || $flaga) {
								if($flaga == false)
							       echo weet::reweetRealId($weet_id ,$user_id);
						     	else {
									if($reweeted_user->weet_id != null)
										echo weet::reweetRealId($reweeted_user->weet_id ,$user_id);
									else echo weet::reweetRealId($reweeted_user->reweet_id ,$user_id);
								 } 
							} else echo $weet_id;  ?>"
							 data-qoq="<?php echo $qoq; ?>"
							 data-status="<?php echo $status; ?>"
							 >  
								<li ><i class="fas fa-reweet icon"></i> 
								<span class="option-text"><?php if($user_reweeted_it) echo 'Undo';  ?>
								Repost</span></li>
							</a>
							<a href="#"
							class="qoute"
							data-user="<?php echo $get_id; ?>"
							data-weet="<?php 
							$reweet = weet::getReweet($weet_id);
							// if(weet::isReweet($weet_id) && $reweet->reweet_msg != null)
							// echo $reweet->weet_id;
							// else
							 echo $weet_id; ?>"> 
								 <li><i class="fas fa-pencil-alt icon"></i> 
								 <span class="option-text"> Review</span></li>
							</a>
                    </div>

<?php	} 

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
			<h3>Review Post</h3>
			<span><button class="close-reweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
		</div>
		<div class="reweet-popup-input">
			<div class="reweet-popup-input-inner">
				<input  class="reweet-msg" type="text" placeholder="Add Review"/>
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
				<button class="qoute-it" 
				data-weet="<?php echo $weet_id;?>"
				data-user="<?php echo $user_id;?>"
				data-tmp="<?php echo $reweet_comment; ?>" 
				data-qoq="<?php echo $qoq; ?>" 
			 type="submit"><i class="fas fa-pencil-alt" aria-hidden="true"></i>Review</button>
			</div>
		</div> 
		

</div>

<!-- Reweet PopUp ends-->
<?php }?>
