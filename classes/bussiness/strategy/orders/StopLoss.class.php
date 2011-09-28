<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StopLoss extends BaseOrder
	{
		/**
		 * @var SeriesCounter
		 */
		private $seriesCounter = null;

		private $price = null;

		/**
		 * @return StopLoss
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return StopLoss
		 */
		public function setSeriesCounter(SeriesCounter $seriesCounter)
		{
			$this->seriesCounter = $seriesCounter;
			return $this;
		}

		public function getSeriesCounter()
		{
			return $this->seriesCounter;
		}

		/**
		 * @return StopLoss
		 */
		public function setPrice($price)
		{
			$this->price = $price;
			return $this;
		}

		public function getPrice()
		{
			return $this->price;
		}

		/**
		 * @return StopLoss
		 */
		public function simpleHandle($value, $realizePrice = null)
		{
			if (!$this->isRealized() && $this->isRealizePrice($value)) {
				$this->realize(
					$realizePrice
						? $realizePrice
						: $value
				);

				Log::me()->add(
					__CLASS__.': realized with price '
					.$this->getRealizationPrice().' and count '.$this->getCount().' ('.$this->getSecurity()->getId().')'
				);

					if ($this->getSeriesCounter())
						$this->getSeriesCounter()->lose();
			}

			return $this;
		}

		/**
		 * @return Strategy
		 */
		public function handle($value)
		{
			$this->simpleHandle($value);
			return parent::handle($value);
		}

		/**
		 * @return Strategy
		 */
		public function handleBar(Bar $bar)
		{
			$this->simpleHandle($bar->getLow(), $this->getPrice());
			$this->simpleHandle($bar->getHigh(), $this->getPrice());

			return parent::handleBar($bar);
		}

		private function isRealizePrice($price)
		{
			return
				(
					$this->getType()->getId() == OrderType::SELL
					&& Math::eqLt($price, $this->getPrice())
				) || (
					$this->getType()->getId()== OrderType::BUY
					&& Math::eqGt($price, $this->getPrice())
				);
		}
	}
?>