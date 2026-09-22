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
                <div class="d-flex gap-2 justify-content-end">
                    <form method="GET" action="{{ route('event') }}" class="d-flex flex-wrap gap-2 justify-content-end">
                        <input type="text" name="search" class="form-control" style="max-width: 200px;" placeholder="{{ __('Cari event...') }}" value="{{ request('search') ?? request('q') }}">
                        <select name="province" class="form-select" style="max-width: 170px;">
                            <option value="">{{ __('All Provinces') }}</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}" @selected(request('province') == $prov->id)>{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                        <select name="city" class="form-select" style="max-width: 150px;">
                            <option value="">{{ __('All Cities') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" @selected(request('city') == $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">Filter</button>
                        @if(request('search') || request('q') || request('province') || request('city'))
                            <a href="{{ route('event') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i></a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl mt-4">
        @if(request('search') || request('q'))
            <div class="alert alert-light border d-flex justify-content-between align-items-center py-2 px-3 mb-4">
                <div>
                    <i class="bi bi-search me-1 text-primary"></i> Menampilkan hasil pencarian untuk: <strong>"{{ request('search') ?? request('q') }}"</strong> 
                    <span class="badge bg-secondary ms-1">{{ count($events) }} event ditemukan</span>
                </div>
                <a href="{{ route('event') }}" class="btn btn-sm btn-outline-secondary">Reset Pencarian</a>
            </div>
        @endif

        <div class="row justify-content-center g-2">
            @forelse($events as $evt)
                <div class="col-auto mb-5">
                    <a href="{{ route('isiEvent', $evt) }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top standard-poster-frame">
                                @if($evt->poster && count($evt->poster) > 0)
                                    @php $posters = $evt->poster; @endphp
                                    <img src="{{ asset('storage/' . $posters[0]) }}" alt="Event poster" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                                @else
                                    <img src="https://static.wikitide.net/thefireriseswikiwiki/thumb/e/ec/The_fire_truly_rises.gif/200px-The_fire_truly_rises.gif" alt="Event thumbnail" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="{{ $evt->nama }}">{{ $evt->nama }}</h5>
                                <ul class="list-unstyled small mb-2">
                                    <li class="mb-1">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $evt->tanggal ? $evt->tanggal->format('d M Y') : '-' }}</span>
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
            @empty
                <div class="col-12 py-5 text-center text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                    <h5 class="fw-semibold">Tidak ada event ditemukan</h5>
                    <p class="small mb-3">Coba ubah kata kunci atau reset filter pencarian Anda.</p>
                    <a href="{{ route('event') }}" class="btn btn-sm btn-primary">Lihat Semua Event</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection