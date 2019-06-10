<?php
$open_group = file_get_contents('datas/' . $this->name . '.json');
$read_group = json_decode($open_group);
foreach ($read_group as $key => $value) {
    $this->chat_name = $key;
    if ($key == $this->togroup) {
        echo "<br/>Chat to <b>$key</b>";
        foreach ($read_group->$key as $n => $val) {
            echo "<br/>" . $val->name . ":( " . $val->chat . " )";
        }
    }
}
?>
