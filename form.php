<?php
    require_once './database/connection.php';

    /**
     * Форма поиска постов по комментариям
     */

    // Подключение к базе данных
    $mysqli = getConnection();

    $errors = [];
    $data = [];
    $search = '';

    //При гет-запросе
    if (isset($_GET['search'])) {
        $search = trim(htmlentities($_GET['search']));

        //Валидируем данные
        if (mb_strlen($search) < 3) {
            $errors[] = "Введите минимум 3 символа для поиска";
        }

        if (empty($errors)) {
            try {
                // Поиск комментариев с искомой строкой вместе с постами
                $stmt = $mysqli->prepare("
                    SELECT `posts`.`id` as `post_id`, `posts`.`title` as `post_title`, `comments`.`body` as `comment_body`
                    FROM `posts`
                    JOIN `comments` ON `posts`.`id` = `comments`.`post_id` 
                    WHERE `comments`.`body` LIKE ?
                    ORDER BY `posts`.`id`, `comments`.`id`
                ");
                $searchPattern = '%' . $search . '%';
                $stmt->bind_param('s', $searchPattern);
                $stmt->execute();
                $result = $stmt->get_result();

                //Группируем комментарии по постам
                $groupedData = [];
                while ($row = $result->fetch_assoc()) {
                    if (!isset($groupedData[$row['post_id']])) {
                        $groupedData[$row['post_id']] = [
                            'post_id' => $row['post_id'],
                            'title' => $row['post_title'],
                            'comments' => []
                        ];
                    }

                    // Добавляем комментарий к посту
                    $groupedData[$row['post_id']]['comments'][] = [
                        'comment_body' => $row['comment_body']
                    ];
                }

                //Формируем конечный массив
                $data['items'] = array_values($groupedData);

                //Найденное количество постов
                $data['count_posts'] = count($groupedData);

                $stmt->close();
            } catch (Exception $e) {
                die("Ошибка: " . $e->getMessage());
            }
        }
    }

    //Подключаем шаблон с формой
    include_once './resources/views/form.html';
