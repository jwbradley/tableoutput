<?php 

class getContents {

	public function __construct($url, $include_path = null, $context=null, $offset=0, $maxlength=5000000, $timeout=5) {
		$this->url          = $url;
		$this->include_path = $include_path;
		$this->context      = $context;
		$this->offset       = $offset;
		$this->maxlength    = $maxlength;
		$this->timeout      = $timeout;
		
	}


	function getFileContents() {

		return file_get_contents($this->url, $this->include_path, $this->context, $this->offset, $this->maxlength);
		
	}
}

?>
