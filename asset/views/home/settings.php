<div class="server-answer-block">
	<span id="server-answer"></span>
</div>
<div class="icon-block">
	<img src="<?= $user_image ?>" />
</div>
<div class="options-nav nav-form">
	<a onclick="show_form(1)">Замена пароля</a>
	<a onclick="show_form(2)">Замена картинки</a>
</div>
<div class="form-block" id="form-1">
	<form id="ajax_change_password" action="<?= URL; ?>home/password/change">
		<legend>Замена пароля</legend>
		<label for="change-password">Пароль:</label>
		<input type="password" id="change-password" name="password" required></input>
		<label for="change-repassword">Повтор пароля:</label>
		<input type="password" id="change-repassword" name="repassword" required></input>
		<button id="change-button" type="submit">Отправить</button>
	</form>
</div>
<div class="form-block" id="form-2">
	<form action="<?= URL; ?>home/image/change" enctype="multipart/form-data" method="POST">
		<legend>Замена картинки</legend>
		<input type="file" id="change-icon" name="file" required></input>
		<label for="change-icon"><img src="asset/views/home/images/download-icon.png" /><span>Выбирите новую иконку</span></label>
		<button id="change-button" type="submit">Отправить</button>
	</form>
</div>