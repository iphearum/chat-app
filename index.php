<link rel="stylesheet" href="asset/css/style.css">

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
            //call function to display
            // echo date('t').date('F').date('y').date('h')."<br/>";

            echo '<div class="auth">';
            $user->getUser($this->name, $this->password);
            $user->ToOnline();
            echo '</div>';

            echo '<div class="group_online">';
            $user->Online('online');
            echo '</div>';

            echo '<div class="groupchat">';
            $userC->getGroupUser($user->getChatId());
            echo '</div>';

            $chat->AddUserToGroup();
            echo '<br/><b>Chating</b><hr/>';

            $chat->showchat($this->name_group);
            echo '<br/>';

            $chat->ChatTo($this->name, $this->name_group);


            echo '<br/><form method="POST">
                <input type="submit" name="update" value="update" id="update">
            </form>';
            if (isset($_POST['update'])) {
                $user->ToOffline();
                header('Location: index.php');
            }
            echo '<br/><form method="POST">
                <input type="submit" name="online" value="online">
            </form>';
            if (isset($_POST['online'])) {
                $user->ToOnline();
                header('Location: index.php');
            }
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
                foreach ($read_file as $key => $val) {
                    if ($_POST['name'] == ($val->name) && $_POST['password'] == ($val->password)) {
                        // setcookie("chatApp[name]", $val->name);
                        // setcookie("chatApp[password]", $val->password);
                        $_SESSION["id"] = $val->chatID;
                        $_SESSION["username"] = $val->name;
                        $_SESSION["password"] = $val->password;
                        // $user->ToOnline();
                        header('Location: index.php');
                    }
                }
            }
        } //end auto login
    }
    public function updateStatus()
    {
        $open_file1 = file_get_contents('datas/users.json');
        $read_file1 = json_decode($open_file1);
        foreach ($read_file as $key => $val) {
            if ($_POST['name'] == ($val->name) && $_POST['password'] == ($val->password)) {
                $data['name'] = $val->name;
                $data['email'] = $val->email;
                $data['password'] = $val->password;
                $data['chatID'] = $val->chatID;
                $data['status'] = "offline";
                $data['profile'] = $val->profile;
                array_push($read_file1[$val->name], $data);
                file_put_contents('datas/users.json', json_encode($read_file1, JSON_PRETTY_PRINT));
            }
        }
    }

    public function Logout()
    {
        $user = new User("users");
        echo '<form action="index.php" method="POST">
            <input type="submit" name="logout" value="Logout">
        </form>';
        if (isset($_POST['logout'])) {
            $user->ToOffline();
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
    
    public function ToOffline(){
        $read = json_decode(file_get_contents('datas/' . $this->name . '.json'));
        foreach ($read as $key => $entry) {
            if ($entry->name == $_SESSION["username"]) {
                $read[$key]->status = "offline";
            }
        }
        $newJsonString = json_encode($read,JSON_PRETTY_PRINT);
        file_put_contents('datas/' . $this->name . '.json', $newJsonString);
    }

    public function ToOnline(){
        $read = json_decode(file_get_contents('datas/' . $this->name . '.json'));
        foreach ($read as $key => $entry) {
            if ($entry->name == $_SESSION["username"]) {
                $read[$key]->status = "online";
            }
        }
        $newJsonString = json_encode($read,JSON_PRETTY_PRINT);
        file_put_contents('datas/' . $this->name . '.json', $newJsonString);
    }

    public function getUser($name, $password)
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $read_file = json_decode($open_file);
        foreach ($read_file as $key => $val) {
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
    public function allUser()
    {
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $read_file = json_decode($open_file);
        foreach ($read_file as $key  => $val) {
            echo "<br/>" . $val->name . ", " . $val->email . ", " . $val->password . ", " . $val->chatID . ", " . $val->status . ", " . $val->profile;
        }
    }
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
        $function = [];
        $open_file = file_get_contents('datas/' . $this->name . '.json');
        $this->data = json_decode($open_file);

        foreach ($this->data as $key => $value) {
            if ($id == $key) {
                foreach ($this->data->$key as $n => $val) {
                    echo '<a ONCLICK="' . $val->name_group . '()"><p>' . $val->name_group . '</p></a>';
                    array_push($function, $val->name_group);
                }
            }
        }

        echo '<script>';
        foreach ($function as $chat => $vale) {
            echo 'function ' . $vale . '(){
                document.cookie = "name_group=' . $vale . '";
            }';
        }
        echo '</script>';
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
    // create new chat
    public function AddUserToGroup()
    {
        echo '
        <form action="index.php" method="POST">
        <h5>Create Group</h5>
        <article>Enter Group Name: </article>
        <input type="text" name= "name_group"/>
        <article>Add People: </article>';
        $open_file = file_get_contents('datas/users.json');
        $read_file = json_decode($open_file);
        $userID = new User($_SESSION["username"]);
        foreach ($read_file as $key => $val) {
            echo "<input type='checkbox' value='$val->chatID' name='$val->chatID'/>$val->name<br/>";
        }
        echo '      
           <input type="submit" name="submit" value="Create Group Chat"/>
          </form>';


        if (isset($_POST['submit'])) {
            $file = file_get_contents('datas/user_chat.json');
            $data = json_decode($file, true);
            $_GET['name_group'] = $_POST['name_group'];
            $_POST[$userID->getChatId()] = $userID->getChatId();
            unset($_POST['name_group']);
            unset($_POST['submit']);
            $user = array_values($_POST);
            foreach ($user as $id) {
                array_push($data[$id], $_GET);
            }
            file_put_contents('datas/user_chat.json', json_encode($data, JSON_PRETTY_PRINT));

            // add name_group to file chats.json
            $filechat = file_get_contents('datas/chats.json');
            $chat = json_decode($filechat, true);
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
$user = new User("users");
echo '<div class="center">';
$login->logout();
// $login->updateStatus();
echo '</div>';
?>

<script src="asset/js/jquery.min.js"></script>
<script>
    function reload() {
        $('#reload').load(location.href + ' #time');
        $('#time').load(location.href + ' #load');
    }

    window.setTimeout(function() {
        document.getElementById('update').submit();
    }, 5000);

    setInterval("reload();", 500);
</script>