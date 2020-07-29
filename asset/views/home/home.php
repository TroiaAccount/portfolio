<div class="refill-block">
	<p>Ваша почта - <?= $email; // загружаем почту пользователя 
					?></p>
	<p>Ваше имя - <?= $name; // загружаем имя пользователя 
					?></p>
	<p>У вас на балансе - <?= $balance; // загружаем баланс 
							?> рублей</p>
	<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
		<input type="hidden" name="receiver" value="4100111796375982">
		<input type="hidden" name="formcomment" value="Проект «Железный человек»: реактор холодного ядерного синтеза">
		<input type="hidden" name="short-dest" value="Проект «Железный человек»: реактор холодного ядерного синтеза">
		<input type="hidden" name="label" value="<?=$_SESSION['token']; ?>">
		<input type="hidden" name="targets" value="Перевод">
		<input type="hidden" name="quickpay-form" value="donate">
		<input type="text" name="sum" value="10" data-type="number">
		<input type="hidden" name="comment" value="Хотелось бы получить дистанционное управление.">
		<label><input type="radio" name="paymentType" value="PC">Яндекс.Деньгами</label>
		<label><input type="radio" name="paymentType" value="AC">Банковской картой</label>
		<input type="submit" value="Перевести"></form>
</div>