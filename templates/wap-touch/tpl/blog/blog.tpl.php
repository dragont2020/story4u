<div class="row margin-bottom-40">
    <div class="content-page">
        <?php $cats = model()->db->query("SELECT * FROM " . tb() . "blogs WHERE type = 'folder' AND status = 0 AND parent != 0 ORDER BY time DESC LIMIT 10"); ?>
        <?php if ($cats):?>
        <div data-role="collapsible" data-collapsed="false" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
            <h3 class="ui-btn">Truyện Mới</h3>
                <?php echo model()->show_cats($cats, 'mobile'); ?>
        </div>
    <?php endif ?>
    
    	<?php
        $list = model()->db->query("SELECT * FROM " . tb() . "blogs WHERE type = 'folder' AND parent = 0 ORDER BY time ");
        foreach($list as $li){
        $cat = model()->list_new_cat($li['id'],'',4);
        if ($cat):?>
            <div data-role="collapsible" data-collapsed="false" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">        
                <h3 class="ui-btn"><?php echo $li['name'] ?></h3>
                <?php echo model()->show_cats($cat, 'mobile'); ?>
            </div>                
         	<?php endif ?>
        
        
      <?php } ?>
    </div>
</div>