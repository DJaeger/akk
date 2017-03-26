Akkreditierungstool für Piraten Parteitage
==========================================

Quelle: https://github.com/Strassenkehrer/akk

WICHTIGER HINWEIS:
------------------
Dieses Tool benötigt **als Server-System** entweder

* ein Linux System mit LAMP
* ein OSX/Apple System mit entsprechend MySQL, Apache2, PHP

Windows wird als Server-System nicht nativ unterstützt und ist ungetestet.

**Für die Clients** genügt ein Browser, Betriebsystem egal.
Aus Datenschutzgründen müssen die Clients sich in einem getrennten Netzwerk
befinden (kein Internet, keine Verbindung zum Rest des Parteitags)

Bitte setzt das System aus Datenschutzgründen in einer virtuelle Maschine auf 
einem Host mit verschlüsselten Festplatten auf und lösche die virtuelle 
Maschine nach dem erfolgreichen Parteitag bzw. sobald du die Daten nicht mehr 
benötigst  (mindestens also nachdem die Einspruchsfrist gegen den Parteitag bei 
Schiedgerichten abgelaufen ist).

Je nach Betriebsystem benötigt die virtuelle Machine ca. 3GB Plattenplatz, 
(bei einem genügsamen Linux ohne Grafik, OpenSSH und LAMP installieren z.B. 
Ubuntu Server 64-bit/amd64 von http://www.ubuntu.com/download/server, 
1GB Ram, 1 CPU, 2MB Videospeicher) mit Virtualisierungssoftware von
https://www.virtualbox.org


Deployment und Ausführung
=========================

Datenbank Setup
---------------
Im Verzeichnis **_sql** ist ein README.txt für die Datenbank und Apache.
Dort wird auch das mit der Userverwaltung erklärt.
Lesen und machen.

Apache Setup
------------
Das Akk-Tool geht davon aus das es unter dem http(s):/localhost Root
betrieben wird. URLs wie http://localhost/~benutzer/akk/index.html
funktionieren nicht!
   
Wenn du dieses Paket nach "$HOME/Sites/akk" entpackt hast, dann 
richte nun einen virtuellen Host in Apache ein der auf einem Port
(z.B. 1337) hört und der exakt diesen Pfad als sein DocumentRoot
Pfad ansieht:
   
Mac:
```
cd /etc/apache2/users
sudo vi $LOGNAME.conf
```
Trage etwa das folgende ein, wobei BENUTZERNAME dein $LOGNAME ist:

```
<Directory "/Users/BENUTZERNAME/Sites/">
	Options Indexes FollowSymLinks Multiviews
	AllowOverride All
	Order allow,deny
	Allow from all
	Require all granted
</Directory>

Listen 1337
<VirtualHost *:1337>
	DocumentRoot "/Users/BENUTZERNAME/Sites/akk"
</VirtualHost>
```

Noch besser wäre, wenn du SSL für diesen virtuellen host einrichtest.
   
Neustart des Apache2: 
```
sudo apachectl restart
```

Akk-Tool Setup
--------------
Trage deinen Parteitag in inc/akk.ini ein:

```
[akk]
Veranstaltung = Bezeichnung des Parteitags, z.B. BPT 15.1
Datum = Datumszeitraum, z.B. 25.7. - 26.7.2015
Ort = Ort des Parteitags, z.B. Würzburg
Ebene = Gliederungsebene: "BV" für einen BPT (Statistik über LVs),
		"LV" für einen LPT (Statistik über KVs)
		oder "KV" für eine Kreismitgliederversammlung (Statistik über Orte)
```

Funktionstest
-------------
Verbinde dich auf http://localhost:1337/ oder https://localhost:1337/
Nach login sollte rechts ein Menü erscheinen.
Unter "Statistik" gibt es eine "Userverwaltung".
Falls du deinen admin Account einträgst, achte auf die richtige Rolle (9).

Daten für Parteitage
--------------------
Besorge dir eine Akkreditierungsliste und eine Beitragsliste.

**Option 1:** Im CRM und im PRM gibt es die Berichte
   
* 319 - AkkTool Parteitag Akk-Datei
* 320 - AkkTool Parteitag Beitrag-Datei
     
Beide Dateien musst du für deine Gliederung herunterladen.

Der LandesGenSek kann diese Dateien herunterladen, allerdings nur für seinen
kompletten LV. Um den Inhalt der Dateien für einen KV zu filtern kann er 
das Script
```
extract-lv-kv.sh
```
benutzen. Das Script erklärt sich nach Aufruf selbst:
```
Usage: ./extract-lv-kv.sh LVCODE "KV NAME" 319_OR_320_REPORT_FILE
       LVCODE ist das LV Kuerzel in 2 Grossbuchstaben
       KVNAME ist der exakte komplette Name des KVs wie im CRM
       319_REPORT_FILE ist der Dateiname des 319er Berichts
       320_REPORT_FILE ist der Dateiname des 320er Berichts
```
Beispiel:
```
extract-lv-kv.sh  NW "Duisburg" Downloads/319_TfDA.csv Downloads/320_j1zQ.csv
```
filtert die Daten NRWs für "Duisburg" in die Dateien 319_KV.csv und 320_KV.csv
und der LV GenSek kann diese beiden Dateien als Akkreditierungsdaten an den 
Kreis "Duisburg" weitergeben.


**Option 2:** Frage die Bundesschatzmeisterei (von Lothar oder Irmgard).
   
Daten für Aufstellungsversammlungen
-----------------------------------
Besorge dir eine Akkreditierungsliste und eine Beitragsliste.

Im CRM gibt es die Berichte

* 323 - AkkTool AV(nur LV) Akk-Datei
* 324 - AkkTool AV(nur LV) Beitrag-Datei

Weiteres Vorgehen wie oben.

WICHTIG: AV Daten sind nur auf Landesverbands-Ebene verfügbar. Für AVs 
unterhalb des Bundeslandes muss vorher nach Postleitzahl/Straße auf den
entsprechende Bezirks-/Kreis-AV Bereicht eingeschränkt werden.

Das Akk-Tool erwartet auch bei AVs eine Stimmberechtigung.
Das ist rechtlich so nicht nötig. Finde einen Weg das zu umgehen,
z.B. in dem du eine Fake-Zahlung bei einer AV erfasst.

Daten für Parteitag plus AV
---------------------------
Musst du für eine Veranstaltung akkreditieren, bei der Parteitag und AV quasi
gleichzeitig stattfinden, dann ist die Empfehlung einen Server für den PT und
einen Server für die AV aufzusetzen.
In beiden Systemen muss das Mitglied geprüft werden und ggf. nur Unterlagen
für den Parteitag oder nur die AV ausgehändigt werden.


Daten-Import
------------
Logge dich wieder im Akk-Tool ein, wähle im Menü "Upload Mitgliedsdaten"
Wähle beide Dateien aus, klicke auf UPLOAD.

Falls der Upload fehlschlägt prüfe ob es Dateien in /web/akk/upload gibt.
Falls nicht: Irgendwas mit den Berechtigungen der Verzeichnisse ist nicht
             richtig.
Falls Dateien vorhanden: Versuche die Dateien durch Aufruf der Scripte manuell 
einzuspielen:
```
/web/akk/data/impakk.sh /web/akk/upload/uplakk.csv
/web/akk/data/impbeitrag.sh /web/akk/upload/uplbeitrag.csv
```
Wenn auch das nicht gelingt, wende dich an Lothar oder Irmgard.

===============================================================================

Danke
=====
Hier steht ein Dank an Hendrik, Jörg und Wilm, die das erste Akk-Tool überhaupt 
für die Piratenpartei programmiert haben sowie an Hendrik und Sebastian, die 
die Akkreditierung immer reibungslos zum Laufen gebracht haben.

Das Akk-tool wurde zum ersten Mal auf dem BPT 11.2 in Offenbach eingesetzt.
Es steht unter beerware-lizenz (http://de.wikipedia.org/wiki/Beerware) - denkt 
daran, wenn ihr sie seht. Es wurde neu geschrieben, weil inzwischen neue Dinge 
dazugekommen sind, wie die Übersicht über die gezahlten Beiträge. Die gewohnte 
Oberfläche haben wir beibehalten.

Irmgard und Lothar, 2015

===============================================================================

Dieses README, die Anpassungen an MacOSX und kleinere Änderungen sind 
im September 2015 entstanden.

:smirk: @piratenschlumpf
