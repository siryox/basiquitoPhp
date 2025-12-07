$(document).ready(function(){

    $('.select2').select2()

    //--------------------------------------------------------------------
    //metodo que busca productor por su id fiscal
    //--------------------------------------------------------------------
    var getProductor = function(valor){
        $.post('/creditos/recepcion/buscarProductor/','value='+valor,function(datos){
            if(datos.length > 0)
            {   
                habilitar_formulario();
                $('#idProductor').attr('value', datos[0].id);
                //$('#idFiscal').attr('value', datos[0].idFiscal);
                //$('#nombre').attr('value', datos[0].razonSocial);
                $('#direccion').attr('value', datos[0].direccion);
                $('#telefono').attr('value', datos[0].tlfPersonal1);
                $('#correo').attr('value', datos[0].correoPersonal1);
                
                
               
            }else
                alert("Productor no encontrado ......."); 
        },'json');
    };

    $('#idFiscal').change(function(){
        let valor = $(this).val();
        if(valor >0)
        {
            if(valor =='9999999')
            {
                //alert("Prueba");
                $('#modal-xl').modal('show');

            }else
                {
                    getProductor(valor);
                    getCredito(valor);    
                }
        }else
            {
                getProductor(valor);
                getCredito(valor);
            }
        
    });


    $(document).on('click','.imprimir',function(e){
        var valor = $(this).val();
        getNotaImp(valor);     
    });


    var getNotaImp = function(valor){
        $.post('/creditos/recepcion/cargarNotaRec/','value='+valor,function(datos){
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


    $(document).on('click','#customCheckbox2',function(){
        if($('#customCheckbox2').prop("checked"))
        {
            $('#credito').attr('disabled', false);   
        }else
            $('#credito').attr('disabled', true);

    });


    var getCredito = function(valor){
        $.post('/creditos/recepcion/cargarCredito/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#credito').html('');
                    var nombre ="";
                    $('#credito').append('<option value="" >-Seleccione-</option>');
                    for(i = 0; i < datos.length;i++)
                    {
                        nombre = datos[i].ProgFinanc+' |  Finca: '+datos[i].fincas_nombre;
                        $('#credito').append('<option value="'+datos[i].id+'" >' +nombre.toUpperCase()+ '</option>');
                    }
                }else
                    {
                        $('#credito').html('');
                    } 
    
        },'json');     
    };


    $('#almacenadora').change(function(){
        let valor = $(this).val();

        getAlmacen(valor);

    });

    var getAlmacen = function(valor){
        $.post('/creditos/recepcion/cargarAlmacen/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#planta').html('');
                    var nombre ="";
                    $('#planta').append('<option value="" >-Seleccione-</option>');
                    for(i = 0; i < datos.length;i++)
                    {
                        nombre = datos[i].nombre+' | Direccion: '+datos[i].direccion;
                        $('#planta').append('<option value="'+datos[i].id+'" >' +nombre.toUpperCase()+ '</option>');
                    }
                } 
    
        },'json');     
    };



    //---------------------------------------------------------------------
    //METODO QUE CARGA RECEPCIONES 
    //--------------------------------------------------------------------
    var getRecepciones = function(valor){
        $.post('/creditos/recepcion/cargarRecepciones/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                        
                }    
        },'json');     
    };


    $(document).on('change','#programa',function(){
        var valor = $(this).val();
        //getRecepciones(valor);
        $('#form-filter').submit();        

    });



    var habilitar_formulario = function(){
        //$('#credito').attr('disabled', false);
        $('#almacenadora').attr('disabled', false);
        $('#planta').attr('disabled', false);
        $('#fechaRecepcion').attr('disabled', false);
        $('#nroVoleta').attr('disabled', false);
        
        $('#conductor').attr('disabled', false);
        $('#idFiscalConductor').attr('disabled', false);
        $('#transporte').attr('disabled', false);
        $('#placa').attr('disabled', false);

        $('#pesoEntrada').attr('disabled', false);
        $('#pesoSalida').attr('disabled', false);
        $('#pesoNeto').attr('disabled', false);
        $('#humedad').attr('disabled', false);
        $('#impureza').attr('disabled', false);
        $('#acondicionado').attr('disabled', false);
        $('#comentario').attr('disabled', false);
        $('#ticEntrada').attr('disabled', false);
        $('#porcImp').attr('disabled', false);
        $('#porcHum').attr('disabled', false);
        $('#infestacion').attr('disabled', false);
        $('#convenio_recepcion').attr('disabled', false);

        
        $('#agregar').attr('disabled', false);
        $('#cancelar').attr('disabled', false);

    };


    // metodo para enviar formulario
    var setDatos = function(){
        $('#idFiscal').val($('#idFiscal').val().trim());
        $('#nroVoleta').val($('#nroVoleta').val().trim());
        $('#fechaRecepcion').val($('#fechaRecepcion').val().trim());

        if($('#idFiscal').val()=='' ||  $('#nroVoleta').val()=='0'|| $('#almacenadora').val()=='0' || $('#fechaRecepcion').val()=='' || $('#planta').val()=='0')
        {
            alert('Complete los datos obligatorios *, ');
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

                if(confirm("¿Confirma que se entregaran los Productos ?"))
                {                    
                    $("#form_recepcion_agregar").submit(); 
                }
            }//FIN DE LA OPCION EDITAR 2
        }
    };  //FIN DE LA FUNCION setDatos

    $('#agregar').click(function(){

        setDatos();

    });

    $(document).on('click','#eliminar',function(){
        
        alert("La Recepción de Cosecha será anulada de forma definitiva")
        $("#form_recepcion_eliminar").submit();  
 
     });

    $(function () {
        $("#example1").DataTable({
          "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
          
            
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        
      });

//-----------------------------------------------------------------------------------
//
//-----------------------------------------------------------------------------------
     
     
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



    //-------------------------------------------------------------------------------------------------
    //metodo para validar el idFiscal 
    $(document).on('change',".valid_productor",function(){
        var valor = $(this).val();
        $.ajax( {
            url: '/productores/productor/validarProductor/',
            type: 'POST',
            dataType : 'json',
            async: true,
            data: 'valor='+valor,
            success:function(datos){
                if(datos.length > 0)    
                {
                       alert("Id Fiscal ya esta registrado");   
                       desactivar_campos();
                        
                }else
                    {
                        activar_campos();
                    }
                
            },error: function(xhr, status) {
                            alert('Disculpe, existe un problema');
                            }
             });   





    })

    //-------------------------------------------------------------------------------------------------------
    
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

    $('#agregar_productor').click(function(){
        $('#agregar_productor').attr("disabled",true);
        $('#anim').css('visibility','visible');
        setDatosProductor();
    });

    // metodo para enviar formulario
    var setDatosProductor = function(){
        $('#razon_social').val($('#razon_social').val().trim());
        $('#idFiscal').val($('#idFiscal').val().trim());
        if($('#razon_social').val()=='' ||  $('#tipo').val()=='0' || $('#idFiscal').val()=='' )
        {
            alert('Complete los datos obligatorios *');
        }
        else
        {
            // $("#form_cliente_agregar").submit();                    
            $.ajax( {
            url: '/creditos/recepcion/guardarProductor/',
            type: 'POST',
            dataType : 'json',
            async: true,
            data: 'tipo='+$('#tipo').val()+'&idFiscal='+$('#idFiscalProd').val()+'&razon_social='+$('#razon_social').val()+'&direccion='+$('#direccion').val()+'&estado='+$('#estado').val()+'&municipio='+$('#municipio').val()+'&sector='+$('#sector').val()+'&calle='+$('#calle').val()+'&av='+$('#av').val()+'&tipo_vivienda='+$('#tipo_vivienda').val()+'&nro='+$('#nro').val()+'&codigo_postal='+$('#codigo_postal').val()+'&parroquia='+$('#parroquia').val()+'&tlf_prod1='+$('#tlf_prod1').val()+'&tlf_prod2='+$('#tlf_prod2').val()+'&tlf_oficina='+$('#tlf_oficina').val()+'&correo_prod1='+$('#correo_prod1').val()+'&correo_prod2='+$('#correo_prod2').val()+'&correo_empresa='+$('#correo_empresa').val()+'&tlf_wsp='+$('#tlf_wsp').val()+'&tlf_wso='+$('#tlf_wso').val()+'&guardar='+1,
            success:function(datos){
                if(datos > 0)    
                {
                    let idFiscal = $('#idFiscalProd').val().trim();
                    let nombre = $('#razon_social').val();
                    $('#idFiscal').append("<option value='"+$('#idFiscalProd').val()+"'>"+nombre.toUpperCase()+"</option>");
                    $("#idFiscal option[value="+ idFiscal +"]").attr("selected",true);
                    $('#anim').css('visibility','hidden');
                    $('#agregar_productor').attr("disabled",false);
                    $('#modal-xl').modal('hide');

                    getProductor(idFiscal);
                    habilitar_formulario();
    
                }else
                    {
                        alert('Error guardano datos de productor o Productor ya existe, Consulte a Soporte Técnico ...'); 
                    }
                
            },error: function(xhr, status) {
                            alert('Disculpe, existe un problema');
                            }
                });   
			
        }
    };  //FIN DE LA FUNCION setDatos
    


});