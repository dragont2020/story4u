<?php
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

/**
 * load library
 */
$seo = load_library('seo');
$imgEditor = load_library('ImageEditor');
$security = load_library('security');
$valid = load_library('validation');
$blogValid = load_library('blogValid', array('module' => 'blog'));

/**
 * check access
 */
if (is_allow_access_blog_app(__FILE__) == false) {
    show_error(403);
}
/**
 * set base url for this page
 */
$base_url = HOME . '/admin/general/modulescp?appFollow=blog/manager';


if($_POST['ok']||$_POST['url']){
	$ins = $model->get_link($_POST['url'], $_POST['in']);
	$data['leech'] = $ins;
	ZenView::set_success('Lưu thành công');
	
	
}
$data['base_url'] = $base_url;
/**
 * set page title
 */
$page_title = 'Leech';
ZenView::set_title($page_title);
$tree[] = url($base_url, 'Quản lí blog');
$tree[] = url($base_url . '/cpanel/' . $blog['parent'], 'Leech Story');
ZenView::set_breadcrumb($tree);

$model->anti_flood();
$data['tree_folder'] = $model->get_tree_folder();
$obj->view->data = $data;
$obj->view->show('blog/manager/leech');
return;