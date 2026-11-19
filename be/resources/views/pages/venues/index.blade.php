@extends('layouts.app')
@section('title', 'Venues')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Venues" page="Venues" active="All Venues" route="{{ route('venues.index') }}" />
@endsection

@if (session('success'))
<div class="alert alert-success alert-dismissible mb-3 mt-3 fade show" role="alert">
    <span class="alert-text text-white"> {{ session('success') }}</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible mb-3 mt-3 fade show" role="alert">
    <span class="alert-text text-white"> {{ session('error') }}</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif


<div class="page-heading">
    <div class="row g-3 ml-stats">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm"><div class="card-body py-2 px-3"><div class="ml-stat">
                <div class="ml-stat-icon ic-primary"><i class="bx bx-building"></i></div>
                <div><div class="ml-stat-value">{{ number_format($stats['total']) }}</div><div class="ml-stat-label">Total Venues</div></div>
            </div></div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm"><div class="card-body py-2 px-3"><div class="ml-stat">
                <div class="ml-stat-icon ic-success"><i class="bx bx-check-shield"></i></div>
                <div><div class="ml-stat-value">{{ number_format($stats['active']) }}</div><div class="ml-stat-label">Active Venues</div></div>
            </div></div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm"><div class="card-body py-2 px-3"><div class="ml-stat">
                <div class="ml-stat-icon ic-warning"><i class="bx bx-pause-circle"></i></div>
                <div><div class="ml-stat-value">{{ number_format($stats['inactive']) }}</div><div class="ml-stat-label">Inactive Venues</div></div>
            </div></div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm"><div class="card-body py-2 px-3"><div class="ml-stat">
                <div class="ml-stat-icon ic-violet"><i class="bx bx-calendar-event"></i></div>
                <div><div class="ml-stat-value">{{ number_format($stats['schedules']) }}</div><div class="ml-stat-label">Total Schedules</div></div>
            </div></div></div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form method="GET" action="{{ route('venues.index') }}" class="d-flex flex-wrap align-items-end gap-2 flex-grow-1">
                    <div style="flex:1 1 160px;min-width:0;"><label class="form-label small mb-0">Search</label><input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm w-100" placeholder="Name, address..." autocomplete="off"></div>
                    <div style="flex:1 1 130px;min-width:0;max-width:200px;"><label class="form-label small mb-0">Status</label><select name="status" class="form-select form-select-sm w-100"><option value="">All</option><option value="active" @selected($status==='active')>Active</option><option value="inactive" @selected($status==='inactive')>Inactive</option></select></div>
                    <div class="d-flex gap-1"><button type="submit" class="btn btn-sm btn-success"><i class="bi bi-funnel"></i> Filter</button><a href="{{ route('venues.index') }}" class="btn btn-sm btn-light">Reset</a></div>
                </form>
                @can('venues.create')
                <a href="{{ route('venues.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add Venue</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($venues as $venue)
        <div class="col-12 col-md-6 col-xl-4">

            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="position-relative">

                    @if ($venue->image)
                    <img src="{{ asset('storage/' . $venue->image) }}"
                        alt="{{ $venue->name }}"
                        class="w-100"
                        style="height: 210px; object-fit: cover;"
                        loading="lazy">
                    @else
                    <div class="d-flex align-items-center justify-content-center bg-light"
                        style="height: 210px;">

                        <i class="bi bi-building text-secondary"
                            style="font-size: 3rem;"></i>

                    </div>
                    @endif


                    <div class="position-absolute top-0 end-0 m-3">
                        @if ($venue->status === 'active')
                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                            <i class="bi bi-circle-fill me-1"
                                style="font-size: 7px;"></i>
                            Active
                        </span>

                        @else

                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">
                            <i class="bi bi-circle-fill me-1"
                                style="font-size: 7px;"></i>
                            Inactive
                        </span>

                        @endif

                    </div>

                </div>


                <div class="card-body p-4 pb-0">
                    <h5 class="fw-bold mb-2"
                        style="color: var(--bs-heading-color);">
                        {{ $venue->name }}
                    </h5>

                    @if ($venue->excerpt)
                    <p class="text-muted small mb-3">
                        {{ $venue->excerpt }}
                    </p>
                    @endif


                    {{-- Address --}}
                    @if ($venue->address)

                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-geo-alt text-secondary mt-1"></i>
                        <span class="small text-muted">
                            {{ $venue->address }}
                        </span>

                    </div>

                    @endif


                    {{-- Phone --}}
                    @if ($venue->phone)

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-telephone text-secondary"></i>
                        <span class="small text-muted">
                            {{ $venue->phone }}
                        </span>

                    </div>

                    @endif


                    {{-- Schedule Count --}}
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-calendar3 text-secondary"></i>

                            <span class="small text-muted">
                                {{ $venue->schedules_count ?? 0 }}
                                {{ ($venue->schedules_count ?? 0) == 1 ? 'Schedule' : 'Schedules' }}
                            </span>

                        </div>

                    </div>

                </div>

                <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                    <div class="d-flex gap-2">

                        {{-- View Map --}}
                        @if ($venue->google_maps_url)
                        <a href="{{ $venue->google_maps_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-primary flex-fill">

                            <i class="bi bi-geo-alt me-1"></i>
                            View Map

                        </a>
                        @endif


                        @can('venues.update')
                        <a href="{{ route('venues.edit', $venue->uuid) }}"
                            class="btn btn-sm btn-outline-dark flex-fill">

                            <i class="bi bi-pencil me-1"></i>
                            Edit
                        </a>
                        @endcan

                        @can('venues.destroy')
                        <form action="{{ route('venues.destroy', $venue->uuid) }}"
                            method="POST"
                            class="flex-fill"
                            id="deleteVenueForm_{{ $venue->uuid }}">

                            @csrf
                            @method('DELETE')

                            <button type="button"
                                class="btn btn-sm btn-danger w-100"
                                onclick="showDeleteVenue('{{ $venue->uuid }}')">

                                <i class="bi bi-trash me-1"></i>
                                Delete

                            </button>

                        </form>
                        @endcan

                    </div>
                </div>


            </div>

        </div>

        @empty

        {{-- Empty State --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <div class="card-body text-center py-5">

                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                        style="
                                width: 64px;
                                height: 64px;
                                background: var(--bs-tertiary-bg);
                            ">

                        <i class="bi bi-building fs-4 text-secondary"></i>

                    </div>

                    <h5 class="fw-bold mb-2"
                        style="color: var(--bs-heading-color);">
                        No Venues Found
                    </h5>

                    <p class="text-muted mb-4">
                        @if ($q !== '' || $status !== '')
                        Try a different keyword or filter, or
                        <a href="{{ route('venues.index') }}">reset the filter</a>.
                        @else
                        Add your first YogaRoots venue location.
                        @endif
                    </p>

                    @can('venues.create')
                    @if ($q === '' && $status === '')
                    <a href="{{ route('venues.create') }}"
                        class="btn btn-primary px-4">
                        <i class="bi bi-plus-lg me-1"></i>
                        Add Venue
                    </a>
                    @endif
                    @endcan

                </div>

            </div>

        </div>

        @endforelse

    </div>

</div>


{{-- Delete Confirmation --}}
<script>
    function showDeleteVenue(uuid) {

        Swal.fire({
            title: 'Delete Venue?',
            text: 'This venue will be permanently deleted. Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33'
        }).then((result) => {

            if (result.isConfirmed) {
                document
                    .getElementById('deleteVenueForm_' + uuid)
                    .submit();
            }

        });

    }
</script>

@endsection
