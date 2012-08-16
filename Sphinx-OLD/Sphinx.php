<?php
/* 
 * Wrapper/Autoloater for Sphinx library main class
 *
 */
global $ROOT;
#set_include_path(get_include_path().PATH_SEPARATOR."$ROOT/lib.external/Sphinx");
require_once "$ROOT/lib.external/Sphinx/Sphinx.php";
?>