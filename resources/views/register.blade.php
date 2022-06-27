<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>

<body>

    {{-- type register --}}
    <!-- <div class="role flex justify-center mb-4">
        <button class="userbtn">User</button>
        <button class="doctorbtn">Doctor</button>
    </div> -->

    <form method="POST" action="{{ url("/api/register") }}" class="user">
        @csrf

        <div class="flex">
            <div class="left flex-auto w-64">
                <!-- Name -->
                <div>
                    <input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                        autofocus placeholder="Name" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                        placeholder="Email" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" placeholder="Password" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required placeholder="Password Confirmation" />
                </div>
            </div>

            {{-- <div class="right flex-auto">

                <div class="ml-4 mt-4">
                    <input id="phone" class="block mt-1 w-full" type="number" name="phone" :value="old('phone')"
                        required placeholder="Phone" />
                </div>

                <div class="ml-4 mt-4">
                    <label for="gender" value="{{ __('Gender') }}" />
                    <select name="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <!-- <div class="ml-4 mt-4 cv">
                    <label for="cv" :value="__('CV')" />

                    <input id="cv" class="block mt-1 w-full" type="file" name="cv" :value="old('cv')" required />
                </div> -->
            </div> --}}
        </div>


        <!-- Select Option Rol type -->
        {{-- <div class="mt-4">
            {{-- <label for="role_id" value="{{ __('Register as:') }}" /> --}}
            {{-- <select name="role_id">
                <option value="user">User</option>
                <option value="doctor">Doctor</option>
            </select>
        </div> --}}

        <div class="flex items-center justify-end mt-4">
            <!-- <a class="underline text-sm text-gray-600 hover:text-gray-900" href="/api/login">
                {{ __('Already registered?') }}
            </a> -->

            <button class="ml-4 regisbtn" name="role_id" value="doctor">
                {{ __('Register') }}
            </button>
        </div>
    </form>


    <!-- <script>
        $(document).ready(function () {

            $('.userbtn').click(function () {
                if ($('.user').is(':visible')) {
                    $('.cv').hide();
                }
            })

            $('.doctorbtn').click(function () {
                // if ($('.doctor').is(':visible')) {
                //     $('.doctor').fadeOut();
                //     if ($(".doctor").data('lastClicked') !== this) {
                //         $('.doctor').fadeIn();
                //     }
                // } else {
                //     $('.user').hide();
                //     $('.doctor').fadeIn();
                // }
                // $(".doctor").data('lastClicked', this);

                $('.cv').show();
                if ($('.cv').is(':visible')) {
                    $('.regisbtn').attr('value', 'doctor')
                }
            })
        })

    </script> -->
</body>

</html>
