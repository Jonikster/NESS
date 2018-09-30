<?  if ($player[rights]!="admin"){die($goawayfuckingcheater);}
   if (isset($_GET[del])){
 	 	if (isset($_POST[confirm])){
 	 		$sql=mysql_query("DELETE FROM items WHERE id='$_GET[del]' LIMIT 1");
 	 		$page.="<br/>Предмет  с id $_GET[del] был успешно уничтожен<br/>";
 	 		$page.="<br/><a href='./?do=admin&amp;mod=items'>К списку вещей</a>";
        }
        else {
 	 	$page.="<form action='./?do=admin&amp;mod=items&amp;del=$_GET[del]' method='post'>
 	 	 <br/>Вы уверены?
         <br/><input type='submit' name='confirm' value='Да' />
		 </form>";}
 }
 elseif (isset($_POST[found])){
 	 	if (isset($_POST[id])) {
        $sql=mysql_query("SELECT id, type, name FROM items WHERE id='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Предмет с id $_POST[found] не найден<br/>";}
         else  {
          	$items=mysql_fetch_array($sql);
			$page.="[$items[id]]- [$items[type]] - [$items[name]]".
       		"<a href='./?do=admin&amp;mod=items&amp;redact=$items[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=items&amp;del=$items[id]'>[X]</a><br/>";
    	 }
    	}
 	 	elseif (isset($_POST[name])) {
 	 	$sql=mysql_query("SELECT id, type, name FROM items WHERE name='$_POST[found]' LIMIT 1");
        if (mysql_num_rows($sql) != 1) {$page.="<br/>Вещь $_POST[found] не найдена<br/>";}
          else  {
          	$items=mysql_fetch_array($sql);
			$page.="[$items[id]]- [$items[type]] - [$items[name]]".
       		"<a href='./?do=admin&amp;mod=items&amp;redact=$items[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=items&amp;del=$items[id]'>[X]</a><br/>";
    	 }
 	 	}
 	 	$page.="<br/><a href='./?do=admin&amp;mod=items'>К списку вещей</a>";
 }
 elseif (isset($_GET[redact])) { 		if (isset($_POST[type])){ 			if ($_POST[type]=="weapon") {
 				$about_item=array("req_level"=>$_POST[req_level],"calibr"=>$_POST[calibr],"maxpatrons"=>$_POST[maxpatrons],"arrayfire"=>$_POST[arrayfire],
	 	    	"type_weap"=>$_POST[type_weap],"type_dmg"=>$_POST[type_dmg],"mindmg"=>$_POST[mindmg],"maxdmg"=>$_POST[maxdmg],"odbonus"=>$_POST[odbonus],
	 	    	"critbonus"=>$_POST[critbonus],"sniperbonus"=>$_POST[sniperbonus],"crash"=>$_POST[crash],"cena"=>$_POST[cena],
	 	    	"massa"=>$_POST[massa]);
	 	    }
	 	    elseif ($_POST[type]=="patron") {
                $about_item=array("calibr"=>$_POST[calibr],"moddmg"=>$_POST[moddmg],"cena"=>$_POST[cena],"massa"=>$_POST[massa]);
	    	}
	    	elseif ($_POST[type]=="misc") {
                $about_item=array("on_use"=>$_POST[on_use],"cena"=>$_POST[cena],"massa"=>$_POST[massa]);
	    	}
	    	elseif ($_POST[type]=="medicament") {
                $about_item=array("type"=>$_POST[typemed],"param"=>$_POST[param],"value"=>$_POST[value],"cena"=>$_POST[cena],"massa"=>$_POST[massa]);
	    	}
	 	    elseif ($_POST[type]=="bodyarm") {                $about_item=array("req_level"=>$_POST[req_level],"resnormal"=>$_POST[resnormal],"resplazma"=>$_POST[resplazma],
	 			"resboom"=>$_POST[resboom],"resvolt"=>$_POST[resvolt], "respoison"=>$_POST[respoison],"resrad"=>$_POST[resrad],"bonusdex"=>$_POST[bonusdex], "kb"=>$_POST[kb],"cena"=>$_POST[cena],"massa"=>$_POST[massa]);	 	    }
	        $about_item=serialize($about_item);
	        $sql = mysql_query("UPDATE items SET name='$_POST[name]', info='$_POST[info]',about_item='$about_item' WHERE id='$_GET[redact]' LIMIT 1");
            $page.="Предмет $_POST[name] с id $_GET[redact] изменен";
            $page.="<br/><a href='./?do=admin&amp;mod=items&amp;redact=$_GET[redact]'>Изменить $_POST[name]</a>"; 		}
 		else{         $sql=mysql_query("SELECT * FROM items WHERE id='$_GET[redact]'");
         $item=mysql_fetch_array($sql);
         $about_item=unserialize($item[about_item]);
         if ($item[type]=="weapon") {         	$page.="<br/><form action='./?do=admin&amp;mod=items&amp;redact=$_GET[redact]' method='post'>
        	<input type='hidden' name='type'  value='weapon' />
        	<br />ID оружия $item[id]
        	<br />Название<br /><input type='text' name='name'  value='$item[name]' />
        	<br />Краткое описание<br/><textarea name='info' rows='3'  value=''>$item[info]</textarea>
        	<br />Требуемый уровень<br /><input type='text' name='req_level'  value='$about_item[req_level]' />
        	<br />Калибр<br /><input type='text' name='calibr'  value='$about_item[calibr]' />
        	<br />Емкость обоймы<br /><input type='text' name='maxpatrons'  value='$about_item[maxpatrons]' />
        	<br />Патронов в очереди<br /><input type='text' name='arrayfire'  value='$about_item[arrayfire]' />

        	<br />Тип оружия
        	<br /><input type='radio' name='type_weap' value='melee'";
        	if ($about_item[type_weap]=="melee"){$page.=" checked='' ";}
        	$page.="/> Ближнего боя
        	<br /><input type='radio' name='type_weap' value='fire'";
        	if ($about_item[type_weap]=="fire"){$page.=" checked='' ";}
        	$page.="/> Дальнего боя
        	<br /><input type='radio' name='type_weap' value='throw'";
        	if ($about_item[type_weap]=="throw"){$page.=" checked='' ";}
        	$page.="/> Метательное

        	<br />Тип урона
        	<br /><input type='radio' name='type_dmg' value='normal'";
        	if ($about_item[type_dmg]=="normal"){$page.=" checked='' ";}
        	$page.="/> Нормальный
        	<br /><input type='radio' name='type_dmg' value='plazma'";
        	if ($about_item[type_dmg]=="plazma"){$page.=" checked='' ";}
        	$page.="/> Плазма
        	<br /><input type='radio' name='type_dmg' value='boom'";
        	if ($about_item[type_dmg]=="boom"){$page.=" checked='' ";}
        	$page.="/> Взрыв
        	<br /><input type='radio' name='type_dmg' value='volt'";
        	if ($about_item[type_dmg]=="volt"){$page.=" checked='' ";}
        	$page.="/> электричество
        	<br />Мин. урон <br /><input type='text' name='mindmg'  value='$about_item[mindmg]' />
        	<br />Макс. урон<br /><input type='text' name='maxdmg'  value='$about_item[maxdmg]' />
        	<br />Бонус ОД<br /><input type='text' name='odbonus'  value='$about_item[odbonus]' />
        	<br />Бонус на крит<br /><input type='text' name='critbonus'  value='$about_item[critbonus]' />
        	<br />Бонус точности<br /><input type='text' name='sniperbonus'  value='$about_item[sniperbonus]' />
        	<br />Вероятность осечки<br /><input type='text' name='crash'  value='$about_item[crash]' />
        	<br />Цена<br /><input type='text' name='cena'  value='$about_item[cena]' />
        	<br />Вес<br /><input type='text' name='massa'  value='$about_item[massa]' />
        	<br /><input type='submit' value='Изменить' /><br />
        	</form><br />";
         }
         elseif ($item[type]=="bodyarm"){            $page.="<br/><form action='./?do=admin&amp;mod=items&amp;redact=$_GET[redact]' method='post'>
            <input type='hidden' name='type'  value='bodyarm' />
            <br />ID брони $item[id]
        	<br />Название<br /><input type='text' name='name'  value='$item[name]' />
        	<br />Краткое описание<br/><textarea name='info' rows='3'  value=''>$item[info]</textarea>
        	<br />Требуемый уровень<br /><input type='text' name='req_level'  value='$about_item[req_level]' />
        	<br />Сопротивление урону
        	<br />Нормальному<br/><input type='text' name='resnormal'  value='$about_item[resnormal]' />
        	<br />Плазме<br/><input type='text' name='resplazma'  value='$about_item[resplazma]' />
        	<br />Взрывам<br/><input type='text' name='resboom'  value='$about_item[resboom]' />
        	<br />Электричеству<br/><input type='text' name='resvolt'  value='$about_item[resvolt]' />
        	<br />Отравлению<br/><input type='text' name='respoison'  value='$about_item[respoison]' />
        	<br />Радиации<br/><input type='text' name='resrad'  value='$about_item[resrad]' />
        	<br />Доп. шанс уворота<br/><input type='text' name='bonusdex'  value='$about_item[bonusdex]' />
        	<br />Коэффициент брони<br/><input type='text' name='kb'  value='$about_item[kb]' />
        	<br />Цена<br /><input type='text' name='cena'  value='$about_item[cena]' />
        	<br />Вес<br /><input type='text' name='massa'  value='$about_item[massa]' />
        	<br /><input type='submit' value='Изменить' /><br />
        	</form><br />";         }
         elseif ($item[type]=="patron"){
            $page.="<br/><form action='./?do=admin&amp;mod=items&amp;redact=$_GET[redact]' method='post'>
            <input type='hidden' name='type'  value='patron' />
        	<br />Название<br /><input type='text' name='name'  value='$item[name]' />
        	<br />Краткое описание<br/><textarea name='info' rows='3'  value=''>$item[info]</textarea>
        	<br />Калибр<br/><input type='text' name='calibr'  value='$about_item[calibr]' />
        	<br />Добавочный урон<br/><input type='text' name='moddmg'  value='$about_item[moddmg]' />
        	<br />Цена<br /><input type='text' name='cena'  value='$about_item[cena]' />
        	<br />Вес<br /><input type='text' name='massa'  value='$about_item[massa]' />
        	<br /><input type='submit' value='Изменить' /><br />
       		</form><br />";
         }
         elseif ($item[type]=="misc"){
            $page.="<br/><form action='./?do=admin&amp;mod=items&amp;redact=$_GET[redact]' method='post'>
            <input type='hidden' name='type'  value='misc' />
        	<br />Название<br /><input type='text' name='name'  value='$item[name]' />
        	<br />Краткое описание<br/><textarea name='info' rows='3'  value=''>$item[info]</textarea>
        	<br />Цена<br /><input type='text' name='cena'  value='$about_item[cena]' />
        	<br />Вес<br /><input type='text' name='massa'  value='$about_item[massa]' />
        	<br />При использовании<br/><textarea name='on_use' rows='3' cols='30' value=''>$about_item[on_use]</textarea>
        	<br /><input type='submit' value='Изменить' /><br />
       		</form><br />";
         }
         elseif ($item[type]=="medicament"){
            $page.="<br/><form action='./?do=admin&amp;mod=items&amp;redact=$_GET[redact]' method='post'>
            <input type='hidden' name='type'  value='medicament' />
        	<br />Название<br /><input type='text' name='name'  value='$item[name]' />
        	<br />Краткое описание<br/><textarea name='info' rows='3'  value=''>$item[info]</textarea>
        	<br />Тип препарата
        	<br /><input type='radio' name='typemed' value='const'";
        	if ($about_item[type]=="const") {$page.=" checked='' ";}
        	$page.=" /> В единицах
        	<br /><input type='radio' name='typemed' value='procent' ";
        	if ($about_item[type]=="procent") {$page.=" checked='' ";}
        	$page.=" /> В процентах
       		<br /><input type='radio' name='typemed' value='full' ";
       		if ($about_item[type]=="full") {$page.=" checked='' ";}
       		$page.=" /> Сверхнормы
        	<br />Что восстанавливает?
        	<br /><input type='radio' name='param' value='hungry' ";
        	if ($about_item[param]=="hungry") {$page.=" checked='' ";}
        	$page.=" /> Голод
        	<br /><input type='radio' name='param' value='hit_points' ";
        	if ($about_item[param]=="hit_points") {$page.=" checked='' ";}
        	$page.=" /> Здоровье
        	<br /><input type='radio' name='param' value='rad' ";
        	if ($about_item[param]=="rad") {$page.=" checked='' ";}
        	$page.=" /> Радиация
        	<br /><input type='radio' name='param' value='effect' ";
        	if ($about_item[param]=="effect") {$page.=" checked='' ";}
        	$page.=" /> Эффект
        	<br /><input type='radio' name='param' value='poison' ";
        	if ($about_item[param]=="poison") {$page.=" checked='' ";}
        	$page.=" /> Отравление
        	<br />Значение<br /><input type='text' name='value'  value='$about_item[value]' />
        	<br />Цена<br /><input type='text' name='cena'  value='$about_item[cena]' />
        	<br />Вес<br /><input type='text' name='massa'  value='$about_item[massa]' />
        	<br /><input type='submit' value='Изменить' /><br />
       		</form><br />";
         }
        }
         $page.="<br/><a href='./?do=admin&amp;mod=items'>К списку вещей</a>"; }

 elseif ($_GET["new"]=="weap"){ 	 if (isset($_POST[name])) {	 	$about_item=array("req_level"=>$_POST[req_level],"calibr"=>$_POST[calibr],"maxpatrons"=>$_POST[maxpatrons],"arrayfire"=>$_POST[arrayfire],
	 	    "type_weap"=>$_POST[type_weap],"type_dmg"=>$_POST[type_dmg],"mindmg"=>$_POST[mindmg],"maxdmg"=>$_POST[maxdmg],"odbonus"=>$_POST[odbonus],
	 	    "critbonus"=>$_POST[critbonus],"sniperbonus"=>$_POST[sniperbonus],"crash"=>$_POST[crash],"cena"=>$_POST[cena],
	 	    "massa"=>$_POST[massa]);
	    $about_item=serialize($about_item);

     	$sql=mysql_query("INSERT INTO items (type,name,info,about_item) VALUES ('weapon','$_POST[name]','$_POST[info]','$about_item');");
     	$page.="<br/>Оружие $_POST[name] добавлено<br/>";
 	 }
 	 else{     $page.="<br/><form action='./?do=admin&amp;mod=items&amp;new=weap' method='post'>
        <br />Название<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br />Требуемый уровень<br /><input type='text' name='req_level'  value='' />
        <br />Калибр<br /><input type='text' name='calibr'  value='' />
        <br />Емкость обоймы<br /><input type='text' name='maxpatrons'  value='' />
        <br />Патронов в очереди<br /><input type='text' name='arrayfire'  value='' />
        <br />Тип оружия
        <br /><input type='radio' name='type_weap' value='melee'/> Ближнего боя
        <br /><input type='radio' name='type_weap' value='fire'/> Дальнего боя
        <br /><input type='radio' name='type_weap' value='throw'/> Метательное
        <br />Тип урона
        <br /><input type='radio' name='type_dmg' value='normal'/> Нормальный
        <br /><input type='radio' name='type_dmg' value='plazma'/> Плазма
        <br /><input type='radio' name='type_dmg' value='boom'/> Взрыв
        <br /><input type='radio' name='type_dmg' value='volt'/> электричество
        <br />Мин. урон <br /><input type='text' name='mindmg'  value='' />
        <br />Макс. урон<br /><input type='text' name='maxdmg'  value='' />

        <br />Бонус ОД<br /><input type='text' name='odbonus'  value='' />
        <br />Бонус на крит<br /><input type='text' name='critbonus'  value='' />
        <br />Бонус точности<br /><input type='text' name='sniperbonus'  value='' />
        <br />Вероятность осечки<br /><input type='text' name='crash'  value='' />
        <br />Цена<br /><input type='text' name='cena'  value='' />
        <br />Вес<br /><input type='text' name='massa'  value='' />
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
     }
     $page.="<br/><a href='./?do=admin&amp;mod=items'>К списку вещей</a>"; }
  elseif ($_GET["new"]=="patron"){
 	  if (isset($_POST[name])) {
	 	$about_item=array("calibr"=>$_POST[calibr],"moddmg"=>$_POST[moddmg],"cena"=>$_POST[cena],"massa"=>$_POST[massa]);
	    $about_item=serialize($about_item);
     	$sql=mysql_query("INSERT INTO items (type,name,info,about_item) VALUES ('patron','$_POST[name]','$_POST[info]','$about_item');");
     	$page.="<br/>Патрон $_POST[name] добавлен<br/>";
	  }
	  else{
      $page.="<br/><form action='./?do=admin&amp;mod=items&amp;new=patron' method='post'>
        <br />Название<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br />Калибр<br/><input type='text' name='calibr'  value='' />
        <br />Добавочный урон<br/><input type='text' name='moddmg'  value='' />
        <br />Цена<br /><input type='text' name='cena'  value='' />
        <br />Вес<br /><input type='text' name='massa'  value='' />
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
      }
      $page.="<br/><a href='./?do=admin&amp;mod=items'>К списку вещей</a>";
 }
 elseif ($_GET["new"]=="arm"){ 	  if (isset($_POST[name])) {
	 	$about_item=array("req_level"=>$_POST[req_level],"resnormal"=>$_POST[resnormal],"resplazma"=>$_POST[resplazma],
	 	"resboom"=>$_POST[resboom],"resvolt"=>$_POST[resvolt],"respoison"=>$_POST[respoison],"resrad"=>$_POST[resrad],"bonusdex"=>$_POST[bonusdex], "kb"=>$_POST[kb], "cena"=>$_POST[cena],"massa"=>$_POST[massa]);
	    $about_item=serialize($about_item);
     	$sql=mysql_query("INSERT INTO items (type,name,info,about_item) VALUES ('bodyarm','$_POST[name]','$_POST[info]','$about_item');");
     	$page.="<br/>Броня $_POST[name] добавлена<br/>";
	  }
	  else{
      $page.="<br/><form action='./?do=admin&amp;mod=items&amp;new=arm' method='post'>
        <br />Название<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br />Требуемый уровень<br /><input type='text' name='req_level'  value='' />
        <br />Сопротивление урону
        <br />Нормальному<br/><input type='text' name='resnormal'  value='' />
        <br />Плазме<br/><input type='text' name='resplazma'  value='' />
        <br />Взрывам<br/><input type='text' name='resboom'  value='' />
        <br />Электричеству<br/><input type='text' name='resvolt'  value='' />
        <br />Отравлению<br/><input type='text' name='respoison'  value='' />
        <br />Радиации<br/><input type='text' name='resrad'  value='' />
        <br />Доп. шанс уворота<br/><input type='text' name='bonusdex'  value='' />
        <br />Коэффициент брони<br/><input type='text' name='kb'  value='' />
        <br />Цена<br /><input type='text' name='cena'  value='' />
        <br />Вес<br /><input type='text' name='massa'  value='' />
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
      }
      $page.="<br/><a href='./?do=admin&amp;mod=items'>К списку вещей</a>";
 }
  elseif ($_GET["new"]=="misc"){
 	  if (isset($_POST[name])) {
	 	$about_item=array("on_use"=>$_POST[on_use],"cena"=>$_POST[cena],"massa"=>$_POST[massa]);
	    $about_item=serialize($about_item);
     	$sql=mysql_query("INSERT INTO items (type,name,info,about_item) VALUES ('misc','$_POST[name]','$_POST[info]','$about_item');");
     	$page.="<br/>Патрон $_POST[name] добавлен<br/>";
	  }
	  else{
      $page.="<br/><form action='./?do=admin&amp;mod=items&amp;new=misc' method='post'>
        <br />Название<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br />Цена<br /><input type='text' name='cena'  value='' />
        <br />Вес<br /><input type='text' name='massa'  value='' />
        <br />При использовании<br/><textarea name='on_use' rows='3' cols='30'  value=''></textarea>
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
      }
      $page.="<br/><a href='./?do=admin&amp;mod=items'>К списку вещей</a>";
 }
  elseif ($_GET["new"]=="medicament"){
 	  if (isset($_POST[name])) {
	 	$about_item=array("type"=>$_POST[type],"param"=>$_POST[param],"value"=>$_POST[value],"cena"=>$_POST[cena],"massa"=>$_POST[massa]);
	    $about_item=serialize($about_item);
     	$sql=mysql_query("INSERT INTO items (type,name,info,about_item) VALUES ('medicament','$_POST[name]','$_POST[info]','$about_item');");
     	$page.="<br/>Препарат $_POST[name] добавлен<br/>";
	  }
	  else{
      $page.="<br/><form action='./?do=admin&amp;mod=items&amp;new=medicament' method='post'>
        <br />Название<br /><input type='text' name='name'  value='' />
        <br />Краткое описание<br/><textarea name='info' rows='3'  value=''></textarea>
        <br />Тип препарата
        <br /><input type='radio' name='type' value='const'/> В единицах
        <br /><input type='radio' name='type' value='procent'/> В процентах
        <br /><input type='radio' name='type' value='full'/> Сверхнормы
        <br />Что восстанавливает?
        <br /><input type='radio' name='param' value='hungry'/> Голод
        <br /><input type='radio' name='param' value='hit_points'/> Здоровье
        <br /><input type='radio' name='param' value='rad'/> Радиация
        <br /><input type='radio' name='param' value='poison'/> Отравление
        <br /><input type='radio' name='param' value='effect'/> Эффект
        <br />Значение<br /><input type='text' name='value'  value='' />
        <br />Цена<br /><input type='text' name='cena'  value='' />
        <br />Вес<br /><input type='text' name='massa'  value='' />
        <br /><input type='submit' value='Добавить' /><br />
        </form><br />";
      }
      $page.="<br/><a href='./?do=admin&amp;mod=items'>К списку вещей</a>";
 }
  else{$count= mysql_result(mysql_query("SELECT COUNT(id) FROM items"),0,0);
    if ($count<=10) {
      	 $sql=mysql_query("SELECT id,type,name FROM  items LIMIT 10");

         $page.="<br/>[id] - [type] - [name] <br/><br/>";
          while($items = mysql_fetch_array($sql))
  			{
    			$page.="[$items[id]]- [$items[type]] - [$items[name]]".
       			 "<a href='./?do=admin&amp;mod=items&amp;redact=$items[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=items&amp;del=$items[id]'>[X]</a><br/>";
   			}
     }
    else {
      		if (!isset($_GET['str'])) {$num_page=1;} else {$num_page=$_GET['str'];}
      		$num_page=intval($num_page);

      		$temp=($num_page-1)*10;
      		if ($temp>$count) {$page.="<br/>Столько вещей не существует!!<br/>";}
            else {
            	$sql=mysql_query("SELECT id, type, name FROM items LIMIT ".$temp.", 10");
            	$page.="<br/>[id] - [name]<br/><br/>";
            	while($items = mysql_fetch_array($sql))
  			 	{
				 $page.="[$items[id]]- [$items[type]] - [$items[name]]".
       			 "<a href='./?do=admin&amp;mod=items&amp;redact=$items[id]'>[ИЗМ]</a><a href='./?do=admin&amp;mod=items&amp;del=$items[id]'>[X]</a><br/>";
   			    }
  			 	$page.=nav_page(intval(ceil($count/10)), $num_page, "./?do=admin&amp;mod=items&amp;str=");
               }

    }
       $page.="<br/><form action='./?do=admin&amp;mod=items' method='post'>
       <input type='text' name='found'  value='' />
       <br /><input type='submit' name='id' value='Найти по ID' />
       <input type='submit' name='name' value='Найти по имени' /><br />
       </form>";
    $page.="<br/><a href='./?do=admin&amp;mod=items&amp;new=weap'>Добавить оружие</a>";
    $page.="<br/><a href='./?do=admin&amp;mod=items&amp;new=arm'>Добавить броню</a>";
    $page.="<br/><a href='./?do=admin&amp;mod=items&amp;new=misc'>Добавить фигню</a>";
    $page.="<br/><a href='./?do=admin&amp;mod=items&amp;new=patron'>Добавить патрон</a>";
    $page.="<br/><a href='./?do=admin&amp;mod=items&amp;new=medicament'>Добавить лекарство</a><br/>";
  }

?>