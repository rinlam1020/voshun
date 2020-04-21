/*
Copyright 2012 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: http://diythemes.com/thesis/rtfm/software-license-agreement/
*/
var thesis_editor;
(function($) {
thesis_editor = {
	login: {
		dismissed: false,
		templates_saved: false,
		css_saved: false
	},
	init: function() {
		$('#t_launch_canvas').on('click', function() {
			thesis_editor.get_canvas(thesis_canvas.url);
		});
		thesis_editor.pane($('.t_pane_switch:first').addClass('t_on'));
		$('.t_pane_switch').click(function() {
			$('.t_pane_switch').removeClass('t_on');
			$(this).addClass('t_on');
			thesis_editor.pane(this);
		});
		$('#t_backup').click(function() {
			var note = prompt('Add a note to this backup so you can reference it later.');
			if (note != null)
				thesis_editor.backup(note);
		});
		thesis_editor.init_backups();
		$('#t_import').click(function() {
			thesis_ui.popup('#popup_import_skin');
		});
		$('#t_select_defaults').click(function(){
			thesis_ui.popup('#popup_skin_default');
		});
		$('#t_restore_default').click(function() {
			thesis_editor.default(confirm("Create an automatic backup before restoring defaults?\n\n(Clicking cancel will perform the restoration without creating a backup.)") ? true : false);
			return false;
		});
		$(window).unload(function() {
			if (typeof thesis_editor.canvas == 'object')
				thesis_editor.canvas.close();
		});
		$('#popup_login_notice .t_popup_close').on('click', function() {
			thesis_editor.login.dismissed = true;
		});
		$('#t_login_expiration').on('click', function() { //TODO add saving notification
			$('#t_login_expiration').prop('disabled', true);
			thesis_templates.save(true);
			thesis_css.save(true);
			setInterval(function() {
				thesis_editor.login_reload();
			}, 2000);
			return false;
		});
		setInterval(function() {
			thesis_editor.check_login();
		}, 1000);
	},
	pane: function(pane_switch) {
		$('.t_pane').hide();
		$('#t_' + $(pane_switch).attr('data-pane')).show();
	},
	get_canvas: function(url) {
		thesis_editor.canvas = window.open(url, thesis_canvas.name, 'modal=yes,scrollbars=1,resizable=1,width=' + $(window).width() + ',height=' + $(window).height());
	},
	init_backups: function() {
		$('.t_restore_backup').on('click', function() {
			thesis_editor.restore($(this).attr('data-id'), confirm("Create an automatic backup before proceeding?\n\n(Clicking Cancel will perform the restoration without creating a backup.)") ? 'true' : 'false');
		});
		$('.t_export_backup').on('click', function() {
			$('#t_export_id').val($(this).attr('data-id'));
			thesis_ui.popup('#popup_export_skin');
		});
		$('.t_delete_backup').on('click', function() {
			if (confirm('Are you sure you want to do this? Once you delete a backup, it cannot be recovered.'))
				thesis_editor.delete($(this).attr('data-id'));
		});
	},
	backup: function(note) {
		var nonce = $('#_wpnonce-thesis-skin-manager').val();
		$('#t_backup').prop('disabled', true);
		$.post(thesis_ajax.url, { action: 'backup_skin', note: note, nonce: nonce }, function(saved) {
			$('#t_backup').prop('disabled', false);
			if (saved) {
				$('#t_manager').prepend(saved);
				$('#manager_saved').css({'left': $('#t_manager_head span').outerWidth()+12+'px'});
				$('#manager_saved').fadeOut(3000, function() { $(this).remove(); });
				$.post(thesis_ajax.url, { action: 'update_backup_skin_table', nonce: nonce }, function(html) {
					$('#t_restore_table').html(html);
					thesis_editor.init_backups();
				});
			}
		});
	},
	restore: function(id, backup) {
		var nonce = $('#_wpnonce-thesis-skin-manager').val();
		$.post(thesis_ajax.url, { action: 'restore_skin_backup', id: id, backup: backup, nonce: nonce }, function() {
			if (typeof thesis_editor.canvas == 'object' && thesis_editor.canvas.window != null)
				thesis_editor.canvas.close();
			window.location.reload();
		});
	},
	delete: function(id) {
		var nonce = $('#_wpnonce-thesis-skin-manager').val();
		$.post(thesis_ajax.url, { action: 'delete_skin_backup', id: id, nonce: nonce }, function(deleted) {
			if (deleted) {
				$('#t_restore').prepend(deleted);
				$('#manager_saved').css({'left': $('#t_restore_head span').outerWidth()+12+'px'});
				$('#manager_saved').fadeOut(3000, function() { $(this).remove(); });
				$.post(thesis_ajax.url, { action: 'update_backup_skin_table', nonce: nonce }, function(html) {
					$('#t_restore_table').html(html);
					thesis_editor.init_backups();
				});
			}
		});
	},
	default: function(backup) {
		var nonce = $('#_wpnonce-thesis-skin-manager').val();
		$('#t_restore_default').prop('disabled', true);
		$.post(thesis_ajax.url, { action: 'restore_skin_default', nonce: nonce, form: $('#t_default_form').serialize(), backup: backup }, function(response) {
			$('#t_restore_default').prop('disabled', false);
			if (response == 'true') {
				if (typeof thesis_editor.canvas == 'object' && thesis_editor.canvas.window != null)
					thesis_editor.canvas.close();
				window.location.reload();
			}
			else {
				$('#t_default_form').append(response);
				$('#default_not_saved').css({'left': $('#t_restore_default').outerWidth()+14+'px'});
				$('#default_not_saved').fadeOut(3000, function() { $(this).remove(); });
			}
		});
	},
	check_login: function() {
		var current_time = Math.round((new Date()).getTime() / 1000);
		if ((thesis_login.expire - current_time) < 120 && (thesis_login.expire - current_time) > 10 && thesis_editor.login.dismissed == false) {
			thesis_ui.popup('#popup_login_notice');
			$('#t_countdown').html((thesis_login.expire - current_time));
		}
		else if ((thesis_login.expire - current_time) <= 10) {
			$('body').fadeOut(2000, function(){
				window.location = thesis_login.url;
			});
		}
	},
	login_reload: function() {
		if ((thesis_editor.login.css_saved && thesis_editor.login.templates_saved) == true) {
			window.location = thesis_login.url;
		}
		return false;
	}
};
$(document).ready(function($){ thesis_editor.init(); });
})(jQuery);