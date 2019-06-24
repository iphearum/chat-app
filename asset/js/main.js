var start;
// Dynamic function
function Dynamic() {
    var d = new Date();
    var t = d.toLocaleTimeString();
    // $("#demo").html(t);
}
// Static function
function Static() {
    var d = new Date();
    var t = d.toLocaleTimeString();
    $('#count').html(t);
}

// function to start Dynamic
$(document).ready(function () {
    start = setInterval("Dynamic()", 1000);
});
// function to stop Dynamic
function stop() {
    clearInterval(start);
}
function checkCookie() {
    var username = getCookie("username");
    if (username != "") {
     alert("Welcome again " + username);
    } else {
      username = prompt("Please enter your name:", "");
      if (username != "" && username != null) {
        setCookie("username", username, 365);
      }
    }
  } 