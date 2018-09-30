<?   if ($player[rights]!="admin"){die($goawayfuckingcheater);}
 if (isset($_GET[del])){
 	 $sql=mysql_query("DELETE FROM quests WHERE id='$_GET[del]' LIMIT 1");
 	 $page.="<br/>Квест  с id $_GET[del] был успешно уничтожен<br/>";
 	 $page.="<br/><a href='./?do=admin&amp;mod=quest'>К списку квестов</a>";
 }
 elseif (isset($_POST[found])){
 	 	if (isset($_POST[id])) {
        $sql=mysql_query("SELECT id, name FROM quests WHERE id='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Квест с id $_POST[found] не найден<br/>";}
          else  {
          	$quests=mysql_fetch_array($sql);
    			$page.="[$quests[id]] - [$quests[name]]".
       			"<a href='./?do=admin&amp;mod=quest&amp;redact=$quests[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=quest&amp;del=$quests[id]'>[X]</a><br/>";
   		  }
 	 	}
 	 	if (isset($_POST[name])) {
 	 	$sql=mysql_query("SELECT id, name FROM quests WHERE name='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Квест с именем $_POST[found] не найден<br/>";}
          else  {
          	$quests=mysql_fetch_array($sql);
    			$page.="[$quests[id]] - [$quests[name]]".
       			"<a href='./?do=admin&amp;mod=quest&amp;redact=$quests[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=quest&amp;del=$quests[id]'>[X]</a><br/>";
   		  }
 	 	}
 	 	$page.="<br/><a href='./?do=admin&amp;mod=quest'>К списку Квестов</a>";
 }
 elseif (isset($_GET[redact])) {
 		$sql=mysql_query("SELECT * FROM quests WHERE id='$_GET[redact]' LIMIT 1");
 	 	$quest=mysql_fetch_array($sql);
 	 	$quest[info]=unserialize($quest[info]);
 	if 	(isset($_POST[name]))  {
        $sql = mysql_query("UPDATE quests SET name='$_POST[name]', town='$_POST[town]'  WHERE id='$_GET[redact]' LIMIT 1");
        $page.="<br/>Квест  с id $_GET[redact] успешно изменен<br/>";

 	}
 	elseif (isset($_GET[delstat])) {
 	    $quest[info]=unset_as_mass($quest[info],$_GET[delstat]);
 	    $tmp=serialize($quest[info]); 		$sql = mysql_query("UPDATE quests SET info='$tmp'  WHERE id='$_GET[redact]' LIMIT 1");
        $page.="<br/>Состояние  $_GET[delstat] уничтожено<br/>"; 	}
 	elseif ($_GET["stat"]=="new") { 		if 	(isset($_POST[status]))  {             $quest[info][$_POST[status]]=$_POST[text];
             $tmp=serialize($quest[info]);
 			 $sql = mysql_query("UPDATE quests SET info='$tmp'  WHERE id='$_GET[redact]' LIMIT 1");
        	 $page.="<br/>Состояние  $_GET[stat] добавлено<br/>"; 		}
 		else   {
        	$page.="<br/><form action='./?do=admin&amp;mod=quest&amp;redact=$_GET[redact]&amp;stat=new' method='post'>
        	<br />Состояние<br /><input type='text' name='status'  value='' />
        	<br />Oписание<br/><textarea name='text' rows='3'  value=''></textarea>
        	<br /><input type='submit' value='Добавить' /><br />
        	</form><br />";
        }
 	}
 	elseif (isset($_GET["stat"])) {
 		if 	(isset($_POST[text]))  {
             $quest[info][$_GET["stat"]]=$_POST[text];
             $tmp=serialize($quest[info]);
 			 $sql = mysql_query("UPDATE quests SET info='$tmp'  WHERE id='$_GET[redact]' LIMIT 1");
        	 $page.="<br/>Состояние  $_GET[stat] изменено<br/>";
 		}
 		else   {
        	$page.="<br/><form action='./?do=admin&amp;mod=quest&amp;redact=$_GET[redact]&amp;stat=$_GET[stat]' method='post'>
        	<br />Состояние  $_GET[stat]
        	<br />Oписание<br/><textarea name='text' rows='3'  value=''>".$quest[info][$_GET["stat"]]."</textarea>
        	<br /><input type='submit' value='Изменить' /><br />
        	</form><br />";
        }
 	}
 	 	$page.="<br/><form action='./?do=admin&amp;mod=quest&amp;redact=$_GET[redact]' method='post'>
        <br />ID Квеста $quest[id]
        <br />Имя<br /><input type='text' name='name'  value='$quest[name]' />
        <br />Город<br /><input type='text' name='town'  value='$quest[town]' />
        <br /><input type='submit' value='Изменить' /><br />
        </form><br />";

        if  (!is_array($quest[info]))  { $page.="<br/>Состояний нет!<br/>"; }
        else {            foreach($quest[info] as $key=>$value){               $page.="<br/>[$key]";
               $page.="<a href='./?do=admin&amp;mod=quest&amp;redact=$_GET[redact]&amp;stat=$key'>[ИЗМ]</a>";
               $page.="<a href='./?do=admin&amp;mod=quest&amp;redact=$_GET[redact]&amp;delstat=$key'>[X]</a>";            }        }
        	$page.="<br/><br/><a href='./?do=admin&amp;mod=quest&amp;redact=$_GET[redact]&amp;stat=new'>Новое состояние</a>";
        	$page.="<br/><a href='./?do=admin&amp;mod=quest'>К списку квестов</a>";
 }
 elseif ($_GET[tmp]=="new")
 {
	if (isset($_POST[id])) {
	 extract($_POST);
     $sql=mysql_query("INSERT INTO quests (id,name,town)
         VALUES ('$id','$name','$town');");
    $page.="<br/>Квест $name добавлен<br/>";

	}
	else {
        $page.="<br/><form action='./?do=admin&amp;mod=quest&amp;tmp=new' method='post'>
        <br />ID Квеста<br /><input type='text' name='id'  value='' />
        <br />Имя<br /><input type='text' name='name'  value='' />
        <br />Город<br /><input type='text' name='town'  value='' />
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
    }
    $page.="<br/><a href='./?do=admin&amp;mod=quest'>К списку Квестов</a>";
 }
 else {$count= mysql_result(mysql_query("SELECT COUNT(id) FROM quests"),0,0);
    if ($count<=10) {
      	 $sql=mysql_query("SELECT id,name,town FROM  quests LIMIT 10");

         $page.="<br/>[id] - [name] <br/><br/>";
          while($quests = mysql_fetch_array($sql))
  			{
    			$page.="[$quests[id]] - [$quests[name]] - [$quests[town]]".
       			 "<a href='./?do=admin&amp;mod=quest&amp;redact=$quests[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=quest&amp;del=$quests[id]'>[X]</a><br/>";
   			}



     }
    else {
      		if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
      		$num_page=intval($num_page);

      		$temp=($num_page-1)*10;
      		if ($temp>$count) {$page.="<br/>Столько Квестов не существует!!<br/>";}
            else {
            	$sql=mysql_query("SELECT id, name, town FROM quests LIMIT ".$temp.", 10");
            	$page.="<br/>[id] - [name]<br/><br/>";
            	while($quests = mysql_fetch_array($sql))
  			 	{

    				$page.="[$quests[id]] - [$quests[name]] - [$quests[town]]".
       			 "<a href='./?do=admin&amp;mod=quest&amp;redact=$quests[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=quest&amp;del=$quests[id]'>[X]</a><br/>";
   			}
  			 	$page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=quest&amp;str=");
               }

    }
       $page.="<br/><form action='./?do=admin&amp;mod=quest' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' name='id' value='Найти по ID' />
       <input type='submit' name='name' value='Найти по имени' /><br />
       </form>";
    $page.="<br/><a href='./?do=admin&amp;mod=quest&amp;tmp=new'>Добавить Квест</a><br/>";
 }
?>