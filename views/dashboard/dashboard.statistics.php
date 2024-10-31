<?php

use App\Models\History;

?>

<div id="tab-statistics">

    <div class="tab-title mb-3">
        <h3><?php esc_html_e('Verbruik', 'tussendoor-rdw'); ?></h3>
    </div>

    <div class="input-group mb-3 align-items-center flex-nowrap">
        <div class="input-group-prepend">
            <label class="input-group-text w-100 fw-light border-0 custom-bg-light" for="php"><?php esc_html_e('Totaal deze maand', 'tussendoor-rdw'); ?></label>
        </div>
        <p class="mb-0 ms-2"><?php echo esc_html(History::getTotalMonthlyRequests()); ?></p>
    </div>

    <div class="input-group mb-3 align-items-center flex-nowrap">
        <div class="input-group-prepend">
            <label class="input-group-text w-100 fw-light border-0 custom-bg-light" for="php"><?php esc_html_e('Totaal deze week', 'tussendoor-rdw'); ?></label>
        </div>
        <p class="mb-0 ms-2"><?php echo esc_html(History::getRequestThisWeek()); ?></p>
    </div>

    <div class="input-group mb-3 align-items-center flex-nowrap">
        <div class="input-group-prepend">
            <label class="input-group-text w-100 fw-light border-0 custom-bg-light" for="php"><?php esc_html_e('Totaal vandaag', 'tussendoor-rdw'); ?></label>
        </div>
        <p class="mb-0 ms-2"><?php echo esc_html(History::getTotalTodayRequests()); ?></p>
    </div>

</div>