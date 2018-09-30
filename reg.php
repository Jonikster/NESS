<?php
include('lib.php');
$page="<p class='d'><b>N.E.S.S.</b><br/>постапокалиптическая RPG игра</p>" ;
if ($_GET["do"] == "register")
{
	extract($_POST);
  	$login=htmlspecialchars($login);
  	$pass=htmlspecialchars($pass);
  	$verpass=htmlspecialchars($verpass);
  	$email=htmlspecialchars($email);
  	$charname=htmlspecialchars($charname);
  		if ((preg_match("/[^A-z0-9]/", $login)) or (strlen($login)<3) or (strlen($login)>30)){$page.="<br/>Логин ДОЛЖЕН БЫТЬ от 3 до 30 символов ЛАТИНСКОГО АЛФАВИТА и/или ЦИФР!!!<br/><br/>";}
  		elseif (mysql_num_rows(mysql_query("SELECT login FROM users WHERE login='$login' LIMIT 1")) > 0) { $page.= "<br />Такой логин уже зарегистрирован. попробуйте еще раз<br />"; }
  		elseif (mysql_num_rows(mysql_query("SELECT char_name FROM users WHERE char_name='$charname' LIMIT 1")) > 0) { $page.= "<br />Такой ник уже зарегистрирован. попробуйте еще раз<br />"; }
  		elseif (preg_match("/[^A-z0-9]/", $pass) || (strlen($pass)<6) || (strlen($pass)>30)){$page.="<br/>Пароль ДОЛЖЕН БЫТЬ от 6 до 30 символов ЛАТИНСКОГО АЛФАВИТА и/или ЦИФР!!!<br/><br/>";}
  		elseif ($pass!=$verpass){$page.="<br/>Пароли не совпадают!<br/><br/>";}
  		elseif (!preg_match("/^[a-z0-9_-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|".
   				"edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-".
   				"9]{1,3}\.[0-9]{1,3})$/is", $email)){$page.="<br/>Это не email!<br/><br/>";}
  		elseif (mysql_num_rows(mysql_query("SELECT email FROM users WHERE email='$email' LIMIT 1")) > 0) { $page.= "<br />На этот email уже зарегистрировался кто-то. Попробуйте еще раз<br />"; }
  		elseif (preg_match("/[^A-z0-9]/", $charname)|| (strlen($pass)<3) || (strlen($charname)>15)){$page.="<br/>Имя персонажа ДОЛЖНО БЫТЬ от 3 до 15 символов ЛАТИНСКОГО АЛФАВИТА и/или ЦИФР!!!<br/><br/>";}
  		elseif (mysql_num_rows(mysql_query("SELECT char_name FROM users WHERE char_name='$lcharname' LIMIT 1")) > 0) { $page.= "Персонаж с таким именем уже зарегистрирован. Попробуйте еще раз<br />"; }
  		elseif (empty($login) or empty($pass) or empty($email) or empty($charname)){$page.="<br/>Вы заполнили не все поля!<br/><br/>";}
  		elseif ($verifity!=$summa ) {$page.="<br/>Введена не правильная сумма<br/><br/>";}
  	else {

    	$base_params = array("str" => 1,"life" => 1,"endur" => 1,"speed" => 1,"dex" => 1,"luck" => 1,"shooting" => 1, "int" => 1);
    	$base_params = serialize($base_params);

    	$skills = array("throwweapon"=> array("act"=> 0,"level"=> 0),"firewapon"=> array("act"=> 0,"level"=> 0),"coldweapon"=> array("act"=> 0,"level"=> 0),"trade"=> array("act"=> 0,"level"=> 0),"handfight"=> array("act"=> 0,"level"=> 0),"hack"=> array("act"=> 0,"level"=> 0),"per"=> array("act"=> 0,"level"=> 0),"weap"=> array("act"=> 0,"level"=> 0),"armer"=> array("act"=> 0,"level"=> 0),"chim"=> array("act"=> 0,"level"=> 0));
    	$skills = serialize($skills);
    	$trauma = array("lefthand"=> "", "righthand"=> "","leftleg"=> "", "rightleg"=> "","eye"=> "");
    	$trauma = serialize($trauma);
    	$base_resists= array("resnormal"=> 2,"resplazma"=> 2,"resboom"=> 2,"resvolt"=> 2,"respoison"=> 2,"resrad"=> 2);
    	$base_resists = serialize($base_resists);
    	$status=array("infight"=> 'no',"using"=>'no',"walk"=>'no');
    	$status = serialize($status);
    	$options[reg_data]=time();
    	$options['pol']=($_POST['pol']=='j'?'j':'m');
    	$options= serialize($options);
    	$pass=md5($pass);  		$sql="INSERT INTO users(login,email,char_name,pass,level,exp,needexp,hit_points,maxhp,rad_points,poison_points,hungry_points,crit_chance,od,maxod,base_params,fact_params,skills,trauma,money,fact_resists, loc_id,citizen,options, status,rights)".
  	              " VALUES ('$login','$email','$charname','$pass',1,0,250,40,40,0,0,0,2,5,5,'$base_params','$base_params','$skills','$trauma',0,'$base_resists','bartertown.main','bartertown.main','$options','$status','user');";
$result = mysql_query($sql);
mysql_query('insert into admininfo value(LAST_INSERT_ID(),"'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['HTTP_USER_AGENT'].'")');
if (!$result) { $page.=mysql_error()."<br/>Произошла какая то ошибка. Пожалуйста, повторите.<br/>";}   	 	else { $page.="<br/>Регистрация прошла успешно! Можете прочесть <a href='./help.php?help=novis'>пособие</a> для новичков или сразу войти в игру.<br/>";}
  	}
  $page .="<br/><a href=\"./reg.php\">Регистрация</a><br/><a href=\"./login.php\">Вход в игру</a><br/>";

} else {    $iamnotbot1=rand(1,5);
    $iamnotbot2=rand(1,5);
    $summa=$iamnotbot1 + $iamnotbot2;
	$page .= "<form action='reg.php?do=register' method='post'>
      <input type='hidden' name='summa' value='".$summa."' />
      <br/> Логин (Требуется только для входа в игру)
      <br/><input type='text' name='login' maxlength='30' value='' />
      <br/> Пароль
      <br/><input type='text' name='pass' maxlength='30' value='' />
      <br/> Повторите пароль
      <br/><input type='text' name='verpass' maxlength='30' value='' />
      <br/> E-mail
      <br/><input type='text' name='email' maxlength='30' value='$email' />
      <br/>Имя персонажа (Игровой ник, будет виден в игре)
      <br/><input type='text' name='charname' maxlength='30' value='$charname' />
<br>Пол: <select name='pol'><option value='m'>мужской</option><option value='j'>женский</option></select>
      <br/>Сосчитайте сколько будет: <br/>".$iamnotbot1." + ".$iamnotbot2." = ?
      <br/><input type='text' name='verifity' maxlength='3' value='' />
      <br/><input type='submit' value='Зарегистрироваться' /><br />
  </form>";
};
$page.="<a href='./'><br/>Главная</a><br/>";
$page.="<br/><p class='d'><b>".date2("j.m.",date('U')+8*3600).date("  G:i",date('U')+8*3600)."</b></p>";
display($page,'Регистрация',$style);


?>