<?php

namespace Admin\Partials;

use App\Fields;
use WPCF7_FormTag;
use App\MainConfig;
use App\Traits\HasView;

/**
 * Class CF7
 * This class is for the Contact Form 7
 * @package Tussendoor\OpenRDWPro
 */
class ContactForm7
{
	use HasView;
	/**
	 * Define the functionality for the contact form 7
	 *
	 * @since 2.0.0
	 *
	 * @param object Object of the confi class
	 */
	public function __construct()
	{
		add_action('init', [$this, 'wpcf7AddShortcode'], 7);
		add_action('admin_init', [$this, 'wpcf7AddTagGenerator'], 22);

		add_filter('wpcf7_validate_open_rdw', [$this, 'wpcf7Validate'], 10, 2);
		add_filter('wpcf7_validate_open_rdw*', [$this, 'wpcf7Validate'], 10, 2);
	}

	/**
	 * Validate the contact form 7 input
	 *
	 * @since 2.0.0
	 *
	 * @param object $result Result of contact form 7 validation
	 * @param string $tag Name of the contact form 7 tag
	 *
	 * @return object $result Return validated result
	 */
	public function wpcf7Validate($result, $tag)
	{
		$tag = new WPCF7_FormTag($tag);

		$name = $tag->name;
		$value = isset($_POST[$name]) ? sanitize_text_field($_POST[$name]) : null;

		if ($tag->is_required() && empty($value)) {
			$result->invalidate($tag, wpcf7_get_message('invalid_required'));
		}

		return $result;
	}

	/**
	 * Register the shortcode handler
	 *
	 * @since 2.0.0
	 *
	 */
	public function wpcf7AddShortcode()
	{
		if (function_exists('wpcf7_add_form_tag')) {
			wpcf7_add_form_tag('open_rdw', [$this, 'wpcf7ShortcodeHandler'], true);
			wpcf7_add_form_tag('open_rdw*', [$this, 'wpcf7ShortcodeHandler'], true);
		}
	}

	/**
	 * Register the shortcode handler
	 *
	 * @since 2.0.0
	 *
	 */
	public function wpcf7AddTagGenerator()
	{
		if (function_exists('wpcf7_add_tag_generator')) {
			wpcf7_add_tag_generator(
				'open_rdw',
				'Kenteken (Open RDW)',
				'wpcf7-tg-pane-open-rdw',
				[$this, 'wpcf7TagPane']
			);
		}
	}

	/**
	 * To add the shortcode handler for contact form 7
	 *
	 * @since 2.0.0
	 */
	public function wpcf7ShortcodeHandler($tag)
	{
		$tag = new WPCF7_FormTag($tag);

		if (empty($tag->name)) {
			return '';
		}

		$atts['class']  = $tag->get_class_option(!empty($class) ? $class : null) . ' open-data-rdw';
		$value = (string) reset($tag->values);
		$value = $tag->get_default_option($value);

		if ($tag->has_option('placeholder') || $tag->has_option('watermark')) {
			$atts['placeholder'] = $value;
			$value = '';
		}

		$atts['id']         = $tag->name;
		$atts['value']      = $value;
		$atts['type']       = 'text';
		$atts['name']       = $tag->name;
		$atts['style']      = 'text-transform:uppercase';
		$atts['maxlength']  = '8';

		$atts = wpcf7_format_atts($atts);

		$html = sprintf(
			'<span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span>',
			$tag->name,
			$atts,
			!empty($validation_error) ? $validation_error : null
		);

		$html .= ' <img src="' . MainConfig::get('plugin.asset_url') . 'images/front/ajax-loader.gif" id="open_rdw-loading" style="display:none">';
		$html .= ' <img src="' . MainConfig::get('plugin.asset_url') . 'images/front//warning-icon.png" id="open_rdw-error" style="display:none">';
		$html .= ' <img src="' . MainConfig::get('plugin.asset_url') . 'images/front/accepted-icon.png" id="open_rdw-accepted" style="display:none">';

		return $html;
	}


	/**
	 * To add pane box of contact form 7
	 *
	 * @since 2.0.0
	 *
	 * @param
	 */
	public function wpcf7TagPane($contact_form)
	{

		$fields = Fields::getFields();
		self::render('admin/wpcf7-display.php', array('fields' => $fields), $this);
	}
}
