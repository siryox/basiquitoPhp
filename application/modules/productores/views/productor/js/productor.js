$(document).ready(function(){

    $('#edit_contacto').click(function(){
        activar_campos_contacto();
        document.getElementById('tlf_representante').focus();

    });

    $('#servicio').click(function(){
        var id = $(this).data('id');
        getServicios(id);
    });

    $('#agregar').click(function(){

        setDatos();

    });

    //METODO PARA ELIMINAR FILA DE CUENTA BACARIA DEL PRODUCTOR
    $(document).on('click',".eliminarFila",function(){
        var value = $(this).val();
        if(confirm("Seguro de eliminar fila ....."))
          $("#fila"+value).remove();
    });




    //LLAMADA A METODO PARA ELEMINIR REGISTRO
    $(document).on('click','#eliminar',function(){
        
       alert("El productor sera eliminado de forma definitiva")
       $("#form_productor_eliminar").submit();  

    });

    $(document).on('change','.autocompletar',function(){
        
        var fila = $(this).data('id');
        var id = $('#descripcion'+fila).val();
        //alert(id+' - '+fila);
        autocompletarServicio(id,fila);


    });

    //activa la carga de estados
    $(document).on('change','#estado',function(){
        
        var id = $(this).val();
        getMunicipio(id);

    });

    //activa la carga de municipios
    $(document).on('change','#municipio',function(){
        
        var id = $(this).val();
        getParroquia(id);

    });


    // //activa la carga de parroquias
    // $(document).on('change','#parroquia',function(){
        
    //     var id = $(this).val();
    //     getSector(id);

    // });



    //-------------------------------------------------------------
    /*****carga listado de municipios asignados al cliente *******/
    //------------------------------------------------------------
    var getMunicipio = function(valor){
        $.post('/productores/productor/loadMunicipio/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#municipio').html('');
                    var nombre ="";
                    $('#municipio').append('<option value="" >-Seleccione-</option>');
                    for(i = 0; i < datos.length;i++)
                    {
                        nombre = datos[i].descripcion_municipio;
                        $('#municipio').append('<option value="'+datos[i].id_municipio+'" >' +nombre.toUpperCase()+ '</option>');
                    }
                } 

        },'json');
    };

    //-------------------------------------------------------------
    /*****carga listado de municipios asignados al cliente *******/
    //------------------------------------------------------------
    var getParroquia = function(valor){
        $.post('/productores/productor/loadParroquia/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#parroquia').html('');
                    var nombre ="";
                    $('#parroquia').append('<option value="" >-Seleccione-</option>');
                    for(i = 0; i < datos.length;i++)
                    {
                        nombre = datos[i].descripcion_parroquia;
                        $('#parroquia').append('<option value="'+datos[i].id_parroquia+'" >' +nombre.toUpperCase()+ '</option>');
                    }
                } 

        },'json');
    };
    // //-------------------------------------------------------------
    // /*****carga listado de sectores de un municipio *******/
    // //------------------------------------------------------------
    // var getSector = function(valor){
    //     $.post('/productores/productor/loadSector/','value='+valor,function(datos){
    //         if(datos.length > 0)
    //             {
    //                 $('#sector').html('');
    //                 var nombre ="";
    //                 $('#sector').append('<option value="" >-Seleccione-</option>');
    //                 for(i = 0; i < datos.length;i++)
    //                 {
    //                     nombre = datos[i].descripcion_parroquia;
    //                     $('#sector').append('<option value="'+datos[i].id_sector+'" >' +nombre.toUpperCase()+ '</option>');
    //                 }
    //             } 

    //     },'json');
    // };
    //-------------------------------------------------------------
    /*****carga listado de servicios asignados al cliente *******/
    //------------------------------------------------------------
     var getServicios = function(valor){
        $.post('/administracion/cliente/cargarServiciosCliente/','cliente='+valor,function(datos){
            if(datos.length>0)
            {
                $('#tabla_prod tbody').html("");
                    var tr = '';
                    for (i = 0; i < datos.length; i++){
                        tr += '<tr>';
                        tr += '<td align="center">'+datos[i].fecha+'</td><td>'+datos[i].DescripcionServ+'</td><td>'+datos[i].Contratado+'</td><td>'+datos[i].Disponible+'</td><td>'+datos[i].Estado+'</td>';
                        tr += '</tr>';
                    }

                $('#tabla_serv tbody').html(tr);
            }    

        },'json');
    };


    //--------------------------------------------------------------
    //buscar servicio y completar campos contratado y disponible
    //-------------------------------------------------------------
    var autocompletarServicio = function(valor,fila){

        $.post('/administracion/cliente/buscarServicio/','servicio='+valor,function(datos){
            if(datos)
            {
               $('#contratado'+fila).val(datos.cantidad_servicio);
               $('#disponible'+fila).val(datos.cantidad_servicio);
               $('#id'+fila).val(datos.id_servicio);
            }    

        },'json');
    };


    //---------------------------------------------------------------------
    //Agrega filas a la tabla de servicio contratados del cliente
    //---------------------------------------------------------------------
    $(document).on('click',"#add_servicio",function(){
        var valor =0;
        $('#add_servicio').attr('disabled',true);

        var fecha = new Date();

        $.ajax( {
            url: '/administracion/cliente/cargarServicios/',
            type: 'POST',
            dataType : 'json',
            async: true,
            data: 'valor='+valor,
            success:function(datos){
                if(datos.length > 0)    
                {
                            
                    var count = $('#tabla_servicios >tbody >tr').length;
                    var idPrd= count +1;

                    var nuevaFila="<tr>";
                    var opt = '<option value="0">-Seleccione-</option>';
                    for(i= 0;i < datos.length;i++ )
                    {
                        opt += '<option value="'+datos[i].id_servicio+'">'+datos[i].nombre_servicio+'</option>'
                    }    
                    nuevaFila=nuevaFila+"<td><input type='date' name='fecha[]' id='fecha"+idPrd+"' size='12' data-id='"+idPrd+"' value='"+fecha.toJSON().slice(0,10)+"'   class='form-control form-control-sm' readonly='true' /></td>";
                    nuevaFila=nuevaFila+"<td><select name='descripcion[]' id='descripcion"+idPrd+"' class='form-control form-control-sm autocompletar' data-id='"+idPrd+"'>"+opt+"</select> <input type='hidden' name='id[]' id='id"+idPrd+"' value='0'/></td>";
                    nuevaFila=nuevaFila+"<td><input type='text' name='contratado[]' id='contratado"+idPrd+"' class='form-control form-control-sm' value='' readonly='true' /></td>";
                    nuevaFila=nuevaFila+"<td><input type='text' name='disponible[]'  id='disponible"+idPrd+"' class='form-control form-control-sm' value='' readonly='true'  /></td>";
                    nuevaFila=nuevaFila+"<td><select name='estado[]' id='estado"+idPrd+"' class='form-control form-control-sm'><option value='Activo'>Activo</option><option value='Inactivo'>Inactivo</option></select></td>";
                    //nuevaFila=nuevaFila+"<td><input type='text' name='cantidad[]'  id='cantidad"+idPrd+"' data-id='"+idPrd+"' class='form-control  text-right input-sm'  value='0'  /></td>";
                    //nuevaFila=nuevaFila+"<td><input type='text' name='iva[]'  id='iva"+idPrd+"' data-id='"+idPrd+"' class='form-control  text-right input-sm'  value='0' readonly='true' /></td>";
                    nuevaFila=nuevaFila+"<td><button type='button' name='eliminar' id='eliminar"+idPrd+"' class='btn btn-default btn-sm' ><i class='fa fa-trash'></i></button></td>";

                    nuevaFila=nuevaFila+"</tr>";
                    $("#tabla_servicios tbody").append(nuevaFila);

                    $('#add_servicio').attr('disabled',false);

                }else
                    {
                        alert('Error cargando lista de servicios');
                        $('#add_servicio').attr('disabled',false);
                    }
                
            },error: function(xhr, status) {
                            alert('Disculpe, existe un problema');
                            }
             });                

    });



    //-------------------------------------------------------------------------------------------------
    //metodo para validar el idFiscal 
    $(document).on('change',".valid_productor",function(){
        var valor = $(this).val();
        $.ajax( {
            url: '/productores/productor/validarProductor/',
            type: 'POST',
            dataType : 'json',
            async: false,
            data: 'valor='+valor,
            success:function(datos){
                if(datos.length > 0)    
                {
                       alert("Id Fiscal ya esta registrado");   
                        
                        
                }else
                    {
                        activar_campos();
                    }
                
            },error: function(xhr, status) {
                            alert('Disculpe, existe un problema');
                            }
             });   





    })



    //-----------------------------------------
    //Activa campos de contactos del cliente
    //----------------------------------------- 
    var activar_campos =function()
    {
        $('#razon_social').attr('readonly',false);
        $('#estado').attr('readonly',false);
        $('#municipio').attr('readonly',false);
        $('#parroquia').attr('readonly',false);
        $('#sector').attr('readonly',false);
        $('#direccion').attr('readonly',false);
        $('#av').attr('readonly',false);
        $('#calle').attr('readonly',false);
        $('#tipo_vivienda').attr('readonly',false);
        $('#nro').attr('readonly',false);
        $('#codigo_postal').attr('readonly',false);

        $('#tlf_prod1').attr('readonly',false);
        $('#tlf_prod2').attr('readonly',false);
        $('#tlf_oficina').attr('readonly',false);
        $('#tlf_wsp').attr('readonly',false);
        $('#tlf_wso').attr('readonly',false);
        $('#correo_prod1').attr('readonly',false);
        $('#correo_prod2').attr('readonly',false);
        $('#correo_empresa').attr('readonly',false);
    }; 
    //-----------------------------------------
    //Desactiva campos de contactos del cliente
    //----------------------------------------- 
    var desactivar_campos = function()
    {
        $('#razon_social').attr('readonly',true);
        $('#estado').attr('readonly',true);
        $('#municipio').attr('readonly',true);
        $('#parroquia').attr('readonly',true);
        $('#sector').attr('readonly',true``);
        $('#direccion').attr('readonly',true);
        $('#av').attr('readonly',true);
        $('#calle').attr('readonly',true);
        $('#tipo_vivienda').attr('readonly',true);
        $('#nro').attr('readonly',true);
        $('#codigo_postal').attr('readonly',true);

        $('#tlf_prod1').attr('readonly',true);
        $('#tlf_prod2').attr('readonly',true);
        $('#tlf_oficina').attr('readonly',true);
        $('#tlf_wsp').attr('readonly',true);
        $('#tlf_wso').attr('readonly',true);
        $('#correo_prod1').attr('readonly',true);
        $('#correo_prod2').attr('readonly',true);
        $('#correo_empresa').attr('readonly',true);
    };


    // metodo para enviar formulario
    var setDatos = function(){
        $('#razon_social').val($('#razon_social').val().trim());
        $('#idFiscal').val($('#idFiscal').val().trim());
        if($('#razon_social').val()=='' ||  $('#tipo').val()=='0' || $('#idFiscal').val()=='' )
        {
            alert('Complete los datos obligatorios *');
        }
        else
        {
            if($('#guardar').val()==1) //guarda el registro nuevo
			{   
                if(confirm("¿Realmente desea guardar el nuevo Productor?"))
                {
                    $("#form_cliente_agregar").submit();                    
                }    
                        
					
			}//FIN DE LA OPCION GUARDAR NUEVO 1
			
            if($('#guardar').val()==2) //guarda el registro editado
            {
                if(confirm("¿Realmente desea editar el Productor ?"))
                {                    
                    $("#form_cliente_agregar").submit(); 
                }
            }//FIN DE LA OPCION EDITAR 2
        }
    };  //FIN DE LA FUNCION setDatos
    
    
    $(document).on('click','.tcred',function(){
        var fila = $(this).attr('id');
        var credito = $(this).data('id');

        getDespachos(credito,fila);
    });


    // Metodo que carga despachos realizados a un productor 
    var getDespachos = function(valor,fila){

        $.post('/productores/productor/cargarDespachoProductor/','value='+valor,function(datos){
            if(datos)
            {
                

                $('#tabla'+fila).html("");

                var tabla="";
                tabla = tabla+'<table>';
                tabla = tabla+'<thead>';
                tabla = tabla+'<tr class="bg-olive disabled color-palette">';
                tabla = tabla+'<th width="90">Doc.</th>';
                tabla = tabla+'<th width="110">Fecha</th>';
                tabla = tabla+'<th width="350">Concepto</th>';
                tabla = tabla+'<th width="110">Monto</th>';
                tabla = tabla+'<th>Estado</th>';
                tabla = tabla+'<th>Rubro</th>';
                tabla = tabla+'<th></th>';
                tabla = tabla+'</tr>';
                tabla = tabla+'</thead>';
                tabla = tabla+'<tbody>';
                if(datos.length >0)
                {
                    for(i=0;i<datos.length;i++)
                    {
                        //var prod = JSON.parse(datos[i].productos);
                        tabla = tabla+'<tr>';
                        
                        tabla = tabla+'<td>'+datos[i].tipo+'-'+datos[i].correlativo+'</td>';
                        tabla = tabla+'<td>'+datos[i].fecha+'</td>';
                        tabla = tabla+'<td>'+datos[i].concepto+'</td>';
                        tabla = tabla+'<td>'+datos[i].montoTotal+'</td>';
                        tabla = tabla+'<td>'+datos[i].estado+'</td>';
                        tabla = tabla+'<td>'+datos[i].rubro+'</td>';
                        tabla = tabla+'<td><button type="button" class="btn btn-default imprimirDsp" data-toggle="modal" data-target="#modal-xl-desp" value="'+datos[i].id+'"><i class="fa-solid fa-boxes-packing"></i></button></td>';
                        tabla = tabla+'</tr>';
                    }    
                }
                tabla = tabla+'</tbody>';
                tabla = tabla+'</table>';  
                
                $('#tabla'+fila).html(tabla);
            }    

        },'json');

    } 



    var getNotaImpAlmacen = function(valor){
        $.post('/productores/productor/cargarNotaImpAlmacen/','value='+valor,function(datos){
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

    $(document).on('click','.imprimir',function(e){
        var valor = $(this).val();
        getNotaImpAlmacen(valor);     
    });



    $(document).on('click','.imprimirDsp',function(e){
        var valor = $(this).val();
        getNotaImpDsp(valor);     
    });

    var getNotaImpDsp = function(valor){
        $.post('/creditos/despacho/cargarNotaImp/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#nota').html(datos[0].nota);
                    
                } 
    
        },'json');
    };






    ///configuracion de datatable
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



      
    
    
    

});