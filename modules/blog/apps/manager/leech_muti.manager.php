<?php

if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

$model = $obj->model->get('blog'); //get blog model
$model->set_filter('status', array(0, 1, 2));
$user = $obj->user; //load user data
$hook = $obj->hook->get('blog'); //get hook

/**
 * load helpers
 */
load_helper('blog_access_app', array('module' => 'blog'));
load_helper('gadget');
load_helper('formCache');


ZenView::add_jquery('jquery-3.1.0.min.js', 'head');
ZenView::add_js('ajax.js', 'head');
/**
 * set base url for this page
 */
$base_url = HOME . '/admin/general/modulescp?appFollow=blog/manager';


if($_POST['ok']||$_POST['url']){
	$url = $_POST['url'];
	$out = array();
	if($_POST['check']==true){
		if(preg_match('|,|', $url)){
			$u1 = explode(',', $url);
			foreach($u1 as $u2){
				$out = array_merge($out, $model->get_url(trim($u2)));	
			}
		}else{
			$out = $model->get_url(trim($url));
		}
	}else{
		if(preg_match('|,|', $url)){
			$u1 = explode(',', $url);
			foreach($u1 as $u2){
				$out = trim($u2);	
			}
		}else{
			$out[0] = trim($url);
		}
	}
	if($_POST['auto_img']==true){
		$data['leech_multi_img'] = $hook->loader('upload_icon', '', array(
            'var' => array(
                'file_data' => $out['img'],
                'file_name' => randStr(10),
                'pos_message' => ZPUBLIC
            )
        ));	
	}else{
		$data['leech_multi_img'] = $out['img'];
	}
	$data['leech_multi_img_url'] = $out['img'];
	$data['leech_multi'] = $out['name'];
	$data['leech_multi_des'] = $out['des'];
	unset($out['name']);
	unset($out['des']);
	unset($out['img']);
	$data['leech_muti_url'] = $out;
	
	ZenView::set_success(count($out).' link được get thành công');
	
	
}
$data['base_url'] = $base_url;
/**
 * set page title
 */
$page_title = 'Leech Multi Story';
ZenView::set_title($page_title);
$tree[] = url($base_url, 'Quản lí blog');
$tree[] = url($base_url . '/leech_muti', 'Leech Multi Story');
ZenView::set_breadcrumb($tree);
$data['tree_folder'] = $model->get_tree_folder();
$obj->view->data = $data;
$obj->view->show('blog/manager/leech_muti');
return;