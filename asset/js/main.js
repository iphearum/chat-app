function reload() {
    $('#reload').load(location.href + ' #time');
    $('#time').load(location.href + ' #reload');
}
setInterval("reload();", 500);

$("#show-chat").click(function () {
    var $button = $(this);
    var $content = $button.next(".content");
    if ($content.is(":animated")) {
        console.log("clicked but did nothing");
        return false;
    }
    console.log("clicked");
    var label_text = $content.is(":hidden") ? "add" : "close";

    $content.slideToggle(500);
    // $content.slideToggle(500, function () {
    //     $button.text(label_text);
    // });
}).dblclick(function (e) {
    console.log("double-clicked but did nothing");
    e.stopPropagation();
    e.preventDefault();
    return false;
});