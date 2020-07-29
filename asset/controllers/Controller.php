<?php
/*
*   Если нужно подключение к БД, принимаем подключение
*   Если нужно подключить модель,то делать это вот так:
*   include_once "asset/models/{namemodel}.php";
*/
    function index(){ //выводим главную страницу
        if($_SESSION['token'] != ""){ // если пользователь авторизован - делаем редирект на личный кабинет
            header("Location: " . URL . "home");
			exit;
        }
        include("asset/views/index.php"); // выводим страницу(где авторизация, регистрация и т.д)
    }

    function login($sql){ // авторизация, принимаем наше подключение к БД
        $select = mysqli_fetch_assoc(mysqli_query($sql, "SELECT * FROM users WHERE email LIKE '" . $_POST['email'] . "'")); // проверяем есть ли данная почта в БД
        $result = ""; // создаём пустую переменную для результата
        if($select['id'] != ""){ // если есть
            if($select['approved'] == 0){ // проверяем подтверждение аккаунта пользователя
                $result = json_encode(['status' => false, 'error' => 'Вам на почту выслано письмо с ссылкой']); // если не подтверждена почта выводим ошибку
            } else { // если подтверждена
                $password = md5($_POST['password']); // введённый пароль хешируем в md5 
                if($password == $select['password']){ // проверяем пароль в БД с тем, что ввели
                    $token = time() . rand(); // если пароль верный создаём токен
                    $token = md5($token); // хешируем токен тоже в md5
                    while(true){ // создаём бесконечный цикл для того, что бы сделать уникальный токен(на всякий случай.токен должен генерироваться сразу уникальным)
                        $select_token = mysqli_fetch_assoc(mysqli_query($sql, "SELECT * FROM logins WHERE token LIKE '" . $token . "'")); // смотрим в БД сгенерированный токен
                        if($select_token['id'] == ""){ // если  нет, то обрываем цикл
                            break;
                        } else { // или генерируем новый токен
                            $token = time() . rand();
                            $token = md5($token);
                        }
                    }
                    mysqli_query($sql, "INSERT INTO logins (user_id, token) VALUES ('" . $select['id'] . "', '" . $token . "')"); // записываем нашу авторизацию в БД
                    $_SESSION = array(); // очищаем сессию(на всякий случай)
                    $_SESSION['token'] = $token; // запиываем в сессию токен
                    $result = ['status' => true, 'error' => null]; // собираем ответ о успешной авторизации
                    $result = json_encode($result); // загоняем в JSON и записываем в вышесозданную переменную
                } else {
                    $result = json_encode(['status' => false, 'error' => 'Не верный пароль']); // если пароль не верный, то записываем ошибку в переменную
                }
            }
        } else {
            $result = json_encode(['status' => false, 'error' => 'Пользователя с такой почтой не сущевствует']); // если такой почты в базе нету, записываем ошибку в переменную
        }

        print_r($result); // выводим результат
    }

    function register($sql){ // регистрация, принимаем наше подключение к БД
        $result = ""; // создаём переменную для ответа
        if($_POST['email'] != "" && $_POST['password'] != "" && $_POST['name'] != ""){ // проверяем параметры которые нам передало с формы
            if($_POST['password'] == $_POST['repassword']){ // проверяем совпадают ли пароли
                $select = mysqli_fetch_assoc(mysqli_query($sql, "SELECT * FROM users WHERE email LIKE '" . $_POST['email'] . "'")); // смотрим, есть ли такая почта в БД
                if($select['id'] != ""){ 
                    $result = json_encode(['status' => false, 'error' => "Пользователь с такой почтой уже сущевствует"]); // если есть выводим ошибку
                } else { 
                    $password = md5($_POST['password']); // если нет, собираем пароль в md5 hash
                    mysqli_query($sql, "INSERT INTO users (name, email, password, user_image) VALUES ('" . $_POST['name'] . "', '" . $_POST['email'] . "', '" . $password . "', '" . URL . "'asset/views/home/images/icon-default.png)"); // записываем данные в БД
                    $result = json_encode(['status' => true, 'error' => null, 'return' => "Пользователь успешно создан, проверьте вашу почту"]); // записываем ответ в переменную
                    $data = base64_encode($_POST['email']); // собираем ссылку для отправки на почту
                    $url = URL . "approved?data=" . $data; // собираем ссылку для отправки на почту 
                    mail($_POST['email'], "Подтверждение", "Ссылка для подтверждения - " . $url); // отправляем письмо на почту
                }
            } else {
                $result = json_encode(['status' => false, 'error' => "Ваши пароли не совпадают"]); // если пароли не совпали, записываем ошибку в переменную
            }
        } else {
            $result = json_encode(['status' => false, 'error' => 'Вы заполнили не все обязательные поля']); // если пришёл не полный запрос, записываем ошибку
        }
        print_r($result); // выводим результат
    }

    function approved($sql){ // подтверждение почты
        if($_GET['data'] != null){ // проверяем на запрос
            $email = base64_decode($_GET['data']); // разбиваем base64 шифр и получаем почту
            $select = mysqli_fetch_assoc(mysqli_query($sql, "SELECT id, approved FROM users WHERE email LIKE '" . $email . "'")); // ищем почту в БД
            if($select['id'] != "" && $select['approved'] == 0){ // проверяем есть ли такой пользователь в БД, если есть сразу же проверяем на подтверждение
                mysqli_query($sql, "UPDATE users SET approved = 1 WHERE id = '" . $select['id'] . "'"); // если пользователь есть и нет подтверждения, то подтверждаем
                header('Location: ' . URL); // редирект на главную страницу сайта
            } else {
                echo "Ссылка недоступна"; // если, пользователя нет или уже подтверждён выводим ошибку
            }
        } else {
            echo "Ссылка недоступна"; // если нет data то выводим ошибку
        }
    }

    function random($max = 9){ // генерация рандомных строк
        $symbol = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0']; // записываем все возможные символы в массив
        $count = count($symbol); // получаем кол-во элементов в массиве
        $count = $count - 1; // отнимаем от кол-ва элементов единицу(отсчёт в массиве идёт с 0, а count считает с 1)
        $result = ""; // создаём пустую переменную для результата
        for($i = 0; $i < $max; $i++){ // заходим в цикл, что бы было то кол-во символов в переменной которое указали в аргументе функции
            $randSymbol = rand(0, $count); // получаем рандомное число от 0 до $count
            $result .= $symbol[$randSymbol]; // берём из массива элемент который указан под номером рандомного числа которое мы получили выше и ложим в переменную результата
        }
        return $result; // выводим результат
    }

    function recovery($sql){ // восстановление пароля
        $result = ""; // создаём пустую переменную для результата
        if($_POST['email'] != ""){ // проверяем пришёл ли запрос 
            $select = mysqli_fetch_assoc(mysqli_query($sql, "SELECT id, approved FROM users WHERE email LIKE '" . $_POST['email'] . "'")); // смотрим на подтверждение и вообще наличия такой почты в БД
            if($select['id'] != ""){ 
                if($select['approved'] == 0){ // проверяем подтверждение почты
                    $result = json_encode(['status' => false, 'error' => 'Для начала подтвердите аккаунт']); // если нет записываем ошибку в переменную
                } else {
                    $newPassword = random(); // если есть, создаём пароль из 9 рандомных символов
                    $newPassword = md5($newPassword); // загоняем в MD5 hash
                    mail($_POST['email'], 'Ваш новый пароль', 'Ваш новый пароль - ' . $newPassword); // оптравляем письмо на почту
                    mysqli_query($sql, "UPDATE users SET password = '" . $newPassword . "' WHERE id = '" . $select['id'] . "'"); // меняем пароль в БД
                    $result = json_encode(['status' => true, 'return' => 'Вам на почту выслан новый пароль']); // записываем результат
                }
            } else {
                $result = json_encode(['status' => false, 'error' => 'Пользователя с данной почтой не сущевствует']); // если такой почты нет в БД, выводим ошибку
            }
        } else {
            $result = json_encode(['status' => false, 'error' => 'Вы не заполнили все обязательные поля']); // если пришёл неполный запрос, выводим ошибку
        }

        print_r($result); // выводим результат
    }
?>