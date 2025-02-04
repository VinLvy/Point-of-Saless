@extends('layouts.admin')

@section('content')
    <h1>Selamat Datang di Dashboard Admin</h1>
    
    <p>Grafik Pendapatan Mingguan:</p>

    <div style="max-width: 800px; margin: 0 auto;">
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
                    backgroundColor: 'rgba(54, 162, 235, 0.1)', // Warna background lebih transparan
                    borderColor: 'rgba(54, 162, 235, 1)', // Warna garis
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Warna titik
                    pointBorderColor: '#fff', // Warna border titik
                    pointHoverBackgroundColor: '#fff', // Warna titik saat dihover
                    pointHoverBorderColor: 'rgba(54, 162, 235, 1)', // Warna border titik saat dihover
                    fill: true // Mengisi area bawah garis
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#333', // Warna teks legend
                            font: {
                                size: 14 // Ukuran font legend
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)', // Warna background tooltip
                        titleColor: '#fff', // Warna judul tooltip
                        bodyColor: '#fff', // Warna teks tooltip
                        borderColor: 'rgba(255, 255, 255, 0.1)', // Warna border tooltip
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false // Sembunyikan warna di tooltip
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false // Sembunyikan grid lines pada sumbu X
                        },
                        ticks: {
                            color: '#666', // Warna teks sumbu X
                            font: {
                                size: 12 // Ukuran font sumbu X
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)', // Warna grid lines sumbu Y
                            borderDash: [5, 5] // Garis putus-putus
                        },
                        ticks: {
                            color: '#666', // Warna teks sumbu Y
                            font: {
                                size: 12 // Ukuran font sumbu Y
                            },
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString(); // Format angka dengan separator
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000, // Durasi animasi
                    easing: 'easeInOutQuart' // Efek animasi
                }
            }
        });
    </script>
@endsection