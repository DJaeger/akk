<?php
class mydb extends PDO
{
    public function __construct($file = 'akk.ini')
    {
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');

        $dns = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
        ';dbname=' . $settings['database']['db'];
        $options  = array ( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password'], $options);
    }
}
class allginfo
{
    public $veranstaltung;
    public $datum;
    public $ort;
    public $akkuser;
    public $akkrolle;
    public $rootdir;
    public $htpasswd;
    public $ebene;

    function __construct($file = 'akk.ini')
    {
        $db = new mydb();

        $file = 'akk.ini';
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');
        $this->veranstaltung = $settings['akk']['Veranstaltung'];
        $this->datum = $settings['akk']['Datum'];
        $this->ort = $settings['akk']['Ort'];
        $this->ebene = $settings['akk']['Ebene'];
        
		$this->rootdir = $settings['system']['rootdir'];
		if ($this->rootdir == "") {
			$this->rootdir = "/web/akk";
		}
		$this->htpasswd = $this->rootdir . "/data/passwd.users";
        
        $usercount=$db->query("SELECT COUNT(*) AS zahl FROM tbluser")->fetch();
        if ($usercount==NULL) die("Usercount ist kaputt");
        $this->userzahl=$usercount['zahl'];
        if ($this->userzahl==0) {
            $this->akkuser =  'admin';
            $this->akkrolle=9;
			syslog(LOG_WARNING,"AkkTool: No User maintained in DB:tbluser, granting admin access. Client: $access " . "{$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})");
        } else {
			$this->akkuser = $_SERVER["REMOTE_USER"];
	
			$userres=$db->query("SELECT rolle FROM tbluser WHERE login=" . $db->quote($this->akkuser))->fetch();
			if ($userres==NULL) {
				if ($this->akkuser == "admin") {
					$this->akkrolle = 9;
					syslog(LOG_WARNING,"AkkTool: No admin user maintained in DB:tbluser, granting admin access. Client: $access " . "{$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})");
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

?>
