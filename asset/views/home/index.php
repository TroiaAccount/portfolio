<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width">
		<title>Личный кабинет</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<link href="asset/views/home/css/style.css" rel="stylesheet">
		<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
        <script defer type="text/javascript" src="asset/views/home/js/script.js"></script>
        <script type="text/javascript" src="asset/views/home/js/ajax.js"></script>
	</head>

	<body>
		<div id="mainwrapper">
			<div  id="loader"><div></div></div>

			<header>
				<div class="header-panel">
					<h1><a href="<?= URL; // указываем ссылку ?>home">Главная страница</a></h1>
					<a class="head-option" href="<?= URL; // указываем ссылку ?>settings">
						<img src="asset/views/home/images/option-img.png" />
					</a>
				</div>
			</header>
			<main id="content">
                <?php
                    $page = $_SERVER['REQUEST_URI']; // получаем путь после домена
                    $page = str_replace("/", "", $page); // убираем лишнее)
                    $page = trim($page); // убираем пробелы по бокам
                    if(file_exists("asset/views/home/" .  $page . ".php")){ // смотрим есть ли такой файл
                        include_once $page . ".php"; // если есть, подключаем его
                    }
                ?>
            </main>
		</div>
	</body>
</html>