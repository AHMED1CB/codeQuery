const contents = document.getElementById("question_desc");

marked.setOptions({
  highlight: function (code, lang) {
    if (hljs.getLanguage(lang)) {
      return hljs.highlight(code, { language: lang }).value;
    }
    return hljs.highlightAuto(code).value;
  },
});

function render() {
  contents.innerHTML = " " + marked.parse(contents.textContent.trim() || "");
  contents
    .querySelectorAll("pre code")
    .forEach((el) => hljs.highlightElement(el) );

}

render()
