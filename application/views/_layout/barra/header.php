<!DOCTYPE html>
<html lang="es">
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
                
      
    
    <style>
    body{
    
    justify-content:bottom;
    aling-items:center;
    min-height:100vh;
    background:url('<?php echo BASE_IMG ?>back.png') no-repeat;
    background-size:cover;
    backgroud-position:bottom;

    }
</style>
  </head>

<body>

   