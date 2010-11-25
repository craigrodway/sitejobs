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
along with Print Master.  If not, see <http://www.gnu.org/licenses/>.
*/


// Include initialisation file
include_once('inc/init.php');

// Get params
//
// Action: [get|create|update]
// Status: [new|open|closed]
// ID: job ID
// Owner: username
// Creator: username

$action		= fRequest::getValid('action', array(NULL, 'get', 'create', 'update'));
$status		= fRequest::getValid('status', array(FALSE, 'new', 'open', 'closed'));
$id			= fRequest::get('id', 'integer?');
$owner		= fRequest::get('owner', 'string?');
$creator	= fRequest::get('creator', 'string?');

// If a job isn't been created, check for authentication
if($action != 'create'){ auth(); }




/** 
 * Get list of all jobs with supplied status
 */
if($action == 'get'){
	
	if(!$id){
	
		// No ID - get all
		
		if(!$status){
			$json['status'] = 'err';
			$json['text'] = "No filter supplied. ($action)";
			out($json);
		}
		
		// Add search parameters if present
		if($status){ $search['status='] = $status; }
		if($owner){ $search['owner='] = $owner; }
		if($creator){ $search['creator='] = $creator; }
		
		try {
			
			$jobs_rs = fRecordSet::build('Job', $search, array('created' => 'desc'));
			$jobs = $jobs_rs->toArray();
			foreach($jobs as &$job){
				$job['age'] = timespan(strtotime($job['created']), time());
				$row['created_'] = date("l j F Y, H:i", strtotime($job['created']));
			}
			
			$json['status'] = 'ok';
			$json['jobs'] = $jobs;
			out($json);
			
		} catch(fException $e) {
			
			$json['status'] = 'err';
			$json['text'] = $e->getMessage();
			out($json);
			
		}
		
	} else {
		
		// Got ID - get that job.
		
		try{
			
			$job = new Job($id);
			$job = $job->toArray();
			$job['age'] = timespan(strtotime($job['created']), time());
			$job['created_'] = date("l j F Y, H:i", strtotime($job['created']));
			
			$json['status'] = 'ok';
			$json['job'] = $job;
			out($json);
			
		} catch(fException $e) {
		
			$json['status'] = 'err';
			$json['text'] = $e->getMessage();
			out($json);
			
		}
		
	}		// End of $id (TRUE) check
	
}		// End of fRequest action == 'get'




/**
 * Create a job
 */
if($action == 'create'){

	// Get required parameters
	$creator = fRequest::get('creator', 'string');
	$room = fRequest::get('room', 'string');
	$computer = fRequest::get('computer', 'string');
	$type = fRequest::get('type', 'string');
	$first = (fRequest::get('first', 'integer') == 1) ? 1 : 0;
	$description = fRequest::get('description', 'string');
	
	// If room is just a number, prefix it with the word 'room'
	if(is_numeric($room)){ $room = 'room ' . $room; }
	
	try{
		
		$job = new Job();
		$job->setCreator($creator);
		$job->setComputer($computer);
		$job->setRoom($room);
		$job->setType($type);
		$job->setFirst($first);
		$job->setDescription($description);
		$job->setStatus('new');
		$job->setCreated(strtotime("now"));
		$job->store();
	
		$json['text'] = '';
		$json['status'] = 'ok';
		$json['id'] = $job->getId();
		out($json);
	
	} catch(fExpectedException $e){
		
		$json['text'] = $e->getMessage();
		$json['status'] = 'err';
		out($json);
		
	} catch(fValidationException $e){
		
		$json['text'] = $e->getMessage();
		$json['status'] = 'err';
		out($json);
		
	}
	
}		// End of action == 'create'




/** 
 * Update details for a job
 */
if($action == 'update'){
	
	// Determine the update task
	$task = fRequest::getValid('task', array('setowner', 'addcomment', 'changestatus'));
		
		// Update owner of a job and set status to open
		if($task == 'setowner'){
			
			try {
				
				$job = new Job($id);
				$job->setOwner($owner);
				$job->setStatus('open');
				$job->setUpdated(new fTimestamp());
				$job->store();
				
				$json['status'] = 'ok';
				$json['text'] = 'Owner has been updated.';
				out($json);
				
			} catch(fExpectedException $e) {
				
				$json['status'] = 'err';
				$json['text'] = 'Error: ' . $e->getMessage();
				out($json);
				
			}
			
		}		// End of task == 'setowner'
		
		
		if($task == 'addcomment'){
			
			$comment_text = fRequest::get('comment', 'string');
			$author = fRequest::get('author', 'string');
			$email = fRequest::get('email', 'boolean');
			$close = fRequest::get('close', 'boolean');
			
			try {
				
				$job = new Job($id);
				
				$comment = new Comment();
				$comment->setJobId($id);
				$comment->setComment($comment_text);
				$comment->setAuthor($author);
				$comment->setTime(new fTimestamp());
				$comment->store();
				
				$json['text'] = 'Comment added.';
				
				// Close job if required
				if($close == TRUE){
					$job->setStatus('closed');
					$job->store();
					$json['text'] = 'Comment added and job closed.';
				}
				
				$json['status'] = 'ok';
				out($json);
				
			} catch(fValidationException $e){
				
				$json['status'] = 'err';
				$json['text'] = 'Error: ' . $e->getMessage();
				out($json);
				
			} catch(fException $e) {
				
				$json['status'] = 'err';
				$json['text'] = 'Error: ' . $e->getMessage();
				out($json);
				
			}
			
		}		// End of task == 'addcomment'
		
}		// End of action == 'update'