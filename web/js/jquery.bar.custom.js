$(function($) {

	$.bar = function(options) {
		var defaults = {
			position: 'top',
			removebutton: false,
			time: 2000
		};
		options = $.extend(defaults, options);

		if ($('.jbar').length) {
			$.removebar();
		}

		jbartimeout = setTimeout('$.removebar()', options.time);
		var _message_span = $(document.createElement('span')).addClass('jbar-content').html(options.message);
		var _wrap_bar;
		(options.position == 'bottom') ?
					_wrap_bar = $(document.createElement('div')).addClass('jbar jbar-bottom') :
					_wrap_bar = $(document.createElement('div')).addClass('jbar jbar-top');

		_wrap_bar.addClass("notification");
		if (options.useClass != undefined) _wrap_bar.addClass(options.useClass);

		if (options.removebutton) {
			var _remove_cross = $(document.createElement('a')).addClass('jbar-cross');
			_remove_cross.click(function(e) { $.bar.removebar(); })
		}
		else {
			_wrap_bar.css({ "cursor": "pointer" });
			_wrap_bar.click(function(e) { $.removebar(); })
		}
		_wrap_bar.append(_message_span).append(_remove_cross).hide().insertBefore($('.content'));
		$('body').append(_wrap_bar);
		_wrap_bar.fadeIn('fast');
		_wrap_bar.css({ "display": "block" });
	};

	var jbartimeout;
	$.removebar = function(txt) {
		if ($('.jbar').length) {
			clearTimeout(jbartimeout);
			$('.jbar').fadeOut('fast', function() {
				$(this).remove();
			});
		}
	};

});


function showNotification(message) {
	showMessage(message, null);
}

function showWarning(message){
	showMessage(message, "warn");
}

function showError(message) {
	showMessage(message, "err");
}

function showMessage(message, classToUse) {
	var options = { message: message };
	if (classToUse != null) options.useClass = classToUse;
	if(classToUse == 'err'){ options.time = 5000; }
	$.bar(options);
}