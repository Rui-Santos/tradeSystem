<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class TradeSystemTestCase extends \PHPUnit_Framework_TestCase
	{
		private $savedSingletons = array();

		public function saveSingleton(\ewgraFramework\Singleton $singleton)
		{
			$this->savedSingletons[get_class($singleton)] = serialize($singleton);
			\ewgraFramework\TestSingleton::dropInstance(get_class($singleton));

			return $this;
		}

		public function restoreSingleton(\ewgraFramework\Singleton $singleton)
		{
			\ewgraFramework\TestSingleton::setInstance(
				get_class($singleton),
				unserialize($this->savedSingletons[get_class($singleton)])
			);

			return $this;
		}
	}
?>