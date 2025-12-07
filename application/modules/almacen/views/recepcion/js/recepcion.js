$(document).ready(function(){

    $('.select2').select2();

    $('#agregar').click(function(){

        setDatos();

    });



    $(document).on('keyup','.searchProducto',function(){
        var row = $(this).data('id');
        $("#fila").val(row);
        $("#myModal").modal();
        
        
    });

    $(document).on('keyup','#search_producto',function(){
        var cadena = $('#search_producto').val();
        if(cadena.length>3)
        {
            getProducto(cadena);
        }            
        
    });

    $(document).on('change','#tipoDocumento',function(){
        var valor = $(this).val();
        if(valor !='N-A')
        {
            $('#correlativo').attr('readOnly',false);
        }else
            $('#correlativo').attr('readOnly',true);
    });

    $(document).on('click','.imprimir',function(e){
        var valor = $(this).val();
        getNotaImp(valor);     
    });

    var getNotaImp = function(valor){
        $.post('/almacen/recepcion/cargarNotaImp/','value='+valor,function(datos){
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



//---------------------------------------------------------------------
//Agrega filas a la tabla 
//---------------------------------------------------------------------
    $(document).on('click',"#add_producto",function(){
        var valor =0;
        var combo = "<option value='0'>-Seleccione-</option>";

       $.ajax( {  
        url: '/almacen/recepcion/buscarAlmacen/',
        type: 'POST',
        dataType : 'json',
        async: false,
        data: 'value='+valor,
        success:function(datos){
                        if(datos)
                        {
                            if(datos.length > 0)
                            {
                                for(i=0; i < datos.length;i++)
                                {
                                    combo = combo + "<option value='"+datos[i].id+"'>"+datos[i].nombre+"</option>";
                                }
                            }
                        }

                        },
        error: function(xhr, status) {
                        alert('Disculpe, existiÃ³ un problema');
                        }
        });

        var fecha = new Date();
       
        var count = $('#productos >tbody >tr').length;
        var idPrd= count +1;

        var nuevaFila="<tr>";
        
        nuevaFila=nuevaFila+"<td><input type='text' name='codigo[]' id='codigo"+idPrd+"'  data-id='"+idPrd+"' value=''   class='form-control form-control-border form-control-sm searchProducto'   /></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='descripcion[]' id='descripcion"+idPrd+"' class='form-control form-control-border form-control-sm'  readOnly='true' /><input type='hidden' name='id[]' id='id"+idPrd+"' value='0'/></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='cantidad[]' id='cantidad"+idPrd+"' class='form-control form-control-border form-control-sm text-right calcular_cant' value='' data-id='"+idPrd+"' readOnly='true'  /></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='pvp[]'  id='pvp"+idPrd+"' class='form-control form-control-border form-control-sm text-right' value='0.00' readOnly='true'  /></td>";
        nuevaFila=nuevaFila+"<td><select  name='almacen[]'  id='almacen"+idPrd+"' class='form-control form-control-border form-control-sm '  readOnly='true'  >"+combo+"</select></td>";
        nuevaFila=nuevaFila+"<td><button type='button' name='eliminar' id='eliminar"+idPrd+"' class='btn btn-default btn-sm' ><i class='fa fa-trash'></i></button></td>";

            nuevaFila=nuevaFila+"</tr>";
        $("#productos tbody").append(nuevaFila);

        // $('#add_servicio').attr('disabled',false);
               
        

        //$('#add_servicio').attr('disabled',false);                
    });

//--------------------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------
    var getProveedor = function(){
        $.post('/almacen/despacho/buscarProveedor/','value=""',function(datos){
            if(datos)
                {
                    $('#visor-destino').html("");
                    var tr = '';
                    tr = tr + '<label>Proveedores</label>';
                    tr= tr+'<select class="form-control select2" name="destino" id="dsp_destino" >';
                    for (i = 0; i < datos.length; i++){
                        
                        var direccion = datos[i].razonSocial.toUpperCase();
                        tr = tr+'<option value="'+datos[i].id+'">'+direccion+'</option>';
    
                    }
                    tr= tr+'</select>';
                    $('#visor-destino').html(tr);
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
                $('#visor-destino').html("");
                var tr = '';
                tr = tr + '<label>Productores</label>';
                tr= tr+'<select class="form-control select2" name="destino" id="dsp_destino" >';
                for (i = 0; i < datos.length; i++){
                    
                    var direccion = datos[i].razonSocial.toUpperCase();
                    tr = tr+'<option value="'+datos[i].id+'">'+direccion+'</option>';

                }
                tr= tr+'</select>';
                $('#visor-destino').html(tr);
            } 

    },'json');
};


//--------------------------------------------------------------------------------
// metodo que realiza busqueda de productos y crea lista 
//-------------------------------------------------------------------------------
var getProducto = function(valor){
    $.post('/almacen/recepcion/buscarProducto/','value='+valor,function(datos){
        if(datos.length>0)
            {
                $('#tabla_prod tbody').html("");
                    var tr = '';
                    for (i = 0; i < datos.length; i++){
                        tr += '<tr>';
                        tr += '<td>'+datos[i].descripcion+'</td><td>'+datos[i].presentacion+'</td><td>'+datos[i].nombre_almacen+'</td><td>'+datos[i].existenciaActual+'</td><td><button class="btn btn-default" type="button" id="carga" value="'+datos[i].id+'" data-dismiss="modal" ><i class="fa fa-cloud-download"></i></button></td>';
                        tr += '</tr>';
                    }

                $('#tabla tbody').html(tr);
            }    

    },'json');
};



//--------------------------------------------------------------------------------
// metodo que realiza busqueda de documento en movimientos 
//-------------------------------------------------------------------------------
$(document).on("change","#correlativo",function(){

    let tipo = $('#tipoDocumento').val();
    let proveedor = $('#dsp_destino').val();
    let nro = $('#correlativo').val();

    $.post('/almacen/recepcion/buscarDocumento/','v3='+proveedor+'&v2='+tipo+'&v1='+nro,function(datos){
        if(datos.length>0)
            {
                alert("Documento registrado, no puede registrarlo nuevamente ....");
                $('#add_producto').attr("disabled",true);
                $('#agregar').attr("disabled",true);
                $('#correlativo').val('');
            }else
                {
                    $('#add_producto').attr("disabled",false);
                    $('#agregar').attr("disabled",false);   
                }    

    },'json');



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
                                //$('#tsaImpuesto'+fila).val(datos[0].tasaImpuesto);
                                $('#cantidad'+fila).val('0');
                                $('#id'+fila).val(datos[0].id);
                                //$('#subtotal'+fila).val('00.0');
                                //$('#costo'+fila).val(datos[0].ctoActual);
                                //$('#deposito'+fila).val(datos[0].nombre_almacen);
                                //$('#idDeposito'+fila).val(datos[0].idAlmacen);
                                $('#almacen'+fila).attr("readonly",false);
                                $('#cantidad'+fila).attr("readonly",false);
                                $('#cantidad'+fila).focus();
                            }

                            },
            error: function(xhr, status) {
                            alert('Disculpe, existiÃ³ un problema');
                            }
            });

});

//--------------------------------------------------------------------------------------
// metodo para enviar formulario
//--------------------------------------------------------------------------------------
 var setDatos = function(){
   // $('#correlativo').val($('#correlativo').val().trim());
    if($('#almacen').val()=='0' ||  $('#tipoDocumento').val()=='-'|| $('#emision').val()=='')
    {
        if($('#almacen').val()=='0')
            {
                alert('Complete los datos obligatorios *, Debe seleccionar un Almacen de Recepción');
            }
            if($('#proveedor').val()=='0')
                {
                    alert('Complete los datos obligatorios *, Debe seleccionar un Proveedor');
                }
            if($('#emision').val()=='')
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
                $("#form_recepcion_agregar").submit();                    
            }    
                    
                
        }//FIN DE LA OPCION GUARDAR NUEVO 1
        
        if($('#guardar').val()==2) //guarda el registro editado
        {
            if(confirm("¿Realmente desea editar el Documento ?"))
            {                    
                $("#form_recepcion_editar").submit(); 
            }
        }//FIN DE LA OPCION EDITAR 2
    }
};  //FIN DE LA FUNCION setDatos



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


    $(document).on('click','#customRadio6',function(){
        
        getProductor();

    });

    $(document).on('click','#customRadio5',function(){
        
        getProveedor();

    });

    $(document).on('click','#eliminar',function(){
        
        alert("La Recepción sera anulada de forma definitiva")
        $("#form_recepcion_eliminar").submit();  
 
     });

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