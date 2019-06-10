<?php
session_start();
class Login
{
    private $name;
    private $password;
    public function Login()
    {
        // call class to manage function
        $user = new User("users");
        $userC = new UserChat("user_chat", $user->getChatId());
        $chat = new Chat('chats', $user->getChatId(), $this->name);

        // code auto login
        if ($_SESSION["username"]!=null && $_SESSION["password"]!=null) {
            $this->name = $_SESSION["username"];
            $this->password = $_SESSION["password"];
            echo $this->name.'-';
            echo $this->password;

            //call function to display
            echo '<br/><div id="time"><div id="load">'.date("Y/m/d h:i:sa").'</div></div><br/>';
            // echo date('t').date('F').date('y').date('h')."<br/>";
            $user->getUser($this->name, $this->password);
            echo '<br/><b>get User login</b><hr/>';

            $user->Online('online');
            echo '<br/><b>user online</b><hr/>';

            $user->allUser();
            echo '<br/><b>list all user </b><hr/>';

            echo $user->getChatId();
            $userC->getGroupUser($user->getChatId());
            echo '<br/><b>List Chat`s User</b><hr/>';

            $chat->allChat();
            echo '<br/><b>All Chat</b><hr/>';

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
        if(isset($_POST['logout'])){
            session_unset();
            session_destroy(); 
            header('Location: index.php');
        }
    }
    public function getNameUser()
    {
        return $this->name;
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
    public function uOnline(){
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
                // echo $this->online."<br/>";
                $friends++;
                if ($getonline == $online) {
                    $onlines++;
                    // echo $val->name.$val->email.$val->password.$val->chatID.$val->status.$val->profile;
                    echo "<br/>($val->name, $val->status, $val->profile)";
                }
            }
            echo "<br/>Friends: " . $friends . ", Online's friend: " . $onlines;
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
                    echo '<a href=""><p>' . $val->name_group . '</p></a>';
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
    public function showchat($togroup){
        echo'<div id="reload"><div id="time">';
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
        echo'</div></div>';
    }

    public function createChate()
    {
        echo'<a href="create_chat.php">Create New</a>';
        // echo '<input type="submit" value="Create New" name="create_new"></form>';
        // if (isset($_GET["create_new"])) {
        //     $_GET['name_group'] = $_POST['name_group'];
        //     $file = file_get_contents('datas/user_chat.json');
        //     $data = json_decode($file, true);
        //     unset($_POST['name_group']);
        //     unset($_POST['create_new']);
        //     $user = array_values($_POST);
        //     foreach ($user as $id) {
        //         array_push($data[$id], $_GET);
        //     }
        //     file_put_contents("datas/user_chat.json", json_encode($data, JSON_PRETTY_PRINT));
        // }
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
$login->logout();
?>

<script src="asset/js/jquery.min.js"></script>
<script>
var file = $.getJSON("datas/users.json");
// print(file);
// a=0;
function reload(){
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
setInterval("reload();",500); 
</script>