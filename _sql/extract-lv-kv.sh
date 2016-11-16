#!/bin/bash

if [ "$4" == "" ]; then
	echo "Usage: $0 LVCODE \"KV NAME\" 319_OR_320_REPORT_FILE"
	echo "       LVCODE ist das LV Kuerzel in 2 Grossbuchstaben"
	echo "       KVNAME ist der exakte komplette Name des KVs wie im CRM"
	echo "       319_REPORT_FILE ist der Dateiname des 319er Berichts"
	echo "       320_REPORT_FILE ist der Dateiname des 320er Berichts"
	exit
fi

FILE319=$(basename $3|cut -b 1-3)_KV.csv
head -n 1 < $3 > $FILE319
grep ";\"$1\";\"$2\";" < $3 >> $FILE319

echo $(grep ";\"$1\";\"$2\";" < $3|wc -l) Zeilen fuer KV $2 nach $FILE319 gefiltert.

FILE320=$(basename $4|cut -b 1-3)_KV.csv
head -n 1 < $4 > $FILE320

echo Baue $FILE320 ..
cat $FILE319 | cut -f 2 -d ";" | while read MNR; do
	grep "^$MNR;" $4 >> $FILE320
done
echo Fertig.
