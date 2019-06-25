AmCharts.loadJSON = function(url) {
  // create the request
  if (window.XMLHttpRequest) {
    // IE7+, Firefox, Chrome, Opera, Safari
    var request = new XMLHttpRequest();
  } else {
    // code for IE6, IE5
    var request = new ActiveXObject('Microsoft.XMLHTTP');
  }

  // load it
  // the last "false" parameter ensures that our code will wait before the
  // data is loaded
  request.open('GET', url, false);
  request.send();

  // parse adn return the output
  return eval(request.responseText);
};
var ChartsAmcharts = function() {
    var initChartSample7 = function() {
        var chartData = AmCharts.loadJSON('inc/gui_grafico_pagamento_home_data.php');
        var chart = AmCharts.makeChart("chart_7", {
            "type": "pie",
            "theme": "light",

            "fontFamily": 'Open Sans',
            
            "color":    '#888',

            "dataProvider": chartData,
            "valueField": "valor_total",
            "titleField": "local_pagamento",
            "outlineAlpha": 0.4,
            "depth3D": 15,
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "angle": 30,
            "exportConfig": {
                menuItems: [{
                    icon: '/lib/3/images/export.png',
                    format: 'png'
                }]
            }
        });

        jQuery('.chart_7_chart_input').off().on('input change', function() {
            var property = jQuery(this).data('property');
            var target = chart;
            var value = Number(this.value);
            chart.startDuration = 0;

            if (property == 'innerRadius') {
                value += "%";
            }

            target[property] = value;
            chart.validateNow();
        });

        $('#chart_7').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }
    
    var initChartSample8 = function() {
        var chartData = AmCharts.loadJSON('inc/gui_grafico_guia_home_data.php?filtro=semana');
        var chart = AmCharts.makeChart("chart_8", {
            "type": "pie",
            "theme": "light",

            "fontFamily": 'Open Sans',
            
            "color":    '#888',

            "dataProvider": chartData,
            "valueField": "contar_linhas",
            "titleField": "status",
            "outlineAlpha": 0.4,
            "depth3D": 15,
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "angle": 30,
            "exportConfig": {
                menuItems: [{
                    icon: '/lib/3/images/export.png',
                    format: 'png'
                }]
            }
        });

        jQuery('.chart_7_chart_input').off().on('input change', function() {
            var property = jQuery(this).data('property');
            var target = chart;
            var value = Number(this.value);
            chart.startDuration = 0;

            if (property == 'innerRadius') {
                value += "%";
            }

            target[property] = value;
            chart.validateNow();
        });

        $('#chart_7').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }
    
    var initChartSample9 = function() {
        var chartData = AmCharts.loadJSON('inc/gui_grafico_guia_home_data.php?filtro=mes');
        var chart = AmCharts.makeChart("chart_9", {
            "type": "pie",
            "theme": "light",

            "fontFamily": 'Open Sans',
            
            "color":    '#888',

            "dataProvider": chartData,
            "valueField": "contar_linhas",
            "titleField": "status",
            "outlineAlpha": 0.4,
            "depth3D": 15,
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "angle": 30,
            "exportConfig": {
                menuItems: [{
                    icon: '/lib/3/images/export.png',
                    format: 'png'
                }]
            }
        });

        jQuery('.chart_7_chart_input').off().on('input change', function() {
            var property = jQuery(this).data('property');
            var target = chart;
            var value = Number(this.value);
            chart.startDuration = 0;

            if (property == 'innerRadius') {
                value += "%";
            }

            target[property] = value;
            chart.validateNow();
        });

        $('#chart_7').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }
    
     var initChartSample2 = function() {
        var chartData = AmCharts.loadJSON('inc/gui_grafico_faturamento_home_data.php?filtro=semanal');
        var chart = AmCharts.makeChart("chart_2", {
    "theme": "light",
    "type": "serial",
    "dataProvider": chartData,
    "valueAxes": [{
        "axisAlpha": 0,
        "position": "left",
        "title": "VALOR MARGEM EM R$",
    }],
    "startDuration": 1,
    "graphs": [{
        "balloonText": "CONSULTAS ([[category]]): <b>R$ [[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2004",
        "type": "column",
        "valueField": "consulta"
    }, {
        "balloonText": "EXAMES ([[category]]): <b>R$ [[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2005",
        "type": "column",
        "clustered":false,
        "columnWidth":0.6,
        "valueField": "exame"
    },
    {
        "balloonText": "DESPESAS ([[category]]): <b>R$ [[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2006",
        "type": "column",
        "clustered":false,
        "columnWidth":0.4,
        "valueField": "despesa"
    }],
    "plotAreaFillAlphas": 0.1,
    "categoryField": "periodo",
    "categoryAxis": {
        "gridPosition": "start"
    }/*,
    "export": {
    	"enabled": true
     }*/

});

        $('#chart_2').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }
    
    var initChartSample3 = function() {
        var chartData = AmCharts.loadJSON('inc/gui_grafico_faturamento_home_data.php?filtro=mensal');
        var chart = AmCharts.makeChart("chart_3", {
    "theme": "light",
    "type": "serial",
    "dataProvider": chartData,
    "valueAxes": [{
        "axisAlpha": 0,
        "position": "left",
        "title": "VALOR MARGEM EM R$",
    }],
    "startDuration": 1,
    "graphs": [{
        "balloonText": "CONSULTAS ([[category]]): <b>R$ [[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2004",
        "type": "column",
        "valueField": "consulta"
    }, {
        "balloonText": "EXAMES ([[category]]): <b>R$ [[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2005",
        "type": "column",
        "clustered":false,
        "columnWidth":0.6,
        "valueField": "exame"
    },
    {
        "balloonText": "DESPESAS ([[category]]): <b>R$ [[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2006",
        "type": "column",
        "clustered":false,
        "columnWidth":0.4,
        "valueField": "despesa"
    }],
    "plotAreaFillAlphas": 0.1,
    "categoryField": "periodo",
    "categoryAxis": {
        "gridPosition": "start"
    }/*,
    "export": {
    	"enabled": true
     }*/

});

        $('#chart_2').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
        });
    }
    
    return {
        //main function to initiate the module

        init: function() {
            
            initChartSample7();
            initChartSample8();
            initChartSample9();
            initChartSample2();
            initChartSample3();

        }

    };
}();

jQuery(document).ready(function() {    
   ChartsAmcharts.init(); 
});