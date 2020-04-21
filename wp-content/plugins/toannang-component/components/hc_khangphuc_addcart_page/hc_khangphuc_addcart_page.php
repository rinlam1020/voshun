<?php



if (!class_exists('TNCP_hc_khangphuc_addcart_page')){

    class TNCP_hc_khangphuc_addcart_page extends TNCP_ToanNang{



        protected $options = [

            'categories' => array(),

            'hotline_ban_hang' => '',

            'menu' => '',

        ];

        function __construct()

        {

            parent::__construct(__FILE__);

            parent::setOptions($this->options);

        }



        /*Add html to Render*/

        public function render(){  }

    }

}

