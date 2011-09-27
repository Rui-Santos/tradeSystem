<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Security
	{
		private $id = null;

		/**
		 * @return Security
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return Security
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}
	}
?>