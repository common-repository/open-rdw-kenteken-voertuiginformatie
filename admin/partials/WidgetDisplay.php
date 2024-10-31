<?php
    /**
     * Our widget back-end view.
     */
?>
<div class="rdw-sort-fields rdw-expand-fields">
<p>
    <label for="<?php echo $this->get_field_name('title') ?>"><?php echo __('Widget title:', 'tussendoor-rdw') ?></label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_name('title') ?>" name="<?php echo $this->get_field_name('title') ?>" value="<?php echo esc_attr($settings['title']) ?>">
</p>
<p>
    <label for="<?php echo $this->get_field_id('class') ?>"><?php echo __('Widget class:', 'tussendoor-rdw') ?></label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id('class') ?>" name="<?php echo $this->get_field_name('class') ?>" value="<?php echo esc_attr($settings['class']) ?>">
</p>

<ul>
<?php

$categories = array();
$fields = [];

if (isset($settings['savedfields'])) {
    $fields = $settings['savedfields'];
} else {
    foreach ($settings['allfields']->categories as $key => $value) {
        $fields[] = $key;
    }
}

if (!isset($settings['checkedfields'])) {
    $settings['checkedfields'] = array();
}

$settings['allfields'] = (array) $settings['allfields'];

foreach ($fields as $value) {

    if (!in_array($settings['allfields']['categories'][$value], $categories)) {

        $categories[] = $settings['allfields']['categories'][$value];

        echo '<li class="ui-sortable">';
        echo '<input type="checkbox">';
        echo '<a>'.$settings['allfields']['categories'][$value].'</a>';
        echo '<ul style="display:none;">';

        foreach ($fields as $value) {

            $checked = array_search($value, $settings['checkedfields']) !== false ? 'checked="checked"' : '';

            if (end($categories) == $settings['allfields']['categories'][$value]) {

                echo '<li class="ui-sortable-handle">';
                echo '<label style="display: block;">';
                echo '<input type="checkbox" class="checkbox" '.$checked.' id="'.$value.'" name="'.$this->get_field_name('checkedfields[]').'" value="'.$value.'">'.$settings['allfields'][$value]['label'];
                echo '<input type="hidden" id="'.$value.'-hidden" name="'.$this->get_field_name('savedfields[]').'" value="'.$value.'">';
                echo '</label>';
                echo '</li>';

            }

        }

        echo '</ul>';
        echo '</li>';

    }

}
?>
</ul>
</div>
<?php
// Unfortunately we need this here so the sortable list and multi select tool works for newly dropped widgets -->
?>
<script type="text/javascript">
    jQuery(document).ready( function () {
        jQuery('.rdw-sort-fields ul').sortable();
    });
</script>