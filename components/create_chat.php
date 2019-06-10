<style type="text/css">
</style>
<label for="">Greate Group</label>
<form action="index.php" method="POST">
    <input type="text" name="name_group" value="" placeholder="create group"><br/>
<?php
    $create_chat = file_get_contents('../datas/users.json');
    $open_chat = json_decode($create_chat);
    foreach ($open_chat as $key => $value) {
        foreach ($open_chat->$key as $n => $val) {
            echo '<input type="checkbox" name="' . $val->chatID . '" value="' . $val->chatID . '">' . $val->name.'<br/>';
        }
    }
?>
    <input type="submit" value="Create New" name="create_new">
</form>
<?php
    $_GET['name_group'] = $_POST['name_group'];
    $file = file_get_contents('../datas/user_chat.json');
    $data = json_decode($file, true);
    unset($_POST['name_group']);
    $user = array_values($_POST);
    foreach ($user as $id) {
        array_push($data[$id], $_GET);
    }
    file_put_contents("../datas/user_chat.json", json_encode($data, JSON_PRETTY_PRINT));
?>
