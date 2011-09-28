<?php
	namespace tradeSystem {

	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);

	define(__NAMESPACE__.'\BASE_DIR', __DIR__);
	define(__NAMESPACE__.'\FRAMEWORK_DIR', BASE_DIR.'/../framework');

	classesAutoloaderInit();

	function classesAutoloaderInit()
	{
		require_once(FRAMEWORK_DIR.'/core/patterns/SingletonInterface.class.php');
		require_once(FRAMEWORK_DIR.'/core/patterns/Singleton.class.php');
		require_once(FRAMEWORK_DIR.'/ClassesAutoloader.class.php');

		\ewgraFramework\ClassesAutoloader::me()->
			addSearchDirectory(BASE_DIR.'/classes', 'tradeSystem')->
			addSearchDirectory(FRAMEWORK_DIR, 'ewgraFramework');
	}

	}

	namespace {
		function __autoload($className)
		{
			if (!class_exists('\ewgraFramework\ClassesAutoloader', false))
				return null;

			\ewgraFramework\ClassesAutoloader::me()->load($className);
		}
	}
?>