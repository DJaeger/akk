#! /bin/bash

# OSX (Darwin) does not know "readlink -f"
[ "$(uname)" == "Darwin" ] && inifile=$(dirname "$0")/../inc/akk.ini
# Other OS (linux) we expect to know "readlink -f"
[ "$(uname)" == "Darwin" ] || inifile=$(dirname "$(readlink -f "$0")")/../inc/akk.ini
# Load config vars
source <(grep = $inifile | sed 's/ *= */=/g' | sed "s/;/#/g")

F=$1.zwi

if [ ! -r $1 ]; then
    echo "Datei $1 nicht gefunden"
    exit 0
fi

cols=`head -1 $1  | sed 's,[^;],,g'| wc -c`

if [ $cols != 21 -a $cols != 23 ]; then
    echo "Falsches Format"
    exit 0
fi

sed 's,^﻿,,' <$1 >$F

head -1 $F | grep -q 'mitgliedsnummer.*refcode.*nachname'
if [ "$?" = "0" ]; then
    sed '1d' <$F >$F.zwi
    cp $F.zwi $F
    shred -u $F.zwi 2>/dev/null || rm -f $F.zwi
fi

if [ $cols = 23 ]; then
    fields="@akkid,mitgliedsnummer,refcode,vorname,nachname,strasse,plz,ort,lv,kv,geburtsdatum,stimmberechtigung,offenerbeitrag,eintrittsdatum,schwebend,suchname,suchvname,akk,akkrediteur,geaendert,kommentar"
elif [ $cols = 21 ]; then
    fields="@akkid,mitgliedsnummer,refcode,vorname,nachname,strasse,plz,ort,lv,kv,geburtsdatum,stimmberechtigung,offenerbeitrag,eintrittsdatum,schwebend,suchname,suchvname,akk,akkrediteur,geaendert,kommentar,warnung,offenerbeitragold"
else
    echo "Falsches Format"
fi

mysql --local-infile --user=$username --password=$password $db <<mysqlende
    DELETE FROM tblpay;
    DELETE FROM tblakk;
    DELETE FROM tbladress;
    LOAD DATA LOCAL INFILE '$F' INTO TABLE tblakk
        FIELDS TERMINATED BY ';'
        OPTIONALLY ENCLOSED BY '"'
        LINES TERMINATED BY '\r\n'
        ($fields)
    ;
mysqlende

RESULT=$?
if [ $RESULT == 0 ]; then
    echo "Akk-Datei wurde geladen"
    shred -u $F 2>/dev/null || rm -f $F
else
    echo Fehler bei MySQL LOAD DATA INFILE INTO TABLE
fi
