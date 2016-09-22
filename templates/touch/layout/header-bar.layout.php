<!-- BEGIN HEADER -->
<nav class="navbar navbar-inverse" id="header">
<div class="container">
  <div class="container-fluid">
    <ul class="topnav">
      <li><a href="<?php echo HOME ?>" title="<?php echo dbConfig('title') ?>"><img src="<?php echo _URL_FILES_SYSTEMS ?>/images/logo.png" alt="<?php echo dbConfig('title') ?>" style="height: 50px;"/></a></li>        
      <?php
        $cats = model()->db->query("SELECT * FROM " . tb() . "blogs WHERE  parent = 0 AND type = 'folder' AND status = 0 ORDER BY time");
        foreach($cats as $item){
            echo '<li><a href="'.HOME . '/' . $item['url'] . '-' . $item['id'] . '.html">'.$item['title'].'</a></li>';
        }
      ?>
	<?php if (IS_MEMBER) : ?>
    <?php if (is(ROLE_MANAGER)): ?>
            <li><i class="fa fa-user"></i><a href="<?php echo HOME ?>/admin"><span> Admin CP</span></a></li>
        <?php endif ?>
         <li><a href="<?php echo HOME ?>/account"><span class="glyphicon glyphicon-user"></span> <?php echo $_client['nickname'] ?></a></li>
         <li><a href="<?php echo HOME ?>/account/settings"><i class="fa fa-cogs" ></i> Setting</a></li>
         <li><a href="<?php echo HOME ?>/logout"><i class="fa fa-power-off"></i> Logout</a></li>
  	<?php else: ?>
  		<li><a href="<?php echo HOME ?>/register"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
  		<li><a href="<?php echo HOME ?>/login"><span class="glyphicon glyphicon-log-in"></span> Sign In</a></li>
  	<?php endif ?>
    <li class="icon">
    <a href="javascript:void(0);" style="font-size:35px;" onclick="topnav()">☰</a>
  </li>
    </ul>
  </div>
</div>
</nav>
<!-- Header END -->