/**
 * TinyMCE plugin: "Insert keyboard shortcut" button (⌘ icon). Opens the picker modal.
 */
(function () {
	"use strict";

	function register() {
		if (typeof tinymce === "undefined") return;
		tinymce.PluginManager.add("btkbd_insert_kbd", function (editor) {
			editor.addButton("btkbd_insert_kbd", {
				title: "Insert keyboard shortcut",
				icon: "btkbd-kbd",
				onclick: function () {
					if (typeof window.btkbdShortcutPickerOpen === "function") {
						window.btkbdShortcutPickerOpen(function (shortcode) {
							editor.insertContent(shortcode);
						});
					}
				}
			});
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", register);
	} else {
		register();
	}
})();
