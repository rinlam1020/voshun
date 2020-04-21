<h1><?php echo sprintf(esc_html__('Welcome to %s!', 'swift-performance'), SWIFT_PERFORMANCE_PLUGIN_NAME); ?></h1>
<p><?php echo sprintf(esc_html__('Thank you for choosing %s! The following few steps will help you to configure the basic settings and improve WordPress performance.', 'swift-performance'), SWIFT_PERFORMANCE_PLUGIN_NAME); ?></p>
<p><?php esc_html_e('If you don\'t want to continue the wizard, you can skip and return to the WordPress dashboard. Come back anytime if you change your mind!', 'swift-performance'); ?></p>
<p><?php esc_html_e('Please note, that if you run the wizard it will reset current settings to the default!', 'swift-performance'); ?></p>
<p>
	<?php
	$plugin_conflicts = self::get_plugin_conflicts();
	if (!empty($plugin_conflicts['hard'])){
		echo sprintf(_n(
			'<strong>%s</strong> will be deactivated during the setup.',
			'The following plugins will be deactivated during the setup: <strong>%s</strong>',
			count($plugin_conflicts['hard']),
			'swift-performance'
		), implode(', ', $plugin_conflicts['hard']));
	}
	?>
</p>

<div class="swift-setup-btn-wrapper">
	<a href="<?php echo esc_url(menu_page_url(SWIFT_PERFORMANCE_SLUG, false)); ?>" class="swift-btn swift-btn-gray swift-btn-md"><?php esc_html_e('Back to Dashboard', 'swift-performance')?></a>
	<a href="<?php echo esc_url(wp_nonce_url(add_query_arg('subpage', 'setup', menu_page_url(SWIFT_PERFORMANCE_SLUG, false)), 'swift-performance-setup', 'swift-nonce')); ?>" class="swift-btn swift-btn-brand swift-btn-md btn-start-wizard"><?php esc_html_e('Start Wizard', 'swift-performance')?></a>
</div>
