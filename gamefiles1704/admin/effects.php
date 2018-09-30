<?   if ($player[rights]!="admin"){die("$goawayfuckingcheater");}
 if (isset($_GET[del])){
 	 $sql=mysql_query("DELETE FROM effects WHERE effid='$_GET[del]' LIMIT 1");
 	 $page.="<br/>Эффект  с id $_GET[del] был успешно уничтожен<br/>";
 	 $page.="<br/><a href='./?do=admin&amp;mod=eff'>К списку эффектов</a>";
 }
 elseif (isset($_POST[found])){
 	 	if (isset($_POST[id])) {
        $sql=mysql_query("SELECT effid, name FROM effects WHERE effid='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Эффект с id $_POST[found] не найден<br/>";}
          else  {
          	$eff=mysql_fetch_array($sql);
    			$page.="[$eff[effid]] - [$eff[effname]]".
       			"<a href='./?do=admin&amp;mod=eff&amp;redact=$eff[effid]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=eff&amp;del=$eff[effid]'>[X]</a><br/>";
   		  }
 	 	}
 	 	if (isset($_POST[name])) {
 	 	$sql=mysql_query("SELECT effid, name FROM effects WHERE name='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Эффект  $_POST[found] не найден<br/>";}
          else  {
          	$monsters=mysql_fetch_array($sql);
    			$page.="[$eff[effid]] - [$eff[name]]".
       			"<a href='./?do=admin&amp;mod=eff&amp;redact=$eff[effid]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=eff&amp;del=$eff[effid]'>[X]</a><br/>";
   		  }
 	 	}
 	 	$page.="<br/><a href='./?do=admin&amp;mod=eff'>К списку эффектов</a>";
 }
 elseif (isset($_GET[redact])) {
 	if 	(isset($_POST[name]))  {
 		if (!empty($_POST[resnormal]) and $_POST[resnormal]!=0) {$res[resnormal]=$_POST[resnormal];}
     	if (!empty($_POST[resplazma]) and $_POST[resplazma]!=0) {$res[resplazma]=$_POST[resplazma];}
     	if (!empty($_POST[resboom]) and $_POST[resboom]!=0) {$res[resboom]=$_POST[resboom];}
     	if (!empty($_POST[resvolt]) and $_POST[resvolt]!=0) {$res[resvolt]=$_POST[resvolt];}
     	if (!empty($_POST[resrad]) and $_POST[resrad]!=0) {$res[resrad]=$_POST[resrad];}
     	if (!empty($_POST[respoison]) and $_POST[respoison]!=0) {$res[respoison]=$_POST[respoison];}

     	if (isset($res)) {$res=serialize($res);}

     	if (!empty($_POST[str]) and $_POST[str]!=0) {$param[str]=$_POST[str];}
     	if (!empty($_POST[endur]) and $_POST[endur]!=0) {$param[endur]=$_POST[endur];}
     	if (!empty($_POST[life]) and $_POST[life]!=0) {$param[life]=$_POST[life];}
     	if (!empty($_POST[speed]) and $_POST[speed]!=0) {$param[speed]=$_POST[speed];}
     	if (!empty($_POST[dex]) and $_POST[dex]!=0) {$param[dex]=$_POST[dex];}
     	if (!empty($_POST[shooting]) and $_POST[shooting]!=0) {$param[shooting]=$_POST[shooting];}
     	if (!empty($_POST[luck]) and $_POST[luck]!=0) {$param[luck]=$_POST[luck];}
     	if (!empty($_POST[int]) and $_POST[int]!=0) {$param[int]=$_POST[int];}

        if (isset($param)) {$param=serialize($param);}
        $sql = mysql_query("UPDATE effects SET name='$_POST[name]', info='$_POST[info]',".
        "resists='$res', params='$param',noeff='$_POST[noeff]',badeff='$_POST[badeff]',chance='$_POST[chance]',end_time='$_POST[end_time]'  WHERE effid='$_GET[redact]' LIMIT 1");
        $page.="<br/>Эффект  $_POST[name] успешно изменен<br/>";
        $page.="<br/><a href='./?do=admin&amp;mod=eff&amp;redact=$_GET[redact]'>К списку эффектy</a>";
        $page.="<br/><a href='./?do=admin&amp;mod=eff'>К списку эффектов</a>";
 	}
 	else{
      	$sql=mysql_query("SELECT * FROM effects WHERE effid='$_GET[redact]'");
 	 	$eff=mysql_fetch_array($sql);
 	 	$res=unserialize($eff[resists]);
 	 	$params=unserialize($eff[params]);
 	 	$page.="<br/><form action='./?do=admin&amp;mod=eff&amp;redact=$_GET[redact]' method='post'>
        <br />ID Эффекта $eff[effid]
        <br />Название<br /><input type='text' name='name'  value='$eff[name]' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''>$eff[info]</textarea>
        <br /><b>Сопротивления</b>
        <br />Нормальному<br/><input type='text' name='resnormal'  value='$res[resnormal]' />
        <br />Плазме<br/><input type='text' name='resplazma'  value='$res[resplazma]' />
        <br />Взрывам<br/><input type='text' name='resboom'  value='$res[resboom]' />
        <br />Электричеству<br/><input type='text' name='resvolt'  value='$res[resvolt]' />
        <br />Отравлению<br/><input type='text' name='respoison'  value='$res[respoison]' />
        <br />Радиации<br/><input type='text' name='resrad'  value='$res[resrad]' />
        <br /><b>Параметры</b>
        <br/> Сила <input type='text' name='str'  value='$params[str]' />
        <br/> Живучесть <input type='text' name='life' value='$params[life]' />
        <br/> Выносливость <input type='text' name='endur' value='$params[endur]' />
        <br/> Скорость <input type='text' name='speed' value='$params[speed]' />
        <br/> Ловкость <input type='text' name='dex' value='$params[dex]' />
        <br/> Удача <input type='text' name='luck' value='$params[luck]' />
        <br/> Меткость <input type='text' name='shooting' value='$params[shooting]' />
        <br/> Интеллект <input type='text' name='int' value='$params[int]' />
        <br/> Снимает эффект <input type='text' name='noeff' value='$eff[noeff]' />
        <br/> Побочный эффект <input type='text' name='badeff' value='$eff[badeff]' />
        <br/> Шанс на него <input type='text' name='chance' value='$eff[chance]' />
        <br/> Время действия побочного эффекта <input type='text' name='end_time' value='$eff[end_time]' />
        <br /><input type='submit' value='Изменить' /><br />
        </form><br />";
    }
        	$page.="<br/><a href='./?do=admin&amp;mod=monst'>К списку монстров</a>";
 }
 elseif ($_GET[tmp]=="new")
 {
	if (isset($_POST[effid])) {
     if (!empty($_POST[resnormal]) and $_POST[resnormal]!=0) {$res[resnormal]=$_POST[resnormal];}
     if (!empty($_POST[resplazma]) and $_POST[resplazma]!=0) {$res[resplazma]=$_POST[resplazma];}
     if (!empty($_POST[resboom]) and $_POST[resboom]!=0) {$res[resboom]=$_POST[resboom];}
     if (!empty($_POST[resvolt]) and $_POST[resvolt]!=0) {$res[resvolt]=$_POST[resvolt];}
     if (!empty($_POST[resrad]) and $_POST[resrad]!=0) {$res[resrad]=$_POST[resrad];}
     if (!empty($_POST[respoison]) and $_POST[respoison]!=0) {$res[respoison]=$_POST[respoison];}

     if (isset($res)) {$res=serialize($res);}
     else {$res="";}

     if (!empty($_POST[str]) and $_POST[str]!=0) {$param[str]=$_POST[str];}
     if (!empty($_POST[endur]) and $_POST[endur]!=0) {$param[endur]=$_POST[endur];}
     if (!empty($_POST[life]) and $_POST[life]!=0) {$param[life]=$_POST[life];}
     if (!empty($_POST[speed]) and $_POST[speed]!=0) {$param[speed]=$_POST[speed];}
     if (!empty($_POST[dex]) and $_POST[dex]!=0) {$param[dex]=$_POST[dex];}
     if (!empty($_POST[shooting]) and $_POST[shooting]!=0) {$param[shooting]=$_POST[shooting];}
     if (!empty($_POST[luck]) and $_POST[luck]!=0) {$param[luck]=$_POST[luck];}
     if (!empty($_POST[int]) and $_POST[int]!=0) {$param[int]=$_POST[int];}

     if (isset($param)) {$param=serialize($param);}
     else {$param="";}

     $sql=mysql_query("INSERT INTO effects (effid,name,info,resists,params,noeff,badeff,chance,end_time) VALUES ('$_POST[effid]','$_POST[name]','$_POST[info]','$res','$param','$_POST[noeff]','$_POST[badeff]','$_POST[chance]','$_POST[end_time]');");
    $page.="<br/>Эффект $_POST[name] добавлен<br/>";

	}
	else {
        $page.="<br/><form action='./?do=admin&amp;mod=eff&amp;tmp=new' method='post'>
        <br />ID эффекта<br /><input type='text' name='effid'  value='' />
        <br />Название<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br /><b>Сопротивления</b>
        <br />Нормальному<br/><input type='text' name='resnormal'  value='' />
        <br />Плазме<br/><input type='text' name='resplazma'  value='' />
        <br />Взрывам<br/><input type='text' name='resboom'  value='' />
        <br />Электричеству<br/><input type='text' name='resvolt'  value='' />
        <br />Отравлению<br/><input type='text' name='respoison'  value='' />
        <br />Радиации<br/><input type='text' name='resrad'  value='' />
        <br /><b>Параметры</b>
        <br/> Сила <input type='text' name='str'  value='' />
        <br/> Живучесть <input type='text' name='life' value='' />
        <br/> Выносливость <input type='text' name='endur' value='' />
        <br/> Скорость <input type='text' name='speed' value='' />
        <br/> Ловкость <input type='text' name='dex' value='' />
        <br/> Удача <input type='text' name='luck' value='' />
        <br/> Меткость <input type='text' name='shooting' value='' />
        <br/> Интеллект <input type='text' name='int' value='' />
        <br/> Снимает эффект <input type='text' name='noeff' value='' />
        <br/> Побочный эффект <input type='text' name='badeff' value='' />
        <br/> Шанс на него <input type='text' name='chance' value='' />
        <br/> Время действия побочного эффекта <input type='text' name='end_time' value='' />
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
    }
    $page.="<br/><a href='./?do=admin&amp;mod=eff'>К списку эффектов</a>";
 }
 else {
    $count= mysql_result(mysql_query("SELECT COUNT(effid) FROM effects"),0,0);
    if ($count<=10) {
      	 $sql=mysql_query("SELECT effid,name FROM  effects LIMIT 10");

         $page.="<br/>[id] - [name] <br/><br/>";
          while($eff = mysql_fetch_array($sql))
  			{
    			$page.="[$eff[effid]] - [$eff[name]]".
       			 "<a href='./?do=admin&amp;mod=eff&amp;redact=$eff[effid]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=eff&amp;del=$eff[effid]'>[X]</a><br/>";
   			}



     }
    else {
      		if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
      		$num_page=intval($num_page);

      		$temp=($num_page-1)*10;
      		if ($temp>$count) {$page.="<br/>Столько не существует!!<br/>";}
            else {
            	$sql=mysql_query("SELECT effid, name FROM effects LIMIT ".$temp.", 10");
            	$page.="<br/>[effid] - [name]<br/><br/>";
            	while($eff = mysql_fetch_array($sql))
  			 	{

    				$page.="[$eff[effid]] - [$eff[name]]".
       			 "<a href='./?do=admin&amp;mod=eff&amp;redact=$eff[effid]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=eff&amp;del=$eff[effid]'>[X]</a><br/>";
   			  }
  			 	$page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=eff&amp;str=");
               }

    }
       $page.="<br/><form action='./?do=admin&amp;mod=eff' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' name='effid' value='Найти по ID' />
       <input type='submit' name='name' value='Найти по имени' /><br />
       </form>";
    $page.="<br/><a href='./?do=admin&amp;mod=eff&amp;tmp=new'>Добавить эффект</a><br/>";

 }
?>