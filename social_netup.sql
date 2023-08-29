-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 05 Ağu 2023, 09:36:15
-- Sunucu sürümü: 10.4.27-MariaDB
-- PHP Sürümü: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `social_netup`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `kullanici_adi` varchar(50) DEFAULT NULL,
  `mesaj` text DEFAULT NULL,
  `resim` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `comment`
--

INSERT INTO `comment` (`id`, `kullanici_id`, `kullanici_adi`, `mesaj`, `resim`) VALUES
(1, 1, 'Kullanıcı Adı', 'Merhaba, bu bir örnek mesajdır.', 'resim_yolu.jpg'),
(2, 1, 'admin', 'deneme yazısı', ''),
(3, 1, 'admin', 'deneme yazısı 2', 'WhatsApp Image 2023-04-25 at 08.59.50.jpeg'),
(4, 0, 'admin', 'deneme yazısı 2', 'WhatsApp Image 2023-04-25 at 08.59.50.jpeg'),
(5, 0, 'admin', 'falan filan', 'logo-it-kopya.png'),
(6, 0, 'admin', 'sanane', 'İstanbul Gelişim Üniversitesi Tercih ve Tanıtım Filmi 2023.mp4'),
(7, 0, '', 'falan', 'logo-it-kopya.png');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(11) NOT NULL,
  `kullanici_sifre` text NOT NULL,
  `ad_soyad` varchar(11) NOT NULL,
  `email` text NOT NULL,
  `country` text NOT NULL,
  `phone` int(11) NOT NULL,
  `gender` text NOT NULL,
  `age` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `kullanici_adi`, `kullanici_sifre`, `ad_soyad`, `email`, `country`, `phone`, `gender`, `age`) VALUES
(6, 'emir123', '3387f4186648723a45ff3a7e12a40676b9e4769d', 'Emir Taha K', 'emirtahakarci12@gmail.com', 'Turkey', 0, 'male', '23'),
(7, 'admin', 'd72a90416f07284bef342f088aa4337137131b02', 'admin', 'admin@gelisim.edu.tr', 'Turkey', 0, 'male', '20');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
