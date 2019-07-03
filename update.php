<?php
function ToOffline(){
    $read = json_decode(file_get_contents('datas/users.json'));
    foreach ($read as $key => $entry) {
        if ($entry->name == "mengthong") {
            $read[$key]->status = "offline";
        }
    }
    $newJsonString = json_encode($read,JSON_PRETTY_PRINT);
    file_put_contents('datas/users.json', $newJsonString);
}

function ToOnline(){
    $read = json_decode(file_get_contents('datas/users.json'));
    foreach ($read as $key => $entry) {
        if ($entry->name == "phearum") {
            $read[$key]->status = "online";
        }
    }
    $newJsonString = json_encode($read,JSON_PRETTY_PRINT);
    file_put_contents('datas/users.json', $newJsonString);
}
// ToOffline();
ToOnline();
?>