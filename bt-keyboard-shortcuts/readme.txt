=== BT Keyboard Shortcuts ===
Contributors: bterp
Tags: keyboard, shortcut, kbd, markup
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Apple-style keyboard shortcut markup. Shortcode [btkbd] for ⌘⇧P-style output.

== Description ==

BT Keyboard Shortcuts renders keyboard shortcuts in Apple-style symbols (⌘, ⇧, ⌥, ⌃) or as text (Command, Shift, Option, Control). Use the `[btkbd]` shortcode anywhere shortcodes are supported.

= Using shortcuts in a post =
In the block or classic editor, type the shortcode directly (e.g. `[btkbd cmd shift p]`), or use the formatting dropdown and choose **⌘ Insert keyboard shortcut** to open a dialog where you pick modifiers and key and insert the generated shortcode.

= Settings page =
The plugin has a **Settings** page: click **Settings** next to the plugin on the **Plugins** screen, or go to **Settings → Keyboard Shortcuts**. There you can toggle the + separator, modifier symbols vs text, key symbols, Mac vs Windows naming, and style presets (Default, Light, Dark, Modern).

= Styling =
To customize `.btkbd` styles, use WordPress core **Additional CSS** in Appearance → Customize (or the Site Editor equivalent for block themes).

= Windows compatibility =
On the Settings page, use **Mac / Windows symbols and names** to choose **Windows (⊞ Win, Alt)** instead of **Mac (⌘ Command, ⌥ Option)** so shortcuts render with Windows-style symbols and labels for your audience.

= Syntax =
`[btkbd cmd shift p]` → ⇧⌘P (symbols, combined)
`[btkbd $@p]` → ⇧⌘P (symbol shorthand input)
`[btkbd Command-Shift-P]` → ⇧⌘P (hyphenated text input)
`[btkbd right]` → → Right Arrow

Display format (symbols/text and plus separators) is controlled by plugin settings.

= Supported modifiers =
cmd/command, ctrl/control, opt/alt, shift, fn, hyper

You can also use symbol shorthand in the shortcode: $ (shift), @ (command), ~ (option), ^ (control). For example, `[btkbd @$p]` renders as ⌘⇧P.

Modifier keys are automatically rearranged to match the order recommended by Apple in their Human Interface Guidelines (e.g. Control, Option, Shift, Command before the key), regardless of the order you type them in the shortcode.

= Key names =
tab, return, enter, delete, esc, right, left, up, down, pgup, pgdn, home, end, space, caps, f1-f12

== Installation ==

1. Upload the `bt-keyboard-shortcuts` folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu.

== Changelog ==

= 1.0.0 =
* Initial release.
