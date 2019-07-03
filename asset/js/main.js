
// console.log(json);

function reload() {
    $('#reload').load(location.href + ' #time');
    $('#time').load(location.href + ' #load');
}
setInterval("reload();", 500);