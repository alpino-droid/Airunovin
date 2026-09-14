@extends('layout/app')

@section('title', __('Club'))

@section('content')
    <div class="container text-start pt-3">
        <div class="row align-items-start">
            <div class="col">
                <div class="h2">
                    {{ __('Registered Clubs') }}
                </div>
            </div>
            <div class="col">
                <div class="d-flex gap-2">
                    <form method="GET" action="{{ route('club') }}" class="d-flex gap-2">
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
        <p class="fs-3">{{ __('Club') }}</p>
        <div class="row justify-content-center g-2">
            @forelse($clubs as $club)
                <div class="col-auto mb-2">
                    <a href="{{ route('isiClub') }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                <img src="{{ $club?->logo_url ?? asset('img/balnkLogo.png') }}" alt="Organization logo" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}'">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="Nama Organisasi">{{ $club->nama }}</h5>
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-1">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $club->city}}</span>
                                    </li>
                                    <li>
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-building" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $club->induk_organisasi}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <p>{{ __('No clubs available') }}</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

