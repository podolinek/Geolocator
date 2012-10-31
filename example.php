<?php
/*
* Geo Locator
*
* You can select which one service is fine for your gps tagging - Google vs.
* Yahoo API. Yahoo has very good APIs and it should be sad forget on them,
* when everybody speaks only about google stuffs.
*
* @Version:		2.0
* @Release:		2010-12-22
* @Author:		Ondrej Podolinsky aka podolinek
* @Contact:		podolinek@gmail.com
*
* Copyright (c) 2010, podolinek
* This class is under MIT Licencense Agreement.
*/
require('GeoLocator.class.php');
require('YahooGeoLocator.class.php');
require('GoogleGeoLocator.class.php');


$yahooApiKey = '';
$googleApiKey= '';
$lang = 'en';

$yahoo = new YahooGeoLocator($yahooApiKey);
$google = new GoogleGeoLocator($googleApiKey,$lang);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>examples for geo locating</title>
</head>
<body>

<p><strong>Search by Yahoo PlaceFinder API service:</strong></p>
<pre>Search by address:
Call:
$yahoo->searchByAddress('Hole&#269;kova 32','Praha','Czech republic')
Return:
<?php
print_r($yahoo->searchByAddress('Hole&#269;kova 32','Praha','Czech republic'));
?>
</pre>

<pre>Search by location:
Call:
$yahoo->searchByLocation('50.073274','14.392619')
Return:
<?php
print_r($yahoo->searchByLocation('50.073274','14.392619'));
?>
</pre>

<p><strong>Search by Google Maps API service:</strong></p>
<pre>Search by address:
Call:
$google->searchByAddress('Hole&#269;kova 32','Praha','Czech republic')
Return:
<?php
print_r($google->searchByAddress('Hole&#269;kova 32','Praha','Czech republic'));
?>
</pre>

<pre>Search by location:
Call:
$google->searchByLocation('50.073274','14.392619')
Return:
<?php
print_r($google->searchByLocation('50.073274','14.392619'));

?>
</pre>
</body>
</html>