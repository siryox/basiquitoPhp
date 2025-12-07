$(document).ready(function(){
    /*** ACCIONES LLAMADAS DESDE LA VISTA PARA MANIPULAR EL FORMULARIO ***/
    /*** Llamado a botones desde el id del elemento boton (#nombre_id)  ***/
        //boton guardar
        $('#agregar').click(function(){
            setDatos();
        });
        //boton agregar recurso
        $('#agregar_recurso').click(function(){
            setRecurso();
        });
        //boton cancelar
        $('#cancelar').click(function(){
            location.reload();
        });
    /*** Llamado al botones tipo clase desde el nombre de la clase (.nombre_clase) ***/
        //boton editar
        $(".editar").click(function(e){
            var li = $(this).val();
            getDatos(li);
        });    
        //boton agregar nuevo
        $(".nuevo").click(function(e){
            habilitar_formulario();
            limpiar_formulario();
        });
        //boton eliminar
        $(".eliminar").click(function(e){
            var li = e.target.parentNode;
            eliminar(li.value);
        });
    
        
    /*** METODOS PARA INTERACTUAR CON EL CONTROLADOR DE LA VISTA ***/
    //Para fijar valores por incluir nuevo o editar registro existente
        var setDatos = function(){
            $('#nombre').val($('#nombre').val().trim());
            $('#idFiscal').val($('#idFiscal').val().trim());
            if($('#idFiscal').val()=='' || $('#idFiscal').val()=='' )
            {
                alert('Complete los datos obligatorios *');
                document.getElementById('nombre').focus();
            }
            else
            {
                if($('#guardar').val()==1) //guarda el registro nuevo
                {
                
                    if(confirm("¿Realmente desea guardar el nuevo Tecnico?"))
                    {
                        $("#form_tecnico").submit();
                    }
                        
                }
                if($('#guardar').val()==2) // para guardar la edicion del registro
                {
                    var str1= new String($('#nmobre').val()).toLowerCase();// a minuscula
                    var str2= new String($('#aux').val()).toLowerCase();
                    if(omitir_tilde(str1)==omitir_tilde(str2))
                    {
                        if(confirm("¿Realmente desea editar los datos  del Tecnico?"))
                        {
                            $("#form_tecnico").submit();
                        }
                    }
                    else
                    {                    
                        
                        if(confirm("¿Realmente desea editar el Tecnico? "))
                        {
                            $("#form_tecnico").submit();
                        }
                            
                    }
                }//FIN DE LA OPCION EDITAR 2
            }//FIN DEL ELSE PRINCIPAL DE LA FUNCION
        };  //FIN DE LA FUNCION setDatos
        
        var getDatos = function(valor){
            $.post('/configuracion/tecnico/buscarTecnico/','valor=' + valor,function(datos){
                if(datos)
                {
                    limpiar_formulario();
                    $('#id').val(datos[0].id);
                    $('#nombre').val(datos[0].nombre);
                    $('#idFiscal').val(datos[0].IdFiscal);
                    $('#telefono').val(datos[0].telefonos);
                    $('#correo').val(datos[0].correos);
                    $('#aux').val(datos[0].nombre);
                    
                    $('#guardar').val('2');   
                    habilitar_formulario();             
                }
                else
                {
                    limpiar_formulario();
                    bloquear_formulario();
                }
            },'json');
            
            
        };  //FIN DE LA FUNCION getDatos
            
        var eliminar = function(valor){
            $.post('/seguridad/role/comprobarUso/', 'valor=' + valor, function (resultado) {
                if (resultado.total > 0)
                    alert("El registro ya se encuentra en uso, no puede ser eliminado.");
                else
                {
                    if (confirm("¿Realmente desea eliminar el Rol?"))
                    {
                        $.post('/seguridad/role/eliminarRole/','valor=' + valor,function(filas){
                            document.location.reload();
                        }, 'json');
                    }
                }
            },'json');
        };
            
    /***** FUNCIONES PARA MANEJAR EVENTOS DENTRO DEL FOMULARIO ******/
        //limpia los valores de los campos de texto
        var limpiar_formulario = function(){
            $('#id').val('');
            $('#nombre').val('');
            $('#idFiscal').val('');
            $('#telefono').val('');
            $('#correo').val('');
            $('#aux').val('');
        };
        //habilita los elementos del formulario
        var habilitar_formulario = function(){
            $('#idFiscal').attr('disabled', false);
            $('#nombre').attr('disabled', false);
            $('#telefono').attr('disabled', false);
            $('#correo').attr('disabled', false);
            $('#agregar').attr('disabled', false);
            $('#cancelar').attr('disabled', false);
        
      };

        //bloquea los elementos del formulario
        var bloquear_formulario = function(valor){
            $('#idFiscal').attr('disabled', true);
            $('#nombre').attr('disabled', true);
            $('#telefono').attr('disabled', true);
            $('#correo').attr('disabled', true);
            $('#agregar').attr('disabled', true);
            $('#cancelar').attr('disabled', true);
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

        $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
            if(e.which == 13)
            {
                e.preventDefault();
                return false;
            }
        });       
    });

    $(function () {
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": [ "csv", "excel", "pdf"]
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

    