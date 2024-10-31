<?php

namespace Admin\Partials;

use App\Fields;

class ContactForm7Display
{

    private $args;
    private $fields;

    public function __construct(array $fields, $args = null)
    {
        if (isset($args)) {
            $args = wp_parse_args($args, array());
        }

        if (!isset($args['content'])) {
            $args['content'] = '';
        }

        $this->fields = Fields::getFields();
    }

    public function render()
    {
?>
        <div class="control-box">
            <fieldset>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo esc_html(__('Field type', 'contact-form-7')); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><?php echo esc_html(__('Field type', 'contact-form-7')); ?></legend>
                                    <label><input type="checkbox" name="required" /> <?php echo esc_html(__('Required field', 'contact-form-7')); ?></label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($this->args['content'] . '-name'); ?>"><?php echo esc_html(__('Name', 'contact-form-7')); ?></label></th>
                            <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr($this->args['content'] . '-name'); ?>" /></td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($this->args['content'] . '-values'); ?>"><?php echo esc_html(__('Default value', 'contact-form-7')); ?></label></th>
                            <td><input type="text" name="values" class="oneline" id="<?php echo esc_attr($this->args['content'] . '-values'); ?>" /><br />
                                <label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html(__('Use this text as the placeholder of the field', 'contact-form-7')); ?></label>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($this->args['content'] . '-id'); ?>"><?php echo esc_html(__('Id attribute', 'contact-form-7')); ?></label></th>
                            <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr($this->args['content'] . '-id'); ?>" /></td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($this->args['content'] . '-class'); ?>"><?php echo esc_html(__('Class attribute', 'contact-form-7')); ?></label></th>
                            <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr($this->args['content'] . '-class'); ?>" /></td>
                        </tr>

                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <br>
                <strong><?= __('Use the shortcode in combination with the following fields:', 'Open RDW kenteken voertuiginformatie') ?></strong>
                <div class="rdw-expand-fields cf-7-row-expand">
                    <ul>
                        <?php $this->renderFieldCategories(); ?>
                    </ul>
                </div>
            </fieldset>
        </div>

        <div class="insert-box rdw-insert-box">
            <input type="text" name="open_rdw" class="tag code" readonly="readonly" onfocus="this.select()" />

            <div class="submitbox">
                <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr(__('Insert Tag', 'contact-form-7')); ?>" />
            </div>

            <br class="clear" />

            <p class="description mail-tag"><label for="<?php echo esc_attr($this->args['content'] . '-mailtag'); ?>"><?php echo sprintf(esc_html(__("To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7')), '<strong><span class="mail-tag"></span></strong>'); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr($this->args['content'] . '-mailtag'); ?>" /></label></p>
        </div>
<?php
    }

    private function renderFieldCategories()
    {
        $categories = [];

        foreach ($this->fields as $value) {
            if (!in_array($value['category'], $categories)) {
                $categories[] = $value['category'];

                echo '<li class="ui-sortable">';
                echo '<a>' . esc_html($value['category']) . '</a>';
                echo '<ul style="display:none;">';
                foreach ($this->fields as $key => $subValue) {
                    if (end($categories) == $subValue['category']) {
                        echo '<li class="ui-sortable-handle">';
                        echo '<label style="display: block;">';
                        echo esc_html($subValue['label']) . ': ';
                        echo '<input type="text" onClick="this.select();" readonly="readonly" value="[text ' . $key . ']" />';
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