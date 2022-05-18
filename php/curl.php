<?php

class curl_site {
	function cURL_this($url, $timeout=5) {
		$this->read_cURL = curl_init();

		curl_setopt_array($this->read_cURL, array( CURLOPT_RETURNTRANSFER => true,
                                                   CURLOPT_URL            => $url,
                                                   CURLOPT_CONNECTTIMEOUT => $timeout,
                                                   CURLOPT_TIMEOUT        => $timeout,
                                                   CURLOPT_USERAGENT      => 'tableoutput curl class.'
                                                   ));
        $ret_cURL = curl_exec($this->read_cURL);

		return $ret_cURL;

	}
}
