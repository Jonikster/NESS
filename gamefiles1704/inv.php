<?
  $title="Инвентарь";
  $bag=unserialize($player[bag]);
  $weapon1=unserialize($player[weapon1]);
  $weapon2=unserialize($player[weapon2]);
  $bodyarm=unserialize($player[bodyarm]);
  $fact_params=unserialize($player[fact_params]);
  $page.="<p class='d'><b>Ваш мешок</b><br/></p>";
  if (isset($_GET[view])) {
     if ($_GET[view]=="weapon1"){
       if (empty($weapon1)) {$page.="<br/>У вас нет основного оружия!";}
       else {      	$item=$weapon1;
        $page.=about_item($item);
        if ($weapon1[about_item][type_weap]=="fire" and $weapon1[about_item][patrons]<$weapon1[about_item][maxpatrons]) {$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=reload&amp;item=weapon1'>Перезарядить</a>"; }     	$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=toinv&amp;item=weapon1'>Снять</a><br/>
     	<br/><a href='./?do=inv&amp;view=eqip'>Экипировка</a>";
       }
     }
     elseif ($_GET[view]=="weapon2"){
       if (empty($weapon2)) {$page.="<br/>У вас нет вторичного оружия!";}
       else {
      	$item=$weapon2;
        $page.=about_item($item);
        if ($weapon2[about_item][type_weap]=="fire" and $weapon2[about_item][patrons]<$weapon2[about_item][maxpatrons]) {$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=reload&amp;item=weapon2'>Перезарядить</a>"; }
     	$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=toinv&amp;item=weapon2'>Снять</a><br/>
     	<br/><a href='./?do=inv&amp;view=eqip'>Экипировка</a>";
       }
     }
     elseif ($_GET[view]=="bodyarm"){
       if (empty($bodyarm)) {$page.="<br/>На вас нет брони!";}
       else {
      	$item=$bodyarm;
        $page.=about_item($item);
        $page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=toinv&amp;item=bodyarm'>Снять</a><br/>
        <br/><a href='./?do=inv&amp;view=eqip'>Экипировка</a>";
       }
     }
     elseif ($_GET[view]=="eqip"){
        $page.="<br/><img src='/img/icon/gun.PNG'/> Осн.оружие: ";
        if (!empty($weapon1)){        	$page.="<a href='?do=inv&amp;view=weapon1'>".$weapon1[name];
        	if ($weapon1[about_item][type_weap]=="fire") {$page.=" [".$weapon1[about_item][patrons]."/".$weapon1[about_item][maxpatrons]."]";}
     	    $page.="</a>";
     	}
        $page.="<br/><img src='/img/icon/gun.PNG'/> Вторичное: ";
        if (!empty($weapon2)){
        	$page.="<a href='?do=inv&amp;view=weapon2'>".$weapon2[name];
        	if ($weapon2[about_item][type_weap]=="fire") {$page.=" [".$weapon2[about_item][patrons]."/".$weapon2[about_item][maxpatrons]."]";}
     	    $page.="</a>";
     	}
        $page.="<br/><img src='/img/icon/shield.PNG'/> Броня: ";
        if (!empty($bodyarm)){
        	$page.="<a href='?do=inv&amp;view=bodyarm'>$bodyarm[name]</a><br/>";
     	}

     }
     else{      $item=$bag[$_GET[view]];
      $page.=about_item($item);      if ($bag[$_GET[view]][type]=="weapon"){         $page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=useweap&amp;mod=1&amp;item=$_GET[view]'>Как основное оружие</a>
         <br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=useweap&amp;mod=2&amp;item=$_GET[view]'>Как вторичное оружие</a>";      }
      elseif($bag[$_GET[view]][type]=="bodyarm"){         $page.="<br/><a href='./?do=inv&amp;act=usearm&amp;item=$_GET[view]'>Одеть</a>";      }
      elseif($bag[$_GET[view]][type]=="patron"){
         if ($weapon1[about_item][type_weap]=="fire" and $weapon1[about_item][calibr]==$bag[$_GET[view]][about_item][calibr]) {$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=load&amp;mod=weapon1&amp;patron=$_GET[view]'>Зарядить в $weapon1[name]</a>"; }
         if ($weapon2[about_item][type_weap]=="fire" and $weapon2[about_item][calibr]==$bag[$_GET[view]][about_item][calibr]) {$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;act=load&amp;mod=weapon2&amp;patron=$_GET[view]'>Зарядить в $weapon2[name]</a>"; }

      }
      elseif($bag[$_GET[view]][type]=="medicament"){
         $page.="<br/><img src='/img/icon/yad.PNG'/> <a href='./?do=use&amp;what=$_GET[view]&amp;target=self'>Использовать</a>";
      }
      elseif($bag[$_GET[view]][type]=="misc"){      	 if (!empty($bag[$_GET[view]][about_item][on_use]))
         {$page.="<br/><img src='/img/icon/yad.PNG'/> <a href='./?do=use&amp;what=$_GET[view]&amp;target=self'>Использовать</a>";}
      }
         if ($bag[$_GET[view]][colvo]==1) {$page.="<br/><img src='/img/icon/throw.PNG'/> <a href='./?do=inv&amp;act=getaway&amp;item=$_GET[view]'>Выкинуть</a><br/>";}
         else {
            $page.="<br/>Количество: ".$bag[$_GET[view]][colvo].
            "<form action='./?do=inv&amp;act=getaway&amp;item=$_GET[view]".$user[id]."' method='post'>
            <input type='text' name='colvo' value='".$bag[$_GET[view]][colvo]."' />
            <br/><input type='submit' value='Выбросить' />
            </form>";
         }
     }
      $page.="<br/><a href='./?do=inv'>В мешок</a>";  }
  elseif (isset($_GET[act])) {
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
    elseif ($_GET[act]=="getaway"){
      $page.="<br/>".$bag[$_GET[item]][name]." выброшено!";
      if (isset($_POST[colvo])) {          $_POST[colvo]=htmlspecialchars($_POST[colvo]);          if  ($bag[$_GET[item]][colvo]>$_POST[colvo]) {
          	 $item=$bag[$_GET[item]];
             $item[colvo]=$_POST[colvo];
             add_to_garbage($player[loc_id],$item);
           	 $player[gruz]=$player[gruz]-$bag[$_GET[item]][about_item][massa]*$_POST[colvo];
             $bag[$_GET[item]][colvo]=$bag[$_GET[item]][colvo]-$_POST[colvo];
             $del="done";

          }      }      if ($del!="done") {      	  $item=$bag[$_GET[item]];
          $item[colvo]=$bag[$_GET[item]][colvo];
          add_to_garbage($player[loc_id],$item);      	  $player[gruz]=$player[gruz]-$bag[$_GET[item]][about_item][massa]*$bag[$_GET[item]][colvo];
      	  $bag=delete_element($bag,$_GET[item]);      }
      $bag=serialize($bag);
      $sql=mysql_query("UPDATE users SET bag='$bag', gruz='$player[gruz]' WHERE id='$player[id]' LIMIT 1");
	  $bag=unserialize($bag);
	  }
    elseif ($_GET[act]=="toinv"){     if ($_GET[item]=='bodyarm') {     	if (!empty($bodyarm)){          for ($i=0;$i<sizeof($bag);$i++){
                      if  ($bag[$i][id]==$bodyarm[id] and $bag[$i][name]==$bodyarm[name] and $bag[$i][info]==$bodyarm[info]){
                      	   $bag[$i][colvo]++;
                      	   $k=1;
                      	   break;
                      }
          }
          if ($k!=1) {$bodyarm[colvo]=1;$bag[]=$bodyarm;}
          $page.="<br/>$bodyarm[name] помещенo в ваш рюкзак.";
                 //расчет фактических сопротивлений
          $bodyarm="";
          if (!is_array($player[base_params])) {$player[base_params]=unserialize($player[base_params]);}
		 if (!is_array($player[base_resists])) {$player[base_resists]=unserialize($player[base_resists]);}
		 if (!is_array($player[effects])) {$player[effects]=unserialize($player[effects]);}
         $return = calculating($player[effects],$player[base_params],$player[base_resists],$bodyarm);
     	 $player[fact_resists]=serialize($return[fact_resists]);
          $bag=serialize($bag);
          $player[crit_chance]=$return[crit_chance];

          $sql=mysql_query("UPDATE users SET bag='$bag',crit_chance='$player[crit_chance]',bodyarm='$bodyarm',fact_resists='$player[fact_resists]' WHERE id='$player[id]' LIMIT 1");          //$page.=mysql_error();
        }else {$page.="<br/>На вас и так ничего не одето!";};
     }
     elseif ($_GET[item]=='weapon1') {           if (!empty($weapon1)){
             	for ($i=0;$i<sizeof($bag);$i++){
                      if  ($bag[$i][id]==$weapon1[id] and $bag[$i][name]==$weapon1[name] and $bag[$i][info]==$weapon1[info]){
                      	   $bag[$i][colvo]++;
                      	   $k=1;
                      	   break;
                      }
             	}
             	 //*if ($weapon1[about_item][type_weap]==fire){
                   //if  ($weapon1[about_item][patrons] > 0) { // кладем патроны в рюкзак

             		 //for($k=0;$k<sizeof($bag);$k++) {
                    	//if ($bag[$k][id]=$weapon1[about_item][calibr]) {
											//$sql=mysql_query("SELECT about_items FROM items WHERE id='$about_items[calibr]' LIMIT 1");
                    		//$bag[$k][colvo]=$bag[$k][colvo]+$weapon1[about_item][patrons];
                    		//$weapon1[about_item][patrons]=0;
                    		//break;
                    	//}
                	 //}
                	 //if  ($weapon1[about_item][patrons] > 0) {
                      	//$idpatrons=$weapon1[about_item][calibr];
           	    	  	
           	    	  	//$patron=mysql_fetch_array($sql);
           	    	  	//$patron[about_item]=unserialize($patron[about_item]);
           	    	  	//$patron[colvo]=$weapon1[about_item][patrons];
           	    	  	//$weapon1[about_item][patrons]=0;
           	    	  	//$bag[]=$patron;
                	 //}
           	 		//}
             	 	//$weapon1[about_item][calibr]="";
             	 	//$weapon1[about_item][patrons]=0;
             	 	//}
            	 if ($k!=1) {$weapon1[colvo]=1;$bag[]=$weapon1;}
            	 $page.="<br/>$weapon1[name] помещен в ваш рюкзак.";
            	 $weapon1="";
         		 $bag=serialize($bag);
      		 	 $sql=mysql_query("UPDATE users SET bag='$bag',weapon1='$weapon1' WHERE id='$player[id]' LIMIT 1");
         	}else {$page.="<br/>Ваш первый слот и так пуст!";}     }
     elseif ($_GET[item]=='weapon2') {
           if (!empty($weapon2)){
             	for ($i=0;$i<sizeof($bag);$i++){
                      if  ($bag[$i][id]==$weapon2[id] and $bag[$i][name]==$weapon2[name] and $bag[$i][info]==$weapon2[info]){
                      	   $bag[$i][colvo]++;
                      	   $k=1;
                      	   break;
                      }
             	}
             	 //if ($weapon2[about_item][type_weap]==fire){
                   //if  ($weapon2[about_item][patrons] > 0) { // кладем патроны в рюкзак

             		// for($k=0;$k<sizeof($bag);$k++) {
                    	//if ($bag[$k][id]=$weapon2[about_item][idpatrons]) {
                    		//$bag[$k][colvo]=$bag[$k][colvo]+$weapon2[about_item][patrons];
                    		//$weapon2[about_item][patrons]=0;
                    		//break;
                    //	}
                	 //}
                	// if  ($weapon2[about_item][patrons] > 0) {
                      //	$idpatrons=$weapon2[about_item][idpatrons];
           	    	  	//$sql=mysql_query("SELECT about_item FROM items WHERE id='".$weapon[about_item][idpatrons]."' LIMIT 1");
           	    	  	//$patron=mysql_fetch_array($sql);
           	    	  	//$patron[about_item]=unserialize($patron[about_item]);
           	    	  	//$patron[colvo]=$weapon2[about_item][patrons];
           	    	  	//$weapon1[about_item][patrons]=0;
           	    	  	//$bag[]=$patron;
                	// }
           	 		//}
             	 	//$weapon2[about_item][idpatrons]="";
             	 //	$weapon2[about_item][patrons]=0;
             	 //	}
            	 if ($k!=1) {$weapon2[colvo]=1;$bag[]=$weapon2;}
            	 $page.="<br/>$weapon2[name] помещен в ваш рюкзак.";
            	 $weapon2="";
         		 $bag=serialize($bag);
      		 	 $sql=mysql_query("UPDATE users SET bag='$bag',weapon2='$weapon2' WHERE id='$player[id]' LIMIT 1");
         	}else {$page.="<br/>Ваш второй слот и так пуст!";}
     }

     $page.="<br/><br/><a href='./?do=inv&amp;view=eqip'>Экипировка</a>";    }
    elseif ($_GET[act]=="usearm"){       if  ($bag[$_GET[item]][type]!="bodyarm"){
      	$page.="<br/>Ошибка! Попробуйте броню!=)";
      }
      elseif($bag[$_GET[item]][about_item][req_level]>$player[level]){      	$page.="<br/>Необходим ".$bag[$_GET[item]][about_item][req_level]." уровень чтобы одеть ".$bag[$_GET[item]][name];      }
      else {         if (!empty($bodyarm)){ //снять старую броню                for ($i=0;$i<sizeof($bag);$i++){
                      if  ($bag[$i][id]==$bodyarm[id] and $bag[$i][name]==$bodyarm[name] and $bag[$i][info]==$bodyarm[info]){
                      	   $bag[$i][colvo]++;
                      	   $k=1;
                      	   break;
                      }if ($k!=1) {$bag[]=$bodyarm;}$page.="<br/>$bodyarm[name] помещенo в ваш рюкзак.";
             	}
}
         $bodyarm=$bag[$_GET[item]];
         $bodyarm=unset_as_mass($bodyarm,"colvo");
         $page.="<br/>Вы одели $bodyarm[name]";
         if ($bag[$_GET[item]][colvo]>1) {$bag[$_GET[item]][colvo]--;}
         else {$bag=delete_element($bag,$_GET[item]);}
                 //расчет фактических сопротивлений
         if (!is_array($player[base_params])){$player[base_params]=unserialize($player[base_params]);}
		 if (!is_array($player[base_resists])){$player[base_resists]=unserialize($player[base_resists]);}
		 if (!is_array($player[effects])) {$player[effects]=unserialize($player[effects]);}
         $return = calculating($player[effects],$player[base_params],$player[base_resists],$bodyarm);
     	 $player[fact_resists]=serialize($return[fact_resists]);


         $bodyarm=serialize($bodyarm);
         $bag=serialize($bag);
      	$sql=mysql_query("UPDATE users SET bag='$bag',bodyarm='$bodyarm',fact_resists='$player[fact_resists]' WHERE id='$player[id]' LIMIT 1");       $page.="<br/>";     }

    elseif ($_GET[act]=="useweap"){      if  ($bag[$_GET[item]][type]!="weapon"){      	$page.="<br/>Ошибка! Попробуйте оружие!=)<br/>";      }
      elseif($bag[$_GET[item]][about_item][req_level]>$player[level]){
      	$page.="<br/>Необходим ".$bag[$_GET[item]][about_item][req_level]." уровень чтобы использовать ".$bag[$_GET[item]][name];
      }
      else {      	 if  ($_GET[mod]==1){         	if (!empty($weapon1)){             	for ($i=0;$i<sizeof($bag);$i++){
                      if  ($bag[$i][id]==$weapon1[id] and $bag[$i][name]==$weapon1[name] and $bag[$i][info]==$weapon1[info]){
                      	   $bag[$i][colvo]++;
                      	   $k=1;
                      	   break;
                      }
             	}
             	 if ($weapon1[about_item][type_weap]==fire){                   if  ($weapon1[about_item][patrons] > 0) { // кладем патроны в рюкзак

             		 for($k=0;$k<sizeof($bag);$k++) {
                    	if ($bag[$k][id]=$weapon1[about_item][idpatrons]) {
                    		$bag[$k][colvo]=$bag[$k][colvo]+$weapon1[about_item][patrons];
                    		$weapon1[about_item][patrons]=0;
                    		break;
                    	}
                	 }
                	 if  ($weapon1[about_item][patrons] > 0) {
                      	$idpatrons=$weapon1[about_item][idpatrons];
           	    	  	$sql=mysql_query("SELECT * FROM items WHERE id='$idpatrons' LIMIT 1");
           	    	  	$patron=mysql_fetch_array($sql);
           	    	  	$patron[about_item]=unserialize($patron[about_item]);
           	    	  	$patron[colvo]=$weapon1[about_item][patrons];
           	    	  	$weapon1[about_item][patrons]=0;
           	    	  	$bag[]=$patron;
                	 }
           	 		}             	 	$weapon1[about_item][idpatrons]="";
             	 	$weapon1[about_item][patrons]=0;
             	 	}
            	 if ($k!=1) {$bag[]=$weapon1;}
            	 $page.="<br/>$weapon1[name] помещен в ваш рюкзак.";         	}
         	$weapon1=$bag[$_GET[item]];
         	$weapon1=unset_as_mass($weapon1,"colvo");
         	$page.="<br/>Вы взяли $weapon1[name] как основное оружие";
         	if ($bag[$_GET[item]][colvo]>1) {$bag[$_GET[item]][colvo]--;}
         	else {$bag=delete_element($bag,$_GET[item]);}
         	$weapon1=serialize($weapon1);
         	$bag=serialize($bag);
      		$sql=mysql_query("UPDATE users SET bag='$bag',weapon1='$weapon1' WHERE id='$player[id]' LIMIT 1");
         }
         elseif  ($_GET[mod]==2){
         	if (!empty($weapon2)){
             	for ($i=0;$i<sizeof($bag);$i++){
                      if  ($bag[$i][id]==$weapon2[id] and $bag[$i][name]==$weapon2[name] and $bag[$i][info]==$weapon2[info]){
                      	   $bag[$i][colvo]++;
                      	   $k=1;
                      	   break;
                      }
             	}
             	if ($weapon2[about_item][type_weap]==fire){
                   if  ($weapon2[about_item][patrons] > 0) { // кладем патроны в рюкзак

             		 for($k=0;$k<sizeof($bag);$k++) {
                    	if ($bag[$k][id]=$weapon2[about_item][idpatrons]) {
                    		$bag[$k][colvo]=$bag[$k][colvo]+$weapon2[about_item][patrons];
                    		$weapon2[about_item][patrons]=0;
                    		break;
                    	}
                	 }
                	 if  ($weapon2[about_item][patrons] > 0) {
                      	$idpatrons=$weapon2[about_item][idpatrons];
           	    	  	$sql=mysql_query("SELECT * FROM items WHERE id='$idpatrons' LIMIT 1");
           	    	  	$patron=mysql_fetch_array($sql);
           	    	  	$patron[about_item]=unserialize($patron[about_item]);
           	    	  	$patron[colvo]=$weapon2[about_item][patrons];
           	    	  	$weapon2[about_item][patrons]=0;
           	    	  	$bag[]=$patron;
                	 }
           	 		}
             	 	$weapon2[about_item][idpatrons]="";
             	 	$weapon2[about_item][patrons]=0;
             	 	}
            	 if ($k!=1) {$bag[]=$weapon2;}
            	 $page.="<br/>$weapon2[name] помещен в ваш рюкзак.";
         	}
         	$weapon2=$bag[$_GET[item]];
         	$weapon2=unset_as_mass($weapon2,"colvo");
         	$page.="<br/>Вы взяли $weapon2[name] как вторичное оружие";
         	$weapon2=serialize($weapon2);
         	if ($bag[$_GET[item]][colvo]>1) {$bag[$_GET[item]][colvo]--;}
         	else {$bag=delete_element($bag,$_GET[item]);}
         	$bag=serialize($bag);
      		$sql=mysql_query("UPDATE users SET bag='$bag',weapon2='$weapon2' WHERE id='$player[id]' LIMIT 1");
         }

      }
      $page.="<br/>";

    }
    elseif ($_GET[act]=="load"){
  	  if ($_GET[mod]=="weapon1") {$weap=$weapon1;}
  	  elseif ($_GET[mod]=="weapon2") {$weap=$weapon2;}     if ($bag[$_GET[patron]][type]!="patron" and $bag[$_GET[patron]][about_item][calibr]!=$weap[about_item][calibr])
        {$page.="<br/>Невозможно $weap[name] зарядить ".$bag[$_GET[patron]][name]."!";}
     else {        if (empty($weap[about_item][idpatrons])) {        	if ($bag[$_GET[patron]][colvo]>$weap[about_item][maxpatrons])
           	{   $weap[about_item][patrons]=$weap[about_item][maxpatrons];
                $weap[about_item][idpatrons]=$bag[$_GET[patron]][id];
                $bag[$_GET[patron]][colvo]= $bag[$_GET[patron]][colvo] - $weap[about_item][maxpatrons];}
           	else {
           	$weap[about_item][patrons]=$bag[$_GET[patron]][colvo];
            $weap[about_item][idpatrons]=$bag[$_GET[patron]][id];
            $bag=delete_element($bag,$_GET[patron]);}
        }
        elseif ($weap[about_item][idpatrons]==$bag[$_GET[patron]][id]) {
        	$need=$weap[about_item][maxpatrons]-$weap[about_item][patrons];            if ($bag[$_GET[patron]][colvo]>$need)
           	{   $weap[about_item][patrons]=$weap[about_item][patrons]+$need;
                $bag[$_GET[patron]][colvo]= $bag[$_GET[patron]][colvo] - $need;}
           	else {
           	$weap[about_item][patrons]=$weap[about_item][patrons]+$bag[$_GET[patron]][colvo];
            $bag=delete_element($bag,$_GET[patron]);}        }
        else {             if  ($weap[about_item][patrons] > 0) { // кладем старые патроны в рюкзак

             	for($k=0;$k<sizeof($bag);$k++) {
                    if ($bag[$k][id]=$weap[about_item][idpatrons]) {
                    $bag[$k][colvo]=$bag[$k][colvo]+$weap[about_item][patrons];
                    $weap[about_item][patrons]=0;
                    break;
                    }
                }
                if  ($weap[about_item][patrons] > 0) {
                      $idpatrons=$weap[about_item][idpatrons];
           	    	  $sql=mysql_query("SELECT * FROM items WHERE id='$idpatrons' LIMIT 1");
           	    	  $patron=mysql_fetch_array($sql);
           	    	  $patron[about_item]=unserialize($patron[about_item]);
           	    	  $patron[colvo]=$weap[about_item][patrons];
           	    	  $weap[about_item][patrons]=0;
           	    	  $bag[]=$patron;
                }
           	 }
           	 if ($bag[$_GET[patron]][colvo]>$weap[about_item][maxpatrons])
           	 {    $weap[about_item][patrons]=$weap[about_item][maxpatrons];
                  $weap[about_item][idpatrons]=$bag[$_GET[patron]][id];
                  $bag[$_GET[patron]][colvo]= $bag[$_GET[patron]][colvo] - $weap[about_item][maxpatrons];}
           	 else {
           	      $weap[about_item][patrons]=$bag[$_GET[patron]][colvo];
                  $weap[about_item][idpatrons]=$bag[$_GET[patron]][id];
                  $bag=delete_element($bag,$_GET[patron]);}        }
        $bag=serialize($bag);
        $weap=serialize($weap);
        $_GET[mod]=htmlspecialchars($_GET[mod]);
        $sql=mysql_query("UPDATE users SET bag='$bag', $_GET[mod]='$weap' WHERE id='$player[id]' LIMIT 1");
       	$page.="<br/>Перезарядка прошла успешно!";     }
      $page.="<br/>";    }
    elseif ($_GET[act]=="reload"){  	  if ($_GET[item]=="weapon1") {$weap=$weapon1;}
  	  elseif ($_GET[item]=="weapon2") {$weap=$weapon2;}
  		if  (!($weap[about_item][maxpatrons]>0) or $weap[about_item][type_weap]!="fire" ){
      	$page.="<br/>Ошибка! ".$weap[name]." невозможно перезарядить!<br/>";
      }
      else {
      	if (empty($weap[about_item][idpatrons])) {           for ($i=0;$i<sizeof($bag);$i++) {           	  if ($bag[$i][type]=="patron"  and $bag[$i][about_item][calibr]==$weap[about_item][calibr])
           	    {       if ($bag[$i][colvo]>$weap[about_item][maxpatrons])
           	    		{   $weap[about_item][patrons]=$weap[about_item][maxpatrons];                            $weap[about_item][idpatrons]=$bag[$i][id];
                            $bag[$i][colvo]= $bag[$i][colvo] - $weap[about_item][maxpatrons];}
           	    		else {
           	    			$weap[about_item][patrons]=$bag[$i][colvo];
                            $weap[about_item][idpatrons]=$bag[$i][id];
                            $bag=delete_element($bag,$i);}
                        $reload="done";
           	    		break;
           	    }
           }
      	}
      	else {   // если оружие заряжено
      		for ($i=0;$i<sizeof($bag);$i++) {
           	  if ($bag[$i][id]==$weap[about_item][idpatrons])
           	    {      $need=$weap[about_item][maxpatrons]-$weap[about_item][patrons];
           	    	   if ($bag[$i][colvo]>$need)
           	    		{   $weap[about_item][patrons]=$weap[about_item][patrons]+$need;
                            $bag[$i][colvo]= $bag[$i][colvo] - $need;}
           	    		else {
           	    			$weap[about_item][patrons]=$weap[about_item][patrons]+$bag[$i][colvo];
                            $bag=delete_element($bag,$i);}
                        $reload="done";
           	    		break;
           	    }
           }
           if   ($reload!="done") { //надо сменить тип патрона
                for ($i=0;$i<sizeof($bag);$i++) {
           	  		if ($bag[$i][type]=="patron"  and $bag[$i][about_item][calibr]==$weap[about_item][calibr])
           	    	{
           	    	    if  ($weap[about_item][patrons] > 0) { // кладем старые патроны в рюкзак

                            for($k=0;$k<sizeof($bag);$k++) {
                            	if ($bag[$k][id]=$weap[about_item][idpatrons]) {
                            		$bag[$k][colvo]=$bag[$k][colvo]+$weap[about_item][patrons];
                            		$weap[about_item][patrons]=0;
                            		break;
                            	}
                            }
                            if  ($weap[about_item][patrons] > 0) {
                            	$idpatrons=$weap[about_item][idpatrons];
           	    	    		$sql=mysql_query("SELECT * FROM items WHERE id='$idpatrons' LIMIT 1");
           	    	    		$patron=mysql_fetch_array($sql);
           	    	    		$patron[about_item]=unserialize($patron[about_item]);
           	    	    		$patron[colvo]=$weap[about_item][patrons];
           	    	    		$weap[about_item][patrons]=0;
           	    	    		$bag[]=$patron;
                            }

           	    	    }
           	    	    if ($bag[$i][colvo]>$weap[about_item][maxpatrons])
           	    		{   $weap[about_item][patrons]=$weap[about_item][maxpatrons];
                            $weap[about_item][idpatrons]=$bag[$i][id];
                            $bag[$i][colvo]= $bag[$i][colvo] - $weap[about_item][maxpatrons];}
           	    		else {
           	    			$weap[about_item][patrons]=$bag[$i][colvo];
                            $weap[about_item][idpatrons]=$bag[$i][id];
                            $bag=delete_element($bag,$i);}
                        $reload="done";
           	    		break;
           	    	}
                }
           }
      	}
        if ($reload=="done")	{
        	$bag=serialize($bag);
        	$weap=serialize($weap);
        	$_GET[item]=htmlspecialchars($_GET[item]);
        	$sql=mysql_query("UPDATE users SET bag='$bag', $_GET[item]='$weap' WHERE id='$player[id]' LIMIT 1");
       		$page.="<br/>Перезарядка прошла успешно!";
        } else  {$page.="<br/>Нет патронов!";}
      }}

    $page.="<br/><a href='./?do=inv'>В мешок</a>";
    }else{



    if (isset($_GET[attack])) { $page.="<br/><br/><a href='./?attack=$_GET[attack]&amp;id=$_GET[id]'>В бой</a>";}
  else{
	$page.="<br/><img src='/img/icon/um.PNG'/> <a href='./?do=inv&amp;view=eqip'>Экипировка</a>";
  	$page.="<br/><img src='/img/icon/kg.PNG'/> Груз: ".$player[gruz]."/".(100+30*$fact_params[str]);
  	$page.="<br/><img src='/img/icon/kredit.PNG'/> Кредитов: ".$player[money];
$page.='<br><br>Рюкзак:';
$tmpgruz=0;
if(empty($bag)){$page.='<br>Ваш рюкзак пуст!';
}else{

$kol=15;//Количество вещей на стр
settype($_GET['p'],'int');
$p=$_GET['p']>=1?$_GET['p']:1;
$c=ceil(sizeof($bag)/$kol);
for($i=$p*$kol-$kol;$i<$p*$kol&&$i<=sizeof($bag);$i++){
$tmpgruz=$tmpgruz+$bag[$i]['colvo']*$bag[$i]['about_item']['massa'];
$page.='<br><a href="/?do=inv&amp;view='.$i.'">'.$bag[$i]['name'];
if($bag[$i]['colvo']>1){$page.='['.$bag[$i]['colvo'].']';}
$page.='</a>';
}
$page.=nav_page($c,$p,'/?do=inv&p=');
    	$tmpgruz=$tmpgruz+ $bodyarm[about_item][massa];
    	$tmpgruz=$tmpgruz+ $weapon1[about_item][massa];
    	$tmpgruz=$tmpgruz+ $weapon2[about_item][massa];
    	$tmp=0;
    	if (is_array($status[barter][to])) {
    		foreach($status[barter][to] as $key=>$value) {
    			if (is_array($value)) {
    				   foreach($value as $tmp=>$val) {
                           $tmpgruz=$tmpgruz+$value[colvo]*$value[about_item][massa];
                       }
    			}
    		}
    	}
    	if ($player[gruz]!= $tmpgruz) {
                $sql=mysql_query("UPDATE users SET gruz='$tmpgruz' WHERE id='$player[id]' LIMIT 1");
                $page.=mysql_error();
    	}
  	}
  }}
  $page.="<br/><a href='./?rand=".rand(1,1000)."'>В игру</a><br/><br/>";
?>