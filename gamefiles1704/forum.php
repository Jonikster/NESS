<?
$player[bag]=unserialize($player[bag]);
if(have_item($player[bag],34)>=1||have_item($player[bag],14)>=1)
{
   $goaw="Вы забанены, доступ к игре закрыт";
   if ($player[rights]=="userban"){die($goaw);}
   if ($player[rights]=="admin" or $player[rights]=="moder") {$superuser=1;}
   $razd=array("free"=>"Свободное общение","trade"=>"Торговля","faq"=>"Игровые вопросы","idea"=>"Предложения и пожелания","bags"=>"Ошибки и баги");
   if (isset($_GET["add"])) {       if (empty($razd[$_GET["add"]])) {$page.="<p class='d'><b>Ошибка!!!</b></p><br/>Такого подраздела не существует!";}
       else {       	    if (isset($_POST[temaname])) {       	    	$_POST[temaname]=substr(htmlspecialchars($_POST[temaname]),0,99);
       	    	$_POST[post]=substr(htmlspecialchars($_POST[post]),0,999);       	    	$page.="<p class='d'><b>$_POST[temaname]</b></p>";             	$sql=mysql_query("SELECT id FROM forum WHERE temaname='$_POST[temaname]' OR post='$_POST[post]'");
             	if (mysql_num_rows($sql)>0 or empty($_POST[post]) or empty($_POST[temaname])) {$page.="<br/>Ошибка! Похоже такая тема уже есть!<br/>";}
             	elseif ($player[rights]=="userban")  {$page.="<br/>Забаненные игроки не могут общаться!";}
             	else {             	   $sql=mysql_query("SELECT max(tema) FROM forum LIMIT 1");
             	   $number=mysql_result($sql,0,0);$_POST['post']=str_replace('
','<br>',$_POST['post']);
             	   $number++;                   $sql=mysql_query("INSERT INTO forum(razdel,tema,temaname,username,time,post) VALUES ('$_GET[add]','$number','$_POST[temaname]','$player[char_name]','".time()."','$_POST[post]')");
                   $page.=mysql_error()."<br/>Тема создана!<br/>";
                   $page.="<br/><a href='./?do=forum&amp;read=$number'>$_POST[temaname]</a>";             	}       	    }
       	    else {         		$sql=mysql_query("SELECT count( distinct tema) FROM forum WHERE razdel='$_GET[add]' LIMIT 1");
      	 		$num=mysql_result($sql,0,0);
      			$page.="<p class='d'><b>".$razd[$_GET["add"]]."</b> Тем: $num</p>";
      			$page.="<form method='post' action='./?do=forum&amp;add=$_GET[add]'>
      			<br/>Название темы<br/><input type='text' name='temaname' maxlength='100' />
      			<br/>Сообщение<br/><textarea name='post' maxlength='5000'></textarea>
      			<br/><input type='submit' value='Добавить' />
      			</form>";
      		}
      		$page.="<br/><a href='./?do=forum&amp;view=$_GET[add]'>В раздел</a>";       }
       $page.="<br/><a href='./?do=forum'>На форум</a><br/>";   }

   elseif (isset($_GET["view"])) {      if (isset($_GET[temedit])) {
   	  	if (!($superuser)) {$mess.="<br/>Вали отседова читер вонючий!!!";}
   	  	else {   	  		$page.="<p class='d'><b>".$razd[$_GET["view"]]."</b></p>";   	  		if (isset($_POST[temaname])) {   	  			 $_POST[temaname]=substr(htmlspecialchars($_POST[temaname]),0,99);                 $sql=mysql_query("UPDATE forum SET temaname='$_POST[temaname]' WHERE tema='$_GET[temedit]'");
                 $page.="<br/>Тема переименована!<br/>";   	  		}
   	  		else {
         	  $sql=mysql_query("SELECT temaname FROM forum WHERE tema='$_GET[temedit]' LIMIT 1");
         	  $temaname=mysql_result($sql,0,"temaname");
         	  $page.="<form method='post' action='./?do=forum&amp;view=$_GET[view]&amp;temedit=$_GET[temedit]'>
      			<br/>Название темы<br/><input type='text' name='temaname' value='$temaname' maxlength='100' />
      			<br/><input type='submit' value='Изменить' />
      			</form>";
           		$page.=" <a href='./?do=forum&amp;view=$_GET[view]&amp;deltem=$_GET[temedit]'>Удалить тему</a>";
      		}
      		$page.="<br/><a href='./?do=forum&amp;view=$_GET[view]'>В раздел</a>";
   	  	}
      }
     else {
      if (isset($_GET[deltem])) {
   	  	if (!($superuser)) {$mess.="<br>Вали отседова читер вонючий!!!";}
   	  	else {
         $sql=mysql_query("DELETE FROM forum WHERE tema='$_GET[deltem]'");
         $mess.="<br/>Тема была удалена!";
   	  	}
      }
      $sql=mysql_query("SELECT count( distinct tema) FROM forum WHERE razdel='$_GET[view]' LIMIT 1");
      $num=mysql_result($sql,0,0);      $page.="<p class='d'><b>".$razd[$_GET["view"]]."</b> Тем: $num</p>";
      $page.=$mess;
      if ($num<1) {$page.="<br/>Тем пока нет!";}
      else {
      	if (empty($_GET[str])) {$_GET[str]=1; }
      	$k=10;
      	$begin=($_GET[str]-1)*10;
      	$sql=mysql_query("SELECT DISTINCT temaname,tema FROM forum WHERE razdel='$_GET[view]' GROUP BY tema DESC LIMIT $begin,10");

      	while ($tema=mysql_fetch_array($sql)) {
      	   $tmp=mysql_query("SELECT MAX(time),username FROM forum WHERE tema='$tema[tema]' GROUP BY tema LIMIT 1");
           $tema["MAX(time)"]= mysql_result($tmp,0,"MAX(time)");
           $tema[username]= mysql_result(mysql_query("SELECT username FROM forum WHERE time='".$tema["MAX(time)"]."' LIMIT 1"),0,"username");           $temes[$tema["MAX(time)"]][username]=$tema[username];
           $temes[$tema["MAX(time)"]][temaname]=$tema[temaname];
           $temes[$tema["MAX(time)"]][tema]=$tema[tema];
         }
         krsort($temes);
         foreach($temes as $key=>$value)
         {  $page.="<br/><a href='./?do=forum&amp;read=$value[tema]'>$value[temaname]</a>";
           if ($superuser) {           	$page.=" <a href='./?do=forum&amp;view=$_GET[view]&amp;temedit=$value[tema]'>[правка]</a>";
           }
					 
     	   $page.="<br/>$value[username] ".date2("j.m.",$key+3600*8).(date("Y",$key)+170).date("  G:i",$key+3600*8);          $page.="<br/>";
      	} $page.="<br/>";
      	$page.=nav_page(ceil($num/10),$_GET[str],"./?do=forum&amp;view=$_GET[view]&amp;str=");
      }
      $page.="<br/>";
      $page.="<br/><a href='./?do=forum&amp;add=$_GET[view]'>Новая тема</a>";
     }
      $page.="<br/><a href='./?do=forum'>На форум</a><br/>";
      $page.="<br/>";   }
   elseif (isset($_GET[read])) {
      $sql=mysql_query("SELECT count(post),temaname,razdel FROM forum WHERE tema='$_GET[read]' GROUP BY temaname DESC LIMIT 1");
      //$page.=mysql_error();
      $res=mysql_fetch_array($sql);
      if (mysql_num_rows($sql)<1) {$page.="<p class='d'><b>Ошибка!!!</b></p><br/>Такой темы не существует!";}
      else {
        $page.="<p class='d'><b>$res[temaname]</b></p>";
        if (isset($_POST[post])) {        	if ($player[rights]=="userban")  {$page.="<br/>Забаненные игроки не могут общаться!";}
        	else  {
       	    	$_POST[post]=substr(htmlspecialchars($_POST[post]),0,999);
             	$sql=mysql_query("SELECT id FROM forum WHERE post='$_POST[post]' LIMIT 1");

             	if (mysql_num_rows($sql)>0 or empty($_POST[post])) {$page.="<br/>Ошибка! Похоже такой пост уже есть!<br/>";}
             	else {                   $maxposts=100;
                   $sql=mysql_query("SELECT count(id) FROM forum WHERE tema='$_GET[read]'");
                   if (mysql_result($sql,0,0)>$maxposts){                      $sql=mysql_query("SELECT MIN(time) FROM forum WHERE tema='$_GET[read]' GROUP BY tema LIMIT 1");
                      $mintime = mysql_result($sql,0,"MIN(time)");
                      $sql=mysql_query("DELETE FROM forum WHERE tema='$_GET[read]' AND time='$mintime' LIMIT 1");
                      $page.=mysql_error();                   } $_POST['post']=str_replace('
','<br>',$_POST['post']);
                   $sql=mysql_query("INSERT INTO forum(razdel,tema,temaname,username,time,post) VALUES ('$res[razdel]','$_GET[read]','$res[temaname]','$player[char_name]','".time()."','$_POST[post]')");
                   $page.=mysql_error()."<br/>Сообщение добавлено!!<br/>";
             	}
             }        }
$page.='<br><a href="/?do=forum&rnd='.rand(1,100).'&read='.$_GET[read].'">Обновить</a>';
        if (isset($_GET[writeto])) {
            $sql=mysql_query("SELECT id,status,rights FROM users where char_name='$_GET[writeto]' LIMIT 1");
   	  		$user=mysql_fetch_array($sql);        	if (isset($_GET[ban])) {        		if (!($superuser)) {$page.="<br/>Вали отседова читер вонючий!!!";}
   	  			else {
   	  			   if (mysql_num_rows($sql)<1) {$page.="<br/>$_GET[writeto] не найден!";}
   	  			   elseif ($user[rights]!='user') {$page.="<br/>$_GET[writeto] нельзя забанить!!!";}
   	  			   else {
   	  			   	$user[status]=unserialize($user[status]);
                    if ($_GET[ban]=="week") {$timeban=24*60*60;}
                    elseif ($_GET[ban]=="3day") {$timeban=2*60*60;}
                    else {$timeban=15*60;}
                    $user[status][timeban]=time()+$timeban;
                    $user[status]=serialize($user[status]);
                    $sql=mysql_query("UPDATE users SET status='$user[status]',rights='userban' WHERE id='$user[id]' LIMIT 1");
         			$page.="<br/>$_GET[writeto] забанен!";
         		   }
   	  			}        	}           $page.="<form method='post' action='./?do=forum&amp;read=$_GET[read]&amp;r=".rand(1,100)."'>
      			<br/>Новое сообщение<br/><textarea name='post' maxlength='5000'>".(isset($_GET[writeto])?$_GET[writeto].', ':'')."</textarea>
      			<br/><input type='submit' value='Добавить' />
      			</form>";
      	   if ($superuser) {      	   		
		        $page.="<br/><img src='/img/icon/cancel.PNG'/> <a href='./?do=forum&amp;read=$_GET[read]&amp;writeto=$_GET[writeto]&amp;ban=day'>Бан 15 минут</a>";
      	   		$page.="<br/><img src='/img/icon/cancel.PNG'/> <a href='./?do=forum&amp;read=$_GET[read]&amp;writeto=$_GET[writeto]&amp;ban=3day'>Бан 2 часа</a>";
      	   		$page.="<br/><img src='/img/icon/cancel.PNG'/> <a href='./?do=forum&amp;read=$_GET[read]&amp;writeto=$_GET[writeto]&amp;ban=week'>Бан сутки</a>";
      	   		}
      	   $page.="<br/><img src='/img/icon/i.PNG'/> <a href='./?view=player&amp;about=$user[id]'>Информация</a>";
      	   $page.="<br/><img src='/img/icon/mail2.PNG'/> <a href='./?do=mail&act=write&user=$_GET[writeto]'>Письмо</a>";
      	   $page.="<br/><br/><a href='./?do=forum&amp;read=$_GET[read]'>Назад</a>";        }
        else {        	if (isset($_GET[delpost])) {
   	  			if (!($superuser)) {$page.="<br/>Прошу покинуть данную страницу, доступна лишь администрации";}
   	  			else {
         			$sql=mysql_query("DELETE FROM forum WHERE id='$_GET[delpost]'");
         			$page.="<br/>Пост удален!";
   	  			}
      		}
					$page.="<form method='post' action='./?do=forum&amp;read=$_GET[read]&amp;r=".rand(1,100)."'><textarea name='post' maxlength='5000'></textarea>
      			<br/><input type='submit' value='Добавить' />";
if (empty($_GET[str])) {$_GET[str]=1; }
        	$begin=($_GET[str]-1)*10;
        	$sql=mysql_query("SELECT id,username,time,post FROM forum WHERE tema='$_GET[read]' ORDER BY time DESC LIMIT $begin,10");

      		while ($post=mysql_fetch_array($sql)) {

     	   		$page.="<br/><a href='./?do=forum&amp;read=$_GET[read]&amp;writeto=$post[username]'>$post[username]</a>, ";
     	   		$page.=date2("j.m.",$post["time"]+3600*8).(date("Y",$post["time"])+170).date("  G:i",$post["time"]+3600*8);
     	   		if ($superuser) {$page.=" [<a href='./?do=forum&amp;read=$_GET[read]&amp;str=$_GET[str]&amp;delpost=$post[id]'>x</a>]";}
      	   		$page.="<br/>$post[post]<br/>";
      		}
      		$page.=nav_page(ceil($res["count(post)"]/10),$_GET[str],"./?do=forum&amp;read=$_GET[read]&amp;str=");
      		
      	}
      }
      	$page.="<br/><a href='./?do=forum&amp;view=$res[razdel]'>В раздел</a>";
      	$page.="<br/><a href='./?do=forum'>На форум</a><br/>";

   }
   else {   	$page.="<p class='d'><b>Форум</b></p>";
   	if ($_GET[act]=="clear" and $player[rights]=="admin") {   		$sql=mysql_query("DELETE FROM forum");
   		$page.="<br/>Зачистка кончилась!!!";   	}
    foreach ($razd as $key=>$value){
     $sql=mysql_query("SELECT count( distinct tema) FROM forum WHERE razdel='$key' LIMIT 1");
     if (mysql_result($sql,0,0)<1) {$num=0;}
     else {$num=mysql_result($sql,0,0);}
     $page.="<br/><b><a href='./?do=forum&amp;view=$key'>".$razd[$key]."</a>[".$num."]</b>";
     if ($num>0) {
     $sql=mysql_query("SELECT tema,MAX(time) FROM forum WHERE razdel='$key' GROUP BY razdel DESC LIMIT 1");
     $page.=mysql_error();
     $tema=mysql_fetch_array($sql);
     $sql=mysql_query("SELECT username,temaname,tema FROM forum WHERE time='".$tema["MAX(time)"]."' LIMIT 1");
     $variable=mysql_fetch_array($sql);
     $page.="<br/><a href='./?do=forum&amp;read=$variable[tema]'>$variable[temaname]</a>";
     $page.="<br/>$variable[username] ".date2("j.m.",$tema["MAX(time)"]+3600*8).(date("Y",$tema["MAX(time)"])+170).date("  G:i",$tema["MAX(time)"]+3600*8);


     } else {$page.="<br/>Тем нет!";}
      $page.="<br/>";    }
    if ($player[rights]=="admin") {$page.="<a href='./?do=forum&amp;act=clear'>Очистить форум</a>";}
    $page.="<br/>";
   }
   $page.="<br/><a href='./'>В игру</a><br/>";
   $page.="<br/><p class='d'><b>".date2("j.m.",date('U')+8*3600).(date("Y")+170).date("  G:i",date('U')+8*3600)."</b></p>";
}else{
$page.="<p class='d'><b>Форум</b></p><br/>";
$page.='Сеть недоступна! Нет возможности подключения к форуму, для соединения с форумом требуется КПК <br/>';
$page.='<br/><a href="/">В игру</a><br/>';
$page.="<br/><p class='d'><b>".date("j.m.",date('U')+8*3600).(date("Y")+170).date("  G:i",date('U')+8*3600)."</b></p>";
}