(() => {
    const form = document.forms[0];
const tagsInput = form.tagsInput;
const titleInput = form.titleInput;
const descInput = form.descInput;
const tagsContainer = document.getElementById("tagsContainer");
const addTagBtn = document.getElementById("addTag");
const createBtn = document.getElementById("publish");

let data = {
  tags: [],
  title: "",
  desc: "",
};

let tagsCount = 0;

function tag() {
  let tagValue = tagsInput.value.trim();
  tagValue = tagValue.split(' ')[0]
  return tagValue ?? false;
}

function title() {
  let titleValue = titleInput.value.trim();

  return titleValue.length > 5 ? titleValue : false;
}

function desc() {
  let descValue = descInput.value.trim();

  return descValue.length > 5 ? descValue : false;
}

addTagBtn.onclick = () => {
  if (tag()) {
    if (!data.tags.includes(tag())) {
      data.tags.push(tag());
    } else {
      showModalMessage("Tag Exists", "Tag Already Created");
      return;
    }

    if (tagsCount == 5) {
      showModalMessage("Invalid Tags Count", "Max Tags Count is 5");
      return;
    }

    const element = document.createElement("span");

    element.className =
      "px-3 cursor-pointer hover:text-white hover:bg-black duration-300 transition py-1 bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-sm rounded-full";

    element.innerText = tag();

    tagsContainer.append(element);

    tagsCount++;

    tagsInput.value = "";
  } else {
    showModalMessage(
      "Invalid Tag",
      "Invalid Tag Name Please Write a Valid Letters",
    );
  }
};

titleInput.oninput = () => {
  if (title()) {
    data.title = title();
  }
};

descInput.oninput = () => {
  if (desc()) {
    data.desc = desc();
  }
};

createBtn.onclick = () => {
  if (data.tags.length == 0) {
    showModalMessage(
      "Invalid Tags Count",
      "Question must contain atleat 1 tag"
    );
    return;
  }

  if (!title()) {
    showModalMessage(
      "Invalid Titlte",
      "Title Must be atleast 5 letters ",
    );
    return;
  }


  if (!desc()){
    showModalMessage(
      "Invalid Question Description",
      "Description Must be atleast 5 letters ",
    );
    return;
  }

  createQuestion()
  

};




async function createQuestion(){

    let response = await fetch('/ask' , {
        method: "POST",
        body: JSON.stringify(data),
         headers: {
        "Content-Type": "application/json"
    },
    })


    response = await response.json();

    if (response.error){
      showModalMessage("Error" , response.error)
    }else{
      showModalMessage("Success" , response.message , "success")

      setTimeout(() => {
          location.href = "/questions"
      } , 3000)

    }



}



form.onsubmit = () => false;
})()