@extends('admin.template')
@section('title','Home')
@section('home-active','text-white bg-primary')
@section('contain')
<div class="container">
    <?php

    use App\Models\Pageview;
    use Illuminate\Support\Facades\DB;

    $record = DB::table('pageviews')
        ->select(DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"), DB::raw('SUM(views) as views'))
        ->where("created_at", ">", \Carbon\Carbon::now()->subMonths(6))
        ->groupBy('months')
        ->get()
        ->sortDesc();

    $months = array();
    $views = array();

    foreach (json_decode($record) as $room_name => $room) {
        $months[] = $room->months;
        $views[] = $room->views;
    }

    $record_daily = DB::table('pageviews')
        ->select(DB::raw("DATE_FORMAT(created_at,'%M %D') as daily"), DB::raw('SUM(views) as views'))
        ->where("created_at", ">", \Carbon\Carbon::now()->subMonths(1))
        ->groupBy('daily')
        ->get();
        // ->sortDesc();

    $arr_daily = array();
    $arr_views_daily = array();

    foreach (json_decode($record_daily) as $daily_name => $daily_detail) {
        $arr_daily[] = $daily_detail->daily;
        $arr_views_daily[] = $daily_detail->views;
    }


    $record_yearly = DB::table('pageviews')
        ->select(DB::raw("DATE_FORMAT(created_at,'%Y') as yearly"), DB::raw('SUM(views) as views'))
        // ->where("created_at", ">", \Carbon\Carbon::now()->subMonths(1))
        ->groupBy('yearly')
        ->get()
        ->sortDesc();

    $arr_yearly = array();
    $arr_views_yearly = array();

    foreach (json_decode($record_yearly) as $yearly_name => $yearly_detail) {
        $arr_yearly[] = $yearly_detail->yearly;
        $arr_views_yearly[] = $yearly_detail->views;
    }

    ?>

    @include('admin/component/space_contain')

    <!-- @foreach($months as $roomi)
    {{$roomi}}
    @endforeach;

    @foreach(json_decode($record) as $room_name => $room)
    {{$room_name}}
    {{$room->months}}
    {{$room->views}}
    @endforeach; -->


    <!-- <div class="card mt-2">
        <form class="d-flex p-3" enctype="multipart/form-data" action="/search-schedule" method="post">
            @csrf
            <input class="form-control me-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div> -->

    <div class="container mt-5 mb-5 bg-white rounded-3">
        <div class="btn-group container mb-0 pb-0" role="group" aria-label="Basic example">
            <button class="btn btn-danger" onclick="clickDaily()">Daily</button>
            <button type="button" class="btn btn-warning" onclick="clickMonthly()">Monthly</button>
            <button type="button" class="btn btn-success" onclick="clickYearly()">Yearly</button>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <div id="parent"></div>
        <canvas id="myChart" class=""></canvas>
        <script>
            var data_month = <?php echo
                                json_encode($months);
                                // json_encode($arr_daily);
                                ?>;
            var data_viewer = <?php echo
                                json_encode($views);
                                // json_encode($arr_views_daily);
                                ?>;

            const ctx = document.getElementById('myChart').getContext('2d');
            var myChart = null;

            function start() {
                myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data_month,
                        datasets: [{
                            label: 'Viewers',
                            data: data_viewer,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',

                              
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',

                               
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

        
            function clickDaily() {
                // alert("Hello! I am an alert box!");
                myChart.destroy();
                data_month = <?php echo
                                // json_encode($months);
                                json_encode($arr_daily);
                                ?>;
                data_viewer = <?php echo
                                // json_encode($views);
                                json_encode($arr_views_daily);
                                ?>;
                start();
            }

            function clickMonthly() {
                // alert("Hello! I am an alert box!");
                myChart.destroy();
                data_month = <?php echo
                                json_encode($months);
                                // json_encode($arr_daily);
                                ?>;
                data_viewer = <?php echo
                                json_encode($views);
                                // json_encode($arr_views_daily);
                                ?>;
                start();
            }

            function clickYearly() {
                // alert("Hello! I am an alert box!");
                myChart.destroy();
                data_month = <?php echo
                                json_encode($arr_yearly);
                                ?>;
                data_viewer = <?php echo
                                json_encode($arr_views_yearly);
                                ?>;
                start();
            }


            start();
        </script>

    </div>

</div>
@endsection