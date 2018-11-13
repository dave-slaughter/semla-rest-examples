<?php
////////////////////////////////////////////////////
// Example of displaying SEMLA tables on another site
//
// Copyright (C) 2006-16 Dave Slaughter webmaster@southlacrosse.org.uk
//
// 2014-05-08 : change short open tags to be php open tags to be compatible
//     with defaults in php 5.3+ 
// 2015-11-12 : update to html5
// 2016-08-01 : remove checkBrowserCaching as this is better done,
//              with other methods, and in any case this is just a sample
//
// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
// 
// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
//
// See http://www.gnu.org/copyleft/lesser.html
////////////////////////////////////////////////////
require_once('httpCache.inc');

// By changing the next 2 variables you can embed different data in your page
// e.g. fixtures, club info, specific tables etc. 

// local file name to store data. 
$localFile = 'mens-league-tables.html';
// URL of data on South Lacrosse site. Make sure to add the .html suffix to get HTML
//  formatted data
$url = 'http://www.southlacrosse.org.uk/rest/tables/mens.html';

// get the data from the South Lacrosse server, caching results for 2 hours
$httpCache = new httpCache($url,$localFile);
$httpCache->fetch();

// output page
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SEMLA Tables example using PHP</title>
<style>
table {
 	text-align: center;
 	width: 100%;
 	border-collapse: collapse;
}
th, td {
	padding: 5px 0.75em;
	vertical-align: top;
	border: 1px solid #000;
}
th {
	background-color: #eee;
}
</style>
</head>
<body>
<p><a href="index.html">Back to Examples page</a></p>
<?php
if ($httpCache->isError()) {
	echo '<p style="color: red;"><b>Error retrieving data : ' . $httpCache->getError()
		. "</b></p>\n"; 
} else {
	$httpCache->printContents();
}
?>
</body>
</html>
