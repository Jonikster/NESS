<?  if ($player[rights]!="admin"){die($goawayfuckingcheater);}
 if (isset($_GET[del])){
 	 $sql=mysql_query("DELETE FROM monsters WHERE id='$_GET[del]' LIMIT 1");
 	 $page.="<br/>Монстр  с id $_GET[del] был успешно уничтожен<br/>";
 	 $page.="<br/><a href='./?do=admin&amp;mod=monst'>К списку монстров</a>";
 }
    if (isset($_GET[del])){
 	 	if (isset($_POST[confirm])){
 	 		$sql=mysql_query("DELETE FROM monsters WHERE id='$_GET[del]' LIMIT 1");
 	 		$page.="<br/>Монстр  с id $_GET[del] был успешно уничтожен<br/>";
 	 		$page.="<br/><a href='./?do=admin&amp;mod=monst'>К списку монстров</a>";
        }
        else {
 	 	$page.="<form action='./?do=admin&amp;mod=monst&amp;del=$_GET[del]' method='post'>
 	 	 <br/>Вы уверены?
         <br/><input type='submit' name='confirm' value='Да'>
		 </form>";}
 }
 elseif (isset($_POST[found])){
 	 	if (isset($_POST[id])) {
        $sql=mysql_query("SELECT id, name FROM monsters WHERE id='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Монстр с id $_POST[found] не найден<br/>";}
          else  {
          	$monsters=mysql_fetch_array($sql);
    			$page.="[$monsters[id]] - [$monsters[name]]".
       			"<a href='./?do=admin&amp;mod=monst&amp;redact=$monsters[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=monst&amp;del=$monsters[id]'>[X]</a><br/>";
   		  }
 	 	}
 	 	if (isset($_POST[name])) {
 	 	$sql=mysql_query("SELECT id, name FROM monsters WHERE name='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Монстр с именем $_POST[found] не найден<br/>";}
          else  {
          	$monsters=mysql_fetch_array($sql);
    			$page.="[$monsters[id]] - [$monsters[name]]".
       			"<a href='./?do=admin&amp;mod=monst&amp;redact=$monsters[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=monst&amp;del=$monsters[id]'>[X]</a><br/>";
   		  }
 	 	}
 	 	$page.="<br/><a href='./?do=admin&amp;mod=monst'>К списку монстров</a>";
 }
 elseif (isset($_GET[redact])) {
 	if 	(isset($_POST[name]))  {        $sql = mysql_query("UPDATE monsters SET name='$_POST[name]', info='$_POST[info]', maxhp='$_POST[maxhp]', maxod='$_POST[maxod]', medic='$_POST[medic]',crit_chance='$_POST[crit_chance]', type_dmg='$_POST[type_dmg]',damage='$_POST[damage]',maxpatrons='$_POST[maxpatrons]',bonusdex='$_POST[bonusdex]',resnormal='$_POST[resnormal]', resplazma='$_POST[resplazma]',resboom='$_POST[resboom]',resvolt='$_POST[resvolt]',on_die='$_POST[on_die]',timemod='$_POST[timemod]'  WHERE id='$_GET[redact]' LIMIT 1");
        $page.="<br/>Монстр  с id $_GET[redact] успешно изменен
        <br/><a href='./?do=admin&amp;mod=monst&amp;redact=$_GET[redact]'>Монстр $_GET[redact]</a><br/>";
 	}
 	else{      	$sql=mysql_query("SELECT * FROM monsters WHERE id='$_GET[redact]'");
 	 	$monster=mysql_fetch_array($sql);
 	 	$page.="<br/><form action='./?do=admin&amp;mod=monst&amp;redact=$_GET[redact]' method='post'>
        	<br />ID Монстра $monster[id]
        	<br />Имя<br /><input type='text' name='name'  value='$monster[name]' />
        	<br />Краткое описание<br/><textarea name='info' rows='3'  value=''>$monster[info]</textarea>
        	<br />Здоровье<br /><input type='text' name='maxhp'  value='$monster[maxhp]' />
        	<br />Очки действия<br /><input type='text' name='maxod'  value='$monster[maxod]' />
        	<br />Сколько раз может лечиться?<br /><input type='text' name='medic'  value='$monster[medic]' />
        	<br />Шанс на критический удар<br /><input type='text' name='crit_chance'  value='$monster[crit_chance]' />
        	<br />Тип урона
        	<br /><input type='radio' name='type_dmg' value='normal'";
            if ($monster[type_dmg]=="normal"){$page.=" checked='' ";}
        	$page.=" /> Нормальный
        	<br /><input type='radio' name='type_dmg' value='plazma'";
        	if ($monster[type_dmg]=="plazma"){$page.=" checked='' ";}
        	$page.=" /> Плазма
        	<br /><input type='radio' name='type_dmg' value='boom'";
        	if ($monster[type_dmg]=="boom"){$page.=" checked='' ";}
        	$page.="/> Взрыв
        	<br /><input type='radio' name='type_dmg' value='volt'";
            if ($monster[type_dmg]=="volt"){$page.=" checked='' ";}
        	$page.="/> электричество
        	<br />Урон<br /><input type='text' name='damage'  value='$monster[damage]' />
        	<br />Максимум патронов<br /><input type='text' name='maxpatrons'  value='$monster[maxpatrons]' />
        	<br />Шанс увернуться<br /><input type='text' name='bonusdex'  value='$monster[bonusdex]' />
        	<br />Сопротивление урону
        	<br />Нормальному<br /><input type='text' name='resnormal'  value='$monster[resnormal]' />
        	<br />Плазме<br /><input type='text' name='resplazma'  value='$monster[resplazma]' />
        	<br />Взрывам<br /><input type='text' name='resboom'  value='$monster[resboom]' />
        	<br />Электричеству<br /><input type='text' name='resvolt'  value='$monster[resvolt]' />
        	<br />При смерти<br/><textarea name='on_die' rows='3'  value=''>$monster[on_die]</textarea>
        	<br />Режим
        	<br /><input type='radio' name='timemod' value='forever'";
            if ($monster[timemod]=="forever"){$page.=" checked='' ";}
        	$page.="/> Всегда
        	<br /><input type='radio' name='timemod' value='night'";
            if ($monster["timemod"]=="night"){$page.=" checked='' ";}
        	$page.="/> Ночью
        	<br /><input type='radio' name='timemod' value='day'";
            if ($monster["timemod"]=="day"){$page.=" checked='' ";}
        	$page.="/> Днем
        	<br /><input type='submit' value='Изменить' /><br />
        	</form><br />";
    }
        	$page.="<br/><a href='./?do=admin&amp;mod=monst'>К списку монстров</a>";
 }
 elseif ($_GET[tmp]=="new")
 {
	if (isset($_POST[id])) {	 extract($_POST);     $sql=mysql_query("INSERT INTO monsters (id,name,info,maxhp,maxod,medic,crit_chance,type_dmg,damage,
               maxpatrons,bonusdex,resnormal,resplazma,resboom,resvolt,on_die,timemod)
         VALUES ('$id','$name','$info','$maxhp','$maxod','$medic','$crit_chance','$type_dmg','$damage',
               '$maxpatrons','$bonusdex','$resnormal','$resplazma','$resboom','$resvolt','$on_die','$timemod');");
    $page.=mysql_error()."<br/>Монстр $name добавлен<br/>";
	}
	else {        $page.="<br/><form action='./?do=admin&amp;mod=monst&amp;tmp=new' method='post'>
        <br />ID Монстра<br /><input type='text' name='id'  value='' />
        <br />Имя<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br />Здоровье<br /><input type='text' name='maxhp'  value='' />
        <br />Очки действия<br /><input type='text' name='maxod'  value='' />
        <br />Сколько раз может лечиться?<br /><input type='text' name='medic'  value='' />
        <br />Шанс на критический удар<br /><input type='text' name='crit_chance'  value='' />
        <br />Шанс увернуться<br /><input type='text' name='bonusdex'  value='' />
        <br />Тип урона
        <br /><input type='radio' name='type_dmg' value='normal'/> Нормальный
        <br /><input type='radio' name='type_dmg' value='plazma'/> Плазма
        <br /><input type='radio' name='type_dmg' value='boom'/> Взрыв
        <br /><input type='radio' name='type_dmg' value='volt'/> электричество
        <br />Урон<br /><input type='text' name='damage'  value='' />
        <br />Максимум патронов<br /><input type='text' name='maxpatrons'  value='' />
        <br />Сопротивление урону
        <br />Нормальному<br /><input type='text' name='resnormal'  value='' />
        <br />Плазме<br /><input type='text' name='resplazma'  value='' />
        <br />Взрывам<br /><input type='text' name='resboom'  value='' />
        <br />Электричеству<br /><input type='text' name='resvolt'  value='' />
        <br />При смерти<br/><textarea name='on_die' rows='3'  value=''></textarea>
        <br />Режим
        <br /><input type='radio' name='timemod' value='forever'/> Всегда
        <br /><input type='radio' name='timemod' value='night'/> Ночью
        <br /><input type='radio' name='timemod' value='day'/> Днем
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
    }
    $page.="<br/><a href='./?do=admin&amp;mod=monst'>К списку монстров</a>"; }
 else {$count= mysql_result(mysql_query("SELECT COUNT(id) FROM monsters"),0,0);
    if ($count<=10) {
      	 $sql=mysql_query("SELECT id,name FROM  monsters LIMIT 10");

         $page.="<br/>[id] - [name] <br/><br/>";
          while($monsters = mysql_fetch_array($sql))
  			{
    			$page.="[$monsters[id]] - [$monsters[name]]".
       			 "<a href='./?do=admin&amp;mod=monst&amp;redact=$monsters[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=monst&amp;del=$monsters[id]'>[X]</a><br/>";
   			}



     }
    else {
      		if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
      		$num_page=intval($num_page);

      		$temp=($num_page-1)*10;
      		if ($temp>$count) {$page.="<br/>Столько монстров не существует!!<br/>";}
            else {
            	$sql=mysql_query("SELECT id, name FROM monsters LIMIT ".$temp.", 10");
            	$page.="<br/>[id] - [name]<br/><br/>";
            	while($monsters = mysql_fetch_array($sql))
  			 	{

    				$page.="[$monsters[id]] - [$monsters[name]]".
       			 "<a href='./?do=admin&amp;mod=monst&amp;redact=$monsters[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=monst&amp;del=$monsters[id]'>[X]</a><br/>";
   			}
  			 	$page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=monst&amp;str=");
               }

    }
       $page.="<br/><form action='./?do=admin&amp;mod=monst' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' name='id' value='Найти по ID' />
       <input type='submit' name='name' value='Найти по имени' /><br />
       </form>";
    $page.="<br/><a href='./?do=admin&amp;mod=monst&amp;tmp=new'>Добавить монстра</a><br/>";
 }
?>