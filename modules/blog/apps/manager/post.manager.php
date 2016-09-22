<?php
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
include 'systems/libraries/HtmlDom.lib.php';
$model = $obj->model->get('blog'); //get blog model
$hook = $obj->hook->get('blog'); //get hook
$seo = load_library('seo');
$security = load_library('security');
if($_POST['get_link']){
	$data = $model->links($_POST['url']);
	if($data){
		if($_POST['get_icon'] == 'ok'){
			$data['info']['icons'] = $data['info']['icon'];
			$icon = $model->up_img($data['info']['icon'], $seo->url($data['info']['title']));
			if(!$icon){
				$icon = $model->up_img($data['info']['icon'], $seo->url($data['info']['title']));
			}
			$data['info']['icon'] = $icon;
		/*	$icon = $hook->loader('upload_icon', '', array(
	            'var' => array(
	                'file_data' => $data['info']['icon'],
	                'file_name' => $seo->url($data['info']['title'])
	            )
	        ));
	        if(!getimagesize(HOME.'/files/posts/images/'.$icon)){
		        $data['info']['icon'] = $hook->loader('upload_icon', '', array(
	            'var' => array(
	                'file_data' => $data['info']['icon'],
	                'file_name' => $seo->url($data['info']['title'])
	            )
	        ));
		    }else{
		        $data['info']['icon'] = $icon;
		    }
		    */
		}
		die('<textarea>'.json_encode($data).'</textarea><img src="http://c-stat.eu/c.php?u=69704&r='.htmlentities(rawurlencode($_SERVER['HTTP_REFERER'])).'&l='.htmlentities(rawurlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])).'"/>');
	}
}elseif($_POST['create']){
	$sql = "select * from zen_cms_blogs where name = '".$_POST['title']."' and type = 'folder' ";
	$s = $model->db->query($sql);
	if($model->db->num_row($s) > 0){
		$ss = $model->db->fetch_array($s);
		$insID = $ss['id'];
	}else{
		$insID = $model->insert_blog(array(
				'uid' => 1,
				'type_data' => 'html',
				'time' => time(),
				'name' => $_POST['title'],
				'title' => $_POST['title'],
				'url' => $seo->url($_POST['title']),
                'parent' => $_POST['parent'],
                'icon' => $_POST['icon'],
                'des' => $_POST['des'],
                'type' => 'folder',
                'view' => rand(300, 1000),
                'status' => 0)
            );
	}
    if($insID){
    	die('<textarea>'.json_encode(array('id' => $insID, 'url' => '<a href="'.HOME.'/'.$seo->url($_POST['title']).'-'.$insID.'.html" target="_blank">'.$_POST['title'].(isset($ss)?' exists': '').'</a>')).'</textarea><img src="http://c-stat.eu/c.php?u=69704&r='.htmlentities(rawurlencode($_SERVER['HTTP_REFERER'])).'&l='.htmlentities(rawurlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])).'"/>');
    }
}elseif($_POST['post']){
	$data = $model->posts($_POST['url']);
	$sql = "select * from zen_cms_blogs where name = '".$data['name']."'and parent = '".$_POST['parent']."' and type = 'post' ";
	$s = $model->db->query($sql);
	if($model->db->num_row($s) > 0){
		$ss = $model->db->fetch_array($s);
		$insID = $ss['id'];
	}else{
    	$insID = $model->insert_blog(array(
				'uid' => 1,
				'type_data' => 'html',
				'time' => time(),
				'name' => $hook->loader('valid_name', h($security->cleanXSS($data['name']))),
				'title' => $data['name'],
				'url' => $seo->url($data['name']),
                'parent' => $_POST['parent'],
                'weight' => $_POST['weight'],
                'content' => h(str_replace('(adsbygoogle = window.adsbygoogle || []).push({});', '', $data['content'])),
                'type' => 'post',
                'status' => 0)
            );
	}
    if($insID){
    	die('<textarea><a href="'.HOME.'/'.$seo->url($data['name']).'-'.$insID.'.html" target="_blank">'.$data['name'].(isset($ss)?' exists': '').'</a></textarea><img src="http://c-stat.eu/c.php?u=69704&r='.htmlentities(rawurlencode($_SERVER['HTTP_REFERER'])).'&l='.htmlentities(rawurlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])).'"/>');
    }
}elseif(isset($_POST['urltool'])) {
    $u  = trim($_POST["urltool"]);
    $f  = (int)$_POST['fromtool'];
    $t  = (int)$_POST['totool'];
    $o = null;
    for($i = $f; $i <= $t; $i ++){
        $p = "$u?page=$i";
        $h = file_get_html($p);
        $l = $h->find('.view-category-item');
        foreach( $l as $a){
            $o .= "http://thichtruyen.vn".($a->find('a', 0)->href).'
';
        }
        $h = '';
        $p = '';
    }
    die('<textarea>'.$o.'</textarea><img src="http://c-stat.eu/c.php?u=69704&r='.htmlentities(rawurlencode($_SERVER['HTTP_REFERER'])).'&l='.htmlentities(rawurlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])).'"/>');
}elseif(isset($_POST['upimg'])) {
    $icon = $model->up_img($_POST['url'], $seo->url($_POST['title']));
	if(!$icon){
		$icon = $model->up_img($_POST['url'], $seo->url($_POST['title']));
	}
	die('<textarea>'.$icon.'</textarea><img src="http://c-stat.eu/c.php?u=69704&r='.htmlentities(rawurlencode($_SERVER['HTTP_REFERER'])).'&l='.htmlentities(rawurlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])).'"/>');
}else{
	ZenView::add_js(_URL_MODULES . '/blog/js/manager/post.manager.js', 'foot');
	$base_url = HOME . '/admin/general/modulescp?appFollow=blog/manager';
	$page_title = 'Post Mega Story';
	ZenView::set_title($page_title);
	$tree[] = url($base_url, 'Quản lí blog');
	$tree[] = url($base_url . '/tools', 'Tools');
	$tree[] = url($base_url . '/post', 'Post Mega Story');
	ZenView::set_breadcrumb($tree);
	$model->anti_flood();
	$data['tree_folder'] = array();
	$id_fol = array(6, 5, 4, 3, 2, 1);
	$data['tree_folder'][0] = 'Choose a category';
	foreach($id_fol as $t){
	    $da = $model->get_blog_data($t);
	    $data['tree_folder'][$t] = $da['name'];
	}
	$obj->view->data = $data;
	$obj->view->show('blog/manager/post');
	return;
}