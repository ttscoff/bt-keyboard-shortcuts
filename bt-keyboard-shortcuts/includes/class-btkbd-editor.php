<?php
/**
 * Block editor format + TinyMCE: Keyboard shortcut inserter (inline, not a block).
 *
 * @package BT_Keyboard_Shortcuts
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * BTKBD Block Editor + TinyMCE.
 */
class BTKBD_Editor
{

	/**
	 * Init.
	 */
	public static function init()
	{
		add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'));
		add_action('enqueue_block_editor_assets', array(__CLASS__, 'enqueue_block_editor_assets'));
		add_filter('mce_external_plugins', array(__CLASS__, 'mce_external_plugins'));
		add_filter('mce_buttons', array(__CLASS__, 'mce_buttons'));
		add_action('admin_footer-post.php', array(__CLASS__, 'render_picker_modal'));
		add_action('admin_footer-post-new.php', array(__CLASS__, 'render_picker_modal'));
	}

	/**
	 * Admin enqueue scripts (picker modal + TinyMCE icon CSS).
	 *
	 * @param string $hook Hook.
	 */
	public static function admin_enqueue_scripts($hook)
	{
		if ($hook !== 'post.php' && $hook !== 'post-new.php') {
			return;
		}
		wp_enqueue_script(
			'btkbd-picker',
			BTKBD_URL . 'src/kbd-picker.js',
			array(),
			BTKBD_VERSION,
			true
		);
		wp_enqueue_style(
			'btkbd-picker',
			BTKBD_URL . 'src/kbd-picker.css',
			array(),
			BTKBD_VERSION
		);
	}

	/**
	 * Enqueue block editor assets (format toolbar button).
	 */
	public static function enqueue_block_editor_assets()
	{
		wp_enqueue_script(
			'btkbd-format',
			BTKBD_URL . 'src/kbd-format.js',
			array('wp-format-library', 'wp-rich-text', 'wp-element', 'wp-i18n', 'wp-block-editor'),
			BTKBD_VERSION,
			true
		);
	}

	/**
	 * TinyMCE external plugins.
	 *
	 * @param array $plugins Plugins.
	 * @return array
	 */
	public static function mce_external_plugins($plugins)
	{
		$plugins['btkbd_insert_kbd'] = BTKBD_URL . 'src/tinymce-insert-kbd.js';
		return $plugins;
	}

	/**
	 * TinyMCE buttons.
	 *
	 * @param array $buttons Buttons.
	 * @return array
	 */
	public static function mce_buttons($buttons)
	{
		$buttons[] = 'btkbd_insert_kbd';
		return $buttons;
	}

	/**
	 * Render shortcut picker modal in admin footer.
	 */
	public static function render_picker_modal()
	{
		$screen = get_current_screen();
		if (!$screen || !post_type_supports($screen->post_type, 'editor')) {
			return;
		}
		$mod_labels = array(
			'cmd' => '⌘ Win',
			'alt' => '⌥ Alt',
			'shift' => '⇧',
			'ctrl' => '⌃',
			'fn' => 'Fn',
		);
		?>
		<div id="btkbd-shortcut-picker-modal" class="btkbd-picker-modal" style="display:none;" aria-hidden="true">
			<div class="btkbd-picker-backdrop"></div>
			<div class="btkbd-picker-dialog" role="dialog"
				aria-label="<?php esc_attr_e('Insert keyboard shortcut', 'bt-keyboard-shortcuts'); ?>">
				<div class="btkbd-picker-header">
					<h2><?php esc_html_e('Keyboard shortcut', 'bt-keyboard-shortcuts'); ?></h2>
					<button type="button" class="btkbd-picker-close"
						aria-label="<?php esc_attr_e('Close', 'bt-keyboard-shortcuts'); ?>">&times;</button>
				</div>
				<div class="btkbd-picker-body">
					<div class="btkbd-picker-modifiers">
						<p class="btkbd-picker-label"><?php esc_html_e('Modifiers', 'bt-keyboard-shortcuts'); ?></p>
						<?php foreach (array('cmd', 'alt', 'shift', 'ctrl', 'fn') as $mod): ?>
							<label class="btkbd-picker-checkbox">
								<input type="checkbox" data-mod="<?php echo esc_attr($mod); ?>">
								<span><?php echo esc_html($mod_labels[$mod]); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
					<div class="btkbd-picker-field">
						<label for="btkbd-picker-key"><?php esc_html_e('Key', 'bt-keyboard-shortcuts'); ?></label>
						<input type="text" id="btkbd-picker-key" class="btkbd-picker-input"
							placeholder="<?php esc_attr_e('e.g. s, return, tab', 'bt-keyboard-shortcuts'); ?>">
					</div>
					<div class="btkbd-picker-field">
						<label
							for="btkbd-picker-shortcode"><?php esc_html_e('Generated shortcode', 'bt-keyboard-shortcuts'); ?></label>
						<input type="text" id="btkbd-picker-shortcode" class="btkbd-picker-input btkbd-picker-shortcode"
							placeholder="[kbd]" readonly>
						<p class="btkbd-picker-help">
							<?php esc_html_e('Select and copy to paste elsewhere.', 'bt-keyboard-shortcuts'); ?>
						</p>
					</div>
					<div class="btkbd-picker-actions">
						<button type="button"
							class="button button-primary btkbd-picker-insert"><?php esc_html_e('Insert', 'bt-keyboard-shortcuts'); ?></button>
						<button type="button"
							class="button btkbd-picker-cancel"><?php esc_html_e('Cancel', 'bt-keyboard-shortcuts'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
