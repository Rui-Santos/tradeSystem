<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MACDSignal extends BaseIndicator
	{
		/**
		 * @var EMA
		 */
		private $EMA = null;

		/**
		 * @var MACD
		 */
		private $MACD = null;

		/**
		 * @var MACDSignal
		 */
		public static function create(MACD $MACD, $EMAPeriod = 9)
		{
			return new self($MACD, $EMAPeriod);
		}

		public function __construct(MACD $MACD, $EMAPeriod)
		{
			$this->EMA = EMA::create($EMAPeriod);
			$this->MACD = $MACD;

			parent::__construct();
		}

		public function getEMAPeriod()
		{
			return $this->EMA->getPeriod();
		}

		public function getValue()
		{
			return $this->EMA->getValue();
		}

		public function hasValue()
		{
			return $this->EMA->hasValue();
		}

		/**
		 * @return MACDSignal
		 */
		public function handle($value)
		{
			if ($this->MACD->hasValue())
				$this->EMA->handle($this->MACD->getValue());

			return parent::handle($value);
		}
	}
?>