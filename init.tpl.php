<?php
	namespace tradeSystem {

	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);

	define(__NAMESPACE__.'\EWGRA_PROJECTS_DIR', '/home/www/ewgraProjects');
	define(__NAMESPACE__.'\FRAMEWORK_DIR', EWGRA_PROJECTS_DIR . '/framework');
	define(__NAMESPACE__.'\BASE_DIR', dirname(__FILE__));

	classesAutoloaderInit();

	function classesAutoloaderInit()
	{
		require_once(FRAMEWORK_DIR . '/core/patterns/SingletonInterface.class.php');
		require_once(FRAMEWORK_DIR . '/core/patterns/Singleton.class.php');
		require_once(FRAMEWORK_DIR . '/ClassesAutoloader.class.php');

		\ewgraFramework\ClassesAutoloader::me()->
			addSearchDirectory(BASE_DIR . '/classes', 'tradeSystem')->
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