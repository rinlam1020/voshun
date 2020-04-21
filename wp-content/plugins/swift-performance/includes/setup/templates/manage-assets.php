<?php global $swift_performance_setup;?>
<h1><?php esc_html_e('Optimization', 'swift-performance'); ?></h1>
<h2><?php esc_html_e('Optimize static resources', 'swift-performance')?></h2>

<div id="merge-scripts-container" class="swift-p-row">
      <input type="hidden" name="options[merge-scripts]" value="0">
      <input type="checkbox" name="options[merge-scripts]" value="1" id="merge-scripts"<?php Swift_Performance_Setup::is_checked('merge-scripts');?>>
      <label for="merge-scripts">
            <?php esc_html_e('Merge Scripts', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('Combine all scripts into one file.', 'swift-performance')?></em></p>
</div>

<div id="minify-scripts-container" class="swift-hidden swift-p-row">
      <input type="hidden" name="options[minify-scripts]" value="0">
      <input type="checkbox" name="options[minify-scripts]" value="1" id="minify-scripts"<?php Swift_Performance_Setup::is_checked('minify-scripts');?>>
      <label for="minify-scripts">
            <?php esc_html_e('Minify Scripts', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('Minify the combined script.', 'swift-performance')?></em></p>
</div>

<hr>

<div id="merge-styles-container" class="swift-p-row">
      <input type="hidden" name="options[merge-styles]" value="0">
      <input type="checkbox" name="options[merge-styles]" value="1" id="merge-styles"<?php Swift_Performance_Setup::is_checked('merge-styles');?>>
      <label for="merge-styles">
            <?php esc_html_e('Merge Styles', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('Combine all styles into one file.', 'swift-performance')?></em></p>
</div>

<div id="bypass-import-container" class="swift-hidden swift-p-row">
      <input type="hidden" name="options[bypass-css-import]" value="0">
      <input type="checkbox" name="options[bypass-css-import]" value="1" id="bypass-css-import"<?php Swift_Performance_Setup::is_checked('bypass-css-import');?>>
      <label for="bypass-css-import">
            <?php esc_html_e('Bypass CSS Import', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('Include imported CSS files in merged styles.', 'swift-performance')?></em></p>
</div>

<hr>

<div id="disable-emojis-container" class="swift-p-row">
      <input type="hidden" name="options[disable-emojis]" value="0">
      <input type="checkbox" name="options[disable-emojis]" value="1" id="disable-emojis"<?php Swift_Performance_Setup::is_checked('disable-emojis');?>>
      <label for="disable-emojis">
            <?php esc_html_e('Disable Emojis', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('Disable default emojis.', 'swift-performance')?></em></p>
</div>

<div id="minify-html-container" class="swift-p-row">
      <input type="hidden" name="options[minify-html]" value="0">
      <input type="checkbox" name="options[minify-html]" value="1" id="minify-html"<?php Swift_Performance_Setup::is_checked('minify-html');?>>
      <label for="minify-html">
            <?php esc_html_e('Minify HTML', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('Remove unnecessary whitespaces from HTML.', 'swift-performance')?></em></p>
</div>

<div class="swift-hidden swift-p-row" id="optimize-prebuild-only-container">
      <input type="hidden" name="options[optimize-prebuild-only]" value="0">
      <input type="checkbox" name="options[optimize-prebuild-only]" value="1" id="optimize-prebuild-only"<?php Swift_Performance_Setup::is_checked('optimize-prebuild-only');?>>
      <label for="optimize-prebuild-only">
            <?php esc_html_e('Optimize Prebuild Only', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('In some cases optimizing the page takes some time. If you enable this option the plugin will optimize the page, only when prebuild cache process is running.', 'swift-performance')?></em></p>
</div>
<div class="swift-hidden swift-p-row" id="merge-background-only-container">
      <input type="hidden" name="options[merge-background-only]" value="0">
      <input type="checkbox" name="options[merge-background-only]" value="1" id="merge-background-only"<?php Swift_Performance_Setup::is_checked('merge-background-only');?>>
      <label for="merge-background-only">
            <?php esc_html_e('Optimize in Background', 'swift-performance');?>
      </label>
      <p><em><?php esc_html_e('In some cases optimizing the page takes some time. If you enable this option the plugin will optimize page in the background.', 'swift-performance')?></em></p>
</div>

<div class="swift-hidden swift-p-row" id="limit-threads-container">
      <input type="hidden" name="options[limit-threads]" value="0">
      <input type="checkbox" name="options[limit-threads]" value="1" id="limit-threads"<?php Swift_Performance_Setup::is_checked('limit-threads');?>>
      <label for="limit-threads">
            <?php esc_html_e('Limit Simultaneous Threads', 'swift-performance');?>
      </label>
      <div class="swift-performance-max-threads swift-hidden">
            <label><?php esc_html_e('Maximum threads', 'swift-performance')?> </label>
            <input type="number" min="1" name="options[max-threads]" value="<?php echo Swift_Performance::get_option('max-threads')?>">
      </div>
      <p><em><?php esc_html_e('Limit maximum simultaneous threads. It can be useful on shared hosting environment to avoid 508 errors.', 'swift-performance')?></em></p>
</div>
