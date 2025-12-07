$(document).ready(function(){
  
  let mapInitialized = false;

  var initMap = function(cords){
    if (!mapInitialized) {
      const map = L.map('map').setView(cords, 12);
  
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:
          '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(map);
  
      L.marker(cords)
        .addTo(map)
        .bindPopup('¡Hola! Este es un marcador.')
        .openPopup();
  
      mapInitialized = true;
    }

  }


  $(document).on('click','.verImagen',function(){      
    var ruta = 'https://agricoladelmeta.com/assets/img/imgMovil/app/'
    var reg = $(this).data('id');
    var carrusel = "";
    
     $('#slide').html('');

    $.post('/creditos/visita/cargarImgVisitas/','value='+reg,function(datos){
        if(datos.length > 0)
            {
                var cont = 0;
                var data = JSON.parse(datos[0].data);
               
                var cordenadas = [data.latitud,data.longitud];
                var imagen = data.images;
                
                var producto = JSON.parse(datos[0].ArrProductos);
                var comentario = data.comentarios;
                var tecnico = '(Técnico: '+datos[0].nombreTecnico+' Fecha de Visita: '+datos[0].fecVisita+')';

                var listaProductos = "";
                var notas = "";
                //alert(producto[0]);
                listaProductos = '<ul>';
                carrusel = carrusel+'<div class="slide-inner">';    

                for(i = 0; i < imagen.length;i++)
                {
                  cont = i+1;
                  carrusel = carrusel+'<input class="slide-open" type="radio" id="slide-'+cont+'" name="slide" aria-hidden="true" hidden="" checked="checked">';
                  carrusel = carrusel+'<div class="slide-item"><img src="'+ruta+imagen[i]+'"></div>';
                  if(producto.length >0)
                    {
                      listaProductos = listaProductos + '<li>N/A</li>';
                    }

                    

                }
                
                listaProductos = listaProductos+'</ul>';

                notas = notas + '<ul>';
                notas = notas + '<li>Fase : '+data.fase+'</li>';
                notas = notas + '<li>Estado : '+data.estadoActual+'</li>';
                notas = notas + '<li>Condiciones : '+data.condiciones+'</li>';
                notas = notas + '</ul>';

                carrusel = carrusel+'<label for="slide-3" class="slide-control prev control-1">‹</label>';
                carrusel = carrusel+'<label for="slide-2" class="slide-control next control-1">›</label>';
                carrusel = carrusel+'<label for="slide-1" class="slide-control prev control-2">‹</label>';
                carrusel = carrusel+'<label for="slide-3" class="slide-control next control-2">›</label>';
                carrusel = carrusel+'<label for="slide-2" class="slide-control prev control-3">‹</label>';
                carrusel = carrusel+'<label for="slide-1" class="slide-control next control-3">›</label>';
                carrusel = carrusel+'<ol class="slide-indicador">';
                carrusel = carrusel+'<li><label for="slide-1" class="slide-circulo">•</label></li>';
                carrusel = carrusel+'<li><label for="slide-2" class="slide-circulo">•</label></li>';
                carrusel = carrusel+'<li><label for="slide-3" class="slide-circulo">•</label></li>';
                carrusel = carrusel+'</ol>';
                carrusel = carrusel+'</div>';

                $('#slide').html(carrusel);

                $('#finca').html(datos[0].nombreFinca);
                $('#locacion').html(data.latitud+' , '+data.longitud);
                $('#productor').html(datos[0].productor);
                $('#financiamiento').html(datos[0].programaFinanc);
                $('#notas').html(notas);
                $('#productos').html(listaProductos);
                $('#comentarios').html(comentario);
                $('#tecnico').html(tecnico);

                initMap(cordenadas);

            } 

    },'json');     

    
 
    
  });


  $(document).on('click','.verVideos',function(){
      var ruta = 'https://agricoladelmeta.com/assets/img/imgMovil/app/'
      var reg = $(this).data('id');
      var carrusel = "";
      
       $('#visor').html('');
       $.post('/creditos/visita/cargarImgVisitas/','value='+reg,function(datos){
        if(datos.length > 0)
        {
            var data = JSON.parse(datos[0].data); 
           
            var videos = data.videos;
            var tecnico = '(Técnico: '+datos[0].nombreTecnico+' Fecha de Visita: '+datos[0].fecVisita+')';

            carrusel = carrusel+'<div class="slide-inner">';    
            for(i = 0; i < videos.length;i++)
              {
                cont = i+1;
                carrusel = carrusel+'<input class="slide-open" type="radio" id="slide-'+cont+'" name="slide" aria-hidden="true" hidden="" checked="checked">';
                carrusel = carrusel+'<div class="item"><video width="700" height="350" controls><source src="'+ruta+videos[i]+'" /> <p>Tu navegador no admite video.</p></video></div>';              

              }
              carrusel = carrusel+'<label for="slide-3" class="slide-control prev control-1">‹</label>';
                carrusel = carrusel+'<label for="slide-2" class="slide-control next control-1">›</label>';
                carrusel = carrusel+'<label for="slide-1" class="slide-control prev control-2">‹</label>';
                carrusel = carrusel+'<label for="slide-3" class="slide-control next control-2">›</label>';
                carrusel = carrusel+'<label for="slide-2" class="slide-control prev control-3">‹</label>';
                carrusel = carrusel+'<label for="slide-1" class="slide-control next control-3">›</label>';
                carrusel = carrusel+'<ol class="slide-indicador">';
                carrusel = carrusel+'<li><label for="slide-1" class="slide-circulo">•</label></li>';
                carrusel = carrusel+'<li><label for="slide-2" class="slide-circulo">•</label></li>';
                carrusel = carrusel+'<li><label for="slide-3" class="slide-circulo">•</label></li>';
                carrusel = carrusel+'</ol>';
                carrusel = carrusel+'</div>';


              $('#visor').html(carrusel);
              $('#titulo_video').html(tecnico);

        }

       },'json');  

    });


//------------------------------------------------------------------------- 
///configuracion de datatable
//---------------------------------------------------------------------------
 $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
    
  });


});