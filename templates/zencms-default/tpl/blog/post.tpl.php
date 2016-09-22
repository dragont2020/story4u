<?php ZenView::display_breadcrumb() ?>
    <h1 align="center"><?php echo ZenView::$D['blog']['name'] ?></h1>
        <div id="blog-content" style="font-size: 20px;">
            <?php echo ZenView::$D['blog']['content'] ?>
        </div>
            <?php $cat = model()->get_blog_data(ZenView::$D['blog']['parent']) ?>
            <ul class="blog-info">
                <li><a href="<?php echo ZenView::$D['blog']['user']['full_url'] ?>"><i class="fa fa-user"></i> <?php echo display_nick(ZenView::$D['blog']['user']['nickname'], ZenView::$D['blog']['user']['perm']) ?></a></li>
                <li><i class="fa fa-calendar"></i> <?php echo m_timetostr(ZenView::$D['blog']['time']) ?></li>
                <li>
                    <i class="fa fa-tags"></i> <a href="<?php echo $cat['full_url'] ?>" title="<?php echo $cat['title'] ?>"><?php echo $cat['name'] ?></a>
                </li>
            </ul>
            <!--/ end blog info -->
            <?php $posts = model()->list_custom_post(ZenView::$D['blog']['parent'],'',0);?>
           <?php if ($posts): $dem = 1;
				echo '<ul class="pager">';
                if(isset($posts[(ZenView::$D['blog']['id'] - 1)])){
                    $pre = model()->get_blog_data((ZenView::$D['blog']['id'] - 1));
                    echo '<li class="previous"><a class="w3-light-blue" href="'.$pre['full_url'].'">< '.$pre['name'].'</a></li>';
                }
                if(isset($posts[(ZenView::$D['blog']['id'] + 1)])){
                    $nex = model()->get_blog_data((ZenView::$D['blog']['id'] + 1));
                    echo '<li class="next"><a class="w3-light-green" href="'.$nex['full_url'].'">'.$nex['name'].' ></a></li>';
                }
                echo '</ul>'
			?>
            <span class="banner" id="dsc-content" style="top: 150px; left: 10px;">
            <div id="menuicon">
              <div class="bar1"></div>
              <div class="bar2"></div>
              <div class="bar3"></div>
            </div>
    	<div id="dsc" style="display: none; width: 600px;">
        <div class="header-navigation pull-right">
            <select onchange="ChangeFont (this);" style="color: black;" size="1">
                <option value="'Arial', sans-serif">Arial</option>
                <option value="'Times New Roman', sans-serif">Times New Roman</option>
            	<option value="'Patrick Hand', cursive">Patrick Hand</option>
            	<option value="'Lobster', cursive">Lobster</option>
            	<option value="'Lalezar', cursive">Lalezar</option>
            	<option value="'Kanit', sans-serif" selected="selected" >Kanit</option>
            	<option value="'Coiny', cursive">Coiny</option>
            </select>
        <a href="javascript:zoominLetter();"><i style="font-size: 50px" class="fa fa-search-plus"></i></a>
        <a href="javascript:zoomoutLetter();"><i style="font-size: 40px" class="fa fa-search-minus"></i></a></div>
        <h3 class="no-top-space">Danh Sách Chương</h3>
        <ul class="nav list-wrap" style="font-size: 20px; height: 550px;">
            <?php foreach ($posts as $item): ?>
            <?php if($item['id']==ZenView::$D['blog']['id']) : ?>
            	<li class="<?php echo (($dem%2 == 0) ?  'item-even' :  'item-odd'); $dem += 1; ?>">
            	<a href="" title="<?php echo $item['title'] ?>"><b style="color: red"><i class="fa fa-eye"></i> <?php echo ucwords($item['name']) ?></b></a></li>
            <?php else : ?>
            	<li class="<?php echo (($dem%2 == 0) ?  'item-even' :  'item-odd') ?>">
            	<a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $dem.'. '.ucwords($item['name']); $dem += 1; ?></a></li>
            <?php endif ?>
            <?php endforeach ?>
        </ul>
   </div>
   </span>
<?php endif ?>
    <!-- Blog attachments -->
    <?php if (!empty(ZenView::$D['blog']['attachments'])): ?>
        <?php if (!empty(ZenView::$D['blog']['attachments']['link'])) foreach(ZenView::$D['blog']['attachments']['link'] as $item): ?>
            <ul class="blog-info">
                <li>
                    <i class="fa fa-download"></i>
                    <a href="<?php echo $item['link'] ?>" title="<?php echo $item['name'] ?>" rel="nofollow"><?php echo $item['name'] ?></a>
                    <span class="smaller">(<?php echo $item['click'] ?> click)</span>
                </li>
            </ul>
        <?php endforeach ?>
        <?php if (!empty(ZenView::$D['blog']['attachments']['file'])) foreach(ZenView::$D['blog']['attachments']['file'] as $item): ?>
            <ul class="blog-info">
                <li>
                    <i class="fa fa-download"></i>
                    <a href="<?php echo $item['link'] ?>" title="<?php echo $item['name'] ?>" rel="nofollow"><?php echo $item['name'] ?></a>
                    <span class="smaller">(<?php echo $item['down'] ?> lượt tải)</span>
                </li>
            </ul>
        <?php endforeach ?>
    <?php endif ?>
    <!-- end blog attachments -->
    <?php if (modConfig('allow_post_comment', 'blog')): ?>
        <!-- Blog comments -->
        <h2>Bình luận</h2>
        <div class="comments">
            <?php ZenView::display_message('comments-list') ?>
            <?php if (ZenView::$D['blog']['comments']) foreach (ZenView::$D['blog']['comments'] as $cmt): ?>
                <div class="media post-comment">
                    <?php echo ($cmt['uid'] ? '<a class="pull-left" href="' . HOME . '/account/wall/' .$cmt['user']['username']. '"><img class="media-object img-responsive post-comment-avatar" alt="64x64" src="' .$cmt['user']['full_avatar']. '" style="width: 48px; height: 48px;"></a>': '') ?>
                    <div class="media-body">
                        <div class="post-comment-msg">
                            <b class="media-heading">
                                <?php echo (empty($cmt['uid']) ? $cmt['name'] : '<a href="' . HOME . '/account/wall/' .$cmt['user']['username']. '">' . display_nick($cmt['user']['nickname'], $cmt['user']['perm']) . '</a>') ?>
                            </b>
                            <article><?php echo $cmt['msg'] ?></article>
                        </div>
                        <div class="post-comment-meta">
                            <div class="public-controls">
                                <?php echo hook('blog', 'post_comment_public_control', '<span>' . $cmt['display_time'] . '</span>', array('var' => array('cmt'=>$cmt))) ?>
                            </div>
                            <div class="private-controls">
                                <?php echo hook('blog', 'post_comment_private_control', '', array('var' => array('cmt'=>$cmt))) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            <?php ZenView::display_paging('comment') ?>
        </div>
        <div class="post-comment">
            <?php ZenView::display_message('comment') ?>
            <form method="POST">
                <?php if (!IS_MEMBER): ?>
                    <div class="form-group">
                        <label for="comment-name">Tên của bạn</label>
                        <input type="text" name="name" class="form-control" placeholder="Tên của bạn"/>
                    </div>
                <?php endif ?>
                <div class="form-group">
                    <label for="comment-msg">Nội dung</label>
                    <textarea name="msg" id="comment-msg" class="form-control"></textarea>
                </div>
                <?php if (!IS_MEMBER): ?>
                    <div class="form-group">
                        <img src="<?php echo ZenView::$D['captcha_src'] ?>"/>
                        <input type="text" name="captcha_code" class="form-control" placeholder="Mã xác nhận"/>
                    </div>
                <?php endif ?>
                <div class="form-group">
                    <input type="hidden" name="token_comment" value="<?php echo ZenView::$D['token_comment'] ?>"/>
                    <input type="submit" name="submit-comment" class="btn btn-primary" value="Bình luận"/>
                </div>
            </form>
        </div>
        <!-- end blog comments -->
    <?php endif ?>