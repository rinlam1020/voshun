<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/24/2018
 * Time: 8:57 AM
 */

if (!function_exists('hc_get_menu')):

    function hc_get_menu($menus = array())
    {
        ?>
        <ul>
            <?php foreach ($menus as $menu): ?>
                <li><a href="<?=$menu['data']['url']?>">
                        <?=$menu['data']['title']?>
                    </a>
                    <?php if (!empty($menu['children'])):?>
                        <ul style="">
                            <?php foreach ($menu['children'] as $children1): ?>
                                <li><a href="<?=$children1['data']['url']?>"><?=$children1['data']['title']?></a>
                                    <?php if (!empty($children1['children'])): ?>
                                        <ul>
                                            <?php foreach ($children1['children'] as $children2): ?>
                                                <li><a href="<?=$children2['data']['url']?>"><?=$children2['data']['title']?></a></li>
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