$(document).ready(function(){


    $('#agregar').click(function(){

        setDatos();

    });

    $(document).on('click','#eliminar',function(){
        
        alert("La Unidad de Produccion sera eliminada de forma definitiva")
        $("#form_finca_eliminar").submit();  
 
     });
    //------------------------------------------------------------------------------------
     //activa la carga de estados
     //----------------------------------------------------------------------------------
     $(document).on('change','#estado',function(){
        
        var id = $(this).val();
        getMunicipio(id);

    });

    //activa la carga de municipios
    $(document).on('change','#municipio',function(){
        
        var id = $(this).val();
        getParroquia(id);

    });




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



    //--------------------------------------------------------------------------------------


    //-----------------------------------------
    //Activa campos de contactos del cliente
    //----------------------------------------- 
    var activar_campos =function()
    {
        $('#idFiscal').attr('readonly',false);
        $('#nomPropietario').attr('readonly',false);
        $('#superfUnidad').attr('readonly',false);
        $('#tipoSuelo').attr('readonly',false);
        $('#tenenciaTierra').attr('readonly',false);
    

        $('#estado').attr('readonly',false);
        $('#municipio').attr('readonly',false);
        $('#parroquia').attr('readonly',false);
        $('#sector').attr('readonly',false);
        $('#direccion').attr('readonly',false);
        $('#coordUnidad').attr('readonly',false);


        $('#tlf_propietario').attr('readonly',false);
        $('#tlf_encargado').attr('readonly',false);
        $('#tlf_finca').attr('readonly',false);
        
        $('#correo_prod1').attr('readonly',false);
        $('#correo_prod2').attr('readonly',false);
        $('#correo_empresa').attr('readonly',false);
    }; 
    //-----------------------------------------
    //Desactiva campos de contactos del cliente
    //----------------------------------------- 
    var desactivar_campos = function()
    {
        $('#idFiscal').attr('readonly',true);
        $('#nomPropietario').attr('readonly',true);
        $('#superfUnidad').attr('readonly',true);
        $('#tipoSuelo').attr('readonly',true);
        $('#tenenciaTierra').attr('readonly',true);
    

        $('#estado').attr('readonly',true);
        $('#municipio').attr('readonly',true);
        $('#parroquia').attr('readonly',true);
        $('#sector').attr('readonly',true);
        $('#direccion').attr('readonly',true);
        $('#coordUnidad').attr('readonly',true);


        $('#tlf_propietario').attr('readonly',false);
        $('#tlf_encargado').attr('readonly',false);
        $('#tlf_finca').attr('readonly',false);
        
        $('#correo_prod1').attr('readonly',false);
        $('#correo_prod2').attr('readonly',false);
        $('#correo_empresa').attr('readonly',false);
    };


    //-------------------------------------------------------------------------------------------------
    // metodo para enviar formulario
    var setDatos = function(){
        $('#nomUnidad').val($('#nomUnidad').val().trim());
        $('#idFiscal').val($('#idFiscal').val().trim());
        if($('#nomUnidad').val()=='' || $('#idFiscal').val()=='' )
        {
            alert('Complete los datos obligatorios *');
        }
        else
        {
            if($('#guardar').val()==1) //guarda el registro nuevo
			{   
                if(confirm("¿Realmente desea guardar los Datos?"))
                {
                    $("#form_finca_agregar").submit();                    
                }    
                        
					
			}//FIN DE LA OPCION GUARDAR NUEVO 1
			
            if($('#guardar').val()==2) //guarda el registro editado
            {
                if(confirm("¿Realmente desea editar la Unidad de Produccion ?"))
                {                    
                    $("#form_finca_agregar").submit(); 
                }
            }//FIN DE LA OPCION EDITAR 2
        }
    };  //FIN DE LA FUNCION setDatos
    
    //---------------------------------------------------------------------
    //Agrega filas a la tabla de lotes de tierra de una unidad de produccion
    //---------------------------------------------------------------------
    $(document).on('click',"#add_lotes",function(){
        var valor =0;
        $('#add_servicio').attr('disabled',true);

        var fecha = new Date();

        var count = $('#tabla_servicios >tbody >tr').length;
        var idPrd= count +1;

        var nuevaFila="<tr>";
    
        nuevaFila=nuevaFila+"<td><input type='number' name='nroLote[]' id='nroLote"+idPrd+"' size='12' data-id='"+idPrd+"' value=''   class='form-control form-control-sm'  /></td>";
        //nuevaFila=nuevaFila+"<td><select name='descripcion[]' id='descripcion"+idPrd+"' class='form-control form-control-sm autocompletar' data-id='"+idPrd+"'>"+opt+"</select> <input type='hidden' name='id[]' id='id"+idPrd+"' value='0'/></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='superficieLote[]' id='superficieLote"+idPrd+"' class='form-control form-control-sm' value=''  /></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='coordenadaLote[]'  id='coordenadaLote"+idPrd+"' class='form-control form-control-sm' value=''   /></td>";
        nuevaFila=nuevaFila+"<td><select name='estadoLote[]' id='estadoLote"+idPrd+"' class='form-control form-control-sm'><option value='Activo'>Activo</option><option value='Inactivo'>Inactivo</option></select></td>";   
        nuevaFila=nuevaFila+"<td><button type='button' name='eliminar' id='eliminar"+idPrd+"' class='btn btn-default btn-sm' ><i class='fa fa-trash'></i></button></td>";

        nuevaFila=nuevaFila+"</tr>";
        $("#tabla_servicios tbody").append(nuevaFila);

        $('#add_servicio').attr('disabled',false);                

    });



     //Money Euro
     $('[data-mask]').inputmask()


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