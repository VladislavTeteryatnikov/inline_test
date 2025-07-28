<?php
    require_once 'config.php';

    //Подключение к mysql
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);

    //Проверка соединения
    if ($mysqli->connect_error){
        die("Ошибка подключения: " . $mysqli->connect_error);
    }

    //Проверка существования базы данных
    $dbCheck = $mysqli->query("SHOW DATABASES WHERE `Database` = '" . DB_NAME . "'");

    if ($dbCheck->num_rows > 0){
        echo "База данных '" . DB_NAME . "' уже существует \n";
    } else{
        //Создание базы данных
        $sql = "CREATE DATABASE `" . DB_NAME . "` CHARACTER SET ". DB_CHARSET ." COLLATE " . DB_COLLATE;
        if ($mysqli->query($sql)){
            echo "База данных '" . DB_NAME . "' успешно создана \n";
        } else{
            die("Ошибка создания базы данных: " . $mysqli->error);
        }
    }

    //Выбор базы данных
    if (!$mysqli->select_db(DB_NAME)){
        die("Ошибка выбора базы данных: " . $mysqli->error);
    }
    echo "База данных '" . DB_NAME . "' выбрана \n";

    //Проверка существования таблицы posts
    $tablePostsCheck = $mysqli->query("SHOW TABLES WHERE `Tables_in_" . DB_NAME . "` = 'posts'");

    if ($tablePostsCheck->num_rows > 0){
        echo "Таблица 'posts' уже существует \n";
    } else{
        // Создание таблицы posts
        $sql = "CREATE TABLE `posts` (
                    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `userId` INT UNSIGNED NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `body` TEXT NOT NULL
                )";

        if ($mysqli->query($sql)) {
            echo "Таблица 'posts' успешно создана \n";
        } else {
            die("Ошибка создания таблицы 'posts': " . $mysqli->error);
        }
    }

    //Проверка существования таблицы comments
    $tableCommentsCheck = $mysqli->query("SHOW TABLES WHERE `Tables_in_" . DB_NAME . "` = 'comments'");

    if ($tableCommentsCheck->num_rows > 0){
        echo "Таблица 'comments' уже существует \n";
    } else{
        //Создание таблицы comments
        $sql = "CREATE TABLE `comments` (
                    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `postId` INT UNSIGNED NOT NULL,
                    `name` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    `body` TEXT NOT NULL,
                    FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE CASCADE
                )";

        if ($mysqli->query($sql)){
            echo "Таблица 'comments' успешно создана \n";
        } else{
            die("Ошибка создания таблицы 'comments': " . $mysqli->error);
        }
    }

    //Закрытие соединения
    $mysqli->close();
    echo "Инициализация БД завершена \n";
