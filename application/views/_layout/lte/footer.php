</div>
</div><!-- /.content-wrapper -->
</div>
<footer class="main-footer">
     <div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
     </div>
        <strong>Copyright &copy; 2024 <a href="#">Sistematic C.A</a>.</strong> All rights reserved.
</footer>

     <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
	  </aside><!-- /.control-sidebar -->
      
</div><!-- ./wrapper -->

        
           
    <!-- jQuery 2.1.4 -->
    <?php //view::tagJsPublic('jquery') ?>
 
    <!-- Bootstrap 3.3.2 JS -->
    <?php //view::tagJs('bootstrap') ?>
      
    <?php view::tagJs('plugins/jquery/jquery.min') ?>
    
    <?php view::tagJs('plugins/bootstrap/js/bootstrap.bundle.min') ?>
    
    <?php view::tagJs('dist/js/adminlte') ?>
    
    <?php view::tagJs('plugins/chart.js/Chart.min') ?>
    
    <?php //view::tagJs('dist/js/demo') ?>
    
    <?php //view::tagJs('dist/js/pages/dashboard3') ?>

    <!-- DataTables  & Plugins -->
    <?php view::tagJs('plugins/datatables/jquery.dataTables.min') ?>
    <?php view::tagJs('plugins/datatables-bs4/js/dataTables.bootstrap4.min') ?>
    <?php view::tagJs('plugins/datatables-responsive/js/dataTables.responsive.min') ?>
    <?php view::tagJs('plugins/datatables-responsive/js/responsive.bootstrap4.min') ?>
    <?php view::tagJs('plugins/datatables-buttons/js/dataTables.buttons.min') ?>
    <?php view::tagJs('plugins/datatables-buttons/js/buttons.bootstrap4.min') ?>
    <?php view::tagJs('plugins/jszip/jszip.min')?>
    <?php view::tagJs('plugins/pdfmake/pdfmake.min') ?>
    <?php view::tagJs('plugins/pdfmake/vfs_fonts') ?>
    <?php view::tagJs('plugins/datatables-buttons/js/buttons.html5.min') ?>
    <?php view::tagJs('plugins/datatables-buttons/js/buttons.print.min') ?>
    <?php view::tagJs('plugins/datatables-buttons/js/buttons.colVis.min') ?>
    <?php view::tagJs('plugins/select2/js/select2.full.min') ?>
    <?php view::tagJs('plugins/bootstrap-switch/js/bootstrap-switch.min') ?>
    
    
    
    
    <?php view::tagJs('plugins/moment/moment.min')  ?>
    <?php view::tagJs('plugins/inputmask/jquery.inputmask.min')  ?>    


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <?php if(isset($_layoutParams['js']) && count($_layoutParams['js'])):?>
    <?php for($i = 0; $i < count($_layoutParams['js']);$i++): ?>
        <script  src="<?php echo $_layoutParams['js'][$i] ?>"  type="text/javascript"></script>          
    <?php endfor ?>
    <?php endif ?>  
  </body>
</html>
