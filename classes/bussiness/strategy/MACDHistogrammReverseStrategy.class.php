<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MACDHistogrammReverseStrategy extends BaseStrategy
	{
		const STOP_LOSS_PERCENT		= 0.01;
		const TAKE_PROFIT_PERCENT	= 0.02;
		const TAKE_PROFIT_INDENT	= 0.4;

		/**
		 * @var SeriesCounter
		 */
		private $seriesCounter = null;

		/**
		 * @var Security
		 */
		private $security = null;

		/**
		 * @var Indicator
		 */
		private $indicator = null;

		private $prevValue = null;

		/**
		 * @return MACDHistogrammReverseStrategy
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
		 * @return MACDHistogrammReverseStrategy
		 */
		public function setIndicator(Indicator $indicator)
		{
			$this->indicator = $indicator;
			return $this;
		}

		/**
		 * @return MACDHistogrammReverseStrategy
		 */
		public function setSecurity(Security $security)
		{
			$this->security = $security;
			return $this;
		}

		public function simpleHandle($value)
		{
			$indicatorValue = $this->indicator->getValue();

			if (
				$this->prevValue !== null
				&& $this->prevValue >= 0
				&& $indicatorValue < 0
			)
				$this->openPosition($value, OrderType::sell());
			else if (
				$this->prevValue !== null
				&& $this->prevValue <= 0
				&& $indicatorValue > 0
			)
				$this->openPosition($value, OrderType::buy());

			$this->prevValue = $indicatorValue;

			return $this;
		}

		public function handle($value)
		{
			$this->simpleHandle($value);
			return parent::handle($value);
		}

		public function handleBar(Bar $bar)
		{
			$this->simpleHandle($bar->getClose());
			return parent::handleBar($bar);
		}

		/**
		 * @return MACDHistogrammReverseStrategy
		 */
		private function openPosition($price, OrderType $orderType)
		{
			$count =
				floor(
					Math::div(
						Portfolio::me()->getBalance(),
						$price
					)
				);

			if ($count && $this->canOpenPosition()) {
				$position =
					Position::create()->
					setCount($count)->
					setPrice($price)->
					setSecurity($this->security)->
					setType($orderType->getPositionType());

				Log::me()->add(
					__CLASS__.': open '.$position->getType()->getName().' position with price '
					.$position->getPrice().' and count '.$position->getCount().' ('.$position->getSecurity()->getId().')'
				);

				PositionManager::me()->manage($position);

				$invertor = $orderType->getInvertor();

				$stopLoss =
					StopLoss::create()->
					setSeriesCounter($this->getSeriesCounter())->
					setCount($position->getCount())->
					setPrice(
						Math::sub(
							$position->getPrice(),
							Math::multiply(
								Math::multiply($position->getPrice(), self::STOP_LOSS_PERCENT),
								$invertor
							)
						)
					)->
					setType($orderType->getInverted())->
					setSecurity($this->security);

				Log::me()->add(
					__CLASS__.': add '.$stopLoss->getType()->getName().' stop-loss with price '
					.$stopLoss->getPrice().' and count '.$stopLoss->getCount().' ('.$stopLoss->getSecurity()->getId().')'
				);

				$takeProfit =
					TakeProfit::create()->
					setSeriesCounter($this->getSeriesCounter())->
					setCount($position->getCount())->
					setIndent(self::TAKE_PROFIT_INDENT)->
					setIndentUnitsType(UnitsType::value())->
					setPrice(
						Math::add(
							$position->getPrice(),
							Math::multiply(
								Math::multiply($position->getPrice(), self::TAKE_PROFIT_PERCENT),
								$invertor
							)
						)
					)->
					setType($orderType->getInverted())->
					setSecurity($this->security);

				Log::me()->add(
					__CLASS__.': add '.$takeProfit->getType()->getName().' take-profit with price '
					.$takeProfit->getPrice().' and count '.$takeProfit->getCount().', indent is '.$takeProfit->getIndent().' '.$takeProfit->getIndentUnitsType()->getName().' ('.$takeProfit->getSecurity()->getId().')'
				);

				$orGroup =
					OrderOrGroup::create()->
					addStrategy($stopLoss)->
					addStrategy($takeProfit);

				$this->insertUp($orGroup);
			}

			return $this;
		}

		private function canOpenPosition()
		{
			return
				PositionManager::me()->getTotalCount($this->security) == 0;
		}
	}
?>