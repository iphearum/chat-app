var json = $.getJSON("datas/users.json",
  function(json) {
    var name = document.getElementById('nameSession').value;
    var pas = document.getElementById('pasSession').value;
    for (var i = 0; i < json.user.length; i++) {
      if (json.user[i].name === name && json.user[i].password === pas) {
        console.log(json.user[i].status="offline");

        break;
      }
    }
  }
);


// console.log(json);

function reload() {
    $('#reload').load(location.href + ' #time');
    $('#time').load(location.href + ' #load');
}
// if user login the data will be online
function setOnline(id, newUsername) {
    for (var i = 0; i < jsonObj.length; i++) {
        if (jsonObj[i].Id === id) {
            jsonObj[i].Username = newUsername;
            return;
        }
    }
}

// set time to offline when the time go throw 5sec
function setOffine(id, newUsername) {
    for (var i = 0; i < jsonObj.length; i++) {
        if (jsonObj[i].Id === id) {
            jsonObj[i].Username = newUsername;
            return;
        }
    }
}
setInterval("reload();", 500);