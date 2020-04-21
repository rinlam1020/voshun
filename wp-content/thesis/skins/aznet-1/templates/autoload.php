<?php
/**
 * Name: Require list file.
 * Description: list function for skin.
 * User: Hoang Neo
 */

if (!class_exists('AutoLoadTemplate')) :
    class AutoLoadTemplate
    {

        public function __construct()
        {
            add_action('hook_top_ContainerSite', array($this, 'getTemplateHeader'), 1);
            /*------ list content------*/
            //add_action( 'hook_top_ContainerSite',  array( $this, 'getTemplateHeader' ), 2);
            add_action('hook_before_ContainerHome', array($this, 'getTemplateHome'), 1);
            add_action('hook_before_ContainerPage', array($this, 'getTemplatePage'), 1);
            add_action('hook_before_ContainerFullPage', array($this, 'getTemplateFullPage'), 1);
            //load category
            add_action('hook_before_ContainerArchive', array($this, 'getTemplateArchive'), 1);
            add_action('hook_before_ContainerArchiveSearch', array($this, 'getTemplateArchiveSearch'), 1);

            //load category woocommerce
            add_action('hook_before_ContainerArchiveProduct', array($this, 'getTemplateArchiveProduct'), 1);
            add_action('hook_before_ContainerSingleProduct', array($this, 'getTemplateSingleProduct'), 1);

            //load single
            add_action('hook_before_ContainerSingle', array($this, 'getTemplateSingle'), 1);
            //load single product
            add_action('hook_before_ContainerProduct', array($this, 'getTemplateProduct'), 1);
            /*------ list content------*/
            add_action('hook_bottom_ContainerSite', array($this, 'getTemplateFooter'), 1);

            /**
             * CUSTOM TEMPLATES
             */
            /*------ list content------*/
            add_action('hook_before_Container404', array($this, 'getTemplate404'), 1);
        }

        /**
         * get template
         */
        function getTemplateHeader()
        {
            require(__DIR__ . '/header.php');
        }

        function getTemplateFooter()
        {
            require(__DIR__ . '/footer.php');
        }

        /**
         * get template
         */
        function getTemplateHome()
        {
            require(__DIR__ . '/home.php');
        }

        function getTemplatePage()
        {
            require(__DIR__ . '/page.php');
        }

        function getTemplateFullPage()
        {
            require(__DIR__ . '/page-full.php');
        }

        function getTemplateArchive()
        {
            require(__DIR__ . '/archive.php');
        }

        function getTemplateArchiveSearch()
        {
            require(__DIR__ . '/archive-search.php');
        }

        function getTemplateSingle()
        {
            require(__DIR__ . '/single.php');
        }

        function getTemplateSingleProduct()
        {
            require(__DIR__ . '/single-product.php');
        }

        function getTemplateArchiveProduct()
        {
            require(__DIR__ . '/archive-product.php');
        }

        function getTemplate404()
        {
            require(__DIR__ . '/404.php');
        }
    }

    /**
     * check class is exit
     * call class
     */

    function HN_AutoLoadTemplate()
    {
        global $HNAutoLoadTemplate;

        if (!isset($HNAutoLoadTemplate)) {
            $HNAutoLoadTemplate = new AutoLoadTemplate();
        }

        return $HNAutoLoadTemplate;
    }

    HN_AutoLoadTemplate();

endif;



