<?php
/***********************************************************
**   Database Access Layer,
**   arbeitet mit der Datenbank, alle DB-Aktivitäten laufen über diese Klasse!
*/
// Konfiguration laden
include('config/database.inc.php');

class Database {
  private $dbconfig;
  private $dbconnection;
  private $log;
  private $query;
  private $result;
  private $stmt = "";   //Statement-Variable (Class-Global)
  private $count = 0;   //RowCount

  function __construct() {
    //importieren der Einstellungen:
    $this->dbconfig = new DatabaseConfig();
    $this->log = new Log();

    if ($this->dbconfig->config['type'] == 'MySQL') {
      $this->dbconnection = new mysqli($this->dbconfig->config['host'], $this->dbconfig->config['user'], $this->dbconfig->config['pass'], $this->dbconfig->config['dbname']);

      if ($this->dbconnection->connect_errno) {
        //Fehler bei der Verbindung mit der Datenbank!
        //Logging nutzen, sobald aufgebaut :)
        //log => $dbconnection->connect_errno; Text: $dbconnection->connect_error;
        $this->log->write('DB-Verbindung zu ' . $this->dbconfig->config['host'] . ':' . $this->dbconfig->config['dbname'] . ' Fehlerhaft! (' . $this->dbconnection->connect_errno . ') ' . $this->dbconnection->connect_error);
      } else {
        //DB-Verbindung erfolgreich,
        $this->log->write('DB-Verbindung zu ' . $this->dbconfig->config['host'] . ':' . $this->dbconfig->config['dbname'] . ' erfolgreich!');
      }
    }
  }

  function checkUser($user, $pass) {

    if ($stmt = $mysqli->prepare("SELECT count(*) FROM `" . $this->dbconfig->config['usertable'] . "` WHERE username='?' and password = sha2('?', 512)")) {
      /* bind parameters for markers */
      $stmt->bind_param("ss", $user, $pass);
      /* execute query */
      $stmt->execute();
      /* bind result variables */
      $stmt->bind_result($count);
      /* fetch value */
      $stmt->fetch();
      /* close statement */
      $stmt->close();
    }
    if ($count == 1) {
      return true;
    } else {
      return false;
    }
    return false;
  }
  function GetUserName($user) {
    $username = "";
    $rowcount = 0;

    if ($stmt = $mysqli->prepare("SELECT CONCAT(firstname, ' ', lastname)  FROM `" . $this->dbconfig->config['usertable'] . "` WHERE username='?'")) {
      $stmt->bind_param("s", $user);
      $stmt->execute();
      if ($stmt->num_rows = 1) {
        $stmt->bind_result($username);
        $stmt->fetch();
      } else {
        $this->log->write('DB::GetUserName(' . $user . ') returned ' . $stmt->num_rows .' rows (must: 1)');
      }
      $stmt->close();
    }
    return $username;
  }



}

 ?>
