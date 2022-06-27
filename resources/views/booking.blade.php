<div class="container">
    <h1 class="text-center wow fadeInUp">Make booking</h1>

    <form class="main-form" action="{{ url('booking') }}" method="POST">

        @csrf

        @if(session()->has('message'))

        <div class="message">
            {{ session()->get('message') }}
        </div>

        @endif

        <div>
            <div>
                <input type="text" name="deskripsi_booking" placeholder="Deskripsi booking">
            </div>
            <div>
                <input type="date" name="tgl_booking">
            </div>
            <div>
                <select name="doctor" id="doctor">
                    <option value="doctor 1">Doctor Satu</option>
                    <option value="doctor 2">Doctor Dua</option>
                    <option value="doctor 3">Doctor Tiga</option>
                </select>
            </div>
        </div>

        <button type="submit">Submit Request</button>
    </form>
</div>
