<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 7]>
<html class="ie ie7" lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
<!--<![endif]-->
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-script-type" content="Javascript" />
	<title><?php $page_title = isset($page_title) ? $page_title : ''; template_page_title($page_title); ?></title>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.cookie.js"></script>
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/admin_style.css" type="text/css" media="all" />
	<script type="text/javascript" src="<?php echo base_url(); ?>js/admin.js"></script>
</head>

<body>

<?php if(!isset($header_silent) || $header_silent == FALSE): ?>
<div class="top-nav" role="navigation">
	<div class="top-nav-bd">
		<ul class="right-ul">
			<li><a href="<?php echo site_url('admin'); ?>">首页</a></li>
			<li class="gap"></li>
			<li><a href="<?php echo site_url('admin/admin_user/password'); ?>">修改密码</a></li>
			<li class="gap"></li>
			<li><a href="<?php echo site_url('admin/admin_user/logout'); ?>">退出</a></li>
		</ul>
		<ul class="left-ul">
			<li><a href="<?php echo site_url('admin/product'); ?>">商品列表</a></li>
			<li class="gap"></li>
			<li><a href="<?php echo site_url('admin/user'); ?>">顾客列表</a></li>
			<li class="gap"></li>
			<li><a href="<?php echo site_url('admin/order'); ?>">订单列表</a></li>
		</ul>
	</div>
</div>

<div class="header">
	<div class="header-bd">
		<div class="logo">
			<h1 class="site-title"><a href="home.php" rel="home">Max美国代购管理</a></h1>
		</div>
	</div>
</div><!-- #header -->


<div class="wrap">
	<div class="layout clearfix">
		<div class="col-menu">
			<div class="menu-bd">
				<?php template_nav_menu(); ?>
			</div>
		</div>
		<!-- #site-navigation -->
		
		<div class="col-main">
			
			<?php template_breadcrumbs(); ?>
			
			<?php if(isset($message) && !empty($message)): ?>
			<div class="msg msg-full">
				<?php
					foreach($message as $type => $val) {
						if(!is_array($val)) {
							$val = array($val);
						}
						
						foreach($val as $msg) {
							echo '<p class="'.$type.'">'.$msg.'</p>';
						}
					}
				?>
			</div>
			<?php endif;?>
			
			<div class="main-wrap clearfix">
<?php endif; ?>