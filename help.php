<?
 $title="Помощь";
 include "lib.php";
 if(isset($_COOKIE[$gamename])){
$cookid=$_COOKIE[$gamename];
$sql=mysql_query("SELECT * FROM users WHERE cookid='$cookid'");
if(mysql_num_rows($sql)!=1){$page.="<br/>Страница устарела. Пожалуйста перезайдите<br/><br/><a href=\"./login.php\">Вход в игру</a><br/>";
}else{
$player = mysql_fetch_array($sql);
$player[options]=unserialize($player[options]);
$style=$player[options][style];
}
}
 if ($_GET[help]=="main" or !isset($_GET[help]))  { 	 $page.="<p class='d'><b>Помощь</b></p>";
      $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=info'>Об игре</a>"; 
     $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=novis'>Пособие для новичков</a>";   
	 $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=pravila'>Правила игры</a><br/>";
 	 $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=newlevel'>Повышение уровня</a>";     
	 $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=params'>Параметры</a>";
     $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=resist'>Сопротивления</a>";
     $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=skills'>Навыки</a>";
     $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=eff'>Эффекты</a>";
     $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=inv'>Инвентарь</a>";
     $page.="<br/><img src='/img/icon/arrow.PNG'/> <a href='./help.php?help=sml'>Смайлики</a>";
     $page.="<br/><br/>"; }
 else {
  if ($_GET[help]==params)  {     $page.="<p class='d'><b>Параметры</b></p>";
     $page.="<br/><img src='/img/icon/tre.PNG'/> <b>Параметры</b> - повышаются за <a href='./help.php?help=newlevel'>очки обучения</a>, повышение определённых параметров даёт определённое преимущество перед противником. Максимальное число параметра 40, для скорости и живучести максимум настигает на 20. Распределение очков обучение непосредственно проходят в характеристиках игрока (Персонаж - Характеристики)<br/>
	 <img src='/img/img/help_params.PNG'/>";
     $page.="<br/><b>Cила</b> - Отвечает за урон в ближнем бою и на <a href='./help.php?help=inv'>грузоподьемность</a>.";
     $page.="<br/><b>Живучесть</b> - Каждый пункт добавляет по 2 ко всем сопротивлениям и к жизням в зависимости от выносливости.";
     $page.="<br/><b>Выносливость</b> - Повышает прирост здоровья при поднятии уровня, по формуле 4+выносливость/2.";
     $page.="<br/><b>Скорость</b> - Отвечает за очки действия (ОД). За каждые 2 единицы скорости +1 к ОД.";
     $page.="<br/><b>Ловкость</b> - Повышает шанс уворота на 2% за единицу ловкости";
     $page.="<br/><b>Удача </b> - Влияет на шанс критического попадания и уменьшает шанс критического от противника.";
     $page.="<br/><b>Меткость </b> - Повышает шанс попадания из огнестрельного оружия на 2% за единицу меткости, а так же шанс критического попадания на 1%.";
     $page.="<br/><b>Интеллект </b> - Повышает получаемый боевой <a href='./?do=exp'>опыт</a> за бой на 10% за каждый пункт.";
  }
  elseif ($_GET[help]=='pravila')  {
   $page.="<p class='d'><b>Правила</b></p>";
 $page.="<br/>Запрещается: <br/>
 - Регистрировать одним игроком более одного игрового персонажа с целью получения игрового преимущества и/или увеличения благосостояния иных своих игровых персонажей или игровых персонажей третьих лиц (мультоводство). <br/>
 - Использовать сторонние программные средства для улучшения/автоматизации игровых возможностей (боты, читерство). <br/>
 - Использовать в имени (нике) игрового персонажа ненормативную лексику, оскорбления, а также регистрировать имена (ники) затрагивающие политику, разжигающие межнациональную рознь, являющиеся ссылками на другие интернет-ресурсы. Также, во избежание путаницы среди остальных игроков, ники игровых персонажа не должны быть схожими с именами (никами) используемыми администрацией игры. <br/>
 - Унижать и порочить честь и достоинство Администрации игры, Модераторов, других Игроков и третьих лиц вне Игры. <br/>
 - Нарушать и пренебрегать правилами поведения в чате (систематические, целенаправленные нарушения). <br/>
 - Использовать программные ошибки Игры (баги), передавать информацию об их наличии третьим лицам, за исключением Администрации. <br/>
 - Посылать, передавать, воспроизводить или распространять любым способом полученное посредством игры программное обеспечение или другие материалы, полностью или частично, защищенные авторскими или другими правами, без разрешения владельца или законного правообладателя. <br/>
 - Распространять информацию откровенно сексуального характера (в разнообразии ее форм изложения и распространения), не предназначенную для публичного доступа лиц, не достигших совершеннолетнего возраста. <br/>
 - Совершать или подстрекать третьих лиц к совершению действий, запрещенных действующим законодательством, в том числе связанных с незаконным оборотом наркотических средств, террористической деятельностью, призывами к свержению законно избранной власти, порнографией, любыми формами дискриминации по половому, возрастному, религиозному или иным признакам. <br/>
 - Передавать реквизиты своего персонажа (логин, пароль) другим игрокам, как безвозмездно, так и за игровые или реальные деньги. <br/>
 - Продавать и покупать вещи, артефакты, ресурсы, игровую валюту из игры за реальные деньги. <br/>
 <br/>
 Примечание: <br/>
 - За эти и другие нарушения правил игры Игроку (пользователю) может быть незамедлительно, без какого-либо предварительного уведомления, отказано в услугах по предоставлению сервиса игры или такие услуги могут быть ограничены полностью или в части. <br/>
 - Администрация управляет и администрирует игру исключительно по своему усмотрению. <br/>
 - Использование сервисов игры производится Игроком (пользователем) исключительно самостоятельно и по принципу «как-есть», то есть администрация игры не отвечает перед игроком ни при каких обстоятельствах за любой прямой и/или косвенный ущерб, которые может быть у игрока в связи с получением сервиса игры или невозможностью такой сервис получить. <br/>
 <br/>
 - Настоящие правила могут быть изменены Администрацией игры без какого-либо предварительного уведомления. Игрок обязуется проверять данные правила на предмет изменений не менее чем один раз в семь дней. В случае, если такой проверки не будет в указанные сроки или после ознакомления с правилами (новой редакцией правил) игрок продолжает пользоваться сервисом игры, считается, что игрок ознакомлен и согласен с правилами (новой редакцией правил). <br/>
 <br/>
Правила форума и радисвязи. <br/>
 - В игре работают обычные правила вежливости, принятые в обществе. Форум - публичное место. То, что невежливо говорить и делать в публичных местах, запрещено на форуме. <br/>
 <br/>
 Игрокам запрещено: <br/>
 - Мат, оскорбительные высказывания в адрес других людей или компаний, травля людей.  <br/>
 - Подстрекательство и провокация ссор, свар, ругани, наездов и т.п. <br/>
 - Беспредметный флуд, когда человек постоянно отмечается во всех темах подряд, бессмысленно и неприкольно. <br/>
 - Публикация на форуме материалов, содержащих: порно, обнажённую натуру, сцены насилия, садо-мазо или просто вызывающих сильные отрицательные эмоции. <br/>
 - Пропаганда насилия, фашизма, разжигание межнациональной розни. <br/>
 - Пропаганда и рассказы о любых наркотиках в любой форме (текст, картинки) <br/>
 - Запрещена публикация материалов и линков связанных с кряками, варезом, ботами, эксплойтами и прочим, что может быть отнесено к нечестной игре или нарушению прав игроков и разработчиков/издателей игр и иного софта, или нарушает существующее законодательство.  <br/>
 <br/>
 Так же запрещена:  <br/>
 - Коммерческая деятельность, коммерческая реклама а так же действия, противоречащие интересам Администрации. <br/>
 - Навязчивая реклама любых коммерческих и некоммерческих проектов. <br/>
 - Обсуждение сторонних онлайн-игр и их реклама. <br/>
 - Некропостинг (поднятие очень старых тем) не приветствуется. Бессмысленный некропостинг сразу нескольких тем - запрещен. <br/>
 - Поднятие старой темы с целью задать осмысленный вопрос по теме обсуждения - разрешено (только для не флудильных тем). <br/>
 - Писать капсом (ПРИМЕР), заборчиком (ПрИмЕр), создавать темы или посты с очень длинным названием. которое не умещается на экране игроков с телефона. <br/>
 - Запрещена публикация материалов нарушающие авторские права. Публикация материалов без разрешения правообладателя. Публикация приватной информации и фотографий/картинок без прямого разрешения владельца приватной информации и лица, изображенного на фотографии/картинке. Публикация информации из закрытых источников, не предполагающих распространение информации. <br/>
  <br/>
 Запрещается: <br/>
 - публично обсуждать действия модераторов и/или администрации, спорить с модератором. <br/>
 Примечание: Если модератор участвует в дискуссии наравне со всеми посетителями, то спорить и дискутировать с ним в данной теме можно. <br/>
 - прямо или завуалированно оскорблять модераторов. <br/>
 - обсуждать действия временно отключенных (забаненных) участников, которые в данный момент не могут ответить. <br/>
 - помещать сообщения от имени забаненных. <br/>
  <br/>
  Примечание: <br/>
 - В случае нарушения применяется система предупреждений и банов. <br/>
 - Если забаненный посетитель создает новый аккаунт, он (новый аккаунт) может быть забанен без предупреждений, и все посты с нового аккаунта могут быть удалены. <br/>
 <br/>
 Про новичков: <br/>
 - Новичкам всячески помогать.  <br/>
 - На их тему не шутить, не подкалывать, не доказывать свое превосходство и крутизну. <br/>
 - Если Вам нечего сказать новичку - не говорите ничего. Не надо писать в тему с вопросом новичка абы что, лишь бы отметиться. <br/>
 - Любой спам в темах новичков категорически запрещён (новичками считаются игроки до 10 уровня включительно) <br/>
 <br/>
 Модераторы: <br/>
 - В случае удаления поста обязательно на месте удалёного поста писать следующие: Пост удалён мною по такой то причине. <br/>
 - Если модератор кого-то банит, он обязан отписать в том разделе, где писал проштрафившийся о факте и причинах бана. <br/>
 - Воздерживаться от удаления тем целиком. <br/>
 - Не удалять посты без крайней на то нужды. <br/>
 - Не допускайте травлю одного человека толпой. <br/>
 - Не позволяйте превращать форум в помойку. <br/>
 - Не позволяйте хамам садиться на шею вам и обществу. <br/>
 - Не решайте за людей, что им нужно, а что нет. <br/>
  <br/>
  Модераторы имею право: <br/>
 - выносить предупреждения пользователям, не соблюдающим правила форума. <br/>
 - банить пользователей, которым недостаточно предупреждений.  <br/>
  <br/>
  Примечание: <br/>
 - Да бы модератору не объяснять полную причину бана или удаления поста, прибегать к обозначению пункта нарушения. <br/>
 n.1 - нанормативная лексика - предупреждение, при периодическом и неоднократном нарушений данного пункта возможен бан. <br/>
 n.2 - оскорбление представителей администрации - любое оскорбление модераторов, будь оно даже выражено нормативной лексикой, наказывается баном на сутки и более. Возможно предупреждение, при примирении сторон (так же, хочу отметить, что модераторы, в свою очередь должны быть терпимее и здраво мыслить! а не видеть в любом обращении оскорбления) <br/>
 n.3 - оскорбление администрации - наказывается баном пожизненно, без возможности возврата в игру. Так же, на усмотрение администрации бан может иметь не пожизненный характер (исключения всегда бывают, мало кого бес попутал) <br/>
 n.4 - мультоводство - создание игроком более одного персонажей в игре, наказывается пожизненным баном мультов, а так же баном основного персонажа от суток до трое в зависимости от количества мультов <br/>
 n.5 - обман администрации - обман с целью личной выгоды наказывается баном на сутки и более. <br/>
 n.6 - пропаганда наркотиков, национальной розни, религиозной нетерпимости - наказывается баном на сутки. Возможно предварительное предупреждение. <br/>
 n.7 - оскорбление игроков игроком - наказывается на усмотрение представителями администрации. Возможно предварительное предупреждение. <br/>
 n.8 - использование багов - обо всех найденых багах (ошибках игры) необходимо сообщать администрации или же в соответствующую тему на форуме. Использование багов с целью выгоды наказываются баном на сутки или более и лишением полученой выгоды с помощью бага, либо полным удалением персонажа! <br/>
 <br/>
 Администрация и Модераторы оставляют за собой право накладывать любой тип наказания на свое усмотрение за: <br/>
 - Необоснованную критику, высказанную с неуважением, к создателям Игры. <br/>
 - Публичные высказывания про уход из игры. <br/>
 - Нецензурные и оскорбительные высказывания в адрес создателей Игры. <br/>
 - Высказывания, способные повлечь негативные последствия для игрового процесса. <br/>
 - А также любые другие действия, негативно влияющие на развитие проекта.
 ";
  }
  elseif ($_GET[help]==resist)  {
     $page.="<p class='d'><b>Сопротивления</b></p>";
     $page.="<br/><img src='/img/icon/tre.PNG'/> <b>Сопротивление</b> - устойчивость к каким либо видам атак противника. Наносимый Вам урон зависит не только от урона противника, но и от ваших значений сопротивлений. Повысить значение сопротивлений можно за счёт повышения <a href='./help.php?help=params'>параметров</a> и использования экипировки";
  }
   elseif ($_GET[help]==novis)  {
     $page.="<p class='d'><b>Краткое пособие</b></p><br/>";
     $page.="<img src='/img/icon/wat.PNG'/> В начале игры Вы оказываетесь на оборонительной базе (сокращённо ОБ). Что же сперва стоит сделать?<br/>
	 - Во первых, если Вам интересен сюжет игры и его обитатели, то стоит начать разговор с капитаном Смит. Он расскажет несколько интересных моментов и введет Вас в курс дела, затем предложит помочь Хаммеру. Если же сюжет не интересен, то можете сразу начать разговор с Хаммером. Хаммер даст Вам задание на поиски именного ножа, который найти не так то и сложно - нож можно найти в куче хлама 4 сектора канализации. Выполнив задание Вы получите драгоценный КПК. (КПК нужен для возможности общения на форуме и по почте)<br/>
	 - Во-вторых, исследуйте базу. На ОБ находится не мало интересных личностей, многие из них могут дать Вам работу. Так же, на территории базы всегда можно найти какой либо хлам, в дальнейшем его можно продать и тем самым заработать кредиты (Кредиты - основная валюта N.E.S.S.). Стоит сразу запомнить, где расположен госпиталь и центральная площадь. В госпитале Вы всегда сможете поправить своё здоровье.<br/>
	 - В-третьих, если Ва уже удалось хоть не много заработать кредитов, то стоит обзовестись оружием. Самое дешевое и простое продаётся у торговца на центральной площади. Вооруженны? Тогда вперёд на монстров! До третьего уровня Вы спокойно можете убивать канализационных крыс и сумашедших, зарабатывая боевой опыт (Опыт - чем опытнее Вы, тем сильнее). Так же, не забывайте, что Вы можете выполнять различные поручения от жителей ОБ. Многие поручение можно выполнять по нескольку раз, через определённое время.<br/>
	 <br/>
	 <img src='/img/icon/wat.PNG'/> Достигли 3 уровня. Что теперь возник вопрос?<br/>
	 - Во первых, достигнув третего уровня, Вы получаете доступ к выходу из ОБ, но не спешите туда! Мир за блокпостом не так приветлив. Стоит преобрести начальную экипировку и самое сильное или удобное для Вас оружие. Вы укомплектованы? Тогда вперёд, на поиски монстров и драгоценного хлама.<br/>
	 - Во-вторых, не советуем Вам слишком далеко уходить от ОБ, можно просто заблудиться. Для начала пройдитесь по дороге до метро и спуститесь туда. Там расположен клуб N.K.Слим, в дальнейшем именно там решится Ваша судьба. В метро расположен магазин с довольно таки привлекательным товаром. Скупитесь, если что то нужно.<br/>
	 - В-третьих, исследововав прилигающую территорию за ОБ и достигнув пятого уровня стоит поговорить с стариком в клубе N.K.Слим. Выполняя поручения Вы найдёте себе там друзей и врагов, перед Вами будет стоять выбор: стать повстанцем или военным. Для тех кто не может решиться стоит подождать 10 уровня и выполнять поручения капитана Смита<br/>
	 <br/>
	 На этом всё. Пособие будет дописано и дополненно ещё. Так же именно Вы, можете принять участие в дополнении пособия ;)
	 ";
  }
  elseif ($_GET[help]==skills)  {
     $page.="<p class='d'><b>Навыки</b></p>";
     $page.="<br/><img src='/img/icon/tre.PNG'/> <b>Навыки</b> - определённые способности игрока, от которых зависит шансы на определённые действия. Повышаются от совершенных Вами действий. Для поднятия уровня навыка очки действий должны достигнуть отметки 20*2^(уровень навыка). Существуют следующие навыки:<br/>
	 Рукопашный бой - каждый уровень +1 урона, +1% попадаемости.<br/>
     Холодное оружие - каждый уровень +1 урона, +1% попадаемости.<br/>
     Стрелковое оружие - каждый уровень +1 урона, +1% попадаемости.<br/>
     Метательное оружие - каждый уровень +1 урона, +1% попадаемости.<br/>
     Торговля - каждый уровень +1% к стоимости товара при продаже, -1% к стоимости товара при покупке.<br/>
     Взлом - каждый уровень +1% к шансу взломать замок более высокого уровня.<br/>
     Наблюдательность - каждый уровень +1% к дропу.<br/>
     Мастер - уровень мастерства.<br/>
     Медик - уровень мастерства.<br/>
     Химик - уровень мастерства.<br/>
     Рудокоп - каждый уровень +1% к шансу добыть руду.<br/><br/>";
	 $page.="Владение оружеем повышается от того как часто Вы пользуетесь им и сколько было сделано убийств.<br/>";
     $page.="Торговля повышается при торговле. Изначально Вы можете продавать вещи по цене 75% от базовой, а продавать по цене 125% от базовой.<br/>";
     $page.="Взлом повышается при удачном взломе закрытых объектов.<br/>";
     $page.="Наблюдательность повышается при убийстве монстра.<br/>";
     $page.="Профессия (Мастер, медик, химик) нужна для изготовления вещей/препаратов/патронов по определённым рецептам и схемам.";
  }
  elseif ($_GET[help]==newlevel)  {
     $page.="<p class='d'><b>Повышение уровня</b></p>";
     $page.="<br/><img src='/img/icon/p.PNG'/> Ваш уровень повышается как только Вы набираете нужное количества боевого <a href='./?do=exp'>опыта</a> для нового уровня. При получении уровня вам дается 5 очков обучения и ваше здоровье повышается. На очки обучения вы можете поднять ваши <a href='./help.php?help=params'>параметры</a>. Для поднятия параметров на 1 единицу Вам потребуется затратить 1 очко обучения. Повышение параметров сверх 30 будет затрачиваться по 2 очка обучения. <a href='./help.php?help=params'>Параметры</a> можно поднять только с помощью специальных терминалов.";
  }
  elseif ($_GET[help]==eff)  {
     $page.="<p class='d'><b>Эффекты</b></p>";
     $page.="<br/><img src='/img/icon/yad.PNG'/> <b>Эффекты</b> - действующие на Вас кратковременные препараты и медикаменты. Все эффекты действуют в течении часа после применения препарата/медикамента. Исключением могут быть побочные эффекты, которые могут длиться до несколько суток. Побочные эффекты - это та цена, которую приходится платить за лечебный эффект";
}
  elseif ($_GET[help]==inv)  {
     $page.="<p class='d'><b>Инвентарь</b></p>";
     $page.="<br/><img src='/img/icon/um.PNG'/> <b>Инвентарь</b> - это ваш личный игровой рюкзак, позволяющий хранить оружие, предметы, ценности и разнообразный хлам. Каждый предмет имеет свой вес - а сколько Вы можете перенести зависит от <a href='./help.php?help=params'>параметров</a> Вашей силы (100+30*сила). Если вы будете перегружены то не сможете что-либо взять.<br/>
	 <img src='/img/img/help_inv.PNG'/><br/>
	 Выше Вы выдите как выглядит содержимое инвентаря. 
	 <br/><img src='/img/icon/um.PNG'/> <b>Экипировка</b> - вещи, которые Вы в данный момент используете (носите), перейдя в экипировку увидете, что на Вас сейчас одето. Далее отображается грузоподьёмность и имеющиеся количество кредитов (Кредиты - игровая валюта). Затем список вещей который находится непосредственно в инвентаре, каждую вещь можно просматривать/выбросить/применить просто кликнув по ней.";
  }
    elseif ($_GET[help]==info)  {
     $page.="<p class='d'><b>Об игре</b></p>";
     $page.="<br/><img src='/img/img/PVN.PNG'/><br/>
	 <img src='/img/icon/yad.PNG'/> <b>N.E.S.S.</b> - это онлайн игра жанра MMORPG для мобильных телефонов!<br/>
	 Захватывающий сюжет, который постепенно раскрывается по ходу игры. Интересный окружающий мир с его не повторимимы жителями, каждый из которых по своему уникален. Толпы ненавистных тварей и противников. Перед Вами будет стоять выбор между тремя враждующими сторонами (каждая сторона имеет свой цвет экипировки). Вас ожидает: прокачка своего персонажа, умений, навыков, торговля, создание вещей, выполнение сложных задач и не только. <br/>
	 <img src='/img/icon/dia.PNG'/> Поначалу происходящее может показаться необычным, но не спешите бросать игру в самом начале. Поиграйте несколько дней, разберитесь в происходящем, и вы не пожалеете, открыв для себя отличное развлечение на много месяцев вперед.
	 ";
}
  elseif ($_GET[help]==sml)  {
$page.="<p class='d'><b>Смайлики</b></p>";
$s[1]='<br>:) или :-) <img src="/img/sml/smile.png">';
$s[]='<br>;) или ;-) <img src="/img/sml/mig.png">';
$s[]='<br>:( или :-( <img src="/img/sml/smil.png">';
$s[]='<br>:P или :-P <img src="/img/sml/be.png">';
$s[]='<br>:D или :-D <img src="/img/sml/lol.png">';
$s[]='<br>:[ или :-[ <img src="/img/sml/ee.png">';
$s[]='<br>B) или 8) <img src="/img/sml/cool.png">';
$s[]='<br>O_o или o_O <img src="/img/sml/boogle.png">';
$s[]='<br>.cry. <img src="/img/sml/cry.png">';
$s[]='<br>.boom. <img src="/img/sml/boom.png">';
$s[]='<br>.boks. <img src="/img/sml/boks.png">';
$s[]='<br>.avtomat. <img src="/img/sml/avtomat.png">';
$s[]='<br>.cho. <img src="/img/sml/cho.png">';
$s[]='<br>.zloy. <img src="/img/sml/zloy.png">';
$s[]='<br>.grena. <img src="/img/sml/granata.png">';
$s[]='<br>.dead. <img src="/img/sml/dead.png">';
$s[]='<br>.bebe. <img src="/img/sml/bebe.png">';
$s[]='<br>.dbebe. <img src="/img/sml/dbebe.png">';
$s[]='<br>.boroda. <img src="/img/sml/boroda.png">';
$s[]='<br>.dee. <img src="/img/sml/dee.png">';
$s[]='<br>.dsmile. <img src="/img/sml/dsmile.png">';
$s[]='<br>.dum. <img src="/img/sml/dum.png">';
$s[]='<br>.faer. <img src="/img/sml/faer.png">';
$s[]='<br>.gotov. <img src="/img/sml/gotov.png">';
$s[]='<br>.ha. <img src="/img/sml/ha.png">';
$s[]='<br>.hm. <img src="/img/sml/hm.png">';
$s[]='<br>.oficer. <img src="/img/sml/oficer.png">';
$s[]='<br>.sigara. <img src="/img/sml/sigara.png">';
$s[]='<br>.smert. <img src="/img/sml/smert.png">';
$s[]='<br>.cenz. <img src="/img/sml/cenz.png">';
$s[]='<br>.alkash. <img src="/img/sml/alkash.png">';
$s[]='<br>.chmok. <img src="/img/sml/chmok.png">';
$s[]='<br>.dlove. <img src="/img/sml/dlove.png">';
$s[]='<br>.fingal. <img src="/img/sml/fingal.png">';
$s[]='<br>.hell. <img src="/img/sml/hell.png">';
$s[]='<br>.love. <img src="/img/sml/love.png">';
$s[]='<br>.roza. <img src="/img/sml/roza.png">';
$s[]='<br>.mir. <img src="/img/sml/mir.gif">';
$s[]='<br>.tank. <img src="/img/sml/tank.png">';
$kol=10;//Количество вещей на стр
settype($_GET['p'],'int');
$p=$_GET['p']>=1?$_GET['p']:1;	
$i=$p*$kol-$kol;
while($i<=$p*$kol){
$page.=$s[$i];
$i++;
}
$page.=nav_page(ceil(count($s)/$kol),$p,'/help.php?help=sml&p=');
}
$page.="<br/><br/><a href='./help.php?help=main'>Помощь</a><br/>";
}
$page.="<a href='./'>Главная</a><br/>";
$page.="<br/><p class='d'><b>".date2("j.m.",date('U')+8*3600).date("  G:i",date('U')+8*3600)."</b></p>";
 display($page,$title,$style);
 ?>