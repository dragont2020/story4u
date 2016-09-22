<div class="row margin-bottom-40">
    <div class="col-md-12 col-sm-12">
        <div class="content-page">
            <!---<div class="alert alert-info">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Note!</strong> Ảnh đã bị nén. Di chuột vào để xem ảnh gốc. Nếu ảnh bị đen bạn cứ di chuột qua sẽ hiện lên.
              </div>---!>
            <div class="panel panel-primary col-md-12">
                <?php $cats = model()->db->query("SELECT * FROM " . tb() . "blogs WHERE type = 'folder' AND status = 0 AND parent != 0 ORDER BY time DESC LIMIT 12");?>
                <?php if ($cats){?>
                    <h1 class="panel-heading" align="center">Truyện Mới</h1>
                    <div class="panel-body">
                        <?php echo model()->show_cats($cats); ?>
                    </div>
             	<?php } ?>
            </div>
            <?php
            $lists = array(2 => '6', 1 => '6', 4 => '6', 3 => '6', 5 => '6', 6 => '6', );
            foreach($lists as $list => $col){
                $li = model()->get_blog_data($list);
                $cats = model()->list_custom_cat($li['id'],'',10);
                if ($cats){?>
                    <div class="panel panel-primary col-md-<?php echo $col ?>">
                        <h1 class="panel-heading" align="center"><a href="<?php echo HOME . '/' . $li['url'] . '-' . $li['id'] . '.html'; ?>" title="<?php echo $li['title'] ?>" style="color: white;"><?php echo $li['name'] ?></a></h1>
                        <div class="panel-body">
                        <?php echo model()->show_cats($cats, 'pc', 'list'); ?>
                        </div>
                    </div>
           	  <?php }
              } ?>
        </div>
    </div>
</div>