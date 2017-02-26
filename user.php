<?php
  $id="user";
  ini_set('include_path', 'inc');
  include("db.php");
  include("define.php");
  include("head.php");
  include_once("passwdfkt.php");

  if ($info->akkrolle != 9) die("Du bist nicht berechtigt diese Seite zu öffnen!");
?>

<?php

  function pRolleText($rolle)
  {
    switch($rolle)
    {
      case 0: return("gesperrt"); break;
      case 1: return("Akkrediteur"); break;
      case 9: return("Admin"); break;
      default: return("");
    }
  }

  function pUserList($db)
  {
    global $info;

    echo "<table><tr>";
    th("User","l");
    th("Name","l");
    th("Rolle","l");
    th("","l");
    echo "</tr>\n<tr>";
    td("Neu");
    td("");
    td("");
    td("<a href='" . $_SERVER["PHP_SELF"] . "?m=10'>Hinzuf&uuml;gen</a>");
    echo "</tr>\n";
    $q1=$db->query("SELECT login,name,rolle FROM tbluser ORDER BY login");
    while($res1=$q1->fetch())
    {
      echo "<tr>";
      td($res1['login']);
      td($res1['name']);
      td($res1['rolle'] . " - " . pRolleText($res1['rolle']));
      if ($res1['login'] == $info->akkuser)
         $s="";
      else
         $s="<a href='" . $_SERVER["PHP_SELF"] . "?m=30&u=" . $res1['login'] . "'>L&ouml;schen</a>";
      td("<a href='" . $_SERVER["PHP_SELF"] . "?m=20&u=" . $res1['login'] . "'>Bearbeiten</a> " . $s);
      echo "</tr>\n";
    }

    echo "</table>\n";
  }

  function pUserAdd($db,$User,$Name,$Rolle)
  {
    echo "<h4>User hinzuf&uuml;gen</h4>\n";
    echo "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'><br>\n";
    echo "<table>";
    echo "<tr><td>Login</td>";
    echo "<td><input type='text' name='u' size=15 value='" . $User . "' autofocus></td></tr>\n";
    echo "<tr><td>Name</td>";
    echo "<td><input type='text' name='n' size=40 value='" . $Name . "'></td></tr>\n";
    echo "<tr><td>Rolle</td>";
    echo "<td><select size='1' name='r'>\n";
    for ($i=0; $i<10; $i++)
    {
      $s=pRolleText($i);
      if ($s!="")
      {
         echo "<option value='" . $i . "'";
         if ($i==$Rolle || ($Rolle=="" && $i==1)) echo " selected";
         echo ">" . $i . " - " . $s . "</option>\n";
      }
    }
    echo "</select></td></tr>";
    echo "<tr><td>Passwort</td>";
    echo "<td><input type='password' name='p2' size=20></td></tr>\n";
    echo "<tr><td>Wiederholung</td>";
    echo "<td><input type='password' name='p3' size=20></td></tr>\n";
    echo "</table>";
    echo "<input type='submit' name='s10' value='OK'>\n";
    echo "<input type='submit' name='x' value='Abbrechen'>\n";
    echo "</form>\n";
  }

  function pUserAddDo($db,$User,$Name,$Rolle,$Pass2,$Pass3)
  {
    if ($User=="")
    {
       errmsg("Login muss angegeben sein");
       return(-1);
    }
    if ($Name=="")
    {
       errmsg("Name muss angegeben sein");
       return(-1);
    }
    if (pRolleText($Rolle)=="")
    {
       errmsg("Ung&uuml;ltige Rolle");
       return(-1);
    }
    if ($Pass2=="")
    {
       errmsg("Passwort muss angegeben sein");
       return(-1);
    }
    if ($Pass2!=$Pass3)
    {
       errmsg("Passwort stimmt nicht mit der Wiederholung &uuml;berein");
       return(-1);
    }

    $res=$db->query("SELECT name FROM tbluser WHERE login=" . $db->quote($User))->fetch();
    if ($res!=NULL)
    {
       errmsg("Der User " . $User . " existiert bereits");
       return(-1);
    }

    pSetPasswd($User,$Pass2);
    $db->exec("INSERT INTO tbluser (login,name,rolle) VALUES (" .
              $db->quote($User) . "," . $db->quote($Name) . "," . $db->quote($Rolle) . ")");
    echo "<h4>User " . $User . " wurde hinzugef&uuml;gt</h4><br>\n";
    return(0);
  }

  function pUserBearbTest($db,$User,$FlagDel)
  {
    global $info;

    if ($User == "")
    {
      errmsg("User muss angegeben sein");
      return(-1);
    }
    if ($FlagDel && $User == $info->akkuser)
    {
      errmsg("Man kann sich nicht selbst l&ouml;schen");
      return(-1);
    }
    $res=$db->query("SELECT rolle FROM tbluser WHERE login=" . $db->quote($User))->fetch();
    if ($res==NULL)
    {
      errmsg("User " . $User . " existiert nicht");
      return(-1);
    }
    return(0);
  }

  function pUserDel($db,$User)
  {
    if (pUserBearbTest($db,$User,true))
    {
      pUserList($db);
      return;
    }
    echo "Soll der User " . $User . " gel&uuml;scht werden?<br>\n";
    echo "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'><br>\n";
    echo "<input type='hidden' name='u' value='" . $User . "'>\n";
    echo "<input type='submit' name='s30' value='OK'>\n";
    echo "<input type='submit' name='x' value='Abbrechen'>\n";
    echo "</form>\n";
  }

  function pUserDelDo($db,$User)
  {
    if (pUserBearbTest($db,$User,true))
    {
      pUserList($db);
      return;
    }
    pSetPasswd($User,"");
    $db->exec("DELETE FROM tbluser WHERE login=" . $db->quote($User));
    echo "<h4>User " . $User . " wurde gel&ouml;scht</h4><br>\n";
  }

  function pUserBearb($db,$User,$Name,$Rolle)
  {
    global $info;

    if (pUserBearbTest($db,$User,false))
    {
      pUserList($db);
      return;
    }

    $res=$db->query("SELECT name,rolle FROM tbluser WHERE login=" . $db->quote($User))->fetch();
    if ($res!=NULL)
    {
      if ($Name=="") $Name=$res['name'];
      if ($Rolle=="") $Rolle=$res['rolle'];
    }

    echo "<h4>User " . $User . " bearbeiten</h4>\n";
    echo "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'><br>\n";
    echo "<table>";
    echo "<tr><td>Login</td>";
    echo "<td><input type='text' name='u' size=15 value='" . $User . "' readonly></td></tr>\n";
    echo "<tr><td>Name</td>";
    echo "<td><input type='text' name='n' size=40 value='" . $Name . "'></td></tr>\n";
    echo "<tr><td>Rolle</td>";
    if ($info->akkuser == $User)
    {
      echo "<td><input type='text' name='r' size=15 value='9 - " . pRolleText(9) . "' readonly></td></tr>\n";
    }
    else
    {
      echo "<td><select size='1' name='r'>\n";
      for ($i=0; $i<10; $i++)
      {
        $s=pRolleText($i);
        if ($s!="")
        {
           echo "<option value='" . $i . "'";
           if ($i==$Rolle || ($Rolle=="" && $i==1)) echo " selected";
           echo ">" . $i . " - " . $s . "</option>\n";
        }
      }
      echo "</select></td></tr>";
    }
    echo "<tr><td>Passwort</td>";
    echo "<td><input type='password' name='p2' size=20 autofocus></td></tr>\n";
    echo "<tr><td>Wiederholung</td>";
    echo "<td><input type='password' name='p3' size=20></td></tr>\n";
    echo "</table>";
    echo "<input type='submit' name='s20' value='OK'>\n";
    echo "<input type='submit' name='x' value='Abbrechen'>\n";
    echo "</form>\n";
  }

  function pUserBearbDo($db,$User,$Name,$Rolle,$Pass2,$Pass3)
  {
    global $info;

    if (pUserBearbTest($db,$User,false))
    {
      return(-1);
    }
    if (($Pass2!="" || $Pass3!="") && $Pass2!=$Pass3)
    {
      errmsg("Passwort stimmt nicht mit der Wiederholung &uuml;berein");
      return(-1);
    }
    if ($User==$info->akkuser && $Rolle!=9)
    {
      errmsg("Man kann sich nicht selbst den Admin-Status nehmen");
      return(-1);
    }

    $res=$db->query("SELECT name,rolle FROM tbluser WHERE login=" . $db->quote($User))->fetch();
    if ($res==NULL)
    {
      $NameAlt="";
      $RolleAlt="";
    }
    else
    {
      $NameAlt=$res['name'];
      $RolleAlt=$res['rolle'];
    }

    if ($Pass2=="" && $NameAlt==$Name && $RolleAlt==$Rolle)
    {
       $StatusText="Keine &Auml;nderung";
    }
    else
    {
      $StatusText="";
      if ($Pass2!="")
      {
        pSetPasswd($User,$Pass2);
        $StatusText="Passwort gesetzt";
      }
      if ($NameAlt!=$Name || $RolleAlt!=$Rolle)
      {
        $db->exec("UPDATE tbluser SET name=" . $db->quote($Name) . ",Rolle=" . $db->quote($Rolle) .
                  " WHERE login=" . $db->quote($User));
        if ($StatusText!="") $StatusText = $StatusText . ", ";
        $StatusText=$StatusText . " Daten aktualisiert";
      }
    }
    echo "<h4>" . $StatusText . " bei User " . $User . "</h4><br>\n";
    return(0);
  }

  $Maske="";
  $User="";
  $Name="";
  $Rolle="";
  $Pass1="";
  $Pass2="";
  $Pass3="";

  $db=new mydb();

  if (!isset($_REQUEST['x']))
  {
    if (isset($_REQUEST['m']))
    {
      $Maske=preg_replace("/[^0-9]/","",$_REQUEST['m']);
    }
    else if (isset($_REQUEST['s10']))
    {
      $Maske=11;
    }
    else if (isset($_REQUEST['s20']))
    {
      $Maske=21;
    }
    else if (isset($_REQUEST['s30']))
    {
      $Maske=31;
    }
    if (isset($_REQUEST['u']))
    {
      $User=preg_replace("/[^0-9a-zA-ZäöüÄÖÜß]/","",$_REQUEST['u']);
      if ($User != $_REQUEST['u'])
      {
        errmsg("Das Login darf nur aus Ziffern und großen und kleinen Buchstaben bestehen");
        $User="";
      }
    }
    if (isset($_REQUEST['n']))
    {
      $Name=preg_replace("/[^0-9a-zA-ZäöüÄÖÜß ,.-_]/","",$_REQUEST['n']);
    }
    if (isset($_REQUEST['r']))
    {
      $Rolle=preg_replace("/[^0-9]/","",$_REQUEST['r']);
    }
    if (isset($_POST['p1']))
    {
      $Pass1=preg_replace("/[^0-9a-zA-Z]/","",$_POST['p1']);
    }
    if (isset($_POST['p2']))
    {
      $Pass2=preg_replace($pass_chr,"",$_POST['p2']);
      if ($Pass2 != $_POST['p2'])
      {
        errmsg($pass_err);
        $Pass2="";
      }
    }
    if (isset($_POST['p3']))
    {
      $Pass3=preg_replace($pass_chr,"",$_POST['p3']);
    }
  }

  switch($Maske)
  {
    case 10: pUserAdd($db,"","","");
             break;
    case 11: $res=pUserAddDo($db,$User,$Name,$Rolle,$Pass2,$Pass3);
             if ($res==0)
                pUserList($db);
             else
                pUserAdd($db,$User,$Name,$Rolle);
             break;
    case 20: pUserBearb($db,$User,"","");
             break;
    case 21: $res=pUserBearbDo($db,$User,$Name,$Rolle,$Pass2,$Pass3);
             if ($res==0)
                pUserList($db);
             else
                pUserBearb($db,$User,$Name,$Rolle);
             break;
    case 30: pUserDel($db,$User);
             break;
    case 31: pUserDelDo($db,$User);
             pUserList($db);
             break;
    default: pUserList($db);
  }
?>

<?php
  include("footer.php");
?>