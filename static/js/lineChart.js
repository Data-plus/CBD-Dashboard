/* CHANGE THIS TO A FOR LOOP TO FETCH DATA LATER */
Chart.defaults.global.responsive = false;

/* LINE CHART SCRIPT */

const CHART = document.getElementById("lineChart");
console.log(CHART);
let lineChart = new Chart(CHART, {
    type: 'line',
    data: {
        labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
        datasets: [
            {
                label: "Past 6 Months",
                fill: false,
                backgroundColor: "rgb(82,105,136,0.8)",
                borderColor: "rgb(82,105,136)",
                borderCapStyle: "butt",
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: 'white',
                pointBackgroundColor: "#fff",
                pointBorderWidth: 2,
                pointHoverRadius: 10,
                pointHoverBackgroundColor: "rgb(82,105,136,0.8)",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 8,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [70, 55, 33, 120, 50, 80, 70],
                lineTension: 0.4,
            },
            {
                label: "Next 3 Months",
                fill: false,
                backgroundColor: "rgb(194,182,208,0.5)",
                borderColor: "rgb(255,170,170)",
                borderCapStyle: "butt",
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "white",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 2,
                pointHoverRadius: 10,
                pointHoverBackgroundColor: "rgb(194,182,208,0.5)",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 8,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [70, 65, 55, 70, 60, 90, 100],
                lineTension: 0.4,
            }
        ]
    },
    options: {
        responsive: false,
        maintainAspectRatio: this.maintainAspectRatio,
        title: {
            display: true,
            text: "Number of Pedestrian Count",
            fontSize: 16,
        },
        legend: {
            position: 'bottom',

            labels: {
                padding: 20,
            }
        }
    }

});









// 
// {/* <script>

// /* LINE CHART SCRIPT */
// var lineChart;
// var getLineData = $.get('/data');

// getLineData.done(function(results){
//   var type = 'line';
//   var datas = {
//     labels: ['Sun', 'Mon', 'Tue', ' Wed', ' Thu', ' Fri', 'Sat'],
//     datasets :[
//       {
//         label: "Past 6 Months",
//         fill: false,
//         backgroundColor: "rgb(82,105,136,0.8)",
//         borderColor: "rgb(82,105,136)",
//         borderCapStyle: "butt",
//         borderDash: [],
//         borderDashOffset: 0.0,
//         borderJoinStyle: 'miter',
//         pointBorderColor: 'white',
//         pointBackgroundColor: "#fff",
//         pointBorderWidth: 2,
//         pointHoverRadius: 10,
//         pointHoverBackgroundColor: "rgb(82,105,136,0.8)",
//         pointHoverBorderColor: "rgba(220,220,220,1)",
//         pointHoverBorderWidth: 8,
//         pointRadius: 1,
//         pointHitRadius: 10,
//         lineTension: 0.4,
//         data: [results.results]
//       }
//     ]
//   };
//   var options = {
//         responsive: false,
//         maintainAspectRatio: this.maintainAspectRatio,
//         title: {
//             display: true,
//             text: "Number of Pedestrian Count",
//             fontSize: 16,
//         },
//         legend: {
//             position: 'bottom',

//             labels: {
//                 padding: 20,
//             }
//         }
//   }
//   lineChart = new Chart("lineChart", type, datas, options)
// });

// function updateLineChart(){
// var updateLineData = $.get('/data');

// updateLineData.done(function(results){
//   var datas = {
//     labels: ['Sun', 'Mon', 'Tue', ' Wed', ' Thu', ' Fri', 'Sat'],
//     datasets :[
//       {
//         label: "Past 6 Months",
//         fill: false,
//         backgroundColor: "rgb(82,105,136,0.8)",
//         borderColor: "rgb(82,105,136)",
//         borderCapStyle: "butt",
//         borderDash: [],
//         borderDashOffset: 0.0,
//         borderJoinStyle: 'miter',
//         pointBorderColor: 'white',
//         pointBackgroundColor: "#fff",
//         pointBorderWidth: 2,
//         pointHoverRadius: 10,
//         pointHoverBackgroundColor: "rgb(82,105,136,0.8)",
//         pointHoverBorderColor: "rgba(220,220,220,1)",
//         pointHoverBorderWidth: 8,
//         pointRadius: 1,
//         pointHitRadius: 10,
//         lineTension: 0.4,
//         data: [results.results]
//       }
//     ]
//   };
//   lineChart.update(datas);
//   })
// };

// $("#map_area").on('click', updateLineChart);

// </script> */}