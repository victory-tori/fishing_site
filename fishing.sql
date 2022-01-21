-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Янв 19 2022 г., 11:38
-- Версия сервера: 10.4.19-MariaDB
-- Версия PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `fishing`
--

-- --------------------------------------------------------

--
-- Структура таблицы `logged_in_users`
--

CREATE TABLE `logged_in_users` (
  `session_id` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `logged_in_users`
--

INSERT INTO `logged_in_users` (`session_id`, `user_id`, `last_update`) VALUES
('35b1b9dk7ge5m1go1mg54d6lsl', 14, '2022-01-19 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `records`
--

CREATE TABLE `records` (
  `user_id` int(11) DEFAULT NULL,
  `record_id` int(11) NOT NULL,
  `record_name` varchar(255) NOT NULL,
  `fish` set('płoć','leszcz','krąp','karp','karaś','kleń','jaź','certa','lin','okoń','sandacz','szczupak','węgorz','wstrzęga','sum','miętus','inna') NOT NULL,
  `date` date NOT NULL,
  `weight` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `records`
--

INSERT INTO `records` (`user_id`, `record_id`, `record_name`, `fish`, `date`, `weight`) VALUES
(8, 2, 'record 1', 'sandacz', '2022-01-06', 12),
(14, 4, 'rekordzik 1', 'sandacz', '2021-12-27', 12),
(14, 5, 'rekordzik 2', 'sum', '2021-12-31', 2.54),
(14, 6, 'rekordzik 3', 'kleń', '2022-01-10', 3.2);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `username`, `fullname`, `email`, `passwd`, `status`, `date`) VALUES
(1, 'serg', 'Sergey Borisevich', 'serg@gmail.com', '$2y$10$uttpRVJlQC4iBoZhjFXt3uPcKdy2vJx/cnrAtqRL25lW0xzTBrH.G', 2, '2022-01-15 00:00:00'),
(8, 'asd', 'Vik Bor', 'viki@gmail.com', '$2y$10$T1Fcovbf01/p/Fm0olmb9uFQnRTtJynvNEY1eUXz1dlqwraCz20CS', 1, '2022-01-15 00:00:00'),
(14, 'kasia', 'Kasia Kasia', 'kasia@pollub.edu.pl', '$2y$10$NU39JjSQe1.4JOmZTDRsgOQk9SHOQJrHsDtACP9pTMtg9Q/wVpQta', 1, '2022-01-19 00:00:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `logged_in_users`
--
ALTER TABLE `logged_in_users`
  ADD PRIMARY KEY (`session_id`);

--
-- Индексы таблицы `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`record_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `records`
--
ALTER TABLE `records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
