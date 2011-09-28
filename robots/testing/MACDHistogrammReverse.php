<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/

	if (!isset($argv[1])) {
		die('You need set input file');
	}

	$inputFile = $argv[1];

	$initFile =
		file_exists(__DIR__.'../init.php')
			? __DIR__.'/../init.php'
			: __DIR__.'/../init.tpl.php';

	require_once($initFile);

	\tradeSystem\classesAutoloaderInit();

	$shortEMA = \tradeSystem\EMA::create(12);
	$longEMA = \tradeSystem\EMA::create(26);

	$MACD = \tradeSystem\MACD::create($shortEMA, $longEMA);

	$MACDSignal = \tradeSystem\MACDSignal::create($MACD);

	$MACDHistogramm =
		\tradeSystem\MACDHistogramm::create($MACD, $MACDSignal);

	$chart =
		\tradeSystem\Chart::create()->
		setInterval(ChartInterval::hourly())->
		addIndicator($shortEMA)->
		addIndicator($longEMA)->
		addIndicator($MACD)->
		addIndicator($MACDSignal)->
		addIndicator($MACDHistogramm);

	$seriesCounter = \tradeSystem\SeriesCounter::create();

	$strategy =
		new \tradeSystem\MACDHistogrammReverseStrategy(
			new \tradeSystem\EndStrategy()
		);

	$security = \tradeSystem\Security::create()->setId('SBER3');

	$strategy->
		setSeriesCounter($seriesCounter)->
		setIndicator($MACDHistogramm)->
		setSecurity($security);

	$strategy = new \tradeSystem\BeginStrategy($strategy);

	\tradeSystem\Portfolio::me()->setBalance(10000);

	$barReader =
		\tradeSystem\FinamBarReader::create()->
		setFileName($inputFile)->
		skipHead();

	$comission =
		\tradeSystem\DailyValueComission::create()->
		setPercent(0.12);

	$lastBar = null;

	while($bar = $barReader->getNext()) {
		\tradeSystem\DateTimeManager::me()->setNow($bar->getDateTime());

		$comission->manage();

		$chart->handleBar($bar);
		$strategy->handleBar($bar);

		$lastBar = $bar;
	}

	PositionManager::me()->closeAll($security, $lastBar->getClose());

	var_dump($seriesCounter);
	var_dump(Portfolio::me());
	var_dump(Log::me());
?>