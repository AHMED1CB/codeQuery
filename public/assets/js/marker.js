const textarea = document.getElementById("desc");
const preview = document.getElementById("preview");

marked.setOptions({
  highlight: function (code, lang) {
    if (hljs.getLanguage(lang)) {
      return hljs.highlight(code, { language: lang }).value;
    }
    return hljs.highlightAuto(code).value;
  },
});

function render() {
  preview.innerHTML = " " + marked.parse(textarea.value || "");
  preview
    .querySelectorAll("pre code")
    .forEach((el) => hljs.highlightElement(el));

}

textarea.addEventListener("input", render);

