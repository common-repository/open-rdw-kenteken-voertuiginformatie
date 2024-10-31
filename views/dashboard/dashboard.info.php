<?php

use App\Api;
use App\MainConfig;

?>

<div id="tab-info">
    <div id="info_table">

        <div class="tab-title mb-3">
            <h3><?php esc_html_e('Installatie', 'tussendoor-rdw'); ?></h3>
        </div>

        <div class="input-group mb-3 align-items-center flex-nowrap">
            <div class="input-group-prepend">
                <label class="input-group-text w-100 fw-light border-0 custom-bg-light" for="php"><?php esc_html_e('PHP', 'tussendoor-rdw'); ?></label>
            </div>
            <p id="php" class="mb-0 ms-2"><?php echo phpversion(); ?></p>
        </div>
    </div>

    <div id="versions" class="mt-5">

        <div class="tab-title mb-3">
            <h3><?php esc_html_e('Versies', 'tussendoor-rdw'); ?></h3>
        </div>

        <div class="input-group mb-3 align-items-center flex-nowrap">
            <div class="input-group-prepend">
                <label class="input-group-text w-100 fw-light border-0 custom-bg-light" for="version">
                    <?php echo MainConfig::get('plugin.nameshort'); ?>
                </label>
            </div>
            <p id="version" class="mb-0 ms-2">
                <?php
                echo esc_html(MainConfig::get('plugin.version'));
                if (MainConfig::get('plugin.is_beta')) {
                ?>
            <div class="badge text-bg-warning rounded-pill fw-light ms-2">Beta</div>
        <?php
                }
        ?>
        </p>
        </div>
        <div class="input-group mb-3 align-items-center flex-nowrap">
            <div class="input-group-prepend">
                <label class="input-group-text w-100 fw-light border-0 custom-bg-light" for="version">
                    API
                </label>
            </div>
            <p id="version" class="mb-0 ms-2">
                <?php echo esc_html(MainConfig::get('api.textversion')); ?>
            </p>
        </div>

        <?php
        /**
         * Action hook to add content after the version input
         * Used for add-ons to add their own version input
         */
        do_action('tussendoor_bol_after_main_version_input'); ?>
    </div>

    <div id="licenses" class="mt-5">
        <div class="tab-title mb-3">
            <h3><?php esc_html_e('Licentie', 'tussendoor-rdw'); ?></h3>
        </div>

        <?php
        /**
         * Action hook to add content before the license input
         */
        do_action('tussendoor_bol_before_main_license_input'); ?>

        <div class="input-group mb-3 align-items-center flex-nowrap">
            <div class="input-group-prepend">
                <label class="input-group-text w-100 fw-light border-0 custom-bg-light" for="license">
                    <?php echo MainConfig::get('plugin.name'); ?>
                </label>
            </div>
            <p id="license" class="mb-0 ms-2">
                <span data-bs-toggle="modal" data-bs-target="#register" class="link-primary cursor-pointer text-decoration-underline">
                    <?php if (!get_option('rdw_tsd_license')) : ?>
                        <i class="fas fa-exclamation-circle"></i>
                    <?php endif; ?>

                    <?php echo get_option('rdw_tsd_license') ?: esc_html_e('Voer een licentiecode in', 'tussendoor-rdw'); ?>

                    <?php if (get_option('rdw_tsd_license')) : ?>
                        <i class="ms-1 <?php echo get_option('tsd_rdw_license_status') === 'valid' ?
                            'fa-solid fa-circle-check text-success' :
                            'fas fa-exclamation-circle text-danger'; ?>"
                            title="<?php echo get_option('tsd_rdw_license_status') === 'valid' ?
                            esc_attr__('Geldig', 'tussendoor-rdw') :
                            esc_attr__('Ongeldig', 'tussendoor-rdw'); ?>">
                        </i>
                    <?php endif; ?>
                </span>
            </p>
        </div>

        <?php
        /**
         * Action hook to add content after the license input
         * Used for add-ons to add their own license input
         */
        do_action('tussendoor_bol_after_main_license_input'); ?>
    </div>

    <div id="auth" class="mt-5">
        <div class="tab-title mb-3">
            <h3><?php esc_html_e('Authenticatie', 'tussendoor-rdw'); ?></h3>
        </div>

        <div class="input-group mb-3 align-items-center flex-nowrap">
            <div class="input-group-prepend">
                <label class="input-group-text w-100 fw-light border-0 custom-bg-light" for="license">
                    <?php esc_html_e('Tussendoor API Token', 'tussendoor-rdw'); ?>
                    <small>
                        <i class="fa fa-info-circle ms-2 text-info"
                        data-bs-toggle="tooltip"
                        data-bs-custom-class="tussendoor-tooltip tussendoor-info"
                        title="<?php esc_attr_e(
                                'Indien het ophalen van kentekens niet werkt kun je dit proberen.
                                Neem anders contact op met Tussendoor B.V.', 'tussendoor-rdw'
                            ); ?>">
                        </i>
                    </small>
                </label>
            </div>
            <p id="license" class="mb-0 ms-2">
                <span type="button"
                    class="btn btn-sm btn-primary js--refresh-api-token">
                    <i class="me-1 fa-solid fa-rotate"></i>
                    <?php esc_html_e('Vernieuwen', 'tussendoor-rdw'); ?>
                </span>
            </p>
        </div>
    </div>
</div>

<div class="modal fade" id="register" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-none border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title"><?php echo get_option('rdw_tsd_license') ? esc_html__('Jouw licentie', 'tussendoor-rdw') : esc_html__('Activeer de plugin', 'tussendoor-rdw'); ?></h5>
                <i class="fa-solid fa-xmark p-2" role="button" data-bs-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body border-0">
                <p class="m-0">
                    <?php echo get_option('rdw_tsd_license') ? esc_html__('Voeg een nieuwe licentiecode toe als je de vorige wil overschrijven.', 'tussendoor-rdw') : esc_html__('Voeg hier je licentiecode toe.', 'tussendoor-rdw'); ?>
                </p>
                <div class="input-group mb-3 flex-nowrap">
                    <div class="input-group-prepend-small">
                        <label for="license_code" class="input-group-text w-100 fw-light border-0 custom-bg-light"><?php esc_html_e('Licentie code', 'tussendoor-rdw') ?></label>
                    </div>
                    <input type="text" class="border-0 w-75" id="license_code" placeholder="<?php esc_html_e('Code', 'tussendoor-rdw'); ?>" value="<?php echo get_option('rdw_tsd_license'); ?>">
                </div>
            </div>
            <div class="modal-footer border-0 mt-3 justify-content-end">
                <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal"><?php esc_html_e('Sluiten', 'tussendoor-rdw'); ?></button>
                <button type="button" class="btn btn-sm btn-success js--register-plugin"><i class="me-1 fa-solid fa-save"></i> <?php esc_html_e('Opslaan', 'tussendoor-rdw'); ?></button>
            </div>
        </div>
    </div>
</div>