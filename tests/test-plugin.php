<?php
/**
 * Tests for the BT Keyboard Shortcuts plugin.
 *
 * @package BT_Keyboard_Shortcuts
 */

class BT_Keyboard_Shortcuts_Plugin_Tests extends WP_UnitTestCase
{

	/**
	 * [kbd] shortcode is registered.
	 */
	public function test_kbd_shortcode_is_registered()
	{
		$this->assertTrue(shortcode_exists('kbd'));
		$this->assertTrue(shortcode_exists('btkbd'));
	}

	/**
	 * Shortcode renders modifier symbols by default (Mac style).
	 */
	public function test_shortcode_renders_modifier_symbols()
	{
		$output = do_shortcode('[kbd cmd shift p]');
		$this->assertStringContainsString('keycombo', $output);
		$this->assertStringContainsString('&#8984;', $output); // Command
		$this->assertStringContainsString('&#8679;', $output); // Shift
		$this->assertStringContainsString('P', $output);
	}

	/**
	 * Shortcode with "text" option renders text labels.
	 */
	public function test_shortcode_text_option_renders_labels()
	{
		$output = do_shortcode('[kbd cmd shift p text]');
		$this->assertStringContainsString('Command', $output);
		$this->assertStringContainsString('Shift', $output);
		$this->assertStringContainsString('P', $output);
	}

	/**
	 * Shortcode with symbol shorthand ($ @ ~ ^) works.
	 */
	public function test_shortcode_symbol_shorthand()
	{
		$output = do_shortcode('[kbd @ $ p]');
		$this->assertStringContainsString('&#8984;', $output);
		$this->assertStringContainsString('&#8679;', $output);
	}

	/**
	 * Empty shortcode returns empty string.
	 */
	public function test_shortcode_empty_returns_empty()
	{
		$output = do_shortcode('[kbd]');
		$this->assertSame('', $output);
	}

	/**
	 * btkbd shortcode alias produces same output as kbd.
	 */
	public function test_btkbd_shortcode_alias()
	{
		$out_kbd = do_shortcode('[kbd cmd p]');
		$out_btkbd = do_shortcode('[btkbd cmd p]');
		$this->assertSame($out_kbd, $out_btkbd);
	}

	/**
	 * BTKBD_Kbd::render with plus option outputs + separator.
	 */
	public function test_render_plus_separator()
	{
		$output = BTKBD_Kbd::render('cmd shift p', true, true, true, 'mac');
		$this->assertStringContainsString('combiner', $output);
		$this->assertStringContainsString('+', $output);
	}

	/**
	 * BTKBD_Kbd::render with text style outputs Command-Shift-P.
	 */
	public function test_render_text_style()
	{
		$output = BTKBD_Kbd::render('cmd shift p', false, false, false, 'mac');
		$this->assertStringContainsString('Command', $output);
		$this->assertStringContainsString('Shift', $output);
		$this->assertStringContainsString('P', $output);
	}

	/**
	 * BTKBD_Kbd::render with Windows style outputs Win and Alt.
	 */
	public function test_render_windows_style()
	{
		$output = BTKBD_Kbd::render('cmd opt s', false, false, false, 'windows');
		$this->assertStringContainsString('Win', $output);
		$this->assertStringContainsString('Alt', $output);
	}

	/**
	 * Shortcode with "plus" renders + between keys.
	 */
	public function test_shortcode_plus_option()
	{
		$output = do_shortcode('[kbd cmd shift p plus]');
		$this->assertStringContainsString('+', $output);
	}

	/**
	 * Key names (tab, return, esc, right) render with symbols or text.
	 */
	public function test_shortcode_key_names()
	{
		$output = do_shortcode('[kbd cmd tab]');
		$this->assertStringContainsString('btkbd', $output);
		$this->assertStringContainsString('keycombo', $output);

		$output_return = do_shortcode('[kbd return]');
		$this->assertStringContainsString('Return', $output_return);

		$output_esc = do_shortcode('[kbd esc]');
		$this->assertStringContainsString('Esc', $output_esc);

		$output_right = do_shortcode('[kbd right key_text]');
		$this->assertStringContainsString('Right Arrow', $output_right);
	}

	/**
	 * Multiple combos separated by / render.
	 */
	public function test_shortcode_multiple_combos()
	{
		$output = do_shortcode('[kbd cmd s / cmd v]');
		$this->assertStringContainsString('separator', $output);
		$this->assertStringContainsString('S', $output);
		$this->assertStringContainsString('V', $output);
	}

	/**
	 * mod_text option: modifiers as text, key as symbol.
	 */
	public function test_shortcode_mod_text()
	{
		$output = do_shortcode('[kbd cmd p mod_text]');
		$this->assertStringContainsString('Command', $output);
		$this->assertStringContainsString('P', $output);
	}

	/**
	 * Shortcode wraps output in btkbd span.
	 */
	public function test_shortcode_wraps_in_btkbd_span()
	{
		$output = do_shortcode('[kbd cmd p]');
		$this->assertStringContainsString('<span class="btkbd">', $output);
		$this->assertStringContainsString('</span>', $output);
	}

	/**
	 * render_sample_for_preview returns valid HTML.
	 */
	public function test_render_sample_for_preview()
	{
		$output = BTKBD_Kbd::render_sample_for_preview(null);
		$this->assertStringContainsString('btkbd', $output);
		$this->assertStringContainsString('keycombo', $output);
	}

	/**
	 * render_sample_for_preview with windows option uses Win/Alt.
	 */
	public function test_render_sample_for_preview_windows()
	{
		$output = BTKBD_Kbd::render_sample_for_preview(array('style' => 'windows'));
		$this->assertStringContainsString('btkbd', $output);
	}

	/**
	 * Shortcode enqueues frontend style when used.
	 */
	public function test_shortcode_enqueues_frontend_style()
	{
		do_shortcode('[kbd cmd p]');
		do_action('wp_enqueue_scripts');
		$this->assertTrue(wp_style_is('btkbd-frontend', 'enqueued'));
	}

	/**
	 * Ctrl/control modifier renders.
	 */
	public function test_ctrl_modifier()
	{
		$output = do_shortcode('[kbd ctrl c]');
		$this->assertStringContainsString('&#8963;', $output); // Control entity
		$this->assertStringContainsString('C', $output);
	}

	/**
	 * Opt/alt modifier renders (Mac symbol).
	 */
	public function test_opt_modifier()
	{
		$output = do_shortcode('[kbd opt v]');
		$this->assertStringContainsString('&#8997;', $output); // Option entity
		$this->assertStringContainsString('V', $output);
	}

	/**
	 * Fn modifier is accepted.
	 */
	public function test_fn_modifier()
	{
		$output = do_shortcode('[kbd fn f]');
		$this->assertStringContainsString('btkbd', $output);
		$this->assertStringContainsString('Fn', $output);
	}

	/**
	 * Single key (letter) renders.
	 */
	public function test_single_key()
	{
		$output = do_shortcode('[kbd p]');
		$this->assertStringContainsString('P', $output);
		$this->assertStringContainsString('keycombo', $output);
	}
}
