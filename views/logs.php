<?php
use Tussendoor\Bol\MainConfig; ?>

<?php $args = [
    'title'         => MainConfig::get('plugin.nameshort'),
    'description'   => esc_html__('Krijg hier inzicht in de acties van de plugin.', 'tussendoor-rdw'),
]; ?>

<?php $this->template('sections/admin.header', compact('args')); ?>

<div id="<?php echo esc_attr(MainConfig::get('plugin.tag')); ?>_logs" class="card-body p-lg-2 px-1 px-md-3 pt-0">
    <section class="card-inner-body pt-lg-2 d-lg-flex flex-wrap">
        <?php if (!empty($this->logFileNames)): ?>
            <div class="w-100 select-wrapper mb-4 d-flex flex-wrap gap-3">
                <select id="log_filter" name="logbook_selector" class="custom-select">
                    <?php foreach ($this->logFileNames as $file): ?>
                        <option value="<?php echo esc_attr($file); ?>" <?php echo ($file === $this->mostRecentLogName ? 'selected="selected"' : ''); ?>><?php echo esc_html($file); ?></option>
                    <?php endforeach; ?>
                </select>
            
                <button type="text" class="btn btn-sm btn-primary js--delete-specific-log" data-log="<?php echo esc_attr($this->mostRecentLogName); ?>"><?php esc_html_e('Verwijder deze log', 'tussendoor-rdw'); ?></button>
                <button type="text" class="btn btn-sm btn-danger js--delete-all-logs"><?php esc_html_e('Verwijder alle logboeken', 'tussendoor-rdw'); ?></button>
            </div>
            <div class="w-100 log-wrapper">
                <pre class="p-4 bg-light js--log-viewer"><?php require(MainConfig::get('plugin.logpath') . $this->mostRecentLogName); ?></pre>
            </div>
        <?php else: ?>
            <div class="w-100">
                <div class="alert alert-info"><p class="m-0"><?php esc_html_e('Er zijn nog geen logs om weer te geven.'); ?></p></div>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php $this->template('sections/admin.footer'); ?>