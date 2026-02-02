/**
 * Keyboard shortcut picker modal: modifiers + key -> generated shortcode (read-only). Insert calls callback.
 */
(function () {
  "use strict";

  var MOD_ORDER = ["fn", "ctrl", "alt", "shift", "cmd"];

  function toShortcodeContent(mods, key) {
    var parts = [];
    MOD_ORDER.forEach(function (m) {
      if (mods[m]) parts.push(m);
    });
    key = (key || "").trim();
    if (key) parts.push(key);
    return parts.join(" ");
  }

  function toShortcodeString(content) {
    if (!content || !content.trim()) return "[kbd]";
    return "[kbd " + content.trim() + "]";
  }

  var modal = null;
  var keyInput = null;
  var shortcodeInput = null;
  var checkboxes = {};
  var callback = null;

  function getContent() {
    var parts = [];
    MOD_ORDER.forEach(function (m) {
      var cb = checkboxes[m];
      if (cb && cb.checked) parts.push(m);
    });
    var key = keyInput ? keyInput.value.trim() : "";
    if (key) parts.push(key);
    return parts.join(" ");
  }

  function syncFormToShortcode() {
    var content = getContent();
    if (shortcodeInput) shortcodeInput.value = toShortcodeString(content);
  }

  function bindModal() {
    if (modal) return;
    modal = document.getElementById("btkbd-shortcut-picker-modal");
    if (!modal) return;
    keyInput = modal.querySelector("#btkbd-picker-key");
    shortcodeInput = modal.querySelector("#btkbd-picker-shortcode");
    MOD_ORDER.forEach(function (m) {
      var el = modal.querySelector('input[data-mod="' + m + '"]');
      if (el) checkboxes[m] = el;
    });

    MOD_ORDER.forEach(function (m) {
      if (checkboxes[m]) {
        checkboxes[m].addEventListener("change", syncFormToShortcode);
      }
    });
    if (keyInput) keyInput.addEventListener("input", syncFormToShortcode);

    modal
      .querySelector(".btkbd-picker-insert")
      .addEventListener("click", function () {
        var content = getContent();
        var shortcode = toShortcodeString(content);
        if (typeof callback === "function") callback(shortcode);
        closeModal();
      });
    modal
      .querySelector(".btkbd-picker-cancel")
      .addEventListener("click", closeModal);
    modal
      .querySelector(".btkbd-picker-close")
      .addEventListener("click", closeModal);
    modal
      .querySelector(".btkbd-picker-backdrop")
      .addEventListener("click", closeModal);
  }

  function openModal(onSelect) {
    callback = onSelect;
    bindModal();
    if (!modal) return;
    MOD_ORDER.forEach(function (m) {
      if (checkboxes[m]) checkboxes[m].checked = false;
    });
    if (keyInput) keyInput.value = "";
    if (shortcodeInput) shortcodeInput.value = "[kbd]";
    modal.style.display = "";
    modal.setAttribute("aria-hidden", "false");
    if (keyInput) keyInput.focus();
  }

  function closeModal() {
    if (modal) {
      modal.style.display = "none";
      modal.setAttribute("aria-hidden", "true");
    }
    callback = null;
  }

  document.addEventListener("keydown", function (e) {
    if (modal && modal.style.display !== "none" && e.key === "Escape") {
      closeModal();
    }
  });

  window.btkbdShortcutPickerOpen = openModal;
})();
