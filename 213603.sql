-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 21.01.2026 klo 14:20
-- Palvelimen versio: 10.11.14-MariaDB-0+deb12u2
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `213603`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `Kaappi`
--

CREATE TABLE `Kaappi` (
  `KaappiID` int(11) NOT NULL,
  `Nimi` varchar(100) NOT NULL,
  `LuokkaID` int(11) NOT NULL,
  `Sijainti` varchar(200) DEFAULT NULL,
  `Tyyppi` enum('huone','kaappi','hylly') NOT NULL DEFAULT 'kaappi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `Kaappi`
--

INSERT INTO `Kaappi` (`KaappiID`, `Nimi`, `LuokkaID`, `Sijainti`, `Tyyppi`) VALUES
(1, 'Tietokonekaappi', 1, 'Luokka TS10, etuosa', 'kaappi'),
(2, 'Komponenttikaappi', 1, 'Luokka TS10, takaosa', 'kaappi'),
(3, 'Työkalukaappi', 2, 'Luokka TS11, seinä', 'kaappi'),
(4, 'Työkaluhylly', 2, 'Luokka TS11, ikkuna', 'hylly'),
(5, 'Toimistokaappi', 3, 'Luokka B201, nurkkaus', 'kaappi'),
(6, 'Kemikaalihylly', 4, 'Laboratorio LAB1, kaappi', 'hylly'),
(7, 'Kamerakaappi', 5, 'Studio, varastohuone', 'kaappi'),
(8, 'Valokalusto', 5, 'Studio, sivuseinä', 'hylly');

-- --------------------------------------------------------

--
-- Rakenne taululle `Kayttaja`
--

CREATE TABLE `Kayttaja` (
  `KayttajaID` int(11) NOT NULL,
  `Nimi` varchar(100) NOT NULL,
  `Sukunimi` varchar(100) NOT NULL,
  `Sahkoposti` varchar(150) NOT NULL,
  `Osoite` varchar(200) DEFAULT NULL,
  `Postinumero` varchar(10) DEFAULT NULL,
  `Kunta` varchar(100) DEFAULT NULL,
  `Luotu` datetime DEFAULT current_timestamp(),
  `Muokkaus` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Rooli` enum('admin','kayttaja') NOT NULL,
  `Salasana` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `Kayttaja`
--

INSERT INTO `Kayttaja` (`KayttajaID`, `Nimi`, `Sukunimi`, `Sahkoposti`, `Osoite`, `Postinumero`, `Kunta`, `Luotu`, `Muokkaus`, `Rooli`, `Salasana`) VALUES
(8, 'Ope', 'Ope', 'Opettaja@sakky.fi', 'Ope', 'Ope', 'Ope', '2026-01-21 14:40:05', '2026-01-21 14:40:05', 'kayttaja', '$2y$10$nMrTKcPjSzVABBPIqrLnw.KHT2sLMGIZ6VUwzChaPKI/18fc0VUDy'),
(9, 'Ope', 'Ope', 'AdminOpettaja@sakky.fi', 'Ope', 'Ope', 'Ope', '2026-01-21 14:40:36', '2026-01-21 16:04:11', 'admin', '$2y$10$dHZNuOaQwhjTdMVLqh8rPuNkRaZhZywZM.2efZc18SwB7pN803LRS');

-- --------------------------------------------------------

--
-- Rakenne taululle `Luokat`
--

CREATE TABLE `Luokat` (
  `LuokkaID` int(11) NOT NULL,
  `Nimi` varchar(100) NOT NULL,
  `Tiedot` text DEFAULT NULL,
  `Kattavuus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `Luokat`
--

INSERT INTO `Luokat` (`LuokkaID`, `Nimi`, `Tiedot`, `Kattavuus`) VALUES
(1, 'TS10', 'Tekniset tieteet 10, elektroniikan oppiluokka', 30),
(2, 'TS11', 'Tekniset tieteet 11, koneluokka', 28),
(3, 'B201', 'Toimistoluokka, hallinto', 25),
(4, 'LAB1', 'Kemian laboratorio', 20),
(5, 'STUDIO', 'Mediatekniikan studio', 35);

-- --------------------------------------------------------

--
-- Rakenne taululle `Tavara`
--

CREATE TABLE `Tavara` (
  `TavaraID` int(11) NOT NULL,
  `Nimi` varchar(100) NOT NULL,
  `Kuvaus` text DEFAULT NULL,
  `Maara` int(11) NOT NULL DEFAULT 0,
  `SaatavillaMaara` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `Tavara`
--

INSERT INTO `Tavara` (`TavaraID`, `Nimi`, `Kuvaus`, `Maara`, `SaatavillaMaara`) VALUES
(1, 'Kannettava tietokone', 'Lenovo ThinkPad T14', 15, 12),
(2, 'Hiiri', 'Langaton hiiri', 30, -46),
(3, 'Näppäimistö', 'Mekaaninen näppäimistö', 20, 16),
(4, 'Arduino Uno', 'Mikrokontrolleri kehitysalusta', 40, 0),
(5, 'Ruuvimeisseli', 'Ristipääruuvimeisseli', 25, 22),
(6, 'Vasara', 'Puuvasara 500g', 10, 8),
(7, 'Porakone', 'Akkuporakone 18V', 8, 6),
(8, 'Monitoimityökalu', 'Leatherman Wave', 12, 10),
(9, 'Kamera', 'Canon EOS R6', 5, 4),
(10, 'Mikrofoni', 'Shure SM7B', 8, 6),
(11, 'Jalusta', 'Kameran jalusta', 10, 8),
(12, 'Valonheitin', 'LED-valo 100W', 15, 12),
(13, 'Lasikuitu', 'Suojalasit', 50, 45),
(14, 'Käsineet', 'Työkäsineet koko M', 60, 50),
(15, 'Pesuaine', 'Yleispuhdistusaine 1L', 30, 25);

-- --------------------------------------------------------

--
-- Rakenne taululle `Varasto_Rivit`
--

CREATE TABLE `Varasto_Rivit` (
  `VarastoRiviID` int(11) NOT NULL,
  `KaappiID` int(11) NOT NULL,
  `TavaraID` int(11) NOT NULL,
  `Maara` int(11) NOT NULL DEFAULT 0,
  `Hylly` varchar(50) DEFAULT NULL,
  `Lisatty` datetime DEFAULT current_timestamp(),
  `Paivitetty` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `Varasto_Rivit`
--

INSERT INTO `Varasto_Rivit` (`VarastoRiviID`, `KaappiID`, `TavaraID`, `Maara`, `Hylly`, `Lisatty`, `Paivitetty`) VALUES
(1, 1, 1, 8, 'Hylly 1', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(2, 1, 2, 15, 'Hylly 2', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(3, 1, 3, 10, 'Hylly 2', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(4, 2, 4, 25, 'Laatikko A', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(5, 2, 13, 30, 'Hylly 3', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(6, 3, 5, 15, 'Laatikko 1', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(7, 3, 6, 10, 'Laatikko 2', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(8, 3, 8, 8, 'Laatikko 3', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(9, 4, 7, 8, 'Pääkaappi', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(10, 4, 14, 40, 'Alahylly', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(11, 5, 1, 7, 'Yläosa', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(12, 5, 2, 15, 'Keskiosa', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(13, 5, 3, 10, 'Keskiosa', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(14, 6, 13, 20, 'Hylly A', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(15, 6, 14, 20, 'Hylly B', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(16, 6, 15, 30, 'Hylly C', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(17, 7, 9, 5, 'Ylähylly', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(18, 7, 10, 8, 'Keskihylly', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(19, 7, 11, 10, 'Alahylly', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(20, 8, 12, 15, 'Valoalue', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(21, 2, 4, 15, 'Laatikko B', '2026-01-21 10:00:00', '2026-01-21 10:00:00'),
(22, 3, 14, 0, NULL, '2026-01-21 10:00:00', '2026-01-21 10:00:00');

-- --------------------------------------------------------

--
-- Rakenne taululle `Varaus`
--

CREATE TABLE `Varaus` (
  `VarausID` int(11) NOT NULL,
  `VarausriviID` int(11) NOT NULL,
  `KayttajaID` int(11) NOT NULL,
  `Aloitus` datetime NOT NULL DEFAULT current_timestamp(),
  `Lopetus` datetime NOT NULL,
  `aktiivinen` tinyint(1) DEFAULT 1,
  `Palautettu` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `Varaus`
--

INSERT INTO `Varaus` (`VarausID`, `VarausriviID`, `KayttajaID`, `Aloitus`, `Lopetus`, `aktiivinen`, `Palautettu`) VALUES
(4, 4, 8, '2026-01-21 14:44:40', '2026-01-22 23:59:59', 1, NULL),
(5, 5, 8, '2026-01-21 15:58:02', '2026-01-22 23:59:59', 0, '2026-01-21 16:02:24');

-- --------------------------------------------------------

--
-- Rakenne taululle `VarausRivit`
--

CREATE TABLE `VarausRivit` (
  `VarausriviID` int(11) NOT NULL,
  `VarastoRiviID` int(11) NOT NULL,
  `Maara` int(11) NOT NULL,
  `Varauspaiva` date NOT NULL,
  `Paattymispaiva` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `VarausRivit`
--

INSERT INTO `VarausRivit` (`VarausriviID`, `VarastoRiviID`, `Maara`, `Varauspaiva`, `Paattymispaiva`) VALUES
(1, 2, 1, '2026-01-21', '2026-01-22'),
(2, 2, 35, '2026-01-21', '2026-01-22'),
(3, 2, 35, '2026-01-21', '2026-01-22'),
(4, 4, 35, '2026-01-21', '2026-01-22'),
(5, 19, 1, '2026-01-21', '2026-01-22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Kaappi`
--
ALTER TABLE `Kaappi`
  ADD PRIMARY KEY (`KaappiID`),
  ADD KEY `fk_kaappi_luokka` (`LuokkaID`);

--
-- Indexes for table `Kayttaja`
--
ALTER TABLE `Kayttaja`
  ADD PRIMARY KEY (`KayttajaID`),
  ADD UNIQUE KEY `Sahkoposti` (`Sahkoposti`);

--
-- Indexes for table `Luokat`
--
ALTER TABLE `Luokat`
  ADD PRIMARY KEY (`LuokkaID`);

--
-- Indexes for table `Tavara`
--
ALTER TABLE `Tavara`
  ADD PRIMARY KEY (`TavaraID`);

--
-- Indexes for table `Varasto_Rivit`
--
ALTER TABLE `Varasto_Rivit`
  ADD PRIMARY KEY (`VarastoRiviID`),
  ADD KEY `fk_varastorivi_kaappi` (`KaappiID`),
  ADD KEY `fk_varastorivi_tavara` (`TavaraID`);

--
-- Indexes for table `Varaus`
--
ALTER TABLE `Varaus`
  ADD PRIMARY KEY (`VarausID`),
  ADD KEY `fk_varaus_varausrivi` (`VarausriviID`),
  ADD KEY `fk_varaus_kayttaja` (`KayttajaID`);

--
-- Indexes for table `VarausRivit`
--
ALTER TABLE `VarausRivit`
  ADD PRIMARY KEY (`VarausriviID`),
  ADD KEY `fk_varausrivi_varastorivi` (`VarastoRiviID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Kaappi`
--
ALTER TABLE `Kaappi`
  MODIFY `KaappiID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Kayttaja`
--
ALTER TABLE `Kayttaja`
  MODIFY `KayttajaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Luokat`
--
ALTER TABLE `Luokat`
  MODIFY `LuokkaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Tavara`
--
ALTER TABLE `Tavara`
  MODIFY `TavaraID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `Varasto_Rivit`
--
ALTER TABLE `Varasto_Rivit`
  MODIFY `VarastoRiviID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `Varaus`
--
ALTER TABLE `Varaus`
  MODIFY `VarausID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `VarausRivit`
--
ALTER TABLE `VarausRivit`
  MODIFY `VarausriviID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `Kaappi`
--
ALTER TABLE `Kaappi`
  ADD CONSTRAINT `fk_kaappi_luokka` FOREIGN KEY (`LuokkaID`) REFERENCES `Luokat` (`LuokkaID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Rajoitteet taululle `Varasto_Rivit`
--
ALTER TABLE `Varasto_Rivit`
  ADD CONSTRAINT `fk_varastorivi_kaappi` FOREIGN KEY (`KaappiID`) REFERENCES `Kaappi` (`KaappiID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_varastorivi_tavara` FOREIGN KEY (`TavaraID`) REFERENCES `Tavara` (`TavaraID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Rajoitteet taululle `Varaus`
--
ALTER TABLE `Varaus`
  ADD CONSTRAINT `fk_varaus_kayttaja` FOREIGN KEY (`KayttajaID`) REFERENCES `Kayttaja` (`KayttajaID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_varaus_varausrivi` FOREIGN KEY (`VarausriviID`) REFERENCES `VarausRivit` (`VarausriviID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Rajoitteet taululle `VarausRivit`
--
ALTER TABLE `VarausRivit`
  ADD CONSTRAINT `fk_varausrivi_varastorivi` FOREIGN KEY (`VarastoRiviID`) REFERENCES `Varasto_Rivit` (`VarastoRiviID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
