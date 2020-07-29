<?php

function check_users($sql)
{
    $token = $_SESSION['token']; // получаем токен из сессии
    if ($token == "") { // смотрим, есть ли токен
        $_SESSION = array(); // если нет, очищаем полностью сессию(на всякий случай)
        header('Location: ' . URL); // редиректимся на главную страницу 
        exit;
    }

    $select = mysqli_fetch_assoc(mysqli_query($sql, "SELECT * FROM logins WHERE token LIKE '" . $token . "'")); // если есть токен, узнаем id пользователя по токену в БД
    if ($select['user_id'] == "") { // смотрим на корректность токена
        $_SESSION = array(); // если токен не верный, очищаем полностью сессию(на всякий случай)
        header('Location: ' . URL); // редиректимся на главную страницу 
        exit;
    }

    return $select; // если всё хорошо, возвращаем строчку из базы данных
}

function home($sql)
{
    $select = check_users($sql); // провоеряем пользователя

    $select_user_info = mysqli_fetch_assoc(mysqli_query($sql, "SELECT * FROM users WHERE id = '" . $select['user_id'] . "'")); // получаем информацию по id пользователя
    $name = $select_user_info['name']; //записываем имя в отдельную переменную
    $email = $select_user_info['email']; // записываем почту в отдельную переменную
    $balance = $select_user_info['balance']; //записываем баланс в отдельную переменную
    $user_image = $select_user_info['user_image']; // записываем ссылку на картинку в отдельную переменную

    include_once "asset/views/home/index.php"; // подключаем основу вёрстки личного кабинета
}

function nitification($sql)
{
    $secret_key = 'fsagfdsgdsfgdsfhgfhgdfhdgfh'; // секретное слово янлекс деньги



    $sha1 = sha1($_POST['notification_type'] . '&' . $_POST['operation_id'] . '&' . $_POST['amount'] . '&' . $_POST['currency'] . '&' . $_POST['datetime'] . '&' . $_POST['sender'] . '&' . $_POST['codepro'] . '&' . $secret_key . '&' . $_POST['label']); // создаём sha1 ключ для проверки правильности платежа
    if ($sha1 != $_POST['sha1_hash']) {
        // тут содержится код на случай, если верификация не пройдена
        exit();
    }

    // тут код на случай, если проверка прошла успешно
    $select = mysqli_fetch_assoc(mysqli_query($sql, "SELECT user_id FROM logins WHERE token LIKE '" . $_POST['label'] . "'")); // забираем наш токен из label и ищем его в БД
    if ($select['user_id'] != "") { // если нашли, то 
        $balance = mysqli_fetch_assoc(mysqli_query($sql, "SELECT balance FROM users WHERE id = '" . $select['user_id'] . "'")); // получаем весь баланс
        $balance = $balance['balance']; // записываем баланс в переменную 
        $balance = $balance + $_POST['amount']; //  складываем сумму которую вложили
        mysqli_query($sql, "UPDATE users SET balance = '" . $balance . "' WHERE id = '" . $select['user_id'] . "'"); // записываем в БД
    }

    exit();
}

function change($sql)
{
    $select = check_users($sql); // првоеряем пользователя
    $result = ""; // создаём пустую переменную для результата

    if ($_POST['password'] != "" && $_POST['repassword'] != "") { // проверяем на наличие запроса 
        if ($_POST['password'] == $_POST['repassword']) { // сверяем пароли
            $password = md5($_POST['password']); // если всё хорошо, загоняем пароль в md5
            mysqli_query($sql, "UPDATE users SET password = '" . $password . "' WHERE id = '" . $select['user_id'] . "'"); // обновляем ячейку в БД
            mysqli_query($sql, "DELETE FROM logins WHERE user_id LIKE '" . $select['user_id'] . "' AND token NOT LIKE '" . $_SESSION['token'] . "'"); // удаляем все токены, кроме пользователя изменившего пароль
            $result = json_encode(['status' => true, 'return' => "Пароль успешно изменён"]); // выводим результат
        } else {
            $result = json_encode(['status' => false, 'error' => "Ваши пароли не совпадают"]); // выводим ошибку если пароли не совпали
        }
    } else {
        $result = json_encode(['status' => false, 'error' => "Вы не заполнили все обязательные поля"]); // выводим ошибку если пришёл не полный запрос
    }

    print_r($result); // выводим результат
}

function imageChange($sql)
{
    $select = check_users($sql); // првоеряем пользователя
    $uploaddir = "asset/views/home/images/user_image/"; // куда заливаем картинки

    $imageName = time() . rand(); // делаем названием
    $imageName = md5($imageName); // хешируем названием в md5

    $fileType = basename($_FILES['file']['name']); // получаем текущее название файла вместе с расширением
    $fileType = explode(".", $fileType); // разбиваем по "." на массив
    $fileType = end($fileType); // получаем расширение

    if($fileType != "png" && $fileType != "jpg" && $fileType != "jpeg" && $fileType != "bmp"){ // проверяем картинка ли это
        header("Location: " . URL . "home"); // если не картинка, редиректим
        exit;
    }

    $imageName = $imageName . "." . $fileType; // генерируем новое название с расширением

    while(true){ // запускаем вечный цикл
        if(!file_exists($uploaddir . $imageName)){ // если такого файла не сущевствует, то обрываем цикл
            break;
        }

        $imageName = time() . rand(); // если сущевствует делаем новое название
        $imageName = md5($imageName);
        $imageName = $imageName . "." . $fileType;
    }

    

    $uploadfile = $uploaddir . $imageName; // записываем расположение файла/названием файла

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) { // проверяем файл и копируем по указаному пути и названию в переменной $uploadfile
        $user_image = URL . "asset/views/home/images/user_image/" . $imageName; // делаем прямую ссылку на картинку для БД
        mysqli_query($sql, "UPDATE users SET user_image = '" . $user_image . "' WHERE id = '" . $select['user_id'] . "'"); // обновляем картинку в БД
        header("Location: " . URL . "home"); // редиректим
    } else { // если, что то пошло не по плану, то выводим ошибку
        echo "Возможная атака с помощью файловой загрузки!";
    }
}
