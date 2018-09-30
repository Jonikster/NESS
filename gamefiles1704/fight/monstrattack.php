<?
   
	  $skills=unserialize($player[skills]);
      $trauma=unserialize($player[trauma]);
      $player[weapon1]=unserialize($player[weapon1]);
      $player[weapon2]=unserialize($player[weapon2]);
      $player[fact_params]=unserialize($player[fact_params]);

      if ($monstr_list[$_GET[id]][status]=="dead" or !isset($monstr_list[$_GET[id]])) {$pageinfo.="<br/>Похоже монстр уже мертв! <br/> [получено $exp опыта]<br/>";}
      else{
        if   ($status[infight] == "no")    //Если игрок  не в бою
         {$combatnotfound=1;
      	  if (!empty($monstr_list[$_GET[id]][in_fight]))	{   //а монстр в бою, находим его и добавляем игрока в этот бой
           $combatnotfound=0;
           $sql = mysql_query("SELECT * FROM combats WHERE combatid='".$monstr_list[$_GET[id]][in_fight]."' LIMIT 1");
           if (mysql_num_rows($sql)!=1){$combatnotfound=1;}
           else {
            $combat = mysql_fetch_array($sql);
           	    $fighters=unserialize($combat[fighters]);
           	 	 foreach ($fighters as $key=>$value)
           	 		{  if ($key=="monstr".$_GET[id])
                                   {$f_id="player".$player[id];
                                   	$fighters[$f_id][last_target]="monstr".$_GET[id];
                                    $tmp=serialize($fighters);
                                    $sql = mysql_query("UPDATE combats SET fighters='$tmp' WHERE loc_id='$player[loc_id]' AND combatid='$combat[combatid]' LIMIT 1");
                                    $status[infight] = $combat[combatid];
                                    $tmp=serialize($status);
                                    $sql = mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
                                    break;
                                   }
           	 		}
           }
           if   ($status[infight] == "no") {$combatnotfound=1;}
          }
          if ($combatnotfound==1) { // и монстр не в бою, создаем новый бой
          	$f_id="player".$player[id];
            $fighters[$f_id][last_target]="monstr".$_GET[id];
            $f_id="monstr".$_GET[id];
            $fighters[$f_id][last_target]="player".$player[id];
            $tmp=serialize($fighters);
            $combatid=rand(1,1000)."player".$player[id];
            $end_round=time()+60;
            $sql=mysql_query("INSERT INTO combats(combatid,loc_id,fighters,round,end_round) VALUES('$combatid','$player[loc_id]','$tmp','1','$end_round');");

            $status[infight]=$combatid;
            $tmp=serialize($status);
            $sql = mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
            $monstr_list[$_GET[id]][in_fight]= $combatid;
            $tmp=serialize($monstr_list);
            $sql = mysql_query("UPDATE locations SET monstr_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");

          }
        }
        else { //Если игрок  в бою
                 if ($status[infight]!=$monstr_list[$_GET[id]][in_fight]){    // если игрок и монстр в разных боях, бои надо объединить!
                   $sql = mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' AND combatid='$status[infight]' LIMIT 1");
                   $combat = mysql_fetch_array($sql);
                   $combat[fighters]=unserialize($combat[fighters]);
                   $sql = mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' and combatid='".$monstr_list[$_GET[id]][in_fight]."' LIMIT 1");
                   $combatmonstr = mysql_fetch_array($sql);
           	       $combatmonstr[fighters]=unserialize($combatmonstr[fighters]);
                   $combat[fighters]["player".$player[id]][last_target]="monstr".$_GET[id];
                   union_combats($combat,$combatmonstr,$monstr_list);
                 }
                 if (empty($monstr_list[$_GET[id]][in_fight]))	{   //если монстр не в бою, добавляем монстра в бой
               	    $sql = mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' AND combatid='$status[infight]' LIMIT 1");
               		$combat = mysql_fetch_array($sql);
               		$fighters=unserialize($combat[fighters]);
               		$fighters["player".$player[id]][last_target]="monstr".$_GET[id];
               		$f_id="monstr".$_GET[id];
               		$fighters[$f_id][last_target]="player".$player[id];
               		$tmp=serialize($fighters);
               		$sql = mysql_query("UPDATE combats SET fighters='$tmp' WHERE loc_id='$player[loc_id]' AND combatid='$combat[combatid]' LIMIT 1");
               		$monstr_list[$_GET[id]][in_fight]= $status[infight];
               		$tmp=serialize($monstr_list);
               		$sql = mysql_query("UPDATE locations SET monstr_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");
             	 }

        }
        if (!is_array($fighters)) {$fighters=unserialize($fighters);}
        if (isset($_GET[go]))
        {  $needod=3;
           if ($trauma[leftleg]=="on") {$needod++;}
           if ($trauma[rightleg]=="on") {$needod++;}
           if ($player[od]<$needod) {$pageinfo.="<br/>Не хватает ОД! Нужно $needod<br/>";}
           else {
              if ($_GET[go]=='to'){
                $distance=distance($monstr_list[$_GET[id]][x],$monstr_list[$_GET[id]][y],$player[x],$player[y]);
                if ($distance<=10) {$pageinfo.="<br/>Ближе уже некуда!<br/>";}
          		else {
          			if ($distance<=20) {$need=5;}
          		    elseif ($distance<=35) {$need=15;}
          		    elseif ($distance<=50) {$need=30;}
          		    elseif ($distance<=100) {$need=45;}
          		    else {$need=90;}
                    $pageinfo.="<br/> Вы подошли ближе<br/>";
                 }
              }
              elseif ($_GET[go]=='away'){
                $distance=distance($monstr_list[$_GET[id]][x],$monstr_list[$_GET[id]][y],$player[x],$player[y]);
                if ($distance>=150) {$pageinfo.="<br/>Дальше нельзя! Потеряете цель из виду!<br/>";}
                else {
          			if ($distance>=100) {$need=155;}
          		    elseif ($distance>=50) {$need=105;}
          		    elseif ($distance>=35) {$need=55;}
          		    elseif ($distance>=20) {$need=40;}
          		    else {$need=25;}
                    $pageinfo.="<br/> Вы отошли<br/>";
                }
              }
              if (isset($need)){
          		    $k=$need/$distance;
          		    $dx=$player[x]-$monstr_list[$_GET[id]][x];
          		    $dy=$player[y]-$monstr_list[$_GET[id]][y];

          		    	$player[x]=intval(round($dx*$k+$monstr_list[$_GET[id]][x]));
          		    	$player[y]=intval(round($dy*$k+$monstr_list[$_GET[id]][y]));
                    $player[od]=$player[od]-$needod;
                    $sql=mysql_query("UPDATE users SET od='$player[od]',x='$player[x]',y='$player[y]' WHERE id='$player[id]' ");

               }
           }
        }

        $pagedown.="<b>".$monstr_list[$_GET[id]][name]."</b><br/>";
        $distance=distance($monstr_list[$_GET[id]][x],$monstr_list[$_GET[id]][y],$player[x],$player[y]);
          		if ($distance<10) {$pagedown.="[Вплотную]";}
          		elseif ($distance<20) {$pagedown.="[Близко]";}
          		elseif ($distance<35) {$pagedown.="[Вблизи]";}
          		elseif ($distance<50) {$pagedown.="[Недалеко]";}
          		elseif ($distance<100) {$pagedown.="[Далеко]";}
          		else {$pagedown.="[Очень далеко]";}
        $pagedown.="<br/><img src='/img/icon/move.PNG'/> ОД монстра: ".$monstr_list[$_GET[id]][od]."/".$monstr_list[$_GET[id]][maxod]."<br/>
        		<img src='/img/icon/heal.PNG'/> HP монстра: ".$monstr_list[$_GET[id]][hit_points]."/".$monstr_list[$_GET[id]][maxhp]."<br/>";

$rand=mt_rand(1,100);
        if (isset($_GET[weapon])) {
              $fighters["player".$player[id]][last_target]="monstr".$_GET[id];
              if ($_GET[weapon]==hand) {
                  $distance=distance($monstr_list[$_GET[id]][x],$monstr_list[$_GET[id]][y],$player[x],$player[y]);
                  $needod=3;
                  if ($trauma[lefthand]=="on") {$needod++;}
                  if ($trauma[righthand]=="on") {$needod++;}
                  if ($distance>10) {$pageinfo.="<br/>Цель слишком далеко!<br/>";}
                  elseif ($player[od]<$needod) {$pageinfo.="<br/>Не достаточно ОД! Нужно $needod<br/>";}

                  else {
                     $f_id="monstr".$_GET[id];
                     $player[od]=$player[od]-$needod;
                     $damage=3+$player[fact_params][str];
                     if ($trauma[lefthand]=="on" or $trauma[righthand]=="on") {$damage=intval(ceil($damage/2));}
                     $defense=$monstr_list[$_GET[id]][resnormal];
                     $defense=intval(ceil($damage*($defense/100)));
                     $damage=$damage-$defense;
                     $rand=rand(1,100);
                     $skills[handfight][act]++;
                     if ($skills[handfight][level]<10){ $skillmode=2*$skills[handfight][level];}
                        else{$skillmode=10+$skills[handfight][level];}
                     $damage=ceil($damage*(100+$skillmode)/100);
                     $uvorot=100-$monstr_list[$_GET[id]][bonusdex]+$skills[handfight][level];
                     if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }
                     if ($uvorot<5) {$uvorot=5;}
                     elseif ($uvorot>95) {$uvorot=95;}
                     if ($rand>$uvorot){
                     	$combatlog="<br/>$player[char_name] ударил ".$monstr_list[$_GET[id]][name].", но противник увернулся от удара";

                        $dmg_round["f_id"]="player".$player[id];
                        $dmg_round[damage]=0;
                        $dmg_round[str]=$combatlog;
                        $fighters[$f_id][dmg_round][]=$dmg_round;
                        }
                     elseif ($rand<=$player[fact_params][luck]) {    // критический удар
                     	$dmg_round["f_id"]="player".$player[id];
                     	$combatlog="<br/>$player[char_name] ударил ".$monstr_list[$_GET[id]][name].", критическое попадание!";
                     	if (rand(1,100)<=$player[fact_params][luck]){ //монстр опрокинут
                        $dmg_round["crit_bonus"][]="breakdown";
                        $combatlog.=" Противник опрокинут навзничь!";
                     	}
                     	if (rand(1,100)<=$player[fact_params][luck]){ //монстр пропускает следущий раунд!
                        $dmg_round["crit_bonus"][]="roundloose";
                        $combatlog.=" Противник пропускает следующий ход!";
                     	}
                     	if (rand(1,100)<=$player[fact_params][luck]){ //защита монстра не учитывается!
                        $damage=$damage+$defense;
                        $combatlog.="Его защита не учитывается!";
                     	}
                     	$damage=3*$damage;
                     	$combatlog.="Он получает 3ой урон - ".$damage*3;
                        $dmg_round[damage]=$damage;
                        $dmg_round[str]=$combatlog;
                     	$fighters[$f_id][dmg_round][]=$dmg_round;
                     }
                     else {
                          $combatlog="<br/>$player[char_name] ударил ".$monstr_list[$_GET[id]][name]." и нанес урон ".$damage;
                     	  $dmg_round["f_id"]="player".$player[id];
                     	  $dmg_round[damage]=$damage;
                     	  $dmg_round[str]=$combatlog;
                     	  $fighters[$f_id][dmg_round][]=$dmg_round;
                     }
                     $pageinfo.="<br/>Вы ударили ".$monstr_list[$_GET[id]][name]."<br/>";
                     $tmp=serialize($fighters);
                     $sql=mysql_query("UPDATE combats SET fighters='$tmp' WHERE combatid='$combat[combatid]' AND loc_id='$player[loc_id]'");
                     $sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]'");

                  }
              }
              elseif ($_GET[weapon]==1 or $_GET[weapon]==2) {
                  if ($_GET[weapon]==1) {$weapon=$player[weapon1];}
                  else {$weapon=$player[weapon2];}
                  $distance=distance($monstr_list[$_GET[id]][x],$monstr_list[$_GET[id]][y],$player[x],$player[y]);
                  $needod=3+$weapon[about_item][odbonus];
                  if ($trauma[lefthand]=="on") {$needod++;}
                  if ($trauma[righthand]=="on") {$needod++;}
                  if ($weapon[about_item][type_weap]=="fire") {$needpatron=1;}
                  if ($_GET[mod]=="array") {$needod++;}
                  elseif ($_GET[mod]=="sniper") {$needod++;}
                  if (empty($weapon)) {$pageinfo.="<br/>Ошибка! Ошибка! Ошибка!<br/>";}
                  elseif ($distance>10 and $weapon[about_item][type_weap]=="melee") {$pageinfo.="<br/>Цель слишком далеко!<br/>";}
                  elseif ($player[od]<$needod) {$pageinfo.="<br/>Не достаточно ОД! Нужно $needod<br/>";}
                  elseif ($weapon[about_item][type_weap]!="fire" and isset($_GET[mod])) {$pageinfo.="<br/>Ошибка! Не тот тип оружия<br/>";}
                  elseif ($weapon[about_item][type_weap]=="fire" and $weapon[about_item][patrons]<$needpatron) {$pageinfo.="<br/>Ошибка! ".$weapon[name]." надо перезарядить!<br/>";}
                  elseif ( $_GET[mod]=="array" and $weapon[about_item][arrayfire]<=1) {$pageinfo.="<br/>Ошибка! Оружие не стреляет очередями!<br/>";}
                  elseif ($weapon[about_item][type_weap]=="fire" and $weapon[about_item][crush]>=$rand) { //осечка
                      $pageinfo.="</br>Осечка! Выстрел не получился!</br>";
                      $player[od]=$player[od]-$needod;
                      $sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]' LIMIT 1");
                  }
				
                  else {
                     $f_id="monstr".$_GET[id];
                     $player[od]=$player[od]-$needod;
                     $mindamage=$weapon[about_item][mindmg];
                     $maxdamage=$weapon[about_item][maxdmg];
                     $uvorot=100-$monstr_list[$_GET[id]][bonusdex];
                     if ($_GET[mod]=="array") {
										 $uvorot=$uvorot-30;
                     	$needpatron=$weapon[about_item][arrayfire];
                     	if ($weapon[about_item][patrons]<$needpatron){
                     	  $needpatron=$weapon[about_item][patrons];
                     	}
                     	$mindamage=$mindamage*$needpatron;
                     }
                     $damage=rand($mindamage,$maxdamage);
                     if ($weapon[about_item][type_weap]=="melee") {
                     	$damage=$damage+$player[fact_params][str];                         $skills[coldweapon][act]++;
												 $uvorot+=$uvorot+$player[fact_params][dex]+$weapon[about_item][sniperbonus]+$skills[coldweapon][level]-$monstr_list[$_GET[id]][bonusdex];
                         
                         if ($skills[coldweapon][level]<10){ $skillmode=2*$skills[coldweapon][level];}
                         else{$skillmode=10+$skills[coldweapon][level];}
                     	if ($trauma[lefthand]=="on" or $trauma[righthand]=="on") {$damage=intval(ceil($damage*100/mt_rand(100,200)));}
                     	$sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]' LIMIT 1");
                     }
                     if ($weapon[about_item][type_weap]=="fire") {
					 $sql=mysql_query("SELECT *  FROM items WHERE id=38 LIMIT 1");//гильзы
$item=mysql_fetch_array($sql);
$item['colvo']=1;
$item['about_item']=unserialize($item['about_item']);
add_to_garbage($player['loc_id'],$item);
					 $skills[fireweapon][act]++;
                        $uvorot=$uvorot+$skills[fireweapon][level]+$weapon[about_item][sniperbonus]+intval(ceil(1.5*$player[fact_params][shooting]));
                        if ($skills[fireweapon][level]<10){ $skillmode=2*$skills[fireweapon][level];}
                        else{$skillmode=10+$skills[fireweapon][level];}
                     	$sql=mysql_query("SELECT about_item FROM items WHERE id='".$weapon[about_item][idpatrons]."' LIMIT 1");
                     	$patronitem=mysql_result($sql,0,"about_item");
                     	$patronitem=unserialize($patronitem);
                     	$damage=$damage+$patronitem[moddmg];
                     	$weapon[about_item][patrons]=$weapon[about_item][patrons]-$needpatron;
                     	$tmp=serialize($weapon);
                     	if ($_GET[weapon]==1) {$sql=mysql_query("UPDATE users SET weapon1='$tmp',od='$player[od]' WHERE id='$player[id]' LIMIT 1");}
                     	else {$sql=mysql_query("UPDATE users SET weapon2='$tmp',od='$player[od]' WHERE id='$player[id]' LIMIT 1");}
                     }
										 
                     if ($weapon[about_item][type_weap]=="throw") {                     	$skills[throwweapon][act]++;
                        $uvorot+=$skills[throwweapon][level];
                        if ($skills[throwweapon][level]<10){ $skillmode=2*$skills[throwweapon][level];}
                        else{$skillmode=10+$skills[throwweapon][level];}
                     }
										 
                     $tmp="res".$weapon[about_item][type_dmg];
                     $defense=$monstr_list[$_GET[id]][$tmp];
                     $defense=intval(ceil($damage*($defense/100)));
                     $damage=$damage-$defense;
                     $damage=ceil($damage*(100+$skillmode)/100);
                     $rand=mt_rand(1,100);
					 if ($distance<=20 and $distance>10) {$uvorot=$uvorot-10;}
                     elseif ($distance<=35) {$uvorot=$uvorot-40;}
                     elseif ($distance<=50) {$uvorot=$uvorot-50;}
                     elseif ($distance<=100) {$uvorot=$uvorot-60;}
                     else {$uvorot=$uvorot-70;}
                     if ($_GET[mod]=="sniper") {$uvorot=$uvorot+10;}
							
                     
                     
                     if ($trauma[eye]=="on") {$uvorot=$uvorot-40;}
                     if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }
                     
                     if ($uvorot<5) {$uvorot=5;}
                     elseif ($uvorot>95) {$uvorot=95;}
										 $rand=mt_rand(10,100);
                     if ($rand>$uvorot){
                     	$combatlog="<br/>$player[char_name] ";
                     	if ($weapon[about_item][type_weap]=="fire")
                     	{ if ($_GET[mod]=="array") {$combatlog.=" выстрелил очередью в ";}
                     	  elseif ($_GET[mod]=="sniper") {$combatlog.=" прицельно выстрелил в ";}
                     	  else {$combatlog.=" выстрелил в ";}
                     	}
                     	if ($weapon[about_item][type_weap]=="melee")
                     	{ $combatlog.=" ударил ";}
                     	if ($weapon[about_item][type_weap]=="throw")
                     	{ $combatlog.=" швырнул $weapon[name] в ";}
                     	$combatlog.=$monstr_list[$_GET[id]][name].", но промахнулся";
                        $dmg_round["f_id"]="player".$player[id];
                        $dmg_round[damage]=0;
                        $dmg_round[str]=$combatlog;
                        $fighters[$f_id][dmg_round][]=$dmg_round;
                        }
                     elseif ($rand<=$player[crit_chance]) {    // критический удар
                        $combatlog="<br/>$player[char_name] ";
                     	if ($weapon[about_item][type_weap]=="fire")
                     	{ if ($_GET[mod]=="array") {$combatlog.=" выстрелил очередью в ";}
                     	  elseif ($_GET[mod]=="sniper") {$combatlog.=" прицельно выстрелил в ";}
                     	  else {$combatlog.=" выстрелил в ";}
                     	}
                     	if ($weapon[about_item][type_weap]=="melee")
                     	{ $combatlog.=" ударил ";}
                     	if ($weapon[about_item][type_weap]=="throw")
                     	{ $combatlog.=" швырнул $weapon[name] в ";}
                     	$combatlog.=$monstr_list[$_GET[id]][name].", критическое попадание!";
                     	$dmg_round["f_id"]="player".$player[id];
                     	$tmp=rand(1,100);
                     	if ($tmp<=$player[crit_chance]){ //монстр опрокинут
                        $dmg_round["crit_bonus"][]="breakdown";
                        $combatlog.=" Противник опрокинут навзничь!";
                     	}
                     	elseif ($tmp<=2*$player[crit_chance]){ //монстр пропускает следущий раунд!
                        $dmg_round["crit_bonus"][]="roundloose";
                        $combatlog.=" Противник пропускает следующий ход!";
                     	}
                     	elseif ($tmp<=3*$player[crit_chance]){ //защита монстра не учитывается!
                        $damage=$damage+$defense;
                        $combatlog.="Его защита не учитывается!";
                     	}
                     	$damage=3*$damage;
                     	$combatlog.=" Он получает 3ой урон - ".$damage*3;
                        $dmg_round[damage]=$damage;
                        $dmg_round[str]=$combatlog;
                     	$fighters[$f_id][dmg_round][]=$dmg_round;
                     }
                     else {
                     	$combatlog="<br/>$player[char_name] ";
                     	if ($weapon[about_item][type_weap]=="fire")
                     	{ if ($_GET[mod]=="array") {$combatlog.=" выстрелил очередью в ";}
                     	  elseif ($_GET[mod]=="sniper") {$combatlog.=" прицельно выстрелил в ";}
                     	  else {$combatlog.=" выстрелил в ";}
                     	}
                     	if ($weapon[about_item][type_weap]=="melee")
                     	{ $combatlog.=" ударил ";}
                     	if ($weapon[about_item][type_weap]=="throw")
                     	{ $combatlog.=" швырнул $weapon[name] в ";}
                     	$combatlog.=$monstr_list[$_GET[id]][name]." и нанес урон ".$damage;
                     	  $dmg_round["f_id"]="player".$player[id];
                     	  $dmg_round[damage]=$damage;
                     	  $dmg_round[str]=$combatlog;
                     	  $fighters[$f_id][dmg_round][]=$dmg_round;
                     }
                     $pageinfo.="<br/>Вы ";
                     	if ($weapon[about_item][type_weap]=="fire")
                     	{ if ($_GET[mod]=="array") {$pageinfo.=" выстрелили очередью в ";}
                     	  elseif ($_GET[mod]=="sniper") {$pageinfo.=" прицельно выстрелили в ";}
                     	  else {$pageinfo.=" выстрелили в ";}
                     	}
                     	if ($weapon[about_item][type_weap]=="melee")
                     	{ $pageinfo.=" ударили ";}
                     	if ($weapon[about_item][type_weap]=="throw")
                     	{ $pageinfo.=" швырнули $weapon[name] в ";}
                     	$pageinfo.=$monstr_list[$_GET[id]][name]."<br/>";
                     if ($weapon[about_item][type_weap]=="throw") { //удаляем метательное оружие из инвентаря
                        $bag=unserialize($player[bag]);
                        for ($i=0;$i<sizeof($bag);$i++){
                            if ($bag[$i][id]==$weapon[id] and $bag[$i][name]==$weapon[name] and $bag[$i][info]==$weapon[info])
                            {  if ($bag[$i][colvo]>1) {$bag[$i][colvo]--;}
                               else {$bag=delete_element($bag,$i);}
                               $k=1;
                               $bag=serialize($bag);
                               break;
                            }
                        }
                        if ($k!=1){$weapon="";
                           if ($_GET[weapon]==1) {
                           	 $sql=mysql_query("UPDATE users SET weapon1='$weapon',od='$player[od]' WHERE id='$player[id]' LIMIT 1");}
                           else { $weapon="";
                           $sql=mysql_query("UPDATE users SET weapon2='$weapon',od='$player[od]' WHERE id='$player[id]' LIMIT 1");}
                         }
                        else{$sql=mysql_query("UPDATE users SET bag='$bag',od='$player[od]' WHERE id='$player[id]' LIMIT 1");}
                     }
                     $tmp=serialize($fighters);
                     $sql=mysql_query("UPDATE combats SET fighters='$tmp' WHERE combatid='$combat[combatid]' AND loc_id='$player[loc_id]' LIMIT 1");
                     if ($_GET[weapon]==1) {$player[weapon1]=$weapon;}
                     else {$player[weapon2]= $weapon;}
                  }
              }
              $tmp=serialize($skills);
              $sql=mysql_query("UPDATE users SET skills='$tmp' WHERE id='$player[id]' LIMIT 1");
        }
            // вывод панели атаки

        if ($player[od]<1){header("Location: index.php");
        	die();}
$pagedown.="<br/><a href='./?attack=monster&amp;id=$_GET[id]&amp;'>Обновить</a>";
$pagedown.="<br/><a href='./?action=end_round'>Закончить ход</a><br/>";
        $uvorot=100-$monstr_list[$_GET[id]][bonusdex];
        $distance=distance($monstr_list[$_GET[id]][x],$monstr_list[$_GET[id]][y],$player[x],$player[y]);
        
        if ($distance<=20 and $distance>10) {$uvorot=$uvorot-10;}
        elseif ($distance<=35) {$uvorot=$uvorot-40;}
        elseif ($distance<=50) {$uvorot=$uvorot-50;}
        elseif ($distance<=100) {$uvorot=$uvorot-60;}
        else {$uvorot=$uvorot-70;}
        if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }

             
                  
        if (!empty($player[weapon1])) {
				
        	
					
            $needod=3+$player[weapon1][about_item][odbonus];
            if ($trauma[lefthand]=="on") {$needod++;}
            if ($trauma[righthand]=="on") {$needod++;}
        	$pagedown.="<br/><b>".$player[weapon1][name]."</b>";
            if ($player[weapon1][about_item][type_weap]=="melee") {
						$uvorot+=$player[fact_params][dex]+$player[weapon1][about_item][sniperbonus]+$skills[coldweapon][level]-$monstr_list[$_GET[id]][bonusdex];            	
							
                if ($distance<=10) {
            	if ($uvorot<5) {$uvorot=5;}
                elseif ($uvorot>95) {$uvorot=95;}
            	$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=1'>Удар[$needod ОД][$uvorot%]</a>";
            	} else {$pagedown.="<br/><img src='/img/icon/cancel.PNG'/> Противник слишком далеко";}
            }
            elseif ($player[weapon1][about_item][type_weap]=="fire") {            	$uvorot+=$skills[fireweapon][level]+intval(ceil(2*($player[fact_params][shooting]+$player[weapon1][about_item][sniperbonus]))-$monstr_list[$_GET[id]][bonusdex]);
                if ($trauma[eye]=="on") {$uvorot=$uvorot-40;}
                if ($uvorot<5) {$uvorot=5;}
                elseif ($uvorot>95) {$uvorot=95;}
								if ($distance>10) {
					
            	$pagedown.="[".$player[weapon1][about_item][patrons]."/".$player[weapon1][about_item][maxpatrons]."]<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=1'>Выстрел[$needod ОД][$uvorot%]</a> ";
            	$tmp=$uvorot+10;
							
						  
            	if ($tmp>95) {$tmp=95;}
							
            	$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=1&amp;mod=sniper'>Прицельный выстрел[".($needod+1)." ОД][$tmp%]</a> ";
            	if ($player[weapon1][about_item][arrayfire]>0) {$uvorot=$uvorot-30; $pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=1&amp;mod=array'>Очередь[".$player[weapon1][about_item][arrayfire]."][".($needod+1)." ОД][".($uvorot-30)."%]</a> ";}
              $pagedown.="<br/><img src='/img/icon/patron.PNG'/> <a href='./?do=inv&amp;act=reload&amp;item=weapon1&amp;attack=monster&amp;id=$_GET[id]'>Перезарядка[".($needod-1-$player[weapon1][about_item][odbonus])." ОД]</a> ";
            }
						   
						}
            elseif ($player[weapon1][about_item][type_weap]=="throw") {            	$uvorot+=$skills[throwweapon][level];
            	if ($uvorot<5) {$uvorot=5;}
                elseif ($uvorot>95) {$uvorot=95;}
                $bag=unserialize($player[bag]);
                for ($i=0;$i<sizeof($bag);$i++){
                	if  ($bag[$i][id]==$player[weapon1][id] and $bag[$i][name]==$player[weapon1][name] and $bag[$i][info]==$player[weapon1][info])
                	{$colvo=$bag[$i][colvo]; break;}
                }
            	$pagedown.="[".($colvo+1)."]<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=1'>Швырнуть[$needod ОД][$uvorot%]</a>";}
            $pagedown.="<br/>";
        }

        $uvorot=100-$monstr_list[$_GET[id]][bonusdex];
        if ($distance<=20 and $distance>10) {$uvorot=$uvorot-10;}
        elseif ($distance<=35) {$uvorot=$uvorot-30;}
        elseif ($distance<=50) {$uvorot=$uvorot-45;}
        elseif ($distance<=100) {$uvorot=$uvorot-60;}
        else {$uvorot=$uvorot-70;}

        if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }
             
        if (!empty($player[weapon2])) {
				
            $needod=3+$player[weapon2][about_item][odbonus];
            if ($trauma[lefthand]=="on") {$needod++;}
            if ($trauma[righthand]=="on") {$needod++;}
        	$pagedown.="<br/><b>".$player[weapon2][name]."</b>";
            if ($player[weapon2][about_item][type_weap]=="melee" ) {
						$uvorot+=$player[fact_params][dex]+$player[weapon2][about_item][sniperbonus]+$skills[coldweapon][level]-$monstr_list[$_GET[id]][bonusdex];            	
							
                if ($distance<=10) {
								
            		if ($uvorot<5) {$uvorot=5;}
                	elseif ($uvorot>95) {$uvorot=95;}
            		$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=2'>Удар[$needod ОД][$uvorot%]</a>";
            	} else {$pagedown.="<br/><img src='/img/icon/cancel.PNG'/> Противник слишком далеко";}
            }
            elseif ($player[weapon2][about_item][type_weap]=="fire" and $distance>10) {            	$uvorot+=$skills[fireweapon][level]+intval(ceil(2*($player[fact_params][shooting]+$player[weapon2][about_item][sniperbonus]))-$monstr_list[$_GET[id]][bonusdex]);
            	
                if ($trauma[eye]=="on") {$uvorot=$uvorot-40;}
                if ($uvorot<5) {$uvorot=5;}
                elseif ($uvorot>95) {$uvorot=95;}
							if ($distance>10) {
							
            	$pagedown.="[".$player[weapon2][about_item][patrons]."/".$player[weapon2][about_item][maxpatrons]."]<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=2'>Выстрел[$needod ОД][$uvorot%]</a> ";
            	$tmp=$uvorot+10;
            	if ($tmp>95) {$tmp=95;}
						  
            	$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=2&amp;mod=sniper'>Прицельный выстрел[".($needod+1)." ОД][$tmp%]</a> ";
              
            	if ($player[weapon2][about_item][arrayfire]>0) {$uvorot=$uvorot-30; $pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=2&amp;mod=array'>Очередь[".$player[weapon2][about_item][arrayfire]."][".($needod+1)." ОД][$uvorot%]</a> ";}
                $pagedown.="<br/><img src='/img/icon/patron.PNG'/> <a href='./?do=inv&amp;act=reload&amp;item=weapon2&amp;attack=monster&amp;id=$_GET[id]'>Перезарядка[".($needod-1-$player[weapon2][about_item][odbonus])." ОД]</a> ";
            }
						  
						}
            elseif ($player[weapon2][about_item][type_weap]=="throw") {            	$uvorot+=$skills[trowweapon][level];
            	if ($uvorot<5) {$uvorot=5;}
                elseif ($uvorot>95) {$uvorot=95;}
                $bag=unserialize($player[bag]);
                for ($i=0;$i<sizeof($bag);$i++){
                	if  ($bag[$i][id]==$player[weapon2][id] and $bag[$i][name]==$player[weapon2][name] and $bag[$i][info]==$player[weapon2][info])
                	{$colvo=$bag[$i][colvo]; break;}
                }
            	$pagedown.="[".($colvo+1)."]<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=2'>Швырнуть[$needod ОД][$uvorot%]</a>";}
            $pagedown.="<br/>";
        }

        if ($distance<=10) {
            $uvorot=100-$monstr_list[$_GET[id]][bonusdex];
        	if ($distance<=20 and $distance>10) {$uvorot=$uvorot-10;}
        	elseif ($distance<=35) {$uvorot=$uvorot-30;}
	        elseif ($distance<=50) {$uvorot=$uvorot-45;}
        	elseif ($distance<=100) {$uvorot=$uvorot-60;}
        	else {$uvorot=$uvorot-70;}
        	if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }
            $uvorot+=$skills[handfight][level]-$player[fact_params][dex]-$monstr_list[$_GET[id]][bonusdex];
        	if ($uvorot<5) {$uvorot=5;}
            elseif ($uvorot>95) {$uvorot=95;}
        	$needod=3;
        	if ($trauma[lefthand]=="on") {$needod++;}
        	if ($trauma[righthand]=="on") {$needod++;}
					
        	$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;weapon=hand'>Атаковать в рукопашную[$needod ОД][$uvorot%] </a>";}

		$needod=2;
        if ($trauma[lefthand]=="on") {$needod++;}
        if ($trauma[righthand]=="on") {$needod++;}
        $pagedown.="<br/><img src='/img/icon/yad.PNG'/> <a href='./?do=use&amp;what=what&amp;target=self'>Использовать[$needod ОД]</a><br/>";
        $needod=3;

        if ($trauma[leftleg]=="on") {$needod++;}
        if ($trauma[rightleg]=="on") {$needod++;}
        if ($distance>5) {$pagedown.="<br/><img src='/img/icon/go.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;go=to'>Подойти поближе[$needod ОД] </a>";}
        if ($distance<=100) {$pagedown.="<br/><img src='/img/icon/go.PNG'/> <a href='./?attack=monster&amp;id=$_GET[id]&amp;go=away'>Отойдти подальше[$needod ОД] </a>";}
        

      }
?>