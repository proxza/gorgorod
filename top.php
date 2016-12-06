<!DOCTYPE html>
<?php

// Инклудим файл настроек и функций
include 'conf.php';
include 'functions.php';

?>

<html>
<head>
    <title>Города v0.11 - ТОП10 игроков [beta]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>
<p class="top10"> Топ 10 лучших игроков</p>
<p class="copyrights"><a href="index.php">Вернуться на главную</a></p>
<br/>
    <table class="toptable" align="center" border="1">
        <tr>
            <td align="center" valign="middle"><b>#</b></td><td valign="middle"><b>Ник</b></td><td align="center" valign="middle"><b>Очки</b></td>
        </tr>
        <?php

        toprate();

        ?>
    </table>

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
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
    <a href="listing.html" target="_blank">Листинг игры</a> от 29.11.16
    <br/>
    <br/>
    <img src="img/duckpower.png" alt="duckpower" title="duckprog">
</p>

</body>
</html>