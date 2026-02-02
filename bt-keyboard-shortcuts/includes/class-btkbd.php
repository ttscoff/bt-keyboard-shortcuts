<?php
/**
 * Kbd shortcode: Apple-style keyboard shortcut markup (replicates Jekyll kbd_tag.rb).
 *
 * [kbd cmd shift p] -> <span class="keycombo"><kbd class="mod symbol">&#8984;</kbd><kbd class="key symbol">P</kbd></span>
 * Supports: cmd/command, ctrl/control, opt/alt, shift, fn, hyper; key names (tab, return, esc, etc.); multiple combos separated by /
 *
 * Options: plus, text, mod_text, key_text (or use_plus=1, symbols=0, etc.)
 *
 * @package BT_Keyboard_Shortcuts
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * BTKBD Kbd shortcode.
 */
class BTKBD_Kbd
{

	private static $mod_order = array('Fn', '⌃', '⌥', '⇧', '⌘', 'Hyper');
	private static $mod_order_win = array('Fn', '⌃', 'Alt', '⇧', '⊞', 'Hyper');
	private static $mod_to_ent = array('⌃' => '&#8963;', '⌥' => '&#8997;', '⇧' => '&#8679;', '⌘' => '&#8984;', 'Fn' => 'Fn', 'Hyper' => 'Hyper');
	private static $mod_to_ent_win = array('⌃' => '&#8963;', '⌥' => 'Alt', '⇧' => '&#8679;', '⌘' => '&#8862;', 'Fn' => 'Fn', 'Hyper' => 'Hyper');
	private static $mod_to_title = array('⌃' => 'Control', '⌥' => 'Option', '⇧' => 'Shift', '⌘' => 'Command', 'Fn' => 'Function', 'Hyper' => 'Hyper (Control+Option+Shift+Command)');
	private static $mod_to_title_win = array('⌃' => 'Control', '⌥' => 'Alt', '⇧' => 'Shift', '⌘' => 'Win', 'Fn' => 'Function', 'Hyper' => 'Hyper');
	private static $upper_map = array(',' => '<', '.' => '>', '/' => '?', ';' => ':', "'" => '"', '[' => '{', ']' => '}', '\\' => '|', '-' => '_', '=' => '+');
	private static $option_keys = array('plus', 'text', 'mod_text', 'key_text', 'use_plus', 'use_modifier_symbols', 'use_key_symbols', 'symbols', 'modifier_symbols', 'key_symbols');

	/**
	 * Init: register shortcodes and frontend style.
	 */
	public static function init()
	{
		add_shortcode('kbd', array(__CLASS__, 'shortcode'));
		add_shortcode('btkbd', array(__CLASS__, 'shortcode'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'register_styles'));
	}

	/**
	 * Register frontend style (enqueued when shortcode is used).
	 */
	public static function register_styles()
	{
		wp_register_style(
			'btkbd-frontend',
			BTKBD_URL . 'assets/kbd-frontend.css',
			array(),
			BTKBD_VERSION
		);
	}

	/**
	 * Enqueue frontend style (default + optional custom CSS).
	 * Custom CSS is enqueued as a separate inline-only handle so it prints even when
	 * the shortcode runs after wp_head (it then outputs in footer and overrides default).
	 */
	public static function enqueue_frontend_style()
	{
		wp_enqueue_style('btkbd-frontend');

		$saved = array();
		if (defined('BTKBD_OPTION_NAME')) {
			$raw = get_option(BTKBD_OPTION_NAME, array());
			$saved = is_array($raw) ? $raw : array();
		}
		$custom_css = isset($saved['custom_css']) ? trim((string) $saved['custom_css']) : '';
		if ($custom_css !== '') {
			wp_register_style('btkbd-frontend-custom', false, array('btkbd-frontend'), BTKBD_VERSION);
			wp_enqueue_style('btkbd-frontend-custom');
			wp_add_inline_style('btkbd-frontend-custom', $custom_css);
		}
	}

	/**
	 * Shortcode callback.
	 *
	 * @param array  $atts    Attributes.
	 * @param string $content Content.
	 * @param string $tag     Tag.
	 * @return string
	 */
	/** Default option keys when settings not available. */
	private static $shortcode_defaults = array(
		'use_plus' => false,
		'use_modifier_symbols' => true,
		'use_key_symbols' => true,
		'style' => 'mac',
	);

	public static function shortcode($atts, $content = null, $tag = '')
	{
		$atts = is_array($atts) ? $atts : array();
		$saved = array();
		if (defined('BTKBD_OPTION_NAME')) {
			$raw = get_option(BTKBD_OPTION_NAME, array());
			$saved = is_array($raw) ? $raw : array();
		}
		$opts = array_merge(self::$shortcode_defaults, $saved);

		$option_values = array('plus', 'text', 'mod_text', 'key_text');
		$parts = array();
		$use_mod_symbol = isset($opts['use_modifier_symbols']) ? (bool) $opts['use_modifier_symbols'] : self::$shortcode_defaults['use_modifier_symbols'];
		$use_key_symbol = isset($opts['use_key_symbols']) ? (bool) $opts['use_key_symbols'] : self::$shortcode_defaults['use_key_symbols'];
		$use_plus = isset($opts['use_plus']) ? (bool) $opts['use_plus'] : self::$shortcode_defaults['use_plus'];
		$style = isset($opts['style']) && $opts['style'] === 'windows' ? 'windows' : 'mac';

		foreach ($atts as $k => $v) {
			$v = trim((string) $v);
			$v_lower = strtolower($v);
			if (in_array($v_lower, $option_values, true)) {
				if ($v_lower === 'plus') {
					$use_plus = true;
				} elseif ($v_lower === 'text') {
					$use_mod_symbol = false;
					$use_key_symbol = false;
				} elseif ($v_lower === 'mod_text') {
					$use_mod_symbol = false;
				} elseif ($v_lower === 'key_text') {
					$use_key_symbol = false;
				}
				continue;
			}
			if (in_array(strtolower($k), self::$option_keys, true)) {
				continue;
			}
			$parts[] = $v;
		}

		if (self::parse_bool_attr($atts, array('symbols', 'use_modifier_symbols', 'use_key_symbols'), null) === false) {
			$use_mod_symbol = false;
			$use_key_symbol = false;
		}
		if (self::parse_bool_attr($atts, array('modifier_symbols'), null) === false) {
			$use_mod_symbol = false;
		}
		if (self::parse_bool_attr($atts, array('key_symbols'), null) === false) {
			$use_key_symbol = false;
		}
		if (self::parse_bool_attr($atts, array('plus', 'use_plus', 'use_plus_sign'), null) === true) {
			$use_plus = true;
		}

		$raw = !empty($parts) ? implode(' ', $parts) : '';
		if ($content !== null && trim((string) $content) !== '') {
			$raw = trim((string) $content);
		}
		$raw = trim($raw);
		if ($raw === '') {
			return '';
		}
		self::enqueue_frontend_style();
		return '<span class="btkbd">' . self::render($raw, $use_mod_symbol, $use_key_symbol, $use_plus, $style) . '</span>';
	}

	/**
	 * Parse boolean attribute.
	 *
	 * @param array     $atts    Attributes.
	 * @param array     $keys    Keys to check.
	 * @param bool|null $default Default.
	 * @return bool|null
	 */
	private static function parse_bool_attr($atts, $keys, $default = null)
	{
		foreach ($keys as $key) {
			if (!array_key_exists($key, $atts)) {
				continue;
			}
			$v = $atts[$key];
			if ($v === '' || $v === '1' || $v === 'true' || $v === 'yes') {
				return true;
			}
			if ($v === '0' || $v === 'false' || $v === 'no') {
				return false;
			}
		}
		return $default;
	}

	/**
	 * Render kbd markup.
	 *
	 * @param string $raw            Raw combo string.
	 * @param bool   $use_mod_symbol Use modifier symbols.
	 * @param bool   $use_key_symbol Use key symbols.
	 * @param bool   $use_plus       Use + separator.
	 * @param string $style          'mac' or 'windows' for modifier display.
	 * @return string
	 */
	public static function render($raw, $use_mod_symbol = true, $use_key_symbol = true, $use_plus = false, $style = 'mac')
	{
		$mod_to_ent = $style === 'windows' ? self::$mod_to_ent_win : self::$mod_to_ent;
		$mod_to_title = $style === 'windows' ? self::$mod_to_title_win : self::$mod_to_title;
		$combos = array_map('trim', explode('/', $raw));
		$output = array();
		foreach ($combos as $combo_str) {
			$combo_str = self::clean_combo($combo_str);
			$mods = array();
			$key = '';
			$len = mb_strlen($combo_str);
			for ($i = 0; $i < $len; $i++) {
				$char = mb_substr($combo_str, $i, 1);
				if ($char === ' ') {
					continue;
				}
				if (in_array($char, array('⌃', '⇧', '⌥', '⌘'), true)) {
					$mods[] = $char;
				} elseif (in_array($char, array('*', '^', '$', '@', '~', '%'), true)) {
					$mods[] = self::char_to_mod($char);
				} else {
					$key .= $char;
				}
			}
			$mods = self::sort_mods($mods);
			$key = trim($key);
			if (strlen($key) === 1) {
				if (empty($mods) && (preg_match('/[A-Z]/', $key) || self::is_upper_key($key))) {
					$mods[] = '⇧';
				}
				if (in_array('⇧', $mods, true)) {
					$key = self::lower_to_upper($key);
				}
				$key = strtoupper($key);
			} elseif (in_array('⇧', $mods, true)) {
				$key = self::lower_to_upper($key);
			}
			$key = str_replace('"', '&quot;', $key);
			if (empty($mods) && empty($key)) {
				continue;
			}
			$kbds = array();
			$titles = array();
			foreach ($mods as $mod) {
				$mod_class = $use_mod_symbol ? 'mod symbol' : 'mod';
				$mod_display = $use_mod_symbol ? (isset($mod_to_ent[$mod]) ? $mod_to_ent[$mod] : $mod) : (isset($mod_to_title[$mod]) ? $mod_to_title[$mod] : $mod);
				$kbds[] = '<kbd class="' . esc_attr($mod_class) . '">' . ($use_mod_symbol ? $mod_display : esc_html($mod_display)) . '</kbd>';
				$titles[] = isset($mod_to_title[$mod]) ? $mod_to_title[$mod] : $mod;
			}
			if ($key !== '') {
				list($key_display, $key_title) = self::name_to_ent($key, $use_key_symbol);
				$key_class = $use_key_symbol ? 'key symbol' : 'key';
				$kbds[] = '<kbd class="' . esc_attr($key_class) . '">' . esc_html($key_display) . '</kbd>';
				$titles[] = $key_title;
			}
			$join = $use_mod_symbol && !$use_plus ? '' : ($use_plus ? '<span class="keycombo combiner">+</span>' : '-');
			$kbd_html = $join === '' ? implode('', $kbds) : implode($join, $kbds);
			$span_class = 'keycombo ' . ($use_mod_symbol && !$use_plus ? 'combined' : 'separated');
			$title_attr = implode('-', $titles);
			$output[] = '<span class="' . esc_attr($span_class) . '" title="' . esc_attr($title_attr) . '">' . $kbd_html . '</span>';
		}
		return implode('<span class="keycombo separator">/</span>', $output);
	}

	/**
	 * Sample HTML for settings page preview (uses provided options or saved).
	 *
	 * @param array|null $options Optional. Options array (use_modifier_symbols, use_key_symbols, use_plus, style).
	 * @return string
	 */
	public static function render_sample_for_preview($options = null)
	{
		if ($options === null && function_exists('BTKBD_Settings::get_options')) {
			$options = BTKBD_Settings::get_options();
		}
		$defaults = array(
			'use_modifier_symbols' => true,
			'use_key_symbols' => true,
			'use_plus' => false,
			'style' => 'mac',
		);
		$options = is_array($options) ? wp_parse_args($options, $defaults) : $defaults;
		$style = isset($options['style']) && $options['style'] === 'windows' ? 'windows' : 'mac';
		$html = self::render(
			'cmd shift s',
			!empty($options['use_modifier_symbols']),
			!empty($options['use_key_symbols']),
			!empty($options['use_plus']),
			$style
		);
		return '<span class="btkbd">' . $html . '</span>';
	}

	/**
	 * Clean combo string.
	 *
	 * @param string $s Input.
	 * @return string
	 */
	private static function clean_combo($s)
	{
		$s = preg_replace('/(?<=\S)-(?=\S)/', ' ', $s);
		$s = preg_replace('/\b(comm(and)?|cmd|clover|win|super)\b/i', '@', $s);
		$s = preg_replace('/\b(cont(rol)?|ctr?l)\b/i', '^', $s);
		$s = preg_replace('/\b(opt(ion)?|alt)\b/i', '~', $s);
		$s = preg_replace('/\bshift\b/i', '$', $s);
		$s = preg_replace('/\b(func(tion)?|fn)\b/i', '*', $s);
		$s = preg_replace('/\bhyper\b/i', '%', $s);
		return trim($s);
	}

	/**
	 * Char to mod symbol.
	 *
	 * @param string $c Char.
	 * @return string
	 */
	private static function char_to_mod($c)
	{
		$map = array('^' => '⌃', '~' => '⌥', '$' => '⇧', '@' => '⌘', '*' => 'Fn', '%' => 'Hyper');
		return isset($map[$c]) ? $map[$c] : $c;
	}

	/**
	 * Sort modifiers.
	 *
	 * @param array $mods Mods.
	 * @return array
	 */
	private static function sort_mods(array $mods)
	{
		$mods = array_unique($mods);
		usort($mods, function ($a, $b) {
			$ia = array_search($a, self::$mod_order, true);
			$ib = array_search($b, self::$mod_order, true);
			$ia = $ia === false ? 999 : $ia;
			$ib = $ib === false ? 999 : $ib;
			return $ia - $ib;
		});
		return $mods;
	}

	/**
	 * Is upper key.
	 *
	 * @param string $key Key.
	 * @return bool
	 */
	private static function is_upper_key($key)
	{
		return in_array($key, array('<', '>', '?', ':', '"', '{', '}', '|', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+'), true);
	}

	/**
	 * Lower to upper.
	 *
	 * @param string $key Key.
	 * @return string
	 */
	private static function lower_to_upper($key)
	{
		return isset(self::$upper_map[$key]) ? self::$upper_map[$key] : $key;
	}

	/**
	 * Name to entity/display.
	 *
	 * @param string $key        Key name.
	 * @param bool   $use_symbol Use symbol.
	 * @return array
	 */
	private static function name_to_ent($key, $use_symbol)
	{
		$key = trim($key);
		$lower = strtolower($key);
		$map = array(
			'f1' => array('F1', 'F1', 'F1 Key'),
			'f2' => array('F2', 'F2', 'F2 Key'),
			'f3' => array('F3', 'F3', 'F3 Key'),
			'f4' => array('F4', 'F4', 'F4 Key'),
			'f5' => array('F5', 'F5', 'F5 Key'),
			'f6' => array('F6', 'F6', 'F6 Key'),
			'f7' => array('F7', 'F7', 'F7 Key'),
			'f8' => array('F8', 'F8', 'F8 Key'),
			'f9' => array('F9', 'F9', 'F9 Key'),
			'f10' => array('F10', 'F10', 'F10 Key'),
			'f11' => array('F11', 'F11', 'F11 Key'),
			'f12' => array('F12', 'F12', 'F12 Key'),
			'tab' => array('', '&#8677;', 'Tab Key'),
			'caps' => array('Caps Lock', '&#8682;', 'Caps Lock Key'),
			'capslock' => array('Caps Lock', '&#8682;', 'Caps Lock Key'),
			'return' => array('Return', '&#9166;', 'Return Key'),
			'enter' => array('Enter', '&#8996;', 'Enter (Fn Return) Key'),
			'delete' => array('Del', '&#9003;', 'Delete'),
			'del' => array('Del', '&#9003;', 'Delete'),
			'backspace' => array('Del', '&#9003;', 'Delete'),
			'esc' => array('Esc', '&#9099;', 'Escape Key'),
			'escape' => array('Esc', '&#9099;', 'Escape Key'),
			'right' => array('Right Arrow', '&#8594;', 'Right Arrow Key'),
			'rt' => array('Right Arrow', '&#8594;', 'Right Arrow Key'),
			'left' => array('Left Arrow', '&#8592;', 'Left Arrow Key'),
			'lt' => array('Left Arrow', '&#8592;', 'Left Arrow Key'),
			'up' => array('Up Arrow', '&#8593;', 'Up Arrow Key'),
			'down' => array('Down Arrow', '&#8595;', 'Down Arrow Key'),
			'dn' => array('Down Arrow', '&#8595;', 'Down Arrow Key'),
			'pgup' => array('PgUp', '&#8670;', 'Page Up Key'),
			'pageup' => array('PgUp', '&#8670;', 'Page Up Key'),
			'pgdn' => array('PgDn', '&#8671;', 'Page Down Key'),
			'pagedown' => array('PgDn', '&#8671;', 'Page Down Key'),
			'home' => array('Home', '&#8598;', 'Home Key'),
			'end' => array('End', '&#8600;', 'End Key'),
			'space' => array('Space', 'Space', 'Space'),
		);
		if (preg_match('/^f(\d{1,2})$/', $lower, $m)) {
			$n = $m[1];
			$v = array("F$n", "F$n", "F$n Key");
			return $use_symbol ? array($v[1], $v[2]) : array($v[0], $v[2]);
		}
		if (isset($map[$lower])) {
			$v = $map[$lower];
			return $use_symbol ? array($v[1], $v[2]) : array($v[0], $v[2]);
		}
		$title = ucfirst($lower);
		return array($key, $title);
	}
}
