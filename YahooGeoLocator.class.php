<?php
/*
* Geo Locator
* YahooGeoLocator.class.php
*
* About class: Class for parsing data from payed Yahoo BOSS Geo Service.
* More info about Yahoo APIs on http://developer.yahoo.com/boss/geo
*
* @Version:		1.2
* @Release:		2010-12-22
* @Author:		Ondrej Podolinsky aka podolinek
* @Contact:		podolinek@gmail.com
*
* Copyright (c) 2010-2013, podolinek
* This class is under GPL Licencense Agreement.
*
*/
class YahooGeoLocator extends GeoLocator {
	const serviceAddress = 'http://where.yahooapis.com/geocode?location=%s&flags=J';//address for calling service

	/**
	* Initial set of base variables in class
	*
	* @param string $apiKey - api key for Yahoo API
	*/
	public function __construct($apiKey = null){
		parent::__construct($apiKey);
	}

	/**
	* Search by address/coords in concrete service
	*
	* @param string $paramVal
	* @param string $paramName
	* @return array - array with results. $results['error'] if any error.
	*/
	protected function searchByParam($paramVal,$paramName) {
		$url = sprintf(self::serviceAddress,$paramVal);

		if(!is_null($this->apiKey))
			$url .= '&appid=' . $this->apiKey;

		$url .= ($paramName == 'latlng') ? '&gflags=R' : '';
        
        $file = $this->getUrl($url);
		$doc = unserialize($file);//service returns data in serialized format

		$errNo = (int)$doc['ResultSet']['Error'];
		$errMsg = $doc['ResultSet']['ErrorMessage'];
		if($errNo == 0){
			$results['count'] = $doc['ResultSet']['Found'];
			foreach($doc['ResultSet']['Result'] AS $res) {
				$results['results'][] = $this->parseResults($res);
			}
		} else {
			$results['error'] = $errMsg;
		}
		return $results;
	}

	/**
	* Extract address + gps from document
	*
	* @param array $arr - one address element from service
	* @return array - array with result.
	*/
	protected function parseResults($arr) {
		$result['lat'] = (float)$arr['latitude'];
		$result['long'] = (float)$arr['longitude'];
		$result['street'] = $arr['street']. ' '.$arr['house'];
		$result['city'] = $arr['city'];
		$result['country'] = $arr['country'];
		$result['zip'] = $arr['uzip'];
		return $result;
	}
}
?>