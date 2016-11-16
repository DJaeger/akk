#! /bin/bash

set -e

# OSX (Darwin) does not know "readlink -f"
# Other OS (linux) we expect to know "readlink -f"
[ "$(uname)" == "Darwin" ] && . $(dirname "$0")/config.sh
[ "$(uname)" == "Darwin" ] || . $(dirname "$(readlink -f "$0")")/config.sh


mysql --local-infile --user=$DBADMINUSER --password=$DBADMINPASS $DBNAME <<mysqlende

SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT;
SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS;
SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION;
SET NAMES utf8;
SET @OLD_TIME_ZONE=@@TIME_ZONE;
SET TIME_ZONE='+00:00';
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0;

DROP DATABASE IF EXISTS \`$DBNAME\`;

CREATE DATABASE \`$DBNAME\` CHARACTER SET utf8 COLLATE utf8_general_ci;

GRANT ALL PRIVILEGES ON \`$DBNAME\`.* TO '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASS';
FLUSH PRIVILEGES;

USE \`$DBNAME\`;

CREATE TABLE \`tbladress\` (
  \`adressID\` int(10) unsigned NOT NULL AUTO_INCREMENT,
  \`akkID\` int(10) unsigned NOT NULL,
  \`mitgliedsnummer\` int(10) unsigned DEFAULT NULL,
  \`vorname\` varchar(250) DEFAULT NULL,
  \`nachname\` varchar(60) DEFAULT NULL,
  \`strasse\` varchar(64) DEFAULT NULL,
  \`plz\` varchar(10) DEFAULT NULL,
  \`ort\` varchar(40) DEFAULT NULL,
  \`lv\` varchar(10) DEFAULT NULL,
  \`kv\` varchar(50) DEFAULT NULL,
  \`akkrediteur\` varchar(50) DEFAULT NULL,
  \`geaendert\` datetime DEFAULT NULL,
  \`kommentar\` varchar(255) DEFAULT NULL,
  \`edit\` tinyint(3) unsigned DEFAULT NULL,
  \`new\` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (\`adressID\`),
  KEY \`tbladress_ibfk_1\` (\`akkID\`),
  CONSTRAINT \`tbladress_ibfk_1\` FOREIGN KEY (\`akkID\`) REFERENCES \`tblakk\` (\`akkID\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE \`tblakk\` (
  \`akkID\` int(10) unsigned NOT NULL AUTO_INCREMENT,
  \`mitgliedsnummer\` int(10) unsigned DEFAULT NULL,
  \`refcode\` varchar(15) DEFAULT NULL,
  \`vorname\` varchar(60) DEFAULT NULL,
  \`nachname\` varchar(60) DEFAULT NULL,
  \`strasse\` varchar(64) DEFAULT NULL,
  \`plz\` varchar(10) DEFAULT NULL,
  \`ort\` varchar(40) DEFAULT NULL,
  \`lv\` varchar(10) DEFAULT NULL,
  \`kv\` varchar(50) DEFAULT NULL,
  \`geburtsdatum\` date DEFAULT NULL,
  \`stimmberechtigung\` tinyint(3) DEFAULT NULL,
  \`offenerbeitrag\` int(10) unsigned DEFAULT NULL,
  \`eintrittsdatum\` date DEFAULT NULL,
  \`schwebend\` tinyint(3) DEFAULT NULL,
  \`suchname\` varchar(120) DEFAULT NULL,
  \`suchvname\` varchar(120) DEFAULT NULL,
  \`akk\` tinyint(3) unsigned NOT NULL DEFAULT '0',
  \`akkrediteur\` varchar(50) DEFAULT NULL,
  \`geaendert\` datetime DEFAULT NULL,
  \`kommentar\` varchar(255) NOT NULL,
  \`warnung\` varchar(1) DEFAULT NULL,
  \`offenerbeitragold\` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (\`akkID\`),
  KEY \`ix_mnr\` (\`mitgliedsnummer\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE \`tblbeitrag\` (
  \`mnr\` varchar(20) NOT NULL,
  \`opjahr\` int(11) NOT NULL,
  \`beitragsoll\` int(11) NOT NULL,
  \`beitragist\` int(11) DEFAULT NULL,
  \`datumsoll\` date DEFAULT NULL,
  \`datumist\` date DEFAULT NULL,
  \`bemerkung\` varchar(255) DEFAULT NULL,
  \`mnrorg\` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE \`tblpay\` (
  \`beitragID\` int(10) unsigned NOT NULL AUTO_INCREMENT,
  \`akkID\` int(10) unsigned DEFAULT NULL,
  \`mitgliedsnummer\` varchar(20) DEFAULT NULL,
  \`beitragoffen\` int(11) DEFAULT NULL,
  \`gezahlt\` int(11) DEFAULT NULL,
  \`akkrediteur\` varchar(50) DEFAULT NULL,
  \`geaendert\` datetime DEFAULT NULL,
  \`kommentar\` varchar(255) DEFAULT NULL,
  PRIMARY KEY (\`beitragID\`),
  KEY \`akkID\` (\`akkID\`),
  CONSTRAINT \`tblpay_ibfk_1\` FOREIGN KEY (\`akkID\`) REFERENCES \`tblakk\` (\`akkID\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE \`tbluser\` (
  \`login\` varchar(50) NOT NULL DEFAULT '',
  \`name\` varchar(50) DEFAULT NULL,
  \`rolle\` int(11) DEFAULT NULL,
  PRIMARY KEY (\`login\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET TIME_ZONE=@OLD_TIME_ZONE;
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT;
SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS;
SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION;
SET SQL_NOTES=@OLD_SQL_NOTES;

mysqlende
