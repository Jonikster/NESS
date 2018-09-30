<?
      $skills=unserialize($player[skills]);
      $trauma=unserialize($player[trauma]);
      $player[weapon1]=unserialize($player[weapon1]);
      $player[weapon2]=unserialize($player[weapon2]);
      $player[fact_params]=unserialize($player[fact_params]);
      $enemyid=htmlspecialchars($_GET[id]);
      $sql=mysql_query("SELECT * FROM users WHERE id='$enemyid' LIMIT 1");
      $enemy=mysql_fetch_array($sql);
      $enemy[status]=unserialize($enemy[status]);
      $enemy[bodyarm]=unserialize($enemy[bodyarm]);
      $enemy[fact_params]=unserialize($enemy[fact_params]);
      $enemy[fact_resists]=unserialize($enemy[fact_resists]);

      if ($enemy[loc_id]!=$player[loc_id]) {$pageinfo.="<br/>Противника тут нет!<br/>";}
      elseif ($enemyid==$player[id]) {$pageinfo.="<br/>$enemyid Нельзя напасть на самого себя!$player[id]<br/>";}
      else{
        if   ($status[infight] == "no")    //Если игрок  не в бою
         {
      	  if ($enemy[status][infight]!="no")	{   //а противник в бою, находим его и добавляем игрока в этот бой
             $sql = mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' and combatid='".$enemy[status][infight]."' LIMIT 1");
             $combat=mysql_fetch_array($sql);
           	 $fighters=unserialize($combat[fighters]);
           	 $f_id="player".$player[id];
             $fighters[$f_id][last_target]="player".$enemyid;
             $tmp=serialize($fighters);
             $sql = mysql_query("UPDATE combats SET fighters='$tmp' WHERE loc_id='$player[loc_id]' AND combatid='$combat[combatid]' LIMIT 1");
             $status[infight] = $combat[combatid];
             $tmp=serialize($status);
             $sql = mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
          }
          else { // и противник не в бою, создаем новый бой
          	$f_id="player".$player[id];
            $fighters[$f_id][last_target]="player".$enemyid;
            $f_id="player".$enemyid;
            $fighters[$f_id][last_target]="player".$player[id];
            $tmp=serialize($fighters);
            $combat[combatid]=rand(1,1000)."player".$player[id];
            $combat[end_round]=time()+60;
            $combat["round"]=1;
            $sql=mysql_query("INSERT INTO combats(combatid,loc_id,fighters,round,end_round) VALUES('$combat[combatid]','$player[loc_id]','$tmp','1','$combat[end_round]');");

            $status[infight]=$combat[combatid];
            $tmp=serialize($status);
            $sql = mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
            $enemy[status][infight]=$combat[combatid];
            $tmp=serialize($enemy[status]);
            $sql = mysql_query("UPDATE users SET status='$tmp' WHERE id='$enemyid' LIMIT 1");

          }
        }
        else { //Если игрок  в бою
             if (empty($enemy[status][infight]))	{   //и противник не в бою, добавляем врага в бой
               $sql = mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' AND combatid='$status[infight]' LIMIT 1");
               $combat = mysql_fetch_array($sql);
               $fighters=unserialize($combat[fighters]);
               $f_id="player".$enemy[id];
               $fighters[$f_id][last_target]="player".$player[id];
               $tmp=serialize($fighters);
               $sql = mysql_query("UPDATE combats SET fighters='$tmp' WHERE loc_id='$player[loc_id]' AND combatid='$combat[combatid]' LIMIT 1");
             }
             else{  //Если враг тоже в бою
                 $sql = mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' AND combatid='$status[infight]' LIMIT 1");
                 $combat = mysql_fetch_array($sql);
           	     $fighters=unserialize($combat[fighters]);
                 if ($status[infight]!=$enemy[status][infight]){    // если  в разных боях, бои надо объединить!
                   $sql = mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' and combatid='".$enemy[status][infight]."' LIMIT 1");
                  $combatenemy = mysql_fetch_array($sql);
           	      $combatenemy[fighters]=unserialize($combatenemy[fighters]);
                  $combat[fighters]=unserialize($combat[fighters]);
                  union_combats($combat,$combatenemy,$monstr_list);
                 }
             }
        }

        if (isset($_GET[go]))
        {  $needod=3;
           if ($trauma[leftleg]=="on") {$needod++;}
           if ($trauma[rightleg]=="on") {$needod++;}
           if ($player[od]<$needod) {$pageinfo.="<br/>Не хватает ОД! Нужно $needod<br/>";}
           else {
              $distance=distance($enemy[x],$enemy[y],$player[x],$player[y]);
              if ($_GET[go]=='to'){
                if ($distance<=10) {$pageinfo.="<br/> Ближе уже некуда!<br/>";}
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
                if ($distance>=150) {$pageinfo.="<br/> Дальше нельзя! Потеряете цель из виду!<br/>";}
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
          		    $dx=$player[x]-$enemy[x];
          		    $dy=$player[y]-$enemy[y];
          		    $player[x]=intval(round($dx*$k+$enemy[x]));
          		    $player[y]=intval(round($dy*$k+$enemy[y]));
                    $player[od]=$player[od]-$needod;
                    $sql=mysql_query("UPDATE users SET od='$player[od]',x='$player[x]',y='$player[y]' WHERE id='$player[id]' ");
			  }
           }
        }

        $pagedown.="<br/><b>".$enemy[char_name]."</b><br/>";
        $distance=distance($enemy[x],$enemy[y],$player[x],$player[y]);
          		if ($distance<10) {$pagedown.=" [Вплотную]";}
          		elseif ($distance<20) {$pagedown.=" [Близко]";}
          		elseif ($distance<35) {$pagedown.=" [Вблизи]";}
          		elseif ($distance<50) {$pagedown.=" [Недалеко]";}
          		elseif ($distance<100) {$pagedown.=" [Далеко]";}
          		else {$pagedown.=" [Очень далеко]";}
        $pagedown.="<br/><img src='/img/icon/move.PNG'/> ОД: ".$enemy[od]."/".$enemy[maxod]."<br/>
        		 <img src='/img/icon/heal.PNG'/> HP: $enemy[hit_points]/$enemy[maxhp]<br/>";
$rand=rand(1,100);
        if (isset($_GET[weapon])) {
              $fighters["player".$player[id]][last_target]="player".$_GET[id];
              if ($_GET[weapon]==hand) {
                  $distance=distance($enemy[x],$enemy[y],$player[x],$player[y]);
                  $needod=3;
                  if ($trauma[lefthand]=="on") {$needod++;}
                  if ($trauma[righthand]=="on") {$needod++;}
                  if ($distance>10) {$pageinfo.="<br/>Цель слишком далеко!<br/>";}
                  elseif ($player[od]<$needod) {$pageinfo.="<br/> Не достаточно ОД! Нужно $needod<br/>";}
                  else {
                     $f_id="player".$enemyid;
                     $player[od]=$player[od]-$needod;
                     $damage=3+$player[fact_params][str];
                     $kb=$enemy[bodyarm][about_item][kb];
                     if ($kb>ceil($damage*50/100)){$kb=ceil($damage*50/100);}
                     $damage=$damage-$kb;
                     if ($trauma[lefthand]=="on" or $trauma[righthand]=="on") {$damage=intval(ceil($damage/2));}
                     $defense=$enemy[fact_resists][resnormal];
                     if ($defense>90) {$defense=90;}
                     $defense=intval(ceil($damage*($defense/100)));
                     $damage=$damage-$defense;
                     if ($damage<1) {$damage=1;}
                     $rand=rand(1,100);
                     $skills[handfight][act]++;
                     if ($skills[handfight][level]<10){ $skillmode=2*$skills[handfight][level];}
                        else{$skillmode=10+$skills[handfight][level];}
                     $damage=ceil($damage*(100+$skillmode)/100);
                     $uvorot=100-2.5*$enemy[fact_params][dex]-$enemy[fact_params][luck]+$skills[handfight][level];
                     if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }
                	 $uvorot=$uvorot-$enemy[bodyarm][about_item][bonusdex];
                     if ($uvorot<10) {$uvorot=10;}
                     elseif ($uvorot>95) {$uvorot=95;}
                     if ($rand>$uvorot){
                     	$combatlog="<br/> $player[char_name] ударил ".$enemy[char_name].", но противник увернулся от удара";
                        $dmg_round["f_id"]="player".$player[id];
                        $dmg_round[damage]=0;
                        $dmg_round[str]=$combatlog;
                        $fighters[$f_id][dmg_round][]=$dmg_round;
                        }
                     elseif ($rand<=ceil(3*$player[fact_params][luck]/2+$player[fact_params][str]/2)) {    // критический удар
                     	$dmg_round["f_id"]="player".$player[id];
                     	$combatlog="<br/>$player[char_name] ударил $enemy[char_name], критическое попадание!";
                     	$tmp=rand(1,100);
                     	if ($tmp<=$player[fact_params][luck]){ //враг опрокинут
                        $dmg_round["crit_bonus"][]="breakdown";
                        $combatlog.=" $enemy[char_name] сбит с ног!";
                     	}
                     	elseif ($tmp<=2*$player[fact_params][luck]){ //монстр пропускает следущий раунд!
                        $dmg_round["crit_bonus"][]="roundloose";
                        $combatlog.=" $enemy[char_name] пропускает следующий ход!";
                     	}
                     	elseif ($tmp<=3*$player[fact_params][luck]){ //защита монстра не учитывается!
                        $damage=$damage+$defense;
                        $combatlog.="Вы пробили защиту $enemy[char_name]!";
                     	}
                     	$tmp=rand(1,100+3*$enemy[fact_params][luck]);
                        if ($tmp<20) {
                        $dmg_round["crit_bonus"][]="lefthand";
                        $combatlog.="$enemy[char_name] ломает левую руку!";
                        }
                        elseif ($tmp<40){
                        $dmg_round["crit_bonus"][]="righthand";
                        $combatlog.="$enemy[char_name] ломает правую руку!";
                        }
                        elseif ($tmp<60){
                        $dmg_round["crit_bonus"][]="leftleg";
                        $combatlog.="$enemy[char_name] ломает левую ногу!";
                                                       }
                        elseif ($tmp<80){
                        $dmg_round["crit_bonus"][]="rightleg";
                        $combatlog.="$enemy[char_name] ломает правую ногу!";
                        }
                        elseif ($tmp<100){
                        $dmg_round["crit_bonus"][]="eye";
                        $combatlog.="$enemy[char_name] повредил глаз!";
                        }
                     	$damage=3*$damage;
                     	$combatlog.="Он получает 3ой урон - ".$damage*3;
                        $dmg_round[damage]=$damage;
                        $dmg_round[str]=$combatlog;
                     	$fighters[$f_id][dmg_round][]=$dmg_round;
                     }
                     else {
                          $combatlog="<br/>$player[char_name] ударил ".$enemy[char_name]." и нанес урон ".$damage;
                     	  $dmg_round["f_id"]="player".$player[id];
                     	  $dmg_round[damage]=$damage;
                     	  $dmg_round[str]=$combatlog;
                     	  $fighters[$f_id][dmg_round][]=$dmg_round;
                     }
                     $pageinfo.="<br/>Вы ударили ".$enemy[char_name]."<br/>";
                     $tmp=serialize($fighters);
                     $sql=mysql_query("UPDATE combats SET fighters='$tmp' WHERE combatid='$combat[combatid]' AND loc_id='$player[loc_id]'");
                     $sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]'");

                  }
              }
              elseif ($_GET[weapon]==1 or $_GET[weapon]==2) {
                  if ($_GET[weapon]==1) {$weapon=$player[weapon1];}
                  else {$weapon=$player[weapon2];}
                  $distance=distance($enemy[x],$enemy[y],$player[x],$player[y]);
                  $needod=3+$weapon[about_item][odbonus];
                  if ($trauma[lefthand]=="on") {$needod++;}
                  if ($trauma[righthand]=="on") {$needod++;}
                  if ($weapon[about_item][type_weap]=="fire") {$needpatron=1;}
                  if ($_GET[mod]=="array") {$needod++;}
                  elseif ($_GET[mod]=="sniper") {$needod++;}
									
                  if (empty($weapon)) {$pageinfo.="<br/> Ошибка! Ошибка! Ошибка!<br/>";}
                  elseif ($distance>10 and $weapon[about_item][type_weap]=="melee") {$pageinfo.="<br/> Цель слишком далеко!<br/>";}
                  elseif ($player[od]<$needod) {$pageinfo.="<br/> Не достаточно ОД! Нужно $needod<br/>";}
                  elseif ($weapon[about_item][type_weap]!="fire" and isset($_GET[mod])) {$pageinfo.="<br/> Ошибка! Не тот тип оружия<br/>";}
                  elseif ($weapon[about_item][type_weap]=="fire" and $weapon[about_item][patrons]<$needpatron) {$pageinfo.="<br/> Ошибка! ".$weapon[name]." надо перезарядить!<br/>";}
                  elseif ( $_GET[mod]=="array" and $weapon[about_item][arrayfire]<=1) {$pageinfo.="<br/> Ошибка! Оружие не стреляет очередями!<br/>";}
                  elseif ($weapon[about_item][type_weap]=="fire" and $weapon[about_item][crush]>=$rand) { //осечка
                      $pageinfo.="</br> Осечка! Выстрел не получился!</br>";
                      $player[od]=$player[od]-$needod;
                      $sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]' LIMIT 1");
                  }
                  else {
                     $f_id="player".$enemy[id];
                     $player[od]=$player[od]-$needod;
                     $mindamage=$weapon[about_item][mindmg];
                     $maxdamage=$weapon[about_item][maxdmg];
                     $uvorot=100-2.5*$enemy[fact_params][dex]-$enemy[fact_params][luck];
                     if ($_GET[mod]=="array") {
                     	$needpatron=$weapon[about_item][arrayfire];
                     	if ($weapon[about_item][patrons]<$needpatron){
                     	  $needpatron=$weapon[about_item][patrons];
                     	}
                     	$mindamage=$mindamage*$needpatron;
                     }
                     $damage=rand($mindamage,$maxdamage);
                     if ($weapon[about_item][type_weap]=="melee") {
                     	$damage=$damage+$player[fact_params][str];
                         $skills[coldweapon][act]++;
                         $uvorot+=$player[fact_params][dex]+$weapon[about_item][sniperbonus]+$skills[coldweapon][level]-$monstr_list[$_GET[id]][bonusdex];
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
                        $uvorot+=$skills[fireweapon][level]+$weapon[about_item][sniperbonus]+intval(ceil(2*$player[fact_params][shooting]));
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
                     if ($weapon[about_item][type_weap]=="throw") {
                     	$skills[throwweapon][act]++;
                        $uvorot+=$skills[throwweapon][level];
                        if ($skills[throwweapon][level]<10){ $skillmode=2*$skills[throwweapon][level];}
                        else{$skillmode=10+$skills[throwweapon][level];}
                     }
                     $kb=$enemy[bodyarm][about_item][kb];
                     if ($kb>ceil($damage*50/100)){$kb=ceil($damage*50/100);}
                     $damage=$damage-$kb;
                     $tmp="res".$weapon[about_item][type_dmg];
                     $defense=$enemy[fact_resists][$tmp];
                     if ($defense>90) {$defense=90;}
                     $defense=intval(ceil($damage*($defense/100)));
                     $damage=$damage-$defense;
                     $damage=ceil($damage*(100+$skillmode)/100);
                     if ($damage<=0) {$damage=1;}
                     $rand=rand(1,100);
					 if ($distance<=20 and $distance>10) {$uvorot=$uvorot-10;}
                     elseif ($distance<=35) {$uvorot=$uvorot-40;}
                     elseif ($distance<=50) {$uvorot=$uvorot-50;}
                     elseif ($distance<=100) {$uvorot=$uvorot-60;}
                     else {$uvorot=$uvorot-70;}
                     if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }
                     if ($_GET[mod]=="sniper") {$uvorot=$uvorot+10;}
                     
                     if ($trauma[eye]=="on") {$uvorot=$uvorot-40;}
                     $uvorot=$uvorot-$enemy[bodyarm][about_item][bonusdex];
                     if ($uvorot<5) {$uvorot=5;}
                     elseif ($uvorot>95) {$uvorot=95;}
                     if ($rand>$uvorot){
                     	$combatlog="<br/> $player[char_name] ";
                     	if ($weapon[about_item][type_weap]=="fire")
                     	{ if ($_GET[mod]=="array") {$combatlog.=" выстрелил очередью в ";}
                     	  elseif ($_GET[mod]=="sniper") {$combatlog.=" прицельно выстрелил в ";}
                     	  else {$combatlog.=" выстрелил в ";}
                     	}
                     	if ($weapon[about_item][type_weap]=="melee")
                     	{ $combatlog.=" ударил ";}
                     	if ($weapon[about_item][type_weap]=="throw")
                     	{ $combatlog.=" швырнул $weapon[name] в ";}
                     	$combatlog.=$enemy[char_name].", но промахнулся";
                        $dmg_round["f_id"]="player".$player[id];
                        $dmg_round[damage]=0;
                        $dmg_round[str]=$combatlog;
                        $fighters[$f_id][dmg_round][]=$dmg_round;
                        }
                     elseif ($rand<=$player[crit_chance]) {    // критический удар
                        $combatlog="<br/> $player[char_name] ";
                     	if ($weapon[about_item][type_weap]=="fire")
                     	{ if ($_GET[mod]=="array") {$combatlog.=" выстрелил очередью в ";}
                     	  elseif ($_GET[mod]=="sniper") {$combatlog.=" прицельно выстрелил в ";}
                     	  else {$combatlog.=" выстрелил в ";}
                     	}
                     	if ($weapon[about_item][type_weap]=="melee")
                     	{ $combatlog.=" ударил ";}
                     	if ($weapon[about_item][type_weap]=="throw")
                     	{ $combatlog.=" швырнул $weapon[name] в ";}
                     	$combatlog.=$enemy[char_name].", критическое попадание!";
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
                     	$tmp=rand(1,100+3*$enemy[fact_params][luck]);
                        if ($tmp<20) {
                        $dmg_round["crit_bonus"][]="lefthand";
                        $combatlog.="$enemy[char_name] ломает левую руку!";
                        }
                        elseif ($tmp<40){
                        $dmg_round["crit_bonus"][]="righthand";
                        $combatlog.="$enemy[char_name] ломает правую руку!";
                        }
                        elseif ($tmp<60){
                        $dmg_round["crit_bonus"][]="leftleg";
                        $combatlog.="$enemy[char_name] ломает левую ногу!";
                                                       }
                        elseif ($tmp<80){
                        $dmg_round["crit_bonus"][]="rightleg";
                        $combatlog.="$enemy[char_name] ломает правую ногу!";
                        }
                        elseif ($tmp<100){
                        $dmg_round["crit_bonus"][]="eye";
                        $combatlog.="$enemy[char_name] повредил глаз!";
                        }
                     	$damage=3*$damage;
                     	$combatlog.=" Он получает 3ой урон - ".$damage*3;
                        $dmg_round[damage]=$damage;
                        $dmg_round[str]=$combatlog;
                     	$fighters[$f_id][dmg_round][]=$dmg_round;
                     }
                     else {
                     	$combatlog="<br/> $player[char_name] ";
                     	if ($weapon[about_item][type_weap]=="fire")
                     	{ if ($_GET[mod]=="array") {$combatlog.=" выстрелил очередью в ";}
                     	  elseif ($_GET[mod]=="sniper") {$combatlog.=" прицельно выстрелил в ";}
                     	  else {$combatlog.=" выстрелил в ";}
                     	}
                     	if ($weapon[about_item][type_weap]=="melee")
                     	{ $combatlog.=" ударил ";}
                     	if ($weapon[about_item][type_weap]=="throw")
                     	{ $combatlog.=" швырнул $weapon[name] в ";}
                     	$combatlog.=$enemy[char_name]." и нанес урон ".$damage;
                     	  $dmg_round["f_id"]="player".$player[id];
                     	  $dmg_round[damage]=$damage;
                     	  $dmg_round[str]=$combatlog;
                     	  $fighters[$f_id][dmg_round][]=$dmg_round;
                     }
                     $pageinfo.="<br/> Вы ";
                     	if ($weapon[about_item][type_weap]=="fire")
                     	{ if ($_GET[mod]=="array") {$pageinfo.=" выстрелили очередью в ";}
                     	  elseif ($_GET[mod]=="sniper") {$pageinfo.=" прицельно выстрелили в ";}
                     	  else {$pageinfo.=" выстрелили в ";}
                     	}
                     	if ($weapon[about_item][type_weap]=="melee")
                     	{ $pageinfo.=" ударили ";}
                     	if ($weapon[about_item][type_weap]=="throw")
                     	{ $pageinfo.=" швырнули $weapon[name] в ";}
                     	$pageinfo.=$enemy[char_name]."<br/>";
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
$pagedown.="<br/><a href='./?attack=player&amp;id=$_GET[id]&amp;'>Обновить</a>";
$pagedown.="<br/><a href='./?action=end_round'>Закончить ход</a><br/>";
        $uvorot=100-2.5*$enemy[fact_params][dex]-$enemy[fact_params][luck];
        $uvorot=$uvorot-$enemy[bodyarm][about_item][bonusdex];
        $distance=distance($enemy[x],$enemy[y],$player[x],$player[y]);
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
            if ($player[weapon1][about_item][type_weap]=="melee") {            	$uvorot+=$player[fact_params][dex]+$player[weapon1][about_item][sniperbonus]+$skills[coldweapon][level]-$monstr_list[$_GET[id]][bonusdex];
                if ($distance<=10) {
            	if ($uvorot<10) {$uvorot=10;}
                elseif ($uvorot>95) {$uvorot=95;}
            	$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=1'>Удар[$needod ОД][$uvorot%]</a>";
            	} else {$pagedown.="<br/><img src='/img/icon/cancel.PNG'/> Противник слишком далеко";}
            }
						
            elseif ($distance>10 and $player[weapon1][about_item][type_weap]=="fire") {            	$uvorot+=$skills[fireweapon][level]+intval(ceil(2*($player[fact_params][shooting]+$player[weapon1][about_item][sniperbonus])));
                if ($trauma[eye]=="on") {$uvorot=$uvorot-40;}
                if ($uvorot<10) {$uvorot=10;}
                elseif ($uvorot>95) {$uvorot=95;}
            	$pagedown.="[".$player[weapon1][about_item][patrons]."/".$player[weapon1][about_item][maxpatrons]."]<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=1'>Выстрел[$needod ОД][$uvorot%]</a> ";
            	$tmp=$uvorot+10;
            	if ($tmp>95) {$tmp=95;}
            	$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=1&amp;mod=sniper'>Прицельный выстрел[".($needod+1)." ОД][$tmp%]</a> ";
            	if ($player[weapon1][about_item][arrayfire]>0) {$uvorot=$uvorot-30; $pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=1&amp;mod=array'>Очередь[".$player[weapon1][about_item][arrayfire]."][".($needod+1)." ОД][$uvorot%]</a> ";}
              $pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?do=inv&amp;act=reload&amp;item=weapon1&amp;attack=player&amp;id=$enemy[id]'>Перезарядка[".($needod-1-$player[weapon1][about_item][odbonus])." ОД]</a> ";
            }
						}
						
            elseif ($distance>25 and $player[weapon1][about_item][type_weap]=="throw") {            	$uvorot+=$skills[throwweapon][level];
            	if ($uvorot<10) {$uvorot=10;}
                elseif ($uvorot>95) {$uvorot=95;}
                $bag=unserialize($player[bag]);
                for ($i=0;$i<sizeof($bag);$i++){
                	if  ($bag[$i][id]==$player[weapon1][id] and $bag[$i][name]==$player[weapon1][name] and $bag[$i][info]==$player[weapon1][info])
                	{$colvo=$bag[$i][colvo]; break;}
                }
            	$pagedown.="[".($colvo+1)."]<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=1'>Швырнуть[$needod ОД][$uvorot%]</a>";}
            $pagedown.="<br/>";
        }
				

        $uvorot=100-2.5*$enemy[fact_params][dex]-$enemy[fact_params][luck];
        $uvorot=$uvorot-$enemy[bodyarm][about_item][bonusdex];
        if ($distance<=20 and $distance>10) {$uvorot=$uvorot-10;}
        elseif ($distance<=35) {$uvorot=$uvorot-40;}
        elseif ($distance<=50) {$uvorot=$uvorot-50;}
        elseif ($distance<=100) {$uvorot=$uvorot-60;}
        else {$uvorot=$uvorot-70;}
        if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }
               
        if (!empty($player[weapon2])) {
        	$uvorot=$uvorot+$player[weapon2][about_item][sniperbonus];
					
            $needod=3+$player[weapon2][about_item][odbonus];
            if ($trauma[lefthand]=="on") {$needod++;}
            if ($trauma[righthand]=="on") {$needod++;}
        	$pagedown.="<br/><b>".$player[weapon2][name]."</b>";
            if ($player[weapon2][about_item][type_weap]=="melee" ) {            	$uvorot+=$player[fact_params][dex]+$player[weapon2][about_item][sniperbonus]+$skills[coldweapon][level]-$monstr_list[$_GET[id]][bonusdex];
                if ($distance<=10) {
            		if ($uvorot<5) {$uvorot=5;}
                	elseif ($uvorot>95) {$uvorot=95;}
            		$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=2'>Удар[$needod ОД][$uvorot%]</a>";
            	} else {$pagedown.="<br/><img src='/img/icon/cancel.PNG'/> Противник слишком далеко";}
            }
						
            elseif ($distance>10 and $player[weapon2][about_item][type_weap]=="fire") {            	$uvorot+=$skills[fireweapon][level]-intval(ceil(2*($player[fact_params][shooting]+$player[weapon2][about_item][sniperbonus])));
                if ($trauma[eye]=="on") {$uvorot=$uvorot-40;}
                if ($uvorot<5) {$uvorot=5;}
                elseif ($uvorot>95) {$uvorot=95;}
            	$pagedown.="[".$player[weapon2][about_item][patrons]."/".$player[weapon2][about_item][maxpatrons]."]<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=2'>Выстрел[$needod ОД][$uvorot%]</a> ";
            	$tmp=$uvorot+10;
            	if ($tmp>95) {$tmp=95;}
            	$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=2&amp;mod=sniper'>Прицельный выстрел[".($needod+1)." ОД][$uvorot%]</a> ";
            	if ($player[weapon2][about_item][arrayfire]>0) {$uvorot=$uvorot-30; $pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=2&amp;mod=array'>Очередь[".$player[weapon2][about_item][arrayfire]."][".($needod+1)." ОД][$uvorot%]</a> ";}
                $pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?do=inv&amp;act=reload&amp;item=weapon2&amp;attack=player&amp;id=$enemy[id]'>Перезарядка[".($needod-1-$player[weapon2][about_item][odbonus])." ОД]</a> ";
            }
						
						
            elseif ($distance>25 and $player[weapon2][about_item][type_weap]=="throw") {            	$uvorot+=$skills[trowweapon][level];
            	if ($uvorot<10) {$uvorot=10;}
                elseif ($uvorot>95) {$uvorot=95;}
                $bag=unserialize($player[bag]);
                for ($i=0;$i<sizeof($bag);$i++){
                	if  ($bag[$i][id]==$player[weapon2][id] and $bag[$i][name]==$player[weapon2][name] and $bag[$i][info]==$player[weapon2][info])
                	{$colvo=$bag[$i][colvo]; break;}
                }
            	$pagedown.="[".($colvo+1)."]<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=2'>Швырнуть[$needod ОД][$uvorot%]</a>";}
            $pagedown.="<br/>";
            }
        
        if ($distance<=10) {
            $uvorot=100-2.5*$enemy[fact_params][dex]-$enemy[fact_params][luck];
            $uvorot=$uvorot-$enemy[bodyarm][about_item][bonusdex];
        	if ($distance<=20 and $distance>10) {$uvorot=$uvorot-10;}
        	elseif ($distance<=35) {$uvorot=$uvorot-40;}
	        elseif ($distance<=50) {$uvorot=$uvorot-50;}
        	elseif ($distance<=100) {$uvorot=$uvorot-60;}
        	else {$uvorot=$uvorot-50;}
        	if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                	 elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                	 $uvorot=$uvorot-20;
                	 }
            $uvorot+=$skills[handfight][level]-$player[fact_params][dex]-$monstr_list[$_GET[id]][bonusdex];
						
        	if ($uvorot<10) {$uvorot=10;}
            elseif ($uvorot>95) {$uvorot=95;}
        	$needod=3;
        	if ($trauma[lefthand]=="on") {$needod++;}
        	if ($trauma[righthand]=="on") {$needod++;}
        	$pagedown.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;weapon=hand'>Атаковать в рукопашную[$needod ОД][$uvorot%] </a>";}

		$needod=2;
        if ($trauma[lefthand]=="on") {$needod++;}
        if ($trauma[righthand]=="on") {$needod++;}
        $pagedown.="<br/><img src='/img/icon/yad.PNG'/> <a href='./?do=use&amp;what=what&amp;target=self'>Использовать[$needod ОД]</a><br/>";
        $needod=3;

        if ($trauma[leftleg]=="on") {$needod++;}
        if ($trauma[rightleg]=="on") {$needod++;}
        if ($distance>5) {$pagedown.="<br/><img src='/img/icon/go.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;go=to'>Подойти поближе[$needod ОД] </a>";}
        if ($distance<=100) {$pagedown.="<br/><img src='/img/icon/go.PNG'/> <a href='./?attack=player&amp;id=$enemy[id]&amp;go=away'>Отойдти подальше[$needod ОД] </a>";}
        $pagedown.="<br/><a href='./?action=end_round'>Закончить ход</a><br/><br/>";

      
?>