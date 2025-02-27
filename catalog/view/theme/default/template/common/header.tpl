<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="catalog/view/javascript/jquery/fancybox-2.1.7/source/jquery.fancybox.js"></script>

<link href="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen" />
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/javascript/jquery/fancybox-2.1.7/source/jquery.fancybox.css" rel="stylesheet">
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
</head>
<body class="<?php echo $class; ?>">

<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <nav id="primary_nav_wrap">
          <ul>
            <li><span>О компании</span>
              <ul>
                <li>
                  <a href="<?php echo $informations[0]['href']; ?>">Информация о компании</a></li>
                <li><a href="<?php echo $informations[7]['href']; ?>">Гарантии и обязательства</a></li>
                <li><a href="<?php echo $informations[8]['href']; ?>">Информация и документация</a></li>
                <li><a href="<?php echo $informations[9]['href']; ?>">Каталог производителей</a>
                <li><a href="<?php echo $informations[10]['href']; ?>">Полный прайс-лист для оптовых покупателей </a></li>
              </ul>
            </li>
            <li><span>Получение и оплата</span>
              <ul>
                <li><span>Получение</span>
                  <ul>
                    <li><a href="<?php echo $informations[11]['href']; ?>">Пункты самовывоза </a></li>
                    <li><a href="<?php echo $informations[12]['href']; ?>">Доставка </a></li>
                  </ul>
                </li>
                <li><a href="<?php echo $informations[13]['href']; ?>">Оплата</a></li>
              </ul>
            </li>
            <li><a href="<?php echo $informations[4]['href']; ?>">Компаниям</a></li>
            <li><a href="/index.php?route=information/news">Новости</a></li>
            <li><a href="<?php echo $informations[6]['href']; ?>">Контакты</a></li>
            <?php if (!$logged) {?>
            <li><span>Вход/Регистрация</span>
              <ul>
                <li><a href="<?=$loginform?>">Страница входа</a></li>
                <li><a href="<?=$registerform?>">Страница регистрации</a></li>
              </ul>
            </li>
            <?} else {?>
            <li><a href="<?=$lkform?>">Личный кабинет</a></li>
            <?} ?>
          </ul>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-2">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="col-sm-4"><?php echo $search; ?></div>
      <div class="col-sm-2">
        <a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span>
      </div>
      <div class="col-sm-2"><?php echo $cart; ?></div>
      <div class="col-sm-2">
        <a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i>
          <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a>
      </div>
    </div>

  </div>

  <div id="fixedfooter">
    <div > <button id="foorer_feedback">Обратная связь</button> </div>
    <div ><button id="foorer_subscr">Подписка</button> </div>
    <div id="footercart"><?=$cart_f?></div>
  </div>

</header>
<?php //if ($categories) { ?>
<?php if (0==1) { ?>
<div class="container">
  <nav id="menu" class="navbar">
    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav">
        <?php foreach ($categories as $category) { ?>
        <?php if ($category['children']) { ?>
        <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
          <div class="dropdown-menu">
            <div class="dropdown-inner">
              <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
              <ul class="list-unstyled">
                <?php foreach ($children as $child) { ?>
                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </div>
            <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
        </li>
        <?php } else { ?>
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
  </nav>
</div>



<div class="floating-form" id="subscr">
  <div class="floating-form-heading">Подписка на рассылку</div>
  <div id="subs_results"></div>
  <label><span>e-mail<span class="required">*</span></span>
    <input type="email" name="email_s" maxlength="70" required="required" class="input-field">
  </label>
  <label>
    <input type="submit" id="subscr_btn" value="отправить">
  </label>
</div>

<div class="floating-form" id="contact_form">
  <div class="floating-form-heading">Заполните форму</div>
  <div id="contact_results"></div>
  <div id="contact_body">
  <label><span>Тема <span class="required">*</span></span>
      <input type="text" name="name" id="subj" required="required" class="input-field">
  </label>
  <label><span>Имя <span class="required">*</span></span>
    <input type="text" name="name" id="name" required="required" class="input-field">
  </label>
  <label><span>Телефон <span class="required">*</span></span>
    <input type="text" name="phone2" maxlength="20" required="required" class="input-field">
  </label>
  <label><span>e-mail<span class="required">*</span></span>
    <input type="email" name="email" maxlength="70" required="required" class="input-field">
  </label>
  <label><span>Текст </span>
    <textarea name="message" id="message" class="textarea-field" required="required"></textarea>
  </label>
  <label>
    <?=$captcha?>
  </label>

  <label>
    <span> </span><input type="submit" id="submit_btn" value="отправить">
  </label>
  </div>

</div>


<?php } ?>