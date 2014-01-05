$(document).ready(function()
{
	// Custom select input
	$('select.bootstrap-select').selectpicker();

	// Auto bootbox for confirm
	$("a[data-toggle='confirm']").click(function(e)
	{
		e.preventDefault();

		var link = $(this).attr('href');

		if (typeof $(this).attr('title') != 'undefined')
			var title = $(this).attr('title');
		else
			var title = "Êtes-vous sûr de vouloir continuer ?";
		bootbox.confirm(title, function(result) {
			if (result == true)
				document.location.href=link;
		}); 
	});

	// Auto tooltip
	$('body').tooltip({
		selector: '[data-toggle=tooltip]',
		container: 'body',
		html: true
	});
});

// noty.js
function notice (message)
{
	$.noty.closeAll()
	var noty_load = 
	noty({
		layout: 'bottomRight',
		animation: {
			open: {height: 'toggle'},
			close: {height: 'toggle'},
			easing: 'swing',
			speed: 500,
		},
		type: 'information',
		text: message,
		timeout: 1300
	});
	return noty_load;
}
function success (message)
{
	$.noty.closeAll()
	var noty_load = 
	noty({
		layout: 'bottomRight',
		animation: {
			open: {height: 'toggle'},
			close: {height: 'toggle'},
			easing: 'swing',
			speed: 500,
		},
		type: 'success',
		text: message,
		timeout: 1300
	});
	return noty_load;
}
function error (message)
{
	$.noty.closeAll()
	var noty_load = 
	noty({
		layout: 'bottomRight',
		animation: {
			open: {height: 'toggle'},
			close: {height: 'toggle'},
			easing: 'swing',
			speed: 500,
		},
		type: 'error',
		text: message,
		timeout: 1300
	});
	return noty_load;
}