<?php
/*
* Geo Locator
* GoogleGeoLocator.class.php
*
* About class: Class for parsing data from google geo service. I think google
* has the best geo database of all, up to 100 000 request per day.
* More info about google maps api: https://developers.google.com/maps/documentation/geocoding/?hl=en
*
* @Version:	1.1
* @Release:	2010-12-22
* @Author:	Ondrej Podolinsky aka podolinek
* @Contact:	podolinek@gmail.com
*
* Copyright (c) 2010-2013, podolinek
* This class is under GPL Licencense Agreement.
*
*/
class GoogleGeoLocator extends GeoLocator {
	const serviceAddress = 'http://maps.google.com/maps/api/geocode/json?%s=%s&sensor=false&language=%s';//address for calling service
	protected $language;//language for results from google

	/**
	* Initial set of base variables in class
	*
	* @param string $lang - langugage for results from google
	* @param string $apiKey - api key for Google API
	*/
	public function __construct($apiKey = null,$lang = 'en') {
		parent::__construct($apiKey);
		$this->language = $lang;
	}

	/**
	* Search by address/coords in concrete service
	*
	* @param string $paramVal
	* @param string $paramName
	* @return array - array with results. $results['error'] if any error.
	*/
	protected function searchByParam($paramVal, $paramName) {
		$url = sprintf(self::serviceAddress, $paramName, $paramVal, $this->language);
		if(!is_null($this->apiKey))
			$url .= '&client=' . $this->apiKey;

        $file = $this->getUrl($url);
		$doc = json_decode($file);//data gets in json format

		$status = (string)$doc->status;

		if($status == 'OK') {
			$i = 0;
			$street = $route = $locality = false;
			foreach($doc->results as $res) {//search for density in result
				if($res->types[0] == 'street_address')
					$street = true;
			    elseif($res->types[0] == 'route')
					$route = true;
			    elseif($res->types[0] == 'locality')
					$locality = true;
			}

			if($street)
				$route = $locality = false;
			elseif($route)
				$street = $locality = false;
			elseif($locality)
				$street = $route = false;

			foreach($doc->results AS $res) {
				if($res->types[0] == 'street_address' && $street || $res->types[0] == 'route' && $route || $res->types[0] == 'locality' && $locality) {
					$results['results'][] = $this->parseResults($res);
					$i++;
				}
			}
			$results['count'] = $i;

		} else
			$results['error'] = $status;
		return $results;
	}
	
	/**
	* Extract address + gps from document
	*
	* @param array $arr - one address element from service
	* @return array - array with result
	*/
	protected function parseResults($arr) {
		foreach($arr->address_components AS $c) {
			if(count($c->types) > 0 && (string)$c->types[0] == 'street_number')
				$house = $c->long_name;
			elseif(count($c->types) == 0)
				$tmpHouse = $c->long_name;
			elseif((string)$c->types[0] == 'route')
				$street = $c->long_name;
			elseif((string)$c->types[0] == 'locality')
				$result['city'] = $c->long_name;
			elseif((string)$c->types[0] == 'country')
				$result['country'] = $c->long_name;
			elseif((string)$c->types[0] == 'postal_code')
				$result['zip'] = $c->long_name;
		}
		$house = (!isset($house) && isset($tmpHouse)) ? $tmpHouse : '';
		
		$result['lat'] = (float)$arr->geometry->location->lat;
		$result['long'] = (float)$arr->geometry->location->lng;
		$result['street'] = $street. ' '.$house;
		$result['street'] = trim($result['street']);
		return $result;
	}
}
?>