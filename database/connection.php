<?php
    require_once 'config.php';

    function getConnection($selectDatabase = true) {
        try {
            if ($selectDatabase) {
                $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            } else {
                $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);
            }

            if ($mysqli->connect_error) {
                throw new Exception('Ошибка подключения:' . $mysqli->connect_error);
            }

            return $mysqli;

        } catch (Exception $e) {
            die('Ошибка: ' . $e->getMessage());
        }
    }