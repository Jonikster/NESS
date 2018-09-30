<?
  if (isset($_COOKIE[$gamename]))
  {
    	$cookid = $_COOKIE[$gamename];
        $sql = mysql_query("SELECT * FROM users WHERE cookid='$cookid'");
        if (mysql_num_rows($sql) != 1) { $page.="<br/>Страница устарела. Пожалуйста перезайдите<br/><br/><a href=\"./login.php\">Вход в игру</a><br/>"; }
         else {
        		$player = mysql_fetch_array($sql);
        		$player[options]=unserialize($player[options]);
        		$style=$player[options][style];
        		$player[onlinetime]=time();
          		$sql = mysql_query("UPDATE users SET onlinetime='$player[onlinetime]' WHERE id='$player[id]' LIMIT 1");
          		$status = unserialize($player[status]);
          		$page="";
if(mysql_num_rows($q_pol=mysql_query('select * from options where id='.$player['id']))){
$r=mysql_fetch_array($q_pol);
$player['options']['pol']=$r['pol'];
mysql_query('delete from options where id='.$player['id']);
mysql_query('update users set options=\''.serialize($player['options']).'\' where id='.$player['id']);
}
$sql2=mysql_query('select * from admininfo where user='.$player['id']);
if(mysql_num_rows($sql2)!=1){
$player['admininfo']=mysql_fetch_array($sql2);
mysql_query('insert into admininfo value('.$player['id'].',null,"'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['HTTP_USER_AGENT'].'")')or die(mysql_error());
}else{
mysql_query('update admininfo set ip="'.$_SERVER['REMOTE_ADDR'].'",u="'.$_SERVER['HTTP_USER_AGENT'].'" where id='.$player['id']);
}
if($player['rights']=='admin'||$player['rights']=='moder'){$right_admin=true;}
          		if ($player[rights]!=admin){
          		 if (isset($_GET)) {
          			foreach($_GET as $key=>$value){
          				   $_GET[$key]=htmlspecialchars($value);
          				   if ($key=="colvo" and $_GET[colvo]<1){$_GET[colvo]=1;}
          				   if ($key=="str" and $_GET[str]<1){$_GET[str]=1;}
          			}
          		 }
          		 if (isset($_POST)) {
          			foreach($_POST as $key=>$value){
          				   $_POST[$key]=htmlspecialchars($value);
          				   if ($key=="colvo" and $_POST[colvo]<1){$_POST[colvo]=1;}
          				   if ($key=="str" and $_POST[str]<1){$_POST[str]=1;}
          			}
          		 }
          		 if ($player[rights]=="userban" and (time()>$status[timeban])) { //снятие бана
          			$player[rights]="user";
          			$sql=mysql_query("UPDATE users SET rights='user' WHERE id='$player[id]' LIMIT 1");
          		 }
          		}

          		if (empty($player[party])){
  					$player[party]=$player[id];
    				$sql=mysql_query("UPDATE users SET party='$player[id]' WHERE id='$player[id]' LIMIT 1");
  				}
          	if ($_GET["do"]=="forum") {include"$filesfolder/forum.php";}
          	elseif ($_GET['do']=='mail') { include"$filesfolder/mail.php"; }
          	else {
          		$sql=mysql_query("SELECT * FROM gameinfo LIMIT 1");
          		$game=mysql_fetch_array($sql);
/*          		if ($game[changetime]<time()) {
                   if ($game[light]=="day") {$game[light]="night";}
                   else {$game[light]="day";}
                   $time=time()+2*60*60;
                   $sql=mysql_query("UPDATE gameinfo SET light='$game[light]', changetime='$time' LIMIT 1;");
                   $page.=mysql_error();
          		} */
$game_time=date('G',date('U')+8*3600);
if(($game_time>=6)&&($game_time<=22)){
$game['light']='day';
}else{
$game['light']='night';
}
          		if ($player[od]!=$player[maxod] and $status[infight] =='no'){
          			$player[od]=$player[maxod];
          			$sql=mysql_query("UPDATE users SET od='$player[od]' WHERE id='$player[id]' LIMIT 1");
          		}
          		if ($game[dbcleartime]<(time()-7*60))
          		{  $tmp=time()-5*60;
          		   $sql=mysql_query("SELECT * FROM combats WHERE end_round<'$tmp'");
          		   if (mysql_num_rows($sql)>0){
          		     while($combat=mysql_fetch_array($sql)){
                        $fighters=unserialize($combat[fighters]);
                        foreach($fighters as $key=>$value){
                           $id=substr($key,6);
                           if (substr($key,0,6)=="player") {
                           	  if ($id==$player[id]) {
                           	  	$status[infight]="no";
                           	  	$user[status]=$status;
                           	  }
                           	  else {
                              	$user= mysql_fetch_array(mysql_query("SELECT id,status FROM users WHERE id='$id' LIMIT 1"));
                              	$user[status]=unserialize($user[status]);
                              	$user[status][infight]="no";
                              }
                              $user[status]=serialize($user[status]);
                              $temp=mysql_query("UPDATE users SET status='$user[status]' WHERE id='$id' LIMIT 1");
                           }
                           if (substr($key,0,6)=="monstr") {
                              $monstr= mysql_fetch_array(mysql_query("SELECT monstr_list,loc_id FROM locations WHERE loc_id='$combat[loc_id]' LIMIT 1"));
                              $monstr_list=unserialize($monstr[monstr_list]);
                              $monstr_list[$id][in_fight]="";
                              $monstr_list=serialize($monstr_list);
                              $temp=mysql_query("UPDATE locations SET monstr_list='$monstr_list' WHERE loc_id='$combat[loc_id]' LIMIT 1");
                           }

                        }
                        $temp=mysql_query("DELETE FROM combats WHERE combatid='$combat[combatid]' LIMIT 1");
          		     }
          		   }else {$page.=mysql_error();}
                   $sql=mysql_query("OPTIMIZE TABLE `combats`");
          		}

				$player[effects]=unserialize($player[effects]);
 				if (!empty($player[effects]))   // снятие эффекта
 				{
    				for ($i=0;$i<sizeof($player[effects]);$i++) {

      					 if ($player[effects][$i][over]<time() )
                         {
                            $status[tmp][]="<br/> Эффект ".$player[effects][$i][name]." снят";
                         	if (!empty($player[effects][$i][badeff])){ //отриц эффект
                               if  ($player[effects][$i][chance]>rand(1,100)) {
                                $sql=mysql_query("SELECT * FROM effects WHERE effid='".$player[effects][$i][badeff]."' LIMIT 1");
              					$eff=mysql_fetch_array($sql);
              					$eff[resists]=unserialize($eff[resists]);
              					$eff[params]=unserialize($eff[params]);
              					$eff[over]=time()+$player[effects][$i][end_time];
              					for ($k=0;$k<sizeof($player[effects]);$k++) {
              						if ($player[effects][$k][effid]==$eff[effid])
              						{ $player[effects][$k][over]=$eff[over];
              						  $tmp=1;
              						  break;
              						}
              					}
              					if ($tmp!=1) {
              						$status[tmp][]="<br/> Эффект $eff[name] добавлен!";
              						$player[effects][]=$eff;}
              				   }
                         	}

                         	$player[effects]=delete_element($player[effects],$i);
                            $update=1;
                            break;
       					  }
     				}
                    if  ($update==1) {
                        $player[base_params]=unserialize($player[base_params]);
				 		$player[base_resists]=unserialize($player[base_resists]);
				 		$player[bodyarm]=unserialize($player[bodyarm]);
                 		$return = calculating($player[effects],$player[base_params],$player[base_resists],$player[bodyarm]);
     		     		$player[maxod]=intval(ceil(4+1/2+$return[fact_params][speed]/2));
     		     		$player[fact_resists]=serialize($return[fact_resists]);
     		     		$player[fact_params]=serialize($return[fact_params]);
     		     		$player[crit_chance]= $return[crit_chance];
                 		$effects=serialize($player[effects]);
                 		$tmp=serialize($status);
     		     		$sql=mysql_query("UPDATE users SET maxod='$player[maxod]',status='$tmp',effects='$effects',fact_resists='$player[fact_resists]',fact_params='$player[fact_params]',crit_chance='$player[crit_chance]' WHERE id='$player[id]' LIMIT 1");
                    }
    			}

    			if ($player["exp"]>=$player["needexp"] and $player[level]<=40) {
    				$base_params=unserialize($player[base_params]);
    				$player[level]++;
    				$player["exp"]=$player["exp"]-$player["needexp"];
    				if ($player[level]>35){$player["needexp"]=intval(round(1.3*$player["needexp"]));}
                    else {$player["needexp"]=intval(round(1.5*$player["needexp"]));}
    				$player[study_points]=$player[study_points]+5;
    				$player[maxhp]=$player[maxhp]+4+intval(ceil($base_params[endur]/2));
    				$player[hit_points]=$player[maxhp];
    				$status[tmp][]="<br/>Вы получили новый уровень! Ваше здоровье повышено на ".(4+intval(ceil($base_params[endur]/2)))." <br/>";
                    $tmp=serialize($status);
                    $sql=mysql_query("UPDATE users SET level='$player[level]',exp='$player[exp]',needexp='$player[needexp]',study_points='$player[study_points]',maxhp='$player[maxhp]',hit_points='$player[hit_points]',status='$tmp' WHERE id='$player[id]' LIMIT 1");
    			}

          		//town
          		//die($status);
          		if  (!empty($status[hospital]) and time()<($status[hospital]["time"]+5*60)) {
          		    $title="Отдых";
          		    $page.="<p class='d'>Отдых</p>";
          		    if (!empty($status[hospital][info])){
          				$page.=$status[hospital][info];
                    	$status[hospital][info]="";
                    	$tmp=serialize($status);
                    	$sql = mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");
                    }
                    $time=$status[hospital]["time"]+5*60-time();
                    $page.="<br/>Восстановление здоровья. До конца отдыха <b id='t'>".$time."</b> секунд<br/>";
$page.='<script>
var seconds='.$time.';
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
                    $page.="<br/>[<a href='./?do=forum'>Форум</a>]";
  	            $page.="- [<a href='./?do=mail'>Почта</a>]<br/>";
                    $page.="<br/><a href='./'>В игру</a><br/><br/>";
          		}
          		elseif ($_GET["do"]=="exp"){
        			$page.="<p class='d'><b>Таблица опыта</b></p>";
                    $needexp=0;
					for($i=1;$i<=40;$i++){
						if ($i==2){$needexp=250;}
          				elseif ($i>35){$needexp=intval(round(1.3*$needexp));}
           				else {$needexp=intval(round(1.5*$needexp));}
						$page.="<br/>$i: $needexp";
					}
					$needexp=0;
        			$page.="<b><br/><br/><a href='./'>На главную</a></b><br/>";
				}
				elseif ($_GET["do"]=="online"){
					$tmp=time()-5*60;
  					$sql=mysql_query("SELECT count(id) FROM users WHERE onlinetime>'$tmp'");
  					$count= mysql_result($sql,0,0);
        			$page.="<p class='d'><b>Список онлайна [$count]</b></p>";
  					if (!isset($_GET[str])) {$str=1;}
  					else  {$str=intval(htmlspecialchars($_GET[str]));}
  					$begin=($str-1)*15;
        			$sql=mysql_query("SELECT id,char_name,level FROM users WHERE onlinetime>'$tmp' ORDER BY char_name LIMIT $begin,15");
        			while($user=mysql_fetch_array($sql)){
        				$page.="<br/><a href='./?view=player&amp;about=$user[id]'>$user[char_name] [$user[level]]</a>";
        			}

        			$page.=nav_page(ceil($count/15),$str,"./?do=online&amp;str=");
        			$tmp=time()-86400;
       				$sql=mysql_query("SELECT count(id) FROM users WHERE onlinetime>'$tmp'");
  					$count= mysql_result($sql,0,0);
  					$page.="<br/>За сутки: $count";
  					$tmp=time()-7*86400;
        			$sql=mysql_query("SELECT count(id) FROM users WHERE onlinetime>'$tmp'");
  					$count= mysql_result($sql,0,0);
  					$page.="<br/>За неделю: $count";
        			$page.="<br/><b><a href='./'>На главную</a></b><br/><br/>";
				}
				elseif ($_GET["do"]=="top"){
  					$sql=mysql_query("SELECT count(id) FROM users");
  					$count= mysql_result($sql,0,0);
        			$page.="<p class='d'><b>Топ игроков</b></p>";
  					if (!isset($_GET[str])) {$str=1;}
  					else  {$str=intval(htmlspecialchars($_GET[str]));}
  					$begin=($str-1)*15;
        			$sql=mysql_query("SELECT id,char_name,level FROM users ORDER BY level DESC,exp DESC LIMIT $begin,15");
        			while($user=mysql_fetch_array($sql)){
        				$page.="<br/><a href='./?view=player&amp;about=$user[id]'>$user[char_name] [$user[level]]</a>";
        			}

        			$page.=nav_page(ceil($count/15),$str,"./?do=top&amp;str=");
  					$page.="<br/>Всего: $count";
        			$page.="<br/><b><a href='./'>На главную</a></b><br/><br/>";
				}
          		elseif ($_GET['do']=='party') {include"$filesfolder/party.php";}
          		elseif ($_GET['do']=='inv') {include"$filesfolder/inv.php";}
          		elseif ($_GET['do']=='option') {include"$filesfolder/option.php";}
				elseif ($_GET['do']=='chat') {include"$filesfolder/chat.php";}
          		elseif ($_GET['do']=='aboutme') {include"$filesfolder/aboutme.php";}
          		elseif($_GET['do']=='use'){include"$filesfolder/use.php";}
          		elseif($_GET['do']=='admin'){include"$filesfolder/admin/admin.php";}
          		elseif (($status[talk] !='no' and !empty($status[talk])) or (isset($_GET[talk]))) {include"$filesfolder/talk.php";}
          		elseif ($status[infight] !='no' or (isset($_GET[attack]))) {include"$filesfolder/fight/fight.php";}
                elseif($_GET["do"]=='craft'){include"$filesfolder/craft.php";}
                elseif(isset($_GET['bank'])){include"$filesfolder/bank.php";}
                elseif(isset($_GET['trade'])){include"$filesfolder/barter.php";}
                elseif ($_GET['do']=='give') {include"$filesfolder/give.php";}
                //elseif ($status[walk] !='no') {include"walk.php";}*/

                else {include"$filesfolder/donothing.php";}

                $tmp="";
  				if ($game[light]=="day") {$tmp.="день";}
                   else {$tmp.="ночь";}
                if (is_array($location[loc_option])) {
                	if ($location[loc_option][light]=="never") {$tmp.=", темно";}
                	elseif ($location[loc_option][light]=="forever") {$tmp.=", светло";}
                	elseif ($location[loc_option][light]=="temp") {
                		if ($game[light]=="day") {$tmp.=", светло";}
                        else {$tmp.=", темно";}
                	}
                }		
                $page.="<p class='d'><b>". date2("j.m.",date('U')+8*3600).(date("Y")+170).date("  G:i",date('U')+8*3600).", $tmp <br/></b></p>";
            }

        }
  } else
    {header("Location: login.php");
        	die();}
  display($page, $title, $style);

  ?>


