<?php

namespace Admin\Partials;

use App\Fields;

class TinyMceDisplay
{

    protected $fields;
    protected $categories = [];

    public function __construct()
    {
        $this->fields = Fields::getFields();
    }

    public function render()
    {
?>
        <div id="rdw-thickbox" class="rdw-popup" style="display: none;">
            <div class="rdw-thickbox-content">
                <div id="rdw-thickbox-header">
                    <h3><?php echo __('Select which fields you would like to display:', 'tussendoor-rdw') ?></h3>
                </div>
                <div id="rdw-thickbox-content">
                    <div class="rdw-sort-fields rdw-expand-fields">
                        <ul>
                            <?php $this->render_fields(); ?>
                        </ul>
                    </div>
                </div>
                <div id="rdw-thickbox-footer">
                    <p>
                        <input type="button" value="<?php echo __('Cancel', 'tussendoor-rdw') ?>" class="button" onclick="tb_remove();">
                        <input type="button" value="<?php echo __('Add shortcode', 'tussendoor-rdw') ?>" class="button button-primary" id="rdw-thickbox-submit">
                    </p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            if (typeof jQuery != 'undefined') {
                jQuery(document).ready(function() {
                    jQuery('.rdw-sort-fields ul').sortable();
                });
            }
        </script>
<?php
    }

    protected function render_fields()
    {
        foreach ($this->fields as $value) {
            if (!in_array($value['category'], $this->categories)) {
                $this->categories[] = $value['category'];
                echo '<li class="ui-sortable">';
                echo '<input type="checkbox">';
                echo '<a>' . $value['category'] . '</a>';
                    echo '<ul style="display:none;">';
                        foreach ($this->fields as $key => $field) {
                            if ($field['category'] == $value['category']) {
                                echo '<li class="ui-sortable-handle">';
                                    echo '<label style="display: block;">';
                                        echo '<input type="checkbox" class="checkbox" id="' . $key . '" name="' . $key . '" value="' . $key . '">' . $field['label'];
                                    echo '</label>';
                                echo '</li>';
                            }
                        }
                    echo '</ul>';
                echo '</li>';
            }
        }
    }
}
?>