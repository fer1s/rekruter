-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 03, 2024 at 12:52 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rekruter`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `entries`
--

CREATE TABLE `entries` (
  `id` int(11) NOT NULL,
  `recruitment` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `entry_type` tinyint(1) NOT NULL,
  `accepted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `recruitments`
--

CREATE TABLE `recruitments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `entries_limit` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `creation_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruitments`
--

-- INSERT INTO `recruitments` (`id`, `name`, `description`, `entries_limit`, `created_by`, `creation_date`) VALUES
-- (1, 'Kurs JS', 'Lorem ipsum dolorum...', 10, 15, '2024-03-09'),
-- (6, 'Kurs Python', 'W tym kursie nauczysz się podstaw programowania w Pythonie.\nUtworzymy pare projektów tj. \n- Serwer HTTP\n- Skrypt do wyszukiwania xyz', 10, 15, '2024-03-09'),
-- (7, 'Kurs C#', 'Podstawy programowania C#', 20, 15, '2024-03-09'),
-- (8, 'Kurs Szkolny', '123123123', 5, 15, '2024-04-26');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permission_level` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

-- INSERT INTO `users` (`id`, `username`, `email`, `password`, `permission_level`) VALUES
-- (15, 'Jakub Molenda', 'test1@mail.com', 'pass hash', 1),
-- (18, 'Jakub Molenda', 'test2@mail.com', 'pass hash', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `entries`
--
ALTER TABLE `entries`
  ADD KEY `recruitment` (`recruitment`),
  ADD KEY `user` (`user`);

--
-- Indeksy dla tabeli `recruitments`
--
ALTER TABLE `recruitments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `recruitments`
--
ALTER TABLE `recruitments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `entries`
--
ALTER TABLE `entries`
  ADD CONSTRAINT `entries_ibfk_1` FOREIGN KEY (`recruitment`) REFERENCES `recruitments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entries_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recruitments`
--
ALTER TABLE `recruitments`
  ADD CONSTRAINT `recruitments_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
