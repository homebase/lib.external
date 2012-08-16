<?php
/**
 * Generic SQL based SyncML Backend.
 *
 * This can be used as a starting point for a custom backend implementation.
 *
 * $Horde: framework/SyncML/SyncML/Backend/Sql.php,v 1.6.2.7 2009/04/05 20:24:43 jan Exp $
 *
 * Copyright 2006-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Karsten Fourmont <karsten@horde.org>
 * @package SyncML
 */



class SyncML_Backend_Hb extends SyncML_Backend {

    /**
     * A PEAR MDB2 instance.
     *
     * @var MDB2
     */
    var $_db;

    /**
     * The current HB profile id.
     *
     * @var string
     */
    var $_profile_id;
    
    /**
	 * The list of pids updated on server side.
	 * 
	 * @var array
	 */
    var $_pids_to_update = array();
    
    /**
     * The current HB device id.
     * Actually map _syncDeviceID & _profile_id into internal device identificator.
     *
     * @var integer
     */
    var $_hbDeviceID;

    /**
     * The current HB session id.
     * Actually map _syncDeviceID & _profile_id into internal device identificator.
     * Replacement for $pid, $device_id & $database altogether
     *
     * @var integer
     */
    var $_hbSession;
    
    /**
     * Constructor.
     *
     * @param array $params  A hash with parameters. In addition to those
     *                       supported by the SyncML_Backend class one more
     *                       parameter is required for the database connection:
     *                       'dsn' => connection DSN.
     */
    function SyncML_Backend_Hb($params)
    {
        parent::SyncML_Backend($params);
    }

    
    
    /**
     * Starts a PHP session.
     *
     * @param string $syncDeviceID  The device ID.
     * @param string $session_id    The session ID to use.
     * @param integer $backendMode  The backend mode, one of the
     *                              SYNCML_BACKENDMODE_* constants.
     */
    function sessionStart($syncDeviceID, $sessionId,
                          $backendMode = SYNCML_BACKENDMODE_SERVER)
    {
            $this->logMessage('Setup session for device ' . $syncDeviceID , __LINE__, PEAR_LOG_DEBUG);
    	
        $this->_syncDeviceID = $syncDeviceID;
        $this->_backendMode = $backendMode;
        
        // Only the server needs to start a session:
        if ($this->_backendMode == SYNCML_BACKENDMODE_SERVER) {
            $sid = md5($syncDeviceID . $sessionId);
            session_id($sid);
            @session_start();
        }
        
		if(!empty($_SESSION['SyncML.state']->_hbDeviceId))
			$this->_hbDeviceID = &$_SESSION['SyncML.state']->_hbDeviceId;
		if(!empty($_SESSION['SyncML.state']->_profile_id))
			$this->_profile_id = &$_SESSION['SyncML.state']->_profile_id;
		if(!empty($_SESSION['SyncML.state']->_hbSession))
			$this->_hbSession = &$_SESSION['SyncML.state']->_hbSession;
		if(!empty($_SESSION['SyncML.state']->_pids_to_update))
			$this->_pids_to_update = &$_SESSION['SyncML.state']->_pids_to_update;
			
			
			
#		$this->logMessage('$_SESSION is ' . print_r($_SESSION, 1) ,  __FILE__, __LINE__, PEAR_LOG_DEBUG);
    }
    
    /**
     * Returns whether a database URI is valid to be synced with this backend.
     *
     * @param string $databaseURI  URI of a database. Like calendar, tasks,
     *                             contacts or notes. May include optional
     *                             parameters:
     *                             tasks?options=ignorecompleted.
     *
     * @return boolean  True if a valid URI.
     */
    function isValidDatabaseURI($databaseURI)
    {
        $database = $this->_normalize($databaseURI);

            $this->logMessage('Requested database is: ' . $database,
                              __FILE__, __LINE__, PEAR_LOG_DEBUG);
                              
        switch($database) {
        case 'contacts';
        	return true;

        default:
            $this->logMessage('Invalid database ' . $database
                              . '. Try tasks, calendar, notes or contacts.',
                              __FILE__, __LINE__, PEAR_LOG_ERR);
            return false;
        }
        
    }

    /**
     * Returns entries that have been modified in the server database.
     *
     * @param string $databaseURI  URI of Database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param integer $from_ts     Start timestamp.
     * @param integer $to_ts       Exclusive end timestamp. Not yet
     *                             implemented.
     * @param array &$adds         Output array: hash of adds suid => 0
     * @param array &$mods         Output array: hash of modifications
     *                             suid => cuid
     * @param array &$dels         Output array: hash of deletions suid => cuid
     *
     * @return mixed  True on success or a PEAR_Error object.
     */
    function getServerChanges($databaseURI, $from_ts, $to_ts, &$adds, &$mods, &$dels)
    {
        $database = $this->_normalize($databaseURI);
        $adds = $mods = $dels = array();
		$this->logMessage('Getting server changes '.$databaseURI . ' ' . $from_ts .' '. $to_ts, __FILE__, __LINE__, PEAR_LOG_DEBUG);
        
		try {
        	$changes = Ab_SyncML_Api::get_changes($this->_hbSession, $this->_pids_to_update);
        	//!!!!! We do not delete anything from client device!
        	//Lets clear to_delete list t be sure
        	$changes['to_delete'] = array();
		} catch (Exception $e){
        	$changes = array('to_add' => array(), 'to_update' =>array(), 'to_delete' => array());
        }
        
        //unfortunately Parf decided to send all data within this request
		//I have to create temporary data provider in $_SESSION, because there might be multiple transaction with device due to packet size limitation.
		
        foreach ($changes['to_add'] as $suid => $to_add){
        	$adds[$suid] = 0;
        	$_SESSION['data_provider'][$databaseURI][$suid] = self::_internal2xvcard($to_add);
        }
        
        foreach ($changes['to_update'] as $suid=>$to_update){
        	$mods[$suid] = $to_update['cuid'];
        	$_SESSION['data_provider'][$databaseURI][$suid] = self::_internal2xvcard($to_update);
        }
        
        foreach ($changes['to_delete'] as $suid => $to_delete){
        	$dels[$suid] = $to_delete['cuid'];
        	$_SESSION['data_provider'][$databaseURI][$suid] = ''; //self::_internal2xvcard($to_delete['data']);
        }
        
        
    }

    /**
     * Retrieves an entry from the backend.
     *
     * @param string $databaseURI  URI of Database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $suid         Server unique id of the entry: for horde
     *                             this is the guid.
     * @param string $contentType  Content-Type: the MIME type in which the
     *                             function should return the data.
     *
     * @return mixed  A string with the data entry or a PEAR_Error object.
     */
    function retrieveEntry($databaseURI, $suid, $contentType)
    {
    	$prepared_data = '';
        $database = $this->_normalize($databaseURI);
		$GLOBALS['backend']->logMessage("Retrieving data from SESSION data provider. contentType=$contentType, databaseURI = $databaseURI, suid=$suid", __FILE__, __LINE__,  PEAR_LOG_DEBUG);
        
        if (isset($_SESSION['data_provider'][$databaseURI][$suid])){
        	return $_SESSION['data_provider'][$databaseURI][$suid];
        }
        
        return false;
    }

    /**
     * Adds an entry into the server database.
     *
     * @param string $databaseURI  URI of Database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $content      The actual data.
     * @param string $contentType  MIME type of the content.
     * @param string $cuid         Client ID of this entry.
     *
     * @return array  PEAR_Error or suid (Horde guid) of new entry
     */
    function addEntry($databaseURI, $content, $contentType, $cuid = null)
    {
    	
    	$GLOBALS['backend']->logMessage('Adding entry cuid=:'.$cuid, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
        $database = $this->_normalize($databaseURI);

    	switch ($contentType) {
    		case 'text/x-vcard':
    		case 'text/directory':
    			$prepared_data = self::_xvcard2internal($content);
    			break;
    		default:
		    	$prepared_data = $content;
    			break;
    	}
    	
    	try {
    		$suid = Ab_SyncML_Api::add_contact($this->_hbSession, $cuid, $prepared_data[0]);
    	} catch (Exception $e){
    		return false;
    	}

    	return $suid;
        
    }

    /**
     * Replaces an entry in the server database.
     *
     * @param string $databaseURI  URI of Database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $content      The actual data.
     * @param string $contentType  MIME type of the content.
     * @param string $cuid         Client ID of this entry.
     *
     * @return string  PEAR_Error or server ID (Horde GUID) of modified entry.
     */
    function replaceEntry($databaseURI, $content, $contentType, $cuid)
    {
    	$GLOBALS['backend']->logMessage('Replacing entry cuid=:'.$cuid, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	$database = $this->_normalize($databaseURI);
    	
    	
    	switch ($contentType) {
    		case 'text/x-vcard':
    		case 'text/directory':
    			$prepared_data = self::_xvcard2internal($content);
#Convert id back to test both convertion directions
#    			self::_internal2xvcard($prepared_data);
    			break;
    		default:
    			$GLOBALS['backend']->logMessage('Ab_SyncML_Api::update_contact. Unknown content type '.$contentType, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
		    	$prepared_data = $content;
    			break;
    	}
    	
    	try {
    		$GLOBALS['backend']->logMessage('Ab_SyncML_Api::update_contact '.print_r($prepared_data, 1), __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    		$suid = Ab_SyncML_Api::update_contact($this->_hbSession, $cuid, $prepared_data[0]);
    		$GLOBALS['backend']->logMessage('Ab_SyncML_Api::update_contact => suid = '.$suid, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    		
    	} catch (Exception $e){
    		$GLOBALS['backend']->logMessage('Replacing entry cuid=:'.$cuid." Exception ".$e->getMessage(), __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    		return false;
    	}
    	$GLOBALS['backend']->logMessage('Replacing entry cuid=:'.$cuid." => suid = $suid", __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	
    	return $suid;

    }

    /**
     * Deletes an entry from the server database.
     *
     * @param string $databaseURI  URI of Database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $cuid         Client ID of the entry.
     *
     * @return boolean  True on success or false on failed (item not found).
     */
    function deleteEntry($databaseURI, $cuid)
    {
    	$GLOBALS['backend']->logMessage('Deleting entry cuid=:'.$cuid, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	$database = $this->_normalize($databaseURI);

    	try {
    		return Ab_SyncML_Api::delete_contact($this->_hbSession, $cuid);
    	} catch (Exception $e){
    		return false;
    	}
    		
    	
    }

    /**
     * Authenticates the user at the backend.
     *
     * @param string $username    A user name.
     * @param string $password    A password.
     *
     * @return boolean|string  The user name if authentication succeeded, false
     *                         otherwise.
     */
    function _checkAuthentication($username, $password)
    {
			$GLOBALS['backend']->logMessage('Client login: ' . $username. ' '.$password, __FILE__, __LINE__,PEAR_LOG_DEBUG);
			
            // Empty passwords result in errors for some authentication
            // backends, don't call the backend in this case.
            
            if ($password === '') {
                return false;
            }
			try {
				//we do not know any davice info right now. Device will send it in the next packet.
            	$credentials = Ab_SyncML_Api::start_session($username, $password, $this->_syncDeviceID, ''); //Do not pass device-tag name - lets autoassign
	    	} catch (Exception $e){
	    	    Log::debug("Error: ".var_export($e->getMessage(),1),'syncml_api');
	    		return false;
	    	}
            	

            if ($credentials['pid']) {
				$_SESSION['SyncML.state']->_hbDeviceID = $credentials['device_id'];
				$_SESSION['SyncML.state']->_profile_id = $credentials['pid'];
				$_SESSION['SyncML.state']->_hbSession = $credentials['session_id'];
				$this->_hbDeviceID = $credentials['device_id'];
				$this->_profile_id = $credentials['pid'];
				$this->_hbSession = $credentials['session_id'];
				
				try {
					//Let's get list of pids that were changed since last session. Real data will be retreived later, right befor send to device.
					$this->_pids_to_update = Ab_SyncML_Api::get_changed_pids($credentials['session_id']);
					//Walkaroud to avoid record with pid = 0
					foreach($this->_pids_to_update as $key => $pid){
						if (empty($pid)) unset($this->_pids_to_update[$key]);
					}
		    	} catch (Exception $e){
		    		//Excepption is not critical. We do not send any updates to device.
					$GLOBALS['backend']->logMessage('Exception: ' . $e->getMessage(), __FILE__, __LINE__,PEAR_LOG_DEBUG);
		    		$this->_pids_to_update = array();
		    	}
				$_SESSION['SyncML.state']->_pids_to_update = $this->_pids_to_update;
				
				return true;
            } else {
            	return false;
            }
    }

    
    /**
     * Sets a user as being authenticated at the backend.
     *
     * @abstract
     *
     * @param string $username    A user name.
     * @param string $credData    Authentication data provided by <Cred><Data>
     *                            in the <SyncHdr>.
     *
     * @return string  The user name.
     */
    function setAuthenticated($username, $credData)
    {
        return $username;
    }

    /**
     * Stores Sync anchors after a successful synchronization to allow two-way
     * synchronization next time.
     *
     * The backend has to store the parameters in its persistence engine
     * where user, syncDeviceID and database are the keys while client and
     * server anchor ar the payload. See readSyncAnchors() for retrieval.
     *
     * @param string $databaseURI       URI of database to sync. Like calendar,
     *                                  tasks, contacts or notes. May include
     *                                  optional parameters:
     *                                  tasks?options=ignorecompleted.
     * @param string $clientAnchorNext  The client anchor as sent by the
     *                                  client.
     * @param string $serverAnchorNext  The anchor as used internally by the
     *                                  server.
     */
    function writeSyncAnchors($databaseURI, $clientAnchorNext,
                              $serverAnchorNext)
    {
    	$GLOBALS['backend']->logMessage('Setting last anchors: '.$clientAnchorNext.' <=> '.$serverAnchorNext, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	
    	$database = $this->_normalize($databaseURI);
    	
    	
    	$map = array();
$GLOBALS['backend']->logMessage('$_SESSION[map]='.print_r(@$_SESSION['map'], 1), __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	if (!empty($_SESSION['map']))
    	foreach ($_SESSION['map'] as $suid => $entry){
//    		$map[$suid] = array('cuid' => $entry['cuid'], 'ts' => $entry['ts']);
    		$map[$entry['cuid']] = $suid;
    	}
    	
    	if (count($map)) {
    		//we have new map entiries to store in this sesssion
    		$GLOBALS['backend']->logMessage('Storing new map entries: '.print_r($map, 1), __FILE__, __LINE__,  PEAR_LOG_DEBUG);
			try {    		
    			Ab_SyncML_Api::create_map($this->_hbSession, $map);
	    	} catch (Exception $e){
	    		return false;
	    	}
    			
    	}

    	try {
    		Ab_SyncML_Api::update_session($this->_hbSession, array('remote_anchor'=>$clientAnchorNext, 'server_anchor'=>$serverAnchorNext, 'status'=>'success'));
    	} catch (Exception $e){
    		return false;
    	}
    		
    	try {
    		Ab_SyncML_Api::close_session($this->_hbSession);
    	} catch (Exception $e){
    		return false;
    	}
    		
    	
    	return true;
    	
    }

    /**
     * Reads the previously written sync anchors from the database.
     *
     * @param string $databaseURI  URI of database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     *
     * @return mixed  Two-element array with client anchor and server anchor as
     *                stored in previous writeSyncAnchor() calls. False if no
     *                data found.
     */
    function readSyncAnchors($databaseURI)
    {
    	$GLOBALS['backend']->logMessage('Getting last anchors', __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	
        $database = $this->_normalize($databaseURI);
        
        try {
        	$anchors = Ab_SyncML_Api::last_anchors($this->_hbDeviceID);
    	} catch (Exception $e){
    		$anchors['remote_anchor'] = 0;
    		$anchors['server_anchor'] = 0;
    	}
        	
    	$GLOBALS['backend']->logMessage('HB RETURN ANCHORS:'.print_r($anchors, 1), __FILE__, __LINE__,  PEAR_LOG_DEBUG);
        return array($anchors['remote_anchor'], $anchors['server_anchor']);
    }

    /**
     * Creates a map entry to map between server and client IDs.
     *
     * If an entry already exists, it is overwritten.
     *
     * @param string $databaseURI  URI of database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $cuid         Client ID of the entry.
     * @param string $suid         Server ID of the entry.
     * @param integer $timestamp   Optional timestamp. This can be used to
     *                             'tag' changes made in the backend during the
     *                             sync process. This allows to identify these,
     *                             and ensure that these changes are not
     *                             replicated back to the client (and thus
     *                             duplicated). See key concept "Changes and
     *                             timestamps".
     */
    function createUidMap($databaseURI, $cuid, $suid, $timestamp = 0)
    {
    	$GLOBALS['backend']->logMessage('Creating map:'.$cuid.'<=>'.$suid, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
        $database = $this->_normalize($databaseURI);

        //store all new map entries in SESSION to be applied right before session is about to close.
        $_SESSION['map'][$suid] = array('cuid' => $cuid, 'database' => $databaseURI, 'ts' => $timestamp);
        
        return true;
    }

    /**
     * Retrieves the Server ID for a given Client ID from the map.
     *
     * @param string $databaseURI  URI of database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $cuid         The client ID.
     *
     * @return mixed  The server ID string or false if no entry is found.
     */
    function _getSuid($databaseURI, $cuid)
    {
    	$GLOBALS['backend']->logMessage('Getting suid for '.$cuid, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	
    	
        $database = $this->_normalize($databaseURI);
        return false;
    }

    /**
     * Retrieves the Client ID for a given Server ID from the map.
     *
     * @param string $databaseURI  URI of database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $suid         The server ID.
     *
     * @return mixed  The client ID string or false if no entry is found.
     */
    function _getCuid($databaseURI, $suid)
    {
    	$GLOBALS['backend']->logMessage('Getting cuid for:'.$suid, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	$database = $this->_normalize($databaseURI);
        return false;
    }

    /**
     * Returns a timestamp stored in the map for a given Server ID.
     *
     * The timestamp is the timestamp of the last change to this server ID
     * that was done inside a sync session (as a result of a change received
     * by the server). It's important to distinguish changes in the backend a)
     * made by the user during normal operation and b) changes made by SyncML
     * to reflect client updates.  When the server is sending its changes it
     * is only allowed to send type a). However the history feature in the
     * backend my not know if a change is of type a) or type b). So the
     * timestamp is used to differentiate between the two.
     *
     * @param string $databaseURI  URI of database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $suid         The server ID.
     *
     * @return mixed  The previously stored timestamp or false if no entry is
     *                found.
     */
    function _getChangeTS($databaseURI, $suid)
    {
    	$GLOBALS['backend']->logMessage('Getting map timestamp for suid=:'.$suid, __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	
        $database = $this->_normalize($databaseURI);
        return false;
    }

    /**
     * Erases all mapping entries for one combination of user, device ID.
     *
     * This is used during SlowSync so that we really sync everything properly
     * and no old mapping entries remain.
     *
     * @param string $databaseURI  URI of database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     */
    function eraseMap($databaseURI)
    {
    	$GLOBALS['backend']->logMessage('Erasing map', __FILE__, __LINE__,  PEAR_LOG_DEBUG);
    	
        $database = $this->_normalize($databaseURI);
        return true;
    }

    /**
     * Cleanup function called after all message processing is finished.
     *
     * Allows for things like closing databases or flushing logs.  When
     * running in test mode, tearDown() must be called rather than close.
     */
    function close()
    {
        parent::close();
//        $this->_db->disconnect();
    }

    /**
     * Generates a unique ID used as suid
     *
     * @return string  A unique ID.
     */
    function _generateID()
    {
        return date('YmdHis') . '.'
            . substr(str_pad(base_convert(microtime(), 10, 36),
                             16,
                             uniqid(mt_rand()),
                             STR_PAD_LEFT),
                     -16)
            . '@'
            . (!empty($_SERVER['SERVER_NAME'])
               ? $_SERVER['SERVER_NAME']
               : 'localhost');
    }

    /**
     * Checks if the parameter is a PEAR_Error object and if so logs the
     * error.
     *
     * @param mixed $o  An object or value to check.
     *
     * @return mixed  The error object if an error has been passed or false if
     *                no error has been passed.
     */
    function _checkForError($o)
    {
        if (is_a($o, 'PEAR_Error')) {
            $this->logMessage($o);
            return $o;
        }
        return false;
    }

    /**
     * Returns a list of item IDs that have been deleted since the last sync
     * run and stores a complete list of IDs for next sync run.
     *
     * Some backend datastores don't keep information about deleted entries.
     * So we have to create a workaround that finds out what entries have been
     * deleted since the last sync run. This method provides this
     * functionality: it is called with a list of all IDs currently in the
     * database. It then compares this list with its own previously stored
     * list of IDs to identify those missing (and thus deleted). The passed
     * list is then stored for the next invocation.
     *
     * @param string $databaseURI  URI of database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param array $currentSuids  Array of all SUIDs (primary keys) currently
     *                             in the server datastore.
     *
     * @return array  Array of all entries that have been deleted since the
     *                last call.
     */
    function _trackDeletes($databaseURI, $currentSuids)
    {
        $database = $this->_normalize($databaseURI);
        if (!is_array($currentSuids)) {
            $currentSuids = array();
        }

        $this->logMessage('_trackDeletes() with ' . count($currentSuids)
                          . ' current ids',
                          __FILE__, __LINE__, PEAR_LOG_DEBUG);

        $r = $this->_db->queryCol(
            'SELECT syncml_suid FROM syncml_suidlist '
            . ' WHERE syncml_syncpartner = '
            . $this->_db->quote($this->_syncDeviceID, 'text')
            . ' AND syncml_db = '
            . $this->_db->quote($database, 'text')
            . ' AND syncml_uid = '
            . $this->_db->quote($this->_user, 'text'));
        if ($this->_checkForError($r)) {
            return $r;
        }

        $this->logMessage('_trackDeletes() found ' . count($r)
                          . ' items in prevlist',
                          __FILE__, __LINE__, PEAR_LOG_DEBUG);

        // Convert to hash with suid as key.
        if (is_array($r)) {
            $prevSuids = array_flip($r);
        } else {
            $prevSuids = array();
        }

        foreach ($currentSuids as $suid) {
            if (isset($prevSuids[$suid])) {
                // Entry is there now and in $prevSuids. Unset in $prevSuids
                // array so we end up with only those in $prevSuids that are
                // no longer there now.
                unset($prevSuids[$suid]);
            } else {
                // Entry is there now but not in $prevSuids. New entry, store
                // in syncml_suidlist
                $r = $this->_db->exec(
                    'INSERT INTO syncml_suidlist '
                    . ' (syncml_syncpartner, syncml_db, syncml_uid, '
                    . 'syncml_suid) VALUES ('
                    . $this->_db->quote($this->_syncDeviceID, 'text') . ', '
                    . $this->_db->quote($database, 'text') . ', '
                    . $this->_db->quote($this->_user, 'text') . ', '
                    . $this->_db->quote($suid, 'text')
                    . ')');
                if ($this->_checkForError($r)) {
                    return $r;
                }
            }
        }

        // $prevSuids now contains the deleted suids. Remove those from
        // syncml_suidlist so we have a current list of all existing suids.
        foreach ($prevSuids as $suid => $cuid) {
            $r = $this->_removeFromSuidList($databaseURI, $suid);
        }

        $this->logMessage('_trackDeletes() with ' . count($prevSuids)
                          . ' deleted items',
                          __FILE__, __LINE__, PEAR_LOG_DEBUG);

        return array_keys($prevSuids);
    }

    /**
     * Removes a suid from the suidlist.
     *
     * Called by _trackDeletes() when updating the suidlist and deleteEntry()
     * when removing an entry due to a client request.
     *
     * @param string $databaseURI  URI of database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param array $suid          The suid to remove from the list.
     */
    function _removeFromSuidList($databaseURI, $suid)
    {
        $database = $this->_normalize($databaseURI);

        $this->logMessage('_removeFromSuidList(): item ' . $suid,
                          __FILE__, __LINE__, PEAR_LOG_DEBUG);
        $r = $this->_db->queryCol(
            'DELETE FROM syncml_suidlist '
            . 'WHERE syncml_syncpartner = '
            . $this->_db->quote($this->_syncDeviceID, 'text')
            . ' AND syncml_db = '
            . $this->_db->quote($database, 'text')
            . ' AND syncml_uid = '
            . $this->_db->quote($this->_user, 'text')
            . ' AND syncml_suid = '
            . $this->_db->quote($suid, 'text'));
        if ($this->_checkForError($r)) {
            return $r;
        }

        $this->logMessage('_removeFromSuidList(): result ' . implode('!', $r),
                          __FILE__, __LINE__, PEAR_LOG_DEBUG);

        return true;
    }

    /**
     * Creates a clean test environment in the backend.
     *
     * Ensures there's a user with the given credentials and an empty data
     * store.
     *
     * @param string $user This user accout has to be created in the backend.
     * @param string $pwd  The password for user $user.
     */
    function testSetup($user, $pwd)
    {
        $this->_user = $user;
        $this->_cleanUser($user);
        $this->_backend->_user = $user;

        $r = $this->_db->exec(
            'INSERT INTO syncml_uids (syncml_uid, syncml_password)'
            . ' VALUES ('
            . $this->_db->quote($user, 'text') . ', '
            . $this->_db->quote($pwd, 'text') . ')');
        $this->_checkForError($r);
    }

    /**
     * Prepares the test start.
     *
     * @param string $user This user accout has to be created in the backend.
     */
    function testStart($user)
    {
        $this->_user = $user;
        $this->_backend->_user = $user;
    }

    /**
     * Tears down the test environment after the test is run.
     *
     * Should remove the testuser created during testSetup and all its data.
     */
    function testTearDown()
    {
        $this->_cleanUser($this->_user);
        $this->_db->disconnect();
    }

    /* Database access functions. The following methods are not part of the
     * backend API. They are here to illustrate how a backend application
     * (like a web calendar) has to modify the data with respect to the
     * history. There are three functions:
     * addEntry_backend(), replaceEntry_backend(), deleteEntry_backend().
     * They are very similar to the API methods above, but don't use cuids or
     * syncDeviceIDs as these are only relevant for syncing. */

    /**
     * Adds an entry into the server database.
     *
     * @param string $user         The username to use. Not strictly necessery
     *                             to store this, but it helps for the test
     *                             environment to clean up all entries for a
     *                             test user.
     * @param string $databaseURI  URI of Database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $content      The actual data.
     * @param string $contentType  MIME type of the content.
     *
     * @return array  PEAR_Error or suid of new entry.
     */
    function addEntry_backend($user, $databaseURI, $content, $contentType)
    {
        $database = $this->_normalize($databaseURI);

        // Generate an id (suid). It's also possible to use a database
        // generated primary key here. */
        $suid = $this->_generateID();

        $created_ts = $this->getCurrentTimeStamp();
        $r = $this->_db->exec(
            'INSERT INTO syncml_data (syncml_id, syncml_db, syncml_uid, '
            . 'syncml_data, syncml_contenttype, syncml_created_ts, '
            . 'syncml_modified_ts) VALUES ('
            . $this->_db->quote($suid, 'text') . ', '
            . $this->_db->quote($database, 'text') . ', '
            . $this->_db->quote($user, 'text') . ', '
            . $this->_db->quote($content, 'text') . ', '
            . $this->_db->quote($contentType, 'text') . ', '
            . $this->_db->quote($created_ts, 'integer') . ', '
            . $this->_db->quote($created_ts, 'integer')
            . ')');
        if ($this->_checkForError($r)) {
            return $r;
        }

        return $suid;
    }

    /**
     * Replaces an entry in the server database.
     *
     * @param string $user         The username to use. Not strictly necessery
     *                             to store this but, it helps for the test
     *                             environment to clean up all entries for a
     *                             test user.
     * @param string $databaseURI  URI of Database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $content      The actual data.
     * @param string $contentType  MIME type of the content.
     * @param string $suid         Server ID of this entry.
     *
     * @return string  PEAR_Error or suid of modified entry.
     */
    function replaceEntry_backend($user, $databaseURI, $content, $contentType,
                                  $suid)
    {
        $database = $this->_normalize($databaseURI);
        $modified_ts = $this->getCurrentTimeStamp();

        // Entry exists: replace current one.
        $r = $this->_db->exec(
            'UPDATE syncml_data '
            . 'SET syncml_modified_ts = '
            . $this->_db->quote($modified_ts, 'integer')
            . ',syncml_data = '
            . $this->_db->quote($content, 'text')
            . ',syncml_contenttype = '
            . $this->_db->quote($contentType, 'text')
            . 'WHERE syncml_db = '
            . $this->_db->quote($database, 'text')
            . ' AND syncml_uid = '
            . $this->_db->quote($user, 'text')
            . ' AND syncml_id = '
            . $this->_db->quote($suid, 'text'));
        if ($this->_checkForError($r)) {
            return $r;
        }

        return $suid;
    }

    /**
     * Deletes an entry from the server database.
     *
     * @param string $user         The username to use. Not strictly necessery
     *                             to store this, but it helps for the test
     *                             environment to clean up all entries for a
     *                             test user.
     * @param string $databaseURI  URI of Database to sync. Like calendar,
     *                             tasks, contacts or notes. May include
     *                             optional parameters:
     *                             tasks?options=ignorecompleted.
     * @param string $suid         Server ID of the entry.
     *
     * @return boolean  True on success or false on failed (item not found).
     */
    function deleteEntry_backend($user, $databaseURI, $suid)
    {
        $database = $this->_normalize($databaseURI);

        $r = $this->_db->queryOne(
            'DELETE FROM syncml_data '
            . 'WHERE syncml_db = '
            . $this->_db->quote($database, 'text')
            . ' AND syncml_uid = '
            . $this->_db->quote($user, 'text')
            . ' AND syncml_id = '
            . $this->_db->quote($suid, 'text'));
        if ($this->_checkForError($r)) {
            return false;
        }

        return true;
    }

    function _cleanUser($user)
    {
        $r = $this->_db->exec('DELETE FROM syncml_data WHERE syncml_uid = '
                              . $this->_db->quote($user, 'text'));
        $this->_checkForError($r);

        $r = $this->_db->exec('DELETE FROM syncml_map WHERE syncml_uid = '
                              . $this->_db->quote($user, 'text'));
        $this->_checkForError($r);

        $r = $this->_db->exec('DELETE FROM syncml_anchors WHERE syncml_uid = '
                              . $this->_db->quote($user, 'text'));
        $this->_checkForError($r);

        $r = $this->_db->exec('DELETE FROM syncml_uids WHERE syncml_uid = '
                              . $this->_db->quote($user, 'text'));
        $this->_checkForError($r);

        $r = $this->_db->exec('DELETE FROM syncml_suidlist WHERE syncml_uid = '
                              . $this->_db->quote($user, 'text'));
        $this->_checkForError($r);
    }
    
	static function _xvcard2internal($data){
		$GLOBALS['backend']->logMessage("CALL: _xvcard2internal($data)" , __LINE__, PEAR_LOG_DEBUG);
		$lines = explode("\n", $data);
		$vcards = O_VBook::parse_vcards($lines);
		$contacts_arr = O_VBook::to_plain_array($vcards);

        $result = array();
        $phone = new Contacts_Phone; 
		foreach($contacts_arr as $contact){
			$tmp_arr = $phone->get_vcard_array($contact);
			if (false !== $tmp_arr) $result[] = $tmp_arr;
		}
		
        return $result;
	}

	static function _internal2xvcard($data){
        $GLOBALS['backend']->logMessage("CALL: _internal2xvcard(".print_r($data, 1).")" , __LINE__, PEAR_LOG_DEBUG);
		#we unset all non primary attributes because most devices/clients accept only one instance of each attribute type.
		$d = array();
		if (empty($data['contacts'])) $data['contacts'] = array();
		foreach ($data['contacts'] as $attr){
			if (!empty($attr['primary'])) {
				if ('y' == $attr['primary']) {
					$d[] = $attr;
				}
			}
		}
		$data['contacts'] = $d;
		
        $GLOBALS['backend']->logMessage("CALL: _internal2xvcard PRIMARYS ONLY = ".print_r($data, 1) , __LINE__, PEAR_LOG_DEBUG);
        
        $writer = new O_VCardWriter;
        $result = O_VCardWriter::write($data);
		$GLOBALS['backend']->logMessage("CALL: _internal2xvcard() result: $result" , __LINE__, PEAR_LOG_DEBUG);
        return $result;
			}
    
	/*
	 * 
	 * 
	 * 
	 * 
	 */
    /**
     * Parses a Date field.
     * We have to avoid loading iCalendar classes as much as possible
     *
     * @static
     */
    static function _parseDate($text)
    {
        $parts = explode('T', $text);
        if (count($parts) == 2) {
            $text = $parts[0];
        }

        if (!preg_match('/^(\d{4})-?(\d{2})-?(\d{2})$/', $text, $match)) {
            return false;
        }

        return array('year' => $match[1],
                     'month' => $match[2],
                     'mday' => $match[3]);
    }

    /**
     * Parses a Time field.
     * We have to avoid loading iCalendar classes as much as possible
     *      *
     * @static
     */
    static function _parseTime($text)
    {
        if (preg_match('/([0-9]{2})([0-9]{2})([0-9]{2})(Z)?/', $text, $timeParts)) {
            $time['hour'] = intval($timeParts[1]);
            $time['minute'] = intval($timeParts[2]);
            $time['second'] = intval($timeParts[3]);
            if (isset($timeParts[4])) {
                $time['zone'] = 'UTC';
            } else {
                $time['zone'] = 'Local';
            }
            return $time;
        } else {
            return false;
        }
    }
    
}
