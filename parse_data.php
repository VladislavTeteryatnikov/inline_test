<?php
    require_once './database/connection.php';

    /**
     * Скрипт для консоли для копирования постов и комментариев в нашу БД
     */

    try {
        //Подключение к базе данных
        $mysqli = getConnection();

        //Скачивание постов
        echo "Скачивание постов\n";
        $postsJson = file_get_contents('https://jsonplaceholder.typicode.com/posts');
        if (!$postsJson) {
            throw new Exception("Ошибка скачивания постов");
        }
        $posts = json_decode($postsJson, true);

        //Подготовка для вставки постов
        $postsCount = 0;
        $stmt = $mysqli->prepare("INSERT INTO `posts` (`id`, `user_id`, `title`, `body`) VALUES (?, ?, ?, ?)");
        foreach ($posts as $post) {
            $stmt->bind_param("iiss", $post['id'], $post['userId'], $post['title'], $post['body']);
            if ($stmt->execute()) {
                $postsCount++;
            } else {
                echo "Предупреждение: Ошибка вставки поста с ID " . $post['id'] . "\n";
            }
        }
        $stmt->close();
        echo "Посты скачаны\n";

        //Скачивание комментариев
        echo "Скачивание комментариев\n";
        $commentsJson = file_get_contents('https://jsonplaceholder.typicode.com/comments');
        if (!$commentsJson) {
            throw new Exception("Ошибка скачивания комментариев");
        }
        $comments = json_decode($commentsJson, true);

        //Подготовка для вставки комментариев
        $commentsCount = 0;
        $stmt = $mysqli->prepare("INSERT INTO `comments` (`id`, `post_id`, `name`, `email`, `body`) VALUES (?, ?, ?, ?, ?)");

        foreach ($comments as $comment) {
            $stmt->bind_param("iisss", $comment['id'], $comment['postId'], $comment['name'], $comment['email'], $comment['body']);
            if ($stmt->execute()) {
                $commentsCount++;
            } else {
                echo "Предупреждение: Ошибка вставки комментария с ID " . $comment['id'] . "\n";
            }
        }
        $stmt->close();
        echo "Комментарии скачаны\n";


        $mysqli->close();
        echo "Загружено $postsCount постов и $commentsCount комментариев\n";

    } catch (Exception $e) {

        if (isset($mysqli)) {
            $mysqli->close();
        }

        if (isset($stmt)) {
            $stmt->close();
        }

        die("Произошла ошибка: " . $e->getMessage());
    }
