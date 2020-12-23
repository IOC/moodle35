<?php
require_once('functions.php');

unset($CFG);
global $CFG;
$CFG = new stdClass();

require_once(__DIR__ . '/../config-moodle2.php');

//validate user
$request = $_POST;
if ((!isset($request['token'])) ||
    (isset($request['token']) && hash('sha256', $request['token']) != '6f049777b2c570e0054f144db6f2808ab97617c601724924037b049fc312ee70')) {
    echo 'OK';
    exit;
}
//validate host
$host = empty($request['host'])?'localhost':$request['host'];
$result = '';

if (isset($request['type'])) {
    //monitor selector
    switch ($request['type']) {
        case 'memcache':
            $result = dumpMemcache($host);
            break;
        case 'mymdb':
            $dsn = 'mysql:host='.$CFG->dbhost.';dbname:'.$CFG->dbname;
            try{
                $DB = new PDO ($dsn, $CFG->dbuser, $CFG->dbpass);
                $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connection failed: '. $e->getMessage();
                exit;
            }
            $result = moodle_session_DB($DB, $CFG->dbname, $CFG->prefix);
            break;
        case 'apache':
            $result = ss_apache_stats($host);
            break;
        case 'mysql':
            $result = mysql_php_monitor();
            break;
        case 'msf':
            //Count php session files
            $result = moodle_session_files();
            break;
        case 'multi_msf':
            //count php session files from multiple servers
            $result = moodle_session_files(TRUE);
            break;
        default:
            break;
    }
}

//Show result
echo $result;
