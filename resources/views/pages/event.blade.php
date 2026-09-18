@extends('layout/app')

@section('title', 'Home - Laravel Blade')

@section('content')
    <div class="container text-start pt-3">
        <div class="row align-items-start">
            <div class="col">
                <div class="h2">
                    {{ __('National Competition Event Schedule') }}
                </div>
                <div>
                    <form action="{{ route('event.create') }}">
                        <button type="submit" class="btn btn-success">{{ __('Submit Event') }}</button>
                    </form>
                </div>
            </div>
            <div class="col">
                <div class="d-flex gap-2">
                    <form method="GET" action="{{ route('event') }}" class="d-flex gap-2">
                        <select name="province" class="form-select">
                            <option value="">{{ __('All Provinces') }}</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}" @selected(request('province') == $prov->id)>{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                        <select name="city" class="form-select">
                            <option value="">{{ __('All Cities') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" @selected(request('city') == $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl mt-5">
        <p class="fs-6">Ayam</p>
        <div class="row justify-content-center g-2">
            @foreach($events as $evt)
                <div class="col-auto mb-5">
                    <a href="{{ route('isiEvent', $evt) }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top standard-poster-frame">
                                @if($evt->poster && count($evt->poster) > 0)
                                    @php $posters = $evt->poster; @endphp
                                    <img src="{{ asset('storage/' . $posters[0]) }}" alt="Event poster" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy">
                                @else
                                    <img src="https://static.wikitide.net/thefireriseswikiwiki/thumb/e/ec/The_fire_truly_rises.gif/200px-The_fire_truly_rises.gif" alt="Event thumbnail" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy">
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="{{ $evt->nama }}">{{ $evt->nama }}</h5>
                                <ul class="list-unstyled small mb-2">
                                    <li class="mb-1">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $evt->tanggal }}</span>
                                    </li>
                                    <li>
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-person" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $evt->penyelenggara }}</span>
                                    </li>
                                </ul>
                                <div class="mt-auto pt-2 border-top">
                                    <div class="text-muted">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
                                        <span>{{ $evt->kota }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

            @if($events->isEmpty())
                <div class="col-12 text-center text-muted py-5">
                    <p>{{ __('No events available') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection