<?php $this->template('sections/admin.header', [
    'args' => $headerArgs,
]); ?>

<div class="card-body p-lg-2 px-1 px-md-3 pt-0">
    <section class="card-inner-body pt-lg-2 d-lg-flex flex-nowrap gap-5">
        <div class="tab-navigation dashboard-navigation pr-lg-3">
            <ul class="nav flex-lg-column">
                <?php foreach ($links as $tab => $arguments) {
                    $this->link($tab, $arguments['title'], $arguments['default'], $arguments);
                } ?>
            </ul>
        </div>
        <div class="tab-content dashboard-panels pr-lg-3 py-lg-0 py-4">
            <?php foreach ($panels as $tab => $arguments) {
                $this->panel($tab, $arguments['default'], $arguments);
            } ?>
        </div>
    </section>
</div>

<?php $this->template('sections/admin.footer'); ?>