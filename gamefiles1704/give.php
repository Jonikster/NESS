<?
   	       $page.="<p class='d'>Пeрeдать</p>";
           $bag=unserialize($player[bag]);
           if (isset($_GET[id]) and isset($_POST[colvo])) {
           	  if ($bag[$_GET[id]][colvo]<=1 or $_POST[colvo]<1) {$_POST[colvo]=1;}
              elseif ($bag[$_GET[id]][colvo]<$_POST[colvo]) {$_POST[colvo]=$bag[$_GET[id]][colvo];}
              $sql=mysql_query("SELECT char_name,loc_id,status,onlinetime FROM users WHERE id='$_GET[to]' LIMIT 1");
              if (mysql_num_rows($sql)!=1) {$page.="<br/>Такой персонаж не найден!<br/>";}
              else{              	$user=mysql_fetch_array($sql);
               	if ($user[loc_id]!=$player[loc_id]) {$page.="<br/>$user[char_name] уже ушел!<br/>";}
               	elseif ((time()-$user[onlinetime])>5*60) {$page.="<br/>$user[char_name] оффлайн!<br/>";}
                else {                	$user[status]=unserialize($user[status]);
                	$page.="<br/>Вы передали $user[char_name] ".$bag[$_GET[id]][name]." в количестве ".$_POST[colvo]."!<br/>";
              		$item=$bag[$_GET[id]];
              		$item[colvo]=$_POST[colvo];
              		$item[cena]=$_POST[cena];
              		$status[barter][to][$_GET[to]][]=$item;
              		$user[status][barter][from][$player[id]]=time();
              		if ($bag[$_GET[id]][colvo]>$_POST[colvo]) {
                 	$bag[$_GET[id]][colvo]= $bag[$_GET[id]][colvo]-$_POST[colvo];
              		}
              		else {$bag=delete_element($bag,$_GET[id]);}
              		$bag=serialize($bag);
              		$status=serialize($status);
                    $sql=mysql_query("UPDATE users SET bag='$bag',status='$status' WHERE id='$player[id]' LIMIT 1");
                    $user[status]=serialize($user[status]);
                    $sql=mysql_query("UPDATE users SET status='$user[status]' WHERE id='$_GET[to]' LIMIT 1");
               }
              }

              $page.="<br/><a href='./?do=give&amp;to=$_GET[to]'>Назад</a>";
           }
           elseif (isset($_GET[view])) {
               $page.=about_item($bag[$_GET[view]]);
               $page.="<br/>Количество: ".$bag[$_GET[view]][colvo].
            			"<br/>Введите количество
            			<form action='./?do=give&amp;to=$_GET[to]&amp;id=$_GET[view]' method='post'>
            			<br /><input type='text' name='colvo' value='".$bag[$_GET[view]][colvo]."' />
            			<br />Цена<br /><input type='text' name='cena' value='".($bag[$_GET[view]][colvo]*$bag[$_GET[view]][about_item][cena])."' />
            			<br /><input type='submit' value='Передать' />
            			</form>";
               $page.="<br/>";
               $page.="<br/><a href='./?do=give&amp;to=$_GET[to]'>Назад</a>";
           }
           elseif(isset($_GET[to])){
               if (!is_array($bag)) {$page.="$bag<br/>У вас ничего нет<br/>";}
               else {
               	  for($i=0;$i<sizeof($bag);$i++) {
                      $page.="<br/><a href='./?do=give&amp;to=$_GET[to]&amp;view=$i'>".$bag[$i][name]."</a>";
                      if ($bag[$i][colvo]>0) {$page.="[".$bag[$i][colvo]."]";}
                  }
                  $page.="<br/>";
               }
           }
           elseif (isset($_GET[from])){                 $sql=mysql_query("SELECT char_name,status,bag,gruz,money FROM users WHERE id='$_GET[from]' LIMIT 1");
                 $user=mysql_fetch_array($sql);
                 $user[status]=unserialize($user[status]);
                 if (empty($user[status][barter][to][$player[id]])) {$page.="<br/>$user[char_name] нечего предложить вам<br/>";}
                 else {                 	if (isset($_GET[varno])) {                 		$user[bag]=unserialize($user[bag]);                 		$page.="<br/>Вы отказались от ".$user[status][barter][to][$player[id]][$_GET[varno]][name]." <br/>";
                        $item=$user[status][barter][to][$player[id]][$_GET[varno]];
                        $item=unset_as_mass($item,"cena");
                        $user[status][tmp][]="<br/>$player[char_name] отказался от ".$user[status][barter][to][$player[id]][$_GET[varno]][name]." <br/>";
                        $user[status][barter][to][$player[id]]=unset_as_mass($user[status][barter][to][$player[id]],$_GET[varno]);
                        if (empty($user[status][barter][to][$player[id]])) {                            $status[barter][from]=unset_as_mass($status[barter][from],$_GET[from]);
                            if (empty($status[barter][from])) {$status[barter]=unset_as_mass($status[barter],"from");}
                            $tmp=serialize($status);
                            $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");                        }
                        for ($i=0;$i<sizeof($user[bag]);$i++){
                          if  ($user[bag][$i][id]==$item[id] and $user[bag][$i][name]==$item[name] and $user[bag][$i][info]==$item[info]){
                      	   	$user[bag][$i][colvo]=$user[bag][$i][colvo]+$item[colvo];
                      	   	$k=$i;
                      	   	break;
                          }
                        }
                       if (!isset($k) and $item[colvo]>0) {$user[bag][]=$item;}
                       $user[bag]=serialize($user[bag]);
                       $user[status]=serialize($user[status]);
                       $sql=mysql_query("UPDATE users SET status='$user[status]',bag='$user[bag]' WHERE id='$_GET[from]' LIMIT 1");                 	}
                 	elseif (isset($_GET["var"])) {
                 		$item=$user[status][barter][to][$player[id]][$_GET["var"]];
                        $cena=$item[cena];
                        if ($player[money]<$cena) {$page.="<br/>У вас не хватает кредитов!<br/>";}
                        else {
                        $item=unset_as_mass($item,"cena");
                        $page.="<br/>Вы приобрели ".$user[status][barter][to][$player[id]][$_GET["var"]][name]." за ".$user[status][barter][to][$player[id]][$_GET["var"]][cena]." <br/>";

                        $user[status][tmp][]="<br/>$player[char_name] приобрел ".$user[status][barter][to][$player[id]][$_GET["var"]][name]." <br/>";
                        $gruz=$item[about_item][massa]*$item[colvo];
                        $user[gruz]=$user[gruz]-$gruz;
                        $player[gruz]=$player[gruz]+$gruz;
                        $user[money]=$user[money]+$cena;
                        $player[money]=$player[money]-$cena;
                        $user[status][barter][to][$player[id]]=unset_as_mass($user[status][barter][to][$player[id]],$_GET["var"]);
                        if (empty($user[status][barter][to][$player[id]])) {
                            $status[barter][from]=unset_as_mass($status[barter][from],$_GET[from]);
                            if (empty($status[barter][from])) {$status[barter]=unset_as_mass($status[barter],"from");}
                            $tmp=serialize($status);
                            $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
                        }
                        for ($i=0;$i<sizeof($bag);$i++){
                          if  ($bag[$i][id]==$item[id] and $bag[$i][name]==$item[name] and $bag[$i][info]==$item[info]){
                      	   	$bag[$i][colvo]=$bag[$i][colvo]+$item[colvo];
                      	   	$k=$i;
                      	   	break;
                          }
                        }
                       if (!isset($k) and $item[colvo]>0) {$bag[]=$item;}
                       $bag=serialize($bag);
                       $sql=mysql_query("UPDATE users SET bag='$bag',gruz='$player[gruz]',money='$player[money]' WHERE id='$player[id]' LIMIT 1");
                       $user[status]=serialize($user[status]);
                       $sql=mysql_query("UPDATE users SET status='$user[status]',gruz='$user[gruz]',money='$user[money]' WHERE id='$_GET[from]' LIMIT 1");
                 	 }
                 	}
                 	else {
                 		$page.="<br/>$user[char_name] предлагает вам:<br/>";
                 		foreach($user[status][barter][to][$player[id]] as $key=>$value){                 	  		$page.="<br/>$value[name] [$value[colvo]] за $value[cena]";
                      		$page.=" [<a href='./?do=give&amp;from=$_GET[from]&amp;var=$key'>ok</a>]";
                      		$page.="[<a href='./?do=give&amp;from=$_GET[from]&amp;varno=$key'>отказ</a>]";
                 		}
                 		$page.="<br/>";
                 	}
                 }           }
           else {           	  if (!is_array($status[barter][from])) {$page.=$status[barter][from]."<br/>Сделок нет!<br/>";}
           	  else {                 $page.="<br/>Вам предлагают:<br/>";
                 foreach($status[barter][from] as $key=>$value){                 	  if (empty($value)){                 	  	$status[barter][from]=unset_as_mass($status[barter][from],$key);
                 	    $tmp=serialize($status);
                 	    $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
                 	  }
                 	  else{                      	$sql=mysql_query("SELECT char_name FROM users WHERE id='$key' LIMIT 1");
                      	$username=mysql_result($sql,0,"char_name");
                      	$page.="<br/><a href='./?do=give&amp;from=$key'>$username</a>";
                      	$page.=date("j.m.",$status[barter][from][$key]).(date("Y",$status[barter][from][$key])+170).date("  G:i",$status[barter][from][$key]);                 	  }
                 }
                 $page.="<br/>";           	  }           }
           $page.="<br/><a href='./'>В игру</a>";
?>