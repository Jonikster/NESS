<?  $title="Разговор";
    $sql = mysql_query("SELECT obj_list FROM locations WHERE loc_id='$player[loc_id]' LIMIT 1");
  	$obj_list=unserialize(mysql_result($sql,0,"obj_list"));
  	$bag=unserialize($player[bag]);
  	$player[base_params]=unserialize($player[base_params]);
  	$player[quests]=unserialize($player[quests]);
  	$base_params=$player[base_params];
  	$player[base_resists]=unserialize($player[base_resists]);
  	$base_resists=$player[base_resists];
  if ($status[talk] =='no' or empty($status[talk])) {      if (isset($_GET[talk])){
  			if (empty($obj_list[$_GET[talk]][status][talkid])) {$end=1;$zagolovok="Ошибка при попытке разговора 1!";}
  			else {
  				$tmp=array("objid"=>$_GET[talk],"talkid"=>$obj_list[$_GET[talk]][status][talkid],"epicid"=>"begin");
                $status[talk]=$tmp;
                $sql = mysql_query("SELECT * FROM talk WHERE talkid='".$status[talk][talkid]."' LIMIT 1");
                $talk=mysql_fetch_array($sql);
                $dialog=unserialize($talk[dialog]);
                if (empty($dialog["begin"])) {$end=1;$zagolovok="Ошибка";$reply=$status[talk][talkid]." Ошибка при попытке разговора 2!";}
                else {                  $tmp=serialize($status);
                  $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");                }  			}      }
  }
  else {     $sql = mysql_query("SELECT * FROM talk WHERE talkid='".$status[talk][talkid]."' LIMIT 1");
     if (mysql_num_rows($sql)!=1) {$end=1;$zagolovok="Ошибка";$reply="Ошибка при попытке разговора 3!";}
     $talk=mysql_fetch_array($sql);
     $dialog=unserialize($talk[dialog]);  }
  	$reply=$dialog[$status[talk][epicid]][reply];
    $zagolovok=$obj_list[$status[talk][objid]][name];

   if (isset($_GET[select])) {
       $if=0;           eval($dialog[$status[talk][epicid]][variants][$_GET[select]]["if"]);

        if ($if>0) { // проверка      	 //Есть ли новый эпизода  в диалоге      	 
		if (isset($dialog[$dialog[$status[talk][epicid]][variants][$_GET[select]]["to"]])) {            
		$status[talk][epicid]= $dialog[$status[talk][epicid]][variants][$_GET[select]]["to"];
            $reply=$dialog[$status[talk][epicid]][reply];
            $zagolovok=$obj_list[$status[talk][objid]][name];
            if  ($status[talk][epicid]=="end") {$status[talk]="no";$end=1;}
            $tmp=serialize($status);
            $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
            eval($dialog[$status[talk][epicid]][on_enter]); // выполнение кода
         }
         else { //в случае ошибки выкидывает из диалога         	
		 $status[talk]= "no";
            $tmp=serialize($status);
            $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
            $end=1;  }
      }      } 


   //if  ($end==1) {$zagolovok="Конец диалога";}

   $page.="<p class='d'>".$zagolovok."</p>";
   $page.="<br/>".$reply."<br/>";
   if  ($status[talk][epicid]=="trade") {   	$page.="<br/><a href='./?trade=buy&amp;npc=".$status[talk][objid]."'>Купить</a>";
   	$page.="<br/><a href='./?trade=sell&amp;npc=".$status[talk][objid]."'>Продать</a>";
   	$status[talk]="no";
   	$tmp=serialize($status);
    $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
   	$end=1;
   }
   elseif  ($status[talk][epicid]=="bank") {
   	$page.="<br/><a href='./?bank=from'>Забрать</a>";
   	$page.="<br/><a href='./?bank=to'>Положить</a>";
   	$status[talk]="no";
   	$tmp=serialize($status);
    $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
   	$end=1;
   }
    if  ($end==1 or $status[talk][epicid]=="end") {       $page.="<br/><a href='./'>В игру</a>";    }
    else {
     for ($i=0;$i<sizeof($dialog[$status[talk][epicid]][variants]);$i++) {
        $if=0;     	eval($dialog[$status[talk][epicid]][variants][$i]["if"]);
        if ($if>0){      $page.="<br/><a href='./?talk=".$status[talk][objid]."&amp;select=$i'>".$dialog[$status[talk][epicid]][variants][$i][text]."</a>";}     }
    }
    $page.="<br/><br/>";
?>