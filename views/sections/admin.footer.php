<?php

use App\MainConfig;

?>

            <div class="card-footer p-lg-2 px-1 px-md-3 bg-transparent border-0">
                <div class="card-footer-content d-flex">
                    <div class="logo">
                        <?php echo file_get_contents(MainConfig::get('plugin.path') . '/admin/images/logo-tsd-liggend.svg'); ?>
                    </div>
                    <div class="ms-auto d-flex align-items-center">
                        <a class="text-muted fw-light text-decoration-none" href="<?php echo esc_url(MainConfig::get('tussendoor.website')); ?>" target="_blank"><?php echo esc_html(MainConfig::get('tussendoor.website_short')); ?></a>
                        <p class="text-muted mx-3 my-0">&#8226;</p>
                        <a class="btn btn-md btn-primary rounded-pill fw-light custom-button-width me-3" href="<?php echo esc_url(MainConfig::get('tussendoor.contact')); ?>" target="_blank"><?php esc_html_e('Help', 'tussendoor-rdw'); ?></a>
                        <a class="btn btn-md btn-warning rounded-pill fw-light custom-button-width feedback" href="<?php echo esc_url(MainConfig::get('tussendoor.feedback')); ?>" target="_blank"><?php esc_html_e('Feedback', 'tussendoor-rdw'); ?></a>
                    </div>
                </div>
            </div>

        </div> <?php /* header: custom-main-card card shadow-none custom-min-view-height-90 m-0 */ ?>
    </div> <?php /* header: bg-light p-5 */ ?>
</div> <?php /* header: plugin.tag_dashboard */ ?>