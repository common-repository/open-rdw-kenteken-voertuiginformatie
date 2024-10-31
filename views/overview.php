<?php

use App\MainConfig;

$subtitle = sprintf(
    /* Translators: %s is the viewtype like "orders" or "returns" */
    esc_html__('Op deze pagina heb je inzicht in je %s.', 'tussendoor-rdw'),
    $type,
); ?>

<?php $args = [
    'title'         => MainConfig::get('plugin.nameshort'),
    'description'   => $subtitle,
]; ?>

<?php $this->template('sections/admin.header', compact('args')); ?>

<?php $this->template($viewType . '/' . $viewType . '.overview', compact('objects')); ?>

<?php $this->template('sections/admin.footer'); ?>
