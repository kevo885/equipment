var options = {
    chart: {
        height: 320,
        type: 'pie',
    }, 
    <?php echo "series: [$gender[0],$gender[1],$gender[2]],"?>
    labels: ['Male','Female','Not specify'],
    legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center',
        verticalAlign: 'middle',
        floating: false,
        fontSize: '14px',
        offsetX: 0,
        offsetY: 7
    },
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
                height: 240
            },
            legend: {
                show: false
            },
        }
    }]

}

var chart = new ApexCharts(
    document.querySelector("#gender-pie-user"),
    options
);

chart.render();
