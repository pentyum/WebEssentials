<?php
session_start();
include "config.inc.php";
include "mysql.class.php";
include "rcon.class.php";

$sql="SELECT * FROM user WHERE id='$_SESSION[id]'";
$q=$db->query($sql);
$my=$db->fetch_array($q);

function getonlinelist(){
	global $con,$rconhost,$rconport,$rconpw;
	$list=array();
	$con->login($rconhost,$rconport,$rconpw);
	$string = $con->run("list");
	$con->logout();
	$string=strstr($string,':');
	$string=ltrim($string,':');
	$list=explode(', ',$string);
	return $list;
}
function is_online($playername){
	return true;
}
function showmsg($playername,$msg){
	global $con,$rconhost,$rconport,$rconpw;
	$con->login($rconhost,$rconport,$rconpw);
	echo $con->run('tellraw '.$playername.' {"text":"[服务器消息] '.$msg.'"}');
	$con->logout();
	return 1;
}
function tp($player1,$player2){
	global $con,$rconhost,$rconport,$rconpw;
	$con->login($rconhost,$rconport,$rconpw);
	$con->run('tp '.$player1.' '.$player2);
	$con->logout();
	return 1;
}
function sethome($playername){
	return 1;
}
function getname($id){
	global $db;
	$q=$db->query("SELECT name FROM user WHERE id='$id'");
	$f=$db->fetch_array($q);
	return $f['name'];
}
function make_whitelist(){
	global $db;
	$str=array();
	$i=0;
	$sql="SELECT * FROM user WHERE logged = '1'";
	$q=$db->query($sql);
	while($online=$db->fetch_array($q)){
		$str[$i]="\n  {\n    \"uuid\": \"".$online['uuid']."\",\n    \"name\": \"".$online['name']."\"\n  }";
		$i++;
	}
	$text=implode(",",$str);
	$whitelistfile=fopen("whitelist.json","w");
	fwrite($whitelistfile,'['.$text."\n".']');
	fclose($whitelistfile);
}
function onlinelist_sync(){
	global $db;
	$list=getonlinelist();
	$num=count($list);
	$i=0;
	while($i<$num){
		$list[$i]="name='".$list[$i]."'";
		$i++;
	}
	$str=implode(' OR ',$list);
	$sql="UPDATE user SET logged='0'";
	$db->query($sql);
	$sql="UPDATE user SET logged='1' WHERE ($str)";
	$db->query($sql);
}
?>