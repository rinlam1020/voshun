<?php
/*
Copyright 2015 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: http://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_license_key {
	public function __construct() {
		add_filter('thesis_more_menu', array($this, 'menu'), 1);
		add_action('admin_post_thesis_license_key', array($this, 'save'));
		if (!empty($_GET['canvas']) && $_GET['canvas'] == 'license_key')
			add_action('thesis_admin_canvas', array($this, 'admin'));
	}

	public function menu($menu) {
		$menu['license'] = array(
			'id' => 't_license',
			'text' => __('License Key', 'thesis'),
			'url' => admin_url('admin.php?page=thesis&canvas=license_key'));
		return $menu;
	}

	public function admin() {
		global $thesis;
		$license = $thesis->api->form->fields(array(
			'license_key' => array(
				'type' => 'text',
				'width' => 'long',
				'label' => __('Enter Your License Key', 'thesis'))), !empty($thesis->api->options['thesis_license_key']) ? array('license_key' => $thesis->api->options['thesis_license_key']) : array(), 't_license_', false, 3, 3);
		echo
			"\t\t<h3>", __('Thesis License Key', 'thesis'), "</h3>\n",
			"\t\t<form method=\"post\" action=\"", esc_url(admin_url('admin-post.php?action=thesis_license_key')), "\" id=\"t_license\">\n",
			"\t\t\t{$license['output']}\n",
			"\t\t\t<button data-style=\"button save\" name=\"save_options\" value=\"1\"><span data-style=\"dashicon big squeeze\">&#xf147;</span> ", $thesis->api->efn(__('Save License Key', 'thesis')), "</button>\n",
			"\t\t\t<button data-style=\"button delete inline\" name=\"delete_options\" value=\"1\"><span data-style=\"dashicon\">&#xf153;</span> ", $thesis->api->efn(__('Delete License Key', 'thesis')), "</button>\n",
			wp_nonce_field('thesis_license_key', '_wpnonce', true, false),
			"\t\t</form>\n";
	}

	public function save() {
		global $thesis;
		$thesis->wp->check();
		check_admin_referer('thesis_license_key');
		if (!empty($_POST['save_options']) && !empty($_POST['license_key']) && ($key = trim($_POST['license_key']))) {
			$license = preg_replace('/\W|\s/', '', $_POST['license_key']);
			update_option('thesis_license_key', $license);
			wp_cache_flush();
			$redirect = add_query_arg(array('message' => 'valid'), wp_get_referer());
		}
		elseif (!empty($_POST['delete_options'])) {
			delete_option('thesis_license_key');
			wp_cache_flush();
			$redirect = add_query_arg(array('message' => 'deleted'), wp_get_referer());
		}
		else
			wp_die(__('No data was received.', 'thesis'));
		wp_redirect($redirect);
		exit;
	}
}