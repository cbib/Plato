-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 09 Février 2016 à 15:05
-- Version du serveur: 5.5.47-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `plato_export_24032016`
--
CREATE DATABASE IF NOT EXISTS `plato_export_14052016` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `plato_export_14052016`;

-- --------------------------------------------------------

--
-- Structure de la table `aliquot`
--

CREATE TABLE IF NOT EXISTS `aliquot` (
  `alq_id` int(10) NOT NULL AUTO_INCREMENT,
  `alq_number` int(10) NOT NULL,
  `alq_value` float NOT NULL,
  `alq_state` varchar(10) CHARACTER SET latin1 NOT NULL,
  `alq_location_FK` int(10) NOT NULL,
  PRIMARY KEY (`alq_id`),
  KEY `alq_location_FK` (`alq_location_FK`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `batch`
--

CREATE TABLE IF NOT EXISTS `batch` (
  `bat_id` int(10) NOT NULL AUTO_INCREMENT,
  `bat_name` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `bat_number` int(10) NOT NULL,
  `bat_date` date NOT NULL,
  `bat_layout` varchar(5) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`bat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `batch_data`
--

CREATE TABLE IF NOT EXISTS `batch_data` (
  `bat_data_id` int(10) NOT NULL AUTO_INCREMENT,
  `bat_data_batch_FK` int(10) NOT NULL,
  `bat_data_experiment_FK` int(10) NOT NULL,
  `bat_data_sample_aliquot_FK` int(5) DEFAULT NULL,
  `bat_data_row` int(2) DEFAULT NULL,
  `bat_data_col` int(2) DEFAULT NULL,
  PRIMARY KEY (`bat_data_id`),
  KEY `bat_data_batch_FK` (`bat_data_batch_FK`),
  KEY `bat_data_experiment_FK` (`bat_data_experiment_FK`),
  KEY `bat_data_sample_aliquot_FK` (`bat_data_sample_aliquot_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `enzyme`
--

CREATE TABLE IF NOT EXISTS `enzyme` (
  `ez_id` int(5) NOT NULL AUTO_INCREMENT,
  `ez_analyte` varchar(100) CHARACTER SET latin1 NOT NULL,
  `ez_slope` int(1) NOT NULL,
  `ez_code` varchar(50) CHARACTER SET latin1 NOT NULL,
  `ez_is_enzyme` int(1) NOT NULL,
  PRIMARY KEY (`ez_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table of enzymes and metabolites' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `enzyme_protocol`
--

CREATE TABLE IF NOT EXISTS `enzyme_protocol` (
  `ez_ptcl_id` int(10) NOT NULL AUTO_INCREMENT,
  `ez_ptcl_enzyme_FK` int(5) NOT NULL,
  `ez_ptcl_protocol_FK` int(10) NOT NULL,
  PRIMARY KEY (`ez_ptcl_id`),
  KEY `ez_ptcl_enzyme_FK` (`ez_ptcl_enzyme_FK`),
  KEY `ez_ptcl_protocol_FK` (`ez_ptcl_protocol_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `equation`
--

CREATE TABLE IF NOT EXISTS `equation` (
  `equ_id` int(10) NOT NULL AUTO_INCREMENT,
  `equ_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`equ_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `experiment`
--

CREATE TABLE IF NOT EXISTS `experiment` (
  `exp_id` int(10) NOT NULL AUTO_INCREMENT,
  `exp_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`exp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table of experimentations, an experimentation is composed by' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `experiment_freshweight`
--

CREATE TABLE IF NOT EXISTS `experiment_freshweight` (
  `exp_fw_id` int(10) NOT NULL AUTO_INCREMENT,
  `exp_fw_experiment_FK` int(10) NOT NULL,
  `exp_fw_freshweight_FK` int(10) NOT NULL,
  PRIMARY KEY (`exp_fw_id`),
  KEY `exp_fw_experiment_FK` (`exp_fw_experiment_FK`),
  KEY `exp_fw_freshweight_FK` (`exp_fw_freshweight_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `experiment_standard`
--

CREATE TABLE IF NOT EXISTS `experiment_standard` (
  `exp_std_id` int(10) NOT NULL AUTO_INCREMENT,
  `exp_std_standard_FK` int(10) NOT NULL,
  `exp_std_experiment_FK` int(10) NOT NULL,
  PRIMARY KEY (`exp_std_id`),
  KEY `exp_std_standard_FK` (`exp_std_standard_FK`),
  KEY `exp_std_experiment_FK` (`exp_std_experiment_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `freshweight`
--

CREATE TABLE IF NOT EXISTS `freshweight` (
  `fw_id` int(10) NOT NULL AUTO_INCREMENT,
  `fw_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`fw_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `freshweight_sample`
--

CREATE TABLE IF NOT EXISTS `freshweight_sample` (
  `fw_spl_id` int(10) NOT NULL AUTO_INCREMENT,
  `fw_spl_freshweight_FK` int(10) NOT NULL,
  `fw_spl_sample_FK` int(10) NOT NULL,
  PRIMARY KEY (`fw_spl_id`),
  KEY `fw_spl_freshweight_FK` (`fw_spl_freshweight_FK`),
  KEY `fw_spl_sample_FK` (`fw_spl_sample_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `loc_id` int(10) NOT NULL AUTO_INCREMENT,
  `loc_fridge` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `loc_etage` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `loc_box` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `loc_comment` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`loc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `processdata`
--

CREATE TABLE IF NOT EXISTS `processdata` (
  `pro_id` int(5) NOT NULL AUTO_INCREMENT,
  `pro_value` float DEFAULT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `protocol`
--

CREATE TABLE IF NOT EXISTS `protocol` (
  `ptcl_id` int(10) NOT NULL AUTO_INCREMENT,
  `ptcl_path` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`ptcl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='TODO' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `rawdata`
--

CREATE TABLE IF NOT EXISTS `rawdata` (
  `rawdata_id` int(10) NOT NULL AUTO_INCREMENT,
  `data_value` float NOT NULL,
  `data_is_excluded` int(1) NOT NULL,
  `data_velocity` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `data_is_proved` int(1) DEFAULT NULL,
  `data_enzyme_FK` int(5) DEFAULT NULL,
  `rawdata_batch_data_FK` int(11) NOT NULL,
  PRIMARY KEY (`rawdata_id`),
  KEY `data_enzyme_FK` (`data_enzyme_FK`),
  KEY `rawdata_ibfk_2` (`rawdata_batch_data_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `raw_equ_proc`
--

CREATE TABLE IF NOT EXISTS `raw_equ_proc` (
  `id_raw_equ_proc` int(5) NOT NULL AUTO_INCREMENT,
  `raw_equ_proc_rawdata_FK` int(10) NOT NULL,
  `raw_equ_proc_equation_FK` int(10) NOT NULL,
  `raw_equ_proc_processdata_FK` int(5) DEFAULT NULL,
  PRIMARY KEY (`id_raw_equ_proc`),
  KEY `raw_equ_proc_rawdata_FK` (`raw_equ_proc_rawdata_FK`),
  KEY `raw_equ_proc_equation_FK` (`raw_equ_proc_equation_FK`),
  KEY `raw_equ_proc_processdata_FK` (`raw_equ_proc_processdata_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `sample`
--

CREATE TABLE IF NOT EXISTS `sample` (
  `spl_id` int(10) NOT NULL AUTO_INCREMENT,
  `spl_number` int(10) NOT NULL,
  `spl_location_FK` int(10) NOT NULL,
  PRIMARY KEY (`spl_id`),
  KEY `spl_location_FK` (`spl_location_FK`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `sample_aliquot`
--

CREATE TABLE IF NOT EXISTS `sample_aliquot` (
  `spl_alq_id` int(10) NOT NULL AUTO_INCREMENT,
  `spl_alq_sample_FK` int(10) NOT NULL,
  `spl_alq_aliquot_FK` int(10) NOT NULL,
  `spl_alq_state` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'free',
  PRIMARY KEY (`spl_alq_id`),
  KEY `spl_alq_sample_FK` (`spl_alq_sample_FK`),
  KEY `spl_alq_aliquot_FK` (`spl_alq_aliquot_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `standard`
--

CREATE TABLE IF NOT EXISTS `standard` (
  `std_id` int(10) NOT NULL AUTO_INCREMENT,
  `std_name` varchar(99) CHARACTER SET latin1 NOT NULL,
  `std_genius` varchar(50) CHARACTER SET latin1 NOT NULL,
  `std_species` varchar(50) CHARACTER SET latin1 NOT NULL,
  `std_genotype` varchar(80) CHARACTER SET latin1 NOT NULL,
  `std_nature` varchar(50) CHARACTER SET latin1 NOT NULL,
  `std_owner` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `std_comment` varchar(99) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`std_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table of standards (material)' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `standard_enzyme`
--

CREATE TABLE IF NOT EXISTS `standard_enzyme` (
  `std_ez_id` int(10) NOT NULL AUTO_INCREMENT,
  `std_ez_unit_FK` int(10) NOT NULL,
  `std_ez_standard_FK` int(10) NOT NULL,
  `std_ez_value` float NOT NULL,
  `std_ez_enzyme_FK` int(5) NOT NULL,
  `std_ez_dilution`  varchar(99) DEFAULT NULL,
  PRIMARY KEY (`std_ez_id`),
  KEY `std_ez_unit_FK` (`std_ez_unit_FK`),
  KEY `std_ez_standard_FK` (`std_ez_standard_FK`),
  KEY `std_ez_enzyme_FK` (`std_ez_enzyme_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='join standard to enzyme' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
  `unit_id` int(10) NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(30) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table of units' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_name` varchar(15) NOT NULL,
  `usr_pwd` varchar(100) NOT NULL,
  `usr_status` varchar(10) NOT NULL,
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `aliquot`
--
ALTER TABLE `aliquot`
  ADD CONSTRAINT `aliquot_ibfk_1` FOREIGN KEY (`alq_location_FK`) REFERENCES `location` (`loc_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `batch_data`
--
ALTER TABLE `batch_data`
  ADD CONSTRAINT `batch_data_ibfk_1` FOREIGN KEY (`bat_data_batch_FK`) REFERENCES `batch` (`bat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batch_data_ibfk_3` FOREIGN KEY (`bat_data_experiment_FK`) REFERENCES `experiment` (`exp_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batch_data_ibfk_4` FOREIGN KEY (`bat_data_sample_aliquot_FK`) REFERENCES `sample_aliquot` (`spl_alq_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `enzyme_protocol`
--
ALTER TABLE `enzyme_protocol`
  ADD CONSTRAINT `enzyme_protocol_ibfk_1` FOREIGN KEY (`ez_ptcl_enzyme_FK`) REFERENCES `enzyme` (`ez_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enzyme_protocol_ibfk_2` FOREIGN KEY (`ez_ptcl_protocol_FK`) REFERENCES `protocol` (`ptcl_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `experiment_freshweight`
--
ALTER TABLE `experiment_freshweight`
  ADD CONSTRAINT `experiment_freshweight_ibfk_1` FOREIGN KEY (`exp_fw_experiment_FK`) REFERENCES `experiment` (`exp_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `experiment_freshweight_ibfk_2` FOREIGN KEY (`exp_fw_freshweight_FK`) REFERENCES `freshweight` (`fw_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `experiment_standard`
--
ALTER TABLE `experiment_standard`
  ADD CONSTRAINT `experiment_standard_ibfk_1` FOREIGN KEY (`exp_std_standard_FK`) REFERENCES `standard` (`std_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `experiment_standard_ibfk_2` FOREIGN KEY (`exp_std_experiment_FK`) REFERENCES `experiment` (`exp_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `freshweight_sample`
--
ALTER TABLE `freshweight_sample`
  ADD CONSTRAINT `freshweight_sample_ibfk_1` FOREIGN KEY (`fw_spl_freshweight_FK`) REFERENCES `freshweight` (`fw_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `freshweight_sample_ibfk_2` FOREIGN KEY (`fw_spl_sample_FK`) REFERENCES `sample` (`spl_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rawdata`
--
ALTER TABLE `rawdata`
  ADD CONSTRAINT `rawdata_ibfk_1` FOREIGN KEY (`data_enzyme_FK`) REFERENCES `enzyme` (`ez_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rawdata_ibfk_2` FOREIGN KEY (`rawdata_batch_data_FK`) REFERENCES `batch_data` (`bat_data_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `raw_equ_proc`
--
ALTER TABLE `raw_equ_proc`
  ADD CONSTRAINT `raw_equ_proc_ibfk_1` FOREIGN KEY (`raw_equ_proc_rawdata_FK`) REFERENCES `rawdata` (`rawdata_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `raw_equ_proc_ibfk_2` FOREIGN KEY (`raw_equ_proc_equation_FK`) REFERENCES `equation` (`equ_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `raw_equ_proc_ibfk_3` FOREIGN KEY (`raw_equ_proc_processdata_FK`) REFERENCES `processdata` (`pro_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sample`
--
ALTER TABLE `sample`
  ADD CONSTRAINT `sample_ibfk_1` FOREIGN KEY (`spl_location_FK`) REFERENCES `location` (`loc_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sample_aliquot`
--
ALTER TABLE `sample_aliquot`
  ADD CONSTRAINT `sample_aliquot_ibfk_1` FOREIGN KEY (`spl_alq_sample_FK`) REFERENCES `sample` (`spl_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sample_aliquot_ibfk_2` FOREIGN KEY (`spl_alq_aliquot_FK`) REFERENCES `aliquot` (`alq_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `standard_enzyme`
--
ALTER TABLE `standard_enzyme`
  ADD CONSTRAINT `standard_enzyme_ibfk_1` FOREIGN KEY (`std_ez_unit_FK`) REFERENCES `unit` (`unit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `standard_enzyme_ibfk_2` FOREIGN KEY (`std_ez_standard_FK`) REFERENCES `standard` (`std_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `standard_enzyme_ibfk_3` FOREIGN KEY (`std_ez_enzyme_FK`) REFERENCES `enzyme` (`ez_id`) ON DELETE CASCADE;
