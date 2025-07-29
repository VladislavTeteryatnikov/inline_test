<?php
    require_once './database/config.php';
    require_once './database/connection.php';

    try {
        //Подключение к mysql
        $mysqli = getConnection(false);

        //Проверка существования базы данных
        $dbCheck = $mysqli->query("SHOW DATABASES WHERE `Database` = '" . DB_NAME . "'");
        if (!$dbCheck) {
            throw new Exception("Ошибка выполнения запроса: " . $mysqli->error);
        }

        if ($dbCheck->num_rows > 0) {
            echo "База данных '" . DB_NAME . "' уже существует \n";
        } else {
            //Создание базы данных
            $query = "CREATE DATABASE `" . DB_NAME . "` CHARACTER SET ". DB_CHARSET ." COLLATE " . DB_COLLATE;
            if ($mysqli->query($query)) {
                echo "База данных '" . DB_NAME . "' успешно создана \n";
            } else {
                throw new Exception("Ошибка создания базы данных: " . $mysqli->error);
            }
        }

        //Выбор базы данных
        if (!$mysqli->select_db(DB_NAME)) {
            throw new Exception("Ошибка выбора базы данных: " . $mysqli->error);
        }
        echo "База данных '" . DB_NAME . "' выбрана \n";

        //Проверка существования таблицы posts
        $tablePostsCheck = $mysqli->query("SHOW TABLES WHERE `Tables_in_" . DB_NAME . "` = 'posts'");
        if (!$tablePostsCheck) {
            throw new Exception("Ошибка выполнения запроса: " . $mysqli->error);
        }

        if ($tablePostsCheck->num_rows > 0) {
            echo "Таблица 'posts' уже существует \n";
        } else {
            // Создание таблицы posts
            $query = "CREATE TABLE `posts` (
                    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `user_id` INT UNSIGNED NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `body` TEXT NOT NULL
                )";

            if ($mysqli->query($query)) {
                echo "Таблица 'posts' успешно создана \n";
            } else {
                throw new Exception("Ошибка создания таблицы 'posts': " . $mysqli->error);
            }
        }

        //Проверка существования таблицы comments
        $tableCommentsCheck = $mysqli->query("SHOW TABLES WHERE `Tables_in_" . DB_NAME . "` = 'comments'");
        if (!$tableCommentsCheck) {
            throw new Exception("Ошибка выполнения запроса: " . $mysqli->error);
        }

        if ($tableCommentsCheck->num_rows > 0) {
            echo "Таблица 'comments' уже существует \n";
        } else {
            //Создание таблицы comments
            $query = "CREATE TABLE `comments` (
                    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `post_id` INT UNSIGNED NOT NULL,
                    `name` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    `body` TEXT NOT NULL,
                    FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
                )";

            if ($mysqli->query($query)){
                echo "Таблица 'comments' успешно создана \n";
            } else{
                throw new Exception("Ошибка создания таблицы 'comments': " . $mysqli->error);
            }
        }

        $mysqli->close();
        echo "Инициализация БД завершена \n";

    } catch (Exception $e) {

        if (isset($mysqli)) {
            $mysqli->close();
        }

        die("Произошла ошибка: " . $e->getMessage());
    }


