const input = document.getElementById('tagsSearch');
const searchBtn = document.querySelector('#tagsSearch + i')


let searchQuery = "";


input.onchange = () => {
    searchQuery = input.value
}


searchBtn.onclick = () => {
    location.search = `?q=${searchQuery}`
}

