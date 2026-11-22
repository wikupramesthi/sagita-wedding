<div id="package-recommendation">
    <style>
        #package-recommendation-form .budget-input-row {
            display: grid;
            grid-template-columns: 44px minmax(180px, 1fr) auto;
            gap: 0;
            align-items: stretch;
        }
        #package-recommendation-form .budget-input-row .input-group-text,
        #package-recommendation-form .budget-input-row .form-control,
        #package-recommendation-form .budget-input-row .btn {
            min-height: 44px;
        }
        #package-recommendation-form .budget-input-row .input-group-text {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: .375rem 0 0 .375rem;
        }
        #package-recommendation-form .budget-input-row .form-control {
            border-radius: 0;
        }
        #package-recommendation-form .budget-input-row .budget-search-button {
            border-radius: 0 .375rem .375rem 0;
        }
        #package-recommendation-results.is-updating {
            opacity: .45;
            transition: opacity .16s ease;
        }
        #package-recommendation-results {
            transition: opacity .16s ease;
        }
        @media (max-width: 575.98px) {
            #package-recommendation-form .budget-input-row {
                display: grid;
                grid-template-columns: 44px minmax(0, 1fr);
            }
            #package-recommendation-form .budget-input-row .input-group-text {
                grid-column: 1;
                grid-row: 1;
            }
            #package-recommendation-form .budget-input-row .form-control {
                grid-column: 2;
                grid-row: 1;
            }
            #package-recommendation-form .budget-input-row .budget-search-button {
                grid-column: 1 / -1;
                grid-row: 2;
                width: 100%;
                margin-top: 8px;
            }
        }
    </style>
    <form
        id="package-recommendation-form"
        action="{{ route('dashboard.index') }}"
        method="GET"
        data-recommendations-url="{{ route('dashboard.recommendations') }}"
        data-no-loader
        novalidate
    >
        <label for="planning-budget-max" class="form-label small fw-semibold">Batas anggaran paket</label>
        <div class="budget-input-row">
            <span class="input-group-text">Rp</span>
            <input
                id="planning-budget-max"
                class="form-control"
                type="text"
                inputmode="numeric"
                autocomplete="off"
                name="budget_max"
                value="{{ $budgetMax !== null ? number_format($budgetMax, 0, ',', '.') : '' }}"
                placeholder="Contoh 50000000"
                required
            >
            <button type="submit" class="btn btn-primary text-nowrap budget-search-button" data-search-button>
                <i class="bi bi-search me-1"></i> Cari Paket
            </button>
        </div>
        <div class="form-text">Masukkan batas anggaran untuk melihat paket yang sesuai tanpa meninggalkan halaman.</div>
    </form>

    <div id="package-recommendation-results" class="mt-3" aria-live="polite">
        @if ($budgetMax !== null)
            <div class="small fw-semibold mb-2">
                Rekomendasi paket hingga Rp {{ number_format($budgetMax, 0, ',', '.') }}
            </div>
            <div class="list-group">
                @forelse ($recommendedPackages as $recommendation)
                    <a
                        href="{{ route('checkout.package', $recommendation['package']->uuid) }}"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-3"
                    >
                        <span class="min-w-0">
                            <span class="fw-semibold d-block text-truncate">{{ $recommendation['package']->name }}</span>
                            <span class="small text-muted">{{ $recommendation['option']->name }} · Rp {{ number_format($recommendation['price'], 0, ',', '.') }}</span>
                        </span>
                        <span class="btn btn-sm btn-outline-primary text-nowrap">Pilih paket</span>
                    </a>
                @empty
                    <div class="list-group-item small text-muted">
                        Belum ada paket yang sesuai. Coba naikkan batas anggaran.
                    </div>
                @endforelse
            </div>
    @endif
    </div>
</div>

<script>
    (() => {
        const form = document.getElementById('package-recommendation-form');
        const results = document.getElementById('package-recommendation-results');

        if (!form || !results) {
            return;
        }

        const budgetInput = form.querySelector('[name="budget_max"]');
        const searchButton = form.querySelector('[data-search-button]');
        const digitsOnly = (value) => String(value ?? '').replace(/\D/g, '');
        const formatRupiah = (value) => digitsOnly(value).replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        budgetInput.addEventListener('input', () => {
            const digits = digitsOnly(budgetInput.value);
            budgetInput.value = formatRupiah(digits);
        });

        budgetInput.addEventListener('blur', () => {
            budgetInput.value = formatRupiah(budgetInput.value);
        });

        const setMessage = (message, className) => {
            const element = document.createElement('div');
            element.className = className;
            element.textContent = message;
            results.replaceChildren(element);
        };

        const renderRecommendations = (payload) => {
            const heading = document.createElement('div');
            heading.className = 'small fw-semibold mb-2';
            heading.textContent = `Rekomendasi paket hingga Rp ${payload.formatted_budget}`;

            const list = document.createElement('div');
            list.className = 'list-group';

            if (payload.recommendations.length === 0) {
                const emptyState = document.createElement('div');
                emptyState.className = 'list-group-item small text-muted';
                emptyState.textContent = 'Belum ada paket yang sesuai. Coba naikkan batas anggaran.';
                list.append(emptyState);
            } else {
                payload.recommendations.forEach((recommendation) => {
                    const link = document.createElement('a');
                    link.href = recommendation.checkout_url;
                    link.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-3';

                    const details = document.createElement('span');
                    details.className = 'min-w-0';

                    const name = document.createElement('span');
                    name.className = 'fw-semibold d-block text-truncate';
                    name.textContent = recommendation.name;

                    const option = document.createElement('span');
                    option.className = 'small text-muted';
                    option.textContent = `${recommendation.option} · Rp ${recommendation.formatted_price}`;
                    details.append(name, option);

                    const action = document.createElement('span');
                    action.className = 'btn btn-sm btn-outline-primary text-nowrap';
                    action.textContent = 'Pilih paket';

                    link.append(details, action);
                    list.append(link);
                });
            }

            results.replaceChildren(heading, list);
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const rawBudget = digitsOnly(budgetInput.value);
            if (!rawBudget) {
                setMessage('Masukkan batas anggaran terlebih dahulu.', 'small text-danger');
                budgetInput.focus();
                return;
            }

            if (Number(rawBudget) < 100000) {
                setMessage('Batas anggaran minimal Rp 100.000.', 'small text-danger');
                budgetInput.focus();
                return;
            }

            budgetInput.value = formatRupiah(rawBudget);

            const originalButtonContent = searchButton.innerHTML;
            const endpoint = new URL(form.dataset.recommendationsUrl, window.location.origin);
            endpoint.searchParams.set('budget_max', rawBudget);
            searchButton.disabled = true;
            searchButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Mencari';
            results.setAttribute('aria-busy', 'true');
            results.classList.add('is-updating');
            setMessage('Sedang mencari paket yang sesuai...', 'small text-muted');

            try {
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const payload = await response.json();

                if (!response.ok) {
                    const validationMessage = payload.errors && payload.errors.budget_max
                        ? payload.errors.budget_max[0]
                        : null;
                    throw new Error(validationMessage || payload.message || 'Pencarian paket gagal. Silakan coba kembali.');
                }

                renderRecommendations(payload);
                const pageUrl = new URL(window.location.href);
                pageUrl.searchParams.set('budget_max', rawBudget);
                window.history.replaceState({}, '', pageUrl);
                requestAnimationFrame(() => {
                    results.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            } catch (error) {
                console.error('Gagal mencari rekomendasi paket:', error);
                setMessage(error.message || 'Rekomendasi belum dapat dimuat. Silakan coba kembali.', 'small text-danger');
            } finally {
                searchButton.disabled = false;
                searchButton.innerHTML = originalButtonContent;
                results.classList.remove('is-updating');
                results.setAttribute('aria-busy', 'false');
            }
        });
    })();
</script>
