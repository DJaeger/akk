<?php

$pass_chr="/[^0-9a-zA-Z\-_\.\+#]/";
$pass_err="Das Passwort darf nur aus Ziffern und grossen und kleinen Buchstaben sowie -_.#+bestehen";

function crypt_apr1_md5 ( $plainpasswd, $saltu="" ) {
    $tmp="";
    if ($saltu == "")
        $salt = substr ( str_shuffle ( "abcdefghijklmnopqrstuvwxyz0123456789" ), 0 , 8 );
    else
        $salt = $saltu;

    $len = strlen ( $plainpasswd );
    $text = $plainpasswd . '$apr1$' . $salt ;
    $bin = pack ( "H32" , md5 ( $plainpasswd . $salt . $plainpasswd ));
    for( $i = $len ; $i > 0 ; $i -= 16 ) {
        $text .= substr ( $bin , 0 , min ( 16 , $i ));
    }
    for( $i = $len ; $i > 0 ; $i >>= 1 ) {
        $text .= ( $i & 1 ) ? chr ( 0 ) : $plainpasswd { 0 };
    }
    $bin = pack ( "H32" , md5 ( $text ));
    for( $i = 0 ; $i < 1000 ; $i ++) {
        $new = ( $i & 1 ) ? $plainpasswd : $bin ;
        if ( $i % 3 ) $new .= $salt ;
        if ( $i % 7 ) $new .= $plainpasswd ;
        $new .= ( $i & 1 ) ? $bin : $plainpasswd ;
        $bin = pack ( "H32" , md5 ( $new ));
    }
    for ( $i = 0 ; $i < 5 ; $i ++) {
        $k = $i + 6 ;
        $j = $i + 12 ;
        if ( $j == 16 ) $j = 5 ;
        $tmp = $bin [ $i ]. $bin [ $k ]. $bin [ $j ]. $tmp ;
    }
    $tmp = chr ( 0 ). chr ( 0 ). $bin [ 11 ]. $tmp ;
    $tmp = strtr ( strrev ( substr ( base64_encode ( $tmp ), 2 )),
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/" ,
    "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz" );
    return "$" . "apr1" . "$" . $salt . "$" . $tmp ;
}

function pSetPasswd($User,$Passwort) {
    global $info;

    if ($Passwort=="")
        $PwdCrypt="";
    else
        $PwdCrypt=crypt_apr1_md5($Passwort);
    $UserD=$User . ":";
    $UserLen=strlen($UserD);

    $fZwi=tmpfile();
    $fPwd=fopen($info->htpasswd,"r");
    if ($fZwi==NULL || $fPwd==NULL) {
        errmsg("Fehler passwdfkt\#001 - Kann Passwort Datei '" . $info->htpasswd . "' nicht öffnen");
    } else {
        while(!feof($fPwd)) {
            $zeile=fgets($fPwd,1024);
            if ($zeile==NULL) break;
            if (substr($zeile,0,$UserLen) != $UserD) 
                fputs($fZwi,$zeile);
        }
    }
    @fclose($fPwd);
    $fPwd=fopen($info->htpasswd,"w");
    fseek($fZwi,0);
    if ($fZwi==NULL || $fPwd==NULL) {
        errmsg("Fehler passwdfkt\#002 - Kann Passwort Datei '" . $info->htpasswd . "' nicht beschreiben");
        die();
    }
    while(!feof($fZwi)) {
        $zeile=fgets($fZwi,1024);
        if ($zeile==NULL) break;
        fputs($fPwd,$zeile);
    }
    if ($Passwort!="") {
        fputs($fPwd,$User . ":" . $PwdCrypt . "\n");
    }

    fclose($fPwd);
    fclose($fZwi);
}

function pCheckPasswd($User,$Passwort) {
    global $info;

    $UserD=$User . ":";
    $UserLen=strlen($UserD);
    $fPwd=fopen($info->htpasswd,"r");
    if ($fPwd==NULL) {
        errmsg("Fehler passwdfkt\#003 - Kann Passwort Datei '" . $info->htpasswd . "' nicht öffnen");
        die();
    }
    while(!feof($fPwd)) {
        $zeile=fgets($fPwd,1024);
        if ($zeile==NULL) break;
        if (substr($zeile,0,$UserLen) == $UserD) {
            fclose($fPwd);
            $PwdCrypt=trim(substr($zeile,$UserLen));
            if (strlen($PwdCrypt) > 14 && substr($PwdCrypt,0,6) == "\$apr1\$") {
                $PwdVgl=crypt_apr1_md5($Passwort,substr($PwdCrypt,6,8));
                if ($PwdVgl==$PwdCrypt) {
                    return(0);
                } else {
                    return(-1);
                }
            }
        }
    }
    fclose($fPwd);
    return(1);
}

function recreateHtaccess() {
    global $info;
    $filePath = $info->rootdir . "/web/.htaccess";
    $fileHandle = fopen($filePath,"w");
    $fileContent  = "AuthName \"Akk-Tool\"\n";
    $fileContent .= "AuthType Basic\n";
    $fileContent .= "AuthUserFile " . $info->htpasswd . "\n";
    $fileContent .= "require valid-user\n";
    $fileContent .= "ErrorDocument 401 \"Zum erneuten Einloggen bitte Link neu aufrufen\"\n";
    fwrite($fileHandle, $fileContent);
    fclose($filePath);
    chmod($filePath, 0644);
}
