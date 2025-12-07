$(document).ready(function(){



    $('.select2').select2()


    $('#agregar').click(function(){

        setDatos();

    });

    $('#agregar2').click(function(){

        setDatosPagar();

    });


    $('#precioLiquidacion').change(function(){
        var valor = $(this).val();
        if(valor>0)
        {
            $('#haSembradas').attr('readOnly',false);
            $('#haCosechadas').attr('readOnly',false);
        }

    });

//------------------------------------------------------------------------- 
 ///configuracion de datatable
 //---------------------------------------------------------------------------
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


//----------------------------------------------------------------
//metodo que carga en combo lista de creditos 
//----------------------------------------------------------------

var getCredito = function(valor){
    $.post('/creditos/liquidacion/buscarCredito/','value='+valor,function(datos){
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
            } 

    },'json');
};


var getInfoCredito = function(valor){
    $.post('/creditos/liquidacion/cargarCredito/','value='+valor,function(datos){
        if(datos.length > 0)
            {
                $('#prgFinanciamiento').val(datos[0].ProgFinanc);
                $('#finca').val(datos[0].fincas_nombre);
                $('#haAprobadas').val(datos[0].creditos_superficieAprobada);
                $('#haCosechadas').val(datos[0].creditos_superficieEfectiva);
                

            } 

    },'json');
};

var getInfoLiquidacion = function(valor){
    $.post('/creditos/liquidacion/infoLiquidacion/','value='+valor,function(datos){
        if(datos.length > 0)
            {

                var lista = JSON.parse(datos[0].result);
                $('#kgCosechados').val(lista.kgCosechados);
                $('#haAprobadas').val(lista.HasAprobadas);
                $('#haCosechadas').val(lista.HasEfectivas);
                $('#kgDescuento').val(lista.kgAnticipos);
                $('#kgLiquidar').val(lista.kgALiquidar);
                $('#precioLiquidacion').val(lista.precioxKg);
                $('#monto').val(lista.montoCosecha);
                $('#montoFinanciado').val(lista.MontoFinanciado);
                $('#totalCosecha').val(lista.montoCosecha);
                $('#totalLiquidar').val(lista.montoAPagar);
                $('#tasaInteres').val(lista.tasaInteres);
                $('#montoInteres').val(lista.MontoIntereses);

                $('#tabla-resumen').html(lista.htmlPrincipal);
                $('#tabla-interes').html(lista.htmlCalculo);
                $('#tabla-cosecha').html(lista.htmlCosecha);
                $('#boletas').val(lista.arrayRecepciones);
                $('#documentos').val(lista.arrayDocumentos);

                $('#agregar').attr('disabled',false);
               //console.log(lista.arrayDocumentos);
            
            }else
                {
                    alert("Hay un problema calculando intereses.... ");
                    $('#agregar').attr('disabled',true);
                }
             

    },'json');
};



var getRecepciones = function(valor){

    $.post('/creditos/liquidacion/cargarRecepciones/','value='+valor,function(datos){
        if(datos.length > 0)
            {
                var camion = "";
                var nuevaFila = "";
                for(i=0;i< datos.length;i++)
                {   
                    nuevaFila=nuevaFila+"<tr id=fila'"+i+"'>";
                    camion = datos[i].modeloCamion+' - '+datos[i].placaCamion;
                    nuevaFila=nuevaFila+"<td><input type='text' name='correlativo[]' id='correlativo"+i+"'  data-id='"+i+"' value='"+datos[i].id+"'   class='form-control form-control-border form-control-sm'   /></td>";
                    nuevaFila=nuevaFila+"<td><input type='date' name='fecha[]' id='fecha"+i+"' class='form-control form-control-border form-control-sm'  readOnly='true' value='"+datos[i].fecha+"' /></td>";
                    nuevaFila=nuevaFila+"<td><input name='transporte[]' id='transporte"+i+"'  data-id='"+i+"' value='"+camion+"' type='text'  class='form-control form-control-sm form-control-border' readOnly='true' ></td>";
                    nuevaFila=nuevaFila+"<td><input type='text' name='pesoNeto[]' id='pesoNeto"+i+"' class='form-control form-control-border form-control-sm text-right' value='"+datos[i].pesoNeto+"' readOnly='true'  /></td>";
                    nuevaFila=nuevaFila+"<td><input type='text' name='pesoAcondicionado[]'  id='pesoAcondicionado"+i+"' class='form-control form-control-border form-control-sm text-right' value='"+datos[i].pesoAcondicionado+"' readOnly='true'  /></td>";
                    nuevaFila=nuevaFila+"<td><input name='nroBoleta[]' id='nroBoleta"+i+"' class='form-control form-control-border form-control-sm text-right' value='"+datos[i].nroBoleta+"' readOnly='true'></td>";   
                   // nuevaFila=nuevaFila+"<td><button type='button' name='eliminarFila' id='eliminar"+idPrd+"' data-id='"+idPrd+"' class='btn btn-default btn-sm eliminarFila' ><i class='fa fa-trash'></i></button></td>";

                    nuevaFila=nuevaFila+"</tr>";
            
                }   

                $("#tabla-cosecha tbody").append(nuevaFila);




            } 
    },'json');

}

var getDocumentos = function(valor){

    $.post('/creditos/liquidacion/cargarCuenta/','value='+valor,function(datos){
        if(datos.length > 0)
            {
                var camion = "";
                var nuevaFila = "";

                var tabla="";
                tabla = tabla+'<table id="tdoc">';
                tabla = tabla+'<thead>';
                tabla = tabla+'<tr class="bg-lightblue color-palette">';
                tabla = tabla+'<th width="90"></th>';
                tabla = tabla+'<th width="100">Doc.</th>';
                tabla = tabla+'<th width="110">Fecha</th>';
                tabla = tabla+'<th width="450">Concepto</th>';
                tabla = tabla+'<th>Monto</th>';
                //tabla = tabla+'<th>Credito</th>';
                //tabla = tabla+'<th></th>';
                tabla = tabla+'</tr>';
                tabla = tabla+'</thead>';
                tabla = tabla+'<tbody>';

                var i = 1;
                for(j=0;j<datos.length;j++)
                {   
                    nuevaFila=nuevaFila+"<tr id=fila'"+i+"'>";
                  //  camion = datos[i].modeloCamion+' - '+datos[i].placaCamion;
                  if(datos[j].marca == 1) 
                    nuevaFila=nuevaFila+"<td><input type='checkbox' name='doc[]' id='doc"+i+"'  data-id='"+i+"' value='"+datos[j].Id+"' checked   class='form-control form-control-border form-control-sm'   /></td>";
                  else
                    nuevaFila=nuevaFila+"<td><input type='checkbox' name='doc[]' id='doc"+i+"'  data-id='"+i+"' value='"+datos[j].Id+"'   class='form-control form-control-border form-control-sm'   /></td>";  
                  
                    nuevaFila=nuevaFila+"<td>"+datos[j].docOrigen+'-'+datos[j].referencia+"</td>";                    
                    nuevaFila=nuevaFila+"<td>"+datos[j].emision+"</td>";
                    nuevaFila=nuevaFila+"<td>"+datos[j].concepto.toUpperCase()+"</td>";
                    nuevaFila=nuevaFila+"<td><input type='text' name='monto[]' id='monto"+i+"' class='form-control form-control-border form-control-sm text-right' value='"+datos[j].monto+"' readOnly='true'  /></td>";
                  //nuevaFila=nuevaFila+"<td><input type='text' name='credito[]'  id='credito"+i+"' class='form-control form-control-border form-control-sm text-right' value='"+datos[i].credito+"' readOnly='true'  /></td>";
                    //nuevaFila=nuevaFila+"<td><input name='nroBoleta[]' id='nroBoleta"+i+"' class='form-control form-control-border form-control-sm text-right' value='"+datos[i].nroBoleta+"' readOnly='true'></td>";   
                   // nuevaFila=nuevaFila+"<td><button type='button' name='eliminarFila' id='eliminar"+idPrd+"' data-id='"+idPrd+"' class='btn btn-default btn-sm eliminarFila' ><i class='fa fa-trash'></i></button></td>";

                    nuevaFila=nuevaFila+"</tr>";
                    i++;
            
                } 
                tabla = tabla + nuevaFila;
                tabla = tabla+'</tbody>';
                tabla = tabla+'</table>';  

                //$("#tabla-cosecha tbody").append(nuevaFila);
                $("#tabla-documentos").html(tabla);



            } 
    },'json');

}

$(document).on('change','#fechaLiquidacion',function(){
        
    var id = $('#credito').val();

    getInfoCredito(id);
    getDocumentos(id);
    //getRecepciones(id);
});



var getNotaImp = function(valor){
    $.post('/creditos/liquidacion/cargarNotaLiq/','value='+valor,function(datos){
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
    getNotaImp(valor);     
});



$(document).on('click','#eliminar',function(){
        
    alert("La Liquidación sera anulada de forma definitiva")
    $("#form_despacho_eliminar").submit();  

 });

$(document).on('click','#calcInteres',function(){
    var fecha = $('#fechaLiquidacion').val();
    var id = $('#credito').val();
    var parameters = "";
    var count = $('#tdoc >tbody >tr').length;
    if($('#haCosechadas').val()>0)
    {
        if(count >0)
        {
            var arr = [];
            for(i=1;i<= count;i++)
            {
                let valor = $("#doc"+i).val();
                if($("#doc"+i).prop("checked"))
                {
                    arr.push(valor)
                }

            }

            parameters = '{"idCredito":"'+id+'","fecha":"'+fecha+'","cxc":['+arr+']}';
            getInfoLiquidacion(parameters);
            //console.log(parameters);



        }

    }else
        {
            alert("Para realizar el calculo de intereses, !se requiere que introduzca las Ha. Efectivas en el registro del credito¡" );

        }    
    
    //getInfoLiquidacion(id,fecha);

})

$(document).on('change','#productor',function(){
        
    var id = $(this).val();
    getCredito(id);
});


// metodo para enviar formulario
var setDatos = function(){
    $('#productor').val($('#productor').val().trim());
    $('#credito').val($('#credito').val().trim());
    if($('#productor').val()=='0' ||  $('#credito').val()=='0')
    {
        if($('#productor').val()=='0')
            {
                alert('Complete los datos obligatorios *, Debe seleccionar un Productor');
            }
            if($('#credito').val()=='0')
                {
                    alert('Complete los datos obligatorios *, Debe seleccionar un Credito Aprobado del Productor');
                }
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
        
        //if($('#guardar').val()==3) //guarda el registro editado
        //{

        //    if(confirm("¿Confirma que se entregaran los Productos ?"))
        //    {                    
        //        $("#form-despacho-entregar").submit(); 
        //    }
        //}//FIN DE LA OPCION EDITAR 2
    }
};  //FIN DE LA FUNCION setDatos

// metodo para enviar formulario pago de liquidacion
var setDatosPagar = function(){
    $('#productor').val($('#productor').val().trim());
    $('#idLiquidacion').val($('#idLiquidacion').val().trim());
    $('#monto_pago').val($('#monto_pago').val().trim());

    if($('#productor').val()=='0' ||  $('#idLiquidacion').val()=='0' ||  $('#monto_pago').val()=='0')
    {
        if($('#productor').val()=='0')
            {
                alert('Complete los datos obligatorios *, Debe seleccionar un Productor');
            }
        if($('#monto_pago').val()=='0')
            {
                alert('Complete los datos obligatorios *, El monto deb ser mayor a Cero');
            }
    }
    else
    {
        if($('#guardar').val()==1) //guarda el registro nuevo
        {   
            if(confirm("¿Se Guardara el Doc.. desea continuar ?"))
            {
                $("#form-liquidar-pagar").submit();                    
            }    
                    
                
        }//FIN DE LA OPCION GUARDAR NUEVO 1
        
        //if($('#guardar').val()==3) //guarda el registro editado
        //{

        //    if(confirm("¿Confirma que se entregaran los Productos ?"))
        //    {                    
        //        $("#form-despacho-entregar").submit(); 
        //    }
        //}//FIN DE LA OPCION EDITAR 2
    }
};  //FIN DE LA FUNCION setDatos




$('#forma').change(function(){
    var valor = $(this).val();

    if(valor == 'efectivoUsd')
    {
        $('#origen_fondos').attr('disabled',true);
        $('#destino_fondos').attr('disabled',true);
        $('#cuenta').attr('disabled',true);
        $('#referencia').attr('disabled',true);
    }else
        {
            $('#origen_fondos').attr('disabled',false);
            $('#destino_fondos').attr('disabled',false);
            $('#cuenta').attr('disabled',false);
            $('#referencia').attr('disabled',false);
        }


})





});