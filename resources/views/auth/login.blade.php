<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('Login') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favico32.png') }}">
    <script src="https://cdn.tailwindcss.com/"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="flex h-screen w-full items-center justify-center login-container">
        <div class="rounded-xl bg-white px-16 py-10 shadow-lg max-sm:px-8 login-form">
            <div class="text-white card justify-content-center">
                {{-- <div class="mb-8 flex flex-col items-center">
                    <h1 class="text-xl font-bold blue-color">{{ __('Login') }}</h1>
                </div> --}}
                <form method="POST" action="{{ route('employees.authenticate') }}" class="flex flex-col items-center">
                    @csrf
                    <div class="mb-4 text-lg row text-center">
                        {{-- <label for="email" class="font-medium col-md-4 text-md-end blue-color">{{ __('Email Address') }}</label> --}}

                        <div class="col-md-6">
                            <input id="email"
                                class="rounded-xl border-2 px-6 py-2 text-black shadow-lg outline-none text-sm @error('email') is-invalid @enderror"
                                autofocus value="{{ old('email') }}" type="email" required name="email"
                                autocomplete="email" placeholder="{{ __('Email Address') }}" />

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 text-lg row text-center">
                        {{-- <label for="password" class="font-medium col-md-4 text-md-end blue-color">{{ __('Password') }}</label> --}}

                        <div class="col-md-6">
                            <input class="rounded-xl border-2 px-6 py-2 text-black shadow-lg outline-none text-sm"
                                type="Password" name="password" placeholder="{{ __('Password') }}" />
                        </div>
                    </div>

                    <hr class="mt-2 custom-hr-login-form">

                    <div class="mt-6 flex justify-center text-lg">
                        <button type="submit" class="rounded-xl px-10 py-2 text-white shadow-xl duration-300 blue-bg text-base">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
