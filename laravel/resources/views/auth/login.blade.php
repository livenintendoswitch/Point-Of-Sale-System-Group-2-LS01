<!DOCTYPE html>
<html>

<head>
    <title>Login POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow w-96">
        <h2 class="text-2xl font-bold mb-6">Login POS</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
        </form>
        @if($errors->any())
        <div class="mt-4 text-red-600">{{ $errors->first() }}</div>
        @endif
    </div>
</body>

</html>