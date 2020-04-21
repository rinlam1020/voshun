<?php
global $swift_performance_setup;
$swift_performance_setup->show_steps = false;
?>
<h1><?php esc_html_e('Your website is ready!', 'swift-performance'); ?></h1>
<?php if (!defined('SWIFT_PERFORMANCE_WHITELABEL')):?>
<p>
	<em><?php _e('You can check advanced settings also to improve your results.<br> If you need any help, check the <a href="https://swiftperformance.io/faq/" target="_blank">FAQ</a>, <a href="https://kb.swteplugins.com/swift-performance/" target="_blank">Knowledge Base</a>, <a href="https://swteplugins.com/support/" target="_blank">Open a support ticket</a>, or join to the <a href="https://www.facebook.com/groups/SwiftPerformanceUsers/" target="_blank">Facebook Community!</a>', 'swift-performance');?></em>
</p>
<?php endif;?>
<p>
	<?php if (Swift_Performance::check_option('purchase-key', '', '!=')):?>
		<a href="<?php echo esc_url(add_query_arg('subpage', 'image-optimizer', menu_page_url(SWIFT_PERFORMANCE_SLUG, false))); ?>" class="swift-btn swift-btn-green"><?php echo esc_html__('Optimize images', 'swift-performance'); ?></a>
	<?php endif;?>
	<a href="<?php echo esc_url(add_query_arg('subpage', 'settings', menu_page_url(SWIFT_PERFORMANCE_SLUG, false))); ?>" class="swift-btn swift-btn-gray"><?php echo sprintf(esc_html__('%s Settings', 'swift-performance'), SWIFT_PERFORMANCE_PLUGIN_NAME); ?></a>
	<a href="<?php echo admin_url(); ?>" class="swift-btn swift-btn-gray"><?php echo esc_html__('Back to dashboard', 'swift-performance'); ?></a>
</p>
<?php if (!defined('SWIFT_PERFORMANCE_WHITELABEL')):?>
<p><?php esc_html_e('What\'s next?', 'swift-performance'); ?></p>
<div class="swift-setup-row">
	<div class="swift-setup-col">
		<ul>
			<li><a href="https://www.facebook.com/groups/SwiftPerformanceUsers/" target="_blank"><?php esc_html_e('Join to the Facebook Community');?></a></li>
			<li><a href="https://www.facebook.com/swiftplugin/" target="_blank"><?php esc_html_e('Follow us on Facebook', 'swift-performance'); ?></a></li>
			<li><a href="https://twitter.com/swiftplugin" target="_blank"><?php esc_html_e('Follow us on Twitter', 'swift-performance'); ?></a></li>
		</ul>
	</div>
</div>
<?php endif;?>
