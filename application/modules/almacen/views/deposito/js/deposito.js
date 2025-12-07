$(document).ready(function(){
   
    $('.select2').select2()

    $('#agregar').click(function(){
        setDatos();
    });
       
   
    $(document).on('click','#eliminar',function(){
        
        alert("El Almacen sera eliminado de forma definitiva")
        $("#form_almacen_eliminar").submit();  
 
     });
    

    $(document).on('change','#nombAlmacen',function(){
        
        var id = $(this).val();
        getDeposito(id);

    });
    


    var getDeposito = function(valor){
        
        $.post('/almacen/deposito/buscarDeposito/', 'valor=' + valor, function (resultado) {
            if (resultado.length >0)
            {
                alert("El registro ya se encuentra en uso, el nombre y tipo de depósito no sera editado");
                document.getElementById('nombAlmacen').value="";
                document.getElementById('nombAlmacen').focus();
            }else
                {
                    habilitar_formulario();
                    document.getElementById('direcAlmacen').focus();
                }
        }, 'json');
    };  



    var habilitar_formulario = function(){
       
        $('#nombAlmacen').attr('disabled', false);
        $('#tlf_alm_ofi').attr('disabled', false);
        $('#descAlmacen').attr('disabled', false);
        $('#direcAlmacen').attr('disabled', false);
        $('#tlf_wsp').attr('disabled', false);
        $('#correo_ofi').attr('disabled', false);
        $('#correo_enc').attr('disabled', false);




    };
    //bloquea los elementos del formulario
    var bloquear_formulario = function(valor){
        $('#nombAlmacen').attr('disabled', true);
        $('#tlf_alm_ofi').attr('disabled', true);
        $('#descAlmacen').attr('disabled', true);
        $('#direcAlmacen').attr('disabled', true);
        $('#tlf_wsp').attr('disabled', true);
        $('#correo_ofi').attr('disabled', true);
        $('#correo_enc').attr('disabled', true);
    };

/*******PARA ELIMINAR LOS REGISTROS DEL LISTADO DE LA VISTA PRINCIPAL**********/
    var restaurar = function(ref){
        if (confirm("¿Realmente desea Restaurar el Depósito?"))
        {
            $.post('/almacen/deposito/estatusDeposito/','valor='+ref+'&estatus='+'1', function (filas){
                document.location.reload();
            }, 'json');
        }
    };
    
    var eliminar = function(ref){
        $.post('/almacen/deposito/comprobarUso/', 'valor=' + ref, function (resultado) {
            if (resultado.total > 0)
            {
                alert("El registro ya se encuentra en uso, no puede ser eliminado.");
            }
            else if (resultado.total===0)
            if (confirm("¿Realmente desea eliminar el registro del depósito?"))
            {
                $.post('/almacen/deposito/estatusDeposito/', 'valor=' + ref+'&estatus='+'9', function (filas) {
                    document.location.reload();
                }, 'json');
            }
        },'json');      
    };

    var setDatos = function(){
        
        $('#direcAlmacen').val($('#direcAlmacen').val().trim());
        $('#nombAlmacen').val($('#nombAlmacen').val().trim());
        
        if( $('#direcAlmacen').val()=='' || $('#nombAlmacen').val()=='' )
        {        
            alert('Complete los datos obligatorios *');
        }else 
        {
            if($('#guardar').val()==1) //si el valor de guardar es 0 desde el agregar
            {
                if(confirm("¿Realmente desea guardar el nuevo Depósito?"))
                {
                    $('#form_deposito_agregar').submit();
                }
            }
            if($('#guardar').val()==2) //si el valor de guardar es 2 desde el editar
            {
                if(confirm("¿Realmente desea editar el Depósito?"))
                {
                    $("#form_deposito_editar").submit();
                }
            }//FIN DE LA OPCION EDITAR 2
        }
    };  //FIN DE LA FUNCION setDatos

    

   
    
    var getCorreo = function(){ 
        if(!(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/.test($('#correo').val())))
        {
            alert("Formato no permitido, ingrese correctamente su correo electrónico.");
            $('#correo').val('');
            $('#correo').focus();
        }
        else
        {
            $.post('/seguridad/usuario/comprobarCorreo/','correo=' + $("#correo").val() ,function(datos){
                if(datos.total >0)
                {
                    alert("El correo electrónico que ingreso ya esta en uso, introduzca otro.");
                    document.getElementById('correo').value="";
                    document.getElementById('correo').focus();
                }
            },'json');
        } 
    };

    

    //Omite tilde, mayuscula y otros tipos de acentuación
    var omitir_tilde = (function () {
        var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÇç",
                to = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuucc",
                mapping = {};

        for (var i = 0, j = from.length; i < j; i++)
            mapping[ from.charAt(i) ] = to.charAt(i);
        
        return function (str) {
            var ret = [];
            for (var i = 0, j = str.length; i < j; i++) {
                var c = str.charAt(i);
                if (mapping.hasOwnProperty(str.charAt(i)))
                    ret.push(mapping[ c ]);
                else
                    ret.push(c);
            }
            return ret.join('');
        }
        
    })();

    //BLOQUEA TECLA ENTER PARA ENVIO DE FORMULARIO
    $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
        if(e.which == 13)
        {
            e.preventDefault();
            return false;
        }
    });




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