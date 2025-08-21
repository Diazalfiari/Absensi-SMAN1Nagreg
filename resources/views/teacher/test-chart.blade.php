<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Chart Visibility</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        canvas {
            background: white !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Test Chart Visibility</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5>Line Chart Test</h5>
                    <canvas id="testLineChart" height="200"></canvas>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="chart-container">
                    <h5>Doughnut Chart Test</h5>
                    <canvas id="testDoughnutChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6>Test Status:</h6>
                    <ul class="mb-0">
                        <li>Line Chart: Should show blue line with visible points</li>
                        <li>Doughnut Chart: Should show colored segments with legend</li>
                        <li>Both charts should have white backgrounds and visible elements</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Test Line Chart
        const lineCtx = document.getElementById('testLineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Attendance Rate (%)',
                    data: [85, 88, 82, 90],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.3)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#333'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: '#333'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#333'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // Test Doughnut Chart
        const doughnutCtx = document.getElementById('testDoughnutChart').getContext('2d');
        const doughnutChart = new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
                datasets: [{
                    data: [75, 5, 8, 12],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                    borderColor: ['#fff', '#fff', '#fff', '#fff'],
                    borderWidth: 2,
                    hoverBackgroundColor: ['#218838', '#e0a800', '#138496', '#c82333'],
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            color: '#333',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
