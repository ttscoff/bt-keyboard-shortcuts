<!--README-->
# BT Keyboard Shortcuts

Apple-style keyboard shortcut markup for WordPress. Use the `[kbd]` shortcode to render shortcuts like ⌘⇧P or Command-Shift-P in posts and pages.

**Requires:** WordPress 5.8+, PHP 7.4+

## Features

- **Shortcode `[kbd]`** for ⌘⇧⌥⌃-style output anywhere shortcodes are supported
- **Symbols or text**: Mac/Windows modifier symbols (⌘, ⇧, ⌥, ⌃) or text labels (Command, Shift, Option, Control)
- **Editor integration**: Insert keyboard shortcut dialog in the classic and block editors
- **Settings page**: Toggle + separator, modifier symbols, key symbols, and Mac vs Windows naming
- **Custom CSS**: Override styling for `.btkbd` keyboard keys with live preview

## Installation

1. Upload the `bt-keyboard-shortcuts` folder to `/wp-content/plugins/`.
2. Activate the plugin via **Plugins** in WordPress.
3. Go to **Settings** (under the plugin on the Plugins page) to configure display and CSS.

![Plugins page: BT Keyboard Shortcuts with Settings link](images/kbd-settings-link@2x.jpg "BT Keyboard Shortcuts plugin with Settings link highlighted")

## Inserting shortcuts in the editor

In the block or classic editor, use the formatting dropdown and choose **⌘ Insert keyboard shortcut** to open the shortcut dialog.

![Editor dropdown with Insert keyboard shortcut option](images/kbd-insert@2x.jpg "Editor formatting menu with Insert keyboard shortcut option")

In the **Keyboard shortcut** dialog, check modifier keys (Win/Alt/Shift/Ctrl/Fn), enter the main key, and use the generated shortcode. Click **Insert** to add it to the content.

![Keyboard shortcut dialog with modifiers and generated shortcode](images/kbd-shortcut-editor@2x.jpg "Keyboard shortcut dialog with modifiers, key field, and generated shortcode")

## Settings

Under **Settings → Keyboard Shortcuts** (or via the plugin’s **Settings** link), you can:

- **Display**: Show + separator (e.g. ⌘+⌥+S), use modifier symbols (⌘⇧⌥⌃) vs text, use symbol entities for keys (Tab, Return, etc.), and choose **Mac** or **Windows** symbols and names.
- **Custom CSS**: Override default styling for `.btkbd` keyboard keys. Changes update the preview below.

![Keyboard Shortcuts settings: Display options and Custom CSS with preview](images/kbd-settings-800@2x.jpg "Settings page with Display options and Custom CSS with live preview")

## Shortcode syntax

| Syntax | Example | Output |
|--------|---------|--------|
| Modifiers + key | `[kbd cmd shift p]` | ⌘⇧P (symbols, combined) |
| With + separator | `[kbd cmd shift p plus]` | ⌘+⇧+P |
| Text labels | `[kbd cmd shift p text]` | Command-Shift-P |
| Modifiers text, key symbol | `[kbd cmd p mod_text]` | Command-Shift-P |
| Arrow keys | `[kbd right key_text]` | → Right Arrow |

### Supported modifiers

`cmd`/`command`, `ctrl`/`control`, `opt`/`alt`, `shift`, `fn`, `hyper`

You can also use symbol shorthand in the shortcode: `$` (Shift), `@` (Command/Win), `~` (Option), `^` (CTRL). For example, `[kbd @$p]` renders as ⇧⌘P.

Modifier keys are automatically rearranged to match the order recommended by Apple in their [Human Interface Guidelines](https://developer.apple.com/design/human-interface-guidelines/keyboard-shortcuts) (e.g. ⌃ ⌥ ⇧ ⌘ before the key), regardless of the order you type them.

### Key names

`tab`, `return`, `enter`, `delete`, `esc`, `right`, `left`, `up`, `down`, `pgup`, `pgdn`, `home`, `end`, `space`, `caps`, `f1`–`f12`

## Frontend output

On the frontend, the shortcode renders as styled keycaps (e.g. ⇧ ⌘ L and ⌥ ⌘ V), using your display and CSS settings.

![Rendered keyboard shortcuts: Shift-Command-L and Option-Command-V](images/kbd-post@2x.jpg "Rendered shortcuts: Press ⇧⌘L and then ⌥⌘V")

## License

GPLv2 or later.
<!--END README-->