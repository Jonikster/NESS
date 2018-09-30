<?
  $page.='<p class="d"><b>Настройки</b></p><br/>';
if($_POST['pol']=='m'||$_POST['pol']=='j'){
$player['options']['pol']=$_POST['pol'];
mysql_query('UPDATE users SET options=\''.serialize($player['options']).'\' WHERE id='.$player['id']);
$page.='<div class="d">Сохранено</div>';
}
$page.='<form method="post">Пол: <select name="pol">
<option value="m">М</option>
<option value="j">Ж</option>
</select<br><input type="submit" value="сохранить"></form>';
$page.="<a href='/?do=aboutme'>Персонаж</a>";
$page.='<br><a href="/">В игру</a><br><br>';