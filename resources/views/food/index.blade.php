@extends('layouts.main')

@section('container')
    <div class="container" style=" padding-top: 80px; padding-bottom: 30px;">
        <div class="d-flex justify-content-end">
            <h3>Total Nutrisi yang dikonsumsi</h3>
            <ul>
                <li>Total Air : {{ $diarys->sum('total_air') }}</li>
                <li>Total Energi : {{ $diarys->sum('total_energi') }}</li>
                <li>Total Protein : {{ $diarys->sum('total_protein') }}</li>
                <li>Total Lemak : {{ $diarys->sum('total_lemak') }}</li>
            </ul>
            <ul>
                <li>Total Karbohidrat : {{ $diarys->sum('total_karbohidrat') }}</li>
                <li>Total Serat : {{ $diarys->sum('total_serat') }}</li>
                <li>Total Abu : {{ $diarys->sum('total_abu') }}</li>
                <li>Total Kalsium : {{ $diarys->sum('total_kalsium') }}</li>
            </ul>
            <ul>
                <li>Total Fosfor : {{ $diarys->sum('total_fosfor') }}</li>
                <li>Total Besi : {{ $diarys->sum('total_besi') }}</li>
                <li>Total Natrium : {{ $diarys->sum('total_natrium') }}</li>
                <li>Total Kalium : {{ $diarys->sum('total_kalium') }}</li>
            </ul>
            <ul>
                <li>Total Tembaga : {{ $diarys->sum('total_tembaga') }}</li>
                <li>Total Seng : {{ $diarys->sum('total_seng') }}</li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @foreach ($diarys as $diary)
                <div class="col-md-12 mb-4">
                    <div class="card" style="background-color: lightgray">
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
                                <form action="/food/{{ $diary->id }}" method="POST" class="d-inline me-4">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $diary->id }}">Detail</button>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal-{{ $diary->id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Food Eaten at : {{ $diary->created_at }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                    <div class="modal-body">
                                        <ul class="list-group list-group-flush">
                                        @foreach($diary->detail as $list)
                                            <li class="list-group-item">
                                                <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#foodModal-{{ $list->food->id }}"><h5 class="mb-0">{{ $list->food->name }}</h5></a>
                                                <small class="mt-0">{{ $list->takaran_saji }} gram</small><br>
                                                {{-- <hr> --}}
                                                @if ($id = $list->food->id )    
                                                @endif
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

                            <!-- Modal for Food -->
                            <div class="modal fade" id="foodModal-{{ $id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">{{ $list->food->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                    <div class="modal-body">
                                        <ul>
                                            <li>Sumber : {{ $list->food->sumber }}</li>
                                            <li>Air : {{ $list->food->air }}</li>
                                            <li>Energi : {{ $list->food->energi }}</li>
                                            <li>Protein : {{ $list->food->protein }}</li>
                                            <li>Lemak : {{ $list->food->lemak }}</li>
                                            <li>Karbohidrat : {{ $list->food->karbohidrat }}</li>
                                            <li>Serat : {{ $list->food->serat }}</li>
                                            <li>Abu : {{ $list->food->abu }}</li>
                                            <li>Kalsium : {{ $list->food->kalsium }}</li>
                                            <li>Fosfor : {{ $list->food->fosfor }}</li>
                                            <li>Besi : {{ $list->food->besi }}</li>
                                            <li>Natrium : {{ $list->food->natrium }}</li>
                                            <li>Kalium : {{ $list->food->kalium }}</li>
                                            <li>Tembaga : {{ $list->food->tembaga }}</li>
                                            <li>Seng : {{ $list->food->seng }}</li>
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