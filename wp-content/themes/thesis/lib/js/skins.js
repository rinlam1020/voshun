/*
Copyright 2012 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: http://diythemes.com/thesis/rtfm/software-license-agreement/
*/
var thesis_skins;
(function($) {
thesis_skins = {
	init: function() {
		$('.skin_delete').on('click', function() {
			thesis_skins.delete($(this).attr('data-class'), $(this).attr('data-name'));
			return false;
		});
		$('#skin_upload').click(function() { thesis_skins.popup('#popup_skin_uploader'); return false; });
	},
	popup: function(popup) {
		var body = $(popup+' .t_popup_body');
		$('body').addClass('no-scroll');
		$(popup).show();
		$(popup + ' .t_popup_body').height(function(){
			var total_height = $(popup + ' .t_popup_html').height(),
				head_height = $(popup + ' .t_popup_head').outerHeight(),
				body_inner = body.innerHeight(),
				body_height = body.height(),
				set_height = total_height - (head_height + (body_inner - body_height));
			return set_height;
		});
		if ($(popup).hasClass('triggered') && !$(popup).hasClass('force_trigger')) return;
		$(popup).addClass('triggered');
		$(body).css({'margin-top': $(popup+' .t_popup_head').outerHeight()});
		$('.t_popup_close').on('click', function() {
			$(popup).hide();
			$('body').removeClass('no-scroll');
		});
		$(body).find('label .toggle_tooltip').on('click', function() {
			$(this).parents('label').parents('p').siblings('.tooltip:first').toggle();
			return false;
		});
		$(body).find('.tooltip').on('mouseleave', function() { $(this).hide(); });
	},
	add_item: function(iframe, div, append, url) {
		if (div !== false)
			$(iframe).contents().find(div).insertAfter(append);
		setTimeout(function(){
			$(iframe).attr('src', url);
		}, 5000);
	},
	delete: function(object, name) {
		if (typeof object == 'string' && typeof name == 'string' && confirm('Are you sure you want to delete this Skin? All Skin files (including the custom.php file) will be deleted, but your Skin data will persist in case you want to re-install this Skin at a later time.'))
			$.post(ajaxurl, { action: 'delete_skin', class: object, name: name }, function(popup) {
				$('#popup_skin_uploader').after(popup);
				thesis_skins.popup('#popup_delete_'+object);
			});
	}
};
$(document).ready(function($){ thesis_skins.init(); });
})(jQuery);