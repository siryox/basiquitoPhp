

$.post('/intranet/cargarDashboard/','value=2',function(datos){
  if(datos.length > 0)
  {
      
    
    var data = JSON.parse(datos[0].datos);
    var alcance =  [data.alcance.hectAprobadas,(data.alcance.hectMax - data.alcance.hectAprobadas)];
    var aprobacion = [data.creditos.totCreditosAp,(data.creditos.totCreditos-data.creditos.totCreditosAp)];
    var montos = [data.montos.mtoTotAprobado,data.produccion.prodTotalMto];
    //console.log(alcance);
    
    grafAlcance(alcance);
    
    grafAprobaciones(aprobacion);

    grafMontos(montos);

  }


},'json');




var grafAlcance = function(alcance){
//------------------------------------------------------------------------------------
// grafico para alcance logrado
//-----------------------------------------------------------------------------------
  var ctx = document.getElementById('grafico1').getContext('2d');
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Ha. Captadas', 'Ha. no Captadas'],
      datasets: [{
        label: 'My First Dataset',
        data: alcance,
        backgroundColor: ['rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)'],
        borderColor: ['rgba(255, 99, 132, 1)','rgba(54, 162, 235, 1)','rgba(255, 206, 86, 1)'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display : 'false',
          position: 'left'
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return `Value: ${tooltipItem.raw}`;
            }
          }
        }
      }
    }
  })

}




//--------------------------------------------------------------------------------------
// grafico de aprobaciones
//--------------------------------------------------------------------------------------
var grafAprobaciones = function(aprobacion){


    const ctx1 = document.getElementById('grafico2').getContext('2d');
    const myPieChart1 = new Chart(ctx1, {
      type: 'pie',
      data: {
        labels: ['Creditos Aprobados', 'Creditos Rezazados'],
        datasets: [{
          label: 'My First Dataset',
          data: aprobacion,
          backgroundColor: ['rgba(255, 99, 554, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)'],
          borderColor: ['rgba(255, 99, 132, 1)','rgba(54, 162, 235, 1)','rgba(255, 206, 86, 1)'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          },
          tooltip: {
            callbacks: {
              label: function(tooltipItem) {
                return `Value: ${tooltipItem.raw}`;
              }
            }
          }
        }
      }
    })
}

//--------------------------------------------------------------------------------------
// grafico de aprobaciones
//--------------------------------------------------------------------------------------
var grafMontos = function(montos){


  const ctx1 = document.getElementById('grafico5').getContext('2d');
  const myPieChart1 = new Chart(ctx1, {
    type: 'doughnut',
    data: {
      labels: ['Total Aprob. $', 'Total Produción $'],
      datasets: [{
        label: 'My First Dataset',
        data: montos,
        backgroundColor: ['rgba(255, 99, 554, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)'],
        borderColor: ['rgba(255, 99, 132, 1)','rgba(54, 162, 235, 1)','rgba(255, 206, 86, 1)'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return `Value: ${tooltipItem.raw}`;
            }
          }
        }
      }
    }
  })
}