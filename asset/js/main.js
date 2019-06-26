var json = $.getJSON("datas/users.json",
  function(json) {
    var name = document.getElementById('nameSession').value;
    var pas = document.getElementById('pasSession').value;
    // console.log(json);
    var offline = {
      "name":name,
      "password":pas,
      "status":"offline"
    };
    function setOffline(json, offline){
      
    }
    function updateJSON(json, offline) {
      return json.map(function(item) {
      return (item.name === offline.name && item.password === offline.pas) ? offline : item;
      });
    }
    json = updateJSON(json, offline);
    console.log(name+pas);
  },
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