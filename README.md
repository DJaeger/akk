Akkreditierungstool für Piraten Parteitage
==========================================

Quelle: https://github.com/Djaeger/akk

WICHTIGER HINWEIS:
------------------
Dieses Tool benötigt **als Server-System** entweder

* ein Linux System mit LAMP
* ein OSX/Apple System mit entsprechend MySQL, Apache2, PHP

Windows wird als Server-System nicht nativ unterstützt und ist ungetestet.
Raspberry Pis reichen vollkommen aus und wurden bereits auf meheren Parteitagen genutzt.

**Für die Clients** genügt ein Browser, Betriebsystem egal.
Aus Datenschutzgründen müssen die Clients sich in einem getrennten Netzwerk
befinden (kein Internet, keine Verbindung zum Rest des Parteitags)

Bitte setzt das System aus Datenschutzgründen in einer virtuelle Maschine auf 
einem Host mit verschlüsselten Festplatten auf und lösche die virtuelle 
Maschine nach dem erfolgreichen Parteitag bzw. sobald du die Daten nicht mehr 
benötigst  (mindestens also nachdem die Einspruchsfrist gegen den Parteitag bei 
Schiedgerichten abgelaufen ist).
Wenn Du einen Rasperry nutzt, achte darauf, dass er während der Veranstaltung nicht "greifbar" aufgestellt ist.
Nach dem Parteitag entferne die SD-Karte und hebe sie bis zur sicheren Löschung sicher auf.

Je nach Betriebsystem benötigt die virtuelle Machine ca. 3GB Plattenplatz, 
(bei einem genügsamen Linux ohne Grafik, OpenSSH und LAMP installieren z.B. 
Ubuntu Server 64-bit/amd64 von http://www.ubuntu.com/download/server, 
1GB Ram, 1 CPU, 2MB Videospeicher) mit Virtualisierungssoftware von
https://www.virtualbox.org


Automatische Installation
=========================
Lade das aktuelle Installations-Script herunter:
```
wget http://scripts.piraten.tools/install-akk.sh
```
Für eine geführte volle Installation einfach mit Bash ausführen:
```
bash install-akk.sh
```
Für eine nicht interaktive Installation (Abschluss des Setups über die Web-Oberfläche):
```
bash install-akk.sh -q
```
Die Einrichtung des Akk-Tools kann jederzeit (neu) gestartet werden über:
http://127.0.0.1:1337/install.php


Manuelles Deployment und Ausführung
===================================

Clone den aktuellen Stand:
```
git clone https://github.com/DJaeger/akk.git /srv/akk/web/
```
Erstelle ein Daten- und ein Upload-Verzeichnis:
```
mkdir /srv/akk/data
mkdir /srv/akk/upload
```

Apache Setup
------------
Das Akk-Tool geht davon aus das es unter dem http(s):/localhost Root
betrieben wird. URLs wie http://localhost/~benutzer/akk/index.html
funktionieren nicht!

Wenn du dieses Paket z.B. nach "/srv/akk/web" entpackt hast, dann 
richte nun einen virtuellen Host in Apache ein der auf einem Port
(z.B. 1337) hört und der exakt diesen Pfad als sein DocumentRoot
Pfad ansieht:
```
sudo nano /etc/apache2/sites-available/akk.conf
```

Trage folgendes ein, evtl. an deine Gegebenheiten angepasst:
```
<Directory "/srv/akk/web">
	Options Indexes FollowSymLinks Multiviews
	AllowOverride All
	Order allow,deny
	Allow from all
	Require all granted
</Directory>
Listen 1337
<VirtualHost *:1337>
	DocumentRoot "/srv/akk/web"
</VirtualHost>
```

Aktivieren der neuen Konfiguation: 
```
sudo a2ensite akk
sudo systemctl reload apache2
```
Noch besser wäre, wenn du SSL für diesen virtuellen host einrichtest.
   

Datenbank Setup
---------------

```
DROP DATABASE IF EXISTS akkdb;
CREATE DATABASE akkdb CHARSET='utf8' COLLATE='utf8_general_ci';
GRANT ALL ON akkdb.* TO akkuser@localhost IDENTIFIED BY 'akkuserpw';
FLUSH PRIVILEGES;
```


Akk-Tool Setup
--------------
Nutze den Web-Installer:
http://127.0.0.1:1337/install.php

Oder trage alle Daten manuell in inc/akk.ini ein:

```
[database]
driver = mysql
host = localhost
;port = 3306
db = akkdb
username = akkuser
password = akkuserpw

[akk]
Veranstaltung = LPT NRW 2019.1
startdate = 26.10.2019
enddate = 27.10.2019
Ort = Herne
# Gliederungsebene: "BV" für einen BPT (Statistik über LVs),
#     "LV" für einen LPT (Statistik über KV) oder 
#     "KV" für eine Kreismitgliederversammlung (Statistik über Orte)
#     oder "EP" zur Wahl für's Europaparlament (Statistik über Nationen)
Ebene = LV
# Parteitag?
PT = 1
# Auftellungsversammlung?
AV = 1

[system]
# rootdir fuer den Upload und htpasswd
# falls nichts angegeben ist, ist /web/akk der default
# rootdir = /web/akk
rootdir = /srv/akk

# htpasswd kann man setzen - wenn nicht gesetzt wird
# wird der folgende Default genommen:
# htpasswd = $rootdir/data/passwd.users
htpasswd = /srv/akk/data/passwd.users
```

Benutzung
-------------
Verbinde dich auf http://localhost:1337/ oder https://localhost:1337/
Dort findest du über die Menüs in der oberen Leiste alle Funktionen.


Daten-Import
============
Logge dich wieder im Akk-Tool ein, wähle im Menü "Upload Mitgliedsdaten"
Wähle beide Dateien aus, klicke auf "Upload".

Falls der Upload fehlschlägt prüfe ob es Dateien in /web/akk/upload gibt.
Falls nicht: Irgendwas mit den Berechtigungen der Verzeichnisse ist nicht
             richtig.

Daten für Parteitage
--------------------
Besorge dir eine Akkreditierungsliste und eine Beitragsliste.

**Option 1:** Im PRM gibt es die Berichte
   
* 319 - AkkTool Parteitag Akk-Datei
* 320 - AkkTool Parteitag Beitrag-Datei
     
Beide Dateien musst du für deine Gliederung herunterladen.

Der LandesGenSek kann diese Dateien herunterladen, allerdings nur für seinen
kompletten LV. Für einen KV müssen die Dateien erst gefiltert werden.

**Option 2:** Frage die Bundesmitgliederverwaltung oder -schatzmeisterei.
   
Daten für Aufstellungsversammlungen
-----------------------------------
Besorge dir eine Akkreditierungsliste und eine Beitragsliste.

Im PRM gibt es die Berichte

* 323 - AkkTool AV(nur LV) Akk-Datei
* 324 - AkkTool AV(nur LV) Beitrag-Datei

Weiteres Vorgehen wie oben.

WICHTIG: AV Daten sind nur auf Landesverbands-Ebene verfügbar. Für AVs 
unterhalb des Bundeslandes muss vorher nach Postleitzahl/Straße auf den
entsprechende Bezirks-/Kreis-AV Bereicht eingeschränkt werden.

Daten für Parteitag plus AV
---------------------------
Musst du für eine Veranstaltung akkreditieren, bei der Parteitag und AV quasi
gleichzeitig stattfinden, dann kannst du entweder beides im Akk-Tool aktivieren
und über ein Akk-Tool für beides akkreditieren oder einen Server für den PT und
einen Server für die AV aufzusetzen.


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

=====

Dieses README, die Anpassungen an MacOSX und kleinere Änderungen sind 
im September 2015 entstanden.

:smirk: @piratenschlumpf

=====

Die weitere Entwicklung wurde Mitte 2016 von Daniel aka DJaeger übernommen.
Seither hat das Akk-Tool ein neues Gewand bekommen, eine neue Druck-Liste, eine
Briefwahl-Adressliste und einen "Counter" mit dem man die aktuelle Zahl 
akkreditierter deutlich sichtbar auf einem Bildschirm anzeigen lassen kann.
Das Tool importiert die Mitgliedsdaten nun nativ mit PHP und benötigt dazu
keine Scripte mehr.

Ende 2019 wurden noch mal Änderungen von Lothar eingespielt, dazu gehörten u.A. 
die Möglichkeit für eine AV parralel zu einem BPT und für Wahlen für das 
Europaparlament zu akkreditieren.

Daniel, 2021
