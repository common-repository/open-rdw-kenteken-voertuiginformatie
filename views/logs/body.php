<?php
use Tussendoor\Bol\MainConfig; ?>

<div id="<?php echo esc_attr(MainConfig::get('plugin.tag')); ?>_logs" class="card-body p-lg-2 px-1 px-md-3 pt-0">
    <section class="card-inner-body pt-lg-2 d-lg-flex flex-wrap">
        <div class="w-100 select-wrapper mb-4">
            <select id="log_filter" name="logbook_selector" class="custom-select">
                <?php foreach ($this->logFileNames as $file): ?>
                    <option value="<?php echo esc_attr($file); ?>" <?php echo ($file === $this->mostRecentLogName ? 'selected="selected"' : ''); ?>><?php echo esc_html($file); ?></option>
                <?php endforeach; ?>                    
            </select>
        </div>
        <div class="w-100 log-wrapper">
            <pre class="p-4 bg-light js--log-viewer"><?php require(MainConfig::get('plugin.logpath') . $this->mostRecentLogName); ?></pre>
        </div>
    </section>
</div>