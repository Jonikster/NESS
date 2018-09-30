<? if ($player[rights]!="admin"){die($goawayfuckingcheater);}
  if (isset($_GET[del])){
 	 	if (isset($_POST[confirm])){
 	 		$sql=mysql_query("DELETE FROM locations WHERE loc_id='$_GET[del]' LIMIT 1");
 	 		$page.="<br/>Локация  с id $_GET[del] была успешно уничтожена<br/>";
 	 		$page.="<br/><a href='./?do=admin&amp;mod=locations'>К списку локаций</a>";
        }
        else {
 	 	$page.="<form action='./?do=admin&amp;mod=locations&amp;del=$_GET[del]' method='post'>
 	 	 <br/>Вы уверены?
         <br/><input type='submit' name='confirm' value='Да'>
		 </form>";}
 }
  elseif (isset($_GET["copy"])){  	if (isset($_POST[loc_id])) {
  		$sql=mysql_query("SELECT * FROM locations WHERE loc_id='$_POST[loc_id]' LIMIT 1");
  		if (mysql_num_rows($sql)>0)  { $page.="<br/>Локация  с id $_POST[loc_id] уже существует!<br/>";}
  		else {
 	 		$sql=mysql_query("SELECT * FROM locations WHERE loc_id='$_GET[copy]' LIMIT 1");
 	 		$loc=mysql_fetch_array($sql);
     		$sql=mysql_query("INSERT INTO locations(loc_id,zone,loc_option,monstr_list,door_list,obj_list) VALUES ('$_POST[loc_id]','$loc[zone]','$loc[loc_option]','$loc[monstr_list]','$loc[door_list]','$loc[obj_list]');");
 	 		$page.="<br/>Локация  с id $_POST[loc_id] была успешно создана<br/>";
 	 		$page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_POST[loc_id]'>Редактировать</a>";
 	 		$k=1;
 	 	}
    }
    if ($k!=1) {
 	 $page.="<br/><form action='./?do=admin&amp;mod=locations&amp;copy=$_GET[copy]' method='post'>
       <br />Введите LOC_ID<input type='text' name='loc_id'  value='' />
       <br /><input type='submit' value='Копировать' />
       </form>";
       }
       $page.="<br/><a href='./?do=admin&amp;mod=locations'>К списку локаций</a>";
 }
  	 elseif (isset($_POST[found])){

        $sql=mysql_query("SELECT loc_id, loc_option FROM locations WHERE loc_id='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Локация с id $_POST[found] не найден<br/>";}
          else  {
          	$locs=mysql_fetch_array($sql);
			$option=unserialize($locs[loc_option]);
    			$page.="[$locs[loc_id]] - [$option[name]]".
       			 "<a href='./?do=admin&amp;mod=locations&amp;copy=$locs[loc_id]'>[copy]</a><a href='./?do=admin&amp;mod=locations&amp;redact=$locs[loc_id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=locations&amp;del=$locs[loc_id]'>[X]</a><br/>";}

 	 	$page.="<br/><a href='./?do=admin&amp;mod=locations'>К списку локаций</a>";
 	 }
 elseif (isset($_GET[redact]))
 { 	 if (isset($_POST[loc_id])){        $option=array("name"=>$_POST[name],"info"=>$_POST[info],"fight"=>$_POST[fight],"loc_x"=>$_POST[loc_x],"loc_y"=>$_POST[loc_y],"light"=>$_POST[light]);
        $option=serialize($option);
        $sql = mysql_query("UPDATE locations SET loc_option='$option',zone='$_POST[zone]' WHERE loc_id='$_GET[redact]' LIMIT 1");
        $page.="<br/>Локация  с loc_id $_GET[redact] успешно измененa
        <br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>Локация $_GET[redact]</a><br/>"; 	 }
 	 elseif ($_GET[rdmod]=="addmonst") { 	 	if (isset($_POST[monstrid])) {           $sql=mysql_query("SELECT * FROM monsters WHERE id='$_POST[monstrid]'");
           if (mysql_num_rows($sql) != 1) {$page.="<br/>Монстра с таким ID нет<br/>";}
           else {           	$monstr=mysql_fetch_array($sql);
            $temp=array("id"=>$monstr[id],"period_respawn"=>$_POST[period_respawn],"respawn"=>time(),
              "name"=>$monstr[name],"info"=>$monstr[info],"status"=>"dead","hit_points"=>0,"maxhp"=>$monstr[maxhp],
              "od"=>$monstr[maxod],"maxod"=>$monstr[maxod],"crit_chance"=>$monstr[crit_chance],"type_dmg"=>$monstr[type_dmg],
              "damage"=>$monstr[damage],"patrons"=>$monstr[maxpatrons],"maxpatrons"=>$monstr[maxpatrons],
              "bonusdex"=>$monstr[bonusdex],"medic"=>$monstr[medic],"resnormal"=>$monstr[resnormal],"resplazma"=>$monstr[resplazma],"resboom"=>$monstr[resboom],
              "resvolt"=>$monstr[resvolt],"in_fight"=>"","timemod"=>$monstr[timemod],
              "on_die"=>$monstr[on_die],"comanda"=>$_POST[comanda]);
             $sql=mysql_query("SELECT monstr_list FROM locations WHERE loc_id='$_GET[redact]'");
             $monstr_list=mysql_result($sql,0,"monstr_list");
             $monstr_list=unserialize($monstr_list);
             $monstr_list[]=$temp;
             $monstr_list=serialize($monstr_list);
             $sql=mysql_query("UPDATE locations SET monstr_list='$monstr_list' WHERE loc_id='$_GET[redact]' LIMIT 1");
             $page.="<br/>Монстр с id $monstr[id] добавлен<br/>";           } 	 	}
 	 	if (isset($_POST[comanda])) {$comanda=$_POST[comanda];}
 	 	else $comanda="monsters"; 	 	$page.=" <form action='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=addmonst' method='post'>
        <br/>ID монстра<br/><input type='text' name='monstrid' value='$_POST[monstrid]'/>
        <br/>Время обновления в секундах<br/><input type='text' name='period_respawn' value='$_POST[period_respawn]' />
        <br/>Команда в бою<br/><input type='text' name='comanda' value='$comanda' />
        <br /><input type='submit' value='Добавить' /><br />
 	    </form>";
 	    $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a>"; 	 }
 	 elseif ($_GET[rdmod]=="monst") {        $sql=mysql_query("SELECT monstr_list FROM locations WHERE loc_id='$_GET[redact]'");
        $loc=mysql_result($sql,0,"monstr_list");
        $monstr_list=unserialize($loc);
        if (empty($monstr_list) or sizeof($monstr_list)<0){$page.="<br/>Монстров на локации нет<br/>";}
        else {
        	$page.="<br/>[id][period_respawn]<br/>";        	for ($i=0;$i<sizeof($monstr_list);$i++) {
                 $page.="<br/>[ ".$monstr_list[$i][id]." ] [ ".$monstr_list[$i][period_respawn]." ]"
                 ."<a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;delmonst=$i'>[X]</a><br/>";
  			};        } $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=addmonst'>Добавить монстра</a><br/>"; 	    $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a>";
 	 }
 	 elseif (isset($_GET[delmonst])) {
 	 	$sql=mysql_query("SELECT monstr_list FROM locations WHERE loc_id='$_GET[redact]'");
        $monstr_list=mysql_result($sql,0,"monstr_list");
        $monstr_list=unserialize($monstr_list);
        for ($i=0;$i<sizeof($monstr_list);$i++) {
                 if ($i!=$_GET[delmonst]) {$mlist[]=$monstr_list[$i];}
  		};
        $monstr_list=serialize($mlist);
        $sql=mysql_query("UPDATE locations SET monstr_list='$monstr_list' WHERE loc_id='$_GET[redact]' LIMIT 1");
        $page.="<br/>Монстр # $_GET[delmonst] уничтожен<br/>
              <a href='?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a><br/> ";

     }
     elseif ($_GET[rdmod]=="obj") {
        $sql=mysql_query("SELECT obj_list FROM locations WHERE loc_id='$_GET[redact]'");
        $tmp=mysql_result($sql,0,"obj_list");
        $obj_list=unserialize($tmp);
        if (empty($obj_list) or sizeof($obj_list)<0){$page.="<br/>Нет ничего<br/>";}
        else {
        	$page.="<br/>[name][type]<br/>";
        	for ($i=0;$i<sizeof($obj_list);$i++) {
                 $page.="<br/>[ ".$obj_list[$i][name]." ] [ ".$obj_list[$i][type]." ]"
                 ." <a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;redobj=$i'>[ИЗМ]</a>
                  <a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;delobj=$i'>[X]</a><br/>";
  			};
        } $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=addobj'>Добавить объект</a><br/>";
 	    $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a>";
 	 }
 	 elseif (isset($_GET[delobj])) {
 	 	$sql=mysql_query("SELECT obj_list FROM locations WHERE loc_id='$_GET[redact]'");
        $obj_list=mysql_result($sql,0,"obj_list");
        $obj_list=unserialize($obj_list);
        for ($i=0;$i<sizeof($obj_list);$i++) {
                 if ($i!=$_GET[delobj]) {$tmplist[]=$obj_list[$i];}
  		};
        $obj_list=serialize($tmplist);
        $sql=mysql_query("UPDATE locations SET obj_list='$obj_list' WHERE loc_id='$_GET[redact]' LIMIT 1");
        $page.="<br/>Объект # $_GET[delobj] уничтожен<br/>
        <br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=obj'>Объекты</a>
              <br/><a href='?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a><br/> ";

     }
     elseif (isset($_GET[redobj])) {
 	 	$sql=mysql_query("SELECT obj_list FROM locations WHERE loc_id='$_GET[redact]'");
        $obj_list=mysql_result($sql,0,"obj_list");
        $obj_list=unserialize($obj_list);
        if (isset($_GET[invobj])) {        	$bag=$obj_list[$_GET[redobj]][bag];            if (isset($_POST[itemid])) {            	$sql=mysql_query("SELECT *  FROM items WHERE id='$_POST[itemid]' LIMIT 1");
 	 			   if (mysql_num_rows($sql)!=1)  {$page.="Предмета с ID $_POST[itemid] не существует!";}
 	 			   else {
 	 			    $page.="<br/>Предмет с ID $_POST[itemid] добавлен в количестве $_POST[colvo]";
 	 			   	$item=mysql_fetch_array($sql);
                    $item[colvo]=$_POST[colvo];
                    $item[about_item]=unserialize($item[about_item]);
                    if (is_array($bag))  {
                    	for ($i=0;$i<sizeof($bag);$i++){
                      		if  ($bag[$i][id]==$item[id] and $bag[$i][name]==$item[name] and $bag[$i][info]==$item[info]){
                      	   		$bag[$i][colvo]=$bag[$i][colvo]+$colvo;
                      	   		$k=1;
                      	   		break;
	                        }
                    	}
                    }
                    if ($k!=1) {$bag[]=$item;}
                    $obj_list[$_GET[redobj]][bag]=$bag;
                    $tmp=serialize($obj_list);
                    $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$_GET[redact]' LIMIT 1");
                    $page.=mysql_error();
                   }
            }

         		if (isset($_GET[itemdel])) {
                   $page.="<br/>".$bag[$_GET[itemdel]][name]." уничтожено<br/>";
                   $bag=delete_element($bag,$_GET[itemdel]);
                   $obj_list[$_GET[redobj]][bag]=$bag;
                   $tmp=serialize($obj_list);
                   $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$_GET[redact]' LIMIT 1");
            	}
            	if (!is_array($bag)){$page.="$bag<br/> Инвентарь пуст!";}
            	else {
            	  for ($i=0;$i<sizeof($bag);$i++){
             		$page.="<br/>".$bag[$i][name];
     		 		if ($bag[$i][colvo]>1) {$page.="[".$bag[$i][colvo]."]";}
     		 		$page.="<a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;redobj=$_GET[redobj]&amp;invobj=edit&amp;itemdel=$i'>[X]</a>";
            	  }
            	}
                $page.="<form action='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;redobj=$_GET[redobj]&amp;invobj=add' method='post'>
                <br/>ID предмета<input type='text' name='itemid' value='' />
                <br/>Количество<input type='text' name='colvo' value='1' />
                <br /><input type='submit' value='Добавить' /><br />
            	</form>";

           $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;redobj=$_GET[redobj]'>К объекту</a><br/>";
        }
        elseif (isset($_POST[name])) {                $tmp="";
        	    if (!empty($_POST[talkid])) $tmp[talkid]=$_POST[talkid];
        	    if (!empty($_POST[hard])) $tmp[hard]=$_POST[hard];
              	$obj=array("name"=>$_POST[name],"info"=>$_POST[info],"type"=>$_POST[type],
              	"status"=>$tmp,"money"=>$_POST[money],"on_enter"=>$_POST[on_enter]);
              	$obj_list[$_GET[redobj]]=$obj;
              	$obj_list=serialize($obj_list);
              	$sql=mysql_query("UPDATE locations SET obj_list='$obj_list' WHERE loc_id='$_GET[redact]'");
              	$page.="<br/>Объект изменен!<br/>";        }
        else  {
        	$obj=$obj_list[$_GET[redobj]];
        	$page.="<form action='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;redobj=$_GET[redobj]' method='post'>
        	<br/>Название<br/><input type='text' name='name' value='$obj[name]' />
        	<br/>Описание<br/><textarea name='info' rows='7'>$obj[info]</textarea>
        	<br /> Тип
        	<br /><input type='radio' name='type' value='npc'";
        	if ($obj[type]=="npc") {$page.=" checked='' ";}
        	$page.=" />Можно поговорить
        	<br /><input type='radio' name='type' value='bag'";
        	if ($obj[type]=="bag") {$page.=" checked='' ";}
        	$page.=" />Ящик
        	<br /><input type='radio' name='type' value='safe' ";
        	if ($obj[type]=="safe") {$page.=" checked='' ";}
        	$page.=" />Сейф
        	<br /><input type='radio' name='type' value='garbage' ";
         	if ($obj[type]=="garbage") {$page.=" checked='' ";}
        	$page.=" />Мусор под ногами
        	<br /><input type='radio' name='type' value='mine' ";
         	if ($obj[type]=="mine") {$page.=" checked='' ";}
        	$page.=" />Жила
        	<br/>Параметр(Cложность взлома,id руды)<br/><input type='text' name='hard' value='".$obj[status][hard]."' />
        	<br/>Денег<br/><input type='text' name='money' value='".$obj[money]."' />
        	<br/>id диалога<br/><input type='text' name='talkid' value='".$obj[status][talkid]."' />
        	<br/>При входе на локацию<br/><textarea name='on_enter' rows='7'>$obj[on_enter]</textarea>
        	<br /><input type='submit' value='Изменить' /><br />
 	    	</form>";
 	    	$page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;redobj=$_GET[redobj]&amp;invobj=edit'>Инвентарь</a>";
        }
        $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=obj'>Объекты</a>";
        $page.="<br /><a href='?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a><br/> ";
     }
     elseif ($_GET[rdmod]=="addobj") {
 	 	$sql=mysql_query("SELECT obj_list FROM locations WHERE loc_id='$_GET[redact]'");
        $obj_list=mysql_result($sql,0,"obj_list");
        $obj_list=unserialize($obj_list);
        if (isset($_POST[name])) {        	    $tmp="";
        	    if (!empty($_POST[talkid])) $tmp[talkid]=$_POST[talkid];
        	    if (!empty($_POST[hard])) $tmp[hard]=$_POST[hard];
              	$obj=array("name"=>$_POST[name],"info"=>$_POST[info],"type"=>$_POST[type],
              	"status"=>$tmp,"on_enter"=>$_POST[on_enter]);
              	$obj_list[]=$obj;
              	$obj_list=serialize($obj_list);
              	$sql=mysql_query("UPDATE locations SET obj_list='$obj_list' WHERE loc_id='$_GET[redact]'");
              	$page.="<br/>Объект добавлен!<br/>";

        }
        else {
        	$page.="<form action='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=addobj' method='post'>
        	<br/>Название<br/><input type='text' name='name' value='' />
        	<br/>Описание<br/><textarea name='info' rows='7'></textarea>
        	<br /> Тип
        	<br /><input type='radio' name='type' value='npc' />Можно поговорить
        	<br /><input type='radio' name='type' value='bag' />Ящик
        	<br /><input type='radio' name='type' value='safe' />Сейф
        	<br /><input type='radio' name='type' value='garbage' />Мусор под ногами
        	<br /><input type='radio' name='type' value='mine' />Жила
        	<br/>Параметр(Cложность взлома,id руды)<br/><input type='text' name='hard' value='' />
        	<br/>id диалога<br/><input type='text' name='talkid' value='' />
        	<br/>При входе на локацию<br/><textarea name='on_enter' rows='7'></textarea>
        	<br /><input type='submit' value='Добавить' /><br />
 	    	</form>";
        }
        $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=obj'>Объекты</a>";
        $page.="<br /><a href='?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a><br/> ";

     }
 	 elseif ($_GET[rdmod]=="door") {
        $sql=mysql_query("SELECT door_list FROM locations WHERE loc_id='$_GET[redact]'");
        $tmp=mysql_result($sql,0,"door_list");
        $door_list=unserialize($tmp);
        if (empty($door_list) or sizeof($door_list)<0){$page.="<br/>Из локации нельзя выйти<br/>";}
        else {
        	$page.="<br/>[caption][target]<br/>";
        	for ($i=0;$i<sizeof($door_list);$i++) {
                 $page.="<br/>[ ".$door_list[$i][caption]." ] [ ".$door_list[$i][target]." ]"
                 ." <a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;reddoor=$i'>[ИЗМ]</a>
                  <a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;deldoor=$i'>[X]</a><br/>";
  			};
        } $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=adddoor'>Добавить дверь</a><br/>";
 	    $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a>";
 	 }
 	 elseif (isset($_GET[deldoor])) { 	 	$sql=mysql_query("SELECT door_list FROM locations WHERE loc_id='$_GET[redact]'");
        $door_list=mysql_result($sql,0,"door_list");
        $door_list=unserialize($door_list);
        for ($i=0;$i<sizeof($door_list);$i++) {
                 if ($i!=$_GET[deldoor]) {$dlist[]=$door_list[$i];}
  		};
        $door_list=serialize($dlist);
        $sql=mysql_query("UPDATE locations SET door_list='$door_list' WHERE loc_id='$_GET[redact]' LIMIT 1");
        $page.="<br/>Дверь # $_GET[deldoor] уничтожена<br/>
        <br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=door'>Двери</a>
              <br/><a href='?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a><br/> ";

     }
     elseif ($_GET[rdmod]=="adddoor") {
 	 	$sql=mysql_query("SELECT door_list FROM locations WHERE loc_id='$_GET[redact]'");
        $door_list=mysql_result($sql,0,"door_list");
        $door_list=unserialize($door_list);
        if (isset($_POST[caption])) {
        	  $sql=mysql_query("SELECT * FROM locations WHERE loc_id='$_POST[target]'");
        	  if (mysql_num_rows($sql)!=1) {$page.="<br/>Ошибка! Неверная цель!<br/>";}
              else {              	$door[caption]=$_POST[caption];
              	$door[target]=$_POST[target];
              	$door_list[]=$door;
              	$door_list=serialize($door_list);
              	$sql=mysql_query("UPDATE locations SET door_list='$door_list' WHERE loc_id='$_GET[redact]'");
              	$page.="<br/>Дверь добавлена $_POST[caption]! Она ведет в $_POST[target]<br/>";
              }

        }
        else {
        	$page.="<form action='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=adddoor' method='post'>
        	<br/>Описание<br/><input type='text' name='caption' value=''/>
        	<br/>ID новой локации<br/><input type='text' name='target' value='' />
        	<br /><input type='submit' value='Добавить' /><br />
 	    	</form>";
        }
        $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=door'>Двери</a>";
        $page.="<br /><a href='?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a><br/> ";

     }
     elseif (isset($_GET[reddoor])) {
 	 	$sql=mysql_query("SELECT door_list FROM locations WHERE loc_id='$_GET[redact]'");
        $door_list=mysql_result($sql,0,"door_list");
        $door_list=unserialize($door_list);
        if (isset($_POST[caption])) {        	  $sql=mysql_query("SELECT loc_id FROM locations WHERE loc_id='$_POST[target]' LIMIT 1");
        	  if (mysql_num_rows($sql)!=1) {$page.="<br/>Ошибка! Неверная цель!<br/>";}              else {
              	$door_list[$_GET[reddoor]][caption]=$_POST[caption];
              	$door_list[$_GET[reddoor]][target]=$_POST[target];
              	$door_list=serialize($door_list);
              	$sql=mysql_query("UPDATE locations SET door_list='$door_list' WHERE loc_id='$_GET[redact]'");
              	$page.="<br/>Дверь изменена!<br/>";
              }        }
        else {
        	$page.="<form action='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;reddoor=$_GET[reddoor]' method='post'>
        	<br/>Описание<br/><input type='text' name='caption' value='".$door_list[$_GET[reddoor]][caption]."'/>
        	<br/>ID новой локации<br/><input type='text' name='target' value='".$door_list[$_GET[reddoor]][target]."' />
        	<br /><input type='submit' value='Изменить' /><br />
 	    	</form>";
        }
        $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=door'>Двери</a>";
        $page.="<br /><a href='?do=admin&amp;mod=locations&amp;redact=$_GET[redact]'>К локации</a><br/> ";

     }
 	 else { 	 	$sql=mysql_query("SELECT * FROM locations WHERE loc_id='$_GET[redact]'");
 	 	$loc=mysql_fetch_array($sql);
 	 	$option=unserialize($loc[loc_option]);
        $page.=" <form action='./?do=admin&amp;mod=locations&amp;redact=$loc[loc_id]' method='post'>
        <input type='hidden' name='loc_id'  value='$loc[loc_id]' />
        <br />ID Локации $loc[loc_id]
        <br />Зона<br /><input type='text' name='zone'  value='$loc[zone]' />
        <br />Название<br /><input type='text' name='name'  value='$option[name]' />
        <br />Краткое описание<br/><textarea name='info' rows='3' >$option[info]</textarea>
        <br />Можно ли драться на ней?
        <br /><input type='radio' name='fight' value='yes'";
        if ($option[fight]=="yes"){$page.=" checked='' ";}
        $page.=" /> Да
        <br /><input type='radio' name='fight' value='no'";
        if ($option[fight]=="no"){$page.=" checked='' ";}
        $page.=" /> Нет
        <br />Ширина<br /><input type='text' name='loc_x'  value='$option[loc_x]' />
        <br />Длина<br /><input type='text' name='loc_y'  value='$option[loc_y]' />
        <br />Освещенность
        <br /><input type='radio' name='light' value='temp'";
        if ($option[light]=="temp"){$page.=" checked='' ";}
        $page.=" /> Взависимости от времени суток
        <br /><input type='radio' name='light' value='forever'";
        if ($option[light]=="forever"){$page.=" checked='' ";}
        $page.=" /> Всегда светло
        <br /><input type='radio' name='light' value='never'";
        if ($option[light]=="never"){$page.=" checked='' ";}
        $page.=" /> Всегда темно
        <br /><input type='submit' value='Изменить' /><br />
        </form>
        <br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=monst'>Монстры</a>
        <br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=door'>Двери</a>
        <br/><a href='./?do=admin&amp;mod=locations&amp;redact=$_GET[redact]&amp;rdmod=obj'>Объекты</a>
        <br/>";
     }
     $page.="<br/><a href='./?do=admin&amp;mod=locations'>К списку локаций</a>"; }
 elseif ($_GET[tmp]=='new') {
    if (isset($_POST[loc_id])) {
    $loc_option=serialize(array("name" =>$_POST[name], "info" =>$_POST[info], "fight" =>$_POST[fight],
                "loc_x" =>$_POST[loc_x], "loc_y" =>$_POST[loc_y], "light" =>$_POST[light]));    $sql=mysql_query("INSERT INTO locations(loc_id,zone,loc_option) VALUES ('$_POST[loc_id]','$_POST[zone]','$loc_option');");
    $page.="<br/>Локация $_POST[name] добавлена<br/>";    }
    else{ 		$page.="<br/><form action='./?do=admin&amp;mod=locations&amp;tmp=new' method='post'>
        <br />ID Локации<br /><input type='text' name='loc_id'  value='' />
        <br />Зона<br /><input type='text' name='zone'  value='' />
        <br />Название<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br />Можно ли драться на ней?
        <br /><input type='radio' name='fight' value='yes'/> Да
        <br /><input type='radio' name='fight' value='no'/> Нет
        <br />Ширина<br /><input type='text' name='loc_x'  value='' />
        <br />Длина<br /><input type='text' name='loc_y'  value='' />
        <br />Освещенность
        <br /><input type='radio' name='light' value='temp'/> Взависимости от времени суток
        <br /><input type='radio' name='light' value='forever'/> Всегда светло
        <br /><input type='radio' name='light' value='never'/> Всегда темно
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
    } $page.="<br/><a href='./?do=admin&amp;mod=locations'>К списку локаций</a>"; }
 elseif (isset($_GET[zone])) {
    $count= mysql_result(mysql_query("SELECT COUNT(loc_id) FROM locations WHERE zone='$_GET[zone]'"),0,0);
    if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
    $num_page=intval($num_page);
    $temp=($num_page-1)*20;
    $sql=mysql_query("SELECT loc_id, loc_option FROM locations WHERE zone='$_GET[zone]' LIMIT ".$temp.", 20");
    $page.="<br/>[loc_id] - [name] <br/><br/>";
    while($locs = mysql_fetch_array($sql))
  		{  			$option=unserialize($locs[loc_option]);
    		$page.="[$locs[loc_id]] - [$option[name]]".
       		"<a href='./?do=admin&amp;mod=locations&amp;copy=$locs[loc_id]'>[copy]</a><a href='./?do=admin&amp;mod=locations&amp;redact=$locs[loc_id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=locations&amp;del=$locs[loc_id]'>[X]</a><br/>";
   		}
  	$page.=nav_page(intval(ceil($count/20)), $num_page, "./?do=admin&amp;mod=locations&amp;zone=$_GET[zone]&amp;str=");
    $page.="<br/><form action='./?do=admin&amp;mod=locations' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' value='Найти по ID' />
       </form>";
       $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;tmp=new'>Добавить локацию</a><br/>";
       $page.="<br/><a href='./?do=admin&amp;mod=locations'>К списку зон</a>";
 }
 else { 	$sql=mysql_query("SELECT DISTINCT zone FROM locations");
    $count= mysql_num_rows($sql);
    if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
    $num_page=intval($num_page);
    $temp=($num_page-1)*20;
    $page.="<br/>Зоны<br/><br/>";
    $i=0;
    for($i=0;$i<$count;$i++)
  		{   $zone = mysql_result($sql,$i);
    		$page.="<a href='./?do=admin&amp;mod=locations&amp;zone=$zone'>$zone</a><br/>";
   		}
  	$page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=locations&amp;str=");
    $page.="<br/><form action='./?do=admin&amp;mod=locations' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' value='Найти по ID' />
       </form>";
       $page.="<br/><a href='./?do=admin&amp;mod=locations&amp;tmp=new'>Добавить локацию</a><br/>";
       $page.="<br/><a href='./?do=admin&amp;mod=locations'>К списку зон</a>";
 }

?>