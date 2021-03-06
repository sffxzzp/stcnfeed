<?php
$file = 'config.php';
require_once('functions.php');
if (file_exists($file)) {
    require_once($file);
    $sqlInfo = array(
        "host"  =>  $host,
        "user"  =>  $user,
        "pwd"   =>  $pwd,
        "db"    =>  $db
    );
    if (isset($_GET["clear"])) {
        if ($_GET["clear"]==$pwd) {
            sqlExec($sqlInfo, 'truncate '.$tablelist.';');
            echo 'clear success!<br>SQL Data re-initialized...<br>';
            curl($_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.$_SERVER['SERVER_NAME'].'/index.php?init');
            echo '<a href="'.$_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.$_SERVER['SERVER_NAME'].$installpath.'/">click me!</a>';
        }
    }
    else {
        if (isset($db)&&isset($host)&&isset($user)&isset($pwd)) {
            if (!getData($sqlInfo, $tablelist)) {
                sqlInit($sqlInfo, 'stcn_time', 'stcn_list');
                echo 'reinit sql success!';
            }
            else {
                echo 'install already success!';
            }
        }
    }
}
else {
    if ($_POST["db"]!==null&&$_POST["host"]!==null&&$_POST["user"]!==null&&$_POST["pwd"]!==null&&$_POST["prefix"]!==null) {
        $data = '<?php
$db = \''.$_POST["db"].'\';
$host = \''.$_POST["host"].'\';
$user = \''.$_POST["user"].'\';
$pwd = \''.$_POST["pwd"].'\';
//---------------
$prefix = \''.$_POST["prefix"].'\';
$tabletime = $prefix.\'time\';
$tablelist = $prefix.\'list\';
//---------------
// the following should be set if placed in subfolder.
// if placed in www root, leave $installpath = \'\';
// e.g. if placed in subfolder and could open using link like: http://abc.com/steam/feed/
//       you should set $installpath to \'/steam/feed\'
//---------------
$installpath = \'\';';
        $sqlInfo = array(
            "host"  =>  $_POST["host"],
            "user"  =>  $_POST["user"],
            "pwd"   =>  $_POST["pwd"],
            "db"    =>  $_POST["db"]
        );
        if (sqlInit($sqlInfo, $_POST["prefix"].'time', $_POST["prefix"].'list')) {
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
</tr>
<tr>
<td>表前缀</td>
<td><input name="prefix" value="stcn_" type="text"></td>
<td>数据库中表的前缀</td>
</tr>
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