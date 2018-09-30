<?  $title="Личный ящик";
    $sql = mysql_query("SELECT obj_list FROM locations WHERE loc_id='$player[loc_id]' LIMIT 1");
  	//$obj_list=unserialize(mysql_result($sql,"obj_list"));
    if ($player[loc_id]!="bank") {$page.="<br/>Вы не в банке!<br/>".$player['loc_id'];}
    else {
        $maxcount=20;
        $bag=unserialize($player[bank]);
    	if ($_GET[bank]=="from")  {
           $page.="<p class='d'>Взять</p>";
           if (isset($_GET[id])) {           	  if (isset($_POST[colvo])) {$_GET[colvo]=$_POST[colvo];}           	  if (isset($_GET[colvo])) {           	  	if ($_GET[colvo]=='colvo') {$colvo=$bag[$_GET[id]][colvo];}
           	  	elseif ($_GET[colvo]>$bag[$_GET[id]][colvo]) {$colvo=$bag[$_GET[id]][colvo];}
           	  	else  {$colvo=$_GET[colvo];}
           	  	$player[fact_params]=unserialize($player[fact_params]);
                if (($player[gruz]+$colvo*$bag[$_GET[id]][about_item][massa])>($player[fact_params][str]*30+100))
                   {$page.="<br/>Слишком тяжело!<br/>";}
                else {
                   $t="<br/>Вы забрали ".$bag[$_GET[id]][name];
                   if ($colvo>1) {$t.=" $colvo штук ";}                   $baguser=unserialize($player[bag]);
                   $player[gruz]=$player[gruz]+$colvo*$bag[$_GET[id]][about_item][massa];
                   for ($i=0;$i<sizeof($baguser);$i++){                      if  ($baguser[$i][id]==$bag[$_GET[id]][id] and $baguser[$i][name]==$bag[$_GET[id]][name] and $baguser[$i][info]==$bag[$_GET[id]][info]){
                      	   $baguser[$i][colvo]=$baguser[$i][colvo]+$colvo;
                      	   $k=1;
                      	   break;
                      }                   }
                   if ($k!=1) {                        $item=$bag[$_GET[id]];
                        $item[colvo]=$colvo;
                        $baguser[]=$item;                   }
                   if ($colvo>=$bag[$_GET[id]][colvo]){$bag=delete_element($bag,$_GET[id]);}
                   else{$bag[$_GET[id]][colvo]=$bag[$_GET[id]][colvo]-$colvo;}
                   $page.="<br/>Всего на хранении: ".sizeof($bag)."/$maxcount<br/>".$t;
                   $baguser=serialize($baguser);
                   $bag=serialize($bag);
                   $sql=mysql_query("UPDATE users SET bag='$baguser',bank='$bag',gruz='$player[gruz]' WHERE id='$player[id]' LIMIT 1");
                }           	  }
           	  else {

           	    $page.="<br/>Всего на хранении: ".sizeof($bag)."/$maxcount<br/>";                $page.=about_item($bag[$_GET[id]]);
                if ($bag[$_GET[id]][colvo]==1) {$page.="<br/><a href='./?bank=from&amp;id=$_GET[id]&amp;colvo=colvo'>Забрать!</a>";}
                else {                   $page.="<br/>Количество: ".$bag[$_GET[id]][colvo].
            			 "<br/>Введите количество
            			 <form action='./?bank=from&amp;id=$_GET[id]' method='post'>
            			<br /><input type='text' name='colvo' value='".$bag[$_GET[id]][colvo]."' />
            			<br /><input type='submit' value='Забрать' />
            			</form>";                }
                $page.="<br/>";
               if (isset($_GET[view])) {$page.="<br/><a href='./?bank=from&amp;view=$_GET[view]'>Назад</a>";}
              }
               $page.="<br/><a href='./?bank=from'>К списку хранимых</a>";           }
           elseif (isset($_GET[view])) {
              $page.="<br/>Всего на хранении: ".sizeof($bag)."/$maxcount<br/>";           	  if ($_GET[view]=="weap"){$tmp="weapon";}
              elseif ($_GET[view]=="arm"){$tmp="bodyarm";}
              elseif ($_GET[view]=="patr"){$tmp="patron";}
              elseif ($_GET[view]=="med"){$tmp="medicament";}
              else {$tmp="misc";}
              if (!is_int($_GET[str])){$_GET[str]=1;}
              $begin=($_GET[str]-1)*15;
               	  	 for($i=$begin;$i<sizeof($bag);$i++) {               	  	   if ($i>$begin+15){break;}
                       if ($bag[$i][type]==$tmp) {
                         $page.="<br/><a href='./?bank=from&amp;id=$i&amp;view=$_GET[view]'>".$bag[$i][name];
                         if ($bag[$i][colvo]>0) {$page.="[".$bag[$i][colvo]."]";}
                         $page.="</a>";
                       }
                     }
                     $page.=nav_page(ceil($count/15),$_GET[str],"./?bank=from&amp;view=$_GET[view]&amp;str=");
                    $page.="<br/>";
                    $page.="<br/><a href='./?bank=from'>Назад</a>";           }
           else{
               $page.="<br/>Всего на хранении: ".sizeof($bag)."/$maxcount<br/>";               $count=sizeof($bag);
               if ($count<=0) {$page.="<br/>Ничего нет<br/>";}
               elseif ($count>15) {               	  for($i=0;$i<sizeof($bag);$i++) {               	  	 if ($bag[$i][type]=="weapon") {$weap++;}
               	  	 if ($bag[$i][type]=="medicament") {$med++;}
               	  	 if ($bag[$i][type]=="bodyarm") {$arm++;}
               	  	 if ($bag[$i][type]=="patrons") {$patr++;}
               	  	 if ($bag[$i][type]=="misc") {$misc++;}               	  }
               	  if ($weap>0) {$page.="<br/><a href='./?bank=from&amp;view=weap'>Оружие[$weap]</a>";}                  if ($arm>0) {$page.="<br/><a href='./?bank=from&amp;view=arm'>Броня[$arm]</a>";}
                  if ($patr>0) {$page.="<br/><a href='./?bank=from&amp;view=patr'>Патроны[$patr]</a>";}
                  if ($med>0) {$page.="<br/><a href='./?bank=from&amp;view=med'>Медикаменты[$med]</a>";}
                  if ($misc>0) {$page.="<br/><a href='./?bank=from&amp;view=misc'>Разное[$misc]</a>";}
                  $page.="<br/>";               }
               else {
                  if (!is_array($bag)){$page.="$bag<br/> На хранении ничего нет!";}
            	  else {               	    for($i=0;$i<sizeof($bag);$i++) {
                      $page.="<br/><a href='./?bank=from&amp;id=$i'>".$bag[$i][name];
                      if ($bag[$i][colvo]>0) {$page.="[".$bag[$i][colvo]."]";}
                      $page.="</a>";
                    }
                  }
                  $page.="<br/>";               }
           }
          $page.="<br/><a href='./?bank=to'>Положить</a>";    	}
    	elseif ($_GET[bank]=="to")  {
           $page.="<p class='d'>Положить</p>";
           $baguser=unserialize($player[bag]);
           if (isset($_GET[id])) {           	  if ($baguser[$_GET[id]][colvo]<=1) {$_GET[colvo]="colvo";}
              if ($baguser[$_GET[id]][colvo]>1 and !isset($_GET[colvo]) and !isset($_POST[colvo])) {
                  $page.="<br/>Всего на хранении: ".sizeof($bag)."/$maxcount<br/>";
                $page.=about_item($baguser[$_GET[id]]);
                if ($baguser[$_GET[id]][colvo]==1) {$page.="<br/><a href='./?bank=to&amp;id=$_GET[id]&amp;colvo=colvo'>Положить!</a>";}
                else {
                   $page.="<br/>Количество: ".$baguser[$_GET[id]][colvo].
            			 "<br/>Введите количество
            			 <form action='./?bank=to&amp;id=$_GET[id]' method='post'>
            			<br /><input type='text' name='colvo' value='".$baguser[$_GET[id]][colvo]."' />
            			<br /><input type='submit' value='Положить' />
            			</form>";
                }
               $page.="<br/>";

              }
           	  elseif (isset($_POST[colvo]) or isset($_GET[colvo])) {
           	    if (isset($_POST[colvo])){$_GET[colvo]=$_POST[colvo];}
           	  	if ($_GET[colvo]=='colvo') {$colvo=$baguser[$_GET[id]][colvo];}
           	  	elseif ($_GET[colvo]>$baguser[$_GET[id]][colvo]) {$colvo=$baguser[$_GET[id]][colvo];}
           	  	else  {$colvo=$_GET[colvo];}
                if (sizeof($bag)>=$maxcount)
                	{$page.="<br/>Не хватает места!!!<br/>";}
                else {
                $t="<br/>Вы положили ".$baguser[$_GET[id]][name];
                   if ($colvo>1) {$t.=" $colvo штук ";}
                   $t.=" на хранение <br/>";
                   $player[gruz]=$player[gruz]-$colvo*$baguser[$_GET[id]][about_item][massa];
                   for ($i=0;$i<sizeof($bag);$i++){
                      if  ($bag[$i][id]==$baguser[$_GET[id]][id] and $bag[$i][name]==$baguser[$_GET[id]][name] and $bag[$i][info]==$baguser[$_GET[id]][info]){
                      	   $bag[$i][colvo]=$bag[$i][colvo]+$colvo;
                      	   $k=1;
                      	   break;
                      }
                   }
                   if ($k!=1) {
                        $item=$baguser[$_GET[id]];
                        $item[colvo]=$colvo;
                        $bag[]=$item;
                   }
                   if ($baguser[$_GET[id]][colvo]>$colvo) {                        $baguser[$_GET[id]][colvo]= $baguser[$_GET[id]][colvo]-$colvo;                   }
                   else {$baguser=delete_element($baguser,$_GET[id]);}
                   $page.="<br/>Всего на хранении: ".sizeof($bag)."/$maxcount<br/>".$t;
                   $bag=serialize($bag);
                   $baguser=serialize($baguser);
                   $sql=mysql_query("UPDATE users SET bag='$baguser',bank='$bag',gruz='$player[gruz]' WHERE id='$player[id]' LIMIT 1");
                }

           	  }
              $page.="<br/><a href='./?bank=to'>Назад</a>";
           }
           elseif (isset($_GET[view])) {           	   $page.="<br/>Всего на хранении: ".sizeof($bag)."/$maxcount<br/>";
               $page.=about_item($baguser[$_GET[view]]);
               if ($baguser[$_GET[view]][colvo]==1) {$page.="<br/><a href='./?bank=to&amp;id=$_GET[id]&amp;colvo=colvo'>Положить!</a>";}
                else {
                   $page.="<br/>Количество: ".$baguser[$_GET[view]][colvo].
            			 "<br/>Введите количество
            			<form action='./?bank=to&amp;id=$_GET[view]' method='post'>
            			<br /><input type='text' name='colvo' value='".$baguser[$_GET[view]][colvo]."' />
            			<br /><input type='submit' value='Продать' />
            			</form>";
                }
               $page.="<br/>";
               $page.="<br/><a href='./?bank=to'>Назад</a>";
           }
           else{
               $page.="<br/>Всего на хранении: ".sizeof($bag)."/$maxcount<br/>";
               if (!is_array($baguser)) {$page.="$bag<br/>У вас ничего нет<br/>";}
               else {
               	  for($i=0;$i<sizeof($baguser);$i++) {
                      $page.="<br/><a href='./?bank=to&amp;id=$i'>".$baguser[$i][name];
                      if ($baguser[$i][colvo]>0) {$page.="[".$baguser[$i][colvo]."]";}
                      $page.="</a> <a href='./?trade=sell&amp;npc=$_GET[npc]&amp;view=$i'>[inf]</a>";
                  }
                  $page.="<br/>";
               }
           }
          $page.="<br/><a href='./?bank=from'>Забрать</a>";
    	}

    }
     $page.="<br/><a href='./'>В игру</a><br/><br/>";