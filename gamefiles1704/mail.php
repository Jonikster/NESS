<?
$player[bag]=unserialize($player[bag]);
if(have_item($player[bag],34)>=1||have_item($player[bag],14)>=1){
settype($_GET['p'],'int');
if($_GET['p']<1) $p=1; else $p=$_GET['p'];
$page.='<p class="d"><b>Почта</b></p><br/>';
if($_GET['act']=='write'||$_GET['mod']=='write')
{
if(!empty($_POST['sms']))
{
if($nick=mysql_fetch_array(mysql_query('select id from users where char_name="'.$_POST['nik'].'"')))
{
if(mysql_query('insert into sms values(null,'.$nick['id'].','.$player['id'].',"'.htmlspecialchars($_POST['sms']).'",'.date('U').',0)'))
{
$page.='Отправлено!<Br>';
}else{
$page.='Ошибка!<br/>';
}}else{
$page.='Пользователь не найден<br/>';
}}else{
$page.='<form method="POST">Кому: <input name="nik" value='.(isset($_GET['to'])?$_GET['to']:$_GET['user']).'><br><textarea name="sms"></textarea><br><input type="submit" value="Отправить"><Br></form>';
}
}else{
if($_GET['act']=='in')
{
if(isset($_GET['id']))
{
settype($_GET['id'],'int');
$q1=mysql_query('select * from sms where id='.$_GET['id'].' and user='.$player['id']);
if(mysql_num_rows($q1)>=1)
{
$sms=mysql_fetch_array($q1);
if($a=mysql_fetch_array(mysql_query('select char_name from users where id='.$sms['ot'])))
{
$ot=$a['char_name'];
}else{
$ot='(Пользователь не найден)';
}
$page.='<a href="/?view=player&about='.$sms['ot'].'">'.$ot.'</a> '.date("j.m.",$sms['date']).(date("Y",$sms['date'])+170).date("  G:i",$sms['date']).'<br/>'.$sms['sms'].'<br>';
if($a['r']==0)
{
mysql_query('update sms set r=1 where id='.$sms['id']);
$nick["mail"]["new"]=0;
$nick["mail"]=serialize($nick["mail"]);
$sql=mysql_query("UPDATE users SET mail='$nick[mail]' WHERE id='$player[id]' LIMIT 1");
}
}else{
$page.='Сообщение не найдено<BR>';
}
$page.='[<a href="?do=mail&act=write&user='.$ot.'">Ответить</a>]';
}else{
$c=mysql_fetch_array(mysql_query('select count(*) from sms where user='.$player['id']));
$q1=mysql_query('select * from sms where user='.$player['id'].' order by id desc limit '.($p*10-10).',10');
$i=0;
while($sms=mysql_fetch_array($q1))
{
if($a=mysql_fetch_array(mysql_query('select char_name from users where id='.$sms['ot'])))
{
$ot=$a['char_name'];
}else{
$ot='(Пользователь не найден)';
}$page.='<a href="?do=mail&act=in&id='.$sms['id'].'">'.$ot.'</a> ['.date("j.m.",$sms['date']).(date("Y",$sms['date'])+170).date("  G:i",$sms['date']).']';if($sms['r']==0){$page.=' - Не прочитано<br>';
}else{
$page.='<br>';
}}
$page.=nav_page(ceil($c['count(*)']/10),$p,'/?do=mail&act=in&p=');
}
}else{
if($_GET['act']=='ex')
{
if(isset($_GET['id']))
{
settype($_GET['id'],'int');
$q1=mysql_query('select * from sms where id='.$_GET['id'].' and ot='.$player['id']);
if(mysql_num_rows($q1)>=1)
{
$sms=mysql_fetch_array($q1);
if($a=mysql_fetch_array(mysql_query('select char_name from users where id='.$sms['user'])))
{
$ot='<a href="/?view=player&about='.$sms['user'].'">'.$a['char_name'].'</a>';
}else{
$ot='(Пользователь не найден)';
}$page.=$ot.' '.date('j.m.',$sms['date']).(date('Y',$sms['date'])+170).date('  G:i',$sms['date']).'<BR>'.$sms['sms'].'<br>';
}else{
$page.='Сообщение не найдено<BR>';
}
}else{
$c=mysql_fetch_array(mysql_query('select count(*) from sms where ot='.$player['id']));
$q1=mysql_query('select * from sms where ot='.$player['id'].' order by id desc limit '.($p*10-10).',10');
$i=0;
while($sms=mysql_fetch_array($q1))
{
if($a=mysql_fetch_array(mysql_query('select char_name from users where id='.$sms['user'])))
{
$ot=$a['char_name'];
}else{
$ot='(Пользователь не найден)';
}
$page.='<a href="?do=mail&act=ex&id='.$sms['id'].'">'.$ot.'</a> ['.date("j.m.",$sms['date']).(date("Y",$sms['date'])+170).date("  G:i",$sms['date']).']';
if($sms['r']==0)
{
$page.=' - Не прочтено<br>';
}else{
$page.='<br>';
}}
$page.=nav_page(ceil($c['count(*)']/10),$p,'/?do=mail&act=ex&p=');
}}else{
$page.="<img src='/img/icon/mail2.PNG'> ";
$page.='<a href="?do=mail&act=in">Входящие</a><br>';
$page.="<img src='/img/icon/mail2.PNG'> ";
$page.='<a href="?do=mail&act=ex">Исходящие</a><br/><br>';
$page.="<img src='/img/icon/mail2.PNG'/> ";
$page.='<a href="?do=mail&act=write">Написать</a><br>';
}}}}else{
$page.="<p class='d'><b>Почта</b></p><br/>";
$page.='Услуга недоступна! Нет возможности отправки и получения сообщений, для получения данных услуг требуется КПК <br/>';
}
$page.='<br/><a href="/">В игру</a><br/>';
$page.="<br/><p class='d'><script type='text/javascript' src='http://mobtop.ru/c/39348.js'></script><noscript><a href='http://mobtop.ru/in/39348'><img src='http://mobtop.ru/39348.gif' alt='MobTop.Ru - Рейтинг и статистика мобильных сайтов'/></a></noscript> <b>".date("j.m.",date('U')+8*3600).(date("Y")+170).date("  G:i",date('U')+8*3600)."</b></p>";