<?php include "global.func.php"; ?>
<html><head></head><body>
<?php
function chrhex($hex){
	$r=chr(hexdec($hex));
	return $r;
}

$sql="SELECT * FROM user WHERE name='$_POST[name]'";
$db->inject_check($_POST['name']);
$db->inject_check($_POST['password']);
$q=$db->query($sql);
$my=$db->fetch_array($q);
if(empty($my['id'])){
	echo '用户名或密码错误!</body></html>';
	exit();
}elseif($my['password']!=$_POST['password']){
	echo '用户名或密码错误!</body></html>';
	exit();
}
onlinelist_sync();
$sql="UPDATE user SET logged = '1' WHERE id = '$my[id]'";
$db->query($sql);
make_whitelist();
$ftpcon = ftp_connect($ftphost) or die("Could not connect");
ftp_login($ftpcon,$ftpuser,$ftppw);
//echo ftp_pwd($ftpcon);
//ftp_get($ftpcon,"whitelist.json","1.9test/whitelist.json",FTP_BINARY);
ftp_put($ftpcon,"1.9test/whitelist.json","whitelist.json",FTP_BINARY);
ftp_close($ftpcon);

$con->login($rconhost,$rconport,$rconpw);
$con->run('whitelist reload');
$con->logout();
$_SESSION['id']=$my['id'];
echo '登录成功!<br /><a href="index.php">返回首页</a>';
?>
</body>
</html>