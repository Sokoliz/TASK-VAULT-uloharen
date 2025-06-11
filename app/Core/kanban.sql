-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: St 11.Jún 2025, 20:54
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `kanban`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `calendar`
--

CREATE TABLE `calendar` (
  `id_event` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `colour` varchar(7) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sťahujem dáta pre tabuľku `calendar`
--

INSERT INTO `calendar` (`id_event`, `id_user`, `title`, `description`, `colour`, `start_date`, `end_date`) VALUES
(38, 20, 'dd', 'ddd', '#5bc0de', '2025-06-04 00:00:00', '2025-06-07 00:00:00'),
(39, 20, 'go to home', 'it is good to go to home', '#0275d8', '2025-06-06 06:30:00', '2025-06-06 08:00:00'),
(64, 31, 'asd', 'asd', '#5bc0de', '2025-06-10 00:00:00', '2025-06-11 00:00:00'),
(68, 25, 'asd', '', '#0275d8', '2025-06-11 00:00:00', '2025-06-22 00:00:00');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `projects`
--

CREATE TABLE `projects` (
  `id_project` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `project_name` varchar(30) NOT NULL,
  `project_description` varchar(255) NOT NULL,
  `project_colour` varchar(7) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sťahujem dáta pre tabuľku `projects`
--

INSERT INTO `projects` (`id_project`, `id_user`, `project_name`, `project_description`, `project_colour`, `start_date`, `end_date`) VALUES
(142, 1, 'Project #1 long title', 'This is an example of a project with a short description.', '#0275d8', '2021-09-01', '2021-11-30'),
(143, 1, 'Project #2', 'Another project example.', '#5bc0de', '2021-11-19', '2021-11-20'),
(144, 1, 'Project #3', 'LONG DESCRIPTION: \r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vitae erat at nisi porttitor rutrum nec sed tellus. Proin vel dictum leo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Interdum et malesuada fames ac ant', '#5cb85c', '2021-11-20', '2021-12-31'),
(145, 1, 'Max of chraracters per field', 'The aximum of chraracters allowed for the title are 30.\r\nThe aximum of chraracters allowed for the title are 225.\r\n', '#f0ad4e', '2021-11-20', '2022-01-31'),
(146, 1, 'Project colours available', 'The colours available for the projects are the following ones: \r\n- blue, \r\n-tile, \r\n- green, \r\n- orange, \r\n- red \r\n- black. \r\n\r\nIn case of leaving the space empty, the automatic color chosen will be white.', '#d9534f', '2021-09-01', '2021-11-20'),
(147, 1, 'Project fields', 'Project name, start date and end date are needed in order to create a project. On the other side description and colour are not mandatory. ', '#292b2c', '2021-11-19', '2021-11-21'),
(148, 1, 'Project dates', 'The end date must be after the start date. Both dates are needed.', '', '2021-11-20', '2021-11-30'),
(149, 1, 'Project without description', '', '#0275d8', '2021-11-22', '2021-11-24'),
(151, 15, 'CC', 'DD', '#0275d8', '2025-06-01', '2025-07-01'),
(153, 20, 'asad', 'good work', '#5cb85c', '2025-06-03', '2025-06-26'),
(154, 20, '2nd project', 'hellow it is first ', '#5bc0de', '2025-06-03', '2025-06-03'),
(155, 20, 'third projet', 'dd', '#0275d8', '2025-06-18', '2025-06-30'),
(156, 24, 'd', 'dd', '#5bc0de', '2025-06-11', '2025-06-12'),
(159, 27, 'asd', 'asd', '#5cb85c', '2025-05-25', '2025-05-31'),
(160, 27, 'asd', 'asd', '#5cb85c', '2025-05-25', '2025-05-30'),
(161, 27, 'sad', 'asd', '', '2025-06-10', '2025-06-17'),
(162, 27, 'asd', '', '', '2025-06-10', '2025-06-17'),
(163, 27, 'asd', 'asd', '#d9534f', '2025-06-10', '2025-06-17'),
(164, 27, 'asd', 'asd', '#f0ad4e', '2025-06-10', '2025-06-17'),
(165, 27, 'asd', 'asd', '#f0ad4e', '2025-06-10', '2025-06-17'),
(166, 27, 'sad', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(167, 27, 'sad', 'sad', '#5cb85c', '2025-06-10', '2025-06-17'),
(168, 27, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(169, 27, 'asd', 'sda', '#5cb85c', '2025-06-10', '2025-06-17'),
(170, 27, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(171, 27, 'das', 'sad', '#5cb85c', '2025-06-10', '2025-06-17'),
(172, 27, 'sad', 'asd', '#f0ad4e', '2025-06-10', '2025-06-17'),
(173, 27, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(174, 27, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(175, 27, 'sad', 'asd', '#f0ad4e', '2025-06-10', '2025-06-17'),
(176, 27, 'ads', 'asd', '#d9534f', '2025-06-10', '2025-06-17'),
(177, 27, 'sad', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(178, 27, 'asd', '', '#5cb85c', '2025-06-10', '2025-06-17'),
(179, 27, 'asd', '', '#5cb85c', '2025-06-10', '2025-06-17'),
(180, 27, 'asd', '', '#5cb85c', '2025-06-10', '2025-06-17'),
(181, 27, 'asd', '', '#5cb85c', '2025-06-10', '2025-06-17'),
(182, 27, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(183, 27, 'asd', '', '#5cb85c', '2025-06-10', '2025-06-17'),
(184, 27, 'asd', '', '#5cb85c', '2025-06-10', '2025-06-17'),
(185, 27, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(186, 27, 'asd', '', '#d9534f', '2025-06-10', '2025-06-17'),
(187, 27, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(188, 27, 'asd', '', '#5cb85c', '2025-06-10', '2025-06-17'),
(192, 29, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(203, 31, 'asd', 'asd', '#5cb85c', '2025-06-10', '2025-06-17'),
(205, 25, 'asd', 'asd', '#5cb85c', '2025-06-11', '2025-06-18'),
(206, 25, 'asd', '', '#5cb85c', '2025-06-11', '2025-06-18');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `tasks`
--

CREATE TABLE `tasks` (
  `id_task` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_project` int(11) DEFAULT NULL,
  `task_status` int(1) NOT NULL,
  `task_name` varchar(30) NOT NULL,
  `task_description` varchar(255) DEFAULT NULL,
  `task_colour` varchar(7) DEFAULT NULL,
  `deadline` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sťahujem dáta pre tabuľku `tasks`
--

INSERT INTO `tasks` (`id_task`, `id_user`, `id_project`, `task_status`, `task_name`, `task_description`, `task_colour`, `deadline`) VALUES
(32, 1, 143, 1, 'Task #1', 'An example of a task with description', '#5cb85c', '2021-11-20'),
(34, 15, 151, 3, 'dd', 'sss', '#5cb85c', '2025-06-03'),
(35, 15, 151, 2, 'tsk2', 'ddd', '#d9534f', '2025-06-02'),
(36, 15, 151, 2, 'dddddd', 'dd', '#5cb85c', '2025-06-18'),
(38, 20, 153, 2, 'read books', 'good habit ', '#d9534f', '2025-06-03'),
(39, 20, 153, 3, 'd', 'dd', '#5cb85c', '2025-06-03'),
(40, 20, 153, 1, 'd', 'dd', '#f0ad4e', '2025-06-03'),
(41, 24, 156, 2, 'd', 'dd', '', '0000-00-00'),
(51, 29, 192, 2, 'asd', 'asd', '#f0ad4e', '2025-06-10'),
(52, 29, 192, 1, 'asdasdas', 'asd', '#f0ad4e', '6225-02-10'),
(67, 31, 203, 2, 'asd', '', '#f0ad4e', '2025-06-10');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id_user`, `user`, `password`) VALUES
(1, 'testuser', 'testpassword'),
(15, 'asad', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe'),
(16, 'ali', '$2y$10$pqelXBetnIGTb/3J76vzAuMFiehGiJ32JLqycLHmZuXA9Hv8nac1K'),
(19, 'rakib', '$2y$10$tkCMHBTN94q/BrfxcHvRluQ47DRvOr1GknQ8nZQ4trAnJfVeFKOnm'),
(20, 'a', '$2y$10$mUgLS9pcbi9FlKdKTWSu2ultRrFJGkVyPdY1ZdZV2iIrC8EuP/hAe'),
(21, 'b', '$2y$10$w4mBXZ.4sQjB0h5K6/n.H.O68QuuCbxDGpc7Te17cS2OYSMuMtMhe'),
(22, 'aaaa', '$2y$10$e8/H72Ewx5m0cDsSev0WpeXw3BIm0PTsVa9iJBzvl3rCejaEXpJ7i'),
(23, 'abc', '$2y$10$cMpzopK4tjD4T22ScoNgD.04xWlMwxnKvYkqVG2I/0M53WyXyVyoq'),
(24, 'aabc', '$2y$10$jS9DE9wrUulVfK.tnA9fV.TcowfBz1FtgDuB8kNSZsmg441trASmi'),
(25, 'asd', '$2y$10$SSjbgNWkNO2VAW0EBAJ3xO3d3vy.LDw5n5AT3ivyZiRlfsKOBta5G'),
(26, 'as', '$2y$10$lUv8HOh84eqNm8jVg35jyui/J.1cgiQb/A0O7EP0sV.t/RY3RrPvq'),
(27, 'ahojky', '$2y$10$orSkSUQD1r5K/BojZ7d3vOJgEY89hnFD7QVqtJotazB5JE4TVAxxS'),
(28, 'Marek', '$2y$10$nN.EnFWEghbCM0rGZsmbeem9ZhmoAUkAvrc1kwiLIP3K4Tw5Yxsd.'),
(29, 'ahoj', '$2y$10$66jqjcBq0EK8LrVdPM35Ruz19labBGZGpxCdI40IbmqFLExKD8RTq'),
(30, 'aaa', '$2y$10$Fm2Mv0SDJYtY1KwToXo5VePJLZCEmFASCJRfHFqN3Konyj.3jSg6C'),
(31, 'asda', '$2y$10$GVYX2mQB/T2XRxWVcJn/0ud5FMRJO62NOFSNMzhcGFmrB.YZoRpeu'),
(32, 'qw', '$2y$10$IhVfvxEZ8x0zPFRifWzQNeQ3G6.03uwrizxO4lGUqEFLLq.H77Y92');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id_event`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexy pre tabuľku `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id_project`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexy pre tabuľku `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id_task`),
  ADD KEY `tasks_id_user` (`id_user`),
  ADD KEY `tasks_id_project` (`id_project`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `user` (`user`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pre tabuľku `projects`
--
ALTER TABLE `projects`
  MODIFY `id_project` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT pre tabuľku `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id_task` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `calendar`
--
ALTER TABLE `calendar`
  ADD CONSTRAINT `calendar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Obmedzenie pre tabuľku `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_id_project` FOREIGN KEY (`id_project`) REFERENCES `projects` (`id_project`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
