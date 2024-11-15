"use strict";

var statistics_chart = document.getElementById("myChart").getContext('2d');

var myChart = new Chart(statistics_chart, {
  type: 'line',
  data: {
    labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    datasets: [{
      label: 'Statistics',
      data: [640, 387, 530, 302, 430, 270, 488],
      borderWidth: 5,
      borderColor: '#6777ef',
      backgroundColor: 'transparent',
      pointBackgroundColor: '#fff',
      pointBorderColor: '#6777ef',
      pointRadius: 4
    }]
  },
  options: {
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          stepSize: 150
        }
      }],
      xAxes: [{
        gridLines: {
          color: '#fbfbfb',
          lineWidth: 2
        }
      }]
    },
  }
});

// $('#visitorMap').vectorMap(
// {
//   map: 'world_en',
//   backgroundColor: '#ffffff',
//   borderColor: '#f2f2f2',
//   borderOpacity: .8,
//   borderWidth: 1,
//   hoverColor: '#000',
//   hoverOpacity: .8,
//   color: '#ddd',
//   normalizeFunction: 'linear',
//   selectedRegions: false,
//   showTooltip: true,
//   pins: {
//     id: '<div class="jqvmap-circle"></div>',
//     my: '<div class="jqvmap-circle"></div>',
//     th: '<div class="jqvmap-circle"></div>',
//     sy: '<div class="jqvmap-circle"></div>',
//     eg: '<div class="jqvmap-circle"></div>',
//     ae: '<div class="jqvmap-circle"></div>',
//     nz: '<div class="jqvmap-circle"></div>',
//     tl: '<div class="jqvmap-circle"></div>',
//     ng: '<div class="jqvmap-circle"></div>',
//     si: '<div class="jqvmap-circle"></div>',
//     pa: '<div class="jqvmap-circle"></div>',
//     au: '<div class="jqvmap-circle"></div>',
//     ca: '<div class="jqvmap-circle"></div>',
//     tr: '<div class="jqvmap-circle"></div>',
//   },
// });

// $('#visitorMap').vectorMap({
//     map: 'indonesia_en', // Ini akan kita ganti dengan indonesia nanti
//     backgroundColor: '#ffffff',
//     borderColor: '#f2f2f2',
//     borderOpacity: .8,
//     borderWidth: 1,
//     hoverColor: '#000',
//     hoverOpacity: .8,
//     color: '#ddd',
//     normalizeFunction: 'linear',
//     selectedRegions: ['ID'], // Highlight Indonesia
//     showTooltip: true,
//     // Focus ke area Indonesia, khususnya Jawa Timur
//     zoomMin: 2,
//     zoomMax: 8,
//     focusOn: {
//         x: 0.615,
//         y: 0.41,
//         scale: 3
//     },
//     pins: {
//         // Koordinat untuk beberapa lokasi di Surabaya
//         'sb1': '<div class="map-pin" data-toggle="tooltip" title="Surabaya Pusat"></div>',
//         'sb2': '<div class="map-pin" data-toggle="tooltip" title="Surabaya Selatan"></div>',
//         'sb3': '<div class="map-pin" data-toggle="tooltip" title="Surabaya Timur"></div>',
//     },
//     pinMode: 'content',
//     // Data dummy untuk pin locations
//     pinLocations: {
//         'sb1': [112.7521, -7.2575],
//         'sb2': [112.7479, -7.2868],
//         'sb3': [112.7700, -7.2901],
//     },
// });

// weather
getWeather();
setInterval(getWeather, 600000);

function getWeather() {
  $.simpleWeather({
  location: 'Bogor, Indonesia',
  unit: 'c',
  success: function(weather) {
    var html = '';
    html += '<div class="weather">';
    html += '<div class="weather-icon text-primary"><span class="wi wi-yahoo-' + weather.code + '"></span></div>';
    html += '<div class="weather-desc">';
    html += '<h4>' + weather.temp + '&deg;' + weather.units.temp + '</h4>';
    html += '<div class="weather-text">' + weather.currently + '</div>';
    html += '<ul><li>' + weather.city + ', ' + weather.region + '</li>';
    html += '<li> <i class="wi wi-strong-wind"></i> ' + weather.wind.speed+' '+weather.units.speed + '</li></ul>';
    html += '</div>';
    html += '</div>';

    $("#myWeather").html(html);
  },
  error: function(error) {
    $("#myWeather").html('<div class="alert alert-danger">'+error+'</div>');
  }
  });
}
