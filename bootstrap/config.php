<?php
//http
define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . '/');

//paths
define('DIR_ROOT', __DIR__ . '/../');
define('DIR_APPLICATION', DIR_ROOT . '/App/');
define('DIR_TEMPLATE', DIR_ROOT . 'App/View/');
define('DIR_LOGS', DIR_ROOT . 'logs/');

//GOOGLE API json file
define('API_NAME', 'StudyOAuth_site');
define('API_JSON', 'client.apps.googleusercontent.com.json');

//database settings
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'messagewall');
define('DB_PASSWORD', 'ZgiatfcXwQJ4Q4IfQAmb');
define('DB_DATABASE', 'messagewall');
