-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `Clario`;
CREATE TABLE `Clario` (
  `accession` text NOT NULL,
  `mrn` text NOT NULL,
  `patient_name` text NOT NULL,
  `site_procedure` text NOT NULL,
  `last_modified` text NOT NULL,
  `exam_time` text NOT NULL,
  `status` text NOT NULL,
  `priority` text NOT NULL,
  `site` text NOT NULL,
  `hospital` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Clario` (`accession`, `mrn`, `patient_name`, `site_procedure`, `last_modified`, `exam_time`, `status`, `priority`, `site`, `hospital`) VALUES
('2286428',	'5640086',	'RLTYS',	'MR Angiography Carotid WO',	'06/11/2018  8:16 AM ',	'06/10/2018  1:18 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE A'),
('642519',	'8963011',	'UECNV',	'MR Angiography Carotid WO',	'06/11/2018  2:37 PM ',	'06/11/2018  1:05 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE B'),
('9577691',	'3244775',	'RMAWB',	'CT Angiography Neck',	'06/11/2018  4:26 PM ',	'06/11/2018  2:37 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE C'),
('3446963',	'7489634',	'OTSMP',	'CT Angiography Abdomen and Pelvis',	'06/12/2018  1:00 PM ',	'06/12/2018 11:49 AM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE C'),
('5996376',	'5150255BR1',	'TAOJZ',	'MR Angiography Carotid W WO',	'06/12/2018  4:25 PM ',	'06/12/2018  2:08 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE A'),
('3851900',	'5150255BR1',	'TAOJZ',	'MR Angiography Cerebral WO',	'06/12/2018  4:24 PM ',	'06/12/2018  2:10 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE A'),
('453809',	'7226099',	'NCWTA',	'CT Arthrogram Knee R',	'06/12/2018  4:35 PM ',	'06/12/2018  3:10 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE D'),
('526883',	'7886258',	'UEMSR',	'CT Angiography Abdomen',	'06/12/2018  5:29 PM ',	'06/12/2018  4:12 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE D'),
('6092108',	'2442405',	'KWNRY',	'MR Cardiac for Morphology W WO',	'06/13/2018  3:38 PM ',	'06/13/2018 10:42 AM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE A'),
('1107537',	'1479805',	'RUSOD',	'CT Angiography Abdomen and Pelvis',	'06/13/2018 12:51 PM ',	'06/13/2018 10:56 AM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE C'),
('4578942',	'896636',	'YUHPA',	'CT Shoulder WO L',	'06/13/2018 12:42 PM ',	'06/13/2018 11:44 AM ',	'Final',	'2 Hour',	'Imaging Hospital',	'SITE D'),
('8922726',	'5403107',	'PBIYM',	'CT Angiography Abdomen',	'06/13/2018  2:32 PM ',	'06/13/2018  1:37 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE D'),
('6143213',	'7609063',	'ECQGY',	'CT Sinus WO',	'06/13/2018  3:38 PM ',	'06/13/2018  1:50 PM ',	'Cancelled',	'24 Hour',	'Imaging Hospital',	'SITE D'),
('9981159',	'2236850',	'CXCBB',	'CT Angiography Head',	'06/15/2018  9:52 AM ',	'06/15/2018  8:40 AM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE C'),
('1329763',	'5052910',	'SBVTL',	'CT Angiography Neck',	'06/15/2018  1:13 PM ',	'06/15/2018 12:07 PM ',	'Final',	'2 Hour',	'Imaging Hospital',	'SITE C'),
('2607531',	'3785922',	'RQFYL',	'CT Angiography Head Neck',	'06/15/2018  6:22 PM ',	'06/15/2018  4:17 PM ',	'Final',	'24 Hour',	'Imaging Hospital',	'SITE C');

DROP TABLE IF EXISTS `Groups`;
CREATE TABLE `Groups` (
  `id` int(11) DEFAULT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Groups` (`id`, `name`) VALUES
(1,	'Super Admin'),
(2,	'Manager'),
(3,	'Analyst'),
(4,	'Patient');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `group_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`created`, `updated`, `group_id`, `name`, `email`, `password`, `active`, `id`) VALUES
('2018-06-20 00:00:00',	'2018-06-20 00:00:00',	1,	'admin',	'webtestmail.10@gmail.com',	'$2y$10$AXIyw1l6oGXo.tOofWpX9eyvYS6Vdztu0IEOyu2cN0Wuke/1wyUei',	1,	1),
('2018-06-20 00:00:00',	'2018-06-20 00:00:00',	2,	'user1',	'user1@gmail.com',	'$2y$10$AXIyw1l6oGXo.tOofWpX9eyvYS6Vdztu0IEOyu2cN0Wuke/1wyUei',	1,	2),
('2018-06-23 12:01:23',	'2018-06-23 12:01:23',	2,	'Anika Curtis',	'pudowumove@mailinator.net',	'$2y$10$AEkeGU.4E26MziQJFUZhkOuED1xLlSIQsCKJndjsQBzYd6uMDQsva',	1,	27),
('2018-06-23 12:01:27',	'2018-06-23 12:01:27',	2,	'Reece Gallegos',	'wojyje@mailinator.net',	'$2y$10$wC2hBW/4koVKmrSabeLtde4RNB87i9v3tO4QaDS6a9Sqp0q.kFHPe',	1,	28),
('2018-06-23 12:01:31',	'2018-06-23 12:01:31',	2,	'Sydney Schneider',	'riqe@mailinator.com',	'$2y$10$XJ7EzCXYFBK3i5APICkKF.OvnLNiSZ41ysRn64tS23T2uWGUwW0VO',	1,	29),
('2018-06-23 12:01:36',	'2018-06-23 12:01:36',	2,	'Zachary Morrow',	'nahihuwib@mailinator.com',	'$2y$10$55hR2W3DpmhjHqkLIkXr9O7Bd1N8larM1u2RHvIgvCl3J3rRIFqLa',	1,	30),
('2018-06-23 12:02:47',	'2018-06-23 12:02:47',	2,	'Lewis Durham',	'ziqy@mailinator.com',	'$2y$10$suLVFaVTzX5mTBAH.W73hOyjlm8OS1WMZDtmYFWWC312GzDuFirO.',	1,	31),
('2018-06-23 12:02:52',	'2018-06-23 12:02:52',	2,	'Orli Henderson',	'jokeqake@mailinator.com',	'$2y$10$eMMZixs328N9W50xNzMzF.roWjhylMYjejEh5fu3Ja1en4RHj5/sC',	1,	32),
('2018-06-23 12:02:56',	'2018-06-23 12:02:56',	2,	'Sonya Cline',	'cehabewiru@mailinator.com',	'$2y$10$tgO4.pMeyD6c9mS7NJ/o1.QGQ18gMp32Ig8zJGBRWyEU1zTEEHpoG',	1,	33),
('2018-06-23 12:03:00',	'2018-06-23 12:03:00',	2,	'Emily Armstrong',	'fowam@mailinator.com',	'$2y$10$FQMo07XkVyFt2uxnic84CeMB9b8YA0xVdnJ8EvEubFJezeWu.EZD6',	1,	34),
('2018-06-23 12:03:03',	'2018-06-23 12:03:03',	2,	'Stone Bridges',	'juwajalaki@mailinator.com',	'$2y$10$PIizitT3U35rsgvtUsZz7.N1SVpS9iGL3RNMpNYHIF2bSDpXpcGD.',	1,	35),
('2018-06-23 12:03:08',	'2018-06-23 12:03:08',	2,	'Stephanie Copeland',	'dehywa@mailinator.com',	'$2y$10$o5X8bQPHlJrcmiCCNvN4t.T66Zga1BkzDfKP1fGZPeYPIFDqR7nDm',	1,	36),
('2018-06-23 12:03:36',	'2018-06-23 12:03:36',	2,	'Rhea Walker',	'hogowosedi@mailinator.com',	'$2y$10$gCT1GBX9BucL1xKv5jcJnuHHzAA4GPrDtDR8Gr.a1TEh058F0y.2.',	1,	37),
('2018-06-23 12:03:40',	'2018-06-23 12:03:40',	4,	'Timon Wheeler',	'qiga@mailinator.net',	'$2y$10$Np.LTk9n5O0G3qIk86g9iuWnQq6GWanKp3zPER.gNCU5QfPJjYNie',	1,	38),
('2018-06-23 12:03:42',	'2018-06-23 12:03:42',	3,	'Serena Clemons',	'nyfyl@mailinator.net',	'$2y$10$pT0EQjMo4hKC1M6B3fE9leD.SzKlcDyxonAXnUWm8JUYBSMZkNkWe',	1,	39),
('2018-06-23 12:03:44',	'2018-06-23 12:03:44',	3,	'Holmes Cooley',	'derog@mailinator.net',	'$2y$10$/qCcat1f8ak79VSD7BjAduAGWDPC/HxvawU1Z5L91iF/SH3jEKMdi',	1,	40),
('2018-06-23 12:03:46',	'2018-06-23 12:03:46',	1,	'Natalie Lamb',	'xyvyxuq@mailinator.com',	'$2y$10$jnQdlB7udSV0/xcytfv8eu1NS8UJfJ3ZgNyYElbFFH9dNhAO4EXUG',	1,	41),
('2018-06-23 12:03:48',	'2018-06-23 12:03:48',	1,	'Deborah Larsen',	'nivilihos@mailinator.net',	'$2y$10$CBg9xJLw2wUFQwSlgUv/HudNyYtRwMhFeCsmYJguVI1DnW7eB7XdC',	1,	42),
('2018-06-23 12:03:49',	'2018-06-23 12:03:49',	1,	'Natalie Conner',	'juqolyzab@mailinator.net',	'$2y$10$QFKCR7gYQ0ab5EogpTpmrO38/EA0rnSRE/IWorF7IoWMTECYiSh6e',	1,	43),
('2018-06-23 12:03:51',	'2018-06-23 12:03:51',	1,	'Stephanie Larsen',	'lajority@mailinator.com',	'$2y$10$QWT.txMrjsNYk/VdAqHtz.lwQjinP12.mB.K2KHJTsdM.GmPSwYni',	1,	44),
('2018-06-23 12:03:54',	'2018-06-23 12:03:54',	3,	'Cairo Casey',	'metopysaqu@mailinator.com',	'$2y$10$I76AIosIzuwqmp9FTW.G1egMewfP2PiJow6rC5ib.24Igxm3gHADa',	1,	45),
('2018-06-23 15:35:41',	'2018-06-23 15:35:41',	5,	'customer',	'customer@gmail.com',	'$2y$10$.qRMgvhjNO5.QEOvI0GKBunp8frjUxQlMu06Y3CFjJGkQUDvIzc4G',	1,	46),
('2018-06-23 16:57:29',	'2018-06-23 16:57:29',	2,	'user2',	'user2@gmail.com',	'$2y$10$o/EA0N8fmrDsm2PqAMDpMOmcdE9mXfhxGDbBW7SqAj23/eh0wZNie',	1,	47),
('2018-06-23 12:21:27',	'2018-06-23 12:21:27',	5,	'Analyst',	'analyst@gmail.com',	'$2y$10$BDTHfe6g87W.YiAeiwp89OXkRcWVbwOltFrpY47Ne50AovobqWlhC',	1,	48),
('2018-06-23 15:31:51',	'2018-06-23 15:31:51',	1,	'Paul Cyriac',	'paul@tecbirds.com',	'$2y$10$w.IFmMNUwwDVNTpTrzVgk.de3dS6mBEcgGMTQJdR6Sk4XzLF5mF.i',	1,	49);

-- 2018-06-25 12:19:15
