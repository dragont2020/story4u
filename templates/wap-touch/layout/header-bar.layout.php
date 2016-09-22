<div data-role="panel" id="danhmuc" data-display="overlay" style="width: 50%" data-swipe-close="false">
    <h2>Truyện Hay</h2>
    <ul data-role="listview">
    <?php
        $cats = model()->db->query("SELECT * FROM " . tb() . "blogs WHERE type = 'folder' AND parent = 0 AND status = 0 ORDER BY time");
        foreach($cats as $item){
            echo '<li><a href="'.HOME . '/' . $item['url'] . '-' . $item['id'] . '.html"><i class="fa fa-star"></i> '.$item['title'].'</a></li>';
        }
      ?>
     <?php if(!isset($_COOKIE['theme'])||$_COOKIE['theme']=='light'){ ?>
		<a class="ui-btn ui-btn-b ui-icon-star ui-btn-icon-left" href="/change_themes.php?theme=night">Night Mode</a>
	<?php }else{ ?>
		<a class="ui-btn ui-btn-a ui-icon-cloud ui-btn-icon-left" href="/change_themes.php?theme=light">Normal Mode</a>
	<?php } ?>
    </ul>
</div>
<div data-role="header" data-position="fixed">
	<a href="#danhmuc" style="font-size: 28px" class="ui-btn ui-corner-all ui-icon-bars ui-btn-icon-notext"></a>
    <h1 style="margin: 0 20%;"><a href="<?php echo HOME ?>" title="<?php echo dbConfig('title') ?>"><img src="<?php echo _URL_FILES_SYSTEMS ?>/images/logo.png" alt="<?php echo dbConfig('title') ?>" style="width: 100%;"/></a></h1>
    <a href="#user" style="font-size: 28px" class="ui-btn ui-corner-all ui-icon-user ui-btn-icon-notext"></a>
</div>
<div data-role="panel" id="user" data-display="overlay" data-position="right" style="width: 50%" data-swipe-close="false">
     <?php if (IS_MEMBER) : ?>
      	<h2>Hi! <?php echo $_client['nickname'] ?></h2>
    	<ul data-role="listview">
             <li><a href="<?php echo HOME ?>/account"><i class="fa fa-user" ></i> Info</a></li>
             <li><a href="<?php echo HOME ?>/account/settings"><i class="fa fa-cogs" ></i> Setting</a></li>
             <li><a href="<?php echo HOME ?>/logout"><i class="fa fa-power-off"></i> Logout</a></li>
      	<?php else: ?>
      	<h2>Account</h2>
    	<ul data-role="listview">
      		<li><a href="<?php echo HOME ?>/register"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      		<li><a href="<?php echo HOME ?>/login"><span class="glyphicon glyphicon-log-in"></span> Sign In</a></li>
      	<?php endif ?>
    </ul>
</div>