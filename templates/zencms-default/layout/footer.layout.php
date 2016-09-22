<span class="banner" style="top: 100px; left: 10px;">
	<?php if(!isset($_COOKIE['theme'])||$_COOKIE['theme']=='light'){ ?>
		<a href="/change_themes.php?theme=night"><i class="fa fa-moon-o" style="font-size: 40px; color: black" ></i></a>
	<?php }else{ ?>
		<a href="/change_themes.php?theme=light"><i class="fa fa-sun-o" style="font-size: 40px; color: yellow" ></i></a>
	<?php } ?>
</span>

<span class="banner" id="clock-content" style="bottom: 150px; right: 10px;">
	<div class="dark" id="clock" style="display: none">
		<div class="display">
			<div class="weekdays"></div>
			<div class="ampm"></div>
			<div class="digits"></div>
		</div>
	</div>
	<i class="fa fa-clock-o" style="font-size: 40px; color: inherit;" ></i>
</span>

<span class="banner" id="scrolltop" style="bottom: 100px; right: 10px;">
<i class="fa fa-arrow-circle-o-up" style="font-size: 40px; color: inherit"></i>
</span>
<span class="banner" id="scrollbottom" style="bottom: 50px; right: 10px;">
<i class="fa fa-arrow-circle-o-down" style="font-size: 40px; color: inherit"></i>
</span>


<script src="<?php echo HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/moment.min.js"></script>
<!-- BEGIN FOOTER -->
<div style="height: 22px"></div>
<div style="background: #272626;	color: #fff;	position: fixed;	bottom: 0px;	right: 0px;	left: 0px; max-height: 22px">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3">
	            2016 &copy; DragonT
	        </div>
	        <div class="col-md-6 col-sm-6" align="center">
	            <ul class="list-unstyled list-inline">
	            	<?php if (is(ROLE_MANAGER)): ?>
                        <li class="w3-tooltip"><i class="fa fa-cogs"></i><span class="w3-text"><a href="<?php echo HOME ?>/admin"> Admin CP</a></span></li>
                    <?php endif ?>
	                <li class="w3-tooltip"><i class="fa fa-envelope"></i>
	                    <span class="w3-text">hotro@mstory.ga</span></li>
	                <li class="w3-tooltip"><i class="fa fa-facebook-square"></i>
	                    <span class="w3-text"><a href="http://fb.com/thuanlegends"> Thánh Thuận</a></span></li>
	            </ul>
	        </div>
            <div style="float: right;">
				<img src='http://c-stat.eu/c.php?u=69704' id="onl"/>
			</div>
        </div>
    </div>
</div>
<!-- END FOOTER -->