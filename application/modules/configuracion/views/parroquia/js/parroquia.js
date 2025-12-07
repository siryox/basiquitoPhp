$(document).ready(function(){
    
   var getDatos = function(valor){
       $.post('/configuracion/parroquia/buscarParroquia/','valor=' + valor,function(datos){
                $('#id').html('');
                $('#descripcion').html('');
                $('#aux').html('');

                $('#id').val(datos.id_parroquia);                
                $('#descripcion').val(datos.descripcion_parroquia);
                $('#aux').val(datos.descripcion_parroquia);
                
                $("#estado option[value="+ datos.estado_id +"]").attr("selected",true);
                $("#estado").attr("disabled",true);
               // $("#municipio").attr("disabled",true);
                $('#guardar').val('2');
				$("#municipio option[value="+ datos.municipio_id +"]").attr("selected",true);

            },'json');
 	}; 
 	
 	var getMunicipios = function(valor){
 		 $.ajax({  
                    url: '/configuracion/parroquia/cargarMunicipio',
                    type: 'POST',
                    dataType : 'json',
                    async: false,
                    data: 'valor='+valor,
                    success:function(datos){
                    if(datos)
                    {
                
                	$("#municipio").html('');
                	$('#municipio').append('<option value="" >-Seleccione-</option>');
                	var cadena="";                   	
                	cadena = datos.descripcion_municipio.toUpperCase();
                	$("#municipio").append("<option value='"+datos.id_municipio+"'>"+cadena+"</option>");	
                
            		};
    			},
                    error: function(xhr, status) {
                    alert('Disculpe, existe un problema');
                }        
 		
 	});
    };    
  var eliminar = function(valor){
        $.post('/configuracion/parroquia/eliminarParroquia/','valor=' + valor,function(datos){
            if(datos)
            {        
               document.location.reload();
            }else
                document.location.reload();
            },'json');
 	};    
  
    
    
     //.nombre de la clase asignada al boton

    $(".eliminar").click(function(e){
        var li = $(this).val();
         
            eliminar(li);
          
    });
    
    
     //boton agregar nuevo
    $(".nuevo").click(function(e){
        habilitar_formulario();
        limpiar_formulario();
        $('#descripcion').focus();
    }); 
    
    //boton cancelar
    $('#cancelar').click(function(){
    	bloquear_formulario();
        limpiar_formulario();
    
    });
    //boton guardar
     $('#agregar').click(function(){
        $("#estado").attr("disabled",true);
        setDatos();
    });
    
    //boton editar
    $(".editar").click(function(e){
    	
        var li = $(this).val();
        getMunicipios(li);
        habilitar_formulario();
        getDatos(li);
        
    }); 
    
    
    $("#estado").change(function(){
    	$.post('/configuracion/parroquia/buscarMunicipioEstado/','valor=' + $("#estado").val(),function(datos){
                if(datos.length > 0)
                {
                	$("#municipio").html('');
                	$('#municipio').append('<option value="" >-Seleccione-</option>');
                	var cadena="";   
                	for(i=0;i < datos.length;i++)
                	{
                		cadena = datos[i].descripcion_municipio.toUpperCase();
                		$("#municipio").append("<option value='"+datos[i].id_municipio+"'>"+cadena+"</option>");	
                	}
                	
                }
                //$("#municipio option[value="+ datos.estado_id +"]").attr("selected",true);
                //$("#municipio").attr("disabled",true);
                
                


            },'json');
    	
    });   
    
     var limpiar_formulario = function(){
        $('#id').val('');
        $('#descripcion').val('');
        $('#aux').val('');        
    };
    //habilita los elementos del formulario
    var habilitar_formulario = function(){
        $('#descripcion').attr('disabled', false);
        $('#estado').attr('disabled', false);
        $('#municipio').attr('disabled', false);
       	$('#agregar').attr('disabled', false);
        $('#cancelar').attr('disabled', false);
    };
    //bloquea los elementos del formulario
    var bloquear_formulario = function(valor){
        $('#descripcion').attr('disabled', true);
        $('#estado').attr('disabled', true);
        $('#municipio').attr('disabled', true);
        $('#agregar').attr('disabled', true);
        $('#cancelar').attr('disabled', true);
    }; 

 	var setDatos = function(){
        $('#descripcion').val($('#descripcion').val().trim());
        
        
        if($('#descripcion').val()=='' || $('#municipio').val()=='')
        {
            alert('Complete los datos obligatorios *');
            document.getElementById('municipio').focus();
        }
        else
        {
            if($('#guardar').val()==1) //guarda el registro nuevo
            {
                $.post('/configuracion/parroquia/comprobarParroquia/','descripcion=' + $("#descripcion").val()+'&municipio='+$('#municipio').val(),function(cantidad){
                    if( cantidad.total==0 )
                    {
                        if(confirm("¿Realmente desea guardar la nueva Parroquia?"))
                        {
                            $("#parroquia").submit();
                        }
                    }
                    if( cantidad.total>=1 )
                    {
                        alert("La Parroquia que intenta registrar ya existe, no puede registrado nuevamente.");
                        document.getElementById('descripcion').focus();
                    }
                },'json');
            }
            if($('#guardar').val()==2) // para guardar la edicion del registro
            {
                var str1= new String($('#nombre').val()).toLowerCase();// a minuscula
                var str2= new String($('#aux').val()).toLowerCase();
                if(omitir_tilde(str1)==omitir_tilde(str2))
                {
                    if(confirm("¿Realmente desea editar El nombre la Parroquia ?"))
                    {
                        $("#parroquia").submit();
                    }
                }
                else
                {
                    $.post('/configuracion/parroquia/comprobarParroquia/','descripcion=' + $("#descripcion").val()+'&municipio='+$('#municipio').val(),function(cantidad){
                        if( cantidad.total==0 )
                        {
                            if(confirm("¿Realmente desea editar los Datos del Parroquia ?"))
                            {
                                $("#parroquia").submit();
                            }
                        }
                        if( cantidad.total>=1 )
                        {
                            alert("La Parroquia que intenta registrar ya existe, no puede ser registrado nuevamente.");
                            document.getElementById('municipio').focus();
                        }
                    },'json');
                }
            }//FIN DE LA OPCION EDITAR 2
        }//FIN DEL ELSE PRINCIPAL DE LA FUNCION
    };  //FIN DE LA FUNCION setDatos
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
        };
        
    })();
    //Bloquea tecleo de ENTER
    $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
        if(e.which == 13)
        {
            e.preventDefault();
            return false;
        }
    });
    
});

