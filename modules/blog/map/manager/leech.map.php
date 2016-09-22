<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::col_item(4, function() {
    				echo '<form class="fill-up" id="post-editor" method="POST" enctype="multipart/form-data">';
                    echo '<div class="form-group">';
                    echo '<label for="input-parent">Chọn thư mục:</label>';
                    echo '<select class="form-control" name="in" id="in">';
                    $in = isset($_POST['in'])?$_POST['in']:0;
                    foreach (ZenView::$D['tree_folder'] as $id => $name) {
                        echo '<option value="' . $id . '" '.(($id==$in)?'selected':'').' >' . $name . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';

                    echo '<div class="form-group">
                                <label>URL</label>
                                <input type="text" class="form-control" style="z-index:0" name="url" id="url" placeholder="'.(isset($_POST['url'])?$_POST['url']:'http://').'"/>
                            </div>';
                    echo '<input type="submit" name="ok" value="Leech"/></form>';
                });
        ZenView::col_item(8, function() {
    				if(isset($_POST['ok'])){
						echo'<div class="form-group">
							<label for="input-parent">Name:</label>
							'.ZenView::$D['leech']['name'].'</div>';
						echo'<div class="form-group">
							<label for="input-parent">ID Cat:</label>
							'.ZenView::$D['leech']['parent'].'</div>';
						echo'<div class="form-group">
							<label for="input-parent">Content:</label>
							<textarea class="form-control" rows="17">'.ZenView::$D['leech']['content'].'</textarea></div>';
						echo'<div class="form-group">
							<label for="input-parent">Des:</label>
							<textarea class="form-control" rows="2">'.ZenView::$D['leech']['des'].'</textarea></div>';
					}
                });
            
});