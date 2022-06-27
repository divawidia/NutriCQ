@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="row">
            @foreach ($diarys as $diary)
                <div class="col-md-12 mb-4" style="background-color: lightgray">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title">{{ $diary->created_at }}</h5>
                        <div class="d-flex">
                            <ul>
                                <li>Total Air : {{ $diary->total_air }}</li>
                                <li>Total Energi : {{ $diary->total_energi }}</li>
                                <li>Total Protein : {{ $diary->total_protein }}</li>
                                <li>Total Lemak : {{ $diary->total_lemak }}</li>
                            </ul>
                            <ul>
                                <li>Total Karbohidrat : {{ $diary->total_karbohidrat }}</li>
                                <li>Total Serat : {{ $diary->total_serat }}</li>
                                <li>Total Abu : {{ $diary->total_abu }}</li>
                                <li>Total Kalsium : {{ $diary->total_kalsium }}</li>
                            </ul>
                            <ul>
                                <li>Total Fosfor : {{ $diary->total_fosfor }}</li>
                                <li>Total Besi : {{ $diary->total_besi }}</li>
                                <li>Total Natrium : {{ $diary->total_natrium }}</li>
                                <li>Total Kalium : {{ $diary->total_kalium }}</li>
                            </ul>
                            <ul>
                                <li>Total Tembaga : {{ $diary->total_tembaga }}</li>
                                <li>Total Seng : {{ $diary->total_seng }}</li>
                            </ul>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="#" class="btn btn-danger me-5">Delete</a>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Detail</button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Food Detail</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul>
                                @foreach($diary->detail as $list)
                                    <li>
                                        <h5 class="mb-0">{{ $list->food->name }}</h5>
                                        <small class="mt-0">{{ $list->takaran_saji }} gram</small><br>
                                    </li>    
                                @endforeach
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection