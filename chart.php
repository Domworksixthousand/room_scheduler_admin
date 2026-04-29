   <script>
    var options = {
        chart: {
            type: 'bar', 
            height: 500,
            stacked: true
        },
        series: [
            { name: 'Done', data: <?php echo $js_done; ?>, color: '#1a8754' },
            { name: 'Occupied', data: <?php echo $js_occupied; ?>, color: '#0d6dfc' },
            { name: 'Cancelled', data: <?php echo $js_cancelled; ?>, color: '#dc3545' }
        ],
        xaxis: {
            categories: <?php echo $js_labels; ?>,
            type: 'category' 
        },
        legend: { position: 'top' }
    };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();
    </script>
