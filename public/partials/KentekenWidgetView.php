<?php

namespace Public\Partials;

use App\Fields;

class KentekenWidgetView
{
	private $args;
	private $settings;
	private $categories = [];
	private $allfields;
	private $checkedfields = [];

	public function __construct($args, $settings)
	{
		$this->args = $args;
		$this->settings = $settings;

		$this->allfields = Fields::getFields();

		if (isset($this->settings['checkedfields'])) {
			$this->checkedfields = $this->settings['checkedfields'];
		} else {
			$this->checkedfields = [];
		}
	}

	public function render($kenteken = null, $kentekeninfo = null)
	{
		?>
		<section id="<?php echo esc_attr($this->args['widget_id']); ?>" class="widget open_rdw_kenteken_widget <?php echo !empty($this->settings['class']) ? $this->settings['class'] : ''; ?>">
			<h2>
				<?php echo !empty($this->settings['title']) ? $this->settings['title'] : ''; ?>
			</h2>
			<form method="post" action="<?php echo esc_attr($_SERVER['REQUEST_URI']) ?>">
				<p><input type="text" name="<?php echo esc_attr($this->args['widget_id']); ?>" value="<?php if (isset($kenteken)) {
																											echo esc_attr($kenteken);
																										} ?>" maxlength="8"></p>
				<p><input name="submit" type="submit" id="submit" value="<?php echo __('Search license', 'tussendoor-rdw'); ?>"></p>
			</form>

			<?php
			if (!isset($kentekeninfo) && isset($kenteken)) {
				echo '<p>' . sprintf(__('Unfortunately no vehicle information was found for license plate %s.', 'tussendoor-rdw'), $kenteken) . '</p>';
			} elseif (isset($kentekeninfo)) {
				echo '<table>';
				foreach ($this->checkedfields as $field) {
					$field = strtolower($field);

					if (!isset($kentekeninfo[$field])) {
						continue;
					}
					$data = $kentekeninfo[$field];

					if ($data != '' && $data !== '0' && $data != 'Niet geregistreerd' && $data != 'N.v.t.') {
						if (!in_array($this->allfields[$field]['category'], $this->categories)) {
							$this->categories[] = $this->allfields[$field]['category'];
							echo '<tr class="open-rdw-head"><th colspan="2"><a>' . esc_html($this->allfields[$field]['category']) . '</a></th></tr>';
						}
						echo '<tr style="display: none;">';
						echo '<td>' . esc_html($this->allfields[$field]['label']) . '</td>';
						echo '<td>' . esc_html($data) . '</td>';
						echo '</tr>';
					}
				}
				echo '</table>';
			}
			?>
		</section>
	<?php
	}
}
?>