<?php
include('lib.php');
 $page.="<p class='d'><b>N.E.S.S.</b><br/>постапокалиптическая RPG игра</p>";
 $page.="По вопросам сотрудничества связаться с нами можно следующими способами:<br/>Почта: john.berden4@gmail.com<br/>Skype: volibor4444<br/>";
     if ($_GET[kont]==infSleem)  
        {
        $page.="<br/><p class='d'><b>Михаил Никифоров</b></p>";
        $page.="Игровой ник: StalkerSleem";
        $page.="<br/>Почта: hren_ego_znaet@bk.ru";
        $page.="<br/>Skype: StalkerSleem1";
        $page.="<br/>QIP: 467 357 125";
		$page.="<br/><br/><a href='./kont.php?kont=main'>Назад</a><br/>";
        }
elseif ($_GET[kont]==infhunter)  
        {
        $page.="<br/><p class='d'><b>Швыдкой Олег</b></p>";
        $page.="Игровой ник: hunter";
        $page.="<br/>Почта: Shvydkij94@ukr.net";
        $page.="<br/>Skype: -----";
		$page.="<br/><br/><a href='./kont.php?kont=main'>Назад</a><br/>";
        }
		elseif ($_GET[kont]==jonikster)  
        {
        $page.="<br/><p class='d'><b>Константин Рыжиков</b></p>";
        $page.="Игровой ник: Jonikster";
        $page.="<br/>Почта: john.berden4@gmail.com";
        $page.="<br/>Skype: volibor4444";
		$page.="<br/><br/><a href='./kont.php?kont=main'>Назад</a><br/>";
        }
elseif ($_GET[kont]==infMIXER)  
        {
        $page.="<br/><p class='d'><b>Дмитрий Михайличенко</b></p>";
        $page.="Игровой ник: MIXER";
        $page.="<br/>Почта: -----";
        $page.="<br/>Skype: -----";
		$page.="<br/><br/><a href='./kont.php?kont=main'>Назад</a><br/>";
        }
elseif ($_GET[kont]==infymnik)  
        {
        $page.="<br/><p class='d'><b>Витя Клестов</b></p>";
        $page.="Игровой ник: ymnik";
        $page.="<br/>Почта: -----";
        $page.="<br/>Skype: ymnik995";
		$page.="<br/><br/><a href='./kont.php?kont=main'>Назад</a><br/>";
        }
else{
   $page.="<br/><img src='/img/icon/p.PNG'/> <b>Над проектом работали:</b>";
   $page.="<br/>Михаил Никифоров (StalkerSleem) [Администратор][<a href='./kont.php?kont=infSleem'>инф</a>]";
   $page.="<br/>Константин Рыжиков (jonikster) [Старший программист проекта][<a href='./kont.php?kont=jonikster'>инф</a>]";
   $page.="<br/>Швыдкой Олег (hunter) [Младший администратор][<a href='./kont.php?kont=infhunter'>инф</a>]";
   $page.="<br/>Дмитрий Михайличенко (MIXER) [Основатель][<a href='./kont.php?kont=infMIXER'>инф</a>]";
   $page.="<br/>Витя Клестов (ymnik) [Помощь в устранении ошибок][<a href='./kont.php?kont=infymnik'>инф</a>]<br/>";
   }

   $page.="<br/><img src='/img/icon/p.PNG'/> <b>Так же благодарность тестировщикам:</b>";
   $page.="<br/> FLESH, WOLFMAN, PEKCAP, WinstoN, stalk, Hacky, Boz<br/>";
   $page.="<br/>";
   $page.="<p class='d'><a href='./'>На главную</a></p>";
display($page,'Контакты',$style);
?>