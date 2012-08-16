<?php
/**     $Id: Sphinx.php 29377 2011-06-21 16:36:53Z michael $
 *
 *	SYNTAX:
 *
 *		$api = Sphinx::api();
 *		$api->setFilterRange('birth_year',1973,1977);
 *		$res = $api->query("L_$lname_id F_$fname_id");
 *
 *		if( !$res )
 *			echo $api->getLastError();
 *
 *		var_dump($res['words']);
 *		foreach($result['matches'] as $docId => $info)
 *			echo "user_id = $docId\n";
 *
 *  DESCRIPTION:
 *
 *      Sphinx api access
 *
 **/

class Sphinx {
	static public function api ($server = 'localhost', $port=3314) {
		require_once 'sphinxapi.php';
		$api = new SphinxClient ();
		$api->SetServer($server,$port);
		$api->setMatchMode(SPH_MATCH_ALL);
		return $api;
    }
}
?>
