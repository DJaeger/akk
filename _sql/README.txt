README
======
Das Script db-init.sql im Verzeichnis _sql legt die Datenbank 'akk' an.

Username und Passwort muessen mit der Einstellung in akk.ini uebereinstimmen.

Es gibt auch ein Script von Lothar namens init.sh-von-lothar.sh
das noch mehr tut - schaut es auch an bevor ihr es benutzt. 
(Es gab Feedback das init.sh-von-lothar.sh noch Fehler haette)

Userverwaltung für das AKK-TOOL
===============================
In akk.ini und inc/akk.ini sind Einträge für eine htpasswd Datei definiert, 
diese sind aber nicht relevant. 

Relevant ist das "rootdir". Dieses sollte aus Sicherheitsgründen nicht das
gleiche sein wie dieses hier. Wenn es als Default oder als

	rootdir = /web/akk
	
definiert ist, dann ist dadrunter das Verzeichnis upload wichtig.

	mkdir -p /web/akk/upload


Ausserdem relevant sind die Einträge in .htaccess,
welche aber auf die (gleiche) Datei "/web/akk/data/passwd.users" zeigen.
(bzw. anderer Pfad/Name wie durch rootdir und/oder htpasswd definiert)

Diese muss angelegt werden, Beispiel:

	htpasswd -c /web/akk/data/passwd.users admin
	
Es wird empfohlen ein sicheres Passwort zu nehmen!

Weitere User kannst du über die Web-Oberfläche anlegen.
Falls du einen User "admin" anlegst achte darauf das er die Rolle "9" bekommt!

Stelle sicher, das der apache Webserver die Datei lesen und schreiben kann,
z.B. durch ein

	chgrp -R _www /web/akk
	chmod -R g+w /web/akk
	chmod a+rx /web/akk /web/akk/data /web/akk/upload /web/akk/data/*.sh

Mac OS X - Wie man apache, sql und php ans laufen bringt
========================================================

Man lese (fuer OSX 10.10):
http://coolestguidesontheplanet.com/get-apache-mysql-php-phpmyadmin-working-osx-10-10-yosemite/

fuer 10.11: 
http://coolestguidesontheplanet.com/get-apache-mysql-php-and-phpmyadmin-working-on-osx-10-11-el-capitan/


1. Möglicherweise ist MySQL für PHP nicht aktiviert.
In dem Fall bekommst du eine Fehlermeldung etwa

  PHP Fatal error:  Uncaught exception 'PDOException' with message 'SQLSTATE[HY000] [2002] 
  No such file or directory' in .../akk/inc/db.php:13

wenn du 

  php index.php

ausführst. Dann:

Als root /etc/php.ini.default nach /etc/php.ini kopieren.
Den Eintrag pdo_mysql.default_socket in php.ini ändern in:

  pdo_mysql.default_socket=/tmp/mysql.sock



2. Möglicherweise meckert PHP, dass es old_passwords nicht mag:

  PHP Fatal error:  Uncaught exception 'PDOException' with message 'SQLSTATE[HY000] [2054] 
  The server requested authentication method unknown to the client'

Schalte old_passwords in /etc/my.cnt deiner MySQL (oder über die Adminoberfläche) aus.
Setze das Passwort des users aus akk.ini in der MySQL neu.
Alternative: 
http://zewaren.net/site/?q=node/121

