<?php



if (!function_exists('tn_cat_product_recursive')) {

    function tn_cat_product_recursive($arraylist, $parent_id = 0)

    {

        $arrays = [];

        foreach ($arraylist as $key => $item) {

            if ($item->parent == $parent_id && $item->slug != 'chua-phan-loai') {

                $array = [];

                $array['data'] = [

                    'term_id' => $item->term_id,

                    'name' => $item->name,

                    'slug' => $item->slug,

                    'count' => $item->count,

                    'description' => $item->description,

                    'parent'      => $item->parent

                ];

                unset($arraylist[$key]);



                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp

                $array['children'] = tn_cat_product_recursive($arraylist, $item->term_id);



                $arrays[] = $array;

            }

        }

        return $arrays;

    }

}



if (!function_exists('woocommerce_khangphuc_archive')):

    function woocommerce_khangphuc_archive($categories = array())

    {
        $name = get_the_archive_title();
        ?>

        <ul class="cat-menu">

            <?php foreach ($categories as $category): ?>

                <li>

                    <a href="<?=get_category_link( $category['data']['term_id'] )?>" <?php if ($name == $category['data']['name']) echo 'class="current"'; ?>>

                        <?=$category['data']['name']?>
                    </a>

                    <?php if (!empty($category['children'])):?>

                        <ul>

                            <?php foreach ($category['children'] as $children1): ?>

                                <li>

                                    <a href="<?=get_category_link( $children1['data']['term_id'] )?>" <?php if ($name == $children1['data']['name']) echo 'class="current"'; ?>><?=$children1['data']['name']?>

                                        <?php if (!empty($children1['children'])): ?><i class="fas fa-angle-double-right"></i> <?php endif; ?>

                                    </a>

                                    <?php if (!empty($children1['children'])): ?>

                                        <ul>

                                            <?php foreach ($children1['children'] as $children2): ?>

                                                <li><a href="<?=get_category_link( $children2['data']['term_id'] )?>" <?php if ($name == $children2['data']['name']) echo 'class="current"'; ?>><?=$children2['data']['name']?></a></li>

                                            <?php endforeach; ?>

                                        </ul>

                                    <?php endif; ?>

                                </li>

                            <?php endforeach; ?>

                        </ul>

                    <?php endif; ?>

                </li>

            <?php endforeach; ?>

        </ul>

        <?php

    }

endif;