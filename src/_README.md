# BT Keyboard Shortcuts

Apple-style keyboard shortcut markup for WordPress. Use the `[kbd]` shortcode to render shortcuts like &#x2318;&#x21E7;P or Command-Shift-P in posts and pages.
<!--README-->**Requires:** WordPress 5.8+, PHP 7.4+

## Features

- **Shortcode `[kbd]`** for &#x2318;&#x21E7;&#x2325;&#x2303;-style output anywhere shortcodes are supported
- **Symbols or text**: Mac/Windows modifier symbols (&#x2318;, &#x21E7;, &#x2325;, &#x2303;) or text labels (Command, Shift, Option, Control)
- **Automatically sort** modifiers in the order specified by Apple's guidelines
- **Editor integration**: Insert keyboard shortcut dialog in the classic and block editors
- **Settings page**: Toggle + separator, modifier symbols, key symbols, and Mac vs Windows naming
- **Custom CSS**: Override styling for `.btkbd` keyboard keys with live preview

## Installation

1. Download the [latest release](https://github.com/ttscoff/bt-keyboard-shortcuts/releases/latest/download/bt-keyboard-shortcuts.zip) and unzip.
2. Upload the `bt-keyboard-shortcuts` folder to `/wp-content/plugins/`.
3. Activate the plugin via **Plugins** in WordPress.
4. Go to **Settings** (under the plugin on the Plugins page) to configure display and CSS.

<!--JEKYLL-->{% img aligncenter /uploads/2026/02/kbd-settings-link.jpg 920 93 "Plugins page: BT Keyboard Shortcuts with Settings link" "Plugins page: BT Keyboard Shortcuts with Settings link" %}<!--END JEKYLL--><!--WP-->
![Plugins page: BT Keyboard Shortcuts with Settings link](images/kbd-settings-link@2x.jpg "BT Keyboard Shortcuts plugin with Settings link highlighted")<!--END WP-->

## Inserting shortcuts in the editor

In the block or classic editor, use the formatting dropdown and choose **Insert keyboard shortcut** to open the shortcut dialog.

<!--JEKYLL-->{% img aligncenter /uploads/2026/02/kbd-insert.jpg 604 303 "Editor dropdown with Insert keyboard shortcut option" "Editor dropdown with Insert keyboard shortcut option" %}<!--END JEKYLL--><!--WP-->
![Editor dropdown with Insert keyboard shortcut option](images/kbd-insert@2x.jpg "Editor formatting menu with Insert keyboard shortcut option")<!--END WP-->

In the **Keyboard shortcut** dialog, check modifier keys (Win/Alt/Shift/Ctrl/Fn), enter the main key, and use the generated shortcode. Click **Insert** to add it to the content.

<!--JEKYLL-->{% img aligncenter /uploads/2026/02/kbd-shortcut-editor.jpg 409 407 "Keyboard shortcut dialog with modifiers and generated shortcode" "Keyboard shortcut dialog with modifiers and generated shortcode" %}<!--END JEKYLL--><!--WP-->![Keyboard shortcut dialog with modifiers and generated shortcode](images/kbd-shortcut-editor@2x.jpg "Keyboard shortcut dialog with modifiers, key field, and generated shortcode")<!--END WP-->

## Settings

Under **Settings &rarr; Keyboard Shortcuts** (or via the plugin's **Settings** link), you can:

- **Display**: Show + separator (e.g. &#x2318;+&#x2325;+S), use modifier symbols (&#x2318;&#x21E7;&#x2325;&#x2303;) vs text, use symbol entities for keys (Tab, Return, etc.), and choose **Mac** or **Windows** symbols and names.
- **Custom CSS**: Override default styling for `.btkbd` keyboard keys. Changes update the preview below.

<!--JEKYLL-->{% img aligncenter /uploads/2026/02/kbd-settings-800.jpg 721 800 "Keyboard Shortcuts settings: Display options and Custom CSS with preview" "Keyboard Shortcuts settings: Display options and Custom CSS with preview" %}<!--END JEKYLL--><!--WP-->![Keyboard Shortcuts settings: Display options and Custom CSS with preview](images/kbd-settings-800@2x.jpg "Settings page with Display options and Custom CSS with live preview")<!--END WP-->

## Shortcode syntax

| Syntax           | Example                  | Output                                |
| ---------------- | ------------------------ | ------------------------------------- |
| Modifiers + key  | `[kbd cmd shift p]`      | &#x2318;&#x21E7;P (symbols, combined) |
| Text labels      | `[kbd cmd shift p text]` | Shift-Command-P                       |
| Shortcut symbols | `[kbd @$P]`              | Shift-Command-P                       |
| Arrow keys       | `[kbd right]`            | &rarr; Right Arrow                    |

### Supported modifiers

`cmd`/`command`, `ctrl`/`control`, `opt`/`alt`, `shift`, `fn`, `hyper`

You can also use symbol shorthand in the shortcode: `$` (Shift), `@` (Command/Win), `~` (Option), `^` (CTRL). For example, `[kbd @$p]` renders as &#x21E7;&#x2318;P.

Modifier keys are automatically rearranged to match the order recommended by Apple in their [Human Interface Guidelines](https://developer.apple.com/design/human-interface-guidelines/keyboard-shortcuts) (e.g. &#x2303; &#x2325; &#x21E7; &#x2318; before the key), regardless of the order you type them.

### Key names

`tab`, `return`, `enter`, `delete`, `esc`, `right`, `left`, `up`, `down`, `pgup`, `pgdn`, `home`, `end`, `space`, `caps`, `f1`--`f12`

## Frontend output

On the frontend, the shortcode renders as styled keycaps (e.g. &#x21E7; &#x2318; L and &#x2325; &#x2318; V), using your display and CSS settings.

<!--JEKYLL-->{% img aligncenter /uploads/2026/02/kbd-post.jpg 442 80 "Rendered keyboard shortcuts: Shift-Command-L and Option-Command-V" %}<!--END JEKYLL--><!--WP-->![Rendered keyboard shortcuts: Shift-Command-L and Option-Command-V](images/kbd-post@2x.jpg "Rendered shortcuts: Press &#x21E7;&#x2318;L and then &#x2325;&#x2318;V")<!--END WP-->

## License

GPLv2 or later.<!--END README-->
