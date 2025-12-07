<ul class="pagination pagination-sm m-0 float-right">
<?php if(isset($this->_paginacion)): ?>

	<?php if($this->_paginacion['primero']): ?>
		
		<li class="page-item" ><a href="<?php echo $link . $this->_paginacion['primero'] ; ?>" class="page-link">Primero</a></li>
	
	<?php else: ?>
		
		<li class="page-item" ><a href="#" class="page-link">Primero</a></li>
	
	<?php endif; ?>
		

	&nbsp;

	<?php if($this->_paginacion['anterior']): ?>
		
		<li class="page-item" ><a href="<?php echo $link . $this->_paginacion['anterior'] ; ?>" class="page-link">Anterior</a></li>
	
	<?php else: ?>
		
		<li class="page-item" ><a href="#" class="page-link">Anterior</a></li>
	
	<?php endif; ?>
	
	&nbsp;
       <?php if($this->_tipoRango):?>
		<?php for($i=0; $i < count($this->_paginacion['rango']);$i++):?>
		
			<?php if($this->_paginacion['actual']== $this->_paginacion['rango'][$i]):?>
			
				<li class="page-item" ><a href="#" class="page-link"><?php echo $this->_paginacion['rango'][$i];?></a></li>
			<?php else: ?>
						
				<li class="page-item" ><a href="<?php echo $link . $this->_paginacion['rango'][$i]; ?>" class="page-link" >
					<?php echo $this->_paginacion['rango'][$i];?>
				</a></li>		
			
			<?php endif; ?>	
	
		<?php endfor; ?>
               <?php endif; ?>                 
	&nbsp;

	<?php if($this->_paginacion['siguiente']): ?>
		
		<li class="page-item" ><a href="<?php echo $link . $this->_paginacion['siguiente']; ?>" class="page-link" >Siguiente</a></li>
	
	<?php else: ?>
		
		<li class="page-item" ><a href="#" class="page-link">Siguiente</a></li>
	
	<?php endif; ?>
	
	&nbsp;

	<?php if($this->_paginacion['ultimo']): ?>
		
		<li class="page-item" ><a href="<?php echo $link . $this->_paginacion['ultimo'] ; ?>" class="page-link" >Ultimo</a></li>
	
	<?php else: ?>
		
		<li class="page-item" ><a href="#" class="page-link">Ultimo</a></li>
	
	<?php endif; ?>
	
<?php endif; ?>
</ul>