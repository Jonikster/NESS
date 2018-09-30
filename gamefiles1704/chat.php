<?
$player['bag']=unserialize($player['bag']);
if(have_item($player['bag'],156)>=1){
$player['quests']=unserialize($player['quests']);
if($player['rights']=='admin' or $player['rights']=='moder') {$superuser=1;}
$page.='<p class="d"><b>Радиосвязь</b></p>';
function str_replace2($s1,$s2,$s3){
global $player;
$s=3;
if($player['quests']['povstanec']['status']=='complete') {$s=1;}
if($player['quests']['boecness']['status']=='complete') {$s='';}
if($player['quests']['naemnik']['status']=='complete') {$s=2;}
if(file_exists('/home/u938174209/public_html/img/sml/'.$s2.$s.'_'.($player['options']['pol']=='j'?'j':'m').'.png')){
$img='<img src="/img/sml/'.$s2.$s.'_'.($player['options']['pol']=='j'?'j':'m').'.png">';
}elseif(file_exists('/home/u938174209/public_html/img/sml/'.$s2.$s.'.png')){
$img='<img src="/img/sml/'.$s2.$s.'.png">';
}else{
$img=file_exists('/home/u938174209/public_html/img/sml/'.$s2.'.png')?'<img src="/img/sml/'.$s2.'.png">':'[смайлненайден]';
}
return str_replace($s1,$img,$s3);
}
function sms($text)
{
$text=$player[rights]=='admin'?$text:htmlspecialchars($text);
$text=str_replace2(':-)','smile',$text);
$text=str_replace2(':)','smile',$text);
$text=str_replace2(';-)','mig',$text);
$text=str_replace2(';)','mig',$text);
$text=str_replace2(':-(','smil',$text);
$text=str_replace2(':(','smil',$text);
$text=str_replace2(':-P','be',$text);
$text=str_replace2(':P','be',$text);
$text=str_replace2('В)','cool',$text);
$text=str_replace2('8)','cool',$text);
$text=str_replace2(':D','lol',$text);
$text=str_replace2(':-D','lol',$text);
$text=str_replace2(':[','ee',$text);
$text=str_replace2(':-[','ee',$text);
$text=str_replace2('O_o','boogle',$text);
$text=str_replace2('o_O','boogle',$text);
$text=str_replace2('.cry.','cry',$text);
$text=str_replace2('.boom.','boom',$text);
$text=str_replace2('.boks.','boks',$text);
$text=str_replace2('.avtomat.','avtomat',$text);
$text=str_replace2('.cho.','cho',$text);
$text=str_replace2('.tank.','tank',$text);
$text=str_replace2('.zloy.','zloy',$text);
$text=str_replace2('.grena.','granata',$text);
$text=str_replace2('.dead.','dead',$text);
$text=str_replace2('.bebe.','bebe',$text);
$text=str_replace2('.dbebe.','dbebe',$text);
$text=str_replace2('.boroda.','boroda',$text);
$text=str_replace2('.dee.','dee',$text);
$text=str_replace2('.dsmile.','dsmile',$text);
$text=str_replace2('.dum.','dum',$text);
$text=str_replace2('.faer.','faer',$text);
$text=str_replace2('.gotov.','gotov',$text);
$text=str_replace2('.ha.','ha',$text);
$text=str_replace2('.hm.','hm',$text);
$text=str_replace2('.oficer.','oficer',$text);
$text=str_replace2('.sigara.','sigara',$text);
$text=str_replace2('.smert.','smert',$text);
$text=str_replace2('.cenz.','cenz',$text);
$text=str_replace2('.alkash.','alkash',$text);
$text=str_replace2('.chmok.','chmok',$text);
$text=str_replace2('.dlove.','dlove',$text);
$text=str_replace2('.fingal.','fingal',$text);
$text=str_replace2('.hell.','hell',$text);
$text=str_replace2('.love.','love',$text);
$text=str_replace2('.mig.','mig',$text);
$text=str_replace2('.roza.','roza',$text);
$text=str_replace2('.mob.','monster',$text);
$text=str_replace2('.map.','map',$text);
$text=str_replace('.grena2.','<img border="0" src="/img/sml/granata.gif" />',$text);
$text=str_replace('.mir.','<img border="0" src="/img/sml/mir.gif" />',$text);
$text=str_replace('.mir2.','<img border="0" src="/img/sml/troe.gif" />',$text);
$text=str_replace('"',"'",$text);
return $text;
}
settype($_GET['p'],'int');
if($_GET['p']<1){
$p=1;
}else{
$p=$_GET['p'];
}
$page.='<br><a href="/?do=chat&p='.$p.'&r='.rand(1,100).'">Обновить</a><br>';// <-- не трогай тут кавычки =_= я всё вижу
if(!empty($_POST['sms'])){
if($player['rights']!='user'&&$player['rights']!='admin'&&$player['rights']!='moder'){
$page.='Забаненые игроки не могут писать<br>';
}else{
if(mysql_query('insert into chat values(null,"'.$player['char_name'].'",'.date('U').',"'.(sms($_POST['sms'])).'",0)')){
$page.='Сообщение добавлено!<br>';
}else{
$page.='Ошибка!<Br>';
}
}
}
if (isset($_GET['writeto']))
{
$page.='<div class="d">';
$sql=mysql_query('SELECT id,status,rights FROM users where char_name="'.$_GET[writeto].'" LIMIT 1');
$user=mysql_fetch_array($sql);
if(isset($_GET['ban'])){
if(!($superuser)){$page.='Ты это читаешь? Да ты хакер! Прошу покинуть эту страницу и сообщить администрации то, как ты сюда попал<br>';
}else{
if(mysql_num_rows($sql)<1){$page.=$_GET['writeto'].' не найден!<br>';
}elseif($user['rights']!='user'){$page.=$_GET['writeto'].' нельзя забанить!<br>';
}else{
$user['status']=unserialize($user['status']);
if($_GET['ban']=='week'){$timeban=24*60*60;}
elseif($_GET[ban]=="3day"){$timeban=2*60*60;
}else{$timeban=15*60;}
$user['status']['timeban']=time()+$timeban;
$user['status']=serialize($user['status']);
$sql=mysql_query('UPDATE users SET status="'.$user['status'].'",rights="userban WHERE id='.$user['id'].' LIMIT 1');
$page.=$_GET['writeto'].' забанен!<br>';
}}}else{
if($superuser){
$page.='<img src="/img/icon/cancel.PNG"><a href="/?do=chat&writeto='.$_GET['writeto'].'&ban=day">Бан 15 минут</a><br>';
$page.='<img src="/img/icon/cancel.PNG"><a href="/?do=chat&writeto='.$_GET['writeto'].'&ban=3day">Бан 2 часа</a><br>';
$page.='<img src="/img/icon/cancel.PNG"><a href="/?do=chat&writeto='.$_GET['writeto'].'&ban=week">Бан сутки</a><br>';
}}
$page.='<img src="/img/icon/i.PNG"><a href="/?view=player&amp;about='.$user['id'].'">Информация</a><br>';
$page.='<img src="/img/icon/mail2.PNG"><a href="/?do=mail&act=write&user='.$_GET['writeto'].'">Письмо</a></div>';
}
settype($_GET['del'],'int');
if($_GET['del']>=1){
if(mysql_query('update chat set del=1 where id='.$_GET['del'])){
$page.='Удалено!<br>';
}else{
$page.='Ошибка!<br>';
}}
if($player['rights']=='userban'){
$page.='Забаненые игроки не могут писать<br>';
}else{
$page.='<form method="post"><input name="sms" value="'.$_GET['writeto'].'"><br><input type="submit" value="Отправить">';
}
$q=mysql_query('select * from chat where del<>1 order by id desc limit '.(10*($p-1)).',10');
while($post=mysql_fetch_array($q)){
$page.= '<br><a href="?do=chat&p='.$p.'&writeto='.$post['user'].'">'.$post['user'].'</a>, '.date2('j.m.',($post['date']+8*3600)).date(' H:i	',($post['date']+8*3600)).($superuser==1?' [<a href="?do=chat&p='.$p.'&del='.$post['id'].'">x</a>]':'').'<br>'.$post['sms'];
}
$c=mysql_fetch_array(mysql_query('select count(*) from chat where del<>1'));
$page.=nav_page(ceil($c['count(*)']/10),$p,'/?do=chat&p=');
}else{
$page.='<p class="d"><b>Радиосвязь</b></p><br/>';
$page.='Связь недоступна! Для пользованием радиосвязей требуется рация<br/>';
}
$page.='<br><a href="/">В игру</a></p>';