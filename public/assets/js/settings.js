toggleModeCheckBox.checked = localStorage.mode === "dark";

toggleModeCheckBox.oninput = toggleDarkMode;

function toggleDarkMode(event) {
  localStorage.mode = event.target.checked ? "dark" : "light";

  document.body.className = localStorage.mode;

}
