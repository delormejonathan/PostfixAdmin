$(document).ready(function()
{
	$('select.bootstrap-select').selectpicker();

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
	})
});