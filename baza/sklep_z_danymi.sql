-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 28 2025 г., 21:11
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
  `id` int(11) NOT NULL,
  `panstwo` text NOT NULL,
  `miasto` text NOT NULL,
  `ulica` text NOT NULL,
  `numer_domu` text NOT NULL,
  `numer_mieszkania` text NOT NULL,
  `kod_pocztowy` int(6) NOT NULL,
  `uzytkownik_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `adres`
--

INSERT INTO `adres` (`id`, `panstwo`, `miasto`, `ulica`, `numer_domu`, `numer_mieszkania`, `kod_pocztowy`, `uzytkownik_id`) VALUES
(1, 'Polska', 'Warszawa', 'Lipowa', '12', '3A', 12345, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `kategoria`
--

CREATE TABLE `kategoria` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `kategoria`
--

INSERT INTO `kategoria` (`id`, `nazwa`) VALUES
(1, 'Narzędzia ogrodowe'),
(2, 'Rośliny'),
(3, 'Nasiona'),
(4, 'Doniczki i akcesoria'),
(5, 'Nawozy i środki ochrony'),
(6, 'Mała architektura ogrodowa');

-- --------------------------------------------------------

--
-- Структура таблицы `pracownik`
--

CREATE TABLE `pracownik` (
  `id` int(11) NOT NULL,
  `imie` text NOT NULL,
  `nazwisko` text NOT NULL,
  `email` text NOT NULL,
  `telefon` text NOT NULL,
  `rola` enum('admin','kurier','pracownik_magazynu') NOT NULL,
  `data_zatrudnienia` date NOT NULL,
  `login` text NOT NULL,
  `haslo` text NOT NULL,
  `placa` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `pracownik`
--

INSERT INTO `pracownik` (`id`, `imie`, `nazwisko`, `email`, `telefon`, `rola`, `data_zatrudnienia`, `login`, `haslo`, `placa`) VALUES
(1, 'Jan', 'Nowak', 'jan.nowak@firma.pl', '500600700', 'admin', '2023-05-15', 'jnowak', 'adminpass', 7500.00);

-- --------------------------------------------------------

--
-- Структура таблицы `produkt`
--

CREATE TABLE `produkt` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `opis` text NOT NULL,
  `url_zdjecia` text DEFAULT NULL,
  `kategoria_id` int(11) DEFAULT NULL,
  `ilosc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `produkt`
--

INSERT INTO `produkt` (`id`, `nazwa`, `cena`, `opis`, `url_zdjecia`, `kategoria_id`, `ilosc`) VALUES
(1, 'Szpadel ogrodowy', 89.99, 'Solidny szpadel do kopania i sadzenia', 'szpadel.jpg', 1, 15),
(2, 'Grabie metalowe', 45.50, 'Grabie do liści i trawy', 'grabie.jpg', 1, 20),
(3, 'Sekator ręczny', 59.90, 'Sekator do cięcia gałęzi', 'sekator.jpg', 1, 30),
(4, 'Róża czerwona (sadzonka)', 25.00, 'Sadzonka róży czerwonej', 'roza.jpg', 2, 50),
(5, 'Tuje szmaragd (sadzonka)', 18.00, 'Tuje do ogrodzenia żywopłotowego', 'tuja.jpg', 2, 40),
(6, 'Lawenda (sadzonka)', 12.50, 'Aromatyczna bylina do ogrodu', 'lawenda.jpg', 2, 60),
(7, 'Nasiona marchewki', 4.99, 'Odmiana wczesna', 'marchewka.jpg', 3, 100),
(8, 'Nasiona trawy uniwersalnej', 15.90, 'Mieszanka do ogrodów i działek', 'trawa.jpg', 3, 80),
(9, 'Nasiona pomidora malinowego', 6.50, 'Pomidor o dużych owocach', 'pomidory.jpg', 3, 90),
(10, 'Doniczka plastikowa 20cm', 7.99, 'Doniczka uniwersalna', 'doniczka.jpg', 4, 100),
(11, 'Osłonka ceramiczna', 29.90, 'Dekoracyjna osłonka', 'oslonka.jpg', 4, 40),
(12, 'Zraszacz ogrodowy', 35.00, 'Do podlewania trawnika', 'zraszacz.jpg', 4, 25),
(13, 'Nawóz uniwersalny 5kg', 39.90, 'Do roślin ozdobnych i warzyw', 'nawoz.jpg', 5, 50),
(14, 'Opryskiwacz ręczny 2L', 22.00, 'Do środków ochrony roślin', 'opryskiwacz.jpg', 5, 35),
(15, 'Preparat przeciw mszycom', 19.50, 'Gotowy do użycia', 'mszyce.jpg', 5, 40),
(16, 'Altanka ogrodowa (zestaw)', 1299.00, 'Drewniana altanka do ogrodu', 'altanka.jpg', 6, 5),
(17, 'Ławka drewniana', 599.00, 'Ławka do ogrodu lub na taras', 'lawka.jpg', 6, 8),
(18, 'Pergola metalowa', 349.00, 'Stelaż do pnączy', 'pergola.jpg', 6, 10),
(19, 'Konewka metalowa', 32.90, 'Tradycyjna konewka', 'konewka.jpg', 1, 30),
(20, 'Ziemia uniwersalna 20L', 14.90, 'Do większości roślin ogrodowych', 'ziemia.jpg', 5, 70);

-- --------------------------------------------------------

--
-- Структура таблицы `szczegoly_zamowienia`
--

CREATE TABLE `szczegoly_zamowienia` (
  `id` int(11) NOT NULL,
  `produkt_id` int(11) DEFAULT NULL,
  `zamowienie_id` int(11) DEFAULT NULL,
  `ilosc_produktu` int(11) NOT NULL,
  `suma` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `szczegoly_zamowienia`
--

INSERT INTO `szczegoly_zamowienia` (`id`, `produkt_id`, `zamowienie_id`, `ilosc_produktu`, `suma`) VALUES
(1, NULL, 1, 1, 299.00),
(2, NULL, 1, 1, 39.99),
(3, NULL, 1, 1, 49.99),
(4, NULL, 2, 1, 59.90),
(5, NULL, 3, 1, 1299.00),
(6, NULL, 3, 1, 499.00),
(7, NULL, 4, 1, 149.99),
(8, NULL, 5, 1, 89.90),
(9, NULL, 5, 1, 29.00),
(10, NULL, 5, 2, 100.00);

-- --------------------------------------------------------

--
-- Структура таблицы `uzytkownik`
--

CREATE TABLE `uzytkownik` (
  `id` int(11) NOT NULL,
  `imie` text NOT NULL,
  `nazwisko` text NOT NULL,
  `email` text NOT NULL,
  `login` text NOT NULL,
  `haslo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `uzytkownik`
--

INSERT INTO `uzytkownik` (`id`, `imie`, `nazwisko`, `email`, `login`, `haslo`) VALUES
(1, 'Anna', 'Kowalska', 'anna.kowalska@example.com', 'ankowa', 'tajne123');

-- --------------------------------------------------------

--
-- Структура таблицы `zamowienie`
--

CREATE TABLE `zamowienie` (
  `id` int(11) NOT NULL,
  `uzytkownik_id` int(11) DEFAULT NULL,
  `adres_id` int(11) DEFAULT NULL,
  `platnosc` enum('blik','pszelew','gotówka') NOT NULL,
  `status` enum('przyjęnte','spakowane','dostarczone') NOT NULL,
  `kurier_id` int(11) DEFAULT NULL,
  `data_czas_zamowienia` datetime NOT NULL,
  `data_czas_realizacji` datetime DEFAULT NULL,
  `suma` decimal(10,2) NOT NULL,
  `uwagi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `zamowienie`
--

INSERT INTO `zamowienie` (`id`, `uzytkownik_id`, `adres_id`, `platnosc`, `status`, `kurier_id`, `data_czas_zamowienia`, `data_czas_realizacji`, `suma`, `uwagi`) VALUES
(1, 1, 1, 'blik', '', 1, '2025-05-25 10:00:00', NULL, 408.99, 'Dostarczyć po 16:00'),
(2, 1, 1, 'gotówka', 'spakowane', 1, '2025-05-26 11:15:00', NULL, 59.90, ''),
(3, 1, 1, '', 'dostarczone', 1, '2025-05-20 09:45:00', '2025-05-22 14:00:00', 1799.00, ''),
(4, 1, 1, 'gotówka', '', 1, '2025-05-24 17:30:00', NULL, 149.99, ''),
(5, 1, 1, 'blik', 'spakowane', 1, '2025-05-23 08:25:00', NULL, 218.90, '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `adres`
--
ALTER TABLE `adres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_uzytkownik` (`uzytkownik_id`);

--
-- Индексы таблицы `kategoria`
--
ALTER TABLE `kategoria`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pracownik`
--
ALTER TABLE `pracownik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rola` (`rola`);

--
-- Индексы таблицы `produkt`
--
ALTER TABLE `produkt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produkt_kategoria` (`kategoria_id`);

--
-- Индексы таблицы `szczegoly_zamowienia`
--
ALTER TABLE `szczegoly_zamowienia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_szczegoly_zamowienia_zamowienie` (`zamowienie_id`),
  ADD KEY `fk_szczegoly_zamowienia_produkt` (`produkt_id`);

--
-- Индексы таблицы `uzytkownik`
--
ALTER TABLE `uzytkownik`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `zamowienie`
--
ALTER TABLE `zamowienie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_zamowienie_uzytkownik` (`uzytkownik_id`),
  ADD KEY `fk_zamowienie_admin` (`kurier_id`),
  ADD KEY `fk_zamowienie_adres` (`adres_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `adres`
--
ALTER TABLE `adres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `kategoria`
--
ALTER TABLE `kategoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `pracownik`
--
ALTER TABLE `pracownik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `produkt`
--
ALTER TABLE `produkt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `szczegoly_zamowienia`
--
ALTER TABLE `szczegoly_zamowienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `uzytkownik`
--
ALTER TABLE `uzytkownik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `zamowienie`
--
ALTER TABLE `zamowienie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `produkt`
--
ALTER TABLE `produkt`
  ADD CONSTRAINT `fk_produkt_kategoria` FOREIGN KEY (`kategoria_id`) REFERENCES `kategoria` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `szczegoly_zamowienia`
--
ALTER TABLE `szczegoly_zamowienia`
  ADD CONSTRAINT `fk_szczegoly_zamowienia_produkt` FOREIGN KEY (`produkt_id`) REFERENCES `produkt` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_szczegoly_zamowienia_zamowienie` FOREIGN KEY (`zamowienie_id`) REFERENCES `zamowienie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `zamowienie`
--
ALTER TABLE `zamowienie`
  ADD CONSTRAINT `fk_zamowienie_admin` FOREIGN KEY (`kurier_id`) REFERENCES `pracownik` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_zamowienie_adres` FOREIGN KEY (`adres_id`) REFERENCES `adres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_zamowienie_uzytkownik` FOREIGN KEY (`uzytkownik_id`) REFERENCES `uzytkownik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
