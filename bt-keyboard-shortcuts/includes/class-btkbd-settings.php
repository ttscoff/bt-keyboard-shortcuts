<?php
/**
 * Admin settings page for BT Keyboard Shortcuts.
 *
 * @package BT_Keyboard_Shortcuts
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * BTKBD Settings.
 */
class BTKBD_Settings
{

	const OPTION_GROUP = 'btkbd_settings';
	const OPTION_NAME = 'btkbd_options';

	/**
	 * Init.
	 */
	public static function init()
	{
		add_action('admin_menu', array(__CLASS__, 'add_menu'));
		add_action('admin_init', array(__CLASS__, 'register_settings'));
		add_filter('plugin_action_links_' . plugin_basename(BTKBD_PATH . 'bt-keyboard-shortcuts.php'), array(__CLASS__, 'plugin_action_links'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_settings_assets'));
		add_action('admin_head-settings_page_bt-keyboard-shortcuts', array(__CLASS__, 'preview_styles'));
	}

	/**
	 * Inline styles for custom CSS preview on settings page.
	 */
	public static function preview_styles()
	{
		echo '<style>.btkbd-css-preview-wrap{margin-top:8px;padding:16px;background:#f0f0f1;border:1px solid #c3c4c7;border-radius:4px;}.btkbd-css-preview-sample{line-height:1.8;}</style>';
	}

	/**
	 * Add Settings link on Plugins page.
	 *
	 * @param array $links Plugin row links.
	 * @return array
	 */
	public static function plugin_action_links($links)
	{
		$url = admin_url('options-general.php?page=bt-keyboard-shortcuts');
		$links[] = '<a href="' . esc_url($url) . '">' . esc_html__('Settings', 'bt-keyboard-shortcuts') . '</a>';
		return $links;
	}

	/**
	 * Add settings page under Settings.
	 */
	public static function add_menu()
	{
		add_options_page(
			__('Keyboard Shortcuts', 'bt-keyboard-shortcuts'),
			__('Keyboard Shortcuts', 'bt-keyboard-shortcuts'),
			'manage_options',
			'bt-keyboard-shortcuts',
			array(__CLASS__, 'render_page')
		);
	}

	/**
	 * Register settings and sections/fields.
	 */
	public static function register_settings()
	{
		register_setting(
			self::OPTION_GROUP,
			self::OPTION_NAME,
			array(
				'type' => 'array',
				'sanitize_callback' => array(__CLASS__, 'sanitize_options'),
			)
		);

		add_settings_section(
			'btkbd_display',
			__('Display', 'bt-keyboard-shortcuts'),
			array(__CLASS__, 'section_display'),
			'bt-keyboard-shortcuts'
		);

		add_settings_field(
			'btkbd_use_plus',
			__('Use + between modifiers and keys', 'bt-keyboard-shortcuts'),
			array(__CLASS__, 'field_checkbox'),
			'bt-keyboard-shortcuts',
			'btkbd_display',
			array('key' => 'use_plus', 'label' => __('Show + separator (e.g. ⌘+⇧+S)', 'bt-keyboard-shortcuts'))
		);

		add_settings_field(
			'btkbd_use_modifier_symbols',
			__('Use modifier symbols', 'bt-keyboard-shortcuts'),
			array(__CLASS__, 'field_checkbox'),
			'bt-keyboard-shortcuts',
			'btkbd_display',
			array('key' => 'use_modifier_symbols', 'label' => __('Show ⌘⇧⌥⌃ symbols instead of text', 'bt-keyboard-shortcuts'))
		);

		add_settings_field(
			'btkbd_use_key_symbols',
			__('Use key symbols', 'bt-keyboard-shortcuts'),
			array(__CLASS__, 'field_checkbox'),
			'bt-keyboard-shortcuts',
			'btkbd_display',
			array('key' => 'use_key_symbols', 'label' => __('Show symbol entities for keys (Tab, Return, etc.)', 'bt-keyboard-shortcuts'))
		);

		add_settings_field(
			'btkbd_style',
			__('Mac / Windows symbols and names', 'bt-keyboard-shortcuts'),
			array(__CLASS__, 'field_style'),
			'bt-keyboard-shortcuts',
			'btkbd_display'
		);

		add_settings_section(
			'btkbd_css',
			__('Custom CSS', 'bt-keyboard-shortcuts'),
			array(__CLASS__, 'section_css'),
			'bt-keyboard-shortcuts'
		);

		add_settings_field(
			'btkbd_custom_css',
			__('Keyboard key styling', 'bt-keyboard-shortcuts'),
			array(__CLASS__, 'field_custom_css'),
			'bt-keyboard-shortcuts',
			'btkbd_css'
		);
	}

	/**
	 * Default option values.
	 *
	 * @return array
	 */
	public static function get_defaults()
	{
		return array(
			'use_plus' => false,
			'use_modifier_symbols' => true,
			'use_key_symbols' => true,
			'style' => 'mac',
			'custom_css' => self::get_default_css(),
		);
	}

	/**
	 * Default CSS content (from frontend stylesheet).
	 *
	 * @return string
	 */
	public static function get_default_css()
	{
		$file = BTKBD_PATH . 'assets/kbd-frontend.css';
		if (is_readable($file)) {
			return (string) file_get_contents($file);
		}
		return '';
	}

	/**
	 * Get options (merged with defaults).
	 *
	 * @return array
	 */
	public static function get_options()
	{
		$option_name = defined('BTKBD_OPTION_NAME') ? BTKBD_OPTION_NAME : self::OPTION_NAME;
		$saved = get_option($option_name, array());
		$saved = is_array($saved) ? $saved : array();
		return array_merge(self::get_defaults(), $saved);
	}

	/**
	 * Sanitize options on save.
	 *
	 * @param array $input Raw input.
	 * @return array
	 */
	public static function sanitize_options($input)
	{
		$defaults = self::get_defaults();
		$input   = is_array($input) ? $input : array();
		$out     = array();

		// Unchecked checkboxes are not sent in POST; use isset so missing = false.
		$out['use_plus']            = isset($input['use_plus']) && $input['use_plus'];
		$out['use_modifier_symbols'] = isset($input['use_modifier_symbols']) && $input['use_modifier_symbols'];
		$out['use_key_symbols']     = isset($input['use_key_symbols']) && $input['use_key_symbols'];
		$out['style']               = isset($input['style']) && $input['style'] === 'windows' ? 'windows' : 'mac';
		$out['custom_css']          = isset($input['custom_css']) ? wp_strip_all_tags($input['custom_css']) : $defaults['custom_css'];

		return $out;
	}

	/**
	 * Section Display description.
	 */
	public static function section_display()
	{
		echo '<p class="description">' . esc_html__('These options control how keyboard shortcuts are rendered. Shortcode attributes can override them per use.', 'bt-keyboard-shortcuts') . '</p>';
	}

	/**
	 * Section Custom CSS description.
	 */
	public static function section_css()
	{
		echo '<p class="description">' . esc_html__('Override the default styling for .btkbd keyboard keys. Changes update the preview below.', 'bt-keyboard-shortcuts') . '</p>';
	}

	/**
	 * Checkbox field.
	 *
	 * @param array $args Field args (key, label).
	 */
	public static function field_checkbox($args)
	{
		$opts = self::get_options();
		$key = $args['key'];
		$val = isset($opts[$key]) ? $opts[$key] : self::get_defaults()[$key];
		$name = self::OPTION_NAME . '[' . $key . ']';
		$id = 'btkbd-' . str_replace('_', '-', $key);
		printf(
			'<label><input type="checkbox" name="%s" id="%s" value="1" %s /> %s</label>',
			esc_attr($name),
			esc_attr($id),
			checked($val, true, false),
			esc_html($args['label'])
		);
	}

	/**
	 * Style field (Mac / Windows).
	 */
	public static function field_style()
	{
		$opts = self::get_options();
		$val = isset($opts['style']) ? $opts['style'] : 'mac';
		$name = self::OPTION_NAME . '[style]';
		?>
		<label><input type="radio" name="<?php echo esc_attr($name); ?>" value="mac" <?php checked($val, 'mac'); ?> />
			<?php esc_html_e('Mac (⌘ Command, ⌥ Option)', 'bt-keyboard-shortcuts'); ?></label><br>
		<label><input type="radio" name="<?php echo esc_attr($name); ?>" value="windows" <?php checked($val, 'windows'); ?> />
			<?php esc_html_e('Windows (⊞ Win, Alt)', 'bt-keyboard-shortcuts'); ?></label>
		<?php
	}

	/**
	 * Custom CSS field: textarea + preview + reset.
	 */
	public static function field_custom_css()
	{
		$opts = self::get_options();
		$css = isset($opts['custom_css']) ? $opts['custom_css'] : self::get_default_css();
		$name = self::OPTION_NAME . '[custom_css]';
		$id = 'btkbd-custom-css';
		$preview_id = 'btkbd-css-preview';
		$default_css = self::get_default_css();
		?>
		<div class="btkbd-css-wrap">
			<textarea name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" class="large-text code"
				rows="16"><?php echo esc_textarea($css); ?></textarea>
			<p>
				<button type="button" class="button"
					id="btkbd-css-reset"><?php esc_html_e('Reset to default', 'bt-keyboard-shortcuts'); ?></button>
			</p>
			<p><strong><?php esc_html_e('Preview', 'bt-keyboard-shortcuts'); ?></strong></p>
			<div id="<?php echo esc_attr($preview_id); ?>" class="btkbd-css-preview-wrap">
				<style id="btkbd-preview-style" type="text/css"></style>
				<div class="btkbd-css-preview-sample">
					<?php echo wp_kses_post(BTKBD_Kbd::render_sample_for_preview()); ?>
				</div>
			</div>
		</div>
		<script>
			(function () {
				var textarea = document.getElementById('<?php echo esc_js($id); ?>');
				var styleEl = document.getElementById('btkbd-preview-style');
				var defaultCss = <?php echo json_encode($default_css); ?>;

				function updatePreview() {
					if (styleEl) styleEl.textContent = textarea ? textarea.value : '';
				}
				if (textarea) {
					textarea.addEventListener('input', updatePreview);
					textarea.addEventListener('change', updatePreview);
				}
				updatePreview();

				var resetBtn = document.getElementById('btkbd-css-reset');
				if (resetBtn && textarea) {
					resetBtn.addEventListener('click', function () {
						textarea.value = defaultCss;
						updatePreview();
					});
				}
			})();
		</script>
		<?php
	}

	/**
	 * Enqueue assets on settings page.
	 *
	 * @param string $hook Admin hook.
	 */
	public static function enqueue_settings_assets($hook)
	{
		if ($hook !== 'settings_page_bt-keyboard-shortcuts') {
			return;
		}
		// No extra CSS/JS needed; inline script in field_custom_css.
	}

	/**
	 * Render settings page.
	 */
	public static function render_page()
	{
		if (!current_user_can('manage_options')) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields(self::OPTION_GROUP);
				do_settings_sections('bt-keyboard-shortcuts');
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
