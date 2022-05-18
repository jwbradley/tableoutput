<?php 

class getContents {

	public function __construct($url, $include_path = null, $context=null, $offset=0, $maxlength=5000000, $timeout=5) {
		$this->url          = $url;
		$this->include_path = $include_path;
		$this->context      = $context;
		$this->offset       = $offset;
		$this->maxlength    = $maxlength;
		$this->timeout      = $timeout;
		// $this->getFileContents();
	}


	function getFileContents() {

		return file_get_contents($this->url, $this->include_path, $this->context, $this->offset, $this->maxlength);
		// return file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=TSLA&interval=5min&apikey=KGOFSXPNMA7WZL1V');

	}
}

?>