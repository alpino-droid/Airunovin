<header class="topbar d-flex align-items-center justify-content-between gap-3">
    <div class="search-box">
        <div class="search-wrap">
            <span class="icon">⌕</span>
            <input type="text" class="form-control" placeholder="{{ __('Search data, events, or members...') }}">
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="btn-group">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary">{{ __('Dashboard') }}</a>
            <a href="{{ route('admin.club') }}" class="btn btn-sm btn-outline-primary">{{ __('Club') }}</a>
            <a href="{{ route('admin.event') }}" class="btn btn-sm btn-outline-primary">{{ __('Event') }}</a>
            <a href="{{ route('admin.marketplace') }}" class="btn btn-sm btn-outline-primary">{{ __('Market') }}</a>
        </div>
        <button class="btn btn-light border">+ {{ __('Create new') }}</button>
        <div class="user-pill">
            <div class="avatar">AD</div>
            <div>
                <div class="fw-bold">Admin</div>
                <small class="text-muted">Super admin</small>
            </div>
        </div>
    </div>
</header>
