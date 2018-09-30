<?
   $endcombat=0;
   $sql = mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' AND combatid='$status[infight]' LIMIT 1");
   if (mysql_num_rows($sql)!=1){$endcombat=1;$error=1;}
   else{
      $combat = mysql_fetch_array($sql);
      $fighters=unserialize($combat[fighters]);

      if ($_GET[action]=="end_round"){
       if ($player[od]>=$player[maxod]){       	  $pageinfo.="<br/> Вы не можете закончить ход, пока ваши ОД полные!<br/>";       }
       else{
   			$pageinfo.="<br/> Вы закончили ход!<br/>";
   	   	if ($player[od]>0)	{
   			$fighters["player".$player[id]][od]=$player[maxod];
   			$player[od]=0;
   			$sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]' LIMIT 1");
   		}
   	   }
   	  }
   	 if (!is_array($fighters)){$endcombat=1;}
   	 else{
      foreach($fighters as $key=>$value){
      	   $combatlog="";
      	   $tmppage="";
      	   if (substr($key,0,6)=="player") {
      	   	  if ($fighters["player".$player[id]][last_target]==$key){$targenemy=1;}
              $id=substr($key,6);
              if ($id==$player[id]) {
              	$od=$player[od];
              	$user[onlinetime]=$player[onlinetime];
              } else
              { $sql=mysql_query("SELECT od,onlinetime FROM users WHERE id='$id' LIMIT 1");
              	$user=mysql_fetch_array($sql);
                $od=$user[od];
              }
              if ($od>0 and ($user[onlinetime]-time())<5*60) {$end_round="no";}
      	   }
           elseif (substr($key,0,6)=="monstr"){     //ход монстра
               $id=substr($key,6);
               if ($monstr_list[$id][status]=="dead" or $monstr_list[$id][hit_points]<1){$monstr_list[$id][od]=0;}
               while  ($monstr_list[$id][od]>0) {
                  $tmppage="";
                  $combatlog="";
                  if ($monstr_list[$id][status]=="dead") {break;};
                   if ($fighters[$key][breakdown]=="yes" ){
                      if ($monstr_list[$id][od]>=3){
                       $fighters[$key][breakdown]="";
                       $monstr_list[$id][od]=$monstr_list[$id][od]-3;
                       $combatlog.="<br/> ".$monstr_list[$id][name]." поднялся";
                       $tmppage.= "<br/> ".$monstr_list[$id][name]." поднялся";
                       if (substr($fighters[$key][last_target],0,6)=="player"){$fighters[$fighters[$key][last_target]][string][]=$tmppage;}
                       }
                      else
                      {  $fighters[$key][ost_od]=$monstr_list[$id][od];
                         $monstr_list[$id][od]=0;
                      }
                   }
                   elseif ($monstr_list[$id][medic]>0 and $monstr_list[$id][od]>=2 and $monstr_list[$id][hit_points]<($monstr_list[$id][maxhp])/2)  { //если монстр может лечиться
                       $monstr_list[$id][medic]=$monstr_list[$id][medic]-1;
                       $medicine=intval(ceil($monstr_list[$id][maxhp]/10));
                       $monstr_list[$id][hit_points]=$monstr_list[$id][hit_points]+$medicine;
                       $monstr_list[$id][od]=$monstr_list[$id][od]-2;
                       $combatlog.="<br/> ".$monstr_list[$id][name]." подлечился на $medicine здоровья";
                       $tmppage.="<br/> ".$monstr_list[$id][name]." подлечился на $medicine здоровья";
                       if (substr($fighters[$key][last_target],0,6)=="player"){$fighters[$fighters[$key][last_target]][string][]=$tmppage;}
                   }elseif ($monstr_list[$id][od]>=3)
                   {
                     if (!empty($fighters[$key][last_target]))  // Существует ли прошлая цель?
                         {  $here=0;
                            foreach($fighters as $k=>$val)      // Проверка в бою ли прошлая цель!
                             { if ($k==$fighters[$key][last_target]) {$here=1; break;} }

                            if ($here=1)
                            {
                                     $enemytype=substr($fighters[$key][last_target],0,6);
                                     $enemyid=substr($fighters[$key][last_target],6);
                                     if ($enemytype=="player")
                                     {
                                       if ($player[id]==$enemyid) {$enemy=$player;}
                                       else{
                                       	$sql=mysql_query("SELECT fact_params,char_name,fact_resists,bodyarm,x,y FROM users WHERE id='$enemyid' LIMIT 1");
                                        $enemy=mysql_fetch_array($sql);
                                       }
                                       $enemyname=$enemy[char_name];
                                       $enemy[fact_params]=unserialize($enemy[fact_params]);
                                       $enemy[fact_resists]=unserialize($enemy[fact_resists]);
                                       $bodyarm=unserialize($enemy[bodyarm]);
                                       $x=$enemy[x];
                                       $y=$enemy[y];
                                       $damage=$monstr_list[$id][damage];
                                       $defense=$enemy[fact_resists]["res".$monstr_list[$id][type_dmg]];
                                       $kb=$bodyarm[about_item][kb];
                                       if ($kb>ceil($damage*50/100)){$kb=ceil($damage*50/100);}
                                       $damage=$damage-$kb;
                                       if ($defense>90) {$defense=90;}
                                       $uvorot=100-$enemy[fact_params][dex]*2.5-$enemy[fact_params][luck];
                                     } elseif ($enemytype=="monstr")
                                     {
                                       $kb=0;
                                       $enemyname=$monstr_list[$enemyid][name];
                                       $defense=$monstr_list[$enemyid][$monstr_list[$id][type_dmg]];
                                       $uvorot=100-$monstr_list[$enemyid][bonusdex];
                                       $x=$monstr_list[$enemyid][x];
                                       $y=$monstr_list[$enemyid][y];
                                     }
                                     $distance=distance($monstr_list[$id][x],$monstr_list[$id][y],$x,$y);
                                     if  (($monstr_list[$id][maxpatrons]<1 and $distance>10) or $distance>50)
                                      {   $monstr_list[$id][od]=$monstr_list[$id][od]-3;
                                      	  if ($distance<=20) {$need=5;}
          		   						  elseif ($distance<=35) {$need=15;}
          		    					  elseif ($distance<=50) {$need=30;}
          		    					  elseif ($distance<=100) {$need=45;}
						          		  else {$need=90;}
                                          $k=$need/$distance;
          		   						  $dx=$monstr_list[$id][x]-$x;
          		   						  $dy=$monstr_list[$id][y]-$y;
          		    					  $monstr_list[$id][x]=intval(round($dx*$k+$x));
          		    					  $monstr_list[$id][y]=intval(round($dy*$k+$y));
          		    					  $combatlog.="<br/> ".$monstr_list[$id][name]." подошел ближе к ".$enemyname;
          		    					  $tmppage.= "<br/> ".$monstr_list[$id][name]." подошел ближе к ".$enemyname;
          		    					  if ($enemytype="player"){
                       					 	    $fighters[$fighters[$key][last_target]][string][]=$tmppage;
                       					  }
                                      }
                                     elseif ($monstr_list[$id][maxpatrons]>0 and $monstr_list[$id][patrons]<=0){
                                        $monstr_list[$id][patrons]=$monstr_list[$id][maxpatrons];
                                        $monstr_list[$id][od]=$monstr_list[$id][od]-2;
                                        $combatlog.="<br/> ".$monstr_list[$id][name]." перезарядил свое оружие.";
                                        $tmppage.="<br/> ".$monstr_list[$id][name]." перезарядил свое оружие.";
                                        if ($enemytype="player"){
                       					 	    $fighters[$fighters[$key][last_target]][string][]=$tmppage;
                       					}
                                     }
                                     else {
                                       $monstr_list[$id][od]=$monstr_list[$id][od]-3;
                                       $defense=intval(ceil($damage*($defense/100)));
                                       $damage=$damage-$defense;
                                       if ($distance<=20 and $distance>10) {$uvorot=$uvorot-10;}
                                       elseif ($distance<=35) {$uvorot=$uvorot-20;}
                                       elseif ($distance<=50) {$uvorot=$uvorot-30;}
                                       if  ($monstr_list[$id][maxpatrons]>0) {$monstr_list[$id][patrons]=$monstr_list[$id][patrons]-1;}
                                       $uvorot=$uvorot-$bodyarm[about_item][bonusdex];
                                       if ($location[loc_option][light]=="never") {$uvorot=$uvorot-20;}
                						elseif ($location[loc_option][light]=="temp" and $game[light]=="night") {
                						$uvorot=$uvorot-20;
                						}

                                       if ($uvorot<10) {$uvorot=10;}
                    				   elseif ($uvorot>95) {$uvorot=95;}
                                       $rand=mt_rand(1,100);
$sql=mysql_query("SELECT *  FROM items WHERE id=38 LIMIT 1");//гильзы
$item=mysql_fetch_array($sql);
$item['colvo']=1;
$item['about_item']=unserialize($item['about_item']);
add_to_garbage($player['loc_id'],$item);
                                       if ($enemytype="player"){$fart=4*$enemy[fact_params][luck];} else {$fart=0;}
                                       if ($rand<$uvorot) {
                                         $combatlog.="<br/> ".$monstr_list[$id][name]." атаковал $enemyname, но промазал!";
                                         $tmppage.="<br/> ".$monstr_list[$id][name]." атаковал Вас!";
                                         $dmg_round[damage]=0;
                       					 $dmg_round[f_id]=$key;
                       					 $dmg_round[str]=$combatlog;
                     					 $fighters[$fighters[$key][last_target]][dmg_round][]=$dmg_round;
                                       }
                                       elseif ($rand<=$monstr_list[$id][crit_chance] and rand(1,100)>$fart) { // критическое попадание
                                       				$combatlog.="<br/> ".$monstr_list[$id][name]." атаковал $enemyname. Критический удар!";
                                                    $tmppage.="<br/> ".$monstr_list[$id][name]." атаковал Вас!";
                                                    $tmp=20+$monstr_list[$id][crit_chance];
                                                    $rnd=mt_rand(1,100);
                                                    if ($rnd<=$tmp){ //опрокинут
                        							$dmg_round["crit_bonus"][]="breakdown";
                        							$combatlog.=" $enemyname опрокинут навзначь!";
                        							$wascrit=1;
                     								}
                     							    elseif ($rnd<=$tmp+20){ // пропускает следущий раунд!
                        						    	$dmg_round["crit_bonus"][]="roundloose";
                        						    	$combatlog.=" $enemyname пропускает следующий ход!";
                        						    	$wascrit=1;
                     								}
                     								elseif ($rnd<=$tmp+40){ //защита не учитывается!
                        								$damage=$damage+$defense;
                        								$combatlog.="Защита врага $enemyname не учитывается!";
                        								$wascrit=1;
                     								}
                     								if ($enemytype="player"){
                                                       $tmp=mt_rand(1,100+3*$enemy[fact_params][luck]);
                                                       if ($tmp<15) {
                                                       $dmg_round["crit_bonus"][]="lefthand";
                                                       $combatlog.="$enemyname ломает левую руку!";
                                                       $wascrit=1;
                                                       }
                                                       elseif ($tmp<35){
                                                       $dmg_round["crit_bonus"][]="righthand";
                                                       $combatlog.="$enemyname ломает правую руку!";
                                                       $wascrit=1;
                                                       }
                                                       elseif ($tmp<50){
                                                       $dmg_round["crit_bonus"][]="leftleg";
                                                       $combatlog.="$enemyname ломает левую ногу!";
                                                       $wascrit=1;
                                                       }
                                                       elseif ($tmp<65){
                                                       $dmg_round["crit_bonus"][]="rightleg";
                                                       $combatlog.="$enemyname ломает правую ногу!";
                                                       $wascrit=1;
                                                       }
                                                       elseif ($tmp<80){
                                                       $dmg_round["crit_bonus"][]="eye";
                                                       $combatlog.="$enemyname повредил глаз!";
                                                       $wascrit=1;
                                                       }

                     								}
                     								if ($wascrit!=1){
                     									$damage=3*$damage;
                     						        	$combatlog.="Он получает 3ой урон - ".$damage*3;
                     						        }
                     						        $wascrit=0;
                       						$dmg_round[damage]=$damage;
                       						$dmg_round[f_id]=$key;
                       						$dmg_round[str]=$combatlog;
                     						$fighters[$fighters[$key][last_target]][dmg_round][]=$dmg_round;
                     						if ($enemytype="player"){
                       					 	$fighters[$fighters[$key][last_target]][string][]=$tmppage;
                       					 	}
                                       }else
                                       {      $combatlog.="<br/> ".$monstr_list[$id][name]." нанес $enemyname урон ".$damage;
                     	  					  $tmppage.="<br/> ".$monstr_list[$id][name]." атаковал Вас";
                     	  					  $dmg_round["f_id"]=$key;
                     	                      $dmg_round[damage]=$damage;
                     	                      $dmg_round[str]=$combatlog;
                     	                      $fighters[$fighters[$key][last_target]][dmg_round][]=$dmg_round;
                                              if ($enemytype="player"){
                       					 	    $fighters[$fighters[$key][last_target]][string][]=$tmppage;
                       					 	   }
                                       }
                                     }



                             }else
                             {$fighters[$key][last_target]="";}

                         }else //если last_target не существует
                         {  $fighters=array_shuffle($fighters);
                         	foreach($fighters as $k=>$val)      // ищем новую цель!
                             { $enemytype=substr($k,0,6);
                               $enemyid=substr($k,6);

                               if ($enemytype=="player" and $monstr_list[$id][comanda]!="npc") { $fighters[$key][last_target]=$k;break;}
                               elseif ($enemytype=="monstr" and $monstr_list[$id][comanda]!=$monstr_list[$enemyid][comanda])
                               {$fighters[$key][last_target]=$k; break;}
                               else {unset_as_mass($fighters,$key);}
                             }

                         }


                   }   else    // завершить ход
                   {  $fighters[$key][od]=$monstr_list[$id][maxod];
                      $monstr_list[$id][od]=0;
                   }


               }
           }
      }
      if ($combat[end_round]<time() or $end_round!="no") {   //конец раунда
            $combat[end_round]=time()+60;
            $combat["round"]++;
            $targetkill=0;
            $newcombatlog="";
            foreach ($fighters as $key=>$value){            	    $dead=0;
            		$combatlog="";
                    $type=substr($key,0,6);
                    $id=substr($key,6);
                    $dmg_round=$fighters[$key][dmg_round];
                     if ($type=="player") {
                        $myid=$player[id];
                        if ($id!=$player[id])
              			{ $sql=mysql_query("SELECT * FROM users WHERE id='$id' LIMIT 1");
               			  $tmpplayer=$player;
               			  $tmpstatus=$status;
               			  $player=mysql_fetch_array($sql);
               			  $status=unserialize($player[status]);
              			}
                           if ($player[od] >0) {$fighters[$key][od]=$player[maxod];}
                           if ($fighters[$key][od]>0) {$player[od]=$player[maxod]=$fighters[$key][od];}
                           else {$player[od]=$player[maxod];}
                           $fighters[$key][od]=0;
                           $trauma=unserialize($player[trauma]);
                          if (is_array($dmg_round)) {
                           for ($i=0;$i<sizeof($dmg_round);$i++) {
                           	  $exp=0;
                              if ($player[hit_points]>0) {
                           		$player[hit_points]=$player[hit_points]-$dmg_round[$i][damage];
                              	if  ($player[hit_points]<$dmg_round[$i][damage]) {
                                    $exp=$player[hit_points];
                              	} else{$exp=$dmg_round[$i][damage];}
                                if ($player[hit_points]<=0)  {
                                  $dmg_round[$i][str].=". $player[char_name] погибает!";
                                  $targetkill=1;
                                  $players--;
                                  //player_die($player);
                                  $dead=1;
                                  if ($myid==$player[id]) {$killme=1;
                                   $killstr=$dmg_round[$i][str];
                                  }
                                  else{
                                  	$player[od]=$player[maxod];
                                  	$sql=mysql_query("SELECT count(id) FROM users WHERE party='$player[party]'");
                                  	$count=mysql_result($sql,0,0);
                                  	if ($count<2){
                                  	   $player[loc_id]=$player[citizen];
                                  	}
                                  	$status[hospital]["time"]=time();
                                  	$status[hospital][info]=$dmg_round[$i][str];
                                  	$status[infight]="no";
                                  	$player[hit_points]=5;
                                  	$tmp=serialize($status);
                                  	$sql=mysql_query("UPDATE users SET status='$tmp',hit_points='$player[hit_points]',loc_id='$player[loc_id]',od='$player[od]' WHERE id='$player[id]' LIMIT 1");
                                  }
                                }
                                if (substr($dmg_round[$i][f_id],0,6)=="player") {
                                   $enemyid=substr($dmg_round[$i][f_id],6);
                                   $sql=mysql_query("SELECT exp, char_name, fact_params,status FROM users WHERE id='$enemyid' LIMIT 1");
                                   $enemy=mysql_fetch_array($sql);
                                   $quest=unserialize($player[quests]);
                                   if (!is_array($enemy[fact_params])) {$enemy[fact_params]=unserialize($enemy[fact_params]);}
                                   if ($targetkill==1){
                                   	   if (!is_array($enemy[status])){$enemy[status]=unserialize($enemy[status]);}
                                       $status[rate][loose][pvp]["count"]++;
                                       $status[rate][loose][pvp][$enemyid]++;
                                       $enemy[status][rate][win][pvp]["count"]++;
									   if($quest[povstanec][status]=="complete") {$o='povstanec';}
									   elseif($quest[boecness][status]=="complete") {$o='boecness';}
									   elseif($quest[naemnik][status]=="complete") {$o='naemnik';}
                                       $enemy[status][rate][win][$o]++;
                                       $enemy[status][rate][win][pvp][$player[id]]++;
                                   	   $enemy[status]=serialize($enemy[status]);
                                   }
                                    if ($exp>0){                                    	if (!is_array($enemy[fact_resists])) {$enemy[fact_resists]=unserialize($enemy[fact_resists]);}
                                   		$bon=($enemy[fact_resists][resnormal]+$enemy[fact_resists][resplazma]+$enemy[fact_resists][resboom]+$enemy[fact_resists][resvolt])/4;
                                   		$priz=intval(floor($exp*(10+$enemy[fact_params][int]+($bon/10))/10));
                                    	$enemy["exp"]=$enemy["exp"]+$priz;
                                    } $bon=0;
                                   $sql=mysql_query("UPDATE users SET exp='$enemy[exp]',status='$enemy[status]' WHERE id='$enemyid' LIMIT 1");
                                }
                                elseif (substr($dmg_round[$i][f_id],0,6)=="monstr" and $targetkill==1) {
                                     $enemyid=substr($dmg_round[$i][f_id],6);
                                     $status[rate][loose][pve]["count"]++;
                                     $status[rate][loose][pve][$monstr_list[$enemyid][id]]++;
                                }
                                $targetkill=0;
                                }
                              else{$dead=1;}
                              if (!empty($dmg_round[$i][crit_bonus])) {

                                  for ($k=0;$k<sizeof($dmg_round[$i][crit_bonus]);$k++) {
                                     if ($dmg_round[$i][crit_bonus][$k]=="breakdown") {$fighters[$key][breakdown] = "yes";}
                                     elseif ($dmg_round[$i][crit_bonus][$k]=="roundloose") {$player[od]=0;}
                                     elseif ($dmg_round[$i][crit_bonus][$k]=="lefthand") {$trauma[lefthand]="on";}
                                     elseif ($dmg_round[$i][crit_bonus][$k]=="righthand") {$trauma[righthand]="on";}
                                     elseif ($dmg_round[$i][crit_bonus][$k]=="leftleg") {$trauma[leftleg]="on";}
                                     elseif ($dmg_round[$i][crit_bonus][$k]=="rightleg") {$trauma[rightleg]="on";}
                                     elseif ($dmg_round[$i][crit_bonus][$k]=="eye") {$trauma[eye]="on";}
                                  }
                              }
                                $fighters[$key][string][]= $dmg_round[$i][str];
                                $combatlog.=$dmg_round[$i][str];
                                if (substr($dmg_round[$i][f_id],0,6)=="player") {
                                  $fighters[$dmg_round[$i][f_id]][string][]= $dmg_round[$i][str];
                                }
                           }
                          }
                           $trauma=serialize($trauma);
                           $tmp=serialize($status);
                           $sql=mysql_query("UPDATE users SET status='$tmp',trauma='$trauma',od='$player[od]',hit_points='$player[hit_points]' WHERE id='$player[id]' LIMIT 1");
                           if (!empty($tmpplayer) and !empty($tmpstatus))  {
                              $player=$tmpplayer;
               			      $status=$tmpstatus;
               			      $tmpplayer="";
               			      $tmpstatus="";}



                     }
                     elseif ($type=="monstr") {
                           if ($monstr_list[$id][status]=="dead"){$dead=1;}
                           if ($monstr_list[$id][od] >0) {$fighters[$key][ost_od]=$monstr_list[$id][od];}
                           $monstr_list[$id][od]=$monstr_list[$id][maxod]+$fighters[$key][ost_od];
                           $fighters[$key][ost_od]=0;
                           if (is_array($dmg_round)) {
                            for ($i=0;$i<sizeof($dmg_round);$i++) {
                            	$targetkill=0;
                            	$exp=0;
                              if ($monstr_list[$id][hit_points]>0) {
                              	if  ($monstr_list[$id][hit_points]<$dmg_round[$i][damage]) {
                                    $exp=$monstr_list[$id][hit_points];
                              	} else{$exp=$dmg_round[$i][damage];}
                           		$monstr_list[$id][hit_points]=$monstr_list[$id][hit_points]-$dmg_round[$i][damage];
                                if ($monstr_list[$id][hit_points]<=0)  {
                                  $dmg_round[$i][str].=". ".$monstr_list[$id][name]." погибает!";
                                  $targetkill=1;
                                  $dead=1;
                                  foreach($fighters as $k=>$val){                                    if ($fighters[$k][last_target]==$key) {$fighters[$k][string][]=$dmg_round[$i][str];}                                  }
                                  $monstr_list[$id][status]="dead";
                                  $monstr_list[$id][in_fight]="";
                                  $monstr_list[$id][respawn]=time();
                                  eval($monstr_list[$id][on_die]);
                                }
                              }
                                else{$dead=1;}
                              if (!empty($dmg_round[$i][crit_bonus])) {

                                  for ($k=0;$k<sizeof($dmg_round[$i][crit_bonus]);$k++) {
                                     if ($dmg_round[$i][crit_bonus][$k]=="breakdown") {$fighters[$key][breakdown] = "yes";}
                                     elseif ($dmg_round[$i][crit_bonus][$k]=="roundloose") {$monstr_list[$id][od]=0;}

                                  }
                              }
                              if (substr($dmg_round[$i][f_id],0,6)=="player" and $exp>0) {
                                   $enemyid=substr($dmg_round[$i][f_id],6);
                                   if ($enemyid==$player[id]) {
                                              $enemy["exp"]=$player["exp"];
                                              $enemy[char_name]=$player[char_name];
                                              $enemy[status]=$status;
                                              $enemy[fact_params]=$player[fact_params];
                                              $enemy[fact_resists]=$player[fact_resists];
                                              $enemy[skills]=$player[skills];
                                   }
                                   else{
                                   	$sql=mysql_query("SELECT exp, char_name,status, fact_resists, fact_params, skills FROM users WHERE id='$enemyid' LIMIT 1");
                                   	$enemy=mysql_fetch_array($sql);
                                   	$enemy[status]=unserialize($enemy[status]);
                                   }
                                   	$enemy[fact_params]=unserialize($enemy[fact_params]);
                                   	$enemy[fact_rseists]=unserialize($enemy[fact_resists]);
                                   	$enemy[skills]=unserialize($enemy[skills]);

                                   $per=3*$enemy[skills][per][level];
                                   $enemy[skills][per][act]++;
                                   $enemy[skills]=serialize($enemy[skills]);
                                   $bon=($monstr_list[$id][resnormal]+$monstr_list[$id][resplazma]+$monstr_list[$id][resboom]+$monstr_list[$id][resvolt])/4;
                                   $priz=intval(floor($exp*(10+$enemy[fact_params][int]+($bon/10))/10));
                                   $bon=0;
                                   $enemy["exp"]=$enemy["exp"]+$priz;
                                   if ($targetkill==1){
                                       $enemy[status][rate][win][pve]["count"]++;
                                       $enemy[status][rate][win][pve][$monstr_list[$id][id]]++;

                                   }  $tmp=serialize($enemy[status]);
                                   $sql=mysql_query("UPDATE users SET status='$tmp',exp='$enemy[exp]',skills='$enemy[skills]' WHERE id='$enemyid' LIMIT 1");
                                   if ($enemyid==$player[id]) {
                                   		      $enemy[fact_params]=serialize($enemy[fact_params]);
                                              $player["exp"]=$enemy["exp"];
                                              $player[char_name]=$enemy[char_name];
                                              $status=$enemy[status];
                                              $player[fact_params]=$enemy[fact_params];
                                              $player[skills]=$enemy[skills];
                                   }
                              }
                              $targetkill=0;
                                $combatlog.=$dmg_round[$i][str];
                                if (substr($dmg_round[$i][f_id],0,6)=="player"  and $dead!=1) {
                                  $fighters[$dmg_round[$i][f_id]][string][]= $dmg_round[$i][str];
                                  //$pageinfo.="<i>[debug]".$dmg_round[$i][f_id]." -".$dmg_round[$i][str]." - ".$fighters["player".$player[id]][string]."[debug]</i>";
                                }
                            }  $tmp=$key;
                           }
                     }
                     $newcombatlog.=$combatlog;
                     $fighters[$key][dmg_round]="";
                     if ($dead==1) {$newfighters[$key]=$fighters[$key];}
            }
            if (!empty($newcombatlog)) {
                    $sql=mysql_query("SELECT combatlog FROM combats WHERE loc_id='$player[loc_id]' and combatid='$status[infight]' LIMIT 1");
                    $tmp=mysql_result($sql,0,"combatlog");
                    $tmp=unserialize($tmp);
                    $tmp[]="<br/><b>Раунд ".($combat["round"]-1)."</b>".$newcombatlog;
                    $tmp=serialize($tmp);
                    $sql=mysql_query("UPDATE combats SET combatlog='$tmp'  WHERE loc_id='$player[loc_id]' and combatid='$status[infight]' LIMIT 1");
            }
            unset($tmp);
            foreach($fighters as $key=>$value){              if (!isset($newfighters[$key])){$tmp[$key]=$fighters[$key];}            }
            $fighters=$tmp;
      }
     }
   }
   $have_enemy=0;
   if  (!empty($fighters["player".$player[id]][string])) {
            for($i=0;$i<sizeof($fighters["player".$player[id]][string]);$i++){
            	$pageinfo.=$fighters["player".$player[id]][string][$i];
            }  $pageinfo.="<br/>";
            $fighters["player".$player[id]][string]="";
   }
   $users=0;
   if ($killme==1){
       		$fighters=unset_as_mass($fighters,"player".$player[id]);
   }
   if (!empty($fighters)){
     foreach($fighters as $key=>$value){        if ($fighters[$key][last_target]=="player".$player[id] and $killme!=1){$have_enemy++;}
        if ($fighters["player".$player[id]][last_target]==$key  and $killme!=1){$targenemy=1;}
        //$id=substr($key,6);
        //if ($monstr_list[$id][status]=="dead" or $monstr_list[$id][hit_points]<1){$monstr_list[$id][od]=0;}
        if (substr($key,0,6)=="player" and $key!="player".$player[id]){        	$users++;
        	$enemyid=substr($fighters[$key][last_target],6);
        	$enemytype=substr($fighters[$key][last_target],0,6);
        	if ($enemytype==monstr and $monstr_list[$enemyid][status]==dead){$users--;}
        }     }
     if ($have_enemy==0 and $targenemy!=1 and $users==0){$endcombat=1;}
   }
   else{$endcombat=1;}

$exp=$monstr_list[$id][maxhp];
       if ($endcombat==1) { // конец боя
       	   $pageinfo.="<br/> Бой окончен!<br/>";
					 $pageinfo.=" [получено $exp опыта]<br/>";
					 $pageinfo.="<br/><a href='./?rand=".rand(1,1000)."'>В игру</a>";
          $sql=mysql_query("DELETE from combats WHERE loc_id='$player[loc_id]' AND combatid='$status[infight]' LIMIT 1");
          if (!empty($fighters)){
            foreach($fighters as $key=>$value){
             	$type=substr($key,0,6);
                $id=substr($key,6);
                if ($type=="monstr")
                {$monstr_list[$id][in_fight]="";}
                elseif ($type=="player") {
                           	  if ($id==$player[id]) {
                           	  	$status[infight]="no";
                           	  	$user[status]=$status;
                           	  }
                           	  else {
                              	$user= mysql_fetch_array(mysql_query("SELECT id,status FROM users WHERE id='$id' LIMIT 1"));
                              	$user[status]=unserialize($user[status]);
                              	$user[status][infight]="no";
                              	if (!empty($fighters["player".$user[id]][string]))
                              	{$user[status][tmp]=$fighters["player".$user[id]][string];}
                              }
                              $user[status]=serialize($user[status]);
                              $temp=mysql_query("UPDATE users SET status='$user[status]' WHERE id='$id' LIMIT 1");
                }
            }
          }
          $delcombat="done";
       }
       elseif($have_enemy==0 and $targenemy!=1 and $users>0) {
       	    $fighters=unset_as_mass($fighters,"player".$player[id]);
       	   	$status[infight]="no";
       	   	$player[od]=$player[maxod];
       	   	$tmp=serialize($status);
            $sql=mysql_query("UPDATE users SET status='$tmp', od='$player[od]'  WHERE id='$player[id]' LIMIT 1");
       }
      $tmp= serialize($monstr_list);
      $sql=mysql_query("UPDATE locations SET monstr_list='$tmp' WHERE loc_id='$player[loc_id]'LIMIT 1");
      $fighters=serialize($fighters);
      if ($delcombat!="done") {$sql=mysql_query("UPDATE combats SET fighters='$fighters', round='$combat[round]',end_round='$combat[end_round]' WHERE loc_id='$player[loc_id]' AND combatid='$status[infight]'");}
      if ($killme==1)
      {
            $player[od]=$player[maxod];
      		$sql=mysql_query("SELECT count(id) FROM users WHERE party='$player[party]'");
      		$count=mysql_result($sql,0,0);
      		if ($count<2){
         		$player[loc_id]=$player[citizen];
      		}
      		$player[hit_points]= 5;
      		$status[hospital]["time"]=time();
      		$status[infight]="no";
      		$tmp=serialize($status);
      		$sql=mysql_query("UPDATE users SET status='$tmp',hit_points='$player[hit_points]',loc_id='$player[loc_id]',od='$player[od]' WHERE id='$player[id]' LIMIT 1");
        $title="Поражение!";
        $page.="<p class='d'> <b>Поражение</b></p>";
      	$page.=$killstr;
      	$page.="<p class='d'><b><a href='./'>В игру</a></b></p>";
      	display($page, $title, $style);
        die();
      }
      if ($error==1)
      {
      		$status[infight]="no";
      		$tmp=serialize($status);
      		$sql=mysql_query("UPDATE users SET status='$tmp',hit_points='$player[hit_points]',loc_id='$player[loc_id]',od='$player[od]' WHERE id='$player[id]' LIMIT 1");
      }
?>