$(document).ready(function(){


    var getProducto = function(valor){

        $.post('/compras/proveedor/buscarProducto/','value='+valor,function(datos){
            if(datos.length>0)
                {
                    $('#tabla_prod tbody').html("");
                        var tr = '';
                        for (i = 0; i < datos.length; i++){
                            tr += '<tr>';
                            tr += '<td>'+datos[i].descripcion+'</td><td>'+datos[i].presentacion+'</td><td>'+datos[i].ctoActual+'</td><td>'+datos[i].ExistenciaTotal+'</td><td><button class="btn btn-default" type="button" id="carga" value="'+datos[i].id+'" data-dismiss="modal" ><i class="fa fa-cloud-download"></i></button></td>';
                            tr += '</tr>';
                        }
    
                    $('#tabla tbody').html(tr);
                }    
    
        },'json');
    };
    
    
    $(document).on('keyup','.searchProducto',function(e){
        var row = $(this).data('id');
        if(e.which == 13) {
            e.preventDefault();
            return false;
        }
        
        if(e.which == 115) {
            $("#fila").val(row);
            $("#myModal").modal(); 
            
        }    
        
    });
    
    $(document).on('keyup','#search_producto',function(){
        var cadena = $('#search_producto').val();
        var almacen = $('#almacen').val();
        if(cadena.length>3)
        {
            getProducto(cadena,almacen);
        }            
        
    });
    
    
//-----------------------------------------------------------------------------------------------------
//METODO QUE SE EJECUTA MEDIANTE CLASE .CARGA PRODUCTO SEGUN ID 
//-----------------------------------------------------------------------------------------------------
$(document).on('click','#carga',function(e){
    //var deposito = $(this).data('dep');
    var valor    = this.value;
    var fila       = $('#fila').val(); 
    //var stock    = $(this).data('stock');
    var disponible  = 0;
    // alert(id);
    $.ajax( {  
            url: '/almacen/despacho/cargarProducto/',
            type: 'POST',
            dataType : 'json',
            async: false,
            data: 'value='+valor,
            success:function(datos){
                            if(datos)
                            {
                                $('#codigo'+fila).val(datos[0].codigo);
                                $('#descripcion'+fila).val(datos[0].descripcion+'('+datos[0].presentacion+')');
                                //$('#pvp'+fila).val(datos[0].Pvp1);
                                $('#pvp'+fila).val('00.0');
                                $('#tsaImpuesto'+fila).val(datos[0].tasaImpuesto);
                                $('#cantidad'+fila).val('0');
                                $('#id'+fila).val(datos[0].id);
                                $('#subtotal'+fila).val('00.0');
                               // $('#costo'+fila).val(datos[0].ctoActual);
                               // $('#deposito'+fila).val(datos[0].nombre_almacen);
                               // $('#idDeposito'+fila).val(datos[0].idAlmacen);
                                
                                $('#cantidad'+fila).attr("readonly",false);
                                $('#pvp'+fila).focus();
                            }

                            },
            error: function(xhr, status) {
                            alert('Disculpe, existiÃ³ un problema');
                            }
            });

});




    //---------------------------------------------------------------------
    //Agrega filas a la tabla 
    //---------------------------------------------------------------------
    $(document).on('click',"#add_producto",function(){
        var valor =0;
       // $('#add_servicio').attr('disabled',true);
    
        var fecha = new Date();
       
        var count = $('#productos >tbody >tr').length;
        var idPrd= count +1;
    
        var nuevaFila="<tr>";
        
        nuevaFila=nuevaFila+"<td><input type='text' name='codigo[]' id='codigo"+idPrd+"'  data-id='"+idPrd+"' value=''   class='form-control form-control-border form-control-sm searchProducto'   /></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='descripcion[]' id='descripcion"+idPrd+"' class='form-control form-control-border form-control-sm'  readOnly='true' /><input type='hidden' name='id[]' id='id"+idPrd+"' value='0'/></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='pvp[]'  id='pvp"+idPrd+"' class='form-control form-control-border form-control-sm text-right' value='0.00'  /></td>";
        nuevaFila=nuevaFila+"<td><input type='text' name='cantidad[]' id='cantidad"+idPrd+"' class='form-control form-control-border form-control-sm text-right calcular_cant' value='' data-id='"+idPrd+"'  /></td>";
        nuevaFila=nuevaFila+"<td><input name='tsaImpuesto[]' id='tsaImpuesto"+idPrd+"' class='form-control form-control-border form-control-sm text-right calcular_impuesto' ></td>";
        nuevaFila=nuevaFila+"<td><input name='subtotal[]' id='subtotal"+idPrd+"' class='form-control form-control-border form-control-sm text-right' readOnly='true'></td>";
        nuevaFila=nuevaFila+"<td><button type='button' name='eliminar' id='eliminar"+idPrd+"' class='btn btn-default btn-sm' ><i class='fa fa-trash'></i></button></td>";
        nuevaFila=nuevaFila+"</tr>";
        $("#productos tbody").append(nuevaFila);
        $('#filas').val(idPrd);
        // $('#add_servicio').attr('disabled',false);
               
        
    
        //$('#add_servicio').attr('disabled',false);                
    });
    
    $(document).on('click','#enviar',function(e){
        var valor = $(this).val();
        getNotaMail(valor);     
    
    });
    
    var getNotaMail = function(valor){
        $.post('/compras/compra/enviarOrdenCpra/','value='+valor,function(datos){
            if(datos.length > 0)
                {
                    $('#resmail').html(datos);            
                } 
    
        },'json');
    };
    
    
    
    
        $(document).on('click','.imprimir',function(e){
            var valor = $(this).val();
            $('#resmail').html("");
            getNotaImp(valor);
            $('#enviar').val(valor);
            
        });
    
        var getNotaImp = function(valor){
            $.post('/compras/compra/cargarNotaImp/','value='+valor,function(datos){
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
    
        $(document).on('blur','.calcular_impuesto',function(){
    
            recalcular();
    
        });
    
        //-------------------------------------------------------------------------
        //--
        //-------------------------------------------------------------------------
        $(document).on('blur','.calcular_cant',function(){
    
            //-----------------------------------
            //valores de grid
            var fila      = $(this).data('id');
            var val_cant  = $(this).val();
            var val_pvp   = $('#pvp'+fila).val();
            var val_iva   = $('#tsaImpuesto'+fila).val();
            var val_subtotal = 0;
            var val_exento = 0;
            
            
            //-------------------------------------------
            //totalizacion
            var total    = $('#totalDoc').val();
            var subtotal = $('#subtotalDoc').val();
            var iva      = $('#impuestoDoc').val();
            //var exento   = $('#exentoDoc').val(); 
            //var imponible= $('#baseImponibleDoc').val();
            var sub_fila = 0;
            var iva_fila = 0; 
             
            if(val_cant > 0 && val_pvp >0)
            {
                sub_fila = parseFloat(val_pvp * val_cant);
                $('#subtotal'+fila).val(sub_fila.toFixed(2));
            }else
                {
                    sub_fila = parseFloat(val_pvp * val_cant);
                    $('#subtotal'+fila).val(sub_fila.toFixed(2));
                }
    
            recalcular()    
                   
                         
        });
    
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
                //$('#exentoDoc').val(excento.toFixed(2));
                //$('#baseImponibleDoc').val(imponible.toFixed(2));
            }
        };
    
    
    
        var setDatos = function(){
            if($('#condicion').val()=='' ||  $('#emision').val()=='' || $('#vencimiento').val()=='')
            {
                    //if($('#almacen').val()=='0')
                    //{
                    //    alert('Complete los datos obligatorios *, Debe seleccionar un Almacen de Recepción');
                    //}
                    if($('#proveedor').val()=='0')
                        {
                            alert('Complete los datos obligatorios *, Debe seleccionar un Proveedor');
                        }
                   
            }
            else
            {
                if($('#guardar').val()==1) //guarda el registro nuevo
                {   
                    if(confirm("¿Se Guardará el Documento; desea continuar ?"))
                    {
                        $("#form_Compra").submit();                    
                    }    
                                      
                }//FIN DE LA OPCION GUARDAR NUEVO 1
                
               
            }
        };  //FIN DE LA FUNCION setDatos
        
    
        $('#agregar').click(function(){
    
            setDatos();
    
        });
    
    
        $('#condicion').change(function(){
            let valor = (this).value;
            if(valor =='CREDITO')
                $('#dias_cre').attr('disabled',false);
            else
                $('#dias_cre').attr('disabled',true);
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