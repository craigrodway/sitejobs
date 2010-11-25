(function($) {
	
	
	/**
	 * Handle user authentication
	 */
	User = {
		
		_current_user: {},
		
		setup: function(data){
			var user = this._current_user;
			user.username = data.username;
			user.display = data.display;
			user.token = data.token;
		},
		
		current: function(callback){
			var user = this._current_user;
			if(user.username && user.token){
				callback(user.username);
			} else {
				callback(false);
			}
		},
		
		isLoggedIn: function(){
			return !!this._current_user.username;
		},
		
		getUsername: function(){
			return this._current_user.username;
		}
		
	}
	
	
	
	
	/**
	 * Samme app
	 */
	var app = $.sammy("#main-list", function(){
		
		this.use("Template");
		this.use("Title");
		
		this.setTitle('Site Jobs / ');
		
		// API URL
		var API = "api.php"
		
		
		/** 
		 * Get new and unassigned jobs
		 */
		this.get('#/new', function(context){
			this.t("New Jobs", "new_fg");
			$('#main-list').text('');
			$.getJSON(API, { action: "get", status: "new" }, function(res){
				if(res.status == "ok"){
					context.partial("web/templates/job.template", {jobs: res.jobs, user: User._current_user });
				}
			});
		});
		
		
		/**
		 * Get all open jobs
		 */
		this.get('#/open', function(context){
			this.t("Jobs in progress", "open_fg");
			$('#main-list').text('');
			//$('#main-list').text(User._current_user.username);
			$.getJSON(API, { action: "get", status: "open" }, function(res){
				console.log(res);
				if(res.status == "ok"){
					context.partial("web/templates/job.template", {jobs: res.jobs, user: User._current_user });
				}
			});
		});
		
		
		/** 
		 * Get jobs of current user
		 */
		this.get('#/mine', function(context){
			this.t("My Jobs", "mine_fg");
			$('#main-list').text('');
			$.getJSON(API, { action: "get", status: "open", owner: User._current_user.username }, function(res){
				if(res.status == "ok"){
					context.partial("web/templates/job.template", {jobs: res.jobs, user: User._current_user });
				}
			});
		});
		
		
		/**
		 * Get all jobs that are closed
		 */
		this.get('#/closed', function(){
			this.t("Completed jobs", "closed_fg");
			$('#main-list').text('');
			$.getJSON(API, { action: "get", status: "closed" }, function(res){
				if(res.status == "ok"){
					$.each(res.jobs, function(i, row){
						context.render("web/templates/job.template", {job: row}).appendTo(context.$element());
					});
				}
			});
		});
		
		
		/**
		 * View one job
		 */
		this.get('#/view/:id', function(context){
			var id = this.params['id'];
			$('#main-list').text('');
			this.t("Information about job ID " + id);
			$.getJSON(API, { action: "get", id: id }, function(res){
				context.partial("web/templates/jobdetail.template", {job: res.job, user: User._current_user });
			});
			
		});
		
		
		/**
		 * Add comment to a job
		 */
		this.post('#/addcomment/:id', function(context){
			var id = this.params['id'];
			context.render('web/templates/addcomment.template', {id: id}, function(x){
				$('#dialog').html(x).appendTo(context.$element()).jqm().jqmShow();
			});
		});
		
		
		/**
		 * Handle form submission for adding a comment
		 */
		this.post('#/addcomment', function(context){
			console.log('This is #/addcomment');
			var data = {
				action: "update",
				task: "addcomment",
				id: this.params['id'],
				comment: this.params['comment'],
				author: User._current_user.username,
				email: (this.params['email']) ? 1 : 0,
				close: (this.params['close']) ? 1 : 0,
			};
			$.post(API, data, function(data){
				console.log(data);
			});
		}); 
		
		
		/**
		 * Take ownership of a job
		 */
		this.post('#/own/:id', function(context){
			var id = this.params['id'];
			$.post(API, { action: "update", task: "setowner", id: id, owner: User._current_user.username }, function(data){
				if(data.status == "ok"){
					app.refresh();
					showNotification(data.text);
					context.trigger("update-counts");
				}
			});
		});
		
		
		
		
		this.before(function(callback){
			// Always make sure info bar is removed
			$.removebar();
		});
		
		
		
		
		/**
		 * Get counts of jobs and update header
		 */
		this.bind("update-counts", function(){
			$.getJSON(API, { action: "get", status: "open" }, function(res){
				var num = 0;
				if(res.status == "ok"){
					num = res.jobs.length;
				}
				$("span#count-open").text(num);
			});
			$.getJSON(API, { action: "get", status: "open", owner: User._current_user.username }, function(res){
				var num = 0;
				if(res.status == "ok"){
					num = res.jobs.length;
				}
				$("span#count-user").text(num);
			});
		});
		
		
		
		
		/**
		 * All helpers
		 */
		this.helpers({
			
			/**
			 * Change title in <title> and page heading
			 */
			t: function(title, cls){
				$('#title').text(title).removeClass().addClass(cls);
				this.title(title);
			},
			
			showLoggedIn: function(username){
				alert("U: " + username);
			}
			
		});
		
		
		
	});
	
	
	$(function() {
		
		// Include auth data in all requests
		$.ajaxSetup({ data: { username: User._current_user.username, token: User._current_user.token }
					, cache: false
					, dataType: "json"
					, dataFilter: function(data, type){
						if(type == "json"){
							res = eval("(" + data + ")");
							if(res.status == "err"){
								showError("An error occurred: " + res.text);
								return false;
							}/* else if(res.status == "warn"){
								showWarning("Note: " + res.text);
								return false;
							}*/
						}
						return data;
					}
		});
		
		// Links that use forms for various methods (i.e. post, delete).
		$("a[data-method]").live("click", function(e) {
			e.preventDefault();
			var link = $(this);
			if (link.attr("data-confirm") && !confirm(link.attr("data-confirm"))){
				return fasle;
			}
			var method = link.attr("data-method") || "get";
			var form = $("<form>", { style: "display: none", method: method, action: link.attr("href") });
			app.$element().append(form);
			form.submit();			
		});
		
		/*$('button[name*=addcomment]').live("click", function(e){
			e.preventDefault();
			console.log(app.EventContext.render('web/templates/addcomment.template'));
			$('#dialog').jqm({ overlay: 0, ajax: 'web/templates/addcomment.template' }).jqmShow();
		});*/
		
		// Run app
		app.run("#/new");
		
		app.trigger("update-counts");
		
	});
	
	
})(jQuery);