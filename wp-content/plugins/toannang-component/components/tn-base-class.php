<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/7/2018
 * Time: 10:51 AM
 */

if (!class_exists('TNCP_ToanNang')) {
    class TNCP_ToanNang
    {
        protected $path;
        protected $setting;
        protected $options;

        public function __construct($path, $ext = null)
        {
            if (is_array($path)) $this->setting = $path;
            else if (is_string($path)) {
                $dir = dirname($path);

                $this->path = @end(explode('/' , str_replace('\\', '/', $dir)));

                if (file_exists(str_replace('\\', '/', $dir . '/setting.json'))) {
                    $this->setting = (array)json_decode(file_get_contents(str_replace('\\', '/', $dir . '/setting.json')));
                } else {
                    $this->setting = include(str_replace('\\', '/', $dir . '/setting.php'));
                }
            }
        }

        public function getSetting()
        {
            return $this->setting ? $this->setting : array();
        }

        public function getPath($file = null)
        {

            if (!empty($this->path)) {
                return plugin_dir_url(__FILE__)  . $this->path . '/';
            }
            return plugin_dir_url($file);
        }

        public function getVersion()
        {
            return $this->setting['version'] ? $this->setting['version'] : '';
        }

        public function setOptions($array)
        {
            $this->options = array_merge($this->options, $array);
            return $this;
        }

        public function getOption($name)
        {
            return $this->options[$name];
        }

        static public function renderComponent($name, $options = array())
        {

            if (class_exists($name)) {
                $class = (new $name());
                if (!empty($options)) {
                    $class->setOptions($options);
                }
                $class->render();
            }
        }

    }
}
