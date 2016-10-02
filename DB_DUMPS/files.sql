-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 27 2016 г., 07:47
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
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `path` varchar(3000) NOT NULL,
  `name` varchar(255) NOT NULL,
  `originalName` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `numberOfDownloads` int(255) NOT NULL DEFAULT '0',
  `description` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `path`, `name`, `originalName`, `size`, `time`, `numberOfDownloads`, `description`) VALUES
(62, '2016/09/18/', '0000461_glyn-smyth-wolves-in-the-throne-room-celestite-tour-2014-artist-edition.jpeg', '0000461_glyn-smyth-wolves-in-the-throne-room-celestite-tour-2014-artist-edition.jpeg', 293045, '2016-09-18 09:38:58', 1, 'It''s a black'),
(63, '2016/09/18/', 'tumblr_ny1r8pfaBf1ref7bto1_500.png', 'tumblr_ny1r8pfaBf1ref7bto1_500.png', 250512, '2016-09-18 09:49:22', 0, 'Kurt'),
(64, '2016/09/18/', 'Mayhem_flag.png', 'Mayhem_flag.png', 2967243, '2016-09-18 09:53:49', 0, 'The true Mayhem'),
(65, '2016/09/18/', '156ac66a37a5a869a94b221f10615c2c_full.jpg', '156ac66a37a5a869a94b221f10615c2c_full.jpg', 154063, '2016-09-18 09:59:10', 2, 'Gorgoroth'),
(66, '2016/09/18/', 'nazvanie fajla.png', 'название файла.png', 291735, '2016-09-18 10:41:54', 0, 'Курочка'),
(67, '2016/09/19/', 'file name.png', 'file name.png', 266203, '2016-09-19 09:51:26', 0, 'Пирамида Маслоу'),
(68, '2016/09/23/', 'Tap Arpegios Arpedzhio Teppingom.mp4', 'Tap Arpegios Арпеджио Тэппингом.mp4', 1301433, '2016-09-23 06:26:00', 0, 'Tapping');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
