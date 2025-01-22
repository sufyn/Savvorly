<?php 
include '../core/init.php';
require_once '../core/classes/validation/Validator.php';
require_once '../core/classes/image.php';

use validation\Validator;

if (User::checkLogIn() === false) 
header('location: index.php'); 

if (isset($_POST['weet'])) {

    $status =  User::checkInput($_POST['status']) ;

    $img = $_FILES['weet_img'];
    
    if ($_POST['status'] == '' && $img['name'] == '' ) {
    $_SESSION['errors_weet'] = ['status or image are required'];
    header('location: ../home.php'); 
    die();
    }

    $v = new Validator;
    $v->rules('status' , $status , ['string' , 'max:14']);
    if ($img['name'] != '') 
     $v->rules('image' , $img , ['image']);

    $errors = $v->errors;
    
    if ($errors == []) { 
        
        if ($img['name'] != '') {
        $image = new Image($img , "weet"); 
        $weetImg = $image->new_name ;
       
        } else $weetImg = null;
        
       
        
        date_default_timezone_set("Africa/Cairo");
        $data = [
            'user_id' => $_SESSION['user_id'] , 
            'post_on' => date("Y-m-d H:i:s") ,
        ];
        // create function can handle with all tables and return last inserted id
        $post_id =   User::create('posts' , $data);
        
        $data_weet = [
            'post_id' => $post_id ,
            'status' => $status , 
            'img' => $weetImg
        ];
        User::create('weets' , $data_weet);
        if ($img['name'] != '') {
        $image->upload(); }
        
        // notify users if mention!

        preg_match_all("/@+([a-zA-Z0-9_]+)/i", $status, $mention);
        $user_id = $_SESSION['user_id'];
        foreach($mention[1] as $men) {
            $id = User::getIdByUsername($men);
            if($id != $user_id ) {
                $data_notify = [
                    'notify_for' => $id ,
                    'notify_from' => $user_id ,
                    'target' => $post_id , 
                    'type' => 'mention' ,
                     'time' => date("Y-m-d H:i:s") ,
                     'count' => '0' , 
                     'status' => '0'
                  ];
          
                  weet::create('notifications' , $data_notify);
                
            } 
            
        }
        // end notification
        //  add trends to database
        preg_match_all("/#+([a-zA-Z0-9_]+)/i", $status, $hashtag);
       
        if(!empty($hashtag) ){ 

            weet::addTrend($status);
        }
        // end add trend
       
        header('location: ../home.php');
    } else {
        $_SESSION['errors_weet'] = $errors;
        header('location: ../home.php');
    }   
} else header('location: ../home.php');
?>