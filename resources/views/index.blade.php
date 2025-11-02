@extends('layouts.app', ['title' => 'Dashboard'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @include('layouts.navtitle', ['navtitle' => 'Dashboard'])
        @if($checker->routePermission('dashboard'))
            <div class="mcontent dashboard">
                <div class="cont">
                    <div class="box shadow-sm rounded mb-3">
                        <div class="count cnt1 shadow-sm rounded">
                            <h1 id="cnt1"></h1>
                            <label>Active Resident</label>
                        </div>
                        <div class="count cnt2 rounded">
                            <h1 id="cnt2"></h1>
                            <label>Overdue Payments</label>
                        </div>
                        <div class="count cnt3 rounded">
                            <h1 id="cnt3"></h1>
                            <label>Open Complaints</label>
                        </div>
                        <div class="count cnt4 rounded">
                            <h1 id="cnt4"></h1>
                            <label>Open Requests</label>
                        </div>
                        <div class="count cnt5 rounded">
                            <h1 id="cnt5"></h1>
                            <label>Visitors Today</label>
                        </div>
                    </div>
                    <div class="box shadow-sm rounded mb-3" id="billing">
                    </div>
                    <div class="d-md-flex gap-3">
                        <div class="box shadow-sm rounded mb-3" id="complaints">
                        </div>
                        <div class="box shadow-sm rounded mb-3" id="visitors">
                        </div>
                    </div>
                    <div class="d-md-flex gap-3">
                        <div class="box shadow-sm rounded mb-3" id="predict_visitors">
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mcontent">
                <div class="no-access">You don't have access to this feature!</div>
            </div>
        @endif
    </div>
</div>
<script>
    $(function(){
        getDashboard();
        getPredictVisitors();
    });

    function getDashboard(){
        $.get("{{ route('dashboard.count') }}").done(function(data){
            console.log(data)
            $("#cnt1").text(data.top.cnt1);
            $("#cnt2").text(data.top.cnt2);
            $("#cnt3").text(data.top.cnt3);
            $("#cnt4").text(data.top.cnt4);
            $("#cnt5").text(data.top.cnt5);

            billingChart(data.billing);
            complaintsChart(data.complaints);
            visitorsChart(data.visitors);
        });
    }

    function billingChart(data){
        Highcharts.chart('billing', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Monthly Billing Overview (Last 6 Months)',
                style: {
                    fontSize: '16px',
                    fontWeight: '800'
                }
            },
            xAxis: {
                categories: data.cat,
                crosshair: true,
                gridLineWidth: 1,
                gridLineColor: '#cccccc',
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: '800'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount (₱)',
                    style: {
                        fontSize: '12px',
                        fontWeight: '800'
                    }
                },
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                },
                gridLineWidth: 1,
                gridLineColor: '#cccccc'
            },
            tooltip: {
                shared: true,
                valuePrefix: '₱',
                style: {
                    fontSize: '12px'
                }
            },
            legend: {
                itemStyle: {
                    fontSize: '12px',
                    fontWeight: '800'
                }
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true
                    },

                }
            },
            series: [{
                name: 'Total Bill',
                data: data.totals,
                color: '#007bff'
            }, {
                name: 'Paid',
                data: data.payments,
                color: '#28a745'
            }, {
                name: 'Unpaid',
                data: data.unpaids,
                color: '#dc3545'
            }]
        });
    }

    function complaintsChart(data){
        Highcharts.chart('complaints', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Complaint Categories',
                style: {
                    fontSize: '16px',
                    fontWeight: '800'
                }
            },
            xAxis: {
                categories: data.types,
                title: {
                    text: null
                },
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: '800'
                    }
                },
                gridLineWidth: 1,
                gridLineColor: '#cccccc'
            },
            yAxis: {
                min: 0,
                title: null,
                labels: {
                    overflow: 'justify',
                    style: {
                        fontSize: '12px',
                        fontWeight: '800'
                    }
                },
                gridLineWidth: 1,
                gridLineColor: '#cccccc'
            },
            tooltip: {
                style: {
                    fontSize: '12px'
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                name: 'Complaints',
                data: data.values
            }]
        });
    }

    function visitorsChart(data){
        Highcharts.chart('visitors', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Number of Visitors by Hour Today',
                style: {
                    fontSize: '16px',
                    fontWeight: '800'
                }
            },
            xAxis: {
                categories: data.hours,
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: '800'
                    }
                },
                gridLineWidth: 1,
                gridLineColor: '#cccccc'
            },
            yAxis: {
                title: {
                    text: 'Visitor Count'
                },
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: '800'
                    }
                },
                allowDecimals: false,
                gridLineWidth: 1,
                gridLineColor: '#cccccc'
            },
            tooltip: {
                style: {
                    fontSize: '12px'
                }
            },
            series: [{
                name: 'Count',
                data: data.counts
            }]
        });
    }

    function getPredictVisitors(){
        $.get("{{ route('dashboard.predict_visitors') }}").done(function(data){
            console.log(data);

            var dates = [];
            var actualVisitors = [];
            var predictedVisitors = [];

            $.each(data, function(key, value) {
                dates.push(value.date);
                actualVisitors.push(value.actual_visitors);
                predictedVisitors.push(value.predicted_visitors);
            });

            // Create the chart
            Highcharts.chart('predict_visitors', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Actual vs Predicted Visitors'
                },
                xAxis: {
                    categories: dates,
                    title: {
                        text: 'Date'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Number of Visitors'
                    },
                    allowDecimals: false
                },
                series: [{
                    name: 'Actual Visitors',
                    data: actualVisitors
                }, {
                    name: 'Predicted Visitors',
                    data: predictedVisitors
                }],
                tooltip: {
                    shared: true,
                    crosshairs: true
                },
                credits: {
                    enabled: false
                }
            });
        });
    }
</script>
@endsection