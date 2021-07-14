<?php
unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = '10.6.0.5';
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '123456';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->wwwroot   = 'https://campus.ticxcat';
$CFG->dataroot  = '//moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

$CFG->session_handler_class = '\core\session\file';
$CFG->session_file_save_path = $CFG->dataroot.'/sessions';

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!

require_once(__DIR__ . '/lib/setup.php');
