<?php

use App\MainConfig;

?>

<div id="tussendoor" class="<?php echo MainConfig::get('plugin.tag'); ?>_dashboard"> <?php /* closed in footer */ ?>
    <div class="bg-light p-5 tussendoor"> <?php /* closed in footer */ ?>
        <div class="custom-main-card card shadow-none custom-min-view-height-90 m-0 p-5 border-0 rounded u-overflow-hidden"> <?php /* closed in footer */ ?>

            <div class="card-header border-bottom-0 p-0">
                <div class="header-title d-flex align-items-center flex-wrap">
                    <?php if (!empty($args['badge'])): ?>
                        <div class="d-flex align-items-center flex-wrap">
                            <h1 class="card-title d-block float-none fw-bold">
                                <?php echo esc_html($args['title']); ?>
                            </h1>
                            <div class="badge text-bg-<?php echo esc_attr($args['badge']['class']); ?> rounded-pill fw-light ms-2">
                                <?php echo esc_html($args['badge']['text']); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <h1 class="card-title d-block float-none fw-bold">
                            <?php echo esc_html($args['title']); ?>
                        </h1>
                    <?php endif; ?>
                    <h6 class="card-subtitle mb-2 mt-1 d-block text-primary fw-lighter w-100"><?php echo esc_html($args['description']); ?></h6>
                </div>

                <?php if (!empty($args['back']) && $args['back'] === true): ?>
                    <button
                    class="btn btn-sm btn-primary ms-auto"
                    onclick="window.history.go(-1); return false;">
                        <i class="fa fa-chevron-left"></i>
                        <?php esc_html_e('Terug', 'woocommerce-wefact'); ?>
                    </button>
                <?php elseif (!empty($args['backUrl'])): ?>
                    <a href="<?php echo esc_url($args['backUrl']); ?>" class="btn btn-sm btn-primary ms-auto">
                        <i class="fa fa-chevron-left"></i>
                        <?php esc_html_e('Terug', 'woocommerce-wefact'); ?>
                    </a>
                <?php endif; ?>

                <div class="alert-container">
                    <hr class="wp-header-end w-100">
                </div>
            </div>