$(document).ready(function(){


    $('#agregar').click(function(){

        setDatos();

    });


    $(document).on('change','#idFiscal',function(){      
        var id = $(this).val();
        getProveedor(id)

    });

    $(document).on('click','#eliminar',function(){
        
        alert("El proveedor sera eliminado de forma definitiva")
        $("#form_proveedor_eliminar").submit();  
 
     });

    var getProveedor = function(valor){
        $.post('/compras/proveedor/buscarProveedor/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    alert("La Identificacion fiscal que intenta registrar ya existe para el Proveedor:  "+datos[0].razonSocial);
                }else
                    {
                        $('#razonSocial').attr('disabled',false);
                        $('#domicilioFiscal').attr('disabled',false);
                        $('#tlf_ventas').attr('disabled',false);
                        $('#tlf_admon').attr('disabled',false);
                        $('#tlf_almacen').attr('disabled',false);
                        $('#correo_ventas').attr('disabled',false);
                        $('#correo_admon').attr('disabled',false);
                        $('#correo_almacen').attr('disabled',false);
                        $('#tlf_wsp').attr('disabled',false);
                        $('#comentario').attr('disabled',false);
                        $('#agregar').attr('disabled',false);
                    } 

        },'json');
    };





    // metodo para enviar formulario
    var setDatos = function(){
        //$('#razonSocial').val($('#razonSocial').val().trim());
        $('#idFiscal').val($('#idFiscal').val().trim());
        if($('#razonSocial').val()=='' ||  $('#tipo').val()=='0' || $('#idFiscal').val()=='' )
        {
            alert('Complete los datos obligatorios *');
        }
        else
        {
            if($('#guardar').val()==1) //guarda el registro nuevo
			{   
                if(confirm("¿Realmente desea guardar el nuevo Proveedor"))
                {
                    $("#form_proveedor_agregar").submit();                    
                }    
                        
					
			}//FIN DE LA OPCION GUARDAR NUEVO 1
			
            if($('#guardar').val()==2) //guarda el registro editado
            {
                if(confirm("¿Realmente desea editar el Proveedor?"))
                {                    
                    $("#form_proveedor_editar").submit(); 
                }
            }//FIN DE LA OPCION EDITAR 2
        }
    };  //FIN DE LA FUNCION setDatos




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