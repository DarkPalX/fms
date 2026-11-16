@extends('layouts.app') @section('pagecss')


<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}"
/>
@endsection @section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie"></i>
                            Fuel Issuances Report
                        </h3>
                        <br /><br />
                        <form class="form-inline">
                            <div class="form-group col-lg-4">
                                <label for="report_basis" class="col-lg-4"> Report Basis</label>
                                <select name="report_basis" id="report_basis" class="form-control col-lg-7">
                                    <option value="date" {{ request()->report_basis == 'date' ? 'selected' : '' }}></option>
                                    <option value="week" {{ request()->report_basis == 'week' ? 'selected' : '' }}>Weekly</option>
                                    <option value="month" {{ request()->report_basis == 'month' ? 'selected' : '' }}>Monthly</option>
                                    <option value="text" {{ request()->report_basis == 'text' ? 'selected' : '' }}>Semi-annual</option>
                                    <option value="number" {{ request()->report_basis == 'number' ? 'selected' : '' }}>Annual</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-3 ordinary_forms">
                                <label for="date_from" class="col-lg-3">From</label>
                                <input type="{{ request()->has('report_basis') ? request()->report_basis : 'date' }}" value="{{ request()->has('date_from') ? request()->date_from : '' }}" name="date_from" class="form-control col-lg-9 date_select">
                            </div>
                            <div class="form-group col-lg-3 ordinary_forms">
                                <label for="date_from" class="col-lg-3">To</label>
                                <input type="{{ request()->has('report_basis') ? request()->report_basis : 'date' }}" value="{{ request()->has('date_to') ? request()->date_to : '' }}" name="date_to" class="form-control col-lg-9 date_select">
                            </div>

                            <div class="form-group col-lg-3 semi_annual_forms" style="display: none;">
                                <label for="date_from" class="col-lg-3">Months</label>
                                <select name="semi_annual" class="form-control col-lg-9">
                                    <option value="['01', '02', '03', '04', '05', '06']" {{ request()->semi_annual == "['01', '02', '03', '04', '05', '06']" ? 'selected' : '' }}>January to June</option>
                                    <option value="['07', '08', '09', '10', '11', '12']" {{ request()->semi_annual == "['07', '08', '09', '10', '11', '12']" ? 'selected' : '' }}>July to December</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 semi_annual_forms" style="display: none;">
                                <label for="date_from" class="col-lg-3">Year</label>
                                <input type="number" value="{{ request()->has('semi_year') ? request()->semi_year : '' }}" placeholder="YYYY" name="semi_year" class="form-control col-lg-9">
                            </div>

                            <button class="btn btn-success"> Plot </button>&nbsp;&nbsp;
                            <a href="{{ route('report.fuel-issuance-chart') }}" class="btn btn-info">Reset</a>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="fuel_issuances_timeline"></div>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-md-7">
                                <div id="fuel_issuances"></div>
                            </div>
                            <div class="col-md-5">
                                <div id="req_act_chart"></div>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="dept_issuances"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection @section('pagejs')

@php
    $report_basis = "";
    if(request()->has('report_basis')){
        $report_basis = request()->report_basis;
    }

@endphp

<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('assets/js/highcharts/exporting.js') }}"></script>
<script src="{{ asset('assets/js/highcharts/export-data.js') }}"></script>
<script>
    var count = {{ $issued }};
    var report_basis = "{{ $report_basis }}";

    $(document).ready(function() {
        if (['week', 'month', 'number', ''].includes(report_basis)) {
            $(".semi_annual_forms").hide();
            $(".ordinary_forms").show();
        } else {
            $(".semi_annual_forms").show();
            $(".ordinary_forms").hide();
        }

        $('#report_basis').change(function() {
            var selectedOption = $(this).val();
            var dateInput = $('.date_select');

            if(selectedOption === 'week' || selectedOption === 'month' || selectedOption === 'number' || selectedOption === ''){
                $(".semi_annual_forms").hide();
                $(".ordinary_forms").show();
            }

            if (selectedOption === 'week') {
                dateInput.attr('type', 'week');
            }else if (selectedOption === 'month') {
                dateInput.attr('type', 'month');
            }else if (selectedOption === 'number') {
                dateInput.attr('type', 'number');
                dateInput.attr('min', 2010);
                dateInput.attr('max', 2100);
                dateInput.attr('placeholder', 'YYYY');
                dateInput.on('input', function() {
                    var year = $(this).val();
                    if (year.length > 4) {
                        $(this).val("");
                    }
                });
            }else if (selectedOption === 'text'){
                $(".semi_annual_forms").show();
                $(".ordinary_forms").hide();
            }else{
                dateInput.attr('type', 'date');
            }
        });
    });

    Highcharts.setOptions({
        colors: Highcharts.map(Highcharts.getOptions().colors, function(color) {
            return {
                radialGradient: {
                    cx: 0.5,
                    cy: 0.3,
                    r: 0.7
                },
                stops: [
                    [0, color],
                    [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                ]
            };
        })
    });

    Highcharts.chart('fuel_issuances_timeline', {
        chart: {
            height: 500,
            plotBorderWidth: 1,
            plotShadow: true,
            zoomType: 'xy'
        },
        title: {
            text: $("#report_basis option:selected").text()+' Fuel Issuances Report'
        },
        xAxis: [{
            categories: <?php echo json_encode($arrayTransaction); ?>,
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Number of Requests',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255,255,255,0.25)'
        },
        series: [ 
            {
                name: 'ISSUED',
                data: <?php echo json_encode($arrayIssued); ?>,
                tooltip: {
                    valueSuffix: ' Requests'
                }
            },
            {
                name: 'PENDING',
                data: <?php echo json_encode($arrayPending); ?>,
                tooltip: {
                    valueSuffix: ' Requests'
                }
            },
            {
                name: 'CANCELLED',
                data: <?php echo json_encode($arrayCancelled); ?>,
                tooltip: {
                    valueSuffix: ' Requests'
                }
            }
        ]
    });


    // Build the pie chart for fuel issuances
    Highcharts.chart('fuel_issuances', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,
            plotShadow: true,
            type: 'pie'
        },
        title: {
            text: 'Fuel Requests Issued: <b>' + count.toLocaleString() + '</b>',
            align: 'left'
        },

        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    connectorColor: 'silver'
                }
            }
        },
        series: [{
            name: 'Percentage',
            data: <?php echo $pieData ?>
        }]
    });

    // Build the column chart for actual liter consumed
    /*
    Highcharts.chart('liters_consumed', {
        chart: {
            height: 500,
            plotBorderWidth: 1,
            plotShadow: true,
            type: 'column'
        },
        title: {
            text: 'Actual Liters consumed per Fuel',
            align: 'left'
        },
        xAxis: {
            type: 'category',
            labels: {
            rotation: -45,
            style: {
                fontSize: '9px',
                fontFamily: 'Verdana, sans-serif'
            }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number of Liters'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.1f} Liters</b>'
        },
        series: [{
            name: 'Population',
            colors: [
            '#9b20d9', '#9215ac', '#861ec9', '#7a17e6', '#7010f9', '#691af3',
            '#6225ed', '#5b30e7', '#533be1', '#4c46db', '#4551d5', '#3e5ccf',
            '#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',
            '#03c69b',  '#00f194'
            ],
            colorByPoint: true,
            groupPadding: 0,
            data: <?php echo $columnData; ?>,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
    
    */

    //request and actual chart
    Highcharts.chart("req_act_chart", {
        chart: {
            height: 500,
            plotBorderWidth: 1,
            plotShadow: true
        },

        title: {
            text: "Requested and Actual Liters consumed per Year",
            align: "left",
        },

        yAxis: {
            title: {
                text: "Number of Liters",
            },
        },

        xAxis: {
            accessibility: {
                
            },
        },

        legend: {
            layout: "vertical",
            align: "right",
            verticalAlign: "middle",
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false,
                },
                pointStart: <?php  echo $years[0]; ?>,
            },
        },

        series: [
            {
                name: "Requested Liters",
                type: 'spline',
                data: <?php echo json_encode($requested); ?>,
            },
            {
                name: "Actual Liters",
                type: 'spline',
                data: <?php echo json_encode($actual); ?>,
            }
        ],

        responsive: {
            rules: [
                {
                    condition: {
                        maxWidth: 500,
                    },
                    chartOptions: {
                        legend: {
                            layout: "horizontal",
                            align: "center",
                            verticalAlign: "bottom",
                        },
                    },
                },
            ],
        },
    });

    //fuel issuances department
    Highcharts.chart('dept_issuances', {
        chart: {
            height: 1250,
            plotBorderWidth: 1,
            plotShadow: true,
            type: 'bar'
        },
        title: {
            text: 'Fuel Requests per Department',
            align: 'left'
        },
        xAxis: {
            categories: <?php echo json_encode($depts); ?>,
            title: {
            text: null
            },
            gridLineWidth: 1,
            lineWidth: 0
        },
        yAxis: {
            min: 0,
            title: {
            text: 'Number of Requests',
            align: 'high'
            },
            labels: {
            overflow: 'justify'
            },
            gridLineWidth: 0
        },
        tooltip: {
            valueSuffix: ' requests'
        },
        plotOptions: {
            bar: {
            borderRadius: '0.5%',
            dataLabels: {
                enabled: true
            },
            groupPadding: 0.1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'ISSUED',
            data: <?php echo json_encode($deptissued); ?>
        }]
    });


</script>

@endsection
