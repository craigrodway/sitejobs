<?php
class Job extends fActiveRecord{
	
	
	
	// Return an iterable set of User objects
	public static function findNew() {
		$rs = fRecordSet::build('Job', array('status=' => 'new'), array('created' => 'desc'));
		foreach($rs as &$r){
			echo $r;
		}
	}


}