<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @link http://www.finam.ru/analysis/export/default.asp
	*/
	final class FinamBarReader
	{
		private $fileName = null;

		private $handle = null;

		private $row = 0;

		private $skipRow = 0;

		/**
		 * @return FinamBarReader
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return FinamBarReader
		 */
		public function setFileName($fileName)
		{
			$this->fileName = $fileName;
			return $this;
		}

		/**
		 * @return FinamBarReader
		 */
		public function setSkipRow($skipRow)
		{
			$this->skipRow = $skipRow;
			return $this;
		}

		/**
		 * @return FinamBarReader
		 */
		public function skipHead()
		{
			$this->setSkipRow(1);
			$this->row = -1;
			return $this;
		}

		public function getRow()
		{
			return $this->row;
		}

		/**
		 * @return Bar
		 */
		public function getNext()
		{
			$result = null;

			if (!$this->handle) {
				$this->handle = fopen($this->fileName, "r");

				if (!$this->handle)
					throw new \Exception();
			}

			while ($this->skipRow) {
				$this->skipRow--;
				$this->row++;
				fgetcsv($this->handle, 1000, ",");
			}

			if (($data = fgetcsv($this->handle, 1000, ",")) !== false) {
				$this->row++;

				$result =
					Bar::create()->
					setOpen($data[4])->
					setHigh($data[5])->
					setLow($data[6])->
					setClose($data[7])->
					setDateTime(
						\ewgraFramework\DateTime::create($data[2].' '.$data[3])
					);
			}

			return $result;
		}
	}
?>