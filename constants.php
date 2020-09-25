<?php
       

define("APINAME", "britania");
define("DISPLAYERRORS", 1);
define("ERRORTYPE", 0); // 0 ~ E_ALL, 1 ~ E_WARNING, 2 ~ E_NOTICE
define("DEBUG", 1);
define("DEBUGFILE", 1);

# Write database
define("DBWHOST", "britannia.database.windows.net");
define("DBWUSER", "sambit");
define("DBWPASS", "beta=X'X^-1X'y");
define("DBWDB", "ccdb");

# Read database
define("DBRHOST", "britannia.database.windows.net");
define("DBRUSER", "sambit");
define("DBRPASS", "beta=X'X^-1X'y");
define("DBRDB", "ccdb");

 // # Write database
 // define("DBWHOST", "172.16.8.63");
 // define("DBWUSER", "tradeapp");
 // define("DBWPASS", "Bbrit@123");
 // define("DBWDB", "ccdb");

 // # Read database
 // define("DBRHOST", "172.16.8.63");
 // define("DBRUSER", "tradeapp");
 // define("DBRPASS", "Bbrit@123");
 // define("DBRDB", "ccdb");

//upload
define("UPLOAD_FILE", "upload/file/");
define("UPLOAD_LOGO", "upload/logo/");

define("key","Test_key");                 //unique secret key
define("iss","slate-jwt");               //issuer
// define("aud","http://test.com");     //audience
define("iat",1356999524);              //not before
define("nbf",1357000000);             //expiration time