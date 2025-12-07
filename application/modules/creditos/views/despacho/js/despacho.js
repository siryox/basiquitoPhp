$(document).ready(function(){

    $('.select2').select2();

    $('#agregar').click(function(){

        setDatos();

    });

    $(document).on('click','#eliminar',function(){
        
        alert("El Despacho sera anulado de forma definitiva")
        $("#form_despacho_eliminar").submit();  
 
     });
    
     $(document).on('click',".eliminarFila",function(){
        var value = $(this).data('id');
        if(confirm("Seguro de eliminar fila ....."))
            reintegarCupos(value);
            $(this).parent().parent().remove();
            recalcular();
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

    $(document).on('keyup','.searchProducto',function(e){
        var row = $(this).data('id');
        $("#fila").val(row);
        if(e.which == 115)
        {
            $('#tabla tbody').html("");
            $("#myModal").modal();
            document.getElementById('search_producto').focus();
            
            $('#search_producto').val('');
            
        }
    });

    $(document).on('change','.searchProducto',function(e){
        var row = $(this).data('id');
        $("#fila").val(row);
        var valor = $(this).val();
        if(e.which != 115)
        {
            getDirectProducto(valor)
        }
    });


    $(document).on('click','.imprimir',function(e){
        var valor = $(this).val();
        getNotaImp(valor);     
    });



    $(document).on('keyup','#search_producto',function(){
        var cadena = $('#search_producto').val();
        if(cadena.length>3)
        {
            getProducto(cadena);
        }            
        
    });


    $(document).on('click','#imprimir',function(e){
        printJS({
            printable: 'nota',
            type: 'html'});     
    });



    $(document).on('change','.entregado',function(){
        var row = $(this).data('id');            
        var valor = $(this).val();
        var cant = parseFloat($('#cantidad'+row).val());
        var entregada = $('#cantidadEntregada'+row).val();

        if(valor > (cant-entregada))
        {
            alert("Cantidad que intenta entregar es mayor a la registrada en el despacho ! Corrija ! ... debe ser menor o igual a:"+cant);
            $(this).val('0');

        }



    });


//----------------------------------------------------------------
//metodo que carga en combo lista de creditos 
//----------------------------------------------------------------

var getNotaImp = function(valor){
    $.post('/creditos/despacho/cargarNotaImp/','value='+valor,function(datos){
        if(datos.length > 0)
            {
                $('#nota').html(datos[0].nota);
                
            } 

    },'json');
};


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
                $('#planTrabajo').val(datos[0].planTrabajo);
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


//--------------------------------------------------------------------------------
// metodo que realiza busqueda de productos y crea lista 
//-------------------------------------------------------------------------------
var getProducto = function(valor){
    var pt = $('#planTrabajo').val();
    $.post('/creditos/despacho/buscarProducto/','value='+valor+'&pt='+pt,function(datos){
        if(datos.length>0)
            {
                $('#tabla_prod tbody').html("");
                    var tr = '';
                    for (i = 0; i < datos.length; i++){
                        tr += '<tr>';
                        tr += '<td><a href="#" data-dismiss="modal" class="carga" data-id="'+datos[i].id+'" data-almacen="'+datos[i].idAlmacen+'">'+datos[i].descripcion+'</a></td><td>'+datos[i].presentacion.toUpperCase()+'</td><td>'+datos[i].nombre_almacen+'</td><td>'+datos[i].existenciaActual+'</td><td><button class="btn btn-default carga" type="button"  data-dismiss="modal" value="'+datos[i].id+'" data-almacen="'+datos[i].idAlmacen+'" ><i class="fa fa-cloud-download"></i></button></td>';
                        tr += '</tr>';
                    }

                $('#tabla tbody').html(tr);
            }    

    },'json');
};


var getDirectProducto = function(valor){
    $.post('/creditos/despacho/cargarProductoCodigo/','value='+valor,function(datos){
          
        if(datos)
            {
                var fila = $('#fila').val();

                var descripcion = datos[0].descripcion.toUpperCase();
                $('#codigo'+fila).val(datos[0].codigo);
                $('#descripcion'+fila).val(descripcion+'('+datos[0].presentacion+')');
                $('#pvp'+fila).val(datos[0].precio);
                $('#tsaImpuesto'+fila).val(datos[0].tasaImpuesto);
                $('#cantidad'+fila).val('0');
                $('#id'+fila).val(datos[0].id);
                $('#subtotal'+fila).val('00.0');
                $('#costo'+fila).val(datos[0].ctoActual);
                $('#deposito'+fila).val(datos[0].nombre_almacen);
                $('#idDeposito'+fila).val(datos[0].idAlmacen);
                $('#grupo'+fila).val(datos[0].grupo);
                
                $('#cantidad'+fila).attr("readonly",false);
                $('#cantidad'+fila).focus();
            }


    },'json');
};

//---------------------------------------------------------------------
//Agrega filas a la tabla 
//---------------------------------------------------------------------
    $(document).on('click',"#add_producto",function(){
        var valor =0;
       // $('#add_servicio').attr('disabled',true);
        
        var fecha = new Date();
       
        var count = $('#productos >tbody >tr').length;
        var tfila = $('#filas').val();
        var idPrd= parseFloat(tfila)+1;

        var nuevaFila="<tr id=fila'"+idPrd+"'>";
        
        nuevaFila=nuevaFila+"<td><input type='text' name='codigo[]' id='codigo"+idPrd+"'  data-id='"+idPrd+"' value=''   class='form-control form-control-border form-control-sm searchProducto'   /></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='descripcion[]' id='descripcion"+idPrd+"' class='form-control form-control-border form-control-sm'  readOnly='true' /><input type='hidden' name='id[]' id='id"+idPrd+"' value='0'/><input type='hidden' name='grupo[]' id='grupo"+idPrd+"' value='0'/></td>";
        nuevaFila=nuevaFila+"<td><input name='deposito[]' id='deposito"+idPrd+"'  data-id='"+idPrd+"' value='' type='text'  class='form-control form-control-sm form-control-border' readOnly='true' ><input type='hidden' name='idDeposito[]' id='idDeposito"+idPrd+"' value='0'/></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='cantidad[]' id='cantidad"+idPrd+"' class='form-control form-control-border form-control-sm text-right calcular_cant' value='' data-id='"+idPrd+"' readOnly='true'  /></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='pvp[]'  id='pvp"+idPrd+"' class='form-control form-control-border form-control-sm text-right calcular_tot' value='0' data-id='"+idPrd+"' readOnly='true'  /></td>";
       
        nuevaFila=nuevaFila+"<td><input name='subtotal[]' id='subtotal"+idPrd+"' class='form-control form-control-border form-control-sm text-right' readOnly='true'><input type='hidden' name='tsaImpuesto[]' id='tsaImpuesto"+idPrd+"' value='0'/><input type='hidden' name='costo[]' id='costo"+idPrd+"' value='0'/></td>";   
        nuevaFila=nuevaFila+"<td><button type='button' name='eliminarFila' id='eliminar"+idPrd+"' data-id='"+idPrd+"' class='btn btn-default btn-sm eliminarFila' ><i class='fa fa-trash'></i></button></td>";

            nuevaFila=nuevaFila+"</tr>";
        $("#productos tbody").append(nuevaFila);
        $('#filas').val(idPrd);
        // $('#add_servicio').attr('disabled',false);
               
        

        //$('#add_servicio').attr('disabled',false);                
    });


//-----------------------------------------------------------------------------------------------------
//METODO QUE SE EJECUTA MEDIANTE CLASE .CARGA PRODUCTO SEGUN ID 
//-----------------------------------------------------------------------------------------------------
	$(document).on('click','.carga',function(e){
	    var id     = $(this).data('id');
	    var valor  = this.value;
        var almacen = $(this).data('almacen');

	    var fila   = $('#fila').val(); 
        var cnfPvp = $('#configPvp').val();
        var pt = $('#planTrabajo').val();
	    //var stock    = $(this).data('stock');
	    var disponible  = 0;
        // alert(id);
        if(id == null)
            id = valor;


        $.ajax( {  
                url: '/creditos/despacho/cargarProducto/',
                type: 'POST',
                dataType : 'json',
                async: false,
                data: 'value='+id+'&pt='+pt+'&almacen='+almacen,
                success:function(datos){
                                    if(datos)
                                    {
                                        var descripcion = datos[0].descripcion.toUpperCase();
                                        $('#codigo'+fila).val(datos[0].codigo);
                                        $('#descripcion'+fila).val(descripcion+'('+datos[0].presentacion+')');
                                        $('#pvp'+fila).val(datos[0].precio);
                                        $('#tsaImpuesto'+fila).val(datos[0].tasaImpuesto);
                                        $('#cantidad'+fila).val('0');
                                        $('#id'+fila).val(datos[0].id);
                                        $('#subtotal'+fila).val('00.0');
                                        $('#costo'+fila).val(datos[0].ctoActual);
                                        $('#deposito'+fila).val(datos[0].nombre_almacen);
                                        $('#idDeposito'+fila).val(datos[0].idAlmacen);
                                        //$('#idDeposito'+fila).val(almacen);
                                        $('#grupo'+fila).val(datos[0].grupo);
                                        
                                        $('#cantidad'+fila).attr("readonly",false);
                                        if(cnfPvp > 0)
                                            $('#pvp'+fila).attr("readonly",false);
                                        
                                        $('#cantidad'+fila).focus();
                                    }

                                },
                error: function(xhr, status) {
                                alert('Disculpe, existiÃ³ un problema');
                                }
                });

    });

    var reintegarCupos = function(fila){
        var grupo = $('#grupo'+fila).val();
        var totalFila = $('#subtotal'+fila).val();
        if(totalFila > 0)
            {
                var valorCupo = 0;
                switch(grupo)
                {
                    case 'SEMILLAS':
                        valorCupo = $('#semilla_disponible').html();
                        valorCupo = parseFloat(valorCupo) + parseFloat(totalFila);
                        $('#semilla_disponible').html(valorCupo);
                    break;
                    case 'FERTILIZANTES':
                        valorCupo = $('#fertilizante_disponible').html();
                        valorCupo = parseFloat(valorCupo) + parseFloat(totalFila);
                        $('#fertilizante_disponible').html(valorCupo);
                    break;
                    case 'AGROQUIMICOS':
                        valorCupo = $('#agroquimicos_disponible').html();
                        valorCupo = parseFloat(valorCupo) + parseFloat(totalFila);
                        $('#agroquimicos_disponible').html(valorCupo);
                    break;
                    case 'COMBUSTIBLES':
                        valorCupo =  $('#combustible_disponible').html();
                        valorCupo = parseFloat(valorCupo) + parseFloat(totalFila);
                        $('#combustible_disponible').html(valorCupo);
                    break;
                    case 'OTROS':
                        valorCupo = $('#otroGto_disponible').html();
                        valorCupo = parseFloat(valorCupo) + parseFloat(totalFila);
                        $('#otroGto_disponible').html(valorCupo);
                    break;

                }
                
            }



    }
    var recalcular = function(){
        var rows = $('#filas').val();
        //alert(rows);
        if(rows >0)
        {
            var subtotal = 0;
            var imponible= 0;
            var impuesto = 0;
            var excento  = 0;
            var total    = 0;

            for(i=1;i <= rows;i++)
            {
                if($('#subtotal'+i).length>0)
                {    
                    var totalFila = $('#subtotal'+i).val();
                    var tiva = $('#tsaImpuesto'+i).val();
                    var cant = $('#cantidad'+i).val();
                    var pvp  = $('#pvp'+i).val();

                    pvp = pvp.replace(',', '.');

                    subtotal = parseFloat(subtotal) + (pvp*cant);

                    if(tiva > 0)
                    {
                        imponible = imponible + (pvp*cant);
                        impuesto =  parseFloat(impuesto) + ((imponible * ((parseFloat(tiva)/100) +1)) - imponible);  
                    }else
                        {
                            excento = parseFloat(excento) + (pvp*cant);
                            
                        }
                    total = (subtotal+impuesto);    
                }        

            }
         
            $('#subtotalDoc').val(subtotal.toFixed(2));
			$('#impuestoDoc').val(impuesto.toFixed(2));
			$('#totalDoc').val(total.toFixed(2));
			$('#exentoDoc').val(excento.toFixed(2));
            $('#baseImponibleDoc').val(imponible.toFixed(2));
        }
    }


    //-------------------------------------------------------------------------
    //--funcion que calcula totales al insertar la cantidad de cada linea 
    //-------------------------------------------------------------------------



    $(document).on('blur','.calcular_cant',function(){
        console.log("entrando");
        //-----------------------------------
        //valores de grid
		var fila      = $(this).data('id');
		var val_cant  = $('#cantidad'+fila).val();
		var val_pvp   = $('#pvp'+fila).val();
		var val_iva   = $('#tsaImpuesto'+fila).val();
		var grupo     = $('#grupo'+fila).val();
        var val_subtotal = 0;
		var val_exento = 0;
		var semDisponible = 0;
        var ferDisponible = 0;
        var agroDisponible = 0;
        var combusDisponible = 0;
        var mto_aprobado = 0;
        var mto_disponible = 0;

        var configCal = $('#conigCal').val();
         val_pvp  = val_pvp.replace(',','.');

        //-------------------------------------------
        //totalizacion
		var total    = $('#totalDoc').val();
		var subtotal = $('#subtotalDoc').val();
		var iva      = $('#impuestoDoc').val();
        var exento   = $('#exentoDoc').val();
		var imponible= $('#baseImponibleDoc').val();
		var sub_fila = 0;
		var iva_fila = 0;
        var subtotalFila = $('#subtotal'+fila).val();
        //------------------------------------------
        //reintegro cupos si subtotal es mayor a 0
        if(subtotalFila > 0)
        {
            reintegarCupos(fila);
        }
        //alert(grupo);
        if(configCal > 0)
        {    
            switch(grupo)
                {
                    case 'SEMILLAS':
                        semDisponible = $('#semilla_disponible').html();
                        sub_fila = parseFloat(val_pvp * val_cant);
                        if(sub_fila > semDisponible)
                        {
                            alert("Cuenta con un Monto de ("+semDisponible+") para ! SEMILLAS ! para montos mayores debe solicitar Autorizacion ....");  
                            $(this).val('0');
                            return false;
                        }else
                            {
                                if(val_cant >= 0)
                                    {                                   			
                                    recalcular(fila);
                                    $('#semilla_disponible').html(semDisponible -sub_fila);
                                    }else
                                        {
                                            alert("La cantidad debe ser mayor a 00.0");
                                        }   
                            }
                    break;
                    case 'FERTILIZANTES':
                        ferDisponible = $('#fertilizante_disponible').html();
                        sub_fila = parseFloat(val_pvp * val_cant);                   
                        if(sub_fila > ferDisponible)
                        {
                            alert("Cuenta con un Monto de ("+ferDisponible+") para ! FERTILIZANTES ! para montos mayores debe solicitar Autorizacion ...."); 
                        }else
                            {
                                if(val_cant >= 0)
                                    {
                                                
                                    recalcular(fila);
                                    $('#fertilizante_disponible').html(ferDisponible - sub_fila );
                                    }else
                                        {
                                            alert("La cantidad debe ser mayor a 00.0");
                                        }      

                            }
                    break;
                    case 'AGROQUIMICOS':

                            agroDisponible = $('#agroquimicos_disponible').html();
                            //alert(agroDisponible);
                            sub_fila = parseFloat(val_pvp * val_cant);                   
                            if(sub_fila > agroDisponible)
                            {
                                alert("Cuenta con un Monto de ("+agroDisponible+") para ! AGROQUIMICOS ! para montos mayores debe solicitar Autorizacion ...."); 
                            }else
                                {
                                    if(val_cant >= 0)
                                        {
                                            recalcular(fila);        
                                            $('#agroquimicos_disponible').html(agroDisponible - sub_fila );
                                        }else
                                            {
                                                alert("La cantidad debe ser mayor a 00.0");
                                            }      
                                }
                    break;
                    case 'COMBUSTIBLES':
                        combusDisponible = $('#combustible_disponible').html();
                        sub_fila = parseFloat(val_pvp * val_cant);                   
                        if(sub_fila > combusDisponible)
                        {
                            alert("Cuenta con un Monto de ("+combusDisponible+") para ! COMBUSTIBLE ! para montos mayores debe solicitar Autorizacion ...."); 
                        }else
                            {
                                if(val_cant >= 0)
                                    {
                                        recalcular(fila);        
                                        $('#combustible_disponible').html(combusDisponible - sub_fila );
                                    }else
                                        {
                                            alert("La cantidad debe ser mayor a 00.0");
                                        }      

                            } 
                    break;
                    case 'OTROS':
                        otroDisponible = $('#otroGto_disponible').html();
                        sub_fila = parseFloat(val_pvp * val_cant);                   
                        if(sub_fila > otroDisponible)
                        {
                            alert("Cuenta con un Monto de ("+otroDisponible+") para ! OTROS GASTOS ! para montos mayores debe solicitar Autorizacion  ...."); 
                        }else
                            {
                                if(val_cant >= 0)
                                    {
                                        recalcular(fila);
                                    $('#otroGto_disponible').html(otroDisponible - sub_fila );
                                    }else
                                        {
                                            alert("La cantidad debe ser mayor a 00.0");
                                        }      

                            }     
                    
                    break;
            }
        }else
            {

                mto_aprobado = $('#mto-aprobado').html();
                mto_disponible = $('#mto-disponible').html();

                sub_fila = parseFloat(val_pvp * val_cant); 
                if(sub_fila > mto_disponible)
                {
                    alert("Sobrepasa el monto disponible ("+mto_disponible+") del Credito  ! para montos mayores debe solicitar Autorizacion  ...."); 
                }else
                    {
                        if(val_cant >= 0)
                            {
                                var resto = parseFloat(mto_disponible) - sub_fila;
                                $('#mto-disponible').html(resto.toFixed(2));
                                recalcular(fila);
                                
                                //alert(sub_fila);
                            }else
                                {
                                    alert("La cantidad debe ser mayor a 00.0");
                                }      

                    }

            }
            
            
        $('#subtotal'+fila).val(sub_fila.toFixed(2));
		
			
 		
		 			
	});


    $(document).on('change','.calcular_tot',function(){
        console.log("entrando");
        //-----------------------------------
        //valores de grid
		var fila      = $(this).data('id');
        
		var val_cant  = $('#cantidad'+fila).val();
		var val_pvp   = $('#pvp'+fila).val();
		var val_iva   = $('#tsaImpuesto'+fila).val();
		var grupo     = $('#grupo'+fila).val();
        var val_subtotal = 0;
		var val_exento = 0;
		var semDisponible = 0;
        var ferDisponible = 0;
        var agroDisponible = 0;
        var combusDisponible = 0;
        var mto_aprobado = 0;
        var mto_disponible = 0;

        if (!(/^[-+]?\d*\.?\d*$/.test(val_pvp))){
            alert('Ingrese un valor numérico válido para el PVP. (0000.00)');
            $('#pvp'+fila).val('0.00');
            return false;   
        }

        var configCal = $('#conigCal').val();

        //-------------------------------------------
        //totalizacion
		var total    = $('#totalDoc').val();
		var subtotal = $('#subtotalDoc').val();
		var iva      = $('#impuestoDoc').val();
        var exento   = $('#exentoDoc').val();
		var imponible= $('#baseImponibleDoc').val();
		var sub_fila = 0;
		var iva_fila = 0;
        var subtotalFila = $('#subtotal'+fila).val();
        //------------------------------------------
        //reintegro cupos si subtotal es mayor a 0
        if(subtotalFila > 0)
        {
            reintegarCupos(fila);
        }
        //alert(grupo);
        if(configCal > 0)
        {    
            switch(grupo)
                {
                    case 'SEMILLAS':
                        semDisponible = $('#semilla_disponible').html();
                        sub_fila = parseFloat(val_pvp * val_cant);
                        if(sub_fila > semDisponible)
                        {
                            alert("Cuenta con un Monto de ("+semDisponible+") para ! SEMILLAS ! para montos mayores debe solicitar Autorizacion ....");  
                            $(this).val('0');
                            return false;
                        }else
                            {
                                if(val_cant > 0)
                                    {                                   			
                                    recalcular(fila);
                                    $('#semilla_disponible').html(semDisponible -sub_fila);
                                    }else
                                        {
                                            alert("La cantidad debe ser mayor a 00.0");
                                        }   
                            }
                    break;
                    case 'FERTILIZANTES':
                        ferDisponible = $('#fertilizante_disponible').html();
                        sub_fila = parseFloat(val_pvp * val_cant);                   
                        if(sub_fila > ferDisponible)
                        {
                            alert("Cuenta con un Monto de ("+ferDisponible+") para ! FERTILIZANTES ! para montos mayores debe solicitar Autorizacion ...."); 
                        }else
                            {
                                if(val_cant > 0)
                                    {
                                                
                                    recalcular(fila);
                                    $('#fertilizante_disponible').html(ferDisponible - sub_fila );
                                    }else
                                        {
                                            alert("La cantidad debe ser mayor a 00.0");
                                        }      

                            }
                    break;
                    case 'AGROQUIMICOS':

                            agroDisponible = $('#agroquimicos_disponible').html();
                            //alert(agroDisponible);
                            sub_fila = parseFloat(val_pvp * val_cant);                   
                            if(sub_fila > agroDisponible)
                            {
                                alert("Cuenta con un Monto de ("+agroDisponible+") para ! AGROQUIMICOS ! para montos mayores debe solicitar Autorizacion ...."); 
                            }else
                                {
                                    if(val_cant > 0)
                                        {
                                            recalcular(fila);        
                                            $('#agroquimicos_disponible').html(agroDisponible - sub_fila );
                                        }else
                                            {
                                                alert("La cantidad debe ser mayor a 00.0");
                                            }      
                                }
                    break;
                    case 'COMBUSTIBLES':
                        combusDisponible = $('#combustible_disponible').html();
                        sub_fila = parseFloat(val_pvp * val_cant);                   
                        if(sub_fila > combusDisponible)
                        {
                            alert("Cuenta con un Monto de ("+combusDisponible+") para ! COMBUSTIBLE ! para montos mayores debe solicitar Autorizacion ...."); 
                        }else
                            {
                                if(val_cant > 0)
                                    {
                                        recalcular(fila);        
                                        $('#combustible_disponible').html(combusDisponible - sub_fila );
                                    }else
                                        {
                                            alert("La cantidad debe ser mayor a 00.0");
                                        }      

                            } 
                    break;
                    case 'OTROS':
                        otroDisponible = $('#otroGto_disponible').html();
                        sub_fila = parseFloat(val_pvp * val_cant);                   
                        if(sub_fila > otroDisponible)
                        {
                            alert("Cuenta con un Monto de ("+otroDisponible+") para ! OTROS GASTOS ! para montos mayores debe solicitar Autorizacion  ...."); 
                        }else
                            {
                                if(val_cant > 0)
                                    {
                                        recalcular(fila);
                                    $('#otroGto_disponible').html(otroDisponible - sub_fila );
                                    }else
                                        {
                                            alert("La cantidad debe ser mayor a 00.0");
                                        }      

                            }     
                    
                    break;
            }
        }else
            {

                mto_aprobado = $('#mto-aprobado').html();
                mto_disponible = $('#mto-disponible').html();

                sub_fila = parseFloat(val_pvp * val_cant); 
                if(sub_fila > mto_disponible)
                {
                    alert("Sobrepasa el monto disponible ("+mto_disponible+") del Credito  ! para montos mayores debe solicitar Autorizacion  ...."); 
                }else
                    {
                        if(val_cant > 0)
                            {
                                var resto = parseFloat(mto_disponible) - sub_fila;
                                $('#mto-disponible').html(resto.toFixed(2));
                                recalcular(fila);
                                
                                //alert(sub_fila);
                            }else
                                {
                                    alert("La cantidad debe ser mayor a 00.0");
                                }      

                    }

            }
            
            
        $('#subtotal'+fila).val(sub_fila.toFixed(2));
		
			
 		
		 			
	});

    // metodo para enviar formulario
    var setDatos = function(){
        $('#productor').val($('#productor').val().trim());
        $('#credito').val($('#credito').val().trim());
        if($('#productor').val()=='0' ||  $('#credito').val()=='0' || $('#fecha').val()=='')
        {
            if($('#productor').val()=='0')
                {
                    alert('Complete los datos obligatorios *, Debe seleccionar un Productor');
                }
            if($('#credito').val()=='0')
                {
                    alert('Complete los datos obligatorios *, Debe seleccionar un Credito Aprobado del Productor');
                }
            if($('#fecha').val()=='')
                {
                    alert('Complete los datos obligatorios *, Debe indicar la fecha ');
                }
            if($('#totalDoc').val()=='0')
                {
                    alert('No se pueden hacer despachos con valor 0.00, verifique los productos ingresados');
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
            if($('#guardar').val()==4) //guarda el registro editado
            {
                if($('#almacen').val()=='0')
                    {
                        alert('Complete los datos obligatorios *, Indicar el almacen de recepción ');
                    }else{

                        if(confirm("¿Se Guardara la Devolución, dessea Continuar ?"))
                            {                    
                                $("#form-despacho-entregar").submit(); 
                            }  

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
