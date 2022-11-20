<x-guest-layout>
    <x-auth-card>

        <h1 class="text-center">Login</h1>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form class="my-5" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" :value="__('Email')" class="form-label">Email address</label>
                <input class="form-control" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" :value="__('Password')" class="form-label">Password</label>
                <input class="form-control" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">{{ __('Remember me') }}</label>
            </div>
            @if (Route::has('password.request'))
            <a class="my-3 d-flex underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif
            <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
        </form>


    </x-auth-card>
</x-guest-layout>