<?php
$id="about";
ini_set('include_path', 'inc');
include("db.php");
include("define.php");

include("head.php");
?>
<h3>Lizenz</h3>
<p><a href="lizenz.php"><img class="fl" src="/img/cc.png" alt="" width="88" height="31"></a>  Dieses Akkreditierungstool steht unter der Creative Common Lizenz <a href="lizenz.php">CC-BY-NC-SA.</a><p>
<p>Das heißt, es kann genutzt und geändert werden, darf aber nicht für kommerzielle Zwecke zum Einsatz kommen. Bei Weiterverbreitung muss diese Lizenz erhalten bleiben.<br>
Es gibt diese Einschränkung: der Text im Header bleibt stehen.</p>

<h3>Danke</h3>
<p>Hier steht ein Dank an Hendrik, Jörg und Wilm, die das erste Akk-Tool überhaupt 
für die Piratenpartei programmiert haben sowie an Hendrik und Sebastian, die 
die Akkreditierung immer reibungslos zum Laufen gebracht haben.</p>
<p>Das Akk-tool wurde zum ersten Mal auf dem BPT 11.2 in Offenbach eingesetzt.</p>
<p>Es steht unter beerware-lizenz (http://de.wikipedia.org/wiki/Beerware) - denkt daran, wenn ihr sie seht.</p>
<p>Es wurde neu geschrieben, weil inzwischen neue Dinge dazugekommen sind, wie die Übersicht über die gezahlten Beiträge. Die gewohnte Oberfläche haben wir beibehalten.</p>
<p>Irmgard und Lothar, 2015</p>

<p>=====</p>

<p>Anpassungen an MacOSX und kleinere Änderungen sind im September 2015 entstanden.</p>
<p>:smirk: @piratenschlumpf</p>

<p>=====</p>

<p>Die weitere Entwicklung wurde Mitte 2016 von Daniel aka DJaeger übernommen.</p>
<p>Seither hat das Akk-Tool ein neues Gewand bekommen, eine neue Druck-Liste, eine Briefwahl-Adressliste und einen "Counter" mit dem man die aktuelle Zahl 
akkreditierter deutlich sichtbar auf einem Bildschirm anzeigen lassen kann.</p>
<p>Das Tool importiert die Mitgliedsdaten nun nativ mit PHP und benötigt dazu keine Scripte mehr.</p>

<p>Ende 2019 wurden noch mal Änderungen von Lothar eingespielt, dazu gehörten u.A. 
die Möglichkeit für eine AV parralel zu einem BPT und für Wahlen für das 
Europaparlament zu akkreditieren.</p>

<p>Daniel, 2021</p>


<?php
include("footer.php");
