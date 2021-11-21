-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: db
-- Üretim Zamanı: 21 Kas 2021, 20:31:31
-- Sunucu sürümü: 10.3.32-MariaDB-1:10.3.32+maria~focal
-- PHP Sürümü: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `app`
--

-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(36) UNSIGNED NOT NULL,
  `orderCode` varchar(20) COLLATE utf8_turkish_ci NOT NULL,
  `productid` int(20) NOT NULL,
  `quantity` int(20) NOT NULL,
  `address` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `shippingDate` int(12) NOT NULL,
  `owner` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orderCode` (`orderCode`);

-- --------------------------------------------------------
DROP TABLE IF EXISTS `token`;
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `token`
--

CREATE TABLE `token` (
  `id` int(20) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `user` int(20) NOT NULL,
  `expire` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(36) NOT NULL,
  `username` varchar(36) DEFAULT NULL,
  `password` varchar(36) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'customer1', '1234'),
(2, 'customer2', '1234'),
(3, 'customer3', '1234'),
(4, 'customer4', '1234'),
(5, 'customer5', '12345');

--
-- Dökümü yapılmış tablolar için indeksler
--



--
-- Tablo için indeksler `token`
--
ALTER TABLE `token`
  ADD UNIQUE KEY `id` (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(36) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `token`
--
ALTER TABLE `token`
  MODIFY `id` int(36) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(36) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

