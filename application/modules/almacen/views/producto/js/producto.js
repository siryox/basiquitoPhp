$(document).ready(function(){
    
    $('.select2').select2();

    $('#agregar').click(function(){

        setDatos();

    });

    $('#utilidad').change(function(){
        calcpvp1();
    });
    $('#utilidadMin').change(function(){
        calcpvp2();
    });

    var calcpvp1 = function(){
        var utilidad  = $('#utilidad').val();
        var costo = $('#costoActual').val();
        var pvp1 = 0;

        pvp1 = ((costo * ((parseFloat(utilidad)/100) +1)) );
        $('#pvp1').val(pvp1.toFixed(2));
    }

    var calcpvp2 = function(){
        var utilidad  = $('#utilidadMin').val();
        var costo = $('#costoActual').val();
        var pvp2 = 0;

        pvp2 = ((costo * ((parseFloat(utilidad)/100) +1)) );
        $('#pvp2').val(pvp2.toFixed(2));
    }

    
    $('#customSwitch1').click(function(){
        if($(this).is(':checked'))
        {
            $('#costoUltcpra').attr('readonly', false);
            $('#costoPromedio').attr('readonly', false);
            $('#costoProrrateado').attr('readonly', false);
            $('#costoActual').attr('readonly', false);
            $('#utilidad').attr('readonly', false);
            $('#utilidadMin').attr('readonly', false);
            $('#pvp1').attr('readonly', false);
            $('#pvp2').attr('readonly', false);
            $('#costoActualMercado').attr('readonly', false);
        }else
        {
            $('#costoUltcpra').attr('readonly', true);
            $('#costoPromedio').attr('readonly', true);
            $('#costoProrrateado').attr('readonly', true);
            $('#costoActual').attr('readonly', true);
            $('#costoActualMercado').attr('readonly', true);
            $('#utilidad').attr('readonly', true);
            $('#utilidadMin').attr('readonly', true);
            $('#pvp1').attr('readonly', true);
            $('#pvp2').attr('readonly', true);
        }
        
    });
    

    $('#customSwitch2').click(function(){
        if($(this).is(':checked'))
        {
            $('#tasaIva').attr('readonly', false);
            
        }else
        {
            $('#tasaIva').attr('readonly', true);
            
        }
        
    });
    
    
    
    //--------------------------------------------------------------------------
	//funcion validar codigo
	//---------------------------------------------------------------------------
    $("#codigo").change(function(){
        if($("#codigo").val()!="")
        {
            $.ajax( {  
                    url: '/almacen/producto/validarCodigo/',
                    type: 'POST',
                    dataType : 'json',
                    async: false,
                    data: 'codigo='+$("#codigo").val(),
                    success:function(datos){
                        if(datos.length > 0)
                        {
                            alert("El Código ya se encuentra Registrado.");
                            $('#codigo').val("");
                            document.getElementById("#codigo").focus();
                        }   

                    },
                    error: function(xhr, status)
                    {
                        alert('Disculpe, existe un problema');
                    }
            });
        }
    });


    // metodo para enviar formulario
    var setDatos = function(){
        $('#codigo').val($('#codigo').val().trim());
        $('#descripcion').val($('#descripcion').val().trim());
        if($('#codigo').val()=='' ||  $('#calisificacion').val()=='' || $('#descripcion').val()=='' )
        {
            alert('Complete los datos obligatorios *');
        }
        else
        {
            if($('#guardar').val()==1) //guarda el registro nuevo
			{   
                if(confirm("¿Realmente desea guardar el nuevo Producto?"))
                {
                    $("#form_producto_agregar").submit();                    
                }    
                        
					
			}//FIN DE LA OPCION GUARDAR NUEVO 1
			
            if($('#guardar').val()==2) //guarda el registro editado
            {
                if(confirm("¿Realmente desea editar el Producto ?"))
                {                    
                    $("#form_producto_editar").submit(); 
                }
            }//FIN DE LA OPCION EDITAR 2
        }
    };  //FIN DE LA FUNCION setDatos


    $(function () {
        $("#example1").DataTable({
          "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        
      });





    $(document).on('click','.imprimir',function(e){
        var valor = $(this).val();
        var fi = $('#inicio').val();
        var ff = $('#final').val();
        var almacen = $('#almacen').val();

        $('#generarNota').val(valor);
       

        getNotaImp(valor,fi,ff,almacen);     
    });

    var getNotaImp = function(valor,fi,ff,almacen){
        $.post('/almacen/producto/cargarNotaImp/','value='+valor+'&fi='+fi+'&ff='+ff+'&almacen='+almacen,function(datos){
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

    $(document).on('click','#generarNota',function(e){
        var valor = $(this).val();
        var fi = $('#inicio').val();
        var ff = $('#final').val();

        var almacen = $('#almacen').val();
        
        
        getNotaImp(valor,fi,ff,almacen);     
    });
        		
        //$(":file").filestyle('buttonText', 'Buscar');
        //$(":file").filestyle('icon', false);
	    //document.getElementById('files').addEventListener('change', previsualizar, false);
});