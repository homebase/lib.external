<?php
/**
 * RockMongo configuration
 *
 * Defining default options and server configuration
 * @package rockmongo
 */
 
$MONGO = array();
$MONGO["features"]["log_query"] = "off";//log queries
$MONGO["features"]["theme"] = "default";//theme
$MONGO["features"]["plugins"] = "on";//plugins

/**
* Configuration of MongoDB servers
*/
if ($_SERVER["HTTP_HOST"] != "admin.rd.dev") {
    // Production version
    $MONGO["servers"] = array(
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
            "mongo_name" => "mdb",
            "mongo_host" => "mdb", // Replace your MongoDB host ip or domain name here
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
*/
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
        array(
            "mongo_name" => "user1",
            "mongo_host" => "user1",
            "mongo_port" => "27017",
            "mongo_timeout" => 0,
            "mongo_auth" => false,//Enable authentication, set to "false" to disable authentication
            "control_auth" => false,//enable control users, works only if mongo_auth=false
            "control_users" => array( 
                "admin" => "admin", // Administrator's USERNAME => PASSWORD
            ),
        ),
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
            "host" => "user1",
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

?>
