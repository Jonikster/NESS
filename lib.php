<?
$serverdb='mysql.hostinger.ru';
$userdb='u938174209_game';
$passdb='qazwsxedc12';
$namedb='u938174209_game';
$gamename='GLOR';
$filesfolder='gamefiles1704';
$link=mysql_connect($serverdb,$userdb,$passdb);
mysql_select_db($namedb);
//error_reporting(0);//error_levl=6135
function opendb() { // Open database connection.

  $link = mysql_connect($serverdb, $userdb, $passdb) or die(mysql_error());
  mysql_select_db($namedb) or die(mysql_error());
  return $link;
}

function base_params($player){$player[base_params]=unserialize($player[base_params]); return $player;}
function fact_params($player){$player[fact_params]=unserialize($player[fact_params]); return $player; }
function skills($player){$player[skills]=unserialize($player[skills]); return $player; }

function display($page,$title,$style){
if (!isset($style)) {$style = 'vagabond';}
//Если браузер Internet Explorer или Mozilla Firefox то тип страниц определяется как текст html, иначе в этих браузерах страница не будет загружатся на экран а предлогается сохранить
 if(strtok(getenv("HTTP_USER_AGENT"),"/")=="Mozilla")
 {
 header("Content-type: text/html; charset=utf-8");
 }
   //Иначе если Опера или любой мобильный браузер то страница определяется как XHTML приложение (страница)
 else
 {
 header("Content-type: text/html; charset=utf-8");
 }
  //блок любого возможного кеша страниц в браузер

Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом
Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
Header("Pragma: no-cache"); // HTTP/1.1
Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
echo utf_badstrip('<?xml version="1.0" encoding="utf-8"?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru"><head><title>N.E.S.S</title><link rel="stylesheet" media="all" type="text/css" href="/css/'.$style.'.css"></head>
<body><div class="f">'.$page.'</div></body></html>');

}

function about_item($item) //Возвращает информацию о вещи
{  $page.="<br/><b>$item[name]</b>
<br/><img src='/img/img/weapon.PNG'/>
<br/>$item[info]<br/>";
$about_item=$item[about_item];
if ($item[type]=="weapon")
 { $page.="<br/><img src='/img/icon/p.PNG'/> Требуемый уровень: $about_item[req_level]";
  if ($about_item[type_weap]=="fire" and $about_item[maxpatrons]>0) {
   $page.="<br/><img src='/img/icon/patron.PNG'/> Калибр: $about_item[calibr]
   <br/><img src='/img/icon/patron.PNG'/> Обойма: $about_item[patrons]/$about_item[maxpatrons]";
   if (!empty($about_item[idpatrons]) and $about_item[patrons]>0) {
$sql=mysql_query("SELECT name FROM items WHERE id='$about_item[idpatrons]' LIMIT 1");
$patronname=mysql_result($sql,0,"name");
$page.="<br/><img src='/img/icon/patron.PNG'/> Заряжено: $patronname";
   }
   if ($about_item[arrayfire]>0) {$page.="<br/><img src='/img/icon/patron.PNG'/> Очередь: $about_item[arrayfire] патрон";}
  }
  elseif ($about_item[type_weap]=="throw") {$page.="<br/><img src='/img/icon/gun.PNG'/> Тип: Метательное";}
  elseif ($about_item[type_weap]=="mellee") {$page.="<br/><img src='/img/icon/gun.PNG'/> Тип: ближнего боя";}
  if ($about_item[type_dmg]=="normal") {$type="Нормальный";}
  elseif ($about_item[type_dmg]=="plazma") {$type="Плазма";}
  elseif ($about_item[type_dmg]=="boom") {$type="Взрыв";}
  elseif ($about_item[type_dmg]=="volt") {$type="Электричество";}
  $page.="<br/><img src='/img/icon/gun.PNG'/> Тип урона: $type
  <br/><img src='/img/icon/gun.PNG'/> Урон: $about_item[mindmg]-$about_item[maxdmg]";
  if ($about_item[odbonus]!=0) {$page.="<br/><img src='/img/icon/move.PNG'/> Од на выстрел: ".(3+$about_item[odbonus]);}
  if ($about_item[sniperbonus]!=0) {$page.="<br/><img src='/img/icon/cel.PNG'/> Дополнительный шанс попасть: $about_item[sniperbonus]%";}
  if ($about_item[crash]!=0) {$page.="<br/><img src='/img/icon/cancel.PNG'/> Шанс осечки: $about_item[crash]%";}

   }
   elseif ($item[type]=="bodyarm"){
  $page.="<br/><img src='/img/icon/p.PNG'/> Требуемый уровень: $about_item[req_level]
  <br/><img src='/img/icon/tre.PNG'/> <b>Сопротивления:</b>";
  if ($about_item[resnormal]>0) {$page.="<br/>Нормальному урону: $about_item[resnormal]%";}
  if ($about_item[resplazma]>0) {$page.="<br/>Плазме: $about_item[resplazma]%";}
  if ($about_item[resboom]>0) {$page.="<br/>Взрывам: $about_item[resboom]%";}
  if ($about_item[resvolt]>0) {$page.="<br/>Электричеству: $about_item[resvolt]%";}
  if ($about_item[respoison]>0) {$page.="<br/>Отравлению: $about_item[respoison]%";}
  if ($about_item[resrad]>0) {$page.="<br/>Радиации: $about_item[resrad]%";}
  if ($about_item[bonusdex]>0) {$page.="<br/><img src='/img/icon/tre.PNG'/> Доп. шанс уворота: $about_item[bonusdex]%";}
  if ($about_item[kb]>0) {$page.="<br/><img src='/img/icon/noicon.PNG'/> Коэффициент брони: $about_item[kb]";}
   }
   elseif ($item[type]=="patron"){
  $page.="<br/><img src='/img/icon/patron.PNG'/> Калибр: $about_item[calibr]";
  if ($about_item[moddmg]!=0) {$page.="<br/><img src='/img/icon/gun.PNG'/> Плюс к урону: $about_item[moddmg]";}
   }
   elseif ($item[type]=="medicament"){
  if ($about_item[param]=="hungry") {
if ($about_item[type]=="const")
  {$page.="<br/><img src='/img/icon/yad.PNG'/> Уталяет голод на $about_item[value] единиц";}
  elseif ($about_item[type]=="procent")
  {$page.="<br/><img src='/img/icon/yad.PNG'/> Уталяет голод на $about_item[value]%";}
  }
  if ($about_item[param]=="rad") {
if ($about_item[type]=="const")
  {$page.="<br/><img src='/img/icon/yad.PNG'/> Выводит $about_item[value] единиц радиации из организма";}
  elseif ($about_item[type]=="procent")
  {$page.="<br/><img src='/img/icon/yad.PNG'/> Выводит $about_item[value]% радиации из организма";}
  }
  if ($about_item[param]=="poison") {
if ($about_item[type]=="const")
  {$page.="<br/><img src='/img/icon/yad.PNG'/> Выводит $about_item[value] единиц ядов из организма";}
  elseif ($about_item[type]=="procent")
  {$page.="<br/><img src='/img/icon/yad.PNG'/> Выводит $about_item[value]% ядов из организма";}
  }
  elseif ($about_item[param]=="hit_points") {
if ($about_item[type]=="const")
  {$page.="<br/><img src='/img/icon/heal.PNG'/> Повышает здоровье на $about_item[value] единиц";}
  elseif ($about_item[type]=="procent")
  {$page.="<br/><img src='/img/icon/heal.PNG'/> Повышает здоровье на $about_item[value]%";}
  elseif ($about_item[type]=="full")
  {$page.="<br/><img src='/img/icon/heal.PNG'/> Здоровье становится больше максимума на $about_item[value]%";}
  }
  elseif ($about_item[param]=="effect"){
   $eff=$about_item[value];
   $sql=mysql_query("SELECT info FROM effects WHERE effid='$eff' LIMIT 1");
   $eff=mysql_result($sql,0,"info");
   $page.="<br/>".$eff;
  }

   }
   $page.="<br/><img src='/img/icon/kredit.PNG'/> Базовая цена: $about_item[cena]";
 if ($about_item[massa]!=0) {$page.="<br/><img src='/img/icon/kg.PNG'/> Вес: $about_item[massa]<br/>";}
 return $page;
}

function about_user($id)
{ global $right_admin;
$sql=mysql_query("SELECT id,char_name,level,rights,exp,quests,needexp,hit_points,maxhp,hungry_points,od,options,maxod,crit_chance,fact_params,fact_resists,trauma,weapon1,weapon2,bodyarm,money,onlinetime FROM users WHERE id='$id' LIMIT 1");
$sql2=mysql_query('select * from admininfo where user='.$id);
$player=mysql_fetch_array($sql);
$player['admininfo']=mysql_fetch_array($sql2);
$weapon1=unserialize($player[weapon1]);
$weapon2=unserialize($player[weapon2]);
$bodyarm=unserialize($player[bodyarm]);
$player['options']=unserialize($player['options']);
if ($player[onlinetime]>(time()-5*60)) {$online="<br/>Онлайн ";}
else {$online="<br/>Последний заход был ".date("j.m.",$player[onlinetime]).(date("Y",$player[onlinetime])+170).date(" G:i",$player[onlinetime]);}
if($player['options']['pol']!='m'&&$player['options']['pol']!='j')$player['options']['pol']='m';
$pol='<img src="/img/icon/'.$player['options']['pol'].'.PNG">';
$page="<br/><b>$player[char_name] $pol </b>$online".($right_admin?' [<a href="/?view=player&about='.$player['id'].'&inf=adm">и</a>]':'').'<br>';
$quest=unserialize($player[quests]);
 if($quest[povstanec][status]=="complete") {$page.="<img src='/img/img/povstanec.PNG'/> <br/>";}
 if($quest[boecness][status]=="complete") {$page.="<img src='/img/img/boecness.PNG'/> <br/>";}
 if($quest[naemnik][status]=="complete") {$page.="<img src='/img/img/naemnik.PNG'/> <br/>";}
 if(empty($quest[povstanec]) and empty($quest[boecness]) and empty($quest[naemnik])) {$page.=" ";}
if ($player[rights]!=user){
if($player[rights]=="admin") {$page.="<b>Статус</b>: Администратор<br/>";}
  if($player[rights]=="moder") {$page.="<b>Статус</b>: Модератор<br/>";}
 }
$page.="<b>Положение: </b>";
$quest=unserialize($player[quests]);
 if($quest[povstanec][status]=="complete") {$page.="Повстанец<br/>";}
 if($quest[boecness][status]=="complete") {$page.="Боец<br/>";}
 if($quest[naemnik][status]=="complete") {$page.="Наёмник<br/>";}
 if(empty($quest[povstanec]) and empty($quest[boecness]) and empty($quest[naemnik])) {$page.="Бродяга<br/>";}
 if($quest[kontraktpovs][status]=="begin") {$page.="Контракт с повстанцами<br/>";}
 if($quest[kontraktness][status]=="begin") {$page.="Контракт с военными<br/>";}
$page.="<br/><img src='/img/icon/kredit.PNG'/> Кредитов: $player[money]
<br/><img src='/img/icon/gun.PNG'/> Осн.оружие: $weapon1[name]
<br/><img src='/img/icon/gun.PNG'/> Вторичное: $weapon2[name]
<br/><img src='/img/icon/shield.PNG'/> Броня: $bodyarm[name]<br/>
  <br/><img src='/img/icon/p.PNG'/> Уровень $player[level]
  <br/>Боевой опыт $player[exp]/$player[needexp]";
  $page.="<br/>Здоровье $player[hit_points]/$player[maxhp]
  <br/>Голод $player[hungry_points]
  <br/>Очки действия $player[od]/$player[maxod]
  <br/>Шанс критического удара $player[crit_chance]";
  $fact_params=unserialize($player[fact_params]);
  $page.="<br/><img src='/img/icon/crown.PNG'/> <a href='./?view=player&amp;stat=$id'>Статистика</a>
  <br/><br/><img src='/img/icon/tre.PNG'/> <b>Характеристики</b>
  <br/>Сила $fact_params[str]
  <br/>Живучесть $fact_params[life]
  <br/>Выносливость $fact_params[endur]
  <br/>Интеллект $fact_params[int]
  <br/>Меткость $fact_params[shooting]
  <br/>Скорость $fact_params[speed]
  <br/>Ловкость $fact_params[dex]
  <br/>Удача $fact_params[luck]";
  $fact_resists=unserialize($player[fact_resists]);
  $page.="<br/><br/><img src='/img/icon/tre.PNG'/> <b>Сопротивления</b>
  <br/>Нормальному урону $fact_resists[resnormal]
  <br/>Плазме $fact_resists[resplazma]
  <br/>Взрывам $fact_resists[resboom]
  <br/>Электричеству $fact_resists[resvolt]
  <br/>Отравлению $fact_resists[respoison]
  <br/>Радиации $fact_resists[resrad]";
  $trauma=unserialize($player[trauma]);
  if ($trauma[lefthand]=="on") {$page.="<br/><b>Сломана левая рука</b>";}
  if ($trauma[righthand]=="on") {$page.="<br/><b>Сломана правая рука</b>";}
  if ($trauma[leftleg]=="on") {$page.="<br/><b>Сломана левая нога</b>";}
  if ($trauma[rightleg]=="on") {$page.="<br/><b>Сломана правая нога</b>";}
  if ($trauma[eye]=="on") {$page.="<br/><b>Поврежден глаз</b>";}
  $page.="<br/><img src='/img/icon/mail2.PNG'/> <a href='./?do=mail&amp;mod=write&amp;to=$player[char_name]'>Написать письмо</a><br/>";
  $page.="<br/><a href='./?rand=".rand(1,1000)."'>В игру</a><br/>"; 
return $page;

}

function loot($loc_id,$itemid,$colvo,$proc) //лут то бишь с монстров на землю
{  if ($proc>rand(1,100))
  {
   $sql=mysql_query("SELECT * FROM items WHERE id='$itemid' LIMIT 1");
 $item=mysql_fetch_array($sql);
 $item[colvo]=$colvo;
 $item[about_item]=unserialize($item[about_item]);
 add_to_garbage($loc_id,$item);
  }

}

function add_to_garbage($loc_id,$item) //добавление вещей к мусору
{  $sql = mysql_query("SELECT obj_list FROM locations WHERE loc_id='$loc_id'");
  $obj_list = mysql_result($sql,0,"obj_list");
  $obj_list=unserialize($obj_list);
  $on_enter="if (time()>".(time()+2*24*60*60).") {destroy_garbage($loc_id);}";
  for ($i=0;$i<sizeof($obj_list);$i++){
  if ($obj_list[$i][type]=="garbage"){
 $obj_list[$i][on_enter]=$on_enter;
 $bag=$obj_list[$i][bag];
 for ($k=0;$k<sizeof($bag);$k++){
 if ($bag[$k][id]==$item[id] and $bag[$k][name]==$item[name] and $bag[$k][info]==$item[info]){
   $bag[$k][colvo]=$bag[$k][colvo]+$item[colvo];
$c=1;
   break;
 }
 }
 if ($c!=1) {$c=1;$bag[]=$item;}
 $obj_list[$i][bag]=$bag;
 break;
  }
  }
  if ($c!=1) {//создаем новый мусор)))
$bag[]=$item;
 $obj=array("name"=>"Всякий хлам","info"=>"Под ногами валяется всякого рода хлам, возможно можно найти что то стоищее","type"=>"garbage",
   "on_enter"=>$on_enter,"bag"=>$bag);
   $obj_list[]=$obj;
  }
$obj_list=serialize($obj_list);
$sql=mysql_query("UPDATE locations SET obj_list='$obj_list' WHERE loc_id='$loc_id' LIMIT 1");
}

function destroy_garbage($loc_id) // уничтожение мусора!
{
$sql = mysql_query("SELECT loc_id,obj_list FROM locations WHERE loc_id='$loc_id'");
 $loc= mysql_fetch_array($sql);
 $obj_list = $loc[obj_list];
 $obj_list = unserialize($obj_list);
 for ($i=0;$i<sizeof($obj_list);$i++){
  if ($obj_list[$i][type]=="garbage"){
 $obj_list=delete_element($obj_list,$i);
 break;
  }
 }
 $obj_list=serialize($obj_list);
$sql=mysql_query("UPDATE locations SET obj_list='$obj_list' WHERE loc_id='$loc_id' LIMIT 1");
}

function add_to_inv ($itemid,$colvo,$bag,$userid,$usergruz) //Добавление шмота в инвентарь игрока
  { $sql=mysql_query("SELECT * FROM items WHERE id='$itemid' LIMIT 1");

   if (mysql_num_rows($sql)!=1) {$page.="Предмета с ID $_POST[itemid] не существует!";}
   else {
   $item=mysql_fetch_array($sql);
$item[colvo]=$colvo;
$item[about_item]=unserialize($item[about_item]);

for ($i=0;$i<sizeof($bag);$i++){
 if ($bag[$i][id]==$item[id] and $bag[$i][name]==$item[name] and $bag[$i][info]==$item[info]){
   $bag[$i][colvo]=$bag[$i][colvo]+$colvo;
   $k=$i;
   break;
 }
}
if (!isset($k) and $colvo>0) {$k=sizeof($bag);$bag[]=$item;}

if ($bag[$k][colvo]<=0) {$bag=delete_element($bag,$k);
 $usergruz=$usergruz-$item[about_item][massa]*$bag[$k][colvo];
}
else {
$usergruz=$usergruz+$item[about_item][massa]*$colvo;
}
//$tmp=serialize($bag);
//$sql=mysql_query("UPDATE users SET bag='$tmp',gruz='$usergruz' WHERE id='$userid' LIMIT 1");
}

$return[bag]=$bag;
 $return[gruz]=$usergruz;
 //$return[page]=$page;
 return $return;
  }


function have_item($bag,$itemid) { //возвращает количество вещей в инвентаре
global $id_item_hi;
$sql=mysql_query("SELECT id,name,info FROM items WHERE id='$itemid' LIMIT 1");
   if (mysql_num_rows($sql)==1)
   {
   $item=mysql_fetch_array($sql);
for ($i=0;$i<sizeof($bag);$i++){
 if ($bag[$i][id]==$item[id] and $bag[$i][name]==$item[name] and $bag[$i][info]==$item[info]){
   $colvo=$bag[$i][colvo];
   break; $id_item_hi=$i;
 }
}
}
if (empty($colvo)) {$colvo=0;}
return $colvo;
}

function add_user($user,$temp){
 if (isset($temp[base_params])) {
 if (!is_array($user[base_params])) {$user[base_params]=unserialize($user[base_params]);}
 foreach ($temp[base_params] as $key=>$value) {
if ($key=="life") {$temp[maxhp]=$temp[maxhp]+$value*2*(4+intval(ceil($user[base_params][endur]/2)));}
  $user[base_params][$key]=$user[base_params][$key]+$temp[base_params][$key];
 }
 $tmp=serialize($user[base_params]);
 $set.="base_params='$tmp',";
 $upd=1;
 }
 if (isset($temp[base_resists])) {
 if (!is_array($user[base_resists])) {$user[base_resists]=unserialize($user[base_resists]);}
 foreach ($temp[base_resists] as $key=>$value) {
  $user[base_resists][$key]=$user[base_resists][$key]+$temp[base_resists][$key];
 }
 $tmp=serialize($user[base_resists]);
 $set.="base_resists='$tmp',";
 $upd=1;
 }
 if ($upd==1) {$bodyarm=unserialize($user[bodyarm]);
 $return=calculating($user[effects],$user[base_params],$user[base_resists],$bodyarm) ;
$fact_params=$return[fact_params];
$fact_resists=$return[fact_resists];
$user[maxod]=intval(ceil(4+1/2+$return[fact_params][speed]/2));
$temp[maxod]=0;
$set.="fact_params='".serialize($return[fact_params])."',fact_resists='".serialize($return[fact_resists])."',crit_chance='$return[crit_chance]',";
 }
 if (isset($temp[trauma])) {
 if (!is_array($user[trauma])) {$user[trauma]=unserialize($user[trauma]);}
 foreach ($temp[trauma] as $key=>$value) {
  $user[trauma][$key]=$temp[trauma][$key];
 }
 $tmp=serialize($user[trauma]);
 $set.="trauma='$tmp',";
 }
 if (isset($temp[bag])) {
 if (!is_array($user[bag])) {$user[bag]=unserialize($user[bag]);}
 foreach ($temp[bag] as $key=>$value) {
  $return=add_to_inv ($value[id],$value[colvo],$user[bag],$user[id],$user[gruz]);
  $user[bag]=$return[bag];
  $user[gruz]=$return[gruz];
 }
 $tmp=serialize($user[bag]);
 $set.="bag='$tmp',";
 $set.="gruz='$user[gruz]',";
 }
 if (isset($temp[skills])) {
 if (!is_array($user[skills])) {$user[skills]=unserialize($user[skills]);}
 foreach ($temp[skills] as $key=>$value) {
  $user[skills][$key][act]=$user[skills][$key][act]+$temp[skills][$key][act];
  $user[skills][$key][level]=$user[skills][$key][level]+$temp[skills][$key][level];
 }
 $tmp=serialize($user[skills]);
 $set.="skills='$tmp',";
 }
 if (isset($temp[quests])) {
 if (!is_array($user[quests])) {$user[quests]=unserialize($user[quests]);}
 foreach ($temp[quests] as $key=>$value) {
  $user[quests][$key][status]=$value;
  $user[quests][$key]["time"]=time();
 }
 $tmp=serialize($user[quests]);
 $set.="quests='$tmp',";
 }
 if (isset($temp[recepts])) {
 if (!is_array($user[recepts])) {$user[recepts]=unserialize($user[recepts]);}
 foreach ($temp[recepts] as $key=>$value) {
  $user[recepts][$key]=$value;
 }
 $tmp=serialize($user[recepts]);
 $set.="recepts='$tmp',";
 }
if (is_array($temp)){
 foreach ($temp as $key=>$value) {
  if ($key!="trauma" and $key!="recepts" and $key!="bag" and $key!="quests" and $key!="id" and $key!="skills" and $key!="base_params" and !is_array($user[$key])) {
  $user[$key]=$user[$key]+$temp[$key];
  $set.="$key='$user[$key]',";
  }
 }
}
 if (isset($set)) {

 $set=substr($set, 0, strlen($set)-1);
 $sql=mysql_query("UPDATE users SET $set WHERE id='$user[id]' LIMIT 1");

}

}

function update_inv_obj ($itemid,$colvo,$obj,$time) //обновление инвентаря ящиков
  //$obj_list[$i][bag]=update_inv_obj($itemid,$colvo,$obj_list[$i][bag],$time,$updatetime);
  { $sql=mysql_query("SELECT * FROM items WHERE id='$itemid' LIMIT 1");
if (mysql_num_rows($sql)==1 and($obj[updatetime]<time() or !isset($obj[updatetime]))){
   if (mysql_num_rows($sql)==1)  {
   $item=mysql_fetch_array($sql);
$item[colvo]=$colvo;
$item[about_item]=unserialize($item[about_item]);

 if (is_array($obj[bag])) {
 for ($i=0;$i<sizeof($obj[bag]);$i++){
  if ($obj[bag][$i][id]==$item[id] and $obj[bag][$i][name]==$item[name] and $obj[bag][$i][info]==$item[info]){
   $obj[bag][$i][colvo]=$colvo;
   $k=1;
   break;
  }
 }
}
if ($k!=1) {$obj[bag][]=$item;}
}
if ($time>0) {
$obj[updatetime]=time()+$time;}
}
 return $obj;

  }



function delete_element($massive,$element) //удаляет элемент из массива) функция unset() помоему глючит)
{for ($i=0;$i<sizeof($massive);$i++)
{ if ($i!=$element) {$newmass[]=$massive[$i];}}
return $newmass;
}



function unset_as_mass($massive,$element)
{
 if (!is_array($massive)){$massive=unserialize($massive);}
 foreach($massive as $key=>$value)
{ if ($key!=$element) {$newarr[$key]=$value;}

}
return $newarr;
}

function array_shuffle ($array) {// сортировка в случайном порядке
  while (count($array) > 0) {
$val = array_rand($array);
$new_arr[$val] = $array[$val];
$array=unset_as_mass($array,$val);
  }
  return $new_arr;
}

function distance($x1,$y1,$x2,$y2) //возвращает дистанцию между точками 1 и 2
{  $distance=round(sqrt(($x1-$x2)*($x1-$x2)+($y1-$y2)*($y1-$y2)));
  return $distance;
};

function calculating($effects,$base_params,$base_resists,$bodyarm) //расчет параметров с эффектами
{

  $fact_params=$base_params;
  $fact_resists=array("resnormal"=>2*$base_params[life],"resplazma"=>2*$base_params[life],
  "resboom"=>2*$base_params[life],"resvolt"=>2*$base_params[life],"resrad"=>2*$base_params[life],"respoison"=>2*$base_params[life],);

  if (!empty($bodyarm)){
foreach($fact_resists as $key=>$value)
 {$fact_resists[$key]=$fact_resists[$key]+$bodyarm[about_item][$key];
if ($fact_resists[$key]<0) {$fact_resists[$key]=0;}
 }
}

  for ($i=0;$i<sizeof($effects);$i++)
{ $eff=$effects[$i];
  if (!empty($eff[resists]))
{  foreach($eff[resists] as $key=>$value)
 {$fact_resists[$key]=$fact_resists[$key]+$eff[resists][$key];
if ($fact_resists[$key]<0) {$fact_resists[$key]=0;}
 }
  }
  if (!empty($eff[params])) {
foreach($eff[params] as $key=>$value)
 { $fact_params[$key]=$fact_params[$key]+$eff[params][$key];
if ($fact_params[$key]<0) {$fact_params[$key]=0;}
 }
  }
}
$return[crit_chance]=$fact_params[luck]+$fact_params[shooting];
$return[fact_params]= $fact_params;
$return[fact_resists]= $fact_resists;
return $return;
}

function nav_page(
 $count,  // Общее кол-во страниц
 $num_page, // Номер текущей страницы
 $url// Какой URL для ссылки на страницу (к нему добавляется номер страницы)
 )
 {
 $page=' ';
 $page_nav = 3; // сколько страниц выводить одновременно в навигации
 $page.="<br/>Стр. ($count):";
 if ($num_page>$count or $num_page<1){ $num_page=1;} // Проверка на корректность номера текущей страницы. Если страница больше чем всего есть страниц или страница меньше 1, что невозможно, то принудительно определим страницу №1 в гостевой
 if($num_page>1 && ($num_page-$page_nav)>1) // Если текущая страница больше 1 и текущая страница больше чем на установленное выше значение $page_nav ушла вперед, то есть например текущая страница №7 оперерирует навигацией по 7-3 = 4 и 7+3 = 10, между 4 и 10й страницей то добавляем ссылку на первую страницу
 {
 $page.=" <a href='".$url."1'>&lt;&lt;</a>"; //вот она
 }
 $start_i = ($num_page-$page_nav); //определяем начальную страницу в цикле вывода, как пример 7-3 = 4
 if($start_i<=1){$start_i=1;} //если начальное значение меньше или равно 1 то нам не нужно выводить в цикле несуществующие страницы как 0,-1, -2 ... А потому ограничиваем начальное значение цикла = 1
 $end_i=($num_page+$page_nav); // Такая же процедура с конечным значением в цикле только как указано в нашем примере будет 7+3 = 10
 if($end_i>=$count) {$start_i=($num_page-$page_nav); $end_i=$count;} //И такое же ограничение конечного значения в цикле, где ограничитель определяется общим количеством страниц $count. И если конечное значение в цикле больше или равно этому значению, то ограничиваем его максимально возможным количеством страниц
   for ($i = $start_i; $i <= $end_i; $i++) //Определив начальное и конечное значения цикла выводим сам цикл
 {
 if($i>0)
 {
 if ($i==$num_page) //если страница в цикле = текущей то нам не нужно на нее указывать ссылку, а просто выводим текстом
 $page.=" <b>$i</b>";
 else $page.=" <a href='$url$i'>$i</a>"; //А на все остальные страницы выводим ссылку
 }
 } // закрываем цикл вывода страниц
 if($num_page!=$count && ($num_page+$page_nav)<$count) // Если текущая страница не = максимально возможной $count и меньше чем на количество страниц определянный ограничителем $page_nav = 3; (то есть не показана в цикле выше), мы добавляем ссылку на последнюю страницу $count
 {
 $page.=" <a href='".$url."$count'>&gt;&gt;</a>"; //вот она
 }
 $page.="<br/>";
 return $page;
} // nav_page()
function online()
{
$tmp=time()-5*60;
 $sql=mysql_query("SELECT count(id) FROM users WHERE onlinetime>'$tmp'");
 return mysql_result($sql,0,0);
}

function date2($s,$t){
$s=date($s,$t);
$s=str_replace('.01.',' янв ',$s);
$s=str_replace('.02.',' фев ',$s);
$s=str_replace('.03.',' март ',$s);
$s=str_replace('.04.',' апр ',$s);
$s=str_replace('.05.',' май ',$s);
$s=str_replace('.06.',' июня ',$s);
$s=str_replace('.07.',' июля ',$s);
$s=str_replace('.08.',' авг ',$s);
$s=str_replace('.09.',' сен ',$s);
$s=str_replace('.10.',' окт ',$s);
$s=str_replace('.11.',' ноя ',$s);
$s=str_replace('.12.',' дек ',$s);
return $s;
}

//--------------------- Функция вырезания битых символов UTF -------------------//
function utf_badstrip($str) {
$ret = '';
for ($i = 0;$i < strlen($str);) {
$tmp = $str{$i++};
$ch = ord($tmp);
if ($ch > 0x7F) {
if ($ch < 0xC0) continue;
elseif ($ch < 0xE0) $di = 1;
elseif ($ch < 0xF0) $di = 2;
elseif ($ch < 0xF8) $di = 3;
elseif ($ch < 0xFC) $di = 4;
elseif ($ch < 0xFE) $di = 5;
else continue;

for ($j = 0;$j < $di;$j++) {
$tmp .= $ch = $str{$i + $j};
$ch = ord($ch);
if ($ch < 0x80 || $ch > 0xBF) continue 2;
}
$i += $di;
}
$ret .= $tmp;

}
return $ret;
}