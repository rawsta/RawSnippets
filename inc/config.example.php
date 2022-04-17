<?php

/* #region CONSTANTS */

define("RAW_BASE", "//YOURDOMAIN.XY/");
define("RAW_NAME", parse_url(RAW_BASE, PHP_URL_HOST));
define("RAW_BASE_PATH", parse_url(RAW_BASE, PHP_URL_PATH));
// define("RAW_API", RAW_BASE_PATH . "api/");
// define("RAW_API_BASE", RAW_BASE . "api/");
define("RAW_ASSETS", RAW_BASE . "assets/");
define("RAW_VER", "0.6.9");
/* DEBUGGING */
define("RAW_DEBUG", true);

/* TODO: add critical data to .env file */
// DATABASE - @CHANGE
define("DB_HOST", "localhost");
define("DB_NAME", "DBNAME");
define("DB_USER", "DBUSER");
define("DB_PASSWORD", "DBPASS");
define("DB_CHARSET", "utf8");

/* TODO: Implement JWT for User */
// JSON WEB TOKEN
// define("JWT_SECRET", "YOUR-SECRET-KEY");
// define("JWT_ISSUER", "YOUR-NAME");
// define("JWT_ALGO", "HS256");
// define("JWT_EXPIRE", 0); // in seconds, 0 for none

/* TODO: Implement API instead of direct queries */
// API ENDPOINTS
// define("API_HTTPS", false);
// define("API_CORS", false);
// define("API_CORS", false); // no cors, accept RAW_name only
// define("API_CORS", true); // any domain + mobile apps
// define("API_CORS", "site-a.com"); // this domain only
// define("API_CORS", ["site-a.com", "site-b.com"]); // multiple domains

/* #endregion */

/* #region SETTINGS AND CONFIGURATION */

/* TODO: set config array */

$config = [
    yourEmail => "XY1", // Admin Email
    numUsers => "XY1", // # of Users per Adminpage
    numSnip => "XY1", // # of Snippets per Adminpage
];

// CACHE BUSTING
function cache_version() {
	if ( RAW_DEBUG ) {
		return time();
	} else {
		return RAW_VER;
	}
}

/* #endregion */

/* #region ERROR HANDLING */

// Show errors in DEV / Hide and write log in PROD
function error_level() {
	if ( RAW_DEBUG ) {
    // ENV = DEVELOPMENT
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set("display_errors", 1);
    ini_set("log_errors", 0);
	} else {
    // ENV = PRODUCTION
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set("display_errors", 0);
    ini_set("log_errors", 1);
    // ini_set("error_log", "PATH/error.log");
	}
}
/* #endregion */



/* #region MAYBE USEFUL? - BACKUP/SCRATCHPAD */

// AUTOMATIC SYSTEM PATH - needed?
// define("PATH_LIB", __DIR__ . DIRECTORY_SEPARATOR);
// define("PATH_BASE", dirname(PATH_LIB) . DIRECTORY_SEPARATOR);
// define("PATH_API", PATH_BASE . "api" . DIRECTORY_SEPARATOR);
// define("PATH_ASSETS", PATH_BASE . "assets" . DIRECTORY_SEPARATOR);
// define("PATH_PAGES", PATH_BASE . "pages" . DIRECTORY_SEPARATOR);



/* FOR API BASE */

// API MODE FLAG
// use this to tweak your system behaviors
// e.g. if (defined("api_mode")) { json_encode(data) } else { display html }
// define("API_MODE", true);

// ENFORCE HTTPS (RECOMMENDED)
// if (API_HTTPS && empty($_SERVER["HTTPS"])) {
//   $_CORE->respond(0, "Please use HTTPS", null, null, 426);
// }

// GET CLIENT ORIGIN
// $_OGN = $_SERVER["HTTP_ORIGIN"] ??
//           $_SERVER["HTTP_REFERER"] ??
//             $_SERVER["REMOTE_ADDR"] ??
//         "" ;

// CORS SUPPORT - ONLY IF NOT LOCALHOST
// if (!in_array($_OGN, ["::1", "127.0.0.1", "localhost"])) {
//   // PARSE ORIGIN HOST NAME
//   $_OGN_HOST = parse_url($_OGN, PHP_URL_HOST);

//   // FALSE - ONLY CALLS FROM RAW_NAME ALLOWED
//   if (API_CORS===false && $_OGN_HOST!=RAW_NAME) { $access = false; }

//   // STRING - ALLOW CALLS FROM API_CORS ONLY
//   else if (is_string(API_CORS) && $_OGN_HOST!=API_CORS) { $access = false; }

//   // ARRAY - SPECIFIED DOMAINS IN API_CORS ONLY
//   else if (is_array(API_CORS) && !in_array($_OGN_HOST, API_CORS)) { $access = false; }

//   // TRUE - ANYTHING GOES
//   else { $access = true; $_OGN = "*"; }

//   // ACCESS DENIED
//   if ($access === false) {
//     $_CORE->respond(0, "Calls from $_OGN not allowed", null, null, 403);
//   }

//   // OUTPUT CORS HEADERS IF REQUIRED
//   if ($_OGN_HOST != RAW_NAME) {
//     header("Access-Control-Allow-Origin: $_OGN");
//     header("Access-Control-Allow-Credentials: true");
//   }
// }

// PARSE URL PATH INTO AN ARRAY
// EXTRACT PATH FROM FULL URL
// @example: https://site.com/api/foo/bar/ => $_PATH="foo/bar"
$_PATH = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$_PATH = substr($_PATH, strlen(RAW_API));
$_PATH = rtrim($_PATH, "/");

// EXPLODE INTO AN ARRAY
// @example: $_PATH="foo/bar/" > $_PATH=["foo", "bar"]
// $_PATH = explode("/", $_PATH);

// MANAGE REQUEST
// VALID API REQUEST?
// $valid = count($_PATH)==2;
// if ($valid) {
//   $_MOD = $_PATH[0];
//   $_REQ = $_PATH[1];
//   $valid = file_exists(PATH_API . "API-$_MOD.php");
// }

// LOAD API HANDLER
// if ($valid) {
  // CLEAN UP
//   unset($access); unset($_PATH); unset($valid);

  // FLAGS THAT ARE USEFUL IN YOUR API
  // $_MOD : requested module. e.g. user
  // $_REQ : requested action. e.g. save
  // $_OGN : client origin. e.g. https://site.com/
  // $_OGN_HOST : host name. e.g. site.com
//   require PATH_API . "API-$_MOD.php";
// } else { $_CORE->respond(0, "Invalid request", null, null, 400); }


/* #endregion */