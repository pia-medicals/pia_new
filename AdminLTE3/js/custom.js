    function chartthediv(div = 'mychart', labels, jsondataset, type,yaxis,xaxis){
    var ctx = document.getElementById(div);
    var lastsixmonth = new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: jsondataset
        },

        options: {           
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: xaxis
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: yaxis
                    },
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
    }

    function chartthediv_pie(div = 'mychart', labels, jsondataset, type,yaxis,xaxis='Month'){
    var ctx = document.getElementById(div);
    var lastsixmonth = new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: jsondataset
        },

        options: {           
            scales: {
                xAxes: [{
                    display: false,
                    scaleLabel: {
                        display: false,
                        labelString: xaxis
                    }
                }],
                yAxes: [{
                    display: false,
                    scaleLabel: {
                        display: false,
                        labelString: yaxis
                    },
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
    }

