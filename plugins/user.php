<?php
/***********************************************************
**   User Access Layer,
**   arbeitet mit der Datenbank, um die Userdaten zu schreiben/lesen
*/

class User {
  private $dbconnection;
  private $log;

  function __construct() {
    $this->log = new Log();
    $this->dbconnection = new Database();
    $this->log->write('User-Module loaded');
  }

  function login($user, $pass) {
    $_SESSION['loggedin'] = false;
    if ($this->dbconnection->checkUser($user, $pass)) {
      $_SESSION['phtagr.userid'] = $user;
      $_SESSION['loggedin'] = true;
      $_SESSION['phtagr.username'] = $this->dbconnection->GetUserName($user);
      $this->log->write('USER::Login: User ' . $_SESSION['phtagr.userid'] . ' (' . $_SESSION['phtagr.username'] . ') logged in successfull');
    } else {
      $_SESSION['loggedin'] = false;
      $this->log->write('USER::Login: User ' . $user . ' NOT logged in (user or password incorrect)');
    }
    return $_SESSION['loggedin'];
  }

  function delete($user) {

  }
  function add($user) {

  }
  function logout() {
    // Löschen aller Session-Variablen.
    $_SESSION = array();

    // Session-Cookie ebenso vernichten, wenn es verwendet wurde:
    // Achtung: Damit wird die Session gelöscht, nicht nur die Session-Daten!
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }

    //Destroy Session, and create new one.
    session_destroy();
    session_start();
  }

  function getright($right = "") {
    if ($_SESSION['loggedin']) {
      if ($right == '') {
        return false;
      } else {

        //ToDo: Rechte auswerten
        return true;
      }
    } else { //not logged in:
        return false;
    }
  }

}

 ?>
