<?
$title="Персонаж";
 if ($_GET[mod]=="skills") { 	$skills=unserialize($player[skills]);
 	$tmp=0;
     foreach ($skills as $key=>$value) {
       if ($skills[$key][act]>=20*(pow(2,$skills[$key][level])))
       {$skills[$key][act]=$skills[$key][act]-20*(pow(2,$skills[$key][level]));
$skills[$key][level]++;
$tmp=1;
       }
       elseif ($skills[$key][act]<0) {$skills[$key][act]=0;$tmp=1;}
     }
     if ($tmp==1) {
$tmp=serialize($skills);
$sql=mysql_query("UPDATE users SET skills='$tmp' WHERE id='$player[id]' LIMIT 1");
     }
     if (!isset($skills[dig])) {     	$skills[dig][level]=0;
     	$skills[dig][act]=0;     }
     if (!isset($skills[handfight])) {
     	$skills[handfight][level]=0;
     	$skills[handfight][act]=0;
     }
     if (!isset($skills[coldweapon])) {
     	$skills[coldweapon][level]=0;
     	$skills[coldweapon][act]=0;
     }
     if (!isset($skills[fireweapon])) {
     	$skills[fireweapon][level]=0;
     	$skills[fireweapon][act]=0;
     }
     if (!isset($skills[throwweapon])) {
     	$skills[throwweapon][level]=0;
     	$skills[throwweapon][act]=0;
     }    $page.="<p class='d'><img src='/img/icon/tre.PNG'/> <b>Навыки</b></p>
    <br/>Рукопашный бой  ".$skills[handfight][level]."(".$skills[handfight][act].")
    <br/>Холодное оружие  ".$skills[coldweapon][level]."(".$skills[coldweapon][act].")
    <br/>Стрелковое оружие  ".$skills[fireweapon][level]."(".$skills[fireweapon][act].")
    <br/>Метательное оружие  ".$skills[throwweapon][level]."(".$skills[throwweapon][act].")
    <br/>Торговля  ".$skills[trade][level]."(".$skills[trade][act].")
    <br/>Взлом  ".$skills[hack][level]."(".$skills[hack][act].")
    <br/>Наблюдательность  ".$skills[per][level]."(".$skills[per][act].")
    <br/>Мастер  ".$skills[weap][level]."(".$skills[weap][act].")
    <br/>Медик  ".$skills[armer][level]."(".$skills[armer][act].")
    <br/>Химик  ".$skills[chim][level]."(".$skills[chim][act].")
    <br/>Рудокоп  ".$skills[dig][level]."(".$skills[dig][act].")<br/>
    <br/><a href='./?do=aboutme'>Персонаж</a>";

 }
 elseif ($_GET[mod]=="eff") { 	$page.="<p class='d'><b>Эффекты</b></p>";
 	if (sizeof($player[effects])<1) {$page.="<br/>Эффектов нет<br/>";}
 	else {
     for ($i=0;$i<sizeof($player[effects]);$i++) {       $page.="<br/><b>".$player[effects][$i][name]."</b>";
       $page.="<br/>".$player[effects][$i][info]."";
       $time=$player[effects][$i]["over"];
       $page.="<br/> Действие до ".date("j.m.",$time).(date("Y",$time)+170).date("  G:i",$time);
       $page.="<br/>";     }
     $page.="<br/>";
    }    $page.="<br/><a href='./?do=aboutme'>Персонаж</a>"; }
 elseif ($_GET[mod]=="quests") { 	$player[quests]=unserialize($player[quests]);

 	$page.="<p class='d'><img src='/img/icon/ok.PNG'/> <b>Квесты</b></p>";
 	if (!is_array($player[quests])) {$page.="<br/>Квестов нет<br/>";}
 	else { 	   if (isset($_GET[id])) {
   $sql=mysql_query("SELECT * FROM quests WHERE id='$_GET[id]' LIMIT 1");
   $quest=mysql_fetch_array($sql);
   $quest[info]=unserialize($quest[info]);
   $page.="<br/><b>$quest[name]</b><br/>";
   $page.="<br/>".$quest[info][$player[quests][$_GET[id]][status]]."<br/>";
 	   }
 	   elseif ($_GET[view]=="complete") {  foreach($player[quests] as $key=>$value){     if  ($value[status]=="complete") {
$sql=mysql_query("SELECT name FROM quests WHERE id='$key' LIMIT 1");
$name=mysql_result($sql,0,"name");     	$page.="<br/><a href='./?do=aboutme&amp;mod=quests&amp;id=$key'>$name</a>";     }  } 	   }
 	   elseif ($_GET[view]=="view") {
  foreach($player[quests] as $key=>$value){
     if  ($value[status]!="complete") {
$sql=mysql_query("SELECT name FROM quests WHERE id='$key' LIMIT 1");
$name=mysql_result($sql,0,"name");
     	$page.="<br/><a href='./?do=aboutme&amp;mod=quests&amp;id=$key'>$name</a>";
     }
  }
 	   }
 		if ($_GET[view]!="view") {$page.="<br/><img src='/img/icon/cancel.PNG'/> <a href='./?do=aboutme&amp;mod=quests&amp;view=view'>Текущие квесты</a>";}
if ($_GET[view]!="complete") {$page.="<br/><img src='/img/icon/ok.PNG'/> <a href='./?do=aboutme&amp;mod=quests&amp;view=complete'>Завершенные квесты</a>";}
$page.="<br/>";
}
$page.="<br/><a href='./?do=aboutme'>Персонаж</a>";
}elseif($_GET['mod']=='main'){
$fact_params=unserialize($player[fact_params]);
echo '<a hidden="'.$fact_params['life'].'"></a>';
$playerq=unserialize($player['quests']);	
if($_GET['skillup']=='str'||$_GET['skillup']=='life'||$_GET['skillup']=='endur'||$_GET['skillup']=='int'||$_GET['skillup']=='shooting'||$_GET['skillup']=='speed'||$_GET['skillup']=='dex'||$_GET['skillup']=='luck'){
if($player['study_points']>=1){
$player['study_points']--;
$bp['base_params'][$_GET['skillup']]=1;
$fact_params[$_GET['skillup']]++;
add_user($player,$bp);
mysql_query('update users set study_points='.$player['study_points'].' where id='.$player['id']);
$page.='<p class="d"><b>Обучено</b></p>';
}else{
$page.='<p class="d"><b>Недостаточно очков обучения</b></p>';
}}
$page.="<p class='d'><b>$player[char_name]</b></p>";
if ($player[rights]!=user){
if($player[rights]=="admin") {$page.="<b>Статус</b>: Администратор<br/>";}
if($player[rights]=="moder") {$page.="<b>Статус</b>: Модератор<br/>";}
}else{$page.="";}
$page.="<b>Положение: </b>";
if($playerq[povstanec][status]=="complete") {$page.="Повстанец<br/>";}
if($playerq[boecness][status]=="complete") {$page.="Боец<br/>";}
if($playerq[naemnik][status]=="complete") {$page.="Наёмник<br/>";} 
if(empty($playerq[povstanec]) and empty($playerq[boecness]) and empty($playerq[naemnik])) {$page.="Бродяга<br/>";}
		 if($quest[kontraktpovs][status]=="begin") {$page.="Контракт с повстанцами<br/>";}
 if($quest[kontraktness][status]=="begin") {$page.="Контракт с военными<br/>";}	
    $page.="<br/><img src='/img/icon/p.PNG'/> Уровень $player[level]
    <br/>Боевой опыт $player[exp]/$player[needexp]";
    if ($player[study_points]>0) {$page.="<br/>Очки обучения $player[study_points]";}
    $page.="<br/>Здоровье $player[hit_points]/$player[maxhp]
    <br/>Голод $player[hungry_points]
    <br/>Очки действия $player[od]/$player[maxod]
    <br/>Шанс критического удара $player[crit_chance]";
    $page.="<br/><br/><img src='/img/icon/tre.PNG'/> <b>Характеристики</b>
    <br/>Сила $fact_params[str] ".($player['study_points']>=1?'[<a href="/?do=aboutme&mod=main&skillup=str">+</a>]':'')."
    <br/>Живучесть $fact_params[life] ".($player['study_points']>=1?'[<a href="/?do=aboutme&mod=main&skillup=life">+</a>]':'')."
    <br/>Выносливость $fact_params[endur] ".($player['study_points']>=1?'[<a href="/?do=aboutme&mod=main&skillup=endur">+</a>]':'')."
    <br/>Интеллект $fact_params[int] ".($player['study_points']>=1?'[<a href="/?do=aboutme&mod=main&skillup=int">+</a>]':'')."
    <br/>Меткость $fact_params[shooting] ".($player['study_points']>=1?'[<a href="/?do=aboutme&mod=main&skillup=shooting">+</a>]':'')."
    <br/>Скорость $fact_params[speed] ".($player['study_points']>=1?'[<a href="/?do=aboutme&mod=main&skillup=speed">+</a>]':'')."
    <br/>Ловкость $fact_params[dex] ".($player['study_points']>=1?'[<a href="/?do=aboutme&mod=main&skillup=dex">+</a>]':'')."
    <br/>Удача $fact_params[luck] ".($player['study_points']>=1?'[<a href="/?do=aboutme&mod=main&skillup=luck">+</a>]':'')."";
    $fact_resists=unserialize($player[fact_resists]);
    $page.="<br/><br/><img src='/img/icon/tre.PNG'/> <b>Сопротивления</b>
    <br/>Нормальному урону $fact_resists[resnormal]
    <br/>Плазме $fact_resists[resplazma]
    <br/>Взрывам $fact_resists[resboom]
    <br/>Электричеству $fact_resists[resvolt]
    <br/>Отравлению $fact_resists[respoison]
    <br/>Радиации $fact_resists[resrad]<br/>";
    $trauma=unserialize($player[trauma]);
    if ($trauma[lefthand]=="on") {$page.="<br/><b>Сломана левая рука</b><br/>";}
    if ($trauma[righthand]=="on") {$page.="<br/><b>Сломана правая рука</b><br/>";}
    if ($trauma[leftleg]=="on") {$page.="<br/><b>Сломана левая нога</b><br/>";}
    if ($trauma[rightleg]=="on") {$page.="<br/><b>Сломана правая нога</b><br/>";}
    if ($trauma[eye]=="on") {$page.="<br/><b>Поврежден глаз</b><br/>";}
    $page.="<br/><a href='./?do=aboutme'>Персонаж</a>"; }
 elseif ($_GET[mod]=="style") {       $page.="<p class='d'><img src='/img/icon/cancel.PNG'/><b>Темы оформления</b></p>";
       if (empty($style)){$style=vagabond-left;}
       if (isset($_GET["var"])){  if (!file_exists("./css/$_GET[var].css")){$page.="<br/>Ошибка! Такой темы не существует";}
  else {  	if ($_GET[conf]==ok){
       $player[options][style]=$_GET["var"];
       $sql=mysql_query("UPDATE users SET options='".serialize($player[options])."' WHERE id='$player[id]' LIMIT 1");       $style=$_GET["var"];
       $page.="<br/>Вы выбрали тему оформления <b>$style</b>. Вы можете выбрать другую тему, если эта вам не нравится:";  	}
  	else{  	  $page.="<br/>Ваша текущая тема оформления - <b>$style</b>. Это тема оформления <b>$_GET[var]</b>.";
  	  $page.="<br/>Чтобы выбрать ее нажмите <a href='./?do=aboutme&amp;mod=style&amp;var=$_GET[var]&amp;conf=ok'>ok</a>";      $page.="<br/>Или выберите другую тему, если эта вам не нравится:";
    }
    $style=$_GET["var"];  }       }
       else{$page.="<br/>Ваша текущая тема оформления - $style. Выберите новую тему оформления:";}
       $dir = opendir ("./css");
       while ($file = readdir ($dir))
       {
$tmp= pathinfo($file, PATHINFO_FILENAME);
if (!empty($tmp) and $tmp!="." and $tmp!=".."){$page.= "<br/><a href='./?do=aboutme&amp;mod=style&amp;var=$tmp'>$tmp</a>";}
       }
       $page.="<br/>";
       closedir ($dir);
 }
 else {
    $page.="<p class='d'><b>Главное меню</b></p>";
	$page.="<br/><img src='/img/icon/tre.PNG'/> <a href='./?do=aboutme&amp;mod=main'>Характеристики</a>";
	$page.="<br/><img src='/img/icon/tre.PNG'/> <a href='./?do=aboutme&amp;mod=skills'>Навыки</a>";
	$page.="<br/><img src='/img/icon/crown.PNG'/> <a href='./?view=player&amp;stat=$player[id]'>Статистика</a>";
	$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;view=eqip'>Экипировка</a>";
	$page.="<br/><img src='/img/icon/util.PNG'/> <a href='./?do=craft'>Крафт</a>";
	$page.="<br/><img src='/img/icon/ok.PNG'/> <a href='./?do=aboutme&amp;mod=quests'>Квесты</a>";
	if (!empty($player[effects])) $page.="<br/><img src='/img/icon/tre.PNG'/> <a href='./?do=aboutme&amp;mod=eff'>Эффекты</a>";
	$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv'>Инвентарь</a>";
	$page.="<br/><img src='/img/icon/cancel.PNG'/> <a href='./?do=aboutme&amp;mod=style'>Темы оформления</a>";
	$page.="<br/><img src='/img/icon/cancel.PNG'/> <a href='./?do=option'>Персональные настройки</a><br/>";
	$page.="<br/><img src='/img/icon/p.PNG'/> <a href='./news.php?str=1'>Новости</a>";
	$page.="<br/><img src='/img/icon/wat.PNG'/> <a href='./help.php?help=main'>Помощь</a>";
	$page.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?do=online'>Кто онлайн?</a>";
	$page.="<br/><img src='/img/icon/crown.PNG'/> <a href='./?do=top'>Топ игроков</a><br/>";
 }
$page.="<br/><a href='./?rand=".rand(1,1000)."'>В игру</a><br/><br/>";