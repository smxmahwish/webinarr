-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2018 at 06:20 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webinar`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `c_id` int(11) NOT NULL,
  `c_r_id` int(11) NOT NULL,
  `c_message` text NOT NULL,
  `c_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `r_id` int(11) NOT NULL,
  `r_s_id` int(11) NOT NULL,
  `r_live_link` varchar(250) NOT NULL,
  `r_attend_webinar` tinyint(4) NOT NULL DEFAULT '0',
  `r_firstname` varchar(255) NOT NULL,
  `r_email` varchar(255) NOT NULL,
  `r_email_sent` tinyint(4) NOT NULL DEFAULT '0',
  `r_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`r_id`, `r_s_id`, `r_live_link`, `r_attend_webinar`, `r_firstname`, `r_email`, `r_email_sent`, `r_datetime`) VALUES
(1, 30, '', 0, 'Mahwish', '0', 0, '2018-11-11 13:06:58'),
(2, 30, '', 0, 'Mahwish', '0', 0, '2018-11-11 13:09:49'),
(3, 30, '', 0, 'Mahwish', '0', 0, '2018-11-11 13:09:56'),
(4, 30, '', 0, 'Mahwish', '0', 0, '2018-11-11 13:11:27'),
(5, 30, '', 0, 'Mahwish', '0', 0, '2018-11-11 13:12:18'),
(6, 30, '', 0, 'Mahwish', '0', 0, '2018-11-11 13:12:39'),
(7, 30, '', 0, 'Mahwish', '0', 0, '2018-11-11 13:13:19'),
(8, 30, '', 0, 'Mahwish', '0', 0, '2018-11-11 13:13:46'),
(9, 26, '', 0, 'Mahwish', '0', 0, '2018-11-12 13:51:51'),
(10, 26, '', 0, 'Mahwish', '0', 0, '2018-11-12 14:37:35'),
(11, 19, '8b5120102e1fe645abe37ebda07ef476', 0, 'Mahwish', '0', 0, '2018-11-12 18:32:50'),
(12, 19, 'c21ecf79db3b391148536c63bcaab48a', 0, 'Mahwish', '0', 0, '2018-11-12 18:32:50'),
(13, 19, 'ff7f3950abf15c8867e866a9fcbdae7f', 0, 'Mahwish', '0', 0, '2018-11-12 18:33:10'),
(14, 19, '15052ba8f3b1da9c9f18ee0dd577dfcb', 0, 'Mahwish', '0', 0, '2018-11-12 18:33:11'),
(15, 19, 'ddb05a3b81bc0cf0dbbcd4ab38d43dde', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 18:37:36'),
(16, 26, '4188d32de459a0b276755c2f2acf346b', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 18:38:21'),
(17, 19, 'c6585ae062653efce7cada88087a7164', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:02:28'),
(18, 19, 'bc82c2eea295307f27f3e263c2d3975c', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:04:04'),
(19, 19, '759bc6d7dfe35aebf82c238f93ccf305', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:04:49'),
(20, 19, '1c4bbf724aacec02462fc0db9ffb394c', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:08:53'),
(21, 29, '2b00a5ecfe4b4e3a549163975634f4ea', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:20:56'),
(22, 29, 'aed82967fe4166ffaed17fa2c6816c11', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:27:10'),
(23, 29, '90745bb75a103a0d73682fa16cc6515f', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:28:45'),
(24, 29, 'ccaee0a163f70120f913fc2bdbbd8596', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:30:00'),
(25, 29, 'f937e63856990f2a4d8dd115e287ef12', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:33:35'),
(26, 29, '5fc9638a248cb9f3b7b8499083fe2c5d', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:33:58'),
(27, 29, '2762b0fb7216a936d31dd75328a05116', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:35:43'),
(28, 29, '7a4c0192bb8e95eb121cbfd5f975fcc9', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:36:28'),
(29, 29, '8d71e13d4778046eed14801650081b1d', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:42:52'),
(30, 29, 'c6b37f64f6ebe1587cbb23fd4a50c820', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:43:22'),
(31, 29, 'f0f350e9c5470457ff1ee852c102c30e', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 20:43:26'),
(32, 29, '5901736f0063a5eab87989bca995534b', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:44:11'),
(33, 29, 'd3263b3389af05aa251dee2fe4dee88a', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:45:25'),
(34, 29, '27c734e1161db3537131e1da1a4a3633', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:46:00'),
(35, 29, 'f0d18a96a36a84110f93470414a06bf9', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:46:48'),
(36, 29, '6443de52c7e2c686361f03f86571ef40', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:49:20'),
(37, 29, 'bd3454229e0c3e9e9278e0b281ce508b', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:49:57'),
(38, 29, 'ab0b9ff0299b8a5c8c061d99ef56699e', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:51:20'),
(39, 29, 'fcff151057ed9f387b7d55fd7b0597df', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:52:06'),
(40, 29, '5976908722f1f204a38f220d79185992', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:53:00'),
(41, 19, '6742aaa80fcf067fce3a110c2662e668', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:54:13'),
(42, 19, '853f7d6e3b046a137fc18e7e4ff453f1', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:54:24'),
(43, 19, '98641efeb9ae314cf20738bf5eec8310', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:55:17'),
(44, 19, '5c312d082c5f6ecf5343407c6459e1cc', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 20:57:03'),
(45, 26, '1e9ba6fc2e9da3ad48e4ea5fce2de102', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 21:00:58'),
(46, 19, '871da69cedaba13c0107aa33c193d7c7', 0, 'Mahwish', 'dsad@dsf.com', 1, '2018-11-12 21:04:34'),
(47, 19, 'eb12eb906aa500e777b372b66c55d375', 0, 'Mahwish', 'dsad@dsf.com', 1, '2018-11-12 21:04:41'),
(48, 19, 'e92eeec3dde44658d1840d810f73712c', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 21:05:50'),
(49, 19, 'd3d07e7c5c81ec98b88b855d23352703', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 21:05:58'),
(50, 19, '80f23468384943888f7e92f74b54b5df', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-12 21:10:25'),
(51, 19, 'cb5d5d0e03108f47fd73fdb26d588fba', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-12 21:15:08'),
(52, 26, '46d7a4e8562f1532bb07688a7076f448', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-13 13:58:40'),
(53, 19, 'f5e58299728432d482c0465ea95e45da', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-13 14:07:49'),
(54, 19, '06c7c14a704bb0728e538447a255389b', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-13 14:08:01'),
(55, 26, '00d4c547a0d17c0eb906f5ecb8f2d7c9', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-13 14:22:21'),
(56, 26, 'd53e396127e2e02d4793e93854b53818', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-13 14:47:50'),
(57, 26, 'ae7658859cad90eebad9d4bfd5e1f0f2', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-13 14:47:55'),
(58, 29, '9faccf144dee3f6fe46832718f1f5d7a', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-13 18:04:31'),
(59, 27, '6fc61c16446b2a9b67268ab55992a7bf', 0, 'Mahwish', 'mahwishumer93@gmail.com', 1, '2018-11-13 21:17:22'),
(60, 27, '38a493f3c22c6b43dc800498788c4b01', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-13 22:04:55'),
(61, 27, '10a27e4217b78c76dcc4a0a1a2159def', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-13 22:04:55'),
(62, 27, '36f45b6f8461b80f13932caca014aeac', 0, 'Mahwish', 'mahwishumer93@gmail.com', 0, '2018-11-13 22:04:56');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `s_id` int(11) NOT NULL,
  `s_w_id` int(11) NOT NULL,
  `s_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `s_end` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_add_date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`s_id`, `s_w_id`, `s_start`, `s_end`, `s_add_date_time`) VALUES
(16, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-11-03 20:12:51'),
(18, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-11-03 20:44:15'),
(19, 2, '2018-11-13 15:00:00', '2018-11-21 16:00:00', '2018-11-03 20:49:07'),
(20, 2, '2018-11-03 16:00:00', '2018-11-20 17:00:00', '2018-11-03 21:08:01'),
(21, 3, '2018-11-20 13:00:00', '2018-11-20 14:00:00', '2018-11-05 18:31:44'),
(22, 3, '2018-11-08 06:00:00', '2018-11-08 07:30:00', '2018-11-06 11:43:29'),
(23, 3, '2018-11-12 22:00:00', '2018-11-13 06:00:00', '2018-11-07 10:04:36'),
(24, 4, '2018-11-10 13:00:00', '2018-11-10 14:00:00', '2018-11-10 18:55:03'),
(25, 4, '2018-11-10 13:00:00', '2018-11-10 14:00:00', '2018-11-10 18:55:04'),
(26, 4, '2018-11-13 13:00:00', '2018-11-13 14:00:00', '2018-11-10 18:55:36'),
(27, 4, '2018-11-21 13:00:00', '2018-11-21 14:00:00', '2018-11-10 18:55:54'),
(28, 4, '2018-11-10 15:00:00', '2018-11-10 16:00:00', '2018-11-10 19:54:23'),
(29, 4, '2018-11-13 15:00:00', '2018-11-13 16:00:00', '2018-11-10 19:54:24'),
(30, 4, '2018-11-13 16:00:00', '2018-11-13 16:00:00', '2018-11-10 20:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `testimonial`
--

CREATE TABLE `testimonial` (
  `t_id` int(11) NOT NULL,
  `t_r_id` int(11) NOT NULL,
  `t_message` text NOT NULL,
  `t_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `webinar`
--

CREATE TABLE `webinar` (
  `w_id` int(11) NOT NULL,
  `w_name` varchar(500) NOT NULL,
  `w_presenter_name` varchar(255) NOT NULL,
  `w_intro_video` varchar(500) NOT NULL,
  `w_webinar_video` text,
  `w_thanks_video` text,
  `w_introduction` text NOT NULL,
  `w_sub_title` varchar(500) NOT NULL,
  `w_explaination` text NOT NULL,
  `w_add_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `webinar`
--

INSERT INTO `webinar` (`w_id`, `w_name`, `w_presenter_name`, `w_intro_video`, `w_webinar_video`, `w_thanks_video`, `w_introduction`, `w_sub_title`, `w_explaination`, `w_add_date`) VALUES
(2, 'Full Stack Web Developent', 'Mahwish', 'Mahwish', NULL, NULL, 'Mahwish', 'Mahwish', 'Mahwish hon ma', '2018-10-29 16:31:52'),
(3, 'SoftwareEngineering', 'sda', 'asds', NULL, NULL, 'ds', 'asd', 'asd', '2018-11-03 20:40:28'),
(4, 'Planning for Success: Starting Your Career In Software Development', 'Mahwish', 'https://www.youtube.com/embed/zpOULjyy-n8?rel=0', 'https://www.youtube.com/embed/zpOULjyy-n8?rel=0', 'https://www.youtube.com/embed/zpOULjyy-n8?rel=0', '<ul><li>Why You Need A Niche And A \"Proof Of Concept\"</li><li>The Secret Weapon Of ALL 7 Figure Coaches &amp; Consultants</li><li>The Simple 2 Step Funnel To Get High Ticket Clients</li></ul>', 'Training From SamexLLC', '<p>Sam Ovens started completely broke working out of his parents garage in New Zealand and in four short years built a wildly profitable consulting business, moved to Manhattan and made over $10 million dollars.</p><p>This FREE training class cut\'s right to the chase and reveals exactly how Sam was able to start and grow his consulting business so quickly and how you can do the same starting RIGHT NOW!</p><p>Sam has one of the best (if not the best) track records in this industry and has created 9 Millionaire consultants and 136 6-Figure consultants with his training.</p><p>Sam usually charges $6,000 - $36,000 to work with clients but this training reveals the exact same methods to you for FREE! Register now before this is taken offline in the new few days.</p>', '2018-11-10 18:16:15'),
(5, 'Planning for Success: Starting Your Career In Software Development', 'Mahwish', 'https://www.youtube.com/embed/zpOULjyy-n8?rel=0', 'https://www.youtube.com/embed/zpOULjyy-n8?rel=0', 'https://www.youtube.com/embed/zpOULjyy-n8?rel=0', '<ul><li>Why You Need A Niche And A \"Proof Of Concept\"</li><li>The Secret Weapon Of ALL 7 Figure Coaches &amp; Consultants</li><li>The Simple 2 Step Funnel To Get High Ticket Clients</li></ul>', 'Training From SamexLLC', '<p>Sam Ovens started completely broke working out of his parents garage in New Zealand and in four short years built a wildly profitable consulting business, moved to Manhattan and made over $10 million dollars.</p><p>This FREE training class cut\'s right to the chase and reveals exactly how Sam was able to start and grow his consulting business so quickly and how you can do the same starting RIGHT NOW!</p><p>Sam has one of the best (if not the best) track records in this industry and has created 9 Millionaire consultants and 136 6-Figure consultants with his training.</p><p>Sam usually charges $6,000 - $36,000 to work with clients but this training reveals the exact same methods to you for FREE! Register now before this is taken offline in the new few days.</p>', '2018-11-13 18:39:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `c_r_id` (`c_r_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`r_id`),
  ADD KEY `r_s_id` (`r_s_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`s_id`),
  ADD KEY `schedule_ibfk_1` (`s_w_id`);

--
-- Indexes for table `testimonial`
--
ALTER TABLE `testimonial`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `t_r_id` (`t_r_id`);

--
-- Indexes for table `webinar`
--
ALTER TABLE `webinar`
  ADD PRIMARY KEY (`w_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `testimonial`
--
ALTER TABLE `testimonial`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webinar`
--
ALTER TABLE `webinar`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`c_r_id`) REFERENCES `register` (`r_id`);

--
-- Constraints for table `register`
--
ALTER TABLE `register`
  ADD CONSTRAINT `register_ibfk_1` FOREIGN KEY (`r_s_id`) REFERENCES `schedule` (`s_id`);

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`s_w_id`) REFERENCES `webinar` (`w_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `testimonial`
--
ALTER TABLE `testimonial`
  ADD CONSTRAINT `testimonial_ibfk_1` FOREIGN KEY (`t_r_id`) REFERENCES `register` (`r_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
