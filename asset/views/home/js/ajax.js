$( window ).on( "load", function() {

  function AjaxForm_change_password(ajax_form, url) {
    $.ajax({
        url:     url, //url обработчика
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),
        success: function(response) {
        let result = $.parseJSON(response);

        if(result.status){
          $("#server-answer").html("Пароль успешно изменён");
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

  $("#change-button").click(
    function(){
      AjaxForm_change_password("ajax_change_password", $("#ajax_change_password").attr('action'));
      return false; 
  });
});