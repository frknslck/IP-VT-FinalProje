<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Panel</title>
</head>
<body>
    <nav class="py-2 border-bottom bg-light">
        <div class="container">
            <a href="/" class="btn btn-link">Siteye Dön</a>
        </div>
    </nav>
    <header class="py-3 mb-4 bg-body-secondary text-center">
        <div class="container">
            <h1>Hoş geldiniz, {{ Auth::user()->name }}</h1>
            <p class="mb-0">Roller: 
                @foreach(Auth::user()->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @endforeach
            </p>
        </div>
    </header>
    <main class="container">
        <div class="mb-4">
            <label for="actionDropdown" class="form-label">Aksiyon Seç:</label>
            <select id="actionDropdown" class="form-select">
                <option selected disabled>Aksiyon seçiniz...</option>
                @foreach(Auth::user()->roles as $role)
                    @foreach($role->actions as $action)
                        <option value="{{ $action->id }}">{{ $action->name }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div id="actionFormContainer" class="mt-4">
        </div>
    </main>
    <script>
        document.getElementById('actionDropdown').addEventListener('change', function() {
            const actionId = this.value;
            const formContainer = document.getElementById('actionFormContainer');
            
            formContainer.innerHTML = `<p>Seçilen aksiyon ID'si: ${actionId}</p>`;
        });
    </script>
</body>
</html>
