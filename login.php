<link rel='stylesheet' href='asset/css/bootstrap.min.css'>
<style>
    .show {
        display: none;
    }

    .center {
        margin: 0% 45%;
        padding-top: 20%;
    }

    body {
        background: #ddd;
        width: 100%;
        height: 500px;
    }
</style>
<?php
echo '
<div class="center" id="close">
<form action="index.php" method="post">
<input type="submit" class="btn btn-primary" value="LOG IN" name="login">
</form>
    <input type="submit" class="btn btn-primary" value="SIGN UP" onclick="signup()">
</div>
<div class="show" id="show">
<div style="position:absolute;top:40%;left:50%;transform: translate(-50%,-50%)">
        <div style="border:1px solid black; border-radius:5px; padding:40px 30px">
            <b style="text-align:center;padding:30%">Chat-App</b><br/><br/>
            <form action="" method="post">
                <label>Username</label><br/>
                <input class="form-control" type="text" name="name"/><br/>
                <label>email</label><br/>
                <input class="form-control" type="email" name="email"/><br/>
                <label>Password</label><br/>
                <input class="form-control" type="password" name="password"/><br/><br/>
                <input class="btn btn-primary" type="submit" name="sign_in"/>
            </form>
            <form action="login.php">
                <input type="submit" value="Back" class="btn btn-">
            </form>
        </div>
    </div>
</div>';
$open_file = file_get_contents('datas/users.json');
$read_file = json_decode($open_file, true);
$user_chat = file_get_contents('datas/user_chat.json');
$read_chat = json_decode($user_chat, true);
if (isset($_POST['sign_in'])) {
    $_POST['chatID'] = 'e' . rand(1, 100000);
    $_POST['profile'] = 'unknow.jpg';
    $_POST['status'] = 'offline';
    array_push($read_file, $_POST);
    file_put_contents('datas/users.json', json_encode($read_file, JSON_PRETTY_PRINT));

    $id = $_POST['chatID'];
    array_push($read_chat[$id], $id);
    $change = json_encode($read_chat);
    $replace = str_replace('null', '[]', $change);
    file_put_contents('datas/user_chat.json', $replace);
    header('Location: index.php');
}
if (isset($_POST['login'])) {
    header('Location: index.php');
}
?>
<script>
    function signup() {
        document.getElementById('show').style.display = "block";
        document.getElementById('close').style.display = "none";
    }
</script>