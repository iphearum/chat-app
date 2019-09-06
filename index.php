<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chat App</title>
    <link rel='stylesheet' href='asset/css/bootstrap.min.css'>
    <link rel="stylesheet" href="asset/css/theme.css">
    <style>
        @import url('asset/css/iconfont/material-icons.css');
    </style>
</head>

<body>
    <div class="myclass">
        <div class="row no-gutters">
            <!-- grid 1 chat to group -->
            <?php
            session_start();
            class Login
            {
                private $name;
                private $password;
                private $id;
                private $name_group;


                public function Login()
                {
                    // call class to manage function
                    $user = new User("users");
                    $userC = new UserChat("user_chat", $user->getChatId());
                    $chat = new Chat('chats', $user->getChatId(), $this->name);
                    // code auto login
                    if ($_SESSION["username"] != null && $_SESSION["password"] != null) {
                        $this->name = $_SESSION["username"];
                        $this->password = $_SESSION["password"];
                        $this->name_group = $_COOKIE['name_group'];
                        echo '<input type="hidden" id="nameSession" value="' . $this->name . '"/>';
                        echo '<input type="hidden" id="pasSession" value="' . $this->password . '"/>';
                        // grid 1
                        echo '<div class="col-md-4 border-right fixed-grid1">
                <div class="settings-tray">';

                        $user->getUser($this->name, $this->password);
                        echo '
                <style id="refresh">
                    .' . $this->name_group . '{
                        background: #B3E5FC;
                        color:white;
                        border-radius:10px;
                    }
                </style>
                ';
                        // $user->ToOnline();
                        echo '<div id="show-chat" title="Click me to display content"><i class="material-icons">add</i></div>';
                        echo '<div class="content">';
                        $chat->AddUserToGroup();
                        echo '</div>';
                        $userC->getGroupUser($user->getChatId());
                        echo '</div></div>';
                        echo '<div class="col-md-8 fixed-grid2">';
                        $chat->showchat($this->name_group);
                        $chat->ChatTo($this->name, $this->name_group);
                        echo '</div>';
                        // update status
                        echo '<form method="POST" style="display:none"><input style="display:block;position:absolute" type="submit" name="offline" value="offline" id="offline"></form>
                <form method="POST" style="display:none"><input type="submit" name="online" value="online"></form>';
                        if (isset($_POST['offline'])) {
                            $user->ToOffline();
                            header('Location: index.php');
                        }
                        if (isset($_POST['online'])) {
                            $user->ToOnline();
                            header('Location: index.php');
                        }
                    }
                    // form login
                    else {
                        // header('Location: login.php');
                        echo '
                <div style="position:absolute;top:40%;left:50%;transform: translate(-50%,-50%)">
                        <div style="border:1px solid black; border-radius:5px; padding:40px 30px">
                            <b style="text-align:center;padding:30%">Chat-App</b><br/><br/>
                            <form action="" method="post">
                                <label>Username</label><br/>
                                <input class="form-control" type="text" name="name"/><br/>
                                <label>Password</label><br/>
                                <input class="form-control" type="password" name="password"/><br/><br/>
                                <input class="btn btn-primary" type="submit" name="sign_in"/>
                            </form>
                        </div>
                    </div>';
                        $open_file = file_get_contents('datas/users.json');
                        $read_file = json_decode($open_file);
                        if (isset($_POST['sign_in'])) {
                            foreach ($read_file as $key => $val) {
                                if ($_POST['name'] == ($val->name) && $_POST['password'] == ($val->password)) {
                                    $_SESSION["id"] = $val->chatID;
                                    $_SESSION["username"] = $val->name;
                                    $_SESSION["password"] = $val->password;
                                    header('Location: index.php');
                                }
                            }
                        }
                    } //end auto login
                }
                // logout
                public function Logout()
                {
                    $user = new User("users");
                    echo '<form action="index.php" method="POST" style="position:absolute;border-radius:10px;border:1px solid blue;top:-10px;right:-10px">
                <input type="submit" name="logout" value="Logout">
            </form>';
                    if (isset($_POST['logout'])) {
                        $user->ToOffline();
                        session_unset();
                        session_destroy();
                        header('Location: login.php');
                    }
                }
                public function getNameUser()
                {
                    return $this->name;
                }
                public function getUserID()
                {
                    return $this->id;
                }
            }

            class User
            {
                public $name;
                private $username;
                private $useremail;
                private $userstatus;
                private $userchatid;
                private $userpass;
                private $userprifile;

                public function __construct($name)
                {
                    $this->name = $name;
                }
                // update to offline
                public function ToOffline()
                {
                    $read = json_decode(file_get_contents('datas/' . $this->name . '.json'));
                    foreach ($read as $key => $entry) {
                        if ($entry->name == $_SESSION["username"]) {
                            $read[$key]->status = "offline";
                        }
                    }
                    $newJsonString = json_encode($read, JSON_PRETTY_PRINT);
                    file_put_contents('datas/' . $this->name . '.json', $newJsonString);
                }
                // update to online
                public function ToOnline()
                {
                    $read = json_decode(file_get_contents('datas/' . $this->name . '.json'));
                    foreach ($read as $key => $entry) {
                        if ($entry->name == $_SESSION["username"]) {
                            $read[$key]->status = "online";
                        }
                    }
                    $newJsonString = json_encode($read, JSON_PRETTY_PRINT);
                    file_put_contents('datas/' . $this->name . '.json', $newJsonString);
                }
                // get specific user
                public function getUser($name, $password)
                {
                    $open_file = file_get_contents('datas/' . $this->name . '.json');
                    $read_file = json_decode($open_file);
                    foreach ($read_file as $key => $val) {
                        if (($val->name == $name) && ($val->password == $password)) {
                            echo '
                    <img class="profile-image" src="asset/images/' . $val->profile . '" alt="">
                        <span class="settings-tray--right" style="right:10px;position:absolute">
                    </span>
                    ';
                            echo $val->name;
                            $this->username = $val->name;
                            $this->useremail = $val->email;
                            $this->userpass = $val->password;
                            $this->userchatid = $val->chatID;
                            $this->userstatus = $val->status;
                            $this->userprofile = $val->profile;
                        }
                    }
                }
                // get all user
                public function allUser()
                {
                    $open_file = file_get_contents('datas/' . $this->name . '.json');
                    $read_file = json_decode($open_file);
                    foreach ($read_file as $key  => $val) {
                        echo "<br/>" . $val->name . ", " . $val->email . ", " . $val->password . ", " . $val->chatID . ", " . $val->status . ", " . $val->profile;
                    }
                }
                // to see someone online
                public function Online($online)
                {
                    $open_file = file_get_contents('datas/' . $this->name . '.json');
                    $getuser = json_decode($open_file);
                    $friends = 0;
                    $onlines = 0;
                    foreach ($getuser as $key => $val) {
                        $getonline = $val->status;
                        $friends++;
                        if ($getonline == $online) {
                            $onlines++;
                            // echo $val->name.$val->email.$val->password.$val->chatID.$val->status.$val->profile;
                            echo "<br/><div class='nameuseronline'>" . $val->name . '<span class ="online"></span></div>';
                        } else {
                            echo "<br/><div class='nameuseronline'>" . $val->name . '<span class ="offline"></span></div>';
                        }
                    }
                    echo '<br/><div class="all_online">Friends:' . $friends . ' ~ Online:' . $onlines . '</div>';
                }
                public function getStatus()
                {
                    return $this->userstatus;
                }
                public function getNameUser()
                {
                    return $this->username;
                }
                public function getEmail()
                {
                    return $this->useremail;
                }
                public function getPassword()
                {
                    return $this->userpass;
                }
                public function getChatId()
                {
                    return $this->userchatid;
                }
                public function getProfile()
                {
                    return $this->userprifile;
                }
            }

            class UserChat
            {
                public $data;
                public $id;
                private $name;
                public function __construct($name, $id)
                {
                    $this->name = $name;
                    $this->id = $id;
                }
                // list all group chat for user
                public function getGroupUser($id)
                {
                    $function = [];
                    $open_file = file_get_contents('datas/' . $this->name . '.json');
                    $chat_file = file_get_contents('datas/chats.json');
                    $this->data = json_decode($open_file);
                    $chats = json_decode($chat_file);
                    foreach ($this->data as $key => $value) {
                        if ($id == $key) {
                            foreach ($this->data->$key as $n => $val) {
                                $name = $val->name_group;
                                $image_group = $val->profile;
                                echo '<div class="friend-drawer friend-drawer--onhover ' . $name . '" ONCLICK="' . $name . '()">
                        <img class="profile-image" src="asset/images/' . $image_group . '" alt="">
                        <div class="text">
                        <h6>' . $val->name_group . '</h6>
                        <p class="text-muted">' . end($chats->$name)->chat . '</p>
                        <div style="width=100%;font-size:9px;margin-top:-5px;color:gray"><span>' . end($chats->$name)->name . '</span><span style="position:absolute;right:20px">' . end($chats->$name)->date . '</span></div>
                        </div>
                        <span class="time text-muted small">13:21</span>
                        </div>';
                                echo '<script>function ' . $name . '(){
                            document.cookie = "name_group=' . $name . '";
                            document.cookie = "image_group=' . $image_group . '";
                        }</script>';
                            }
                        }
                    }
                }
            }

            class Chat
            {
                private $name;
                private $chat_name;
                public $data;
                public $togroup;
                public $username;
                public function __construct($name, $id, $username)
                {
                    $this->name = $name;
                    $this->id = $id;
                    $this->username = $username;
                }
                // list all group name
                public function allChat()
                {
                    $open_file = file_get_contents('datas/' . $this->name . '.json');
                    $data = json_decode($open_file);
                    echo "<br/><b>File name: " . $this->name . "</b>";
                    foreach ($data as $key => $value) {
                        echo "<br/>name group <b>$key</b>";
                    }
                }
                // chat to specific group
                public function ChatTo($username, $togroup)
                {
                    $user = new User("users");

                    $this->togroup = $togroup;
                    $date = date("Y/m/d h:i:sa");
                    $open_group = file_get_contents('datas/' . $this->name . '.json');
                    $read_group = json_decode($open_group);
                    foreach ($read_group as $key => $value) {
                        $this->chat_name = $key;
                        if ($key == $this->togroup) {
                            echo '
                        <div class="row">
                            <div class="col-12" style="bottom:0px;position:absolute; background:#eee;left:0px;margin:0px 0px">
                            <form method="post" action="">
                            <div class="chat-box-tray" style="margin-top:0px;margin-bottom:0px">
                            <i class="material-icons">sentiment_very_satisfied</i>
                            <input type="text" class="form-control" placeholder="Type your message here..." name="chat" style="border-radius:15px;padding:15px"/>
                                <input type="hidden" class="form-control" placeholder="typing..." name="name" value="' . $username . '"/>
                                <input type="hidden" class="form-control" placeholder="typing..." name="date" value=' . $date . ' />
                                <input type="hidden" class="form-control" placeholder="typing..." name="image" value="' . $username . '.jpg" />
                            <i class="material-icons">mic</i>
                            <i class="material-icons"><input type="submit" name="send" value="send" style="background:none;margin-left:-7px;" /></i>
                            </div>
                            </form>
                        </div>';
                            if (isset($_POST["send"])) {
                                $user->ToOnline();
                                if ($_POST['chat'] != '') {
                                    $file = file_get_contents('datas/' . $this->name . '.json');
                                    $data = json_decode($file, true);
                                    unset($_POST["send"]);
                                    // $data[$this->togroup] = array_values($data[$this->togroup]);
                                    array_push($data[$this->togroup], $_POST);
                                    file_put_contents('datas/' . $this->name . '.json', json_encode($data, JSON_PRETTY_PRINT));
                                }
                            }
                        }
                    }
                }
                // show content chat
                public function showchat($togroup)
                {
                    // echo '<div id="reload"><div id="time">';
                    echo '<div id="refresh_chat">';
                    $this->togroup = $togroup;
                    $open_group = file_get_contents('datas/' . $this->name . '.json');
                    $read_group = json_decode($open_group);
                    foreach ($read_group as $key => $value) {
                        $this->chat_name = $key;
                        if ($key == $this->togroup) {
                            echo '<div class="settings-tray">
                        <div class="friend-drawer no-gutters friend-drawer--grey">
                        <img class="profile-image" src="asset/images/' . $_COOKIE['image_group'] . '" alt="">
                        <div class="text">
                        <h6>' . $key . '</h6>
                        </div>
                        <span class="settings-tray--right" style="right:10px;position:absolute">
                        <i class="material-icons">cached</i>
                        <i class="material-icons">message</i>
                        <i class="material-icons">menu</i>
                        </span>
                    </div>
                    </div>

                    <div id="viewport">
                        <div class="chatbox">
                            <div class="chats">
                    <ul>';
                            foreach ($read_group->$key as $n => $val) {
                                if ($_SESSION["username"] == $val->name) {
                                    $name = $val->name;
                                    $val->name = "you";
                                    echo '
                            <li>
                            <div class="msg ' . $val->name . '">
                            ' . $val->chat . '
                            <span class="time">' . $val->date . '</span>
                            </div>
                            </li>';
                                } else {
                                    echo '<li>
                            <div class="msg him">
                            <img class="profile-image" src="asset/images/' . $val->image . '" alt="" style="width:25px;height:25px; position:absolute;top:7px;left:-30px">
                                <span class="partner">' . $val->name . '</span>
                                ' . $val->chat . '
                                <span class="time">' . $val->date . '</span>
                                </div>
                            </li>';
                                }
                            }
                        }
                    }
                    echo '</ul></div></div></div></div>';
                }
                // create new chat
                public function AddUserToGroup()
                {
                    echo '<div class="create-chat">
                <form action="index.php" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
                <input placeholder="Create group" type="text" name= "name_group" class="form-control"><ul style="padding-top:10px;text-align:left">
                <input type="file" name="avatar" accept="image/png, image/jpeg"><ul class="create-group"><br/>';
                    $open_file = file_get_contents('datas/users.json');
                    $read_file = json_decode($open_file);
                    $userID = new User($_SESSION["username"]);
                    foreach ($read_file as $key => $val) {
                        if ($val->name != $_SESSION["username"]) {
                            echo "<li class='useradd'>|<input style='width:30px;margin:5px 5px;' type='checkbox' value='$val->chatID' name='$val->chatID'/>$val->name |</li>";
                        } else {
                            echo "<li class='useradd' style='display:none'>|<input style='width:30px;margin:5px 5px;' type='checkbox' value='$val->chatID' name='$val->chatID' checked/>$val->name |</li>";
                        }
                    }
                    echo '</ul><input type="submit" class="btn btn-primary btn-sm" name="submit" value="Create Group Chat"/>
            </form></div>';
                    if (isset($_POST['submit'])) {
                        $target_dir = "asset/images/";
                        $name = rand(1, 100000) . ".jpg";
                        $new_name = $target_dir . $name;
                        $file = file_get_contents('datas/user_chat.json');
                        $data = json_decode($file, true);
                        if ($_POST['name_group'] != null) {
                            move_uploaded_file($_FILES["avatar"]["tmp_name"], $new_name);
                            $_GET['profile'] = $name;
                            $_GET['name_group'] = $_POST['name_group'];
                            $_POST[$userID->getChatId()] = $userID->getChatId();
                            unset($_POST['name_group']);
                            unset($_POST['avatar']);
                            unset($_POST['submit']);
                            // add name_group to user
                            $user = array_values($_POST);
                            foreach ($user as $id) {
                                array_push($data[$id], $_GET);
                            }
                            file_put_contents('datas/user_chat.json', json_encode($data, JSON_PRETTY_PRINT));

                            // add name_group to file chats
                            $filechat = file_get_contents('datas/chats.json');
                            $chat = json_decode($filechat, true);
                            unset($_GET['profile']);
                            $chatadd = array_values($_GET);
                            foreach ($chatadd as $id) {
                                array_push($chat[$id], $_GET);
                            }
                            $change = json_encode($chat);
                            $replace = str_replace('null', '[]', $change);
                            file_put_contents('datas/chats.json', $replace);
                        }
                    }
                }
            }
            $login = new Login();
            $user = new User("users");
            echo '<div>';
            $login->logout();
            echo '</div>';
            ?>
            <script src="asset/js/jquery.min.js"></script>
            <script src="asset/js/main.js"></script>