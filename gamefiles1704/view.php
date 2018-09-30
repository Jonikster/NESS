<?
if ($player[rights]=="admin" or $player[rights]=="moder") {$superuser=1;}
if ($_GET['view']=='player') {
if (isset($_GET[id])) {
settype($_GET['id'],'int');
$sql=mysql_query("SELECT id,char_name,rights,hit_points,maxhp,level,quests FROM users WHERE id='$_GET[id]' ");
if(mysql_num_rows($sql)>=1){
$enemy=mysql_fetch_array($sql);
$playerq=unserialize($player[quests]);
if(mysql_num_rows($q_pol=mysql_query('select * from options where id='.$enemy['id']))){
$r=mysql_fetch_array($q_pol);
$pol='<img src="/img/icon/'.$r['pol'].'.PNG">';
}else{
$pol='';
}
$page.="<br/><b>$enemy[char_name] $pol</b><br/>";
$quest=unserialize($player[quests]);
 if($quest[povstanec][status]=="complete") {$page.="<img src='/img/img/povstanec.PNG'/> <br/>";}
 if($quest[boecness][status]=="complete") {$page.="<img src='/img/img/boecness.PNG'/> <br/>";}
 if($quest[naemnik][status]=="complete") {$page.="<img src='/img/img/naemnik.PNG'/> <br/>";}
 if(empty($quest[povstanec]) and empty($quest[boecness]) and empty($quest[naemnik])) {$page.=" ";}
if($enemy[rights]!=user){
if($enemy[rights]=="admin") {$page.="<b>Статус</b>: Администратор<br/>";}
if($enemy[rights]=="moder") {$page.="<b>Статус</b>: Модератор<br/>";}
}
else{$page.="";}
$page.="<b>Положение: </b>";
$quest=unserialize($enemy[quests]);
if($quest[povstanec][status]=="complete") {$page.="Повстанец<br/>";}
if($quest[boecness][status]=="complete") {$page.="Боец<br/>";}
if($quest[naemnik][status]=="complete") {$page.="Наёмник<br/>";}
if(empty($quest[povstanec]) and empty($quest[boecness]) and empty($quest[naemnik])) {$page.="Бродяга<br/>";}
if($quest[kontraktpovs][status]=="begin") {$page.="Контракт с повстанцами<br/>";}
if($quest[kontraktness][status]=="begin") {$page.="Контракт с военными<br/>";}	
$page.="<br/><img src='/img/icon/p.PNG'/> Уровень $enemy[level]";
$page.="<br/><img src='/img/icon/heal.PNG'/> Здоровье $enemy[hit_points]/$enemy[maxhp]";
$page.="<br/><img src='/img/icon/i.PNG'/> <a href='./?view=player&amp;about=$_GET[id]'>Информация</a>";
$page.="<br/><img src='/img/icon/yad.PNG'/> <a href='./?do=use&amp;what=what&amp;target=player&amp;id=$enemy[id]'>Использовать на нем</a>";
if ($location[loc_option][fight]!='no'&&$enemy['level']>=3) {$page.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]'>Напасть</a>";}
$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=give&amp;to=$enemy[id]'>Передать</a>";
$page.="<br/><img src='/img/icon/dia.PNG'/> <a href='./?do=party&amp;invite=$enemy[id]'>Пригласить в пати</a><br/>";
}else{
$page.='<br/>Ошибка. Не верная ссылка<br/>';
}
$page.="<br/><a href='./'>В игру</a><br/>";
}
elseif (isset($_GET[about])) {
settype($_GET['about'],'int');
$sql=mysql_query("SELECT char_name,id FROM users WHERE id='$_GET[about]'");
if($right_admin===true&&$_GET['inf']=='adm'){
$enemy=mysql_fetch_array($sql);
$sql=mysql_query('select * from admininfo where user='.$enemy['id']);
$enemy['admininfo']=mysql_fetch_array($sql);
$page.='<br><b>'.$enemy['char_name'].'</b><br>';
$page.='IP: '.$enemy['admininfo']['ip'].'<br>Клиент: '.$enemy['admininfo']['u'];
$page.='<br>IP регистрации: '.(empty($enemy['admininfo']['ip_reg'])?'Информация отсутствует':$enemy['admininfo']['ip_reg']).'<br>Игроки с таким IP:<br>';
$q=mysql_query('select * from admininfo where ip="'.$enemy['admininfo']['ip'].'"');
$i=1;
while($i<=mysql_num_rows($q)){
$r=mysql_fetch_array($q);
$sql=mysql_query('SELECT char_name,id FROM users WHERE id='.$r['user']);
$enemy=mysql_fetch_array($sql);
$page.='<a href="./?view=player&amp;about='.$enemy['id'].'">'.$enemy['char_name'].'</a><br>';
$i++;
}
}else{
if(mysql_num_rows($sql)>=1){
$page.=about_user($_GET[about]);
}else{
$page.='<br/>Ошибка. Не верная ссылка<br/>';
}
}}
elseif (isset($_GET["stat"])) {
$sql=mysql_query("SELECT status,char_name FROM users WHERE id='$_GET[stat]' LIMIT 1");
$user=mysql_fetch_array($sql);
$user[status]=unserialize($user[status]);
$page.="<br/><b>$user[char_name]</b><br/>";
if ($_GET[win]=="pvp"){
if (!is_array($user[status][rate][win][pvp])){$page.="<br/>Ни одного игрока не побеждено";}
else{
$count=sizeof($user[status][rate][win][pvp])-1;
if (!isset($_GET[str])){$_GET[str]=1;}
$begin=($_GET[str]-1)*15;  $i=1;
foreach($user[status][rate][win][pvp] as $key=>$value)
{
if ($key!="count" and $i>=$begin and $i<($begin+15) and $i<=$count){                     $sql=mysql_query("SELECT char_name FROM users WHERE id='$key' LIMIT 1");
$name=mysql_result($sql,0,"char_name");
$page.="<br/><a href='./?view=player&amp;id=$key'>$name</a> - $value";
$i++;
}
elseif ($i>=($begin+15) or $i>$count) {break;}
}$page.="<br/>";
if ($count>15){$page.=nav_page(ceil($count/15), $_GET[str], "././?view=player&amp;stat=$_GET[stat]&amp;win=pvp&amp;str=");}
}
}
elseif ($_GET[win]=="pve"){
if (!is_array($user[status][rate][win][pve])){$page.="<br/>Ни одного монстра не побеждено";}
else{
$count=sizeof($user[status][rate][win][pve]);
if (!isset($_GET[str])){$_GET[str]=1;}
$begin=($_GET[str]-1)*15;  $i=1;
foreach($user[status][rate][win][pve] as $key=>$value)
{
if ($key!="count" and $i>=$begin and $i<($begin+15) and $i<=$count){
$sql=mysql_query("SELECT name FROM monsters WHERE id='$key' LIMIT 1");
$name=mysql_result($sql,"name");
$page.="<br/>$name - $value";
}
elseif ($i>=($begin+15) or $i>$count) {break;}$i++;
}$page.="<br/>";
if ($count>15){$page.=nav_page(ceil($count/15), $_GET[str], "././?view=player&amp;stat=$_GET[stat]&amp;win=pve&amp;str=");}
}
}
elseif ($_GET[loose]=="pvp"){
if (!is_array($user[status][rate][loose][pvp])){$page.="<br/>Ни одного поражения от игроков";}
else{
$count=sizeof($user[status][rate][loose][pvp])-1;
if (!isset($_GET[str])){$_GET[str]=1;}
$begin=($_GET[str]-1)*15;  $i=1;
foreach($user[status][rate][loose][pvp] as $key=>$value)
{
if ($key!="count" and $i>=$begin and $i<($begin+15) and $i<=$count){
$sql=mysql_query("SELECT char_name FROM users WHERE id='$key' LIMIT 1");
$name=mysql_result($sql,0,"char_name");
$page.="<br/><a href='./?view=player&amp;id=$key'>$name</a> - $value";
$i++;
}
elseif ($i>=($begin+15) or $i>$count) {break;}
}$page.="<br/>";
if ($count>15){$page.=nav_page(ceil($count/15), $_GET[str], "././?view=player&amp;stat=$_GET[stat]&amp;loose=pvp&amp;str=");}
}
}
elseif ($_GET[loose]=="pve"){
if (!is_array($user[status][rate][loose][pve])){$page.="<br/>Ни одного поражения от монстров";}
else{
$count=sizeof($user[status][rate][loose][pve])-1;
if (!isset($_GET[str])){$_GET[str]=1;}
$begin=($_GET[str]-1)*15;  $i=1;
foreach($user[status][rate][loose][pve] as $key=>$value)
{
if ($key!="count" and $i>=$begin and $i<($begin+15) and $i<=$count){                   	if (is_string($key) and !empty($key)){
$sql=mysql_query("SELECT id,name FROM monsters WHERE id='$key' LIMIT 1");
$page.=mysql_error();
$monstr=mysql_fetch_array($sql);
$page.="<br/>$monstr[name] - $value";
$i++;
}
}
elseif ($i>=($begin+15) or $i>$count) {break;}
}$page.="<br/>";
if ($count>15){$page.=nav_page(ceil($count/15), $_GET[str], "././?view=player&amp;stat=$_GET[stat]&amp;loose=pve&amp;str=");}
}
}
else{
if (empty($user[status][rate][win][pve]["count"])){$user[status][rate][win][pve]["count"]=0;}
if (empty($user[status][rate][win][pvp]["count"])){$user[status][rate][win][pvp]["count"]=0;}
if (empty($user[status][rate][loose][pve]["count"])){$user[status][rate][loose][pve]["count"]=0;}
if (empty($user[status][rate][loose][pvp]["count"])){$user[status][rate][loose][pvp]["count"]=0;}
$page.="Побед: ".($user[status][rate][win][pve]["count"]+$user[status][rate][win][pvp]["count"]).". Поражений: ".($user[status][rate][loose][pve]["count"]+$user[status][rate][loose][pvp]["count"]);echo '<a hidden="'.($user[status][rate][win][boecness]).'"></a>';
$page.="<br/>Победы над <a href='./?view=player&amp;stat=$_GET[stat]&amp;win=pve'> монстрами[".$user[status][rate][win][pve]["count"]."]</a>,
<a href='./?view=player&amp;stat=$_GET[stat]&amp;win=pvp'> игроками[".$user[status][rate][win][pvp]["count"]."]</a>";
$page.='<br>Убито военных['.((int)$user[status][rate][win][boecness]).'], повстанцев['.((int)$user[status][rate][win][povstanec]).'], наёмников['.((int)$user[status][rate][win][naemnik]).'] ';echo '<a';
$page.="<br/>Поражений от <a href='./?view=player&amp;stat=$_GET[stat]&amp;loose=pve'> монстров[".$user[status][rate][loose][pve]["count"]."]</a>,
<a href='./?view=player&amp;stat=$_GET[stat]&amp;loose=pvp'> игроков[".$user[status][rate][loose][pvp]["count"]."]</a><br/>";
}
$page.="<br/><a href='".str_replace("&","&amp;",$_SERVER[HTTP_REFERER])."'>Персонаж</a>";
$page.="<br/><a href='./'>В игру</a><br/>";
}
  	else{
    	$tmp=time()-5*60;
  		$sql=mysql_query("SELECT id,char_name,hit_points,status,x,y FROM users WHERE loc_id='$player[loc_id]' AND onlinetime>'$tmp' ORDER BY char_name"); //
    	while ($enemy=mysql_fetch_array($sql)){
           if ($enemy[hit_points]>0 and $enemy[id]!=$player[id]){
                $enemy[status]=unserialize($enemy[status]);

         		$page.="<br/>".$enemy[char_name];
          		$distance=distance($enemy[x],$enemy[y],$player[x],$player[y]);
          		if ($distance<=10) {$page.=" [Вплотную]";}
          		elseif ($distance<=20) {$page.=" [Близко]";}
          		elseif ($distance<=35) {$page.=" [Вблизи]";}
          		elseif ($distance<=50) {$page.=" [Недалеко]";}
          		elseif ($distance<=100) {$page.=" [Далеко]";}
          		else {$page.=" [Очень далеко]";}
          		if ($enemy[status][infight]!="no"){$page.="[В бою]";}
          		$page.=" [<a href='./?view=player&amp;id=$enemy[id]'>инф</a>]";
                $page.=" [<a href='./?do=use&amp;what=what&amp;target=player&amp;id=$enemy[id]'>исп</a>]";
          		if ($location[loc_option][fight] != 'no') {$page.=" [<a href='./?attack=player&amp;id=$enemy[id]'>бить</a>]";}
           }

    	}
    }
  }
  elseif ($_GET['view']=='monstr') {
  	 if (isset($_GET[id])) {
settype($_GET['id'],'int');
  	 	$id=$_GET[id];
if(empty($monstr_list[$id]['name'])){
$page.='<br/>Ошибка. Не верная ссылка<br/>!';//тут тоже не забудь)
}else{
if ($monstr_list[$id][type_dmg]==normal){$dmgtype="Нормальный";}
  	 	elseif ($monstr_list[$id][type_dmg]==plazma){$dmgtype="Плазма";}
  	 	elseif ($monstr_list[$id][type_dmg]==boom){$dmgtype="Взрыв";}
  	 	elseif ($monstr_list[$id][type_dmg]==volt){$dmgtype="Электричество";}
        $page.="<br/><img src='/img/icon/cel.PNG'/><b>".$monstr_list[$id][name]."</b>
           <br/>".$monstr_list[$id][info]."
           <br/><img src='/img/icon/heal.PNG'/> Здоровье: ".$monstr_list[$id][hit_points]."/".$monstr_list[$id][maxhp]."
           <br/><img src='/img/icon/move.PNG'/> Очки действия: ".$monstr_list[$id][od]."/".$monstr_list[$id][maxod]."
           <br/><img src='/img/icon/gun.PNG'/> Урон: ".$monstr_list[$id][damage]."
           <br/><img src='/img/icon/gun.PNG'/> Тип урона: $dmgtype
           <br/><img src='/img/icon/tre.PNG'/> <b>Сопротивления:</b>
           <br/>Нормальному урону: ".$monstr_list[$id][resnormal]."
           <br/>Плазме: ".$monstr_list[$id][resplazma]."
           <br/>Взрывам: ".$monstr_list[$id][resboom]."
           <br/>Электричеству: ".$monstr_list[$id][resvolt]."
           <br/><img src='/img/icon/tre.PNG'/> Бонус на уворот: ".$monstr_list[$id][bonusdex]."%
           <br/><img src='/img/icon/tre.PNG'/> Шанс крита: ".$monstr_list[$id][crit_chance]."%";
           $page.="<br/><img src='/img/icon/yad.PNG'/> <a href='./?do=use&amp;what=what&amp;target=monster&amp;id=$id'>Использовать на нем</a>";
           if ($location[loc_option][fight] != 'no') {$page.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$id'>Атаковать</a><br/>";}
          $page.="<br/><a href='./'>В игру</a><br/>";
}
}else{
      for ($i=0;$i<sizeof($monstr_list);$i++)
      {   if ($monstr_list[$i][status]=="life")
         {$page.="<br/><a href='./?view=monstr&amp;id=$i'>".$monstr_list[$i][name]."</a>";
          $distance=distance($monstr_list[$i][x],$monstr_list[$i][y],$player[x],$player[y]);
          if ($distance<=10) {$page.=" [Вплотную]";}
          elseif ($distance<=20) {$page.=" [Близко]";}
          elseif ($distance<=35) {$page.=" [Вблизи]";}
          elseif ($distance<=50) {$page.=" [Недалеко]";}
          elseif ($distance<=100) {$page.=" [Далеко]";}
          else {$page.=" [Очень далеко]";}
          if (!empty($monstr_list[$i][in_fight])){$page.=" [В бою] ";}
          if ($location[loc_option][fight] != 'no') {$page.=" [<a href='./?attack=monster&amp;id=$i'>бить</a>]";}
         }

      }
     }
  }