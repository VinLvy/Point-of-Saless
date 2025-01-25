@extends('layouts.admin')

@section('content')
    <h1>Selamat Datang di Dashboard Admin</h1>
    
    <p>Grafik Pendapatan Mingguan:</p>

    <div style="max-width: 600px; margin: 0 auto;">
        <canvas id="incomeChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('incomeChart').getContext('2d');
        var incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels ?? []) !!},
                datasets: [{
                    label: 'Total Pendapatan',
                    data: {!! json_encode($data ?? []) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection

