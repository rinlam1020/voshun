<?php global $swift_performance_setup;?>
<h1><?php esc_html_e('Media', 'swift-performance'); ?></h1>

<div class="swift-p-row">
      <input type="hidden" name="options[lazy-load-images]" value="0">
      <input type="checkbox" name="options[lazy-load-images]" value="1" id="lazyload-images-enabled"<?php Swift_Performance_Setup::is_checked('lazy-load-images');?>>
      <label for="lazyload-images-enabled">
            <?php esc_html_e('Lazy Load Images', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('Load images only when they appear in the browser’s viewport.', 'swift-performance')?><em></p>
</div>

<div class="swift-p-row">
      <input type="hidden" name="options[lazyload-iframes]" value="0">
      <input type="checkbox" name="options[lazyload-iframes]" value="1" id="lazyload-iframes-enabled"<?php Swift_Performance_Setup::is_checked('lazyload-iframes');?>>
      <label for="lazyload-iframes-enabled">
            <?php esc_html_e('Lazy Load Iframes', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('Load iframes only when they appear in the browser’s viewport.', 'swift-performance')?></em></p>
</div>

<div class="swift-p-row">
      <input type="hidden" name="options[optimize-uploaded-images]" value="0">
      <input type="checkbox" name="options[optimize-uploaded-images]" value="1" id="optimize-images-enabled"<?php Swift_Performance_Setup::is_checked('optimize-uploaded-images');?>>
      <label for="optimize-images-enabled">
            <?php esc_html_e('Optimize images on upload', 'swift-performance');?>
            <?php if (Swift_Performance::check_option('purchase-key','')):?>
            <div class="swift-performance-warning"><span class="dashicons dashicons-warning"></span><?php esc_html_e('Image Optimizer and Compute API requires a valid purchase key', 'swift-performance');?></div>
            <?php endif;?>
      </label>
      <p><em><?php esc_html_e('Enable if you would like to optimize the images during the upload using the our Image Optimization API service.', 'swift-performance')?></em></p>
</div>

<div id="keep-original-images-container" class="swift-p-row">
      <input type="hidden" name="options[keep-original-images]" value="0">
      <input type="checkbox" name="options[keep-original-images]" value="1" id="keep-original-images"<?php Swift_Performance_Setup::is_checked('keep-original-images');?>>
      <label for="keep-original-images">
            <?php esc_html_e('Keep Original Images', 'swift-performance');?>
            <?php if (Swift_Performance::check_option('purchase-key','')):?>
            <div class="swift-performance-warning"><span class="dashicons dashicons-warning"></span><?php esc_html_e('Image Optimizer and Compute API requires a valid purchase key', 'swift-performance');?></div>
            <?php endif;?>
      </label>
      <p><em><?php esc_html_e('If you enable this option the image optimizer will keep original images.', 'swift-performance')?></em></p>
</div>
