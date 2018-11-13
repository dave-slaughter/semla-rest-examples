<?php
////////////////////////////////////////////////////
// Example of displaying SEMLA fixtures on another site using XML data
//
// Copyright (C) 2011-16 Dave Slaughter webmaster@southlacrosse.org.uk
//
// 2014-05-08 : change short open tags to be php open tags to be compatible
//     with defaults in php 5.3+ 
// 2015-11-12 : update to html5
// 2016-08-01 : remove checkBrowserCaching as this is better done,
//              with other methods, and any case this is just a sample 
//              remove web links as that feature is deprecated
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

// local file name to store data
$localFile = 'mens-fixtures-purley.xml';
// URL of data on South Lacrosse site
$url = 'http://www.southlacrosse.org.uk/rest/fixtures/mens/Purley';

// get the data from the South Lacrosse server, caching results for 2 hours
$httpCache = new httpCache($url,$localFile);
$httpCache->fetch();

// output page heading
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SEMLA Fixtures example using PHP</title>
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
	// parse XML data into more useable SimpleXMLElement objects
	libxml_use_internal_errors(true);
	$xml = simplexml_load_string($httpCache->getContents());
	if (!$xml) {
		// output parsing errors
		echo '<p style="color: red;"><b>Error parsing XML data :';
	    foreach(libxml_get_errors() as $error) {
	        echo '<br/> ', $error->message;
	    }
	    echo "</b></p>\n"; 
	} else {
?>
<table>
<thead>
<tr>
<th>Date</th>
<th>Home</th>
<th/>
<th/>
<th/>
<th>Away</th>
<th>Competition</th>
</tr>
</thead>
<tbody>
<?php
	// Loop thru all the fixtures in the XML document
	foreach ($xml->fixture as $fixture) {
		$date = date('j M y',strtotime($fixture->date));
		// note: competition is wrapped in htmlspecialchars() as it
		// may contain &
?>
<tr>
<td><?php echo $date; ?></td>
<td><?php if (isset($fixture->{'home-team'})) echo $fixture->{'home-team'}; ?></td>
<td><?php if (isset($fixture->{'home-goals'})) echo $fixture->{'home-goals'}; ?></td>
<td><?php if (isset($fixture->{'home-team'}) || isset($fixture->{'away-team'})) echo 'v'; ?></td>
<td><?php if (isset($fixture->{'away-goals'})) echo $fixture->{'away-goals'}; ?></td>
<td><?php if (isset($fixture->{'away-team'})) echo $fixture->{'away-team'}; ?></td>
<td><?php if (isset($fixture->competition)) echo htmlspecialchars($fixture->competition); ?></td>
</tr>
<?php	} // end of fixtures loop ?>
</tbody>
</table>
<?php
	} // end of else 
} // end of else

// finish off page
?>
<p><small>Data provided by <a href="http://www.southlacrosse.org.uk/">southlacrosse.org.uk</a></small></p>
</body>
</html>
