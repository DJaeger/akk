#! /bin/bash

# OSX (Darwin) does not know "readlink -f"
[ "$(uname)" == "Darwin" ] && . $(dirname "$0")/config.sh

# Other OS (linux) we expect to know "readlink -f"
[ "$(uname)" == "Darwin" ] || . $(dirname "$(readlink -f "$0")")/config.sh

if [ "$2" == "" ]; then
	echo "Usage: $0 MITGLIEDSNUMMER \"WARNUNGSTEXT\""
	echo "       Warnungstext gleich Mitgliedsnummer -> dann wird die Warnung geloescht"
	exit
fi

if [ "$1" == "$2" ]; then
mysql --user=$DBUSER --password=$DBPASS $DBNAME <<mysqlende
  UPDATE tblakk SET warnung = null WHERE mitgliedsnummer = $1;
mysqlende
	RESULT=$?
	if [ $RESULT == 0 ]; then
		echo Warnung bei Mitglied $1 entfernt, Kommentar bleibt bestehen.
	else
		echo Fehler bei MySQL Aufruf
	fi
else
mysql --user=$DBUSER --password=$DBPASS $DBNAME <<mysqlende
  UPDATE tblakk SET warnung = 1, kommentar = "$2" WHERE mitgliedsnummer = $1;
mysqlende
	RESULT=$?
	if [ $RESULT == 0 ]; then
		echo Warnung bei Mitglied $1 gesetzt.
	else
		echo Fehler bei MySQL Aufruf
	fi
fi

