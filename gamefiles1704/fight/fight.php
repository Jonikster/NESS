<?
  $title="Бой";

  $sql = mysql_query("SELECT * FROM locations WHERE loc_id='$player[loc_id]'");
  $location = mysql_fetch_array($sql);
  $location[loc_option]=unserialize($location[loc_option]);
  $monstr_list=unserialize($location[monstr_list]);
  $pageup.="<p class='d'><b>Бой</b></p>";
  if ($location[loc_option][fight] == 'no') {  	$pageinfo.="<br/>Здесь сражаться нельзя, защищённая территория!<br/>";
  	if ($status[infight]!="no"){  		$status[infight]="no";
        $status=serialize($status);
        $sql=mysql_query("UPDATE users SET status='$status' WHERE id='$player[id]' LIMIT 1");  	}
  	}
  else {
   if ($status[infight]!='no') {  //Если  игрок в бою
      include"$filesfolder/fight/init.php";   }

   if ($_GET[attack]=='monster' and $player[od]>0)
   {  include"$filesfolder/fight/monstrattack.php";}
   elseif ($_GET[attack]=='player' and $player[od]>0)   //атака на игрока
   {  include"$filesfolder/fight/playerattack.php";
   }
   else {

      $sql=mysql_query("SELECT * FROM combats WHERE loc_id='$player[loc_id]' AND combatid='$status[infight]' LIMIT 1 ");
      $combat=mysql_fetch_array($sql);
      $fighters=unserialize($combat[fighters]);
      if ($_GET['view']=='monstr') {
  	 	if (isset($_GET[id])) {
  	 		$id=$_GET[id];
        	$pagedown.="<br/><img src='/img/icon/cel.PNG'/><b>".$monstr_list[$id][name]."</b>
           <br/>".$monstr_list[$id][info]."
           <br/><img src='/img/icon/heal.PNG'/> Здоровье: ".$monstr_list[$id][hit_points]."/".$monstr_list[$id][maxhp]."
           <br/><img src='/img/icon/move.PNG'/> Очки действия: ".$monstr_list[$id][od]."/".$monstr_list[$id][maxod]."
           <br/><img src='/img/icon/gun.PNG'/> Урон: ".$monstr_list[$id][damage]."
           <br/>";
  	    }
      }


     if (!empty($fighters)){
      $pagedown.="<br/><img src='/img/icon/cel.PNG'/><b>Цели</b>";
      foreach ($fighters as $key=>$value) {
         if (substr($key,0,6)=="player"){
           $id=substr($key,6);
           if ($id!=$player[id]) {
           		$sql=mysql_query("SELECT char_name,hit_points,x,y,status FROM users WHERE id='$id' LIMIT 1");
           		$enemy=mysql_fetch_array($sql);                $enemy[status]=unserialize($enemy[status]);

         		$pagedown.="<br/>".$enemy[char_name];
          		$distance=distance($enemy[x],$enemy[y],$player[x],$player[y]);
          		if ($distance<=10) {$pagedown.=" [Вплотную]";}
          		elseif ($distance<=20) {$pagedown.=" [Близко]";}
          		elseif ($distance<=35) {$pagedown.=" [Вблизи]";}
          		elseif ($distance<=50) {$pagedown.=" [Недалеко]";}
          		elseif ($distance<=100) {$pagedown.=" [Далеко]";}
          		else {$pagedown.=" [Очень далеко]";}
          		
          		if ($fighters[$key][last_target]=="player".$player[id]){$pagedown.="[нападает на вас]";}
          		elseif ($enemy[status][infight]!="no"){$pagedown.="[в бою]";}
							$pagedown.="[<a href='./?do=use&amp;what=what&amp;target=player&amp;id=$id'>исп</a>]";
          		$pagedown.="[<a href='./?view=player&amp;id=$id'>инф</a>][<a href='./?attack=player&amp;id=$id'>бить</a>]";

           }
         }
      }
      $pagedown.="";
      foreach ($fighters as $key=>$value) {        $upd=0;
        if (substr($key,0,6)=="monstr"){
           $id=substr($key,6);

         		$pagedown.="<br/>".$monstr_list[$id][name];
          		$distance=distance($monstr_list[$id][x],$monstr_list[$id][y],$player[x],$player[y]);
          		if ($distance<=10) {$pagedown.=" [Вплотную]";}
          		elseif ($distance<=20) {$pagedown.=" [Близко]";}
          		elseif ($distance<=35) {$pagedown.=" [Вблизи]";}
          		elseif ($distance<=50) {$pagedown.=" [Недалеко]";}
          		elseif ($distance<=100) {$pagedown.=" [Далеко]";}
          		else {$pagedown.=" [Очень далеко]";}
          		if ($monstr_list[$id][in_fight]=="player".$player[id]){$pagedown.="[нападает на вас]";}
          		else if (!empty($monstr_list[$id][in_fight])){$pagedown.="[в бою]";}
          		if ($monstr_list[$id][status]=="dead"){$pagedown.="[труп]";}
          		$pagedown.="[<a href='./?do=use&amp;what=what&amp;target=monster&amp;id=$id'>исп</a>]";
          		$pagedown.="[<a href='./?view=monstr&amp;id=$id'>инф</a>][<a href='./?attack=monster&amp;id=$id'>бить</a>]";

        }
      }

     }




   }
 }
 if (isset($combat[round])) {$pageup.="<br/>Раунд: ".$combat[round]."<br/><img src='/img/icon/time.PNG'/> Время до конца раунда: <b id='t'>".($combat[end_round]-time())."</b> сек<br/>";
$pageup.='<script>
var seconds='.($combat[end_round]-time()).';
textNode=document.getElementById("t").firstChild;
function display(){
if(seconds>0){
seconds-=1;
textNode.nodeValue=seconds;
setTimeout("display()",1000);
}
}
display();
</script>';
}
 $pageup.="<br/><img src='/img/icon/heal.PNG'/> HP: ".$player[hit_points]."/".$player[maxhp]."<br/>
                <img src='/img/icon/move.PNG'/> ОД: ".$player[od]."/".$player[maxod]."<br/>";
 $pagedown.="<br/><br/>[<a href='./?do=combatlog'>Лог боя</a>]";
   $pagedown.=" - [<a href='./?do=aboutme'>Персонаж</a>]";
  $pagedown.=" - [<a href='./?do=inv'>Инвентарь</a>]";
  $pagedown.=" - [<a href='./?do=forum'>Форум</a>]";
  $pagedown.=" - [<a href='./?do=mail'>Почта</a>]";
  $pagedown.=' - [<a href="/?rnd='.rand(0,100).'">Обновить</a>]<br><br>';

 $page.=$pageup.$pageinfo."<br/>".$pagedown ;

 if ($_GET["do"]=="combatlog") { 	  $combatlog=unserialize($combat[combatlog]);
 	  $page="<br/><b>История боя</b><br/>";
 	  for ($i=0;$i<sizeof($combatlog);$i++){ 	  	$page.=$combatlog[$i]; 	  }
      $page.="<br/><br/><a href='./?rand=".rand(1,1000)."'>В игру</a><br/><br/>";
 }

   function union_combats($combat1,$combat2,$monstr_list){    //объединение боев
     if ($combat1[loc_id]==$combat2[loc_id]){
       foreach ($combat2[fighters] as $key=>$value){           $combat1[fighters][$key]=$combat2[fighters][$key];
           $type=substr($key,0,6);
           $id=substr($key,6);
           if ($type=="player"){
            $sql = mysql_query("SELECT status FROM users WHERE id='$id' LIMIT 1");
            $status=unserialize(mysql_result($sql,0,"status"));
            $status[infight]=$combat1[combatid];
            $status=serialize($status);
            $sql=mysql_query("UPDATE users SET status='$status' WHERE id='$id' LIMIT 1");           }
           if ($type=="monstr"){
            $monstr_list[$id][in_fight]= $combat1[combatid];
            $monstrupd=1;
           }       }
       if ($monstrupd==1){
            $tmp=serialize($monstr_list);
            $sql = mysql_query("UPDATE locations SET monstr_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");
       }
      $sql=mysql_query("DELETE from combats WHERE loc_id='$combat2[loc_id]' AND combatid='$combat2[combatid]' LIMIT 1");      $combat1[fighters]=serialize($combat1[fighters]);
      $sql=mysql_query("UPDATE combats SET fighters='$combat1[fighters]' WHERE loc_id='$combat1[loc_id]' AND combatid='$combat1[combatid]' LIMIT 1");     }
   }


?>