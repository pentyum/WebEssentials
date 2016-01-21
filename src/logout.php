<?php include "global.func.php"; ?>
<html><head></head><body>
<?php
$sql="UPDATE user SET logged = '0' WHERE id = '$_SESSION[id]'";
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
unset($_SESSION['id']);
echo '登出成功!<br /><a href="index.php">返回首页</a>';
?>
</body>
</html>