<?php
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

$model = $obj->model->get('blog'); //get blog model
ZenView::add_js(_URL_MODULES . '/blog/js/manager/get.manager.js', 'foot');
$base_url = HOME . '/admin/general/modulescp?appFollow=blog/manager';

$page_title = 'Get Multi';
ZenView::set_title($page_title);
$tree[] = url($base_url, 'Quản lí blog');
$tree[] = url($base_url . '/tools', 'Tools');
$tree[] = url($base_url . '/get', 'Get Multi');
ZenView::set_breadcrumb($tree);

$model->anti_flood();
$data['tree_folder'] = array();
$id_fol = array(6, 5, 4, 3, 2, 1);
$data['tree_folder'][0] = 'Chọn thư mục';
foreach($id_fol as $tree){
    $da = $model->get_blog_data($tree);
    $data['tree_folder'][$tree] = $da['name'];
}
$obj->view->data = $data;
$obj->view->show('blog/manager/get');
return;