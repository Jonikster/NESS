<?  $title="Торговля";
    $sql = mysql_query("SELECT obj_list FROM locations WHERE loc_id='$player[loc_id]' LIMIT 1");
  	$obj_list=unserialize(mysql_result($sql,0,"obj_list"));
  	$player[skills]=unserialize($player[skills]);
    if ($obj_list[$_GET[npc]][type]!="npc") {$page.="<br/><img src='/img/icon/cancel.PNG'/> Торговля невозможна!<br/>";}
    else {
    	if ($_GET[trade]=="buy")  {
           $bag=$obj_list[$_GET[npc]][bag];
           $page.="<p class='d'>Купить</p>";
           if ($obj_list[$_GET[npc]][updatetime]<time())
           {
             $obj_list[$_GET[npc]][bag]="";
             foreach($bag as $key=>$value){
             	$obj_list[$_GET[npc]]=update_inv_obj($value[id],$value[colvo],$obj_list[$_GET[npc]],0);
             }
             $obj_list[$_GET[npc]][updatetime]=time()+3600;
             $tmp=serialize($obj_list);
             $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");
           }
           if (isset($_GET[id])) {
           	  if (isset($_POST[colvo])) {$_GET[colvo]=$_POST[colvo];}
           	  if (isset($_GET[colvo])) {
           	  	if ($_GET[colvo]=='colvo') {$colvo=$bag[$_GET[id]][colvo];}
           	  	elseif ($_GET[colvo]>$bag[$_GET[id]][colvo]) {$colvo=$bag[$_GET[id]][colvo];}
           	  	else  {$colvo=$_GET[colvo];}
           	  	$cena=intval(ceil(($bag[$_GET[id]][about_item][cena]*(125-$player[skills][trade][level]))/100));
           	  	$fact_params=unserialize($player[fact_params]);
                if ($player[money]<($cena*$colvo))
                   {$page.="<br/><img src='/img/icon/kredit.PNG'/> Ваши финансы: ".$player[money]."<br/>";$page.="<br/><img src='/img/icon/cancel.PNG'/> Не хватает денег!<br/>";}
                elseif (($player[gruz]+$colvo*$bag[$_GET[id]][about_item][massa])>($fact_params[str]*30+100))
                   {$page.="<br/><img src='/img/icon/cancel.PNG'/> Слишком тяжело!<br/>";}
                else {
								   
                   $player[money]=$player[money]-$cena*$colvo;
									 
                   $page.="<br/><img src='/img/icon/kredit.PNG'/> Ваши финансы: ".$player[money]."<br/>";
                   $page.="<br/><img src='/img/icon/shop.PNG'/> Вы приобрели ".$bag[$_GET[id]][name];
									 
									 
                   if ($colvo>1) {$page.=" $colvo штук "; }
                   $page.=" за ".$cena*$colvo."!<br/>";
									 
                   $baguser=unserialize($player[bag]);
                   if  ( $bag[$_GET[id]][type]!="patrons" ) {$player[skills][trade][act]=$player[skills][trade][act]+$colvo;}
                   $player[gruz]=$player[gruz]+$colvo*$bag[$_GET[id]][about_item][massa];
                   for ($i=0;$i<sizeof($baguser);$i++){
                      if  ($baguser[$i][id]==$bag[$_GET[id]][id] and $baguser[$i][name]==$bag[$_GET[id]][name] and $baguser[$i][info]==$bag[$_GET[id]][info]){
                      	   $baguser[$i][colvo]=$baguser[$i][colvo]+$colvo;
                      	   $k=1;
                      	   break;
                      }
                   }
                   if ($k!=1) {
                        $item=$bag[$_GET[id]];
                        $item[colvo]=$colvo;
                        $baguser[]=$item;
                   }
                   $baguser=serialize($baguser);
                   $skills=serialize($player[skills]);
                   $sql=mysql_query("UPDATE users SET bag='$baguser',skills='$skills',gruz='$player[gruz]',money='$player[money]' WHERE id='$player[id]' LIMIT 1");
                }

           	  }
           	  else {
           	    $page.="<br/><img src='/img/icon/kredit.PNG'/> Ваши финансы: ".$player[money]."<br/>";
                $page.=about_item($bag[$_GET[id]]);
                $page.="<br/>Отпускная цена: ".(intval(ceil(($bag[$_GET[id]][about_item][cena]*(125-$player[skills][trade][level]))/100)));
                $page.="<br/>";
                if ($bag[$_GET[id]][colvo]==1) {$page.="<a href='./?trade=buy&amp;npc=$_GET[npc]&amp;id=$_GET[id]&amp;colvo=colvo'>Купить!</a><br/>";}
                else {
                   $page.="Количество: ".$bag[$_GET[id]][colvo].
            			"<br/>Введите количество:
            			<br/><a href='./?trade=buy&amp;npc=$_GET[npc]&amp;id=$_GET[id]&amp;colvo=colvo'>Купить все!</a>
            			<form action='./?trade=buy&amp;npc=$_GET[npc]&amp;id=$_GET[id]' method='post'>
            			<input type='text' name='colvo' value='".$bag[$_GET[view]][colvo]."'/>
            			<br/><input type='submit' value='Купить'/>
            			</form>";
									}

               if (isset($_GET[view])) {$page.="<br/><a href='./?trade=buy&amp;npc=$_GET[npc]&amp;view=$_GET[view]'>Назад</a>";}
              }
               $page.="<br/><a href='./?trade=buy&amp;npc=$_GET[npc]'>Все товары</a>";
           }
           elseif (isset($_GET[view])) {
              $page.="<br/><img src='/img/icon/kredit.PNG'/> Ваши финансы: ".$player[money]."<br/>";
           	  if ($_GET[view]=="weap"){$tmp="weapon";}
              elseif ($_GET[view]=="arm"){$tmp="bodyarm";}
              elseif ($_GET[view]=="patr"){$tmp="patron";}
              elseif ($_GET[view]=="med"){$tmp="medicament";}
              else {$tmp="misc";}
               	  	 for($i=0;$i<sizeof($bag);$i++) {
                       if ($bag[$i][type]==$tmp) {
                         $page.="<br/><a href='./?trade=buy&amp;npc=$_GET[npc]&amp;id=$i&amp;view=$_GET[view]'>".$bag[$i][name];
                         if ($bag[$i][colvo]>0) {$page.=" [".$bag[$i][colvo]."]";}
                         $page.="</a> - ".(intval(ceil(($bag[$i][about_item][cena]*(125-$player[skills][trade][level]))/100)));
                       }
                     }
                  $page.="<br/>";
                  $page.="<br/><a href='./?trade=buy&amp;npc=$_GET[npc]&amp;rand=".rand(1,1000)."'>Назад</a>";
           }
           else{
               $page.="<br/><img src='/img/icon/kredit.PNG'/> Ваши финансы: ".$player[money]."<br/>";
               $count=sizeof($obj_list[$_GET[npc]][bag]);
               if ($count<=0) {$page.="<br/>Ничего нет<br/>";}
               elseif ($count>15) {
               	  for($i=0;$i<sizeof($bag);$i++) {
               	  	 if ($bag[$i][type]=="weapon") {$weap++;}
               	  	 if ($bag[$i][type]=="medicament") {$med++;}
               	  	 if ($bag[$i][type]=="bodyarm") {$arm++;}
               	  	 if ($bag[$i][type]=="patron") {$patr++;}
               	  	 if ($bag[$i][type]=="misc") {$misc++;}
               	  }
               	  if ($weap>0) {$page.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?trade=buy&amp;npc=$_GET[npc]&amp;view=weap'>Оружие [$weap]</a>";}
                  if ($arm>0) {$page.="<br/><img src='/img/icon/shield.PNG'/> <a href='./?trade=buy&amp;npc=$_GET[npc]&amp;view=arm'>Броня [$arm]</a>";}
                  if ($patr>0) {$page.="<br/><img src='/img/icon/patron.PNG'/> <a href='./?trade=buy&amp;npc=$_GET[npc]&amp;view=patr'>Патроны [$patr]</a>";}
                  if ($med>0) {$page.="<br/><img src='/img/icon/heal.PNG'/> <a href='./?trade=buy&amp;npc=$_GET[npc]&amp;view=med'>Медикаменты [$med]</a>";}
                  if ($misc>0) {$page.="<br/><img src='/img/icon/util.PNG'/> <a href='./?trade=buy&amp;npc=$_GET[npc]&amp;view=misc'>Разное [$misc]</a>";}
                  $page.="<br/>";
               }
               else {
                  if (!is_array($bag)){$page.="$bag<br/><img src='/img/icon/cancel.PNG'/> У торговца ничего нет!";}
            	  else {
               	    for($i=0;$i<sizeof($bag);$i++) {
                      $page.="<br/><a href='./?trade=buy&amp;npc=$_GET[npc]&amp;id=$i'>".$bag[$i][name];
                      if ($bag[$i][colvo]>0) {$page.=" [".$bag[$i][colvo]."]";}
                      $page.="</a> - ".(intval(ceil(($bag[$i][about_item][cena]*(125-$player[skills][trade][level]))/100)));
                    }
                  }
                  $page.="<br/>";
               }
           }
					 
          $page.="<br/><a href='./?trade=sell&amp;npc=$_GET[npc]'>Режим продажи</a>";
    	}
    	elseif ($_GET[trade]=="sell")  {
			
           $page.="<p class='d'>Продать</p>";
           $page.="<br/><img src='/img/icon/kredit.PNG'/> Финансы торговца: ".$obj_list[$_GET[npc]][money]."<br/>";
           $bag=unserialize($player[bag]);
           if (isset($_GET[id])) {
           	  if ($bag[$_GET[id]][colvo]<=1) {$_GET[colvo]="colvo";}
              if ($bag[$_GET[id]][colvo]>1 and !isset($_GET[colvo]) and !isset($_POST[colvo])) {
                $page.=about_item($bag[$_GET[id]]);
                $page.="<br/>Продажная цена: ".(intval(ceil(($bag[$_GET[id]][about_item][cena]*(75+$player[skills][trade][level]))/100)));
                if ($bag[$_GET[id]][colvo]==1) {$page.="<br/><a href='./?trade=sell&amp;npc=$_GET[npc]&amp;id=$_GET[id]&amp;colvo=colvo'>Продать!</a>";}
                else {
                   $page.="<br/>Количество: ".$bag[$_GET[id]][colvo].
            			"<br/>Введите количество:
            			<form action='./?trade=sell&amp;npc=$_GET[npc]&amp;id=$_GET[id]' method='post'>
            			<input type='text' name='colvo' value='".$bag[$_GET[id]][colvo]."' />
            			<br/><input type='submit' value='Продать' />
            			</form>";
                }
              }
           	  elseif (isset($_POST[colvo]) or isset($_GET[colvo])) {
           	    if (isset($_POST[colvo])){$_GET[colvo]=$_POST[colvo];}
           	  	if ($_GET[colvo]=='colvo') {$colvo=$bag[$_GET[id]][colvo];}
           	  	elseif ($_GET[colvo]>$bag[$_GET[id]][colvo]) {$colvo=$bag[$_GET[id]][colvo];}
           	  	else  {$colvo=$_GET[colvo];}
           	  	$cena=intval(ceil(($bag[$_GET[id]][about_item][cena]*(75+$player[skills][trade][level]))/100));
           	  	$fact_params=unserialize($player[fact_params]);
                if ($obj_list[$_GET[npc]][money]<$cena*$colvo)
                	{$page.="<br/><img src='/img/icon/cancel.PNG'/> У ".$obj_list[$_GET[npc]][name]."  Нет столько денег!<br/>";}
                else {
                   $page.="<br/><img src='/img/icon/shop.PNG'/> Вы продали ".$bag[$_GET[id]][name];
                   if ($colvo>1) {$page.=" $colvo штук ";}
                   $page.=" за ".($cena*$colvo)."! <br/>";
                   $player[gruz]=$player[gruz]-$colvo*$bag[$_GET[id]][about_item][massa];
                   $player[money]=$player[money]+$cena*$colvo;
                   $obj_list[$_GET[npc]][money]=$obj_list[$_GET[npc]][money]-$cena*$colvo;
									 
                    if  ( $bag[$_GET[id]][type]!="patrons" ) {$player[skills][trade][act]=$player[skills][trade][act]+$colvo;}
                   	  if ($bag[$_GET[id]][colvo]>$colvo) {
                        $bag[$_GET[id]][colvo]= $bag[$_GET[id]][colvo]-$colvo;
	
                   	  }
                   	  else {$bag=delete_element($bag,$_GET[id]);}
											

                   $obj_list = serialize($obj_list);
                   $sql=mysql_query("UPDATE locations SET obj_list='$obj_list'WHERE loc_id='$player[loc_id]' LIMIT 1");
                   $bag=serialize($bag);
                   $skills=serialize($player[skills]);
                   $sql=mysql_query("UPDATE users SET bag='$bag',skills='$skills',gruz='$player[gruz]',money='$player[money]' WHERE id='$player[id]' LIMIT 1");
                }
           	  }
              $page.="<br/><a href='./?trade=sell&amp;npc=$_GET[npc]'>Назад</a>";
           }
           elseif (isset($_GET[view])) {
               $page.=about_item($bag[$_GET[view]]);
                $page.="<br/>Продажная цена: ".(intval(ceil(($bag[$_GET[view]][about_item][cena]*(75+$player[skills][trade][level]))/100)));
                if ($bag[$_GET[view]][colvo]==1) {$page.="<br/>";}
                else {
                   $page.="<br/>Количество: ".$bag[$_GET[view]][colvo].
            			"<br/>Введите количество:
            			<br/><a href='./?trade=sell&amp;npc=$_GET[npc]&amp;id=$_GET[view]&amp;colvo=colvo'>Продать все!</a>
            			<form action='./?trade=sell&amp;npc=$_GET[npc]&amp;id=$_GET[view]' method='post'>
            			<input type='text' name='colvo' value='".$bag[$_GET[view]][colvo]."' />
            			<br/><input type='submit' value='Продать' />
            			</form>";
                }
               $page.="<br/><a href='./?trade=sell&amp;npc=$_GET[npc]'>Назад</a>";
           }
           else{
               if (!is_array($bag)) {$page.="$bag<br/><img src='/img/icon/cancel.PNG'/> У вас ничего нет<br/>";}
               else {
               	  for($i=0;$i<sizeof($bag);$i++) {
                      $page.="<br/><a href='./?trade=sell&amp;npc=$_GET[npc]&amp;id=$i'>".$bag[$i][name];
                      if ($bag[$i][colvo]>1) {$page.=" [".$bag[$i][colvo]."]";}
                      $page.="</a> [<a href='./?trade=sell&amp;npc=$_GET[npc]&amp;view=$i'>инф</a>] - ".(intval(ceil(($bag[$i][about_item][cena]*(75+$player[skills][trade][level]))/100)));
                  }
                  $page.="<br/>";
               }
           }
          $page.="<br/><a href='./?trade=buy&amp;npc=$_GET[npc]'>Режим покупок</a>";
    	}

    }
     $page.="<br/><a href='./'>В игру</a><br/><br/>";
?>