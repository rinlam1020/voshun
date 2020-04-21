<div class="componnet-tns">
    <?php
    $token = md5('toannang@');
    include 'functions.php';
    global $status, $metadata;
    if(!empty($status['status']) && $status['status'] == true):?>
    <div id="duplicate-post-notice" class="notice is-dismissible">
        <p><?= $status['msg']?></p>
    </div>
    <?php elseif(!empty($status['status']) && $status['status'] == false): ?>
    <div id="duplicate-post-notice" class="notice is-dismissible">
        <p><?= $status['msg']?></p>
    </div>
    <?php endif;
    /**
     * Tìm kiếm
     */
    if(!empty($_GET['search'])){
        if($_GET['search'] == 'mostpopular'){
            $allcomponents = tn_topdownload($metadata);
        }
        elseif($_GET['search'] == 'featured'){
            $allcomponents = tn_topvote($metadata);
        }
        else{
            $allcomponents = array_find($_GET['search'],$database);
        }
        $seachstr = $_GET['search'];
    }
    else{
        $seachstr = '';
        $allcomponents = $database;
    }


    ?>
    <h2 class="nav-tab-wrapper toannang-component">
        <a href="admin.php?page=toan-nang-component" id="tncp_general_tab" class="nav-tab ">General</a>
        <a href="admin.php?page=toan-nang-component-add-new" id="tncp_add_new_tab" class="nav-tab nav-tab-active"><i class="dashicons-before dashicons-store"></i> Store</a>
        <a href="admin.php?page=toan-nang-component-changelog" id="tncp_changelog_tab" class="nav-tab">Changelog</a>
    </h2>
    <div id="tab-addnew" class="section wrap">
        <h1 class="wp-heading-inline">Thêm gói giao diện</h1>
        <div class="wp-filter">
            <ul class="filter-links">
                <li class="plugin-install-featured"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new" <?php if(!isset($_GET['search']) || $_GET['search'] == '') echo 'class="current"'?>>Tất cả</a> </li>
                <li class="plugin-install-popular"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new&search=header" <?php if(isset($_GET['search']) && $_GET['search'] == 'header') echo 'class="current"'?>>Header</a></li>
                <li class="plugin-install-recommended"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new&search=footer" <?php if(isset($_GET['search']) && $_GET['search'] == 'footer') echo 'class="current"'?>>Footer</a> </li>
                <li class="plugin-install-favorites"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new&search=home" <?php if(isset($_GET['search']) && $_GET['search'] == 'home') echo 'class="current"'?>>Home</a></li>
                <li class="plugin-install-favorites"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new&search=archive" <?php if(isset($_GET['search']) && $_GET['search'] == 'archive') echo 'class="current"'?>>Archive</a></li>
                <li class="plugin-install-favorites"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new&search=single" <?php if(isset($_GET['search']) && $_GET['search'] == 'single') echo 'class="current"'?>>Single</a></li>
                <li class="plugin-install-favorites"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new&search=page" <?php if(isset($_GET['search']) && $_GET['search'] == 'page') echo 'class="current"'?>>Page</a></li>
                <li class="plugin-install-favorites"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new&search=mostpopular" <?php if(isset($_GET['search']) && $_GET['search'] == 'mostpopular') echo 'class="current"'?>>Tải nhiều</a></li>
                <li class="plugin-install-favorites"><a href="/wp-admin/admin.php?page=toan-nang-component-add-new&search=featured" <?php if(isset($_GET['search']) && $_GET['search'] == 'featured') echo 'class="current"'?>>Đánh giá cao</a></li>
            </ul>

            <form class="search-form search-plugins" action="" method="get">
                <input type="hidden" value="toan-nang-component-add-new" name="page">
                <label><span class="screen-reader-text">Tìm gói</span>
                    <input type="search" name="search" value="<?= $seachstr ?>" class="wp-filter-search" placeholder="Tìm gói giao diện..." aria-describedby="live-search-desc">
                </label>
                <input type="submit" id="search-submit" class="button hide-if-js" value="Tìm plugin">
            </form>
        </div>
    </div>
    <div class="addons">
        <?php if(!empty($allcomponents)) : ?>
        <form method="post" action="" name="ba_settings_form">
            <div class="tncp_boxes">
                <?php foreach ($allcomponents as $index => $value) {     ?>

                            <div class="tncp_box alignleft dime postbox">
                                <h3 class="hndle ui-sortable-handle"><?= $value['name'] ?></h3>
                                <div class="clear"></div>
                                <div class="thumb">
                                    <?php $thumb = $server.$value['thumbnail'];?>
                                    <a data-fancybox="screenshoot" href="<?php echo $thumb; ?>"><img src="<?php echo $thumb ?>"/></a>
                                </div>
                                <div class="inside">
                                    <p class="description"><strong>Mô tả: </strong><?php echo $value['description'] ? $value['description'] : ''; ?> </p>
                                    <p class="author"><strong>Tác giả: </strong><?php echo $value['author'] ? $value['author'] : ''; ?></p>
                                    <p class="version"><strong>Folder: </strong><?php echo $index ? $index : ''; ?></p>
                                    <div class="rating"><?php $stars = $metadata[$index]['stars']?>
                                    <ul>
                                        <li><i class="fa  <?php if($stars >= 1){echo 'fa-star';}else{echo 'fa-star-o';} ?>"></i> </li>
                                        <li><i class="fa  <?php if($stars >= 2){echo 'fa-star';}else{echo 'fa-star-o';} ?>"></i> </li>
                                        <li><i class="fa  <?php if($stars >= 3){echo 'fa-star';}else{echo 'fa-star-o';} ?>"></i> </li>
                                        <li><i class="fa  <?php if($stars >= 4){echo 'fa-star';}else{echo 'fa-star-o';} ?>"></i> </li>
                                        <li><i class="fa  <?php if($stars >= 5){echo 'fa-star';}else{echo 'fa-star-o';} ?>"></i> </li>
                                    </ul>
                                    </div>
                                    <div class="downloads">(<?= number_format($metadata[$index]['downloads'])?> lượt tải về)</div>
                                    <div class="clear"></div>
                                    <p><?php
                                        if (!is_components_exists($index)) :     ?>
                                        <input type="hidden" name="token" value="<?= $token ?>">
                                            <button type="submit" name="<?php echo $index; ?>_download" id="<?php echo $index; ?>"
                                                    class="button-primary enable"><i class="dashicons-before dashicons-download"></i> Tải về</button>

                                        <?php else :?>
                                            <button type="button" class="button button-disabled" disabled="disabled">Đã tải về</button>
                                            <?php $key = tn_slugify_name_component($index); if(is_btn_enabled($key)): ?>
                                                <input type="submit" name="<?php echo $key; ?>_disable" id="ID_<?php echo $key; ?>"
                                                       value="Tắt" class="button-secondary disable"/>
                                            <?php else: ?>
                                                <input type="submit" name="<?php echo $key; ?>_enable" id="ID_<?php echo $key; ?>"
                                                       value="Kích hoạt" class="button-primary enable"/>
                                            <?php endif;?>
                                        <?php endif; ?></p>
                                </div>
                            </div>

                    <?php
                } ?>

            </div>
        </form>
        <?php endif; ?>
    </div>
</div>