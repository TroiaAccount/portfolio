//Функция перелистывания форм
function show_form(number_form){
	//удаляем класс активности с сылки
	$(".active-link").removeClass("active-link");
	//добавляем класс активности на нажатую кнопку
	$(".nav-form a:eq(" + (number_form - 1) + ")").addClass("active-link");
	//удаляем класс активности с формы
	$(".active-form").removeClass("active-form");
	//добавляем класс активности на форму
	$(".form-block:eq(" + (number_form - 1) + ")").addClass("active-form");
}

//При загрузке страницы
$( window ).on( "load", function() {
  //Скрываем лоадер
  $('#loader').hide();
  //Показываем контент
  $('#mainwrapper').show(500);
 
});