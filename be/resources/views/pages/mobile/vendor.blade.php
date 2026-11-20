@extends('layouts.mobile')

@section('title', __('mobile.vendor'))

@section('content')

<section class="screen {{ request()->routeIs('vendor.mobile') ? 'active' : '' }}" id="vendors">

    <div class="px-4 pt-4">

        {{-- Header --}}
        <a href="{{ route('profile.edit') }}"
            class="text-dark text-decoration-none d-inline-flex align-items-center mb-3" onclick="if (window.history.length > 1) { window.history.back(); return false; }">
                <i class="bi bi-arrow-left fs-5"></i>
                <span class="ms-2 small fw-semibold">{{ __('mobile.back') }}</span>
            </a>

        <p class="eyebrow mb-1">{{ __('mobile.our_team') }}</p>

        <h1 class="fw-semibold" style="font-size: 28px">
            {{ __('mobile.meet_vendors') }}
        </h1>


        {{-- Search --}}
        <div class="app-card d-flex align-items-center gap-2 px-3 py-2">
            <i class="bi bi-search text-muted2"></i>

            <input
                id="search"
                class="border-0 bg-transparent w-100 small"
                style="outline: none"
                placeholder="{{ __('mobile.find_vendor') }}"
                autocomplete="off">
        </div>


        {{-- Filters --}}
        <div class="d-flex gap-2 no-scrollbar mt-3" id="filters">

            <button
                type="button"
                class="filter active"
                data-f="Semua">{{ __('mobile.all') }}</button>

            @foreach($vendors_categories as $vendors_category)

            <button
                type="button"
                class="filter"
                data-f="{{ $vendors_category->name }}">
                {{ $vendors_category->name }}
            </button>

            @endforeach

        </div>


        {{-- Vendor List --}}
        <div class="d-grid gap-3 mt-3" id="vendorList">

            @forelse ($vendors as $vendor)

            @php
            $avatar = $vendor->avatar;

            if ($avatar) {
            $avatar = Str::startsWith(
            $avatar,
            ['http://', 'https://']
            )
            ? $avatar
            : asset('storage/' . $avatar);
            } else {
            $avatar = asset('dist/assets/images/avatar.jpg');
            }

            $vendorsCategoryNames = $vendor
            ->vendors_categories
            ->pluck('name')
            ->implode(',');
            @endphp


            <article
                class="app-card overflow-hidden vendor-item"
                data-uuid="{{ $vendor->uuid }}"
                data-name="{{ strtolower($vendor->name) }}"
                data-vendor-name="{{ $vendor->name }}"
                data-avatar="{{ $avatar }}"
                data-bio="{{ $vendor->biografi ?? __('mobile.default_bio') }}"
                data-experience="{{ $vendor->pengalaman ?? __('mobile.default_experience') }}"
                data-vendors-categories="{{ strtolower($vendorsCategoryNames) }}">

                <img
                    class="img-teacher"
                    src="{{ $avatar }}"
                    alt="{{ $vendor->name }}"
                    loading="lazy"
                    decoding="async">


                <div class="p-3">

                    {{-- Vendor Categories --}}
                    <div class="d-flex flex-wrap gap-2">

                        @forelse ($vendor->vendors_categories as $vendors_category)

                        <span
                            class="chip bg-sage-soft text-sage m-micro">
                            {{ $vendors_category->name }}
                        </span>

                        @empty

                        <span
                            class="chip bg-sage-soft text-sage m-micro">
                            {{ __('mobile.vendor') }}
                        </span>

                        @endforelse

                    </div>


                    {{-- Name --}}
                    <h2 class="h5 fw-semibold mt-2 mb-1">
                        {{ $vendor->name }}
                    </h2>


                    {{-- Bio --}}
                    <p class="small text-muted2 mb-2 vendor-bio">
                        {{ Str::limit(
                                $vendor->biografi ?? __('mobile.default_bio'),
                                120
                            ) }}
                    </p>


                    {{-- Experience --}}
                    <p
                        class="mb-0 text-muted2 text-small">

                        <i class="bi bi-award"></i>

                        {{ $vendor->pengalaman ?? __('mobile.default_experience') }}

                    </p>


                    {{-- Button --}}
                    <button
                        type="button"
                        class="btn btn-warm w-100 py-2 mt-3"
                        onclick="showVendor('{{ $vendor->uuid }}')">

                        {{ __('mobile.view_profile') }}

                    </button>

                </div>

            </article>

            @empty

            <div class="app-card p-4 text-center">

                <p class="mb-1 fw-semibold">
                    {{ __('mobile.no_vendors') }}
                </p>

                <p class="small text-muted2 mb-0">
                    {{ __('mobile.no_vendors_desc') }}
                </p>

            </div>

            @endforelse


            {{-- No Search Result --}}
            @if($vendors->count())

            <div
                id="noVendorResult"
                class="app-card p-4 text-center d-none">

                <i class="bi bi-person-x fs-2 text-muted2"></i>

                <p class="mb-1 mt-2 fw-semibold">
                    {{ __('mobile.no_vendors_found') }}
                </p>

                <p class="small text-muted2 mb-0">
                    {{ __('mobile.try_another') }}
                </p>

            </div>

            @endif

        </div>

    </div>

</section>

<div
    class="modal fade"
    id="vendorModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 overflow-hidden">

            {{-- Image --}}
            <div class="position-relative flex-shrink-0">

                <img
                    id="modalVendorAvatar"
                    src=""
                    alt=""
                    class="w-100"
                    style="
                        height:260px;
                        object-fit:contain;
                        background:#f5f3ed;
                    ">

                <button
                    type="button"
                    class="btn-close position-absolute top-0 end-0 m-3 bg-white rounded-circle p-2"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                </button>

            </div>

            {{-- Scrollable Content --}}
            <div
                class="modal-body p-4"
                style="overflow-y:auto;">

                <div
                    id="modalVendorCategories"
                    class="d-flex flex-wrap gap-2 mb-2">
                </div>

                <h2
                    id="modalVendorName"
                    class="h4 fw-semibold mb-2">
                </h2>

                <p
                    id="modalVendorExperience"
                    class="small text-muted2 mb-3">
                </p>

                <div>
                    <h6 class="fw-semibold mb-2">
                        {{ __('mobile.about') }}
                    </h6>

                    <p
                        id="modalVendorBio"
                        class="small text-muted2 mb-0"
                        style="line-height:1.8;">
                    </p>
                </div>

            </div>

            {{-- Bottom Close --}}
            <div class="px-4 pb-4 pt-2 flex-shrink-0">
                <button
                    type="button"
                    class="btn btn-warm border w-100 py-2"
                    data-bs-dismiss="modal">{{ __('mobile.close') }}</button>
            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput =
            document.getElementById('search');

        const filterButtons =
            document.querySelectorAll('#filters .filter');

        const vendors =
            document.querySelectorAll('.vendor-item');

        const noResult =
            document.getElementById('noVendorResult');

        let activeFilter = 'Semua';


        // =========================
        // SEARCH + FILTER
        // =========================

        function filterVendors() {

            const keyword =
                searchInput.value
                .toLowerCase()
                .trim();

            let visibleCount = 0;


            vendors.forEach(function(vendor) {

                const name =
                    vendor.dataset.name || '';

                const vendors_categories =
                    vendor.dataset.vendorsCategories || '';


                // Search nama atau kategori vendor
                const searchMatch =
                    keyword === '' ||
                    name.includes(keyword) ||
                    vendors_categories.includes(keyword);


                // Filter vendors_category
                const filterMatch =
                    activeFilter === 'Semua' ||
                    vendors_categories
                    .split(',')
                    .map(item => item.trim())
                    .includes(
                        activeFilter.toLowerCase()
                    );


                if (searchMatch && filterMatch) {

                    vendor.classList.remove('d-none');

                    visibleCount++;

                } else {

                    vendor.classList.add('d-none');

                }

            });


            // Empty state
            if (noResult) {

                if (visibleCount === 0) {
                    noResult.classList.remove('d-none');
                } else {
                    noResult.classList.add('d-none');
                }

            }

        }


        // Search
        if (searchInput) {

            searchInput.addEventListener(
                'input',
                filterVendors
            );

        }


        // Filter
        filterButtons.forEach(function(button) {

            button.addEventListener('click', function() {

                filterButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                });

                this.classList.add('active');

                activeFilter =
                    this.dataset.f;

                filterVendors();

            });

        });

    });


    // =========================
    // TAMPILKAN MODAL VENDOR
    // =========================

    function showVendor(uuid) {

        const vendor =
            document.querySelector(
                '.vendor-item[data-uuid="' + uuid + '"]'
            );


        if (!vendor) {
            return;
        }


        const name =
            vendor.dataset.vendorName || '';

        const avatar =
            vendor.dataset.avatar || '';

        const bio =
            vendor.dataset.bio ||
            @json(__('mobile.default_bio'));

        const experience =
            vendor.dataset.experience ||
            @json(__('mobile.default_experience'));

        const vendors_categories =
            vendor.dataset.vendorsCategories || '';


        // Avatar
        const modalAvatar =
            document.getElementById(
                'modalVendorAvatar'
            );

        modalAvatar.src = avatar;
        modalAvatar.alt = name;


        // Name
        document.getElementById(
            'modalVendorName'
        ).textContent = name;


        // Experience
        document.getElementById(
                'modalVendorExperience'
            ).innerHTML =
            '<i class="bi bi-award me-1"></i>' +
            experience;


        // Bio
        document.getElementById(
            'modalVendorBio'
        ).textContent = bio;


        // Vendor Categories
        const vendorsCategoryContainer =
            document.getElementById(
                'modalVendorCategories'
            );

        vendorsCategoryContainer.innerHTML = '';


        if (vendors_categories.trim()) {

            vendors_categories
                .split(',')
                .forEach(function(vendors_category) {

                    vendors_category =
                        vendors_category.trim();

                    if (!vendors_category) {
                        return;
                    }

                    const chip =
                        document.createElement('span');

                    chip.className =
                        'chip bg-sage-soft text-sage';

                    chip.style.fontSize = '10px';

                    chip.textContent =
                        vendors_category;

                    vendorsCategoryContainer
                        .appendChild(chip);

                });

        } else {

            vendorsCategoryContainer.innerHTML = `
            <span
                class="chip bg-sage-soft text-sage m-micro">
                {{ __('mobile.vendor') }}
            </span>
        `;

        }


        // Open modal
        const modalElement =
            document.getElementById(
                'vendorModal'
            );

        const modal =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );

        modal.show();

    }
</script>

@endsection