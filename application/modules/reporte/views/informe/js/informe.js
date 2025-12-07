$(document).ready(function(){



//----------------------------------------------------------------
//METODOS PARA IMPRIMIR REPORTE DE SEGUIMIENTO DE CREDITO
//----------------------------------------------------------------
$(document).on('click','.imprimir',function(e){
    var valor = $('#plan').val();
    getSeguimientoCred(valor);     
});

var getSeguimientoCred = function(valor){
    $.post('/reporte/informe/cargarRepSeguimiento/','value='+valor,function(datos){
        if(datos.length > 0)
            {
                $('#nota').html(datos[0].rep);
                
            } 

    },'json');
};

//----------------------------------------------------------------
//METODOS PARA IMPRIMIR REPORTE DE RESUMEN DE PLAN DE FINANCIAMIENTO
//----------------------------------------------------------------
$(document).on('click','.imprimir-resumen',function(e){
    var valor = $('#plan1').val();
    $('#btn-descarga').prop('href','');
    $('#reporte').css({'visibility':'hiden'});
    $('#animacion').css({'visibility':'visible'});
    getResumenPlan(valor);     
});
var getResumenPlan = function(valor){
    $.post('/reporte/informe/cargarResumenPlan/','value='+valor,function(datos){
        if(datos.length > 0)
            {
                if(datos[0].rep !='null')
                {
                    var nombre = datos[0].nombre;
                    //$('#nota').html(datos[0].rep);
                    $('#reporte').css({'visibility':'visible'});
                    $('#animacion').css({'visibility':'hidden'});
                    $('#btn-descarga').prop('href','https://backend.agricoladelmeta.com/application/public/excel/'+nombre);  
                }else
                    {
                        $('#animacion').html('Error 500, Solicite Soporte Tec.');
                    }

            }else
                 {
                    $('#animacion').html('Error 500, Solicite Soporte Tec.');
                }

    },'json')
};

//----------------------------------------------------------------
//METODOS PARA IMPRIMIR REPORTE DE RESUMEN DE PLAN DE FINANCIAMIENTO
//----------------------------------------------------------------
$(document).on('click','.imprimir-resumen-cosecha',function(e){
    var valor1 = $('#plan2').val();
    var valor2 = $('#convenio1').val();
    
    $('#btn-descarga').prop('href','');
    $('#reporte').css({'visibility':'hiden'});
    $('#animacion').css({'visibility':'visible'});
    
    getResumenCosecha(valor1,valor2);     
});
var getResumenCosecha = function(valor1,valor2){
    $.post('/reporte/informe/cargarResumenCosecha/','value1='+valor1+'&value2='+valor2,function(datos){
       // $.post('/reporte/informe/cargarResumenPlan/','value='+valor1,function(datos){
        if(datos.length > 0)
            {
                if(datos[0].rep !='null')
                {
                    //$('#nota').html(datos[0].rep);
                    $('#reporte').css({'visibility':'visible'});
                    $('#animacion').css({'visibility':'hidden'});
                    $('#btn-descarga').prop('href','https://backend.agricoladelmeta.com/application/public/excel/MetaResumenCosecha.xls');  
                }else
                    {
                        $('#animacion').html('Error 500, Solicite Soporte Tec.');
                    }    
            }else
                {
                    $('#animacion').html('Error 404, Solicite Soporte Tec.');
                } 

    },'json')
};





$(document).on('click','#imprimir',function(e){
    printJS({
        printable: 'nota',
        type: 'html'});     
});


//--------------------------------------------------------------------







$("#go_ini_nota").click(function() {
    $('.modal').animate({scrollTop: 0},400);
 });


})