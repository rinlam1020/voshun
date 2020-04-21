<?php

function thesis_aznet_1_defaults() {
    return array (
    'boxes' =>
    array (
        'thesis_html_container' =>
        array (
            'thesis_html_container_1348093642' =>
            array (
                'class' => '',
                '_admin' =>
                    array (
                        'open' => true,
                    ),
                '_id' => 'ContainerBoxes',
                '_name' => 'Container Boxes',
            ),
            'thesis_html_container_1348009564' =>
            array (
                'id' => 'container-site',
                'class' => '',
                '_id' => 'ContainerSite',
                '_name' => 'Container Site',
            ),
            'thesis_html_container_1348009571' =>
            array (
                'html' => 'none',
                '_id' => 'ContainerHome',
                '_name' => 'Container Home',
            ),
            'thesis_html_container_1348009575' =>
            array (
                'html' => 'none',
                '_id' => 'ContainerArchive',
                '_name' => 'Container Archive',
            ),
            'thesis_html_container_1348009577' =>
            array (
                'html' => 'none',
                '_id' => 'ContainerPage',
                '_name' => 'Container Page',
            ),
            'thesis_html_container_1348009579' =>
            array (
                'html' => 'none',
                '_id' => 'ContainerSingle',
                '_name' => 'Container Single',
            ),
            'thesis_html_container_1348009581' =>
            array (
                'html' => 'none',
                '_id' => 'ContainerFullPage',
                '_name' => 'Container Full Page',
            ),
            'thesis_html_container_1348009585' =>
            array (
                'html' => 'none',
                '_id' => 'ContainerWoocommerce',
                '_name' => 'Container Archive Woocommerce',
            ),
            'thesis_html_container_1348009587' =>
            array (
                'html' => 'none',
                '_id' => 'ContainerProduct',
                '_name' => 'Container Product',
            ),
        )
    ),
    'templates' =>
    array (
        'home' =>
        array (
            'boxes' =>
            array (
                'thesis_html_body' =>
                array (
                    0 => 'thesis_html_container_1348093642',
                ),
                'thesis_html_container_1348093642' =>
                array (
                    0 => 'thesis_html_container_1348009564',
                ),
                'thesis_html_container_1348009564' =>
                array(
                    0 => 'thesis_html_container_1348009571',
                ),
            ),
        ),
        'archive' =>
        array (
            'boxes' =>
            array (
                'thesis_html_body' =>
                array (
                    0 => 'thesis_html_container_1348093642',
                ),
                'thesis_html_container_1348093642' =>
                array (
                    0 => 'thesis_html_container_1348009564',
                ),
                'thesis_html_container_1348009564' =>
                array(
                    0 => 'thesis_html_container_1348009575',
                ),
            ),
        ),
        'custom_1348591137' =>
        array (
            'title' => 'Full Page',
            'options' =>
            array (
                'thesis_html_body' =>
                array (
                    0 => 'thesis_html_container_1348093642',
                ),
                'thesis_html_container_1348093642' =>
                array (
                    0 => 'thesis_html_container_1348009564',
                ),
                'thesis_html_container_1348009564' =>
                array(
                    0 => 'thesis_html_container_1348009581',
                ),
            ),
        ),
        'single' =>
        array (
            'boxes' =>
            array (
                'thesis_html_body' =>
                array (
                    0 => 'thesis_html_container_1348093642',
                ),
                'thesis_html_container_1348093642' =>
                array (
                    0 => 'thesis_html_container_1348009564',
                ),
                'thesis_html_container_1348009564' =>
                array(
                    0 => 'thesis_html_container_1348009579',
                ),
            ),
        ),
        'page' =>
        array (
            'boxes' =>
            array (
                'thesis_html_body' =>
                array (
                    0 => 'thesis_html_container_1348093642',
                ),
                'thesis_html_container_1348093642' =>
                array (
                    0 => 'thesis_html_container_1348009564',
                ),
                'thesis_html_container_1348009564' =>
                array(
                    0 => 'thesis_html_container_1348009577',
                ),
            ),
        ),
    ),
    'css' => '/*---:[ layout structure ]:---*/
body {
	font-family: \'Open Sans\',sans-serif;
	font-size: 14px;
	line-height: 22px;
	color: #111;
	background-color: #fff;
	padding: 0;
	margin: 0;
}
/*---:[ links ]:---*/
a {
	color: #0e4f8f;
	text-decoration: none;
}
p a {
	text-decoration: none;
}
p a:hover {
	text-decoration: none;
}
');
}
