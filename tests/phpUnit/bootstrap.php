<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/

	$initDir = __DIR__.'/../..';

	$initFile =
		file_exists($initDir.'/init.php')
			? $initDir.'/init.php'
			: $initDir.'/init.tpl.php';

	require_once($initFile);

	\tradeSystem\classesAutoloaderInit();

	\ewgraFramework\ClassesAutoloader::me()->
		addSearchDirectory(\tradeSystem\BASE_DIR.'/tests/cases', 'tradeSystem\tests');

	require_once(__DIR__.'/TradeSystemTestCase.class.php');

	define(__NAMESPACE__.'\CASES_DIR', __DIR__.'/../cases');
	define(__NAMESPACE__.'\TMP_DIR', '/tmp/tradeSystemTests');


	$dir = \ewgraFramework\Dir::create()->setPath(TMP_DIR);

	if (!$dir->isExists())
		$dir->make();
?>