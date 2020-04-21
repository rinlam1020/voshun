<?php

class Swift_Performance_Setup {

	/**
	 * Array of steps
	 * @var array
	 */
	public $steps = array();

	/**
	 * Current step
	 * @var array
	 */
	public $current_step = array();

	/**
	 * Show steps in footer
	 * @var boolean
	 */
	public $show_steps = true;

	/**
	 * Localization array for JS
	 * @var array
	 */
	public $localize = array();

	/**
	 * Analyze array
	 */
	public $analyze = array();

	public $disable_continue = false;

	/**
	 * Catch pseudo function calls and do nothing (we use init for early catch the page);
	 * @param string $function
	 * @param array $params
	 */
	public function __call($function, $params){
		// Do nothing
	}

	/**
	 * Create instance
	 */
	public function __construct() {
		// Set installer directory path
		if (!defined('SWIFT_PERFORMANCE_SETUP_DIR')){
			define ('SWIFT_PERFORMANCE_SETUP_DIR', SWIFT_PERFORMANCE_DIR . 'includes/setup/');
		}

		// Set installer directory URI
		if (!defined('SWIFT_PERFORMANCE_SETUP_URI')){
			define('SWIFT_PERFORMANCE_SETUP_URI', SWIFT_PERFORMANCE_URI . 'includes/setup/');
		}

		// Ajax handlers
		add_action('wp_ajax_swift_performance_setup', array($this, 'ajax_handler'));

		// Return if page is not the Swift Performance Setup Wizard page
		if (!isset($_GET['subpage']) || !in_array($_GET['subpage'], array('setup', 'deactivate')) || !isset($_GET['page']) || $_GET['page'] != SWIFT_PERFORMANCE_SLUG ){
			return false;
		}

		ini_set('display_errors', 0);

		// Init steps
		if ($_GET['subpage'] == 'setup'){
			$this->steps = array(
					array(
						'title'	=> esc_html__('Purchase Key', 'swift-performance'),
						'id'		=> 'purchase-key',
					),
					array(
						'title'		=> (esc_html__('Analyze your site', 'swift-performance')),
						'id'			=> 'analyze',
						'disable-skip'	=> true,
					),
					array(
						'title'	=> esc_html__('Caching mode', 'swift-performance'),
						'id'		=> 'caching',
					),
					array(
						'title'	=> esc_html__('Optimization', 'swift-performance'),
						'id'		=> 'manage-assets',
					),
					array(
						'title'	=> esc_html__('Media', 'swift-performance'),
						'id'		=> 'media',
					),
					array(
						'title'	=> esc_html__('Finish', 'swift-performance'),
						'id'		=> 'finish',
					)
			);
		}
		else if ($_GET['subpage'] == 'deactivate'){
			$this->steps = array(
					array(
						'title'	=> esc_html__('Deactivation Settings', 'swift-performance'),
						'id'		=> 'deactivate-settings',
						'disable-skip'	=> true
					),
					array(
						'title'	=> esc_html__('Deactivate', 'swift-performance'),
						'id'		=> 'deactivate',
					)
			);
		}

		// Init
		add_action('admin_init', array($this, 'init'));

		// Change wp title
		add_action('wp_title', array($this, 'wp_title'));
	}

	/**
	 * Init setup wizard
	 */
	public function init(){
		$swift_performance_purchase_key = Swift_Performance::get_option('purchase-key');

		if (!current_user_can('manage_options')){
			return;
		}

		// Localization
		$this->localize = array(
				'i18n' => array(
					'Upload' => esc_html__('Upload', 'swift-performance'),
					'Modify' => esc_html__('Modify', 'swift-performance'),
					'Please wait...' => esc_html__('Please wait...', 'swift-performance'),
					'Test timed out' => esc_html__('Test timed out', 'swift-performance')
				),
				'ajax_url'		=> add_query_arg('page', 'swift_performance_setup', admin_url('admin-ajax.php')),
				'nonce'		=> wp_create_nonce('swift-performance-setup'),
				'home_url'		=> home_url()
		);

		// Enqueue Setup Wizard CSS
		wp_enqueue_style('swift-performance-setup', SWIFT_PERFORMANCE_SETUP_URI . 'css/setup.css', array(), SWIFT_PERFORMANCE_VER);

		// Enqueue Setup Wizard JS
		wp_enqueue_script('swift-performance-setup', SWIFT_PERFORMANCE_SETUP_URI . 'js/setup.js', array(), SWIFT_PERFORMANCE_VER);
		wp_localize_script('swift-performance-setup', 'swift_performance', $this->localize);

		//WP admin styles
		wp_enqueue_style( 'wp-admin' );

		// Set current step
		if (!empty($swift_performance_purchase_key) && $_GET['subpage'] == 'setup'){
			$step = isset($_REQUEST['step']) ? (int)$_REQUEST['step'] : 1;
			unset($this->steps[0]);
		}
		else{
			$step = isset($_REQUEST['step']) ? (int)$_REQUEST['step'] : 0;
		}
		$this->current_step 		= $this->steps[$step];
		$this->current_step['index']	= $step;

		// Do current step actions
		$this->do_step();

		// Render step
		$this->render();
	}

	/**
	 * Do current step actions
	 */
	public function do_step(){
		if (isset($_REQUEST['swift-nonce']) && wp_verify_nonce($_REQUEST['swift-nonce'], 'swift-performance-setup') && current_user_can('manage_options')){
			if (isset($_POST['options']) && is_array($_POST['options'])){
				foreach ($_POST['options'] as $key => $value) {
					Swift_Performance::update_option($key, $value);
				}
			}
			switch ($this->current_step['id']){
				// Analyze
				case 'analyze':
					// Reset Redux
					global $swift_performance_options;
					$reduxsa = ReduxSAFrameworkInstances::get_instance('swift_performance_options');

					// Backup whitelabel options
					$whitelabel_options = array();
					foreach($swift_performance_options as $key => $option){
						if (preg_match('~^whitelabel-~', $key)){
							$whitelabel_options[$key] = $option;
						}
					}

					// Get default values
					$swift_performance_options = $reduxsa->_default_values();

					// Restore whitelabel options
					foreach($whitelabel_options as $key => $option){
						$swift_performance_options[$key] = $option;
					}

					$swift_performance_options['merge-background-only'] = 1;
					$swift_performance_options['limit-threads'] = 1;

					$swift_performance_options['minify-html'] = 1;
					$swift_performance_options['html-auto-fix'] = 1;

					$swift_performance_options['merge-styles'] = 1;
					$swift_performance_options['bypass-css-import'] = 1;

					$swift_performance_options['merge-scripts'] = 1;
					$swift_performance_options['exclude-inline-scripts'][] = 'document.write';

					update_option('swift_performance_options', $swift_performance_options);

					// Empty cache
					Swift_Performance_Cache::clear_all_cache();

					// Prepare image optimizer
					if (Swift_Performance::check_option('purchase-key', '', '!=')){
						Swift_Performance_Image_Optimizer::db_install();
					}
				break;
				case 'finish':
					// Enable 404 caching after testing is ok
					Swift_Performance::update_option('cache-404',1);

					// Empty cache
					Swift_Performance_Cache::clear_all_cache();
				break;
				case 'deactivate':
					update_option('swift-performance-deactivation-settings', array(
						'keep-settings' => (isset($_POST['keep-settings']) && $_POST['keep-settings'] == 'enabled' ? 1 : 0),
						'keep-custom-htaccess' => (isset($_POST['keep-custom-htaccess']) && $_POST['keep-custom-htaccess'] == 'enabled' ? 1 : 0),
						'keep-warmup-table' => (isset($_POST['keep-warmup-table']) && $_POST['keep-warmup-table'] == 'enabled' ? 1 : 0),
						'keep-image-optimizer-table' => (isset($_POST['keep-image-optimizer-table']) && $_POST['keep-image-optimizer-table'] == 'enabled' ? 1 : 0),
						'keep-logs' => (isset($_POST['keep-logs']) && $_POST['keep-logs'] == 'enabled' ? 1 : 0)
					), false);
				break;
				case 'self-check':
					Swift_Performance_Cache::clear_all_cache();
				break;
			}
		}
	}

	/**
	 * Render current step
	 */
	public function render(){
		// Run only the first time
		update_option('swift-perforomance-initial-setup-wizard', 1);

		$template = 'start-wizard';

		if (defined('DOING_AJAX')){
			$GLOBALS['hook_suffix'] = SWIFT_PERFORMANCE_SLUG;
			return;
		}

		// Verify nonce
		if (isset($_REQUEST['swift-nonce']) && wp_verify_nonce($_REQUEST['swift-nonce'], 'swift-performance-setup') && current_user_can('manage_options')){
			// Save settings
			$this->_save_settings();

			// Set template
			$template = $this->current_step['id'];
		}

		// Get header part
		$this->_get_template_part('admin-header');

		// Get Body
		if (!isset($_REQUEST['swift-nonce']) || !wp_verify_nonce($_REQUEST['swift-nonce'], 'swift-performance-setup') || !current_user_can('manage_options')){
			$this->_get_template_part($template);
			$this->show_steps = false;
		}
		else{
			$this->_get_template_part($template);
		}

		// Get Footer
		$this->_get_template_part('admin-footer');

		// Exit
		die;
	}

	/**
	 * Print prev/next step links
	 */
	public function step_links() {
		$current = $this->current_step['index'];

		if ($this->current_step['id'] == 'analyze'){
			$step_keys = array_keys($this->steps);
			echo '<div class="swift-setup-btn-wrapper">';
			echo '<input type="hidden" name="step" value="'.($current + 1).'">'.
			'<input type="hidden" name="swift-performance-setup-action" value="'.esc_attr($this->current_step['id']).'">'.
			'<a class="swift-btn swift-btn-green swift-btn-lg swift-use-autoconfig" href="'. esc_url(wp_nonce_url(add_query_arg(array('subpage' => $_GET['subpage'], 'step' => (end($step_keys))), menu_page_url(SWIFT_PERFORMANCE_SLUG, false)), 'swift-performance-setup', 'swift-nonce')) . '" disabled>'.esc_html__('Use Autoconfigured Settings', 'swift-performance').'</a>'.
			'<button disabled class="swift-btn swift-btn-brand swift-setup-next">'.esc_html__('Continue Wizard', 'swift-performance').'</button>';
			echo '</div>';
		}
		else {
			$current 	= $this->current_step['index'];
			$prev		= isset($this->steps[$current-1]) ? '<a class="swift-btn swift-btn-gray swift-btn-lg" href="'. esc_url(wp_nonce_url(add_query_arg('step', ($current-1), add_query_arg('subpage', $_GET['subpage'], menu_page_url(SWIFT_PERFORMANCE_SLUG, false))), 'swift-performance-setup', 'swift-nonce')) . '">'.esc_html__('Previous step', 'swift-performance').'</a>' : '';
			$this->current_step['id'] == 'analyze';
			if (isset($this->steps[$current+1])){
				$skip = '<a class="swift-btn swift-btn-gray swift-btn-lg swift-skip-step" href="'. esc_url(wp_nonce_url(add_query_arg('step', ($current+1), add_query_arg('subpage', $_GET['subpage'], menu_page_url(SWIFT_PERFORMANCE_SLUG, false))), 'swift-performance-setup', 'swift-nonce')) . '">'.esc_html__('Skip this step', 'swift-performance').'</a>';
				$next = wp_nonce_field('swift-performance-setup', 'swift-nonce').
						'<input type="hidden" name="step" value="'.($current + 1).'">'.
						'<input type="hidden" name="swift-performance-setup-action" value="'.esc_attr($this->current_step['id']).'">'.
						'<button class="swift-btn swift-btn-green swift-setup-next" ' . ($this->disable_continue == true ? 'disabled' : '') . '>'.esc_html__('Continue', 'swift-performance').'</button>';
			}
			echo '<div class="swift-setup-btn-wrapper">';
			echo $prev;
			if (!isset($this->current_step['disable-skip']) || !$this->current_step['disable-skip']){
				echo $skip;
			}
			echo $next;
			echo '</div>';
		}
	}

	/**
	 * Handle ajax requests
	 */
	public function ajax_handler(){
		global $wpdb;
		if (!isset($_REQUEST['swift-nonce']) || !wp_verify_nonce($_REQUEST['swift-nonce'], 'swift-performance-setup') && current_user_can('manage_options')){
			wp_die(0);
		}

		if (isset($_REQUEST['ajax-action'])){
			$dashicon			= 'yes';
			$disable_autoconfig	= false;
			$message			= '';
			switch ($_REQUEST['ajax-action']){
				case 'timeout';
					delete_transient('swift_performance_analyze_multithread');
					$current_process = mt_rand(0,PHP_INT_MAX);
					Swift_Performance::set_transient('swift_performance_timeout_test_pid', $current_process, 600);

					// Try 600 seconds by default
					Swift_Performance::set_time_limit(601, 'timeout_test');

					// Flush connection
					Swift_Performance_Tweaks::flush_connection();
					for ($i=0;$i<600;$i+=10){
						$timeout_test_process	= $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = '_transient_swift_performance_timeout_test_pid'");
						$multithread		= $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = '_transient_swift_performance_analyze_multithread'");
						if (($timeout_test_process !== false && $timeout_test_process != $current_process) || ($i >= 50 && empty($multithread)) ){
							break;
						}
						update_option('swift_performance_timeout', $i);
						sleep(10);
					}
					delete_transient('swift_performance_analyze_multithread');
				break;
				case 'max-connections':
					Swift_Performance::set_transient('swift_performance_analyze_multithread', 1, 600);
				break;
				case 'api':
					if (Swift_Performance::check_api()){
						Swift_Performance::update_option('use-compute-api', 1);
						Swift_Performance::update_option('optimize-uploaded-images', 1);
					}
					else {
						$dashicon = 'no';
					}
				break;
				case 'cpu':
					require_once SWIFT_PERFORMANCE_SETUP_DIR . 'cpu-benchmark.php';
					$cpu_benchmark = Swift_Performance_CPU_Benchmark::test();
					$message = sprintf(__('Result: %ds. ', 'swift-performance'), $cpu_benchmark);
					if (empty($cpu_benchmark) || $cpu_benchmark >= 10){
						Swift_Performance::update_option('merge-styles', 0);
						Swift_Performance::update_option('merge-scripts', 0);
						Swift_Performance::update_option('max-threads', 1);
						$dashicon = 'warning';
						$message .= __('Slow environment detected. It is recommended to use only the auto configured settings.', 'swift-performance');
					}
					else if ($cpu_benchmark >= 5){
						Swift_Performance::update_option('critical-css', 0);
						Swift_Performance::update_option('merge-styles-exclude-3rd-party', 1);
						Swift_Performance::update_option('merge-scripts-exlude-3rd-party', 1);
						Swift_Performance::update_option('max-threads', 1);
						$dashicon = 'warning';
						$message .= __('Slow environment detected. Please note, Critical CSS and merge 3rd party assets can increase CPU usage.', 'swift-performance');
					}
					else if ($cpu_benchmark >= 2.5){
						Swift_Performance::update_option('critical-css', 0);
						Swift_Performance::update_option('max-threads', 2);
					}
					else {
						Swift_Performance::update_option('max-threads', 3);
					}

				break;
				case 'webserver':
					$missing_apache_modules = array();
					$server_software = Swift_Performance::server_software();
					$rewrites = false;
					if ($server_software == 'apache'){
						$rewrites = true;
						// Check modules if server isn't litespeed
						if (preg_match('~apache~i', $_SERVER['SERVER_SOFTWARE']) && function_exists('apache_get_modules')){
							$missing_apache_modules = array_diff(array(
								'mod_expires',
								'mod_deflate',
								'mod_setenvif',
								'mod_headers',
								'mod_filter',
								'mod_rewrite',
							), apache_get_modules());
						}

						if (preg_match('~apache~i', $_SERVER['SERVER_SOFTWARE']) && function_exists('apache_get_modules')){
							if (!in_array('mod_rewrite', apache_get_modules())){
								$rewrites = false;
							}
						}

						// Check htaccess
						$htaccess = ABSPATH . '.htaccess';

						if (!file_exists($htaccess)){
							@touch($htaccess);
							if (!file_exists($htaccess)){
								$rewrites = false;
							}
						}
						else if (!is_writable($htaccess)){
							$rewrites = false;
						}

						$message = sprintf(__('%s detected. ', 'swift-performance'), ucfirst($server_software));

						if ($server_software == 'apache'){
							$message .=  ($rewrites ? __('Rewrites are working. ', 'swift-performance') : __('Rewrites are not working. ', 'swift-performance'));
							if (!empty($missing_apache_modules)){
								$dashicon = 'warning';
								$missing_modules = (count($missing_apache_modules) > 1 ? implode(', ', $missing_apache_modules) : $missing_apache_modules[0]);
								$message .=  sprintf(
										_n(
										'Please enable %s Apache module for better optimization.',
										'Please enable the following Apache modules for better optimization: %s.',
										count($missing_apache_modules),
										'swift-performance')
									, $missing_modules);
							}
						}
						else if ($server_software === 'unkonwn'){
							$message = __('Server software was not detected.', 'swift-performance');
						}

					}

					// Set caching mode
					if ($rewrites){
						Swift_Performance::update_option('caching-mode', 'disk_cache_rewrite');
						try {
							// Generate and write htaccess rules
							$rules = Swift_Performance::build_rewrite_rules();
							Swift_Performance::write_rewrite_rules($rules);
						}
						catch (Exception $e){
							self::print_notice(array('type' => 'error', 'message' => $e->get_error_message()));
						}
					}
				break;
				case 'loopback':
					// Automated prebuild
					if (self::check_loopback()){
						Swift_Performance::update_option('automated_prebuild_cache', 1);
						Swift_Performance::update_option('optimize-prebuild-only', 1);
						Swift_Performance::update_option('merge-background-only', 0);
						$message = __('Loopback is working', 'swift-perforance');
					}
					else {
						$disable_autoconfig = true;
						$dashicon = 'no';
						$message = __('Loopback is disabled.', 'swift-performance');
					}
				break;
				case 'varnish-proxy':
					$cloudflare = false;
					$varnish = false;

					if (self::check_loopback()){
						$response = wp_remote_get(home_url(), array('timeout' => 60, 'sslverify' => false));

						// Extend the max buffer size for large pages
						$max_buffer_size = max(Swift_Performance::get_option('dom-parser-max-buffer'), strlen($response['body']));
						Swift_Performance::update_option('dom-parser-max-buffer', $max_buffer_size);

						$cf		= wp_remote_retrieve_header( $response, 'cf-cache-status' );
						$xv		= wp_remote_retrieve_header( $response, 'x-varnish' );
						$xc		= wp_remote_retrieve_header( $response, 'x-cache' );

						if (!empty($cf)){
							Swift_Performance::update_option('cloudflare-auto-purge',1);
							$message .= __('Cloudflare was detected. Please set your API credentials on next screen.', 'swift-performance');
							$disable_autoconfig = true;
							$dashicon = 'warning';
						}

						if (!empty($xv) || !empty($xc)){
							Swift_Performance::update_option('varnish-auto-purge',1);
							$message .= __('Varnish was detected', 'swift-performance');
						}

						if (empty($message)){
							$message = __('No Varnish or Cloudflare was detected', 'swift-performance');
						}
					}
					else{
						$dashicon	= 'warning';
						$message	= __('Loopback is disabled, Swift can\'t check Varnish and reverse proxy. If you are using Cloudflare cache please set your API credentials on next screen.', 'swift-performance');
					}
				break;
				case 'php-settings':
					// Safe Mode
					$safe_mode = ini_get('safe_mode');

					// time limit
					$set_time_limit = Swift_Performance::is_function_disabled('set_time_limit');

					// memory
					$memory = 0;
					$memory_limit = ini_get('memory_limit');
					if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
					    	if (strtoupper($matches[2]) == 'G') {
  					    		$memory = $matches[1] * 1024 * 1024 * 1024; // nnnM -> nnn MB
  					    	}
					    	else if (strtoupper($matches[2]) == 'M') {
					      	$memory = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
					    	}
						else if (strtoupper($matches[2]) == 'K') {
					        	$memory = $matches[1] * 1024; // nnnK -> nnn KB
					    	}
						else{
					        	$memory = $matches[1]; // nnnK -> nnn KB
					    	}
					}

					// Probably we are on limited shared hosting
					if ($safe_mode || $set_time_limit){
						Swift_Performance::update_option('critical-css', 0);
						Swift_Performance::update_option('merge-styles-exclude-3rd-party', 1);
						Swift_Performance::update_option('merge-scripts-exlude-3rd-party', 1);
						if ($safe_mode){
							$message .= __("Safe mode is enabled. ", 'swift-performance');
							$dashicon = 'warning';
						}

						if ($set_time_limit){
							$message .= __("set_time_limit is disabled. ", 'swift-performance');
							$dashicon = 'warning';
						}
					}

					$message .= sprintf(__("Memory: %s. ", 'swift-performance'), $memory_limit);
					// We don't have too much memory, probably the hosting is limited
					if ($memory < 83886080){
						Swift_Performance::update_option('critical-css', 0);
						$message .= __("Low memory environment detected. ", 'swift-performance');
						$dashicon = 'warning';
					}
				break;
				case 'plugins':
					$plugin_conflicts = self::get_plugin_conflicts();
					// Deactivate plugins
					if (!empty($plugin_conflicts['hard'])){
						$network_wide = is_plugin_active_for_network(SWIFT_PERFORMANCE_PLUGIN_BASENAME);
						deactivate_plugins(array_keys($plugin_conflicts['hard']), false, $network_wide);
						$message = sprintf(_n(
							            '%s was deactivated. ',
							            'The following plugins were deactivated: %s. ',
							            count($plugin_conflicts['hard']),
							            'swift-performance'
							        ), implode(', ', $plugin_conflicts['hard']));
					}
					else {
						$message = __('No hard plugin conflict found. ', 'swift-performance');
					}

					// Soft conflicts
					// WP Touch
					if (isset($plugin_conflicts['soft']['wp-touch'])){
						Swift_Performance::update_option('mobile-support', 1);
						$excluded_useragents = (array)Swift_Performance::get_option('exclude-useragents');
						$excluded_useragents[] = '#(Mobile|Android|Silk/|Kindle|BlackBerry|Opera Mini|Opera Mobi)#';
						Swift_Performance::update_option('exclude-useragents', $excluded_useragents);

						$message .= __('WP Touch detected, caching for mobile is disabled. ', 'swift-performance');
					}

					// Autoptimize
					if (isset($plugin_conflicts['soft']['autoptimize'])){
						Swift_Performance::update_option('merge-styles', 0);
						Swift_Performance::update_option('merge-scripts', 0);
						Swift_Performance::update_option('minify-html', 0);

						$message .= __('Autoptimize detected, merge styles/scripts and minify HTML were disabled. ', 'swift-performance');
					}

				break;
				case 'configure-cache':
					$public	= get_post_types(array('publicly_queryable'=>true));
					$excluded	= array_diff(Swift_Performance::get_post_types(), array_merge(array('page'),(array)$public));

					if (!empty($excluded)){
						Swift_Performance::update_option('exclude-post-types', (array)$excluded);
					}

					// Prepare warmup table
					Swift_Performance::mysql_query("TRUNCATE " . SWIFT_PERFORMANCE_TABLE_PREFIX . 'warmup');
					Swift_Performance::get_prebuild_urls();

					$message = __('Done.', 'swift-performance');
				break;
				case 'report-js-error':
					Swift_Performance_Cache::clear_all_cache();
					Swift_Performance::update_option('merge-scripts', 0);
					Swift_Performance::update_option('minify-html', 0);
					die;
				break;
			}
		}

		wp_send_json(array(
			'dashicon'			=> $dashicon,
			'disable_autoconfig'	=> $disable_autoconfig,
			'message'			=> $message
		));
	}

	/**
	 * Go back to the previous step
	 */
	private function _revert_step(){
		$index 				= $this->current_step['index']-1;
		$this->current_step 		= $this->steps[$index];
		$this->current_step['index']	= $index;
	}

	/**
	 * Save settings
	 */
	private function _save_settings(){
		if (current_user_can('manage_options') && isset($_POST['swift-performance-setup-action'])){
			switch ($_POST['swift-performance-setup-action']){
				case 'purchase-key':
					//Verify purchase key via Swift API
					$validate = wp_remote_get(SWIFT_PERFORMANCE_API_URL . 'validate?purchase_key=' . $_POST['envato-purchase-key'] . '&site=' . urlencode(home_url()), array('timeout' => 60));

					//Handle HTTP errors
					if (is_wp_error($validate)) {
						self::print_notice(array('type' => 'error', 'message' => esc_html__('Couldn\'t connect to API server, please try again.', 'swift-performance')));
						// Go back;
						$this->_revert_step();
					}
					else{
						if ($validate['response']['code'] == 200){
							unset($this->steps[0]);
 							global $swift_performance_purchase_key;
 							$swift_performance_purchase_key = $_POST['envato-purchase-key'];
 							Swift_Performance::update_option('purchase-key', $swift_performance_purchase_key);
			                  }
			                  else if ($validate['response']['code'] == 401){
							// Go back;
							$this->_revert_step();
							self::print_notice(array('type' => 'error', 'message' => esc_html__('Purchase Key is invalid', 'swift-performance') ));
			                  }
						else {
							// Go back;
							$this->_revert_step();
							self::print_notice(array('type' => 'error', 'message' => sprintf(esc_html__('API server is not reachable. Error: %s', 'swift-performance'), $validate['response']['code']) ));
						}

					}
					break;
			}
		}
	}

	/**
	 * Includes the given template
	 * @param string $template
	 */
	private function _get_template_part($template) {
		if (strpos($template, '.') !== false){
			return false;
		}
		if (file_exists(SWIFT_PERFORMANCE_SETUP_DIR . 'templates/' . $template . '.php')){
			include SWIFT_PERFORMANCE_SETUP_DIR . 'templates/' . $template . '.php';
		}
	}

	/**
	 * Function to overwrite <title> tag
	 */
	public function wp_title(){
		return sprintf(esc_html__( '%s Setup Wizard - ', 'swift-performance' ), SWIFT_PERFORMANCE_PLUGIN_NAME);
	}

	/**
	 * Print admin notice
	 * @param array $message
	 */
	public static function print_notice($message){
		$class = ($message['type'] == 'success' ? 'updated' : ($message['type'] == 'warning' ? 'update-nag' : ($message['type'] == 'error' ? 'error' : 'notice')));
		echo '<div class="swift-performance-notice '.$class.'" style="padding:25px 10px 10px 10px;position: relative;display: block;"><span style="color:#888;position:absolute;top:5px;left:5px;">'.SWIFT_PERFORMANCE_PLUGIN_NAME.'</span>'.$message['message'].'</div>';
	}

	/**
	 * Check is loopback enabled
	 * @return boolean
	 */
	public static function check_loopback(){
		$response = wp_remote_get(home_url(), array('timeout' => 60, 'sslverify' => false));

		//Handle HTTP errors
		if (is_wp_error($response)) {
			$loopback = false;
		}
		else{
			if ($response['response']['code'] == 200){
				$loopback = true;
			}
			else {
				$loopback = false;
			}
		}

		return $loopback;
	}

	/**
	 * Get known plugin conflicts
	 * @return array
	 */
	public static function get_plugin_conflicts(){
		$plugin_conflicts = array();
		$active_plugins = get_option('active_plugins');
		foreach ($active_plugins as $plugin_file) {
			$source = file_get_contents(WP_PLUGIN_DIR . '/' . $plugin_file);
			// W3TC
			if (preg_match('~Plugin Name: W3 Total Cache~', $source)){
				$plugin_conflicts['hard'][$plugin_file] = 'W3 Total Cache';
			}

			// WP Supercache
			if (preg_match('~Plugin Name: WP Super Cache~', $source)){
				$plugin_conflicts['hard'][$plugin_file] = 'WP Super Cache';
			}

			// WPR
			if (preg_match('~Plugin Name: WP Rocket~', $source)){
				$plugin_conflicts['hard'][$plugin_file] = 'WP Rocket';
			}

			// WP Fastest Cache
			if (preg_match('~Plugin Name: WP Fastest Cache~', $source)){
				$plugin_conflicts['hard'][$plugin_file] = 'WP Fastest Cache';
			}

			// Autoptimize
			if (preg_match('~Plugin Name: Autoptimize~', $source)){
				$plugin_conflicts['soft']['autoptimize'] = true;
			}

			// Autoptimize
			if (preg_match('~Plugin Name: Better WordPress Minify~', $source)){
				$plugin_conflicts['hard'][$plugin_file] = 'Better WordPress Minify';
			}

			// WPtouch
			if (preg_match('~Plugin Name: WPtouch Mobile Plugin~', $source)){
				$plugin_conflicts['soft']['wp-touch'] = true;
			}

		}

		return $plugin_conflicts;
	}

	/**
	 * Echo "checked" if option is equals with the given value
	 * @param array|string $key
	 * @param string $value
	 */
	public static function is_checked($option, $value = '1'){
		if (is_array($option)){
			foreach ($option as $key => $value) {
				if (Swift_Performance::check_option($key, $value, '!=')){
					return;
				}
			}
			echo ' checked';
		}
		else if (Swift_Performance::check_option($option, $value)){
			echo ' checked';
		}
	}
}
