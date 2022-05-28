@extends('layouts.app')
@section('content')
    <div class="container">
        <select class="form-select" id="select-tahun" aria-label="Default select example">
            <option selected disabled>Pilih Tahun</option>
            <option {{ request()->tahun == "2020" ? "selected" : "" }} name="tahun" value="2020">2020</option>
            <option {{ request()->tahun == "2021" ? "selected" : "" }} name="tahun" value="2021">2021</option>
            <option {{ request()->tahun == "2022" ? "selected" : "" }} name="tahun" value="2022">2022</option>
        </select>

        <canvas id="myChart" width="400" height="200"></canvas>
        <div class="card bg-primary text-white card-rounded">
            <div class="card-body text-center pt-4">
                <h4 class="card-title">
                    <b><i class="fas fa-check me-1"></i></b>
                    Kelompok 21, <b>{{ auth()->user()->name }}</b>
                </h4>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-success btn-lg mt-2">
                    <i class="fas fa-rocket me-2"></i> Lihat Website
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script>
        var tahun = document.querySelector('#select-tahun');
        tahun.addEventListener('change', (e) => {
            window.location.href = '?tahun=' + tahun.value
            console.log(tahun.value);
        })
    </script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Penjualan',
                    data:['{{$january}}', '{{$february}}', '{{$march}}', '{{$april}}', '{{$may}}', '{{$june}}', '{{$july}}', '{{$august}}', '{{$september}}', '{{$october}}', '{{$november}}', '{{$december}}'],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
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
        </script>
@endsection
