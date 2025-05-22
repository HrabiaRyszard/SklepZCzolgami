-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 22 2025 г., 10:15
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `sklep`
--

-- --------------------------------------------------------

--
-- Структура таблицы `adres`
--

CREATE TABLE `adres` (
  `adres_id` int(11) NOT NULL,
  `panstwo` text NOT NULL,
  `miasto` text NOT NULL,
  `ulica` text NOT NULL,
  `numer_domu` text NOT NULL,
  `numer_mieszkania` text NOT NULL,
  `instrukcje_dostawy` text NOT NULL,
  `kod_pocztowy` int(11) NOT NULL,
  `uzytkownik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `uzytkownik`
--

CREATE TABLE `uzytkownik` (
  `uzytkownik_id` int(11) NOT NULL,
  `imie` text NOT NULL,
  `nazwisko` text NOT NULL,
  `email` text NOT NULL,
  `login` text NOT NULL,
  `haslo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `adres`
--
ALTER TABLE `adres`
  ADD PRIMARY KEY (`adres_id`),
  ADD KEY `fk_uzytkownik` (`uzytkownik_id`);

--
-- Индексы таблицы `uzytkownik`
--
ALTER TABLE `uzytkownik`
  ADD PRIMARY KEY (`uzytkownik_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `adres`
--
ALTER TABLE `adres`
  MODIFY `adres_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `uzytkownik`
--
ALTER TABLE `uzytkownik`
  MODIFY `uzytkownik_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `adres`
--
ALTER TABLE `adres`
  ADD CONSTRAINT `fk_uzytkownik` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownik` (`uzytkownik_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
