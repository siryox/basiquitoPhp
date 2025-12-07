$(document).ready(function(){

    $('.select2').select2();

    $('#agregar').click(function(){

        setDatos();

    });

    $(document).on('click','#eliminar',function(){
        
        alert("El Despacho sera Anulado de forma definitiva")
        $("#form_despacho_eliminar").submit();  
 
     });
    
//-------------------------------------------------------------
//activa busqueda de productor
//------------------------------------------------------------
    $(document).on('click','#customRadio6',function(){
        
        getProductor();

    });

    $(document).on('click','#customRadio5',function(){
        
        getProveedor();

    });

    $(document).on('change','#almacen',function(){
        
        var valor = $(this).value;
        if(valor != 0)
        {
            $('#add_producto').attr("disabled",false);
            $('#agregar').attr("disabled",false);   
        }
    });


    $(document).on('keyup','.searchProducto',function(){
        var row = $(this).data('id');
        $("#fila").val(row);
        $("#myModal").modal();
        
        
    });

    $(document).on('keyup','#search_producto',function(){
        var cadena = $('#search_producto').val();
        var almacen = $('#almacen').val();
        if(cadena.length>3)
        {
            getProducto(cadena,almacen);
        }            
        
    });

    $(document).on('click','.imprimir',function(e){
        var valor = $(this).val();
        getNotaImp(valor);     
    });

    var getNotaImp = function(valor){
        $.post('/almacen/despacho/cargarNotaImp/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#nota').html(datos[0].nota);
                    
                } 
    
        },'json');
    };
    

    $(document).on('click','#imprimir',function(e){
        printJS({
            printable: 'nota',
            type: 'html'});     
    });

//----------------------------------------------------------------
//metodo que carga en combo lista de creditos 
//----------------------------------------------------------------
var getProveedor = function(){
    $.post('/almacen/despacho/buscarProveedor/','value=""',function(datos){
        if(datos)
            {
                $('#destino').html("");
                var tr = '';
                tr = tr + '<label>Proveedores</label>';
                tr= tr+'<select class="form-control select2" name="destino" id="dsp_destino" >';
                for (i = 0; i < datos.length; i++){
                    
                    var direccion = datos[i].razonSocial.toUpperCase();
                    tr = tr+'<option value="'+datos[i].id+'">'+direccion+'</option>';

                }
                tr= tr+'</select>';
                $('#destino').html(tr);
            } 

    },'json');
};

//----------------------------------------------------------------
//metodo que carga datos de los productores
//----------------------------------------------------------------
var getProductor = function(){
    $.post('/almacen/despacho/buscarProductor/','value=""',function(datos){
        if(datos)
            {
                $('#destino').html("");
                var tr = '';
                tr = tr + '<label>Productores</label>';
                tr= tr+'<select class="form-control select2" name="destino" id="dsp_destino" >';
                for (i = 0; i < datos.length; i++){
                    
                    var direccion = datos[i].razonSocial.toUpperCase();
                    tr = tr+'<option value="'+datos[i].id+'">'+direccion+'</option>';

                }
                tr= tr+'</select>';
                $('#destino').html(tr);
            } 

    },'json');
};


//--------------------------------------------------------------------------------
// metodo que realiza busqueda de productos y crea lista 
//-------------------------------------------------------------------------------
var getProducto = function(valor,almacen){

    $.post('/almacen/despacho/buscarProducto/','value='+valor+'&value1='+almacen,function(datos){
        if(datos.length>0)
            {
                $('#tabla_prod tbody').html("");
                    var tr = '';
                    for (i = 0; i < datos.length; i++){
                        tr += '<tr>';
                        tr += '<td>'+datos[i].descripcion+'</td><td>'+datos[i].presentacion+'</td><td>'+datos[i].existenciaActual+'</td><td><button class="btn btn-default" type="button" id="carga" value="'+datos[i].id+'" data-dismiss="modal" ><i class="fa fa-cloud-download"></i></button></td>';
                        tr += '</tr>';
                    }

                $('#tabla tbody').html(tr);
            }    

    },'json');
};




//---------------------------------------------------------------------
//Agrega filas a la tabla 
//---------------------------------------------------------------------
$(document).on('click',"#add_producto",function(){
    var valor =0;
   // $('#add_servicio').attr('disabled',true);

    var fecha = new Date();
   
    var count = $('#productos >tbody >tr').length;
    var idPrd= count +1;

    var nuevaFila="<tr>";
    
    nuevaFila=nuevaFila+"<td><input type='text' name='codigo[]' id='codigo"+idPrd+"'  data-id='"+idPrd+"' value=''   class='form-control form-control-border form-control-sm searchProducto'   /></td>";
    nuevaFila=nuevaFila+"<td><input type='text' name='descripcion[]' id='descripcion"+idPrd+"' class='form-control form-control-border form-control-sm'  readOnly='true' /><input type='hidden' name='id[]' id='id"+idPrd+"' value='0'/></td>";
    nuevaFila=nuevaFila+"<td><input type='text' name='cantidad[]' id='cantidad"+idPrd+"' class='form-control form-control-border form-control-sm text-right calcular_cant' value='' data-id='"+idPrd+"' readOnly='true'  /></td>";
    nuevaFila=nuevaFila+"<td><input type='text' name='pvp[]'  id='pvp"+idPrd+"' class='form-control form-control-border form-control-sm text-right' value='0.00'   /></td>";
    nuevaFila=nuevaFila+"<td><button type='button' name='eliminar' id='eliminar"+idPrd+"' class='btn btn-default btn-sm' ><i class='fa fa-trash'></i></button></td>";

        nuevaFila=nuevaFila+"</tr>";
    $("#productos tbody").append(nuevaFila);

    // $('#add_servicio').attr('disabled',false);
           
    

    //$('#add_servicio').attr('disabled',false);                
});


//-----------------------------------------------------------------------------------------------------
//METODO QUE SE EJECUTA MEDIANTE CLASE .CARGA PRODUCTO SEGUN ID 
//-----------------------------------------------------------------------------------------------------
	$(document).on('click','#carga',function(e){
	    //var deposito = $(this).data('dep');
	    var valor    = this.value;
	    var fila       = $('#fila').val(); 
	    //var stock    = $(this).data('stock');
	    var disponible  = 0;
        // alert(id);
        $.ajax( {  
                url: '/almacen/despacho/cargarProducto/',
                type: 'POST',
                dataType : 'json',
                async: false,
                data: 'value='+valor,
                success:function(datos){
                                if(datos)
                                {
                                    $('#codigo'+fila).val(datos[0].codigo);
                                    $('#descripcion'+fila).val(datos[0].descripcion+'('+datos[0].presentacion+')');
                                    $('#pvp'+fila).val(datos[0].Pvp1);
                                    $('#tsaImpuesto'+fila).val(datos[0].tasaImpuesto);
                                    $('#cantidad'+fila).val('0');
                                    $('#id'+fila).val(datos[0].id);
                                    $('#subtotal'+fila).val('00.0');
                                    $('#costo'+fila).val(datos[0].ctoActual);
                                    $('#deposito'+fila).val(datos[0].nombre_almacen);
                                    $('#idDeposito'+fila).val(datos[0].idAlmacen);
                                    
                                    $('#cantidad'+fila).attr("readonly",false);
                                    $('#cantidad'+fila).focus();
                                }

                                },
                error: function(xhr, status) {
                                alert('Disculpe, existiÃ³ un problema');
                                }
                });

    });

     //-------------------------------------------------------------------------
    //--
    //-------------------------------------------------------------------------
    $(document).on('blur','.calcular_cant',function(){

        //-----------------------------------
        //valores de grid
		var fila      = $(this).data('id');
		var val_cant  = $(this).val();
		var val_pvp   = $('#pvp'+fila).val();
		//var val_iva   = $('#tsaImpuesto'+fila).val();
		var val_subtotal = 0;
		var val_exento = 0;
		
		
        //-------------------------------------------
        //totalizacion
		var total    = $('#totalDoc').val();
		var subtotal = $('#subtotalDoc').val();
		//var iva      = $('#impuestoDoc').val();
        //var exento   = $('#exentoDoc').val();
		//var imponible= $('#baseImponibleDoc').val();
		var sub_fila = 0;
		var iva_fila = 0; 
		 
 		if(val_cant > 0)
 		{
            sub_fila = parseFloat(val_pvp * val_cant);			
			subtotal = parseFloat(subtotal) + (val_pvp*val_cant);          

			total = parseFloat(total) + (val_pvp*val_cant);
			
    //alert(val_iva);

			$('#subtotalDoc').val(subtotal.toFixed(2));
			//$('#impuestoDoc').val(iva.toFixed(2));
			$('#totalDoc').val(total.toFixed(2));
			//$('#exentoDoc').val(val_exento.toFixed(2));
            //$('#baseImponibleDoc').val(imponible.toFixed(2));
			
            
			//iva_fila =((sub_fila * ((parseFloat(val_iva)/100) +1)) - sub_fila);
			
			//$('#subtotal'+fila).val(sub_fila.toFixed(2));
			//$('#iva'+fila).val(iva_fila.toFixed(2));
			//$('#total'+fila).val(parseFloat(sub_fila + iva_fila).toFixed(2));
			 		
 		}
		 			
	});


   //--------------------------------------------------------------------------------------
// metodo para enviar formulario
//--------------------------------------------------------------------------------------
 var setDatos = function(){
    if($('#almacen').val()=='0' ||  $('#dsp_destino').val()=='0')
    {
        if($('#almacen').val()=='0')
            {
                alert('Complete los datos obligatorios *, Debe seleccionar un Almacen de Recepción');
            }
            if($('#proveedor').val()=='0')
                {
                    alert('Complete los datos obligatorios *, Debe seleccionar un Proveedor');
                }
            if($('#correlativo').val()=='')
                {
                    alert('Complete los datos obligatorios *, Debe introducir el correlativo del documento');
                }    
    }
    else
    {
        if($('#guardar').val()==1) //guarda el registro nuevo
        {   
            if(confirm("¿Se Guardara el Doc.. desea continuar ?"))
            {
                $("#form_despacho_agregar").submit();                    
            }    
                    
                
        }//FIN DE LA OPCION GUARDAR NUEVO 1
        
        if($('#guardar').val()==2) //guarda el registro editado
        {
            if(confirm("¿Realmente desea editar el Documento ?"))
            {                    
                $("#form_despacho_editar").submit(); 
            }
        }//FIN DE LA OPCION EDITAR 2
    }
};  //FIN DE LA FUNCION setDatos





      ///configuracion de datatable
    $(function () {
        $("#example1").DataTable({
          "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": ["copy", "csv", "excel"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        });
      });

});    
