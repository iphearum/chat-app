<?php
$_GET['name_group'] = $_POST['name_group'];
$file = file_get_contents('../datas/user_chat.json');
$data = json_decode($file, true);
unset($_POST['name_group']);
// unset($_POST['create_new']);
$user = array_values($_POST);
foreach ($user as $id) {
    array_push($data[$id], $_GET);
}
file_put_contents("../datas/user_chat.json", json_encode($data, JSON_PRETTY_PRINT));
?>