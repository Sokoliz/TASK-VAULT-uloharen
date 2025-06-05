-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2025 at 01:01 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

-- Základné nastavenia SQL režimu - vypnutie automatického dopĺňania nulových hodnôt
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- Začiatok transakcie - zabezpečí, že všetky príkazy budú vykonané ako jeden celok
START TRANSACTION;

-- Nastavenie časovej zóny na UTC
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kanban`
--

-- --------------------------------------------------------
-- Vytvorenie databázy, ak neexistuje
CREATE DATABASE IF NOT EXISTS `KANBAN`;

-- Výber databázy
USE `KANBAN`;

--
-- Table structure for table `calendar`
--

-- Vytvorenie tabuľky pre kalendárové udalosti používateľov
CREATE TABLE `CALENDAR` (
  `ID_EVENT` INT(11) NOT NULL, -- Jedinečný identifikátor udalosti
  `ID_USER` INT(11) DEFAULT NULL, -- Odkaz na používateľa, ktorý vytvoril udalosť
  `TITLE` VARCHAR(50) NOT NULL, -- Názov udalosti (povinný)
  `DESCRIPTION` VARCHAR(200) NOT NULL, -- Popis udalosti (povinný)
  `COLOUR` VARCHAR(7) CHARACTER SET UTF8 COLLATE UTF8_GENERAL_CI DEFAULT NULL, -- Farba udalosti v hexadecimálnom formáte
  `START_DATE` DATETIME NOT NULL, -- Dátum a čas začiatku udalosti
  `END_DATE` DATETIME DEFAULT NULL -- Dátum a čas konca udalosti (nepovinný)
) ENGINE=INNODB DEFAULT CHARSET=UTF8MB4 COLLATE=UTF8MB4_UNICODE_CI;

--
-- Dumping data for table `calendar`
--

-- Vloženie ukážkových dát do tabuľky calendar
INSERT INTO `CALENDAR` (
  `ID_EVENT`,
  `ID_USER`,
  `TITLE`,
  `DESCRIPTION`,
  `COLOUR`,
  `START_DATE`,
  `END_DATE`
) VALUES (
  38,
  20,
  'dd',
  'ddd',
  '#5bc0de',
  '2025-06-04 00:00:00',
  '2025-06-07 00:00:00'
),
(
  39,
  20,
  'go to home',
  'it is good to go to home',
  '#0275d8',
  '2025-06-06 06:30:00',
  '2025-06-06 08:00:00'
);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

-- Vytvorenie tabuľky pre projekty - hlavné entity Kanban systému
CREATE TABLE `PROJECTS` (
  `ID_PROJECT` INT(11) NOT NULL, -- Jedinečný identifikátor projektu
  `ID_USER` INT(11) NOT NULL, -- Odkaz na vlastníka projektu
  `PROJECT_NAME` VARCHAR(30) NOT NULL, -- Názov projektu (povinný, max 30 znakov)
  `PROJECT_DESCRIPTION` VARCHAR(255) NOT NULL, -- Popis projektu (povinný, max 255 znakov)
  `PROJECT_COLOUR` VARCHAR(7) NOT NULL, -- Farba projektu v hex formáte
  `START_DATE` DATE NOT NULL, -- Dátum začiatku projektu
  `END_DATE` DATE NOT NULL -- Dátum konca projektu
) ENGINE=INNODB DEFAULT CHARSET=UTF8MB4 COLLATE=UTF8MB4_UNICODE_CI;

--
-- Dumping data for table `projects`
--

-- Vloženie ukážkových projektov do tabuľky
INSERT INTO `PROJECTS` (
  `ID_PROJECT`,
  `ID_USER`,
  `PROJECT_NAME`,
  `PROJECT_DESCRIPTION`,
  `PROJECT_COLOUR`,
  `START_DATE`,
  `END_DATE`
) VALUES (
  142,
  1,
  'Project #1 long title',
  'This is an example of a project with a short description.',
  '#0275d8',
  '2021-09-01',
  '2021-11-30'
),
(
  143,
  1,
  'Project #2',
  'Another project example.',
  '#5bc0de',
  '2021-11-19',
  '2021-11-20'
),
(
  144,
  1,
  'Project #3',
  'LONG DESCRIPTION: \r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vitae erat at nisi porttitor rutrum nec sed tellus. Proin vel dictum leo. Interdum et malesuada fames ac ante ipsum primis in faucibus. Interdum et malesuada fames ac ant',
  '#5cb85c',
  '2021-11-20',
  '2021-12-31'
),
(
  145,
  1,
  'Max of chraracters per field',
  'The aximum of chraracters allowed for the title are 30.\r\nThe aximum of chraracters allowed for the title are 225.\r\n',
  '#f0ad4e',
  '2021-11-20',
  '2022-01-31'
),
(
  146,
  1,
  'Project colours available',
  'The colours available for the projects are the following ones: \r\n- blue, \r\n-tile, \r\n- green, \r\n- orange, \r\n- red \r\n- black. \r\n\r\nIn case of leaving the space empty, the automatic color chosen will be white.',
  '#d9534f',
  '2021-09-01',
  '2021-11-20'
),
(
  147,
  1,
  'Project fields',
  'Project name, start date and end date are needed in order to create a project. On the other side description and colour are not mandatory. ',
  '#292b2c',
  '2021-11-19',
  '2021-11-21'
),
(
  148,
  1,
  'Project dates',
  'The end date must be after the start date. Both dates are needed.',
  '',
  '2021-11-20',
  '2021-11-30'
),
(
  149,
  1,
  'Project without description',
  '',
  '#0275d8',
  '2021-11-22',
  '2021-11-24'
),
(
  151,
  15,
  'CC',
  'DD',
  '#0275d8',
  '2025-06-01',
  '2025-07-01'
),
(
  153,
  20,
  'asad',
  'good work',
  '#5cb85c',
  '2025-06-03',
  '2025-06-26'
),
(
  154,
  20,
  '2nd project',
  'hellow it is first ',
  '#5bc0de',
  '2025-06-03',
  '2025-06-03'
),
(
  155,
  20,
  'third projet',
  'dd',
  '#0275d8',
  '2025-06-18',
  '2025-06-30'
),
(
  156,
  24,
  'd',
  'dd',
  '#5bc0de',
  '2025-06-11',
  '2025-06-12'
);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

-- Vytvorenie tabuľky pre úlohy - základné prvky Kanban boardu
CREATE TABLE `TASKS` (
  `ID_TASK` INT(11) NOT NULL, -- Jedinečný identifikátor úlohy
  `ID_USER` INT(11) DEFAULT NULL, -- Odkaz na používateľa, ktorému je úloha priradená
  `ID_PROJECT` INT(11) DEFAULT NULL, -- Odkaz na projekt, ku ktorému úloha patrí
  `TASK_STATUS` INT(1) NOT NULL, -- Status úlohy (1=To Do, 2=In Progress, 3=Done)
  `TASK_NAME` VARCHAR(30) NOT NULL, -- Názov úlohy (povinný)
  `TASK_DESCRIPTION` VARCHAR(255) DEFAULT NULL, -- Popis úlohy (nepovinný)
  `TASK_COLOUR` VARCHAR(7) DEFAULT NULL, -- Farba úlohy v hex formáte
  `DEADLINE` DATE NOT NULL -- Termín dokončenia úlohy
) ENGINE=INNODB DEFAULT CHARSET=UTF8MB4 COLLATE=UTF8MB4_UNICODE_CI;

--
-- Dumping data for table `tasks`
--

-- Vloženie ukážkových úloh do tabuľky
INSERT INTO `TASKS` (
  `ID_TASK`,
  `ID_USER`,
  `ID_PROJECT`,
  `TASK_STATUS`,
  `TASK_NAME`,
  `TASK_DESCRIPTION`,
  `TASK_COLOUR`,
  `DEADLINE`
) VALUES (
  32,
  1,
  143,
  1,
  'Task #1',
  'An example of a task with description',
  '#5cb85c',
  '2021-11-20'
),
(
  34,
  15,
  151,
  3,
  'dd',
  'sss',
  '#5cb85c',
  '2025-06-03'
),
(
  35,
  15,
  151,
  2,
  'tsk2',
  'ddd',
  '#d9534f',
  '2025-06-02'
),
(
  36,
  15,
  151,
  2,
  'dddddd',
  'dd',
  '#5cb85c',
  '2025-06-18'
),
(
  38,
  20,
  153,
  2,
  'read books',
  'good habit ',
  '#d9534f',
  '2025-06-03'
),
(
  39,
  20,
  153,
  3,
  'd',
  'dd',
  '#5cb85c',
  '2025-06-03'
),
(
  40,
  20,
  153,
  1,
  'd',
  'dd',
  '#f0ad4e',
  '2025-06-03'
),
(
  41,
  24,
  156,
  2,
  'd',
  'dd',
  '',
  '0000-00-00'
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

-- Vytvorenie tabuľky pre používateľov systému
CREATE TABLE `USERS` (
  `ID_USER` INT(11) NOT NULL, -- Jedinečný identifikátor používateľa
  `USER` VARCHAR(50) NOT NULL, -- Prihlasovacie meno používateľa (musí byť unikátne)
  `PASSWORD` VARCHAR(200) NOT NULL -- Heslo používateľa (uložené ako hash)
) ENGINE=INNODB DEFAULT CHARSET=UTF8MB4 COLLATE=UTF8MB4_UNICODE_CI;

--
-- Dumping data for table `users`
--

-- Vloženie ukážkových používateľov do tabuľky
INSERT INTO `USERS` (
  `ID_USER`,
  `USER`,
  `PASSWORD`
) VALUES (
  1,
  'testuser',
  'testpassword'
),
(
  15,
  'asad',
  'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe'
),
(
  16,
  'ali',
  '$2y$10$pqelXBetnIGTb/3J76vzAuMFiehGiJ32JLqycLHmZuXA9Hv8nac1K'
),
(
  19,
  'rakib',
  '$2y$10$tkCMHBTN94q/BrfxcHvRluQ47DRvOr1GknQ8nZQ4trAnJfVeFKOnm'
),
(
  20,
  'a',
  '$2y$10$mUgLS9pcbi9FlKdKTWSu2ultRrFJGkVyPdY1ZdZV2iIrC8EuP/hAe'
),
(
  21,
  'b',
  '$2y$10$w4mBXZ.4sQjB0h5K6/n.H.O68QuuCbxDGpc7Te17cS2OYSMuMtMhe'
),
(
  22,
  'aaaa',
  '$2y$10$e8/H72Ewx5m0cDsSev0WpeXw3BIm0PTsVa9iJBzvl3rCejaEXpJ7i'
),
(
  23,
  'abc',
  '$2y$10$cMpzopK4tjD4T22ScoNgD.04xWlMwxnKvYkqVG2I/0M53WyXyVyoq'
),
(
  24,
  'aabc',
  '$2y$10$jS9DE9wrUulVfK.tnA9fV.TcowfBz1FtgDuB8kNSZsmg441trASmi'
);

--
-- Indexes for dumped tables
--

-- Definovanie primárnych a cudzích kľúčov pre optimalizáciu vyhľadávania

--
-- Indexes for table `calendar`
--
ALTER TABLE `CALENDAR`
  ADD PRIMARY KEY (
    `ID_EVENT`
  ), ADD KEY `ID_USER` (
    `ID_USER`
  );

--
-- Indexes for table `projects`
--
ALTER TABLE `PROJECTS`
  ADD PRIMARY KEY (
    `ID_PROJECT`
  ), ADD KEY `ID_USER` (
    `ID_USER`
  );

--
-- Indexes for table `tasks`
--
ALTER TABLE `TASKS`
  ADD PRIMARY KEY (
    `ID_TASK`
  ), ADD KEY `TASKS_ID_USER` (
    `ID_USER`
  ), ADD KEY `TASKS_ID_PROJECT` (
    `ID_PROJECT`
  );

--
-- Indexes for table `users`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (
    `ID_USER`
  ), ADD UNIQUE KEY `USER` (
    `USER`
  );

--
-- AUTO_INCREMENT for dumped tables
--

-- Nastavenie automatického inkrementovania ID hodnôt pre každú tabuľku

--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `CALENDAR` MODIFY `ID_EVENT` INT(
  11
) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `PROJECTS` MODIFY `ID_PROJECT` INT(
  11
) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `TASKS` MODIFY `ID_TASK` INT(
  11
) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `USERS` MODIFY `ID_USER` INT(
  11
) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

-- Definovanie referenčnej integrity medzi tabuľkami

--
-- Constraints for table `calendar`
--
ALTER TABLE `CALENDAR`
  ADD CONSTRAINT `CALENDAR_IBFK_1` FOREIGN KEY (
    `ID_USER`
  )
    REFERENCES `USERS` (
      `ID_USER`
    ) ON DELETE SET NULL;

-- Pri vymazaní používateľa sa jeho udalosti ponechajú, ale bez priradenia

--
-- Constraints for table `projects`
--
ALTER TABLE `PROJECTS`
  ADD CONSTRAINT `PROJECTS_IBFK_1` FOREIGN KEY (
    `ID_USER`
  )
    REFERENCES `USERS` (
      `ID_USER`
    ) ON DELETE CASCADE;

-- Pri vymazaní používateľa sa vymažú aj všetky jeho projekty

--
-- Constraints for table `tasks`
--
ALTER TABLE `TASKS`
  ADD CONSTRAINT `TASKS_ID_PROJECT` FOREIGN KEY (
    `ID_PROJECT`
  )
    REFERENCES `PROJECTS` (
      `ID_PROJECT`
    ) ON DELETE CASCADE, -- Pri vymazaní projektu sa vymažú aj všetky jeho úlohy
    ADD CONSTRAINT `TASKS_ID_USER` FOREIGN KEY (
      `ID_USER`
    )
      REFERENCES `USERS` (
        `ID_USER`
      ) ON DELETE SET NULL;

-- Pri vymazaní používateľa sa jeho úlohy ponechajú, ale bez priradenia

COMMIT;

-- Potvrdenie transakcie - uloženie všetkých vykonaných zmien

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;