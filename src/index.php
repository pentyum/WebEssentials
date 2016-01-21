<?php include "global.func.php" ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pentium Minecraft 服务器登录系统 0.5 alpha</title>
</head>
<body>
<?php
	if(empty($_SESSION['id'])){
?>
<h2>Pentium Minecraft 服务器登录系统 0.5 alpha</h2><br />
<form action="login.php" method="post">
用户名:<input type="text" name="name" /><br />
密码:<input type="password" name="password" /><br />
<input type="submit" value="登录" />
</form>
<hr />
【alpha更新记录】<br />
0.7(计划):增加home和sethome以及back功能<br />
0.6(计划):增加tpahere功能<br />
0.5:增加同步玩家在线列表功能，优化代码<br />
0.4:增加tpa功能<br />
0.3:优化代码<br />
0.2:改进rcon功能<br />
0.1:增加登录功能
<?php
	}else{
		if($_GET['action']=='changepassword'){
			$db->inject_check($_POST['password']);
			$db->query("UPDATE user SET password='$_POST[password]' WHERE id='$my[id]'");
			$my['password']=$_POST['password'];
		}elseif($_GET['action']=='tpa'){
			$db->inject_check($_GET['player']);
			if(empty($_GET['player'])){
				echo '未定义玩家!<br />';
			}elseif(!is_online($_GET['player'])){
				echo '该玩家不在线!<br />';
			}else{
				$tname=getname($_GET['player']);
				showmsg($my['name'],'请求已发送至 '.$tname);
				showmsg($tname,$my['name'].' 想要传送到你这里来,请前往网页端接受请求.');
				$db->query("UPDATE user SET tpid='$_GET[player]' WHERE id='$my[id]'");
			}
		}elseif($_GET['action']=='tpaccept'){
			$q=$db->query("SELECT * FROM user WHERE tpid='$my[id]'");
			$tplayer=$db->fetch_array($q);
			if(empty($tplayer['id'])){
				echo '没有玩家向你发出过请求!<br />';
			}elseif(!is_online($tplayer['name'])){
				echo '请求已失效!<br />';
			}else{
				showmsg($my['name'],'成功接受请求.');
				showmsg($tplayer['name'],'对方已接受你的传送请求.');
				$db->query("UPDATE user SET tpid='0' WHERE tpid='$my[id]'");
				tp($tplayer['name'],$my['name']);
			}
		}
		echo '你的id是: '.$my['id'].'<br />';
		echo '你的用户名是: '.$my['name'].'<br />';
		echo '你的UUID是: '.$my['uuid'].'<br /><form method="post" action="index.php?action=changepassword">';
		echo '修改密码: <input type="text" value="'.$my['password'].'" name="password" /><br /><input type="submit" value="修改" /></form>';
		echo '<hr />【功能】<br />';
		echo '<br />传送(tpa):';
		$q=$db->query("SELECT id,name FROM user WHERE logged='1'");
		while($list=$db->fetch_array($q)){
			echo ' <a href="index.php?action=tpa&player='.$list['id'].'">'.$list['name'].'</a>';
		}
		echo '<br />传送至此(tpahere):';
		$q=$db->query("SELECT id,name FROM user WHERE logged='1'");
		while($list=$db->fetch_array($q)){
			echo ' <a href="index.php?action=tpahere&player='.$list['id'].'">'.$list['name'].'</a>';
		}
		echo '<br /><a href="index.php?action=tpaccept">接受传送(tpaccept)</a> <a href="index.php?action=tpdeny">拒绝传送(tpdeny)</a>';
		echo '<hr /><a href="logout.php">登出</a>';
	}
?>
<p><a href="http://www.miitbeian.gov.cn">皖ICP备15024293号</a></p>
</body>
</html>