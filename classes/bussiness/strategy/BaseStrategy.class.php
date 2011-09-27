<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseStrategy extends \ewgraFramework\Observable implements Strategy
	{
		/**
		 * @return Strategy
		 */
		private $inner = null;

		/**
		 * @return Strategy
		 */
		private $outer = null;

		/**
		 * @return Strategy
		 */
		public function __construct(Strategy $strategy = null)
		{
			if ($strategy)
				$this->setInner($strategy);
		}

		/**
		 * @return Strategy
		 */
		public function handle($value)
		{
			return
				$this->hasInner()
					? $this->getInner()->handle($value)
					: $this;
		}

		/**
		 * @return Strategy
		 */
		public function handleBar(Bar $bar)
		{
			return $this->handle($bar->getClose());
		}

		/**
		 * @return Strategy
		 */
		public function setInner(Strategy $strategy)
		{
			$this->inner = $strategy;

			if ($strategy->getOuter() != $this)
				$strategy->setOuter($this);

			return $this;
		}

		/**
		 * @return Strategy
		 */
		public function dropInner()
		{
			$this->inner = null;
			return $this;
		}

		/**
		 * @return Strategy
		 */
		public function getInner()
		{
			return $this->inner;
		}

		public function hasInner()
		{
			return !is_null($this->inner);
		}

		/**
		 * @return Strategy
		 */
		public function setOuter(Strategy $strategy)
		{
			$this->outer = $strategy;

			if ($strategy->getInner() != $this)
				$strategy->setInner($this);

			return $this;
		}

		/**
		 * @return Strategy
		 */
		public function getOuter()
		{
			return $this->outer;
		}

		public function hasOuter()
		{
			return !is_null($this->outer);
		}

		/**
		 * @return Strategy
		 */
		public function getFirstStrategy()
		{
			if (!$this->getOuter())
				return $this;

			return $this->getOuter()->getFirstStrategy();
		}

		/**
		 * @return Strategy
		 */
		public function getLastStrategy()
		{
			if (!$this->getInner())
				return $this;

			return $this->getInner()->getLastStrategy();
		}

		/**
		 * @return Strategy
		 */
		public function drop()
		{
			if ($this->hasInner())
				$this->getOuter()->setInner($this->getInner());
			else if ($this->getOuter())
				$this->getOuter()->dropInner();

			return $this;
		}

		/**
		 * @return Strategy
		 */
		public function insert(Strategy $strategy)
		{
			if ($this->hasInner()) {
				$strategy->getLastStrategy()->setInner($this->getInner());
			}

			$this->setInner($strategy);
			return $this;
		}
	}
?>