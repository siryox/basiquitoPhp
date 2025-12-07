<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo APP_NAME ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
     
    <!-- Google Font: Source Sans Pro -->
    <?php view::tagCss('fonts/google/sourceSansPro')  ?>
    
    <!-- Font Awesome Icons -->    
    <?php //view::tagPlugingCss('plugins/fontawesome-free/css/all')  ?>

	

	<?php view::tagPlugingCss('plugins/datatables-bs4/css/dataTables.bootstrap4.min') ?>
	<?php view::tagPlugingCss('plugins/datatables-responsive/css/responsive.bootstrap4.min') ?>
	<?php view::tagPlugingCss('plugins/datatables-buttons/css/buttons.bootstrap4.min') ?>
	<?php view::tagPlugingCss('plugins/select2/css/select2.min') ?>
	<?php view::tagPlugingCss('plugins/select2-bootstrap4-theme/select2-bootstrap4.min') ?>
   
    <!-- Ionicons 2.0.0  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <?php view::tagCss('fonts/ionicons/ionicons.min')  ?>
    <?php view::tagCss('fonts/font-awesome-4.5.0/css/font-awesome.min')  ?>
	<?php view::tagCss('fonts/fontawesome-6.6.0/css/all')  ?>
    
	 <!-- Theme style -->
    <?php view::tagCss('dist/css/adminlte')  ?>
    
    
    <!-- jquery-ui -->    
    <?php //view::tagCssPublic('jquery-ui.theme')  ?>
    
    <!-- Bootstrap 3.3.4 -->
     <?php //view::tagCss('bootstrap')  ?>
    
 
    <?php if(isset($_layoutParams['css']) && count($_layoutParams['css'])):?>
    <?php for($i = 0; $i < count($_layoutParams['css']);$i++): ?>
           <link href="<?php echo $_layoutParams['css'][$i] ?>" rel="stylesheet" type="text/css">
    <?php endfor ?>
    <?php endif ?>
                
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

            
  </head>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php view::partial('barra', $_layoutParams) ?>
      
      <!-- Left side column. contains the logo and sidebar -->
      <?php view::partial('menu', $_layoutParams) ?>
		
      


		<!-- Content Wrapper. Contains page content -->
		  <div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
			  <div class="container-fluid">
				<div class="row mb-2">
				  <div class="col-sm-6">
					<h1 class="m-0"><?php if(isset($this->title))echo ucfirst($this->title) ?></h1>
				  </div><!-- /.col -->
				  <div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
					<!--  <li class="breadcrumb-item"><a href="<?php echo BASE_URL ?>/intranet/index">Home</a></li>
					  <li class="breadcrumb-item active">Dashboard v3</li>-->
					</ol>
				  </div><!-- /.col -->
				</div><!-- /.row -->
			  </div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<div class="content">
				<div class="container-fluid">
				<?php if(isset($this->error)): ?>
				<li class="alert alert-danger alert-dismissible " role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-exclamation"></i> <?php echo $this->error; ?>
				</li>
				<?php endif; ?>
				<?php if(isset($this->mensaje)): ?>
						<li class="alert alert-success alert-dismissible " role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-comments"></i> <?php echo $this->mensaje; ?>
					</li>
				<?php endif; ?>
				<?php if(isset($this->info)): ?>
						<li class="alert alert-warning alert-dismissible " role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-info"></i> <?php echo $this->info; ?>
					</li>
				<?php endif; ?>









        
            	







