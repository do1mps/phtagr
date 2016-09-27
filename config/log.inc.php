<?php

class LogConfig {

  /** central Log Config
      type    Log-Type:   Available: File, Database
      name    File: FileName incl. Path
      table   Database: Table-Name to log in
      FieldDT Database: Table-Row for date_time
      FieldTXT Database: Table-Row for LogLine
  */

	public $config = array(
    'type' => 'File',
    'name' => 'phtagrlog.txt',
    'table' => 'log',
    'fieldDT' => 'Timestamp',
    'fieldTXT' => 'Text'
	);

}

 ?>
