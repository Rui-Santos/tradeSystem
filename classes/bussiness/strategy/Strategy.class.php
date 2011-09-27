<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface Strategy extends \ewgraFramework\ObservableInterface
	{
		/**
		 * @return Strategy
		 */
		public function __construct(Strategy $strategy = null);

		/**
		 * @return Strategy
		 */
		public function handleBar(Bar $bar);

		/**
		 * @return Strategy
		 */
		public function setInner(Strategy $strategy);

		/**
		 * @return Strategy
		 */
		public function getInner();

		public function hasInner();

		/**
		 * @return Strategy
		 */
		public function dropInner();

		/**
		 * @return Strategy
		 */
		public function setOuter(Strategy $strategy);

		/**
		 * @return Strategy
		 */
		public function getOuter();

		public function hasOuter();

		/**
		 * @return Strategy
		 */
		public function getFirstStrategy();

		/**
		 * @return Strategy
		 */
		public function getLastStrategy();

		/**
		 * @return Strategy
		 */
		public function insert(Strategy $strategy);

		/**
		 * @return Strategy
		 */
		public function drop();
	}
?>