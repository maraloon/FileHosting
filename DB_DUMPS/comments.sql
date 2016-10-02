-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 27 2016 г., 08:47
-- Версия сервера: 10.1.13-MariaDB
-- Версия PHP: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `filehosting`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `text` varchar(5000) CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nick` varchar(30) DEFAULT NULL,
  `fileId` int(11) NOT NULL,
  `parentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `text`, `time`, `nick`, `fileId`, `parentId`) VALUES
(1, 'Так и есть', '2016-08-25 07:15:40', NULL, 65, NULL),
(2, 'Что за сайт?', '2016-08-25 07:15:40', NULL, 65, NULL),
(12, 'Не стоит вскрывать эту тему', '2016-08-25 07:33:43', 'Tony', 65, 2),
(13, 'Двачую', '2016-08-28 09:45:20', 'Greg', 65, 12),
(14, 'Какие то вы странные', '2016-08-28 20:43:27', '', 65, 13),
(15, 'ТНН!', '2016-08-28 20:43:44', 'Nickie', 65, NULL),
(17, 'Солидарен', '2016-09-07 07:03:29', '', 65, 15),
(18, 'Чек-чек', '2016-09-09 17:46:42', 'Katril', 65, 1),
(19, 'Ееее, сок!', '2016-09-09 18:13:01', '', 65, 18);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
