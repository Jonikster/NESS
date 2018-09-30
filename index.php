<?ob_start('ob_gzhandler');
$page='';
//ini_set('display_errors',0);
include 'lib.php';
if($_GET["do"]=="online" && !isset($_COOKIE[$gamename])){
$tmp=time()-5*60;
$sql=mysql_query("SELECT count(id) FROM users WHERE onlinetime>'$tmp'");
$count=mysql_result($sql,0,0);
$page.="<p class='d'><b>Список онлайн[$count]</b></p>";
if(!isset($_GET['str'])){$str=1;
}else{
$str=intval(htmlspecialchars(stripslashes(trim(mysql_real_escape_string($_GET[str])))));
}
$begin=($str-1)*15;
$sql=mysql_query("SELECT id,char_name,level FROM users  WHERE onlinetime>'$tmp' ORDER BY char_name LIMIT $begin,15");
while($user=mysql_fetch_array($sql)){
$page.='<br>'.$user['char_name'].' ['.$user['level'].']';
}
$page.=nav_page(ceil($count/15),$str,"./?do=online&amp;str=");
$tmp=time()-86400;
$sql=mysql_query("SELECT count(id) FROM users WHERE onlinetime>'$tmp'");
$count=mysql_result($sql,0,0);
if($count>0){
$page.="<br>Засутки: $count";}
$tmp=time()-7*86400;
$sql=mysql_query("SELECT count(id) FROM users WHERE onlinetime>'$tmp'");
$count=mysql_result($sql,0,0);
if($count>0){
$page.="<br>Занеделю: $count";
}
$page.="<p class='d'><b><a href='./'>Наглавную</a></b></p>";
display($page,$title,$style);
ob_end_flush();
}else{
include'gamefiles1704/index.php';
}