Geolocator
==========
About
-----
Package to determine the address or GPS coordinates of location.

HTTP GET requests to Google Maps or Yahoo Finder APIs to either return the geographic coordinates of a given address or determine the address of given geographic coordinates.

You can select which one service is fine for your gps tagging - Google vs. Yahoo API. Yahoo has very good APIs and it should be sad forget on them, when everybody speaks only about google stuffs.

Requirements
------------
PHP 5.2 and higher.

Initialize
----------
<pre>
//required class
require('GeoLocator.class.php');
//yahoo geo search
require('YahooGeoLocator.class.php');
//OR
//google geo search
require('GoogleGeoLocator.class.php');

//initialize objects
$yahooApiKey = '';
$service = new YahooGeoLocator($yahooApiKey);
//OR
$googleApiKey= '';
$lang = 'en';
$service = new GoogleGeoLocator($googleApiKey,$lang);
</pre>

Using
-----
Search lat, long by address:
<pre>
$service->searchByAddress('Strojnická 12','Praha','Czech republic');
</pre>
Search address by lat, long:
<pre>
$service->searchByLocation('50.073274','14.392619');
</pre>

Version
-------
2.1

Copyright
---------
2010-2014

License
-------
GPL