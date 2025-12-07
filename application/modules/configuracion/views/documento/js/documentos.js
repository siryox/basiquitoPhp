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


/***** FUNCIONES PARA MANEJAR EVENTOS DENTRO DEL FOMULARIO ******/
        //limpia los valores de los campos de texto
        var limpiar_formulario = function(){
            $('#id').val('');
            $('#nombre').val('');
            $('#contador').val('');
            $('#plantilla').val('');
            $('#sigla').val('');
            $('#descripcion').val('');
            $('#aux').val('');
        };
        //habilita los elementos del formulario
        var habilitar_formulario = function(){
            $('#contador').attr('disabled', false);
            $('#plantilla').attr('disabled', false);
            $('#nombre').attr('disabled', false);
            $('#descripcion').attr('disabled', false);
            $('#sigla').attr('disabled', false);
            $('#agregar').attr('disabled', false);
            $('#cancelar').attr('disabled', false);
        
      };

        //bloquea los elementos del formulario
        var bloquear_formulario = function(valor){
            $('#contador').attr('disabled', true);
            $('#plantilla').attr('disabled', true);
            $('#nombre').attr('disabled', true);
            $('#descripcion').attr('disabled', true);
            $('#sigla').attr('disabled', true);
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
        
        

        /*** METODOS PARA INTERACTUAR CON EL CONTROLADOR DE LA VISTA ***/
    //Para fijar valores por incluir nuevo o editar registro existente
        var setDatos = function(){
            $('#nombre').val($('#nombre').val().trim());
            $('#sigla').val($('#sigla').val().trim());
            if($('#nombre').val()=='' || $('#nombre').val()=='' )
            {
                alert('Complete los datos obligatorios *');
                document.getElementById('nombre').focus();
            }
            else
            {
                if($('#guardar').val()==1) //guarda el registro nuevo
                {
                
                    if(confirm("¿Realmente desea guardar el nuevo Documento?"))
                    {
                        $("#form_documento").submit();
                    }
                        
                }
                if($('#guardar').val()==2) // para guardar la edicion del registro
                {
                    var str1= new String($('#nmobre').val()).toLowerCase();// a minuscula
                    var str2= new String($('#aux').val()).toLowerCase();
                    if(omitir_tilde(str1)==omitir_tilde(str2))
                    {
                        if(confirm("¿Realmente desea editar los datos  del Documuento?"))
                        {
                            $("#form_documento").submit();
                        }
                    }
                    else
                    {                    
                        
                        if(confirm("¿Realmente desea editar el Documento? "))
                        {
                            $("#form_documento").submit();
                        }
                            
                    }
                }//FIN DE LA OPCION EDITAR 2
            }//FIN DEL ELSE PRINCIPAL DE LA FUNCION
        };  //FIN DE LA FUNCION setDatos


        var getDatos = function(valor){
            $.post('/configuracion/documento/buscarDocumento/','valor=' + valor,function(datos){
                if(datos)
                {
                    limpiar_formulario();
                    $('#id').val(datos[0].id);
                    $('#nombre').val(datos[0].nombre);
                    $('#descripcion').val(datos[0].descripcion);
                    $('#sigla').val(datos[0].sigla);
                    
                    $('#aux').val(datos[0].nombre);
                    
                    $("#contador option[value="+ datos[0].contador +"]").attr("selected",true);
                    $("#plantilla option[value="+ datos[0].plantilla +"]").attr("selected",true);
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
                       // $.post('/seguridad/role/eliminarRole/','valor=' + valor,function(filas){
                       //     document.location.reload();
                       // }, 'json');
                    }
                }
            },'json');
        };


        var getPlantilla = function(valor){
            $('#nota').html('');
            $.post('/configuracion/documento/buscarPlantilla/','valor='+valor,function(datos){
                if(datos)
                {
                    $('#nota').html(datos[0].valor);

                }
            },'json');
        }



        $('.plantilla').on('click', function(){
            var valor = $(this).val();
            getPlantilla(valor);
        });

            




    });