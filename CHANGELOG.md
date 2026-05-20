### 1.0.4

2026-05-20 15:05

#### CHANGED

- Tested up to WordPress 7.1 in plugin readme
- Readme.txt example for symbol shorthand now shows correct modifier order (P for `[btkbd @$p]`)

### 1.0.3

2026-03-24 09:48

### 1.0.2

2026-03-23 08:41

### 1.0.1

2026-03-23 06:52

#### CHANGED

- Replace the generic [kbd] shortcode with prefixed [btkbd] only.
- Enqueue settings-page preview CSS and JS with WordPress wp_register/wp_enqueue/wp_add_inline APIs instead of printing raw inline tags.
- Removed the plugin Custom CSS textarea and live preview from Keyboard Shortcuts settings.
- The plugin now only uses built-in display options in settings and no longer stores arbitrary CSS from users.
- Load plugin translations from languages/ via load_plugin_textdomain so Settings and admin UI follow the site language in BT Keyboard Shortcuts and BT Downloads
- Add language files for BT Keyboard Shortcuts: .pot template plus en_US, de_DE, es_ES, fr_FR, it_IT (.po and compiled .mo) for settings page and Insert keyboard shortcut dialog
- Add language files for BT Downloads: .pot template plus en_US, de_DE, es_ES, fr_FR, it_IT (.po and compiled .mo) for Downloads CPT, edit screen, card template page, and Insert download UI
- PHP unit tests for [kbd]/[btkbd] shortcode (registration, modifier symbols, text/plus options, Windows style, key names, multiple combos, mod_text, empty/symbol shorthand).
- Vitest-based JS test setup with placeholder test and jsdom environment.
- Composer dev deps (PHPUnit, WP PHPUnit, yoast/phpunit-polyfills, PHPCS, WPCS) and scripts: test:php, lint:phpcs, test:all.
- Npm scripts test:js, test:js:watch, test:all; devDependencies jsdom and vitest.
- Bin/install-wp-tests.sh for WordPress PHPUnit test environment (DB, WP core, test lib).
- PHPUnit config (phpunit.xml.dist), bootstrap (tests/bootstrap.php), optional wp-tests-env.php for WP_CORE_DIR/WP_TESTS_DIR.
- Block editor format/toolbar and block JS strings (e.g. "Insert keyboard shortcut", "Insert download", "Select a download...") now use wp_set_script_translations so they are translated when the site language is not English
- Update the shortcode picker UI and generated snippets to use [btkbd] consistently in Classic and Block editor flows.
- Added an in-admin notice that directs styling to WordPress core Additional CSS targeting `.btkbd` classes.
- Encode default CSS for admin-side JavaScript with wp_json_encode to prevent unsafe script embedding sequences.
- Escape saved custom CSS at output before passing it to wp_add_inline_style for frontend rendering.
- Frontend output no longer injects user-saved inline CSS, removing arbitrary CSS insertion behavior.

### 1.0.0

2026-03-19 10:24

#### CHANGED

- Replace the generic [kbd] shortcode with prefixed [btkbd] only.
- Enqueue settings-page preview CSS and JS with WordPress wp_register/wp_enqueue/wp_add_inline APIs instead of printing raw inline tags.
- Load plugin translations from languages/ via load_plugin_textdomain so Settings and admin UI follow the site language in BT Keyboard Shortcuts and BT Downloads
- Add language files for BT Keyboard Shortcuts: .pot template plus en_US, de_DE, es_ES, fr_FR, it_IT (.po and compiled .mo) for settings page and Insert keyboard shortcut dialog
- Add language files for BT Downloads: .pot template plus en_US, de_DE, es_ES, fr_FR, it_IT (.po and compiled .mo) for Downloads CPT, edit screen, card template page, and Insert download UI
- PHP unit tests for [kbd]/[btkbd] shortcode (registration, modifier symbols, text/plus options, Windows style, key names, multiple combos, mod_text, empty/symbol shorthand).
- Vitest-based JS test setup with placeholder test and jsdom environment.
- Composer dev deps (PHPUnit, WP PHPUnit, yoast/phpunit-polyfills, PHPCS, WPCS) and scripts: test:php, lint:phpcs, test:all.
- Npm scripts test:js, test:js:watch, test:all; devDependencies jsdom and vitest.
- Bin/install-wp-tests.sh for WordPress PHPUnit test environment (DB, WP core, test lib).
- PHPUnit config (phpunit.xml.dist), bootstrap (tests/bootstrap.php), optional wp-tests-env.php for WP_CORE_DIR/WP_TESTS_DIR.
- Block editor format/toolbar and block JS strings (e.g. "Insert keyboard shortcut", "Insert download", "Select a download...") now use wp_set_script_translations so they are translated when the site language is not English
- Update the shortcode picker UI and generated snippets to use [btkbd] consistently in Classic and Block editor flows.
- Encode default CSS for admin-side JavaScript with wp_json_encode to prevent unsafe script embedding sequences.
- Escape saved custom CSS at output before passing it to wp_add_inline_style for frontend rendering.
- Remove plugin-managed arbitrary Custom CSS textarea and inline CSS injection; direct users to WordPress core Additional CSS for styling `.btkbd`.
- **New**: `[kbd]` shortcode for rendering Apple-style keyboard shortcuts (&#x2318;, &#x21E7;, &#x2325;, &#x2303;) or text labels (Command, Shift, Option, Control).
- **New**: Editor integration with an "Insert keyboard shortcut" dialog in the classic and block editors.
- **New**: Settings page to configure + separators, symbol vs text output, key symbols, and Mac vs Windows naming.
- **New**: Support for modifier aliases (`cmd`, `ctrl`, `opt`, `shift`, `fn`, `hyper`) and symbol shorthand (`$` shift, `@` command/Win, `~` option, `^` control).
- **New**: Automatic normalization of modifier order to match Apple's Human Interface Guidelines.
- **Removed**: Plugin-managed Custom CSS textarea/live preview to comply with WordPress.org policy; use core Additional CSS instead.

## 1.0.0 --- Initial release

- **New**: `[kbd]` shortcode for rendering Apple-style keyboard shortcuts (&#x2318;, &#x21E7;, &#x2325;, &#x2303;) or text labels (Command, Shift, Option, Control).
- **New**: Editor integration with an "Insert keyboard shortcut" dialog in the classic and block editors.
- **New**: Settings page to configure + separators, symbol vs text output, key symbols, and Mac vs Windows naming.
- **New**: Support for modifier aliases (`cmd`, `ctrl`, `opt`, `shift`, `fn`, `hyper`) and symbol shorthand (`$` shift, `@` command/Win, `~` option, `^` control).
- **New**: Automatic normalization of modifier order to match Apple's Human Interface Guidelines.
- **Removed**: Plugin-managed Custom CSS textarea/live preview to comply with WordPress.org policy; use core Additional CSS instead.
