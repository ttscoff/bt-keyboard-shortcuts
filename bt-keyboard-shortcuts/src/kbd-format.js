/**
 * Block editor format: Keyboard shortcut. Adds ⌘ button to format toolbar dropdown.
 * When clicked, opens the picker modal; on Insert, inserts [kbd]...[/kbd] at cursor.
 */
(function (richText, blockEditor, element, i18n) {
  "use strict";

  var registerFormatType = richText.registerFormatType;
  var insert = richText.insert;
  var RichTextToolbarButton = blockEditor.RichTextToolbarButton;
  var createElement = element.createElement;
  var __ = i18n.__;

  var pendingInsert = null;

  /* Apple Command key symbol (⌘) as SVG for toolbar icon */
  var cmdIcon = createElement(
    "svg",
    {
      xmlns: "http://www.w3.org/2000/svg",
      width: 24,
      height: 24,
      viewBox: "0 0 24 24"
    },
    createElement(
      "text",
      {
        x: 12,
        y: 17,
        textAnchor: "middle",
        fontSize: 18,
        fontWeight: 600,
        fill: "currentColor",
        style: { fontFamily: "system-ui, -apple-system, sans-serif" }
      },
      "\u2318"
    )
  );

  function KbdButton(props) {
    var value = props.value;
    var onChange = props.onChange;

    function handleClick() {
      pendingInsert = { value: value, onChange: onChange };
      if (typeof window.btkbdShortcutPickerOpen === "function") {
        window.btkbdShortcutPickerOpen(function (shortcode) {
          if (pendingInsert) {
            var next = insert(pendingInsert.value, shortcode);
            pendingInsert.onChange(next);
            pendingInsert = null;
          }
        });
      }
    }

    return createElement(RichTextToolbarButton, {
      icon: cmdIcon,
      title: __("Insert keyboard shortcut", "bt-keyboard-shortcuts"),
      onClick: handleClick,
      role: "menuitem"
    });
  }

  registerFormatType("btkbd/insert-kbd", {
    name: "btkbd/insert-kbd",
    title: __("Keyboard shortcut", "bt-keyboard-shortcuts"),
    tagName: "span",
    className: "btkbd-shortcut",
    edit: KbdButton
  });
})(
  window.wp.richText,
  window.wp.blockEditor,
  window.wp.element,
  window.wp.i18n
);
