@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    {{-- menampilkan alert --}}
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#8c7cf0'
            });
        });
    </script>
    @endif

    <div class="bg-white p-6 rounded-xl shadow border mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            Selamat Datang, {{ ucfirst($user->role) }}!
        </h2>
    </div>

    {{-- Konten Berdasarkan Role --}}
    @if ($user->role === 'petugas')
        {{-- Konten untuk petugas --}}
        <div class="bg-white p-6 rounded-xl shadow border">
            <div class="bg-gray-100 rounded-lg overflow-hidden">
                <div class="text-center bg-gray-100 py-3 font-semibold text-gray-600">
                    Total Penjualan Hari Ini
                </div>
                <div class="py-8 text-center">
                    <p class="text-4xl font-bold text-gray-800">{{ $totalPenjualan }}</p>
                    <p class="mt-2 text-sm text-gray-500">Jumlah total penjualan yang terjadi hari ini.</p>
                </div>
                <div class="text-center text-xs text-gray-400 bg-gray-100 py-2">
                    Terakhir diperbarui: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y H:i') }}                </div>
            </div>
        </div>

    @elseif ($user->role === 'admin')
        {{-- Konten untuk admin --}}
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Pie Chart -->
            <div class="bg-white p-6 rounded-xl shadow border md:w-1/2">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Persentase Penjualan Produk</h3>
                <div class="w-64 h-64 mx-auto">
                    <canvas id="overallPieChart"></canvas>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="bg-white p-6 rounded-xl shadow border md:w-1/2 overflow-x-auto">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Grafik Penjualan Produk</h3>
                <div class="w-[1000px]">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
                @if(empty($chartData))
                    <p class="text-sm text-gray-500 mt-2">Belum ada data untuk ditampilkan dalam grafik.</p>
                @endif
            </div>
        </div>

        <!-- Stock Chart -->
        <div class="bg-white p-6 rounded-xl shadow border mt-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Stok Produk Saat Ini</h3>
            <div class="w-full overflow-x-auto">
                <!-- Menyesuaikan ukuran canvas agar lebih kecil -->
                <canvas id="stockChart" width="100" height="50"></canvas>
            </div>
            @if(empty($totalStokProduk))
                <p class="text-sm text-gray-500 mt-2">Belum ada data stok produk untuk ditampilkan.</p>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pie Chart
        const produkLabels = @json(array_keys($totalPerProduk));
        const produkTotals = @json(array_values($totalPerProduk));
        const pieColors = [
            '#f87171', '#60a5fa', '#fbbf24', '#34d399', '#c084fc',
            '#f97316', '#ec4899', '#22d3ee', '#818cf8', '#fde68a',
            '#86efac', '#fca5a5'
        ];

        const ctxPie = document.getElementById('overallPieChart')?.getContext('2d');
        if (ctxPie && produkLabels.length > 0) {
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: produkLabels,
                    datasets: [{
                        data: produkTotals,
                        backgroundColor: pieColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(2) + '%';
                                    return `${label}: ${value} (${percentage})`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Bar Chart
        const rawData = @json($chartData);
        if (rawData.length > 0) {
            const labels = rawData.map(item => item.tanggal);
            const totalPenjualan = rawData.map(item => {
                return Object.entries(item)
                    .filter(([key]) => key !== 'tanggal')
                    .reduce((sum, [_, val]) => sum + (val ?? 0), 0);
            });

            const ctx = document.getElementById('salesChart')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Penjualan',
                            data: totalPenjualan,
                            backgroundColor: 'rgba(59, 130, 246, 0.3)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: { mode: 'index', intersect: false },
                        },
                        scales: {
                            x: {
                                ticks: { maxRotation: 60, minRotation: 45 }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                     precision: 0,
                                     stepSize: 2
                                    }
                            }
                        }
                    }
                });
            }
        }

        // Stok Produk Chart
    const stokData = @json($totalStokProduk);
    if (stokData.length > 0) {
        const stokLabels = stokData.map(item => item.nama_produk);
        const stokValues = stokData.map(item => item.stok);

        const ctxStok = document.getElementById('stockChart')?.getContext('2d');
        if (ctxStok) {
            new Chart(ctxStok, {
                type: 'bar',
                data: {
                    labels: stokLabels,
                    datasets: [{
                        label: 'Stok Produk',
                        data: stokValues,
                        backgroundColor: 'rgba(34,197,94,0.3)',
                        borderColor: 'rgba(34,197,94,1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Stok: ${context.parsed.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                stepSize: 2, // Set step size to 5
                            }
                        }
                    }
                }
            });
        }
    }
    });
</script>
@endpush
