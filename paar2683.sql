-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: mai 14, 2026 la 08:22 AM
-- Versiune server: 10.4.32-MariaDB
-- Versiune PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `paar2683`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'FPS'),
(2, 'Sport'),
(3, 'Tactical FPS'),
(4, 'Survival');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `price` varchar(20) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `platform`, `price`, `image_path`, `description`) VALUES
(1, 'EA SPORTS FC 26 - Antony GOAT Edition', 2, 'PS5, Xbox, PC', '119.99', 'pngs/ea_fc26.webp', 'Experimentează magia braziliană și celebra rotire legendară cu tehnologia Hypermotion V.'),
(2, 'Counter-Strike 2', 1, 'PC, macOS, Linux', 'Gratuit', 'pngs/csgo.png', 'Joc de tactică și strategie pentru echipe competitive.'),
(3, 'Rocket League', 2, 'PC, PS5, Xbox, Switch', '29.99', 'pngs/rocket.png', 'Fotbal cu mașini în lume virtuală.'),
(4, 'Valorant', 3, 'PC', 'Gratuit', 'pngs/valorant.png', 'Shooter tactic cu abilități speciale.'),
(5, 'Minecraft', 4, 'PC', '30.00', 'pngs/1778717608_Minecraft.webp', 'Un player spawnat într-o lume cubică învață să supraviețuiască');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT 'pngs/default_user.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `profile_image`) VALUES
(1, 'admin', '$2y$10$88fF9G9a4mH5ibYA.nJFWOjSBYh4DbWFdL9X8tX.ahFRQX.b2tvmq', 'admin', 'pngs/avatar_1_1778717319.png'),
(2, 'Alex', '$2y$10$8bLWuYWPM7WG.HrI30hUl.tlOSVE5jITnDeRk0cioOizNeIToxUpe', 'user', 'pngs/default_user.jpg'),
(3, 'Mihai', '$2y$10$LJeupXvTr3Jq3f9J39XygeSr5J.HIWPyhgoDsv.2oLQRfW5Bz/B3y', 'user', 'pngs/default_user.jpg');

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexuri pentru tabele `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexuri pentru tabele `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pentru tabele `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pentru tabele `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
