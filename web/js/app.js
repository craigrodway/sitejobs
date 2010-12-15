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
			$.getJSON(API, { action: "get", status: "open", owner: User._current_user.username }, function(res){
				if(res.status == "ok"){
					context.partial("web/templates/job.template", {jobs: res.jobs, user: User._current_user });
				}
			});
		});
		
		
		/**
		 * Get all jobs that are closed
		 */
		this.get('#/closed', function(context){
			this.t("Completed jobs", "closed_fg");
			$.getJSON(API, { action: "get", status: "closed" }, function(res){
				console.log(res);
				if(res.status == "ok"){
					context.partial("web/templates/job.template", {jobs: res.jobs, user: User._current_user });
					/*$.each(res.jobs, function(i, row){
						context.render("web/templates/job.template", {job: row}).appendTo(context.$element());
					});*/
				} else {
					$('#main-list').text('');
				}
			});
		});
		
		
		/**
		 * View one job
		 */
		this.get('#/view/:id', function(context){
			var id = this.params['id'];
			this.t("Information about job ID " + id);
			$.getJSON(API, { action: "get", id: id }, function(res){
				context.partial("web/templates/jobdetail.template", {job: res.job, user: User._current_user });
			});
			
		});
		
		
		/**
		 * Get all jobs created by...
		 */
		this.get("#/creator/:user", function(context){			
			var data = {
				 action: "get"
				,creator: this.params["user"]
				,searchtype: "="
			};
			context.t("Problems reported by " + data.creator, "");
			$.post(API, data, function(res){
				console.log(res);
				if(res.status == "ok"){
					if(res.jobs.length > 0){
						context.partial("web/templates/job.template", {jobs: res.jobs, user: User._current_user });
					} else {
						$('#main-list').html("<br /><p>No problems reported by " + data.creator + "</p>");
					}
				}
			});
		});
		
		
		/**
		 * Add comment to a job (just show window)
		 */
		this.post('#/addcomment/:id', function(context){
			var id = this.params['id'];
			// Get the comment form template and then render it in a jqModal window
			context.render('web/templates/addcomment.template', {id: id}, function(x){
				// Need to duplicate the dialog element first, and then work on the copy
				$('#dialog_source').clone().attr("id", "dialog").appendTo('body');
				// Set the HTML contents then append dialog element to main Sammy element (so the form submits dynamicly)
				$('#dialog').html(x).appendTo(context.$element()).jqm().jqmShow();
			});
		});
		
		
		/**
		 * Handle form submission for adding a comment
		 */
		this.post('#/addcomment', function(context){
			console.log('This is #/addcomment');
			var a = this;
			var data = {
				action: "update"
				,task: "addcomment"
				,id: this.params['id']
				,comment: this.params['comment']
				,author: User._current_user.username
				,email: (this.params['email']) ? 1 : 0
				,close: (this.params['close']) ? 1 : 0
			};
			$.post(API, data, function(ret){
				if(ret.status == 'ok'){
					// Hide the dialog
					$('#dialog').jqmHide();
					// Redirect to the job page (it will show the new comment)
					a.redirect("#/view/" + data.id);
					showNotification("Your comment has been added.");
					context.trigger("update-counts");
				}
			});
		}); 
		
		
		/**
		 * Handle search form submission
		 */
		this.post("#/search", function(context){
			var data = {
				 action: "get"
				,job_id: this.params["id"]
				,room: this.params["room"]
				,creator: this.params["creator"]
				,type: this.params["type"]
				,searchtype: "~"
			};
			$.post(API, data, function(res){
				if(res.status == 'ok'){
					context.t("Search results", "");
					if(res.jobs.length > 0){
						context.partial("web/templates/job.template", {jobs: res.jobs, user: User._current_user });
					} else {
						$('#main-list').html('<br /><p>No results. Please try again with different options.</p>');
					}
				}
			});
		});
		
		
		/**
		 * Take ownership of a job
		 */
		this.post('#/own/:id', function(context){
			var id = this.params['id'];
			var a = this;
			$.post(API, { action: "update", task: "setowner", id: id, owner: User._current_user.username }, function(ret){
				if(ret.status == "ok"){
					a.redirect("#/mine");
					context.trigger("update-counts");
					showNotification(ret.text);
				}
			});
		});
		
		
		
		
		this.before(function(callback){
			// Always make sure notification info bar is removed (could end up with a pile-up of bars)
			if( $('.jbar').length > 1 ) $.removebar();
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
		
		
		// Handle search queries from out-of-app search box, and send to main app
		$('form#search').bind("submit", function(e){
			e.preventDefault();
			var data = {};
			// Collect form values and put in data object
			$('form#search input[type*=text],form#search select').each(function(i, el){
				data[$(el).attr("name")] = $(el).val();
			});
			// Run the Sammy route and pass it our form data
			app.runRoute("post", "#/search", data);
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