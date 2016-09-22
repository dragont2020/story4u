<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::padded(function() {
        ZenView::display_message();
        ZenView::col(function() {
            ZenView::col_item(4, function() {
    			echo '<p><input type="checkbox" id="auto_comma" /><label for="auto_comma">automatically add line</label></p>
                <p><input type="checkbox" id="auto_get" checked /><label for="auto_get">automatically get link</label></p>';
            	echo '<p><select class="form-control" id="parent">';
                foreach (ZenView::$D['tree_folder'] as $id => $name) {
                    echo '<option value="' . $id . '">' . $name . '</option>';
            	}
           	 	echo '</select></p>';
                echo '<p>URL: <strong id="comma"></strong><textarea class="form-control" name="urls" id="urls" style="min-height: 400px; max-width:100%"></textarea></p>
    			<p><input type="checkbox" name="get_icon" id="get_icon" accesskey="q" checked /><label for="get_icon">auto import icon</label></p>
    			<input type="button" onclick="auto_all()" id="get" value="Get URL" class="btn btn-primary rm-fill-up" />
                <input type="button" id="tool" value="Tool" class="btn btn-success rm-fill-up" data-toggle="modal" data-target="#modaltool" />
                <div class="modal fade" id="modaltool" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Tool Of Tool</h4>
                    </div>
                    <div class="modal-body">
                      <p>URL main: <input type="text" id="urltool" class="form-control"/></p>
                      <p>Page: </p>
                      <p>From <input type="number" id="fromtool" value="1" /></p>
                      <p>To <input type="number" id="totool" value="1" /></p>
                      <p><input type="button" id="cretool" value="Create" class="btn btn-primary" data-dismiss="modal"/></p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div>
              ';

            });
            ZenView::col_item(8, function() {
            	echo '<div id="result" class="w3-content" style="display: none">
                    <ul class="nav nav-tabs">
 						   <li class="active"><a data-toggle="tab" href="#links"><i class="fa fa-link"></i>Link</a></li>
 						   <li><a data-toggle="tab" href="#icon"><i class="fa fa-image"></i>Icon</a></li>
    						<li><a data-toggle="tab" href="#des"><i class="fa fa-info"></i>Des</a></li>
    						<li><a data-toggle="tab" href="#log"><i class="fa fa-comments"></i>Log</a></li>
				 		 </ul>
                          <div class="tab-content">
                          <div class="w3-panel w3-border w3-round-large">
                          <h3 style="font-weight: bold">Link</h3>
                          <h4>Console</h4>
                        </div>
                		<div id="links" class="tab-pane fade in active">';
                	echo '<div class="form-inline" id="fol_form">
                    <div class="input-group addon-left">
                    <a class="input-group-addon" id="auto_title"><div class="loader"></div></a><input class="form-control w3-input" type="text" id="title" /></div>

   	             <a class="btn btn-primary" id="cre_fol" onclick="cre()" title="Create" style="float: right;"/>Create</a>
   	             <input type="text" id="id_parent" class="form-control w3-input" style="float: right;"/>
                	</div>
				    <div class="input-group addon-left" id="button_get"><a class="input-group-addon" id="ready_get"><div class="loader"></div></a><a class="btn btn-primary" id="get_multi" onclick="get_m()" title="Get">Start Get</a></div>
					<div class="progress" style="background-color: #d5d5d5;"><div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" style="width: 0%;"></div></div>
      				<div id="info_form"><div class="input-group addon-left">
                    <a class="input-group-addon" id="auto_info"><div class="loader"></div></a><input type="button" data-toggle="collapse" class="btn btn-info" data-target="#info" value="Info"></div>
	    <div id="info" class="collapse panel panel-info" style="max-height: 300px;overflow: scroll;">
					<div style="z-index: 4; position: fixed; right: 36px;" class="panel-heading"><input id="all" checked="" type="checkbox"><label for="all">All</label><code id="max"></code> links are ready</div>

					<div class="panel-body">
                	</div></div></div>
                </div>
                		<div id="icon" class="tab-pane fade">
                		<p class="form-inline"><input class="form-control" style="width: 90%" type="text" id="url_icon" value="" />
                		<a class="btn btn-primary" id="up_icon" title="Upload" onclick="upload()"><i class="fa fa-upload"></i></a></p>
                		<div style="height: 350px" id="view_icon"><img style="height: 350px" src=""></div>
                		<input class="form-control" style="width: 100%" type="text" id="icon_post" value="" />
                		</div>
                		<div id="des" class="tab-pane fade">
                		<textarea class="form-control" id="des_url" style="min-height: 400px; max-width:100%"></textarea>
                		</div>
                        <div id="log" class="tab-pane fade">
                		<textarea class="form-control" id="logs" style="min-height: 400px; max-width:100%">Console Log:</textarea>
                		</div>
                	</div>
                </div>';
            });
        });
    });
});