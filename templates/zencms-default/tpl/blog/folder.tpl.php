<div class="row margin-bottom-40">
    <div class="col-md-12 col-sm-12">
        <div class="content-page">
            <?php if(ZenView::$D['blog']['parent']==0){ ?>
            	<div class="row">
                    <?php $cats = model()->list_custom_cat(ZenView::$D['blog']['id'], 'posts_in_folder', 12, 'page') ?>
                    <?php if ($cats):?>
                        <h2 class="no-top-space"><?php echo ZenView::$D['blog']['name'] ?></h2>
                            <?php echo model()->show_cats($cats); ?>
                            <hr class="blog-post-sep"/>
                            <div class="pull-right"><?php ZenView::display_paging('posts_in_folder') ?></div>
             	<?php endif ?>
            </div>
            <?php }else{ ?>
            	<?php $posts = model()->list_custom_post(ZenView::$D['blog']['id'],'',0);
            	$cat = model()->get_blog_data(ZenView::$D['blog']['parent'],'',1);?>
                    <?php if ($posts){$dem = 1;?>
            	<div class="col-md-3 col-sm-3" style="position: fixed; width: 20%;">
                        <h2 class="no-top-space">Danh Sách Chương</h2>
                        <input type="text" id="sif" style="width: 100%;color: black;" placeholder="Search for names" />
                        <ul class="nav list-wrap" style="height: 720px;">
                            <?php foreach ($posts as $item): ?>
                                <li class="<?php echo (($dem%2 == 0) ?  'item-even' :  'item-odd')?>"><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $dem.'. '.ucwords($item['name']); $dem += 1; ?></a></li>
                            <?php endforeach ?>
                        </ul>
                   </div>
                   <div class="col-md-offset-3 col-md-9 col-sm-9" id="blog_info">
                        <h1 class="blog-title"><?php echo ZenView::$D['blog']['name'] ?></h1>
                        <h2><a href="<?php echo $cat['full_url'] ?>" title="<?php echo $cat['title'] ?>"><?php echo $cat['name'] ?></a></h2>
                        <span class="logo_post" style="right: 375px"></span>
                        <img src="<?php echo model()->full_icon(ZenView::$D['blog']['icon']) ?>" style="height: 500px;width: 450px;float: right;" alt="<?php echo $item['title'] ?>" class="img-responsive"/>
                        <h3><?php echo htmlspecialchars_decode(ZenView::$D['blog']['des']) ?></h3><br/><br/>
                   </div>
             	<?php } ?>
            <?php } ?>
        </div>
    </div>
</div>