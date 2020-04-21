<div class="componnet-tns">
    <?php include 'functions.php';
    global $status;
    if(!empty($status['status']) && $status['status'] == true):?>
    <div id="duplicate-post-notice" class="notice is-dismissible">
        <p><?= $status['msg']?></p>
    </div>
    <?php elseif(!empty($status['status']) && $status['status'] == false): ?>
    <div id="duplicate-post-notice" class="notice is-dismissible">
        <p><?= $status['msg']?></p>
    </div>
    <?php endif;?>
    <h1 class="component-title">
        <img src="<?php echo get_tncp_option('url'); ?>assets/images/logo-toannang.png"
             alt="<?php echo get_tncp_option('name'); ?>" title="<?php echo get_tncp_option('name'); ?>"
             width="auto" height="46" class="alignleft"/><?php echo get_tncp_option('name')?></h1>
    <p><strong> <?php echo get_tncp_option('name'); ?> </strong>(v <?php echo get_tncp_option('version'); ?>) được
        phát triển bởi nhóm <a href="<?php echo get_tncp_option('pluginURI') ?>">Lập trình viên TN</a>. Plugin này cho
        phép liệt kê, quản lý các gói giao diện.</p>
    <?php
    global $autoupdate_config;
    $current_version = get_tncp_option('version');
    $update_data = json_decode(file_get_contents($autoupdate_config->url));
    ?>
    <?php if ($update_data->version !== $current_version || $autoupdate_config->type == 'dev'): ?>
        <script>
            jQuery(document).ready(function () {
                jQuery('#btn-update').on('click', function () {
                    var this_btn = this;
                    jQuery.ajax({
                        url: '',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'act': 'update',
                            'version': '<?= $update_data->version ?>'
                        },
                        beforeSend: function () {
                            jQuery(this_btn).text('Đang cập nhật...');
                        },
                        success: function (d, s) {
                            if (d.status) {
                                jQuery(this_btn).text(d.msg);
                            } else {
                                jQuery(this_btn).text(d.msg);
                            }
                        },
                        complete: function () {
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    });
                });
            });
        </script>
    <div id="duplicate-post-notice" class="notice is-dismissible">
        <p>Đã có phiên bản <?= $autoupdate_config->type == 'dev' ? 'developer' : $update_data->version ?>  <a href="javascript:;" id="btn-update" class="button-primary enable">Cập nhật ngay</a></p>
    </div>
        <?php endif; ?>
        <?php if (is_dev()): ?>
        <a href="javascript:;" id="btn-current-type">Chế độ developer</a>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Bỏ qua thông báo này </span></button>

    <?php endif; ?>
    <h2 class="nav-tab-wrapper toannang-component">
        <a href="admin.php?page=toan-nang-component" id="tncp_general_tab" class="nav-tab nav-tab-active">General</a>
        <a href="admin.php?page=toan-nang-component-add-new" id="tncp_add_new_tab" class="nav-tab"><i class="dashicons-before dashicons-store"></i> Store</a>
        <a href="admin.php?page=toan-nang-component-changelog" id="tncp_changelog_tab" class="nav-tab">Changelog</a>
    </h2>
    <div class="clear"></div>

    <?php $active = $all = $inactive = 0; foreach (get_tncp_setting() as $index => $value){
        $key = tn_slugify_name_component($index);
        if (is_btn_enabled($key)) $active++;
        else $inactive++;
    }
    ?>
    <ul class="subsubsub">
        <li class="all"><a href="admin.php?page=toan-nang-component&status=all" class="<?php if((isset($_GET['status']) && $_GET['status'] == 'all') || empty($_GET['status'])) :?>current <?php endif ?>" aria-current="page">Tất cả <span class="count">(<?= count(get_tncp_setting())?>)</span></a> |</li>
        <li class="active"><a href="admin.php?page=toan-nang-component&status=active" class="<?php if(isset($_GET['status']) && $_GET['status'] == 'active') :?>current <?php endif ?>">Kích hoạt <span class="count">(<?= $active ?>)</span></a> |</li>
        <li class="inactive"><a href="admin.php?page=toan-nang-component&status=inactive" class="<?php if(isset($_GET['status']) && $_GET['status'] == 'inactive') :?>current <?php endif ?>">Không kích hoạt <span class="count">(<?= $inactive ?>)</span></a></li>
    </ul>



    <div class="clear"></div>
    <?php if(isset($_GET['status']) && $_GET['status'] == 'active') :?>
        <div class="addons">
            <h3>Danh sách gói giao diện:</h3>
            <form method="post" action="" name="ba_settings_form">
                <div class="tncp_boxes">
                    <?php foreach (get_tncp_setting() as $index => $value) {
                        if (!empty($value['Component Name'])) {
                            $key = tn_slugify_name_component($index); ?>
                            <?php if (is_btn_enabled($key)) : ?>
                                <div class="tncp_box alignleft dime postbox a">
                                    <h3 class="hndle ui-sortable-handle"><?= $value['Component Name'] ?></h3>

                                    <div class="clear"></div>
                                    <div class="thumb">
                                        <?php
                                        if(file_exists(get_tncp_option('path') . 'components/' . $index . '/screenshoot.jpg') ){
                                            $thumb = get_tncp_option('url') . 'components/' . $index . '/screenshoot.jpg';
                                        }
                                        elseif(file_exists(get_tncp_option('path') . 'components/' . $index . '/screenshot.jpg')){
                                            $thumb = get_tncp_option('url') . 'components/' . $index . '/screenshot.jpg';
                                        }
                                        else{
                                            $thumb = get_tncp_option('url') . 'assets/images/logo-toannang.png';
                                        }
                                        ?>

                                        <a data-fancybox="screenshoot" href="<?php echo $thumb; ?>"><img
                                                    src="<?php echo $thumb ?>"/> </a>
                                    </div>
                                    <div class="inside">
                                        <p class="description"><strong>Mô
                                                tả: </strong><?php echo $value['Description'] ? $value['Description'] : ''; ?> </p>
                                        <p class="classname"><strong>Tên Class: </strong><?php if (class_exists($key)) :?> <?php echo 'TNCP_'.$key; ?> </p><?php endif;?>
                                        <p class="author"><strong>Tác
                                                giả: </strong><?php echo $value['Author'] ? $value['Author'] : ''; ?></p>
                                        <p class="version"><strong>Folder: </strong><?php echo $index ? $index : ''; ?></p>
                                        <p>
                                            <?php
                                            if (is_btn_enabled($key)) {
                                                ?>
                                                <input type="submit" name="<?php echo $key; ?>_disable" id="ID_<?php echo $key; ?>"
                                                       value="Tắt" class="button-secondary disable"/>
                                            <?php } else if ((tn_get_option($key) == '')) { ?>
                                                <input type="submit" name="<?php echo $key; ?>_enable" id="ID_<?php echo $key; ?>"
                                                       value="Kích hoạt" class="button-primary enable"/>
                                            <?php } ?>
                                            <?php if (!empty($value['Documentation'])) { ?>
                                                <a href="<?php echo $value['Documentation']; ?>" target="_blank"
                                                   class="button-secondary">Hướng dẫn</a>
                                            <?php } ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php }
                    } ?>

                </div>
            </form>
        </div>
    <?php elseif(isset($_GET['status']) && $_GET['status'] == 'inactive') : ?>
        <div class="addons">
            <h3>Danh sách gói giao diện:</h3>
            <form method="post" action="" name="ba_settings_form">
                <?php  $ids = array();  ?>

                <div class="tncp_boxes">
                    <?php foreach (get_tncp_setting() as $index => $value) {
                        if (!empty($value['Component Name'])) {
                            $key = tn_slugify_name_component($index); ?>
                            <?php if (!is_btn_enabled($key)) : ?>
                                <?php $ids[] = $index; ?>
                                <div class="tncp_box alignleft dime postbox">
                                    <h3 class="hndle ui-sortable-handle"><?= $value['Component Name'] ?></h3>
                                    <div class="clear"></div>
                                    <div class="thumb">
                                        <?php
                                        if(file_exists(get_tncp_option('path') . 'components/' . $index . '/screenshoot.jpg') ){
                                            $thumb = get_tncp_option('url') . 'components/' . $index . '/screenshoot.jpg';
                                        }
                                        elseif(file_exists(get_tncp_option('path') . 'components/' . $index . '/screenshot.jpg')){
                                            $thumb = get_tncp_option('url') . 'components/' . $index . '/screenshot.jpg';
                                        }
                                        else{
                                            $thumb = get_tncp_option('url') . 'assets/images/logo-toannang.png';
                                        }
                                        ?>
                                        <a data-fancybox="screenshoot" href="<?php echo $thumb; ?>"><img
                                                    src="<?php echo $thumb ?>"/> </a>
                                    </div>
                                    <div class="inside">
                                        <p class="description"><strong>Mô
                                                tả: </strong><?php echo $value['Description'] ? $value['Description'] : ''; ?> </p>
                                        <p class="classname"><strong>Tên Class: </strong><?php if (class_exists($key)) :?> <?php echo 'TNCP_'.$key; ?> </p><?php endif;?>
                                        <p class="author"><strong>Tác
                                                giả: </strong><?php echo $value['Author'] ? $value['Author'] : ''; ?></p>
                                        <p class="version"><strong>Folder: </strong><?php echo $index ? $index : ''; ?></p>
                                        <p>
                                            <?php
                                            if (is_btn_enabled($key)) {
                                                ?>
                                                <input type="submit" name="<?php echo $key; ?>_disable" id="ID_<?php echo $key; ?>"
                                                       value="Tắt" class="button-secondary disable"/>
                                            <?php } else if ((tn_get_option($key) == '')) { ?>
                                                <input type="submit" name="<?php echo $key; ?>_enable" id="ID_<?php echo $key; ?>"
                                                       value="Kích hoạt" class="button-primary enable"/>
                                            <?php } ?>
                                            <?php if (!empty($value['Documentation'])) { ?>
                                                <a href="<?php echo $value['Documentation']; ?>" target="_blank"
                                                   class="button-secondary">Hướng dẫn</a>
                                            <?php } ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php }
                    }  $ids = implode(',',$ids);?>

                </div>
                <input type="hidden" name="id_inactive_component" value="<?= $ids ?>">
                <?php if(!empty($ids)):?><div class="delete-components"><button name="deletecomponent" class="btn btn-danger"><i class="dashicons dashicons-trash"></i> Dọn dẹp</button></div>
                <?php endif; ?>
            </form>
        </div>
    <?php else: ?>
        <div class="alignright actions bulkactions deactivate-components">
            <label for="componentchecked">Chọn tất cả</label>
            <input id="componentchecked" type="checkbox" value="" name="selectcomponentall">
            <label for="bulk-action-selector-top" class="screen-reader-text">Lựa chọn thao tác hàng loạt</label>
            <select name="action_component" id="action_component">
                <option value="-1">Tác vụ</option>
                <option value="deactivate" class="hide-if-no-js">Tắt kích hoạt</option>
                <option value="active">Kích hoạt</option>
            </select>
            <a id="do-action-component" href="#" class="button action">Áp dụng</a>
        </div>
        <div class="clear"></div>
        <div class="addons">
            <h3>Danh sách gói giao diện:</h3>
            <form method="post" action="" name="ba_settings_form">

                <div class="allcomponent tncp_boxes">
                    <?php foreach (get_tncp_setting() as $index => $value) {
                        if (!empty($value['Component Name'])) {
                            $key = tn_slugify_name_component($index);?>
                            <div class="tncp_box alignleft dime postbox ">
                                <h3 class="hndle ui-sortable-handle"><?= $value['Component Name'] ?></h3>
                                <input class="form-control cmpchecked" type="checkbox" name="cmpchecked[]" value="<?= $key?>" />
                                <div class="clear"></div>
                                <div class="thumb">
                                    <?php
                                    if(file_exists(get_tncp_option('path') . 'components/' . $index . '/screenshoot.jpg') ){
                                        $thumb = get_tncp_option('url') . 'components/' . $index . '/screenshoot.jpg';
                                    }
                                    elseif(file_exists(get_tncp_option('path') . 'components/' . $index . '/screenshot.jpg')){
                                        $thumb = get_tncp_option('url') . 'components/' . $index . '/screenshot.jpg';
                                    }
                                    else{
                                        $thumb = get_tncp_option('url') . 'assets/images/logo-toannang.png';
                                    }
                                    ?>
                                    <a data-fancybox="screenshoot" href="<?php echo $thumb; ?>"><img
                                                src="<?php echo $thumb ?>"/> </a>
                                </div>
                                <div class="inside">
                                    <p class="description"><strong>Mô
                                            tả: </strong><?php echo $value['Description'] ? $value['Description'] : ''; ?> </p>
                                    <p class="classname"><strong>Tên Class: </strong><?php if (class_exists($key)) :?> <?php echo $key; ?> </p><?php endif;?>
                                    <p class="author"><strong>Tác
                                            giả: </strong><?php echo $value['Author'] ? $value['Author'] : ''; ?></p>
                                    <p class="version"><strong>Folder: </strong><?php echo $index ? $index : ''; ?></p>
                                    <p>
                                        <?php
                                        if (is_btn_enabled($key)) {
                                            ?>
                                            <input type="submit" name="<?php echo $key; ?>_disable" id="ID_<?php echo $key; ?>"
                                                   value="Tắt" class="button-secondary disable"/>
                                        <?php } else if ((tn_get_option($key) == '')) { ?>
                                            <input type="submit" name="<?php echo $key; ?>_enable" id="ID_<?php echo $key; ?>"
                                                   value="Kích hoạt" class="button-primary enable"/>
                                        <?php } ?>
                                        <?php if (!empty($value['Documentation'])) { ?>
                                            <a href="<?php echo $value['Documentation']; ?>" target="_blank"
                                               class="button-secondary">Hướng dẫn</a>
                                        <?php } ?>
                                    </p>
                                </div>
                            </div>

                        <?php }
                    } ?>

                </div>
            </form>
        </div>
    <?php endif ?>
</div>
<script>
    jQuery(document).ready(function () {
        jQuery(document).on('change','#componentchecked',function () {
            jQuery('.allcomponent').each(function () {
                var checked = jQuery(this).find('.cmpchecked');
                if(checked.attr('checked') !== 'checked'){
                    checked.attr('checked','checked');
                }
                else{
                    checked.removeAttr('checked');
                }
            })
        });
        jQuery('#do-action-component').click(function (e) {
            e.preventDefault();
            var mang = [];
            jQuery('.allcomponent .tncp_box').each(function () {
                var checked = jQuery(this).find('.cmpchecked');
                if(checked.attr('checked') === 'checked') {
                    mang.push(checked.val());
                }
            });
            var action_component = jQuery('#action_component').val();
            var form = jQuery('<form method="post" action="">' +
                '<input type="hidden" name="id_components" value="' + mang + '">' +
                '<input type="hidden" name="action_component" value="' + action_component + '"></form>');
            jQuery(document.body).append(form);
            jQuery(form).submit();

        })
    })
</script>