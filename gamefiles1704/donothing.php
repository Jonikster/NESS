<?
  $bann="YUO ARE BANNED!!";
  if ($player[rights]=="userban"){die($bann);}
 $sql = mysql_query("SELECT * FROM locations WHERE loc_id='$player[loc_id]'");
 $location = mysql_fetch_array($sql);
 $location[loc_option]=unserialize($location[loc_option]);
 $location[door_list]=unserialize($location[door_list]);
 $location[obj_list]=unserialize($location[obj_list]);
 $monstr_list=unserialize($location[monstr_list]);

 if ($_GET["do"]=="go" and isset($_GET["var"])) {
    $sql=mysql_query("SELECT * FROM locations WHERE loc_id='".$location[door_list][$_GET["var"]][target]."'");
    if (mysql_num_rows($sql)!=1 or $status[infight]!="no") {$page.=mysql_error()."<br/>Ошибка! Неверная цель!<br/>";}
    elseif ($player[party]!=$player[id] and !empty($player[party])){$status[tmp][]="<br/>Вас направляет глава вашей пати! Вы не можете сами передвигаться!";}
    else{
      $count=mysql_result(mysql_query("SELECT count(id) FROM users WHERE party='$player[party]'"),0,0);
      if ($count>1){
        $sql=mysql_query("SELECT id,char_name,status,onlinetime,skills FROM users WHERE party='$player[party]'");
  			  $where="";
  			  while($user=mysql_fetch_array($sql)) {
  			  	 $user[status]=unserialize($user[status]);
         $where.=" id='$user[id]' OR";
         $users[]=$user;
         if($user[status][infight] !='no'){$partyfight=1;break;}
         elseif($user[status][talk] !='no' and !empty($user[status][talk])){$partytalk=1;break;}
  			  }
  			  if ($partyfight==1){$status[tmp][]="<br/>Вы не можете передвигаться пока ваши сопартийцы в бою!";}
  			  elseif ($partytalk==1){$status[tmp][]="<br/>Вы не можете передвигаться пока ваши сопартийцы болтают!";}
  			  else {
  			  	 $where=substr($where, 0, strlen($set)-2);
  			  	 $loc_id=$location[door_list][$_GET["var"]][target];
         $sql=mysql_query("UPDATE users SET loc_id='$loc_id' WHERE $where");
  			   $page.=mysql_error();
         $player[loc_id]=$location[door_list][$_GET["var"]][target];
  			  }

      }
      else {
       $loc_id=$location[door_list][$_GET["var"]][target];
   $player[loc_id]=$location[door_list][$_GET["var"]][target];
   $sql=mysql_query("UPDATE users SET loc_id='$player[loc_id]' WHERE id='$player[id]' LIMIT 1");
      }
      if($partyfight!=1 and $partytalk!=1) {

  $sql = mysql_query("SELECT * FROM locations WHERE loc_id='$player[loc_id]'");
 		  	$location = mysql_fetch_array($sql);
 		  	$location[loc_option]=unserialize($location[loc_option]);
 		  	$location[door_list]=unserialize($location[door_list]);
 		  	$monstr_list=unserialize($location[monstr_list]);
 		  	$location[obj_list]=unserialize($location[obj_list]);
 		  	$obj_list=$location[obj_list];
  if (!empty($obj_list)) {
   			for ($i=0;$i<sizeof($obj_list);$i++) {
   	//$page.=$obj_list[$i][on_enter];
   				if (!empty($obj_list[$i][on_enter])) {
   	eval($obj_list[$i][on_enter]); // процедура обновления объектов)
   	$wasupd=1;
   	}
   			}
   			if ($wasupd==1) {
   			 $tmp=serialize($obj_list);
   			 $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");
   			}
  	  	}
  	   if (is_array($monstr_list) and $location[loc_option][fight] != 'no') {

  	  		shuffle($monstr_list);
        for($i=0;$i<sizeof($monstr_list);$i++){
    if ($monstr_list[$i][status]!="dead" and !empty($monstr_list[$i]) and empty($monstr_list[$i][in_fight])){
    	if (!isset($users)) {$user=$player;$user[status]=$status;}
    	else {
    	 $tmp=shuffle($users);
    	 foreach($users as $key=>$value){
              if (time()>($value[status][hospital]["time"]+5*60) and ($value[onlinetime]>time()-5*60)){
                   $user=$value;break;
              }
    	 }
            }
            $user[skills]=unserialize($user[skills]);
            $rnd=mt_rand(1,100);
            if (($user[skills][per][level]+75)>$rnd){break;}
          $f_id="player".$user[id];
  				$fighters[$f_id][last_target]="monstr".$i;
  				$f_id="monstr".$i;
  				$fighters[$f_id][last_target]="player".$user[id];
  				$tmp=serialize($fighters);
  				$combatid=rand(1,1000)."player".$user[id];
  				$end_round=time()+60;
  				$sql=mysql_query("INSERT INTO combats(combatid,loc_id,fighters,round,end_round) VALUES('$combatid','$player[loc_id]','$tmp','1','$end_round');");
  				$user[status][infight]=$combatid;
  				$tmp=serialize($user[status]);
  				$sql = mysql_query("UPDATE users SET status='$tmp' WHERE id='$user[id]' LIMIT 1");
  				$monstr_list[$i][in_fight] = $combatid;
  				$tmp=serialize($monstr_list);
  				$sql = mysql_query("UPDATE locations SET monstr_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");
                if ($user[id]==$player[id]){
            $page.="<p class='d'><b>Нападение!</b></p>";
            $page.="<br/>На вас напал ".$monstr_list[$i][name]."!<br/>";
            $page.="<p class='d'><a href='./'><b>В бой</b></a></p>";
                  display($page, "Нападение", $style);
                  die();
                }
                break;

    }
        }
       }
  	  }
    }

 }

 if ($_GET['do']=='useobj') {include"$filesfolder/useobj.php"; }
 else {
 	$page.="<p class='d'><b><a href='./?do=aboutloc'>".$location[loc_option][name]."</a></b></p>";

 if ($_GET['do']=='aboutloc') {$page.="<br/>".$location[loc_option][info]."<br/>";}
 elseif ($_GET['view']=='obj') {
   $page.="<br/>".$location[obj_list][$_GET["var"]][info]."<br/>";
 }
 elseif (isset($_GET[view])){include"$filesfolder/view.php";}
 else {

  respawn_monsters($monstr_list, $player[loc_id],$location[loc_option]);
  if  (is_array($location[obj_list])) {
  	$count=sizeof($location[obj_list]);
   for ($i=0;$i<sizeof($location[obj_list]);$i++) {
   	if ($location[obj_list][$i][type]=="garbage" and empty($location[obj_list][$i][bag]))
   	{$count--;}
   }
			if ($player[hit_points]<=0) {

			$dead=1;
			$player[od]=$player[maxod];
$count=mysql_result($sql,0,0);
$player[loc_id]=$player[citizen];
$status[hospital]["time"]=time();

$status[infight]="no";
$player[hit_points]=5;
$tmp=serialize($status);
	$sql=mysql_query("UPDATE users SET status='$tmp',hit_points='$player[hit_points]',loc_id='$player[loc_id]',od='$player[od]' WHERE id='$player[id]' LIMIT 1");
			}
			$page.="<br/><img src='/img/icon/heal.PNG'/> HP - $player[hit_points]/$player[maxhp] </a><br/>";
			$player["mail"]=unserialize($player["mail"]);
$mail_new=mysql_num_rows(mysql_query('select * from sms where r=0 and user='.$player['id']));
if($mail_new>=1){$page.='<img src="/img/icon/mail1.PNG"><a href="/?do=mail&act=in">Почта ['.$mail_new.']</a> - ';}
   $page.='<a href="/?rnd='.rand(1,100).'">Обновить</a><br>';
   if ($count>0) {
   	$page.="<br/><img src='/img/icon/dia.PNG'/> <b>Объекты</b>";
   	 for ($i=0;$i<sizeof($location[obj_list]);$i++) {
   		if (!($location[obj_list][$i][type]=="garbage" and empty($location[obj_list][$i][bag]))){
   	 	if ($location[obj_list][$i][type]=="npc")	{$page.='<br><a href="/?talk='.$i.'&rnd='.rand(1,100).'">'.$location[obj_list][$i][name]."</a>";}
 else {$page.='<br><a href="/?do=useobj&amp;var='.$i.'&rnd='.rand(1,100).'">'.$location[obj_list][$i][name]."</a>";}
 $page.=" <a href='./?view=obj&amp;var=$i'><img src='/img/icon/i.PNG'/></a>";
     }
   	}
   	$page.="<br/>";
   }
  }
  
  if (isset($status[barter][from])) {$page.="<br/><img src='/img/icon/kredit.PNG'/> Вам предлогают <a href='./?do=give'>торговаться!</a>";}
  if (isset($status[inviteparty])) {
  	$sql=mysql_query("SELECT char_name FROM users WHERE id='$status[inviteparty]' LIMIT 1");
  	$char_name=mysql_result($sql,0,"char_name");
  	$page.="<br/><img src='/img/icon/dia.PNG'/> $char_name пригласил вас в пати! [<a href='./?do=party&amp;welcome=ok'>ok</a>][<a href='./?do=party&amp;welcome=cancel'>x</a>]";}
  if (!empty($status[tmp])){
  	for ($i=0;$i<sizeof($status[tmp]);$i++){
  		$page.=$status[tmp][$i];
  	}
  	$page.="<br/>";
  	$status=unset_as_mass($status,"tmp");
  	$tmp=serialize($status);
  	$sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
  }

  $tmp=time()-5*60;
  $sql=mysql_query("SELECT count(*) FROM users WHERE loc_id='$player[loc_id]' AND onlinetime>$tmp");
  $count=mysql_result($sql,0,0)-1;
  if ($count>0){
 	if ($count>5) {$page.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?view=player'>Игроков: $count</a>";}
 	else {$page.="<br/><img src='/img/icon/gun.PNG'/> <b>Игроки</b>";}
  $tmp=time()-5*60;
 	//$sql=mysql_query("SELECT id,char_name,hit_points,status,x,y FROM users WHERE loc_id='$player[loc_id]' "); // AND onlinetime>'$tmp'
  $sql=mysql_query("SELECT id,char_name,hit_points,status,x,y,quests FROM users WHERE loc_id='$player[loc_id]' AND onlinetime>'$tmp' ORDER BY char_name");
  $c=0;
  	while ($enemy=mysql_fetch_array($sql)){
  	  if ($c>=5) {break;}
      if ($enemy[hit_points]>0 and $enemy[id]!=$player[id]){
        $enemy[status]=unserialize($enemy[status]);
        $quest=unserialize($enemy[quests]);
				$c++;
				if($quest[povstanec][status]=="complete") {$page.='<br><a style="color:#FF0000" href="./?view=player&amp;id='.$enemy[id].'">'.$enemy[char_name].'</a>';}
        elseif($quest[boecness][status]=="complete") {$page.='<br><a style="color:#00CC00" href="./?view=player&amp;id='.$enemy[id].'">'.$enemy[char_name].'</a>';}
        elseif($quest[naemnik][status]=="complete") {$page.='<br><a style="color:#0099FF" href="./?view=player&amp;id='.$enemy[id].'">'.$enemy[char_name].'</a>';}
        else {$page.='<br/><a style="color:#808080" href="./?view=player&amp;id='.$enemy[id].'">'.$enemy[char_name].'</a>';}
 	$distance=distance($enemy[x],$enemy[y],$player[x],$player[y]);
 	if ($distance<=10) {$page.=" [Вплотную]";}
 	elseif ($distance<=20) {$page.=" [Близко]";}
 	elseif ($distance<=35) {$page.=" [Вблизи]";}
 	elseif ($distance<=50) {$page.=" [Недалеко]";}
 	elseif ($distance<=100) {$page.=" [Далеко]";}
 	else {$page.=" [Очень далеко]";}
 	if ($enemy[status][infight]!="no"){$page.="[В бою]";}
 	$page.="[<a href='./?do=use&amp;what=what&amp;target=player&amp;id=$enemy[id]'>исп</a>]";
 	if ($location[loc_option][fight]!='no'&&$enemy['level']>=3) {$page.="[<a href='./?attack=player&amp;id=$enemy[id]'>бить</a>]";}
      }

  	}
  	$page.="<br/>";
 	}
  $count=count_monsters($monstr_list);
 	if ($count>0){
 		if ($count>3) {$page.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?view=monstr'>Монстров: ".sizeof($monstr_list)."</a>";}
 		else {$page.="<br/><img src='/img/icon/gun.PNG'/> <b>Монстры</b>";}

 		$c=0;
 		for ($i=0; $c<3; $i++){
 	  		if ($i>sizeof($monstr_list)) {break;}
    		if ($monstr_list[$i][status]=="life")
  { $page.="<br/> <a href='./?view=monstr&amp;id=$i'>".$monstr_list[$i][name]."</a>";
 	$distance=distance($monstr_list[$i][x],$monstr_list[$i][y],$player[x],$player[y]);
 	if ($distance<=10) {$page.=" [Вплотную]";}
 	elseif ($distance<=20) {$page.=" [Близко]";}
 	elseif ($distance<=35) {$page.=" [Вблизи]";}
 	elseif ($distance<=50) {$page.=" [Недалеко]";}
 	elseif ($distance<=100) {$page.=" [Далеко]";}
 	else {$page.=" [Очень далеко]";}
 	if (!empty($monstr_list[$i][in_fight])){$page.="[В бою]";}
 	if ($location[loc_option][fight] != 'no') {$page.="[<a href='./?attack=monster&amp;id=$i'>бить</a>]";}
 	$c++;
  }
 		}
  	$page.="<br/>";
 	}

 	if (!empty($location[door_list])) {
   $page.="<br/><img src='/img/icon/arrow.PNG'/> ";
   $page.="<b>Движение</b>";
   for ($i=0;$i<sizeof($location[door_list]);$i++) {
     $page.="<br/><a href='./?do=go&amp;to=".$location[door_list][$i][caption]."&amp;var=$i&amp;rnd=".rand(1,10000)."'>".$location[door_list][$i][caption]."</a>";
   }
   $page.="<br/>";
 	}
 }
 }
if ($player[rights]=="admin") {$page.="<br/>[<a href='./?do=admin'>Админка</a>]";}
$page.="<br/>[<a href='./?do=party'>Пати</a>]";
$page.=" - [<a href='./?do=aboutme'>Персонаж</a>]";
$page.=" - [<a href='./?do=inv'>Инвентарь</a>]";
$page.=' - [<a href="/?do=chat">Радиосвязь</a>]';
$page.=' - [<a href="/?do=forum">Форум</a>]';
$page.=" - [<a href='./?do=mail'>Почта</a>]";
$page.=' - [<a href="/?rnd='.rand(1,100).'">Обновить</a>]<br><br>';

function count_monsters($monstr_list) //возвращает количество живых монстров на локации
{ $colvo=0;
for($i=0;$i<sizeof($monstr_list);$i++)
 {if ($monstr_list[$i][status]=="life") {$colvo++;} }
 return $colvo;}

  function respawn_monsters($monstr_list, $loc_id,$option) //воскрешение монстров
    { $was_resp=0;
     for($i=0;$i<sizeof($monstr_list);$i++)
       {
        if ((($monstr_list[$i][respawn] + $monstr_list[$i][period_respawn])<time()) and $monstr_list[$i][status]=="dead")
         {
          $sql=mysql_query("SELECT * FROM monsters WHERE id='".$monstr_list[$i][id]."' LIMIT 1");
          $monstr=mysql_fetch_array($sql);
          $monstr_list[$i][respawn]=time();
          $monstr_list[$i][status]="life";
          foreach($monstr as $key=>$value){
      $monstr_list[$i][$key]=$value;
          }
          
          
          
          $monstr_list[$i][hit_points]=$monstr[maxhp];
          $monstr_list[$i][od]=$monstr[maxod];
          $monstr_list[$i][x]=rand(1,$option[loc_x]);
          $monstr_list[$i][y]=rand(1,$option[loc_y]);
          $was_resp++;
         }
        //if (($monstr_list[$i]["timemod"]=="day" and $game[light]!="day") or ($monstr_list[$i]["timemod"]=="night" and $game[light]!="night"))
        //{$monstr_list[$i][status]="dead";}
       }
       if ($was_resp>0) {
       $monstr_list=serialize($monstr_list);
       $sql=mysql_query("UPDATE locations SET monstr_list='$monstr_list' WHERE loc_id='$loc_id'");}
    }

 function update_money_obj($obj,$count,$next) // $obj_list[$i]=update_money_obj($obj_list[$i],1000,6*60*60);
 	{        // обновление денег у npc
     if ($obj[status][updmoney]<time()) {
       $obj[status][updmoney]=time()+$next;
       $obj[money]= $count;
 	   }
     return $obj;
 	}

?>