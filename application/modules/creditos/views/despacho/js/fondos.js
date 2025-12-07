$(document).ready(function(){
    $('.select2').select2();


    $('#agregar').click(function(){

        setDatos();

    });
//-------------------------------------------------------------
//activa busqueda de productor
//------------------------------------------------------------
$(document).on('change','#productor',function(){
        
    var id = $(this).val();
    getCredito(id);
    getProductor(id);

});

$(document).on('change','#credito',function(){
    var id = $(this).val();
    getCupos(id);
    $('#add_producto').attr('disabled',false);
});



//----------------------------------------------------------------
//metodo que carga en combo lista de creditos 
//----------------------------------------------------------------

var getCredito = function(valor){
    $.post('/creditos/despacho/cargarCredito/','value='+valor,function(datos){
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


//----------------------------------------------------------------
//metodo que carga en tabla con cupos disponibles 
//----------------------------------------------------------------

var getCupos = function(valor){
    $.post('/creditos/despacho/buscarCredito/','value='+valor,function(datos){
        if(datos.length > 0)
            {
                $('#visor').html('');
                var tabla ="";
                //tabla = JSON.parse(datos[0].cupos);
                tabla = datos[0].cuposHtml
                //alert(tabla);
                $('#visor').html(tabla);
            } 

    },'json');
};


//----------------------------------------------------------------
//metodo que carga datos del productor 
//----------------------------------------------------------------

var getProductor = function(valor){
    $.post('/creditos/despacho/buscarProductor/','value='+valor,function(datos){
        if(datos)
            {
                var direccion = datos[0].direccion.toUpperCase();
                $('#direccion').html(direccion.toLowerCase());
                $('#sector').html(datos[0].sector);
                $('#telefono').html(datos[0].tlfPersonal1);
                $('#correo').html(datos[0].correoPersonal1);
            } 

    },'json');
};



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
                $("#form-despacho-agregar").submit();                    
            }    
                    
                
        }//FIN DE LA OPCION GUARDAR NUEVO 1
        
        if($('#guardar').val()==3) //guarda el registro editado
        {

            if(confirm("¿Confirma que se entregaran los Productos ?"))
            {                    
                $("#form-despacho-entregar").submit(); 
            }
        }//FIN DE LA OPCION EDITAR 2
    }
}; 


$('#activar_comicion').click(function(){
    if($('#activar_comicion').prop('checked'))
    {
        $('#comision').attr('readonly',false);
    }else
        {
            $('#comision').attr('readonly',true);
        }
    
});



var totalizar = function(){

    var monto = $('#monto').val();
    var comision = $('#comision').val();
    var tasa = $('#tasa').val();
    var total = 0;
    var forma = $('#forma').val();
    var disponible = $('#mto-disponible').html();

    switch(forma)
    {
        case 'efectivoUsd':
            total = (parseFloat(monto) + parseFloat(comision));
        break;
        case 'transferenciaBs':
            total = ((parseFloat(monto) + parseFloat(comision)) / tasa); 
        break;
        case 'transferenciaUsd':
            total = (parseFloat(monto) + parseFloat(comision));
        break;
        case 'pagomovil':
            total = ((parseFloat(monto) + parseFloat(comision)) / tasa);
        break;

    }
    

    if(parseFloat(disponible)>total)
    {
        $('#total').val(total.toFixed(2));
        resto = disponible-total;
        //$('#mto-disponible').html(resto.toFixed(2));
    }
    else
        alert("El total de la transaccion es superior al saldo disponible en credito.");

        

};

$(document).on("change",".totalizar",function(){

    totalizar();


});


});