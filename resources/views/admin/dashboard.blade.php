@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{$users}}</h3>
                                <p>Total Users</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{$openRequest}}</h3>
                                <p>Total Open Request</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-universal-access"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <a href="{{route('openIdentify')}}">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{$openIdentify}}</h3>
                                    <p>Total Open Identification</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file-contract"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-6">
                        <a href="{{route('feedback.index')}}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{$feedback}}</h3>
                                    <p>Total Feedbacks</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-comment"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <section class="col-lg-12 connectedSortable">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    New Users Of This Week
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="new-user-chart"
                                         style="position: relative; height: 300px;">
                                        <canvas id="new-user-chart-canvas" height="300" style="height: 300px;"></canvas>
                                    </div>
                                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                                        <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Users sorted by usage level
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="user-usage-level-chart"
                                         style="position: relative; height: 300px;">
                                        <canvas id="user-usage-level-chart-canvas" height="300" style="height: 300px;"></canvas>
                                    </div>
                                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                                        <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Open Request Of This Week
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="open-request-chart"
                                         style="position: relative; height: 300px;">
                                        <canvas id="open-request-chart-canvas" height="300" style="height: 300px;"></canvas>
                                    </div>
                                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                                        <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('jquery')
    <script>
        $(function() {
            /*---------------------NEW USER CHART START---------------------*/
            var newUserChartCanvas = document.getElementById('new-user-chart-canvas').getContext('2d')
            var newUserChartData = {
                labels: <?=$lastDayLabels?>,
                datasets: [
                    <?php
                        $colors = ['babysitter' => 'pink', 'both' => 'blue', 'parent' => 'green', null => 'orange'];
                        foreach ($newUserArray as $profile_type => $data): ?>
                    {
                        label: '<?=(!$profile_type) ? "undefined" : $profile_type?>',
                        backgroundColor: '<?=$colors[$profile_type]?>',
                        borderColor: '<?=$colors[$profile_type]?>',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [<?php echo implode(", ", $data); ?>]
                    },
                    <?php endforeach ?>
                ]
            }
            var newUserChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            precision: 0, // Set precision to 0 to display only integers
                            beginAtZero: true // Start the y-axis at zero
                        }
                    }]
                }
            }
            var newUserChart = new Chart(newUserChartCanvas, {
                type: 'bar',
                data: newUserChartData,
                options: newUserChartOptions
            })
            /*---------------------NEW USER CHART END---------------------*/

            /*---------------------NEW USER LEVEL START---------------------*/
            var ctx = document.getElementById('user-usage-level-chart-canvas').getContext('2d');
            var userLevelChartData = {
                labels: <?=$usageLevelLabels?>,
                datasets: [{
                    label: 'User Levels',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    data: <?php echo $usageLevelData; ?>
                }]
            };
            var userLevelChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
            };

            new Chart(ctx, {
                type: 'bar',
                data: userLevelChartData,
                options: userLevelChartOptions
            });
            /*---------------------NEW USER LEVEL END---------------------*/

            /*---------------------OPEN REQUEST CHART START---------------------*/
            var openRequestChartCanvas = document.getElementById('open-request-chart-canvas').getContext('2d')
            var openRequestChartData = {
                labels: <?=$lastDayLabels?>,
                datasets: [{
                    label: 'Digital Goods',
                    backgroundColor: 'rgba(52,161,64,0.9)',
                    borderColor: 'rgba(52,161,64,0.9)',
                    pointRadius: false,
                    pointColor: 'rgba(52,161,64,1)',
                    pointStrokeColor: 'rgba(52,161,64,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(52,161,64,1)',
                    data: [<?php echo implode(", ", $newRequestArray); ?>]
                }]
            }
            var openRequestChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            precision: 0, // Set precision to 0 to display only integers
                            beginAtZero: true // Start the y-axis at zero
                        }
                    }]
                }
            }
            var openRequestChart = new Chart(openRequestChartCanvas, {
                type: 'line',
                data: openRequestChartData,
                options: openRequestChartOptions
            })
            /*---------------------OPEN REQUEST CHART END---------------------*/
        })
    </script>
@endsection
