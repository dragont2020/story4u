 <?php
define('__ZEN_KEY_ACCESS', 'DragonT');
include 'systems/includes/DBDriver.php';
$db = new DB_driver;
function page($q = '', $p = '', $t = '', $np = ''){
    global $db;
    $s    = ($p - 1) * 10;
    $hint = "";
    $sql  = $db->query("select * from zen_cms_blogs where type = 'folder' $t and status = 0 and parent != 0 and name LIKE '%$q%' order by name limit $s, 10");
    if ($sql) {
        $total   = count($db->query("select * from zen_cms_blogs where type = 'folder' $t and status = 0 and parent != 0 and name LIKE '%$q%'"));
        $total_p = ceil($total / 10);
        $hint .= '<ul class="w3-ul">';
        foreach ($sql as $d) {
            $cat = $db->select('zen_cms_blogs', array('id' => $d['parent']));
            $hint .= '<li>
            <a href="' . $d['url'] . '-' . $d['id'] . '.html" title="' . $d['title'] . '">' . $d['name'] . '</a>
            ' . ($np == '' ? '<a class="w3-right" href="' . $cat[0]['url'] . '-' . $cat[0]['id'] . '.html" title="' . $cat[0]['title'] . '">' . $cat[0]['name'] . '</a></li>' : '');
        }
        $hint .= '</ul>';
        if ($total_p > 1 && $np == '') {
            $start = 1;
            $end   = 7;
            if ($p >= 5)                $start = $p - 3;
            if ($p >= 5)                $end = $p + 3;
            if ($end > $total_p)                $end = $total_p;
            $hint .= '<ul class="w3-pagination" style="float: right;">';
            if ($p - 4 > 1)                $hint .= '<li><a href="#page1" onclick="showResult(1)" class="w3-btn w3-cyan">1</a></li><li><a class="w3-btn w3-grey">...</a></li>';
            if ($p > 1)                $hint .= '<li><a href="#page' . ($p - 1) . '" onclick="showResult(' . ($p - 1) . ')" class="w3-btn w3-orange"><</a></li>';
            for ($i = $start; $i <= $end; $i++) {
                $hint .= '<li><a href="#page' . $i . '" onclick="showResult(' . $i . ')" class="w3-btn ' . (($p == $i) ? 'w3-purple' : 'w3-dark-grey') . '">' . $i . '</a></li>';
            }
            if ($p < $total_p)                $hint .= '<li><a href="#page' . ($p + 1) . '" onclick="showResult(' . ($p + 1) . ')" class="w3-btn w3-orange">></a></li>';
            if ($p + 4 < $total_p && $total_p > $end)                $hint .= '<li><a class="w3-btn w3-grey">...</a></li><li><a href="#page' . $total_p . '" onclick="showResult(' . $total_p . ')" class="w3-btn w3-pink">' . $total_p . '</a></li>';
            $hint .= '</ul>';
        } elseif ($total > 10)            $hint .= '<a href="/search" class="w3-btn w3-right"> Tìm thêm</a>';
    } else {
        $hint = '<p style="color: red">Not Found</p>';
    }
    return $hint;
}
function up($url, $title = '', $dir = 'files/posts/images/'){
    $ext = explode('.', basename($url));
    $d = $dir . date("m-Y");
    if (!is_dir($d))        mkdir($d);
    if(!is_dir($d.'/thumb'))    mkdir($d.'/thumb');
    $name = date("m-Y") . '/' . to_slug($title) . '.' . end($ext);
    $file = $dir . $name;
    $put  = file_put_contents($file, file_get_contents($url));
    if ($put) {
        /*$image = new SimpleImage();
        $image->load($file);
        $image->resize(270,300);
        $image->save($d.'/thumb/'.basename($name));
        */return $name;
    } else return false;
}

if (isset($_POST['url_icon']) && isset($_POST['title_icon'])) {
    $url   = $_POST['url_icon'];
    $title = $_POST['title_icon'];
    $up    = up($url, $title);
    if ($up) {
        echo $up;
    }
}elseif(isset($_GET['q'])) {
    $q  = trim($_GET["q"]);
    $p  = isset($_GET['p']) ? $_GET['p'] : 1;
    $t  = (isset($_GET['t']) && $_GET['t'] != 0) ? 'and parent = ' . $_GET['t'] : '';
    $np = (isset($_GET['np'])) ? 'off' : '';
    echo page($q, $p, $t, $np);
}else{
	header("Location: /sitemap.xml");
}
?>