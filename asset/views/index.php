<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width">
	<title>Формы</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="asset/views/style.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script defer type="text/javascript" src="asset/views/script.js"></script>
	<script type="text/javascript" src="asset/views/ajax.js"></script>
</head>

<body>
	<div id="mainwrapper">
		<div id="loader">
			<div>sadf</div>
		</div>

		<main id="content">
			<div class="nav-form">
				<a onclick="show_form(1)">Регистрация</a>
				<a onclick="show_form(2)">Восстановление</a>
				<a onclick="show_form(3)" class="active-link">Авторизация</a>
			</div>
			<div class="server-answer-block">
				<span id="server-answer"></span>
			</div>
			<div class="form-block" id="form-1">
				<form id="ajax_form_reg" action="<?php echo URL; ?>register" method="POST">
					<legend>Регистрация</legend>
					<label for="reg-name">Имя:</label>
					<input type="text" id="reg-name" name="name" required></input>
					<label for="reg-post">Почта:</label>
					<input type="text" id="reg-post" name="email" required></input>
					<label for="reg-password">Пароль:</label>
					<input type="password" id="reg-password" name="password" required></input>
					<label for="reg-repassword">Повтор пароля:</label>
					<input type="password" id="reg-repassword" name="repassword" required></input>
					<button id="reg-button" type="submit">Отправить</button>
				</form>
			</div>
			<div class="form-block" id="form-2">
				<form id="ajax_form_recov" action="<?php echo URL; ?>recovery">
					<legend>Восстановление пароля</legend>
					<label for="recov-post">Почта:</label>
					<input type="text" id="recov-post" name="email" required></input>
					<button id="recov-button" type="submit">Отправить</button>
				</form>
			</div>
			<div class="form-block active-form" id="form-3">
				<form id="ajax_form_auth" action="<?php echo URL; ?>login" method="POST">
					<label>
						<legend>Вход</legend>
						<label for="auth-post">Почта:</label>
						<input type="text" id="auth-post" name="email" required></input>
						<label for="auth-password">Пароль:</label>
						<input type="password" id="auth-password" name="password" required></input>
						<button id="auth-button" type="submit">Отправить</button>
					</label>
				</form>
			</div>
		</main>
	</div>
</body>

</html>