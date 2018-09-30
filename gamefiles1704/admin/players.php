<?  if ($player[rights]!="admin"){die($goawayfuckingcheater);}
 	 if (isset($_GET[del])){ 	 	if (isset($_POST[confirm])){
 	 		$sql=mysql_query("DELETE FROM users WHERE id=".$_GET[del]);
 	 		$page.="<br/>Пользователь с id ".$_GET[del]." был успешно уничтожен. Больше он вас беспокоить не будет!)))<br/>";        }
        else {
 	 	$page.="<form action='./?do=admin&amp;mod=players&amp;del=$_GET[del]' method='post'>
 	 	 <br/>Вы уверены?
         <br/><input type='submit' name='confirm' value='Да'>
		 </form>";}
 	 }
 	 elseif (isset($_POST[found])){ 	 	if (isset($_POST[id])) {        $sql=mysql_query("SELECT id, login, char_name, level FROM users WHERE id='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Пользователь с id $_POST[found] не найден<br/>";}
          else  {          	$users=mysql_fetch_array($sql);
            $page.="<br/>[".$users[id]."] - [".$users[login]."] - [".$users[char_name]."] - [".$users[level]."]  ".
       			 	"<a href='./?do=admin&amp;mod=players&amp;redact=".$users[id]."'>[ИЗМ]</a><a href='./?do=admin&amp;mod=players&amp;del=".$users[id]."'>[X]</a><br/>";
          	} 	 	}
 	 	if (isset($_POST[name])) { 	 	$sql=mysql_query("SELECT id, login, char_name, level FROM users WHERE char_name='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Пользователь с именем $_POST[found] не найден<br/>";}
          else  {
          	$users=mysql_fetch_array($sql);
            $page.="<br/>[".$users[id]."] - [".$users[login]."] - [".$users[char_name]."] - [".$users[level]."]  ".
       			 	"<a href='./?do=admin&amp;mod=players&amp;redact=".$users[id]."'>[ИЗМ]</a><a href='./?do=admin&amp;mod=players&amp;del=".$users[id]."'>[X]</a><br/>";
          	} 	 	}
 	 	$page.="<br/><a href='./?do=admin&amp;mod=players'>К списку игроков</a>"; 	 }
 	 elseif (isset($_GET[redact])){ 	 	if (isset($_POST[str])){    		$base_params = array("str" => $_POST[str],"life" => $_POST[life],"endur" => $_POST[endur],"speed" => $_POST[speed],"dex" => $_POST[dex],"luck" => $_POST[luck],"shooting" => $_POST[shooting], "int" => $_POST[int]);
    		$base_params = serialize($base_params);        	$sql = mysql_query("UPDATE users SET base_params='$base_params' WHERE id='$_GET[redact]' LIMIT 1");
            $page.="<br/>Базовые параметры игрока с id $_GET[redact] успешно изменены
            <br/><a href='./?do=admin&amp;mod=players&amp;redact=$_GET[redact]&amp;rdmod=bpar'>Базовые параметры </a>"; 	 	}
 	 	elseif (isset($_POST[login])){ 	 		extract($_POST);            $sql = mysql_query("UPDATE users SET login='$login', char_name='$char_name', email='$email', level='$level', exp='$exp', needexp='$needexp', study_points='$study_points', maxhp='$maxhp', rad_points='$rad_points', poison_points='$poison_points', hungry_points='$hungry_points', maxod='$maxod', money='$money', loc_id='$loc_id', rights='$rights' WHERE id='$_GET[redact]' LIMIT 1");
            $page.="<br/>Характеристики игрока с id $_GET[redact] успешно изменены<br/>"; 	 	}
        elseif (isset($_POST[trade])){        	$sql=mysql_query("SELECT skills FROM users WHERE id='$_GET[redact]'");
        	$user=mysql_fetch_array($sql);
 	 		$skills=unserialize($user[skills]);
 	 		$skills[trade][level]=$_POST[trade];
            $skills[hack][level]=$_POST[hack];
            $skills[per][level]=$_POST[per];
            $skills[weap][level]=$_POST[weap];
            $skills[armer][level]=$_POST[armer];
            $skills[chim][level]=$_POST[chim];
            $skills=serialize($skills);
            $sql = mysql_query("UPDATE users SET skills='$skills' WHERE id='$_GET[redact]' LIMIT 1");
            $page.="<br/>Навыки игрока с id $_GET[redact] успешно изменены
            <br/><a href='./?do=admin&amp;mod=players&amp;redact=$_GET[redact]&amp;rdmod=skills'>Навыки</a>";
        }
        elseif (isset($_POST[trauma])){    		$trauma = array("lefthand"=> $_POST[lefthand], "righthand"=> $_POST[righthand],"leftleg"=> $_POST[leftleg], "rightleg"=> $_POST[rightleg],"eye"=> $_POST[eye]);
    		$trauma = serialize($trauma);
        	$sql = mysql_query("UPDATE users SET trauma='$trauma' WHERE id='$_GET[redact]' LIMIT 1");
            $page.="$_POST[lefthand]<br/>Травмы игрока с id $_GET[redact] успешно изменены
            <br/><a href='./?do=admin&amp;mod=players&amp;redact=$_GET[redact]&amp;rdmod=trauma'>Травмы </a>";
        }
        elseif (isset($_POST[resnormal])){    		$base_resists= array("resnormal"=> $_POST[resnormal],"resplazma"=> $_POST[resplazma],"resboom"=> $_POST[resboom],"resvolt"=> $_POST[resvolt],"respoison"=> $_POST[respoison],"resrad"=> $_POST[resrad]);
    		$base_resists = serialize($base_resists);
        	$sql = mysql_query("UPDATE users SET base_resists='$base_resists' WHERE id='$_GET[redact]' LIMIT 1");
            $page.="<br/>Базовые сопротивления игрока с id $_GET[redact] успешно изменены
            <br/><a href='./?do=admin&amp;mod=players&amp;redact=$_GET[redact]&amp;rdmod=res'>Базовые сопротивления </a>";
        }
 	 	elseif ($_GET[rdmod]=="bpar") { 	 		$sql=mysql_query("SELECT base_params,id,login,char_name FROM users WHERE id='$_GET[redact]'");
 	 		$user=mysql_fetch_array($sql);
 	 		$user[base_params]=unserialize($user[base_params]); 	 		$page.="<br/>id: $user[id]
 	 		<br/>Login: $user[login]
 	 		<br/>Имя персонажа: $user[char_name]
 	 		<br/><b>Базовые параметры</b>
 	 		<form action='./?do=admin&amp;mod=players&amp;redact=".$user[id]."' method='post'>
     		<br/> Сила <input type='text' name='str'  value='".$user[base_params][str]."' />
            <br/> Живучесть <input type='text' name='life' value='".$user[base_params][life]."' />
            <br/> Выносливость <input type='text' name='endur' value='".$user[base_params][endur]."' />
            <br/> Скорость <input type='text' name='speed' value='".$user[base_params][speed]."' />
            <br/> Ловкость <input type='text' name='dex' value='".$user[base_params][dex]."' />
            <br/> Удача <input type='text' name='luck' value='".$user[base_params][luck]."' />
            <br/> Меткость <input type='text' name='shooting' value='".$user[base_params][shooting]."' />
            <br/> Интеллект <input type='text' name='int' value='".$user[base_params][int]."' />
 	 		<br /><input type='submit' value='Изменить' /><br />
        	</form>"; 	 	}
        elseif ($_GET[rdmod]=="skills") {        	$sql=mysql_query("SELECT skills,id,login,char_name FROM users WHERE id='$_GET[redact]'");
 	 		$user=mysql_fetch_array($sql);
 	 		$skills=unserialize($user[skills]);
 	 		$page.="<br/>id: $user[id]
 	 		<br/>Login: $user[login]
 	 		<br/>Имя персонажа: $user[char_name]
 	 		<br/><b>Навыки</b>
            <form action='./?do=admin&amp;mod=players&amp;redact=".$user[id]."' method='post'>
            <br/> Торговля <input type='text' name='trade' size='4' value='".$skills[trade][level]."' />
            <br/> Взлом <input type='text' name='hack' size='4' value='".$skills[hack][level]."' />
            <br/> Наблюдательность <input type='text' size='4' name='per' value='".$skills[per][level]."' />
            <br/> Оружейник <input type='text' name='weap' size='4' value='".$skills[weap][level]."' />
            <br/> Портной <input type='text' name='armer' size='4' value='".$skills[armer][level]."' />
            <br/> Химик <input type='text' name='chim' size='4' value='".$skills[chim][level]."' />
            <br /><input type='submit' value='Изменить' /><br />
            </form>";        }
        elseif ($_GET[rdmod]=="res") {       		$sql=mysql_query("SELECT base_resists,id,login,char_name FROM users WHERE id='$_GET[redact]'");
 	 		$user=mysql_fetch_array($sql);
 	 		$base_resist=unserialize($user[base_resists]);
 	 		$page.="<br/>id: $user[id]
 	 		<br/>Login: $user[login]
 	 		<br/>Имя персонажа: $user[char_name]
 	 		<br/><br/><b>Базовые сопротивления</b>
            <form action='./?do=admin&amp;mod=players&amp;redact=".$user[id]."' method='post'>
            <br/>Сопротивление нормальному урону  <input type='text' name='resnormal' value='$base_resist[resnormal]' />
            <br/>Сопротивление плазменному урону  <input type='text' name='resplazma' value='$base_resist[resplazma]' />
            <br/>Сопротивление взрывному урону  <input type='text' name='resboom' value='$base_resist[resboom]' />
            <br/>Сопротивление электричеству  <input type='text' name='resvolt' value='$base_resist[resvolt]' />
            <br/>Сопротивление яду  <input type='text' name='respoison' value='$base_resist[respoison]' />
            <br/>Сопротивление радиации  <input type='text' name='resrad' value='$base_resist[resrad]' />

            <br /><input type='submit' value='Изменить' /><br />
            </form>";
        }
        elseif ($_GET[rdmod]=="inv") {
            $sql=mysql_query("SELECT id,bag,weapon1,weapon2,bodyarm,gruz  FROM users WHERE id='$_GET[redact]'");
 	 		$user=mysql_fetch_array($sql);
            $bag=unserialize($user[bag]);
            $weapon1=unserialize($user[weapon1]);
            $weapon2=unserialize($user[weapon2]);
            $bodyarm=unserialize($user[bodyarm]);
            if (isset($_POST[itemid])) {             $return=add_to_inv ($_POST[itemid],$_POST[colvo],$bag,$user[id],$user[gruz]);
             $bag=$return[bag];
             $user[gruz]=$return[gruz];
             $tmp=serialize($bag);
             $sql=mysql_query("UPDATE users SET bag='$tmp',gruz='$user[gruz]' WHERE id='$user[id]' LIMIT 1");
             $page.=$return[page]."<br/>Предмет с ID $_POST[itemid] добавлен в количестве $_POST[colvo]
             <br/><a href='./?do=admin&amp;mod=players&amp;redact=$user[id]&amp;rdmod=inv'>В инвентарь</a>";            }
            else {
         		if (isset($_GET[delitem])) {                   $page.="<br/>".$bag[$_GET[delitem]][name]." уничтожено<br/>";
                   $user[gruz]=$user[gruz]-$bag[$_GET[delitem]][about_item][massa]*$bag[$_GET[delitem]][colvo];
                   $bag=delete_element($bag,$_GET[delitem]);
                   $user[bag]=serialize($bag);
                   $sql=mysql_query("UPDATE users SET bag='$user[bag]',gruz='$user[gruz]' WHERE id='$user[id]' LIMIT 1");            	}
            	$page.="<br/>Оружие 1: $weapon1[name]";
            	$page.="<br/>Оружие 2: $weapon2[name]";
            	$page.="<br/>Броня: $bodyarm[name]<br/>";
            	for ($i=0;$i<sizeof($bag);$i++){             		$page.="<br/>".$bag[$i][name];
     		 		if ($bag[$i][colvo]>1) {$page.="[".$bag[$i][colvo]."]";}
     		 		$page.="<a href='./?do=admin&amp;mod=players&amp;redact=$user[id]&amp;rdmod=inv&amp;delitem=$i'>[X]</a>";            	}
                $page.="<form action='./?do=admin&amp;mod=players&amp;redact=$user[id]&amp;rdmod=inv' method='post'>
                <br/>ID предмета<input type='text' name='itemid' value='' />
                <br/>Количество<input type='text' name='colvo' value='1' />
                <br /><input type='submit' value='Добавить' /><br />
            	</form>";
            }
           $page.="<br/><a href='./?do=admin&amp;mod=players&amp;redact=$user[id]'>К игроку</a><br/>";        }
        elseif ($_GET[rdmod]=="bank") {
            $sql=mysql_query("SELECT id,bank  FROM users WHERE id='$_GET[redact]'");
 	 		$user=mysql_fetch_array($sql);
            $bank=unserialize($user[bank]);
            if (isset($_POST[itemid])) {
             $return=add_to_inv ($_POST[itemid],$_POST[colvo],$bank,$user[id],$tmp);
             $bank=$return[bag];
             $tmp=serialize($bank);
             $sql=mysql_query("UPDATE users SET bank='$tmp' WHERE id='$user[id]' LIMIT 1");
             $page.=$return[page]."<br/>Предмет с ID $_POST[itemid] добавлен в количестве $_POST[colvo]
             <br/><a href='./?do=admin&amp;mod=players&amp;redact=$user[id]&amp;rdmod=bank'>В банк</a>";
            }
            else {
         		if (isset($_GET[delitem])) {
                   $page.="<br/>".$bank[$_GET[delitem]][name]." уничтожено<br/>";
                   $bank=delete_element($bank,$_GET[delitem]);
                   $user[bank]=serialize($bank);
                   $sql=mysql_query("UPDATE users SET bag='$user[bank]' WHERE id='$user[id]' LIMIT 1");
            	}
            	for ($i=0;$i<sizeof($bank);$i++){
             		$page.="<br/>".$bank[$i][name];
     		 		if ($bank[$i][colvo]>1) {$page.="[".$bank[$i][colvo]."]";}
     		 		$page.="<a href='./?do=admin&amp;mod=players&amp;redact=$user[id]&amp;rdmod=bank&amp;delitem=$i'>[X]</a>";
            	}
                $page.="<form action='./?do=admin&amp;mod=players&amp;redact=$user[id]&amp;rdmod=bank' method='post'>
                <br/>ID предмета<input type='text' name='itemid' value='' />
                <br/>Количество<input type='text' name='colvo' value='1' />
                <br /><input type='submit' value='Добавить' /><br />
            	</form>";
            }
           $page.="<br/><a href='./?do=admin&amp;mod=players&amp;redact=$user[id]'>К игроку</a><br/>";
        }
        elseif ($_GET[rdmod]=="trauma") {
        	$sql=mysql_query("SELECT trauma,id,login,char_name FROM users WHERE id='$_GET[redact]'");
 	 		$user=mysql_fetch_array($sql);
 	 		$user[trauma]=unserialize($user[trauma]);
 	 		$page.="<br/>id: $user[id]
 	 		<br/>Login: $user[login]
 	 		<br/>Имя персонажа: $user[char_name]
 	 		<br/><b>Травмы</b>
            <form action='./?do=admin&amp;mod=players&amp;redact=".$user[id]."' method='post'>
            <input type='hidden' name='trauma' value='1' />
            <br/><input name='lefthand' type='checkbox' value='on' ";
            if ($user[trauma][lefthand] == "on") {$page.="checked='on' ";}
            $page.=" />Сломана левая рука
            <br/><input name='righthand'  type='checkbox' value='on' ";
            if ($user[trauma][righthand] == "on") {$page.="checked='on' ";}
            $page.="/>Сломана правая рука
            <br/><input name='leftleg' type='checkbox'  value='on' ";
            if ($user[trauma][leftleg] == "on") {$page.="checked='on' ";}
            $page.="/> Сломана левая нога
            <br/><input name='rightleg' type='checkbox' value='on' ";
            if ($user[trauma][rightleg] == "on") {$page.="checked='on' ";}
            $page.="/>Сломана правая нога
            <br/><input name='eye' type='checkbox' value='on' ";
            if ( $user[trauma][eye] == "on") {$page.="checked='on' ";}
            $page.="/>     Поврежден глаз
            <br /><input type='submit' value='Изменить' /><br />
            </form>";}
 	 	else { 	 	$sql=mysql_query("SELECT * FROM users WHERE id='$_GET[redact]'");
 	 	$user=mysql_fetch_array($sql);
 	 	  if (isset($_POST[pass]))
 	 	  { $user[pass]= md5($_POST[pass]); 	 	   $sql=mysql_query("UPDATE users SET pass='$user[pass]' WHERE id='$user[id]' LIMIT 1");
 	 	   $page.="<br/>Новый  пароль $_POST[pass]";
 	 	  }
 	 	    $page.="<br/>id: $user[id]
            <br/>MD5 PAROL: $user[pass] <a href='./?do=admin&amp;mod=players&amp;redact=$_GET[redact]&amp;pass=change'> [Изм] </a>";
 	 	  if (isset($_GET[pass])) {
 	 	       $page.=" <form action='./?do=admin&amp;mod=players&amp;redact=".$user[id]."' method='post'>
 	 	       <input type='text' name='pass' value='' />
 	 	       <br/><input type='submit' value='Изменить' /><br />
        		</form>"; 	 	  }
        $page.=" <form action='./?do=admin&amp;mod=players&amp;redact=".$user[id]."' method='post'>
        	<br/>Login: <input type='text' name='login' maxlength='30' value='$user[login]' />
            <br/>Имя персонажа:  <input type='text' name='char_name' maxlength='15' value='$user[char_name]' />
        	<br/>Email:  <input type='text' name='email' value='$user[email]' />
        	<br/>Уровень <input type='text' name='level' value='$user[level]' />
        	<br/>Текущий опыт  <input type='text' name='exp' value='$user[exp]' />
        	<br/>Опыт до нового уровня <input type='text' name='needexp' value='$user[needexp]' />
        	<br/>Очки обучения  <input type='text' name='study_points' value='$user[study_points]' />
            <br/>Здоровье <input type='text' name='maxhp' value='$user[maxhp]' />
            <br/>Радзаражение <input type='text' name='rad_points' value='$user[rad_points]' />
            <br/>Отравление <input type='text' name='poison_points' value='$user[poison_points]' />
            <br/>Голод <input type='text' name='hungry_points' value='$user[hungry_points]' />
            <br/>Очки действия <input type='text' name='maxod' value='$user[maxod]' />

            <br/>Деньги <input type='text' name='money' value='$user[money]' />
            <br/>Локация <input type='text' name='loc_id' value='$user[loc_id]' />
            <br/>Права <input type='text' name='rights' value='$user[rights]' />
            <br/><input type='submit' value='Изменить' /><br />
        	</form>
        	<br/><a href='./?do=admin&amp;mod=players&amp;redact=".$user[id]."&amp;rdmod=bpar'>Базовые параметры </a>
			<br/><a href='./?do=admin&amp;mod=players&amp;redact=".$user[id]."&amp;rdmod=skills'>Навыки</a>
        	<br/><a href='./?do=admin&amp;mod=players&amp;redact=".$user[id]."&amp;rdmod=inv'>Инвентарь</a>
        	<br/><a href='./?do=admin&amp;mod=players&amp;redact=".$user[id]."&amp;rdmod=bank'>банк</a>
            <br/><a href='./?do=admin&amp;mod=players&amp;redact=".$user[id]."&amp;rdmod=res'>Базовые сопротивления </a>
            <br/><a href='./?do=admin&amp;mod=players&amp;redact=".$user[id]."&amp;rdmod=eff'> Эффекты </a>
            <br/><a href='./?do=admin&amp;mod=players&amp;redact=".$user[id]."&amp;rdmod=trauma'>Травмы</a><br/>

			";
        }  $page.="<br/><a href='./?do=admin&amp;mod=players'>К списку игроков</a>"; 	 }
 	 elseif (isset($_GET[rights])) { 	 	$page.="<br/><b>$_GET[rights]</b>";
 	    $count= mysql_result(mysql_query("SELECT COUNT(id) FROM users WHERE rights='$_GET[rights]'"),0,0); 	 	if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
      		$num_page=intval($num_page);
      		$temp=($num_page-1)*10;
      		$sql=mysql_query("SELECT id, login, char_name, level FROM users WHERE rights='$_GET[rights]' LIMIT ".$temp.", 10");
            $page.="<br/>[id] - [login] - [char_name] - [level]<br/><br/>";
            while($users = mysql_fetch_array($sql))
  			 {
    			$page.="[".$users[id]."] - [".$users[login]."] - [".$users[char_name]."] - [".$users[level]."]  ".
       			 "<a href='./?do=admin&amp;mod=players&amp;unset=".$users[id]."'>[СНЯТЬ]</a><a href='./?do=admin&amp;mod=players&amp;redact=".$users[id]."'>[ИЗМ]</a><a href='./?do=admin&amp;mod=players&amp;del=".$users[id]."'>[X]</a><br/>";
  			 }
  			 $page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=players&amp;rights=$_GET[rights]&amp;str=");
 	 }
 	 else {
 	  if (isset($_GET["unset"])) {
            $sql=mysql_query("UPDATE users SET rights='user'  WHERE id='$_GET[unset]' LIMIT 1");
            $page.="<br/>Игрок c id $_GET[unset] теперь обычный пользователь<br/>";
 	 	}
 	  $count= mysql_result(mysql_query("SELECT COUNT(id) FROM users"),0,0);

       if ($count<=10) {
      	 $sql=mysql_query("SELECT id, login, char_name, level FROM users LIMIT 10");

         $page.="<br/>[id] - [login] - [char_name] - [level]<br/><br/>";
          while($users = mysql_fetch_array($sql))
  			{
    			$page.="[".$users[id]."] - [".$users[login]."] - [".$users[char_name]."] - [".$users[level]."]  ".
       			 "<a href='./?do=admin&amp;mod=players&amp;redact=".$users[id]."'>[ИЗМ]</a><a href='./?do=admin&amp;mod=players&amp;del=".$users[id]."'>[X]</a><br/>";

  			}



       }else
       {
      		if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
      		$num_page=intval($num_page);

      		$temp=($num_page-1)*10;
      		if ($temp>$count) {$page.="<br/>Столько пользователей не существует!!<br/>";}
            else {
            	$sql=mysql_query("SELECT id, login, char_name, level FROM users LIMIT ".$temp.", 10");
            	$page.="<br/>[id] - [login] - [char_name] - [level]<br/><br/>";
            	while($users = mysql_fetch_array($sql))
  			 	{
    				$page.="[".$users[id]."] - [".$users[login]."] - [".$users[char_name]."] - [".$users[level]."]  ".
       			 	"<a href='./?do=admin&amp;mod=players&amp;redact=".$users[id]."'>[ИЗМ]</a><a href='./?do=admin&amp;mod=players&amp;del=".$users[id]."'>[X]</a><br/>";
  			 	}
  			 	$page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=players&amp;str=");
               }

       }
       $page.="<br/><form action='./?do=admin&amp;mod=players' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' name='id' value='Найти по ID' />
       <input type='submit' name='name' value='Найти по имени' /><br />
       </form>";
       $page.="<a href='./?do=admin&amp;mod=players&amp;rights=moder'>Список модераторов</a>";
       $page.="<br /><a href='./?do=admin&amp;mod=players&amp;rights=banned'>Список забанненых</a>";
       $page.="<br /><a href='./?do=admin&amp;mod=players&amp;rights=blocked'>Список заблоченных</a><br />";
     }

?>