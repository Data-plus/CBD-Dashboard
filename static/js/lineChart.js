const CHART = document.getElementById("lineChart");
console.log(CHART);
let lineChart = new Chart(CHART, {
    type: 'line',
    data: {
        labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
        datasets: [
            {   data: [],
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
                lineTension: 0.4,
            },
            {    
                data: [],
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