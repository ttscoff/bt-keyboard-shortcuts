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

		add_settings_field(
			'btkbd_style_preset',
			__('Keyboard key style preset', 'bt-keyboard-shortcuts'),
			array(__CLASS__, 'field_style_preset'),
			'bt-keyboard-shortcuts',
			'btkbd_display'
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
			'style_preset' => 'default',
		);
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
		$input   = is_array($input) ? $input : array();
		$out     = array();

		// Unchecked checkboxes are not sent in POST; use isset so missing = false.
		$out['use_plus']            = isset($input['use_plus']) && $input['use_plus'];
		$out['use_modifier_symbols'] = isset($input['use_modifier_symbols']) && $input['use_modifier_symbols'];
		$out['use_key_symbols']     = isset($input['use_key_symbols']) && $input['use_key_symbols'];
		$out['style']               = isset($input['style']) && $input['style'] === 'windows' ? 'windows' : 'mac';
		$out['style_preset']        = self::sanitize_style_preset(isset($input['style_preset']) ? $input['style_preset'] : 'default');

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
	 * Style preset field.
	 */
	public static function field_style_preset()
	{
		$opts = self::get_options();
		$val = isset($opts['style_preset']) ? self::sanitize_style_preset($opts['style_preset']) : 'default';
		$name = self::OPTION_NAME . '[style_preset]';
		?>
		<select name="<?php echo esc_attr($name); ?>" id="btkbd-style-preset">
			<option value="default" <?php selected($val, 'default'); ?>><?php esc_html_e('Default', 'bt-keyboard-shortcuts'); ?></option>
			<option value="light" <?php selected($val, 'light'); ?>><?php esc_html_e('Light', 'bt-keyboard-shortcuts'); ?></option>
			<option value="dark" <?php selected($val, 'dark'); ?>><?php esc_html_e('Dark', 'bt-keyboard-shortcuts'); ?></option>
			<option value="modern" <?php selected($val, 'modern'); ?>><?php esc_html_e('Modern', 'bt-keyboard-shortcuts'); ?></option>
		</select>
		<p class="description">
			<?php esc_html_e('Choose a built-in keycap style. Advanced customization can still be done in Appearance > Customize > Additional CSS.', 'bt-keyboard-shortcuts'); ?>
		</p>
		<?php
	}

	/**
	 * Sanitize preset slug.
	 *
	 * @param string $preset Preset slug.
	 * @return string
	 */
	private static function sanitize_style_preset($preset)
	{
		$allowed = array('default', 'light', 'dark', 'modern');
		$preset = is_string($preset) ? strtolower($preset) : 'default';
		return in_array($preset, $allowed, true) ? $preset : 'default';
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
			<div class="notice notice-info inline">
				<p>
					<?php esc_html_e('To customize BT Keyboard Shortcuts styles, use Appearance > Customize > Additional CSS (or the Site Editor equivalent for block themes) and target the .btkbd classes.', 'bt-keyboard-shortcuts'); ?>
				</p>
			</div>
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
