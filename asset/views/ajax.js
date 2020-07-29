$( window ).on( "load", function() {

  function AjaxForm_reg(ajax_form, url) {
    $.ajax({
        url:     url, //url обработчика
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),
        success: function(response) {
        let result = $.parseJSON(response);

        if(result.status){
          $("#server-answer").html("Пользователь успешно создан, вам на почту выслано письмо с ссылкой");
        }else{
          $("#server-answer").html("Ошибка!:" + result.error);
        }

        $(".server-answer-block").fadeIn(1000);

        setTimeout(function(){
          $(".server-answer-block").fadeOut(1000);
        },3000);
      },
      error: function(response) {
        $("html").html("Ошибка. Данные не отправлены.");
      }
    });
  }

  function AjaxForm_recov(ajax_form, url) {
    $.ajax({
        url:     url, //url обработчика
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),
        success: function(response) {
        let result = $.parseJSON(response);

        if(result.status){
          $("#server-answer").html("Вам на почту выслано письмо с ссылкой для сброса пароля");
        }else{
          $("#server-answer").html("Ошибка!:" + result.error);
        }

        $(".server-answer-block").fadeIn(1000);

        setTimeout(function(){
          $(".server-answer-block").fadeOut(1000);
        },3000);
      },
      error: function(response) {
        $("html").html("Ошибка. Данные не отправлены.");
      }
    });
  }

  function AjaxForm_auth(ajax_form, url) {
    $.ajax({
        url:     url, //url обработчика
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),
        success: function(response) {
        let result = $.parseJSON(response);

        if(result.status){
          $("#server-answer").html("Успешный вход");
        }else{
          $("#server-answer").html("Ошибка!:" + result.error);
        }

        $(".server-answer-block").fadeIn(1000);

        setTimeout(function(){
          $(".server-answer-block").fadeOut(1000);
        },3000);
      },
      error: function(response) {
        $("html").html("Ошибка. Данные не отправлены.");
      }
    });
  }

  //Ставим обработчики на все кнопки форм

  $("#reg-button").click(
    function(){
      AjaxForm_reg("ajax_form_reg", $("#ajax_form_reg").attr('action'));
      return false; 
  });

  $("#recov-button").click(
    function(){
      AjaxForm_recov("ajax_form_recov", $("#ajax_form_recov").attr('action'));
      return false; 
  });

  $("#auth-button").click(
    function(){
      AjaxForm_auth("ajax_form_auth", $("#ajax_form_auth").attr('action'));
      return false; 
  });
});