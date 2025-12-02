<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Employee Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center h-screen" style="background-color: #f1f5f9;">
    <div class="card" style="width: 100%; max-width: 400px;">
        <div class="card-header text-center">
            <h1 class="text-xl font-bold text-primary">EmpMgmt</h1>
            <p class="text-sm text-gray mt-4">Sign in to your account</p>
        </div>
        <div class="card-body">
            <form action="{{ route('authenticate') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label for="email" class="text-sm font-medium mb-4" style="display:block;">Email Address</label>
                    <input type="email" name="email" id="email" class="input" required autofocus>
                    @error('email')
                        <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="text-sm font-medium mb-4" style="display:block;">Password</label>
                    <input type="password" name="password" id="password" class="input" required>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-4">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>
