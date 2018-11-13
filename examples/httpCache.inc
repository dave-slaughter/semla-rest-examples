<?php
////////////////////////////////////////////////////
// HTTP caching support - for browser and server
// works in PHP 5.3+ with cURL installed
//
// httpCache - cache http requests from another server
//
// Copyright (C) 2006-16 Dave Slaughter webmaster@southlacrosse.org.uk
//
// 2016-08-01 : change to deprecated constructor
//			  : remove checkBrowserCaching as this is better done,
//              with other methods, and any case this is just a sample 
// 2018-11-12 : tidy up cache directory settings
//			  : remove checkBrowserCaching as this is better done,
//              with other methods, and any case this is just a sample
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

/**
 * Cache an HTTP request for remote data
 */
class httpCache {
	// URL we want to retrieve
	var $url;
	
	// local filename we want to cache the request in
	var $destFile;
	
	// directory to cache files in. set here so it's easy to change
	var $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . '.cache';
	
	// info about the cached file
	// The time we last asked the remote server for the http file is stored in a
	// seperate $destCacheInfoFile file. The modified date on $destFile isn't used
	// as then the 304 processing in cacheHeaders wouldn't work correctly.
	//
	// $destCacheInfoFile accessTime is set to the remote server time, so when
	//   we talk to the remote server we can set the If-Modified-Since header correctly,
	//   which enables the server to only send the file if it has changed.
	// $destCacheInfoFile modifiedTime is set to local time so we can check if the
	//   cache is stale
	var $destCacheInfoFile;
	
	// minimum timeout between requests to server
	var $timeout;
	var $contents = null;
	var $error;
	
	/**
	 * @param string $url URL to retrieve
	 * @param string $destFile local filename to store the file
	 * @param int $timeout timeout between requests, default 2 hours
	 */
	function __construct($url, $destFile, $timeout=7200) {
		$this->url = $url;
		$this->destFile = $this->cacheDir . DIRECTORY_SEPARATOR . $destFile;
		$this->destCacheInfoFile = $this->destFile . '.cacheinfo';
		$this->timeout = $timeout;
	}
	function delete() {
		unlink($this->destFile);
		unlink($this->destCacheInfoFile);
	}
	/** Fetch the file from the remote server and cache it locally. If the timeout
	 * period hasn't been reached then the local copy will be used if possible.	*/
	function fetch() {
		unset($this->error);
		if (file_exists($this->destFile) && file_exists($this->destCacheInfoFile)) {
			$lastChecked = filemtime($this->destCacheInfoFile);
		} else {
			$lastChecked = 0;
		}
		if($lastChecked < (time() - $this->timeout)) {
			// Initialize the session
			$session = curl_init($this->url);
	
			// Set curl options
			// return response as string and not send to output
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			// attempt to retrieve the modification date
			curl_setopt($session, CURLOPT_FILETIME, true);
			// send the If-Modified-Since header with the last accessed time
			if ($lastChecked != 0) {
				$fileModGMT = gmdate('D, d M Y H:i:s', fileatime($this->destCacheInfoFile)).' GMT';
				curl_setopt($session, CURLOPT_HTTPHEADER, array("If-Modified-Since: $fileModGMT"));
			}
			curl_setopt($session, CURLOPT_USERAGENT, "httpCache/1.0");
				
			// Make the request
			$this->contents = curl_exec($session);
			if ($this->contents === false) {
				$this->error = 'Curl error: ' . curl_error($session);
				curl_close($session);
				return;
			}
			$statusCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
			$remoteDate = curl_getinfo($session, CURLINFO_FILETIME);
				
			// Close the curl session
			curl_close($session);
	
			// Check the HTTP Status code
			switch( $statusCode ) {
				case 200:
					// Success
					break;
				case 304:
					// file on server was not modified - so update the cacheinfo file with the current date
					// from the server (otherwise on the next view of this page we will try to
					// fetch it again). Since we don't set the $contents, calls to getContents will return
					// the contents from the cached file 
					$this->_setCacheInfoDate($remoteDate);
					return;
				default:
					$this->error =  'Retrieving data from '.$this->url.' failed, and returned an unexpected HTTP status of: ' . $statusCode;
					return;
			}		
			$tmpf = tempnam('/tmp','SLRS');
			$fp = fopen($tmpf,'w');
			fwrite($fp, $this->contents);
			fclose($fp);
			if (!file_exists($this->cacheDir)) {
				mkdir($this->cacheDir, 0755, true);
			}
			rename($tmpf, $this->destFile);
			$this->_setCacheInfoDate($remoteDate);
		}
	}
	
	/** Return the actual file contents as a string */
	function getContents() {
		// get from string in memory if possible, otherwise from cached file
		if ($this->contents) {
			return $this->contents;
		}
		return file_get_contents($this->destFile);
	}
	/** print the current contents */
	function printContents() {
		// print from string in memory if possible, otherwise print from cached file
		if ($this->contents) {
			echo $this->contents;
		} else {
			readfile($this->destFile);
		}
	}
	function isError() {
		return isset($this->error);
	}
	function getError() {
		return $this->error;
	}
	// internal function to update modified/accessed date on cacheinfo file.
	function _setCacheInfoDate($remoteDate) {
		if ($remoteDate != -1) {
			touch($this->destCacheInfoFile, time(), $remoteDate);
		} else {
			touch($this->destCacheInfoFile);
		}
	}
} // end of class HttpCache
