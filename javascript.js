var items = document.getElementById('demo');
$.getJSON("datas/users.json", function(json) {
    console.log(json.user[0].name);
    var item =json.user;
    item.forEach(function item(u,i){
        console.log(u.status);
        console.log(i);
        if(u.chatID=='001')(
            setInterval(function(){
                console.log(u.chatID)
            },1000)
        );
        items.innerHTML = items.innerHTML+"chat_id["+u.chatID+"] = "+u.status+"<br/>";
    })
    // break;
});