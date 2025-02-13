@extends('layouts.menu')

@section('title', 'Dashboard')

@section('content')
    <div class="flex-1 p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>            
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <i class="fas fa-dollar-sign text-4xl text-green-700 mr-4"></i>
                <div>
                    <h3 class="text-xl font-bold">Ingresos</h3>
                    <p class="text-gray-600">${{ number_format($totalEntries, 2) }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <i class="fas fa-money-bill-wave text-4xl text-red-500 mr-4"></i>
                <div>
                    <h3 class="text-xl font-bold">Gastos</h3>
                    <p class="text-gray-600">${{ number_format($totalExpenses, 2) }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <i class="fas fa-balance-scale text-4xl text-blue-500 mr-4"></i>
                <div>
                    <h3 class="text-xl font-bold">Balance</h3>
                    <p class="text-gray-600">${{ number_format($balance, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="flex-1 p-10">          
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Ingresos vs Gastos</h3>
                    <canvas id="incomeExpenseChart"></canvas>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Distribución de Gastos</h3>
                    <canvas id="expenseDistributionChart" class="chart-canvas"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Balance</h3>
                    <canvas id="balanceChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            position: 'bottom-start',
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            background: 'rgba(34, 79, 54)',
            customClass: {
                popup: 'rounded-lg text-white'
            }
        });
    </script>
    @endif
    <script>

        const incomeData = @json(array_values($incomeData));
        const expenseData = @json(array_values($expenseData));
        const incomeLabels = @json(array_keys($incomeData));
        const expenseLabels = @json(array_keys($expenseData));
        const expenseDistributionLabels = @json(array_keys($expenseDistribution));
        const expenseDistributionData = @json(array_values($expenseDistribution));

        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        
        const incomeMonthLabels = incomeLabels.map(month => monthNames[month - 1]);
        const expenseMonthLabels = expenseLabels.map(month => monthNames[month - 1]);


        const incomeExpenseChart = new Chart(document.getElementById('incomeExpenseChart'), {
            type: 'bar',
            data: {
                labels: incomeMonthLabels,
                datasets: [
                    {
                        label: 'Ingresos',
                        backgroundColor: 'green',
                        data: incomeData
                    },
                    {
                        label: 'Gastos',
                        backgroundColor: 'red',
                        data: expenseData
                    }
                ]
            }
        });

        const expenseDistributionChart = new Chart(document.getElementById('expenseDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: expenseDistributionLabels,
                datasets: [
                    {
                        backgroundColor: ['blue', 'orange', 'purple', 'gray'],
                        data: expenseDistributionData
                    }
                ]
            }
        });


        const balanceChart = new Chart(document.getElementById('balanceChart'), {
            type: 'line',
            data: {
                labels: incomeMonthLabels,
                datasets: [
                    {
                        label: 'Saldo Mensual',
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        data: incomeData.map((income, index) => income - (expenseData[index] || 0)),
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }
                ]
            }
        });
    </script>
@endsection