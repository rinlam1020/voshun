<?php
/*
Copyright 2012 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: http://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_html_head extends thesis_box {
	public $type = 'rotator';
	public $root = true;
	public $head = true;

	protected function translate() {
		global $thesis;
		$this->title = sprintf(__('%s Head', 'thesis'), $thesis->api->base['html']);
	}

	public function html() {
		global $thesis;
		$attributes = apply_filters('thesis_head_attributes', '');
		echo
			"<head", (!empty($attributes) ? " $attributes" : ''), ">\n",
			(($charset = apply_filters('thesis_meta_charset', (!empty($thesis->api->options['blog_charset']) ? $thesis->api->options['blog_charset'] : 'utf-8'))) !== false ?
			"<meta charset=\"". esc_attr(wp_strip_all_tags($charset)). "\" />\n" : '');
			$thesis->api->hook('hook_head_top');
			$this->rotator();
			$thesis->api->hook('hook_head_bottom');
		echo
			"</head>\n";
	}
}

class thesis_title_tag extends thesis_box {
	public $head = true;
	private $separator = '&#8212;';

	protected function translate() {
		global $thesis;
		$this->title = __($thesis->api->strings['title_tag'], 'thesis');
	}

	protected function options() {
		global $thesis;
		return empty($thesis->wpseo) ? array(
			'branded' => array(
				'type' => 'checkbox',
				'label' => sprintf(__('%s Branding', 'thesis'), $this->title),
				'options' => array(
					'on' => sprintf(__('Append site name to <code>&lt;title&gt;</code> tags %s', 'thesis'), __($thesis->api->strings['not_recommended'], 'thesis')))),
			'separator' => array(
				'type' => 'text',
				'width' => 'tiny',
				'label' => __($thesis->api->strings['character_separator'], 'thesis'),
				'tooltip' => __('This character will appear between the title and site name (where appropriate).', 'thesis'),
				'placeholder' => $this->separator)) : false;
	}

	protected function post_meta() {
		global $thesis;
		return empty($thesis->wpseo) ? array(
			'title' => $this->title,
			'fields' => array(
				'title' => array(
					'type' => 'text',
					'width' => 'full',
					'label' => sprintf(__('Custom %s', 'thesis'), $this->title),
					'tooltip' => sprintf(__('By default, Thesis uses the title of your post as the contents of the %1$s tag. You can override this and further extend your on-page %2$s by entering your own %1$s tag here.', 'thesis'), '<code>&lt;title&gt;</code>', $thesis->api->base['seo']),
					'counter' => __($thesis->api->strings['title_counter'], 'thesis')))) : false;
	}

	protected function term_options() {
		global $thesis;
		return empty($thesis->wpseo) ? array(
			'title' => array(
				'type' => 'text',
				'label' => $this->title,
				'counter' => __($thesis->api->strings['title_counter'], 'thesis'))) : false;
	}

	public function html() {
		global $thesis, $wp_query; #wp
		$site = !empty($thesis->api->options['blogname']) ? htmlspecialchars_decode($thesis->api->options['blogname'], ENT_QUOTES) : '';
		$separator = !empty($this->options['separator']) ? trim($this->options['separator']) : $this->separator;
		$title = !empty($this->post_meta['title']) ?
			$this->post_meta['title'] : (!empty($this->term_options['title']) ?
			$this->term_options['title'] : (!!$wp_query->is_home || is_front_page() ? (!empty($thesis->api->home_seo->options['title']) ?
			$thesis->api->home_seo->options['title'] : (($tagline = !empty($thesis->api->options['blogdescription']) ? htmlspecialchars_decode($thesis->api->options['blogdescription']) : false) ?
			"$site $separator $tagline" :
			$site)) : (!!$wp_query->is_search ?
			__($thesis->api->strings['search'], 'thesis'). ': '. $wp_query->query_vars['s'] :
			wp_title('', false))));
		$title .= ($wp_query->query_vars['paged'] > 1 ?
			" $separator ". __($thesis->api->strings['page'], 'thesis'). " {$wp_query->query_vars['paged']}" : '').
			(!empty($this->options['branded']['on']) && !$wp_query->is_home ?
			" $separator $site" : '');
		echo
			'<title>',
			($thesis->wpseo ?
				wp_title('', false) :
				trim($thesis->api->efh(apply_filters($this->_class, $title, $separator)))),
			"</title>\n";
	}
}

class thesis_meta_description extends thesis_box {
	public $head = true;

	protected function translate() {
		global $thesis;
		$this->title = __($thesis->api->strings['meta_description'], 'thesis');
	}

	protected function post_meta() {
		global $thesis;
		return array(
			'title' => $this->title,
			'fields' => array(
				'description' => array(
					'type' => 'textarea',
					'rows' => 2,
					'label' => $this->title,
					'tooltip' => sprintf(__('Entering a %1$s description is just one more thing you can do to seize an on-page %2$s opportunity. Keep in mind that a good %1$s description is both informative and concise.', 'thesis'), '<code>&lt;meta&gt;</code>', $thesis->api->base['seo']),
					'counter' => __($thesis->api->strings['description_counter'], 'thesis'))));
	}

	protected function term_options() {
		global $thesis;
		return array(
			'description' => array(
				'type' => 'textarea',
				'rows' => 2,
				'label' => $this->title,
				'counter' => __($thesis->api->strings['description_counter'], 'thesis')));
	}

	public function html() {
		global $thesis, $wp_query, $post;
		$description = !empty($wp_query->is_singular) ? (!empty($this->post_meta['description']) ?
			$this->post_meta['description'] : (!empty($post->post_excerpt) ?
			$post->post_excerpt :
			$thesis->api->trim_excerpt($post->post_content, true))) : (!empty($this->term_options['description']) ?
			$this->term_options['description'] : (!!$wp_query->is_home ? (!empty($thesis->api->home_seo->options['description']) ?
			$thesis->api->home_seo->options['description'] : (!empty($thesis->api->options['blogdescription']) ?
			htmlspecialchars_decode($thesis->api->options['blogdescription'], ENT_QUOTES) : false)) : false));
		$description = apply_filters($this->_class, $description);
		if (!empty($description))
			echo "<meta name=\"description\" content=\"", trim($thesis->api->efh($description)), "\" />\n";
	}
}

class thesis_meta_keywords extends thesis_box {
	public $head = true;

	protected function translate() {
		global $thesis;
		$this->title = __($thesis->api->strings['meta_keywords'], 'thesis');
	}

	protected function options() {
		global $thesis;
		return array(
			'tags' => array(
				'type' => 'checkbox',
				'options' => array(
					'on' => sprintf(__('Automatically use tags as keywords on posts %s', 'thesis'), __($thesis->api->strings['not_recommended'], 'thesis')))));
	}

	protected function post_meta() {
		global $thesis;
		return array(
			'title' => $this->title,
			'fields' => array(
				'keywords' => array(
					'type' => 'text',
					'width' => 'full',
					'label' => $this->title,
					'tooltip' => sprintf(__('Like the %1$s description, %1$s keywords are yet another on-page %2$s opportunity. Enter a few keywords that are relevant to your article, but don&#8217;t go crazy here&#8212;just a few should suffice.', 'thesis'), '<code>&lt;meta&gt;</code>', $thesis->api->base['seo']))));
	}

	protected function term_options() {
		return array(
			'keywords' => array(
				'type' => 'text',
				'label' => $this->title));
	}

	public function html() {
		global $thesis, $wp_query;
		$keywords = !empty($this->post_meta['keywords']) ?
			$this->post_meta['keywords'] : (!empty($this->term_options['keywords']) ?
			$this->term_options['keywords'] : (!!$wp_query->is_home && !empty($thesis->api->home_seo->options['keywords']) ?
			$thesis->api->home_seo->options['keywords'] : false));
		if (empty($keywords) && $wp_query->is_single && !empty($this->options['tags']['on'])) {
			$tags = array();
			if (is_array($post_tags = get_the_tags())) #wp
				foreach ($post_tags as $tag)
					$tags[] = $tag->name;
			if (!empty($tags))
				$keywords = implode(', ', $tags);
		}
		$keywords = apply_filters($this->_class, $keywords);
		if (!empty($keywords))
			echo "<meta name=\"keywords\" content=\"", trim($thesis->api->efh($keywords)), "\" />\n";
	}
}

class thesis_meta_robots extends thesis_box {
	public $head = true;
	public $robots = array();

	protected function translate() {
		global $thesis;
		$this->title = __($thesis->api->strings['meta_robots'], 'thesis');
	}

	protected function options() {
		global $thesis;
		$fields = $default = array(
			'robots' => array(
				'type' => 'checkbox',
				'options' => array(
					'noindex' => '<code>noindex</code>',
					'nofollow' => '<code>nofollow</code>',
					'noarchive' => '<code>noarchive</code>')));
		$default['robots']['default'] = array('noindex' => true);
		return array(
			'directory' => array(
				'type' => 'checkbox',
				'label' => __('Directory Tags (Sitewide)', 'thesis'),
				'tooltip' => sprintf(__('For %s purposes, we recommend turning on both of these options.', 'thesis'), $thesis->api->base['seo']),
				'options' => array(
					'noodp' => '<code>noodp</code>',
					'noydir' => '<code>noydir</code>'),
				'default' => array(
					'noodp' => true,
					'noydir' => true)),
			'robots' => array(
				'type' => 'object_set',
				'label' => __('Set Robots By Page Type', 'thesis'),
				'select' => __('Select a page type:', 'thesis'),
				'objects' => array(
					'category' => array(
						'type' => 'object',
						'label' => __('Category', 'thesis'),
						'fields' => $fields),
					'post_tag' => array(
						'type' => 'object',
						'label' => __('Tag', 'thesis'),
						'fields' => $fields),
					'tax' => array(
						'type' => 'object',
						'label' => __('Taxonomy', 'thesis'),
						'fields' => $fields),
					'author' => array(
						'type' => 'object',
						'label' => __('Author', 'thesis'),
						'fields' => $default),
					'day' => array(
						'type' => 'object',
						'label' => __('Daily Archive', 'thesis'),
						'fields' => $default),
					'month' => array(
						'type' => 'object',
						'label' => __('Monthly Archive', 'thesis'),
						'fields' => $default),
					'year' => array(
						'type' => 'object',
						'label' => __('Yearly Archive', 'thesis'),
						'fields' => $default),
					'blog' => array(
						'type' => 'object',
						'label' => __('Blog', 'thesis'),
						'fields' => array(
							'robots' => array(
								'type' => 'checkbox',
								'options' => array(
									'noindex' => '<code>noindex</code> (not recommended)',
									'nofollow' => '<code>nofollow</code> (not recommended)',
									'noarchive' => '<code>noarchive</code> (not recommended)')))))));
	}

	protected function post_meta() {
		global $thesis;
		return array(
			'title' => $this->title,
			'fields' => array(
				'robots' => array(
					'type' => 'checkbox',
					'label' => $this->title,
					'tooltip' => sprintf(__('Fine-tune the %1$s on every page of your site with these handy robots meta tag selectors.', 'thesis'), $thesis->api->base['seo']),
					'options' => array(
						'noindex' => sprintf(__('<code>noindex</code> %s', 'thesis'), __($thesis->api->strings['this_page'], 'thesis')),
						'nofollow' => sprintf(__('<code>nofollow</code> %s', 'thesis'), __($thesis->api->strings['this_page'], 'thesis')),
						'noarchive' => sprintf(__('<code>noarchive</code> %s', 'thesis'), __($thesis->api->strings['this_page'], 'thesis'))))));
	}

	protected function term_options() {
		global $thesis;
		return array(
			'robots' => array(
				'type' => 'checkbox',
				'label' => $this->title,
				'options' => array(
					'noindex' => sprintf(__('<code>noindex</code> %s', 'thesis'), __($thesis->api->strings['this_page'], 'thesis')),
					'nofollow' => sprintf(__('<code>nofollow</code> %s', 'thesis'), __($thesis->api->strings['this_page'], 'thesis')),
					'noarchive' => sprintf(__('<code>noarchive</code> %s', 'thesis'), __($thesis->api->strings['this_page'], 'thesis')))));
	}

	protected function construct() {
		add_filter("thesis_term_option_{$this->_class}_robots", array($this, 'get_term_defaults'), 10, 2);
	}

	public function get_term_defaults($default, $taxonomy) {
		if (empty($taxonomy)) return $default;
		$taxonomy = $taxonomy != 'category' && $taxonomy != 'post_tag' ? 'tax' : $taxonomy;
		return !empty($this->options[$taxonomy]) && is_array($this->options[$taxonomy]) ? $this->options[$taxonomy] : $default;
	}

	public function preload() {
		global $thesis, $wp_query;
		$options = $thesis->api->get_options($this->_options(), $this->options);
		$page_type = $wp_query->is_archive ? ($wp_query->is_category ?
			'category' : ($wp_query->is_tag ?
			'post_tag' : ($wp_query->is_tax ?
			'tax' : ($wp_query->is_author ?
			'author' : ($wp_query->is_day ?
			'day' : ($wp_query->is_month ?
			'month' : ($wp_query->is_year ?
			'year' : false))))))) : false;
		$this->robots = !empty($this->post_meta['robots']) ?
			$this->post_meta['robots'] : (!empty($this->term_options['robots']) ?
			$this->term_options['robots'] : ($wp_query->is_home && empty($page_type) && !empty($options['blog']['robots']) ?
			$options['blog']['robots'] : ($wp_query->is_search || $wp_query->is_404 ?
			array('noindex' => true, 'nofollow' => true, 'noarchive' => true) : (!empty($page_type) && !empty($options[$page_type]['robots']) ?
			$options[$page_type]['robots'] : (!empty($options[$page_type]) ? $options[$page_type] : false)))));
		if (!empty($options['directory']['noodp']))
			$this->robots['noodp'] = true;
		if (!empty($options['directory']['noydir']))
			$this->robots['noydir'] = true;
		if (!empty($this->robots) && !empty($this->robots['noindex']))
			add_filter('thesis_canonical_link', '__return_false');
	}

	public function html() {
		$content = array();
		if (!empty($this->robots) && is_array($this->robots))
			foreach ($this->robots as $tag => $value)
				if ($value)
					$content[] = $tag;
		if (!empty($content))
			echo '<meta name="robots" content="', apply_filters($this->_class, implode(', ', $content)), "\" />\n";
	}
}

class thesis_stylesheets_link extends thesis_box {
	public $head = true;

	protected function translate() {
		$this->title = __('Stylesheets', 'thesis');
	}

	public function html() {
		global $thesis;
		$scripts = $styles = $links = array();
		$font_script = apply_filters('thesis_font_script', array()); // array filter
		$font_stylesheet = apply_filters('thesis_font_stylesheet', array()); // array filter
		// Queue up additional scripts and stylesheets to be displayed before the main stylesheet
		if (!empty($font_script))
			if (is_array($font_script))
				foreach ($font_script as $js)
					$scripts[] = "<script src=\"". esc_url($js). "\"></script>";
			else
				$scripts[] = "<script src=\"". esc_url($font_script). "\"></script>";
		if (!empty($font_stylesheet))
			if (is_array($font_stylesheet))
				foreach ($font_stylesheet as $css)
					$styles[] = "<link rel=\"stylesheet\" href=\"". esc_url($css). "\" />";
			else
				$styles[] = "<link rel=\"stylesheet\" href=\"". esc_url($font_stylesheet). "\" />";
		foreach (apply_filters($this->_class, array(array('url' => THESIS_USER_SKIN_URL. '/css.css'))) as $sheet)
			if (!empty($sheet['url']))
				$links[] = '<link rel="stylesheet" href="'. esc_url($sheet['url']). '" />';
		// Output added scripts and sheets, beginning with an optional meta viewport declaration
		echo (($viewport = apply_filters('thesis_meta_viewport', 'width=device-width, initial-scale=1')) ?
			"<meta name=\"viewport\" content=\"". esc_attr(wp_strip_all_tags(is_array($viewport) ? implode(', ', array_filter($viewport)) : $viewport)). "\" />\n" : '');
		// Hook for including other CSS or prefetching URLs
		$thesis->api->hook('hook_before_stylesheet');
		if (!empty($scripts))
			echo implode("\n", $scripts). "\n";
		if (!empty($styles))
			echo implode("\n", $styles). "\n";
		// Only output the main stylesheet in the appropriate context
		if (!empty($links) && !((is_user_logged_in() && current_user_can('manage_options')) && (!empty($_GET['thesis_editor']) && $_GET['thesis_editor'] === '1' || !empty($_GET['thesis_canvas']) && in_array($_GET['thesis_canvas'], array(1, 2)))))
			echo implode("\n", $links), "\n";
	}
}

class thesis_canonical_link extends thesis_box {
	public $head = true;
	public $links = array();

	protected function translate() {
		global $thesis;
		$this->title = sprintf(__('Canonical %s', 'thesis'), $thesis->api->base['url']);
	}

	protected function post_meta() {
		global $thesis;
		return array(
			'title' => $this->title,
			'fields' => array(
				'url' => array(
					'type' => 'text',
					'width' => 'full',
					'code' => true,
					'label' => sprintf(__('%1$s %2$s', 'thesis'), $this->title, __($thesis->api->strings['override'], 'thesis')),
					'tooltip' => sprintf(__('Although Thesis auto-generates proper canonical %1$ss for every page of your site, there are certain situations where you may wish to supply your own canonical %1$s for a given page.<br /><br />For example, you may want to run a checkout page with %2$s, and because of this, you may only want this page to be accessible with the %3$s protocol. In this case, you&#8217;d want to supply your own canonical %1$s, which would include %3$s.', 'thesis'), $thesis->api->base['url'], $thesis->api->base['ssl'], '<code>https</code>'),
					'description' => __($thesis->api->strings['include_http'], 'thesis'))));
	}

	public function preload() {
		$this->compatibility();
		$this->links();
	}

	private function compatibility() {
		global $thesis;
		if (!empty($thesis->wpseo) && class_exists('WPSEO_Frontend')) {
			$yoast = WPSEO_Frontend::get_instance();
			remove_action('wpseo_head', array($yoast, 'canonical'), 20);
		}
		add_filter('aioseop_canonical_url', '__return_false');
	}

	private function links() {
		global $wp_query, $wp_rewrite;
		$params = array();
		$pagination = $max = $current = false;
		$slash = '';
		$query_arg = 'paged';
		$base_url = $wp_query->is_singular ?
			get_permalink() :
			html_entity_decode(get_pagenum_link());
		$url = explode('?', $base_url);
		$base_url = trailingslashit($url[0]). ($wp_rewrite->using_index_permalinks() && !strpos($base_url, 'index.php') ?
			'index.php/' : '');
		if (isset($url[1])) {
			wp_parse_str($url[1], $url_params);
			$params = array_merge($params, urlencode_deep($url_params));
		}
		if ($wp_query->is_singular) {
			global $post, $page, $multipage, $numpages;
			setup_postdata($post);
			$base_url = !empty($this->post_meta['url']) ?
				trailingslashit($this->post_meta['url']) : $base_url;
			if (!empty($multipage)) {
				$pagination = true;
				$max = !empty($numpages) ? intval($numpages) : 1;
				$current = !empty($page) ? intval($page) : 1;
				$query_arg = 'page';
				if (!empty($params[$query_arg]))
					unset($params[$query_arg]);
			}
			else
				$this->links['canonical'] = "<link rel=\"canonical\" href=\"". esc_url_raw(!empty($params) ?
					add_query_arg($params, $base_url) :
					$base_url). "\" />";
		}
		elseif ($wp_query->is_archive || $wp_query->is_posts_page || ($wp_query->is_home && !$wp_query->is_posts_page)) {
			$pagination = true;
			$slash = trailingslashit($wp_rewrite->pagination_base);
			$max = !empty($wp_query->max_num_pages) ? intval($wp_query->max_num_pages) : 1;
			$current = !empty($wp_query->query['paged']) ? intval($wp_query->query['paged']) : 1;
		}
		if (!empty($pagination))
			foreach (array($current - 1, $current, $current + 1) as $n)
				if ($n >= 1 && $n <= $max) {
					$link = esc_url_raw(!empty($params) ?
						add_query_arg(array_merge($params, $n == 1 ? array() : array($query_arg => $n)), $base_url) :
						($n > 1 ? "$base_url$slash$n/" : $base_url));
					if ($n < $current)
						$this->links['prev'] = "<link rel=\"prev\" href=\"$link\" />";
					elseif ($n == $current)
						$this->links['canonical'] = "<link rel=\"canonical\" href=\"$link\" />";
					elseif ($n > $current)
						$this->links['next'] = "<link rel=\"next\" href=\"$link\" />";
				}
	}

	public function html() {
		if (!empty($this->links) && !empty($this->links['canonical']) && !apply_filters($this->_class, true))
			unset($this->links['canonical']);
		if (!empty($this->links))
			echo implode("\n", $this->links). "\n";
	}
}

class thesis_html_head_scripts extends thesis_box {
	public $head = true;

	protected function translate() {
		$this->title = __('Head Scripts', 'thesis');
	}

	protected function options() {
		return array(
			'scripts' => array(
				'type' => 'textarea',
				'rows' => 8,
				'code' => true,
				'label' => __('Scripts', 'thesis'),
				'tooltip' => __('If you wish to add scripts that will only function properly when placed in the document <code>&lt;head&gt;</code>, you should add them here.<br /><br /><strong>Note:</strong> Only do this if you have no other option. Scripts placed in the <code>&lt;head&gt;</code> can have a negative impact on site performance.', 'thesis'),
				'description' => __('include <code>&lt;script&gt;</code> and other tags as necessary', 'thesis')));
	}

	public function html() {
		if (empty($this->options['scripts'])) return;
		echo trim($this->options['scripts']), "\n";
	}
}

class thesis_favicon extends thesis_box {
	public $type = false;
	protected $filters = array(
		'menu' => 'site',
		'docs' => 'http://diythemes.com/thesis/rtfm/admin/site/add-favicon/',
		'priority' => 20);

	protected function translate() {
		$this->title = __('Favicon', 'thesis');
		$this->tooltip = sprintf(__('If you don&#39;t already have a favicon, you can create one with this handy <a href="%1$s" target="_blank" rel="noopener">online tool</a>.', 'thesis'), 'https://www.favicon-generator.org/');
		$this->filters['description'] = __('Upload a favicon', 'thesis');
	}

	protected function class_options() {
		return array(
			'image' => array(
				'type' => 'image_upload',
				'label' =>  __('Upload a Favicon', 'thesis'),
				'tooltip' => $this->tooltip,
				'upload_label' => __('Upload Image', 'thesis'),
				'prefix' => $this->_class));
	}

	protected function construct() {
		global $thesis;
		if ($thesis->environment == 'admin') {
			new thesis_upload(array(
				'title' => __('Upload Image', 'thesis'),
				'prefix' => $this->_class,
				'file_type' => 'image',
				'show_delete' => !empty($this->class_options['image']['url']) ? true : false,
				'delete_text' => __('Remove Image', 'thesis'),
				'save_callback' => array($this, 'save')));
			add_action("{$this->_class}_before_thesis_iframe_form", array($this, '_script'));
		}
		elseif (empty($thesis->environment))
			add_action('hook_head', array($this, 'html'));
	}

	public function _script() {
		global $thesis;
		$url = !empty($_GET['url']) ?
			urldecode($_GET['url']) : (!empty($this->class_options['image']['url']) ?
			$this->class_options['image']['url'] : false);
		if (!!$url)
			echo "<img style=\"max-width: 32px;\" id=\"", esc_attr($this->_id), "_box_image\" src=\"", $thesis->api->url_current(esc_url($url)), "\" />\n";
	}

	public function admin_init() {
		add_action('admin_head', array($this, 'admin_css'));
	}

	public function admin_css() {
		echo
			"<style>\n",
			"#t_canvas #save_options { display: none; }\n",
			"</style>\n";
	}

	public function html() {
		global $thesis;
		$url = !empty($this->class_options['image']['url']) ?
			$this->class_options['image']['url'] :
			THESIS_IMAGES_URL. '/favicon.ico';
		echo "<link rel=\"shortcut icon\" href=\"", $thesis->api->url_current(esc_url($url)), "\" />\n";
	}

	public function save($image, $delete) {
		global $thesis;
		$save = !empty($image) ? $thesis->api->set_options($this->_class_options(), array('image' => $image)) : false;
		if (empty($save)) {
			if (!empty($delete))
				delete_option($this->_class);
		}
		else
			update_option($this->_class, $save);
	}
}

class thesis_html_body extends thesis_box {
	public $type = 'rotator';
	public $root = true;
	public $switch = true;

	protected function translate() {
		global $thesis;
		$this->title = sprintf(__('%s Body', 'thesis'), $thesis->api->base['html']);
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(false, false, true);
		unset($html['id']);
		return array_merge($html, array(
			'wp' => array(
				'type' => 'checkbox',
				'label' => __('Automatic WordPress Body Classes', 'thesis'),
				'tooltip' => sprintf(__('WordPress can output body classes that allow you to target specific types of posts and content more easily. You may experience a %1$s naming conflict if you use this option (and most of the output adds unnecessary weight to the %2$s), so we do not recommend it.', 'thesis'), $thesis->api->base['class'], $thesis->api->base['html']),
				'options' => array(
					'auto' => __('Use automatically-generated WordPress <code>&lt;body&gt;</code> classes', 'thesis')))));
	}

	protected function post_meta() {
		global $thesis;
		return array(
			'title' => __('Custom Body Class', 'thesis'),
			'fields' => array(
				'class' => array(
					'type' => 'text',
					'width' => 'medium',
					'code' => true,
					'label' => __($thesis->api->strings['html_class'], 'thesis'),
					'tooltip' => sprintf(__('If you want to style this post individually, you should enter a %1$s name here. Anything you enter here will appear on this page&#8217;s <code>&lt;body&gt;</code> tag. Separate multiple classes with spaces.<br /></br /><strong>Note:</strong> %1$s names cannot begin with numbers!', 'thesis'), $thesis->api->base['class']))));
	}

	protected function template_options() {
		global $thesis;
		return array(
			'title' => __('Body Class', 'thesis'),
			'fields' => array(
				'class' => array(
					'type' => 'text',
					'width' => 'medium',
					'code' => true,
					'label' => __('Template Body Class', 'thesis'),
					'tooltip' => sprintf(__('If you wish to provide a custom %1$s for this template, you can do that here. Please note that a naming conflict could cause unintended results, so be careful when choosing a %1$s name.', 'thesis'), $thesis->api->base['class']))));
	}

	public function html() {
		global $thesis;
		echo "<body", $this->classes(), (!empty($this->options['attributes']) ? ' '. trim($this->options['attributes']) : ''), ">\n";
		$thesis->api->hook('hook_before_html');
		$this->rotator();
		$thesis->api->hook('hook_after_html');
		echo "</body>\n";
	}

	private function classes() {
		$classes = array();
		if (!empty($this->post_meta['class']))
			$classes[] = trim($this->post_meta['class']);
		if (!empty($this->template_options['class']))
			$classes[] = trim($this->template_options['class']);
		if (!empty($this->options['class']))
			$classes[] = trim($this->options['class']);
		$classes = is_array($filtered = apply_filters("{$this->_class}_class", $classes)) && !empty($filtered) ? $filtered : $classes;
		if (!empty($this->options['wp']['auto']) || apply_filters('thesis_use_wp_body_classes', false))
			$classes = is_array($wp = get_body_class()) ? array_merge($classes, $wp) : $classes;
		return !empty($classes) ?
			' class="'. trim(esc_attr(implode(' ', $classes))). '"' : '';
	}
}

class thesis_html_container extends thesis_box {
	public $type = 'rotator';

	protected function translate() {
		global $thesis;
		$this->title = sprintf(__('%1$s %2$s', 'thesis'), $thesis->api->base['html'], $this->name = __('Container', 'thesis'));
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array(
			'div' => 'div',
			'p' => 'p',
			'section' => 'section',
			'article' => 'article',
			'header' => 'header',
			'footer' => 'footer',
			'aside' => 'aside',
			'span' => 'span',
			'nav' => 'nav',
			'none' => sprintf(__('no %s wrap', 'thesis'), $thesis->api->base['html'])), 'div', true);
		$html['html']['dependents'] =
			array('div', 'p', 'section', 'article', 'header', 'footer', 'aside', 'span', 'nav');
		$html['id']['parent'] = $html['class']['parent'] = $html['attributes']['parent'] =
			array('html' => array('div', 'p', 'section', 'article', 'header', 'footer', 'aside', 'span', 'nav'));
		return $html;
	}

	public function html($args = array()) {
		global $thesis;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", $depth = !empty($depth) ? $depth : 0);
		$html = !empty($this->options['html']) ? esc_attr($this->options['html']) : 'div';
		$hook = trim(esc_attr(!empty($this->options['_id']) ?
			$this->options['_id'] : (!empty($this->options['hook']) ?
			$this->options['hook'] : '')));
		if (!empty($hook))
			$thesis->api->hook("hook_before_$hook");
		if ($html != 'none') {
			echo
				"$tab<$html", (!empty($this->options['id']) ? ' id="'. trim(esc_attr($this->options['id'])). '"' : ''),
				(!empty($this->options['class']) ? ' class="'. trim(esc_attr($this->options['class'])). '"' : ''),
				(!empty($this->options['attributes']) ? ' '. trim($this->options['attributes']) : ''), ">\n";
			if (!empty($hook))
				$thesis->api->hook("hook_top_$hook");
		}
		$this->rotator(array_merge($args, array('depth' => $html == 'none' ? $depth : $depth + 1)));
		if ($html != 'none') {
			if (!empty($hook))
				$thesis->api->hook("hook_bottom_$hook");
			echo
				"$tab</$html>\n";
		}
		if (!empty($hook))
			$thesis->api->hook("hook_after_$hook");
	}
}

class thesis_site_title extends thesis_box {
	protected function translate() {
		$this->title = __('Site Title', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array('div' => 'div', 'p' => 'p'), 'div');
		$html['html']['tooltip'] = __('Your site title will be contained within <code>&lt;h1&gt;</code> tags on your home page, but the tag you specify here will be used on all other pages.', 'thesis');
		unset($html['id'], $html['class']);
		return $html;
	}

	public function html($args = array()) {
		global $thesis, $wp_query; #wp
		$title = trim($thesis->api->efn(
			apply_filters($this->_class, !empty($thesis->api->options['blogname']) ?
				htmlspecialchars_decode($thesis->api->options['blogname'], ENT_QUOTES) : false)));
		$logo = ($logo = apply_filters("{$this->_class}_logo", $title)) ?
			strip_tags(html_entity_decode($logo), '<img>') : false;
		if (empty($title) && empty($logo)) return;
		extract($args = is_array($args) ? $args : array());
		$html = apply_filters("{$this->_class}_html", $wp_query->is_home || is_front_page() ?
			'h1' : (!empty($this->options['html']) ?
			esc_attr($this->options['html']) :
			'div'));
		$title = !empty($logo) ? strip_tags($logo, '<img>') : $title;
		echo
			str_repeat("\t", !empty($depth) ? $depth : 0),
			"<$html id=\"site_title\">",
			(apply_filters("{$this->_class}_link", true) ?
			"<a href=\"". esc_url(home_url()). "\">$title</a>" :
			$text),
			"</$html>\n";
	}
}

class thesis_site_tagline extends thesis_box {
	protected function translate() {
		$this->title = __('Site Tagline', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array('div' => 'div', 'p' => 'p'), 'div');
		unset($html['id'], $html['class']);
		return $html;
	}

	protected function construct() {
		global $thesis;
		$thesis->wp->filter($this->_class, array(
			'convert_chars' => false,
			'convert_smilies' => false));
		add_filter($this->_class, array($thesis->api, 'efn'));
	}

	public function html($args = array()) {
		global $thesis;
		if (!($text = trim(apply_filters($this->_class, !empty($thesis->api->options['blogdescription']) ?
			htmlspecialchars_decode($thesis->api->options['blogdescription'], ENT_QUOTES) : false)))) return;
		extract($args = is_array($args) ? $args : array());
		$html = apply_filters("{$this->_class}_html", !empty($this->options['html']) ? esc_attr($this->options['html']) : 'div');
		echo
			str_repeat("\t", !empty($depth) ? $depth : 0),
			"<$html id=\"site_tagline\">$text</$html>\n";
	}
}

class thesis_post_box extends thesis_box {
	public $type = 'rotator';
	public $dependents = array(
		'thesis_post_headline',
		'thesis_post_date',
		'thesis_post_author',
		'thesis_post_author_avatar',
		'thesis_post_author_description',
		'thesis_post_edit',
		'thesis_post_content',
		'thesis_post_excerpt',
		'thesis_post_num_comments',
		'thesis_post_categories',
		'thesis_post_tags',
		'thesis_post_image',
		'thesis_post_thumbnail');
	public $children = array(
		'thesis_post_headline',
		'thesis_post_author',
		'thesis_post_edit',
		'thesis_post_content');

	protected function translate() {
		$this->title = $this->name = __('Post Box', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array(
			'div' => 'div',
			'section' => 'section',
			'article' => 'article'), 'div');
		unset($html['id']);
		$html['class']['tooltip'] = sprintf(__('This box already contains a %1$s, <code>post_box</code>. If you wish to add an additional %1$s, you can do that here. Separate multiple %1$ses with spaces.%2$s', 'thesis'), $thesis->api->base['class'], __($thesis->api->strings['class_note'], 'thesis'));
		return array_merge($html, array(
			'wp' => array(
				'type' => 'checkbox',
				'label' => __($thesis->api->strings['auto_wp_label'], 'thesis'),
				'tooltip' => __($thesis->api->strings['auto_wp_tooltip'], 'thesis'),
				'options' => array(
					'auto' => __($thesis->api->strings['auto_wp_option'], 'thesis'))),
			'schema' => $thesis->api->schema->select()));
	}

	public function html($args = array()) {
		global $thesis, $wp_query, $post; #wp
		extract($args = is_array($args) ? $args : array());
		$classes = array();
		$tab = str_repeat("\t", $depth = !empty($depth) ? $depth : 0);
		$post_count = !empty($post_count) ? $post_count : false;
		$html = !empty($this->options['html']) ? esc_attr($this->options['html']) : 'div';
		if (!empty($this->options['class']))
			$classes[] = trim($this->options['class']);
		if (empty($post_count) || $post_count == 1)
			$classes[] = 'top';
		if (!empty($this->options['wp']['auto']))
			$classes = is_array($wp = get_post_class()) ? $classes + $wp : $classes;
/*
		Post Box hierarchy for Schema implementation:
		1. Direct: Schema defined at content level via post meta
		2. Inheritance: Schema defined higher up in the HTML and passed to this Box via argument ($args)
		3. Template: Schema defined at the HTML options level of this Box
*/
		$post_schema = $thesis->api->schema->get_post_meta($post->ID);
		$schema = !empty($post_schema) ?
			($post_schema == 'no_schema' ? false : $post_schema) : (!empty($schema) ?
			$schema : (!empty($this->options['schema']) ?
			$this->options['schema'] : false));
		$hook = trim(esc_attr(!empty($this->options['_id']) ?
			$this->options['_id'] : (!empty($this->options['hook']) ?
			$this->options['hook'] : false)));
/*
		Post Box HTML output
*/
		if (!empty($hook))
			$thesis->api->hook("hook_before_$hook", $post_count);		// hook before
		echo "$tab<$html", ($wp_query->is_404 ? '' : " id=\"post-$post->ID\""), ' class="post_box', (!empty($classes) ? ' '. trim(esc_attr(implode(' ', $classes))) : ''), '"', ($schema ? ' itemscope itemtype="'. esc_url($thesis->api->schema->types[$schema]). '"' : ''), ">\n"; #wp
		if (is_singular() && $schema)
			echo "$tab\t<link itemprop=\"mainEntityOfPage\" href=\"", get_permalink(), "\" />\n";
		if (apply_filters('post_box_rotator_override', false))
			do_action('post_box_rotator');
		else {
			if (!empty($hook))
				$thesis->api->hook("hook_top_$hook", $post_count);		// hook top
			$this->rotator(array_merge($args, array('depth' => $depth + 1, 'schema' => $schema)));
			if (!empty($hook))
				$thesis->api->hook("hook_bottom_$hook", $post_count);	// hook bottom
		}
		echo "$tab</$html>\n";
		if (!empty($hook))
			$thesis->api->hook("hook_after_$hook", $post_count);		// hook after
	}
}

class thesis_post_list extends thesis_box {
	public $type = 'rotator';
	public $dependents = array(
		'thesis_post_headline',
		'thesis_post_date',
		'thesis_post_author',
		'thesis_post_author_avatar',
		'thesis_post_num_comments',
		'thesis_post_edit');
	public $children = array(
		'thesis_post_headline',
		'thesis_post_num_comments',
		'thesis_post_edit');
	public $templates = array(
		'home',
		'archive');

	protected function translate() {
		$this->title = $this->name = __('Post List', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array(
			'ul' => 'ul',
			'ol' => 'ol'), 'ul');
		unset($html['id']);
		return array_merge($html, array(
			'schema' => $thesis->api->schema->select()));
	}

	public function html($args = array()) {
		global $thesis, $wp_query, $post; #wp
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", $depth = !empty($depth) ? $depth : 0);
		$post_count = !empty($post_count) ? $post_count : false;
		$html = $class = $hook = false;
		$post_schema = $thesis->api->schema->get_post_meta($post->ID);
		$schema = !empty($post_schema) ?
			($post_schema == 'no_schema' ? false : $post_schema) : (!empty($schema) ?
			$schema : (!empty($this->options['schema']) ?
			$this->options['schema'] : false));
		if (!empty($post_count) && ($post_count == 1 || ($wp_query->post_count > 1 && $post_count == $wp_query->post_count))) {
			$html = !empty($this->options['html']) ? esc_attr($this->options['html']) : 'ul';
			$class = !empty($this->options['class']) ? trim(esc_attr($this->options['class'])) : false;
			$hook = trim(esc_attr(!empty($this->options['_id']) ?
				$this->options['_id'] : (!empty($this->options['hook']) ?
				$this->options['hook'] : false)));
		}
/*
		Post List HTML output
*/
		if (!empty($post_count) && $post_count == 1) {
			if (!empty($hook))
				$thesis->api->hook("hook_before_$hook", $post_count);		// hook before
			echo "$tab<$html class=\"post_list", (!empty($class) ? " $class" : ''), "\">\n";
		}
		$tab = "$tab\t";
		$depth = $depth + 1;
		echo "$tab<li id=\"post-$post->ID\"", (!empty($schema) ? ' itemscope itemtype="'. esc_url($thesis->api->schema->types[$schema]). '"' : ''), ">\n";
		$this->rotator(array_merge($args, array('depth' => $depth + 1, 'schema' => $schema)));
		echo "$tab</li>\n";
		if ($wp_query->post_count >= 1 && $post_count == $wp_query->post_count) {
			$tab = str_repeat("\t", $depth - 1);
			echo "$tab</$html>\n";
			if (!empty($hook))
				$thesis->api->hook("hook_after_$hook", $post_count);		// hook after
		}
	}
}

class thesis_post_headline extends thesis_box {
	protected function translate() {
		$this->title = __('Headline', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array(
			'h1' => 'h1',
			'h2' => 'h2',
			'h3' => 'h3',
			'h4' => 'h4',
			'p' => 'p',
			'span' => 'span'), 'h1');
		$html['class']['tooltip'] = sprintf(__('This box already contains a %1$s, <code>headline</code>. If you wish to add an additional %1$s, you can do that here. Separate multiple %1$ses with spaces.%2$s', 'thesis'), $thesis->api->base['class'], __($thesis->api->strings['class_note'], 'thesis'));
		unset($html['id']);
		return array_merge($html, array(
			'link' => array(
				'type' => 'checkbox',
				'options' => array(
					'on' => __('Link headline to article page', 'thesis')))));
	}

	public function html($args = array()) {
		global $thesis;
		extract($args = is_array($args) ? $args : array());
		$html = !empty($this->options['html']) ? esc_attr($this->options['html']) : 'h1';
		$class = !empty($this->options['class']) ? ' '. trim(esc_attr($this->options['class'])) : '';
	 	echo
			str_repeat("\t", !empty($depth) ? $depth : 0),
			"<$html class=\"headline$class\"", (!empty($schema) ? ' itemprop="headline"' : ''), '>',
			(!empty($this->options['link']['on']) ?
			'<a href="'. get_permalink(). '" rel="bookmark">'. get_the_title(). '</a>' :
			get_the_title()),
			"</$html>\n";
	}
}

class thesis_post_author extends thesis_box {
	protected function translate() {
		$this->title = __('Author', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options();
		$html['class']['tooltip'] = sprintf(__('This box already contains a %1$s of <code>post_author</code>. If you&#8217;d like to supply another %1$s, you can do that here.%2$s', 'thesis'), $thesis->api->base['class'], __($thesis->api->strings['class_note'], 'thesis'));
		unset($html['id']);
		return array_merge($html, array(
			'intro' => array(
				'type' => 'text',
				'width' => 'short',
				'label' => __('Author Intro Text', 'thesis'),
				'tooltip' => sprintf(__('Any text you supply here will be wrapped in %s, like so:<br /><code>&lt;span class="post_author_intro"&gt</code>your text<code>&lt;/span&gt;</code>.', 'thesis'), $thesis->api->base['html'])),
			'link' => array(
				'type' => 'checkbox',
				'options' => array(
					'on' => __('Link author names to archives', 'thesis')),
				'dependents' => array('on')),
			'nofollow' => array(
				'type' => 'checkbox',
				'options' => array(
					'on' => __('Add <code>nofollow</code> to author link', 'thesis')),
				'parent' => array(
					'link' => 'on'))));
	}

	public function html($args = array()) {
		global $thesis;
		extract($args = is_array($args) ? $args : array());
		$author = !empty($this->options['link']['on']) ?
			'<a href="'. esc_url(get_author_posts_url(get_the_author_meta('ID'))). '"'. (!empty($this->options['nofollow']['on']) ?
				' rel="nofollow"' : ''). '>'. get_the_author(). '</a>' :
			get_the_author();
		echo
			str_repeat("\t", !empty($depth) ? $depth : 0), (!empty($this->options['intro']) ?
			'<span class="post_author_intro">'. trim($thesis->api->efh($this->options['intro'])). '</span> ' : ''),
			apply_filters($this->_class,
			'<span class="post_author'. (!empty($this->options['class']) ?
				' '. trim(esc_attr($this->options['class'])) : ''). '"'. (!empty($schema) ?
				' itemprop="author"' : ''). ">$author</span>"), "\n";
	}
}

class thesis_post_author_avatar extends thesis_box {
	protected function translate() {
		$this->title = __('Author Avatar', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		return array(
			'size' => array(
				'type' => 'text',
				'width' => 'tiny',
				'label' => __($thesis->api->strings['avatar_size'], 'thesis'),
				'tooltip' => __($thesis->api->strings['avatar_tooltip'], 'thesis'),
				'description' => 'px'));
	}

	public function html($args = array()) {
		global $post;
		extract($args = is_array($args) ? $args : array());
		echo str_repeat("\t", !empty($depth) ? $depth : 0). get_avatar(
			$post->post_author,
			!empty($this->options['size']) && is_numeric($this->options['size']) ? $this->options['size'] : false,
			false). "\n";
	}
}

class thesis_post_author_description extends thesis_box {
	protected function translate() {
		$this->title = __('Author Description', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array('div' => 'div', 'p' => 'p'), 'p');
		unset($html['id'], $html['class']);
		return array_merge($html, array(
			'display' => array(
				'type' => 'checkbox',
				'options' => array(
					'author' => __('Show author name', 'thesis'),
					'intro' => __('Show author description intro text', 'thesis'),
					'avatar' => __('Include author avatar', 'thesis')),
				'default' => array(
					'intro' => true,
					'avatar' => true),
				'dependents' => array('intro', 'avatar')),
			'intro' => array(
				'type' => 'text',
				'width' => 'medium',
				'label' => __('Description Intro Text', 'thesis'),
				'placeholder' => __('About the author:', 'thesis'),
				'parent' => array(
					'display' => 'intro')),
			'avatar_size' => array(
				'type' => 'text',
				'width' => 'tiny',
				'label' => __('Avatar Size', 'thesis'),
				'description' => 'px',
				'placeholder' => __('96', 'thesis'),
				'parent' => array(
					'display' => 'avatar'))));
	}

	protected function construct() {
		global $thesis;
		$thesis->wp->filter($this->_class, array(
			'wptexturize' => false,
			'convert_smilies' => false,
			'convert_chars' => false,
			'shortcode_unautop' => false,
			'do_shortcode' => false,
			'wp_make_content_images_responsive' => false));
		if (class_exists('WP_Embed')) {
			$embed = new WP_Embed;
			add_filter($this->_class, array($embed, 'run_shortcode'), 8);
			add_filter($this->_class, array($embed, 'autoembed'), 8);
		}
	}

	public function html($args = array()) {
		global $thesis, $post;
		if (($text = apply_filters($this->_class, get_the_author_meta('user_description', get_the_author_meta('ID')))) == '') return;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$options = $thesis->api->get_options(array_merge($this->_html_options(), $this->_options()), $this->options);
		$html = !empty($options['html']) ? esc_attr($options['html']) : 'p';
		$avatar_size = !empty($options['avatar_size']) && is_numeric($options['avatar_size']) ? $options['avatar_size'] : '';
		echo
			"$tab<$html class=\"author_description\">\n",
			(!empty($options['display']['avatar']) ?
			"$tab\t". get_avatar($post->post_author, $avatar_size, false). "\n" : ''),
			"$tab\t", (!empty($options['display']['intro']) ?
			'<span class="author_description_intro">'.
			trim($thesis->api->efh(!empty($options['intro']) ? $options['intro'] : __('About the author:', 'thesis'))).
			"</span>\n" : ''),
			"$tab\t", (!empty($options['display']['author']) ?
			'<span class="author_name">'. trim($thesis->api->efh(get_the_author())). '</span> ' : ''),
			trim($text), "\n",
			"$tab</$html>\n";
	}
}

class thesis_post_date extends thesis_box {
	protected function translate() {
		$this->title = __('Date', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options();
		$html['class']['tooltip'] = sprintf(__('This box already contains a %1$s of <code>post_date</code>. If you&#8217;d like to supply another %1$s, you can do that here.%2$s', 'thesis'), $thesis->api->base['class'], __($thesis->api->strings['class_note'], 'thesis'));
		unset($html['id'], $html['class']);
		return array_merge($html, array(
			'format' => array(
				'type' => 'text',
				'width' => 'short',
				'code' => true,
				'label' => __('Date Format', 'thesis'),
				'tooltip' => __($thesis->api->strings['date_tooltip'], 'thesis'),
				'default' => $thesis->api->get_option('date_format')),
			'intro' => array(
				'type' => 'text',
				'width' => 'short',
				'label' => __('Date Intro Text', 'thesis'),
				'tooltip' => sprintf(__('Any text you supply here will be wrapped in %s, like so:<br /><code>&lt;span class="post_date_intro"&gt</code>your text<code>&lt;/span&gt;</code>.', 'thesis'), $thesis->api->base['html'])),
			'schema' => array(
				'type' => 'checkbox',
				'label' => __('If a Markup Schema Is Present&hellip;', 'thesis'),
				'tooltip' => __('If a markup schema is present, this box will output the date <code>&lt;meta&gt;</code> automatically. This option is only intended to control whether or not the date actually displays on the page when a schema is present.', 'thesis'),
				'options' => array(
					'only' => sprintf(__('do not show the date, but include the date <code>&lt;meta&gt;</code> in the %s', 'thesis'), $thesis->api->base['html'])))));
	}

	public function html($args = array()) {
		global $thesis;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$time = get_the_time('Y-m-d');
		$format = strip_tags(!empty($this->options['format']) ?
			$this->options['format'] :
			apply_filters("{$this->_class}_format", $thesis->api->get_option('date_format')));
		echo
			(!empty($schema) ?
			"$tab<meta itemprop=\"datePublished\" content=\"$time\" />\n".
			"$tab<meta itemprop=\"dateModified\" content=\"". get_the_modified_date('Y-m-d'). "\" />\n" : ''),
			(empty($schema) || (!empty($schema) && !isset($this->options['schema']['only'])) ?
			$tab. (!empty($this->options['intro']) ?
			'<span class="post_date_intro">'. trim($thesis->api->efh($this->options['intro'])). '</span> ' : '').
			"<span class=\"post_date". (!empty($this->options['class']) ? ' '. trim(esc_attr($this->options['class'])) : ''). "\" title=\"$time\">".
			get_the_time($format).
			"</span>\n" : '');
	}
}

class thesis_post_edit extends thesis_box {
	protected function translate() {
		global $thesis;
		$this->title = __('Edit Link', 'thesis');
		$this->edit = apply_filters("{$this->_class}_text", strtolower(__($thesis->api->strings['edit'], 'thesis')));
	}

	protected function html_options() {
		return array(
			'text' => array(
				'type' => 'text',
				'label' => sprintf(__('%s Text', 'thesis'), $this->title),
				'tooltip' => sprintf(__('The default edit link text is &lsquo;%s&rsquo;, but you can change that by entering your own text here.', 'thesis'), $this->edit),
				'placeholder' => $this->edit));
	}

	public function html($args = array()) {
		global $thesis;
		$url = get_edit_post_link();
		if (empty($url)) return;
		extract($args = is_array($args) ? $args : array());
		echo
			str_repeat("\t", !empty($depth) ? $depth : 0),
			"<a class=\"post_edit\" href=\"$url\" title=\"". __($thesis->api->strings['click_to_edit'], 'thesis'). "\" rel=\"nofollow\">",
			trim($thesis->api->efh(!empty($this->options['text']) ? $this->options['text'] : $this->edit)),
			"</a>\n";
	}
}

class thesis_post_content extends thesis_box {
	protected function translate() {
		$this->title = __('Content', 'thesis');
		$this->custom = __('Custom &ldquo;Read More&rdquo; Text', 'thesis');
		$this->read_more = apply_filters("{$this->_class}_read_more", __('[click to continue&hellip;]', 'thesis'));
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options();
		$html['class']['tooltip'] = sprintf(__('This box already contains a %1$s of <code>post_content</code>. If you&#8217;d like to supply another %1$s, you can do that here.%2$s', 'thesis'), $thesis->api->base['class'], __($thesis->api->strings['class_note'], 'thesis'));
		unset($html['id']);
		return array_merge($html, array(
			'read_more' => array(
				'type' => 'text',
				'width' => 'medium',
				'label' => __('&ldquo;Read More&rdquo; Text', 'thesis'),
				'tooltip' => sprintf(__('If you use <code>&lt;!--more--&gt;</code> within your post, the text you enter here will be shown to your visitors to encourage them to click through (on blog and archive pages only).<br/><br/>You can override this text on any post or page by filling out the <strong>%s</strong> field on the post editing screen.', 'thesis'), $this->custom),
				'placeholder' => $this->read_more)));
	}

	protected function post_meta() {
		return array(
			'title' => $this->custom,
			'fields' => array(
				'read_more' => array(
					'type' => 'text',
					'width' => 'medium',
					'label' => $this->custom,
					'tooltip' => __('If you use <code>&lt;!--more--&gt;</code> within your post, you can specify custom &ldquo;Read More&rdquo; text here. If you don&#8217;t specify anything, Thesis will use the default text. Please note that the &ldquo;Read More&rdquo; text only appears on blog and archive pages.', 'thesis'))));
	}

	public function html($args = array()) {
		global $thesis, $wp_query;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$schema = !empty($schema) ? ' itemprop="'. ($schema == 'article' ? 'articleBody' : 'text'). '"' : '';
		/*---:[ begin HTML output ]:---*/
		$thesis->api->hook('hook_before_post_content');
		echo "$tab<div class=\"post_content", (!empty($this->options['class']) ? ' '. trim(esc_attr($this->options['class'])) : ''), "\"$schema>\n";
		$thesis->api->hook('hook_top_post_content');
		the_content(trim($thesis->api->efh(!empty($this->post_meta['read_more']) ?
			$this->post_meta['read_more'] : (!empty($this->options['read_more']) ?
			$this->options['read_more'] :
			$this->read_more))));
		if ($wp_query->is_singular && apply_filters("{$this->_class}_page_links", true))
			wp_link_pages(array(
				'before' => '<div class="page-links">'. __($thesis->api->strings['pages'], 'thesis'). ':',
				'after' => '</div>'));
		$thesis->api->hook('hook_bottom_post_content');
		echo "$tab</div>\n";
		$thesis->api->hook('hook_after_post_content');
	}
}

class thesis_post_excerpt extends thesis_box {
	protected function translate() {
		$this->title = __('Excerpt', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options();
		$html['style'] = array(
			'type' => 'radio',
			'label' => __('Excerpt Type', 'thesis'),
			'tooltip' => __('The Thesis enhanced excerpt strips <code>h1</code>-<code>h4</code> tags and images, in addition to the typical items removed by WordPress.', 'thesis'),
			'options' => array(
				'thesis' => __('Thesis enhanced (recommended)', 'thesis'),
				'wp' => __('WordPress default', 'thesis')),
			'default' => 'thesis');
		$html['ellipsis'] = array(
			'type' => 'radio',
			'label' => __('Excerpt Ellipsis', 'thesis'),
			'options' => array(
				'bracket' => __('Show ellipsis with a bracket at the end of the excerpt', 'thesis'),
				'no_bracket' => __('Show ellipsis without a bracket at the end of the excerpt', 'thesis'),
				'none' => __('Do not show an ellipsis', 'thesis')),
			'default' => 'bracket');
		$html['read_more_show'] = array(
			'type' => 'checkbox',
			'label' => __('Read More Link', 'thesis'),
			'options' => array(
				'show' => __('Show &ldquo;Read More&rdquo; link at the end of an excerpt', 'thesis')),
			'dependents' => array('show'));
		$html['read_more_text'] = array(
			'type' => 'text',
			'label' => __('Read More Text', 'thesis'),
			'width' => 'long',
			'placeholder' => __('Read More', 'thesis'),
			'parent' => array(
				'read_more_show' => 'show'));
		unset($html['id']);
		return $html;
	}

	public function html($args = array()) {
		global $thesis, $post;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		if (empty($this->options['read_more_show']['show']))
			$thesis->wp->filter($this->_class, array('wpautop' => false));
		if (isset($this->options['read_more_show']['show'])) {
			add_filter('excerpt_more', array($this, 'more'), 1);
			add_filter('thesis_trim_excerpt', array($this, 'more'), 100);
		}
		$content = empty($this->options['style']) ? (!empty($post->post_excerpt) ?
			$thesis->api->efa($post->post_excerpt) :
			$thesis->api->trim_excerpt($thesis->api->efa($post->post_content))) :
			$thesis->api->efa(get_the_excerpt());
		echo
			"$tab<div class=\"post_content post_excerpt", (!empty($this->options['class']) ? ' '. trim(esc_attr($this->options['class'])) : ''), '"', (!empty($schema) ? ' itemprop="description"' : ''), ">\n",
			apply_filters($this->_class, isset($this->options['read_more_show']['show']) ? wpautop($content, false) : $content), #wp
			"$tab</div>\n";
		if (isset($this->options['read_more_show']['show'])) {
			remove_filter('excerpt_more', array($this, 'more'), 1);
			remove_filter('thesis_trim_excerpt', array($this, 'more'), 100);
		}
	}

	public function more($in = '', $read_more = false) {
		global $thesis, $post;
		$out = '';
		$in = str_replace(array('[...]', '[…]', '[&hellip;]'), '', preg_replace('/&hellip;*$/', '', trim($in)));
		if (!$read_more) {
			if (!isset($this->options['ellipsis']))
				$out .= ' [&hellip;]';
			elseif (isset($this->options['ellipsis'])) {
				if ($this->options['ellipsis'] == 'no_bracket')
					$out .= '&hellip;';
				elseif ($this->options['ellipsis'] == 'none')
					$out .= '';
			}
		}
		// When in the Thesis enhanced mode, this method will be called twice:
		// Once for the excerpt filter and again for the trim_excerpt API method.
		static $track = 1;
		if (isset($this->options['read_more_show']['show']) && ((!empty($this->options['style']) && $this->options['style'] == 'wp') || (empty($this->options['style']) && $track % 2 === 0))) {
			$read_more = is_array($post_meta = get_post_meta($post->ID, '_thesis_post_content', true)) && !empty($post_meta['read_more']) ?
				$post_meta['read_more'] : (!empty($this->options['read_more_text']) ?
				$this->options['read_more_text'] : __('Read More', 'thesis'));
			$out .= "\n<a class=\"excerpt_read_more\" href=\"". get_permalink(). "\">". trim($thesis->api->efh($read_more)). "</a>";
		}
		$track++;
		return (!empty($in) ? rtrim($in, ',.?!:;') : ''). $out;
	}
}

class thesis_post_num_comments extends thesis_box {
	protected function translate() {
		$this->title = __('Number of Comments', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		return array(
			'display' => array(
				'type' => 'checkbox',
				'label' => __($thesis->api->strings['display_options'], 'thesis'),
				'options' => array(
					'link' => __('Link to comments section', 'thesis'),
					'term' => __('Show term with number (ex: &#8220;5 comments&#8221; instead of &#8220;5&#8221;)', 'thesis'),
					'closed' => __('Display even if comments are closed', 'thesis')),
				'default' => array(
					'link' => true,
					'term' => true,
					'closed' => true),
				'dependents' => array('link', 'term')),
			'link' => array(
				'type' => 'text',
				'label' => __('Comment Link Destination', 'thesis'),
				'placeholder' => 'comments',
				'tooltip' => __('Anything you enter here will be prepended with # to determine the link destination; for example: #comments.', 'thesis'),
				'parent' => array(
					'display' => 'link')),
			'singular' => array(
				'type' => 'text',
				'label' => __($thesis->api->strings['comment_term_singular'], 'thesis'),
				'placeholder' => __($thesis->api->strings['comment_singular'], 'thesis'),
				'parent' => array(
					'display' => 'term')),
			'plural' => array(
				'type' => 'text',
				'label' => __($thesis->api->strings['comment_term_plural'], 'thesis'),
				'placeholder' => __($thesis->api->strings['comment_plural'], 'thesis'),
				'parent' => array(
					'display' => 'term')));
	}

	public function html($args = array()) {
		global $thesis;
		$options = $thesis->api->get_options(array_merge($this->_html_options(), $this->_options()), $this->options);
		if (!(comments_open() || (!comments_open() && !empty($options['display']['closed'])))) return;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$number = get_comments_number(); #wp
		echo (!empty($schema) ?
			"$tab<meta itemprop=\"interactionCount\" content=\"UserComments:$number\" />\n" : ''),
			$tab, apply_filters($this->_class, (!empty($options['display']['link']) ?
				'<a class="num_comments_link" href="'. get_permalink(). '#'. ($number > 0 ? (!empty($options['link']) ? esc_attr($options['link']) : 'comments') : 'commentform'). '" rel="nofollow">' : '').
				"<span class=\"num_comments\">$number</span>".
				(!empty($options['display']['term']) ?
			 	' '. trim($thesis->api->efh($number == 1 ? (!empty($options['singular']) ?
				$options['singular'] : __($thesis->api->strings['comment_singular'], 'thesis')) : (!empty($options['plural']) ?
				$options['plural'] : __($thesis->api->strings['comment_plural'], 'thesis')))) : '').
				(!empty($options['display']['link']) ?
				'</a>' : '')), "\n";
	}
}

class thesis_post_categories extends thesis_box {
	protected function translate() {
		$this->title = __('Categories', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array(
			'p' => 'p',
			'div' => 'div',
			'span' => 'span'), 'p');
		unset($html['id'], $html['class']);
		return array_merge($html, array(
			'intro' => array(
				'type' => 'text',
				'width' => 'short',
				'label' => __($thesis->api->strings['intro_text'], 'thesis'),
				'tooltip' => sprintf(__('Any intro text you provide will precede the post category output, and it will be wrapped in %s, like so: <code>&lt;span class="post_cats_intro"&gt;</code>your text<code>&lt;/span&gt;</code>.', 'thesis'), $thesis->api->base['html'])),
			'separator' => array(
				'type' => 'text',
				'width' => 'tiny',
				'label' => __($thesis->api->strings['character_separator'], 'thesis'),
				'tooltip' => __('If you&#8217;d like to separate your categories with a particular character (a comma, for instance), you can do that here.', 'thesis')),
			'nofollow' => array(
				'type' => 'checkbox',
				'options' => array(
					'on' => __('Add <code>nofollow</code> to category links', 'thesis')))));
	}

	public function html($args = array()) {
		global $thesis;
		if (!is_array($categories = get_the_category())) return;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$html = trim(esc_attr(apply_filters("{$this->_class}_html", !empty($this->options['html']) ? $this->options['html'] : 'p')));
		$nofollow = !empty($this->options['nofollow']['on']) ? ' nofollow' : '';
		$cats = array();
		foreach ($categories as $cat)
			$cats[] = "<a href=\"". esc_url(get_category_link($cat->term_id)). "\" rel=\"category tag$nofollow\">". trim($thesis->api->efh($cat->name)). "</a>";
		if (!empty($cats))
			echo
				"$tab<$html class=\"post_cats\"", (!empty($schema) ? ' itemprop="keywords"' : ''), ">\n",
				(!empty($this->options['intro']) ?
				"$tab\t<span class=\"post_cats_intro\">". trim($thesis->api->efh($this->options['intro'])). "</span>\n" : ''),
				"$tab\t", implode((!empty($this->options['separator']) ? trim($thesis->api->efh($this->options['separator'])) : '') . "\n$tab\t", $cats), "\n",
				"$tab</$html>\n"; #wp
	}
}

class thesis_post_tags extends thesis_box {
	protected function translate() {
		$this->title = __('Tags', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array(
			'p' => 'p',
			'div' => 'div',
			'span' => 'span'), 'p');
		unset($html['id'], $html['class']);
		return array_merge($html, array(
			'intro' => array(
				'type' => 'text',
				'width' => 'short',
				'label' => __($thesis->api->strings['intro_text'], 'thesis'),
				'tooltip' => sprintf(__('Any intro text you provide will precede the post tag output, and it will be wrapped in %s, like so: <code>&lt;span class="post_tags_intro"&gt;</code>.', 'thesis'), $thesis->api->base['html'])),
			'separator' => array(
				'type' => 'text',
				'width' => 'tiny',
				'label' => __($thesis->api->strings['character_separator'], 'thesis'),
				'tooltip' => __('If you&#8217;d like to separate your tags with a particular character (a comma, for instance), you can do that here.', 'thesis')),
			'nofollow' => array(
				'type' => 'checkbox',
				'options' => array(
					'on' => __('Add <code>nofollow</code> to tag links', 'thesis')))));
	}

	public function html($args = array()) {
		global $thesis;
		if (!is_array($post_tags = get_the_tags())) return; #wp
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$html = esc_attr(apply_filters("{$this->_class}_html", !empty($this->options['html']) ? $this->options['html'] : 'p'));
		$nofollow = !empty($this->options['nofollow']['on']) ? ' nofollow' : '';
		$tags = array();
		foreach ($post_tags as $tag)
			$tags[] = "<a href=\"". esc_url(get_tag_link($tag->term_id)). "\" rel=\"tag$nofollow\">". trim($thesis->api->efh($tag->name)). "</a>"; #wp
		if (!empty($tags))
			echo
				"$tab<$html class=\"post_tags\"", (!empty($schema) ? ' itemprop="keywords"' : ''), ">\n",
				(!empty($this->options['intro']) ?
				"$tab\t<span class=\"post_tags_intro\">". trim($thesis->api->efh($this->options['intro'])). "</span>\n" : ''),
				"$tab\t", implode((!empty($this->options['separator']) ? trim($thesis->api->efh($this->options['separator'])) : ''). "\n$tab\t", $tags), "\n",
				"$tab</$html>\n";
	}
}

class thesis_post_image extends thesis_box {
	protected function translate() {
		$this->image_type = __('Post Image', 'thesis');
		$this->title = sprintf(__('Thesis %s', 'thesis'), $this->image_type);
	}

	protected function html_options() {
		global $thesis;
		return array(
			'alignment' => array(
				'type' => 'select',
				'label' => __($thesis->api->strings['alignment'], 'thesis'),
				'tooltip' => __($thesis->api->strings['alignment_tooltip'], 'thesis'),
				'options' => array(
					'' => __($thesis->api->strings['alignnone'], 'thesis'),
					'left' => __($thesis->api->strings['alignleft'], 'thesis'),
					'right' => __($thesis->api->strings['alignright'], 'thesis'),
					'center' => __($thesis->api->strings['aligncenter'], 'thesis'))),
			'link' => array(
				'type' => 'checkbox',
				'options' => array(
					'link' => __('Link image to post', 'thesis')),
				'default' => array(
					'link' => true)));
	}

	protected function post_meta() {
		global $thesis;
		return array(
			'title' => $this->title,
			'fields' => array(
				'image' => array(
					'type' => 'add_media',
					'upload_label' => sprintf(__('Upload a %s', 'thesis'), $this->image_type),
					'tooltip' => sprintf(__('Upload a %1$s here, or else input the %2$s of an image you&#8217;d like to use in the <strong>%3$s %2$s</strong> field below.', 'thesis'), strtolower($this->image_type), $thesis->api->base['url'], $this->image_type),
					'label' => "$this->image_type {$thesis->api->base['url']}"),
				'alt' => array(
					'type' => 'text',
					'width' => 'full',
					'label' => sprintf(__('%s <code>alt</code> Text', 'thesis'), $this->image_type),
					'tooltip' => __($thesis->api->strings['alt_tooltip'], 'thesis')),
				'caption' => array(
					'type' => 'text',
					'width' => 'full',
					'label' => sprintf(__('%s Caption', 'thesis'), $this->image_type),
					'tooltip' => __($thesis->api->strings['caption_tooltip'], 'thesis')),
				'frame' => array(
					'type' => 'checkbox',
					'label' => __($thesis->api->strings['frame_label'], 'thesis'),
					'tooltip' => __($thesis->api->strings['frame_tooltip'], 'thesis'),
					'options' => array(
						'on' => __($thesis->api->strings['frame_option'], 'thesis'))),
				'alignment' => array(
					'type' => 'select',
					'label' => __($thesis->api->strings['alignment'], 'thesis'),
					'tooltip' => __($thesis->api->strings['alignment_tooltip'], 'thesis'),
					'options' => array(
						'' => __($thesis->api->strings['skin_default'], 'thesis'),
						'left' => __($thesis->api->strings['alignleft'], 'thesis'),
						'right' => __($thesis->api->strings['alignright'], 'thesis'),
						'center' => __($thesis->api->strings['aligncenter'], 'thesis'),
						'flush' => __($thesis->api->strings['alignnone'], 'thesis')))));
	}

	protected function construct() {
		global $thesis;
		if (empty($thesis->_post_image_rss) && $this->_display()) {
			add_filter('the_content', array($this, 'add_image_to_feed'));
			$thesis->_post_image_rss = true;
		}
	}

	public function html($args = array()) {
		global $thesis, $wp_query; #wp
		if (empty($this->post_meta['image']['url']) || !is_array($this->post_meta['image'])) return;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$attachment = !empty($this->post_meta['image']['id']) ? get_post($this->post_meta['image']['id']) : false;
		$alt = !empty($this->post_meta['alt']) ?
			$this->post_meta['alt'] : (!empty($this->post_meta['image']['id']) && ($wp_alt = get_post_meta($this->post_meta['image']['id'], '_wp_attachment_image_alt', true)) ?
			$wp_alt : get_the_title(). ' '. strtolower($this->image_type));
		$caption = trim(!empty($this->post_meta['caption']) ?
			$this->post_meta['caption'] : (is_object($attachment) && $attachment->post_excerpt ?
			$attachment->post_excerpt : false));
		$align = !empty($this->post_meta['alignment']) ?
			$this->post_meta['alignment'] : (!empty($this->options['alignment']) ?
			$this->options['alignment'] : false);
		$alignment = !empty($align) ? ' '. ($align == 'left' ?
			'alignleft' : ($align == 'right' ?
			'alignright' : ($align == 'center' ?
			'aligncenter' : 'alignnone'))) : '';
		$frame = !empty($this->post_meta['frame']) ? ' frame' : '';
		if (empty($this->post_meta['image']['width']) || empty($this->post_meta['image']['height']) && ($image_data = getimagesize($this->post_meta['image']['url']))) {
			$this->post_meta['image']['width'] = !empty($image_data[0]) ? $image_data[0] : false;
			$this->post_meta['image']['height'] = !empty($image_data[1]) ? $image_data[1] : false;
		}
		$dimensions = !empty($this->post_meta['image']['width']) && !empty($this->post_meta['image']['height']) ?
			" width=\"{$this->post_meta['image']['width']}\" height=\"{$this->post_meta['image']['height']}\"" : '';
		$img = '';
		if (!empty($this->post_meta['image']['url']))
			$img = "<img class=\"post_image$alignment$frame\" src=\"". esc_url($thesis->api->url_current($this->post_meta['image']['url'])). "\"$dimensions alt=\"". trim($thesis->api->efh($alt)). "\"". (!empty($schema) ? ' itemprop="image"' : ''). " />";
		if (!isset($this->options['link']))
			$img = "<a class=\"post_image_link\" href=\"". get_permalink(). "\" title=\"". trim($thesis->api->efh(__($thesis->api->strings['click_to_read'], 'thesis'))). "\">$img</a>"; #wp
		echo $caption ?
			"$tab<div class=\"post_image_box wp-caption$alignment\"". (!empty($this->post_meta['image']['width']) ? " style=\"width: {$this->post_meta['image']['width']}px\"" : ''). ">\n".
			"$tab\t$img\n".
			"$tab\t<p class=\"wp-caption-text\">". trim($thesis->api->efa($caption)). "</p>\n".
			"$tab</div>\n" : "$tab$img\n";
	}

	public function add_image_to_feed($content) {
		global $thesis, $post;
		if (!is_feed()) return $content;
		$image = get_post_meta($post->ID, "_{$this->_class}", true);
		if (empty($image['image']['url'])) return $content;
		$attachment = !empty($image['image']['id']) ? get_post($image['image']['id']) : false;
		$alt = !empty($image['alt']) ?
			$image['alt'] : (!empty($image['image']['id']) && ($wp_alt = get_post_meta($image['image']['id'], '_wp_attachment_image_alt', true)) ?
			$wp_alt : get_the_title(). ' '. strtolower($this->image_type));
		$caption = trim(!empty($image['caption']) ?
			$image['caption'] : (is_object($attachment) && $attachment->post_excerpt ?
			$attachment->post_excerpt : false));
		$dimensions = !empty($image['image']['width']) && !empty($image['image']['height']) ?
			" width=\"{$image['image']['width']}\" height=\"{$image['image']['height']}\"" : '';
		return
			"<p><a href=\"". get_permalink(). "\" title=\"". $thesis->api->ef(__($thesis->api->strings['click_to_read'], 'thesis')). "\"><img class=\"post_image\" src=\"". esc_url($thesis->api->url_current($image['image']['url'])). "\"$dimensions alt=\"". trim($thesis->api->ef($alt)). "\" /></a></p>\n".
			($caption ?
			"<p class=\"caption\">". trim($thesis->api->efa($caption)). "</p>\n" : '').
			$content;
	}
}

class thesis_post_thumbnail extends thesis_box {
	protected function translate() {
		$this->image_type = __('Thumbnail', 'thesis');
		$this->title = "Thesis $this->image_type";
	}

	protected function html_options() {
		global $thesis;
		return array(
			'alignment' => array(
				'type' => 'select',
				'label' => __($thesis->api->strings['alignment'], 'thesis'),
				'tooltip' => __($thesis->api->strings['alignment_tooltip'], 'thesis'),
				'options' => array(
					'' => __($thesis->api->strings['alignnone'], 'thesis'),
					'left' => __($thesis->api->strings['alignleft'], 'thesis'),
					'right' => __($thesis->api->strings['alignright'], 'thesis'),
					'center' => __($thesis->api->strings['aligncenter'], 'thesis'))),
			'link' => array(
				'type' => 'checkbox',
				'options' => array(
					'link' => __('Link image to post', 'thesis')),
				'default' => array(
					'link' => true)));
	}

	protected function post_meta() {
		global $thesis;
		return array(
			'title' => $this->title,
			'fields' => array(
				'image' => array(
					'type' => 'add_media',
					'upload_label' => sprintf(__('Upload a %s', 'thesis'), $this->image_type),
					'tooltip' => sprintf(__('Upload a %1$s here, or else input the %2$s of an image you&#8217;d like to use in the <strong>%3$s %2$s</strong> field below.', 'thesis'), strtolower($this->image_type), $thesis->api->base['url'], $this->image_type),
					'label' => "$this->image_type {$thesis->api->base['url']}"),
				'alt' => array(
					'type' => 'text',
					'width' => 'full',
					'label' => sprintf(__('%s <code>alt</code> Text', 'thesis'), $this->image_type),
					'tooltip' => __($thesis->api->strings['alt_tooltip'], 'thesis')),
				'caption' => array(
					'type' => 'text',
					'width' => 'full',
					'label' => sprintf(__('%s Caption', 'thesis'), $this->image_type),
					'tooltip' => __($thesis->api->strings['caption_tooltip'], 'thesis')),
				'frame' => array(
					'type' => 'checkbox',
					'label' => __($thesis->api->strings['frame_label'], 'thesis'),
					'tooltip' => __($thesis->api->strings['frame_tooltip'], 'thesis'),
					'options' => array(
						'on' => __($thesis->api->strings['frame_option'], 'thesis'))),
				'alignment' => array(
					'type' => 'select',
					'label' => __($thesis->api->strings['alignment'], 'thesis'),
					'tooltip' => __($thesis->api->strings['alignment_tooltip'], 'thesis'),
					'options' => array(
						'' => __($thesis->api->strings['skin_default'], 'thesis'),
						'left' => __($thesis->api->strings['alignleft'], 'thesis'),
						'right' => __($thesis->api->strings['alignright'], 'thesis'),
						'center' => __($thesis->api->strings['aligncenter'], 'thesis'),
						'flush' => __($thesis->api->strings['alignnone'], 'thesis')))));
	}

	public function html($args = array()) {
		global $thesis, $wp_query; #wp
		if (empty($this->post_meta['image']['url']) || !is_array($this->post_meta['image'])) return;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$attachment = !empty($this->post_meta['image']['id']) ? get_post($this->post_meta['image']['id']) : false;
		$alt = !empty($this->post_meta['alt']) ?
			$this->post_meta['alt'] : (!empty($this->post_meta['image']['id']) && ($wp_alt = get_post_meta($this->post_meta['image']['id'], '_wp_attachment_image_alt', true)) ?
			$wp_alt : get_the_title(). ' '. strtolower($this->image_type));
		$caption = trim(!empty($this->post_meta['caption']) ?
			$this->post_meta['caption'] : (is_object($attachment) && $attachment->post_excerpt ?
			$attachment->post_excerpt : false));
		$align = !empty($this->post_meta['alignment']) ?
			$this->post_meta['alignment'] : (!empty($this->options['alignment']) ?
			$this->options['alignment'] : false);
		$alignment = !empty($align) ? ' '. ($align == 'left' ?
			'alignleft' : ($align == 'right' ?
			'alignright' : ($align == 'center' ?
			'aligncenter' : 'alignnone'))) : '';
		$frame = !empty($this->post_meta['frame']) ? ' frame' : '';
		if (empty($this->post_meta['image']['width']) || empty($this->post_meta['image']['height']) && ($image_data = getimagesize($this->post_meta['image']['url']))) {
			$this->post_meta['image']['width'] = !empty($image_data[0]) ? $image_data[0] : false;
			$this->post_meta['image']['height'] = !empty($image_data[1]) ? $image_data[1] : false;
		}
		$dimensions = !empty($this->post_meta['image']['width']) && !empty($this->post_meta['image']['height']) ?
			" width=\"". (int)$this->post_meta['image']['width']. "\" height=\"". (int)$this->post_meta['image']['height']. "\"" : '';
		$img = '';
		if (!empty($this->post_meta['image']['url']))
			$img = "<img class=\"thumb$alignment$frame\" src=\"". esc_url($thesis->api->url_current($this->post_meta['image']['url'])). "\"$dimensions alt=\"". trim($thesis->api->ef($alt)). '"'. (!empty($schema) ? ' itemprop="thumbnailUrl"' : ''). " />";
		if (!isset($this->options['link']))
			$img = "<a class=\"thumb_link\" href=\"". get_permalink(). "\" title=\"". $thesis->api->ef(__($thesis->api->strings['click_to_read'], 'thesis')). "\">$img</a>"; #wp
		echo $caption ?
			"$tab<div class=\"thumb_box wp-caption$alignment\"". (!empty($this->post_meta['image']['width']) ? " style=\"width: {$this->post_meta['image']['width']}px\"" : ''). ">\n".
			"$tab\t$img\n".
			"$tab\t<p class=\"wp-caption-text\">". trim($thesis->api->efa($caption)). "</p>\n".
			"$tab</div>\n" : "$tab$img\n";
	}
}

class thesis_archive_title extends thesis_box {
	public $templates = array('archive');

	protected function translate() {
		$this->title = __('Archive Title', 'thesis');
	}

	protected function term_options() {
		return array(
			'title' => array(
				'type' => 'text',
				'code' => true,
				'label' => $this->title));
	}

	public function html($args = array()) {
		global $thesis, $wp_query;
		extract($args = is_array($args) ? $args : array());
		$title = !empty($this->term_options['title']) ?
			$this->term_options['title'] : ($wp_query->is_search ?
			__('Search:', 'thesis'). ' '. $wp_query->query_vars['s'] : ($wp_query->is_archive ? ($wp_query->is_author ?
			$thesis->wp->author($wp_query->query_vars['author'], 'display_name') : ($wp_query->is_day ?
			get_the_time('l, F j, Y') : ($wp_query->is_month ?
			get_the_time('F Y') : ($wp_query->is_year ?
			get_the_time('Y') : $wp_query->queried_object->name)))) : false));
		if ($title)
			echo str_repeat("\t", !empty($depth) ? $depth : 0),
				"<h1 class=\"archive_title headline\">", trim($thesis->api->efn(apply_filters($this->_class, $title))), "</h1>\n";
	}
}

class thesis_archive_content extends thesis_box {
	public $templates = array('archive');

	protected function translate() {
		$this->title = __('Archive Content', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options();
		$html['class']['tooltip'] = sprintf(__('This box already contains a %1$s called <code>archive_content</code>. If you wish to add an additional %1$s, you can do that here. Separate multiple %1$ses with spaces.%2$s', 'thesis'), $thesis->api->base['class'], __($thesis->api->strings['class_note'], 'thesis'));
		unset($html['id']);
		return $html;
	}

	protected function term_options() {
		return array(
			'content' => array(
				'type' => 'textarea',
				'rows' => 8,
				'label' => $this->title));
	}

	protected function construct() {
		global $thesis;
		$thesis->wp->filter($this->_class, array(
			'wptexturize' => false,
			'convert_smilies' => false,
			'convert_chars' => false,
			'wpautop' => false,
			'shortcode_unautop' => false,
			'do_shortcode' => false,
			'wp_make_content_images_responsive' => false));
		if (class_exists('WP_Embed')) {
			$embed = new WP_Embed;
			add_filter($this->_class, array($embed, 'run_shortcode'), 8);
			add_filter($this->_class, array($embed, 'autoembed'), 8);
		}
	}

	public function html($args = array()) {
		global $thesis, $wp_query;
		if (!($content = !empty($this->term_options['content']) ? $this->term_options['content'] : (is_search() && $wp_query->post_count == 0 ? __('No results found.', 'thesis') : false))) return;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		echo
			"$tab<div class=\"archive_content", (!empty($this->options['class']) ? ' '. trim(esc_attr($this->options['class'])) : ''), "\">\n",
			trim(apply_filters($this->_class, $content)),
			"$tab</div>\n";
	}
}

class thesis_text_box extends thesis_box {
	protected function translate() {
		$this->title = $this->name = __('Text Box', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array(
			'div' => 'div',
			'none' => sprintf(__('No %s wrapper', 'thesis'), $thesis->api->base['html'])), 'div');
		$html['html']['dependents'] = array('div');
		$html['id']['parent'] = $html['class']['parent'] = array('html' => 'div');
		return $html;
	}

	protected function options() {
		global $thesis;
		return array(
			'text' => array(
				'type' => 'textarea',
				'rows' => 8,
				'code' => true,
				'label' => sprintf(__('Text/%s', 'thesis'), $thesis->api->base['html']),
				'tooltip' => sprintf(__('This box allows you to insert plain text and/or %1$s. All text will be formatted just like a normal WordPress post, and all valid %1$s tags are allowed.<br /><br /><strong>Note:</strong> %2$s code is not allowed here.', 'thesis'), $thesis->api->base['html'], $thesis->api->base['php']),
				'description' => sprintf(__('Use %1$s tags and shortcodes just like in a post! %2$s is not allowed.', 'thesis'), $thesis->api->base['html'], $thesis->api->base['php'])),
			'filter' => array(
				'type' => 'checkbox',
				'options' => array(
					'on' => __('disable automatic <code>&lt;p&gt;</code> tags for this Text Box', 'thesis'))));
	}

	protected function construct() {
		global $thesis;
		$filters = !empty($this->options['filter']['on']) ?
			array(
				'wptexturize' => false,
				'convert_smilies' => false,
				'convert_chars' => false,
				'do_shortcode' => false,
				'wp_make_content_images_responsive' => false) :
			array(
				'wptexturize' => false,
				'convert_smilies' => false,
				'convert_chars' => false,
				'wpautop' => false,
				'shortcode_unautop' => false,
				'do_shortcode' => false,
				'wp_make_content_images_responsive' => false);
		$thesis->wp->filter($this->_id, $filters);
		if (class_exists('WP_Embed')) {
			$embed = new WP_Embed;
			add_filter($this->_id, array($embed, 'run_shortcode'), 8);
			add_filter($this->_id, array($embed, 'autoembed'), 8);
		}
	}

	public function html($args = array()) {
		global $thesis;
		extract($args = is_array($args) ? $args : array());
		$tab = str_repeat("\t", !empty($depth) ? $depth : 0);
		$html = !empty($this->options['html']) ? ($this->options['html'] == 'none' ? false : esc_attr($this->options['html'])) : 'div';
		if (empty($this->options['text']) && !is_user_logged_in()) return;
		echo
			($html ?
			"$tab<div". (!empty($this->options['id']) ? ' id="'. trim(esc_attr($this->options['id'])). '"' : ''). ' class="'. (!empty($this->options['class']) ? trim(esc_attr($this->options['class'])) : 'text_box'). "\">\n" : ''),
			$tab, ($html ? "\t" : ''), trim(apply_filters($this->_id, !empty($this->options['text']) ?
				$this->options['text'] :
				sprintf(__('This is a Text Box named %1$s. You can write anything you want in here, and Thesis will format it just like a WordPress post. <a href="%2$s">Click here to edit this Text Box</a>.', 'thesis'), $this->name, admin_url("admin.php?page=thesis&canvas=$this->_id")))), "\n",
			($html ?
			"$tab</div>\n" : '');
	}
}

class thesis_query_box extends thesis_box {
	public $type = 'rotator';
	public $dependents = array(
		'thesis_post_headline',
		'thesis_post_date',
		'thesis_post_author',
		'thesis_post_author_avatar',
		'thesis_post_author_description',
		'thesis_post_edit',
		'thesis_post_content',
		'thesis_post_excerpt',
		'thesis_post_num_comments',
		'thesis_post_categories',
		'thesis_post_tags',
		'thesis_post_image',
		'thesis_post_thumbnail');
	public $children = array(
		'thesis_post_headline',
		'thesis_post_author',
		'thesis_post_edit',
		'thesis_post_excerpt');
	public $exclude = array();
	private $query = false;

	protected function translate() {
		$this->title = $this->name = __('Query Box', 'thesis');
	}

	protected function html_options() {
		global $thesis;
		$html = $thesis->api->html_options(array(
			'div' => 'div',
			'section' => 'section',
			'article' => 'article',
			'ul' => 'ul',
			'ol' => 'ol'), 'div');
		$html['html']['dependents'] = array('div', 'ul', 'ol', 'article', 'section');
		$html['id']['parent'] = array(
			'html' => array('ul', 'ol'));
		$html['class']['parent'] = array(
			'html' => array('div', 'section', 'article', 'ul', 'ol'));
		return array_merge($html, array(
			'wp' => array(
				'type' => 'checkbox',
				'label' => __($thesis->api->strings['auto_wp_label'], 'thesis'),
				'tooltip' => __($thesis->api->strings['auto_wp_tooltip'], 'thesis'),
				'options' => array(
					'auto' => __($thesis->api->strings['auto_wp_option'], 'thesis')),
				'parent' => array(
					'html' => array('div', 'article', 'section'))),
			'output' => array(
				'type' => 'checkbox',
				'label' => __('Link Output', 'thesis'),
				'tooltip' => __('Selecting this will link each list item to its associated post. All output will be linked.', 'thesis'),
				'options' => array(
					'link' => __('Link list item to post', 'thesis')),
				'parent' => array(
					'html' => array('ul', 'ol'))),
			'schema' => $thesis->api->schema->select()));
	}

	protected function options() {
		global $thesis;
		if (!($thesis->environment == 'editor' || $thesis->environment == 'ajax' || (!empty($_GET['canvas']) && ($_GET['canvas'] == $this->_id || $_GET['canvas'] == "{$thesis->skin->_class}__content")))) return;
		// get the post types
		$get_post_types = get_post_types('', 'objects');
		$post_types = array();
		foreach ($get_post_types as $name => $pt_obj)
			if (!in_array($name, array('revision', 'nav_menu_item', 'attachment')))
				$post_types[$name] = trim($thesis->api->efh(!empty($pt_obj->labels->name) ?
					$pt_obj->labels->name :
					$pt_obj->name));
		$loop_post_types = $post_types;
		// now get the taxes associated with each post type, set up the dependents list
		$pt_has_dep = array();
		$term_args = array(
			'number' => 50, // get 50 terms for each tax
			'orderby' => 'count',
			'order' => 'DESC'); // but only the most popular ones!
		if (isset($loop_post_types['page'])) unset($loop_post_types['page']); // doing this so it appears in the menu in the right order, but we have to handle the options below.
		foreach ($loop_post_types as $name => $output) {
			$t = get_object_taxonomies($name, 'objects');
			$pt_has_dep[] = $name;
			if (!!$t) {
				$options_later = array(); // clear out the options_later array
				$options_later[$name. '_tax'] = array( // begin setup of taxonomy list for this post type
					'type' => 'select',
					'label' => __('Select Query Type', 'thesis'));
				$t_options = array(); // $t_options will be an array of slug => label for the taxes associated with this post type
				$t_options[''] = sprintf(__('Recent %s', 'thesis'), $output);
				foreach ($t as $tax_name => $tax_obj) {
					// make the post type specific list of taxonomies
					$t_options[$tax_name] = !empty($tax_obj->label) ?
						$tax_obj->label : (!empty($tax_obj->labels->name) ?
						$tax_obj->labels->name :
						$tax_name);
					// now let's make the term options for this category
					$options_later[$name. '_'. $tax_name. '_term'] = array(
						'type' => 'select',
						'label' => sprintf(__('Choose from available %s', 'thesis'), $t_options[$tax_name]));
					$get_terms = get_terms($tax_name, $term_args);
					$options_later[$name. '_'. $tax_name. '_term']['options'][''] = sprintf(__('Select %s Entries'), $t_options[$tax_name]);
					foreach ($get_terms as $term_obj) {
						// make the term list for this taxonomy
						$options_later[$name. '_'. $tax_name. '_term']['options'][$term_obj->term_id] = (!empty($term_obj->name) ?
							$term_obj->name :
							$term_obj->slug);
						// tell the taxonomy it has dependents, and which one has it
						$options_later[$name. '_tax']['dependents'][] = $tax_name;
					}
					$options_later[$name. '_'. $tax_name. '_term']['parent'] = array($name. '_tax' => $tax_name);
					if (count($get_terms) == 50) { // did we hit the 50 threshhold? if so, add in a text box
						$options_later[$name. '_'. $tax_name. '_term_text']['type'] = 'text';
						$options_later[$name. '_'. $tax_name. '_term_text']['label'] = __('Optionally, provide a numeric ID.', 'thesis');
						$options_later[$name. '_'. $tax_name. '_term_text']['width'] = 'medium';
						$options_later[$name. '_'. $tax_name. '_term_text']['parent'] = array($name. '_tax' => $tax_name);
					}
				}
				$options_later[$name. '_tax']['options'] = $t_options;
				$options_grouped[$name. '_group'] = array( // the group
					'type' => 'group',
					'parent' => array('post_type' => $name),
					'fields' => $options_later);
			}
		}
		// add on pages
		$pt_has_dep[] = 'page';
		$get_pages = get_pages();
		$pages_option = array('' => __('Select a page:', 'thesis'));
		foreach ($get_pages as $page_object)
			$pages_option[$page_object->ID] = $page_object->post_title;
		$options['post_type'] = array( // create the post type option
			'type' => 'select',
			'label' => __('Select Post Type', 'thesis'),
			'options' => $post_types,
			'dependents' => $pt_has_dep);
		foreach ($options_grouped as $name => $make)
			$options[$name] = $make;
		$options['pages'] = array(
			'type' => 'group',
			'parent' => array('post_type' => 'page'),
			'fields' => array(
				'page' => array(
					'type' => 'select',
					'label' => __('Select a Page', 'thesis'),
					'options' => $pages_option)));
		$options['num'] = array(
			'type' => 'text',
			'width' => 'tiny',
			'label' => __($thesis->api->strings['posts_to_show'], 'thesis'),
			'parent' => array('post_type' => array_keys($loop_post_types)));
		$author = array(
			'label' => __('Filter by Author', 'thesis'));
		if (!$users = wp_cache_get('thesis_editor_users')) {
			$user_args = array(
				'orderby' => 'post_count',
				'number' => 50);
			$users = get_users($user_args);
			wp_cache_add('thesis_editor_users', $users); // use this for the users list in the editor (if needed)
		}
		$user_data = array('' => '----');
		foreach ($users as $user_obj)
			$user_data[$user_obj->ID] = !empty($user_obj->display_name) ?
				$user_obj->display_name : (!empty($user_obj->user_nicename) ?
				$user_obj->user_nicename :
				$user_obj->user_login);
		$author['type'] = 'select';
		$author['options'] = $user_data;
		$more['author'] = $author;
		$more['order'] = array(
			'type' => 'select',
			'label' => __('Order', 'thesis'),
			'tooltip' => __('Ascending means 1,2,3; a,b,c. Descending means 3,2,1; c,b,a.', 'thesis'),
			'options' => array(
				'' => __('Descending', 'thesis'),
				'ASC' => __('Ascending', 'thesis')));
		$more['orderby'] = array(
			'type' => 'select',
			'label' => __('Orderby', 'thesis'),
			'tooltip' => __('Choose a field to sort by', 'thesis'),
			'options' => array(
				'' => __('Date', 'thesis'),
				'ID' => __('ID', 'thesis'),
				'author' => __('Author', 'thesis'),
				'title' => __('Title', 'thesis'),
				'modified' => __('Modified', 'thesis'),
				'rand' => __('Random', 'thesis'),
				'comment_count' => __('Comment count', 'thesis'),
				'menu_order' => __('Menu order', 'thesis')));
		$more['offset'] = array(
			'type' => 'text',
			'width' => 'short',
			'label' => __('Offset', 'thesis'),
			'tooltip' => __('By entering an offset parameter, you can specify any number of results to skip.', 'thesis'));
		$more['sticky'] = array(
			'type' => 'radio',
			'label' => __('Sticky Posts', 'thesis'),
			'options' => array(
				'' => __('Show sticky posts in their natural position', 'thesis'),
				'show' => __('Show sticky posts at the top', 'thesis')));
		$more['exclude'] = array(
			'type' => 'checkbox',
			'label' => __('Exclude from Main Loop', 'thesis'),
			'tooltip' => __('If your Query Box is being used as part of the main content output, you may want to account for pagination and duplicate output. Selecting this option will effectively prevent the main loop from showing the posts contained in this query, and the output will not be shown on pagination.', 'thesis'),
			'options' => array(
				'yes' => __('Exclude results from the Main WP Loop.', 'thesis')));
		$pt_has_dep = array_flip($pt_has_dep);
		unset($pt_has_dep['page']);
		$options['more'] = array(
			'type' => 'group',
			'label' => __('Advanced Query Options', 'thesis'),
			'fields' => $more,
			'parent' => array('post_type' => array_keys($pt_has_dep))); // remove advanced options for pages since there is no need to sort
		return $options;
	}

	public function construct() {
		if (!$this->_display() || empty($this->options['exclude']['yes'])) return;
		$this->make_query();
		foreach ($this->query->posts as $post)
			$this->exclude[] = (int) $post->ID;
		add_filter('thesis_query', array($this, 'alter_loop'));
	}

	public function make_query() {
		global $thesis;
		if (!empty($this->options['post_type']) && $this->options['post_type'] == 'page') {
			if (empty($this->options['page'])) return;
			$query = array('page_id' => absint($this->options['page']));
		}
		else {
			$query = array( // start building the query
				'post_type' => !empty($this->options['post_type']) ? $this->options['post_type'] : '',
				'posts_per_page' => !empty($this->options['num']) ? (int) $this->options['num'] : absint($thesis->api->get_option('posts_per_page')),
				'ignore_sticky_posts' => !empty($this->options['sticky']) ? 0 : 1,
				'order' => !empty($this->options['order']) && $this->options['order'] == 'ASC' ? 'ASC' : 'DESC',
				'orderby' => !empty($this->options['orderby']) && in_array($this->options['orderby'], array('ID', 'author', 'title', 'modified', 'rand', 'comment_count', 'menu_order')) ? (string) $this->options['orderby'] : 'date');
			if (!empty($this->options['post_type']) && !empty($this->options[$this->options['post_type']. '_tax']) && (!empty($this->options[$this->options['post_type']. '_'. $this->options[$this->options['post_type']. '_tax']. '_term_text']) || !empty($this->options[$this->options['post_type']. '_'. $this->options[$this->options['post_type']. '_tax']. '_term'])))
				$query['tax_query'] = array(
					array(
						'taxonomy' => (string) $this->options[$this->options['post_type']. '_tax'],
						'field' => 'id',
						'terms' => !empty($this->options[$this->options['post_type']. '_'. $this->options[$this->options['post_type']. '_tax']. '_term_text']) ?
						(int) $this->options[$this->options['post_type']. '_'. $this->options[$this->options['post_type']. '_tax']. '_term_text'] :
						(int) $this->options[$this->options['post_type']. '_'. $this->options[$this->options['post_type']. '_tax']. '_term']));
			if (!empty($this->options['author']))
				$query['author'] = (string) $this->options['author'];
			if (!empty($this->options['offset']))
				$query['offset'] = (int) $this->options['offset'];
		}
		$this->query = new WP_Query(apply_filters("thesis_query_box_{$this->_id}", $query)); // new or cached query object
	}

	public function alter_loop($query) {
		if (!is_home()) return $query;
		$query->query_vars['post__not_in'] = $this->exclude;
		return $query;
	}

	public function html($args = array()) {
		global $thesis;
		if (empty($this->query))
			$this->make_query();
		if (empty($this->query)) return;
		extract($args = is_array($args) ? $args : array());
		$depth = isset($depth) ? $depth : 0;
		$tab = str_repeat("\t", $depth);
		$html = !empty($this->options['html']) ? $this->options['html'] : 'div';
		$list = $html == 'ul' || $html == 'ol' ? true : false;
		$link = !empty($this->options['output']['link']) ? $this->options['output']['link'] : false;
		$id = !empty($this->options['id']) ? ' id="'. trim(esc_attr($this->options['id'])). '"' : '';
		$class = (!empty($list) ?
			'query_list' : 'query_box'). (!empty($this->options['class']) ?
			' '. trim(esc_attr($this->options['class'])) : '');
		$hook = trim(esc_attr(!empty($this->options['_id']) ?
			$this->options['_id'] : (!empty($this->options['hook']) ?
			$this->options['hook'] : false)));
		$counter = 1;
		$depth = $list ? $depth + 2 : $depth + 1;
		if (!!$list) {
			if (!empty($hook))
				$thesis->api->hook("hook_before_$hook");
			echo "$tab<$html$id class=\"$class\">\n";
			if (!empty($hook))
				$thesis->api->hook("hook_top_$hook");
		}
		while ($this->query->have_posts()) {
			$this->query->the_post();
			do_action('thesis_init_post_meta', $this->query->post->ID);
			$post_schema = $thesis->api->schema->get_post_meta($this->query->post->ID);
			$schema = !empty($post_schema) ?
				($post_schema == 'no_schema' ? false : $post_schema) : (!empty($this->options['schema']) ?
				$this->options['schema'] : false);
			$schema_att = $schema ?
				' itemscope itemtype="'. esc_url($thesis->api->schema->types[$schema]). '"' : '';
			if (!!$list) {
				if (!empty($hook))
					$thesis->api->hook("hook_before_item_$hook", $counter);
				echo
					"$tab\t<li class=\"query_item_$counter\"$schema_att>\n",
					($link ?
					"$tab\t\t<a href=\"". esc_url(get_permalink()). "\">\n" : '');
			}
			else {
				if (!empty($hook))
					$thesis->api->hook("hook_before_$hook", $counter);
				echo "$tab<$html class=\"$class", (!empty($this->options['wp']['auto']) ? ' '. implode(' ', get_post_class()) : ''), "\"$schema_att>\n";
				if (!empty($hook))
					$thesis->api->hook("hook_top_$hook", $counter);
			}
			$this->rotator(array_merge($args, array('depth' => $depth, 'schema' => $schema, 'post_count' => $counter, 'post_id' => $this->query->post->ID)));
			if (!!$list) {
				echo ($link ?
					"$tab\t\t</a>\n" : ''),
					"$tab\t</li>\n";
				if (!empty($hook))
					$thesis->api->hook("hook_after_item_$hook", $counter);
			}
			else {
				if (!empty($hook))
					$thesis->api->hook("hook_bottom_$hook", $counter);
				echo "$tab</$html>\n";
				if (!empty($hook))
					$thesis->api->hook("hook_after_$hook", $counter);
			}
			$counter++;
		}
		if (!!$list) {
			if (!empty($hook))
				$thesis->api->hook("hook_bottom_$hook");
			echo "$tab</$html>\n";
			if (!empty($hook))
				$thesis->api->hook("hook_after_$hook");
		}
		wp_reset_query();
	}

	public function query($query) {
		$query->query_vars['posts_per_page'] = (int) $this->options['num'];
		return $query;
	}
}

class thesis_attribution extends thesis_box {
	protected function translate() {
		$this->title = __('Attribution', 'thesis');
	}

	protected function options() {
		return array(
			'text' => array(
				'type' => 'textarea',
				'rows' => 2,
				'label' => __('Attribution Text', 'thesis'),
				'tooltip' => __('You can override the default attribution text here. If you&#8217;d like to keep the default attribution text, simply leave this field blank.', 'thesis')));
	}

	protected function construct() {
		global $thesis;
		$thesis->wp->filter($this->_class, array(
			'wptexturize' => false,
			'convert_smilies' => false,
			'convert_chars' => false,
			'do_shortcode' => false,
			'wp_make_content_images_responsive' => false));
		if (class_exists('WP_Embed')) {
			$embed = new WP_Embed;
			add_filter($this->_class, array($embed, 'run_shortcode'), 8);
			add_filter($this->_class, array($embed, 'autoembed'), 8);
		}
		add_filter($this->_class, array($thesis->api, 'efa'));
	}

	public function html($args = array()) {
		global $thesis;
		extract($args = is_array($args) ? $args : array());
		if (!empty($this->options['text']))
			$text = $this->options['text'];
		else {
			$skin = trim($thesis->api->efh($thesis->skins->skin['name']));
			$skin = property_exists($thesis->skin, 'url') && !empty($thesis->skin->url) ?
				'<a href="'. esc_url($thesis->skin->url). "\" rel=\"nofollow\">$skin</a>" : $skin;
			$text = sprintf(__('This site rocks the %1$s Skin for <a href="%2$s" rel="nofollow">Thesis</a>.', 'thesis'), $skin, esc_url(apply_filters("{$this->_class}_url", 'http://diythemes.com/')));
		}
		echo
			str_repeat("\t", !empty($depth) ? $depth : 0),
			"<p class=\"attribution\">", trim(apply_filters($this->_class, $text)), "</p>\n";
	}
}

class thesis_js extends thesis_box {
	public $type = false;
	private $libs = array();

	protected function template_options() {
		$description = __('please include <code>&lt;script&gt;</code> tags', 'thesis');
		$libs = array(
			'jquery' => 'jQuery',
			'jquery-ui-core' => 'jQuery UI',
			'jquery-effects-core' => 'jQuery Effects',
			'thickbox' => 'Thickbox');
		return array(
			'title' => __('JavaScript', 'thesis'),
			'fields' => array(
				'libs' => array(
					'type' => 'checkbox',
					'label' => __('JavaScript Libraries', 'thesis'),
					'tooltip' => __('Want to add more JS libraries? You can add any script libraries registered with WordPress by using the <code>thesis_js_libs</code> filter.', 'thesis'),
					'options' => is_array($js = apply_filters('thesis_js_libs', $libs)) ? $js : $libs),
				'scripts' => array(
					'type' => 'textarea',
					'rows' => 4,
					'code' => true,
					'label' => __('Footer Scripts', 'thesis'),
					'tooltip' => __('The optimal location for most scripts is just before the closing <code>&lt;/body&gt;</code> tag. If you want to add JavaScript to your site, this is the preferred place to do that.<br /><br /><strong>Note:</strong> Certain scripts will only function properly if placed in the document <code>&lt;head&gt;</code>. Please place those scripts in the &ldquo;Head Scripts&rdquo; box below.', 'thesis'),
					'description' => $description),
				'head_scripts' => array(
					'type' => 'textarea',
					'rows' => 4,
					'code' => true,
					'label' => __('Head Scripts', 'thesis'),
					'tooltip' => __('If you wish to add scripts that will only function properly when placed in the document <code>&lt;head&gt;</code>, you should add them here.<br /><br /><strong>Note:</strong> Only do this if you have no other option. Scripts placed in the <code>&lt;head&gt;</code> will negatively impact Skin performance.', 'thesis'),
					'description' => $description)));
	}

	protected function construct() {
		add_action('hook_head', array($this, 'head_scripts'), 9);
		add_action('hook_after_html', array($this, 'add_scripts'), 8);
	}

	public function head_scripts() {
		if (!empty($this->template_options['head_scripts']))
			echo trim($this->template_options['head_scripts']), "\n";
		if (is_array($scripts = apply_filters('thesis_head_scripts', false)))
			foreach ($scripts as $script)
				echo "$script\n";
	}

	public function add_scripts() {
		$this->libs(!empty($this->template_options['libs']) && is_array($this->template_options['libs']) ? array_keys($this->template_options['libs']) : false);
		foreach ($this->libs as $lib => $src)
			echo "<script src=\"$src\"></script>\n";
		if (!empty($this->template_options['scripts']))
			echo trim($this->template_options['scripts']), "\n";
		if (is_array($scripts = apply_filters('thesis_footer_scripts', false)))
			foreach ($scripts as $script)
				echo "$script\n";
	}

	private function libs($libs) {
		global $wp_scripts;
		if (!is_array($libs)) return;
		$s = is_object($wp_scripts) ? $wp_scripts : new WP_Scripts;
		foreach ($libs as $lib)
			if (is_object($s->registered[$lib]) && empty($this->libs[$lib]) && !in_array($lib, $s->done)) {
				if (!empty($s->registered[$lib]->deps))
					$this->libs($s->registered[$lib]->deps);
				if (!empty($s->registered[$lib]->src))
					$this->libs[$lib] = $s->base_url. $s->registered[$lib]->src;
			}
	}
}

class thesis_tracking_scripts extends thesis_box {
	public $type = false;
	protected $filters = array(
		'menu' => 'site',
		'docs' => 'http://diythemes.com/thesis/rtfm/admin/site/tracking-scripts/');

	protected function translate() {
		global $thesis;
		$this->title = __($thesis->api->strings['tracking_scripts'], 'thesis');
		$this->filters['description'] = __('Add tracking scripts to the footer of your site', 'thesis');
	}

	protected function class_options() {
		global $thesis;
		return array(
			'scripts' => array(
				'type' => 'textarea',
				'rows' => 10,
				'code' => true,
				'label' => $this->title,
				'description' => __('please include <code>&lt;script&gt;</code> tags', 'thesis'),
				'tooltip' => sprintf(__('Any scripts you add here will be displayed just before the closing <code>&lt;/body&gt;</code> tag on every page of your site.<br /><br />If you need to add a script to your %1$s <code>&lt;head&gt;</code>, visit the <a href="%2$s">%1$s Head Editor</a> and click on the <strong>Head Scripts</strong> box.', 'thesis'), $thesis->api->base['html'], admin_url('admin.php?page=thesis&canvas=head'))));
	}

	protected function construct() {
		global $thesis;
		if (is_admin() && ($update = $thesis->api->get_option('thesis_scripts')) && !empty($update)) {
			update_option($this->_class, ($this->options = array('scripts' => $update)));
			delete_option('thesis_scripts');
			wp_cache_flush();
		}
		elseif (!empty($this->options['scripts']))
			add_action('hook_after_html', array($this, 'html'), 9);
	}

	public function html() {
		if (empty($this->options['scripts'])) return;
		echo trim($this->options['scripts']), "\n";
	}
}

class thesis_404 extends thesis_box {
	public $type = false;
	protected $filters = array(
		'menu' => 'site',
		'docs' => 'http://diythemes.com/thesis/rtfm/admin/site/custom-404-page/',
		'priority' => 40);
	private $page = false;

	public function translate() {
		$this->title = __('404 Page', 'thesis');
		$this->filters['description'] = __('Select a 404 page', 'thesis');
	}

	protected function construct() {
		global $thesis;
		$this->page = is_numeric($page = $thesis->api->get_option('thesis_404')) ? $page : $this->page;
		if (!empty($this->page)) {
			add_filter('thesis_404', array($this, 'query'));
			add_filter('thesis_404_page', array($this, 'set_page'));
		}
		if ($thesis->environment == 'admin')
			add_action('admin_post_thesis_404', array($this, 'save'));
	}

	public function query($query) {
		return $this->page ? new WP_Query("page_id=$this->page") : $query;
	}

	public function set_page() {
		return $this->page;
	}

	public function admin_init() {
		add_action('admin_head', array($this, 'css_js'));
	}

	public function css_js() {
		echo
			"<script>\n",
			"var thesis_404;\n",
			"(function($) {\n",
			"thesis_404 = {\n",
			"\tinit: function() {\n",
			"\t\t$('#edit_404').on('click', function() {\n",
			"\t\t\tvar page = $('#thesis_404').val();\n",
			"\t\t\tif (page != 0)\n",
			"\t\t\t\t$(this).attr('href', $('#edit_404').attr('data-base') + page + '&action=edit');\n",
			"\t\t\telse\n",
			"\t\t\t\treturn false;\n",
			"\t\t});\n",
			"\t}\n",
			"};\n",
			"$(document).ready(function($){ thesis_404.init(); });\n",
			"})(jQuery);\n",
			"</script>\n";
	}

	public function admin() {
		global $thesis;
		$tab = str_repeat("\t", $depth = 2);
		$docs = !empty($this->filters['docs']) ?
			' <a data-style="dashicon" href="'. esc_url($this->filters['docs']). '" title="'. __('See Documentation', 'thesis'). '" target="_blank" rel="noopener">&#xf348;</a>' : '';
		echo
			$thesis->api->alert(__('Saving 404 page&hellip;', 'thesis'), 'saving_options', true, false, 2),
			(!empty($_GET['saved']) ? $thesis->api->alert(trim($thesis->api->efh($_GET['saved'] === 'yes' ?
			__('404 page saved!', 'thesis') :
			__('404 page not saved. Please try again.', 'thesis'))), 'options_saved', true, false, $depth) : ''),
			"$tab<h3>", trim($thesis->api->efh($this->title)), "$docs</h3>\n",
			"$tab<form class=\"thesis_options_form\" method=\"post\" action=\"", admin_url('admin-post.php?action=thesis_404'), "\">\n",
			"$tab\t<div class=\"option_item option_field\">\n",
			wp_dropdown_pages(array(
				'name' => 'thesis_404',
				'echo' => 0,
				'show_option_none' => __('Select a 404 page', 'thesis'). ':',
				'option_none_value' => '0',
				'selected' => $this->page)),
			"$tab\t</div>\n",
			"$tab\t", wp_nonce_field('thesis-save-404', '_wpnonce-thesis-save-404', true, false), "\n",
			"$tab\t<button data-style=\"button save top-right\" id=\"save_options\" value=\"1\"><span data-style=\"dashicon big squeeze\">&#xf147;</span> ", trim($thesis->api->efn(sprintf(__('%1$s %2$s', 'thesis'), __($thesis->api->strings['save'], 'thesis'), $this->title))), "</button>\n",
			"$tab</form>\n",
			"$tab<a id=\"edit_404\" data-style=\"button action\" href=\"", admin_url("post.php?post=$this->page&action=edit"), "\" data-base=\"", admin_url('post.php?post='), "\"><span data-style=\"dashicon\">&#xf464;</span> ", trim($thesis->api->efn(sprintf(__('%1$s %2$s', 'thesis'), __($thesis->api->strings['edit'], 'thesis'), $this->title))), "</a>\n";
	}

	public function save() {
		global $thesis;
		$thesis->wp->check();
		$thesis->wp->nonce($_POST['_wpnonce-thesis-save-404'], 'thesis-save-404');
		$saved = 'no';
		if (is_numeric($page = $_POST['thesis_404'])) {
			if ($page == '0')
				delete_option('thesis_404');
			else
				update_option('thesis_404', $page);
			$saved = 'yes';
		}
		wp_redirect("admin.php?page=thesis&canvas=$this->_class&saved=$saved");
		exit;
	}
}