/* CHANGE THIS TO A FOR LOOP TO FETCH DATA LATER */

/* RADAR CHART SCRIPT */

const CHART2 = document.getElementById("radarChart");
console.log(CHART2);
let radarChart = new Chart(CHART2, {
    type: 'radar',
    data: {
        labels: ["Entertainment", "Foot Traffic", "Transportation", "Accessibility"],
        datasets: [
            {
                label: 'Rating of this gallery out of 5',
                backgroundColor: "rgba(220,226,234,0.8)",
                borderColor: "rgba(195,205,219,1)",
                borderWidth: 3,
                pointHoverBorderWidth: 8,
                pointHoverBackgroundColor: "rgba(255,255,255,0)",
                data: [2,4,3,5],
            }
        ]
    },
    options: {
        responsive: false,
        maintainAspectRatio: this.maintainAspectRatio,
        title: {
            display: true,
            text: "Location evaluation ratings",
            fontSize: 16
        },
        label: {
            fontColor: "rgba(75,192,192,1)",
        },
        legend: {
            position: 'bottom',
            onClick: false,
            labels: {
                padding: 20,
            }
        },
        scale: {
            ticks: {
                beginAtZero: true,
                max: 5,
                maxTicksLimit: 5,
                display: true,
                backdropColor: 'transparent',
            },
            gridLines: {
                circular: true, /* THIS DOES NOT WORK FML........ */
                lineWidth: 0.6,
            },
            labels: {
                padding: 20,
            },
            pointLabels :{
                fontSize: 12,
            }
        }
    }
});


