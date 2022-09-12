{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport"--}}
{{--          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
{{--    <meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
{{--    <title>Login</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<form method="POST" action="{{ route('employees.authenticate') }}">--}}
{{--    @csrf--}}
{{--    <div>--}}
{{--        <label for="email">Email</label>--}}
{{--        <input--}}
{{--            type="email"--}}
{{--            name="email"--}}
{{--            value="{{ old('email') }}"--}}
{{--        />--}}
{{--        @error('email')--}}
{{--        <p></p>{{ $message }}--}}
{{--        @enderror--}}
{{--    </div>--}}

{{--    <div>--}}
{{--        <label for="password">Password</label>--}}
{{--        <input--}}
{{--            type="password"--}}
{{--            name="password"--}}
{{--            value="{{ old('password') }}"--}}
{{--        />--}}
{{--        @error('password')--}}
{{--        <p></p>{{ $message }}--}}
{{--        @enderror--}}
{{--    </div>--}}

{{--    <div>--}}
{{--        <button type="submit">Sign In</button>--}}
{{--    </div>--}}
{{--</form>--}}
{{--</body>--}}
{{--</html>--}}


    <!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>KindaCode.com</title>
</head>

<body>
<div class="w-screen h-screen flex justify-center items-center">
    <form class="p-10 bg-white rounded-xl drop-shadow-lg space-y-5" method="POST" action="{{ route('employees.authenticate') }}">
        @csrf
        <h1 class="text-center text-3xl">Sign In</h1>
        <div class="flex flex-col space-y-2">
            <label class="text-sm font-light" for="email">Email</label>
            <input class="w-96 px-3 py-2 rounded-md border border-slate-400" type="email" placeholder="Your Email"
                   name="email" id="email">
        </div>
        <div class="flex flex-col space-y-2">
            <label class="text-sm font-light" for="password">Password</label>
            <input class="w-96 px-3 py-2 rounded-md border border-slate-400" type="password"
                   placeholder="Your Password" name="password" id="password">
        </div>

        <button class="w-full px-10 py-2 bg-blue-600 text-white rounded-md
            hover:bg-blue-500 hover:drop-shadow-md duration-300 ease-in" type="submit">
            Sign In
        </button>
    </form>
</div>
</body>
</html>
