<?   if ($player[rights]!="admin"){die($goawayfuckingcheater);}

 if (isset($_GET[del])){
 	 	if (isset($_POST[confirm])){ 	 		$sql=mysql_query("DELETE FROM recepts WHERE id='$_GET[del]' LIMIT 1");
 	 		$page.="<br/>Рецепт  с id $_GET[del] был успешно уничтожен<br/>";
 	 		$page.="<br/><a href='./?do=admin&amp;mod=craft'>К списку рецептов</a>";
        }
        else {
 	 	$page.="<form action='./?do=admin&amp;mod=recepts&amp;del=$_GET[del]' method='post'>
 	 	 <br/>Вы уверены?
         <br/><input type='submit' name='confirm' value='Да'>
		 </form>";}
 }
 elseif (isset($_POST[found])){
 	 	if (isset($_POST[id])) {
        $sql=mysql_query("SELECT id, name FROM recepts WHERE id='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Рецепт с id $_POST[found] не найден<br/>";}
          else  {
          	$recept=mysql_fetch_array($sql);
    			$page.="[$recept[id]] - [$recept[name]]".
       			"<a href='./?do=admin&amp;mod=craft&amp;redact=$recept[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=craft&amp;del=$recept[id]'>[X]</a><br/>";
   		  }
 	 	}
 	 	if (isset($_POST[name])) {
 	 	$sql=mysql_query("SELECT id, name FROM recepts WHERE name='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Рецепт $_POST[found] не найден<br/>";}
          else  {
          	$recept=mysql_fetch_array($sql);
    			$page.="[$recept[id]] - [$recept[name]]".
       			"<a href='./?do=admin&amp;mod=craft&amp;redact=$recept[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=craft&amp;del=$recept[id]'>[X]</a><br/>";
   		  }
 	 	}
 	 	$page.="<br/><a href='./?do=admin&amp;mod=craft'>К списку рецептов</a>";
 }
 elseif (isset($_GET[redact])) {
 	if 	(isset($_POST[name]))  {
        $sql = mysql_query("UPDATE recepts SET name='$_POST[name]',info='$_POST[info]',skill='$_POST[skill]',levelskill='$_POST[levelskill]'  WHERE id='$_GET[redact]' LIMIT 1");
        $page.=mysql_error()."<br/>Рецепт  с id $_GET[redact] успешно изменен
        <br/><a href='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]'>Рецепт $_GET[redact]</a><br/>";

 	}
 	else{
      	$sql=mysql_query("SELECT * FROM recepts WHERE id='$_GET[redact]'");
 	 	$recept=mysql_fetch_array($sql);
 	 	$recept[components]=unserialize($recept[components]);
 	 	$recept[result]=unserialize($recept[result]);
 	 	if (isset($_GET[compdel])) {
           $recept[components]=unset_as_mass($recept[components],$_GET[compdel]);
           $tmp=serialize($recept[components]);
           $sql=mysql_query("UPDATE recepts SET components='$tmp' WHERE id='$_GET[redact]' LIMIT 1");
           $page.="<br/>Компонент удален!<br/>";
 		}
 		if (isset($_GET[resultdel])) {
           $recept[result]=unset_as_mass($recept[result],$_GET[resultdel]);
           $tmp=serialize($recept[result]);
           $sql=mysql_query("UPDATE recepts SET result='$tmp' WHERE id='$_GET[redact]' LIMIT 1");
           $page.="<br/>Результат удален!<br/>";
 		}

 	 	$page.="<br/><form action='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]' method='post'>
        <br />ID Рецепта: $recept[id]
        <br />Название<br /><input type='text' name='name'  value='$recept[name]' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''>$recept[info]</textarea>
        <br />Навык<br /><input type='text' name='skill'  value='$recept[skill]' />
        <br />Требуемый уровень навыка<br /><input type='text' name='levelskill'  value='$recept[levelskill]' />
        <br /><input type='submit' value='Изменить' /><br />
        </form><br />";

        if ($_GET[comp]=="new") {
          if (isset($_POST[colvo])) {
              $recept[components][$_POST[id]]=$_POST[colvo];              $tmp=serialize($recept[components]);
           	  $sql=mysql_query("UPDATE recepts SET components='$tmp' WHERE id='$_GET[redact]' LIMIT 1");
              $page.="<br/>Компонент добавлен!<br/>";          }
          else {
        	$page.="<br/><form action='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;comp=new' method='post'>
        	<br />ID Компоненте<br /><input type='text' name='id'  value='' />
        	<br />Количество<br /><input type='text' name='colvo'  value='1' />
        	<br /><input type='submit' value='Добавить' /><br />
        	</form><br />";
        	}
 		}
 		elseif (isset($_GET[comp])) {
          if (isset($_POST[colvo])) {
              $recept[components][$_GET[comp]]=$_POST[colvo];
              $tmp=serialize($recept[components]);
           	  $sql=mysql_query("UPDATE recepts SET components='$tmp' WHERE id='$_GET[redact]' LIMIT 1");
              $page.="<br/>Компонент изменен!<br/>";
          }
          else {
        	$page.="<br/><form action='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;comp=$_GET[comp]' method='post'>
        	<br />ID Компонентa $_GET[comp]
        	<br />Количество<br /><input type='text' name='colvo'  value='".$recept[components][$_GET[comp]]."' />
        	<br /><input type='submit' value='Изменить' /><br />
        	</form><br />";
        	}
 		}
        if ($_GET[result]=="new") {
          if (isset($_POST[colvo])) {
              $recept[result][$_POST[id]]=$_POST[colvo];
              $tmp=serialize($recept[result]);
           	  $sql=mysql_query("UPDATE recepts SET result='$tmp' WHERE id='$_GET[redact]' LIMIT 1");
              $page.="<br/>Результат добавлен!<br/>";
          }
          else {
        	$page.="<br/><form action='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;result=new' method='post'>
        	<br />ID Результата<br /><input type='text' name='id'  value='' />
        	<br />Количество<br /><input type='text' name='colvo'  value='1' />
        	<br /><input type='submit' value='Добавить' /><br />
        	</form><br />";
        	}
 		}
 		elseif (isset($_GET[result])) {
          if (isset($_POST[colvo])) {
              $recept[result][$_GET[result]]=$_POST[colvo];
              $tmp=serialize($recept[result]);
           	  $sql=mysql_query("UPDATE recepts SET result='$tmp' WHERE id='$_GET[redact]' LIMIT 1");
              $page.="<br/>Результат изменен!<br/>";
          }
          else {
        	$page.="<br/><form action='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;result=$_GET[result]' method='post'>
        	<br />ID результата $_GET[result]
        	<br />Количество<br /><input type='text' name='colvo'  value='".$recept[result][$_GET[result]]."' />
        	<br /><input type='submit' value='Изменить' /><br />
        	</form><br />";
        	}
 		}
 		$page.="<br />Компоненты:";
        if (empty($recept[components])){$page.="<br />Компонентов нет!";}
        else{             foreach($recept[components] as $key=>$value){
             	 $sql=mysql_query("SELECT name FROM items WHERE id='$key' LIMIT 1");
                 $name=mysql_result($sql,0,"name");                 $page.="<br/><a href='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;comp=$key'>$name</a>[$value] ";
                 $page.=" <a href='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;compdel=$key'>[X]</a>";             }
        }
        $page.="<br/><a href='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;comp=new'>Добавить компонент</a><br/>";
        $page.="<br/>";
        $page.="<br />Результат:";
        if (!is_array($recept[result])){$page.="<br />Результата нет!<br />";}
        else{
             foreach($recept[result] as $key=>$value){
             	 $sql=mysql_query("SELECT name FROM items WHERE id='$key' LIMIT 1");
                 $name=mysql_result($sql,0,"name");
                 $page.="<br/><a href='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;result=$key'>$name</a>[$value] ";
                 $page.=" <a href='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;resultdel=$key'>[X]</a>";
             }

        }
    }
      $page.="<br/><a href='./?do=admin&amp;mod=craft&amp;redact=$_GET[redact]&amp;result=new'>Добавить результат</a>";
        	$page.="<br/><br/><a href='./?do=admin&amp;mod=craft'>К списку рецептов</a>";
 }
 elseif ($_GET[tmp]=="new")
 {
	if (isset($_POST[id])) {
	 extract($_POST);
     $sql=mysql_query("INSERT INTO recepts (id,name,info,skill,levelskill,result)
         VALUES ('$id','$name','$info','$skill','$levelskill','$result');");
    $page.="<br/>Рецепт $name добавлен<br/>";

	}
	else {
        $page.="<br/><form action='./?do=admin&amp;mod=craft&amp;tmp=new' method='post'>
        <br />ID Рецепта<br /><input type='text' name='id'  value='' />
        <br />Название<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br />Навык<br /><input type='text' name='skill'  value='' />
        <br />Требуемый уровень навыка<br /><input type='text' name='levelskill'  value='' />
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
    }
    $page.="<br/><a href='./?do=admin&amp;mod=craft'>К списку рецептов</a>";
 }
 else {$count= mysql_result(mysql_query("SELECT COUNT(id) FROM recepts"),0,0);
    if ($count<=10) {
      	 $sql=mysql_query("SELECT id,name FROM  recepts LIMIT 10");

         $page.="<br/>[id] - [name] <br/><br/>";
          while($recept = mysql_fetch_array($sql))
  			{
    			$page.="[$recept[id]] - [$recept[name]]".
       			 "<a href='./?do=admin&amp;mod=craft&amp;redact=$recept[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=craft&amp;del=$recept[id]'>[X]</a><br/>";
   			}



     }
    else {
      		if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
      		$num_page=intval($num_page);

      		$temp=($num_page-1)*10;
      		if ($temp>$count) {$page.="<br/>Столько не существует!!<br/>";}
            else {
            	$sql=mysql_query("SELECT id, name FROM recepts LIMIT ".$temp.", 10");
            	$page.="<br/>[id] - [name]<br/><br/>";
            	while($recept = mysql_fetch_array($sql))
  			 	{

    				$page.="[$recept[id]] - [$recept[name]]".
       			 "<a href='./?do=admin&amp;mod=craft&amp;redact=$recept[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=craft&amp;del=$recept[id]'>[X]</a><br/>";
   			   }
  			 	$page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=craft&amp;str=");
               }

    }
       $page.="<br/><form action='./?do=admin&amp;mod=craft' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' name='id' value='Найти по ID' />
       <input type='submit' name='name' value='Найти по имени' /><br />
       </form>";
    $page.="<br/><a href='./?do=admin&amp;mod=craft&amp;tmp=new'>Добавить рецепт</a><br/>";
 }
?>