<table border="1px black">
    <tr>
        <th>Nama Dokter</th>
        <th>Deskripsi booking</th>
        <th>Tanggal booking</th>
        <th>Status</th>
        <th>Cancel booking</th>
    </tr>

    @foreach($bookings as $booking)

    <tr>
        <td>{{ $booking->name }}</td>
        <td>{{ $booking->deskripsi_booking }}</td>
        <td>{{ $booking->tgl_booking }}</td>
        <td>{{ $booking->status }}</td>
        <td> <a onclick="return confirm('are you sure want to delete this?')" href="{{ url('cancel_booking',$booking->id) }}"><button>Cancel</button></a> </td>
    </tr>

    @endforeach
</table>
