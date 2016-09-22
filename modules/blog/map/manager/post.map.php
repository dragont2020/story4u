<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::padded(function() {
        ZenView::display_message();
        ZenView::col(function() {
            ZenView::col_item(4, function() {
    			echo '<p><input type="checkbox" id="auto_get" name="auto_ac" checked /><label for="auto_get"> automatically get link</label></p>';
            	echo '<p><select class="form-control" onchange="change_pa(this.value)" id="parent" style="color: #000; background-color: darkgrey;">';
                foreach (ZenView::$D['tree_folder'] as $id => $name) {
                    echo '<option value="' . $id . '">' . $name . '</option>';
            	}
           	 	echo '</select></p>';
                echo '<p>URL: <strong id="comma"></strong><textarea class="form-control" name="urls" id="urls" style="min-height: 450px;max-width:100%;color: black;background-color: darkgrey;white-space: pre;"></textarea></p>
    			<p><input type="checkbox" name="get_icon" id="get_icon" checked /><label for="get_icon"> auto import icon</label></p>
    			<input type="button" onclick="auto_run()" id="get" value="Get URL" class="btn btn-primary rm-fill-up" />
                <input type="button" id="tool" value="Tool" class="btn btn-success rm-fill-up" data-toggle="modal" data-target="#modaltool"/>
                <div class="modal fade" id="modaltool" role="dialog">
	                <div class="modal-dialog" style="background-color: gray;">
		                  <div class="modal-content">
			                    <div class="modal-header">
			                      <button type="button" class="close" data-dismiss="modal">&times;</button>
			                      <h4 class="modal-title">Tool For Post</h4>
			                    </div>
			                    <div class="modal-body">
			                      <p>URL main:  <select id="urltool" class="w3-input">
			                      		<option>Please choose the categories </option>
										</select></p>
			                      <p>From: <input type="number" id="fromtool" value="1" min="1" class="w3-input"/></p>
			                      <p>To: <input type="number" id="totool" value="1" min="1" class="w3-input"/></p>
			                      <p><input type="button" onclick="tool()" value="Create" class="btn btn-primary" data-dismiss="modal"/></p>
			                    </div>
		                  </div>
	                </div>
              </div>';
              /*echo'
              <div class="modal fade" id="ks" role="dialog">
				<div class="modal-dialog" style="background-color: gray;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Keyboard shortcut</h4>
						</div>
						<div class="modal-body">
							<p><code>[1-6]</code>------------------------------- category id from 1 to 6</p>
							<p><code>[Tab]</code>------------------------------- open modal tool</p>
							<p><code>[Enter]</code>---------------------------- get link</p>
							<p><code>[Q]</code>---------------------------------- check automatically</p>
							<p><code>[W]</code>---------------------------------- create folder</p>
							<p><code>[E]</code>---------------------------------- reupload icon</p>
							<p><code>[R]</code>---------------------------------- post</p>
							<p><code>[S]</code>---------------------------------- show/hide working part</p>
						</div>
					</div>

				</div>
			  </div>
              ';*/
            });
            ZenView::col_item(8, function() {
            	echo '<div id="result" class="w3-content" style="display: none;">
            		<ul class="nav nav-tabs">
					    <li class="active"><a data-toggle="tab" href="#info_tab">Info</a></li>
					    <li><a data-toggle="tab" href="#link_tab">Link</a></li>
				  	</ul>
				  	<div class="progress" style="display: none;" id="main_bar">
					  <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:0%">
					    0%
					  </div>
					</div>
				  	<div class="tab-content">
					    <div id="info_tab" class="tab-pane fade in active">
					    	<div class="col-md-6 ">
			                    <div class="item">
			                            <img src="'.HOME . '/files/systems/images/default/blog_icon.png">
			                            <div class="title_post" id="parent" style="top: -2px">Post Parent</div>
										<div class="title_post" id="title" style="bottom: 78px">Post Title</div>
			                            <input type="text" id="icon_url" class="w3-input" style="margin-top: 10px;" />
			                            <a onclick="create()" class="btn btn-primary"><i class="fa fa-folder"></i> Create</a>
										<a onclick="re_up()" id="re_up" class="btn btn-primary" style="float: right;"><i class="fa fa-upload"></i> Upload</a>
			                    </div>
							</div>
		                    <div class="col-md-offset-6 ">
								<div id="des" style="overflow: auto;max-height: 500px; max-width:100%"></div>
							</div>
					    </div>
					    <div id="link_tab" class="tab-pane fade">
							<div id="link" style="overflow: auto; max-height: 510px">
							</div>
							<a class="btn btn-primary" id="all"><i class="fa fa-check-square-o"></i> All</a>
							<a class="btn btn-primary" id="num"></a>
							<a onclick="posts()" class="btn btn-primary w3-right"><i class="fa fa-star"></i> Post</a>
					    </div>
					  </div>
                </div>';
            });
        });
    });
});