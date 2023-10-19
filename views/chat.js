const form = document.getElementById("chat-form");
const input = document.getElementById("message");
const containerDiv = document.getElementById("messages");
let numberOfMsgs = 0;
const onlineDiv = document.querySelector(".online");
const awayDiv = document.querySelector(".away");
const offlineDiv = document.querySelector(".offline");

function logCookiesId(cookieKey) {
  const cookies = document.cookie.split(";");
  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].trim();
    if (cookie.startsWith(cookieKey + "=")) {
      const cookieValue = cookie.substring(cookieKey.length + 1);
      return cookieValue;
    }
  }
  return null;
}

function checkImageExists(imageUrl, callback) {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", imageUrl);

  xhr.onload = function () {
    // Check if the status code is in the 2xx range (successful response).
    const isImageExists = xhr.status >= 200 && xhr.status < 300;
    callback(isImageExists);
  };

  xhr.onerror = function () {
    // An error occurred during the request (e.g., 404 Not Found).
    callback(false);
  };

  xhr.send();
}

// looping Data from DataBase and creating HTML elements for every Data that i want to show
try {
  function addMessages(resMessages) {
    for (let i in resMessages) {
      const value = resMessages[i];
      const colors = 7;
      const colorIndex = value.user % colors;
      const cookieId = logCookiesId("id");
      const time = new Date(value.created_at);

      const currentDate = new Date(); // Get the current date and time
      const message = new Date(time); // Convert the message date string to a Date object

      const isToday =
        message.getDate() === currentDate.getDate() &&
        message.getMonth() === currentDate.getMonth() &&
        message.getFullYear() === currentDate.getFullYear();

      const isYesterday =
        message.getDate() === currentDate.getDate() - 1 &&
        message.getMonth() === currentDate.getMonth() &&
        message.getFullYear() === currentDate.getFullYear();

      let formattedDate1;
      if (isToday) {
        formattedDate1 = "Today";
      } else if (isYesterday) {
        formattedDate1 = "Yesterday";
      } else {
        formattedDate1 = message.toLocaleDateString("en-US");
      }

      const hours = String(message.getHours()).padStart(2, "0"); // Get hours and pad with leading zero if necessary
      const minutes = String(message.getMinutes()).padStart(2, "0"); // Get minutes and pad with leading zero if necessary

      const formattedTime = `${hours}:${minutes}`;

      const formattedDate = `${formattedDate1} ${formattedTime}`;

      const msgContainerDiv = document.createElement("div");
      const containerNameDate = document.createElement("div");
      const msgNameDiv = document.createElement("div");
      const msgDateDiv = document.createElement("div");
      const msgTextDiv = document.createElement("div");
      const imgDiv = document.createElement("div");
      const img = document.createElement("img");

      const msgName = document.createTextNode(value.name);
      const msgDate = document.createTextNode(formattedDate);
      const msgText = document.createTextNode(value.message);

      //your messages being dislayed on the right side
      if (cookieId == value.user) {
        msgContainerDiv.classList.add("msgContainer", "you");
        containerNameDate.classList.add("containerNameDate", "you");
      } else {
        msgContainerDiv.classList.add("msgContainer");
        containerNameDate.classList.add("containerNameDate");
      }

      msgNameDiv.classList.add("msg", "msgName", `color_${colorIndex}`);
      msgDateDiv.classList.add("msg", "msgDate");
      msgTextDiv.classList.add("msg", "msgText");
      img.classList.add("msgImg");

      containerDiv.appendChild(msgContainerDiv);
      containerNameDate.appendChild(msgNameDiv);
      containerNameDate.appendChild(msgDateDiv);
      msgContainerDiv.appendChild(containerNameDate);
      msgNameDiv.appendChild(msgName);
      msgDateDiv.appendChild(msgDate);

      //if url of the img have been sent, regex checks it

      if (
        /\.(jpg|jpeg|png|gif)$|^https:|^data:image\/jpeg/i.test(value.message)
      ) {
        checkImageExists(value.message, function (exists) {
          if (exists) {
            console.log("Image exists on the server.");
            img.src = value.message;
            img.style.width = "100%";
            img.style.borderRadius = "5px";
            imgDiv.appendChild(img);
            msgContainerDiv.appendChild(imgDiv);
          } else {
            console.log("Image does not exist on the server.");
            msgContainerDiv.appendChild(msgTextDiv);
            msgTextDiv.appendChild(msgText);
          }
        });
      } else {
        msgContainerDiv.appendChild(msgTextDiv);
        msgTextDiv.appendChild(msgText);
      }

      //always scroll down
      containerDiv.scrollTop =
        containerDiv.scrollHeight - containerDiv.clientHeight;
    }
  }
} catch (error) {
  console.log("An error occurred:", error.message);
}

//function msg
function getMessages() {
  const xhr2 = new XMLHttpRequest(); // create the HTTP request
  xhr2.open("GET", "index.php?action=chat&messages=count"); // open the request with parameter message
  xhr2.send(); // send request to the server
  xhr2.addEventListener("load", (e) => {
    const resMessages = JSON.parse(xhr2.responseText);
    const count = resMessages["messages"] - numberOfMsgs;
    //in this request i check if number of msg in DB equal to the number of current msgs been displayed
    if (resMessages["messages"] > numberOfMsgs) {
      const xhr3 = new XMLHttpRequest(); // create the HTTP request
      xhr3.open("GET", `index.php?action=chat&messages=new&count=${count}`); // open the request with parameter message
      xhr3.send(); // send request to the server
      xhr3.addEventListener("load", (e) => {
        /**@param {array} msg */
        const msg = JSON.parse(xhr3.responseText);
        msg.reverse();
        numberOfMsgs = resMessages["messages"];
        addMessages(msg);
      });
    }
  });
}
//calling the function that displaying all the msg once page first loaded
getMessages();
//calling function with the timer, every time its being called, its checks for the new msgs and updates
setInterval(getMessages, 1000);

//when you manually sending the msg
form.addEventListener("submit", (e) => {
  // listen to the submit button
  const inputValue = input.value;
  const cookieId = logCookiesId("id"); //checking ID of the logged User from the Cookie
  e.preventDefault(); // cancel all the default behavior(Prevent the form from submitting normally)

  //if input value is not empty, execute code below
  if (inputValue !== "") {
    // send data to the form
    const sentData = {
      user_id: cookieId,
      message: inputValue,
    };
    const toSendJson = JSON.stringify(sentData);

    // const formData = new FormData(e.target); // put the data from form to the variable
    const xhr = new XMLHttpRequest(); // create the HTTP request
    xhr.open("POST", "index.php?action=chat&messages=submit"); // open the request
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(toSendJson); // send request with data from the form
    xhr.addEventListener("load", (e) => {
      // when you send request, webpage is load
      let res = { error: true };

      try {
        res = JSON.parse(xhr.responseText); // when its load, parse XHR response
      } catch (e) {
        alert(e);
        console.log(e.message);
      }
      if (res["error"]) {
        const chat = document.getElementById("chat");
        const div = document.createElement("div");
        const divError = document.createTextNode("ERROR");
        input.classList.add("error");
        div.appendChild(divError);
        chat.appendChild(div);
      } else {
        input.classList.remove("error");
        input.value = "";
        getMessages();
      }
    });
  }
});

function whoIsOnline() {
  const cookieId = logCookiesId("id");

  const currentDate = new Date();
  const year = currentDate.getFullYear();
  const month = String(currentDate.getMonth() + 1).padStart(2, "0");
  const day = String(currentDate.getDate()).padStart(2, "0");
  const hours = String(currentDate.getHours()).padStart(2, "0");
  const minutes = String(currentDate.getMinutes()).padStart(2, "0");
  const seconds = String(currentDate.getSeconds()).padStart(2, "0");

  const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

  const online = {
    user_id: cookieId,
    time: formattedDateTime,
  };
  const toSendJson = JSON.stringify(online);
  console.log(toSendJson);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "index.php?action=chat&online");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(toSendJson);
  xhr.addEventListener("load", (e) => {
    const res = JSON.parse(xhr.responseText);

    function addStatusInHTML(name, status) {
      const statusDiv = document.getElementsByClassName(status)[0];

      if (document.getElementById(name)) {
        return;
      }
      const div = document.createElement("div");

      const circle = document.createElement("div");
      div.classList.add("setFlex");
      div.appendChild(circle);

      div.id = name;
      const text = document.createTextNode(name);
      div.appendChild(text);
      statusDiv.appendChild(div);

      if (status === "online") {
        circle.classList.add("green");
      } else if (status === "away") {
        circle.classList.add("yellow");
      } else {
        circle.classList.add("grey");
      }
    }

    function removeStatusInHTML(name, status) {
      const statusDiv = document.getElementsByClassName(status)[0];
      const div = document.getElementById(name);

      if (div && statusDiv.contains(div)) {
        statusDiv.removeChild(div);
      }
    }

    function addOrRemoveStatusInHTML(names, status) {
      names.forEach((name) => {
        switch (status) {
          case "online": {
            removeStatusInHTML(name, "away");
            removeStatusInHTML(name, "offline");
            break;
          }
          case "away": {
            removeStatusInHTML(name, "online");
            removeStatusInHTML(name, "offline");
            break;
          }
          case "offline": {
            removeStatusInHTML(name, "away");
            removeStatusInHTML(name, "online");
            break;
          }
        }
        addStatusInHTML(name, status);
      });
    }

    const parseAndDisplayUserStatus = (people) => {
      // add the online people
      const online = people
        .filter((status) => Object.keys(status).includes("online"))
        .map((user) => user.online);

      // add the away people
      const away = people
        .filter((status) => Object.keys(status).includes("away"))
        .map((user) => user.away);

      // add the offline people
      const offline = people
        .filter((status) => Object.keys(status).includes("offline"))
        .map((user) => user.offline);

      // insert online people in HTML
      addOrRemoveStatusInHTML(online, "online");
      addOrRemoveStatusInHTML(away, "away");
      addOrRemoveStatusInHTML(offline, "offline");
    };

    parseAndDisplayUserStatus(res);
  });
}

whoIsOnline();
setInterval(whoIsOnline, 1000);
