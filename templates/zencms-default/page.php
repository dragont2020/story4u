<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- Head BEGIN -->
<head>
<meta charset="utf-8"/>
<title>
<?php ZenView::get_title() ?>
</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta content="<?php echo htmlspecialchars(strip_tags(ZenView::get_desc(true))) ?>" name="description" />
<meta content="<?php ZenView::get_keyword() ?>" name="keywords" />
<meta content="DragonT" name="author" />
<meta property="og:site_name" content="<?php ZenView::get_title() ?>" />
<meta property="og:title" content="<?php ZenView::get_title() ?>" />
<meta property="og:description" content="<?php echo htmlspecialchars(strip_tags(ZenView::get_desc(true))) ?>" />
<meta property="og:type" content="website" />
<meta property="og:image" content="<?php ZenView::get_image() ?>" />
<!-- link to image for socio -->
<meta property="og:url" content="<?php ZenView::get_url() ?>" />
<link rel="shortcut icon" href="<?php echo HOME ?>/files/systems/images/favicon.ico" />
<!-- Fonts START -->
<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed|Coiny|Kanit|Lalezar|Lobster|Patrick+Hand&subset=vietnamese" rel="stylesheet"/>
<!-- Fonts END -->
<!-- Global styles START -->
<link href="<?php echo HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<link href="<?php echo HOME ?>/files/systems/w3/w3.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo HOME ?>/files/systems/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
<!-- Global styles END -->
<!-- Theme styles START -->
<?php if(!isset($_COOKIE['theme'])||$_COOKIE['theme']=='light'){ ?>
<link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/css/style.css" rel="stylesheet"/>
<?php }else{ ?>
<link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/global/css/night-mode.css" rel="stylesheet"/>
<link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/css/night-style.css" rel="stylesheet"/>
<?php } ?>
<link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/css/custom.css" rel="stylesheet"/>
<!-- Theme styles END -->

</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="corporate">
<?php ZenView::load_layout('header-bar') ?>
<div class="main">
  <div class="container">
    <div class="vipLoad">
      <p>
        <?php ZenView::get_title() ?>
      </p>
      <div class="cssload-container">
	<div class="cssload-shaft1"></div>
	<div class="cssload-shaft2"></div>
	<div class="cssload-shaft3"></div>
	<div class="cssload-shaft4"></div>
	<div class="cssload-shaft5"></div>
	<div class="cssload-shaft6"></div>
	<div class="cssload-shaft7"></div>
	<div class="cssload-shaft8"></div>
	<div class="cssload-shaft9"></div>
	<div class="cssload-shaft10"></div>
</div><b class="small" style="cursor: pointer;color: white;">Disable This Popup</b> </div>
    <?php ZenView::display_content() ?>
  </div>
</div>
<?php ZenView::load_layout('footer') ?>
<script type="text/javascript" src="<?php echo HOME ?>/files/systems/js/jquery/jquery-3.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo HOME ?>/files/systems/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/scripts/scroll.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo _BASE_TEMPLATE ?>/js/custom.js"></script>
<!-- END PAGE LEVEL JAVASCRIPTS -->
<?php ZenView::get_foot() ?>
</body>
<!-- END BODY -->
</html>