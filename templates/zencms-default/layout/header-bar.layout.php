<sticknav>
<nav class="navbar navbar-inverse" id="header">
<div class="container">
  <div class="container-fluid">
    <div class="navbar-header">
    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navtop">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
      <a href="<?php echo HOME ?>" title="<?php echo dbConfig('title') ?>"><img src="<?php echo _URL_FILES_SYSTEMS ?>/images/logo.png" alt="<?php echo dbConfig('title') ?>" style="height: 50px;"/></a>
    </div>
    <div class="collapse navbar-collapse" id="navtop">
	    <ul class="nav navbar-nav">
	      <li><a href="<?php echo HOME ?>"><i class="fa fa-home"></i> Trang Chủ</a></li>
	      <li><a id="search_box" style="cursor: pointer;"><i class="fa fa-search"></i> Tìm Kiếm</a></li>
	      <li><input type="text" id="key" class="w3-input w3-border" style="width: 190px;"/><div id="sr">
	      </div></li>
	      <li class="w3-dropdown-hover">
	        <a href="#"><i class="fa fa-bookmark-o"></i> Truyện Hay</a>
	          <div class="w3-dropdown-content w3-border">
	          <?php
	            $cats = model()->db->query("SELECT * FROM " . tb() . "blogs WHERE  parent = 0 AND type = 'folder' AND status = 0 ORDER BY time");
	            foreach($cats as $item){
	                echo '<a href="'.HOME . '/' . $item['url'] . '-' . $item['id'] . '.html">'.$item['title'].'</a>';
	            }
	          ?>
	        </div>
	      </li>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
	    	<?php if (IS_MEMBER) : ?>
	             <li><a href="<?php echo HOME ?>/account"><span class="glyphicon glyphicon-user"></span> <?php echo $_client['nickname'] ?></a></li>
	             <li><a href="<?php echo HOME ?>/account/settings"><i class="fa fa-cogs" ></i> Setting</a></li>
	             <li><a href="<?php echo HOME ?>/logout"><i class="fa fa-power-off"></i> Logout</a></li>
	      	<?php else: ?>
	      		<li><a href="<?php echo HOME ?>/register"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
	      		<li><a href="<?php echo HOME ?>/login"><span class="glyphicon glyphicon-log-in"></span> Sign In</a></li>
	      	<?php endif ?>
	    </ul>
    </div>
  </div>
</div>
</nav>
</sticknav>