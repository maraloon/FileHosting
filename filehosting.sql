-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 09 2016 г., 19:51
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
  `path` mediumtext NOT NULL,
  `text` varchar(5000) CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nick` varchar(30) DEFAULT NULL,
  `fileId` int(11) NOT NULL,
  `parentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `path`, `text`, `time`, `nick`, `fileId`, `parentId`) VALUES
(43, '000', 'Круто', '2016-11-04 07:11:04', 'Anon', 3, NULL),
(44, '001', 'Это ведь кавер?', '2016-11-04 07:11:24', 'Anon', 3, NULL),
(46, '001.001', 'Ага, на ГО', '2016-11-04 07:11:51', 'Anon', 3, 44),
(48, '000.001', 'Мне тоже нравится', '2016-11-06 08:31:20', 'Anon', 3, 43),
(49, '000.001.001', 'А мне нет', '2016-11-06 08:31:44', 'Anon', 3, 48),
(56, '2.001', 'asssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssssasssssssssssssssssssssssssssss', '2016-11-08 19:36:42', 'asssssssssssssssssssssssssssss', 3, 44);

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `path` varchar(3000) NOT NULL,
  `name` varchar(255) NOT NULL,
  `originalName` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `mime` text,
  `mediaInfo` text COMMENT 'json-строка',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `numberOfDownloads` int(255) NOT NULL DEFAULT '0',
  `description` varchar(3000) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `path`, `name`, `originalName`, `size`, `mime`, `mediaInfo`, `time`, `numberOfDownloads`, `description`, `userId`) VALUES
(1, '2016/10/30/', 'filename_-.jpg', 'filename_-.jpg', 17665, 'image/jpeg', '', '2016-10-30 09:17:01', 0, 'Uh', 13),
(2, '2016/10/30/', 'filename_-_li0.jpg', 'filename_-.jpg', 17665, 'image/jpeg', '', '2016-10-30 09:17:27', 0, 'Загружаем повторно файл', 13),
(3, '2016/10/30/', 'Morokh - Vechnaya vesna.mp3', 'Morokh - Вечная весна.mp3', 11863552, 'audio/mpeg', '{"dataformat":"mp3","channels":2,"sample_rate":44100,"bitrate":320000,"codec":"LAME"}', '2016-10-30 09:19:13', 0, 'Русские буквы и пробелы в названии', 13),
(4, '2016/10/30/', 'Tap Arpegios Arpedzhio Teppingom.mp4', 'Tap Arpegios Арпеджио Тэппингом.mp4', 1301433, 'video/mp4', '{"dataformat":"quicktime","resolution_x":320,"resolution_y":240,"frame_rate":600}', '2016-10-30 09:20:33', 0, 'Видео', 13),
(10, '2016/11/09/', '14726441047370.webm', '14726441047370.webm', 3204926, 'video/webm', '{"dataformat":"vp8","resolution_x":420,"resolution_y":360,"frame_rate":30}', '2016-11-09 18:47:51', 0, 'Буку буку ё маё', 13);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `token` text COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `token`) VALUES
(13, 'g6tzl6sbuxexoz6rzszg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
