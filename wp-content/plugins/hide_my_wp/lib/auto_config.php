<?php

$prefix ='';
if (is_multisite() && $this->is_subdir_mu)
    $prefix =  $this->blog_path;



/* Reference:

private $post_replace_old=array();
private $post_replace_new=array();
private $post_preg_replace_new=array();
private $post_preg_replace_old=array();

private $top_replace_old=array();
private $top_replace_new=array();

private $replace_old=array();
private $replace_new=array();

private $preg_replace_old=array();
private $preg_replace_new=array();
$auto_config_replace_urls = array(); //strings with ==
private $auto_config_inline_css ='';
private $auto_config_inline_js = '';*/

//Gravity Form
if (class_exists( 'GFForms') && $this->opt('new_plugin_path')){
    include_once ('auto_config/gravity-forms.php');
}


if (class_exists( 'Jetpack') && $this->opt('new_plugin_path')){
    include_once('auto_config/jetpack.php');
}

//order ext etx in init (and load if class)
if (class_exists( 'WooCommerce') && $this->opt('new_plugin_path')){
    include_once ('auto_config/woo-commerce.php');
}



?>