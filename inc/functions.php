<?php
/*
Copyright (C) 2010 Craig A Rodway.

This file is part of Site Jobs.

Site Jobs is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Site Jobs is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Site Jobs.  If not, see <http://www.gnu.org/licenses/>.
*/




/**
 * Authenticate a user and token pair in GET/POST params
 */
function auth(){
	
	// Get variables passed to us
	$username = fRequest::get('username', 'string');
	$user_token = fRequest::get('token', 'string');
	
	// Data we can find out and shouldn't change
	$secret = 'RRN4G11KTA1';
	$ua = $_SERVER['HTTP_USER_AGENT'];
	
	// Generate what we think the token *should* be
	$server_token = sha1(sprintf("%s_%s_%s", $username, $secret, $ua));
	
	// Compare our token with the one generated at the other end, and exit if they don't match
	if($server_token != $user_token){
		$json['status'] = 'err';
		$json['text'] = 'Authentication failed';
		out($json);		
	}
	
}




/**
 * Send output as JSON with correct headers
 */
function out($json){
	fJSON::sendHeader();
	if(!is_string($json)){
		echo fJSON::encode($json);
	} else {
		echo $json;
	}
	exit;
}




/**
 * Calculate timestamp between two times
 */
function timespan($seconds = 1, $time = ''){
	if ( ! is_numeric($seconds)){
		$seconds = 1;
	}

	if ( ! is_numeric($time)){
		$time = time();
	}

	if ($time <= $seconds){
		$seconds = 1;
	} else {
		$seconds = $time - $seconds;
	}

	#$str = '';
	$years = floor($seconds / 31536000);

	$seconds -= $years * 31536000;
	#$months = floor($seconds / 2628000);
	
	$months = NULL;

	if ($years > 0 OR $months > 0){
		$seconds -= $months * 2628000;
	}

	#$weeks = floor($seconds / 604800);

	if ($years > 0 OR $months > 0 OR $weeks > 0){
		$seconds -= $weeks * 604800;
	}

	$days = floor($seconds / 86400);

	if ($months > 0 OR $weeks > 0 OR $days > 0){
		$seconds -= $days * 86400;
	}

	$hours = floor($seconds / 3600);

	if ($days > 0 OR $hours > 0){
		$seconds -= $hours * 3600;
	}

	$minutes = floor($seconds / 60);

	if ($days > 0 OR $hours > 0 OR $minutes > 0){
		$seconds -= $minutes * 60;
	}


	$times['years'] = $years;
	$times['months'] = $months;
	$times['weeks'] = $weeks;
	$times['days'] = $days;
	$times['hours'] = $hours;
	$times['minutes'] = $minutes;
	$times['seconds'] = $seconds;

	$time = NULL;
	foreach($times as $measure => $value){
		if(is_array($time)){ continue; }
		if($value > 0){
			$time = array('value' => $value, 'measure' => $measure);
		}
	}

	if($time['value'] == 1){ $time['measure'] = preg_replace('/s$/', '', $time['measure']); }

	return $time;

}




/**
 * Get a list of all job creators
 */
function lookup_creators(){
	
	global $db;
	$creators = array();
	$sql = 'SELECT DISTINCT(creator) FROM jobs';
	$creators_rs = $db->query($sql)->FetchAllRows();
	foreach($creators_rs as $creator){
		$creators[] = $creator['creator'];
	}
	return $creators;
	
}




/**
 * Word limiter (borrowed from CodeIgniter)
 */
function word_limiter($str, $limit = 100, $end_char = '&#8230;'){
	
	if (trim($str) == ''){
		return $str;
	}

	preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

	if (strlen($str) == strlen($matches[0])){
		$end_char = '';
	}
	
	return rtrim($matches[0]).$end_char;
	
}