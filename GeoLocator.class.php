<?php
/*
* Geo Locator
* GeoLocator.class.php
* About class: abstract class with defined all methods used in extended classes for gps services.
*
* @Version:	1.1
* @Release:	2010-12-22
* @Author:	Ondrej Podolinsky aka podolinek
* @Contact:	podolinek@gmail.com
*
* Copyright (c) 2010, podolinek
* This class is under GPL Licencense Agreement.
*
* I will be pleased for any feedback.)
*
*/
abstract class GeoLocator{
	protected $apiKey;//api key for services, if it is available

	/**
	* Initial set of base variables in class
	*
	* @param string $apiKey - api key for google
	*/
	function __construct($apiKey = null) {
		if(strlen($apiKey) > 0)
			$this->apiKey = $apiKey;
	}

	/**
	* Return the nearest location around gps pos on lat x long
	*
	* @param float $latitude
	* @param float $longitude
	* @return array - result of search
	*/
	public function searchByLocation($latitude,$longitude) {
		$coord = urlencode("$latitude,$longitude");
		$results = $this->searchByParam($coord,'latlng');
		return $results;
	}
	
	/**
	* Return the nearest location around address
	*
	* @param string $street
	* @param string $city
	* @param string $country
	* @return array - result of search
	*/
	public function searchByAddress($street,$city=null,$country=null) {
		$address = $street;
		if(!is_null($city))
			$address .= ', '.$city;
		if(!is_null($country))
			$address .= ', '.$country;
		$address = urlencode($address);
		$results = $this->searchByParam($address,'address');
		return $results;
	}

    /**
     * Curl get page
     *
     * @param string $url
     * @return strint - page returned from $url
     */
    protected function getUrl($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $content = curl_exec ($ch);

        if (curl_error($ch)) {
          throw new Exception(curl_errno($ch) . ": " . curl_error($ch));
        }

        curl_close ($ch);

        return $content;
    }
    
	/**
	* Search by address/coords in concrete service
	*
	* @param string $paramVal
	* @param string $paramName
	* @return array - array with results. $results['error'] if any error.
	*/
	abstract protected function searchByParam($paramVal,$paramName);

	/**
	* Extract address + gps from document
	*
	* @param array $arr - one address element from service
	* @return array - array with result.
	*/
	abstract protected function parseResults($arr);
}
?>