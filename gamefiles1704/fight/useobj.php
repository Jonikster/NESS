<?
         $page.="<p class='d'><b>".$location[obj_list][$_GET['var']][name]."<a href='./?view=obj&amp;var=$_GET[var]'>[inf]</a></b></p>";
         if ($location[obj_list][$_GET['var']][type]=="safe") {           if ( $location[obj_list][$_GET['var']][status][is_open]=="lock") {
           	     $page.="<br/>Невозможно открыть! Замок заклинило!<br/>";
           }
           elseif ( $location[obj_list][$_GET['var']][status][is_open]!="open") {
         	  if (($player[skill][hack][level]- $location[obj_list][$_GET['var']][status][hard]+50)>rand(1,100)) {                  $location[obj_list][$_GET['var']][status][is_open]="open";
                  $temp["exp"]=$location[obj_list][$_GET['var']][status][hard]+$player[level]*$player[skill][hack][level]+1;
                  $temp[skills][hack][act]=1;
                  add_user($player,$temp);
                  $location[obj_list][$_GET['var']][status][lasthack]=time();
                  $tmp=serialize($location[obj_list]);
                  $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");
                  $page.="<br/>Вы взломали замок!<br/>[получено $temp[exp] опыта]<br/>";         	  }
         	  else {$page.="<br/>Не  удалось взломать замок!";
                  if (rand(1,100)>($player[skill][hack][level]- $location[obj_list][$_GET['var']][status][hard]+50)) {                     $location[obj_list][$_GET['var']][status][is_open]="lock";
                     $location[obj_list][$_GET['var']][status][lasthack]=time();
                     $page.="<br/>Замок заклинило!";
                     $tmp=serialize($location[obj_list]);
                  	 $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");                  }
                  $page.="<br/>";
         	  }
           }

         }
         if ($location[obj_list][$_GET['var']][type]=="garbage" or ($location[obj_list][$_GET['var']][type]=="safe" and $location[obj_list][$_GET['var']][status][is_open]=="open") or $location[obj_list][$_GET['var']][type]=="bag") {
                $objbag=$location[obj_list][$_GET['var']][bag];
                if (empty($objbag)) {$page.="<br/>Объект пуст<br/>";}
                elseif (isset($_GET[take])){
                    $player[fact_params]=unserialize($player[fact_params]);
                	if ($objbag[$_GET[take]][colvo]>1) {
                		if (isset($_POST[colvo])) {
                			if ($_POST[colvo]> $objbag[$_GET[take]][colvo]) {$colvo=$objbag[$_GET[take]][colvo];}
                			else {$colvo=$_POST[colvo];}
                            if (($player[gruz]+$objbag[$_GET[take]][about_item][massa]*$colvo)>30*($player[fact_params][str])+100)
                    		{$page.="<br/>Вы это не унесете!<br/>";}
                            else {                            	$page.="<br/>Вы взяли $colvo штук ".$objbag[$_GET[take]][name]." <br/>";
                                if ($colvo<$objbag[$_GET[take]][colvo]){
                                	$objbag[$_GET[take]][colvo]=$objbag[$_GET[take]][colvo]-$colvo;
                                	$bag=unserialize($player[bag]);
                                	for ($i=0;$i<sizeof($bag);$i++){
                                		if ($bag[$i][id]==$objbag[$_GET[take]][id] and  $bag[$i][name]==$objbag[$_GET[take]][name] and  $bag[$i][info]==$objbag[$_GET[take]][info])
                                		{  $bag[$i][colvo]=$bag[$i][colvo]+$colvo;
                                           $doit="done";
                                           break;
                                		}
                                	}
                                	if ($doit!="done")
                                	{   $item=$objbag[$_GET[take]];
                                		$item[colvo]=$colvo;
                                		$bag[]=$item;
                                	}
                                	$player[gruz]=$player[gruz]+$objbag[$_GET[take]][about_item][massa]*$colvo;
                                	$tmp=serialize($bag);
                                	$sql=mysql_query("UPDATE users SET bag='$tmp', gruz='$player[gruz]' WHERE id='$player[id]' LIMIT 1");
                                    $location[obj_list][$_GET['var']][bag]=$objbag;
                                    $tmp=serialize($location[obj_list]);
                                    $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");
                                }
                                else {
                                	$bag=unserialize($player[bag]);
                                	for ($i=0;$i<sizeof($bag);$i++){
                                		if ($bag[$i][id]==$objbag[$_GET[take]][id] and  $bag[$i][name]==$objbag[$_GET[take]][name] and  $bag[$i][info]==$objbag[$_GET[take]][info])
                                		{  $bag[$i][colvo]=$bag[$i][colvo]+$colvo;
                                           $doit="done";
                                           break;
                                		}
                                	}
                                	if ($doit!="done")
                                	{   $item=$objbag[$_GET[take]];
                                		$item[colvo]=$colvo;
                                		$bag[]=$item;
                                	}

                                	$player[gruz]=$player[gruz]+$objbag[$_GET[take]][about_item][massa]*$colvo;
                                	$objbag=delete_element($objbag,$_GET[take]);
                                	$tmp=serialize($bag);
                                	$sql=mysql_query("UPDATE users SET bag='$tmp', gruz='$player[gruz]' WHERE id='$player[id]' LIMIT 1");
                                    $location[obj_list][$_GET['var']][bag]=$objbag;
                                    $tmp=serialize($location[obj_list]);
                                    $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");

                                }
                            }
                		}
                		else {
                        $page.="<form action='./?do=useobj&amp;var=$_GET[var]&amp;take=$_GET[take]' method='post'>
                        <br/> Введите количество
                        <br/><input type='text' name='colvo' value='".$objbag[$_GET[take]][colvo]."' />
                        <br/><input type='submit' value='Взять' />
                        </form>";
                        }
                	}
                	else {
                    	if (($player[gruz]+$objbag[$_GET[take]][about_item][massa])>30*($player[fact_params][str])+100)
                    	{$page.="<br/>Вы это не унесете!!!<br/>";}
                    	else {                    		$page.="<br/>Вы взяли ".$objbag[$_GET[take]][name]." <br/>";
                    		$bag=unserialize($player[bag]);
                            for ($i=0;$i<sizeof($bag);$i++){
                             if ($bag[$i][id]==$objbag[$_GET[take]][id] and  $bag[$i][name]==$objbag[$_GET[take]][name] and  $bag[$i][info]==$objbag[$_GET[take]][info])
                             {  $bag[$i][colvo]=$bag[$i][colvo]+1;
                                  $doit="done";
                                  break;
                             }
                            }
                            if ($doit!="done")
                            {   $item=$objbag[$_GET[take]];
                            $item[colvo]=1;
                            $bag[]=$item;
                            }
                             $player[gruz]=$player[gruz]+$objbag[$_GET[take]][about_item][massa];
                            $objbag=delete_element($objbag,$_GET[take]);

                            $tmp=serialize($bag);
                            $sql=mysql_query("UPDATE users SET bag='$tmp', gruz='$player[gruz]' WHERE id='$player[id]' LIMIT 1");
                            $location[obj_list][$_GET['var']][bag]=$objbag;
                            $tmp=serialize($location[obj_list]);
                            $sql=mysql_query("UPDATE locations SET obj_list='$tmp' WHERE loc_id='$player[loc_id]' LIMIT 1");


                    	}
                    }
                    $page.="<br/><a href='./?do=useobj&amp;var=$_GET[var]'>В ".$location[obj_list][$_GET['var']][name]."</a>";
                }
                else{
                    for($i=0;$i<sizeof($objbag);$i++){
                      	$page.="<br/><a href='./?do=useobj&amp;var=$_GET[var]&amp;take=$i'>".$objbag[$i][name];
     					if ($objbag[$i][colvo]>1) {$page.="[".$objbag[$i][colvo]."]";}
     					$page.="</a>";
                    }
                    $page.="<br/>";
                }
         }
         elseif ($location[obj_list][$_GET['var']][type]=="mine") {         if (!is_array($location[obj_list][$_GET['var']][status])) {$location[obj_list][$_GET['var']][status]=unserialize($location[obj_list][$_GET['var']][status]);}           $resurs=$location[obj_list][$_GET['var']][status][hard];
           if ((time()-$status[digtime])<30){$page.="<br/>Тайм аут 30 секунд!<br/>";}
           elseif ($_SERVER['REQUEST_URI']==$status[lasturl]) {$page.="<br/>Ошибка! Неуникальная страница! Хватит обновлять!<br/>";}
           else {
            if ($resurs==39 and substr($player[loc_id],0,13)=="sera.minemain"){            	$player[quests]=unserialize($player[quests]);
           	 if (abs($player[quests][getsera]["time"]-time())>30*60){           	 	$page.="<br/>Вы должны оплатить добычу серы!<br/>";
           	 	$notpay=1;           	 }            }
            if ($notpay!=1){               $rand=mt_rand(1,100);
               $skills=unserialize($player[skills]);
               if ($rand>50+$skills[dig][level]){               	$page.="<br/>Неудача! Добыть не удалось!<br/>";
               	$status[digtime]=time();
               	$tmp=serialize($status);
               	$sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");               }
               else{
               	  $sql=mysql_query("SELECT name FROM items WHERE id='$resurs' LIMIT 1");
               	  $name=mysql_result($sql,0,"name");                  $page.="<br/>Добыча удалась! $name добавлен в рюкзак!<br/>";
                  $temp[bag][0][id]=$resurs;
                  $temp[bag][0][colvo]=1;
                  $temp[skills][dig][act]=1;
                  add_user($player,$temp);
               	  $status[digtime]=time();
               	  $tmp="";
               	  $status[lasturl]=$_SERVER['REQUEST_URI'];
               	  $tmp=serialize($status);
               	  $sql=mysql_query("UPDATE users SET status='$tmp' WHERE id='$player[id]' LIMIT 1");               }            }
           }         }
$page.=" <br/><a href='./'>В игру</a><br/><br/>";


?>