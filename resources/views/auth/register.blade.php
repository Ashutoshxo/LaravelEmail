<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6">Register</h2>
            
            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2">Name</label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <div class="mb-6">
                    <label class="block mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required 
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Register
                </button>
                
                <p class="mt-4 text-center">
                    Already have account? 
                    <a href="{{ route('login') }}" class="text-blue-600">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>