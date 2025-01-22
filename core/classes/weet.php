<?php 

class weet extends User {
    
    protected static $pdo;
      
      public static function weets($user_id) {
        $stmt = self::connect()->prepare("SELECT * from `posts`
        WHERE user_id = :user_id OR user_id IN (SELECT following_id from `follow` WHERE follower_id = :user_id)
        ORDER BY post_on DESC");
        $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      public static function weetsUser($user_id) {
        $stmt = self::connect()->prepare("SELECT * from `posts`
        WHERE user_id = :user_id
        ORDER BY post_on DESC");
        $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      public static function likedweets($user_id) {
        $stmt = self::connect()->prepare("SELECT * from `posts`
        WHERE id IN (SELECT post_id from `likes` WHERE user_id = :user_id)
        ORDER BY post_on DESC");
        $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      public static function mediaweets($user_id) {
        $stmt = self::connect()->prepare("SELECT * from `posts`
        WHERE id IN (SELECT post_id from `weets` WHERE user_id = :user_id AND img is not null)
        ORDER BY post_on DESC");
        $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      public static function comments($weet_id) {
        $stmt = self::connect()->prepare("SELECT * from `comments`
        WHERE post_id = :weet_id
        ORDER BY time");
        $stmt->bindParam(":weet_id" , $weet_id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
      }

      public static function replies($comment_id) {
        $stmt = self::connect()->prepare("SELECT * from `replies`
        WHERE comment_id = :comment_id
        ORDER BY time");
        $stmt->bindParam(":comment_id" , $comment_id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
      }

      public static function isweet($weet_id){
            
        $stmt = self::connect()->prepare("SELECT * FROM `weets` 
        WHERE `post_id` = :weet_id");
        $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
        $stmt->execute(); 

        if ($stmt->rowCount() > 0) {
            return true;
        } else return false;
    }
    public static function isReweet($weet_id){
            
        $stmt = self::connect()->prepare("SELECT * FROM `reweets` 
        WHERE `post_id` = :weet_id");
        $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
        $stmt->execute(); 

        if ($stmt->rowCount() > 0) {
            return true;
        } else return false;

    }

        public static function getTimeAgo($timestamp){
            date_default_timezone_set("Africa/Cairo");
               
            $time_ago        = strtotime($timestamp);
            $current_time = strtotime(date("Y-m-d H:i:s")); 
            // $current_time    = time();
            $time_difference = $current_time - $time_ago;
            $seconds         = $time_difference;
            
            $minutes = round($seconds / 60); // value 60 is seconds  
            $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
            $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
            $weeks   = round($seconds / 604800); // 7*24*60*60;  
            $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
            $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
                   
            if ($seconds <= 60){
        
            return "just now";
        
            } else if ($minutes <= 60){
        
            if ($minutes == 1){
        
                return "one minute ago";
        
            } else {
        
                return "$minutes minutes ago";
        
            }
        
            } else if ($hours <= 24){
        
            if ($hours == 1){
        
                return "an hour ago";
        
            } else {
        
                return "$hours hrs ago";
        
            }
        
            } else if ($days <= 7){
        
            if ($days == 1){
        
                return "yesterday";
        
            } else {
        
                return "$days days ago";
        
            }
        
            } else if ($weeks <= 4.3){
        
            if ($weeks == 1){
        
                return "a week ago";
        
            } else {
        
                return "$weeks weeks ago";
        
            }
        
            } else if ($months <= 12){
        
            if ($months == 1){
        
                return "a month ago";
        
            } else {
        
                return "$months months ago";
        
            }
        
            } else {
            
            if ($years == 1){
        
                return "one year ago";
        
            } else {
        
                return "$years years ago";
        
            }
            }
        }
        


        public static function getTrendByHash($hashtag){
            $stmt = self::connect()->prepare("SELECT * FROM `trends` 
            WHERE `hashtag` LIKE :hashtag LIMIT 5");
            $stmt->bindValue(":hashtag", $hashtag.'%');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    
        public static function getMention($mension){
            $stmt = self::connect()->prepare("SELECT `id`,`username`,`name`,`img` FROM `users` 
            WHERE `username` LIKE :mension OR `name` LIKE :mension LIMIT 5");
            $stmt->bindValue("mension", $mension.'%');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
    
        }
        public static function HashtagExist($hash){
            
            $stmt = self::connect()->prepare("SELECT * FROM `trends` 
            WHERE `hashtag` = '$hash' ");
            //  $stmt->bindParam(":hashtag", $hash, PDO::PARAM_INT);
            $stmt->execute(); 
    
            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;
    
        }

        public static function addTrend($hashtag){
            preg_match_all("/#+([a-zA-Z0-9_]+)/i", $hashtag, $matches);
            if($matches){
                $result = array_values($matches[1]);
            }
            $sql = "INSERT INTO `trends` (`hashtag`, `created_on`) VALUES (:hashtag, CURRENT_TIMESTAMP)";
            foreach ($result as $trend) { 
                 if (!weet::HashtagExist($trend)) {
                     
                     if($stmt = self::connect()->prepare($sql)){
                         $stmt->execute(array(':hashtag' => $trend));
                     }
                 }
            }
        } 

       
        
        public static function getweetLinks($weet){
            $weet = preg_replace("/(https?:\/\/)([\w]+.)([\w\.]+)/", "<a href='$0' target='_blink'>$0</a>", $weet);
            $weet = preg_replace("/#([\w]+)/", "<a class='hash-weet' href='#'>$0</a>", $weet);		
            $weet = preg_replace("/@([\w]+)/", "<a class='hash-weet' href='http://localhost/savvorly/$1'>$0</a>", $weet);
            return $weet;		
        }
        public static function hashtagAndMentionweet($weet){
            $weet = preg_replace("/(https?:\/\/)([\w]+.)([\w\.]+)/", "<a href='$0' target='_blink'>$0</a>", $weet);
            $weet = preg_replace("/#([\w]+)/", "<a class='hash-weet' href='#'>$0</a>", $weet);		
            $weet = preg_replace("/@([\w]+)/", "<a class='hash-weet' href='#'>$0</a>", $weet);
            return $weet;		
        }
        
        public static function countLikes($post_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(post_id) as count FROM `likes`
            WHERE post_id = :post_id");
            $stmt->bindParam(":post_id" , $post_id , PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_OBJ);
            return $count->count;
        }
        public static function countweets($user_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(user_id) as count FROM `posts`
            WHERE user_id = :user_id");
            $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_OBJ);
            return $count->count;
        }

        public static function countComments($post_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(post_id) as count FROM `comments`
            WHERE post_id = :post_id");
            $stmt->bindParam(":post_id" , $post_id , PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_OBJ);
            return $count->count;
        }
        public static function countReplies($comment_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(comment_id) as count FROM `replies`
            WHERE comment_id = :comment_id");
            $stmt->bindParam(":comment_id" , $comment_id , PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_OBJ);
            return $count->count;
        }

        public static function countReweets($weet_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(*) as count FROM `reweets`
            WHERE (`weet_id` = :weet_id or `reweet_id` = :weet_id)  and reweet_msg is null 
            GROUP BY weet_id , reweet_id");
            $stmt->bindParam(":weet_id" , $weet_id , PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $count = $stmt->fetch(PDO::FETCH_OBJ);
                return $count->count;
            } else return false;
            
        }

        public static function unLike($user_id, $weet_id){
            
            $stmt = self::connect()->prepare("DELETE FROM `likes` 
            WHERE `user_id` = :user_id and `post_id` = :weet_id");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;

        }

        public static function userLikeIt( $user_id ,$weet_id){
            
            $stmt = self::connect()->prepare("SELECT `post_id` , `user_id` FROM `likes` 
            WHERE `user_id` = :user_id and `post_id` = :weet_id");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;

        }
        public static function usersLiked($weet_id){
            
            $stmt = self::connect()->prepare("SELECT `post_id` , `user_id` FROM `likes` 
            WHERE  `post_id` = :weet_id");
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        }

        public static function userRetweeetedIt($user_id ,$weet_id){
            
            $stmt = self::connect()->prepare("SELECT `id` , `user_id` FROM `posts` JOIN `reweets`
            on id = post_id
            WHERE `user_id` = :user_id and (`weet_id` = :weet_id or `reweet_id` = :weet_id)  and reweet_msg is NULL");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;

        } 
        public static function usersRetweeeted($weet_id){
            
            $stmt = self::connect()->prepare("SELECT `id` , `user_id` FROM `posts` JOIN `reweets`
            on id = post_id
            WHERE (`weet_id` = :weet_id or `reweet_id` = :weet_id)  and reweet_msg is NULL");
            // $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        }
        public static function checkReweet($user_id ,$weet_id){
            
            $stmt = self::connect()->prepare("SELECT `id` , `user_id` FROM `posts` JOIN `reweets`
            on id = post_id
            WHERE `user_id` = :user_id and `post_id` = :weet_id  and reweet_msg is NULL");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;

        }


        

        public static function undoReweet($user_id , $weet_id) {
            
            $stmt = self::connect()->prepare("DELETE FROM `posts` 
            WHERE `user_id` = :user_id and `id` = :weet_id
            ");
            // and id not in (SELECT post_id from `reweets` WHERE reweet_msg is not null)
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;
        }

        public static function reweetRealId($weet_id , $user_id) {
            $stmt = self::connect()->prepare("SELECT post_id FROM reweets JOIN posts
            on id = post_id
            WHERE (weet_id = :weet_id or  reweet_id = :weet_id) and `user_id` = :user_id");
            $stmt->bindParam(":weet_id" , $weet_id , PDO::PARAM_STR);
            $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
            $stmt->execute();
            $id = $stmt->fetch(PDO::FETCH_OBJ);
            return $id->post_id;
        }

       
        
        
        public static function likedweetRealId($weet_id) {
            $stmt = self::connect()->prepare("SELECT weet_id FROM reweets 
            WHERE post_id = :weet_id");
            $stmt->bindParam(":weet_id" , $weet_id , PDO::PARAM_STR);
            $stmt->execute();
            $id = $stmt->fetch(PDO::FETCH_OBJ);
            return $id->weet_id;
        }
        
        // public static function getRealId($weet_id) {
        //     $stmt = self::connect()->prepare("SELECT post_id FROM reweets 
        //     WHERE post_id = :weet_id");
        //     $stmt->bindParam(":weet_id" , $weet_id , PDO::PARAM_STR);
        //     $stmt->execute();
        //     $id = $stmt->fetch(PDO::FETCH_OBJ);
        //     return $id->weet_id;
        // }

        public static function getweet($weet_id){
            $stmt = self::connect()->prepare("SELECT * FROM `weets` JOIN `posts` 
            on posts.id = weets.post_id 
            WHERE `post_id` = :weet_id");
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        public static function getComment($weet_id){
            $stmt = self::connect()->prepare("SELECT * FROM `comments` 
            WHERE `id` = :weet_id");
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        public static function getReweet($weet_id){
            $stmt = self::connect()->prepare("SELECT * FROM `reweets` JOIN `posts` 
            on id = post_id 
            WHERE `post_id` = :weet_id");
            $stmt->bindParam(":weet_id", $weet_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } 
        public static function getData($id) {
            $stmt = self::connect()->prepare("SELECT * from `posts` WHERE `id` = :id");
            $stmt->bindParam(":id" , $id , PDO::PARAM_STR);
            $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
     }  

     public static function includeHeader($title) {
        global $weets;
        $weets = $title;
        include 'includes/weets.php';
    }


     

    



        
}