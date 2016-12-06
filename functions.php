<?php

// Главная функция игры
function searchgorod($gorod)
{
    //mb_internal_encoding("UTF-8"); // Прописываем кодировку кодировка
    // Проверка первой буквы введенного слова
    $firstbukva = mb_substr($gorod, 0, 1);
    // Поднимаем букву (защита от неграмотных :D)
    $firstbukva = mb_convert_case($firstbukva, MB_CASE_UPPER, "UTF-8");

    // Разбиваем введеное слово и берем из него последную букву
    $col = strlen($gorod);
    $col = $col / 2;
    $col--;
    $bukva = mb_substr($gorod, $col, 1);

    // Проверка на запрещающие знаки типа мягкого и твердого
    if ($bukva === "ь" OR $bukva === "ъ" OR $bukva === "ы" OR $bukva === "й") {
        $col--;
        $bukva = mb_substr($gorod, $col, 1);
    }

    // Наш заброс в базу который рандомно (RAND()) ищет одно (LIMIT 1) слово по последней букве введенного слова
    $result = mysql_query("SELECT * FROM `glist` WHERE `gcity` LIKE '$bukva%' ORDER BY RAND() LIMIT 1");

    // Поднимает регистр буквы из сессии (для точного сравнения с введенной)
    $_SESSION["b"] = mb_convert_case($_SESSION["b"], MB_CASE_UPPER, "UTF-8");

    // Условие с проверкой сессии, если переменной в сессии нет (1й раз зашли либо проиграли) - создаем переменную ЛИБО проверяем наличие переменной с буквой
    if (isset($_SESSION["count1"]) == FALSE OR $firstbukva == $_SESSION["b"]) {
        // Инициируем переменные в сесии
        $_SESSION["count1"] = 0;
        $_SESSION["b"] = 0;
        $_SESSION["count1"]++;

        // Цикл вывода результат из БД
        while ($data = mysql_fetch_array($result)) {
            // Присваиваем переменные (чисто для визуального удобства в использовании)
            $city = $data[gcity];
            $country = $data[country];

            // Проверка на ошибку запроса, если запрос выдал ошибку (false) - ничего не выводим
            if ($data != false) {

                // Разбиваем полученное название города и берем последную букву для "подсказки" и сравнения (будущем)
                $col2 = iconv_strlen($city);
                $col2--;
                $bukva2 = mb_substr($city, $col2, 1);

                // Проверка на запрещающие знаки типа мягкого и твердого
                if ($bukva2 === "ь" OR $bukva2 === "ъ" OR $bukva2 === "ы" OR $bukva2 === "й") {
                    $col2--;
                    $bukva2 = mb_substr($city, $col2, 1);
                }
                $_SESSION["b"] = $bukva2;

                // Поднимаем буковку (регистр)
                $bukva2 = mb_convert_case($bukva2, MB_CASE_UPPER, "UTF-8");

                // Выводим наш результат, с флагами и форматированием, wiki и т.п.
                echo "<p>" . $gorod . " <img src=\"img/c/" . $GLOBALS["c"] . "\" alt=FLAG class=flags align=top> -> " . $city . " <img src=\"img/c/" . $country . "\" alt=FLAG class=flags align=top> <a href=\"https://ru.wikipedia.org/wiki/" . $city . "\" target=\"_blank\" border=0><img src=\"img/wiki.png\" alt=WIKI class=flags align=top></a><br />";
                echo "Вам на <font color=red>" . $bukva2 . "</font></p>";
            }
        }
    } else {
        echo "<p>Вы ввели город не с той буквы <br /> Вы проиграли... Начните заново</p>";
        // Удаляем сессии при неудаче
        session_unset();
        session_destroy();
        setcookie("id", "");
        setcookie("loginpanel", "");
        header("Refresh: 3; url=index.php");
    }
}


// Фильтрация символов вводимого слова
function clear($data)
{
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES);
    $data = mysql_real_escape_string($data);
    $data = ereg_replace("'", " ", $data);
    return $data;
}

// Функция проверки вводимого города по базе
function realcity($data)
{
    // Ищем и достаем из базы страну (country) по имени введенного города (LIKE data)
    $result = mysql_query("SELECT `country` FROM `glist` WHERE `gcity` LIKE '$data'");
    $data = mysql_fetch_array($result);

    // Проверка вывода запроса, если ошибка(false), выводим сообщение об ошибке, если true - присваиваем глобальной 
    // переменной C - значение data[0] которое содержит в себе страну (название файла вида country.png)
    if ($data == false) {
        echo "Такого города нет!";
        $data = 0;
        session_unset($_SESSION["b"]);
        setcookie("id", "");
        setcookie("loginpanel", "");
        header("Refresh: 3; url=index.php");
    } else {
        $GLOBALS["c"] = $data[0];
    }
    // Возвращаем значение
    return $data;

}

// Функция добавления городов из файла
function addcity()
{
    // Открываем файлик goroda.txt в котором храниться список городов (с новой строки)
    $lines = file('goroda.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Игнорируем пустые строки и не добавляем пустые
    $col = count($lines); // Считаем количество строк (городов) в файле

    // Создаем статическую переменную в которой будет храниться кол.циклов (добавленных городов)
    static $colgorod = 0;

    // Цикл добавления городов
    for ($i = 0; $i < $col;) {
        // Проверяем, есть ли уже в базе такой город
        $proverka = mysql_query("SELECT `id` FROM `glist` WHERE `gcity` LIKE '$lines[$i]'");
        $data = mysql_fetch_array($proverka);
        // Если города нет в базе, вносим (INSERT) в базу города. В конце в ручную указываем принадлежность города к стране (временно)
        if ($data == false) {
            $result = mysql_query("INSERT INTO `glist` (`gcity`, `country`) VALUES ('" . $lines[$i] . "', 'Spain.png')");
        }
        $i++;
        $colgorod++; // +1 к стат.счетчику для отображения кол.внесенных городов
    }

    // Проверка, прошел ли запрос успешно
    if ($result) {
        echo "Новые города внесены <br />";
        echo "Добавлено: " . $colgorod . " городов"; // Вывод кол.добавленых городов
    } else {
        echo "Произошла ошибка!";
    }
}

function adduser($id, $name){
    $result = mysql_query("SELECT `score` FROM `players` WHERE `id` LIKE '$id'");
    $data = mysql_fetch_array($result);

    if ($data == false) {
        $result = mysql_query("INSERT INTO `players` (`hesh_id`, `name`, `score`) VALUES ('" . $id . "', '" . $name . "', '0')");
    }
}

function updatescore($id){
    $result = mysql_query("SELECT `score` FROM `players` WHERE `hesh_id` LIKE '$id'");

    while($row = mysql_fetch_array($result)){
        $score = $row['score'] + 1;
        $sql = mysql_query("UPDATE `players` SET score='" . $score . "' WHERE hesh_id='" . $id . "'");
    }
}

function toprate(){
    $result = mysql_query("SELECT * FROM `players` ORDER BY `score` DESC LIMIT 10");
    $win = 1;

    while($row = mysql_fetch_array($result)){
        echo '<tr><td align="center" valign="middle">'.$win.'</td><td>'.$row['name'].'</td><td align="center" valign="middle">'.$row['score'].'</td></tr>';
        $win++;
    }

}

?>