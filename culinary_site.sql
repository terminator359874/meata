-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 04 2025 г., 15:41
-- Версия сервера: 10.5.17-MariaDB
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `culinary_site`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, '', 'buchelatib@gmail.com', '$2y$10$jczgPRo8BLO5CS7JEuUhYe8BESTRiK6QUlbxI7BhMZGcQ7LSADY/i'),
(2, 'Трамп', 'usanumber1@yandex.ru', '$2y$10$.dYygOjZUcWTeE.lYjnJ5eStvEJxTUYL7wCDfDKHiq8bwZdEGplji'),
(3, 'Zelenka', 'greenz@gmail.ukr', '$2y$10$ZdZ/CPU9SZoVZP2IeyIDHOlRB2ORUOUfcEjGVgprKwWT/YFNFHLbi'),
(4, 'Я', 'ya@gmail.com', '$2y$10$Q021VNnrUjFP7OjLKnTudee4g574gjqSfAMVaDKZImCpA6ZfrH8zO'),
(5, 'Я', 'adfgh@gmail.com', '$2y$10$3hM00i8hyHmMhBQ/quQC2eldIR1r9BvLDU2XaTX9VlW3yD7QEQOZe');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
