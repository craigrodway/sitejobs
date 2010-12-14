<?php require('inc/init.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Bishop Barrington Site Jobs</title>
	<link rel="shortcut icon" href="web/img/favicon.ico" type="image/x-icon" /> 
	<link rel="icon" href="web/img/favicon.ico" type="image/x-icon" />
	<link href="web/css/960.css" media="screen, projection" rel="stylesheet" type="text/css" />
	<link href="web/css/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<style type="text/css">.clear{ zoom: 1; display: block; }</style>
	<![endif]--> 
</head>
<body>
	
	
	<div id="fullwidth">
		<div class="container_24" id="head">
			
			<div class="grid_12" id="head-left">
				<h1><a href="">Bishop Barrington Site Jobs</a></h1>
			</div>
			
			<div class="grid_12" id="head-right">
				<a href="http://intranet/">&larr; back to intranet</a>. 
				<span id="count-open"></span> open jobs. <span id="count-user"></span> assigned to me.
			</div>
			
			<div class="clear"></div>
			
		</div>
	</div>
	
	
	
	
	<div class="container_24">
		
		<div class="grid_18" id="main">
			<h2 id="title"></h2>
			<div id="main-list">
				<?php echo $content ?>
			</div>
		</div>
		
		<div class="grid_6" id="sidebar">
			<div class="box"><div>
				<h2>Show...</h2>
				<a class="button new_bg" href="#/new">New</a><br />
				<a class="button open_bg" href="#/open">In Progress</a><br />
				<a class="button mine_bg" href="#/mine">Mine</a><br />
				<a class="button closed_bg" href="#/closed">Completed</a><br />
			</div></div>
			
			<br />
			
			<div class="box"><div>
				<h2>Search</h2>
				<table class="simple">
					<tr>
						<td><label for="room">ID</label></td>
						<td><input type="text" name="id" id="search_id" size="6" /></td>
					</tr>
					<tr>
						<td><label for="room">Room</label></td>
						<td><input type="text" name="room" id="search_room" size="10" /></td>
					</tr>
					<tr>
						<td><label for="room">Creator</label></td>
						<td><select name="creator" id="search_creator">
							<option value="">(any)</option>
							<?php
							$creators = lookup_creators();
							foreach($creators as $creator){
								printf('<option value="%1$s">%1$s</option>', $creator);
							}
							?>
						</select></td>
					</tr>
					<tr>
						<td><label for="room">Type</label></td>
						<td><select type="text" name="type" id="search_type">
							<option value="">(any)</option>
							<option value="damage">Damage</option>
							<option value="replacement">Replacement</option>
							<option value="wear-and-tear">Wear &amp; Tear</option>
							<option value="fault">Fault</option>
						</select></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="search" value="Find" /></td>
					</tr>
				</table>
			</div></div>
		</div>
		
	</div>
	
	<script type="text/javascript" src="javascript.php" charset="utf-8"></script>
	<script type="text/javascript" src="http://intranet/v3/asp.sitejobs-auth.asp"></script>
	
	<div id="dialog_source" class="jqmWindow"></div>
	
</body>
</html>
