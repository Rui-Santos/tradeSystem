<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class OrderOrGroup extends BaseOrder
	{
		private $strategies = array();

		/**
		 * @return StrategyOrGroup
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return StrategyOrGroup
		 */
		public function addStrategy(Strategy $strategy)
		{
			$this->strategies[] = $strategy;
			return $this;
		}

		/**
		 * @return Strategy
		 */
		public function handle($value)
		{
			if (!$this->isRealized()) {
				foreach ($this->strategies as $strategy) {
					$strategy->handle($value);

					if ($strategy->isRealized()) {
						$this->realized();
						break;
					}
				}
			}

			return parent::handle($value);
		}

		/**
		 * @return Strategy
		 */
		public function handleBar(Bar $bar)
		{
			if (!$this->isRealized()) {
				foreach ($this->strategies as $strategy) {
					$strategy->handleBar($bar);

					if ($strategy->isRealized()) {
						$this->realized();
						break;
					}
				}
			}

			return parent::handleBar($bar);
		}
	}
?>