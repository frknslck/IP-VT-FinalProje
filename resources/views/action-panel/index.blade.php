<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Action Panel</title>
</head>
<body>
    
    <nav class="py-2 border-bottom bg-light">
        <div class="container">
            <a href="/" class="btn btn-link">Back to Site</a>
        </div>
    </nav>
    <header class="py-3 mb-4 bg-body-secondary text-center">
        <div class="container">
            <h1>Welcome, {{ $user->name }}</h1>
            <p class="mb-0">Roller: 
                @foreach($user->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @endforeach
            </p>
        </div>
    </header>

    <main class="container">
        @if(auth()->user()->roles->contains(function ($role) {
            return in_array($role->name, ['Admin', 'Corp']);
        }))
            <div class="mb-4">
                <canvas id="profitChart" style="width:100%; height:300px;"></canvas>
            </div>
        @endif
        <div class="mb-4">
            <form action="{{ route('action-panel.index') }}" method="GET">
                <label for="actionDropdown" class="form-label">Select an action</label>
                <select id="actionDropdown" name="action_id" class="form-select" onchange="this.form.submit()">
                    <option selected disabled>Select an action...</option>
                    @foreach($actions as $action)
                        <option value="{{ $action->id }}" {{ $selectedActionId == $action->id ? 'selected' : '' }}>
                            {{ $action->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div id="actionFormContainer" class="my-5">
            @if($viewData)
                {!! $viewData !!}
            @endif
        </div>
    </main>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chart = JSON.parse('{!! $chart !!}');
    const monthlyTotals = chart.reduce((acc, order) => {
        const month = moment(order.created_at).format('YYYY-MM');
        acc[month] = (acc[month] || 0) + parseFloat(order.total_amount);
        return acc;
    }, {});

    const sortedMonths = Object.keys(monthlyTotals).sort();
    const totalAmounts = sortedMonths.map(month => monthlyTotals[month]);

    const ctx = document.getElementById('profitChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: sortedMonths,
            datasets: [{
                label: 'Total Monthly Order Amounts (Only Completed Orders)',
                data: totalAmounts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Total Monthly Order Amounts (Only Completed Orders)'
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Amount ($)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });
});
</script>
</html>

