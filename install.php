<?php
$file = 'config.php';
require_once('functions.php');
if (file_exists($file)) {
    require_once($file);
    if (isset($_GET["clear"])) {
        if ($_GET["clear"]==$pwd) {
            $sqlInfo = array(
                "host"  =>  $host,
                "user"  =>  $user,
                "pwd"   =>  $pwd,
                "db"    =>  $db
            );
            sqlExec($sqlInfo, 'truncate '.$tablelist.';');
            echo 'clear success!';
        }
    }
    else {
        if (isset($db)&&isset($host)&&isset($user)&isset($pwd)) {
            echo 'install already success!';
        }
    }
}
else {
    if (isset($_POST["db"])&&isset($_POST["host"])&&isset($_POST["user"])&&isset($_POST["pwd"])) {
        $data = '<?php
$db = \''.$_POST["db"].'\';
$host = \''.$_POST["host"].'\';
$user = \''.$_POST["user"].'\';
$pwd = \''.$_POST["pwd"].'\';
//---------------
$tabletime = \'time\';
$tablelist = \'list\';
?>';
        $sqlInfo = array(
            "host"  =>  $_POST["host"],
            "user"  =>  $_POST["user"],
            "pwd"   =>  $_POST["pwd"],
            "db"    =>  $_POST["db"]
        );
        if (sqlInit($sqlInfo, 'time', 'list')) {
            echo 'success!<br><a href="/">Click here to continue!</a>';
            file_put_contents($file, $data);
        }
        else {
            echo 'error!';
        }
    }
    else {
        echo '
<form method="post" action="####">
<table>
<tr>
<td>数据库名</td>
<td><input name="db" placeholder="database" value="stcnfeed" type="text" required="required"></td>
<td>安装到哪个数据库？</td>
</tr>
<tr>
<td>数据库主机</td>
<td><input name="host" placeholder="ip" value="localhost" type="text" required="required"></td>
<td>如果填写localhost之后不能正常工作的话，请向主机服务提供商索要数据库信息。</td>
<tr>
<td>用户名</td>
<td><input name="user" placeholder="username" type="text"></td>
<td>您的MySQL用户名</td>
</tr>
<tr>
<td>密码</td>
<td><input name="pwd" placeholder="password" type="text"></td>
<td>…及其密码</td>
</tr>
</table>
<input type="submit" />
</form>
';
    }
}
?>