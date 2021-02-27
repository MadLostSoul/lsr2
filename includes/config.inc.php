<?php
/*
Copyright (c) 2021 Copyright Holder All Rights Reserved.
REM: Konfiguartionfile for LiquidSoap Controller v2
*/

// NOTE: Errorhandling
error_reporting(E_ALL);
// set_error_handler("customError", E_ALL);
// error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);

// NOTE: Debugging tool for PHP pages
// (Output also in the browser console)
require_once 'ChromePhp.php';

/*
 ChromePhp::log('This is just a log message');
 ChromePhp::warn("This is a warning message ");
 ChromePhp::error("This is an error message");
 ChromePhp::log($_SERVER);
 */

 // Enable/disable debugging
 $GLOBALS["debug"] = 0;

 // Test der aktuellen $GLOBALS
 /*
  ChromePhp::LOG($GLOBALS['rtb_timeout_default'],'RTB (init) Timeout: ');
  ChromePhp::LOG($GLOBALS['add_timeout'],'def (init) Timeout: ');
  */

 // Globale Variablen
 //
 // Begin :: Additionals für PHP Login #########################################################
 //
 $ls_script_info = array();
 $ls_script_info['ls_script_title'] = "&copy;LiquidSoap&reg; Telnet Communicator";
 $ls_script_info['ls_script_name'] = "TerrorBase &copy;LiquidSoap&reg; Test Stream!";
 $ls_script_info['ls_script_version'] = "v2.0.0.x [alpha]";

 // Login Charakterset
 $login_charset = "utf8";

 // Login Usertabelle
 $tbl_ls_user = "ls_users";
 $tbl_ls_user_level = "ls_user_levels";
 //
 // End :: Additionals für PHP Login ##
 //

 // LiquidSoap©®
 $ls_host = "127.0.0.1";
 $ls_port = "1234";

 // MySQL
 $mysql_host = "127.0.0.1";
 $mysql_db = "liquidsoap";
 $mysql_user = "liquidsoap";
 $mysql_pass = "moppelkotze";
 $GLOBALS["mysql_version"] = "";
