$(document).ready(function(){

    $(document).on('change','#programa',function(){
        
        var id = $(this).val();
        getCreditoAprobar(id);


    });

    $(document).on('click','.showCredit',function(){
        
        var id = $(this).val();
        getCredito(id);

    });

    $(document).on('click','.activate',function(){
        var id = this.id;

        var pos = id.search('-');
        var len = id.length;
        var cadena = id.substring(pos+1,len)
        //alert(cadena);
        $('#'+cadena).attr('readonly',false);

    });

    $(document).on('change','#superficieAprobada',function(){
        
        recalcularFase('preparacion');
    });

    $(document).on('click','#agregar',function(){
        
        setDatos();
    });

     //----------------------------------------------------------------------------------------
    //
    //---------------------------------------------------------------------------------------
    var getCredito = function(valor){
        $.post('/creditos/credito/buscarCredito/','value='+valor,function(datos){
            if(datos.length > 0)
            {
                var faces = JSON.parse(datos[0].progFinOrig);

                $('#title_credito').html("Programa de Financiamiento: "+datos[0].ProgFinanc);
                $('#idFiscal').attr('value', datos[0].idFiscalProductor);
                $('#nombProductor').attr('value', datos[0].productores_nombreProductor);
                $('#unidadProduccion').attr('value', datos[0].fincas_nombre);
                $('#fechaSolicitud').attr('value', datos[0].fechaSolicitud);
                $('#superficieSolicitada').attr('value', datos[0].creditos_superficieSolicitada);
                $('#idCredito').attr('value', datos[0].id);
                
                //console.log(faces);
                if(faces)
                {
                    var content = "";
                    var contentReal = "";
                    for(var key in faces)
                    {
                        //console.log(faces[key]);
                        switch(key)
                        {
                            case 'preparacion':
                                $("#preparacion").html("");
                                $("#preparacionReal").html("");
                                for(var i in faces[key])
                                {
                                    
                                    var unidad = cargarUnida(i);

                                    content = content+"<label>"+i +"</label>";
                                    content = content+"<div class='input-group input-group-sm'>"; 
                                    content = content+"<div class='input-group-prepend'>"
                                    content = content+"<span class='input-group-text'>"
                                    content = content+"<input type='checkbox' id='chk-preparacion_"+i+"' class='activate' />"
                                    content = content+"</span>"
                                    content = content+"</div>";

                                    content = content+"<input name='preparacion_"+i+"' id='preparacion_"+i+"' type='text' class='form-control form-control-sm text-right' value='"+faces[key][i]+"' readOnly='true' />"
                                    
                                    content = content+"<div class='input-group-prepend'>"
                                    content = content+"<span class='input-group-text'>"
                                    content = content+unidad
                                    content = content+"</span>"
                                    content = content+"</div>";    
                                    content = content+"</div>";            



                                    
                                    contentReal = contentReal+"<label>"+i+"</label>";
                                    contentReal = contentReal+"<div class='input-group input-group-sm'>"; 
                                    //contentReal = contentReal+"<div class='input-group-prepend'>"
                                    //contentReal = contentReal+"<span class='input-group-text'>"
                                   // contentReal = contentReal+"<input type='checkbox'   />"
                                   // contentReal = contentReal+"</span>"
                                  //  contentReal = contentReal+"</div>";

                                    contentReal = contentReal+"<input name='preparacion_"+i+"_real' id='preparacion_"+i+"_real' type='text' class='form-control form-control-sm text-right' value='"+faces[key][i]+"' readOnly='true' >"
                                    
                                    contentReal = contentReal+"<div class='input-group-prepend'>"
                                    contentReal = contentReal+"<span class='input-group-text'>"
                                    contentReal = contentReal+unidad
                                    contentReal = contentReal+"</span>"
                                    contentReal = contentReal+"</div>";    
                                    contentReal = contentReal+"</div>";  
                                }
                                $("#preparacion").append(content);
                                $("#preparacionReal").append(contentReal);
                                content = "";
                                contentReal = "";
                            break;
                            case 'siembra':
                                $("#siembra").html("");
                                $("#siembraReal").html("");
                                for(var j in faces[key])
                                    {
                                        var unidad = cargarUnida(j);

                                        content = content+"<label>"+j+"</label>";
                                        content = content+"<div class='input-group input-group-sm'>"; 
                                        content = content+"<div class='input-group-prepend'>"
                                        content = content+"<span class='input-group-text'>"
                                        content = content+"<input type='checkbox'   />"
                                        content = content+"</span>"
                                        content = content+"</div>";
                                        
                                        content = content+"<input name='siembra_"+j+"' id='siembra_"+j+"' type='text' class='form-control form-control-sm text-right' value='"+faces[key][j]+"' readOnly='true' >"
                                        
                                        content = content+"<div class='input-group-prepend'>"
                                        content = content+"<span class='input-group-text'>"
                                        content = content+unidad
                                        content = content+"</span>"
                                        content = content+"</div>";    
                                        content = content+"</div>";  
                                        
                                        
                                        
                                        contentReal = contentReal+"<label>"+j+"</label>";
                                        contentReal = contentReal+"<div class='input-group input-group-sm'>"; 
                                        
                                        contentReal = contentReal+"<input name='siembra_"+j+"_real' id='siembra_"+j+"_real' type='text' class='form-control form-control-sm text-right' value='"+faces[key][j]+"' readOnly='true' >"
                                        
                                        contentReal = contentReal+"<div class='input-group-prepend'>"
                                        contentReal = contentReal+"<span class='input-group-text'>"
                                        contentReal = contentReal+unidad
                                        contentReal = contentReal+"</span>"
                                        contentReal = contentReal+"</div>";    
                                        contentReal = contentReal+"</div>"; 
                                    }
                                $("#siembra").append(content);
                                $("#siembraReal").append(contentReal);
                                content = "";
                                contentReal = "";

                            break;
                            case 'mantenimiento':
                                $("#mantenimiento").html("");
                                $("#mantenimientoReal").html("")
                                for(var k in faces[key])
                                    {
                                        var unidad = cargarUnida(k);
                                        
                                        content = content+"<label>"+k+"</label>";
                                        content = content+"<div class='input-group input-group-sm'>"; 
                                        content = content+"<div class='input-group-prepend'>"
                                        content = content+"<span class='input-group-text'>"
                                        content = content+"<input type='checkbox'   />"
                                        content = content+"</span>"
                                        content = content+"</div>";
                                        content = content+"<input name='mantenimiento_"+k+"' id='mantenimiento_"+k+"' type='text' class='form-control form-control-sm text-right' value='"+faces[key][k]+"' readOnly='true' >"
                                        
                                        content = content+"<div class='input-group-prepend'>"
                                        content = content+"<span class='input-group-text'>"
                                        content = content+unidad
                                        content = content+"</span>"
                                        content = content+"</div>";    
                                        content = content+"</div>";  



                                        contentReal = contentReal+"<label>"+k+"</label>";
                                        contentReal = contentReal+"<div class='input-group input-group-sm'>"; 
                                        
                                        contentReal = contentReal+"<input name='mantenimiento_"+k+"_real' id='mantenimiento_"+k+"_real' type='text' class='form-control form-control-sm text-right' value='"+faces[key][k]+"' readOnly='true' >"
                                       
                                        contentReal = contentReal+"<div class='input-group-prepend'>"
                                        contentReal = contentReal+"<span class='input-group-text'>"
                                        contentReal = contentReal+unidad
                                        contentReal = contentReal+"</span>"
                                        contentReal = contentReal+"</div>";    
                                        contentReal = contentReal+"</div>"; 
                                    }
                                $("#mantenimiento").append(content);
                                $("#mantenimientoReal").append(contentReal); 
                                content = "";
                                contentReal = "";  
                            break;
                            case 'cosecha':
                                $("#cosecha").html("");
                                $("#cosechaReal").html(""); 
                                for(var y in faces[key])
                                    {
                                        var unidad = cargarUnida(y);

                                        content = content+"<label>"+y+"</label>";
                                        content = content+"<div class='input-group input-group-sm'>"; 
                                        content = content+"<div class='input-group-prepend'>"
                                        content = content+"<span class='input-group-text'>"
                                        content = content+"<input type='checkbox'   />"
                                        content = content+"</span>"
                                        content = content+"</div>";
                                        content = content+"<input name='cosecha_"+y+"' id='cosecha_"+y+"' type='text' class='form-control form-control-sm text-right' value='"+faces[key][y]+"' readOnly='true'>"
                                        
                                        content = content+"<div class='input-group-prepend'>"
                                        content = content+"<span class='input-group-text'>"
                                        content = content+unidad
                                        content = content+"</span>"
                                        content = content+"</div>";    
                                        content = content+"</div>";  

                                        contentReal = contentReal+"<label>"+y+"</label>";
                                        contentReal = contentReal+"<div class='input-group input-group-sm'>"; 
                                        contentReal = contentReal+"<input name='cosecha_"+y+"_real' id='cosecha_"+y+"_real' type='text' class='form-control form-control-sm text-right' value='"+faces[key][y]+"' readOnly='true' >"
                                       
                                        contentReal = contentReal+"<div class='input-group-prepend'>"
                                        contentReal = contentReal+"<span class='input-group-text'>"
                                        contentReal = contentReal+unidad
                                        contentReal = contentReal+"</span>"
                                        contentReal = contentReal+"</div>";    
                                        contentReal = contentReal+"</div>";
                                    }
                                $("#cosecha").append(content);
                                $("#cosechaReal").append(contentReal); 
                                content = "";
                                contentReal = "";      
                            break;
                        }
                    }    
                }
                    

                
                
            } 
        },'json');
    };

    


    var getCreditoAprobar = function(valor){
        $.post('/creditos/credito/buscarCreditoPorAprobar/','value='+valor,function(datos){
            if(datos.length > 0)
            {
                $("#tabla_creditos tbody").html('');
                for(i= 0;i < datos.length;i++ )
                {
                    var nombre = datos[i].productores_nombreProductor.toUpperCase();
                    var nuevaFila="<tr>";
                    nuevaFila=nuevaFila+"<td>"+datos[i].id+"</td>";
                    nuevaFila=nuevaFila+"<td>"+nombre+"</td>"
                    nuevaFila=nuevaFila+"<td class=''>"+datos[i].fincas_nombre+"</td>"
                    nuevaFila=nuevaFila+"<td>"+datos[i].id+"</td>";
                    nuevaFila=nuevaFila+"<td>"+datos[i].creditos_superficieSolicitada+"</td>";
                    nuevaFila = nuevaFila+"<td><button type='button' name='mostrar'+i id='mostrar'+i value='"+datos[i].id+"' class='btn btn-default showCredit' data-toggle='modal' data-target='#modal-xl'><i class='fas  fa-folder-open' ></i></button></td>";
                    nuevaFila=nuevaFila+"</tr>";
                    $("#tabla_creditos tbody").append(nuevaFila);


                }
                
            }else
                alert("Consulta sin Resultados....");
        },'json');
    };


    var cargarUnida = function(valor){

        var cadena = valor.search('-');
        var unidad = "";
        if(cadena > 0)
        {
            unidad = valor.substring(0,cadena);
            switch(unidad)
            {
                case 'Mto':
                    return '$';
                break;
                case 'dias':
                    return 'Ds.';
                break;
                case 'Lt':
                    return 'Lt.';
                break;
                case 'Kg':
                    return 'Kg.';
                break;
                case 'visitas':
                    return 'Visitas.';
                break;
                


            }
            
        }else
            {
                switch(valor)
                {
                    case 'Mto':
                        return '$';
                    break;
                    case 'dias':
                        return 'Ds.';
                    break;
                    case 'Lt':
                        return 'Lt.';
                    break;
                    case 'Kg':
                        return 'Kg.';
                    break;
                    case 'visitas':
                        return 'Visitas.';
                    break;
                }
            
            }
    } 

    var recalcularFase = function(valor){
        var ha_aprobada = $('#superficieAprobada').val();
        var formulario = document.getElementById('form_aprobacion');
        for (var i=0;i<formulario.elements.length;i++)
        {
            var obj = formulario.elements[i];
            if(obj.type=='text')
            {
                    var name = formulario.elements[i].name; 
                    var valor = 0;
                    if(name.search('Mto') >0)
                    {   
                        if(name.search('real') > 0)
                        {
                            //document.getElementById(name).value = formulario.elements[i].value * ha_aprobada;
                        }else
                            {
                                valor = parseFloat(formulario.elements[i].value * ha_aprobada);
                                //console.log(formulario.elements[i].name +':'+formulario.elements[i].value);
                                document.getElementById(name+'_real').value = valor;
                            }   
                                                
                    }
                    if(name.search('Lt') >0)
                    {
                        if(name.search('real') > 0)
                        {
                            
                        }else
                            {
                                //console.log(formulario.elements[i].name +':'+formulario.elements[i].value);
                                document.getElementById(name+'_real').value = formulario.elements[i].value * ha_aprobada;
                            }
                        
                    }
                    if(name.search('Kg') >0)
                        {
                            if(name.search('real') > 0)
                            {
                                
                            }else
                                {
                                    //console.log(formulario.elements[i].name +':'+formulario.elements[i].value);
                                    document.getElementById(name+'_real').value = formulario.elements[i].value * ha_aprobada;
                                }
                            
                        }
                        //console.log(formulario.elements[i].name +':'+formulario.elements[i].value);
                  
            }
            
            
        }
            //var valor = this.value;
            


    }



     // metodo para enviar formulario
     var setDatos = function(){
        $('#unidadProduccion').val($('#unidadProduccion').val().trim());
        $('#idCredito').val($('#idCredito').val().trim());
        if($('#idFiscal').val()=='' || $('#idCredito').val()=='0' || $('#unidadProduccion').val()=='' )
        {
            alert('Complete los datos obligatorios *');
        }
        else
        {
            if($('#guardar').val()==3) //guarda el registro nuevo
			{   
                if(confirm("¿Se Aprobara el Credito, desea continuar... ?"))
                {
                    $("#form_aprobacion").submit();                    
                }    
                        
					
			}//FIN DE LA OPCION GUARDAR NUEVO 1
			
            
        }
    };  //FIN DE LA FUNCION setDatos
});