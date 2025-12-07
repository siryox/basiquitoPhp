$(document).ready(function(){





    $('#agregar').click(function(){

        setDatos();

    });

    $('#agregar-cuota').click(function(){
        if(confirm("¿Realmente desea crear los debitos por Gestion Tecnico Administrativa ?"))
        {                    
            $("#form_programa_cuotas").submit(); 
        }
        

    });


    $(document).on('click','#eliminar',function(){
        
        alert("El Credito sera eliminado de forma definitiva")
        $("#form_programa_eliminar").submit();  
 
     });

     $(document).on('change','#programa',function(){
        var idPrograma = this.value;

        getPrograma(idPrograma);
        getCuotas(idPrograma);

     })

     var getCuotas = function(valor){

        $.post('/financiamiento/programa/cargarcuotas/','value='+valor,function(datos){
            $("#tabla-cuotas tbody").html('');
            if(datos.length > 0)
            {
                $("#tabla-cuotas tbody").html('');
                for(i= 0;i < datos.length;i++ )
                {
                    var nuevaFila="<tr>";
                    nuevaFila = nuevaFila+"<td>"+datos[i].id+"</td>";
                    nuevaFila = nuevaFila+"<td>"+datos[i].emision+"</td>";
                    nuevaFila = nuevaFila+"<td>"+datos[i].RazonSocial+"</td>"
                    nuevaFila = nuevaFila+"<td >"+datos[i].concepto+"</td>"
                    nuevaFila = nuevaFila+"<td class='text-right'>"+datos[i].monto+"</td>"
                    nuevaFila = nuevaFila+"<td class='text-right'>"+datos[i].saldo+"</td>"
                    nuevaFila = nuevaFila+"<td >"+datos[i].estado+"</td>"
                    nuevaFila = nuevaFila+"<td class=''>"+datos[i].idCredito+"</td>"
                    nuevaFila = nuevaFila+"<td class=''>"+datos[i].genInteres+"</td>"
                    nuevaFila = nuevaFila+"</tr>";
                    $("#tabla-cuotas tbody").append(nuevaFila);
                }
                
                
            } 
        },'json');
    };


    var getPrograma = function(valor){

        $.post('/financiamiento/programa/cargarprograma/','value='+valor,function(datos){
            if(datos.length > 0)
            {
                $('#estado').val(datos[0]['estado']);
                
                
            } 
        },'json');
    };
     




    //-------------------------------------------------------------------------------------------------
    // metodo para enviar formulario
    var setDatos = function(){
        $('#fechaInicio').val($('#fechaInicio').val().trim());
        $('#fechaFinal').val($('#fechaFinal').val().trim());
        if($('#fechaInicio').val()=='' || $('#fechaFinal').val()=='' )
        {
            alert('Complete los datos obligatorios *');
        }
        else
        {
            if($('#guardar').val()==1) //guarda el registro nuevo
			{   
                if(confirm("¿Realmente desea guardar el Programa de Financiamiento?"))
                {
                    $("#form_programa_agregar").submit();                    
                }    
                        
					
			}//FIN DE LA OPCION GUARDAR NUEVO 1
			
            if($('#guardar').val()==2) //guarda el registro editado
            {
                if(confirm("¿Realmente desea editar el Programa de Financiamiento ?"))
                {                    
                    $("#form_programa_editar").submit(); 
                }
            }//FIN DE LA OPCION EDITAR 2
        }
    };  //FIN DE LA FUNCION setDatos






   
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
    })


});