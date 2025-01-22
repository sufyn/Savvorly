$(function () {
  $(document).on("click", ".like-btn", function () {
    var weet_id = $(this).data("weet");
    var user_id = $(this).data("user");
    var counter = $(this).find(".likes-count");
    var count = counter.text();
    var button = $(this);

    //     $.post('http://localhost/savvorly/core/ajax/like.php', {like:weet_id, user_id:user_id}, function(){
    // 	counter.show();
    // 	button.addClass('unlike-btn');
    // 	button.removeClass('like-btn');
    // 	count++;
    // 	counter.text(count);
    // 	button.find('.fa-heart').addClass('fas');
    //     button.find('.fa-heart').removeClass('far');
    //     button.find('.fa-heart').removeClass('mt-icon-reaction');
    //  });

    $.ajax({
      type: "POST",
      url: "core/ajax/like.php",
      data: { like: weet_id, user_id: user_id },
      cache: false,
      success: function (data) {
        // var result =   $('.tmp').html();

        counter.text(data);
        button.addClass("unlike-btn");
        button.removeClass("like-btn");

        button.find(".fa-heart").addClass("fas");
        button.find(".fa-heart").removeClass("far");
        button.find(".fa-heart").removeClass("mt-icon-reaction");
      },
    });
  });

  $(document).on("click", ".unlike-btn", function () {
    var weet_id = $(this).data("weet");
    var user_id = $(this).data("user");
    var counter = $(this).find(".likes-count");
    var count = counter.text();
    var button = $(this);

    // $.post('http://localhost/savvorly/core/ajax/like.php', {unlike:weet_id, user_id:user_id}, function(){
    // 	counter.show();
    // 	button.addClass('like-btn');
    // 	button.removeClass('unlike-btn');
    // 	count--;
    // 	if(count === 0){
    // 		counter.hide();
    // 	}else{
    // 	  counter.text(count);
    // 	}
    // 	  counter.text(count);
    //     button.find('.fa-heart').addClass('far');
    //     button.find('.fa-heart').addClass('mt-icon-reaction');
    // 	button.find('.fa-heart').removeClass('fas');
    // });
    $.ajax({
      type: "POST",
      url: "core/ajax/like.php",
      data: { unlike: weet_id, user_id: user_id },
      cache: false,
      success: function (data) {
        // var result =   $('.tmp').html();
        if (data == 0) counter.text("");
        else counter.text(data);

        button.addClass("like-btn");
        button.removeClass("unlike-btn");

        button.find(".fa-heart").addClass("far");
        button.find(".fa-heart").addClass("mt-icon-reaction");
        button.find(".fa-heart").removeClass("fas");
      },
    });
  });
});
