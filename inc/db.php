<?php
class mydb extends PDO {
    public function __construct($file = 'akk.ini') {
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');

        $dns = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
        ';dbname=' . $settings['database']['db'];
        $options  = array ( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password'], $options);
    }
}
class allginfo {
    public $veranstaltung;
    public $startdate;
    public $enddate;
    public $ort;
    public $ebene;
    public $PT;
    public $AV;
    public $nations;
    public $akkuser;
    public $akkrolle;
    public $rootdir;
    public $htpasswd;

    /*
     * $modus: 0 = normal mit login/user check gegen DB.tbluser
     *         1 = Aufruf von/für Statistikseite, ohne login
     */
    function __construct($file = 'akk.ini', $modus = 0) {
        $db = new mydb();

        $file = 'akk.ini';
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');
        $this->veranstaltung = $settings['akk']['Veranstaltung'];
        $this->startdate = $settings['akk']['startdate'];
        $this->enddate = $settings['akk']['enddate'];
        $this->ort = $settings['akk']['Ort'];
        $this->ebene = $settings['akk']['Ebene'];
        $this->PT = intval($settings['akk']['PT']);
        $this->AV = intval($settings['akk']['AV']);
        if ( $this->ebene == 'EP' ) {
            $this->nations = array ('BE','GR','MT','SK','BG','IE','NL','SI','DK','IT','AT','ES','DE','HR','PL','CZ','EE','LV','PT','HU','FI','LT','RO','GB','FR','LU','SE','CY','EU','D','A','');
        } else {
            $this->nations = array ('DE','D','de','d','');
        }
        $this->rootdir = (!empty($settings['system']['rootdir']))?$settings['system']['rootdir']:"/web/akk";
        $this->htpasswd = (!empty($settings['system']['htpasswd']))?$settings['system']['htpasswd']:$settings['system']['rootdir']."/data/passwd.users";

        if ($modus == 0) {
            $usercountquery=$db->query("SELECT COUNT(*) AS zahl FROM tbluser");
            if ($usercountquery==false) throw new Exception("User-Tabelle fehlt");
            $usercount=$usercountquery->fetch();
            if ($usercount==NULL) throw new Exception("Usercount ist kaputt");
            $this->userzahl=$usercount['zahl'];
            if ($this->userzahl==0) {
                $this->akkuser =  'admin';
                $this->akkrolle=9;
                syslog(LOG_WARNING,"AkkTool: No User maintained in DB:tbluser, granting admin access. Client: " . "{$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})");
            } else {
                $this->akkuser = $_SERVER["REMOTE_USER"];

                $userres=$db->query("SELECT rolle FROM tbluser WHERE login=" . $db->quote($this->akkuser))->fetch();
                if ($userres==NULL) {
                    if ($this->akkuser == "admin") {
                        $this->akkrolle = 9;
                        syslog(LOG_WARNING,"AkkTool: No admin user maintained in DB:tbluser, granting admin access. Client: " . "{$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})");
                    } else {
                        die("User existiert nicht in der Datenbank");
                    }
                } else {
                    $this->akkrolle=$userres['rolle'];
                    if ($this->akkrolle==0) die("User ist gesperrt");
                    syslog(LOG_WARNING,"AkkTool: Access to user " . $this->akkuser . " granted. Client: "
                    . "{$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})");
                }
            }
        }
    }
}
