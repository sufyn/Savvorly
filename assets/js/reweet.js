$(function () {
  $(document).on("click", ".option", function (e) {
    var weet_id = $(this).data("weet");
    var user_id = $(this).data("user");
    var reweeted_it = $(this).data("reweeted");
    var status = $(this).data("status");

    $counter = $(this).find(".likes-count");
    $count = $counter.text();
    $button = $(this);
    $op = $(this).next();
    // $op = $(this).find('.options');
    var flag = $(this).data("tmp");
    var sign = $(this).data("sign");
    var qoq = $(this).data("qoq");

    $.post(
      "core/ajax/reweet.php",
      {
        option: weet_id,
        user_id: user_id,
        reweeted: reweeted_it,
        sign: sign,
        tmp: flag,
        qoq: qoq,
        status: status,
      },
      function (data) {
        $op.html(data);

        $(document).click(function (e) {
          if ($(e.target).closest(".options").length > 0) {
            return false;
          }

          $(".reweet-div").hide();
        });
      }
    );

    $(document).one("click", ".reweet-i", function (event) {
      var weet_id = $(this).data("weet");
      var user_id = $(this).data("user");
      $c = $(this);

      var qoq = $(this).data("qoq");
      // event.stopPropagation();
      event.stopImmediatePropagation();

      $.post(
        "core/ajax/reweet.php",
        { reweet: weet_id, user_id: user_id, isQoute: flag, qoq: qoq },
        function (data) {
          // $('.popupweet').html(data);

          $counter.text(data);
          $button.removeClass("reweet").addClass("reweeted");
          $c.removeClass("reweet-i").addClass("reweeted-i");
          $(".reweet-div").hide();

          // $.ajax({
          // 	url: 'http://localhost/savvorly/home.php',
          // 	success: function(data) {

          // 		window.location.reload(); // This is not jQuery but simple plain ol' JS

          // 	}
          //   });

          location.reload();
        }
      );
    });

    $(document).one("click", ".reweeted-i", function (event) {
      var weet_id = $(this).data("weet");
      var user_id = $(this).data("user");
      var status = $(this).data("status");

      $c = $(this);

      event.stopImmediatePropagation();
      console.log(weet_id);
      $.post(
        "core/ajax/reweet.php",
        { unreweet: weet_id, user_id: user_id },
        function (data) {
          //  if (data == 0)
          //   $counter.text('');
          // else
          $counter.text(data);
          $button.removeClass("reweeted").addClass("reweet");
          $c.removeClass("reweeted-i").addClass("reweet-i");

          $(".reweet-div").hide();
          if (!status) location.reload();
          else history.go(-1);
        }
      );
    });

    $(document).on("click", ".qoute", function () {
      var weet_id = $(this).data("weet");
      var user_id = $(this).data("user");
      $counter = $(this).find(".likes-count");
      $count = $counter.text();
      $button = $(this);

      // console.log(weet_id);
      // console.log($reweeted_it);
      // console.log($sign);
      $.post(
        "core/ajax/reweet.php",
        { showPopup: weet_id, user_id: user_id },
        function (data) {
          $(".popupweet").html(data);

          $(".close-reweet-popup").click(function () {
            $(".reweet-popup").hide();
          });
        }
      );
    });
  });

  $(document).one("click", ".qoute-it", function (event) {
    $(".reweet-popup").addClass("active");
    var weet_id = $(this).data("weet");
    var user_id = $(this).data("user");
    var flag = $(this).data("tmp");
    var qoq = $(this).data("qoq");

    event.stopImmediatePropagation();

    // tricky hint each function to select one class only
    var comment;
    $(".reweet-msg").each(function () {
      comment = $(this).val();
    });

    console.log(weet_id);
    console.log(user_id);
    console.log(comment);

    $.post(
      "core/ajax/reweet.php",
      {
        qoute: weet_id,
        user_id: user_id,
        comment: comment,
        isQoute: flag,
        qoq: qoq,
      },
      function (data) {
        $(".reweet-popup").hide();
        location.reload();

        // $counter.text(data);
        // $button.removeClass('reweet').addClass('reweeted');
      }
    );
  });
});
