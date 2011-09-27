<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EMA extends BaseIndicator
	{
		private $period = null;

		/**
		 * @var SMA
		 */
		private $SMA = null;

		/**
		 * @return EMA
		 */
		public static function create($period)
		{
			return new self($period);
		}

		public function __construct($period)
		{
			$this->period = $period;
			$this->SMA = SMA::create($period);
		}

		public function getPeriod()
		{
			return $this->period;
		}

		/**
		 * @return EMA
		 */
		public function handle($value)
		{
			if (!$this->hasValue()) {
				$this->SMA->handle($value);

				if ($this->SMA->hasValue())
					$this->setValue($this->SMA->getValue());
			} else {
				$this->setValue(
					Math::add(
						$this->getValue(),
						Math::multiply(
							Math::div(2, $this->period+1),
							Math::sub($value, $this->getValue())
						)
					)
				);
			}

			return $this;
		}
	}
?>