<? $goawayfuckingcheater="GO AWAY FUCKING CHEATER!!!";
   if ($player[rights]!="admin"){die($goawayfuckingcheater);}
   $page.="<p class='d'><b>Админка </b><a href='./?do=admin&amp;mod=about'>[инф]</a></p>";

   if ($_GET['mod']=='about'){$page.="<br/>Святая святых, от сюда можно управлять игровым миром NESSа. Ты это читаешь? А как ты сюда попал?! Доступ сюда только для администратора. Попрошу покинуть данную страницу и сообщить администратору как ты сдесь оказался(ась)<br/><br/>";}
   elseif ($_GET['mod']=='code'){
    if (isset($_POST['program'])){$program=$_POST['program'];eval($program);$page .= "<br/>Код выполнен.<br/>";};
    $page .= "<form action='?do=admin&amp;mod=code' method='post'>
             <br/> Напишите код
             <br/><textarea name='program' rows='7' cols='50' >$program</textarea>

      		 <br/><input type='submit' value='Выполнить' /><br />
  			 </form>";
   }
   elseif ($_GET['mod']=='addnews')  {
     if (isset($_POST['news'])){
     	$sql=mysql_query("INSERT INTO news(date,news,who) VALUES('".time()."','$_POST[news]','Администратор')");
     	$page .= "<br/>Новость добавлена.<br/>";
     	};
    $page .= "<form action='?do=admin&amp;mod=addnews' method='post'>
             <br/> Новость
             <br/><textarea name='news' rows='7' cols='50' >$program</textarea>

      		 <br/><input type='submit' value='Добавть' /><br />
  			 </form>";
   }
   elseif ($_GET['mod']=='players')  {  include"$filesfolder/admin/players.php";   }
   elseif ($_GET['mod']=='locations')  {  include"$filesfolder/admin/locations.php";   }
   elseif ($_GET['mod']=='monst')  {  include"$filesfolder/admin/monsters.php";   }
   elseif ($_GET['mod']=='items')  {  include"$filesfolder/admin/items.php";   }
   elseif ($_GET['mod']=='eff')  {  include"$filesfolder/admin/effects.php";   }
   elseif ($_GET['mod']=='dialog')  {  include"$filesfolder/admin/dialogs.php";   }
   elseif ($_GET['mod']=='craft')  {  include"$filesfolder/admin/recepts.php";   }
   elseif ($_GET['mod']=='quest')  {  include"$filesfolder/admin/quests.php";   }
   else {
   $page.="<br/><a href='./?do=admin&amp;mod=code'>Выполнить код</a><br/>
        <a href='./?do=admin&amp;mod=players'>Игроки</a><br/>
        <a href='./?do=admin&amp;mod=locations'>Локации</a><br/>
        <a href='./?do=admin&amp;mod=monst'>Монстры</a><br/>
        <a href='./?do=admin&amp;mod=items'>Вещи</a><br/>
        <a href='./?do=admin&amp;mod=eff'>Эффекты</a><br/>
        <a href='./?do=admin&amp;mod=dialog'>Диалоги</a><br/>
        <a href='./?do=admin&amp;mod=craft'>Рецепты</a><br/>
        <a href='./?do=admin&amp;mod=quest'>Квесты</a><br/>
        <a href='./?do=admin&amp;mod=addnews'>Добавить новость</a><br/>";
   }
   $page.="<br/><a href='./?do=admin'>Админка</a><br/><br/><a href='./'>В игру</a><br/><br/>";
   $title='Админка';
?>