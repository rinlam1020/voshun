<h1><?php _e('Analyze your site', 'swift-performance');?></h1>
<ul class="swift-analyze">
      <li data-step="timeout"><span class="dashicons dashicons-minus"></span> Timeout test <span class="result"></span></li>
      <li data-step="max-connections"><span class="dashicons dashicons-minus"></span> Check Max Connections <span class="result"></span></li>
      <li data-step="api"><span class="dashicons dashicons-minus"></span> API connection <span class="result"></span></li>
      <li data-step="cpu"><span class="dashicons dashicons-minus"></span> CPU Benchmark <span class="result"></span></li>
      <li data-step="webserver"><span class="dashicons dashicons-minus"></span> Webserver & Rewrites <span class="result"></span></li>
      <li data-step="loopback"><span class="dashicons dashicons-minus"></span> Loopback <span class="result"></span></li>
      <li data-step="varnish-proxy"><span class="dashicons dashicons-minus"></span> Detect Varnish & Cloudflare <span class="result"></span></li>
      <li data-step="php-settings"><span class="dashicons dashicons-minus"></span> PHP settings <span class="result"></span></li>
      <li data-step="plugins"><span class="dashicons dashicons-minus"></span> Detect 3rd party plugins <span class="result"></span></li>
      <li data-step="configure-cache"><span class="dashicons dashicons-minus"></span> Configure cache<span class="result"></span></li>
      <li data-step="self-check"><span class="dashicons dashicons-minus"></span> Validate settings (it can take up to 1-2 mins) <span class="result"></span></li>
</ul>

<div class="swift-preview-wrap swift-hidden"><iframe id="swift-preview-frame" src="" class="swift-preview-frame"></iframe></div>
