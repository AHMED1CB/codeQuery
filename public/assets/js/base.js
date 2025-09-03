(() => {
  const mode = localStorage.mode ?? "light";
  document.body.classList.toggle(mode);
})();

function showModalMessage(title, message, icon = "error") {
  Swal.fire({
    icon: icon,
    text: message,
    title: title,
    showConfirmButton: false,
    customClass: {
      popup: "modal",
    },
  });
}
