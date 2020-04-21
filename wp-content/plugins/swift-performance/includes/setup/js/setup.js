(function() {

	// Minify scripts show/hide
	jQuery(document).on('click change','.swift-setup #merge-scripts', function(){
		if (jQuery('[name="options[merge-scripts]"]:checked').val() == '1'){
			jQuery('#minify-scripts-container').removeClass('swift-hidden');
		}
		else {
			jQuery('#minify-scripts-container').addClass('swift-hidden');
		}
	});

	// Minify scripts show/hide
	jQuery(document).on('click change','.swift-setup #merge-styles', function(){
		if (jQuery('[name="options[merge-styles]"]:checked').val() == '1'){
			jQuery('#bypass-import-container').removeClass('swift-hidden');
		}
		else {
			jQuery('#bypass-import-container').addClass('swift-hidden');
		}
	});

	// Merge assets in background checkbox show/hide
	jQuery(document).on('click change','.swift-setup #merge-scripts, .swift-setup #merge-styles', function(){
		if (jQuery('.swift-setup #merge-scripts:checked').val() == '1' || jQuery('.swift-setup #merge-styles:checked').val() == '1'){
			jQuery('#optimize-prebuild-only-container, #merge-background-only-container, #limit-threads-container, #minify-html-container').removeClass('swift-hidden');
		}
		else {
			jQuery('#optimize-prebuild-only-container, #merge-background-only-container, #limit-threads-container, #minify-html-container').addClass('swift-hidden').find('input[type="checkbox"]').removeAttr('checked');
		}
	});

	// Cloudflare checkbox show/hide
	jQuery(document).on('click change','#cloudflare-auto-purge', function(){
		if (jQuery('#cloudflare-auto-purge').attr('checked')){
			jQuery('#cloudflare-email-container, #cloudflare-api-key-container').removeClass('swift-hidden');
		}
		else {
			jQuery('#cloudflare-email-container, #cloudflare-api-key-container').addClass('swift-hidden').val('');
		}
	});

	// Varnish checkbox show/hide
	jQuery(document).on('click change','#varnish-auto-purge', function(){
		if (jQuery('#varnish-auto-purge').attr('checked')){
			jQuery('#custom-varnish-host-container').removeClass('swift-hidden');
		}
		else {
			jQuery('#custom-varnish-host-container').addClass('swift-hidden').val('');
		}
	});

	// Keep original options
	jQuery(document).on('click change','#optimize-images-enabled', function(){
		if (jQuery('#optimize-images-enabled:checked').length > 0){
			jQuery('#keep-original-images-container').removeClass('swift-hidden');
		}
		else {
			jQuery('#keep-original-images-container').addClass('swift-hidden');
		}
	});

	// Limit Threads
	jQuery(document).on('click change','#limit-threads', function(){
		if (jQuery('#limit-threads:checked').length > 0){
			jQuery('.swift-performance-max-threads').removeClass('swift-hidden');
		}
		else {
			jQuery('.swift-performance-max-threads').addClass('swift-hidden');
		}
	});

	jQuery(function(){
		// Analyze
		function analyze(){
			if (jQuery('.swift-analyze').length > 0){
				if (jQuery('.swift-analyze li:not(.done)').length > 0){
					var step = jQuery('.swift-analyze li:not(.done)')[0];
					jQuery(step).find('.dashicons').removeClass('dashicons-minus').addClass('dashicons-update').addClass('swift-performance-rotate');
					if (jQuery(step).attr('data-step') != 'self-check' ){
						jQuery.ajaxSetup({timeout:60000});
						jQuery.get(ajaxurl, {'action' : 'swift_performance_setup', 'ajax-action' : jQuery(step).attr('data-step'), 'swift-nonce' : swift_performance.nonce}, function(response){
							var dashicon = (typeof response.dashicon !== 'undefined' ? response.dashicon : 'yes');
							jQuery(step).find('.dashicons').removeClass('dashicons-update').removeClass('swift-performance-rotate').addClass('dashicons-' + dashicon);
							jQuery(step).addClass('done');
							if (typeof response.message !== 'undefined'){
								jQuery(step).find('.result').text(response.message)
							}

							if (response.disable_autoconfig === true){
								jQuery('.swift-use-autoconfig').remove();
							}
							analyze();
						}).fail(function(){
							jQuery(step).find('.dashicons').removeClass('dashicons-update').removeClass('swift-performance-rotate').addClass('dashicons-clock');
							jQuery(step).find('.result').text(__('Test timed out'));
							jQuery(step).addClass('done');
							analyze();
						});
					}
					// Self check
					else {
						var block = false;
						window.addEventListener("message", function (message){
						 if (message.data == 'report-js-error'){
							block = true;
							jQuery.get(ajaxurl, {'action': 'swift_performance_setup', 'ajax-action': 'report-js-error', 'swift-nonce': swift_performance.nonce}, function(){
								jQuery(step).find('.dashicons').removeClass('dashicons-update').removeClass('swift-performance-rotate').addClass('dashicons-yes');
								jQuery(step).addClass('done');
								analyze();
							});
						 }
						}, false);
						jQuery('#swift-preview-frame').on('load', function(){
							setTimeout(function(){
								if (!block){
									jQuery(step).find('.dashicons').removeClass('dashicons-update').removeClass('swift-performance-rotate').addClass('dashicons-yes');
									jQuery(step).addClass('done');
									analyze();
								}
							},500);
						});

						jQuery('#swift-preview-frame').attr('src', swift_performance.home_url + '?force-cache=' + Math.random());
					}
				}
				else {
					jQuery('.swift-setup-btn-wrapper [disabled]').removeAttr('disabled');
				}
			}
		}
		analyze();

		// Fire selects on Load

		// Merge assets
		jQuery('.swift-setup #merge-scripts').trigger('change');

		// Merge styles
		jQuery('.swift-setup #merge-styles').trigger('change');

		// Limit threads
		jQuery('#limit-threads').trigger('change');
		// Cloudflare
		jQuery('#cloudflare-auto-purge').trigger('change');
		// Varnish
		jQuery('#varnish-auto-purge').trigger('change');
	});

	/**
	 * Localization
	 * @param string text
	 * @return string
	 */
	function __(text){
		if (typeof swift_performance.i18n[text] !== 'undefined'){
			return swift_performance.i18n[text];
		}
		else {
			return text;
		}
	}
})();
