<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Monitoring Padi Rendah Karbon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-gray-100">
<x-sidebar />


<!-- Main Content -->
<div class="p-4 pt-10 sm:ml-64">

    @php
        $latest = $data->first(); // ambil data sensor terbaru
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
        <!-- Kelembapan Tanah -->
        <div class="bg-white p-6 rounded-xl text-left border border-gray-300">
            <h2 class="text-md font-semibold text-gray-500">Soil Moisture</h2>
            <p class="text-4xl font-bold text-black mt-2" id="kelembapan_tanah">{{ $latest->kelembapan_tanah }}%</p>
            <p class="text-xs text-gray-500 mt-2">Terakhir: {{ $latest->created_at }}</p>
        </div>

        <!-- Suhu -->
        <div class="bg-white p-6 rounded-xl text-left border border-gray-300">
            <h2 class="text-md font-semibold text-gray-500">Temperature</h2>
            <p class="text-4xl font-bold text-black mt-2" id="suhu">{{ $latest->suhu }}°C</p>
            <p class="text-xs text-gray-500 mt-2">Terakhir: {{ $latest->created_at }}</p>
        </div>

        <!-- Gas Karbon -->
        <div class="bg-white p-6 rounded-xl text-left border border-gray-300">
            <h2 class="text-md font-semibold text-gray-500">Gas Karbon (MQ135)</h2>
            <p class="text-4xl font-bold text-black mt-2" id="gas_karbon">{{ $latest->gas_karbon}} ppm</p>
            <p class="text-xs text-gray-500 mt-2">Terakhir: {{ $latest->created_at }}</p>
        </div>

        <!-- Gas Metana -->
        <div class="bg-white p-6 rounded-xl text-left border border-gray-300">
            <h2 class="text-md font-semibold text-gray-500">Gas Metana (MQ4)</h2>
            <p class="text-4xl font-bold text-black mt-2" id="gas_metana">{{ $latest->gas_metana}} ppm</p>
            <p class="text-xs text-gray-500 mt-2">Terakhir: {{ $latest->created_at }}</p>
        </div>
    </div>
    <div class="pt-4 mb-4">
    <label for="filter" class="block mb-2 text-sm font-medium text-gray-700">Grafik</label>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Kelembapan -->
    <div class="bg-white rounded-lg border border-gray-300 p-4">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold">Kelembapan Tanah</h2>
            <select class="filter-chart border rounded p-1" data-chart="kelembapan">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
            </select>
        </div>
        <canvas id="chartKelembapan" class="w-full h-72"></canvas>
    </div>

    <!-- Suhu -->
    <div class="bg-white rounded-lg border border-gray-300 p-4">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold">Suhu</h2>
            <select class="filter-chart border rounded p-1" data-chart="suhu">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
            </select>
        </div>
        <canvas id="chartSuhu" class="w-full h-72"></canvas>
    </div>

    <!-- Karbon -->
    <div class="bg-white rounded-lg border border-gray-300 p-4">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold">Gas Karbon</h2>
            <select class="filter-chart border rounded p-1" data-chart="karbon">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
            </select>
        </div>
        <canvas id="chartKarbon" class="w-full h-72"></canvas>
    </div>

    <!-- Metana -->
    <div class="bg-white rounded-lg border border-gray-300 p-4">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold">Gas Metana</h2>
            <select class="filter-chart border rounded p-1" data-chart="metana">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
            </select>
        </div>
        <canvas id="chartMetana" class="w-full h-72"></canvas>
    </div>
</div>


<script>
    let chartKelembapan, chartSuhu, chartKarbon, chartMetana;

    async function fetchSensorData() {
        try {
            const response = await fetch("/api/sensor-data");
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Gagal mengambil data sensor:", error);
            return [];
        }
    }

    function processData(data, type = 'daily') {
        const grouped = {};

        data.forEach(item => {
            const date = new Date(item.created_at);
            let key = '';

            switch(type) {
                case 'weekly':
                    const startOfWeek = new Date(date);
                    startOfWeek.setDate(date.getDate() - date.getDay());
                    key = startOfWeek.toISOString().split('T')[0];
                    break;
                case 'monthly':
                    key = `${date.getFullYear()}-${(date.getMonth()+1).toString().padStart(2, '0')}`;
                    break;
                default: // daily
                    key = date.toISOString().split('T')[0];
                    break;
            }

            if (!grouped[key]) {
                grouped[key] = {
                    kelembapan_tanah: [],
                    suhu: [],
                    gas_karbon: [],
                    gas_metana: []
                };
            }

            grouped[key].kelembapan_tanah.push(item.kelembapan_tanah);
            grouped[key].suhu.push(item.suhu);
            grouped[key].gas_karbon.push(item.gas_karbon);
            grouped[key].gas_metana.push(item.gas_metana);
        });

        const labels = Object.keys(grouped);
        const kelembapan = labels.map(k => average(grouped[k].kelembapan_tanah));
        const suhu = labels.map(k => average(grouped[k].suhu));
        const karbon = labels.map(k => average(grouped[k].gas_karbon));
        const metana = labels.map(k => average(grouped[k].gas_metana));

        return { labels, kelembapan, suhu, karbon, metana };
    }

    function average(arr) {
        if (!arr.length) return 0;
        return (arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(2);
    }

    async function updateAllCharts() {
        const filter = document.getElementById('filter')?.value || 'daily';
        const rawData = await fetchSensorData();
        const { labels, kelembapan, suhu, karbon, metana } = processData(rawData, filter);

        if (chartKelembapan) chartKelembapan.destroy();
        if (chartSuhu) chartSuhu.destroy();
        if (chartKarbon) chartKarbon.destroy();
        if (chartMetana) chartMetana.destroy();

        const createChart = (ctxId, label, data, borderColor, bgColor) => {
            const ctx = document.getElementById(ctxId).getContext('2d');
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label,
                        data,
                        borderColor,
                        backgroundColor: bgColor,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: label }
                    }
                }
            });
        };

        chartKelembapan = createChart('chartKelembapan', 'Kelembapan Tanah (%)', kelembapan, 'rgb(34,197,94)', 'rgba(34,197,94,0.2)');
        chartSuhu = createChart('chartSuhu', 'Suhu (°C)', suhu, 'rgb(59,130,246)', 'rgba(59,130,246,0.2)');
        chartKarbon = createChart('chartKarbon', 'Gas Karbon', karbon, 'rgb(239,68,68)', 'rgba(239,68,68,0.2)');
        chartMetana = createChart('chartMetana', 'Gas Metana', metana, 'rgb(250,204,21)', 'rgba(250,204,21,0.2)');
    }

    document.addEventListener("DOMContentLoaded", updateAllCharts);
</script>



</div>


</body>
</html>
