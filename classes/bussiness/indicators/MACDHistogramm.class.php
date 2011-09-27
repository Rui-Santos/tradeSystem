<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MACDHistogramm extends BaseIndicator
	{
		/**
		 * @var MACD
		 */
		private $MACD = null;

		/**
		 * @var MACDSignal
		 */
		private $MACDSignal = null;

		/**
		 * @return MACDHistogramm
		 */
		public static function create(MACD $MACD, MACDSignal $MACDSignal)
		{
			return new self($MACD, $MACDSignal);
		}

		public function __construct(MACD $MACD, MACDSignal $MACDSignal)
		{
			$this->MACD = $MACD;
			$this->MACDSignal = $MACDSignal;

			parent::__construct();
		}

		/**
		 * @return MACDHistogramm
		 */
		public function handle($value)
		{
			if (
				$this->hasValue()
				|| (
					$this->MACD->getValue()
					&& $this->MACDSignal->getValue()
				)
			) {
				$this->setValue(
					Math::sub(
						$this->MACD->getValue(),
						$this->MACDSignal->getValue()
					)
				);
			}

			return $this;
		}
	}
?>