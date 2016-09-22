<?php ZenView::add_css(REAL_HOME . '/files/systems/templates/admin-flat/theme/admin/pages/css/blog.css') ?>

<div class="row">
    <?php if (is_module_activate('blog')): ?>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-folder-open"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php echo model('blog')->count() ?> bài
                </div>
                <div class="desc">
                    bài đã được viết
                </div>
            </div>
            <a class="more" href="<?php echo genUrlAppFollow('blog/manager') ?>">
                Quản lí blog <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
        <?php $n = 3; $m = 6?>
    <?php else: ?>
        <?php $n = 4; $m = 12 ?>
    <?php endif ?>
    <div class="col-lg-<?php echo $n ?> col-md-<?php echo $n ?> col-sm-6 col-xs-12">
        <div class="dashboard-stat red-intense">
            <div class="visual">
                <i class="fa fa-puzzle-piece"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php echo model()->count_module() ?>
                </div>
                <div class="desc">
                    module đã cài đặt
                </div>
            </div>
            <a class="more" href="<?php echo HOME ?>/admin/general/modules">
                Danh sách <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-<?php echo $n ?> col-md-<?php echo $n ?> col-sm-6 col-xs-12">
        <div class="dashboard-stat green-haze">
            <div class="visual">
                <i class="fa fa-font"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php echo model()->count_template() ?>
                </div>
                <div class="desc">
                    giao diện đã cài đặt
                </div>
            </div>
            <a class="more" href="<?php echo HOME ?>/admin/general/templates">
                Danh sách <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-<?php echo $n ?> col-md-<?php echo $n ?> col-sm-<?php echo $m ?> col-xs-12">
        <div class="dashboard-stat purple-plum">
            <div class="visual">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php echo model('account')->count_users() ?>
                </div>
                <div class="desc">
                    thành viên đã đăng kí
                </div>
            </div>
            <a class="more" href="<?php echo HOME ?>/admin/members/list">
                Danh sách <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>


<div class="row">
<div class="col-md-12 blog-page">
<div class="row">
<div class="col-md-8 col-sm-8 article-block">
    <?php ZenView::display_message() ?>
    <?php ZenView::display_message('new_feed') ?>
    <?php if (ZenView::$D['new_feeds']) foreach (ZenView::$D['new_feeds'] as $item): ?>
        <div class="row">
            <div class="col-md-2 blog-img">
                <img src="<?php echo $item->full_icon ?>" class="img-responsive">
            </div>
            <div class="col-md-10 blog-article">
                <h3>
                    <a href="<?php echo $item->full_url ?>" title="<?php echo $item->title ?>" target="_blank"><?php echo $item->name ?></a>
                </h3>
                <p><?php echo $item->short_desc ?></p>
                <div class="blog-tag-data">
                    <ul class="list-inline">
                        <li>
                            <i class="fa fa-calendar"></i>
                            <a><?php echo $item->display_time ?></a>
                        </li>
                    </ul>
                </div>
                <a class="btn btn-danger" href="<?php echo $item->full_url ?>" title="<?php echo $item->title ?>" target="_blank">Đọc tiếp <i class="m-icon-swapright m-icon-white"></i></a>
            </div>
        </div>
        <hr/>
    <?php endforeach ?>
</div>
<!--end col-md-9-->
<div class="col-md-4 col-sm-4 blog-sidebar">
    <div class="top-news">
        <?php $list_class = array('red', 'green', 'blue', 'yellow', 'purple') ?>
        <?php $i = 0?>
        <?php if (ZenView::$D['list_cat']) foreach(ZenView::$D['list_cat'] as $item): ?>
            <?php if ($i > 4) $i = 0 ?>
            <a href="<?php echo $item->full_url ?>" class="btn <?php echo $list_class[$i++] ?>" target="_blank">
                <span class="text-dot"><?php echo $item->name ?></span>
                <em><i class="fa fa-link"></i> Xem thêm</em>
                <i class="fa fa-globe top-news-icon"></i>
            </a>
        <?php endforeach ?>
    </div>
</div>
<!--end col-md-3-->
</div>
</div>
</div>