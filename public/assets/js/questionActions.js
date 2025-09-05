(() => {
  const voteUp = document.getElementById("vup");
  const voteDown = document.getElementById("vdown");
  const totalVotes = document.getElementById("totalVotes");
  const questionId = location.pathname.split("/")[2];
  const answer = document.querySelector('textarea')
  const answerBtn = document.querySelector('textarea + button')
  let state = 0;

  voteUp.onclick = () => {
    if (state == 0) {
      vote("UP", (response) => {
        let type = response.currentVote;
        if (type == "UP") {
          voteUp.querySelector("i").className = "ph ph-arrow-fat-up-fill";
          voteDown.querySelector("i").className = "ph ph-arrow-fat-down";
        } else {
          voteUp.querySelector("i").className = "ph ph-arrow-fat-up";
        }
        totalVotes.innerHTML = response.totalVotes;
      });
    }
  };

  voteDown.onclick = () => {
    if (state == 0) {
      vote("DOWN", (response) => {
        let type = response.currentVote;

        if (type == "DOWN") {
          voteDown.querySelector("i").className = "ph ph-arrow-fat-down-fill";
          voteUp.querySelector("i").className = "ph ph-arrow-fat-up";
        } else {
          voteDown.querySelector("i").className = "ph ph-arrow-fat-down";
        }

        totalVotes.innerHTML = response.totalVotes;
      });
    }
  };

  async function vote(type, onOk = () => {}) {
    const body = {
      vote: type.toUpperCase(),
    };
    state = 1;
    let response = await fetch(`/questions/${questionId}/vote`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    });

    if (response.ok) {
      response = await response.json();
      onOk(response);
    }

    state = 0;
  }

  // Create Answer

  answerBtn.onclick = async  () => {
    if (answer.value.trim()){

        let response = await fetch(`/questions/${questionId}/answer` , {
            method : "POST",
            body: JSON.stringify({answer: answer.value.trim()})
        })

        if (response.ok){
            location.reload()
        }



    }
  }



}
)()