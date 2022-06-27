<table border="1px solid black">
    <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Deskripsi booking</th>
        <th>Status</th>
        <th>Approved</th>
        <th>Canceled</th>
        <th>Link</th>
    </tr>

    @foreach($data as $booking)
    <tr>
        <td>{{ $booking->name }}</td>
        <td>{{ $booking->tgl_booking }}</td>
        <td>{{ $booking->deskripsi_booking }}</td>
        <td>{{ $booking->status }}</td>
        <td>
            <a href="{{ url('approved',$booking->id) }}">
                <button>Approved</button>
            </a>
        </td>
        <td>
            <a href="{{ url('canceled',$booking->id) }}">
                <button>Canceled</button>
            </a>
        </td>
        <td>
            <a href="{{ url('linkmeet',$booking->id) }}">
                <button>Send link</button>
            </a>
        </td>
    </tr>

    @endforeach
</table>
