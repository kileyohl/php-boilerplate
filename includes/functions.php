<?php
/**
 * Function Include Page
 *
 * @author Chris Cagle <cc@3pcmedia.com>
 */

# set default timezone
date_default_timezone_set('America/New_York');

include('helper.php');
include('config.php');
ini_set('log_errors', 1);
ini_set('error_log', 'errorlog.txt');

if (DEBUG) {
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
} else {
	error_reporting(0);
	@ini_set('display_errors', 0);
}

function connect_database() {
	mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
  mysql_select_db(DB_DATABASE) or die(mysql_error());
}


## CUSTOM FUNCTIONS BELOW ##

