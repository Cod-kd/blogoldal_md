-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3307
-- Létrehozás ideje: 2025. Már 05. 08:21
-- Kiszolgáló verziója: 5.7.24
-- PHP verzió: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `blogoldal`
--
CREATE DATABASE IF NOT EXISTS `blogoldal` DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci;
USE `blogoldal`;

DELIMITER $$
--
-- Eljárások
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `registration` (IN `nameIn` VARCHAR(60), IN `emailIn` VARCHAR(60), IN `passwordIn` VARCHAR(64))   BEGIN
INSERT INTO `users`
    (`name`, `email`, `password`)
VALUES
	(nameIn, emailIn, passwordIn);
END$$

--
-- Függvények
--
CREATE DEFINER=`root`@`localhost` FUNCTION `getHashedPassword` (`emailIn` VARCHAR(60)) RETURNS VARCHAR(64) CHARSET utf8 COLLATE utf8_hungarian_ci  BEGIN
    DECLARE hashed_password VARCHAR(64);
	SELECT `password` INTO hashed_password FROM `users`
    WHERE `email`=emailIn;
    RETURN hashed_password;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(60) COLLATE utf8_hungarian_ci NOT NULL,
  `description` text COLLATE utf8_hungarian_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_hungarian_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'Hello', 'h@g.hu', 'password'),
(2, 'Dani', 'jani@j.hu', '71ed75ecad74950a16be587312a5fc11d405811ef7a05610da3ca85c233eb3df'),
(4, 'Dan', 'jan@j.hu', 'ad370ed99b189921e7fe26057c40aab9f4fee8385e47606f50f348b9a5530af0');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
