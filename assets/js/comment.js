$(function(){
	

		$(document).on('click','.comment', function(){
			var weet_id    = $(this).data('weet');
			var user_id     = $(this).data('user');
			$counter        = $(this).find(".likes-count");
			$count          = $counter.text();
			$button         = $(this);
			
			
	   	console.log(weet_id);
           console.log(user_id);
			$.post('core/ajax/comment.php', {showPopup:weet_id,user_id:user_id}, function(data){
				$('.popupComment').html(data);
				 
				$('.close-reweet-popup').click(function(){
					$('.reweet-popup').hide();
				})
			});
		});



	$(document).one('click', '.comment-it', function(event){
		$('.reweet-popup').addClass('active');
		var weet_id   = $(this).data('weet');
		var user_id    = $(this).data('user');
		// var flag   = $(this).data('tmp');
		// var qoq   = $(this).data('qoq');
		
	

		// tricky hint each function to select one class only
       var comment ;
		$('.reweet-msg').each(function(){
		comment =	$(this).val()
		});
        // event.stopImmediatePropagation();
		
		// console.log(weet_id);
		// console.log(user_id);
        // console.log(comment);
	
	    $.post('core/ajax/comment.php', {qoute:weet_id,user_id:user_id,comment:comment}, function(data){
			
		   $('.reweet-popup').hide();
           $('.comments').html(data);

	   location.reload();
	   
	    	// $counter.text(data);
	    	// $button.removeClass('reweet').addClass('reweeted');
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



$(document).one('click', '.reply-it', function(event){
    $('.reweet-popup').addClass('active');
    var comment_id   = $(this).data('weet');
    var user_id    = $(this).data('user');
    // var flag   = $(this).data('tmp');
    // var qoq   = $(this).data('qoq');
    


    // tricky hint each function to select one class only
   var comment ;
    $('.reweet-msg').each(function(){
    comment =	$(this).val()
    });
    // event.stopImmediatePropagation();
    
    // console.log(comment_id);
    // console.log(user_id);
    // console.log(comment);

    $.post('core/ajax/comment.php', {reply:comment_id,user_id:user_id,comment:comment}, function(data){
        
       $('.reweet-popup').hide();
       $('.comments').html(data);

   location.reload();
   
        // $counter.text(data);
        // $button.removeClass('reweet').addClass('reweeted');
    });

});
});