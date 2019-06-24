<link rel="stylesheet" href="asset/css/style.css">
<?php
session_start();
class Login
{
    private $name;
    private $password;
    private $id;
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
            //call function to display
            // echo date('t').date('F').date('y').date('h')."<br/>";

            echo '<div class="auth">';
            $user->getUser($this->name, $this->password);
            echo '</div>';
            echo '<div class="group_online">';
            $user->Online('online');
            echo '</div>';

            echo '<div class="all_user">';
            // $user->allUser();
            echo '</div>';


            echo '<div class="groupchat">';
            $userC->getGroupUser($user->getChatId());
            echo '</div>';

            // $chat->allChat();
            // echo '<br/><b>All Chat</b><hr/>';

            $chat->createChate();
            echo '<br/><b>Chating</b><hr/>';

            $chat->showchat("group1");
            echo '<br/><b>create Chat</b><hr/>';

            $chat->ChatTo($this->name, 'group1');
            echo '<br/><b>chat to group </b><hr/>';
        }
        // code need to assign value
        else {
            echo '
            <div style="position:absolute;top:40%;left:50%;transform: translate(-50%,-50%)">
                <div style="border:1px solid black; border-radius:5px; padding:40px 30px">
                    <b>Form Login to ChatApp</b><br/><br/>
                    <form action="" method="post">
                        <label>Username</label><br/>
                        <input type="text" name="name"/><br/>
                        <label>Password</label><br/>
                        <input type="password" name="password"/><br/><br/>
                        <input type="submit" name="sign_in"/>
                    </form>
                </div>
            </div>
			';
            $open_file = file_get_contents('datas/users.json');
            $read_file = json_decode($open_file);
            if (isset($_POST['sign_in'])) {
                foreach ($read_file as $key => $value) {
                    foreach ($read_file->$key as $n => $val) {
                        if ($_POST['name'] == ($val->name) && $_POST['password'] == ($val->password)) {
                            // setcookie("chatApp[name]", $val->name);
                            // setcookie("chatApp[password]", $val->password);
                            $_SESSION["id"] = $val->chatID;
                            $_SESSION["username"] = $val->name;
                            $_SESSION["password"] = $val->password;
                            header('Location: index.php');
                        }
                    }
                }
            }
        } //end auto login
    }
    public function Logout()
    {
        echo '<form action="index.php" method="POST">
            <input type="submit" name="logout" value="Logout">
        </form>';
        if (isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            header('Location: index.php');
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
?>

<?php
class User extends File
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
    public function getUser($name, $password)
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $read_file = json_decode($open_file);
        foreach ($read_file as $key => $value) {
            foreach ($read_file->$key as $n => $val) {
                if (($val->name == $name) && ($val->password == $password)) {
                    echo $val->name . ", " . $val->email . ", " . $val->password . ", " . $val->chatID . ", " . $val->status . ", " . $val->profile;
                    $this->username = $val->name;
                    $this->useremail = $val->email;
                    $this->userpass = $val->password;
                    $this->userchatid = $val->chatID;
                    $this->userstatus = $val->status;
                    $this->userprofile = $val->profile;
                }
            }
        }
    }
    public function allUser()
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $read_file = json_decode($open_file);
        foreach ($read_file as $key => $value) {
            foreach ($read_file->$key as $n => $val) {
                echo "<br/>" . $val->name . ", " . $val->email . ", " . $val->password . ", " . $val->chatID . ", " . $val->status . ", " . $val->profile;
            }
        }
    }
    public function uOnline()
    {
        $online = 'online';
    }
    public function Online($online)
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $getuser = json_decode($open_file);
        $friends = 0;
        $onlines = 0;
        foreach ($getuser as $key => $value) {
            foreach ($getuser->$key as $key1 => $val) {
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

class UserChat extends File
{
    public $data;
    public $id;
    private $name;
    public function __construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;
    }
    public function getGroupUser($id)
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $this->data = json_decode($open_file);
        foreach ($this->data as $key => $value) {
            if ($id == $key) {
                foreach ($this->data->$key as $n => $val) {
                    echo '<b id="001"></b>';
                    echo '<a href="" onclick("' . $val->name_group . '();")><p id="' . $val->name_group . '">' . $val->name_group . '</p></a>';
                    // echo '<script>document.getElementById("'.$val->name_group.'").addEventListener(click, function); </script>';
                    echo '<script>
                    var group = document.getElementById("' . $val->name_group . '").value;
                    group.innerHTML=document.getElementById("001");
                    function ' . $val->name_group . '(){
                        document.cookie = "chat_name=' . $val->name_group . '";
                    } 
                    }
                    </script>';
                }
            }
        }
    }
}

class Chat extends File
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

    public function getAllChat()
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $data = json_decode($open_file);
        echo "<div style='color:blue'><br/><b>File name: " . $this->name . "</b>";
        foreach ($data as $key => $value) {
            echo "<br/>name group <b>$key</b>";
            foreach ($data->$key as $n => $val) {
                echo "<br/>" . $val->name . ":( " . $val->chat . " )";
            }
        }
        echo "</div>";
    }
    public function allChat()
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $data = json_decode($open_file);
        echo "<br/><b>File name: " . $this->name . "</b>";
        foreach ($data as $key => $value) {
            echo "<br/>name group <b>$key</b>";
        }
    }
    public function ChatTo($username, $togroup)
    {
        $this->togroup = $togroup;
        $date = date("Y/m/d h:i:sa");
        $open_group = file_get_contents('datas/' . $this->name . '.json');
        $read_group = json_decode($open_group);
        foreach ($read_group as $key => $value) {
            $this->chat_name = $key;
            if ($key == $this->togroup) {
                echo '<form method="post" action="">
                <div class="row">
                    <div class="col-md-11">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="typing..." name="chat" />
                            <input type="hidden" class="form-control" placeholder="typing..." name="name" value="' . $username . '"/>
                            <input type="hidden" class="form-control" placeholder="typing..." name="date" value=' . $date . ' />
                            <input type="hidden" class="form-control" placeholder="typing..." name="image" value="' . $username . '.jpg" />
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group" style="margin-top:-5px;margin-left:10px">
                            <input type="submit" class="btn btn-sm btn-white" name="send" value="" />
                        </div>
                    </div>
                </div>
            </form>';
                if (isset($_POST["send"])) {
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
    public function showchat($togroup)
    {
        echo '<div id="reload"><div id="time">';
        $this->togroup = $togroup;
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
        echo '</div></div>';
    }

    public function createChate()
    {
        echo '<div class="create_chat">
        <form action="" method="POST">
            <input type="text" name="name_group" value="" placeholder="Name group"><br/>';
        $create_chat = file_get_contents('datas/users.json');
        $open_chat = json_decode($create_chat);
        foreach ($open_chat as $key => $value) {
            foreach ($open_chat->$key as $n => $val) {
                // if($val->chatID=$_SESSION['id']){
                //     echo '<input style="display:none" type="checkbox" name="' . $val->chatID . '" value="' . $val->chatID . '">' . $val->name . '<br/>';
                // }else
                echo '<input type="checkbox" name="' . $val->chatID . '" value="' . $val->chatID . '">' . $val->name . '<br/>';
            }
        }
        echo '<br/><input type="submit" name="submit" value="create_chat">
        </form></div>';
        $_GET['group_name'] = $_POST['group_name'];
        $file = file_get_contents('datas/user_chat.json');
        $data = json_decode($file, true);
        $_POST[$_SESSION['id']] = $_SESSION['id'];
        unset($_POST['group_name']);
        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $user = array_values($_POST);
            foreach ($user as $id) {
                array_push($data[$id], $_GET);
            }
            file_put_contents("datas/user_chat.json", json_encode($data, JSON_PRETTY_PRINT));
        }
    }
}

// abstract class
abstract class File
{
    public $name_file;
    public function __construct($name)
    {
        $this->name_file = $name;
    }
    public function ReadFile()
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $this->read_file = json_decode($open_file);
    }
}
$login = new Login();
echo '<div class="center">';
$login->logout();
echo '</div>';
?>

<script src="asset/js/jquery.min.js"></script>
<script>
    var file = $.getJSON("datas/users.json");

    function reload() {
        $('#reload').load(location.href + ' #time');
        $('#time').load(location.href + ' #load');
    }
    // if user login the data will be online
    function setUsername(id, newUsername) {
        for (var i = 0; i < jsonObj.length; i++) {
            if (jsonObj[i].Id === id) {
                jsonObj[i].Username = newUsername;
                return;
            }
        }
    }

    // set time to offline when the time go throw 5sec
    function setUsername(id, newUsername) {
        for (var i = 0; i < jsonObj.length; i++) {
            if (jsonObj[i].Id === id) {
                jsonObj[i].Username = newUsername;
                return;
            }
        }
    }
    setInterval("reload();", 500);
</script>