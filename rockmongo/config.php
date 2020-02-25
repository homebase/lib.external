<?php
/**
 * RockMongo configuration
 *
 * Defining default options and server configuration
 * @package rockmongo
 */
 
$MONGO = array();
$MONGO["features"]["log_query"] = "on";//log queries
$MONGO["features"]["theme"] = "default";//theme
$MONGO["features"]["plugins"] = "on";//plugins

$i = 0;

/**
* Configuration of MongoDB servers
* 
* @see more details at http://rockmongo.com/wiki/configuration?lang=en_us
*/
if (1/*$_SERVER["HTTP_HOST"] != "admin.rd.dev"*/) {
    // Production version
    $MONGO["servers"] = array(
        // New COLO mongo server
        array(
            "mongo_name" => "d-mdb",
            "mongo_host" => "d-mdb",
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array(
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "mdb2",
            "mongo_host" => "mdb2", // Replace your MongoDB host ip or domain name here
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "rbackup",
            "mongo_host" => "rbackup", // Replace your MongoDB host ip or domain name here
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array(
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "d-user",
            "mongo_host" => "d-user",
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array(
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "pwb",
            "mongo_host" => "pwb",
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array(
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
/*
        array(
            "mongo_name" => "mnews2",
            "mongo_host" => "mnews2",
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "mnews",
            "mongo_host" => "mnews",
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "mdb2",
            "mongo_host" => "mdb2", // Replace your MongoDB host ip or domain name here
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => true,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "mdb1",
            "mopngo_host" => "mdb1", // Replace your MongoDB host ip or domain name here
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "gfs1",
            "mongo_host" => "gfs1", // Replace your MongoDB host ip or domain name here
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "gfs2",
            "mongo_host" => "gfs2", // Replace your MongoDB host ip or domain name here
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "mongo_name" => "a4c",
            "mongo_host" => "a4c", // Replace your MongoDB host ip or domain name here
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
*/

    );
} else {
// Developmen version
    die("fix cofig format, please!");
    $MONGO["servers"] = array(
        array(
            "host" => "mdb1", // Replace your MongoDB host ip or domain name here
            "port" => "27017", // MongoDB connection port
            "username" => null, // MongoDB connection username
            "password" => null, // MongoDB connection password
            "auth_enabled" => false,//Enable authentication, set to "false" to disable authentication
            "admins" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),

            // show only following databases (and also allow to pick custom db by name):
            //"show_dbs" => array(
            //    'admin', 'local'
            //)

        ),
        array(
            "host" => "mdb2", // Replace your MongoDB host ip or domain name here
            "port" => "27017", // MongoDB connection port
            "username" => null, // MongoDB connection username
            "password" => null, // MongoDB connection password
            "auth_enabled" => false,//Enable authentication, set to "false" to disable authentication
            "admins" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),

            // show only following databases (and also allow to pick custom db by name):
            //"show_dbs" => array(
            //    'admin', 'local'
            //)

        ),
        array(
            "host" => "gfs1", // Replace your MongoDB host ip or domain name here
            "port" => "27017", // MongoDB connection port
            "username" => null, // MongoDB connection username
            "password" => null, // MongoDB connection password
            "auth_enabled" => false,//Enable authentication, set to "false" to disable authentication
            "admins" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "host" => "gfs2", // Replace your MongoDB host ip or domain name here
            "port" => "27017", // MongoDB connection port
            "username" => null, // MongoDB connection username
            "password" => null, // MongoDB connection password
            "auth_enabled" => false,//Enable authentication, set to "false" to disable authentication
            "admins" => array(
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "host" => "a4c", // Replace your MongoDB host ip or domain name here
            "port" => "27017", // MongoDB connection port
            "username" => null, // MongoDB connection username
            "password" => null, // MongoDB connection password
            "auth_enabled" => false,//Enable authentication, set to "false" to disable authentication
            "admins" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
        array(
            "host" => "pa8user",
            "port" => "27017",
            "username" => null,
            "password" => null,
            "auth_enabled" => true,
            "admins" => array( 
                "admin" => "admin"
            )
        ),

    );
}
/*

$MONGO["servers"][$i]["mongo_name"] = "Localhost";//mongo server name
//$MONGO["servers"][$i]["mongo_sock"] = "/var/run/mongo.sock";//mongo socket path (instead of host and port)
$MONGO["servers"][$i]["mongo_host"] = "127.0.0.1";//mongo host
$MONGO["servers"][$i]["mongo_port"] = "27017";//mongo port
$MONGO["servers"][$i]["mongo_timeout"] = 0;//mongo connection timeout
//$MONGO["servers"][$i]["mongo_db"] = "MONGO_DATABASE";//default mongo db to connect, works only if mongo_auth=false
//$MONGO["servers"][$i]["mongo_user"] = "MONGO_USERNAME";//mongo authentication user name, works only if mongo_auth=false
//$MONGO["servers"][$i]["mongo_pass"] = "MONGO_PASSWORD";//mongo authentication password, works only if mongo_auth=false
$MONGO["servers"][$i]["mongo_auth"] = false;//enable mongo authentication?

$MONGO["servers"][$i]["control_auth"] = true;//enable control users, works only if mongo_auth=false
$MONGO["servers"][$i]["control_users"]["admin"] = "admin";//one of control users ["USERNAME"]=PASSWORD, works only if mongo_auth=false

$MONGO["servers"][$i]["ui_only_dbs"] = "";//databases to display
$MONGO["servers"][$i]["ui_hide_dbs"] = "";//databases to hide
$MONGO["servers"][$i]["ui_hide_collections"] = "";//collections to hide
$MONGO["servers"][$i]["ui_hide_system_collections"] = false;//whether hide the system collections
*/

//$MONGO["servers"][$i]["docs_nature_order"] = false;//whether show documents by nature order, default is by _id field
//$MONGO["servers"][$i]["docs_render"] = "default";//document highlight render, can be "default" or "plain"

$i ++;

/**
 * mini configuration for another mongo server
 */
/**
$MONGO["servers"][$i]["mongo_name"] = "Localhost2";
$MONGO["servers"][$i]["mongo_host"] = "127.0.0.1";
$MONGO["servers"][$i]["mongo_port"] = "27017";
$MONGO["servers"][$i]["control_users"]["admin"] = "password";
$i ++;
**/

?>