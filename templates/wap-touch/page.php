<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php ZenView::get_title() ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content="<?php ZenView::get_desc() ?>" name="description" />
    <meta content="<?php ZenView::get_keyword() ?>" name="keywords" />
    <meta content="DragonT" name="author" />
    <meta property="og:site_name" content="<?php ZenView::get_title() ?>" />
    <meta property="og:title" content="<?php ZenView::get_title() ?>" />
    <meta property="og:description" content="<?php ZenView::get_desc() ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?php ZenView::get_image() ?>" />
    <meta property="og:url" content="<?php ZenView::get_url() ?>" />
    <link rel="shortcut icon" href="<?php echo HOME ?>/files/systems/images/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" rel="stylesheet"/>
    
<script src="<?php echo _BASE_TEMPLATE ?>/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo _BASE_TEMPLATE ?>/js/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?php echo _BASE_TEMPLATE ?>/js/custom.js"></script>
</head>

<body>
<?php if(!isset($_COOKIE['theme'])||$_COOKIE['theme']=='light'){ ?>
		<div data-role="page" id="pageone" data-theme="a">
	<?php }else{ ?>
		<div data-role="page" id="pageone" data-theme="b">
	<?php } ?>

<?php ZenView::load_layout('header-bar') ?>

<div data-role="main" class="ui-content">

        <?php ZenView::display_content() ?>
</div>

<?php ZenView::load_layout('footer') ?>
</div>  
</body>
</html>