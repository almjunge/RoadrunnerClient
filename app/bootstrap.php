<?php
require_once __DIR__ . '/../vendor/silex.phar';

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Symfony\Component\ClassLoader\UniversalClassLoader;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Doctrine\ODM\CouchDB\DocumentManager;

use Roadrunner\Database\CouchDB;

// class loader
$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
	'Roadrunner'      => __DIR__ . '/../src',
	'Doctrine'    => array(
		__DIR__ . '/../vendor/couchdb-odm/lib',
		__DIR__ . '/../vendor/couchdb-odm/lib/vendor/doctrine-common/lib'
	),
	'Monolog'      => __DIR__ . '/../vendor/Monolog/src',
));
$loader->register();

// couch db
$config = new \Doctrine\ODM\CouchDB\Configuration();
$httpClient = new \Doctrine\ODM\CouchDB\HTTP\SocketClient();
$config->setHttpClient($httpClient);

$dm = DocumentManager::create($config);

// logger
$log = new Logger('roadrunner');
$log->pushHandler(new StreamHandler(
	'file://' . __DIR__ . '/../log/error.log',
	Logger::ERROR
));

// helper functions
function link_to($url, $name) {
	return sprintf('<a href="%s">%s</a>', url_for($url), $name);
}

function url_for($url) {
	return 'http://' . $_SERVER['HTTP_HOST'] . '/' . trim($url, '/');
}
