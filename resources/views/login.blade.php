 <form method="POST" action="{{ url("/login") }}">
     @csrf

     <div class="flex">
         <div class="left flex-auto w-64">

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

         </div>

     </div>

     <div class="flex items-center justify-end mt-4">
         <button class="ml-4 regisbtn" name="role_id" value="doctor">
             {{ __('Login') }}
         </button>
     </div>
 </form>
