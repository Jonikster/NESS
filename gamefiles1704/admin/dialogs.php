<? if ($player[rights]!="admin"){die($goawayfuckingcheater);}
   if (isset($_GET[del])){
 	 	if (isset($_POST[confirm])){
 	 		$sql=mysql_query("DELETE FROM talk WHERE talkid='$_GET[del]' LIMIT 1");
 	 		$page.="<br/>Диалог  с talkid $_GET[del] был успешно уничтожен<br/>";
 	 		$page.="<br/><a href='./?do=admin&amp;mod=dialog'>К списку диалогов</a>";
        }
        else {
 	 	$page.="<form action='./?do=admin&amp;mod=dialog&amp;del=$_GET[del]' method='post'>
 	 	 <br/>Вы уверены?
         <br/><input type='submit' name='confirm' value='Да'>
		 </form>";}
 }
  elseif (isset($_GET["copy"])){
  	if (isset($_POST[talk_id])) {
  		$sql=mysql_query("SELECT * FROM talk WHERE talkid='$_POST[talk_id]' LIMIT 1");
  		if (mysql_num_rows($sql)>0)  { $page.="<br/>Диалог  с id $_POST[talk_id] уже существует!<br/>";}
  		else {
 	 		$sql=mysql_query("SELECT * FROM talk WHERE talkid='$_GET[copy]' LIMIT 1");
 	 		$talk=mysql_fetch_array($sql);
     		$sql=mysql_query("INSERT INTO talk(talkid,name,dialog) VALUES ('$_POST[talk_id]','$talk[name]','$talk[dialog]');");
 	 		$page.="<br/>Диалог  с id $_POST[talk_id] был успешно создан<br/>";
 	 		$page.="<br/><a href='./?do=admin&amp;mod=dialog&amp;redact=$_POST[talk_id]'>Редактировать</a>";
 	 		$k=1;
 	 	}
    }
    if ($k!=1) {
 	 $page.="<br/><form action='./?do=admin&amp;mod=dialog&amp;copy=$_GET[copy]' method='post'>
       <br />Введите TALK_ID<input type='text' name='talk_id'  value='' />
       <br /><input type='submit' value='Копировать' />
       </form>";
       }
       $page.="<br/><a href='./?do=admin&amp;mod=dialog'>К списку</a>";
 }
 elseif (isset($_GET[redact])){
 	$sql=mysql_query("SELECT * FROM talk WHERE talkid='$_GET[redact]' LIMIT 1");
 	$talk=mysql_fetch_array($sql);
 	$link="./?do=admin&amp;mod=dialog&amp;redact=$_GET[redact]";
    $dialog=unserialize($talk[dialog]);
 	if (isset($_POST[epicid])) {
            $dialog[$_POST[epicid]]=array("on_enter"=>$_POST[on_enter],"reply"=>$_POST[reply]);
            $tmp=serialize($dialog);
            $sql=mysql_query("UPDATE talk SET dialog='$tmp' WHERE talkid='$_GET[redact]' LIMIT 1");
            $page.=mysql_error()."<br/>Эпизод $_POST[epicid] добавлен";
            $_GET["var"]=$_POST[epicid];
         }
    if (isset($_GET[delvar]))  {
        $dialog=unset_as_mass($dialog,$_GET[delvar]);
        $tmp=serialize($dialog);
        $sql=mysql_query("UPDATE talk SET dialog='$tmp' WHERE talkid='$_GET[redact]' LIMIT 1");
        $page.=mysql_error()."<br/>Эпизод $_GET[delvar] удален";
    }
 	if ($_GET[tmp]=="new") {
 	     	$page.="<form action='$link' method='post'>
         	<br/>Episode ID<br/><input type='text' name='epicid' />
         	<br/>ON_ENTER<br/><textarea name='on_enter' rows='4' cols='30' ></textarea>
         	<br/>reply<br/><textarea name='reply' rows='4' cols='30' ></textarea>
 	     	<br/><input type='submit' value='Добавить' />
 	     	</form>";


 	  }
 	elseif (!is_array($dialog)) {$page.="<br/>Эпизодов нет<br/>$talk[dialog]";}
 	elseif (isset($_GET["var"])) {
 		if (isset($_POST[on_enter]) and $_GET[tmp]!="new") {
 			$dialog[$_GET["var"]][on_enter]=$_POST[on_enter];
 			$dialog[$_GET["var"]][reply]=$_POST[reply];
            $tmp=serialize($dialog);
            $sql=mysql_query("UPDATE talk SET dialog='$tmp' WHERE talkid='$_GET[redact]' LIMIT 1");
            $page.=mysql_error()."<br/>Эпизод $_GET[var] изменен";
 		}
      if (!isset($dialog[$_GET["var"]])) {$page.="<br/> Такого эпизода нет!";}
      else {
        if (isset($_POST["new"])) {
          if (empty($dialog[$_POST["to"]])) {$page.="<br/>Неверная цель!";}
          else {
          	$tmp=array("if"=>$_POST["if"],"text"=>$_POST["text"],"to"=>$_POST["to"]);
           $dialog[$_GET["var"]][variants][]=$tmp;
           $tmp=serialize($dialog);
           $sql=mysql_query("UPDATE talk SET dialog='$tmp' WHERE talkid='$_GET[redact]' LIMIT 1");
           $page.=mysql_error()."<br/>Реплика добавлена";
          }
        }
        elseif (isset($_POST["edit"]))
        {   if (empty($dialog[$_POST["to"]])) {$page.="<br/>Неверная цель!";}
          else {
          $dialog[$_GET["var"]][variants][$_GET[id]]=array("if"=>$_POST["if"],"text"=>$_POST["text"],"to"=>$_POST["to"]);

           $tmp=serialize($dialog);
           $sql=mysql_query("UPDATE talk SET dialog='$tmp' WHERE talkid='$_GET[redact]' LIMIT 1");
           $page.=mysql_error()."<br/>Реплика изменена";
          }
        }

        if ($_GET[id]=="new") {
          $page.="<form action='$link&amp;var=$_GET[var]' method='post'>
            <input type='hidden' name='new' />
         	<br/>ЕСЛИ<br/><textarea name='if' rows='3' cols='30' >$"."if=1;</textarea>
         	<br/>Текст ссылки<br/><textarea name='text' rows='4' cols='30' ></textarea>
         	<br/>Куда ведет<br/><input type='text' name='to' value='begin' />
 	     	<br/><input type='submit' value='Добавить' />
 	     	</form>";
           $page.="<br/><a href='$link&amp;var=$_GET[var]'>К эпизоду</a>";
        }
        elseif (isset($_GET[id])) {
            $variant=$dialog[$_GET["var"]][variants][$_GET[id]];
             $page.="<form action='$link&amp;var=$_GET[var]&amp;id=$_GET[id]' method='post'>
            <input type='hidden' name='edit' />
         	<br/>ЕСЛИ<br/><textarea name='if' rows='3' cols='30' >$variant[if]</textarea>
         	<br/>Текст ссылки<br/><textarea name='text' rows='4' cols='30' >$variant[text]</textarea>
         	<br/>Куда ведет<br/><input type='text' name='to' value='$variant[to]' />
 	     	<br/><input type='submit' value='Изменить' />
 	     	</form>";
 	     	$page.="<br/><a href='$link&amp;var=$_GET[var]'>К эпизоду</a>";

        }
        else {
      	 $page.="<form action='$link&amp;var=$_GET[var]' method='post'>
         	<br/><b>Episode ID: </b> $_GET[var]<br/>
         	<br/>ON_ENTER<br/><textarea name='on_enter' rows='4' cols='30' >".$dialog[$_GET["var"]][on_enter]."</textarea>
         	<br/>Reply<br/><textarea name='reply' rows='4' cols='30' >".$dialog[$_GET["var"]][reply]."</textarea>
 	     	<br/><input type='submit' value='Изменить' />
 	     	</form>";
 	     	if (!is_array($dialog[$_GET["var"]][variants])) {$page.="<br/>Реплик нет!";}
 	     	else {
 	     		$variants=$dialog[$_GET["var"]][variants];
 	     		for ($i=0;$i<sizeof($variants);$i++) {
 	     			$page.="<br/>IF (".$variants[$i]["if"].")";
 	     			$page.="<br/><a href='$link&amp;var=$_GET[var]&amp;id=$i'>{".$variants[$i]["text"]."}</a>=><a href='$link&amp;var=".$variants[$i]["to"]."'>".$variants[$i]["to"]."</a>";
 	     		}
 	     	}
        }
     	$page.="<br/><br/><a href='$link&amp;var=$_GET[var]&amp;id=new'>Новая реплика</a><br/>";
     }


 	}
 	if (is_array($dialog )){
     foreach ($dialog as $key=>$value) {
        	$page.="<br/><a href='$link&amp;var=$key'>".$key."</a>
        	<a href='$link&amp;delvar=$key'>[X]</a>";
 	 	}
 	 	}
       $page.="<br/>";



    $page.="<br/><a href='$link&amp;tmp=new'>Добавить новый эпизод</a>";
    $page.="<br/><a href='./?do=admin&amp;mod=dialog'>К списку диалогов</a>";
 }
 elseif (isset($_POST[found])){
 	 	if (isset($_POST[id])) {
        $sql=mysql_query("SELECT talkid, name FROM talk WHERE talkid='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Диалог с talkid $_POST[found] не найден<br/>";}
          else  {
          	$talk=mysql_fetch_array($sql);
    			$page.="[$talk[talkid]] - [$talk[name]]".
       			"<a href='./?do=admin&amp;mod=dialog&amp;redact=$talk[talkid]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=dialog&amp;del=$talk[talkid]'>[X]</a><br/>";
   		  }
 	 	}
 	 	if (isset($_POST[name])) {
 	 	$sql=mysql_query("SELECT talkid, name FROM talk WHERE name='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Диалог  $_POST[found] не найден<br/>";}
          else  {
          	$talk=mysql_fetch_array($sql);
    			$page.="[$talk[id]] - [$talk[name]]".
       			"<a href='./?do=admin&amp;mod=dialog&amp;redact=$talk[talkid]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=dialog&amp;del=$talk[talkid]'>[X]</a><br/>";
   		  }
 	 	}
 	 	$page.="<br/><a href='./?do=admin&amp;mod=dialog'>К списку диалогов</a>";
 }
 elseif ($_GET[tmp]=="new")
 {
	if (!empty($_POST[talkid])) {
	 $dialog["end"][reply]="byi";
	 $dialog["begin"][reply]="hi";
	 $dialog=serialize($dialog);
     $sql=mysql_query("INSERT INTO talk (talkid,name,dialog) VALUES ('$_POST[talkid]','$_POST[name]','$dialog');");
     $page.="<br/>Диалог $_POST[name] добавлен<br/>";

	}
	else {
        $page.="<br/><form action='./?do=admin&amp;mod=dialog&amp;tmp=new' method='post'>
        <br />TALK ID<br /><input type='text' name='talkid'  value='' />
        <br />Имя<br /><input type='text' name='name'  value='' />
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
    }
    $page.="<br/><a href='./?do=admin&amp;mod=dialog'>К списку диалогов</a>";
 }
  else {$count= mysql_result(mysql_query("SELECT COUNT(talkid) FROM talk"),0,0);
    if ($count<=10) {
      	 $sql=mysql_query("SELECT talkid,name FROM  talk LIMIT 10");

         $page.="<br/>[talkid] - [name] <br/><br/>";
          while($talk = mysql_fetch_array($sql))
  			{
    			$page.="[$talk[talkid]] - [$talk[name]]".
       			 "<a href='./?do=admin&amp;mod=dialog&amp;copy=$talk[talkid]'>[copy]</a> <a href='./?do=admin&amp;mod=dialog&amp;redact=$talk[talkid]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=dialog&amp;del=$talk[talkid]'>[X]</a><br/>";
   			}



     }
    else {
      		if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
      		$num_page=intval($num_page);

      		$temp=($num_page-1)*10;
      		if ($temp>$count) {$page.="<br/>Столько монстров не существует!!<br/>";}
            else {
            	$sql=mysql_query("SELECT talkid, name FROM talk LIMIT ".$temp.", 10");
            	$page.="<br/>[talkid] - [name]<br/><br/>";
            	while($talk = mysql_fetch_array($sql))
  			 	{

    				$page.="[$talk[talkid]] - [$talk[name]]".
       			 "<a href='./?do=admin&amp;mod=dialog&amp;copy=$talk[talkid]'>[copy]</a> <a href='./?do=admin&amp;mod=dialog&amp;redact=$talk[talkid]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=dialog&amp;del=$talk[talkid]'>[X]</a><br/>";
   			  }
  			 	$page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=dialog&amp;str=");
               }

    }
       $page.="<br/><form action='./?do=admin&amp;mod=dialog' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' name='id' value='Найти по ID' />
       <input type='submit' name='name' value='Найти по имени' /><br />
       </form>";
    $page.="<br/><a href='./?do=admin&amp;mod=dialog&amp;tmp=new'>Добавить диалог</a><br/>";
 }
?>