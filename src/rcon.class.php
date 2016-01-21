<?php
class rcon {
 var $sock = NULL;
 var $t = 0;

 function login($host,$port,$password) {
  $this->sock = fsockopen($host,$port);
  socket_set_timeout($this->sock,2,0);
  $buffer = chr(strlen($password)+10).chr(0).chr(0).chr(0).chr($this->t).chr(0).chr(0).chr(0).chr(3).chr(0).chr(0).chr(0).$password.chr(0).chr(0);
  fwrite($this->sock,$buffer);
  $this->read_till(chr(10).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(2).chr(0).chr(0).chr(0).chr(0).chr(0));
  $this->t++;
  return $result;
 }

 function read_till($what) {
  $buf = '';
  while (1) {
   $IAC = chr(255);
    
   $DONT = chr(254);
   $DO = chr(253);
    
   $WONT = chr(252);
   $WILL = chr(251);
    
   $theNULL = chr(0);
  
   $c = $this->getc();
    
   if ($c === false) return $buf;
   if ($c == $theNULL) {
    continue;
   }
  
   if ($c == "1") {
    continue;
   }
 
   if ($c != $IAC) {
    $buf .= $c;
   
    if ($what == (substr($buf,strlen($buf)-strlen($what)))) {
     return $buf;
    }
    else {
     continue;
    }
   }
 
   $c = $this->getc();
    
   if ($c == $IAC) {
   $buf .= $c;
   }
   else if (($c == $DO) || ($c == $DONT)) {
    $opt = $this->getc();
    // echo "we wont ".ord($opt)."\n";
    fwrite($this->sock,$IAC.$WONT.$opt);
   }
   elseif (($c == $WILL) || ($c == $WONT)) {
    $opt = $this->getc();
    // echo "we dont ".ord($opt)."\n";
    fwrite($this->sock,$IAC.$DONT.$opt);
   }
   else {
    // echo "where are we? c=".ord($c)."\n";
   }
  }
 }

 function logout() {
  if ($this->sock)  fclose($this->sock);
  $this->sock = NULL;
 }

 function getc() {
  return fgetc($this->sock); 
 }

 function run($command){
  $str='';
  $num =0 ;
  $h=0;
  $buffer = 
  chr(strlen($command)+10).chr(0).chr(0).chr(0).chr($this->t).chr(0).chr(0).chr(0).chr(2).chr(0).chr(0).chr(0).$command.chr(0).chr(0);
  fwrite($this->sock,$buffer);
  while($h==0){
	  $h=ord(fgetc($this->sock));
  }
  //echo $h;
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
fgetc($this->sock);
  while($num<$h-10){
	  $str=$str.fgetc($this->sock);
	  $num++;
  }
  $this->t++;
  return $str;
 }
  
 
}
$con=new rcon;
//$con->login('localhost',25575,'12345678');
//echo $con->run('whitelist reload');
//$con->logout();
 
?>