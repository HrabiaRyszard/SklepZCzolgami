-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 22 2025 г., 15:34
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
  `kod_pocztowy` int(6) NOT NULL,
  `uzytkownik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `kategoria`
--

CREATE TABLE `kategoria` (
  `kategoria_id` int(11) NOT NULL,
  `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `platnosc`
--

CREATE TABLE `platnosc` (
  `platnosc_id` int(11) NOT NULL,
  `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `pracownik`
--

CREATE TABLE `pracownik` (
  `pracownik_id` int(11) NOT NULL,
  `imie` text NOT NULL,
  `nazwisko` text NOT NULL,
  `email` text NOT NULL,
  `telefon` text NOT NULL,
  `rola_id` int(11) NOT NULL,
  `data_zatrudnienia` date NOT NULL,
  `login` text NOT NULL,
  `haslo` text NOT NULL,
  `płaca` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `produkt`
--

CREATE TABLE `produkt` (
  `produkt_id` int(11) NOT NULL,
  `nazwa` text NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `opis` text NOT NULL,
  `url_zdjecia` text NOT NULL,
  `kategoria_id` int(11) NOT NULL,
  `aktywny` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `produkt_w_zamowieniu`
--

CREATE TABLE `produkt_w_zamowieniu` (
  `produkt_w_zamowieniu_id` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `zamowienie_id` int(11) NOT NULL,
  `ilosc_produktu` int(11) NOT NULL,
  `suma` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `rola`
--

CREATE TABLE `rola` (
  `rola_id` int(11) NOT NULL,
  `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `rola`
--

INSERT INTO `rola` (`rola_id`, `nazwa`) VALUES
(1, 'admin'),
(2, 'kierownik_magazynu'),
(3, 'pracownik_magazynu'),
(4, 'kurier');

-- --------------------------------------------------------

--
-- Структура таблицы `status_zamowienia`
--

CREATE TABLE `status_zamowienia` (
  `status_id` int(11) NOT NULL,
  `nazwa` text NOT NULL
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

-- --------------------------------------------------------

--
-- Структура таблицы `zamowienie`
--

CREATE TABLE `zamowienie` (
  `zamowienie_id` int(11) NOT NULL,
  `uzytkownik_id` int(11) NOT NULL,
  `adres_id` int(11) NOT NULL,
  `platnosc_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `kurier_id` int(11) NOT NULL,
  `data_czas_zamowienia` datetime NOT NULL,
  `data_czas_realizacji` datetime DEFAULT NULL,
  `suma` decimal(10,2) NOT NULL,
  `uwagi` text NOT NULL
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
-- Индексы таблицы `kategoria`
--
ALTER TABLE `kategoria`
  ADD PRIMARY KEY (`kategoria_id`);

--
-- Индексы таблицы `platnosc`
--
ALTER TABLE `platnosc`
  ADD PRIMARY KEY (`platnosc_id`);

--
-- Индексы таблицы `pracownik`
--
ALTER TABLE `pracownik`
  ADD PRIMARY KEY (`pracownik_id`),
  ADD KEY `fk_rola` (`rola_id`);

--
-- Индексы таблицы `produkt`
--
ALTER TABLE `produkt`
  ADD PRIMARY KEY (`produkt_id`),
  ADD KEY `fk_kategoria` (`kategoria_id`);

--
-- Индексы таблицы `produkt_w_zamowieniu`
--
ALTER TABLE `produkt_w_zamowieniu`
  ADD PRIMARY KEY (`produkt_w_zamowieniu_id`),
  ADD KEY `fk_produkt_w_zamowieniu_produkt` (`produkt_id`),
  ADD KEY `fk_produkt_w_zamowieniu_zamowienie` (`zamowienie_id`);

--
-- Индексы таблицы `rola`
--
ALTER TABLE `rola`
  ADD PRIMARY KEY (`rola_id`);

--
-- Индексы таблицы `status_zamowienia`
--
ALTER TABLE `status_zamowienia`
  ADD PRIMARY KEY (`status_id`);

--
-- Индексы таблицы `uzytkownik`
--
ALTER TABLE `uzytkownik`
  ADD PRIMARY KEY (`uzytkownik_id`);

--
-- Индексы таблицы `zamowienie`
--
ALTER TABLE `zamowienie`
  ADD PRIMARY KEY (`zamowienie_id`),
  ADD KEY `fk_zamowienie_uzytkownik` (`uzytkownik_id`),
  ADD KEY `fk_zamowienie_adres` (`adres_id`),
  ADD KEY `fk_zamowienie_platnosc` (`platnosc_id`),
  ADD KEY `fk_zamowienie_status` (`status_id`),
  ADD KEY `fk_zamowienie_kurier` (`kurier_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `adres`
--
ALTER TABLE `adres`
  MODIFY `adres_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `kategoria`
--
ALTER TABLE `kategoria`
  MODIFY `kategoria_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `platnosc`
--
ALTER TABLE `platnosc`
  MODIFY `platnosc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pracownik`
--
ALTER TABLE `pracownik`
  MODIFY `pracownik_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `produkt`
--
ALTER TABLE `produkt`
  MODIFY `produkt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `produkt_w_zamowieniu`
--
ALTER TABLE `produkt_w_zamowieniu`
  MODIFY `produkt_w_zamowieniu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `rola`
--
ALTER TABLE `rola`
  MODIFY `rola_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `status_zamowienia`
--
ALTER TABLE `status_zamowienia`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `uzytkownik`
--
ALTER TABLE `uzytkownik`
  MODIFY `uzytkownik_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zamowienie`
--
ALTER TABLE `zamowienie`
  MODIFY `zamowienie_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `adres`
--
ALTER TABLE `adres`
  ADD CONSTRAINT `fk_uzytkownik` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownik` (`uzytkownik_id`);

--
-- Ограничения внешнего ключа таблицы `pracownik`
--
ALTER TABLE `pracownik`
  ADD CONSTRAINT `fk_rola` FOREIGN KEY (`rola_id`) REFERENCES `rola` (`rola_id`);

--
-- Ограничения внешнего ключа таблицы `produkt`
--
ALTER TABLE `produkt`
  ADD CONSTRAINT `fk_kategoria` FOREIGN KEY (`kategoria_id`) REFERENCES `kategoria` (`kategoria_id`);

--
-- Ограничения внешнего ключа таблицы `produkt_w_zamowieniu`
--
ALTER TABLE `produkt_w_zamowieniu`
  ADD CONSTRAINT `fk_produkt_w_zamowieniu_produkt` FOREIGN KEY (`produkt_id`) REFERENCES `produkt` (`produkt_id`),
  ADD CONSTRAINT `fk_produkt_w_zamowieniu_zamowienie` FOREIGN KEY (`zamowienie_id`) REFERENCES `zamowienie` (`zamowienie_id`);

--
-- Ограничения внешнего ключа таблицы `zamowienie`
--
ALTER TABLE `zamowienie`
  ADD CONSTRAINT `fk_zamowienie_adres` FOREIGN KEY (`adres_id`) REFERENCES `adres` (`adres_id`),
  ADD CONSTRAINT `fk_zamowienie_kurier` FOREIGN KEY (`kurier_id`) REFERENCES `pracownik` (`pracownik_id`),
  ADD CONSTRAINT `fk_zamowienie_platnosc` FOREIGN KEY (`platnosc_id`) REFERENCES `platnosc` (`platnosc_id`),
  ADD CONSTRAINT `fk_zamowienie_status` FOREIGN KEY (`status_id`) REFERENCES `status_zamowienia` (`status_id`),
  ADD CONSTRAINT `fk_zamowienie_uzytkownik` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownik` (`uzytkownik_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
