function reload() {
    $('#refresh').load(location.href + ' #refresh')
    $('#refresh_chat').load(location.href + ' #refresh_chat')
    $('#refresh_group').load(location.href + ' #refresh_group')
}

function offline() {
    $('#offline').click();
}
setInterval("reload();", 500);
setInterval("offline();", 100000);

$("#show-chat").click(function () {
    var $button = $(this);
    var $content = $button.next(".content");
    if ($content.is(":animated")) {
        console.log("clicked but did nothing");
        return false;
    }
    console.log("clicked");
    $content.slideToggle(500);
}).dblclick(function (e) {
    console.log("double-clicked but did nothing");
    e.stopPropagation();
    e.preventDefault();
    return false;
});