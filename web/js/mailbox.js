$(document).ready(function()
{
	$('#mailboxes #edit #password').click(function()
	{
		$.ajax(
		{
			type: 'POST',
			url: Routing.generate('postfix_mailboxes_password' , { id : $('#mailboxes #edit').attr('data-mailbox-id') }),
			success: function(password) {
				notice('Mot de passe modifié')
				$('#mailboxes #edit #password').prev().html(password);
			},
			error: function() {
				error('Impossible de modifier le mot de passe')
			}
		});
	});
	$('#mailboxes #edit #mailbox_active').click(function()
	{
		var active = $(this).prop("checked");
		$.ajax(
		{
			type: 'POST',
			url: Routing.generate('postfix_mailboxes_active' , { id : $('#mailboxes #edit').attr('data-mailbox-id') }),
			data: { active : active },
			success: function(password) {
				active ? notice('La boite est activé') : notice('La boite est désactivé')
				$('#mailboxes #edit #mailbox_active').prev().html(password);
			},
			error: function() {
				error('Impossible de modifier la boite')
			}
		});
	});
	$('#mailboxes #edit #alias_add').click(function()
	{
		$.ajax(
		{
			type: 'GET',
			url: Routing.generate('postfix_mailboxes_alias_add' , { id : $('#mailboxes #edit').attr('data-mailbox-id') }),
			success: function(html) {
				bootbox.dialog({
					title: "Ajouter un alias",
					message: html,
					buttons: {
						success: {
							label: "Ajouter",
							className: "btn-success",
							callback: function() {

								var alias = $('#alias_name').val();
								// var status = true;

								if (alias.length == 0)
								{
									error("L'alias ne peut être vide");
									return false;
								}


								$.ajax(
								{
									type: 'POST',
									url: Routing.generate('postfix_mailboxes_alias_add' , { id : $('#mailboxes #edit').attr('data-mailbox-id') }),
									data: { alias : alias },
									success: function() {
										document.location.reload();
									},
									error: function(message) {
										error("Impossible d'ajouter l'alias : " + message.responseText);
									}
								});
							}
						},
					}
				});
			},
			error: function() {
				error('Impossible de charger la page')
			}
		});
	});
	$('#mailboxes #edit #alias_active').click(function()
	{
		var alias = $(this).parent().parent().attr('data-alias-id');
		var active = $(this).prop("checked");
		$.ajax(
		{
			type: 'POST',
			url: Routing.generate('postfix_mailboxes_alias_active' , { id : $('#mailboxes #edit').attr('data-mailbox-id') , alias_id : alias }),
			data: { active : active },
			success: function(password) {
				active ? notice("L'alias est activé") : notice("L'alias est désactivé")
				$('#mailboxes #edit #alias_active').prev().html(password);
			},
			error: function() {
				error("Impossible de modifier l'alias")
			}
		});
	});
});