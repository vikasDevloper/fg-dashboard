function create_bar(id,title,xaxis,data,seriesname){
  var options = {
    chart: {
      renderTo: id,
      type: 'column',

    },
    title: {
      text: title
    },
    plotOptions: {
            series: {
                pointWidth: 50
            }
        },
    xAxis: {

      categories:  xaxis

    },

    yAxis: {
      labels: {
        formatter: function () {
         return this.value / 10000000 + 'Cr';
       }
     },
     title: {

      text: 'Amounts'

    }

  },

  series: [{
    name: seriesname,
    data:  data
  }],
  credits: {
    enabled: false
  }
};
var chart = new Highcharts.Chart(options);
chart.reflow();
}

function create_group_bar(id,title,xaxis,data,seriesname){
  var options = {
    chart: {
      renderTo: id,
      type: 'column',

    },
    title: {
      text: title
    },
    xAxis: {

      categories:  xaxis

    },

    yAxis: {
      labels: {
        formatter: function () {
         return this.value / 10000000 + 'Cr';
       }
     },
     title: {

      text: 'Amounts'

    }

  },

  series: data,
  credits: {
    enabled: false
  }
};
var chart = new Highcharts.Chart(options);

}
//create_drilldown_bar
 function create_drilldown_bar(id,title,xaxis,data,seriesname){

 var options = {
  chart: {
    renderTo: id,
    type: 'column',
    events: {
                drilldown: function (e) {
                 
                        var chart = this;
                            //calling ajax to load the drill down levels
                            $.get("/getMonthly",{ 'month':  e.point.name}, function(data) {
                               // chart.hideLoading();
                                console.log(data);
                                chart.addSeriesAsDrilldown(e.point, jQuery.parseJSON(data));
                            }); 
                     
                }
            }
  },
plotOptions: {
            series: {
                pointWidth: 50
            }
        },
  title: {

    text: title

  },

/*  xAxis: {

            //categories: ['Jan','Feb','Mar', 'Apr', 'May', 'June']
            //type: 'category'

          },*/
          xAxis: [
                    {
                    id: 0,
                    type: 'category'
                  }, {
                    id: 1,
                    type: 'datetime'
                  }
                ],

          yAxis: {
            labels: {
                formatter: function () {
                 return this.value / 10000000 + 'Cr';
               }
             },
               title: {

              text: 'Amounts'

            }

          },

          series: [{
            name: 'Online Turnover',
            type: 'column',
            colorByPoint: true,
            xAxis: 0,
            data:  data5
          }] 
          ,
        credits: {
          enabled: false
        },
        drilldown: {
        series: []
          }
 }
 var chart = new Highcharts.Chart(options);

}

function create_pie_chart(id, title, piobj, seriesname){

   var options = {

    chart: {
        renderTo: id,
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: title
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
      series: [{
        name: seriesname,
        colorByPoint: true,
        data: piobj
      }],
      credits: {
        enabled: false
      }
      

      

 }
 var chart = new Highcharts.Chart(options);

}


