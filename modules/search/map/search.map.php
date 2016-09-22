<?php
ZenView::block('Tìm Kiếm', function() {
    ZenView::padded(function() {
        $tree = '<option value="0">Tất cả</option>';
        foreach (ZenView::$D['result'] as $item) {
                        $tree .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
                	}
        echo '
     <h2>Search For Names</h2><form  style="color: #000"><select class="w3-right">'.$tree.'</select>
     <input type="text" id="search" class="w3-input w3-border w3-hover-green">
     <div id = "livesearch"></div>
  </form>';
    });
});
