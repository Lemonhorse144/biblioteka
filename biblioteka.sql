SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `biblioteka` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `biblioteka`;

CREATE TABLE `czytelnicy` (
  `czytelnik` bigint(10) NOT NULL,
  `nazwisko` varchar(30) NOT NULL,
  `imie` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

INSERT INTO `czytelnicy` (`czytelnik`, `nazwisko`, `imie`) VALUES
(1, 'Hejmo', 'Patryk'),
(2, 'Krzak', 'Mateusz');

CREATE TABLE `dane` (
  `czytelnik` bigint(10) NOT NULL,
  `miejscowosc` varchar(30) NOT NULL,
  `kodpocztowy` varchar(6) NOT NULL,
  `ulica` varchar(30) NOT NULL,
  `telefon` int(9) NOT NULL,
  `mail` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

INSERT INTO `dane` (`czytelnik`, `miejscowosc`, `kodpocztowy`, `ulica`, `telefon`, `mail`) VALUES
(1, 'Poznań', '61-821', 'ul. Ogrodowa 13', 700487553, 'haejiemo@mail.net'),
(2, 'Myślenice', '32-400', 'ul. Jagiellońska 8', 966647907, 'kaerzetaka@poczta.pl');

CREATE TABLE `ksiegozbior` (
  `kod` bigint(10) NOT NULL,
  `isbn` bigint(13) NOT NULL,
  `tytul` varchar(50) NOT NULL,
  `autor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

INSERT INTO `ksiegozbior` (`kod`, `isbn`, `tytul`, `autor`) VALUES
(1, 9788365588579, 'Pan Tadeusz', 'Mickiewicz Adam'),
(2, 9788373897847, 'Potop', 'Sienkiewicz Henryk');

CREATE TABLE `wypozyczenia` (
  `czytelnik` bigint(10) NOT NULL,
  `kod` bigint(10) NOT NULL,
  `wypozyczenie` date NOT NULL DEFAULT current_timestamp(),
  `zwrot` date NOT NULL,
  `oddano` date DEFAULT NULL,
  `num` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

INSERT INTO `wypozyczenia` (`czytelnik`, `kod`, `wypozyczenie`, `zwrot`, `oddano`, `num`) VALUES
(1, 1, '2024-02-12', '2024-03-12', NULL, 1),
(1, 1, '2024-03-01', '2024-04-01', NULL, 2),
(1, 2, '2024-03-01', '2024-04-01', '2024-03-13', 3),
(2, 1, '2024-03-03', '2024-04-03', NULL, 4);


ALTER TABLE `czytelnicy`
  ADD PRIMARY KEY (`czytelnik`);

ALTER TABLE `dane`
  ADD PRIMARY KEY (`czytelnik`);

ALTER TABLE `ksiegozbior`
  ADD PRIMARY KEY (`kod`),
  ADD KEY `isbn` (`isbn`);

ALTER TABLE `wypozyczenia`
  ADD PRIMARY KEY (`num`),
  ADD KEY `czytelnik` (`czytelnik`,`kod`),
  ADD KEY `kod` (`kod`);


ALTER TABLE `czytelnicy`
  MODIFY `czytelnik` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `dane`
  MODIFY `czytelnik` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `ksiegozbior`
  MODIFY `kod` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `wypozyczenia`
  MODIFY `num` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `dane`
  ADD CONSTRAINT `dane_ibfk_1` FOREIGN KEY (`czytelnik`) REFERENCES `czytelnicy` (`czytelnik`);

ALTER TABLE `wypozyczenia`
  ADD CONSTRAINT `wypozyczenia_ibfk_1` FOREIGN KEY (`kod`) REFERENCES `ksiegozbior` (`kod`),
  ADD CONSTRAINT `wypozyczenia_ibfk_2` FOREIGN KEY (`czytelnik`) REFERENCES `czytelnicy` (`czytelnik`);
