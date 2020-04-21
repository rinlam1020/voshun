/*
Copyright 2012 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: http://diythemes.com/thesis/rtfm/software-license-agreement/
*/
var thesis_options;
(function($) {
thesis_options = {
	form: function() {
		thesis_options.init('.thesis_options_form');
	},
	init: function(form) {
		var position = $('#save_options').outerWidth()+35+'px';
		thesis_options.objects.init(form);
		thesis_options.groups(form);
		thesis_options.controls(form);
		thesis_options.tooltips(form);
		thesis_options.counter(form);
		$('#save_options').ready(function() {
			$('#options_saved').css({'right': position}).show().fadeOut(3000, function() { $(this).remove(); });
		});
		$('#save_options').on('click', function() {
			if ($(this).hasClass('thesis_save_ajax'))
				$(this).prop('disabled', true);
			$('#saving_options').css({'right': position}).show();
		});
		$('.thesis_save_ajax').on('click', function() {
			thesis_options.save($(this).attr('data-type'), form);
			return false;
		});
		$('body').keydown(function(e){
			return thesis_options.maybe_save(e);
		});
	},
	objects: {
		init: function(form) {
			$(form+' .object_select').on('change', function() {
				if ((val = $(this).val()))
					thesis_options.objects.popup(val);
			});
			$(form+' .option_object').each(function() {
				thesis_options.objects.editable(this);
			});
		},
		editable: function(id) {
			$(id).on('mouseover', function() {
				var pos = $(this).position(),
					h = $(this).outerHeight(),
					tip = $(this).data('tooltip');
				if (tip)
					$(this).parent().append('<div class="t_ajax_alert" style="top: '+(pos.top + h + 10)+'px; left: '+pos.left+'px;"><div class="t_message"><p>'+tip+'</p></div></div>');
			}).on('mouseout', function() { $(this).siblings('.t_ajax_alert').remove(); }).on('click', function() {
				thesis_options.objects.popup($(this).data('id'));
			});
		},
		popup: function(id) {
			if (!id) return;
			var popup = '#object_popup_'+id;
			$('body').addClass('no-scroll');
			$(popup).show();
			$(popup).find('input, select').keypress(function(event) { return event.keyCode != 13; });
			if ($(popup).hasClass('triggered') && !$(popup).hasClass('force_trigger')) return;
			$(popup).addClass('triggered');
			$(popup+' .object_popup_close').on('click', function() {
				thesis_options.objects.set(id);
				$(popup).hide();
				$('body').removeClass('no-scroll');
				return false;
			});
		},
		set: function(id) {
			var	popup = '#object_popup_'+id,
				name = $(popup+' .object_popup_name').html(),
				select = $(popup).siblings('.option_field').is('*') ?
					$(popup).siblings('.option_field').children('p').children('.object_select') :
					false,
				object = [],
				tooltip = '';
			$.each($(popup+' :input:visible').not($('.counter')), function() {
				var val = $(this).val(),
					label = $(this).siblings('label').not($('.counter_label')).is('*') ?
						$.trim($(this).siblings('label').not($('.counter_label')).html().replace(/<[^>]+>[^<]*<[^>]+>|<[^\/]+\/>/ig, "")) :
						false;
				if ((type = $(this).attr('type'))) {
					if (type == 'checkbox' || type == 'radio') {
						label = type == 'radio' && $(this).parents('ul').siblings('.list_label').is('*') ?
							$.trim($(this).parents('ul').siblings('.list_label').contents(':not(span)').text()) :
							false;
						if ($(this).is(':checked') || $(this).is(':selected'))
							object.push({ tooltip: (label ? label+': ' : '')+$(this).siblings('label').html() });
					}
					else if (type != 'hidden' && val)
						object.push({ tooltip: (label ? label+': ' : '')+val });
				}
				else {
					$(this).children('option').each(function() {
						if (val && $(this).val() == val)
							object.push({ tooltip: (label ? label+': ' : '')+$(this).html() });
					});
				}
			});
			if (!$.isEmptyObject(object)) {
				var i = 1;
				$.each(object, function(j, field) {
					tooltip = tooltip+field.tooltip+(i < object.length ? ', ' : '');
					i++;
				});
				if ($('#object_'+id).is('*'))
					$('#object_'+id).data({ tooltip: tooltip });
				else if (select) {
					$(popup).siblings('.object_list').append(
						'\t\t<li class="option_object" data-style="button object" id="object_'+id+'" data-id="'+id+'" data-tooltip="'+tooltip+'" title="click to edit">'+name+'</li>\n');
					thesis_options.objects.editable('#object_'+id);
					$(select).children('option').each(function() {
						if ($(this).val() == id)
							$(this).remove();
					});
					$(select).val('');
				}
			}
			else if ($('#object_'+id).is('*')) {
				if (select) {
					$('#object_'+id).remove();
					$(select).append('<option value="'+id+'">'+name+'</option>');
				}
				else
					$('#object_'+id).data('tooltip', '');
			}
		}
	},
	groups: function(form) {
		$(form+' .option_group > label').on('click', function() {
			var toggle = $(this).children('.toggle_group');
			$(toggle).toggleClass('toggled');
			if ($(toggle).hasClass('toggled'))
				$(toggle).html('&#8863;');
			else
				$(toggle).html('&#8862;');
			$(this).siblings('.group_fields').toggle();
		});
	},
	controls: function(form) {
		$(form+' .control').each(function() {
			if ($(this).is(':checked') || $(this).is(':selected')) {
				if ($(this).data('id'))
					$(this).parents('.control_group').siblings('.dependent_'+$(this).data('id')).show();
				else if ($(this).attr('title'))
					$(this).parents('.control_group').siblings('.dependent_'+$(this).attr('title')).show();
			}
		});
		$(form+' .control_group input[type="checkbox"].control').on('change', function() {
			var dep = '.dependent_'+$(this).data('id');
			$(this).parent().siblings('li').children(dep).add($(this).parents('.control_group').siblings(dep)).hide();
			if ($(this).is(':checked'))
				$(this).parent().siblings('li').children(dep).add($(this).parents('.control_group').siblings(dep)).show();
		});
		$(form+' .control_group .radio input').on('change', function() {
			$(this).parents('.control_group').siblings('.dependent_'+$(this).parents('.radio').data('id')).hide();
			$(this).parents('.radio').children('li').each(function() {
				if ($(this).children('.control').is(':checked'))
					$(this).parents('.control_group').siblings('.dependent_'+$(this).children('.control').data('id')).show();
			});
		});
		$(form+' .control_group select').on('change', function() {
			var dep = '.dependent_'+$(this).data('id');
			$(this).parents('.control_group').siblings(dep).hide();
			$(this).children('.control').each(function() {
				if ($(this).is(':selected'))
					$(this).parents('.control_group').siblings('.dependent_'+$(this).data('id')).show();
			});
		});
	},
	tooltips: function(form) {
		$(form+' label .toggle_tooltip').on('click', function() {
			$(this).parents('label').parent('p').siblings('.tooltip:first').toggle();
			return false;
		});
		$(form+' .list_label .toggle_tooltip').on('click', function() {
			$(this).parents('.list_label').siblings('.tooltip:first').toggle();
			return false;
		});
		$(form+' .tooltip').on('mouseleave', function() { $(this).hide(); });
	},
	counter: function(form) {
		$(form+' .count_field').each(function() {
			var count = $(this).val().length;
			$(this).siblings('.counter').val(count);
			$(this).siblings('label').children('.counter').val(count);
		}).keyup(function() {
			var count = $(this).val().length;
			$(this).siblings('.counter').val(count);
			$(this).siblings('label').children('.counter').val(count);
		});
	},
	save: function(type, form) {
		if ($('#options_saved').length === 0) {
			$.post(ajaxurl, { action: 'save'+(type ? '_'+type : ''), form: $(form).serialize() }, function(saved) {
				var position = $('#save_options').outerWidth()+35+'px';
				$('#saving_options').hide();
				$('#t_canvas').append(saved);
				$('#options_saved').css({'right': position}).show().fadeOut(3000, function() {
					$(this).remove();
					$(this).promise().done(function() {
						$('#save_options').prop('disabled', false);
					});
				});
			});
		}
	},
	maybe_save: function(e) {
		// check and see if the correct sequence was pressed and if we're on the admin side (editor already has key saves)
		if (e.keyCode == 83 && ((/Mac/i.test(navigator.userAgent) && e.metaKey) || (/Win/i.test(navigator.userAgent) && e.ctrlKey)) && typeof wp == 'object' && $('#save_options').length > 0) {
			$('#save_options').click();
			return false;
		}
		return true;
	}
	
};
$(document).ready(function($){ thesis_options.form(); });
})(jQuery);