( () => {

  toggleModeCheckBox.checked = localStorage.mode === "dark";

toggleModeCheckBox.oninput = toggleDarkMode;

function toggleDarkMode(event) {
  localStorage.mode = event.target.checked ? "dark" : "light";

  document.body.className = localStorage.mode;
}

// Update Profile

const avatarInput = document.getElementById("imgInput");
const fullNameInput = document.getElementById("fullname");
const usernameInput = document.getElementById("username");
const bioInput = document.getElementById("bio");
const profileAvatar = document.getElementById("profile_avatar");
const saveChanges = document.getElementById("saveChanges");

const data = new FormData();

avatarInput.onchange = (event) => {
  let file = event.target.files[0];
  if (file) {
    const reader = new FileReader();

    reader.onload = (event) => {
      profileAvatar.src = event.target.result;
    };

    reader.readAsDataURL(file);

    data.set('avatar' , file)
  }
};

fullNameInput.onchange = () => {
  data.set('full_name' ,  fullNameInput.value.trim())
};

usernameInput.onchange = () => {
  data.set('username' , usernameInput.value.trim())
};

bioInput.onchange = () => {
  data.set('bio',   bioInput.value.trim()) 
};

saveChanges.onclick = async () => {
  if (
    data.get('username') ||
    data.get('full_name') ||
    data.get('avatar') ||
    data.get('bio')
  ) {
    let response = await fetch("/users/profile/update", {
      method: "POST",
      body: data,
    
    });

    response = await response.text();
    if (response.error) {
      showModalMessage("Invalid Data", response.error, "error");
    } else {
      showModalMessage("Success", "User Updated Successfully", "success");
    }
  }
};

})()