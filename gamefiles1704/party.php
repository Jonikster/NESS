<?
  $page.="<p class='d'>Пати</p>";
  $count=mysql_result(mysql_query("SELECT count(id) FROM users WHERE party='$player[party]'"),0,0);
  if ($player[party]==$player[id]) {      $page.="<br/>Глава: Вы";  }
  else{
  	$sql=mysql_query("SELECT id,char_name,status FROM users WHERE id='$player[party]' LIMIT 1");
  	$user=mysql_fetch_array($sql);
  	$user[status]=unserialize($user[status]);
  	$page.="<br/>Глава: <a href='./?view=player&amp;id=$user[id]'>$user[char_name]</a>";
  	if ($user[status][infight]!="no"){$page.="[В бою]";}
  }
  $page.="<br/>";

  if (isset($_GET[goaway])) {
     if ($_GET[goaway]=="self"){        if ($player[id]!=$player[party]){              	$player[party]=$player[id];
    			$sql=mysql_query("UPDATE users SET party='$player[id]' WHERE id='$player[id]' LIMIT 1");
    	}
    	if($count>1){    		$sql=mysql_query("SELECT id,level FROM users WHERE party='$player[id]' and id!='$player[id]' ORDER by level LIMIT 1");
    		$newlider=mysql_fetch_array($sql);            $sql=mysql_query("UPDATE users SET party='$newlider[id]' WHERE party='$player[id]' and id!='$player[id]'");
  			$count=mysql_result(mysql_query("SELECT count(id) FROM users WHERE party='$player[party]'"),0,0);
    	}
        $page.="<br/>Вы покинули пати!";     }     elseif ($player[id]!=$player[party]) {$page.="<br/> Из пати выгонять может только ее глава";}
     else {        $sql=mysql_query("SELECT party,char_name,status FROM users WHERE id='$_GET[goaway]' LIMIT 1");
        $user=mysql_fetch_array($sql);
        if ($user[party]!=$player[id]) {$page.="<br/> $user[char_name] не в вашей пати!";}
        else {        	$page.="<br/>Вы выгнали $user[char_name] из вашей пати!";        	$user[status]=unserialize($user[status]);        	$user[status][tmp][]="<br/>$player[char_name] выгнал вас из пати!";
        	$user[status]=serialize($user[status]);        	$sql=mysql_query("UPDATE users SET status='$user[status]',party='$_GET[goaway]' WHERE id='$_GET[goaway]' LIMIT 1");        }     }  }
  if (isset($_GET[invite])) {
     if ($player[id]!=$player[party]) {$page.="<br/> В пати может приглашать только ее глава";}
     else {
        $sql=mysql_query("SELECT party,char_name,status,loc_id FROM users WHERE id='$_GET[invite]' LIMIT 1");
        $user=mysql_fetch_array($sql);
        if ($user[party]==$player[id]) {$page.="<br/> $user[char_name] уже в вашей пати!";}
        if ($user[loc_id]!=$player[loc_id]) {$page.="<br/> $user[char_name] в другой локации!";}
        else {
        	$page.="<br/>Вы пригласили $user[char_name] в вашу пати!";
        	$user[status]=unserialize($user[status]);
        	$user[status][inviteparty]=$player[id];
        	//"<br/>[char_name] пригласил вас в пати! [<a href='./?do=party&amp;welcome=ok'>ok</a>][<a href='./?do=party&amp;welcome=cancel'>X</a>]";
        	$user[status]=serialize($user[status]);
        	$sql=mysql_query("UPDATE users SET status='$user[status]' WHERE id='$_GET[invite]' LIMIT 1");
        }
     }
  }
  if (isset($_GET[welcome])){      if (empty($status[inviteparty])){$page.="<br/>Приглашений в пати нет";}
      else {      	if ($_GET[welcome]=="ok")
      	{      	 $sql=mysql_query("SELECT char_name,party FROM users WHERE id='$status[inviteparty]' LIMIT 1");         $user=mysql_fetch_array($sql);
         $page.="<br/>Вы присоединились к пати $user[char_name]";
         $player[party]=$user[party];
         if ($count>1) {
         $sql=mysql_query("UPDATE users SET party='$player[party]' WHERE party='$player[id]'");}
         else{$sql=mysql_query("UPDATE users SET party='$status[inviteparty]' WHERE id='$player[id]'");}
        }
        elseif ($_GET[welcome]=="cancel")
      	{
      	 $sql=mysql_query("SELECT char_name,status FROM users WHERE id='$status[inviteparty]' LIMIT 1");
         $user=mysql_fetch_array($sql);
         $page.="<br/>Вы отказались от пати с $user[char_name]";
         $user[status]=unserialize($user[status]);
         $user[status][tmp][]="<br/>$player[char_name] отказался от пати с Вами";
         $user[status]=serialize($user[status]);
         $sql=mysql_query("UPDATE users SET $status='$user[status]' WHERE id='$status[inviteparty]' LIMIT 1");
        }
         $status=unset_as_mass($status,"inviteparty");
         $tmp=serialize($status);
         $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");      }  }
  if (!is_int($_GET[str])){$_GET[str]=1;}
  $begin=($_GET[str]-1)*10;
  if ($count<2) {$page.="<br/>В вашей пати никого нет";}
  else {
   $sql=mysql_query("SELECT id,char_name,status FROM users WHERE party='$player[party]' ORDER by char_name LIMIT $begin,10");
   while($user=mysql_fetch_array($sql)) {  	   if ($user[id]!=$player[party]){  		 $user[status]=unserialize($user[status]);
         $page.="<br/>";
  		 if ($user[id]==$player[id]){$page.="[Вы]";}
         $page.="<a href='./?view=player&amp;id=$user[id]'>$user[char_name]</a>";
         if ($user[status][infight]!="no"){$page.="[В бою]";}
         if (time()<($user[status][hospital]["time"]+5*60)){$page.="[отдых]";}
         if ($player[party]==$player[id]) {$page.=" [<a href='./?do=party&amp;goaway=$user[id]'>x</a>]";}
       }   }
   if ($count>=10){$page.=nav_page(ceil($count/10), $_GET[str],"./?do=party&amp;str=");}
  }
  $page.="<br/>";
  $page.="<br/><a href='./?do=party&amp;goaway=self'>Покинуть пати</a>";
  $page.="<br/><a href='./'>В игру</a><br/><br/>";
?>