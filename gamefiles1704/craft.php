<?
   $recepts=unserialize($player[recepts]);
   $page.="<p class='d'><b>Рецепты</b></p>";
   $bag=unserialize($player[bag]);
   $player[skills]=unserialize($player[skills]);
   if (!is_array($recepts)) {$page.="<br/><img src='/img/icon/cancel.PNG'/> Рецептов крафта нет!<br/>";}
   else {

     if (isset($_GET[make])) {         if (!isset($recepts[$_GET[make]])) {$page.="<br/><img src='/img/icon/cancel.PNG'/> Вы не знаете такого!<br/>";}
         else {         	$sql=mysql_query("SELECT * FROM recepts WHERE id='$_GET[make]' LIMIT 1");
            $recept=mysql_fetch_array($sql);
            if ($player[skills][$recept[skill]][level]<$recept[levelskill])
            {$page.="<br/><img src='/img/icon/dei.PNG'/> Слишком низкий навык!<br/>";}
          else {
            $recept[components]=unserialize($recept[components]);
            $recept[result]=unserialize($recept[result]);
            foreach($recept[components] as $key=>$value)  // А все ли ингридиенты есть у игрока?
            {
            	if (have_item($bag,$key)<$value) {$error=1;$page.="<br/><img src='/img/icon/dei.PNG'/> Не хватает индигриендов!<br/>"; break;}
            }
            if ($error!=1) {                foreach($recept[components] as $key=>$value)  // удаление ингридиентов?
               { $return=add_to_inv ($key,(-1)*$value,$bag,$player[id],$player[gruz]);
                 $bag=$return[bag];
                 $player[gruz]=$return[gruz];
               }
                  foreach($recept[result] as $key=>$value)  // добавление результата
               { if ($value<1){$value=1;}
                 $return=add_to_inv ($key,$value,$bag,$player[id],$player[gruz]);
                 $bag=$return[bag];
                 $player[gruz]=$return[gruz];
               }
                 $tmp=serialize($bag);
                 $recepts[$_GET[make]]=$recepts[$_GET[make]]+1;
                 $tmp1=serialize($recepts);
                 $player[skills][$recept[skill]][act]++;
                 $tmp2=serialize($player[skills]);
                 $sql=mysql_query("UPDATE users SET bag='$tmp',skills='$tmp2',gruz='$player[gruz]',recepts='$tmp1' WHERE id='$player[id]' LIMIT 1");
                 $page.="<br/><img src='/img/icon/dei.PNG'/> Сделано!<br/>";            }
            $page.="<br/><a href='?do=craft&amp;make=$_GET[make]'><img src='/img/icon/dei.PNG'/> Сделать еще раз!</a><br/>";
           }         }     }   	  elseif (isset($_GET[id])) {         if (!isset($recepts[$_GET[id]])) {$page.="<br/><img src='/img/icon/cancel.PNG'/> Вы не знаете такого!<br/>";}
         else {            $sql=mysql_query("SELECT * FROM recepts WHERE id='$_GET[id]' LIMIT 1");
            $recept=mysql_fetch_array($sql);
            $page.="<br/><b>$recept[name]</b>";
            $page.="<br/>$recept[info]<br/>";
            $page.="<br/><img src='/img/icon/yad.PNG'/> Сделано: ".$recepts[$_GET[id]];
            $page.="<br/><img src='/img/icon/tre.PNG'/> Навык: ";
            if ($recept[skill]=="weap") {$page.="Мастер";}
            if ($recept[skill]=="armer") {$page.="Медик";}
            if ($recept[skill]=="chim") {$page.="Химик";}
            $page.="<br/><img src='/img/icon/p.PNG'/> Уровень навыка: $recept[levelskill]";
            $recept[components]=unserialize($recept[components]);
            foreach ($recept[components] as $key=>$value)   {                $where.=" id='$key' OR";            }
            $where=substr($where, 0, strlen($where)-2);
            $page.="<br/><img src='/img/icon/yad.PNG'/> <b>Компоненты</b>";
            $sql=mysql_query("SELECT id,name,info FROM items WHERE $where");
            while($item=mysql_fetch_array($sql)) {
                for ($i=0;$i<sizeof($bag);$i++){
                      if  ($bag[$i][id]==$item[id] and $bag[$i][name]==$item[name] and $bag[$i][info]==$item[info]){
                      	   if ($bag[$i][colvo]>=$recept[components][$item[id]]) {                              $have="yes";                      	   }
                      	   break;
                      }
                }
                $page.="<br/>$item[name] [".$recept[components][$item[id]]."]";
                if ($have=="yes")  {$page.="[есть]";}
                else  {$page.="[нету]";$havenot=1;}
                $have="";

              }  $page.="<br/>";
              $page.="<br/><img src='/img/icon/yad.PNG'/> <b>Результат</b>";
            $recept[result]=unserialize($recept[result]);
            $where="";
            foreach ($recept[result] as $key=>$value)   {
                $where.=" id='$key' OR";
            }
            $where=substr($where, 0, strlen($where)-2);
            $sql=mysql_query("SELECT id,name,info FROM items WHERE $where");
            while($item=mysql_fetch_array($sql)) {
                $page.="<br/>$item[name] [".$recept[result][$item[id]]."] ";
            }  $page.="<br/>";
            if ($player[skills][$recept[skill]][level]<$recept[levelskill])
            {$page.="<br/><img src='/img/icon/dei.PNG'/> Слишком низкий навык!<br/>";}
            elseif ($havenot==1){$page.="<br/><img src='/img/icon/dei.PNG'/> У вас не хватает компонентов!<br/>";}
            else {$page.="<br/><a href='?do=craft&amp;make=$_GET[id]'>Сделать!</a><br/>";}         }   	  }
   	  elseif (isset($_GET[skill])) {              foreach($recepts as $key=>$value){              	 $where.=" id='$key' OR";              }
              $where=substr($where, 0, strlen($where)-2);
              $sql=mysql_query("SELECT id,name,skill FROM recepts WHERE $where");
              while($recept=mysql_fetch_array($sql)) {
              	if ($recept[skill]==$_GET[skill]) {$page.="<br/><a href='./?do=craft&amp;id=$recept[id]'>$recept[name]</a>";$k++;}
              }
              if ($k<1) {$page.="<br/><img src='/img/icon/cancel.PNG'/> Вы не знаете рецепты данного типа!<br/>";}
              $page.="<br/>";   	  }      if ($_GET[skill]!="chim") {$page.="<br/><img src='/img/icon/yad.PNG'/> <a href='./?do=craft&amp;skill=chim'>Химические рецепты</a>";}
      if ($_GET[skill]!="armer") {$page.="<br/><img src='/img/icon/heal.PNG'/> <a href='./?do=craft&amp;skill=armer'>Медицинские рецепты</a>";}
      if ($_GET[skill]!="weap") {$page.="<br/><img src='/img/icon/gun.PNG'/> <a href='./?do=craft&amp;skill=weap'>Мастерские рецепты</a><br/>";}   };
   $page.="<br/><a href='./?rand=".rand(1,1000)."'>В игру</a><br/><br/>";
?>