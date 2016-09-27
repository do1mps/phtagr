<?php

class DatabaseConfig {

  /** central Database Config
      type    DB-Type, Example: Mysql
      host    DB-Server, Example: localhost
      user    DB-User, Example: phtagr_1
      pass    DB-Password
      dbname  DB-Name, Example: phtagr
  */

	public $config = array(
    'type' => 'MySQL',
    'host' => 'localhost',
    'user' => 'phtagr_1',
    'pass' => 'phtagr_Password1!',
    'dbname' => 'phtagr',
    'usertable' => 'users',
    'imagetable' => 'images'
	);

}

 ?>
