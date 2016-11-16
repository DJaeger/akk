<div id = "search">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<fieldset class="fl"><legend>Mnr oder Nachname:</legend><input type="Text" class="w20" name="mnr" id="mnr" value="" size="1" ></fieldset>
<fieldset class="fl"><legend>Vorname:</legend><input type="Text" class="w20" name="vorname" id="vorname" value="" size="1" ></fieldset>
<p class="fl"><input class="button" type="submit" value="Suchen" name="send"></p>
<div class="cb"></div>
</form>
<table class="akk">
<colgroup>
<col width="9%">
<col width="10%">
<col width="10%">
<col width="4%">
<col width="20%">
<col width="6%">
<col width="8%">
<col width="8%">
<col width="24%">
</colgroup>
<tr>
<th>Mnr</th><th>Nachname</th><th>Vorname</th><th>LV</th><th>Adresse</th><th>Offen</th><th class="c">Akk</th><th class="c">pay</th><th>Edit / Info</th>
</tr>
</table>
</div> <!-- search -->