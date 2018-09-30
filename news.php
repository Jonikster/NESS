<?
        $title="Новости";
        include "lib.php"; if(isset($_COOKIE[$gamename])){
$cookid=$_COOKIE[$gamename];
$sql=mysql_query("SELECT * FROM users WHERE cookid='$cookid'");
if(mysql_num_rows($sql)!=1){$page.="<br/>Неправильные печеньки=)). Пожалуйста почистите печеньки<br/><br/><a href=\"./login.php\">Вход в игру</a><br/>";
}else{
$player = mysql_fetch_array($sql);
$player[options]=unserialize($player[options]);
$style=$player[options][style];
}
}
$_GET['str']=$_GET['str']<=1?1:$_GET['str'];
  		$sql=mysql_query("SELECT count(date) FROM news");
  		$count= mysql_result($sql,0,0);
  		if (isset($_GET[str])){$_GET[str]=htmlspecialchars($_GET[str]);}
  		else {$_GET[str]=1;}
  		$begin=($_GET[str]-1)*10;
        $page.="<p class='d'><b>Новости</b></p>";
        $sql=mysql_query("SELECT * FROM news ORDER BY date DESC LIMIT $begin,10");
        while($news=mysql_fetch_array($sql)){
        	$page.="<br/><b>$news[who]</b> (".date("j.m.",$news["date"]).(date("Y",$news["date"])+170).date("  G:i",$news["date"]).")";
            $page.="<br/>$news[news]<br/>";
        } //$page.="<br/>";
        $page.=nav_page(ceil($count/10),$_GET[str],"./news.php?str=")."<br/>";
        $page.="<p class='d'><b><a href='./'>На главную</a></b></p>";
        display($page,$title,$style);