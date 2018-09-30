<?
  $title="Использовать";
  $bag=unserialize($player[bag]);
  if ($_GET[what]=="what"){
      $page.="<p class='d'><b>Что использовать?</b></p>";
  	  	for ($i=0;$i<sizeof($bag);$i++) {
            if ($bag[$i][type]=="medicament") {
             $page.="<br/><a href='./?do=use&amp;what=$i&amp;target=$_GET[target]&amp;id=$_GET[id]'>".$bag[$i][name];
             if ($bag[$i][colvo]>1) {$page.="[".$bag[$i][colvo]."]";}
             $page.="</a>";
             $k=1;
            }
  	  	}
  	  	if ($k!=1) {;$page.="<br/> У вас нет медикаментов!";}
  	  	$page.="<br/>";
  }
  elseif ($_GET[target]=="monster") {  	$page.="<p class='d'><b>".$bag[$_GET[what]][name]."</b></p>";    $sql = mysql_query("SELECT monstr_list FROM locations WHERE loc_id='$player[loc_id]' LIMIT 1");
    $monstr_list = mysql_result($sql,0,"monstr_list");
    $monstr_list=unserialize($monstr_list);
    if ($monstr_list[$_GET[id]][status]=="dead") {$page.="<br/>Нельзя использовать медикаменты на трупах!<br/>";}
    elseif (isset($_GET[what])){
      	if ($bag[$_GET[what]][type]!="medicament") {$page.="<br/>Невозможно использовать!<br/>";}
      	else {
           if ($status[infight]!="no") {
         	$needod=2;
         	$trauma=unserialize($player[trauma]);
         	if ($trauma[lefthand]=="on") {$needod++;}
         	if ($trauma[righthand]=="on") {$needod++;}
         	if ($player[od]<$needod) {$zapret=1;}
          	else {
           	$player[od]=$player[od]-$needod;
           	$sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]' LIMIT 1");}
    		}
    	   if ($zapret==1) {$page.="<br/>Не хватает очков действия! Надо $needod!<br/>";}
    	   elseif ($bag[$_GET[what]][about_item][param]=="hit_points"){
              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Здоровье ".$monstr_list[$_GET[id]][name]." повышено на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";
                 $monstr_list[$_GET[id]][hit_points]=$monstr_list[$_GET[id]][hit_points]+$bag[$_GET[what]][about_item][value];
                 if ($monstr_list[$_GET[id]][hit_points]>$monstr_list[$_GET[id]][maxhp]) {$monstr_list[$_GET[id]][hit_points]=$monstr_list[$_GET[id]][maxhp];}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){
              	 $page.="<br/>Здоровье ".$monstr_list[$_GET[id]][name]." повышено на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $monstr_list[$_GET[id]][hit_points]=$monstr_list[$_GET[id]][hit_points]+intval(round($monstr_list[$_GET[id]][maxhp]*$bag[$_GET[what]][about_item][value]/100));
                 if ($monstr_list[$_GET[id]][hit_points]>$monstr_list[$_GET[id]][maxhp]) {$monstr_list[$_GET[id]][hit_points]=$monstr_list[$_GET[id]][maxhp];}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="full"){
              	 $page.="<br/>Здоровье ".$monstr_list[$_GET[id]][name]." повышено на ".$bag[$_GET[what]][about_item][value]."% сверхмаксимума!<br/>";
                 $monstr_list[$_GET[id]][hit_points]=intval(round($monstr_list[$_GET[id]][maxhp]*(100+$bag[$_GET[what]][about_item][value])/100));
              }
              $monstr_list=serialize($monstr_list);
              $sql=mysql_query("UPDATE locations SET monstr_list='$monstr_list' WHERE loc_id='$player[loc_id]' LIMIT 1");
           }
           elseif ($bag[$_GET[what]][about_item][param]=="hungry"){           	  $page.="<br/>".$monstr_list[$_GET[id]][name]." c шумом счавкал угощение =) <br/>";
           }
           else {$page.="<br/>".$monstr_list[$_GET[id]][name]." отбивался и брыкался, однако вы все таки сделали это! <br/>";}
           $player[gruz]==$player[gruz]-$bag[$_GET[what]][about_item][massa];
      		if ($bag[$_GET[what]][colvo]>1){
      		  $bag[$_GET[what]][colvo]--;
      		}
      		else {$bag=delete_element($bag,$_GET[what]);}
      		$bag=serialize($bag);
        	$sql=mysql_query("UPDATE users SET gruz='$player[gruz]',bag='$bag' WHERE id='$player[id]' LIMIT 1");
      	}

    }  }
  elseif ($_GET[target]=="self") {
  	  $page.="<p class='d'><b>".$bag[$_GET[what]][name]."</b></p>";      if (isset($_GET[what])){      	if ($bag[$_GET[what]][type]!="medicament" and $bag[$_GET[what]][type]!="misc") {$page.="<br/>Невозможно использовать!<br/>";}
      	else {           if ($status[infight]!="no") {
         	$needod=2;
         	$trauma=unserialize($player[trauma]);
         	if ($trauma[lefthand]=="on") {$needod++;}
         	if ($trauma[righthand]=="on") {$needod++;}
         	if ($player[od]<$needod) {$zapret=1;}
          	else {
           	$player[od]=$player[od]-$needod;
           	$sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]' LIMIT 1");}
    		}
    	   if ($zapret==1) {$page.="<br/>Не хватает очков действия! Надо $needod!<br/>";}
    	   elseif ($bag[$_GET[what]][type]=="misc"){
    	     if (!empty($bag[$_GET[what]][about_item][on_use]))
              {eval($bag[$_GET[what]][about_item][on_use]);}

           }           elseif ($bag[$_GET[what]][about_item][param]=="hungry"){              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Голод понижен на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";                 $player[hungry_points]=$player[hungry_points]-$bag[$_GET[what]][about_item][value];
                 if ($player[hungry_points]<0) {$player[hungry_points]=0;}              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){              	 $page.="<br/>Голод понижен на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $player[hungry_points]=$player[hungry_points]-intval(round($player[hungry_points]*$bag[$_GET[what]][about_item][value]/100));
                 if ($player[hungry_points]<0) {$player[hungry_points]=0;}
              }
              $sql=mysql_query("UPDATE users SET hungry_points='$player[hungry_points]' WHERE id='$player[id]' LIMIT 1");           }
           elseif ($bag[$_GET[what]][about_item][param]=="rad"){
              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Радиация понижена на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";
                 $player[rad_points]=$player[rad_points]-$bag[$_GET[what]][about_item][value];
                 if ($player[rad_points]<0) {$player[rad_points]=0;}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){
              	 $page.="<br/>Радиация понижена на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $player[rad_points]=$player[rad_points]-intval(round($player[rad_points]*$bag[$_GET[what]][about_item][value]/100));
                 if ($player[rad_points]<0) {$player[rad_points]=0;}
              }
              $sql=mysql_query("UPDATE users SET rad_points='$player[rad_points]' WHERE id='$player[id]' LIMIT 1");
           }
           elseif ($bag[$_GET[what]][about_item][param]=="poison"){
              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Отравление понижено на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";
                 $player[poison_points]=$player[poison_points]-$bag[$_GET[what]][about_item][value];
                 if ($player[poison_points]<0) {$player[poison_points]=0;}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){
              	 $page.="<br/>Отравление понижено на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $player[poison_points]=$player[poison_points]-intval(round($player[poison_points]*$bag[$_GET[what]][about_item][value]/100));
                 if ($player[poison_points]<0) {$player[poison_points]=0;}
              }
              $sql=mysql_query("UPDATE users SET poison_points='$player[poison_points]' WHERE id='$player[id]' LIMIT 1");
           }
           elseif ($bag[$_GET[what]][about_item][param]=="hit_points"){
              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Ваше здоровье повышено на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";
                 $player[hit_points]=$player[hit_points]+$bag[$_GET[what]][about_item][value];
                 if ($player[hit_points]>$player[maxhp]) {$player[hit_points]=$player[maxhp];}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){
              	 $page.="<br/>Ваше здоровье повышено на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $player[hit_points]=$player[hit_points]+intval(round($player[maxhp]*$bag[$_GET[what]][about_item][value]/100));
                 if ($player[hit_points]>$player[maxhp]) {$player[hit_points]=$player[maxhp];}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="full"){
              	 $page.="<br/>Ваше здоровье повышено на ".$bag[$_GET[what]][about_item][value]."% сверхмаксимума!<br/>";
                 $player[hit_points]=intval(round($player[maxhp]*(100+$bag[$_GET[what]][about_item][value])/100));
              }
              $sql=mysql_query("UPDATE users SET hit_points='$player[hit_points]' WHERE id='$player[id]' LIMIT 1");
           }
           elseif ($bag[$_GET[what]][about_item][param]=="effect"){
              $eff=$bag[$_GET[what]][about_item][value];
              $sql=mysql_query("SELECT * FROM effects WHERE effid='$eff' LIMIT 1");
              $eff=mysql_fetch_array($sql);
              $eff[resists]=unserialize($eff[resists]);
              $eff[params]=unserialize($eff[params]);
              $eff[over]=time()+60*60;
              if (!empty($eff[noeff])) {                 for ($i=0;$i<sizeof($player[effects]);$i++) {                    if ($player[effects][$i][effid]==$eff[noeff])
                    {$player[effects]=delete_element($player[effects],$i);}
                     break;                 }              }
              else {              	for ($i=0;$i<sizeof($player[effects]);$i++) {
                    if ($player[effects][$i][effid]==$eff[effid])
                    {$have=1;
                    $player[effects][$i][over]=$eff[over];
                     break;
                    }
                 }
                 if ($have!=1) {                 	$player[effects][]= $eff;                 }              }

              if ($have!=1) {              	 $player[base_params]=unserialize($player[base_params]);
				 $player[base_resists]=unserialize($player[base_resists]);
				 $player[bodyarm]=unserialize($player[bodyarm]);
                 $return = calculating($player[effects],$player[base_params],$player[base_resists],$player[bodyarm]);
     		     $player[fact_resists]=serialize($return[fact_resists]);
     		     $player[fact_params]=serialize($return[fact_params]);
                 $player[effects]=serialize($player[effects]);
     		     $sql=mysql_query("UPDATE users SET crit_chance='$return[crit_chance]',effects='$player[effects]',fact_resists='$player[fact_resists]',fact_params='$player[fact_params]' WHERE id='$player[id]' LIMIT 1");

     		  }
     		  else {
     		   $player[effects]=serialize($player[effects]);               $sql=mysql_query("UPDATE users SET effects='$player[effects]' WHERE id='$player[id]' LIMIT 1");     		  }

           }
           $page.="<br/>Вы применили ".$bag[$_GET[what]][name]."!<br/>";
           $player[gruz]==$player[gruz]-$bag[$_GET[what]][about_item][massa];
      		if ($bag[$_GET[what]][colvo]>1){      			$bag[$_GET[what]][colvo]--;      		}
      		else {$bag=delete_element($bag,$_GET[what]);}
      		$bag=serialize($bag);        	$sql=mysql_query("UPDATE users SET gruz='$player[gruz]',bag='$bag' WHERE id='$player[id]' LIMIT 1");      	}
      }  }
  elseif ($_GET[target]=="player") {
  	  $page.="<p class='d'><b>".$bag[$_GET[what]][name]."</b></p>";
      if (isset($_GET[what])){
      	if ($bag[$_GET[what]][type]!="medicament") {$page.="<br/>Невозможно использовать!<br/>";}
      	else {      	   $sql=mysql_query("SELECT * FROM users WHERE id='$_GET[id]' LIMIT 1");
           $page.=mysql_error();
           $user=mysql_fetch_array($sql);
           $user[status]=unserialize($user[status]);
           if ($user[loc_id]!=$player[loc_id]) {$page="<br/>Игрока нет на локации!<br/>";}
          else {
           if ($status[infight]!="no") {
         	$needod=2;
         	$trauma=unserialize($player[trauma]);
         	if ($trauma[lefthand]=="on") {$needod++;}
         	if ($trauma[righthand]=="on") {$needod++;}
         	if ($player[od]<$needod) {$zapret=1;}
          	else {
           	$player[od]=$player[od]-$needod;
           	$sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]' LIMIT 1");}
    		}
    	   if ($zapret==1) {$page.="<br/>Не хватает очков действия! Надо $needod!<br/>";}
           elseif ($bag[$_GET[what]][about_item][param]=="hungry"){
              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Голод $user[char_name] понижен на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";

                 $user[hungry_points]=$user[hungry_points]-$bag[$_GET[what]][about_item][value];
                 if ($user[hungry_points]<0) {$user[hungry_points]=0;}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){
              	 $page.="<br/>Голод $user[char_name] понижен на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $user[hungry_points]=$user[hungry_points]-intval(round($user[hungry_points]*$bag[$_GET[what]][about_item][value]/100));
                 if ($user[hungry_points]<0) {$user[hungry_points]=0;}
              }
              $user[status][tmp][]="<br/>$player[char_name] применил к вам ".$bag[$_GET[what]][name];
              $user[status]=serialize($user[status]);
              $sql=mysql_query("UPDATE users SET hungry_points='$user[hungry_points]',status='$user[status]' WHERE id='$user[id]' LIMIT 1");
           }
           elseif ($bag[$_GET[what]][about_item][param]=="rad"){
              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Радиация $user[char_name] понижена на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";
                 $user[rad_points]=$user[rad_points]-$bag[$_GET[what]][about_item][value];
                 if ($user[rad_points]<0) {$user[rad_points]=0;}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){
              	 $page.="<br/>Радиация $user[char_name] понижена на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $user[rad_points]=$user[rad_points]-intval(round($user[rad_points]*$bag[$_GET[what]][about_item][value]/100));
                 if ($player[rad_points]<0) {$player[rad_points]=0;}
              }
              $user[status][tmp][]="<br/>$player[char_name] применил к вам ".$bag[$_GET[what]][name];
              $user[status]=serialize($user[status]);
              $sql=mysql_query("UPDATE users SET rad_points='$user[rad_points]', status='$user[status]' WHERE id='$user[id]' LIMIT 1");
           }
           elseif ($bag[$_GET[what]][about_item][param]=="poison"){
              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Отравление $user[char_name] понижено на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";
                 $user[poison_points]=$user[poison_points]-$bag[$_GET[what]][about_item][value];
                 if ($user[poison_points]<0) {$user[poison_points]=0;}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){
              	 $page.="<br/>Отравление $user[char_name] понижено на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $user[poison_points]=$user[poison_points]-intval(round($user[poison_points]*$bag[$_GET[what]][about_item][value]/100));
                 if ($user[poison_points]<0) {$user[poison_points]=0;}
              }
              $user[status][tmp][]="<br/>$player[char_name] применил к вам ".$bag[$_GET[what]][name];
              $user[status]=serialize($user[status]);
              $sql=mysql_query("UPDATE users SET poison_points='$user[poison_points]' WHERE id='$user[id]' LIMIT 1");
           }
           elseif ($bag[$_GET[what]][about_item][param]=="hit_points"){
              if ($bag[$_GET[what]][about_item][type]=="const"){
                 $page.="<br/>Здоровье $user[char_name] повышено на ".$bag[$_GET[what]][about_item][value]." единиц<br/>";
                 $user[hit_points]=$user[hit_points]+$bag[$_GET[what]][about_item][value];
                 if ($user[hit_points]>$user[maxhp]) {$user[hit_points]=$user[maxhp];}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="procent"){
              	 $page.="<br/>Здоровье $user[char_name] повышено на ".$bag[$_GET[what]][about_item][value]."%<br/>";
                 $user[hit_points]=$user[hit_points]+intval(round($user[maxhp]*$bag[$_GET[what]][about_item][value]/100));
                 if ($user[hit_points]>$user[maxhp]) {$user[hit_points]=$user[maxhp];}
              }
              elseif ($bag[$_GET[what]][about_item][type]=="full"){
              	 $page.="<br/>Здоровье $user[char_name] повышено на ".$bag[$_GET[what]][about_item][value]."% сверхмаксимума!<br/>";
                 $user[hit_points]=intval(round($user[maxhp]*(100+$bag[$_GET[what]][about_item][value])/100));
              }
              $user[status][tmp][]="<br/>$player[char_name] применил к вам ".$bag[$_GET[what]][name];
              $user[status]=serialize($user[status]);
              $sql=mysql_query("UPDATE users SET hit_points='$user[hit_points]', status='$user[status]' WHERE id='$user[id]' LIMIT 1");
           }
           elseif ($bag[$_GET[what]][about_item][param]=="effect"){
              $user[effects]=unserialize($user[effects]);
              $eff=$bag[$_GET[what]][about_item][value];
              $sql=mysql_query("SELECT * FROM effects WHERE effid='$eff' LIMIT 1");
              $eff=mysql_fetch_array($sql);
              $eff[resists]=unserialize($eff[resists]);
              $eff[params]=unserialize($eff[params]);
              $eff[over]=time()+60*60;
              if (!empty($eff[noeff])) {
                 for ($i=0;$i<sizeof($user[effects]);$i++) {
                    if ($user[effects][$i][effid]==$eff[noeff])
                    {$user[effects]=delete_element($user[effects],$i);}
                     break;
                 }
              }
              else {
              	for ($i=0;$i<sizeof($user[effects]);$i++) {
                    if ($user[effects][$i][effid]==$eff[effid])
                    {$have=1;  break;
                    $user[effects][$i][over]=$eff[over];
                    }
                 }
                 if ($have!=1) {
                 	$user[effects][]= $eff;
                 }
              }
              $user[status][tmp][]="<br/>$player[char_name] применил к вам ".$bag[$_GET[what]][name];
              $user[status]=serialize($user[status]);
              if ($have!=1) {
              	 $user[base_params]=unserialize($user[base_params]);
				 $user[base_resists]=unserialize($user[base_resists]);
				 $user[bodyarm]=unserialize($user[bodyarm]);
                 $return = calculating($user[effects],$user[base_params],$user[base_resists],$user[bodyarm]);
     		     $user[fact_resists]=serialize($return[fact_resists]);
     		     $user[fact_params]=serialize($return[fact_params]);
                 $user[effects]=serialize($user[effects]);
     		     $sql=mysql_query("UPDATE users SET status='$user[status]',effects='$user[effects]',fact_resists='$user[fact_resists]',fact_params='$user[fact_params]' WHERE id='$user[id]' LIMIT 1");

     		  }
     		  else {
     		   $user[effects]=serialize($user[effects]);
               $sql=mysql_query("UPDATE users SET status='$user[status]',effects='$user[effects]' WHERE id='$user[id]' LIMIT 1");
     		  }
              $page.="<br/>Вы применилии ".$bag[$_GET[what]][name]." на $user[char_name]!<br/>";
           }
           $player[gruz]==$player[gruz]-$bag[$_GET[what]][about_item][massa];
      		if ($bag[$_GET[what]][colvo]>1){
      			$bag[$_GET[what]][colvo]--;
      		}
      		else {$bag=delete_element($bag,$_GET[what]);}
      		$bag=serialize($bag);
        	$sql=mysql_query("UPDATE users SET gruz='$player[gruz]',bag='$bag' WHERE id='$player[id]' LIMIT 1");
      	  }
      	}

      }
  }

  $page.="<br/><a href='./?do=aboutme'>Персонаж</a>";
  $page.="<br/><a href='./?do=inv'>Инвентарь</a>";
  $page.="<br/><a href='./'>В игру</a><br/><br/>";
?>