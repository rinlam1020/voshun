<?php
/**
 * Name: Custom class file.
 * Description: Custome hock and function my theme aznet 1.
 * User: Hoang Neo
 * Date: 16/11/2017
 */

if (!class_exists('CustomThemeThesis')) :

    class CustomThemeThesis
    {
        public function __construct()
        {
            /**
             * load includes
             */
            require(__DIR__. '/includes/index.php');

            /**
             * Custom Function
             */
            require(__DIR__. '/my-function.php');

            /**
            * Call templates
            */
            require(__DIR__. '/templates/autoload.php');

            /**
             * Component layout ( master )
             */
            if(!is_admin()){
                require (__DIR__ . '/layouts.php');
            }
        }
    }

    /**
     *check class is exit
     * call class
     */

    function HN_CustomThemeThesis()
    {
        global $HNCustomThemeThesis;

        if (!isset($HNCustomThemeThesis)) {
            $HNCustomThemeThesis = new CustomThemeThesis();
        }

        return $HNCustomThemeThesis;
    }

    HN_CustomThemeThesis();

endif;