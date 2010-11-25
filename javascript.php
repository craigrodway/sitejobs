<?php
/**
 * Return javascript
 */

include_once('inc/init.php');

$compress = FALSE;
$jspath = DOC_ROOT . '/web/js/';

//$js[] = 'sha1.js';
$js[] = 'jquery-1.4.2.js';
$js[] = 'jquery.bar.custom.js';
$js[] = 'jqModal.js';
$js[] = 'sammy-0.6.2.js';
$js[] = 'sammy.template.js';
$js[] = 'sammy.title.js';
$js[] = 'app.js';

header("Content-Type: application/x-javascript");

foreach($js as $filename){
	$realpath = realpath($jspath . $filename);
	$file = new fFile($realpath);
	$contents = $file->read();
	echo "/** File: $filename **/\n$contents\n";
}