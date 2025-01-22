$(function(){
	

    $(document).on('click','.reweets-u', function(){
        var weet_id    = $(this).data('weet');
      
        
    //    console.log(weet_id);
    
        $.post('core/ajax/users.php', {reweetby:weet_id}, function(data){
            $('.popupUsers').html(data);
             
            $('.close-reweet-popup').click(function(){
                $('.reweet-popup').hide();
            })
            $(document).click(function(e){
				if( $(e.target).closest('.reweet-popup-body-wrap').length > 0 ) {
					return false;
				}
			    
				$('.reweet-popup').hide();
			})
        });
    });

    $(document).on('click','.likes-u', function(){
        var weet_id    = $(this).data('weet');
      
        
    //    console.log(weet_id);
    
        $.post('core/ajax/users.php', {likeby:weet_id}, function(data){
            $('.popupUsers').html(data);
             
            $('.close-reweet-popup').click(function(){
                $('.reweet-popup').hide();
            })
            $(document).click(function(e){
				if( $(e.target).closest('.reweet-popup-body-wrap').length > 0 ) {
					return false;
				}
			    
				$('.reweet-popup').hide();
			})
        });
    });

    $(document).on('click','.count-following-i', function(){
        var user_id    = $(this).data('follow');
      
       

    
        $.post('core/ajax/users.php', {following:user_id}, function(data){
            $('.popupUsers').html(data);
             
            $('.close-reweet-popup').click(function(){
                $('.reweet-popup').hide();
            })
            $(document).click(function(e){
				if( $(e.target).closest('.reweet-popup-body-wrap').length > 0 ) {
					return false;
				}
			    
				$('.reweet-popup').hide();
			})
        });
    });

    $(document).on('click','.count-followers-i', function(){
        var user_id    = $(this).data('follow');
      
        
  
    
        $.post('core/ajax/users.php', {follower:user_id}, function(data){
            $('.popupUsers').html(data);
             
            $('.close-reweet-popup').click(function(){
                $('.reweet-popup').hide();
            })
            $(document).click(function(e){
				if( $(e.target).closest('.reweet-popup-body-wrap').length > 0 ) {
					return false;
				}
			    
				$('.reweet-popup').hide();
			})
        });
    });






$(document).on('click','.reply', function(){
    var weet_id    = $(this).data('weet');
    var user_id     = $(this).data('user');
    $counter        = $(this).find(".likes-count");
    $count          = $counter.text();
    $button         = $(this);
    
    
   console.log(weet_id);
   console.log(user_id);
    $.post('core/ajax/comment.php', {showReply:weet_id,user_id:user_id}, function(data){
        $('.popupComment').html(data);
         
        $('.close-reweet-popup').click(function(){
            $('.reweet-popup').hide();
        })
    });
});




});