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