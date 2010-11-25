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
		
		<div class="grid_19" id="main">
			<h2 id="title"></h2>
			<div id="main-list">
				<?php echo $content ?>
			</div>
		</div>
		
		<div class="grid_5" id="sidebar">
			<div class="box"><div>
				<h2>Show...</h2>
				<a class="button new_bg" href="#/new">New</a><br />
				<a class="button open_bg" href="#/open">In Progress</a><br />
				<a class="button mine_bg" href="#/mine">Mine</a><br />
				<a class="button closed_bg" href="#/closed">Completed</a><br />
			</div></div>
		</div>
		
	</div>
	
	<script type="text/javascript" src="javascript.php" charset="utf-8"></script>
	<script type="text/javascript" src="http://intranet/v3/asp.sitejobs-auth.asp"></script>
	
	<div id="dialog" class="jqmWindow"></div>
	
</body>
</html>
