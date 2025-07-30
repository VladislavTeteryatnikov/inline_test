-- Создание базы данных inline
CREATE DATABASE IF NOT EXISTS `inline`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Создание таблицы posts
CREATE TABLE `posts` (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL
);

-- Создание таблицы comments
CREATE TABLE `comments` (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `post_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL,
    FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
);