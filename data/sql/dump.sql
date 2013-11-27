-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 21, 2013 at 01:22 PM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `checkit`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `restore_password_key` varchar(32) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `restore_password_key`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', 'ISSArt', 'admin@issart.com', '7cb2483a727ea99c0b90343efc9c0ccd-:7_&PsXM', NULL, 1, '2013-11-21 13:21:40', '2013-11-21 13:21:40');

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE IF NOT EXISTS `alert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `msg_type` varchar(10) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `alert`
--

INSERT INTO `alert` (`id`, `user_id`, `property_id`, `msg_type`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 'success', 'Welcome To OMMI!', 'Welcome To OMMI!', 0, '2013-11-21 13:21:40', '2013-11-21 13:21:40'),
(2, 5, NULL, 'info', 'Dear freand! This is a best alert message for you...', 'Dear freand! This is a best alert message for you... Dear freand! This is a best alert message for you... Dear freand! This is a best alert message for you...', 0, '2013-11-21 13:21:40', '2013-11-21 13:21:40'),
(3, 5, NULL, 'warning', 'Warning!!!', 'Warning message', 0, '2013-11-21 13:21:40', '2013-11-21 13:21:40'),
(4, 1, NULL, 'success', 'Welcome To OMMI!', 'Welcome To OMMI!', 0, '2013-11-21 13:21:40', '2013-11-21 13:21:40'),
(5, 1, 1, 'info', 'alert_propertyApplication_insert_owner_title', 'alert_propertyApplication_insert_owner_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(6, 5, 1, 'success', 'alert_propertyApplication_insert_visitor_title', 'alert_propertyApplication_insert_visitor_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(7, 5, 1, 'info', 'alert_propertyApplication_statusChange_title', 'alert_propertyApplication_statusChange_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(8, 1, 1, 'info', 'alert_propertyApplication_insert_owner_title', 'alert_propertyApplication_insert_owner_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(9, 5, 1, 'success', 'alert_propertyApplication_insert_visitor_title', 'alert_propertyApplication_insert_visitor_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(10, 5, 1, 'info', 'alert_propertyApplication_statusChange_title', 'alert_propertyApplication_statusChange_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(11, 1, 1, 'info', 'alert_propertyApplication_insert_owner_title', 'alert_propertyApplication_insert_owner_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(12, 5, 1, 'success', 'alert_propertyApplication_insert_visitor_title', 'alert_propertyApplication_insert_visitor_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(13, 5, 1, 'info', 'alert_propertyApplication_statusChange_title', 'alert_propertyApplication_statusChange_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(14, 1, 1, 'info', 'alert_propertyApplication_insert_owner_title', 'alert_propertyApplication_insert_owner_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(15, 5, 1, 'success', 'alert_propertyApplication_insert_visitor_title', 'alert_propertyApplication_insert_visitor_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(16, 5, 1, 'info', 'alert_propertyApplication_statusChange_title', 'alert_propertyApplication_statusChange_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(17, 1, 2, 'info', 'alert_propertyApplication_insert_owner_title', 'alert_propertyApplication_insert_owner_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(18, 5, 2, 'success', 'alert_propertyApplication_insert_visitor_title', 'alert_propertyApplication_insert_visitor_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(19, 5, 2, 'info', 'alert_propertyApplication_statusChange_title', 'alert_propertyApplication_statusChange_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(20, 1, 2, 'info', 'alert_propertyApplication_insert_owner_title', 'alert_propertyApplication_insert_owner_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(21, 5, 2, 'success', 'alert_propertyApplication_insert_visitor_title', 'alert_propertyApplication_insert_visitor_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(22, 5, 2, 'info', 'alert_propertyApplication_statusChange_title', 'alert_propertyApplication_statusChange_body', 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(23, 4, 3, 'info', 'alert_propertyApplication_insert_owner_title', 'alert_propertyApplication_insert_owner_body', 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(24, 5, 3, 'success', 'alert_propertyApplication_insert_visitor_title', 'alert_propertyApplication_insert_visitor_body', 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(25, 5, 3, 'info', 'alert_propertyApplication_statusChange_title', 'alert_propertyApplication_statusChange_body', 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE IF NOT EXISTS `favorite` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `property_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`property_id`),
  KEY `favorite_property_id_property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`user_id`, `property_id`) VALUES
(5, 1),
(5, 2),
(5, 3),
(4, 4),
(5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_id` int(11) DEFAULT NULL,
  `handle` varchar(32) DEFAULT NULL,
  `body` text,
  `md5` varchar(32) DEFAULT NULL,
  `timeout` decimal(14,4) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `handle` (`handle`),
  KEY `message_queueid_idx` (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `message`
--


-- --------------------------------------------------------

--
-- Table structure for table `metro_line`
--

CREATE TABLE IF NOT EXISTS `metro_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `color` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `metro_line`
--

INSERT INTO `metro_line` (`id`, `name`, `color`) VALUES
(1, '1', 'f2c931'),
(2, '2', '216eb4'),
(3, '3', '9a9940'),
(4, '3bis', '89d3de'),
(5, '4', 'bb4d98'),
(6, '5', 'f68f4d'),
(7, '6', '78c696'),
(8, '7', 'f59eb3'),
(9, '7bis', '78c696'),
(10, '8', 'c5a3cc'),
(11, '9', 'cec82b'),
(12, '10', 'e0b03a'),
(13, '11', 'cccccc'),
(14, '12', '028d5b'),
(15, '13', '6b9ea6'),
(16, '14', '672f8f'),
(17, 'A', '672f8f'),
(18, 'B', '672f8f'),
(19, 'C', '672f8f'),
(20, 'D', '672f8f'),
(21, 'E', '672f8f'),
(22, 'FUN', '672f8f'),
(23, 'ORV', '672f8f');

-- --------------------------------------------------------

--
-- Table structure for table `metro_station`
--

CREATE TABLE IF NOT EXISTS `metro_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metro_line_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `pixel_x` int(11) DEFAULT NULL,
  `pixel_y` int(11) DEFAULT NULL,
  `sort_position` tinyint(4) DEFAULT '0',
  `latitude` double(18,10) DEFAULT NULL,
  `longitude` double(18,10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `metro_line_id_idx` (`metro_line_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=332 ;

--
-- Dumping data for table `metro_station`
--

INSERT INTO `metro_station` (`id`, `metro_line_id`, `name`, `pixel_x`, `pixel_y`, `sort_position`, `latitude`, `longitude`) VALUES
(1, 16, 'Olympiades', 5380, 6100, 9, 48.8268582997, 2.3672970120),
(2, 16, 'Bercy', 5800, 5550, 6, 48.8404280893, 2.3795829688),
(3, 16, 'Bibliotheque-Francois Mitterrand', 5750, 5950, 8, 48.8296059727, 2.3760926117),
(4, 16, 'Châtelet', 4158, 4251, 4, 48.8585195224, 2.3471194011),
(5, 16, 'Saint-Lazare', 2912, 2967, 1, 48.8755778692, 2.3261856586),
(6, 16, 'Gare de Lyon', 5340, 5139, 5, 48.8447045451, 2.3740659075),
(7, 16, 'Cour Saint-Emilion', 5920, 5830, 7, 48.8334843072, 2.3876129884),
(8, 16, 'Pyramides', 3290, 3767, 3, 48.8669463021, 2.3336650658),
(9, 16, 'Madeleine', 2668, 3723, 2, 48.8700643518, 2.3251637003),
(10, 14, 'Abbesses', 3868, 2103, 7, 48.8844176452, 2.3387128117),
(11, 5, 'Alésia', 3882, 6192, 25, 48.8283985143, 2.3267456737),
(12, 2, 'Alexandre-Dumas', 6125, 4106, 22, 48.8561744490, 2.3949898158),
(13, 11, 'Alma-Marceau', 2131, 3917, 14, 48.8646460307, 2.3008833661),
(14, 3, 'Anatole-France', 2025, 1877, 2, 48.8924723754, 2.2853738784),
(15, 23, 'Antony', 3515, 7422, 1, 48.7547197228, 2.3013137249),
(16, 2, 'Anvers', 4163, 2265, 12, 48.8831322903, 2.3439728252),
(17, 1, 'Argentine', 1755, 3028, 6, 48.8755330929, 2.2893216640),
(18, 3, 'Arts-et-Métiers', 4853, 3603, 17, 48.8652929437, 2.3563777809),
(19, 15, 'Asnieres-Gennevilliers Les Courtilles', 1958, 679, 10, 48.9301634126, 2.2840744717),
(20, 14, 'Assemblée Nationale', 2714, 4330, 15, 48.8608996857, 2.3206322378),
(21, 8, 'Aubervilliers Pantin (4 Chemins)', 5965, 1589, 3, 48.9038299162, 2.3920219501),
(22, 12, 'Avenue Emile-Zola', 1982, 5116, 11, 48.8469642746, 2.2949380752),
(23, 2, 'Avron', 6149, 4390, 23, 48.8509782114, 2.3979801301),
(24, 10, 'Balard', 1709, 6120, 1, 48.8362754635, 2.2782183270),
(25, 5, 'Barbès-Rochechouart', 4518, 2273, 5, 48.8836207315, 2.3497371943),
(26, 15, 'Basilique de Saint-Denis', 4568, 718, 2, 48.9366033097, 2.3594147175),
(27, 10, 'Bastille', 5085, 4375, 21, 48.8528417310, 2.3691806051),
(28, 7, 'Bel-Air', 6300, 5083, 26, 48.8418961424, 2.4012056271),
(29, 2, 'Belleville', 5564, 2987, 17, 48.8720416042, 2.3768056077),
(30, 1, 'Bérault', 6962, 4988, 24, 48.8453342425, 2.4292384979),
(31, 7, 'Bercy', 5805, 5576, 23, 48.8404280893, 2.3795829688),
(32, 11, 'Billancourt', 827, 6164, 2, 48.8319437593, 2.2381099971),
(33, 7, 'Bir-Hakeim (Grenelle)', 1710, 4665, 6, 48.8543331586, 2.2887828558),
(34, 2, 'Blanche', 3471, 2257, 10, 48.8836536085, 2.3316675232),
(35, 6, 'Bobigny-Pablo-Picasso', 7001, 1711, 1, 48.9069699443, 2.4496207174),
(36, 6, 'Bobigny-Pantin (Raymond Queneau)', 6801, 1912, 2, 48.8952689905, 2.4248018777),
(37, 7, 'Boissière', 1481, 3721, 3, 48.8670270457, 2.2904738454),
(38, 9, 'Bolivar', 5701, 2590, 3, 48.8805834299, 2.3739778330),
(39, 11, 'Bonne Nouvelle', 4409, 3275, 23, 48.8705587205, 2.3486795697),
(40, 9, 'Botzaris', 6142, 2651, 5, 48.8793377265, 2.3887339915),
(41, 10, 'Boucicaut', 2085, 5761, 3, 48.8412408954, 2.2876343175),
(42, 12, 'Boulogne-Jean-Jaurès', 709, 5539, 2, 48.8422216613, 2.2388358502),
(43, 12, 'Boulogne Pont de Saint-Cloud', 562, 5685, 1, 48.8407983651, 2.2282112904),
(44, 3, 'Bourse', 3792, 3690, 14, 48.8687976839, 2.3412326833),
(45, 6, 'Bréguet-Sabin', 5255, 4163, 15, 48.8565162146, 2.3709371333),
(46, 15, 'Brochant', 2921, 1866, 15, 48.8903794212, 2.3200276516),
(47, 9, 'Buttes-Chaumont', 5996, 2651, 4, 48.8777769835, 2.3811293196),
(48, 11, 'Buzenval', 6483, 4438, 32, 48.8517360616, 2.4010248254),
(49, 8, 'Cadet', 3990, 2807, 13, 48.8754751871, 2.3430589538),
(50, 7, 'Cambronne', 2527, 5154, 9, 48.8474563363, 2.3018078042),
(51, 6, 'Campo-Formio', 4960, 5668, 20, 48.8357759722, 2.3581531411),
(52, 12, 'Cardinal-Lemoine', 4405, 5016, 21, 48.8463912486, 2.3514780732),
(53, 15, 'Carrefour-Pleyel', 3636, 1029, 4, 48.9194552102, 2.3432002491),
(54, 8, 'Censier-Daubenton', 4480, 5502, 25, 48.8405358795, 2.3514288121),
(55, 1, 'Champs-Elysées-Clémenceau', 2544, 3802, 10, 48.8674114214, 2.3139594501),
(56, 12, 'Chardon-Lagache', 1206, 5490, 4, 48.8447174018, 2.2665416873),
(57, 10, 'Charenton-Ecoles', 6529, 6073, 31, 48.8213638104, 2.4140743468),
(58, 7, 'Charles de Gaulle-Etoile', 1908, 3245, 1, 48.8744076037, 2.2957626847),
(59, 12, 'Charles Michels', 1867, 5219, 10, 48.8463362741, 2.2863323856),
(60, 11, 'Charonne', 5935, 4225, 29, 48.8547662368, 2.3852706478),
(61, 5, 'Château d''Eau', 4701, 2955, 8, 48.8725890109, 2.3554703688),
(62, 1, 'Château de Vincennes', 7021, 5038, 25, 48.8445363117, 2.4395097055),
(63, 8, 'Château Landon', 5158, 2628, 10, 48.8783126784, 2.3622989086),
(64, 5, 'Château Rouge', 4304, 2066, 4, 48.8871760671, 2.3498266334),
(65, 8, 'Châtelet', 4158, 4101, 20, 48.8585195224, 2.3471194010),
(66, 15, 'Châtillon Montrouge', 2821, 6916, 32, 48.8103147676, 2.3012480220),
(67, 11, 'Chaussée d''Antin (La Fayette)', 3517, 3268, 20, 48.8728970366, 2.3335011614),
(68, 10, 'Chemin Vert', 5044, 4168, 20, 48.8575640615, 2.3679345256),
(69, 7, 'Chevaleret', 5297, 5932, 21, 48.8344079355, 2.3671652407),
(70, 5, 'Cité', 4039, 4294, 14, 48.8551005858, 2.3467203742),
(71, 12, 'Cluny-La Sorbonne', 4083, 4752, 19, 48.8510383641, 2.3452215824),
(72, 2, 'Colonel Fabien', 5565, 2722, 16, 48.8779422921, 2.3701471378),
(73, 10, 'Commerce', 2171, 5328, 5, 48.8448111918, 2.2933534501),
(74, 10, 'Concorde', 2666, 3935, 10, 48.8661829956, 2.3232998786),
(75, 14, 'Convention', 2422, 6057, 26, 48.8374696207, 2.2968995828),
(76, 8, 'Corentin-Cariou', 5642, 1909, 5, 48.8949061258, 2.3824455027),
(77, 14, 'Corentin-Celton', 1951, 6508, 28, 48.8272434444, 2.2793067037),
(78, 7, 'Corvisart', 4602, 5940, 18, 48.8296116849, 2.3494383273),
(79, 2, 'Courcelles', 2312, 2847, 5, 48.8792362488, 2.3036051529),
(80, 2, 'Couronnes', 5728, 3196, 18, 48.8689698758, 2.3798685187),
(81, 10, 'Créteil-L''Echat (Hôpital Henri Mondor)', 6846, 6666, 35, 48.7964623870, 2.4490618710),
(82, 10, 'Créteil-Préfecture (Hôtel de Ville)', 6851, 6935, 37, 48.7797484034, 2.4591442675),
(83, 10, 'Créteil-Université', 6846, 6802, 36, 48.7893506911, 2.4514473975),
(84, 8, 'Crimée', 5526, 2028, 6, 48.8906811629, 2.3768152895),
(85, 11, 'Croix-de-Chavaux (Jacques Duclos)', 6908, 4024, 36, 48.8579269412, 2.4360983031),
(86, 9, 'Danube', 6382, 2468, 8, 48.8820231366, 2.3927020524),
(87, 10, 'Daumesnil (Félix Eboué)', 6154, 5218, 26, 48.8393629125, 2.3958459081),
(88, 7, 'Denfert-Rochereau', 4007, 5694, 15, 48.8335919036, 2.3318934600),
(89, 7, 'Dugommier', 5984, 5416, 24, 48.8389122332, 2.3889525722),
(90, 7, 'Dupleix', 1893, 4877, 7, 48.8508056366, 2.2927695871),
(91, 15, 'Duroc', 2904, 5159, 24, 48.8467835319, 2.3167228570),
(92, 10, 'Ecole Militaire', 2177, 4693, 7, 48.8545185491, 2.3055438162),
(93, 10, 'Ecole Vétérinaire de Maisons-Alfort', 6703, 6249, 32, 48.8151517943, 2.4207563593),
(94, 7, 'Edgar-Quinet', 3553, 5486, 13, 48.8403339878, 2.3261713916),
(95, 12, 'Eglise d''Auteuil', 1304, 5281, 6, 48.8471813582, 2.2689009878),
(96, 6, 'Eglise de Pantin', 6665, 2034, 3, 48.8931139817, 2.4132856803),
(97, 1, 'Esplanade de la Défense', 1055, 2313, 2, 48.8878431221, 2.2504423247),
(98, 5, 'Etienne Marcel', 4168, 3763, 11, 48.8634946590, 2.3486698400),
(99, 3, 'Europe', 2855, 2707, 9, 48.8787098769, 2.3228248406),
(100, 11, 'Exelmans', 1073, 5689, 5, 48.8424273379, 2.2597366158),
(101, 10, 'Faidherbe-Chaligny', 5611, 4682, 23, 48.8502384504, 2.3842745810),
(102, 14, 'Falguière', 3089, 5400, 22, 48.8446378942, 2.3174964687),
(103, 10, 'Félix Faure', 2165, 5554, 4, 48.8429772289, 2.2919978878),
(104, 10, 'Filles du Calvaire', 5051, 3746, 18, 48.8633028923, 2.3664531380),
(105, 8, 'Fort d''Aubervilliers', 6170, 1352, 2, 48.9146921997, 2.4041771014),
(106, 11, 'Franklin-Roosevelt', 2379, 3651, 15, 48.8693052286, 2.3083087645),
(107, 14, 'Front Populaire', 5199, 1490, 1, 48.9065795187, 2.3658753917),
(108, 22, 'Funiculaire Gare basse', 4088, 2205, 1, 48.8844354700, 2.3434006427),
(109, 22, 'Funiculaire Gare haute', 4088, 2112, 2, 48.8856217865, 2.3433462725),
(110, 15, 'Gabriel-Péri', 2193, 1143, 12, 48.9162335021, 2.2949237642),
(111, 15, 'Gaité', 3431, 5732, 26, 48.8384911503, 2.3225827798),
(112, 3, 'Gallieni (Parc de Bagnolet)', 6996, 3647, 25, 48.8633345779, 2.4156826043),
(113, 3, 'Gambetta', 6441, 3647, 23, 48.8648347783, 2.3984622387),
(114, 6, 'Gare d''Austerlitz', 5076, 5300, 18, 48.8431055835, 2.3643130482),
(115, 8, 'Gare de l''Est (Verdun)', 4908, 2743, 11, 48.8760613055, 2.3579186772),
(116, 1, 'Gare de Lyon', 5466, 5019, 19, 48.8447045451, 2.3740659075),
(117, 6, 'Gare du Nord', 4936, 2433, 10, 48.8799654433, 2.3547030784),
(118, 15, 'Garibaldi', 3266, 1396, 6, 48.9060316198, 2.3317332760),
(119, 1, 'George V', 2172, 3423, 8, 48.8719825732, 2.3004626834),
(120, 7, 'Glacière', 4361, 5946, 17, 48.8313647071, 2.3439388943),
(121, 13, 'Goncourt (Hôpital Saint-Louis)', 5366, 3168, 6, 48.8696803934, 2.3712726501),
(122, 11, 'Grands Boulevards', 4028, 3268, 22, 48.8714189082, 2.3431970107),
(123, 15, 'Guy-Môquet', 3090, 1833, 8, 48.8924675161, 2.3272267550),
(124, 11, 'Havre-Caumartin', 3191, 3272, 19, 48.8733427701, 2.3285077503),
(125, 6, 'Hoche', 6467, 2170, 4, 48.8913165639, 2.4031974138),
(126, 1, 'Hôtel de Ville', 4481, 4054, 16, 48.8567695272, 2.3514945398),
(127, 11, 'Iéna', 1786, 4049, 13, 48.8644728974, 2.2937999591),
(128, 10, 'Invalides', 2545, 4240, 9, 48.8616865405, 2.3143570432),
(129, 6, 'Jacques-Bonsergent', 5012, 3096, 12, 48.8709749040, 2.3605378839),
(130, 11, 'Jasmin', 1054, 4990, 8, 48.8523396005, 2.2679068138),
(131, 9, 'Jaurès', 5550, 2437, 2, 48.8825437461, 2.3702318192),
(132, 12, 'Javel-André-Citroen', 1638, 5292, 9, 48.8461034638, 2.2784549784),
(133, 13, 'Jourdain', 6125, 2868, 9, 48.8751674277, 2.3891930998),
(134, 14, 'Jules Joffrin', 4067, 1911, 5, 48.8924519922, 2.3444646963),
(135, 8, 'Jussieu', 4642, 5089, 23, 48.8458380895, 2.3546915984),
(136, 7, 'Kléber', 1714, 3505, 2, 48.8710948130, 2.2930791542),
(137, 2, 'La Chapelle', 4895, 2265, 14, 48.8839663988, 2.3584996259),
(138, 8, 'La Courneuve-8-Mai-1945', 6171, 1126, 1, 48.9209043594, 2.4104554269),
(139, 1, 'La Défense (Grande Arche)', 976, 2175, 1, 48.8919344692, 2.2378827983),
(140, 15, 'La Fourche', 3084, 2066, 9, 48.8873918956, 2.3260386771),
(141, 14, 'Lamarck-Caulaincourt', 3857, 1931, 6, 48.8897560452, 2.3388901365),
(142, 10, 'La Motte-Picquet-Grenelle', 2170, 5036, 6, 48.8491605024, 2.2979492991),
(143, 11, 'La Muette', 1068, 4383, 10, 48.8579377399, 2.2741170670),
(144, 10, 'La Tour-Maubourg', 2366, 4440, 8, 48.8573850542, 2.3095986448),
(145, 6, 'Laumière', 5697, 2292, 7, 48.8850422338, 2.3795955532),
(146, 10, 'Ledru-Rollin', 5261, 4550, 22, 48.8511941202, 2.3759453524),
(147, 8, 'Le Kremlin-Bicêtre', 4761, 6443, 35, 48.8103806352, 2.3616129501),
(148, 8, 'Le Peletier', 3848, 2921, 14, 48.8749360635, 2.3398025382),
(149, 15, 'Les Agnettes', 2077, 1030, 11, 48.9229162321, 2.2858275179),
(150, 8, 'Les Gobelins', 4633, 5685, 26, 48.8366105353, 2.3516421736),
(151, 5, 'Les Halles', 4153, 3904, 12, 48.8621947212, 2.3458362673),
(152, 1, 'Les Sablons (Jardin d''acclimatation)', 1352, 2621, 4, 48.8809017991, 2.2725390854),
(153, 10, 'Liberté', 6356, 5900, 30, 48.8258380800, 2.4069255776),
(154, 15, 'Liège', 3084, 2547, 17, 48.8795640572, 2.3265172852),
(155, 8, 'Louis Blanc', 5294, 2497, 9, 48.8810261030, 2.3655569576),
(156, 3, 'Louise Michel', 2111, 1966, 3, 48.8888292428, 2.2881373109),
(157, 10, 'Lourmel', 1928, 5925, 2, 48.8384881096, 2.2817585233),
(158, 1, 'Louvre-Rivoli', 3597, 4046, 14, 48.8605382947, 2.3410004903),
(159, 12, 'Mabillon', 3637, 4747, 17, 48.8528181349, 2.3354092889),
(160, 10, 'Madeleine', 2708, 3693, 11, 48.8700643518, 2.3251637003),
(161, 15, 'Mairie de Clichy', 2475, 1430, 13, 48.9036594416, 2.3055660013),
(162, 11, 'Mairie de Montreuil', 7025, 3915, 37, 48.8623601851, 2.4416562031),
(163, 5, 'Mairie de Montrouge', 3775, 6490, 27, 48.8180648291, 2.3195367404),
(164, 15, 'Mairie de Saint-Ouen', 3446, 1211, 5, 48.9120620157, 2.3341630154),
(165, 13, 'Mairie des Lilas', 7024, 2617, 13, 48.8797989954, 2.4162389352),
(166, 14, 'Mairie d''Issy', 1737, 6728, 29, 48.8248138109, 2.2735039998),
(167, 8, 'Mairie d''Ivry', 5769, 6578, 34, 48.8111784269, 2.3838082733),
(168, 8, 'Maison Blanche', 4761, 6244, 29, 48.8226203194, 2.3581886767),
(169, 10, 'Maisons-Alfort-Les Juilliottes', 6838, 6527, 34, 48.8031418698, 2.4449910267),
(170, 10, 'Maisons-Alfort-Stade', 6835, 6378, 33, 48.8084176209, 2.4355822036),
(171, 15, 'Malakoff-Plateau de Vanves', 2828, 6487, 30, 48.8231314025, 2.2970150852),
(172, 15, 'Malakoff-Rue Etienne Dolet', 2837, 6712, 31, 48.8146679725, 2.2969989635),
(173, 3, 'Malesherbes', 2545, 2392, 7, 48.8828147398, 2.3096940943),
(174, 11, 'Maraichers', 6585, 4347, 33, 48.8526633633, 2.4062307212),
(175, 5, 'Marcadet-Poissonniers', 4300, 1903, 3, 48.8908769929, 2.3498370716),
(176, 11, 'Marcel Sembat', 952, 6034, 3, 48.8339143014, 2.2440813883),
(177, 14, 'Marx-Dormoy', 4929, 1824, 3, 48.8903020650, 2.3602058428),
(178, 12, 'Maubert-Mutualité', 4271, 4882, 20, 48.8499086727, 2.3485034297),
(179, 2, 'Ménilmontant', 5928, 3400, 19, 48.8659488112, 2.3833942785),
(180, 11, 'Michel-Ange-Auteuil', 1073, 5297, 7, 48.8477403460, 2.2642974417),
(181, 11, 'Michel-Ange-Molitor', 1068, 5487, 6, 48.8448674365, 2.2620478642),
(182, 10, 'Michel Bizot', 6274, 5343, 27, 48.8367033670, 2.4029691612),
(183, 12, 'Mirabeau', 1405, 5425, 5, 48.8473396417, 2.2734059593),
(184, 11, 'Miromesnil', 2764, 3279, 17, 48.8737063841, 2.3151991849),
(185, 2, 'Monceau', 2479, 2679, 6, 48.8806306676, 2.3089662422),
(186, 10, 'Montgallet', 5966, 5034, 25, 48.8442504626, 2.3898021375),
(187, 7, 'Montparnasse-Bienvenue', 3388, 5475, 12, 48.8430428503, 2.3226350506),
(188, 5, 'Mouton-Duvernet', 3999, 5919, 24, 48.8313196996, 2.3294269348),
(189, 11, 'Nation', 6277, 4577, 31, 48.8484657506, 2.3959057446),
(190, 7, 'Nationale', 5052, 5943, 20, 48.8327104551, 2.3620454618),
(191, 14, 'Notre-Dame de Lorette', 3771, 2822, 10, 48.8766256957, 2.3384537104),
(192, 14, 'Notre-Dame des Champs', 3576, 5255, 20, 48.8449178498, 2.3288529681),
(193, 11, 'Oberkampf', 5311, 3552, 26, 48.8645156236, 2.3686742364),
(194, 5, 'Odéon', 3885, 4701, 16, 48.8522489364, 2.3385575587),
(195, 10, 'Opéra', 3334, 3417, 12, 48.8707029412, 2.3318051564),
(196, 23, 'Orly-Ouest', 4001, 7366, 2, 48.7285134699, 2.3587214188),
(197, 23, 'Orly-Sud', 4216, 7370, 3, 48.7278373152, 2.3679598729),
(198, 6, 'Ourcq', 5908, 2170, 6, 48.8869750227, 2.3860295880),
(199, 8, 'Palais-Royal (Musée du Louvre)', 3342, 4057, 18, 48.8628468214, 2.3358166119),
(200, 3, 'Parmentier', 5747, 3537, 20, 48.8651606068, 2.3758467947),
(201, 7, 'Passy', 1487, 4464, 5, 48.8576333212, 2.2858650114),
(202, 7, 'Pasteur', 3009, 5483, 11, 48.8429383503, 2.3126564450),
(203, 4, 'Pelleport', 6638, 3368, 2, 48.8685198745, 2.4013519901),
(204, 3, 'Péreire', 2344, 2204, 5, 48.8845503318, 2.2975170487),
(205, 3, 'Père-Lachaise', 6120, 3650, 22, 48.8626131584, 2.3867218119),
(206, 15, 'Pernéty', 3316, 5902, 27, 48.8338169002, 2.3176639582),
(207, 2, 'Philippe Auguste', 6129, 3856, 21, 48.8583654721, 2.3893873915),
(208, 7, 'Picpus', 6445, 4950, 27, 48.8452604479, 2.4012551645),
(209, 8, 'Pierre et Marie Curie', 5654, 6477, 33, 48.8156836605, 2.3772866230),
(210, 2, 'Pigalle', 3869, 2261, 11, 48.8824943850, 2.3376226052),
(211, 2, 'Place de Clichy', 3095, 2261, 9, 48.8836233672, 2.3273068221),
(212, 9, 'Place des Fêtes', 6381, 2831, 6, 48.8768191471, 2.3934731351),
(213, 8, 'Place d''Italie', 4774, 5952, 27, 48.8314061536, 2.3557213734),
(214, 8, 'Place Monge (Jardin des Plantes)', 4473, 5289, 24, 48.8431960998, 2.3521784510),
(215, 15, 'Plaisance', 3144, 6085, 28, 48.8317625861, 2.3140673448),
(216, 10, 'Pointe du Lac', 6842, 7070, 38, 48.7679578711, 2.4659898292),
(217, 8, 'Poissonnière', 4368, 2754, 12, 48.8773395505, 2.3496062390),
(218, 3, 'Pont de Levallois-Bécon', 1927, 1784, 1, 48.8971187551, 2.2804894173),
(219, 1, 'Pont de Neuilly', 1220, 2480, 3, 48.8845093796, 2.2598917247),
(220, 11, 'Pont de Sèvres', 693, 6303, 1, 48.8295929841, 2.2302536479),
(221, 8, 'Pont Marie (Cité des Arts)', 4518, 4339, 21, 48.8533107455, 2.3574588160),
(222, 8, 'Pont Neuf', 3816, 4099, 19, 48.8584022525, 2.3419492363),
(223, 2, 'Porte Dauphine (Maréchal de Lattre de Tassigny)', 1283, 3248, 1, 48.8716448025, 2.2766480746),
(224, 12, 'Porte d''Auteuil', 850, 5421, 8, 48.8480737823, 2.2586478276),
(225, 3, 'Porte de Bagnolet', 6758, 3656, 24, 48.8643963045, 2.4079999527),
(226, 3, 'Porte de Champerret', 2205, 2053, 4, 48.8855507197, 2.2927420414),
(227, 10, 'Porte de Charenton', 6237, 5700, 29, 48.8325258940, 2.3997010994),
(228, 8, 'Porte de Choisy', 5231, 6380, 31, 48.8201452158, 2.3643464453),
(229, 15, 'Porte de Clichy', 2762, 1710, 14, 48.8943611611, 2.3137383438),
(230, 5, 'Porte de Clignancourt', 4066, 1606, 1, 48.8972870604, 2.3447788774),
(231, 14, 'Porte de la Chapelle', 4928, 1607, 2, 48.8980716717, 2.3590915419),
(232, 8, 'Porte de la Villette', 5781, 1773, 4, 48.8978102191, 2.3858061126),
(233, 11, 'Porte de Montreuil', 6699, 4252, 34, 48.8534495044, 2.4104344487),
(234, 6, 'Porte de Pantin', 6194, 2162, 5, 48.8883327365, 2.3914686140),
(235, 11, 'Porte de Saint-Cloud', 1068, 5894, 4, 48.8378883866, 2.2568025150),
(236, 15, 'Porte de Saint-Ouen', 3077, 1622, 7, 48.8972510523, 2.3288376697),
(237, 4, 'Porte des Lilas', 6801, 2847, 4, 48.8770699912, 2.4063858234),
(238, 15, 'Porte de Vanves', 2940, 6283, 29, 48.8276756893, 2.3053401488),
(239, 14, 'Porte de Versailles', 2269, 6214, 27, 48.8324619820, 2.2880027964),
(240, 1, 'Porte de Vincennes', 6559, 4684, 22, 48.8472657114, 2.4088835253),
(241, 8, 'Porte d''Italie', 4977, 6384, 30, 48.8189346111, 2.3598558027),
(242, 8, 'Porte d''Ivry', 5474, 6392, 32, 48.8212704975, 2.3690949435),
(243, 10, 'Porte Dorée', 6388, 5530, 28, 48.8354388892, 2.4060183531),
(244, 5, 'Porte d''Orléans (Général Leclerc)', 3764, 6291, 26, 48.8226012875, 2.3248055531),
(245, 1, 'Porte Maillot', 1524, 2788, 5, 48.8779645030, 2.2818361726),
(246, 9, 'Pré-Saint-Gervais', 6587, 2612, 7, 48.8799349564, 2.3990768969),
(247, 8, 'Pyramides', 3334, 3726, 17, 48.8669463021, 2.3336650658),
(248, 8, 'Pyramides', 3340, 3737, 17, 48.8669463021, 2.3336650658),
(249, 13, 'Pyrenees', 5816, 2869, 8, 48.8738907818, 2.3848898390),
(250, 7, 'Quai de la Gare', 5536, 5820, 22, 48.8373989542, 2.3737838849),
(251, 6, 'Quai de la Rapée', 5074, 4922, 17, 48.8457951983, 2.3670356341),
(252, 3, 'Quatre Septembre', 3515, 3594, 13, 48.8693729306, 2.3357424748),
(253, 13, 'Rambuteau', 4673, 3857, 3, 48.8614000355, 2.3535049622),
(254, 11, 'Ranelagh', 1063, 4694, 9, 48.8553920308, 2.2699797504),
(255, 7, 'Raspail', 3868, 5557, 14, 48.8389637143, 2.3305830968),
(256, 5, 'Réaumur-Sébastopol', 4445, 3690, 10, 48.8663118302, 2.3520352553),
(257, 14, 'Rennes', 3401, 5001, 19, 48.8480544329, 2.3280830512),
(258, 11, 'République', 5188, 3366, 25, 48.8674860391, 2.3636695982),
(259, 10, 'Reuilly-Diderot', 5777, 4856, 24, 48.8472381327, 2.3861181866),
(260, 6, 'Richard-Lenoir', 5419, 3986, 14, 48.8608927535, 2.3720977805),
(261, 11, 'Richelieu-Drouot', 3730, 3257, 21, 48.8720139168, 2.3390880994),
(262, 8, 'Riquet', 5407, 2142, 7, 48.8881235581, 2.3744874257),
(263, 11, 'Robespierre', 6801, 4133, 35, 48.8555640048, 2.4235567389),
(264, 2, 'Rome', 2841, 2316, 8, 48.8825292241, 2.3209841442),
(265, 11, 'Rue de la Pompe (Avenue Georges Mandel)', 1142, 4049, 11, 48.8641004132, 2.2776263299),
(266, 11, 'Rue des Boulets', 6071, 4349, 30, 48.8521417734, 2.3891428046),
(267, 14, 'Rue du Bac', 3065, 4687, 17, 48.8557564722, 2.3255688218),
(268, 3, 'Rue Saint-Maur', 5905, 3650, 21, 48.8639878082, 2.3800732953),
(269, 11, 'Saint-Ambroise', 5649, 3936, 27, 48.8612121033, 2.3740976038),
(270, 11, 'Saint-Augustin', 2887, 3147, 18, 48.8746742716, 2.3204313965),
(271, 15, 'Saint-Denis-Porte de Paris', 4089, 920, 3, 48.9296276098, 2.3574101643),
(272, 15, 'Saint-Denis-Universite', 4707, 584, 1, 48.9456254806, 2.3623587582),
(273, 4, 'Saint-Fargeau', 6805, 3114, 3, 48.8719510995, 2.4048576355),
(274, 15, 'Saint-Francois-Xavier', 2664, 4908, 23, 48.8518632148, 2.3140345269),
(275, 14, 'Saint-Georges', 3865, 2533, 9, 48.8785085363, 2.3375885122),
(276, 5, 'Saint-Germain des Prés', 3555, 4701, 17, 48.8536134868, 2.3337204654),
(277, 7, 'Saint-Jacques', 4157, 5829, 16, 48.8332522884, 2.3362200154),
(278, 3, 'Saint-Lazare', 3612, 4875, 10, 48.8755778692, 2.3261856586),
(279, 1, 'Saint-Mandé', 6784, 4816, 23, 48.8462977573, 2.4186731009),
(280, 6, 'Saint-Marcel', 5090, 5511, 19, 48.8395141858, 2.3614087791),
(281, 5, 'Saint-Michel', 3925, 4578, 15, 48.8532875817, 2.3434684520),
(282, 1, 'Saint-Paul (Le Marais)', 4745, 4316, 17, 48.8551436006, 2.3606533540),
(283, 11, 'Saint-Philippe du Roule', 2624, 3408, 16, 48.8721798206, 2.3098200938),
(284, 5, 'Saint-Placide', 3468, 5161, 19, 48.8465803886, 2.3269326356),
(285, 10, 'Saint-Sébastien-Froissart', 5033, 3952, 19, 48.8612805878, 2.3670240953),
(286, 5, 'Saint-Sulpice', 3463, 4922, 18, 48.8508047852, 2.3308675644),
(287, 12, 'Ségur', 2644, 5231, 13, 48.8467524551, 2.3078204185),
(288, 3, 'Sentier', 4077, 3686, 15, 48.8674133309, 2.3466136387),
(289, 14, 'Sèvres-Babylone', 3234, 4830, 18, 48.8512043553, 2.3258728629),
(290, 7, 'Sèvres-Lecourbe', 2724, 5362, 10, 48.8451307700, 2.3101839037),
(291, 5, 'Simplon', 4185, 1729, 2, 48.8946326892, 2.3470910653),
(292, 14, 'Solférino', 2882, 4507, 16, 48.8585512443, 2.3226806222),
(293, 8, 'Stalingrad', 5338, 2291, 8, 48.8843047895, 2.3670850145),
(294, 11, 'Strasbourg-Saint-Denis', 4610, 3259, 24, 48.8691997058, 2.3540971057),
(295, 8, 'Sully-Morland', 4733, 4553, 22, 48.8510338404, 2.3618669636),
(296, 13, 'Telegraphe', 6624, 2864, 11, 48.8753936354, 2.3985900638),
(297, 3, 'Temple', 4999, 3481, 18, 48.8663687823, 2.3610807223),
(298, 2, 'Ternes', 2134, 3021, 4, 48.8780661990, 2.2980307126),
(299, 8, 'Tolbiac', 4767, 6102, 28, 48.8265068988, 2.3572419274),
(300, 14, 'Trinité-d''Estienne d''Orves', 3390, 2836, 11, 48.8765087932, 2.3330580767),
(301, 11, 'Trocadéro', 1465, 4054, 12, 48.8629708358, 2.2870198440),
(302, 1, 'Tuileries', 3025, 4046, 12, 48.8644970967, 2.3303141803),
(303, 12, 'Vaneau', 3076, 4999, 15, 48.8488716625, 2.3219343475),
(304, 15, 'Varenne', 2538, 4547, 22, 48.8575166013, 2.3155302335),
(305, 14, 'Vaugirard (Adolphe Chérioux)', 2603, 5869, 25, 48.8395680328, 2.3009826399),
(306, 5, 'Vavin', 3691, 5437, 21, 48.8420036542, 2.3290032189),
(307, 2, 'Victor Hugo', 1558, 3515, 2, 48.8696716619, 2.2852672684),
(308, 8, 'Villejuif-Léo Lagrange', 4756, 6619, 35, 48.8041740664, 2.3639435266),
(309, 8, 'Villejuif-Louis Aragon', 4751, 6934, 37, 48.7870020157, 2.3669747148),
(310, 8, 'Villejuif-Paul Vaillant Couturier (Hôpital Paul Br', 4761, 6784, 36, 48.7959447271, 2.3679661374),
(311, 3, 'Villiers', 2650, 2503, 8, 48.8810543983, 2.3152705510),
(312, 14, 'Volontaires', 2786, 5683, 24, 48.8416970274, 2.3080411456),
(313, 11, 'Voltaire (Léon Blum)', 5784, 4079, 28, 48.8578758429, 2.3798839196),
(314, 3, 'Wagram', 2436, 2290, 6, 48.8838739610, 2.3046514421),
(315, 18, 'Antony', 3482, 7386, 1, 48.7550073109, 2.3012047782),
(316, 18, 'Arcueil-Cachan', 3978, 6886, 4, 48.7989637452, 2.3278264235),
(317, 17, 'Auber', 3186, 3458, 3, 48.8728028704, 2.3297976143),
(318, 18, 'Bagneux', 3832, 7053, 3, 48.7935073908, 2.3211344661),
(319, 18, 'Bourg-la-Reine', 3683, 7176, 2, 48.7801183312, 2.3122781905),
(320, 17, 'Charles de Gaulle-Etoile', 1975, 3168, 2, 48.8740492795, 2.2948251379),
(321, 18, 'Cité Universitaire', 4043, 6292, 7, 48.8199505116, 2.3409839491),
(322, 18, 'Gentilly', 4038, 6511, 6, 48.8155735023, 2.3407386664),
(323, 17, 'La Défense (Grande Arche)', 961, 2202, 1, 48.8918864546, 2.2377829420),
(324, 18, 'Laplace', 4042, 6725, 5, 48.8079249127, 2.3334990532),
(325, 18, 'Luxembourg', 4043, 5025, 9, 48.8459336878, 2.3406136122),
(326, 17, 'Nation', 6256, 4598, 6, 48.8483471801, 2.3959499233),
(327, 18, 'Port Royal', 4034, 5395, 8, 48.8389863698, 2.3370368298),
(328, 17, 'Vincennes', 7204, 4639, 7, 48.8472269351, 2.4310666191),
(329, 17, 'Chatelet-Les Halles', 4254, 3966, 4, 48.8611491277, 2.3466329400),
(330, 18, 'Gare du Nord', 4846, 2440, 12, 48.8800360157, 2.3545491254),
(331, 17, 'Gare de Lyon', 5451, 5054, 5, 48.8447572696, 2.3740723000);

-- --------------------------------------------------------

--
-- Table structure for table `page_content`
--

CREATE TABLE IF NOT EXISTS `page_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_content_sluggable_idx` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `page_content`
--


-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE IF NOT EXISTS `property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) DEFAULT NULL,
  `region_block_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `amount_of_rent_excluding_charges` decimal(14,2) DEFAULT NULL,
  `amount_of_charges` decimal(14,2) DEFAULT NULL,
  `is_furnished` tinyint(4) DEFAULT '0',
  `lease_duration` int(11) DEFAULT NULL,
  `deposit` int(11) DEFAULT '1',
  `availability` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postcode` varchar(8) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `size` decimal(14,2) DEFAULT NULL,
  `property_type` int(11) DEFAULT NULL,
  `number_of_rooms1` int(11) DEFAULT '1',
  `number_of_rooms2` int(11) DEFAULT '1',
  `honoraire` decimal(14,2) DEFAULT NULL,
  `is_urgent` tinyint(4) DEFAULT NULL,
  `is_separate_restrooms` tinyint(4) DEFAULT NULL,
  `is_parquet_floor` tinyint(4) DEFAULT NULL,
  `is_molding` tinyint(4) DEFAULT NULL,
  `is_double_glazing` tinyint(4) DEFAULT NULL,
  `is_storage_area` tinyint(4) DEFAULT NULL,
  `is_fireplace` tinyint(4) DEFAULT NULL,
  `is_conditioner` tinyint(4) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `is_lift` tinyint(4) DEFAULT NULL,
  `is_balcony` tinyint(4) DEFAULT NULL,
  `is_terrace` tinyint(4) DEFAULT NULL,
  `is_garden` tinyint(4) DEFAULT NULL,
  `is_yard` tinyint(4) DEFAULT NULL,
  `is_attic` tinyint(4) DEFAULT NULL,
  `is_basement` tinyint(4) DEFAULT NULL,
  `is_garage` tinyint(4) DEFAULT NULL,
  `is_parking_lot` tinyint(4) DEFAULT NULL,
  `is_swimming_pool` tinyint(4) DEFAULT NULL,
  `is_digicode` tinyint(4) DEFAULT NULL,
  `is_watchman` tinyint(4) DEFAULT NULL,
  `is_old_building` tinyint(4) DEFAULT NULL,
  `is_very_old_building` tinyint(4) DEFAULT NULL,
  `is_renove` tinyint(4) DEFAULT NULL,
  `is_guardian` tinyint(4) DEFAULT NULL,
  `is_new` tinyint(4) DEFAULT NULL,
  `is_cave` tinyint(4) DEFAULT NULL,
  `number_of_bathrooms` int(11) DEFAULT '1',
  `is_individuel` tinyint(4) DEFAULT NULL,
  `is_central` tinyint(4) DEFAULT NULL,
  `is_au_sol` tinyint(4) DEFAULT NULL,
  `is_gaz` tinyint(4) DEFAULT NULL,
  `is_electrique` tinyint(4) DEFAULT NULL,
  `is_autre` tinyint(4) DEFAULT NULL,
  `main_photo` varchar(255) DEFAULT NULL,
  `is_r_student` tinyint(4) DEFAULT NULL,
  `is_r_employee` tinyint(4) DEFAULT NULL,
  `is_r_independent` tinyint(4) DEFAULT NULL,
  `is_r_other` tinyint(4) DEFAULT NULL,
  `is_roomate` tinyint(4) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `state` tinyint(4) DEFAULT '1',
  `is_published` tinyint(4) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `latitude` double(18,10) DEFAULT NULL,
  `longitude` double(18,10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id_idx` (`owner_id`),
  KEY `region_block_id_idx` (`region_block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`id`, `owner_id`, `region_block_id`, `title`, `amount_of_rent_excluding_charges`, `amount_of_charges`, `is_furnished`, `lease_duration`, `deposit`, `availability`, `address`, `postcode`, `city`, `phone`, `size`, `property_type`, `number_of_rooms1`, `number_of_rooms2`, `honoraire`, `is_urgent`, `is_separate_restrooms`, `is_parquet_floor`, `is_molding`, `is_double_glazing`, `is_storage_area`, `is_fireplace`, `is_conditioner`, `floor`, `is_lift`, `is_balcony`, `is_terrace`, `is_garden`, `is_yard`, `is_attic`, `is_basement`, `is_garage`, `is_parking_lot`, `is_swimming_pool`, `is_digicode`, `is_watchman`, `is_old_building`, `is_very_old_building`, `is_renove`, `is_guardian`, `is_new`, `is_cave`, `number_of_bathrooms`, `is_individuel`, `is_central`, `is_au_sol`, `is_gaz`, `is_electrique`, `is_autre`, `main_photo`, `is_r_student`, `is_r_employee`, `is_r_independent`, `is_r_other`, `is_roomate`, `views`, `state`, `is_published`, `created_at`, `updated_at`, `latitude`, `longitude`) VALUES
(1, 1, 85, '2 Rue Pecquay', 100.00, 100.00, 1, 1, 2, '2013-08-14', '2 Rue Pecquay', '75004', 'Paris', '', 56.00, 1, 2, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, NULL, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 1, 0, 0, 0, 1, 12, 6, 1, '2013-08-14 08:47:04', '2013-08-14 08:52:39', 48.8596465000, 2.3556187000),
(2, 1, 90, '8 Rue Clovis', 200.00, 200.00, 1, 3, 1, '2013-08-16', '8 Rue Clovis', '75005', 'Paris', '', 56.00, 1, 3, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, NULL, NULL, NULL, 1, 0, 1, 0, 0, 0, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 1, 1, 1, 0, 0, 34, 6, 1, '2013-08-14 08:52:44', '2013-08-14 08:56:45', 48.8461646000, 2.3491135000),
(3, 4, 69, '4 Boulevard Saint-Michel', 300.00, 150.00, 0, 6, 1, '2013-08-20', '4 Boulevard Saint-Michel', '75006', 'Paris', '', 46.00, 1, 2, 2, NULL, NULL, 1, 1, 1, 0, 0, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, NULL, NULL, NULL, 1, 1, 0, 0, 1, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 1, 128, 6, 1, '2013-08-14 08:56:53', '2013-08-14 09:01:13', 48.8530957000, 2.3437525000),
(4, 4, 86, '15 Rue du Bourg Tibourg', 250.00, 150.00, 0, 2, 1, '2013-08-14', '15 Rue du Bourg Tibourg', '75004', 'Paris', '', 51.00, 1, 2, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 8, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 1, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 1, 1, 1, 1, 1, 121, 6, 1, '2013-08-14 09:01:18', '2013-08-14 09:05:38', 48.8570196000, 2.3557888000),
(5, 4, 97, '101 Rue Saint-Lazare', 333.00, 200.00, 1, 6, 2, '2013-08-15', '101 Rue Saint-Lazare', '75009', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 0, 34, 6, 1, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8756240000, 2.3279515000),
(6, 3, 88, '1 Parvis Notre-Dame', 333.00, 200.00, 1, 6, 2, '2013-08-15', '1 Parvis Notre-Dame - place Jean-Paul-II', '75004', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 0, 144, 6, 1, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8535815000, 2.3489147000),
(7, 5, 71, 'Rue Bonaparte', 333.00, 200.00, 1, 6, 2, '2013-08-15', 'Rue Bonaparte', '75006', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 0, 0, 6, 0, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8534870000, 2.3333562000),
(8, 1, 62, '61 Avenue Franklin Delano Roosevelt', 333.00, 200.00, 1, 6, 2, '2013-08-15', '61 Avenue Franklin Delano Roosevelt', '75008', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 0, 121, 6, 1, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8716465000, 2.3098805000),
(9, 1, 63, 'Place de la Concorde', 360.00, 220.00, 1, 6, 2, '2013-08-15', 'Place de la Concorde', '75008', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 0, 66, 6, 0, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8656331000, 2.3212357000),
(10, 1, 65, '31 Avenue Bosquet', 390.00, 220.00, 1, 6, 2, '2013-08-15', '31 Avenue Bosquet', '75007', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 0, 155, 6, 0, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8591025000, 2.3035937000),
(11, 2, 39, '36bis Rue Nicolo', 3490.00, 220.00, 1, 6, 2, '2013-08-15', '36bis Rue Nicolo', '75116', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 0, 56, 6, 1, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8599319000, 2.2788063000),
(12, 2, 39, '11 Rue Chardon-Lagache', 590.00, 220.00, 1, 6, 2, '2013-08-15', '36bis Rue Nicolo', '75016', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/4/5dab307644a8b457b37673202e8cd8f2.jpeg', 0, 1, 1, 0, 0, 0, 6, 0, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8599374000, 2.2787943000),
(13, 2, 91, '29 Rue Mouffetard', 590.00, 220.00, 1, 6, 2, '2013-08-15', '29 Rue Mouffetard', '75005', 'Paris', '', 58.70, 1, 1, 1, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '/media/property/5/7c41f94255527e462217b0c43baf825d.jpeg', 0, 1, 1, 0, 0, 67, 6, 1, '2013-08-14 09:06:10', '2013-08-14 09:16:21', 48.8440298000, 2.3493874000);

-- --------------------------------------------------------

--
-- Table structure for table `property_application`
--

CREATE TABLE IF NOT EXISTS `property_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `property_visit_date_id` int(11) DEFAULT NULL,
  `visit_time` time DEFAULT NULL,
  `rate` tinyint(4) DEFAULT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `is_accepted` tinyint(1) DEFAULT '0',
  `is_declined` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_visit_date_id_idx` (`property_visit_date_id`),
  KEY `visitor_id_idx` (`visitor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `property_application`
--

INSERT INTO `property_application` (`id`, `visitor_id`, `property_id`, `property_visit_date_id`, `visit_time`, `rate`, `message`, `is_read`, `is_accepted`, `is_declined`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 1, '20:12:00', 127, NULL, 0, 0, 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(2, 5, 1, 2, '20:12:00', 127, NULL, 0, 1, 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(3, 5, 1, 3, '10:30:00', 127, NULL, 0, 0, 1, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(4, 5, 1, 4, '15:30:00', 127, NULL, 0, 0, 1, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(5, 5, 2, 5, '15:30:00', 127, NULL, 0, 0, 1, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(6, 5, 2, 6, '19:30:00', 127, NULL, 0, 0, 0, '2013-11-21 13:21:57', '2013-11-21 13:21:57'),
(7, 5, 3, 7, '18:30:00', 127, NULL, 0, 0, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `property_issue`
--

CREATE TABLE IF NOT EXISTS `property_issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `subject_id` int(11) NOT NULL DEFAULT '0',
  `message` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`,`property_id`,`user_id`,`subject_id`),
  KEY `property_issue_user_id_user_id` (`user_id`),
  KEY `property_issue_subject_id_property_issue_subject_id` (`subject_id`),
  KEY `property_issue_property_id_property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `property_issue`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_issue_subject`
--

CREATE TABLE IF NOT EXISTS `property_issue_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `property_issue_subject`
--

INSERT INTO `property_issue_subject` (`id`, `subject_name`) VALUES
(1, 'message 1'),
(2, 'message 2'),
(3, 'spam');

-- --------------------------------------------------------

--
-- Table structure for table `property_visit_dates`
--

CREATE TABLE IF NOT EXISTS `property_visit_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `availability` date DEFAULT NULL,
  `at_time` time DEFAULT NULL,
  `visitors` int(11) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `property_visit_dates`
--

INSERT INTO `property_visit_dates` (`id`, `property_id`, `availability`, `at_time`, `visitors`, `created_at`, `updated_at`) VALUES
(1, 1, '2013-11-15', '08:00:00', 0, '2013-11-14 08:51:59', '2013-11-14 08:51:59'),
(2, 1, '2013-12-23', '08:00:00', 0, '2013-11-14 08:52:02', '2013-11-14 08:52:02'),
(3, 1, '2013-11-30', '08:00:00', 0, '2013-08-14 08:52:04', '2013-08-14 08:52:04'),
(4, 1, '2013-11-27', '08:00:00', 0, '2013-08-14 08:52:10', '2013-08-14 08:52:10'),
(5, 2, '2013-11-16', '08:00:00', 0, '2013-08-14 08:56:30', '2013-08-14 08:56:30'),
(6, 2, '2013-11-28', '15:00:00', 0, '2013-08-14 08:56:37', '2013-08-14 08:56:37'),
(7, 3, '2013-11-11', '16:00:00', 1, '2013-08-14 09:00:55', '2013-08-14 09:00:55'),
(8, 3, '2013-11-11', '15:00:00', 2, '2013-08-14 09:01:06', '2013-08-14 09:01:06'),
(9, 4, '2013-12-14', '18:00:00', 1, '2013-08-14 09:05:03', '2013-08-14 09:05:03'),
(10, 4, '2013-11-15', '19:00:00', 1, '2013-08-14 09:05:13', '2013-08-14 09:05:13'),
(11, 5, '2013-11-17', '11:00:00', 3, '2013-08-14 09:16:08', '2013-08-14 09:16:08'),
(12, 5, '2013-12-19', '11:00:00', 3, '2013-08-14 09:16:13', '2013-08-14 09:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `property_x_metro_station`
--

CREATE TABLE IF NOT EXISTS `property_x_metro_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metro_station_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `distance` double(18,3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `metro_station_id_idx` (`metro_station_id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `property_x_metro_station`
--


-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE IF NOT EXISTS `queue` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_name` varchar(100) DEFAULT NULL,
  `timeout` smallint(6) DEFAULT '30',
  PRIMARY KEY (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `queue`
--


-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `zoom_level` tinyint(4) DEFAULT NULL,
  `path` text,
  `type` varchar(255) DEFAULT NULL,
  `region_city_id` int(11) DEFAULT NULL,
  `region_district_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_type_idx` (`type`),
  KEY `region_region_district_id_region_id` (`region_district_id`),
  KEY `region_region_city_id_region_id` (`region_city_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=141 ;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`id`, `name`, `zoom_level`, `path`, `type`, `region_city_id`, `region_district_id`) VALUES
(1, 'Paris', NULL, '[[48.899574,2.324295], [48.900025,2.391586], [48.886031,2.396393], [48.878806,2.407379], [48.871129,2.414932], [48.8386,2.414246], [48.826396,2.38266], [48.815545,2.363434], [48.817354,2.352448], [48.815998,2.335968], [48.833628,2.275543], [48.834984,2.256317], [48.845378,2.252197], [48.845509,2.250300], [48.848328,2.241719], [48.848560,2.227300], [48.855450,2.225929], [48.866520,2.231590], [48.870468,2.239320], [48.876339,2.246700], [48.874199,2.255110], [48.880520,2.258199], [48.878292,2.280070], [48.88874,2.296829], [48.895511,2.307129], [48.899574,2.324295]]', 'RegionCity', NULL, NULL),
(2, '1er : Louvre', NULL, '[[48.863037,2.320769],[48.868938,2.325229],[48.869419,2.325149],[48.869949,2.328018],[48.868286,2.330302],[48.863407,2.350944],[48.857124,2.347351],[48.856972,2.34691],[48.855373,2.345966],[48.854012,2.344529],[48.855247,2.3424],[48.8568,2.340469],[48.858467,2.33725],[48.85968,2.331459],[48.863037,2.320769]]', 'RegionDistrict', 1, NULL),
(3, '2ème : Bourse', NULL, '[[48.87196,2.339999],[48.870689,2.34789],[48.869308,2.35433],[48.863411,2.350979],[48.868294,2.330292],[48.86972,2.328211],[48.869923,2.328072],[48.87196,2.339999]]', 'RegionDistrict', 1, NULL),
(4, '3ème : Temple', NULL, '[[48.867905,2.36236],[48.866436,2.364764],[48.86319,2.366694],[48.85582,2.368454],[48.856441,2.364335],[48.857262,2.361631],[48.858757,2.358626],[48.860111,2.356825],[48.861214,2.353391],[48.862034,2.350172],[48.869286,2.354249],[48.867905,2.36236]]', 'RegionDistrict', 1, NULL),
(5, '4ème : Hôtel-de-Ville', NULL, '[[48.845989,2.36465],[48.846958,2.366299],[48.85281,2.3692],[48.855869,2.36836],[48.856407,2.364329],[48.857258,2.36167],[48.858749,2.358579],[48.860161,2.35678],[48.86124,2.353299],[48.86203,2.350169],[48.857147,2.347329],[48.856964,2.346986],[48.855396,2.345966],[48.854027,2.344551],[48.853298,2.34695],[48.851978,2.35013],[48.850849,2.35467],[48.84864,2.360899],[48.845989,2.36465]]', 'RegionDistrict', 1, NULL),
(6, '5ème : Panthéon', NULL, '[[48.83675,2.351799],[48.84,2.361839],[48.844379,2.36493],[48.844997,2.366],[48.84861,2.36098],[48.850788,2.3548],[48.851997,2.350169],[48.853298,2.346859],[48.854038,2.3445],[48.85376,2.344199],[48.850258,2.342576],[48.839622,2.336462],[48.837601,2.345489],[48.83675,2.351799]]', 'RegionDistrict', 1, NULL),
(7, '6ème : Luxembourg', NULL, '[[48.846851,2.316469],[48.847851,2.31904],[48.850311,2.32412],[48.851608,2.32704],[48.851952,2.32866],[48.856339,2.331599],[48.85918,2.33386],[48.85849,2.337499],[48.85675,2.340592],[48.855247,2.342512],[48.853992,2.344465],[48.853767,2.34425],[48.850349,2.342639],[48.83963,2.33652],[48.8451,2.31999],[48.846851,2.316469]]', 'RegionDistrict', 1, NULL),
(8, '7ème : Palais-Bourbon', NULL, '[[48.84581,2.309531],[48.847153,2.307342],[48.858192,2.289877],[48.861862,2.294769],[48.863499,2.300219],[48.863838,2.31833],[48.859676,2.331462],[48.85918,2.333844],[48.856258,2.33159],[48.854424,2.33041],[48.852673,2.329295],[48.851826,2.328479],[48.851601,2.327106],[48.847801,2.319274],[48.845909,2.313651],[48.845215,2.311355],[48.84581,2.309531]]', 'RegionDistrict', 1, NULL),
(9, '8ème : Élysée', NULL, '[[48.864601,2.30155],[48.865131,2.299869],[48.86869,2.298799],[48.871151,2.297299],[48.87326,2.295539],[48.873192,2.29457],[48.873669,2.294029],[48.87429,2.294399],[48.874317,2.295409],[48.87775,2.297769],[48.878029,2.297639],[48.87846,2.298049],[48.87825,2.298799],[48.880459,2.309009],[48.88134,2.316609],[48.88345,2.32721],[48.875908,2.326849],[48.875404,2.326611],[48.873825,2.326976],[48.873329,2.326869],[48.872597,2.32629],[48.872471,2.326505],[48.869534,2.325819],[48.869392,2.325152],[48.869019,2.32526],[48.863098,2.320769],[48.863811,2.31828],[48.863522,2.30167],[48.864601,2.30155]]', 'RegionDistrict', 1, NULL),
(10, '9ème : Opéra', NULL, '[[48.882011,2.339599],[48.883781,2.34952],[48.880692,2.34987],[48.87888,2.349209],[48.87722,2.349],[48.87566,2.348139],[48.873871,2.34785],[48.870689,2.34789],[48.87196,2.34004],[48.869572,2.32579],[48.872471,2.326519],[48.872589,2.326309],[48.873341,2.32688],[48.873779,2.326979],[48.875439,2.32663],[48.87595,2.32682],[48.883419,2.32717],[48.883621,2.327929],[48.884651,2.32948],[48.882271,2.337406],[48.882011,2.339599]]', 'RegionDistrict', 1, NULL),
(11, '10ème : Enclos-St-Laurent', NULL, '[[48.884079,2.368755],[48.883289,2.369142],[48.882637,2.370213],[48.878632,2.370257],[48.878124,2.370814],[48.87756,2.370601],[48.872108,2.376952],[48.870644,2.372917],[48.86779,2.364205],[48.867226,2.363477],[48.867905,2.362404],[48.869286,2.354293],[48.870728,2.347941],[48.873943,2.347812],[48.875751,2.348155],[48.877277,2.348971],[48.879223,2.349272],[48.880692,2.349873],[48.883797,2.349486],[48.884472,2.359227],[48.884361,2.364806],[48.884079,2.368755]]', 'RegionDistrict', 1, NULL),
(12, '11ème : Popincourt', NULL, '[[48.867115,2.383175],[48.86607,2.383776],[48.862823,2.387465],[48.858444,2.389654],[48.857571,2.39223],[48.856548,2.394321],[48.85133,2.398398],[48.848709,2.399214],[48.848103,2.399064],[48.848351,2.395899],[48.85017,2.384291],[48.850483,2.379999],[48.852459,2.371544],[48.853222,2.370257],[48.853081,2.369184],[48.86319,2.366737],[48.866409,2.36472],[48.867256,2.363477],[48.86779,2.364205],[48.872108,2.376866],[48.867115,2.383175]]', 'RegionDistrict', 1, NULL),
(13, '12ème : Reuilly', NULL, '[[48.846809,2.414499],[48.836529,2.41252],[48.83427,2.409609],[48.829689,2.40111],[48.828049,2.39562],[48.827198,2.390719],[48.826469,2.38858],[48.84613,2.364539],[48.847031,2.366429],[48.85313,2.36918],[48.85313,2.370379],[48.852417,2.371544],[48.850368,2.38017],[48.850079,2.384459],[48.848328,2.39596],[48.846809,2.414499]]', 'RegionDistrict', 1, NULL),
(14, '13ème : Gobelins', NULL, '[[48.838322,2.342039],[48.831947,2.34111],[48.831429,2.34131],[48.826408,2.34165],[48.823658,2.34146],[48.821461,2.34242],[48.820278,2.343939],[48.819382,2.34459],[48.817631,2.3441],[48.816441,2.34405],[48.81641,2.346839],[48.817989,2.351669],[48.817501,2.3544],[48.816441,2.356799],[48.816132,2.363339],[48.82148,2.37881],[48.825298,2.385269],[48.826481,2.38856],[48.844997,2.365959],[48.844379,2.36493],[48.840031,2.36193],[48.836731,2.35189],[48.837067,2.348927],[48.837605,2.345431],[48.838322,2.342039]]', 'RegionDistrict', 1, NULL),
(15, '14ème : Observatoire', NULL, '[[48.825428,2.301464],[48.841503,2.321032],[48.840996,2.321547],[48.843651,2.324594],[48.839695,2.336396],[48.838284,2.342105],[48.831982,2.341117],[48.828793,2.341504],[48.8256,2.341632],[48.823254,2.341504],[48.821304,2.34249],[48.820118,2.344036],[48.819355,2.344551],[48.817574,2.344164],[48.816502,2.344078],[48.816811,2.33571],[48.818478,2.332878],[48.825428,2.301464]]', 'RegionDistrict', 1, NULL),
(16, '15ème : Vaugirard', NULL, '[[48.827492,2.292365],[48.833763,2.277001],[48.830429,2.274255],[48.829807,2.275027],[48.82896,2.272797],[48.828114,2.270564],[48.828171,2.267818],[48.831108,2.267303],[48.833591,2.27065],[48.835342,2.269191],[48.834724,2.266188],[48.835175,2.264127],[48.849014,2.278204],[48.855679,2.287644],[48.858276,2.289963],[48.845852,2.309274],[48.845287,2.311335],[48.846813,2.316483],[48.845173,2.320003],[48.843594,2.324552],[48.841049,2.321633],[48.841618,2.320948],[48.825512,2.301464],[48.827492,2.292365]]', 'RegionDistrict', 1, NULL),
(17, '16ème : Passy', NULL, '[[48.878292,2.28007],[48.873859,2.294939],[48.871193,2.297387],[48.868427,2.299039],[48.865269,2.29991],[48.864574,2.301571],[48.863361,2.3018],[48.86166,2.29408],[48.85759,2.28893],[48.854321,2.28601],[48.847988,2.277429],[48.835121,2.26404],[48.835678,2.257859],[48.839298,2.253909],[48.845398,2.25477],[48.845509,2.2503],[48.848328,2.241719],[48.84856,2.2273],[48.85545,2.225929],[48.86652,2.23159],[48.870468,2.23932],[48.876339,2.2467],[48.874199,2.25511],[48.88052,2.258199],[48.878292,2.28007]]', 'RegionDistrict', 1, NULL),
(18, '17ème : Batignolles-Monceau', NULL, '[[48.878368,2.279999],[48.882771,2.283259],[48.88842,2.29185],[48.88966,2.29768],[48.895081,2.30575],[48.900612,2.321889],[48.90081,2.330131],[48.887592,2.325625],[48.88345,2.32721],[48.88131,2.31691],[48.880402,2.30867],[48.878151,2.298074],[48.873917,2.295027],[48.878368,2.279999]]', 'RegionDistrict', 1, NULL),
(19, '18ème : Butte-Montmartre', NULL, '[[48.900719,2.370379],[48.896709,2.370379],[48.895359,2.37184],[48.894459,2.370289],[48.887592,2.367081],[48.886604,2.36663],[48.884411,2.36472],[48.884411,2.358789],[48.883678,2.34952],[48.88187,2.339739],[48.882286,2.337254],[48.88443,2.32953],[48.883526,2.32792],[48.883415,2.327192],[48.887619,2.325603],[48.900829,2.33021],[48.901279,2.353209],[48.900719,2.370379]]', 'RegionDistrict', 1, NULL),
(20, '19ème : Buttes-Chaumont', NULL, '[[48.877857,2.408749],[48.875999,2.402309],[48.875259,2.395255],[48.875549,2.39021],[48.874531,2.387719],[48.874649,2.386519],[48.873859,2.38472],[48.872219,2.376819],[48.87809,2.370379],[48.882771,2.37021],[48.883339,2.36892],[48.884132,2.368669],[48.884403,2.364742],[48.886688,2.366781],[48.890202,2.368348],[48.894508,2.370289],[48.89547,2.37175],[48.896648,2.370279],[48.90078,2.370379],[48.900269,2.3769],[48.900661,2.38849],[48.899818,2.392009],[48.898239,2.39381],[48.88588,2.39725],[48.88176,2.401879],[48.880119,2.4066],[48.877857,2.408749]]', 'RegionDistrict', 1, NULL),
(21, '20ème : Ménilmontant', NULL, '[[48.865269,2.4133],[48.859058,2.414149],[48.853809,2.41398],[48.85104,2.41527],[48.846809,2.41441],[48.84811,2.399049],[48.848789,2.399309],[48.851269,2.398359],[48.856522,2.39441],[48.858475,2.389654],[48.862961,2.387379],[48.86618,2.383509],[48.867142,2.383195],[48.872238,2.376822],[48.87397,2.38497],[48.874699,2.386519],[48.874531,2.38789],[48.875549,2.390379],[48.875217,2.395297],[48.87595,2.402219],[48.877857,2.40858],[48.87431,2.410979],[48.87109,2.41338],[48.865269,2.4133]]', 'RegionDistrict', 1, NULL),
(22, 'CLIGNANCOURT JULES JOFFRIN', NULL, '[[48.897594,2.344637],[48.892487,2.348928],[48.888393,2.347984],[48.88982,2.342877],[48.889412,2.334917],[48.88662,2.332556],[48.887943,2.332685],[48.891712,2.334981],[48.89679,2.338607],[48.897594,2.344637]]', 'RegionBlock', NULL, 19),
(23, 'GRANDES CARRIERES CLICHY', NULL, '[[48.896774,2.338564],[48.891655,2.334917],[48.887974,2.332685],[48.886589,2.332578],[48.8834,2.327428],[48.887562,2.32554],[48.895676,2.328308],[48.896774,2.338564]]', 'RegionBlock', NULL, 19),
(24, 'MOSKOWA-PORTE MONTMARTRE PORTE DE CLIGNANCOURT', NULL, '[[48.895679,2.3283],[48.89677,2.33847],[48.89756,2.34461],[48.897861,2.34716],[48.897961,2.35251],[48.901741,2.352018],[48.901402,2.330132],[48.895679,2.3283]]', 'RegionBlock', NULL, 19),
(25, 'AMIRAUX SIMPLON POISSONNIERS', NULL, '[[48.891781,2.34875],[48.890388,2.35553],[48.890221,2.35695],[48.901756,2.354507],[48.901726,2.351997],[48.897961,2.35253],[48.897839,2.34712],[48.89753,2.34455],[48.892448,2.34897],[48.891781,2.34875]]', 'RegionBlock', NULL, 19),
(26, 'MONTMARTRE', NULL, '[[48.884644,2.329445],[48.882355,2.337298],[48.882019,2.339787],[48.8834,2.346869],[48.888393,2.347941],[48.889805,2.342877],[48.889381,2.334938],[48.88662,2.332578],[48.884644,2.329445]]', 'RegionBlock', NULL, 19),
(27, 'GOUTTE D&#39;OR CHATEAU ROUGE', NULL, '[[48.8834,2.346869],[48.884331,2.357125],[48.887295,2.357554],[48.890312,2.356997],[48.890427,2.35558],[48.891754,2.3488],[48.8834,2.346869]]', 'RegionBlock', NULL, 19),
(28, 'LA CHAPELLE MARX DORMOY', NULL, '[[48.884331,2.357039],[48.884388,2.364678],[48.886814,2.366781],[48.894264,2.370172],[48.89542,2.371759],[48.895733,2.37133],[48.894489,2.366352],[48.893192,2.363348],[48.895367,2.359185],[48.895226,2.355838],[48.887436,2.357554],[48.884331,2.357039]]', 'RegionBlock', NULL, 19),
(29, 'CHARLES HERMITE EVANGILE', NULL, '[[48.895699,2.37128],[48.898659,2.37021],[48.900726,2.370193],[48.901768,2.37],[48.901756,2.354486],[48.895161,2.35583],[48.89539,2.35909],[48.893162,2.36334],[48.894508,2.36639],[48.895699,2.37128]]', 'RegionBlock', NULL, 19),
(30, 'LA FOURCHE GUY MOQUET', NULL, '[[48.895901,2.32187],[48.895916,2.324617],[48.895603,2.326591],[48.895592,2.328265],[48.887592,2.325497],[48.891582,2.31848],[48.895901,2.32187]]', 'RegionBlock', NULL, 18),
(31, 'EPINETTES-BESSIERES', NULL, '[[48.895908,2.32184],[48.891521,2.31841],[48.887798,2.31481],[48.893749,2.30425],[48.895279,2.3067],[48.897499,2.31227],[48.899651,2.31908],[48.901611,2.32264],[48.901417,2.33011],[48.895599,2.32824],[48.895592,2.32652],[48.895908,2.32466],[48.895908,2.32184]]', 'RegionBlock', NULL, 18),
(32, 'BATIGNOLLES CARDINET', NULL, '[[48.89164,2.318416],[48.887577,2.32554],[48.883343,2.327428],[48.882072,2.320175],[48.886505,2.315884],[48.887409,2.313867],[48.887775,2.314811],[48.89164,2.318416]]', 'RegionBlock', NULL, 18),
(33, 'LEGENDRE-LEVIS', NULL, '[[48.887402,2.31382],[48.885151,2.310734],[48.882488,2.30545],[48.882301,2.30442],[48.880379,2.30871],[48.882069,2.32013],[48.886501,2.31579],[48.887402,2.31382]]', 'RegionBlock', NULL, 18),
(34, 'COURCELLES WAGRAM', NULL, '[[48.880383,2.308717],[48.878124,2.298117],[48.873749,2.294941],[48.878716,2.294211],[48.879761,2.294683],[48.882072,2.291722],[48.885178,2.297602],[48.880383,2.308717]]', 'RegionBlock', NULL, 18),
(35, 'TERNES-MAILLOT', NULL, '[[48.873775,2.294941],[48.878124,2.280779],[48.882244,2.283053],[48.884304,2.285929],[48.883232,2.287431],[48.885262,2.290907],[48.882919,2.290778],[48.879787,2.294641],[48.878632,2.294083],[48.873775,2.294941]]', 'RegionBlock', NULL, 18),
(36, 'CHAMPERRET BERTHIER', NULL, '[[48.884274,2.285972],[48.887775,2.291594],[48.889553,2.297387],[48.893757,2.304211],[48.887802,2.314811],[48.887436,2.313738],[48.885063,2.310691],[48.882469,2.305369],[48.882355,2.304254],[48.885208,2.297516],[48.88213,2.291765],[48.882919,2.290649],[48.885262,2.290864],[48.883232,2.287388],[48.884274,2.285972]]', 'RegionBlock', NULL, 18),
(37, 'CHAILLOT', NULL, '[[48.877346,2.283354],[48.862949,2.287173],[48.859802,2.292109],[48.862144,2.295306],[48.863216,2.29919],[48.863415,2.3017],[48.864517,2.30155],[48.865082,2.300048],[48.868584,2.298889],[48.870983,2.297473],[48.873775,2.295027],[48.877346,2.283354]]', 'RegionBlock', NULL, 17),
(38, 'PORTE DAUPHINE', NULL, '[[48.862907,2.287173],[48.864376,2.274899],[48.864037,2.27211],[48.86285,2.267947],[48.866493,2.264729],[48.872959,2.243185],[48.876316,2.246361],[48.874199,2.255116],[48.880466,2.258377],[48.877701,2.279963],[48.878124,2.28065],[48.877361,2.283311],[48.862907,2.287173]]', 'RegionBlock', NULL, 17),
(39, 'MUETTE-NORD', NULL, '[[48.872959,2.243228],[48.866295,2.231469],[48.856895,2.266359],[48.85836,2.270222],[48.857712,2.279878],[48.858532,2.284555],[48.856049,2.287173],[48.859802,2.292066],[48.862881,2.287173],[48.864376,2.274942],[48.864063,2.27211],[48.86285,2.267861],[48.866524,2.264686],[48.872959,2.243228]]', 'RegionBlock', NULL, 17),
(40, 'MUETTE-SUD', NULL, '[[48.856895,2.266316],[48.857346,2.264578],[48.856033,2.263763],[48.852757,2.275865],[48.850639,2.279577],[48.856033,2.287109],[48.858532,2.284513],[48.8577,2.279813],[48.858376,2.270179],[48.856895,2.266316]]', 'RegionBlock', NULL, 17),
(41, 'AUTEUIL-SUD', NULL, '[[48.846699,2.275565],[48.847446,2.273655],[48.846939,2.271874],[48.847588,2.269042],[48.847546,2.266231],[48.845512,2.252541],[48.843056,2.251318],[48.83865,2.251747],[48.834881,2.255309],[48.834003,2.261338],[48.833454,2.262583],[48.846699,2.275565]]', 'RegionBlock', NULL, 17),
(42, 'AUTEUIL-NORD', NULL, '[[48.84845,2.227306],[48.855453,2.225761],[48.866238,2.231426],[48.857372,2.264557],[48.856075,2.263784],[48.852741,2.275801],[48.85067,2.279577],[48.846699,2.275457],[48.847378,2.273655],[48.846981,2.271852],[48.847603,2.269192],[48.84766,2.266273],[48.845627,2.252541],[48.845627,2.250481],[48.848339,2.240353],[48.84845,2.227306]]', 'RegionBlock', NULL, 17),
(43, 'VAUGIRARD PARC DES EXPOSITIONS', NULL, '[[48.835289,2.268848],[48.835175,2.26902],[48.833652,2.270565],[48.831558,2.267132],[48.827946,2.267475],[48.827999,2.273998],[48.832352,2.280607],[48.827435,2.29228],[48.830769,2.292194],[48.836758,2.297688],[48.841106,2.287731],[48.836872,2.278547],[48.833763,2.276917],[48.835175,2.273312],[48.835289,2.268848]]', 'RegionBlock', NULL, 16),
(44, 'CITROEN-BOUCICAUT', NULL, '[[48.833366,2.262497],[48.835316,2.268763],[48.835175,2.273269],[48.833794,2.276874],[48.836926,2.278461],[48.841137,2.287731],[48.841869,2.286229],[48.842522,2.286916],[48.842888,2.286143],[48.843803,2.286251],[48.845131,2.282968],[48.843792,2.281766],[48.846699,2.275457],[48.833366,2.262497]]', 'RegionBlock', NULL, 16),
(45, 'EMERIAU-ZOLA', NULL, '[[48.843761,2.286315],[48.846699,2.288804],[48.846912,2.293203],[48.851784,2.281144],[48.850639,2.279491],[48.846672,2.275479],[48.843792,2.281702],[48.845119,2.282989],[48.843761,2.286315]]', 'RegionBlock', NULL, 16),
(46, 'DUPLEIX MOTTE PIQUET', NULL, '[[48.858166,2.289834],[48.851219,2.300906],[48.849438,2.303653],[48.847916,2.301421],[48.845261,2.297645],[48.851784,2.281122],[48.858166,2.289834]]', 'RegionBlock', NULL, 16),
(47, 'CAMBRONE GARIBALDI', NULL, '[[48.849411,2.30373],[48.847263,2.307301],[48.848057,2.310348],[48.846024,2.313437],[48.846889,2.31648],[48.845509,2.31905],[48.845112,2.32013],[48.84037,2.30416],[48.842739,2.30249],[48.843811,2.29768],[48.845261,2.2976],[48.849411,2.30373]]', 'RegionBlock', NULL, 16),
(48, 'SAINT-LAMBERT', NULL, '[[48.839977,2.290263],[48.837238,2.296529],[48.839272,2.300305],[48.840343,2.304082],[48.842716,2.302451],[48.843845,2.297602],[48.845261,2.297516],[48.8428,2.294812],[48.840996,2.291164],[48.839977,2.290263]]', 'RegionBlock', NULL, 16),
(49, 'VIOLET COMMERCE', NULL, '[[48.845287,2.297602],[48.846954,2.293267],[48.846729,2.288847],[48.843735,2.286229],[48.842857,2.286143],[48.842522,2.286916],[48.841869,2.286229],[48.840008,2.290177],[48.841049,2.291207],[48.8428,2.294769],[48.845287,2.297602]]', 'RegionBlock', NULL, 16),
(50, 'GEORGES BRASSENS', NULL, '[[48.83289,2.311378],[48.827831,2.304554],[48.827831,2.304554],[48.825401,2.301292],[48.827435,2.292237],[48.830769,2.292109],[48.836784,2.297602],[48.83289,2.311378]]', 'RegionBlock', NULL, 16),
(51, 'PASTER MONTPARNASSE', NULL, '[[48.845089,2.320175],[48.843651,2.324467],[48.84119,2.321334],[48.840202,2.321774],[48.839809,2.32142],[48.840542,2.320046],[48.83474,2.31288],[48.836319,2.310133],[48.838493,2.311442],[48.839485,2.309446],[48.841446,2.307858],[48.845089,2.320175]]', 'RegionBlock', NULL, 16),
(52, 'ALLERAY PROCESSION', NULL, '[[48.83474,2.312858],[48.83289,2.311335],[48.836758,2.297623],[48.837238,2.29655],[48.839272,2.300262],[48.841461,2.307816],[48.839497,2.309425],[48.838509,2.311442],[48.836311,2.310122],[48.83474,2.312858]]', 'RegionBlock', NULL, 16),
(53, 'RASPAIL-MONTPARNASSE', NULL, '[[48.838284,2.317257],[48.837212,2.318244],[48.837688,2.322707],[48.83416,2.332449],[48.831757,2.341075],[48.838367,2.341933],[48.843651,2.324467],[48.841221,2.321334],[48.840176,2.321763],[48.839779,2.321377],[48.840515,2.320046],[48.838284,2.317257]]', 'RegionBlock', NULL, 15),
(54, 'MONTSOURIS DAREAU', NULL, '[[48.831779,2.341053],[48.826405,2.341762],[48.823494,2.341418],[48.821262,2.342641],[48.819397,2.344551],[48.817558,2.3441],[48.815567,2.344465],[48.816319,2.341762],[48.816982,2.332063],[48.818226,2.332664],[48.818958,2.329252],[48.822182,2.330217],[48.827816,2.332363],[48.829937,2.334058],[48.834145,2.33247],[48.831779,2.341053]]', 'RegionBlock', NULL, 15),
(55, 'JEAN MOULIN PORTE D&#39;ORLEANS', NULL, '[[48.822376,2.314167],[48.824554,2.318974],[48.82478,2.318072],[48.829384,2.322664],[48.827946,2.326655],[48.827774,2.33232],[48.822182,2.33026],[48.818958,2.32923],[48.822376,2.314167]]', 'RegionBlock', NULL, 15),
(56, 'DIDOT-PORTE DE VANVES', NULL, '[[48.825375,2.301378],[48.822376,2.314038],[48.824554,2.318974],[48.824837,2.318029],[48.829414,2.322664],[48.83289,2.311292],[48.825375,2.301378]]', 'RegionBlock', NULL, 15),
(57, 'PERNETY', NULL, '[[48.83831,2.317214],[48.837238,2.318244],[48.837688,2.322707],[48.834187,2.332535],[48.829979,2.333994],[48.827831,2.332277],[48.827946,2.326655],[48.829472,2.322621],[48.83289,2.311378],[48.834782,2.312794],[48.83831,2.317214]]', 'RegionBlock', NULL, 15),
(58, 'EUROPE', NULL, '[[48.883316,2.327299],[48.87561,2.326784],[48.87516,2.324252],[48.882103,2.320604],[48.883316,2.327299]]', 'RegionBlock', NULL, 9),
(59, 'MAIRIE', NULL, '[[48.875385,2.315669],[48.881397,2.315283],[48.88216,2.320604],[48.875217,2.324295],[48.87561,2.326784],[48.873802,2.326956],[48.875385,2.315669]]', 'RegionBlock', NULL, 9),
(60, 'PARC MONCEAU', NULL, '[[48.877304,2.3067],[48.8764,2.307901],[48.874878,2.30567],[48.875328,2.315733],[48.881325,2.315283],[48.880409,2.308953],[48.879196,2.303052],[48.877304,2.3067]]', 'RegionBlock', NULL, 9),
(61, 'HOCHE FRIEDLAND', NULL, '[[48.8764,2.307891],[48.874931,2.305713],[48.87376,2.30391],[48.873367,2.304425],[48.872887,2.303545],[48.873253,2.303052],[48.871872,2.300928],[48.873802,2.295005],[48.878124,2.298106],[48.878124,2.298106],[48.879208,2.303084],[48.877312,2.3067],[48.8764,2.307891]]', 'RegionBlock', NULL, 9),
(62, 'SAINT-PHILIPPE DU ROULE', NULL, '[[48.875286,2.31436],[48.872604,2.314661],[48.870502,2.313051],[48.868977,2.310047],[48.871872,2.300906],[48.873241,2.30303],[48.872887,2.303567],[48.873383,2.304425],[48.87376,2.30391],[48.874889,2.305648],[48.875286,2.31436]]', 'RegionBlock', NULL, 9),
(63, 'ELYSEES-MADELEINE', NULL, '[[48.873798,2.32693],[48.872589,2.32639],[48.869598,2.32584],[48.8694,2.32515],[48.868889,2.32528],[48.865082,2.32232],[48.868969,2.31002],[48.870499,2.31303],[48.872608,2.31468],[48.875271,2.31433],[48.87537,2.31575],[48.873798,2.32693]]', 'RegionBlock', NULL, 9),
(64, 'TRIANGLE D&#39;OR', NULL, '[[48.865082,2.322278],[48.86319,2.320776],[48.863922,2.317858],[48.863472,2.301679],[48.864532,2.301507],[48.865082,2.300026],[48.86861,2.298868],[48.870995,2.297473],[48.873775,2.294962],[48.865082,2.322278]]', 'RegionBlock', NULL, 9),
(65, 'GROS CAILLOU', NULL, '[[48.863697,2.31039],[48.854042,2.309361],[48.854267,2.305069],[48.851276,2.300949],[48.858109,2.289791],[48.862118,2.295113],[48.863205,2.299147],[48.863415,2.3017],[48.863697,2.31039]]', 'RegionBlock', NULL, 8),
(66, 'INVALIDES', NULL, '[[48.854042,2.309318],[48.853645,2.314639],[48.851841,2.314425],[48.851727,2.319059],[48.853054,2.319059],[48.85503,2.319789],[48.86158,2.325883],[48.863216,2.32069],[48.863922,2.317901],[48.863697,2.31039],[48.854042,2.309318]]', 'RegionBlock', NULL, 8),
(67, 'SAINT-THOMAS D&#39;AQUIN', NULL, '[[48.859406,2.33305],[48.8587,2.332792],[48.858532,2.333565],[48.851894,2.328629],[48.851585,2.326827],[48.848507,2.320433],[48.851669,2.319016],[48.853054,2.318974],[48.855061,2.319703],[48.861553,2.32584],[48.859406,2.33305]]', 'RegionBlock', NULL, 8),
(68, 'ECOLE MILITAIRE', NULL, '[[48.848499,2.32039],[48.84684,2.31631],[48.84605,2.313437],[48.848026,2.310433],[48.847237,2.307386],[48.851212,2.30082],[48.85429,2.30502],[48.853642,2.31459],[48.85186,2.31442],[48.85178,2.31893],[48.848499,2.32039]]', 'RegionBlock', NULL, 8),
(69, 'MONNAIE', NULL, '[[48.858688,2.33644],[48.858425,2.337556],[48.856773,2.341043],[48.855061,2.343028],[48.854309,2.344723],[48.853775,2.344229],[48.850243,2.342598],[48.851231,2.340624],[48.852463,2.338629],[48.853676,2.338371],[48.853886,2.336912],[48.85746,2.336419],[48.857613,2.336075],[48.858688,2.33644]]', 'RegionBlock', NULL, 7),
(70, 'ODEON', NULL, '[[48.850231,2.342491],[48.84396,2.338886],[48.844273,2.337556],[48.844383,2.332792],[48.845062,2.332149],[48.848648,2.332406],[48.848789,2.332964],[48.852631,2.333007],[48.853054,2.335367],[48.853928,2.336869],[48.853786,2.338371],[48.852432,2.338629],[48.850231,2.342491]]', 'RegionBlock', NULL, 7),
(71, 'SAINT GERMAIN DES PRES', NULL, '[[48.859406,2.333007],[48.8587,2.336397],[48.857571,2.336054],[48.85746,2.336397],[48.853958,2.336826],[48.853081,2.335367],[48.851894,2.328587],[48.858532,2.333565],[48.858757,2.332664],[48.859406,2.333007]]', 'RegionBlock', NULL, 7),
(72, 'RENNES', NULL, '[[48.851616,2.326741],[48.847916,2.327728],[48.846954,2.326827],[48.845345,2.328029],[48.844017,2.330561],[48.844864,2.332191],[48.848648,2.332406],[48.848763,2.332921],[48.852657,2.332964],[48.851616,2.326741]]', 'RegionBlock', NULL, 7),
(73, 'SAINT-PLACIDE', NULL, '[[48.851501,2.326655],[48.847885,2.327771],[48.843876,2.323823],[48.845455,2.318974],[48.846897,2.316356],[48.851501,2.326655]]', 'RegionBlock', NULL, 7),
(74, 'NOTRE DAME DES CHAMPS', NULL, '[[48.843975,2.338865],[48.83997,2.33665],[48.843868,2.32378],[48.846951,2.32682],[48.845341,2.32802],[48.84404,2.33056],[48.844891,2.33223],[48.84441,2.33287],[48.844292,2.33755],[48.843975,2.338865]]', 'RegionBlock', NULL, 7),
(75, 'LES HALLES', NULL, '[[48.863407,2.350924],[48.858524,2.348156],[48.861519,2.33905],[48.862129,2.3395],[48.862228,2.3391],[48.865768,2.34109],[48.863407,2.350924]]', 'RegionBlock', NULL, 2),
(76, 'VENDOME', NULL, '[[48.867622,2.333608],[48.868328,2.330089],[48.870049,2.327986],[48.869457,2.325153],[48.868866,2.325239],[48.866436,2.323394],[48.86401,2.331161],[48.867622,2.333608]]', 'RegionBlock', NULL, 2),
(77, 'PALAIS ROYAL', NULL, '[[48.867596,2.333608],[48.865814,2.341118],[48.862259,2.339101],[48.862118,2.339573],[48.861553,2.339015],[48.86401,2.331076],[48.867596,2.333608]]', 'RegionBlock', NULL, 2),
(78, 'SAINT GERMAIN L&#39;AUXERROIS', NULL, '[[48.858517,2.348156],[48.857204,2.347426],[48.857037,2.346997],[48.855453,2.346053],[48.854324,2.344744],[48.854969,2.34313],[48.856659,2.34111],[48.85841,2.33755],[48.859428,2.33296],[48.863209,2.32073],[48.866459,2.32339],[48.86248,2.33635],[48.858517,2.348156]]', 'RegionBlock', NULL, 2),
(79, 'GAILLON-VIVIENNE', NULL, '[[48.870049,2.32802],[48.871998,2.340002],[48.87146,2.34304],[48.866428,2.34094],[48.865639,2.34154],[48.868301,2.33021],[48.870049,2.32802]]', 'RegionBlock', NULL, 3),
(80, 'SENTIER BONNE NOUVELLE', NULL, '[[48.871464,2.343006],[48.870728,2.347898],[48.869316,2.354164],[48.866245,2.352533],[48.868584,2.341847],[48.871464,2.343006]]', 'RegionBlock', NULL, 3),
(81, 'MONTORGUEIL SAINT-DENIS', NULL, '[[48.86861,2.341805],[48.866493,2.340903],[48.865589,2.341547],[48.86343,2.350903],[48.866234,2.352533],[48.86861,2.341805]]', 'RegionBlock', NULL, 3),
(82, 'REAUMUR', NULL, '[[48.869289,2.354121],[48.868809,2.356524],[48.867954,2.362404],[48.867306,2.363455],[48.866524,2.361288],[48.86549,2.356138],[48.864723,2.359614],[48.861893,2.356846],[48.862656,2.354207],[48.863407,2.350892],[48.866234,2.352544],[48.869289,2.354121]]', 'RegionBlock', NULL, 4),
(83, 'RAMBUTEAU FRANCS BOURGEOIS', NULL, '[[48.863407,2.350913],[48.861984,2.350119],[48.861229,2.353467],[48.860077,2.356857],[48.858349,2.359303],[48.857254,2.361739],[48.856464,2.364314],[48.855759,2.368498],[48.861004,2.36721],[48.861778,2.361267],[48.863403,2.358263],[48.861893,2.356836],[48.862648,2.354261],[48.863407,2.350913]]', 'RegionBlock', NULL, 4),
(84, 'TEMPLE', NULL, '[[48.866528,2.361288],[48.866528,2.361288],[48.865482,2.356138],[48.8647,2.359625],[48.863403,2.358241],[48.861771,2.361267],[48.861004,2.367189],[48.863121,2.366706],[48.866543,2.364743],[48.867313,2.363455],[48.866528,2.361288],[48.866528,2.361288]]', 'RegionBlock', NULL, 4),
(85, 'SAINT MERRI', NULL, '[[48.861992,2.350109],[48.861229,2.353456],[48.860085,2.356868],[48.857471,2.354164],[48.854664,2.352233],[48.855778,2.349551],[48.856556,2.346675],[48.857079,2.347019],[48.857204,2.347405],[48.861992,2.350109]]', 'RegionBlock', NULL, 5),
(86, 'SAINT-GERVAIS', NULL, '[[48.860027,2.356825],[48.858318,2.359271],[48.857246,2.361674],[48.856472,2.364335],[48.85458,2.362919],[48.854649,2.362683],[48.851841,2.359958],[48.854664,2.352211],[48.857513,2.354186],[48.860027,2.356825]]', 'RegionBlock', NULL, 5),
(87, 'ARSENAL', NULL, '[[48.856457,2.364314],[48.855751,2.368519],[48.853081,2.369056],[48.847164,2.36618],[48.846249,2.364399],[48.848579,2.360945],[48.851852,2.359936],[48.854664,2.362661],[48.854565,2.362919],[48.856457,2.364314]]', 'RegionBlock', NULL, 5),
(88, 'LES ILES', NULL, '[[48.848591,2.360902],[48.851288,2.352834],[48.852081,2.349808],[48.854141,2.34453],[48.855453,2.346075],[48.856556,2.346654],[48.855778,2.349529],[48.854664,2.35219],[48.851868,2.359915],[48.848591,2.360902]]', 'RegionBlock', NULL, 5),
(89, 'SORBONNE', NULL, '[[48.854126,2.34453],[48.852093,2.349787],[48.850285,2.348671],[48.849876,2.349036],[48.848591,2.348499],[48.847519,2.348521],[48.84697,2.348864],[48.844498,2.349229],[48.844398,2.348049],[48.845329,2.34468],[48.845894,2.343779],[48.846081,2.34292],[48.846375,2.34247],[48.84663,2.341547],[48.84708,2.341912],[48.847305,2.340775],[48.850258,2.342577],[48.853775,2.344229],[48.854126,2.34453]]', 'RegionBlock', NULL, 6),
(90, 'SAINT-VICTOR', NULL, '[[48.852093,2.349765],[48.850285,2.348628],[48.849876,2.349036],[48.848591,2.348435],[48.847446,2.348521],[48.84697,2.348821],[48.844482,2.349207],[48.843678,2.352684],[48.843662,2.354915],[48.843834,2.354937],[48.847588,2.362382],[48.848591,2.360923],[48.851288,2.352791],[48.852093,2.349765]]', 'RegionBlock', NULL, 6),
(91, 'JARDIN DES PLANTES', NULL, '[[48.84758,2.36238],[48.846256,2.364389],[48.845276,2.365665],[48.839931,2.36169],[48.836781,2.35184],[48.837109,2.34895],[48.839218,2.34993],[48.841469,2.34957],[48.8423,2.34972],[48.84446,2.3492],[48.84367,2.35268],[48.843689,2.35489],[48.843842,2.35491],[48.84758,2.36238]]', 'RegionBlock', NULL, 6),
(92, 'VAL DE GRACE', NULL, '[[48.84663,2.341547],[48.84639,2.342448],[48.846081,2.34292],[48.845882,2.343822],[48.845329,2.344658],[48.844425,2.34807],[48.844482,2.349186],[48.842281,2.349701],[48.841488,2.349551],[48.839245,2.349916],[48.837124,2.34895],[48.838341,2.341912],[48.839951,2.336633],[48.847321,2.340775],[48.847095,2.34189],[48.84663,2.341547]]', 'RegionBlock', NULL, 6),
(93, 'CLICHY-TRINITE', NULL, '[[48.88467,2.329445],[48.882328,2.337556],[48.88089,2.335646],[48.879265,2.334723],[48.877796,2.334251],[48.876965,2.334208],[48.876823,2.335453],[48.876175,2.335281],[48.876373,2.332277],[48.875584,2.326784],[48.883373,2.327342],[48.88467,2.329445]]', 'RegionBlock', NULL, 10),
(94, 'LORETTE-MARTYRS', NULL, '[[48.882328,2.337534],[48.882019,2.339594],[48.880383,2.340367],[48.879944,2.340152],[48.878742,2.345259],[48.878235,2.344916],[48.876514,2.344294],[48.875778,2.343564],[48.875641,2.342877],[48.87619,2.335281],[48.876808,2.335453],[48.876965,2.334208],[48.877743,2.334208],[48.87928,2.334702],[48.880875,2.335646],[48.882328,2.337534]]', 'RegionBlock', NULL, 10),
(95, 'TRUDAINE-MAUBEUGE', NULL, '[[48.882019,2.339594],[48.883415,2.346869],[48.883671,2.349594],[48.882401,2.349615],[48.880466,2.349808],[48.879208,2.349293],[48.877262,2.349057],[48.875751,2.343543],[48.876556,2.344315],[48.878292,2.344959],[48.878742,2.345259],[48.879944,2.340152],[48.880367,2.340345],[48.882019,2.339594]]', 'RegionBlock', NULL, 10),
(96, 'LAFAYETTE-RICHER', NULL, '[[48.877262,2.349036],[48.875778,2.348156],[48.874001,2.347856],[48.870712,2.347834],[48.871971,2.340002],[48.872677,2.335324],[48.875187,2.335131],[48.87619,2.33526],[48.875652,2.342899],[48.877262,2.349036]]', 'RegionBlock', NULL, 10),
(97, 'PROVENCE OPERA', NULL, '[[48.87561,2.326784],[48.876389,2.332277],[48.876205,2.33526],[48.875217,2.33511],[48.872677,2.335324],[48.872013,2.339916],[48.870037,2.328029],[48.869583,2.325819],[48.87262,2.326376],[48.873817,2.326934],[48.87561,2.326784]]', 'RegionBlock', NULL, 10),
(98, 'ST VINCENT DE PAUL LARIBOISIERE', NULL, '[[48.883656,2.349572],[48.884361,2.357039],[48.884388,2.359314],[48.882866,2.358928],[48.879536,2.357039],[48.879253,2.358584],[48.87767,2.358069],[48.876316,2.357297],[48.877335,2.349014],[48.879337,2.349272],[48.880493,2.349787],[48.883656,2.349572]]', 'RegionBlock', NULL, 11),
(99, 'LOUISBLANC AQUEDUC', NULL, '[[48.876316,2.357254],[48.877728,2.358069],[48.879223,2.358499],[48.879536,2.356997],[48.882893,2.358928],[48.884331,2.359228],[48.884388,2.364635],[48.884132,2.368412],[48.882271,2.370214],[48.879536,2.367897],[48.881142,2.364979],[48.878464,2.361803],[48.875778,2.360172],[48.876316,2.357254]]', 'RegionBlock', NULL, 11),
(100, 'PORTE ST-DENIS PARADIS', NULL, '[[48.876148,2.358112],[48.869289,2.354078],[48.870754,2.34777],[48.874001,2.34777],[48.875778,2.348113],[48.877361,2.349057],[48.876373,2.357254],[48.876148,2.358112]]', 'RegionBlock', NULL, 11),
(101, 'CHATEAU D&#39;EAU-LANCRY', NULL, '[[48.875751,2.360086],[48.87513,2.359099],[48.874229,2.362704],[48.868694,2.366996],[48.867397,2.363262],[48.867905,2.362404],[48.869289,2.354121],[48.876148,2.358112],[48.875751,2.360086]]', 'RegionBlock', NULL, 11),
(102, 'GRANGE AUX BELLES TERRAGE', NULL, '[[48.882271,2.370214],[48.877895,2.370214],[48.875217,2.36721],[48.872959,2.363648],[48.874229,2.362618],[48.87516,2.359056],[48.875778,2.360129],[48.87849,2.36176],[48.881142,2.364936],[48.879589,2.367854],[48.882271,2.370214]]', 'RegionBlock', NULL, 11),
(103, 'FAUBOURG DU TEMPLE HOPITAL ST LOUIS', NULL, '[[48.877842,2.370172],[48.872055,2.376781],[48.868694,2.366953],[48.872959,2.363648],[48.875244,2.367167],[48.877842,2.370172]]', 'RegionBlock', NULL, 11),
(104, 'REPUBLIQUE SAINT AMBROISE', NULL, '[[48.857063,2.368155],[48.863163,2.366652],[48.86655,2.364678],[48.867424,2.363262],[48.870079,2.370944],[48.860367,2.378497],[48.857063,2.368155]]', 'RegionBlock', NULL, 12),
(105, 'BELLEVILLE SAINT-MAUR', NULL, '[[48.870079,2.370944],[48.872055,2.376738],[48.866943,2.383132],[48.862907,2.387338],[48.861187,2.381029],[48.863049,2.379227],[48.862118,2.377124],[48.870079,2.370944]]', 'RegionBlock', NULL, 12),
(106, 'LEON-BLUM FOLIE-REGNAULT', NULL, '[[48.862907,2.387295],[48.858475,2.389483],[48.857628,2.392101],[48.856499,2.394462],[48.855766,2.391329],[48.855087,2.387037],[48.853928,2.381887],[48.858501,2.379055],[48.859829,2.376566],[48.860394,2.378497],[48.862144,2.377081],[48.863049,2.379227],[48.861214,2.381029],[48.862907,2.387295]]', 'RegionBlock', NULL, 12),
(107, 'BASTILLE-POPINCOURT', NULL, '[[48.854042,2.382231],[48.850174,2.384505],[48.850483,2.37987],[48.852516,2.371373],[48.853336,2.370172],[48.853111,2.369056],[48.857094,2.368112],[48.859776,2.376523],[48.858559,2.379098],[48.853928,2.381845],[48.854042,2.382231]]', 'RegionBlock', NULL, 12),
(108, 'NATION ALEXANDRE DUMAS', NULL, '[[48.854042,2.382231],[48.855145,2.387037],[48.855793,2.391243],[48.856525,2.394462],[48.851219,2.398367],[48.847958,2.399118],[48.848507,2.39502],[48.850174,2.384462],[48.854042,2.382231]]', 'RegionBlock', NULL, 12),
(109, 'BEL-AIR-NORD', NULL, '[[48.847942,2.399139],[48.846756,2.414331],[48.840061,2.41343],[48.840008,2.412701],[48.838509,2.412271],[48.837917,2.41004],[48.838142,2.408323],[48.840401,2.40901],[48.840034,2.404418],[48.840912,2.40459],[48.841106,2.402229],[48.840797,2.402272],[48.840824,2.400599],[48.844948,2.402229],[48.847347,2.398925],[48.847942,2.399139]]', 'RegionBlock', NULL, 13),
(110, 'NATION-PICPUS', NULL, '[[48.841969,2.401028],[48.841911,2.39974],[48.842464,2.399547],[48.84235,2.397766],[48.842789,2.397337],[48.841049,2.394054],[48.842663,2.392123],[48.84351,2.393861],[48.845062,2.392015],[48.844231,2.390213],[48.845444,2.388711],[48.845428,2.387252],[48.846317,2.387273],[48.846275,2.38605],[48.846897,2.387123],[48.850174,2.384419],[48.84848,2.39517],[48.847942,2.399118],[48.847336,2.398903],[48.844963,2.402208],[48.841969,2.401028]]', 'RegionBlock', NULL, 13),
(111, 'ALIGRE-GARE DE LYON', NULL, '[[48.850174,2.384419],[48.844879,2.383261],[48.844273,2.38148],[48.84317,2.378561],[48.840191,2.372468],[48.846218,2.364399],[48.847179,2.366159],[48.853138,2.369056],[48.853336,2.370172],[48.852547,2.371373],[48.850471,2.380042],[48.850174,2.384419]]', 'RegionBlock', NULL, 13),
(112, 'BERCY', NULL, '[[48.840202,2.372446],[48.842239,2.37662],[48.840542,2.378926],[48.838963,2.388024],[48.838963,2.389355],[48.837296,2.391243],[48.835232,2.392788],[48.829556,2.401071],[48.828396,2.39768],[48.827518,2.392101],[48.826473,2.388968],[48.840202,2.372446]]', 'RegionBlock', NULL, 13),
(113, 'JARDIN DE REUILLY', NULL, '[[48.837379,2.391157],[48.837521,2.391543],[48.836956,2.393475],[48.837887,2.394376],[48.838593,2.393174],[48.839497,2.394376],[48.839611,2.395749],[48.842663,2.392058],[48.84351,2.393818],[48.845062,2.392015],[48.844215,2.39017],[48.845486,2.388711],[48.845428,2.387166],[48.846306,2.387209],[48.846333,2.386007],[48.846897,2.38708],[48.850143,2.384419],[48.844864,2.383175],[48.843227,2.378583],[48.842239,2.376566],[48.840542,2.378926],[48.838989,2.387981],[48.838963,2.389269],[48.837379,2.391157]]', 'RegionBlock', NULL, 13),
(114, 'BEL-AIR-SUD', NULL, '[[48.839611,2.395749],[48.837124,2.402229],[48.835033,2.403817],[48.835033,2.405705],[48.835545,2.406564],[48.835007,2.408109],[48.833313,2.405577],[48.832973,2.404375],[48.832409,2.403903],[48.832352,2.405748],[48.833565,2.407851],[48.834751,2.411027],[48.836418,2.412357],[48.840061,2.413387],[48.840034,2.412658],[48.838535,2.412229],[48.837944,2.409954],[48.8382,2.408323],[48.840401,2.408967],[48.840061,2.404418],[48.840939,2.404547],[48.841137,2.402229],[48.840855,2.402229],[48.840855,2.400599],[48.841953,2.401028],[48.841927,2.39974],[48.842464,2.399526],[48.842381,2.39768],[48.842831,2.397337],[48.841049,2.394032],[48.839611,2.395749]]', 'RegionBlock', NULL, 13),
(115, 'VALLEE DE FECAMP', NULL, '[[48.832352,2.405748],[48.829525,2.401071],[48.835316,2.392659],[48.837379,2.391114],[48.837547,2.391586],[48.837013,2.393432],[48.837944,2.394376],[48.838593,2.393217],[48.83947,2.394333],[48.839611,2.395792],[48.837154,2.402229],[48.835064,2.403817],[48.835091,2.405791],[48.835571,2.406564],[48.835007,2.408066],[48.833313,2.405577],[48.832973,2.404289],[48.832436,2.403903],[48.832352,2.405748]]', 'RegionBlock', NULL, 13),
(116, 'SALPETRIERE-AUSTERLITZ', NULL, '[[48.83091,2.355924],[48.836021,2.370858],[48.839329,2.367425],[48.841278,2.370987],[48.845287,2.365665],[48.839951,2.361674],[48.836815,2.351847],[48.83091,2.355924]]', 'RegionBlock', NULL, 14),
(117, 'CROULEBARBE', NULL, '[[48.838341,2.341933],[48.836784,2.35189],[48.83139,2.355537],[48.829556,2.349486],[48.831787,2.340989],[48.838341,2.341933]]', 'RegionBlock', NULL, 14),
(118, 'BUTTES AUX CAILLES MOUCHEZ', NULL, '[[48.831787,2.340989],[48.829582,2.349443],[48.83139,2.355494],[48.83094,2.355924],[48.826164,2.357211],[48.825825,2.350173],[48.822491,2.346997],[48.821278,2.346997],[48.821247,2.346482],[48.819637,2.345967],[48.819412,2.344508],[48.821247,2.342577],[48.823593,2.341332],[48.826504,2.341719],[48.831787,2.340989]]', 'RegionBlock', NULL, 14),
(119, 'PEUPLIERS-BRILLAT RUNGIS', NULL, '[[48.825825,2.35013],[48.826191,2.357168],[48.815964,2.360301],[48.816105,2.355881],[48.818394,2.352448],[48.815933,2.346654],[48.81506,2.34571],[48.815598,2.344379],[48.817574,2.344036],[48.819469,2.344508],[48.819695,2.345924],[48.821247,2.346439],[48.821304,2.346954],[48.822605,2.346997],[48.825825,2.35013]]', 'RegionBlock', NULL, 14),
(120, 'NATIONALE DEUX MOULINS', NULL, '[[48.833508,2.363305],[48.83128,2.366352],[48.829159,2.365108],[48.827042,2.366481],[48.826107,2.359915],[48.826107,2.357168],[48.83091,2.355881],[48.833508,2.363305]]', 'RegionBlock', NULL, 14),
(121, 'OLYMPIADES CHOISY', NULL, '[[48.826138,2.357168],[48.826107,2.359872],[48.827042,2.366481],[48.821644,2.36927],[48.819496,2.373605],[48.815933,2.363176],[48.815964,2.360301],[48.826138,2.357168]]', 'RegionBlock', NULL, 14),
(122, 'DUNOIS BIBLIOTHEQUE JEANNE D&#39;ARC', NULL, '[[48.827042,2.366395],[48.827774,2.371373],[48.833,2.381115],[48.840233,2.372403],[48.841248,2.370987],[48.839355,2.367339],[48.836052,2.370815],[48.833481,2.363305],[48.831306,2.366266],[48.829159,2.365065],[48.827042,2.366395]]', 'RegionBlock', NULL, 14),
(123, 'PATAY-MASSENA', NULL, '[[48.833031,2.381072],[48.826504,2.388926],[48.825119,2.384849],[48.821754,2.379227],[48.819496,2.373562],[48.821644,2.369227],[48.827042,2.366438],[48.827805,2.371287],[48.833031,2.381072]]', 'RegionBlock', NULL, 14),
(124, 'PONT DE FLANDRE', NULL, '[[48.890175,2.382746],[48.890766,2.381973],[48.893501,2.376652],[48.893784,2.373304],[48.894463,2.370386],[48.895451,2.371802],[48.89576,2.371202],[48.898724,2.370129],[48.900726,2.370172],[48.901768,2.37],[48.901852,2.385321],[48.90078,2.391715],[48.899315,2.394118],[48.896042,2.396822],[48.895535,2.393646],[48.890175,2.382746]]', 'RegionBlock', NULL, 20),
(125, 'FLANDRE AUBERVILLIERS', NULL, '[[48.884132,2.368412],[48.884415,2.364593],[48.88673,2.366695],[48.894295,2.370129],[48.894489,2.370429],[48.893757,2.373476],[48.893532,2.376566],[48.892319,2.378926],[48.884132,2.368412]]', 'RegionBlock', NULL, 20),
(126, 'BASSIN DE LA VILLETTE', NULL, '[[48.882668,2.369785],[48.884132,2.368369],[48.892345,2.378798],[48.890766,2.382016],[48.890202,2.382789],[48.895561,2.393603],[48.896099,2.396736],[48.893642,2.397938],[48.889523,2.398796],[48.889523,2.395835],[48.882668,2.369785]]', 'RegionBlock', NULL, 20),
(127, 'SECRETAN', NULL, '[[48.885208,2.37927],[48.882301,2.382016],[48.881454,2.380514],[48.878773,2.37824],[48.877361,2.378368],[48.876316,2.379699],[48.876457,2.377253],[48.876373,2.37545],[48.877022,2.374034],[48.878349,2.374077],[48.877869,2.370129],[48.882301,2.370172],[48.882694,2.369785],[48.885208,2.37927]]', 'RegionBlock', NULL, 20),
(128, 'BAS BELLEVILLE', NULL, '[[48.872025,2.376738],[48.877842,2.370129],[48.878376,2.374034],[48.876995,2.374034],[48.876347,2.375536],[48.876488,2.377338],[48.876347,2.379656],[48.875893,2.381415],[48.874512,2.383261],[48.873833,2.385278],[48.873043,2.380557],[48.872025,2.376738]]', 'RegionBlock', NULL, 20),
(129, 'BUTTES CHAUMONT', NULL, '[[48.873859,2.385278],[48.874706,2.386866],[48.87479,2.388239],[48.875271,2.389526],[48.876965,2.389569],[48.876881,2.390642],[48.878464,2.390814],[48.88295,2.385492],[48.882778,2.383733],[48.882301,2.382016],[48.881481,2.380471],[48.878799,2.37824],[48.877419,2.37824],[48.876289,2.379656],[48.875919,2.38133],[48.874512,2.383304],[48.873859,2.385278]]', 'RegionBlock', NULL, 20),
(130, 'MANIN-JAURES', NULL, '[[48.889523,2.395835],[48.888283,2.395663],[48.886421,2.393904],[48.88467,2.392144],[48.883175,2.387896],[48.882919,2.385492],[48.882778,2.383647],[48.882301,2.382016],[48.885235,2.379227],[48.889523,2.395835]]', 'RegionBlock', NULL, 20),
(131, 'PLACE DES FETES', NULL, '[[48.878464,2.390814],[48.87962,2.391243],[48.879196,2.392488],[48.87962,2.394805],[48.879646,2.39635],[48.87928,2.396393],[48.879307,2.398109],[48.880154,2.398367],[48.87962,2.399697],[48.87756,2.400298],[48.875584,2.399054],[48.875301,2.395406],[48.8755,2.391973],[48.875271,2.389483],[48.876995,2.389483],[48.876938,2.390556],[48.878464,2.390814]]', 'RegionBlock', NULL, 20),
(132, 'PORTE DES LILAS', NULL, '[[48.88018,2.39832],[48.880772,2.40008],[48.878769,2.40158],[48.880489,2.40128],[48.882149,2.40248],[48.87962,2.409654],[48.878349,2.410684],[48.87603,2.40244],[48.875549,2.39901],[48.87764,2.40025],[48.879639,2.39961],[48.88018,2.39832]]', 'RegionBlock', NULL, 20),
(133, 'DANUBE', NULL, '[[48.889523,2.398796],[48.884979,2.399139],[48.88216,2.402487],[48.880524,2.401242],[48.878742,2.401543],[48.880775,2.400041],[48.880211,2.398367],[48.879307,2.398109],[48.87928,2.396393],[48.879646,2.39635],[48.87962,2.394805],[48.879196,2.392402],[48.879589,2.391243],[48.878517,2.390771],[48.882919,2.385449],[48.883202,2.387853],[48.884727,2.392144],[48.888252,2.39562],[48.88961,2.395878],[48.889523,2.398796]]', 'RegionBlock', NULL, 20),
(134, 'BELLEVILLE', NULL, '[[48.873959,2.385449],[48.873749,2.389505],[48.86998,2.394419],[48.867058,2.382982],[48.872055,2.376759],[48.873055,2.380557],[48.873817,2.385213],[48.873959,2.385449]]', 'RegionBlock', NULL, 21),
(135, 'AMANDIERS', NULL, '[[48.868835,2.389741],[48.868187,2.390084],[48.865055,2.39562],[48.862881,2.387252],[48.867085,2.383003],[48.868835,2.389741]]', 'RegionBlock', NULL, 21),
(136, 'GAMBETTA', NULL, '[[48.867737,2.396221],[48.869965,2.394419],[48.868809,2.389698],[48.868187,2.390041],[48.865055,2.395577],[48.861496,2.399268],[48.862118,2.401242],[48.862881,2.403045],[48.862625,2.403259],[48.863445,2.404075],[48.862316,2.406178],[48.864632,2.408624],[48.860649,2.40931],[48.861046,2.413859],[48.865364,2.413301],[48.871067,2.413516],[48.872646,2.413473],[48.874115,2.412958],[48.872986,2.408495],[48.870785,2.408795],[48.869202,2.405276],[48.871716,2.404289],[48.868244,2.401371],[48.869118,2.400641],[48.867737,2.396221]]', 'RegionBlock', NULL, 21),
(137, 'TELEGRAPHE-PELLEPORT SAINT-FARGEAU', NULL, '[[48.874142,2.412915],[48.876766,2.412014],[48.878349,2.410727],[48.87606,2.402487],[48.87561,2.399054],[48.875301,2.395277],[48.8755,2.392015],[48.875217,2.389483],[48.87479,2.388239],[48.874706,2.38678],[48.873974,2.385278],[48.873802,2.389441],[48.869995,2.394333],[48.867706,2.396178],[48.869118,2.400599],[48.868244,2.401328],[48.871742,2.404246],[48.869202,2.405276],[48.870754,2.40871],[48.872986,2.408452],[48.874142,2.412915]]', 'RegionBlock', NULL, 21),
(138, 'PERE LACHAISE REUNION', NULL, '[[48.853027,2.40798],[48.856384,2.405534],[48.860481,2.400641],[48.861778,2.400084],[48.861526,2.399268],[48.865082,2.395577],[48.862934,2.387252],[48.858532,2.389398],[48.857712,2.391973],[48.856556,2.394419],[48.851276,2.398238],[48.853027,2.40798]]', 'RegionBlock', NULL, 21),
(139, 'PLAINE', NULL, '[[48.846756,2.414331],[48.851418,2.415662],[48.854298,2.415147],[48.85133,2.398238],[48.848,2.399054],[48.846756,2.414331]]', 'RegionBlock', NULL, 21),
(140, 'SAINT-BLAISE', NULL, '[[48.854298,2.415104],[48.861046,2.413816],[48.86068,2.409267],[48.864632,2.408538],[48.862373,2.406092],[48.863503,2.404075],[48.862568,2.403173],[48.86285,2.402959],[48.861752,2.400041],[48.860424,2.400684],[48.856384,2.405491],[48.853081,2.407937],[48.854298,2.415104]]', 'RegionBlock', NULL, 21);

-- --------------------------------------------------------

--
-- Table structure for table `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `search_type` varchar(10) DEFAULT NULL,
  `is_temp` tinyint(1) DEFAULT '1',
  `found_items` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `conditions` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `search`
--

INSERT INTO `search` (`id`, `user_id`, `search_type`, `is_temp`, `found_items`, `name`, `conditions`, `created_at`, `updated_at`) VALUES
(1, 5, 'main', 0, 6, 'Budget and Surface Search', 'a:2:{s:10:"max_budget";a:3:{s:5:"field";s:32:"amount_of_rent_excluding_charges";s:5:"value";s:4:"2000";s:4:"sign";s:2:"<=";}s:8:"min_size";a:3:{s:5:"field";s:4:"size";s:5:"value";s:2:"10";s:4:"sign";s:2:">=";}}', '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(2, 5, 'main', 0, 10, 'Surface Search', 'a:16:{s:15:"region_block_id";a:2:{s:4:"sign";s:1:"=";s:5:"value";s:3:"101";}s:10:"min_budget";a:3:{s:5:"field";s:32:"amount_of_rent_excluding_charges";s:5:"value";s:3:"100";s:4:"sign";s:2:">=";}s:10:"max_budget";a:3:{s:5:"field";s:32:"amount_of_rent_excluding_charges";s:5:"value";s:4:"2000";s:4:"sign";s:2:"<=";}s:8:"min_size";a:3:{s:5:"field";s:4:"size";s:5:"value";s:2:"10";s:4:"sign";s:2:">=";}s:8:"max_size";a:3:{s:5:"field";s:4:"size";s:5:"value";s:4:"2000";s:4:"sign";s:2:"<=";}s:12:"is_furnished";a:2:{s:4:"sign";s:1:"=";s:5:"value";s:1:"1";}s:16:"number_of_rooms1";a:1:{i:0;a:2:{s:4:"sign";s:2:">=";s:5:"value";s:1:"5";}}s:13:"property_type";a:6:{i:0;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:1:"1";}i:1;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:1:"2";}i:2;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:1:"3";}i:3;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:1:"4";}i:4;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:1:"5";}i:5;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:1:"6";}}s:16:"number_of_rooms2";a:1:{i:0;a:2:{s:4:"sign";s:2:">=";s:5:"value";s:1:"5";}}s:10:"is_roomate";a:1:{i:0;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:1:"1";}}s:8:"planning";a:3:{i:0;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:21:"is_separate_restrooms";}i:1;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:16:"is_parquet_floor";}i:2;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:12:"is_fireplace";}}s:11:"outbuilding";N;s:8:"exterior";a:1:{i:0;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:10:"is_terrace";}}s:8:"building";a:1:{i:0;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:11:"is_guardian";}}s:14:"heating_system";a:1:{i:0;a:2:{s:4:"sign";s:1:"=";s:5:"value";s:10:"is_central";}}s:14:"lease_duration";a:2:{s:4:"sign";s:2:"<=";s:5:"value";s:1:"2";}}', '2013-11-21 13:21:58', '2013-11-21 13:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `settings_option`
--

CREATE TABLE IF NOT EXISTS `settings_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `settings_option`
--


-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_transaction_id` varchar(50) DEFAULT NULL,
  `paypal_correlation_id` varchar(50) DEFAULT NULL,
  `paypal_ec_token` varchar(50) DEFAULT NULL,
  `paypal_response_body` text,
  `is_cancelled` tinyint(1) DEFAULT '0',
  `amount` int(10) unsigned DEFAULT NULL,
  `currency_code` varchar(3) DEFAULT NULL,
  `paypal_ack` varchar(20) DEFAULT NULL,
  `is_success` tinyint(1) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `error_code` mediumint(9) DEFAULT NULL,
  `error_message` varchar(255) DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `transaction`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` varchar(10) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip` varchar(6) DEFAULT NULL,
  `facebook_id` varchar(50) DEFAULT NULL,
  `is_premium` tinyint(1) DEFAULT NULL,
  `title` varchar(3) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `confirm_registration_key` varchar(32) DEFAULT NULL,
  `restore_password_key` varchar(32) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `phone` varchar(32) DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_siret` varchar(50) DEFAULT NULL,
  `company_zip` int(11) DEFAULT NULL,
  `company_city` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `role`, `country`, `city`, `address`, `zip`, `facebook_id`, `is_premium`, `title`, `type`, `confirm_registration_key`, `restore_password_key`, `is_active`, `is_confirmed`, `phone`, `company_name`, `company_address`, `company_siret`, `company_zip`, `company_city`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'Ivan', 'Owner', 'owner@test.ru', 'e75b842e536f19d94aa8171ba69afc6a-rDJED*w_', 'owner', 'RU', NULL, NULL, NULL, NULL, NULL, NULL, 'owner', NULL, NULL, 1, 1, '', NULL, NULL, NULL, NULL, NULL, '2013-11-21 13:21:32', '2013-11-21 13:21:32'),
(2, 'dmitry', 'Dmitry', 'Petrov', 'dmitry@mail.com', 'b7a04b95effcf2e9cbf7497600dce574-M=)Q)sEb', 'owner', 'RU', NULL, NULL, NULL, NULL, NULL, NULL, 'owner', NULL, NULL, 1, 1, '', NULL, NULL, NULL, NULL, NULL, '2013-11-21 13:21:33', '2013-11-21 13:21:33'),
(3, 'alexandr', 'Alexandr', 'Maximov', 'alexandr@mail.com', '763611e031c9e8bd29a04cfd26e13cda-CiUgyx57', 'resident', 'RU', NULL, NULL, NULL, NULL, NULL, NULL, 'resident', NULL, NULL, 1, 1, '', NULL, NULL, NULL, NULL, NULL, '2013-11-21 13:21:33', '2013-11-21 13:21:33'),
(4, 'pavel', 'Pavel', 'Ivanov', 'pavel@mail.com', 'a8161bfaab9126f84110986314c79a4e-d+_*+Y:!', 'resident', 'RU', NULL, NULL, NULL, NULL, NULL, NULL, 'resident', NULL, NULL, 1, 1, '', NULL, NULL, NULL, NULL, NULL, '2013-11-21 13:21:33', '2013-11-21 13:21:33'),
(5, 'resident', 'Ivan', 'Resident', 'resident@test.ru', '4affd9754ea0bacdf6a1d3a9b95805bd-#qu&E9$v', 'resident', 'RU', NULL, NULL, NULL, NULL, NULL, NULL, 'resident', NULL, NULL, 1, 1, '', NULL, NULL, NULL, NULL, NULL, '2013-11-21 13:21:33', '2013-11-21 13:21:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_message`
--

CREATE TABLE IF NOT EXISTS `user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_by_sender_id` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_by_receiver_id` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id_idx` (`sender_id`),
  KEY `receiver_id_idx` (`receiver_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user_message`
--

INSERT INTO `user_message` (`id`, `sender_id`, `receiver_id`, `title`, `message`, `is_read`, `deleted_by_sender_id`, `deleted_by_receiver_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Hi', 'How are you??', 0, 0, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(2, 2, 1, 'RE: Hi', 'I''m fine, thanks', 0, 0, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(3, 2, 3, 'Hello, my friend', 'How are you??', 0, 0, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(4, 1, 2, 'Hi, how are you?', 'How are you??', 0, 0, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(5, 5, 2, 'Where is my money??', 'Where is my money??', 0, 0, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(6, 2, 5, 'RE: Where is my money??', 'Hmmm... What money??', 0, 0, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_property`
--

CREATE TABLE IF NOT EXISTS `user_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `property_visit_date_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `visit_time` time DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  KEY `property_id_idx` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_property`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_resident`
--

CREATE TABLE IF NOT EXISTS `user_resident` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `rent_type` varchar(15) DEFAULT NULL,
  `resident_name` varchar(255) DEFAULT NULL,
  `resident_type` varchar(15) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `employee_type` varchar(3) DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `monthly_income_guaranteed` decimal(10,2) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_resident`
--

INSERT INTO `user_resident` (`id`, `user_id`, `rent_type`, `resident_name`, `resident_type`, `job_title`, `employee_type`, `monthly_income`, `monthly_income_guaranteed`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 5, 'roommate', 'Ivan Ivanov', 'employee', 'Developer', 'cdi', 500.00, NULL, 1, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(2, 5, 'roommate', 'John Doe', 'employee', 'BeetSoft CEO', 'cdi', 1500.00, NULL, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58'),
(3, 5, 'roommate', 'Jone Doe', 'independent', 'Google Coowner', 'cdi', 125.00, NULL, 0, '2013-11-21 13:21:58', '2013-11-21 13:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_resident_document`
--

CREATE TABLE IF NOT EXISTS `user_resident_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_resident_id` int(11) DEFAULT NULL,
  `user_resident_garant_id` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_resident_id_idx` (`user_resident_id`),
  KEY `user_resident_garant_id_idx` (`user_resident_garant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_resident_document`
--

INSERT INTO `user_resident_document` (`id`, `user_resident_id`, `user_resident_garant_id`, `file`, `original_name`, `type`) VALUES
(1, 1, NULL, 'passport.pdf', 'passport.pdf', 'passport'),
(2, 1, NULL, 'student_id.pdf', 'student_id.pdf', 'student_id'),
(3, 2, NULL, 'payslip.pdf', 'payslip.pdf', 'payslip'),
(4, 3, NULL, 'payslip.pdf', 'payslip.pdf', 'payslip'),
(5, 1, 1, 'payslip.pdf', 'payslip.pdf', 'payslip');

-- --------------------------------------------------------

--
-- Table structure for table `user_resident_garant`
--

CREATE TABLE IF NOT EXISTS `user_resident_garant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_resident_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_resident_id_idx` (`user_resident_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user_resident_garant`
--

INSERT INTO `user_resident_garant` (`id`, `user_resident_id`, `amount`, `type`, `company_name`) VALUES
(1, 1, 500.00, 'family', NULL),
(2, 1, 500.00, 'employer', NULL),
(3, 2, 300.00, 'friend', NULL),
(4, 3, 500.00, 'employer', NULL),
(5, 3, 580.00, 'friend', NULL),
(6, 3, 550.00, 'family', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE IF NOT EXISTS `user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `settings` text,
  `newsletters` tinyint(1) DEFAULT '0',
  `offers` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_settings`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `alert`
--
ALTER TABLE `alert`
  ADD CONSTRAINT `alert_property_id_property_id` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alert_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_property_id_property_id` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `metro_station`
--
ALTER TABLE `metro_station`
  ADD CONSTRAINT `metro_station_metro_line_id_metro_line_id` FOREIGN KEY (`metro_line_id`) REFERENCES `metro_line` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `property_owner_id_user_id` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_region_block_id_region_id` FOREIGN KEY (`region_block_id`) REFERENCES `region` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_application`
--
ALTER TABLE `property_application`
  ADD CONSTRAINT `pppi` FOREIGN KEY (`property_visit_date_id`) REFERENCES `property_visit_dates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_application_visitor_id_property_id` FOREIGN KEY (`visitor_id`) REFERENCES `property` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_application_visitor_id_user_id` FOREIGN KEY (`visitor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_issue`
--
ALTER TABLE `property_issue`
  ADD CONSTRAINT `property_issue_property_id_property_id` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_issue_subject_id_property_issue_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `property_issue_subject` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_issue_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_visit_dates`
--
ALTER TABLE `property_visit_dates`
  ADD CONSTRAINT `property_visit_dates_property_id_property_id` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_x_metro_station`
--
ALTER TABLE `property_x_metro_station`
  ADD CONSTRAINT `property_x_metro_station_metro_station_id_metro_station_id` FOREIGN KEY (`metro_station_id`) REFERENCES `metro_station` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_x_metro_station_property_id_property_id` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `region`
--
ALTER TABLE `region`
  ADD CONSTRAINT `region_region_city_id_region_id` FOREIGN KEY (`region_city_id`) REFERENCES `region` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `region_region_district_id_region_id` FOREIGN KEY (`region_district_id`) REFERENCES `region` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `search`
--
ALTER TABLE `search`
  ADD CONSTRAINT `search_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_message`
--
ALTER TABLE `user_message`
  ADD CONSTRAINT `user_message_receiver_id_user_id` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_message_sender_id_user_id` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_property`
--
ALTER TABLE `user_property`
  ADD CONSTRAINT `user_property_property_id_property_id` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_property_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_resident`
--
ALTER TABLE `user_resident`
  ADD CONSTRAINT `user_resident_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_resident_document`
--
ALTER TABLE `user_resident_document`
  ADD CONSTRAINT `user_resident_document_user_resident_id_user_resident_id` FOREIGN KEY (`user_resident_id`) REFERENCES `user_resident` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uuui` FOREIGN KEY (`user_resident_garant_id`) REFERENCES `user_resident_garant` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_resident_garant`
--
ALTER TABLE `user_resident_garant`
  ADD CONSTRAINT `user_resident_garant_user_resident_id_user_resident_id` FOREIGN KEY (`user_resident_id`) REFERENCES `user_resident` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
