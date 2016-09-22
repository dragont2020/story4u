<div class="row margin-bottom-40">
    <div class="col-md-12 col-sm-12">
        <div class="content-page">
        <?php
        	$p = isset($_GET['page'])?$_GET['page']:1;
        	$lim = 10;
        ?>
            <?php if(ZenView::$D['blog']['parent']==0){ ?>
            	<div class="row">
                    <?php $cats = model()->list_new_cat(ZenView::$D['blog']['id'],'',0); ?>
                    <?php 
                    	$total = count($cats);
                    	$total_page = ceil($total / $lim);
                    	$start = ($p - 1) * $lim;
                    	$c = model()->list_new_cat(ZenView::$D['blog']['id'], '', "$start, $lim");
                    ?>
                    <h3 class="ui-btn"><?php echo ZenView::$D['blog']['name'];?></h3>
                    <form class="ui-filterable">
  							<input id="myFilter" data-type="search" placeholder="Search for names..">
				    </form>
                    <?php echo model()->show_cats($c, 'mobile'); ?>
    				<div data-role="header">
    				<?php
                    if ($p > 1 && $total_page > 1){
                		echo '<a class="ui-btn ui-btn-left" href="?page='.($p-1).'">Prev</a>';
            		}
            		echo"<h2>$p</h2>";
            		if ($p < $total_page && $total_page > 1){
                		echo '<a class="ui-btn ui-btn-right" href="?page='.($p+1).'">Next</a>';
            		}
                    ?>
                    </div>
            </div>
            <?php }else{ ?>
                    <div data-role="panel" id="danhsc" data-display="overlay" style="width: 90%">
    					<h2>Danh Sách Chương</h2>
    					<form class="ui-filterable">
  							<input id="myFilter" data-type="search" placeholder="Search for names..">
						</form>
    					<ul data-role="listview" data-filter="true" data-input="#myFilter">
                            <?php $posts = model()->list_custom_post(ZenView::$D['blog']['id'],'',0);
                                foreach ($posts as $item): ?>
                                <li><a href="<?php echo $item['full_url'] ?>"><?php echo $item['name'] ?></a></li>
                            <?php endforeach ?>
                        </ul>
    				</div>
                   <div class="col-md-9 col-sm-9">
                        <h1 class="blog-title"><?php echo ZenView::$D['blog']['name'] ?></h1>
                        <a href="#danhsc" class="ui-btn ui-btn-inline ui-corner-all ui-shadow">Danh Sách Chương</a>
                        <img src="<?php echo model()->full_icon(ZenView::$D['blog']['icon']) ?>" style="height: 500px;width: 450px;" alt="<?php echo $item['title'] ?>" class="img-responsive"/>
                        <h3><?php echo ZenView::$D['blog']['des'] ?></h3><br/><br/>
                   </div>
            <?php } ?>
        </div>
    </div>
</div>