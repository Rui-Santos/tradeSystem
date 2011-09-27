<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MACD extends BaseIndicator
	{
		/**
		 * @var EMA
		 */
		private $shortEMA	= null;

		/**
		 * @var EMA
		 */
		private $longEMA	= null;

		/**
		 * @return MACD
		 */
		public static function create(EMA $shortEMA, EMA $longEMA)
		{
			return new self($shortEMA, $longEMA);
		}

		public function __construct(EMA $shortEMA, EMA $longEMA)
		{
			$this->shortEMA = $shortEMA;
			$this->longEMA = $longEMA;

			parent::__construct();
		}

		/**
		 * @return MACD
		 */
		public function handle($value)
		{
			if (
				$this->hasValue()
				|| (
					$this->shortEMA->hasValue()
					&& $this->longEMA->hasValue()
				)
			) {
				$this->setValue(
					Math::sub(
						$this->shortEMA->getValue(),
						$this->longEMA->getValue()
					)
				);
			}

			return $this;
		}
	}
?>