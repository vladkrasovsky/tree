<?php

define("DBHOST", "localhost");
define("DBUSER", "<user>");
define("DBPASS", "<pass>");
define("DB", "<db_name>");

$connection = @mysqli_connect(DBHOST, DBUSER, DBPASS, DB) or die("Нет соединения с БД");
mysqli_set_charset($connection, "utf8") or die("Не установлена кодировка соединения");
