<?php
/***********************************************************
**   Database Access Layer,
**   arbeitet mit der Datenbank, alle DB-Aktivitäten laufen über diese Klasse!

** Erweiterungen: DB-Log (Angabe Tabelle, FeldDateTime, FeldText)
*/
// Konfiguration laden
include('config/log.inc.php');

class Log {
  private $logconfig;
  private $loghandle;

  function __construct() {
    //importieren der Einstellungen:
    $this->logconfig = new LogConfig();

    if ($this->logconfig->config['type'] == 'File') {
      $this->loghandle = fopen($this->logconfig->config['name'], "a");  //Append File
    } elseif ($this->logconfig->config['type'] == 'Database') {
      $this->loghandle = fopen($this->logconfig->config['name'], "a");  //Append File
      $this->write('Log to Database is not yet implemented! Sorry...');
    }
  }

  public function write($line) {
//    if ($this->logconfig->config['type'] == 'File') {
      $d = new DateTime();
      $line = $d->format('Y-m-d\TH:i:s') . ": " . $line . "\n"; // 2011-01-01T15:03:01: ....
      fwrite($this->loghandle, $line);
//    }

  }
}

 ?>
