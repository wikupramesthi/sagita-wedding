<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\Package;
use App\Models\Package\PackagePromo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PackagePromoController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->can('package.promo.index'), 404);

        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');
        $scope = (string) $request->query('scope', '');

        $promos = PackagePromo::query()
            ->with('package:uuid,name')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('code', 'like', '%' . $search . '%')
                        ->orWhereHas('package', fn ($p) => $p->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when(in_array($status, ['active', 'inactive'], true), fn ($q) => $q->where('is_active', $status === 'active'))
            ->when($scope === 'global', fn ($q) => $q->whereNull('package_uuid'))
            ->when($scope === 'package', fn ($q) => $q->whereNotNull('package_uuid'))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $packages = Package::query()->orderBy('name')->get(['uuid', 'name']);

        return view('pages.package-promos.index', compact('promos', 'packages', 'search', 'status', 'scope'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()?->can('package.promo.store'), 404);

        $validated = $this->validated($request);
        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');
        $validated['used_count'] = 0;
        $validated['sort_order'] = (int) (PackagePromo::max('sort_order') ?? 0) + 1;
        $validated['starts_at'] = $this->parseDate($validated['starts_at'] ?? null);
        $validated['ends_at'] = $this->parseDate($validated['ends_at'] ?? null, true);

        PackagePromo::create($validated);

        return back()->with('success', 'Promo berhasil ditambahkan.');
    }

    public function update(Request $request, string $packagePromo)
    {
        abort_unless(auth()->user()?->can('package.promo.update'), 404);

        $promo = PackagePromo::where('uuid', $packagePromo)->firstOrFail();
        $validated = $this->validated($request, $promo->uuid);
        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');
        $validated['starts_at'] = $this->parseDate($validated['starts_at'] ?? null);
        $validated['ends_at'] = $this->parseDate($validated['ends_at'] ?? null, true);

        $promo->update($validated);

        return back()->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(string $packagePromo)
    {
        abort_unless(auth()->user()?->can('package.promo.destroy'), 404);

        $promo = PackagePromo::where('uuid', $packagePromo)->firstOrFail();
        $promo->delete();

        return back()->with('success', 'Promo berhasil dihapus.');
    }

    private function validated(Request $request, ?string $ignoreUuid = null): array
    {
        $validated = $request->validate([
            'package_uuid' => ['nullable', 'uuid', 'exists:packages,uuid'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('package_promos', 'code')->ignore($ignoreUuid, 'uuid'),
            ],
            'type' => ['required', Rule::in(['percent', 'fixed'])],
            'value' => ['required', 'integer', 'min:1'],
            'min_purchase' => ['nullable', 'integer', 'min:0'],
            'max_discount' => ['nullable', 'integer', 'min:1'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ], [], [
            'package_uuid' => 'paket',
            'code' => 'kode promo',
            'type' => 'tipe diskon',
            'value' => 'nilai diskon',
            'min_purchase' => 'minimal pembelian',
            'max_discount' => 'maksimal diskon',
            'max_uses' => 'maksimal pemakaian',
            'starts_at' => 'tanggal mulai',
            'ends_at' => 'tanggal berakhir',
        ]);

        if ($validated['type'] === 'percent' && (int) $validated['value'] > 100) {
            throw ValidationException::withMessages([
                'value' => 'Nilai diskon persen maksimal 100.',
            ]);
        }

        if (($validated['type'] ?? '') === 'fixed') {
            $validated['max_discount'] = null;
        }

        $validated['min_purchase'] = (int) ($validated['min_purchase'] ?? 0);

        return $validated;
    }

    private function parseDate($value, bool $endOfDay = false): ?string
    {
        if (blank($value)) {
            return null;
        }
        $date = Carbon::parse($value);
        return ($endOfDay ? $date->endOfDay() : $date->startOfDay())->format('Y-m-d H:i:s');
    }
}