<?php 

class isAvailable {
	function is_available($url, $timeout) {
		$ch = curl_init();                         // get cURL handle

		$opts = array(CURLOPT_RETURNTRANSFER => true,    // do not output to browser
			          CURLOPT_URL => $url,               // set URL
			          CURLOPT_HEADER => true,            // make sure we get the header
			          CURLOPT_NOBODY => true,            // do a HEAD request only
			          CURLOPT_TIMEOUT => $timeout);      // set timeout
		curl_setopt_array($ch, $opts);

		$ret = curl_exec($ch);

		if (empty($ret)) {
			// some kind of an error happened
			$results = 'Timed Out';
			if (is_null($ret)) {
				return 'GotNULL';
			}
			curl_close($ch);                       // close cURL handler
			return $results;

		} else {
			$info = curl_getinfo($ch);
			curl_close($ch);                       // close cURL handler

			if (empty($info['http_code'])) {

				// die("No HTTP code was returned");

				return 'No Response';
			} else {
				// load the HTTP codes
				$http_codes = parse_ini_file("./httpCodes.ini");

				// echo results
				return $http_codes[$info['http_code']];

			}

		}
	}
}

?>