<!DOCTYPE html>
<?php

session_start(); // Создаем сессию
// Вывод всех ошибок для теста
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// Инклудим файл настроек и функций
include 'conf.php';
include 'functions.php';
$c = "Ukraine.png"; // Глобальная переменная C в которую будет заноситься страна города

if (!isset($_COOKIE['id'])) {
    if (isset($_POST['login']) && !empty($_POST['login'])) {
        $generate_id = rand(0, 99);
        $generate_id = md5($generate_id);

        setcookie("id", $generate_id);
        setcookie("loginpanel", 1);

        adduser($generate_id, $_POST['login']);

        header("Location: index.php");
    }
}else{
    updatescore($_COOKIE['id']);
}
?>

<html>
<head>
    <title>Города v0.16 [beta]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>

<form action="index.php" method="post">
<table class="tbl" align="center">
    <thead>
    <tr>
        <th valign="middle" align="center" height="60px" width="350px">

            <?php

            // Проверка введенной переменной на существование и вывод функций игры
            if (isset($_POST['city']) && !empty($_POST['city'])) {

                $proverka = $_POST['city'];

                // Вызов функции фильтрации символов
                $gorod = clear($proverka);

                // Вызов функции на проверку по базе городов - существует ли город
                if (realcity($gorod) != 0) {
                    // Вызов основной функции игры
                    searchgorod($gorod);
                }
            }

            ?>

        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="middle" align="center" height="60px" width="350px">
            <?php
            if (!isset($_COOKIE['loginpanel'])){
                echo '<span class="Lenka">Введите ваш ник: </span><p><input name="login" type="text" placeholder="Введите ваш никнэйм">';
            }else {
                echo '<p><input name="city" type="text" placeholder="Введите название города"/></p>';
                }
            ?>
            <p><input type="submit" class="buttonfromgoogle"/></p>

        </td>
    </tr>
    </tbody>

    <br/>
    <br/>

    <tfoot>
    <tr>
        <td align="center" width="350px"><b>Готово: </b></td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Подключение/создание базы городов;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Поиск по последней букве введенного города;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Рандомный вывод города по первой букве из базы;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Стили (CSS);</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Фильтрация вводимых данных (экранирование символов);</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Фильтрация городов оканчивающихся на "ы/ь/ъ" знаки;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> [<b>DEV</b>] Добавление городов из файла (AdminPanel);</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Фильтрация на существующие города по базе;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Подсказки (подсветка букв и т.п.);</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Отображение страны города (флаг);</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Интеграция с Wiki;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> [<b>DEV</b>] Отдельная страница с комментариями и листингом кода;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Сессии;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Фильтрация (сравнение) буквы введенного города и выпавшего;</td>
    </tr>
    <tr>
        <td><span class="plus">+</span> Авторизация игроков (через БД); <img src="img/new.gif" alt="NEW"></td>
    </tr>
    <tr>
        <td><span class="plus">+</span> ТОП игроков; <img src="img/new.gif" alt="NEW"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="center"><b>Не готово:</b></td>
    </tr>
    <tr>
        <td><span class="minus">-</span> Фильтрация повторяющихся городов;</td>
    </tr>
    <tr>
        <td><span class="minus">-</span> Блочный дизайн;</td>
    </tr>
    <tr>
        <td><span class="minus">-</span> Light-ЧПУ;</td>
    </tr>
    <tr>
        <td><span class="minus">-</span> [<b>DEV</b>] Удаление городов из базы (AdminPanel);</td>
    </tr>
    <tr>
        <td><span class="minus">-</span> [<b>DEV</b>] Авторизация (AdminPanel);</td>
    </tr>
    </tfoot>
</table>
</form>

<br/>

<p class="top10"><a href="top.php" target="_blank">Топ игроков</a></p>
<br/>
<p class="copyrights">
    <?php

    // Вывод количества городов в базе
    $result = mysql_query("SELECT COUNT(1) FROM glist");
    $data = mysql_fetch_array($result);

    echo "Всего городов в базе: <b>" . $data[0] . " (+<span class=plus>297</span>)</b>";

    // Закрываем коннект к базе
    mysql_close($db);

    ?>
    <br/>
    <a href="listing.html" target="_blank">Листинг игры</a> от 04.12.16
    <br/>
    <br/>
    <img src="img/duckpower.png" alt="duckpower" title="duckprog">
</p>

</body>
</html>