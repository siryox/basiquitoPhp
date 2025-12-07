$(document).ready(function(){

    
    $('.select2').select2()

    $(document).on('change','#idFiscal',function(){
        
        var id = $(this).val();
        getProductor(id);


    });
    $('#agregar').click(function(){

        setDatos();

    });
    $('#agregarcxc').click(function(){

        setDatoscxc();

    });
   

    $('#agregarext').click(function(){

        setDatosext();

    });

    $(document).on('change','#plan',function(){
        
        var id = $(this).val();
        getPrograma(id);


    });

    $(document).on('click','#eliminar',function(){
        
        alert("El Credito sera eliminado de forma definitiva")
        $("#form_credito_eliminar").submit();  
 
     });

     //llamada para cargar formato a mostras para imprimir
     $(document).on('click','.imprimir',function(e){
        var valor = $(this).val();
        getcxcImp(valor);     
    });


     $(document).on('click','#imprimir',function(e){
        printJS({
            printable: 'cxc',
            type: 'html'});     
    });

    //-------------------------------------------------------------
    /*****carga listado de municipios asignados al cliente *******/
    //------------------------------------------------------------
    var getProductor = function(valor){
        $.post('/creditos/credito/buscarProductor/','value='+valor,function(datos){
            if(datos.length > 0)
            {
                $('#idFiscal').attr('value', datos[0].idFiscal);
                $('#nombProductor').attr('value', datos[0].razonSocial);
                $('#evaluacionProductor').attr('value', datos[0].evaluacion);
                $('#deuda').attr('value', datos[0].deudaAnterior);
                
                
            } 
        },'json');
    };

    
   
    //-------------------------------------------------------------
    /*****carga listado de municipios asignados al cliente *******/
    //------------------------------------------------------------
    var getPrograma = function(valor){
        $.post('/creditos/credito/buscarPrograma/','value='+valor,function(datos){
            if(datos.length > 0)
            {
                $('#fechaInicio').attr('value', datos[0].fechaInicio);
                $('#fechaFinal').attr('value', datos[0].fechaFinal);
                $('#haMax').attr('value', datos[0].hectMax);
                $('#tasaInteres').attr('value', datos[0].tasaInteres);
                $('#moneda').attr('value', datos[0].moneda);
                
            } 
        },'json');
    };


    //----------------------------------------------------------------------------------------
    //
    //---------------------------------------------------------------------------------------
    var getCredito = function(valor){
        $.post('/creditos/credito/buscarCredito/','value='+valor,function(datos){
            if(datos.length > 0)
            {
                $('#idFiscal').attr('value', datos[0].idFiscal);
                $('#nombProductor').attr('value', datos[0].razonSocial);
                $('#evaluacionProductor').attr('value', datos[0].evaluacion);
                $('#deuda').attr('value', datos[0].deudaAnterior);
                
                
            } 
        },'json');
    };



    var getcxcImp = function(valor){
        $.post('/creditos/credito/cargarCxcImp/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#cxc').html(datos[0].cxc);
                    
                } 
    
        },'json');
    };
    



    $(document).on('click','.imprimir-despacho',function(e){
        var valor = $(this).val();
        getDespImp(valor);     
    });

    //-------------------------------------------------------------------------------------
    //gestionar reporte de recepcion de cosecha
    //-------------------------------------------------------------------------------------
    $(document).on('click','.imprimir-recepcion',function(e){
        var valor = $(this).val();
        getRecepImp(valor);     
    });


    var getRecepImp = function(valor){
        $.post('/creditos/credito/cargarRecepImp/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#despacho').html(datos[0].rec);                  
                } 
    
        },'json');
    };


    //-------------------------------------------------------------------------------------
    
    
    var getDespImp = function(valor){
        $.post('/creditos/credito/cargarDespImp/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#despacho').html(datos[0].dsp);                  
                } 
    
        },'json');
    };

    $(document).on('click','#imprimir-dsp',function(e){
        printJS({
            printable: 'despacho',
            type: 'html'});     
    });


    // metodo para enviar formulario
    var setDatos = function(){
        $('#fechaSolicitud').val($('#fechaSolicitud').val().trim());
        $('#idFiscal').val($('#idFiscal').val().trim());
        if($('#fechaSolicitud').val()=='' ||  $('#idFiscal').val()=='' || $('#plan').val()=='0' || $('#finca').val()=='0' )
        {
            alert('Complete los datos obligatorios *');
        }
        else
        {
            if($('#guardar').val()==1) //guarda el registro nuevo
			{   
                if(confirm("¿Realmente desea guardar el nuevo Credito?"))
                {
                    $("#form_credito_agregar").submit();                    
                }    
                        
					
			}//FIN DE LA OPCION GUARDAR NUEVO 1
			
            if($('#guardar').val()==2) //guarda el registro editado
            {
                if(confirm("¿Realmente desea editar el Credito ?"))
                {                    
                    $("#form_credito_editar").submit(); 
                }
            }//FIN DE LA OPCION EDITAR 2
        }
    };  //FIN DE LA FUNCION setDatos


    //----------------------------------------------------------------------------------------
    //-
    //----------------------------------------------------------------------------------------
    $(document).on("keyup",".search",function(){
        var pro = this.value;
        if(pro.length >2)
        {
            $.ajax( {
                url: '/creditos/credito/buscarProductorNombre/',
                type: 'POST',
                dataType : 'json',
                async: true,
                data: 'value='+pro,
                success:function(datos){
                    
                        $("#tabla tbody").html('');
                        
                            //if(datos.length >0)
                            //{
                                                            
                                for(i= 0;i < datos.length;i++ )
                                {
                                    var nuevaFila="<tr>";
                                    nuevaFila=nuevaFila+"<td>"+datos[i].idFiscal+"</td>";
                                    nuevaFila=nuevaFila+"<td>"+datos[i].razonSocial+"</td>"
                                    nuevaFila=nuevaFila+"<td class='text-right'>"+datos[i].tlfPersonal1+"</td>"
                                    nuevaFila = nuevaFila+"<td><button type='button' name='cerrar'+i id='cerrar'+i value='"+datos[i].idFiscal+"' class='btn btn-default cerrar' data-dismiss='modal'><i class='fa fa-cloud-download' ></i></button></td>";
                                    nuevaFila=nuevaFila+"</tr>";
                                    $("#tabla tbody").append(nuevaFila);

                                }
                            //}else
                            //{
                            //   return true;
                            //}

                        },
                error: function(xhr, status) {
                        alert('Disculpe, existe un problema');
                        }
            });
        }    

    });


    $(document).on('click','.cerrar',function(e){
        //var deposito = $(this).data('dep');
		var valor = this.value;
	    getProductor(valor);

		//$('#guardar').attr('disabled',false);

	});



    $(document).on('change','#motivo',function(e){
        var valor = $(this).val();
        if(valor == 'AMPLIACION DE HECTAREAS SOLICITADAS')
        {
            $('#hectareasAdicionales').attr('disabled',false);
            $('#montoext').attr('disabled',true);
        }else
        {
            $('#hectareasAdicionales').attr('disabled',true);
            $('#montoext').attr('disabled',false);
        }
        console.log(valor); 
    });


   //------------------------------------------------------------------------- 
 ///configuracion de datatable
 //---------------------------------------------------------------------------
 $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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


  



  // metodo para enviar formulario
  var setDatoscxc = function(){
    $('#fechacxc').val($('#fechacxc').val().trim());
    $('#montocxc').val($('#montocxc').val().trim());
    if($('#fechacxc').val()=='' ||  $('#monto').val()=='0' || $('#conceptocxc').val()=='' || $('#tipo').val()=='0' )
    {
        alert('Complete los datos obligatorios *');
    }
    else
    {
        if($('#guardar_cxc').val()==1) //guarda el registro nuevo
        {   
            
            $("#form-cxc").submit();                    
                
        }//FIN DE LA OPCION GUARDAR NUEVO 1
        
        
    }
};  //FIN DE LA FUNCION setDatos


 // metodo para enviar formulario credito adicional
 var setDatosext = function(){
    $('#fechaext').val($('#fechaext').val().trim());
    $('#montoext').val($('#montoext').val().trim());
    if($('#fechaext').val()=='' ||  $('#montoext').val()=='0' || $('#informe').val()=='' || $('#tecnico').val()=='0' )
    {
        alert('Complete los datos obligatorios *');
    }
    else
    {
        if($('#guardar_adicional').val()==1) //guarda el registro nuevo
        {   
            
            $("#form-ext").submit();                    
                
        }//FIN DE LA OPCION GUARDAR NUEVO 1
        
        
    }
};  //FIN DE LA FUNCION setDatos


$("#go_ini_cxc").click(function() {
    $('.modal').animate({scrollTop: 0},400);
 });





});