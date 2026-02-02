<?php
/**
 * Plugin Name: BT Keyboard Shortcuts
 * Plugin URI: https://github.com/ttscoff/bt-keyboard-shortcuts/
 * Description: Apple-style keyboard shortcut markup. Shortcode [kbd] for ⌘⇧P-style output.
 * Version: 1.0.0
 * Author: Brett Terpstra
 * Author URI: https://brettterpstra.com
 * License: GPLv2 or later
 * Text Domain: bt-keyboard-shortcuts
 */

if (!defined('ABSPATH')) {
	exit;
}

define('BTKBD_PATH', plugin_dir_path(__FILE__));
define('BTKBD_URL', plugin_dir_url(__FILE__));
define('BTKBD_VERSION', '1.0.0');
define('BTKBD_OPTION_NAME', 'btkbd_options');

require_once BTKBD_PATH . 'includes/class-btkbd.php';
require_once BTKBD_PATH . 'includes/class-btkbd-editor.php';
require_once BTKBD_PATH . 'includes/class-btkbd-settings.php';

BTKBD_Kbd::init();
BTKBD_Editor::init();
BTKBD_Settings::init();
