<?php
/*
Copyright 2012 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: http://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_images {
	public function __construct() {
		global $thesis;
		$args = array(
			'title' => __('Thesis Upload Image', 'thesis'),
			'prefix' => 'thesis_images',
			'file_type' => 'image',
			'folder' => 'skin');
		$this->upload = new thesis_upload($args);
		add_action("{$args['prefix']}_after_thesis_iframe_form", array($this, 'get_images'));
		add_action("{$args['prefix']}_thesis_iframe_head", array($this, 'css'));
		add_action("{$args['prefix']}_thesis_iframe_body_bottom", array($this, 'script'));
		if ($thesis->environment == 'admin')
			add_action('admin_post_thesis_delete_image', array($this, 'delete_image'));
	}

	public function css() {
		echo
			"<style>\n",
			"body { padding: 0 24px; }\n",
			"#t_iframe_submit { margin-bottom: 24px; }\n",
			"#images { float:left; clear: both; padding: 13px 24px; background: #fff; box-shadow: 0 0 6px rgba(0,0,0,0.3); font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Segoe, \"Helvetica Neue\", Tahoma, sans-serif; }\n",
			"#images table { border-collapse: collapse; border-spacing: 0; }\n",
			"th { font: bold 18px/26px -apple-system, BlinkMacSystemFont, \"Segoe UI\", Segoe, \"Helvetica Neue\", Tahoma, sans-serif; }\n",
			"th, td { padding: 6px 12px; text-align: center; }\n",
			"tr th:first-child, tr td:first-child { padding-left: 0; }\n",
			"tr th:last-child, tr td:last-child { padding-right: 0; }\n",
			".code { font-family: Consolas, Menlo, Monaco, Verdana, sans-serif; font-size: 14px; }\n",
			"tr:hover { background: #fffdcc; }\n",
			"img { display: block; max-width: 200px; max-height: 150px; }\n",
			"[data-style~=\"button\"] { display: inline-block; font-size: inherit; line-height: 1em; text-decoration: none; vertical-align: top; color: #111; background-color: #d2d2d2; padding: 12px; border-style: solid; border-width: 1px 1px 4px 1px; border-color: rgba(0,0,0,0.1); border-bottom-color: rgba(0,0,0,0.25); border-radius: 12px; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; user-select: none; transition: background-color 0.15s ease, color 0.15s ease; }\n",
			"[data-style~=\"button\"]:hover { background-color: #dfdfdf; text-decoration: none; }\n",
			"[data-style~=\"button\"]:active, [data-style~=\"button\"].active { text-decoration: none; background-color: #dfdfdf; border-bottom-width: 2px; margin-top: 2px; outline: none; }\n",
			"[data-style~=\"button\"]:focus { box-shadow: none; outline: none; }\n",
			"[data-style~=\"delete\"] { color: #fff; text-shadow: 1px 1px 0 rgba(0,0,0,0.25); background-color: #d50b0b; }\n",
			"[data-style~=\"delete\"]:hover, [data-style~=\"delete\"]:active { color: #fff; background-color: #ed0c0c; }\n",
			"#images textarea { width: 300px; font-family: inherit; font-size: inherit; line-height: inherit; text-align: center; border: 0; background: transparent; vertical-align: bottom; resize: none; outline: none; }\n",
			"</style>\n";
	}

	public function get_images() {
		global $thesis;
		if (!defined('THESIS_USER_SKIN_IMAGES') || !defined('THESIS_USER_SKIN_IMAGES_URL')) return false;
		$img_dir = THESIS_USER_SKIN_IMAGES;
		$img_url = THESIS_USER_SKIN_IMAGES_URL;
		$files = @scandir($img_dir);
		$images = '';
		if ($files === false)
			return false;
		foreach ($files as $file)
			if (!in_array($file, array('.', '..'))) {
				$image_data = @getimagesize("$img_dir/$file");
				if (!empty($image_data)) {
					$image_url = trailingslashit($img_url). $file;
					$images .= 
						"\t\t\t<tr>\n".
						"\t\t\t\t<td><img src=\"". esc_url($image_url). "\" /></td>\n".
						"\t\t\t\t<td class=\"code\"><textarea rows=\"1\" readonly=\"readonly\">images/$file</textarea></td>\n".
						"\t\t\t\t<td class=\"number\">{$image_data[0]}</td>\n".
						"\t\t\t\t<td class=\"number\">{$image_data[1]}</td>\n".
						"\t\t\t\t<td><a onclick=\"if (!confirm('". __('Are you sure you want to delete this image?', 'thesis'). "')) return false\" data-style=\"button delete\" href=\"". esc_url(wp_nonce_url(admin_url('admin-post.php?action=thesis_delete_image&image='. urlencode($file)), 'thesis-delete-image')). "\">{$thesis->api->strings['delete']}</a></td>\n".
						"\t\t\t</tr>\n";
				}
			}
		echo
			"<div id=\"images\">\n",
			"\t<table>\n",
			"\t\t<thead>\n",
			"\t\t\t<tr class=\"highlight\">\n",
			"\t\t\t\t<th>" . __('Image', 'thesis') . "</th>\n",
			"\t\t\t\t<th>" . sprintf(__('%s Reference', 'thesis'), $thesis->api->base['css']) . "</th>\n",
			"\t\t\t\t<th class=\"number\">" . __('Width (px)', 'thesis') . "</th>\n",
			"\t\t\t\t<th class=\"number\">" . __('Height (px)', 'thesis') . "</th>\n",
			"\t\t\t</tr>\n",
			"\t\t</thead>\n",
			"\t\t<tbody>\n",
			$images,
			"\t\t</tbody>\n",
			"\t</table>\n",
			"</div>\n";
	}

	public function delete_image() {
		if (!current_user_can('manage_options') && !wp_verify_nonce($_REQUEST['_wpnonce'], 'thesis-delete-image'))
			wp_die(__('You cannot delete images.', 'thesis'));
		$file = THESIS_USER_SKIN_IMAGES. '/'. urldecode($_GET['image']);
		$thesis_realpath = rtrim(realpath(THESIS_USER_SKIN_IMAGES), DIRECTORY_SEPARATOR). DIRECTORY_SEPARATOR;
		$file_realpath = realpath($file); // realpath() also implicitly performs file_exists()
		if ($file_realpath === FALSE || strpos($file_realpath, $thesis_realpath) !== 0 || getimagesize($file) === false)
			wp_die(__('You cannot perform this action.', 'thesis'));
		@unlink($file);
		wp_redirect(admin_url("admin-post.php?action=thesis_images_window&window_nonce=". wp_create_nonce('thesis_upload_iframe')));
		exit;
	}

	public function script() {
		echo
			"<script>\n",
			"jQuery(document).ready(function(){",
			"\tjQuery('td.code textarea').click(function(){\n",
			"\t\tjQuery(this).select();\n",
			"\t});\n",
			"})",
			"</script>\n";
	}
}