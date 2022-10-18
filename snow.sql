-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 05, 2022 at 02:50 PM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snow`
--

-- --------------------------------------------------------

--
-- Table structure for table `comptes`
--

CREATE TABLE `comptes` (
  `id_compte` int(2) NOT NULL,
  `nom_compte` varchar(50) NOT NULL,
  `prenom_compte` varchar(50) NOT NULL,
  `login_compte` varchar(100) NOT NULL,
  `pass_compte` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comptes`
--

INSERT INTO `comptes` (`id_compte`, `nom_compte`, `prenom_compte`, `login_compte`, `pass_compte`) VALUES
(18, 'admin', 'toto', 'admin', 0x30623963323632356463323165663035663661643464646634376335663230333833376161333263);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id_contact` int(4) UNSIGNED NOT NULL,
  `nom_contact` varchar(70) NOT NULL DEFAULT '',
  `prenom_contact` varchar(50) NOT NULL,
  `email_contact` varchar(200) NOT NULL,
  `message_contact` text,
  `date_contact` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id_contact`, `nom_contact`, `prenom_contact`, `email_contact`, `message_contact`, `date_contact`) VALUES
(1, 'vxc', 'dfvx', '123@yahoo.com', 'bcb', '2022-09-05 13:15:58'),
(2, 'bgd', 'nfgn', '456@yahoo.com', 'fnd', '2022-09-05 14:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id_page` int(3) NOT NULL,
  `id_rubrique` int(2) NOT NULL,
  `id_compte` int(3) NOT NULL,
  `titre_page` varchar(200) NOT NULL,
  `contenu_page` text NOT NULL,
  `fichier_page` varchar(250) DEFAULT NULL,
  `date_page` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id_page`, `id_rubrique`, `id_compte`, `titre_page`, `contenu_page`, `fichier_page`, `date_page`) VALUES
(31, 5, 18, 'SF', 'CSQ', '../medias/media31_b.jpg', '2022-09-05 13:40:42'),
(32, 3, 18, 'test image', 'image1', '../medias/media32_b.jpg', '2022-09-05 14:27:15'),
(36, 1, 18, 'xcw<c', '&lt;cc', '../medias/media36_b.jpg', '2022-09-05 15:30:23'),
(37, 7, 18, 'csd', 'csqcq', '../medias/media37_b.jpg', '2022-09-05 15:31:34'),
(38, 2, 18, 'Glisse shop Alpes', '<p>\r\n<span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at arcu ac lectus hendrerit pretium. Duis non rhoncus mi.&nbsp;</span>\r\n</p>\r\n<p>\r\n<span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);\">Phasellus efficitur tempus elit, et sodales diam accumsan at. Integer in augue vehicula, venenatis ex quis, porta est. Nam maximus nec nisi in volutpat. Sed porta sit amet erat quis consectetur. Sed sit amet metus volutpat, venenatis elit at, aliquet mauris. Quisque eu nisl massa.</span>\r\n</p>\r\n<p>\r\n<span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);\">&nbsp;Praesent volutpat, quam a vulputate maximus, quam ipsum cursus turpis, nec lobortis mi mauris malesuada purus. Suspendisse non nisi laoreet, convallis lorem a, feugiat dui.&nbsp;</span>\r\n</p>\r\n<p>\r\n<span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);\">Sed sed purus elit. Cras non rutrum orci. Suspendisse rutrum, nunc quis lacinia maximus, urna magna mollis lacus, vitae aliquet urna augue at turpis. Donec tincidunt pulvinar lorem vel sollicitudin. Phasellus aliquet enim pharetra aliquet placerat.</span>\r\n</p>', '../medias/media38_b.jpg', '2022-09-05 15:35:11'),
(39, 2, 18, 'Gliss shop PyrÃ©nÃ©es', '<p>\r\n<span style=\"font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);\">Integer a mi ac dolor lobortis elementum. Etiam euismod interdum ex vel commodo. Duis vel molestie felis. Sed tristique consectetur lectus quis interdum.Â </span>\r\n</p>\r\n<p>\r\n<span style=\"font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);\">Cras ut leo a enim vulputate mollis. Vivamus vel lobortis nisi, eget commodo erat. Sed pretium non felis ac condimentum. Ut id magna eget est aliquet tempus. Morbi non placerat lectus. Ut eget pulvinar mi. Duis sed euismod libero, sed vulputate urna.</span>\r\n</p>', '../medias/media39_b.jpg', '2022-09-05 13:42:10'),
(40, 7, 18, 'for autumn', '<h2>lorem ipum</h2>\r\n<p>\r\n<span style=\"font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);\">lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at arcu ac lectus hendrerit pretium. Duis non rhoncus mi. Phasellus efficitur tempus elit, et sodales diam accumsan at. Integer in augue vehicula, venenatis ex quis, porta est. Nam maximus nec nisi in volutpat</span>\r\n<br>\r\n</p>\r\n<h2>lorem ipum</h2>\r\n<h4>loremipum</h4>\r\n<p>\r\n<span style=\"font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);\">olrem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at arcu ac lectus hendrerit pretium. Duis non rhoncus mi. Phasellus efficitur tempus elit, et sodales diam accumsan at. Integer in augue vehicula, venenatis ex quis, porta est. Nam maximus nec nisi in volutpat</span>\r\n<br>\r\n</p>\r\n<p>\r\n<p>\r\n<ul>\r\n<li>lorem ipum</li>\r\n</ul>\r\n</p>\r\n</p>', '../medias/media40_b.png', '2022-09-05 14:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `rubriques`
--

CREATE TABLE `rubriques` (
  `id_rubrique` int(2) NOT NULL,
  `nom_rubrique` varchar(150) NOT NULL,
  `lien_rubrique` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rubriques`
--

INSERT INTO `rubriques` (`id_rubrique`, `nom_rubrique`, `lien_rubrique`) VALUES
(1, 'TEAMS', ''),
(2, 'EXPERIENCE', ''),
(3, 'SHOP', ''),
(5, 'CONTACT', ''),
(7, 'EVENTS', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comptes`
--
ALTER TABLE `comptes`
  ADD PRIMARY KEY (`id_compte`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id_contact`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id_page`),
  ADD KEY `id_rubrique` (`id_rubrique`),
  ADD KEY `id_compte` (`id_compte`);

--
-- Indexes for table `rubriques`
--
ALTER TABLE `rubriques`
  ADD PRIMARY KEY (`id_rubrique`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comptes`
--
ALTER TABLE `comptes`
  MODIFY `id_compte` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id_contact` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id_page` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `rubriques`
--
ALTER TABLE `rubriques`
  MODIFY `id_rubrique` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
