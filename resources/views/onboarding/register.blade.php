<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Playverse — Establishing Connection</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0a0a0a] text-white antialiased min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full p-6 border border-gray-800 rounded-md">
        <h1 class="text-xl font-semibold mb-1">Establishing Connection…</h1>
        <p class="text-xs text-gray-400 mb-6">Create your Author/Studio access.</p>

        <form action="{{ route('onboarding.register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-300">Your Name</label>
                <input name="name" class="w-full mt-1 bg-[#0a0a0a] border border-gray-700 rounded-md p-2 text-sm" required>
                @error('name')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm text-gray-300">Email</label>
                <input name="email" type="email" class="w-full mt-1 bg-[#0a0a0a] border border-gray-700 rounded-md p-2 text-sm" required>
                @error('email')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm text-gray-300">Password</label>
                <input name="password" type="password" class="w-full mt-1 bg-[#0a0a0a] border border-gray-700 rounded-md p-2 text-sm" required>
                @error('password')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm text-gray-300">Studio Name</label>
                <input name="studio_name" class="w-full mt-1 bg-[#0a0a0a] border border-gray-700 rounded-md p-2 text-sm" required>
                @error('studio_name')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>

            <button class="w-full bg-white/5 hover:bg-white/10 border border-gray-700 rounded-md py-2 text-sm">
                Connect
            </button>
        </form>
    </div>
</body>
</html>
