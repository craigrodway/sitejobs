/**
 * Job class
 */
Job = function(attrs){
	var defaultjob = {};
	this.attrs = $.extend({}, defaultjob, job);
}




/** 
 * Job list class
 */

JobList = function(){
	this.jobs = new Object();
	this.numJobs = 0;
	this.type = null;
}


/**
 * Add a job to the list
 */
JobList.prototype.addJob = function(attrs){
	if(typeof(this.jobs[attrs.id]) == "undefined"){
		this.jobs[attrs.id] = new Job(attrs);
		this.numJobs++;
	}
}


JobList.prototype.get = function(id, status){
	var status = (status) ? status : "new"
	$.post(
		"jobs.php",
		{ action: 'get', status: status }, 
		function(data){
			if(data.status == 'err'){
				alert(data.text);
			} else {
				var jobs = [];
				$.each(data, function(i, row){
					jobs.push(new J(row));
				});
				success(jobs);
			}
		},
		"json"
	);
}