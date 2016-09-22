<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::col_item(4, function() {
    				echo '<form class="fill-up" id="post-editor" method="POST" enctype="multipart/form-data">';
                    echo '<div class="form-group">';

                    echo '<label>URL:</label><br><input type="checkbox" id="auto" />Tự động thêm dấu phẩy
                                <textarea class="form-control" style="min-height: 400px" name="url" id="url" placeholder="'.(isset($_POST['url'])?$_POST['url']:'http://').'" ></textarea>
                            </div><button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Tool</button><br>';
                    echo '<input type="checkbox" name="check" checked /> get multi link<br>';
                    echo '<input type="checkbox" name="auto_img" checked /> auto get icon<br>';
                    echo '<input type="submit" name="ok" class="btn btn-primary" value="Get Link"/></form>
                    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Sub Link</h4>
        </div>
        <div class="modal-body">
          <p>From: <input type="number" id="s_link" style="width: 75px" value="0" disabled />
          To: <input type="number" id="e_link" style="width: 75px" value="9"/> pages</p>
          <p><input type="checkbox" id="skip0" /> skip 0</p>
          <p>Link: <input type="text" id="sublink" style="width: 400px" /></p>
          <p>Output:</p>
          <p>link + 0,</p>
          <p>...,</p>
          <p>...,</p>
          <p>link + to</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal" id="sub">Ok</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>';
                });
        ZenView::col_item(8, function() {
        	ZenView::display_message();
    				if(isset($_POST['ok'])){
							echo'<div class="form-group"><div class="tab">';
							echo '<ul class="nav nav-tabs nav-tabs-left">
                        			<li class="active"><a href="#folder" data-toggle="tab"><i class="fa fa-folder"></i> <span>Folder</span></a></li>
                        			<li><a href="#image" data-toggle="tab"><i class="fa fa-image"></i> <span>Icon</span></a></li>	
                        			<li><a href="#des" data-toggle="tab"><i class="fa fa-tags"></i> <span>Des</span></a></li>	
                        
                    			</ul>
                    			<div class="tab-content tab-box-content padded">
                    				<div class="tab-pane active" id="folder">
                            		
                                
                            			';
                    		echo '<select class="form-control" name="in" id="in">';
                    		$in = isset($_POST['in'])?$_POST['in']:0;
                    		foreach (ZenView::$D['tree_folder'] as $id => $name) {
                        			echo '<option value="' . $id . '">' . $name . '</option>';
                    		}
                    		echo '</select>';
                    		echo '<div class="form-inline">
                    		<input type="text" class="form-control" id="name_folder" value="'.ZenView::$D['leech_multi'].'" style="width:550px" />
                    		<input type="text" class="form-control" id="id_folder" value="" style="max-width:100px" />
                    		<input type="button" id="create" class="btn btn-primary" value="Create" /></div>';
                    		$tong = ZenView::$D['leech_muti_url'];
                    		$img = preg_match('|http://|', ZenView::$D['leech_multi_img'])?ZenView::$D['leech_multi_img']:_URL_FILES . '/posts/images/' . ZenView::$D['leech_multi_img'];
                    		echo'
                        			</div>
                        			<div class="tab-pane" id="image">
                            			<div class="form-group">
                            				<div class="input-group addon-right">
                                				<input type="text" class="form-control" name="input-upload-icon-url" id="url_img" value="'.ZenView::$D['leech_multi_img_url'].'"/>
                                				<a class="input-group-addon" id="up_img" title="Upload" style="cursor: pointer"><i class="fa fa-upload"></i></a>
                            				</div>
                            			<div id="result" class="clearfix"><img src="'.$img.'"/></div>
                                		<br><input type="text" class="form-control" id="img" value="'.ZenView::$D['leech_multi_img'].'"/>
                                		<br><input type="checkbox" id="check_img" checked /> use this image
                        				</div>
                    				</div>
                        			<div class="tab-pane" id="des">
                            			<div class="form-group">
                            				<div class="input-group addon-right">
                                				Des: <textarea style="min-height: 400px;width:100%" id="mota">'.ZenView::$D['leech_multi_des'].'</textarea>
                            				</div>
                        				</div>
                    				</div>
                    		</div>';
                    		
                    		
							echo'<br>
							<button id="leech" class="btn btn-primary" />Leech</button><br>
							Elapsed Time: giờ <b id="h"></b> phút <b id="m"></b> giây <b id="s"></b><br>
							Time Remaining: giờ <b id="he"></b> phút <b id="me"></b> giây <b id="se"></b><br>
							</div><input type="hidden" id="tong" value="'.count($tong).'" />
							<div class="progress" id="pt">
    						<div class="progress-bar progress-bar-striped active" role="progressbar" id="phantram" style="width:0%">
							0%
    						</div>
  							</div>
  							<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#info">Info</button>';
							echo'<div id="info" class="collapse">
							<input type="button" id="check-all" class="btn btn-success" value="Check All" />
							<input type="button" id="uncheck-all" class="btn btn-warning" value="UnCheck All" />
							<i class="fa fa-check btn btn-success" id="ok">0</i>
							<i class="fa fa-ban btn btn-danger" id="error">0</i>
							<i class="fa fa-exclamation-triangle btn btn-primary" id="warning">0</i>
							<div style="max-height :300px ;max-width :999px ;overflow:scroll;" id="noidung">';
							foreach($tong as $key => $value){
								$key = ++$key ;
								if(count($tong)>=100){
									if($key<100)$ad[$key] = "0";
									if($key<10)$ad[$key] .= '0';
								}elseif(count($tong)>=10){
									if($key<10)$ad[$key] = '0';
								}
								echo '<div class="form-control" id="id-'.$key.'">
								'.$ad[$key].$key.'. <input type="checkbox" id="check-'.$key.'" checked />
								<span id="link-'.$key.'">'.$value.'</span>
								<i class="fa fa-refresh fa-spin fa-2x fa-fw" id="loading-'.$key.'" style="display: none"></i>
								<i class="fa fa-ban" id="error-'.$key.'" style="color: red;display: none"></i>
								<i class="fa fa-check" id="ok-'.$key.'" style="color: green;display: none"></i>
								<i class="fa fa-exclamation-triangle" id="warning-'.$key.'" style="color: blue;display: none"></i>
								<i id="note-'.$key.'"></i></div>';
							}
							echo'</div></div>';
							
						
					}
                });
            
});