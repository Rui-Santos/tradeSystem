<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class TakeProfit extends BaseOrder
	{
		/**
		 * @var SeriesCounter
		 */
		private $seriesCounter = null;

		private $price = null;

		private $extremumPrice = null;

		private $indent = null;

		/**
		 * @var UnitsType
		 */
		private $indentUnitsType = null;

		/**
		 * @return TakeProfit
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return TakeProfit
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
		 * @return TakeProfit
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
		 * @return TakeProfit
		 */
		public function setIndent($indent)
		{
			$this->indent = $indent;
			return $this;
		}

		public function getIndent()
		{
			return $this->indent;
		}

		/**
		 * @return TakeProfit
		 */
		public function setIndentUnitsType(UnitsType $unitsType)
		{
			$this->indentUnitsType = $unitsType;
			return $this;
		}

		/**
		 * @return UnitsType
		 */
		public function getIndentUnitsType()
		{
			return $this->indentUnitsType;
		}

		/**
		 * @return TakeProfit
		 */
		public function simpleHandle($value, $realizePrice = null)
		{
			if (!$this->isRealized()) {
				if ($this->isRealizePrice($value)) {
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
						$this->getSeriesCounter()->win();
				} else
					$this->updateExtremum($value);
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
			$this->updateExtremum($bar->getLow());
			$this->updateExtremum($bar->getHigh());

			$realizationPrice = $this->getDecisionPrice();

			$this->simpleHandle($bar->getLow(), $realizationPrice);
			$this->simpleHandle($bar->getHigh(), $realizationPrice);

			return parent::handleBar($bar);
		}

		public function isWatchIndent()
		{
			return
				$this->extremumPrice !== null
				&& (
					(
						$this->getType()->getId() == OrderType::SELL
						&& Math::eqGt($this->extremumPrice, $this->getPrice())
					) || (
						$this->getType()->getId() == OrderType::BUY
						&& Math::eqLt($this->extremumPrice, $this->getPrice())
					)
				);
		}

		private function isRealizePrice($price)
		{
			return
				$this->isWatchIndent()
				&& (
					(
						$this->getType()->getId() == OrderType::SELL
						&& Math::eqGt(
							$this->getDecisionPrice(),
							$price
						)
					) || (
						$this->getType()->getId() == OrderType::BUY
						&& Math::eqLt(
							$this->getDecisionPrice(),
							$price
						)
					)
				);
		}

		/**
		 * @return TakeProfit
		 */
		private function updateExtremum($price)
		{
			if (
				(
					$this->getType()->getId() == OrderType::BUY
					&& (
						Math::gt($this->extremumPrice, $price)
						|| $this->extremumPrice === null
					)
				) || (
					$this->getType()->getId() == OrderType::SELL
					&& Math::lt(
						$this->extremumPrice,
						$price
					)
				)
			) {
				$this->extremumPrice = $price;
			}

			return $this;
		}

		private function getDecisionPrice()
		{
			$result = null;

			$invertor = $this->getType()->getInvertedInvertor();

			if ($this->getIndentUnitsType()->getId() == UnitsType::VALUE) {
				$result =
					Math::sub(
						$this->extremumPrice,
						Math::multiply($this->getIndent(), $invertor)
					);
			} else {
				$result =
					Math::sub(
						$this->extremumPrice,
						Math::multiply(
							$this->extremumPrice,
							Math::multiply(
								Math::div($this->indent, 100),
								$invertor
							)
						)
					);
			}

			return $result;
		}
	}
?>