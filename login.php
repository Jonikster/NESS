<?php // login.php :: Handles logins and cookies.

include('lib.php');
   	$page="<p class='d'><b>N.E.S.S.</b><br/>постапокалиптическая RPG игра</p>" ;
if (isset($_GET["do"])) {
    if ($_GET["do"] == "login") {
      $login=htmlspecialchars($_POST["login"]);
        $pass=md5(htmlspecialchars($_POST["pass"]));
        $sql = mysql_query("SELECT * FROM users WHERE login='$login' AND pass='$pass' LIMIT 1");

        if (mysql_num_rows($sql) == 0) {$page.=" <br/><img src='/img/icon/cancel.PNG'/> Неправильный логин или пароль.<br/><br/><img src='/img/icon/cancel.PNG'/> <a href=\"./login.php\">Пожалуйста повторите!</a>";} else
        {
        	$row = mysql_fetch_array($sql);
        	if (isset($_POST["rememberme"])) { $expiretime = time()+31536000; $rememberme = 1; } else { $expiretime = 0; $rememberme = 0; }
        	$cookie = md5($row["id"] . " " . $login . " " . md5($pass . "--" . rand(1,1000)) . " " . $rememberme);
        	setcookie($gamename, $cookie, $expiretime, "/", "", 0);
            $sql = "UPDATE users SET cookid='$cookie' WHERE id=".$row[id];
            $result = mysql_query($sql);
            if ($result) {
        	    $page.="<br/><img src='/img/icon/p.PNG'/> Вы вошли в игру. Поздравляем!<br/><br/><img src='/img/icon/arrow.PNG'/> <a href='./'>Далее</a><br/><br/>";
        	    display($page, "Вход",$style);
        	    die();
        	 } else {$page.="<br/><img src='/img/icon/cancel.PNG'/> ОШИБКА!!!<br/>";};
        }



     }

}
else {
          setcookie($gamename, "", time()-100000, "/", "", 0);

}
    $page.="<br/><b>Пролог</b>";
    $page.="<br/>2010 год, произошла ядерная война, повлекшая за собой череду глобальных изменений в мире. Повышенный радиационный фонд привёл к мутации, каждый кто попал под него потерял рассудок или стал ходячим мертвецом, сушу заполонили разного вида твари и мутанты... Единственное, что спасало - это возникшая из не откуда военная организация N.E.S.S. Которая в свою очередь стала властью и держит 82% всей территории. Единичные места остались не под властью военных и эта история начинается как раз с такого места, нейтральная оборонительная база, которая по стечению обстоятельств оказалась в эпицентре событий...";
    $page.="<br/><br/><img src='./logo.png' />";
    $page.="<br/><br/><img src='/img/icon/p.PNG'/> <b><a href='./news.php?str=1'>Последняя новость:</a></b>";
	$sql=mysql_query("SELECT * FROM news ORDER BY date DESC LIMIT 1");
	if (mysql_num_rows($sql)!=1){$page.="<br/>Новостей еще не было!<br/>";}
	else{
        $news=mysql_fetch_array($sql);
        	$page.="<br/><b>$news[who]</b> (".date("j.m.",$news["date"]).(date("Y",$news["date"])+170).date("  G:i",$news["date"]).")";
            $page.="<br/>$news[news]<br/>";
	}
	$page .= "<form action='login.php?do=login' method='post'>
      <br /> Логин<br />
      <input type='text' name='login' maxlength='30' value='$login' />
      <br /> Пароль<br />
        <input type='text' name='pass' maxlength='30' value='$pass' />
      <br /> <input type='checkbox' name='rememberme' value='yes' /> Запомнить?<br />
      <input type='submit' value='Войти' />
  </form>";

    $page.="<br/><img src='/img/icon/ok.PNG'/> <a href=\"./reg.php\">Регистрация</a>" ;
    $page.="<br/><img src='/img/icon/wat.PNG'/> <a href='./help.php?help=main'>Помощь</a>" ;
    $page.="<br/><img src='/img/icon/gun.PNG'/> Онлайн [<a href='./?do=online'>".online()."</a>]<br/><br/>";
	$page.="<script type='text/javascript' src='http://mobtop.ru/c/39347.js'></script><noscript><a href='http://mobtop.ru/in/39347'><img src='http://mobtop.ru/39347.gif' alt='MobTop.Ru - Рейтинг и статистика мобильных сайтов'/></a></noscript><br/>";
    $page.="<br/><p class='d'> N.E.S.S. (c) 2010-2012<br/>[<a href=\"./help.php?help=pravila\">Правила</a>] - [<a href=\"./kont.php\">Контакты</a>] </p>";

    display($page, "N.E.S.S. - постапокалиптическая RPG игра",$style);




?>