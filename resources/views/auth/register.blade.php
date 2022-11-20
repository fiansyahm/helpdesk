<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <h1 class="text-center">Register</h1>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        
        <form class="my-3" method="POST" action="{{ route('register') }}">
            @csrf
            <!-- Name -->
            <div class="mb-3">
                <label class="form-label" for="name" :value="__('Name')">Nama</label>

                <input class="form-control" id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label class="form-label" for="email" :value="__('Email')">Email</label>

                <input class="form-control" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label" for="password" :value="__('Password')">Password</label>

                <input class="form-control" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label" for="password_confirmation" :value="__('Confirm Password')">Confirm Password</label>

                <input class="form-control" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>


            <a class="my-3 d-flex underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <button type="submit" class="d-flex btn btn-primary">{{ __('Register') }}</button>
        </form>
    </x-auth-card>
</x-guest-layout>