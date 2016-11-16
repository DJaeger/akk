DIR=$(realpath $(dirname $0)/..)
DB=ppdakk
DBUSER=ppdakk
DBPASS=passwort-von-DBUSER
ADMUSER=admin
ADMPASS=akkadmin

PWDFILE=$DIR/data/passwd.users
WEBDIR=$DIR/web

mysql --user=root --password <<crdbende
DROP DATABASE IF EXISTS $DB;
CREATE DATABASE $DB CHARSET='utf8' COLLATE='utf8_general_ci';
GRANT ALL ON $DB.* TO $DBUSER@localhost IDENTIFIED BY '$DBPASS';
FLUSH PRIVILEGES;
crdbende

mysql --user=$DBUSER --password=$DBPASS $DB <<crtblende

CREATE TABLE tblakk (
  akkID int(10) unsigned NOT NULL auto_increment,
  mitgliedsnummer int(10) unsigned default NULL,
  refcode varchar(15) default NULL,
  vorname varchar(60) default NULL,
  nachname varchar(60) default NULL,
  strasse varchar(64) default NULL,
  plz varchar(10) default NULL,
  ort varchar(40) default NULL,
  lv varchar(10) default NULL,
  kv varchar(50) default NULL,
  geburtsdatum date default NULL,
  stimmberechtigung tinyint(3) default NULL,
  offenerbeitrag int(10) unsigned default NULL,
  eintrittsdatum date default NULL,
  schwebend tinyint(3) default NULL,
  suchname varchar(120) default NULL,
  suchvname varchar(120) default NULL,
  akk tinyint(3) unsigned NOT NULL default '0',
  akkrediteur varchar(50) default NULL,
  geaendert datetime default NULL,
  kommentar varchar(255) NOT NULL,
  warnung varchar(1) default NULL,
  offenerbeitragold int(10) unsigned default NULL,
  PRIMARY KEY  (akkID),
  KEY ix_mnr (mitgliedsnummer)
);

CREATE TABLE tbladress (
  adressID int(10) unsigned NOT NULL auto_increment,
  akkID int(10) unsigned NOT NULL,
  mitgliedsnummer int(10) unsigned default NULL,
  vorname varchar(250) default NULL,
  nachname varchar(60) default NULL,
  strasse varchar(64) default NULL,
  plz varchar(10) default NULL,
  ort varchar(40) default NULL,
  lv varchar(10) default NULL,
  kv varchar(50) default NULL,
  akkrediteur varchar(50) default NULL,
  geaendert datetime default NULL,
  kommentar varchar(255) default NULL,
  edit tinyint(3) unsigned default NULL,
  new tinyint(3) unsigned default NULL,
  PRIMARY KEY  (adressID),
  KEY tbladress_ibfk_1 (akkID),
  CONSTRAINT tbladress_ibfk_1 FOREIGN KEY (akkID) REFERENCES tblakk (akkID)
);

CREATE TABLE tblbeitrag (
  mnr varchar(20) NOT NULL,
  opjahr int(11) NOT NULL,
  beitragsoll int(11) NOT NULL,
  beitragist int(11) default NULL,
  datumsoll date default NULL,
  datumist date default NULL,
  bemerkung varchar(255) default NULL
);

CREATE TABLE tblpay (
  beitragID int(10) unsigned NOT NULL auto_increment,
  akkID int(10) unsigned default NULL,
  mitgliedsnummer varchar(20) default NULL,
  beitragoffen int(11) default NULL,
  gezahlt int(11) default NULL,
  akkrediteur varchar(50) default NULL,
  geaendert datetime default NULL,
  kommentar varchar(255) default NULL,
  PRIMARY KEY  (beitragID),
  KEY akkID (akkID),
  CONSTRAINT tblpay_ibfk_1 FOREIGN KEY (akkID) REFERENCES tblakk (akkID)
);

CREATE TABLE tbluser
(
  login varchar(20),
  name  varchar(60),
  rolle int
);
CREATE UNIQUE INDEX iuser01 ON tbluser (name);
INSERT INTO tbluser (login,name,rolle) VALUES ('$ADMUSER','Administrator',9);
crtblende

htpasswd -bc $PWDFILE $ADMUSER $ADMPASS
chmod 666 $PWDFILE

cat >$WEBDIR/.htaccess <<htaccessende
AuthName "PHPMyAdmin"
AuthType Basic
AuthUserFile $PWDFILE
require valid-user
htaccessende

chmod 644 $DIR/web/.htaccess

