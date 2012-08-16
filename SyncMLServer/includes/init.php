<?
/* PEAR_LOG_* constants */
define('PEAR_LOG_EMERG',    0);     /* System is unusable */
define('PEAR_LOG_ALERT',    1);     /* Immediate action required */
define('PEAR_LOG_CRIT',     2);     /* Critical conditions */
define('PEAR_LOG_ERR',      3);     /* Error conditions */
define('PEAR_LOG_WARNING',  4);     /* Warning conditions */
define('PEAR_LOG_NOTICE',   5);     /* Normal but significant */
define('PEAR_LOG_INFO',     6);     /* Informational */
define('PEAR_LOG_DEBUG',    7);     /* Debug-level messages */

define('MY_PATH', dirname(__FILE__).'/');

function err_handler($err_no, $err_str, $err_file, $err_line, $err_context){
		if( !error_reporting() )  return;
		print ("\nERROR:\n");
		print ($err_no . '  ' . $err_str . '  ' .  $err_file . '  ' .  $err_line /*. '  ' .  print_r($err_context,1)*/);
		return true;
}

/*
function __autoload ($class) { # void
#   $ext    = strpos( $class,'_i' ) ? '.interface.php' : '.php';
#   $file=str_replace( array('__','_i','_'), array("-","/","/"), $class);
	$ext    = '.php';
    $file=str_replace( array('__','_'), array("-","/"), $class);
   if( (@include_once($file.$ext))==false) {
      $p=strrpos($file,"/");
      $name= $p ? substr($file,$p) : $file;
      require_once "$file/$name$ext";
   }
}
*/
set_error_handler('err_handler', E_ALL);


set_include_path(MY_PATH . PATH_SEPARATOR . get_include_path());

require_once MY_PATH.'Horde/iCalendar.php';
require_once MY_PATH.'Horde/iCalendar/vcard.php';

//require_once 'Log.php';
/* should be loaded by __autoload() from $ROOT/lib/
require_once MY_PATH."O/VBook.php";
require_once MY_PATH."O/VCard.php";
require_once MY_PATH."O/VCardWriter.php";
require_once MY_PATH."O/Contacts/Base.php";
require_once MY_PATH."Contacts/Phone.php";
*/


//$backend = 'Sql';
$backend = 'Hb';
/*
$backend_parms = array('dsn' => 'mysql://odessa:mike-odessa@localhost/hb_syncml', // adjust as required
                       'debug_dir' => '/var/www/sync/tmp', // debug output to this dir, must be writeable be web server
                       'debug_files' => true, // log all (wb)xml packets received or sent to debug_dir:
                       'log_level' => PEAR_LOG_DEBUG); // log everything
*/
$backend_parms = array('dsn' => 'mysql://root:root@localhost/hb_sync', // adjust as required
                       'debug_dir' => "$ROOT/var/log/syncml/", // debug output to this dir, must be writeable be web server
                       'debug_files' => true, // log all (wb)xml packets received or sent to debug_dir:
                       'log_level' => PEAR_LOG_DEBUG); // log everything


require_once MY_PATH.'SyncML.php';
require_once (MY_PATH.'SyncML/Backend.php');



$GLOBALS['backend'] = SyncML_Backend::factory($backend, $backend_parms);



?>